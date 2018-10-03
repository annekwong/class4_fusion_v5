<?php

class BlockLogShell extends Shell
{

    var $uses = array('BlockLog');

    function main()
    {
        //$sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        
        $now = date("Y-m-d H:i:s");
        
        $unblock_list = $this->BlockLog->query("SELECT re_enable_time,re_enable,log_id FROM block_log WHERE re_enable != true AND re_enable_time <= '{$now}';");
        
        foreach ($unblock_list as $unblock_list_items)
        {
            $block_log_id = intval($unblock_list_items[0]['log_id']);
            
            $unblock_sql = "DELETE FROM resource_block WHERE block_log_id = $block_log_id returning block_log_id";
            
            $flg = $this->BlockLog->query($unblock_sql);
            if($flg !== false)
            {
                $this->BlockLog->query("UPDATE block_log set re_enable = true WHERE log_id = $block_log_id");
            }
            
            $return_id = print_r($flg,true);
            
            file_put_contents('/tmp/unblock.log', date('Y-m-d H:i:s') . "\r\n block id is " . $return_id . "\r\n" , FILE_APPEND);
        }
        file_put_contents('/tmp/unblock.log', "\r\n" , FILE_APPEND);
    }
    
    
}
