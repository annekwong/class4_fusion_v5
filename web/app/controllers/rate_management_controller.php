<?php 

class RateManagementController extends AppController
{
    public $name = "RateManagement";
    public $uses = array('Rate', 'Clientrate', 'ImportRateStatus');
    public $components = array('RequestHandler');
    
    
    function beforeFilter()
    {
        Configure::write('debug', 0);
        Configure::load('myconf');
        return true;
    }
    
    function _auth($auth_id)
    {
        $sys_auth_id = Configure::read('system.token');
        if ($sys_auth_id != $auth_id)
        {
            header('HTTP/1.1 404 Not Found'); 
            exit;
        }
    }
    
    public function rate_tables($auth_id)
    {
        $this->_auth($auth_id);
        $rate_tables = $this->Rate->find('all', array(
            'fields' => array('"Rate"."rate_table_id" AS "RateTable__id"', '"Rate"."name" AS "RateTable__name"'),
            'order'  => 'Rate.name ASC',
        ));
        
        $this->set('rate_tables', $rate_tables);
    }
    
    public function upload($auth_id)
    {
        $this->_auth($auth_id);
        $fileName = time() . '_' . uniqid();
        $targetFolder = Configure::read('rateimport.put');
        $targetFile = $targetFolder . '/' . $fileName .".csv";
        move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
        $origFileName = $_POST['fileName'];
        $rate_table_id = $_POST['rate_table_id'];
        $dateTime = $_POST['dateTime'];
        $timezone = $_POST['timezone'];
        $effective_date_format = $_POST['effective_date_format'];
        $same_measure = $_POST['same_measure'];
        
        
        $cmd = '';
        
        $end_effective_date = 'NULL';
        if ($same_measure == '2')
        {
            if (!empty($dateTime))
                $dateTime = "-T " . str_replace(" ", "_", $dateTime) . $timezone;
            else
                $dateTime = '';
        } else if ($same_measure == '0') {
            $end_effective_date = "'". $dateTime . $timezone . "'";
            $dateTime = '';
        } else {
            $dateTime = '';
        }
        
        $rate_table = $this->Clientrate->query("select jur_type from rate_table where rate_table_id = {$rate_table_id}");
        if ($rate_table[0][0]['jur_type'] == 3)
        {
            $cmd_parm = '-u 1  -C 0';
        }
        else
        {
            $cmd_parm = '-u 0';
        }
        
        $sql = "insert into import_rate_status(rate_table_id, status) values ({$rate_table_id}, -1) returning id";
        $log_result = $this->Clientrate->query($sql);
        $log_id = $log_result[0][0]['id'];
        
        $binpath = Configure::read('rateimport.bin');
        $confpath = Configure::read('rateimport.conf');
        $outpath = Configure::read('rateimport.out');
        
        $system_type = Configure::read('system.type');
        
        $cmd = "{$binpath} $dateTime -F ''{$origFileName}'' -t $system_type -d {$confpath} -r {$rate_table_id} -c {$effective_date_format} -f ''{$targetFile}'' -o {$outpath} -m {$same_measure} -U 0 {$cmd_parm} -I {$log_id}";
          
        $sql = "insert into rate_upload_queue(cmd, rate_table_id, status, end_date, log_id) values ('{$cmd}', $rate_table_id, 0, {$end_effective_date}, {$log_id})";
        $this->Clientrate->query($sql);
        
        $data = array('log_id' => $log_id);
        $this->set('data', $data);
    }
    
    public function view_result($auth_id,$log_id)
    {
        $this->_auth($auth_id);
        $import_log = $this->ImportRateStatus->find('first', array(
            'fields' => array(
                'ImportRateStatus.status', 'ImportRateStatus.delete_queue', 'ImportRateStatus.update_queue', 
                'ImportRateStatus.insert_queue', 'ImportRateStatus.error_counter', 
                'ImportRateStatus.reimport_counter', 'ImportRateStatus.error_log_file', 'ImportRateStatus.reimport_log_file',
                'ImportRateStatus.time', 'ImportRateStatus.upload_file_name', 'ImportRateStatus.local_file',
                'ImportRateStatus.method','ImportRateStatus.error_log_file','ImportRateStatus.error_counter',
                'ImportRateStatus.start_epoch','ImportRateStatus.end_epoch',
            ),
            'conditions' => array(
                'ImportRateStatus.id' => $log_id
            )
        ));
        
        if ($import_log['ImportRateStatus']['error_counter'] > 0) {
            $import_log['ImportRateStatus']['status'] = 0;
        }
        /*
        if (filesize($import_log['ImportRateStatus']['error_log_file'])) {
            $import_log['ImportRateStatus']['status'] = 0;
        }
        */
        $this->set("data", $import_log);
        $status = array("Error!", "Running", "Insert", "Update", "Delete", "Done", "Done");
        $status["-1"] = 'Waiting';
        $status["-2"] = "End Date";
        $this->set('status', $status);
    }
    
}