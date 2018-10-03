<?php

class LogsController extends AppController
{

    var $name = 'Logs';
    var $components = array('RequestHandler');
    var $helpers = array('common');
    var $uses = array('Log', 'KillPgSqlLog', 'RateBotImportLogs');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    public function api_log()
    {
        $this->loadModel('ApiLog');

        $this->data = $this->ApiLog->find('all', array('order' => 'id DESC', 'limit' => 100));
    }

    function index()
    {
        $this->pageTitle = "Management/Search Logs";

        $currPage = 1;
        $pageSize = 100;
        $search_arr = array();
        $order_arr = array();


        if (!empty($_REQUEST['search']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
        }
        else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['type'] = !empty($_REQUEST['type']) ? ($_REQUEST['type']) : '';
            $search_arr['search_val'] = !empty($_REQUEST['search_val']) ? ($_REQUEST['search_val']) : '';
            $search_arr['start_date'] = !empty($_REQUEST['start_date']) ? ($_REQUEST['start_date']) : '';
            $search_arr['end_date'] = !empty($_REQUEST['end_date']) ? ($_REQUEST['end_date']) : '';
            $search_arr['name'] = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

        if (!empty($_REQUEST ['page']))
        {
            $currPage = $_REQUEST ['page'];
        }

        $pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 15;

        if (!empty($_REQUEST ['size']))
        {
            $pageSize = $_REQUEST ['size'];
        }


        $results = $this->Log->ListLog($currPage, $pageSize, $search_arr, $search_type);
        $this->set('p', $results);
    }

    /**
     * 
     * 显示当前执行的SQL 
     */
    public function sql_log()
    {

        $this->pageTitle = "Log/Sql Logs";

        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $database = $sections['db']['dbname'];

        $order_sql = "ORDER BY query_start DESC";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $get_data = $this->params['url'];

        $this->set('get_data', $get_data);

        $where = "";

        if (isset($get_data['time_start']) && $get_data['time_start'])
        {
            $where .= " AND query_start >= '" . $get_data['time_start'] . "'";
        }

        if (isset($get_data['time_end']) && $get_data['time_end'])
        {
            $where .= " AND query_start <= '" . $get_data['time_end'] . "'";
        }

        $sql = "select * from pg_stat_activity where datname = '{$database}' "
                . "and query is not null {$where} {$order_sql}";
        $sql_data = $this->Log->query($sql);
        foreach ($sql_data as $key => $items)
        {
            $html_query = htmlentities($items[0]['query']);
            $trim = trim($html_query);
            if (!strcmp('&lt;IDLE&gt;', $trim))
            {
                unset($sql_data[$key]);
            }
        }
        $this->set('sql_data', $sql_data);
    }

    public function kill_job($id)
    {
        $id = base64_decode($id);
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $database = $sections['db']['dbname'];
        $sql = "select query,query_start, pg_terminate_backend({$id}) from pg_stat_activity where pid <> pg_backend_pid() AND datname = '{$database}' AND pid = $id";
        $kill_data = $this->Log->query($sql);

        if ($kill_data === false || $kill_data[0][0]['pg_terminate_backend'] == false)
        {
            $this->Session->write('m', $this->Log->create_json(101, 'The Job is killed failed!'));
        }
        else
        {
            $flg = false;
            if (isset($kill_data[0][0]['query']))
            {
                Configure::write('debug', 2);
                $query = $kill_data[0][0]['query'];
                $query_value = "$$$query$$";
                $insert_sql = "INSERT INTO kill_pg_sql_log (query,start_time) VALUES ($query_value,'{$kill_data[0][0]['query_start']}')";
                $flg = $this->Log->query($insert_sql);
            }

            if ($flg === false)
            {
                $this->Session->write('m', $this->Log->create_json(201, 'The Job is killed successfully! Kill log failed'));
            }
            else
            {
                $this->Session->write('m', $this->Log->create_json(201, 'The Job is killed successfully!'));
            }
        }
        $this->redirect('/logs/sql_log');
    }

    public function kill_log()
    {
        $this->pageTitle = "PGSQL kill Log";

        $get_data = $this->params['url'];
        $conditions = array();

        $now = date("Y-m-d H:i:s", time());
        if (!$this->isnotEmpty($get_data, "k_start_datetime"))
        {
            $get_data['k_start_datetime'] = date("Y-m-d", strtotime("-1 month")) . " 00:00:00";
        }
        if (!$this->isnotEmpty($get_data, "k_end_datetime"))
        {
            $get_data['k_end_datetime'] = $now;
        }
        $conditions[] = "start_time between '" . $get_data['k_start_datetime'] . "' AND '{$get_data['k_end_datetime']}'";
        if ($this->isnotEmpty($get_data, "start_datetime"))
        {
            $conditions[] = "start_time >= '{$get_data['start_datetime']}'";
        }
        if ($this->isnotEmpty($get_data, "end_datetime"))
        {
            $conditions[] = "start_time <= '{$get_data['end_datetime']}'";
        }
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
            //'id' => 'desc',
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('KillPgSqlLog');
        $this->set('get_data', $get_data);
    }

    public function authorization_log()
    {

        $this->pageTitle = "Log/Authorization Logs";

        $order_sql = "ORDER BY id DESC";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $error_type = array(
            0 => 'normal',
            1 => 'auth params incomplete',
            2 => 'user nothingness',
            3 => 'wrong password',
            4 => 'wrong username',
        );

        $this->set('error_type', $error_type);
        $get_data = $this->params['url'];

        $this->set('get_data', $get_data);

        $start_date = isset($get_data['start_date']) ? $get_data['start_date'] : date("Y-m-d");
        $end_date = isset($get_data['stop_date']) ? $get_data['stop_date'] : date("Y-m-d");

        $start_time = isset($get_data['start_time']) ? $get_data['start_time'] : "00:00:00";
        $end_time = isset($get_data['stop_time']) ? $get_data['stop_time'] : "23:59:59";

        $tz = isset($get_data['gmt']) ? $get_data['gmt'] : "+0000";

        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('tz', $tz);

        $start_datetime = strtotime($start_date . ' ' . $start_time . $tz);
        $end_datetime = strtotime($end_date . ' ' . $end_time . $tz);

        $where = "WHERE time BETWEEN {$start_datetime} AND {$end_datetime}";

        if (!empty($get_data['search']))
        {
            $where .= " AND (request_port =" . intval($get_data['search'])
                    . " OR username = '{$get_data['search']}' OR authname = '{$get_data['search']}' OR sip_callid LIKE '%{$get_data['search']}%'" . ")";
        }

        if (isset($get_data['direction']) && $get_data['direction'] != 'all')
        {
            $where .= " AND direction = " . intval($get_data['direction']);
        }
        if (isset($get_data['auth_type']) && $get_data['auth_type'] != 'all')
        {
            $where .= " AND auth_type = " . intval($get_data['auth_type']);
        }

        if (isset($get_data['error_type']) && $get_data['error_type'] != 'all')
        {
            $where .= " AND error_type = " . intval($get_data['error_type']);
        }

        $sql = "SELECT * FROM authorization_logs {$where} {$order_sql}";

        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = $this->Log->query("SELECT count(*) as sum FROM authorization_logs {$where}");
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page')))
        {
            $currPage = $this->params['url']['page'];
        }
        $pageSize = 20;
        $page->setTotalRecords($totalrecords[0][0]['sum']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql .= " limit '$pageSize' offset '$offset'";
        $data_arr = $this->Log->query($sql);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

    public function rate_bot_import_logs ()
    {
        $this->pageTitle = "Rate Bot Import Log";
        $this->paginate['order'] = 'id DESC';

        $statuses = array(
            '0' => 'New email is received',
            '1' => 'Process',
            '2' => 'Done',
            '3' => 'Error',
            '4' => 'Error'
        );

        $this->set('statuses', $statuses);
        $this->set('logs', $this->paginate('RateBotImportLogs'));
    }

}

?>
