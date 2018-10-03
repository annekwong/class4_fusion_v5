<?php

class CleanupController extends AppController
{

    var $name = "Cleanup";
    var $uses = array('Cleanup');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->checkSession("login_type");
    }

    public function index()
    {
        $this->pageTitle = 'Configuration/Back-Up and Data Cleansing';
        $condition = array('order' => array('Cleanup.id'));

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "Cleanup.{$field} {$sort}";
                $condition = array('order' => array("{$order_sql}"));
            }
        }
        $cleanups = $this->Cleanup->find('all', $condition);

        $this->set('cleanups', $cleanups);
    }

    public function edit_panel($id = NULL)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if ($id != null)
            {
                $this->data['Cleanup']['id'] = $id;
                $this->Session->write('m', $this->Cleanup->create_json(201, __('The  [%s] is modified successfully!', true,array($this->data['Cleanup']['name']) )));
            }
            else
            {
                $this->Session->write('m', $this->Cleanup->create_json(201, __('The  [%s] is created successfully!', true,array($this->data['Cleanup']['name']) )));
            }
            
            $this->Cleanup->save($this->data);
            $cleanup = $this->Cleanup->findById($id);
            $this->_add_crontab($this->data);
            $this->_remove_crontab($cleanup);
            $this->xredirect("/cleanup/index");
        }
        $this->data = $this->Cleanup->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }

    public function change_status($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id_decode = base64_decode($id);
        $cleanup = $this->Cleanup->findById($id_decode);

        if ($cleanup['Cleanup']['actived'])
        {
            $cleanup['Cleanup']['actived'] = false;
            $this->Cleanup->create_json_array("", 201, 'The [' . $cleanup['Cleanup']['name'] . '] is inactived successfully !');
            $this->_remove_crontab($cleanup);
        }
        else
        {
            $cleanup['Cleanup']['actived'] = true;
            $this->_add_crontab($cleanup);
            $this->Cleanup->create_json_array("", 201, 'The [' . $cleanup['Cleanup']['name'] . '] is actived successfully !');
        }
        $this->Cleanup->save($cleanup);
        //$this->_add_crontab();
        $this->xredirect(array('controller' => 'cleanup', 'action' => 'index'));
    }
    
    function _remove_crontab($cleanup){
        $crontab_time = "";
        if(!empty($cleanup['Cleanup']['backup_frequency']) &&  !empty($cleanup['Cleanup']['data_cleansing_frequency']) ){
            
            if($cleanup['Cleanup']['backup_frequency'] == 1){
                $crontab_time = "0 0 * * * ";
            }else if($cleanup['Cleanup']['backup_frequency'] == 2){
                $crontab_time = "0 0 1 * * ";
            }else if($cleanup['Cleanup']['backup_frequency'] == 3){
                $crontab_time = "0 0 * * 0 ";
            }
            
            if(!empty($crontab_time)){
                App::import("Vendor", "crontab", array('file' => "crontab.php"));
                $crontab = new Crontab();
                $php_path = Configure::read('php_exe_path');
                $cmd = "{$php_path} " . APP . "../cake/console/cake.php cleanup {$cleanup['Cleanup']['id']}";
                $crontab->remove_cronjob($crontab_time, $cmd);
                //$crontab->append_cronjob($crontab_time, $cmd);
            }
            
        }
        
        
        
    }
    
    function _add_crontab($cleanup){
        
        $crontab_time = "";
        if(!empty($cleanup['Cleanup']['backup_frequency']) &&  !empty($cleanup['Cleanup']['data_cleansing_frequency']) ){
            
            if($cleanup['Cleanup']['backup_frequency'] == 1){
                $crontab_time = "0 0 * * * ";
            }else if($cleanup['Cleanup']['backup_frequency'] == 2){
                $crontab_time = "0 0 1 * * ";
            }else if($cleanup['Cleanup']['backup_frequency'] == 3){
                $crontab_time = "0 0 * * 0 ";
            }
            
            if(!empty($crontab_time)){
                App::import("Vendor", "crontab", array('file' => "crontab.php"));
                $crontab = new Crontab();
                $php_path = Configure::read('php_exe_path');
                $cmd = "{$php_path} " . APP . "../cake/console/cake.php cleanup {$cleanup['Cleanup']['id']}";
                ///$crontab->remove_cronjob($crontab_time, $cmd);
                $crontab->append_cronjob($crontab_time, $cmd);
            }
            
        }
        
        
        
        
        
        /*
        
        
         $rule_info_item = $this->Cleanup->query("select * from cleanup  ");
        
        foreach($rule_info_item as $value){
            if(empty($value[0]['data_cleansing_frequency']) || empty($value[0]['data_cleansing_frequency'])){
                continue;
            }
            
            
            
            
        }
        
        
        $crontab_time = "0 0 * * * ";
        App::import("Vendor", "crontab", array('file' => "crontab.php"));
        $crontab = new Crontab();
        $php_path = Configure::read('php_exe_path');
        $cmd = "{$php_path} " . APP . "../cake/console/cake.php cleanup";
        $crontab->remove_cronjob($crontab_time, $cmd);
        $crontab->append_cronjob($crontab_time, $cmd);
         * 
         */
    }
    
    function _test(){
        Configure::write('debug', 2);
        $this->autoLayout = false;
        $this->autoRender = false;
        //$this->_remove_invoice(200);
        
         Configure::load('myconf');
             $file_path = Configure::read('database_export_path');
             var_dump($file_path);
             $res = scandir($file_path);
             array_shift($res);
             array_shift($res);
           // var_dump($res);
        
            if(!empty($res)){
                $file_count = 0;
                foreach($res as $value){
                    $a = filemtime($file_path.DS.$value);
                    $b = strtotime("-1 day",  time());
                    if($a < $b){
                        
                    }
                    //@unlink($value);
                    //$file_count ++;
                }
            }
        exit;
    }
    
     function _remove_invoice($count = 0){
        
        if($count != 0){
            $res = $this->Cleanup->query(" select * from invoice where invoice_time < current_timestamp(0) - interval  '{$count} days'  limit 1000  ");
        
            if(!empty($res)){
                foreach($res as $value){
                    $this->Cleanup->query("delete from payment_invoice where invoice_id = {$value[0]['invoice_id']}  ");
                    $this->Cleanup->query("delete from invoice where invoice_id = {$value[0]['invoice_id']}  ");
                     Configure::load('myconf');
                    $invoice_path = Configure::read('generate_invoice.path');
                    $invoice_file = $invoice_path . DS . $value[0]['invoice_number'] . '_invoice.pdf';
                    //$file_path = realpath(ROOT . '/../download/cdr_download/' . $value[0]['file_name']) . '.gz';
                    @unlink($invoice_file);
                }
                $file_count = count($res);
                file_put_contents('/tmp/cleanup.log', date('Y-m-d H:i:s') . " remove invoice :$file_count    \r\n" , FILE_APPEND);
            }
            
        }
        
    }
    
     function _remove_cdr_export($count = 0){
        
        if($count != 0){
            $res = $this->Cleanup->query(" select * from cdr_export_log where export_time < current_timestamp(0) - interval  '{$count} days'   ");
        
            if(!empty($res)){
                foreach($res as $value){
                    $this->Cleanup->query("delete from cdr_export_log where id = {$value[0]['id']}  ");
                    $file_path = realpath(ROOT . '/../download/cdr_download/' . $value[0]['file_name']) . '.gz';
                    @unlink($file_path);
                }
                $file_count = count($res);
                file_put_contents('/tmp/cleanup.log', date('Y-m-d H:i:s') . " remove CDR Export Log count :$file_count    \r\n" , FILE_APPEND);
            }
            
        }
        
    }
    
      function _remove_mod_log($count = 0){
        
        if($count != 0){
            $res = $this->Cleanup->query(" select * from modif_log where time < current_timestamp(0) - interval  '{$count} days'   ");
        
            if(!empty($res)){
                foreach($res as $value){
                    $this->Cleanup->query("delete from modif_log where id = {$value[0]['id']}  ");
                    //$file_path = realpath(ROOT . '/../download/cdr_download/' . $value[0]['file_name']) . '.gz';
                    //@unlink($file_path);
                }
                $file_count = count($res);
                file_put_contents('/tmp/cleanup.log', date('Y-m-d H:i:s') . " remove Modification Log count :$file_count    \r\n" , FILE_APPEND);
            }
            
        }
        
    }


}
