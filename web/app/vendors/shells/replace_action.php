<?php

class ReplaceActionShell extends Shell
{

    var $uses = array('ImportExportLog', 'ResourceReplaceAction');

    function main()
    {
        $log_id = (int) $this->args[0];
        $sql = "SELECT * FROM import_export_logs WHERE id = {$log_id}";
        $data_info = $this->ImportExportLog->query($sql);
        $data_ext_arr = unserialize($data_info[0][0]['ext_attributes']);
        $upload_file = $data_info[0][0]['file_path'];
        $file = fopen($upload_file, 'r');
        $goods_list = array();
        while ($data = fgetcsv($file))
        {
            $goods_list[] = $data;
        }
        $header_arr = $goods_list[0];
        unset($goods_list[0]);
        sort($goods_list);
        $data_arr = array();
        $export_log = new ImportExportLog();
        $schema = $this->requestAction('/down/get_schema_repalce_action');
        foreach ($header_arr as $key => $items)
        {
            if (!array_key_exists($items, $schema))
            {
                $data ['ImportExportLog']['id'] = $log_id;
                $data ['ImportExportLog']['finished_time'] = gmtnow();
                $data ['ImportExportLog']['status'] = '-4';
                $export_log->save($data);
                exit;
            }
            foreach ($goods_list as $key2 => $item)
            {
                $data_arr[$key2][$schema[$items]] = $item[$key];
            }
        }
        $error_sum = 0;
        $duplicate_numbers = 0;
        $error_file_path = $data_info[0][0]['error_file_path'];
        $total_num = count($data_arr);
        foreach ($data_arr as $data_item)
        {
            $action = new ResourceReplaceAction();
            $sql = "SELECT id FROM resource_replace_action WHERE ani_prefix ='{$data_item['ani_prefix']}' "
            . "AND ani ='{$data_item['ani']}' AND ani_min_length ='{$data_item['ani_min_length']}'"
            . " AND ani_max_length ='{$data_item['ani_max_length']}' AND resource_id = '{$data_ext_arr['save_ext_attributes']['resource_id']}'";
            $data_info = $action->query($sql);
            if ($data_info)
            {
                $duplicate_numbers ++;
                if (strcmp($data_ext_arr['save_ext_attributes']['duplicate_type'], 'ignore'))
                {
                    $delete_sql = "DELETE FROM resource_replace_action WHERE id = '{$data_info[0][0]['id']}'";
                    $action->query($delete_sql);
                }
                else
                {
                    $error_content = "{$data_item['reponse_code']} is exsit!\t\n";
                    file_put_contents($error_file_path, $error_content, FILE_APPEND);
                    continue;
                }
            }
            $this->data['ResourceReplaceAction'] = $data_item;
            $this->data['ResourceReplaceAction']['resource_id'] = $data_ext_arr['save_ext_attributes']['resource_id'];
            $result = $action->save($this->data ['ResourceReplaceAction']);
            if (!$result)
            {
                $error_sum ++;
                $error_content = "Insert data failed!\t\n";
                file_put_contents($error_file_path, $error_content, FILE_APPEND);
                continue;
            }
            $this->data['ResourceReplaceAction']['id'] = false;
        }
        fclose($file);
        $success_numbers = intval($total_num) - intval($error_sum) - intval($duplicate_numbers);

        $data ['ImportExportLog']['id'] = $log_id;
        $data ['ImportExportLog']['finished_time'] = gmtnow();
        $data ['ImportExportLog']['status'] = 2;
        $data ['ImportExportLog']['success_numbers'] = $success_numbers;
        $data ['ImportExportLog']['error_row'] = intval($error_sum);
        $data ['ImportExportLog']['duplicate_numbers'] = intval($duplicate_numbers);
        $export_log->save($data);
    }

}
