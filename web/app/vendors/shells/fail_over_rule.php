<?php

class FailOverRuleShell extends Shell
{

    var $uses = array('ImportExportLog', 'ResourceNextRouteRule');

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
        $schema = $this->requestAction('/down/get_schema_failover_rule');
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
            switch (trim($data_item['route_type']))
            {
                case 'Fail to Next Host':
                    $data_item['route_type'] = 1;
                    break;
                case 'Fail to Next Trunk':
                    $data_item['route_type'] = 2;
                    break;
                case 'Stop':
                    $data_item['route_type'] = 3;
                    break;
            }
            $rule = new ResourceNextRouteRule();
            $sql = "SELECT id FROM resource_next_route_rule WHERE reponse_code ='{$data_item['reponse_code']}' AND resource_id = '{$data_ext_arr['save_ext_attributes']['resource_id']}'";
            $data_info = $rule->query($sql);
            if ($data_info)
            {
                $duplicate_numbers ++;
                if (strcmp($data_ext_arr['save_ext_attributes']['duplicate_type'], 'ignore'))
                {
                    $delete_sql = "DELETE FROM resource_next_route_rule WHERE id = '{$data_info[0][0]['id']}'";
                    $rule->query($delete_sql);
                }
                else
                {
                    $error_content = "{$data_item['reponse_code']} is exsit!\t\n";
                    file_put_contents($error_file_path, $error_content, FILE_APPEND);
                    continue;
                }
            }
            $this->data['ResourceNextRouteRule'] = $data_item;
            $this->data['ResourceNextRouteRule']['resource_id'] = $data_ext_arr['save_ext_attributes']['resource_id'];
            $result = $rule->save($this->data ['ResourceNextRouteRule']);
            if (!$result)
            {
                $error_sum ++;
                $error_content = "Insert data failed!\t\n";
                file_put_contents($error_file_path, $error_content, FILE_APPEND);
                continue;
            }
            $this->data['ResourceNextRouteRule']['id'] = false;
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
