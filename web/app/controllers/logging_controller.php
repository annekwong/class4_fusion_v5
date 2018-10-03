<?php

class LoggingController extends AppController
{

    var $name = "Logging";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('Logging');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
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

    /**
     * 
     * @param type $log_id   logid   有则需要弹窗提示输入备注
     * @param type $path      备注后返回的地址
     */
    public function index($log_id = null, $path = null)
    {
        $this->pageTitle = "Log/Modification Log";
        if ($log_id && $path)
        {
            $this->set('log_id', $log_id);
            $this->set('path', $path);
        }
        else
        {
            $this->set('log_id', '');
            $this->set('path', '');
        }
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-d");
        $end_date = isset($_GET['stop_date']) ? $_GET['stop_date'] : date("Y-m-d");

        $start_time = isset($_GET['start_time']) ? $_GET['start_time'] : "00:00:00";
        $end_time = isset($_GET['stop_time']) ? $_GET['stop_time'] : "23:59:59";

        $tz = isset($_GET['gmt']) ? $_GET['gmt'] : "+0000";
        $page = $this->_get('size',100);

        $start_datetime = $start_date . ' ' . $start_time . $tz;
        $end_datetime = $end_date . ' ' . $end_time . $tz;

        $order_arr = array('Logging.time' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $this->paginate = array(
            'fields' => 'DISTINCT time, module, type, name, detail,rollback_flg,id,rollback',
            'limit' => $page,
            'order' => $order_arr,
            'conditions' => array(
                "Logging.time BETWEEN '{$start_datetime}' AND '{$end_datetime}'",
            ),
        );

        if (isset($_GET['operator']))
        {
            array_push($this->paginate['conditions'], "Logging.name ilike '%{$_GET['operator']}%'");
        }

        if (isset($_GET['target']))
        {
            array_push($this->paginate['conditions'], "Logging.detail ilike '%{$_GET['target']}%'");
        }

        if (isset($_GET['action']) && $_GET['action'] != 'all')
        {
            array_push($this->paginate['conditions'], "Logging.type = {$_GET['action']}");
        }

        $actions = array(
            '0' => 'Creation',
            '1' => 'Deletion',
            '2' => 'Modification',
            '3' => 'Trunk enabled',
            '4' => 'Trunk disabled',
            '5' => 'Carrer enabled',
            '6' => 'Carrer disabled',
        );
        $this->data = $this->paginate('Logging');
        $this->set('actions', $actions);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('tz', $tz);
        $this->set('all_operator', $this->Logging->find_all_operator());
    }

    public function update_log_current()
    {
        $this->pageTitle = "Log/Current Update Log";

        $file_path = Configure::read('update_log.current');
        $data = file_get_contents($file_path);
        $data = str_replace("\n", "<br />", $data);
        $data = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data);
        $this->set('data', $data);
    }

    public function update_log_history()
    {
        $this->pageTitle = "Log/History Update Log";

        $file_path = Configure::read('update_log.history');

        $data = file_get_contents($file_path);
        $data = str_replace("\n", "<br />", $data);
        $data = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data);
        $this->set('data', $data);
    }

    public function show_notes($log_id, $path)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->set('log_id', $log_id);
        $this->set('path', $path);
    }

    public function add_notes()
    {
        //       Configure::write('debug', 0);
//        $this->autoRender = false;
//        $this->autoLayout = false;
        if ($this->RequestHandler->isPost())
        {
            $post_arr = $this->params['form'];
            $key_arr = array('log_id', 'path');
            if (!$this->isnotEmpty($post_arr, $key_arr))
            {
                $this->Session->write('m', $this->Logging->create_json(101, 'Failed!'));
                $this->redirect('index');
            }
            $post_arr['content'] = trim($post_arr['content']);
            if ($this->isnotEmpty($post_arr, array('content')))
            {
                $sql = "update modif_log set detail = detail || ' Notes: {$post_arr['content']}' where id = '{$post_arr['log_id']}' returning id";

                $return = $this->Logging->query($sql);
                if ($return[0][0]['id'])
                {
                    $this->Session->write('m', $this->Logging->create_json(201, 'successfully!'));
                    $path = preg_replace('/-/', '/', $post_arr['path']);
                    $this->xredirect("/" . $path);
                }
                else
                {
                    $this->Session->write('m', $this->Logging->create_json(101, 'Failed!'));
                    $this->redirect('index');
                }
            }
            else
            {
                $path = preg_replace('/-/', '/', $post_arr['path']);
                $this->xredirect("/" . $path);
            }
        }
        $this->redirect('index');
    }

    //rollback
    public function rollback_data($log_id)
    {
        // Custom error handler
        set_error_handler(array($this, 'handleError'));

        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$log_id)
        {
            $this->Session->write('m', $this->Logging->create_json(101, 'Failed!'));
            $this->redirect('index');
        }
        $log_id = base64_decode($log_id);
        $log_info = $this->Logging->findById($log_id);
        if (!$log_info)
        {
            $this->Session->write('m', $this->Logging->create_json(101, 'Failed!'));
            $this->redirect('index');
        }
        if (!$log_info['Logging']['rollback'] || $log_info['Logging']['rollback_flg'])
        {
            $this->Session->write('m', $this->Logging->create_json(101, 'Failed!'));
            $this->redirect('index');
        }
        $rollback_msg = $log_info['Logging']['rollback_msg'];

        try {
            $flg = $this->_rollback_detail($log_info);

            if ($flg)
            {
                $rollback_flg = 1;
                $this->Session->write('m', $this->Logging->create_json(201, $rollback_msg));
            }
            else
            {
                $rollback_flg = 2;
                $this->Session->write('m', $this->Logging->create_json(101, "Rollback is failed!"));
            }
            $this->Logging->query("UPDATE modif_log SET rollback_flg = $rollback_flg WHERE id = {$log_id}");
        } catch (Exception $e) {
            $rollback_flg = 2;
            $error = $e->getMessage();

            $this->Session->write('m', $this->Logging->create_json(101, "Rollback is failed!"));
            $this->Logging->query("UPDATE modif_log SET rollback_flg = $rollback_flg, rollback_msg = '{$error}' WHERE id = {$log_id}");
        }

        $this->redirect('/logging/index');
    }

    public function _rollback_detail($log_info)
    {
        Configure::write('debug', 2);
        $result = null;

        $rollback_sql = $log_info['Logging']['rollback'];
        $rollback_extra_info = $log_info['Logging']['rollback_extra_info'];
        $rollback_extra_arr = json_decode($rollback_extra_info, true);
        $rollback_type = isset($rollback_extra_arr['type']) ? $rollback_extra_arr['type'] : 1;
        switch ($rollback_type) {
            case 1:
                $del = '::';
                if (strpos($rollback_sql, $del) !== false) {
                    $rollback_sql_arr = explode($del, $rollback_sql);
                    if (!empty($rollback_sql_arr)) {
                        foreach ($rollback_sql_arr as $rollback_sql) {
                            if (!empty(trim($rollback_sql))) {
                                $this->Logging->query($rollback_sql);
                            }
                        }
                    }
                } else {
                    $this->Logging->query($rollback_sql);
                }
                $result = $this->Logging->getAffectedRows();
                break;
            case 2:
                $rollback_sql_arr = explode(";", $rollback_sql);
                $return_data = $this->Logging->query($rollback_sql_arr[0]);
                if (isset($rollback_sql_arr[1]) && !empty($rollback_sql_arr[1])) {
                    $insert_id = $return_data[0][0]['translation_id'];
                    $other_sql = str_replace("{translation_id}", $insert_id, $rollback_sql_arr[1]);

                    $this->Logging->query($other_sql);

                    $result = $this->Logging->getAffectedRows();
                } else {
                    $result = $return_data;
                }
                break;
            case 3:
                $rollback_sql_arr = explode("&&", $rollback_sql);
                $return_data = $this->Logging->query($rollback_sql_arr[0]);
                if (isset($rollback_sql_arr[1]) && !empty($rollback_sql_arr[1])) {
                    $insert_id = $return_data[0][0]['product_id'];
                    $other_sql = str_replace("{product_id}", $insert_id, $rollback_sql_arr[1]);

                    $this->Logging->query($other_sql);

                    $result = $this->Logging->getAffectedRows();
                } else {
                    $result = $return_data;
                }
                break;
            case 4:
                $rollback_sql_arr = explode("&&", $rollback_sql);
                $return_data = $this->Logging->query($rollback_sql_arr[0]);
                if (isset($rollback_sql_arr[1]) && !empty($rollback_sql_arr[1])) {
                    $insert_id = $return_data[0][0]['rate_table_id'];
                    $other_sql = str_replace("{rate_table_id}", $insert_id, $rollback_sql_arr[1]);

                    $this->Logging->query($other_sql);

                    $result = $this->Logging->getAffectedRows();
                } else {
                    $result = $return_data;
                }
                break;
            case 5:
                $rollback_sql_arr = explode("&&", $rollback_sql);
                $return_data = $this->Logging->query($rollback_sql_arr[0]);
                if (isset($rollback_sql_arr[1]) && !empty($rollback_sql_arr[1])) {
                    $insert_id = $return_data[0][0]['role_id'];
                    $other_sql = str_replace("{role_id}", $insert_id, $rollback_sql_arr[1]);

                    $this->Logging->query($other_sql);
                    
                    $result = $this->Logging->getAffectedRows();
                } else {
                    $result = $return_data;
                }
                break;
        }

        return $result;
    }

    public function license_modification_log()
    {
        $this->pageTitle = 'Log/License Modification Log';

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
        $get_data = $this->params['url'];

        $this->set('get_data', $get_data);

        $where_sql = " WHERE 1=1 ";

        //pr($get_data);die;
        //var_dump(!strcmp($get_data['status'], '0'));

        if (isset($get_data['status']) && !empty($get_data['status']))
        {
            $where_sql .= " AND type = {$get_data['status']}";
        }
//        pr($get_data);

        if (isset($get_data['time_start']) && $get_data['time_start'])
        {
            $where_sql .= " AND modify_on >= '" . $get_data['time_start'] . "'";
        }

        if (isset($get_data['time_end']) && $get_data['time_end'])
        {
            $where_sql .= " AND modify_on <= '" . $get_data['time_end'] . "'";
        }


        $sql = "select count(*) from license_modification_log {$where_sql}";
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->Logging->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT * from license_modification_log {$where_sql} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";

        $data = $this->Logging->query($sql);
        $page->setDataArray($data);

        $this->set('p', $page);
        $type_arr = array(
            '1' => 'Self-Defined Cap Limit',
            '2' => 'Self-Defined CPS Limit',
            '3' => 'Initialize(Cap Limit)',
            '4' => 'Initialize(CPS Limit)',
        );
        $this->set('type_arr', $type_arr);
    }

    public function us_jurisdiction_update_log()
    {
        $this->pageTitle = "US Jurisdiction Update Log";
        $this->loadModel('JurisdictionUpdateLog');
        $conditions = array();
        $order_arr = array('JurisdictionUpdateLog.id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr_orig = explode('-', $order_by);
            if (count($order_arr_orig) == 2)
            {
                $field = $order_arr_orig[0];
                $sort = $order_arr_orig[1];
                $order_arr = array($field => $sort);
            }
        }
        if ($this->isnotEmpty($this->params['url'], array('time_start')))
            $conditions[] = "JurisdictionUpdateLog.tigger_time > '{$this->params['url']['time_start']}'";
        if ($this->isnotEmpty($this->params['url'], array('time_start')))
            $conditions[] = "JurisdictionUpdateLog.tigger_time < '{$this->params['url']['time_end']}'";
        
        $this->paginate = array(
            'fields' => array('JurisdictionUpdateLog.tigger_time', 'JurisdictionUpdateLog.is_new_file', 'JurisdictionUpdateLog.status',
                'ImportExportLogs.time', 'ImportExportLogs.finished_time', 'ImportExportLogs.success_numbers', 'JurisdictionUpdateLog.import_log_id'
            ),
            'limit' => 100,
            'order' => $order_arr,
            'joins' => array(
                array(
                    'table' => 'import_export_logs',
                    'alias' => 'ImportExportLogs',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ImportExportLogs.id = JurisdictionUpdateLog.import_log_id'
                    ),
                )
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('JurisdictionUpdateLog');
        $status = array(
            '' => "--",
            1 => 'Succeed',
            -1 => 'FTP Connect Failed',
            -2  =>  'FTP Login Failed',
            -3  =>  'FTP Insufficient permissions',
            -4  =>  'ftp File is not found',
            -5  =>  'Import Failed',
            -6  =>  'Import Log Insert Failed'
        );
        $this->set('status', $status);
        $this->set('get_data',  $this->params['url']);
    }

}

?>
