<?php

class UsDomesticTrafficController extends AppController
{

    var $name = 'UsDomesticTraffic';
    var $uses = Array('Cdrs', 'Cdr','Rate','UsExportLog', 'Systemparam');
    var $helpers = array('javascript', 'html', 'common');
    var $components = array('RequestHandler');

    public function index()
    {
        $this->redirect('return_code_report');
    }

    public function init_query()
    {
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('routing_plans', $routing_plans);
        $us_rate_tables = $this->Rate->get_us_rate_table();
        $this->set('us_rate_tables', $us_rate_tables);
    }

    public function get_date_time()
    {
        $get_data = $this->params['url'];
        $start_date = $get_data['start_date'];
        $start_time = $get_data['start_time'];
        $stop_date = $get_data['stop_date'];
        $stop_time = $get_data['stop_time'];
        $tz = $get_data['query']['tz'];
        $return_arr = array(
            'start_datetime' => $start_date." ".$start_time.$tz,
            'stop_datetime' => $stop_date." ".$stop_time.$tz,
        );
        $this->set('start_date',$return_arr['start_datetime']);
        $this->set('end_date',$return_arr['stop_datetime']);
        return $return_arr;
    }

    public function lcr_report()
    {
        $this->init_query();
        $active = "lr";
        $this->set('active', $active);
        $this->get_us_data($active);
    }

    public function lcr_vendor_report()
    {
        $this->init_query();
        $active = "lvr";
        $this->set('active', $active);
        $this->get_us_data($active);
    }

    public function termination_vendor_report()
    {
        $this->init_query();
        $active = "tvr";
        $this->set('active', $active);
        $this->get_us_data($active);
        $this->set('has_trunk',true);
        $this->set('egress',$this->UsExportLog->findAll_egress_id());
    }

    public function frequent_ani_report()
    {
        $this->init_query();
        $active = "far";
        $this->set('active', $active);
        $this->get_us_data($active);
        $this->set('ingress',$this->UsExportLog->findAll_ingress_id());
    }

    public function frequent_lrn_report()
    {
        $this->init_query();
        $active = "flr";
        $this->set('active', $active);
        $this->get_us_data($active);
        $this->set('ingress',$this->UsExportLog->findAll_ingress_id());
    }

    public function add_export_log($str_type)
    {
        $function_name = $this->str_to_function($str_type);
        if($this->RequestHandler->isGet())
        {
            $type = $this->str_to_type($str_type);
            if($type === false)
            {
                $this->Session->write('m', $this->UsExportLog->create_json(101, __('Failed',true)));
                $this->redirect($function_name);
            }
            $get_data = $this->params['url'];
            $date_arr = $this->get_date_time();
            $file_name = strtoupper($function_name) . time() . '.csv';
            if ($type == 4)
                $file_name = strtoupper($function_name) . time() . '.xls';
            $save_log_data = array(
                'report_type' => $type,
                'route_plan_id' => $get_data['routing_plan'],
                'rate_table_id' => $get_data['us_rate_table'],
                'start_time' => $date_arr['start_datetime'],
                'end_time' => $date_arr['stop_datetime'],
                'status' => 0,
                'create_time' => date('Y-m-d H:i:sO'),
                'file_name' => $file_name,
                'bill_method' => $get_data['bill_method'],
            );
            if(isset($get_data['trunk_id']))
                $save_log_data['trunk_id'] = $get_data['trunk_id'];
            $flg = $this->UsExportLog->save($save_log_data);
            if($flg === false)
            {
                $this->Session->write('m', $this->UsExportLog->create_json(101, __('Save Failed',true)));
                $this->redirect($function_name);
            }
            $log_id = $this->UsExportLog->getLastInsertId();
            $php_path = Configure::read('php_exe_path');
            $cmd = "{$php_path} " . APP . "../cake/console/cake.php us_report_export $type {$log_id} /dev/null & echo $!";
            $info = $this->Systemparam->find('first',array(
                'fields' => array('cmd_debug'),
            ));
            if(Configure::read('cmd.debug'))
            {
                file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
            }
            $pid = shell_exec($cmd);
            $pid_arr = explode("\n",$pid);
            $pid = $pid_arr[0];
//            $this->UsExportLog->query("UPDATE us_export_log SET pid = $pid WHERE id = $log_id");
            $this->Session->write('m', $this->UsExportLog->create_json(201, sprintf(__('You Job [#%s] is scheduled to execute in the queue.',true),$pid)));
            $this->redirect($function_name);
        }
        else
        {
            $this->Session->write('m', $this->UsExportLog->create_json(101, __('Failed',true)));
            $this->redirect($function_name);
        }


    }
    public function get_us_data($str_type)
    {
        $conditions = array();
        $type = $this->str_to_type($str_type);
        if($type === false)
        {
            $type = 1;
        }
        $conditions['report_type'] = $type;
        if(isset($this->params['url']['time']) && $this->params['url']['time'])
            $conditions['UsExportLog.create_time >= ?'] = $this->params['url']['time'];
        if(isset($this->params['url']['end_time']) && $this->params['url']['end_time'])
            $conditions['UsExportLog.create_time <= ?'] = $this->params['url']['end_time'];
        $fields_arr = array(
            'UsExportLog.id','UsExportLog.report_type','UsExportLog.start_time','UsExportLog.end_time','UsExportLog.status',
            'UsExportLog.num_of_row','UsExportLog.create_time','UsExportLog.file_name','RateTable.name','RoutePlan.name',
            'UsExportLog.pid'
        );
        $joins_arr = array(
            array(
                'table' => 'rate_table',
                'alias' => 'RateTable',
                'type' => 'left',
                'conditions' => array(
                    'RateTable.rate_table_id = UsExportLog.rate_table_id'
                ),
            ),
            array(
                'table' => 'route_strategy',
                'alias' => 'RoutePlan',
                'type' => 'left',
                'conditions' => array(
                    'RoutePlan.route_strategy_id = UsExportLog.route_plan_id'
                ),
            ),
        );
        if(in_array($type,array(4,5,6)))
        {
            array_push($fields_arr,'Resource.alias');
            array_push($joins_arr,array(
                'table'=>'resource',
                'alias' =>'Resource',
                'type' => 'left',
                'conditions' => array(
                    'UsExportLog.trunk_id = Resource.resource_id'
                )
            ));
        }

        $this->paginate = array(
            'fields' => $fields_arr,
            'limit' => 100,
            'order' => array(
                'UsExportLog.id' => 'desc',
            ),
            'conditions' => $conditions,
            'joins' => $joins_arr,
        );

        $this->set('get_data', $this->params['url']);

        $status = array("Waiting", "In Progress", "Query", "Compress", "Done", "Canceled",-1 => 'Error');
        $this->set('status', $status);
        $this->data = $this->paginate('UsExportLog');

    }




    public function str_to_type($str_type)
    {
        switch ($str_type){
            case 'rcr':
                $type = 1;
                break;
            case 'lr':
                $type = 2;
                break;
            case 'lvr':
                $type = 3;
                break;
            case 'tvr':
                $type = 4;
                break;
            case 'far':
                $type = 5;
                break;
            case 'flr':
                $type = 6;
                break;
            default : $type = false;
        }
        return $type;
    }

    public function str_to_function($str_type)
    {
        switch ($str_type){
            case 'rcr':
                $function = "return_code_report";
                break;
            case 'lr':
                $function = 'lcr_report';
                break;
            case 'lvr':
                $function = 'lcr_vendor_report';
                break;
            case 'tvr':
                $function = 'termination_vendor_report';
                break;
            case 'far':
                $function = 'frequent_ani_report';
                break;
            case 'flr':
                $function = 'frequent_lrn_report';
                break;
            default : $function = 'index';
        }
        return $function;
    }

    public function export_log_down()
    {
        Configure::write('debug', 0);
        Configure::load('myconf');
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($_GET['key']);
        $log = $this->UsExportLog->findById($id);
        $filename = $log['UsExportLog']['file_name'].'.bz2';
        $file_path = realpath(ROOT . '/../download/us_report_download') ."/". $filename;

//        $filename = basename(($file_name));

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        else if (preg_match("/Firefox/", $ua))
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        else
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        ob_clean();
        flush();
        $x_sendfile_supported = in_array('mod_xsendfile', apache_get_modules());
        if (file_exists($file_path) && !headers_sent() && $x_sendfile_supported)
            header("X-Sendfile: $file_path");
        else
            @readfile($file_path);
    }


    public function export_download_error()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $cdr_export_log = base64_decode($this->params['url']['key']);
        $data = $this->UsExportLog->find('first',array(
            'fields' => 'error_msg',
            'conditions' => array(
                'id' => $cdr_export_log
            ),
        ));
        $error_msg = $data['UsExportLog']['error_msg'];
        $filename = "us_report_download_error.log";
        $file_path = realpath(ROOT . '/../download/us_report_download') ."/". $filename;
        file_put_contents($file_path,$error_msg);
        $filename = basename(($filename));

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        else if (preg_match("/Firefox/", $ua))
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        else
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        ob_clean();
        flush();
        @readfile($file_path);
    }


    public function export_log_kill($str_type = '')
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($_GET['key']);
        $log = $this->UsExportLog->findById($id);
        $backend_pid = (int) $log['UsExportLog']['pid'];
        if ($backend_pid > 0)
        {
            $cmd = "kill -9 $backend_pid";
            shell_exec($cmd);
        }
        $log['UsExportLog']['status'] = 5;
        $this->CdrExportLog->save($log);
        $this->CdrExportLog->create_json_array('#query-smartPeriod', 201, __('The Job[#%s] is canceled successfully!', true, $id));
        $this->Session->write("m", CdrExportLog::set_validator());
        $function_name = $this->str_to_function($str_type);
        $this->redirect($function_name);
    }
}

?>
