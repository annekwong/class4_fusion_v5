<?php

class RatefindersController extends AppController
{

    var $name = "Ratefinders";
    var $helpers = array('Javascript', 'Html', 'Text');
    var $components = array('RequestHandler');
    var $uses = array('Clients');

    public function index()
    {
        $post_data = array(
            'by_type' => 1,
            'code_search' => '',
            'code_deck' => '',
            'code_name' => '',
            'from_type' => 1,
            'ingress_trunk' => array(),
            'egress_trunk' => array(),
            'route_plan' => ''
        );
        $code_arr = array();
        $header_title = "Egress Trunk";
        $result = "";
        $search_flg = 0;
        if ($this->RequestHandler->isPost())
        {
            $post_data = array_merge($post_data, $this->params['form']);
            if ($post_data['code_name'])
            {
                $code_sql = "SELECT code_id,name FROM code WHERE code_deck_id = {$post_data['code_deck']}";
                $code_result = $this->Clients->query($code_sql);
                foreach ($code_result as $items)
                {
                    $code_arr[$items[0]['code_id']] = $items[0]['name'];
                }
            }
            if ($post_data['by_type'] == 1)
            {
                $where_sql = "";
                if ($post_data['code_search'])
                {
                    $where_sql = "AND  code::prefix_range <@  '{$post_data['code_search']}'";
                }
            }
            else
            {
                $where_sql = " AND code_name = '{$post_data['code_name']}' ";
            }
            switch ($post_data['from_type'])
            {
                case 1:
                    $header_title = "Ingress Trunk";
                    $ingress_trunk_str = implode(',', $post_data['ingress_trunk']);
                    $result_sql = "SELECT rate.code,rate.rate,rate.effective_date,resource.alias"
                            . " FROM resource_prefix as prefix LEFT JOIN rate ON prefix.rate_table_id = rate.rate_table_id"
                            . " LEFT JOIN resource ON prefix.resource_id = resource.resource_id WHERE resource.resource_id in"
                            . " ($ingress_trunk_str) $where_sql";
                    $result = $this->Clients->query($result_sql);
                    break;
                case 2:
                    $egress_trunk_str = implode(',', $post_data['egress_trunk']);
                    $result_sql = "SELECT rate.code,rate.rate,rate.effective_date,resource.alias"
                            . " FROM resource LEFT JOIN rate ON resource.rate_table_id = rate.rate_table_id"
                            . " WHERE resource.resource_id in ($egress_trunk_str) $where_sql";
                    $result = $this->Clients->query($result_sql);
                    break;
                case 3:
                    $dynamic_route_resource_sql = "select distinct(resource_id) from dynamic_route_items where dynamic_route_id in "
                            . "(select distinct(dynamic_route_id) from route where route_strategy_id = {$post_data['route_plan']} and dynamic_route_id is not null)";
                    $dynamic_route_resource = $this->Clients->query($dynamic_route_resource_sql);
                    $static_route_resource_sql = "select distinct(resource_id) from product_items_resource "
                            . "where item_id in (select item_id from product_items where product_id in "
                            . "(select distinct(static_route_id) from route where route_strategy_id = {$post_data['route_plan']} "
                            . "and static_route_id is not null))";
                    $static_route_resource = $this->Clients->query($static_route_resource_sql);
                    $prefix_sql = "SELECT digits FROM route WHERE route_strategy_id = {$post_data['route_plan']} AND digits is not null";
                    $prefix_result = $this->Clients->query($prefix_sql);
//                    echo $prefix_sql;
//                    pr($prefix_result);die;
                    $prefix_arr = array();
                    foreach ($prefix_result as $items)
                    {
                        if ($items[0]['digits'])
                        {
                            $prefix_arr[] = "code::prefix_range <@ '{$items[0]['digits']}'";
                        }
                    }
                    $prefix_where = "";
                    if($prefix_arr)
                    {
                        $prefix_where = "AND (".implode(' or ', $prefix_arr).")";
                    }
                    foreach ($dynamic_route_resource as $items)
                    {
                        $resource_arr[] = $items[0]['resource_id'];
                    }
                    foreach ($static_route_resource as $items)
                    {
                        $resource_arr[] = $items[0]['resource_id'];
                    }
                    $resource_arr = array_unique($resource_arr);
                    $egress_trunk_str = implode(',', $resource_arr);
                    $result_sql = "SELECT rate.code,rate.rate,rate.effective_date,resource.alias"
                            . " FROM resource LEFT JOIN rate ON resource.rate_table_id = rate.rate_table_id"
                            . " WHERE resource.resource_id in ($egress_trunk_str) $where_sql $prefix_where";
                    $result = $this->Clients->query($result_sql);
                    break;
            }
            $search_flg = 1;
        }
        $this->set('search_flg', $search_flg);
        $this->set('header_title', $header_title);
        $this->set('data', $result);
        $this->set('code_arr', $code_arr);
        $this->set('post_data', $post_data);
        $code_deck_sql = "SELECT code_deck_id,name FROM code_deck";
        $code_deck_result = $this->Clients->query($code_deck_sql);
        $this->set('code_deck', $code_deck_result);
        $egress_trunk = $this->Clients->findAll_egress();
        $this->set('egress_trunk', $egress_trunk);
        $ingress_trunk = $this->Clients->findAll_ingress();
        $this->set('ingress_trunk', $ingress_trunk);
        $route_plan = $this->Clients->find_routepolicy();
        $this->set('route_plan', $route_plan);
    }

    public function _ready($filename, $filelines, $maxfields, $sorted_type)
    {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        //$startline = ($currPage - 1) * $pageSize;
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($filelines); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $result = $this->_line_content($filename, $currPage, $pageSize, $filelines);
        $page->setDataArray($result);
        $this->set('p', $page);
        $this->set('maxfields', $maxfields);
        $this->set('sorted_type', $sorted_type);
    }

    public function _line_content($filename, $startline, $endline, $filelines)
    {
        $arr = array();
        $fp = fopen($filename, "r");
        for ($i = 0; $i <= $startline - 1; $i++)
        {
            fgets($fp);
        }
        //for ($i = $startline; $i <= $endline; $i++) {
        for ($i = 1; $i <= $endline; $i++)
        {
            array_push($arr, fgetcsv($fp));
            if ($filelines == $i)
                break;
        }
        return $arr;
    }

    public function _socket($cmd)
    {
        $content = "";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port")))
        {
            socket_write($socket, $cmd, strlen($cmd));
            while ($out = socket_read($socket, 2048))
            {
                $content .= $out;
                if (strpos($out, "~!@#$%^&*()") !== FALSE)
                {
                    break;
                }
                unset($out);
            }
            $content = strstr($content, "~!@#$%^&*()", TRUE);
        }
        socket_close($socket);
        return $content;
    }

    public function down()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=rate_finder.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        $fileinfo = $_COOKIE['ratefinder'];
        $fileinfo_arr = explode(",", $fileinfo);
        $filename = $fileinfo_arr[0];
        $filelines = $fileinfo_arr[1];
        $maxfields = $fileinfo_arr[2];
        $sorted_type = $fileinfo_arr[3];
        echo 'Code Name';
        if ($sorted_type == 'false')
        {
            echo ',Code';
        }
        echo ',Min,Max,Avg,';
        for ($i = 1; $i <= $maxfields; $i++)
        {
            echo 'Trunk-' . $i . ',';
        }
        echo "\n";
        echo file_get_contents($filename);
    }

    public function ajax_get_code_name()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($this->RequestHandler->isPost())
        {
            $code_deck_id = $this->params['form']['code_deck_id'];
            $code_sql = "SELECT code_id,name FROM code WHERE code_deck_id = $code_deck_id";
            $code_result = $this->Clients->query($code_sql);
            foreach ($code_result as $items)
            {
                $code_arr[$items[0]['code_id']] = $items[0]['name'];
            }
            echo json_encode($code_arr);
        }
        else
        {
            echo 0;
        }
    }

}

?>
