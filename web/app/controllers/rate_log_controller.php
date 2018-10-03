<?php

class RateLogController extends AppController
{

    var $name = 'RateLog';
    var $helpers = array();
    var $uses = array('ImportRateStatus', 'Clientrate','RateUploadTask');
    var $components = array();

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份exprot
        if ($this->params['action'] == 'get_file')
            return true;
        parent::beforeFilter(); //调用父类方法
    }

    public function import($rate_table_id = null)
    {
        $order_arr = array('RateUploadTask.id' => 'desc');
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
            'fields' => array(
                'RateUploadTask.operator_user', 'RateUploadTask.upload_file_path', 'RateUploadTask.upload_orig_file',
                'RateUploadTask.upload_format_file', 'RateUploadTask.result_file_path', 'RateUploadTask.id',
                'RateUploadTask.rate_table_id', 'RateUploadTask.reduplicate_rate_action', 'RateUploadTask.status',
                'RateUploadTask.progress', 'RateUploadTask.create_time', 'RateUploadTask.start_time',
                'RateUploadTask.end_time', 'rate_table.name', 'rate_table.rate_table_id','RateUploadTask.default_info',
            ),
            'limit' => 100,
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'type' => 'INNER',
                    'conditions' => array(
                        'RateUploadTask.rate_table_id = rate_table.rate_table_id'
                    ),
                ),
            ),
            'order' => $order_arr,
        );

        if (array_key_exists('rate_table', $this->params['url']) && !empty($this->params['url']['rate_table']))
        {
            $rate_table_id = $this->params['url']['rate_table'];
            $this->paginate['conditions']['RateUploadTask.rate_table_id'] = $rate_table_id;
        }
        if (array_key_exists('time', $this->params['url']) && !empty($this->params['url']['time']))
        {
            $date = strtotime($this->params['url']['time']);
            $this->paginate['conditions']['start_time >=?'] = $date;
        }
        if (array_key_exists('end_time', $this->params['url']) && !empty($this->params['url']['end_time']))
        {
            $end_time = strtotime($this->params['url']['end_time']);
            $this->paginate['conditions']['start_time <=?'] = $end_time;
        }
        $this->set('get_data', $this->params['url']);

        $this->set('status', $this->RateUploadTask->get_status_arr());
        $this->set('method', $this->RateUploadTask->get_method_arr());

        $this->data = $this->paginate('RateUploadTask');
        $this->set('rate_table_id', $rate_table_id);

        $rate_table = $this->RateUploadTask->find_all_rate_table();

        $this->set('rate_table', $rate_table);
    }


    public function import_bak($rate_table_id = null)
    {

        $order_arr = array('ImportRateStatus.id' => 'desc');
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
            'fields' => array(
                'ImportRateStatus.status', 'ImportRateStatus.delete_queue', 'ImportRateStatus.update_queue',
                'ImportRateStatus.insert_queue', 'ImportRateStatus.error_counter', 'ImportRateStatus.id',
                'ImportRateStatus.reimport_counter', 'ImportRateStatus.error_log_file', 'ImportRateStatus.reimport_log_file',
                'ImportRateStatus.time', 'ImportRateStatus.upload_file_name', 'ImportRateStatus.local_file',
                'ImportRateStatus.method', 'rate_table.name', 'rate_table.rate_table_id', 'users.name',
                'ImportRateStatus.start_epoch', 'ImportRateStatus.end_epoch','ImportRateStatus.default_info',
            ),
            'limit' => 100,
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ImportRateStatus.rate_table_id = rate_table.rate_table_id'
                    ),
                ),
                array(
                    'table' => 'users',
                    'type' => 'left',
                    'conditions' => array(
                        'ImportRateStatus.user_id = users.user_id'
                    ),
                )
            ),
            'order' => $order_arr,
        );

        if (key_exists('rate_table', $this->params['url']) && !empty($this->params['url']['rate_table']))
        {
            $rate_table_id = $this->params['url']['rate_table'];
            $this->paginate['conditions']['ImportRateStatus.rate_table_id'] = $rate_table_id;
        }

        if (key_exists('time', $this->params['url']) && !empty($this->params['url']['time']) && key_exists('end_time', $this->params['url']) && !empty($this->params['url']['end_time']))
        {
            //echo "www";
            $date = $this->params['url']['time'];
            $end_time = $this->params['url']['end_time'];
            $time = strtotime($date);
            $end_time = strtotime($end_time);
            $this->paginate['conditions'][] = "ImportRateStatus.start_epoch >= '" . $time . "'";
            $this->paginate['conditions'][] = "ImportRateStatus.start_epoch <= '" . $end_time . "'";
        }
        $this->set('get_data', $this->params['url']);

        $status = array("Error!", "Running", "Insert", "Update", "Delete", "Done", "Cancel");
        $status["-1"] = 'Waiting';
        $status["-2"] = "End Date";
        $this->set('status', $status);

        $this->data = $this->paginate('ImportRateStatus');
        $this->set('rate_table_id', $rate_table_id);

        $rate_table = $this->ImportRateStatus->find_all_rate_table();

        $this->set('rate_table', $rate_table);
    }

    public function stop($ratetable_id,$id)
    {
//        Configure::write('debug', 0);
//        $this->autoRender = false;
//        $this->autoLayout = false;
//        $cmd = "kill -9 `cat /tmp/rate_import_{$ratetable_id}_pid`";
//        shell_exec($cmd);
//        $sql = "update import_rate_status set status = 6 where rate_table_id = {$ratetable_id}";
//        $this->ImportRateStatus->query($sql);
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "update import_rate_status set status = 6 where rate_table_id = {$ratetable_id} and id = {$id} and status = -1 ";
        $flg = $this->ImportRateStatus->query($sql);
        if($flg === false)
            $this->Session->write('m', $this->ImportRateStatus->create_json(101, __('Failed!',true)));
        else
            $this->Session->write('m', $this->ImportRateStatus->create_json(201,__('Succeed!',true)));
        $this->redirect('/rate_log/import');
    }

    public function get_file()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $file = base64_decode($_GET['file']);
        $file = str_replace('_by_ocn_lata', '', $file);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
    }
}

?>
