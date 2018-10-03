<?php

class AniPopulateShell extends Shell
{

    var $uses = array('RandomAniGeneration');

    function main()
    {
        $log_id = (int) $this->args[0];
        $log_sql = "SELECT prefix,number_of_digits,random_table_id FROM random_ani_populated_log WHERE id = {$log_id}";
        $log_info = $this->RandomAniGeneration->query($log_sql);
        if(empty($log_info))
        {
            $sql = "UPDATE random_ani_populated_log SET status = 3 WHERE id = {$log_id}";
            $flg = $this->RandomAniGeneration->query($sql);
            return false;
        }
        $sql = "UPDATE random_ani_populated_log SET status = 1 WHERE id = {$log_id}";
        $flg = $this->RandomAniGeneration->query($sql);
        $prefix = intval($log_info[0][0]['prefix']);
        $random_table_id = $log_info[0][0]['random_table_id'];
        $number_of_digits = (int) $log_info[0][0]['number_of_digits'];
        Configure::load('myconf');
        $file_path = Configure::read('database_export_path');
        $num_file = $file_path . DS . 'populate.csv';
        $exist_file = $file_path . DS . 'populate_old.csv';
        $success_file = $file_path . DS . 'populate_new.csv';
//生成初始全部数据文件
        $start_num = $prefix * pow(10,$number_of_digits);
        $end_num = $prefix . str_repeat(9,$number_of_digits);

        $start = intval($start_num);
        $end = intval($end_num);
        $tmp_start = $start;
        $success_num = 0;
        $duplicate_num = 0;
        $total_num = $end - $start + 1;
//        获取当前已有数据文件
        file_put_contents($exist_file,'');
        $sql = "\COPY (SELECT ani_number,random_table_id FROM random_ani_generation WHERE random_table_id = $random_table_id ) TO '" . $exist_file . "' delimiter as ','";
        $this->RandomAniGeneration->_get_psql_cmd($sql);
        while(true){
            if($tmp_start >= $end)
                break;
            if(file_exists($num_file))
                shell_exec("> $num_file");
            if(file_exists($success_file))
                shell_exec("> $success_file");
            $tmp_end = $tmp_start + 999999;
            if($tmp_end > $end)
                $tmp_end = $end;
            $num_cmd = "seq -f '%1.f,$random_table_id' $tmp_start $tmp_end > $num_file";
            shell_exec($num_cmd);
            $total_num_cmd = 'wc -l ' .$num_file;
            $total_num_result = shell_exec($total_num_cmd);
            $tmp_total_num = explode(' ',$total_num_result)[0];
//取出不重复的数据 ($num_file 中有 $exist_file中没有)
            $success_cmd = "grep -vFf $exist_file $num_file > $success_file";
            shell_exec($success_cmd);

            $success_num_cmd = 'wc -l ' .$success_file;
            $success_num_result = shell_exec($success_num_cmd);
            $tmp_success_num = explode(' ',$success_num_result)[0];
            $success_num += $tmp_success_num;

            $duplicate_num += $tmp_total_num - $tmp_success_num;

            $sql = "\COPY random_ani_generation (ani_number,random_table_id) FROM '$success_file' delimiter as ','";
            $this->RandomAniGeneration->_get_psql_cmd($sql);

            $tmp_start = $tmp_end+1;
            $sql = "UPDATE random_ani_populated_log SET total_num = {$total_num},success_num = {$success_num},"
                . "duplicate_num = {$duplicate_num} WHERE id = $log_id";
            $flg = $this->RandomAniGeneration->query($sql);
//            echo "total_num:".$total_num." success_num:".$success_num." duplicate_num:".$duplicate_num."\n";
//            var_dump($flg);
            ob_clean();
            sleep(5);
        }
        $sql = "UPDATE random_ani_populated_log SET finsh_time = CURRENT_TIMESTAMP(0),status = 2 WHERE id = $log_id";
        $this->RandomAniGeneration->query($sql);
        shell_exec("> $num_file");
        shell_exec("> $exist_file");
        shell_exec("> $success_file");
    }

}
