<?php

class SipPacketController extends AppController
{

    var $name = "SipPacket";
    var $uses = array('Cdr');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function index()
    {
        $voip_monitor_path = '/opt/voip/';
        $order_sql = "order by id desc";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "order by {$field} {$sort}";
            }
        }
        $ani = (isset($_GET['ani']) && !empty($_GET['ani'])) ? $_GET['ani'] : '';
        $dnis = (isset($_GET['dnis']) && !empty($_GET['dnis'])) ? $_GET['dnis'] : '';
        $ingress_ip = (isset($_GET['ingress_ip']) && !empty($_GET['ingress_ip'])) ? $_GET['ingress_ip'] : '';
        $orig_ip = (isset($_GET['orig_ip']) && !empty($_GET['orig_ip'])) ? $_GET['orig_ip'] : '';
        $start_time = (isset($_GET['start_time']) && !empty($_GET['start_time'])) ? $_GET['start_time'] : '';
        $end_time = (isset($_GET['end_time']) && !empty($_GET['end_time'])) ? $_GET['end_time'] : '';
        $search_sql = "";
        if ($ani)
        {
            $search_sql .= " AND origination_destination_number like '%{$ani}%'";
        }
        if ($dnis)
        {
            $search_sql .= " AND origination_source_number like '%{$dnis}%'";
        }
        if ($ingress_ip)
        {
            $search_sql .= " AND origination_source_host_name = '{$ingress_ip}'";
        }
        if ($orig_ip)
        {
            $search_sql .= " AND origination_destination_host_name = '{$orig_ip}'";
        }


        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];

        if (empty($start_time))
            $start_time = date("Y-m-d 00:00:00");

        if (empty($end_time))
            $end_time = date("Y-m-d 23:59:59");

        require_once 'MyPage.php';
        $page = new MyPage ();
        //$totalrecords = $this->Cdr->query($count_sql);
        $page->setTotalRecords(1000); //总记录数
        //$page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;

        if ($_SESSION ['login_type'] == 3)
        {
            $filter_client = '';
        }
        else
        {
            $sst_user_id = $_SESSION['sst_user_id'];
//            $filter_client = "and
//(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists
//(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id}
//and (role_name = 'admin' or sys_role.view_all = true)))";
            $filter_client = $this->get_user_limit_filter();
        }
        // to_timestamp(start_time_of_date/1000000) as start_time, to_timestamp(release_tod/1000000) as end_time,

        $sql = <<<DELIMITER
select id,call_duration,origination_destination_number,origination_source_number,
origination_call_id,termination_destination_number,origination_destination_host_name,time,origination_source_host_name from client_cdr 
where time between '$start_time' and '$end_time'  $filter_client $search_sql $order_sql limit '$pageSize' offset '$offset'
DELIMITER;

        $result = $this->Cdr->query($sql);
        $page->setDataArray($result);
        $this->set('p', $page);
    }

    public function termination()
    {
        $voip_monitor_path = '/opt/voip/';
        $order_sql = "order by id desc";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "order by {$field} {$sort}";
            }
        }
        $ani = (isset($_GET['ani']) && !empty($_GET['ani'])) ? $_GET['ani'] : '';
        $dnis = (isset($_GET['dnis']) && !empty($_GET['dnis'])) ? $_GET['dnis'] : '';
        $ingress_ip = (isset($_GET['ingress_ip']) && !empty($_GET['ingress_ip'])) ? $_GET['ingress_ip'] : '';
        $orig_ip = (isset($_GET['orig_ip']) && !empty($_GET['orig_ip'])) ? $_GET['orig_ip'] : '';
        $term_ip = (isset($_GET['term_ip']) && !empty($_GET['term_ip'])) ? $_GET['term_ip'] : '';
        $term_profile_ip = (isset($_GET['term_profile_ip']) && !empty($_GET['term_profile_ip'])) ? $_GET['term_profile_ip'] : '';
        $start_time = (isset($_GET['start_time']) && !empty($_GET['start_time'])) ? $_GET['start_time'] : '';
        $end_time = (isset($_GET['end_time']) && !empty($_GET['end_time'])) ? $_GET['end_time'] : '';
        $search_sql = "";
        if ($ani)
        {
            $search_sql .= " AND termination_destination_number like '%{$ani}%'";
        }
        if ($dnis)
        {
            $search_sql .= " AND termination_source_number like '%{$dnis}%'";
        }
        if ($ingress_ip)
        {
            $search_sql .= " AND origination_source_host_name = '{$ingress_ip}'";
        }
        if ($orig_ip)
        {
            $search_sql .= " AND origination_destination_host_name = '{$orig_ip}'";
        }
        if ($term_ip)
        {
            $search_sql .= " AND termination_destination_host_name = '{$term_ip}'";
        }
        if ($term_profile_ip)
        {
            $search_sql .= " AND termination_source_host_name = '{$term_profile_ip}'";
        }

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];

        if (empty($start_time))
            $start_time = date("Y-m-d 00:00:00");

        if (empty($end_time))
            $end_time = date("Y-m-d 23:59:59");

        require_once 'MyPage.php';
        $page = new MyPage ();
        //$totalrecords = $this->Cdr->query($count_sql);
        $page->setTotalRecords(1000); //总记录数
        //$page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;

        if ($_SESSION ['login_type'] == 3)
        {
            $filter_client = '';
        }
        else
        {
            $sst_user_id = $_SESSION['sst_user_id'];
//            $filter_client = "and
//(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists
//(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id}
//and (role_name = 'admin' or sys_role.view_all = true)))";
            $filter_client = $this->get_user_limit_filter();
        }
        // to_timestamp(start_time_of_date/1000000) as start_time, to_timestamp(release_tod/1000000) as end_time,

        $sql = <<<DELIMITER
select id,call_duration,termination_destination_number,termination_source_number,
termination_call_id,termination_destination_host_name,termination_source_host_name,origination_destination_host_name,time,origination_source_host_name from client_cdr 
where time between '$start_time' and '$end_time'  $filter_client $search_sql $order_sql limit '$pageSize' offset '$offset'
DELIMITER;

        $result = $this->Cdr->query($sql);
        $page->setDataArray($result);
        $this->set('p', $page);
    }

    public function download_sip_cap($id, $time)
    {

        Configure::write('debug', 0);
        //$voip_monitor_path = '/opt/voip/';
        $voip_monitor_path = Configure::read("voipmonitor.path");
        $this->autoRender = false;
        $this->autoLayout = false;
        $time = base64_decode($time);
        $sql = "select start_time_of_date as start_time, release_tod as end_time, origination_call_id from client_cdr".date("Ymd")." where time='{$time}' and id = {$id}";
        $result = $this->Cdr->query($sql);
        $start_time = (int) substr($result[0][0]['start_time'], 0, 10);
        $call_id = $result[0][0]['origination_call_id'];

        $minuses = array_merge(range(0, -5, -1), range(1, 5));

        $found = null;

        foreach ($minuses as $minus)
        {
            $current_time = $start_time + ($minus * 60);
            $current_date_time = date("Y-m-d/H/i", $current_time);

            $pattern = $voip_monitor_path . $current_date_time . '/SIP/' . $call_id . '*.pcap';

            $found = glob($pattern);
            if ($found)
                break;
        }

        if (!$found)
        {
            $this->Session->write('m', $this->Cdr->create_json(101, __('The pcap file is not found!', true)));
            $this->redirect('/sip_packet');
        }
        else
        {
            $file_path = $found[0];
            $filename = basename($file_path);

            header("Content-type: application/octet-stream");

            //处理中文文件名
            $ua = $_SERVER["HTTP_USER_AGENT"];
            $encoded_filename = rawurlencode($filename);
            if (preg_match("/MSIE/", $ua))
            {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            }
            else if (preg_match("/Firefox/", $ua))
            {
                header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
            }
            else
            {
                header('Content-Disposition: attachment; filename="' . $filename . '"');
            }

            @readfile($file_path);
        }
    }

}
