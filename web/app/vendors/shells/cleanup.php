<?php

class CleanupShell extends Shell
{

    var $uses = array('Cleanup');

    function main()
    {
         Configure::load('myconf');
        $sip_path = Configure::read('sipp.sipp_exe');
        $rule_id = isset($this->args[0]) ? (int) $this->args[0] : "";
   

        
        $rule_info_item = $this->Cleanup->query("select * from cleanup where id = {$rule_id} and  actived = 't' and data_cleansing_frequency != null and data_cleansing_frequency != 0 ");
        

        if(empty($rule_info_item)){
             file_put_contents('/tmp/cleanup.log', "\r\n\r\n" . date('Y-m-d H:i:s') . "    can not find active record  \r\n" , FILE_APPEND);
            return false;
        }
        
        foreach($rule_info_item as $value){
            
            if($value[0]['name'] == 'CDR Export Log'){
                $this->_remove_cdr_export($value[0]['data_cleansing_frequency']);
            }else if($value[0]['name'] == 'Modification Log'){
                $this->_remove_mod_log($value[0]['data_cleansing_frequency']);
            }else if($value[0]['name'] == 'Invoice'){
                $this->_remove_invoice($value[0]['data_cleansing_frequency']);
            }else if($value[0]['name'] == 'Import Log'){
                $this->_remove_import_log($value[0]['data_cleansing_frequency']);
            }else if($value[0]['name'] == 'Expired Rates'){
                $this->_remove_export_rate_log($value[0]['data_cleansing_frequency']);
            }
            
        }
        
        file_put_contents('/tmp/cleanup.log', date('Y-m-d H:i:s') . " End Script   \r\n" , FILE_APPEND);
    }
    
    function _remove_export_rate_log($count = 0){
         if($count != 0){
             Configure::load('myconf');
             $file_path = Configure::read('database_export_path');
             $res = scandir($file_path);
             array_shift($res);
             array_shift($res);
        
            if(!empty($res)){
                $file_count = 0;
                foreach($res as $value){
                    $a = filectime($file_path.DS.$value);
                    $b = strtotime("-{$count} day",time());
                     if($a < $b){
                        @unlink($value);
                        $file_count ++;
                    }
                }
                
                file_put_contents('/tmp/cleanup.log', date('Y-m-d H:i:s') . " remove export rate file :$file_count    \r\n" , FILE_APPEND);
            }
        }
    }
    
    function _remove_import_log($count = 0){
        if($count != 0){
            $res = $this->Cleanup->query(" select * from import_export_logs where time < current_timestamp(0) - interval  '{$count} days'  ");
        
            if(!empty($res)){
                foreach($res as $value){
                    //$this->Cleanup->query("delete from payment_invoice where invoice_id = {$value[0]['invoice_id']}  ");
                    $this->Cleanup->query("delete from import_export_logs where id = {$value[0]['id']}  ");
                   
                    @unlink($value[0]['file_path']);
                    @unlink($value[0]['error_file_path']);
                }
                $file_count = count($res);
                file_put_contents('/tmp/cleanup.log', date('Y-m-d H:i:s') . " remove import log :$file_count    \r\n" , FILE_APPEND);
            }
            
        }
    }
    
    
    function _remove_invoice($count = 0){
        
        if($count != 0){
            $res = $this->Cleanup->query(" select * from invoice where invoice_time < current_timestamp(0) - interval  '{$count} days'   ");
        
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
