<?php
class RerateController extends AppController
{

    var $name = "Rerate";
    var $uses = array('RerateCdrTask', 'Systemparam');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    /**
     * Hide All rerate functions.
     */
    public function beforeFilter()
    {
        $this->render('error404');
    }


    public function index()
    {
        $this->redirect('create_task');
    }



    /**
     * Create Rerate Task
     */
    public function create_task()
    {

        if($this->RequestHandler->isPost())
        {
            $post_data = $this->params['form'];
            $save_data = $this->params['data'];
            if (strtotime($save_data['to_time'].$save_data['timezone']) > (time() - 2*3600))
            {
                $this->Session->write('m', $this->RerateCdrTask->create_json(101, __('End Time can not exceed Report Time!',true)));
                $this->redirect('create_task');
            }
            $save_data['from_time'] = strtotime($save_data['from_time']);
            $save_data['to_time'] = strtotime($save_data['to_time']);
            if ($save_data['from_time'] > $save_data['to_time']){
                $this->Session->write('m', $this->RerateCdrTask->create_json(101, __('End Time can not exceed Start Time!',true)));
                $this->redirect('create_task');
            }

            $resource_client = $this->RerateCdrTask->getResourceClient();
            $client_arr = array();

            $trunks_arr = array();
            foreach ($post_data['trunk_data']['trunk_id'] as $key => $trunk)
            {
                if (!in_array($resource_client[$trunk],$client_arr))
                    $client_arr[] = $resource_client[$trunk];
                $tmp_arr = array(
                    $trunk,
                    1,
                    intval($post_data['trunk_data']['rate_table'][$key]),
                    intval(strtotime($post_data['trunk_data']['rate_effective_date'][$key])),
                );
                $trunks_arr[$key] = implode(',',$tmp_arr);
            }
            if ( $post_data['re_rate_trunk_type'] )
                $save_data['egress_trunk'] = implode(';',$trunks_arr);
            else
                $save_data['ingress_trunk'] = implode(';',$trunks_arr);


            $save_data['create_time'] = time();
            $save_data['client_ids'] = implode(',',$client_arr);
            $flg = $this->RerateCdrTask->save($save_data);
            if($flg === false)
                $this->Session->write('m', $this->RerateCdrTask->create_json(101, 'Failed!'));
            else
            {
                $this->Session->write('m', $this->RerateCdrTask->create_json(201, __('You Job is scheduled to execute in the queue.',true)));
                $this->redirect('re_rate_log');
            }

        }
        $this->set('ingress_trunks',$this->RerateCdrTask->get_client_ingress_group('', false));
        $this->set('ingress_trunks_active',$this->RerateCdrTask->get_client_ingress_group());
        $this->set('egress_trunks',$this->RerateCdrTask->get_client_egress_group(false));
        $this->set('egress_trunks_active',$this->RerateCdrTask->get_client_egress_group());
        $this->set('rate_tables',$this->RerateCdrTask->find_all_rate_table());
    }


    public function re_rate_log()
    {
        $this->pageTitle = 'Re-Rate History';
        $conditions = array(
        );
        $default_pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 20;
        $pageSize = $this->_get('size') ? $this->_get('size') : $default_pageSize;
        $_GET['size'] = $pageSize;
        $this->paginate = array(
            'fields' => array(
            ),
            'limit' => $pageSize,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('RerateCdrTask');

        $this->set('status',$this->RerateCdrTask->getStatus());
        $this->set('active_arr',array('No','Yes'));
        $this->set('ingress_trunks',$this->RerateCdrTask->findAll_ingress_id());
        $this->set('egress_trunks',$this->RerateCdrTask->findAll_egress_id());
    }


    public function re_report($encode_history_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $history_id = base64_decode($encode_history_id);
        $count = $this->RerateCdrTask->find('count',array('conditions' => array(
            'id' => $history_id,
            'or' => array(
                'status >= ?' => 5,
                'status' => 4
            ),
        )));
        if (!$count)
        {
            $this->Session->write('m', $this->RerateCdrTask->create_json(101, __('Illegal operation!',true)));
            $this->redirect('create_task');
        }

        $this->RerateCdrTask->save(array('id' => $history_id,'status' => 6));
        $this->loadModel('RerateReportExecLog');
        $save_log_arr = array(
            'task_id' => $history_id,
            'create_by' => $this->Session->read('sst_user_name'),
            'create_on' => date('Y-m-d H:i:sO'),
            'status' => 0,
            'exec_type' => 1
        );
        $this->RerateReportExecLog->save($save_log_arr);
        $log_id = $this->RerateReportExecLog->getLastInsertID();

        $this->Session->write("m", $this->RerateCdrTask->create_json(201,__("The Job#[%s] is scheduled to execute in the queue.", true,array($log_id))));
        $this->redirect('execute_log');
    }

    public function execute_log()
    {
        $this->loadModel('RerateReportExecLog');
        $this->pageTitle = 'Re-Rate report Exec Log';
        $conditions = array(
        );

        $order_arr = array('id' => 'desc');
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
            ),
            'limit' => $this->getPageSize(),
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('RerateReportExecLog');
        $this->set('status',$this->RerateReportExecLog->getStatus());
        $this->set('exec_type',$this->RerateReportExecLog->getExecType());
    }


    public function ajax_get_trunk_by_log_id($log_id,$type = 'ingress_trunks')
    {
        Configure::write('debug', 0);
        if(!in_array($type,array('ingress_trunks','egress_trunks')))
            $type = "ingress_trunks";
        $data = $this->RerateLog->get_trunk_by_log_id($log_id,$type);
        $this->set('data',$data);

    }

    public function download_cdr($encode_history_id)
    {
        $history_id = base64_decode($encode_history_id);
        $count = $this->RerateCdrTask->find('count',array('conditions' => array(
            'id' => $history_id,
        )));
        if (!$count)
        {
            $this->Session->write('m', $this->RerateCdrTask->create_json(101, __('Illegal operation!',true)));
            $this->redirect('create_task');
        }
        $this->loadModel('RerateCdrDownloadLog');
        $save_log_arr = array(
            'task_id' => $history_id,
            'create_by' => $this->Session->read('sst_user_name'),
            'create_on' => date('Y-m-d H:i:sO'),
            'status' => 0
        );
        $this->RerateCdrDownloadLog->save($save_log_arr);
        $log_id = $this->RerateCdrDownloadLog->getLastInsertID();
        
        $this->Session->write("m", $this->RerateCdrTask->create_json(201,__("The Job#[%s] is scheduled to execute in the queue.", true,array($log_id))));
        $this->redirect('cdr_download_log');
    }

    public function cdr_download_log()
    {
        $this->loadModel('RerateCdrDownloadLog');
        $this->pageTitle = 'Re-Rate Cdr Download Log';
        $conditions = array(
        );

        $order_arr = array('id' => 'desc');
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
            ),
            'limit' => $this->getPageSize(),
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('RerateCdrDownloadLog');
        $this->set('status',$this->RerateCdrDownloadLog->getStatus());
    }

    public function export($encode_history_id)
    {
        $log_id = base64_decode($encode_history_id);
        $this->loadModel('RerateCdrDownloadLog');
        $data = $this->RerateCdrDownloadLog->find('first',array(
                'fields' => array('download_file'),
                'conditions' => array(
                    'id' => $log_id,
                    'status' => 3
                )
            )
        );
        if (!$data)
        {
            $this->Session->write('m', $this->RerateCdrDownloadLog->create_json(101, __('Illegal operation!',true)));
            $this->redirect('cdr_download_log');
        }
        $download_file = $data['RerateCdrDownloadLog']['download_file'];
        if (!file_exists($download_file))
        {
            $this->Session->write('m', $this->RerateCdrDownloadLog->create_json(101, __('File is not exists!',true)));
            $this->redirect('cdr_download_log');
        }
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . basename($download_file));
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($download_file);
        exit;
    }

    public function re_balance($encode_history_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $history_id = base64_decode($encode_history_id);
        $count = $this->RerateCdrTask->find('count',array('conditions' => array(
            'id' => $history_id,
            'status >= ?' => 8,
        )));
        if (!$count)
        {
            $this->Session->write('m', $this->RerateCdrTask->create_json(101, __('Illegal operation!',true)));
            $this->redirect('create_task');
        }
        $this->RerateCdrTask->save(array('id' => $history_id,'status' => 9));
        $this->loadModel('RerateReportExecLog');
        $save_log_arr = array(
            'task_id' => $history_id,
            'create_by' => $this->Session->read('sst_user_name'),
            'create_on' => date('Y-m-d H:i:sO'),
            'status' => 0,
            'exec_type' => 2
        );
        $this->RerateReportExecLog->save($save_log_arr);
        $log_id = $this->RerateReportExecLog->getLastInsertID();

        Configure::load('myconf');
        $php_path = Configure::read('php_exe_path');
//        $cmd = "{$php_path} " . APP . "../cake/console/cake.php re_balance $history_id $log_id /dev/null";
        $cmd = "{$php_path} " . APP . "../cake/console/cake.php re_balance $history_id $log_id /tmp/test.log";
        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));
        file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);

        shell_exec($cmd);
        $this->Session->write("m", $this->RerateCdrTask->create_json(201,__("The Job#[%s] is scheduled to execute in the queue.", true,array($log_id))));
        $this->redirect('execute_log');
    }

}