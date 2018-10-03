<?php

class RatesManagementController extends AppController
{

    var $name = "RatesManagement";
    var $uses = array('RateHandler', 'RateManagement', 'RateManagementOption', 'RateManagementDetail', 'Systemparam');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function index($type = "unprocessed")
    {
        $type_array = array('unprocessed', 'processed', 'unrecognized');
        if (!in_array($type, $type_array))
        {
            $type = "unprocessed";
        }
        $conditions = array();
        switch ($type)
        {
            case "unprocessed":
                $rate_management_detail_sql = "(select id from rate_management_detail where rate_management_detail.rate_management_id = RateManagement.id "
                        . " and rate_management_detail.rate_table_id is not null and rate_management_detail.status  =0 limit 1) is not null";
                break;
            case "processed":
                $rate_management_detail_sql = "(select id from rate_management_detail where rate_management_detail.rate_management_id = RateManagement.id "
                        . " and rate_management_detail.rate_table_id is not null and rate_management_detail.status  != 0 limit 1) is not null";
                break;
            case "unrecognized":
                $rate_management_detail_sql = "(select id from rate_management_detail where rate_management_detail.rate_management_id = RateManagement.id "
                        . " and rate_management_detail.rate_table_id is null limit 1) is not null "
                        . "or (select id from rate_management_detail where rate_management_detail.rate_management_id = RateManagement.id limit 1) is null";
                break;
            default : $rate_management_detail_sql = "";
        }
        $conditions[] = $rate_management_detail_sql;
        $this->params['pass'][0] = $type;
        $status = array('Unprocessed', 'Processing', 'Upload Successful', 'Upload Failed');
        $this->set('status', $status);
        $this->paginate = array(
            'fields' => array(),
            'limit' => 100,
            'order' => array(
            //'id' => 'desc',
            ),
            'conditions' => $conditions,
        );
        $data = $this->paginate('RateManagement');
        $this->set('rateManagements', $data);
        $this->set('rate_table', $this->RateManagement->find_all_rate_table());
    }

    public function move_to_process()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $detail_id = $this->params['form']['detail_id'];
        $rate_table_id = (int) $this->params['form']['rate_table_id'];
        $rate_table = $this->RateManagementDetail->query("select name from rate_table where rate_table_id = {$rate_table_id}");
        if (!$detail_id || !$rate_table)
            $this->RateManagementDetail->create_json_array('', 101, 'Failed!');
        else
        {
            $rate_table_name = $rate_table[0][0]['name'];
            $sql = "UPDATE rate_management_detail SET rate_table_id = {$rate_table_id}, rate_table_name = '{$rate_table_name}' WHERE id = {$detail_id}";
            $flg = $this->RateManagementDetail->query($sql);
            if ($flg === false)
            {
                $this->RateManagementOption->create_json_array('', 101, 'Failed!');
            }
            else
            {
                $this->RateManagementOption->create_json_array('', 201, 'succeed!');
            }
        }
        $this->RateManagementDetail->write('m', RateManagementOption::set_validator());
        $this->redirect('index/unprocessed');
    }

    public function download($encode_detail_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $detail_id = base64_decode($encode_detail_id);
        $detail = $this->RateManagementDetail->findById($detail_id);

        $file = $detail['RateManagementDetail']['file_path'];
        $filename = basename($file);

        header("Content-type: application/octet-stream");
        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        readfile($file);
        //让Xsendfile发送文件
        //header("X-Sendfile: $file");
    }

    public function upload_configuration($encode_rate_table_id)
    {
        $table_id = base64_decode($encode_rate_table_id);

        $data = $this->RateManagementOption->find(
                'first', array(
            'conditions' => array('rate_table_id' => $table_id),
                )
        );
        if ($this->RequestHandler->ispost())
        {
            $save_data = $this->data;
            $save_data['headers'] = implode(",", $save_data['fields_default_arr']);
            unset($save_data['fields_default_arr']);
            $flg = $this->RateManagementOption->save($save_data);
            if ($flg === false)
                $this->Session->write('m', $this->RateManagementOption->create_json(101, __('Save Failed!', true)));
            else
                $this->Session->write('m', $this->RateManagementOption->create_json(201, __('Save successfully!', true)));
            $this->redirect('upload_configuration/' . $encode_rate_table_id);
        }
        $all_headers = array("code", "code_name", "country", "effective_date", "end_date", "min_time", "interval", "rate",
            "intra_rate", "inter_rate", "ocn", "lata", 'first_part_of_code', 'second_part_of_code',
            "ignore", "ignore","ignore", "ignore","ignore", "ignore","ignore", "ignore","ignore", "ignore");
        $ignore_count = 10;
        $selected_headers = array();
        $select_headers_tmp = array();
        $unselected_headers = array();
        if (isset($data['RateManagementOption']['headers']))
            $select_headers_tmp = explode(",", $data['RateManagementOption']['headers']);

        $unselected_headers_tmp = array_diff($all_headers, $select_headers_tmp);
        if ($select_headers_tmp)
        {
            $selected_ignore_count = preg_match_all('/ignore/',$data['RateManagementOption']['headers']);
            if ($selected_ignore_count)
            {
                $unselected_ignore_count = $ignore_count - $selected_ignore_count;
                for ($i = 0; $i < $unselected_ignore_count; $i++)
                    $unselected_headers_tmp[] = 'ignore';
            }
        }
        $this->loadModel('RateTable');
        $schema = $this->RateTable->default_schema;
        foreach ($unselected_headers_tmp as $unselected_header)
            $unselected_headers[][$unselected_header] = isset($schema[$unselected_header]['name']) ? Inflector::humanize($schema[$unselected_header]['name']) :  Inflector::humanize($unselected_header);


        foreach ($select_headers_tmp as $selected_header)
            $selected_headers[][$selected_header] = isset($schema[$selected_header]['name']) ? Inflector::humanize($schema[$selected_header]['name']) :  Inflector::humanize($selected_header);

//        pr($unselected_headers);
//        pr($selected_headers);
        $notify_arr = array(1=>'Vendor',2=>'Owner Email',3=>'Both');
        $this->set('notify_arr',$notify_arr);
        $same_code_effective_arr = array(
            1   => 'Delete Existing Records',
            2   => 'End-Date Existing Records',
            0   => 'End-Date All Records'
        );
        $this->set('same_code_effective_arr',$same_code_effective_arr);
        $effective_date_formats = array('mm/dd/yyyy', 'yyyy-mm-dd', 'mm/dd/yyyy', 'dd/mm/yyyy', 'yyyy/mm/dd');
        $this->set('unselected_headers', $unselected_headers);
        $this->set("select_headers", $selected_headers);
        $this->set('effective_date_formats', $effective_date_formats);
        $this->set('data', $data);
        $this->set('table_id', $table_id);
        $list = $this->RateManagementOption->query('select jur_type, currency_id,name from rate_table where rate_table_id = ' . $table_id);
        $this->set('name', $list[0][0]['name']);
        $this->set('jur_type', $list[0][0]['jur_type']);
        $this->set('currency', $list[0][0]['currency_id']);
    }

    public function add_rate_handler()
    {
        $this->set('rate_table', $this->RateHandler->find_all_rate_table());
    }

    public function rate_handler()
    {
        $data = $this->RateHandler->findById(1);
        $this->set('rate_table', $this->RateHandler->find_all_rate_table());
        $this->set('data', $data);
    }

    public function add_save_rate_handler_handle()
    {
//        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$this->RequestHandler->ispost())
        {
            $this->RateHandler->create_json_array('', 101, 'Failed!');
            $this->RateHandler->write('m', RateHandler::set_validator());
            $this->redirect('rate_handler_list');
        }
        $post_data = $this->params['form'];
        $post_data['mail_tls'] = isset($post_data['mail_tls']) ? 1 : 0;
        $post_data['mail_ssl'] = isset($post_data['mail_ssl']) ? 1 : 0;
        $flg = $this->RateHandler->save($post_data);

        if ($flg === false)
        {
            $this->RateHandler->create_json_array('', 101, 'Failed!');
        }
        else
        {
            $this->RateHandler->create_json_array('', 201, 'succeed!');
        }
        $this->RateHandler->write('m', RateHandler::set_validator());
        $this->redirect('rate_handler_list');
    }

    public function ajax_get_upload_parameters($rate_table_id)
    {
        Configure::write('debug', 0);
        $rate_management_option = $this->RateManagementOption->findByRateTableId($rate_table_id);
        $this->set("data", $rate_management_option);
        $same_code_effective_arr = array(
            1   => 'Delete Existing Records',
            2   => 'End-Date Existing Records',
            0   => 'End-Date All Records'
        );
        $this->set('same_code_effective_arr',$same_code_effective_arr);
    }

    public function upload()
    {
        $detail_id = $_POST['detail_id'];
        $this->RateManagementOption->save($this->data);
        $detail = $this->RateManagementDetail->findById($detail_id);
        $rate_table_id = $detail['RateManagementDetail']['rate_table_id'];
        $rate_management_option = $this->RateManagementOption->findByRateTableId($rate_table_id);
        $this->set("rate_option", $rate_management_option);
        $rates_filepath = $detail['RateManagementDetail']['file_path'];
        if (!file_exists($rates_filepath))
        {
            $this->RateManagementOption->create_json_array('', 201, __('file is not exist',true));
            $this->Session->write('m', $this->RateManagementOption->create_json(201, __('file is not exist',true)));
            $this->redirect('index');
        }
        $end_date = $rate_management_option['RateManagementOption']['dup_end_date_all'];
        $end_date1 = $rate_management_option['RateManagementOption']['dup_end_date'];

        $tz = $rate_management_option['RateManagementOption']['dup_end_date_all_tz'];
        $dz1 = $rate_management_option['RateManagementOption']['dup_end_date_tz'];
        $date_format = trim($rate_management_option['RateManagementOption']['effective_date_format']);
        $this->set('date_format',$date_format);
        $sample_do = $rate_management_option['RateManagementOption']['dup_method'];
        $filename = $detail['RateManagementDetail']['orig_file_name'];
        $with_header = $rate_management_option['RateManagementOption']['with_header'];
        $code_name_match = $rate_management_option['RateManagementOption']['code_name_match'];

        $end_effective_date = 'NULL';

        if ($sample_do == '2')
        {
            if (!empty($end_date1))
                $end_date = "-T " . str_replace(" ", "_", $end_date1) . $dz1;
            else
                $end_date = '';
        } else if ($sample_do == '0')
        {
            $end_effective_date = "'" . $end_date . $tz . "'";
            $end_date = '';
        }
        else
        {
            $end_date = '';
        }


        $binpath = Configure::read('rateimport.bin');
        $confpath = Configure::read('rateimport.conf');
        $confpath_info = pathinfo($confpath);
        $confpath = $confpath_info['dirname'];

        $outpath = Configure::read('rateimport.out');

        $cmd_parm = '-u 0';
        $rate_table = $this->RateManagementDetail->query("select jur_type, code_deck_id from rate_table where rate_table_id = {$rate_table_id}");
        if ($rate_table[0][0]['jur_type'] == 3 || $rate_table[0][0]['jur_type'] == 4)
        {
            $targetFolder = Configure::read('rateimport.put');
            //$cmd_parm = '-u 1';
            $is_ocn_lata = 1;
            $rates_filepath_cmd = $targetFolder . DIRECTORY_SEPARATOR . trim($_POST['myfile_guid']) . '_by_ocn_lata' . ".csv";
        }
        else
        {
            //$cmd_parm = '-u 0';
            $is_ocn_lata = 0;
            $rates_filepath_cmd = $rates_filepath;
        }

        if (empty($rate_table[0][0]['code_deck_id']))
        {
            $cmd_codek = '-C 0';
        }
        else
        {
            $cmd_codek = '-C 1';
        }

        $system_type = Configure::read('system.type');

        $cmd = "{$binpath} $end_date -F '{$filename}' -t $system_type -d {$confpath} -r {$rate_table_id} -c {$date_format} -f '{$rates_filepath_cmd}' -o {$outpath} -m {$sample_do} -U {$_SESSION['sst_user_id']} {$cmd_parm} {$cmd_codek}";
        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));
        if (Configure::read('cmd.debug'))
        {
            file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
        }

        //$cmd = str_replace("'", "''", $cmd);
        $this->set('cmd', $cmd);
        $this->set('rate_table_id', $rate_table_id);
        $this->set('end_effective_date', $end_effective_date);
        $this->set("is_ocn_lata", $is_ocn_lata);
        $this->set("date_format", $date_format);
        $this->set('rates_file_cmd', $rates_filepath_cmd);
        $this->set('with_header', $with_header);
        $this->set('code_name_match', $code_name_match);

        $this->loadModel('RateTable');
        $schema = $this->RateTable->default_schema;

        $fields = array_keys($schema);

        $abspath = $rates_filepath;

        $cmds = array();

        array_push($cmds, "'s/\\r/\\n/g'");
        array_push($cmds, "'/^$/d'");
        $replace_double_quotes = "'s/\"//g'";
        array_push($cmds, $replace_double_quotes);
        array_push($cmds, "'s/\?//g'");
        $cmd_str = implode(' -e ', $cmds);
        $cmd2 = "sed -i -e {$cmd_str} {$abspath}";
        shell_exec($cmd2);

        $table = array();
        $row = 1;

        $handle = popen("head -n 21 '{$abspath}'", "r");

        while ($row <= 21 && $data = fgetcsv($handle, 1000, ","))
        {
            $row++;
            array_push($table, $data);
        }
        foreach ($table[0] as &$table_header)
            $table_header = strtolower($table_header);
        pclose($handle);
        if ($with_header && isset($table[0]))
        {
            if (in_array("Effective_Date", $table[0]) || in_array("effective_date", $table[0]))
            {
                $this->set('effective_date_flg', 1);
            }
            if (in_array("interval", $table[0]))
            {
                $this->set('interval_flg', 1);
            }
            if (in_array("min_time", $table[0]))
            {
                $this->set('min_time_flg', 1);
            }
        }
        $this->set('table', $table);
        $this->set('columns', $fields);
        $this->set('abspath', $abspath);
        $this->set('detail_id',$detail_id);
    }

}
