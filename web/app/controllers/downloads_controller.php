<?php

App::import('Model', 'ImportExportLog');

class DownloadsController extends AppController
{

    var $name = 'Downloads';
    var $helpers = array('AppDownload', "AppUploads");
    var $uses = array('ProductItem');
    var $components = array('RequestHandler');

    const CSV_DELIMITER = ",";
    const CARRIER = "Client";

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    function egress_action()
    {
        $this->pageTitle = "Download/Egress Action";
        //$this->set("name",$this->select_name($id,"ResourceDirection"));
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_download("ResourceDirection", '', $this->webroot . "gatewaygroups/view_egress");
        }
        else
        {
            $this->_download("ResourceDirection", '', $this->webroot . "prresource/gatewaygroups/view_egress");
        }
    }

    function ingress_action()
    {
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_download("ResourceDirection", '', $this->webroot . "gatewaygroups/view_ingress");
        }
        else
        {
            $this->_download("ResourceDirection", '', $this->webroot . "prresource/gatewaygroups/view_ingress");
        }
    }

    function block_list()
    {
        $this->pageTitle = "Download/Block List";
        $this->set('module', 'Routing');
        $this->set('action', 'Block list');
        $this->_download("ResourceBlock", '', $this->webroot . "blocklists/index");
    }

    function jur_country()
    {
        $this->pageTitle = "Download/Jur Country";
        $this->_download("JurisdictionUpload", '', $this->webroot . "jurisdictionprefixs/view");
    }

    function carrier()
    {
        $this->_download("Carrier", '', $this->webroot . "clients/view");
    }

    function egress_host()
    {
        $this->pageTitle = "Download/Egress Host";
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_download("EgressHost", '', $this->webroot . "gatewaygroups/view_egress");
        }
        else
        {
            $this->_download("EgressHost", '', $this->webroot . "prresource/gatewaygroups/view_egress");
        }
    }

    function ingress_host()
    {
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_download("EgressHost", '', $this->webroot . "gatewaygroups/view_ingress");
        }
        else
        {
            $this->_download("EgressHost", '', $this->webroot . "prresource/gatewaygroups/view_ingress");
        }
    }

    function route_plan($id = null)
    {
        $id = intval($id);
        $this->set("name", $this->select_name($id, "Route"));
        if ($id <= 0)
        {
            $this->_download_null();
        }
        else
        {
            $this->set('id', $id);
            $this->_download("Route", "route_strategy_id = $id", $this->webroot . "routestrategys/routes_list/" . $id);
        }
    }

    function ingress_tran($id = null)
    {
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_download("ResourceTranslation", '', $this->webroot . "gatewaygroups/view_ingress", $id);
        }
        else
        {
            $this->_download("ResourceTranslation", '', $this->webroot . "prresource/gatewaygroups/view_ingress", $id);
        }
    }

    function egress()
    {
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_download("Egress", 'egress = true', $this->webroot . "gatewaygroups/view_egress");
        }
        else
        {
            $this->_download("Egress", 'egress = true', $this->webroot . "prresource/gatewaygroups/view_egress");
        }
    }

    function ingress()
    {
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_download("Ingress", 'ingress = true', $this->webroot . "gatewaygroups/view_ingress");
        }
        else
        {
            $this->_download("Ingress", 'ingress = true', $this->webroot . "prresource/gatewaygroups/view_ingress");
        }
    }

    function digit_translation($id = null)
    {
        $id = intval($id);
        $this->set("name", $this->select_name($id, "DigitTranslation"));
        if ($id <= 0)
        {
            $this->_download_null();
        }
        else
        {
            $this->set('id', $id);
            $this->_download("DigitTranslation", "translation_id = $id", $this->webroot . "digits/translation_details/" . $id);
        }
    }

    /**
     * download  rate
     * @param $id
     */
    function rate($id = null)
    {
        if ($id)
        {
            if (strcmp($id,intval($id)))
                $id = base64_decode($id);
        }

        Configure::write('debug', 0);
        $this->set('name', $this->select_name($id, 'RateTable'));
        $this->set('module', 'Switch');
        $this->set('action', 'rates');

        $id = intval($id);
        if ($id <= 0)
        {
            $this->_download_null();
        }
        else
        {
            $this->set('id', $id);
            if (isset($_SESSION['rate_search']))
            {
                $where = $_SESSION['rate_search'];
            }
            $eff = '';
            if (isset($_POST['type']) && $_POST['type'] == 'effect')
            {
                $eff = "and effective_date <= now() and (now() <  end_date or end_date is null)";
            }


            //$this->_download("RateTable","rate_table_id = $id    {$where} $eff",$this->webroot."clientrates/view/".$id,$id);
        }
        $model_name = "RateTable";
        $this->loadModel($model_name);
        $model = $this->{$model_name};
        $list = $model->query('select jur_type, currency_id,name from rate_table where rate_table_id = ' . $id);
        $this->set('jur_type', $list[0][0]['jur_type']);
        $this->set('currency', $list[0][0]['currency_id']);
        $this->set('table_id', $id);

        if ($this->RequestHandler->isPost()) {
            $this->autoRender = FALSE;
            $this->autoLayout = FALSE;

            $this->loadModel("Rate");

            $fields = $this->params['form']['fields'];
            array_push($fields, 'trunk');

            $header_text = !empty($_POST['header']) ? $_POST['header'] : false;
            $footer_text = !empty($_POST['footer']) ? $_POST['footer'] : false;
            $format = !empty($_POST['format']) && $_POST['format'] == 'csv' ? 1 : 2;
            $fields = $this->_filter_all_valid_fields(array_keys($this->_get_schema($model)), $fields);
            $fields_str = implode(",", $fields);
            $fields_str = str_replace('new_rate', 'null as new_rate', $fields_str);
            $fields_str = str_replace('change_status', 'null as change_status', $fields_str);
            $fromSql = $fields;

            foreach ($fromSql as $key => $item) {
                if (in_array(trim($item), array('change_status', 'new_rate'))) {
                    unset($fromSql[$key]);
                }
            }

            $fromSql = implode(',', $fromSql);
            $sql = "SELECT $fields_str FROM (SELECT {$fromSql} FROM rate WHERE rate_table_id = $id {$where} $eff) as rate";
            $newRateSql = "SELECT $fields_str FROM (SELECT {$fromSql} FROM rate WHERE rate_table_id = $id {$where}) as rate";
            $generatedFile = $this->Rate->generateFile($sql, $format, false, $newRateSql);

            ob_clean();

            if($format == 1){
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename=rate.csv");
                header("Content-Length: " . filesize($generatedFile));
                header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
                header('Expires:0');
                header('Pragma:public');
            } else if($format == 'xls') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Length: " . filesize($generatedFile));
                header("Content-Disposition: attachment;filename=rate.xls");
                header("Content-Transfer-Encoding: binary ");
            }
            echo "Rate Table[" . $list[0][0]['name'] . "]. Download Time:" . date('Y-m-d H:i:s') . "\n";

            if ($header_text)
                echo $header_text . "\n";

            readfile($generatedFile);

            if ($footer_text)
                echo $footer_text . "\n";

            exit();
        }
        $this->set('default_fields', $this->{$model_name}->default_export_fields($list[0][0]['jur_type']));
        $this->_render_download_page($model_name, '');
    }

    function code_deck($id = null)
    {
        $this->set('name', $this->select_name($id, 'Code'));
        $this->set('module', 'Switch');
        $this->set('action', 'Code Deck List');
        $id = intval($id);
        if ($id <= 0)
        {
            $this->_download_null();
        }
        else
        {
            $this->set('id', $id);
            $this->_download("Code", "code_deck_id = $id", $this->webroot . "codedecks/codes_list/" . $id, $id);
        }
    }

    function product_item($id = null)
    {
        $id = intval($id);
        $this->set("name", $this->select_name($id, "Productitem"));
        if ($id <= 0)
        {
            $this->_download_null();
        }
        else
        {
            $this->set('id', $id);
            if (isset($_SESSION['product_search']))
            {
                $where = $_SESSION['product_search'];
            }
            $model_name = "Productitem";
            $conditions = "product_id = $id  {$where}";
            $back_url = $this->webroot . "products/route_info/" . $id;
            ini_set("max_execution_time", "3600");
            ini_set("memory_limit", "-1");
            /*             * ****求＄ID ***** */
            if ($this->RequestHandler->isPost())
            {
                $this->loadModel($model_name);
                $model = $this->{$model_name};
                $fields = $this->params['form']['fields'];
                array_push($fields, 'trunk');
                $fields = $this->_filter_all_valid_fields(array_keys($this->_get_schema2($model)), $fields);
                if (empty($fields))
                {
                    throw new Exception('Please Choice Columns.');
                }
                $format = strtolower(trim($this->params['form']['format']));
                if (!in_array($format, array('csv', 'xls')))
                {
                    $format = 'csv';
                }
                $fd = $this->_new_file($model->alias, $format);
                $this->_write_header($fd, $format, count($fields));
                $this->_write_content2($fd, $format, $model, $fields, $conditions); #write data
                $this->_write_footer($fd, $format, count($fields));
                $fd->close();
                Configure::write('debug', 0);
                $this->autoRender = false;
                $this->layout = '';
                /*                 * ****求＄ID ***** */
                $jId = explode("=", $conditions);
                $id = intval($jId[1]);
                $this->_log($model->alias, $fd->path, $id);
                $this->_send_file($fd->path, $fd->name);
            }

            $this->loadModel($model_name);
            $this->set('schema', $this->_get_schema2($this->{$model_name}));
            $this->set('back_url', $back_url);
            $this->render('downloads');
        }
    }

    /*
      function _new_product_to_csv($id) {
      print_r($this->params['form']);
      $sql = "select item_id,digits,strategy,
      (select name from time_profile where time_profile_id = product_items.time_profile_id) as time_profile,
      array(
      select resource.alias from resource left join product_items_resource on resource.resource_id = product_items_resource.resource_id
      where product_items_resource.item_id =product_items.item_id order by product_items_resource.id asc
      ) as trunk
      from product_items
      where product_id = '$id'";
      $result = $this->ProductItem->query($sql);
      $keys = array_keys($result);
      print_r($k);
      } */

    ////////////////////////////// private method /////////////////////
    function _render_download_page($model_name, $back_url = null)
    {

        $this->loadModel($model_name);
        $shema = $this->_get_schema($this->{$model_name});
        $remove_cols = ["zone", "ocn", "lata", "seconds", "grace_time"];
        $shema = array_diff_key($shema,array_flip($remove_cols));
        $this->set('schema', $shema);
        $this->set('back_url', $back_url);
        $this->render('downloads');
    }

    /**
     *
     * @param unknown_type $model_name
     * @param unknown_type $conditions
     * @param unknown_type $back_url
     * @param unknown_type $id
     */
    function _download($model_name, $conditions = '', $back_url = '', $id = null)
    {
        ini_set("max_execution_time", "3600");
        ini_set("memory_limit", "-1");

        /*         * ****求＄ID ***** */
        if ($this->RequestHandler->isPost())
        {
            $this->_do_download($model_name, $conditions);
        }

        $this->_render_download_page($model_name, $back_url);
    }

    function _do_download($model_name, $conditions)
    {
        //	$this->_catch_exception_msg(array('DownloadsController','_do_download_impl'), $model_name, $conditions);
        $this->_do_download_impl($model_name, $conditions);
    }

    function _do_download_impl($model_name, $conditions)
    {
        //////****************************************************************************************************************************************************
        $this->loadModel($model_name);
        $model = $this->{$model_name};
        $fields = $this->params['form']['fields'];
        array_push($fields, 'trunk');
        $fields = $this->_filter_all_valid_fields(array_keys($this->_get_schema($model)), $fields);
        if (empty($fields))
        {
            throw new Exception('Please Choice Conloms.');
        }
        $format = strtolower(trim($this->params['form']['format']));
        if (!in_array($format, array('csv', 'xls')))
        {
            $format = 'csv';
        }
        $fd = $this->_new_file($model->alias, $format);
        $this->_write_header($fd, $format, count($fields));
        $this->_write_content($fd, $format, $model, $fields, $conditions); #write data
        $this->_write_footer($fd, $format, count($fields));
        $fd->close();
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->layout = '';
        /*         * ****求＄ID ***** */
        $jId = explode("=", $conditions);
        $id = intval($jId[1]);
        $this->_log($model->alias, $fd->path, $id);
        $this->_send_file($fd->path, $fd->name);
    }

    function _send_file($download_file, $file_name)
    {
        ob_clean();
        header("Content-type: application/octet-stream;charset=utf8");
        header("Accept-Ranges: bytes");
        header("Content-Disposition: attachment; filename=" . $file_name);
        echo file_get_contents($download_file);
        exit();
        return true;
    }

    function _format_csv_string($text)
    {
        return str_replace('"', '""', $text);
    }

    function _write_header($fd, $format, $col_num)
    {
        $text = $this->params['form']['header'];
        if (!empty($text))
        {
            if ($format == 'csv')
            {
                $text = $this->_format_csv_string($text);
                $fd->write("\"{$text}\"\n");
            }
            if ($format == 'xls')
            {
                $text = $this->_format_csv_string($text);
                $fd->write("\"{$text}\"chr(13)");
                #TODO XLS
            }
        }
    }

    /**
     *
     * @param $fd
     * @param $format
     * @param $model
     * @param $fields
     * @param $conditions
     */
    function _write_content($fd, $format, $model, $fields, $conditions)
    {
//		$conditions = '';
        $order = '';
        if (method_exists($model, 'find_all_for_download'))
        {

            //自定义下载方法
            $datas = $model->find_all_for_download($fields, $conditions, $order);
        }
        else
        {
            if($model->name = self::CARRIER){
                $datas = $model->data_with_values();
            }else{
                $datas = $model->find("all", array('conditions' => $conditions, 'order' => $order, 'recursive' => -1));
            }

            /*

              $datas = $model->query("select product_items.item_id,product_items.digits,product_items.strategy,
              product_items.time_profile_id,
              trunk.alias as trunk,trunk.by_percentage as percentage
              from product_items left join
              (select product_items_resource.id,product_items_resource.item_id, resource.alias ,product_items_resource.by_percentage
              from resource
              left join product_items_resource
              on resource.resource_id = product_items_resource.resource_id
              ) as trunk
              on product_items.item_id = trunk.item_id
              where {$conditions} order by trunk.id asc");

             */
        }


        if ($format == "csv")
        {
            $this->_write_csv_content($fd, $model, $fields, $datas);
        }
        if ($format == "xls")
        {
            $this->_write_xls_content($fd, $model, $fields, $datas);
        }
    }

    function _write_content2($fd, $format, $model, $fields, $conditions)
    {
//		$conditions = '';
        $order = '';

        if (method_exists($model, 'find_all_for_download'))
        {

            //自定义下载方法
            $datas = $model->find_all_for_download($fields, $conditions, $order);
        }
        else
        {

            /* $datas = $model->query("select product_items.item_id,product_items.digits,product_items.strategy,
              product_items.time_profile_id,
              trunk.alias as trunk,trunk.by_percentage as percentage
              from product_items left join
              (select product_items_resource.id,product_items_resource.item_id, resource.alias ,product_items_resource.by_percentage
              from resource
              left join product_items_resource
              on resource.resource_id = product_items_resource.resource_id
              ) as trunk
              on product_items.item_id = trunk.item_id
              where {$conditions} order by trunk.id asc"); */
            $datas = $model->query("select product_items.item_id,product_items.digits,product_items.strategy,
                        product_items.time_profile_id,
                        array( select resource.alias from resource left join product_items_resource on resource.resource_id = 
product_items_resource.resource_id where product_items_resource.item_id =product_items.item_id order by 
product_items_resource.id asc ) as trunk, 
                        array( select by_percentage from product_items_resource where product_items_resource.item_id =product_items.item_id order by 
product_items_resource.id asc ) as percentage
                        from product_items
                        where {$conditions} order by item_id desc");
            if (!empty($datas))
            {
                foreach ($datas as $k => $v)
                {
                    $datas[$k][0]['trunk'] = str_replace(",", ";", trim($v[0]['trunk'], '{}'));
                    $datas[$k][0]['percentage'] = strpos($v[0]['percentage'], 'NULL') !== false ? '' : str_replace(",", ";", trim($v[0]['percentage'], '{}'));
                }
            }
            //var_dump($datas);exit;
        }


        if ($format == "csv")
        {
            $this->_write_csv_content2($fd, $model, $fields, $datas);
        }
        if ($format == "xls")
        {
            $this->_write_xls_content2($fd, $model, $fields, $datas);
        }
    }

    function _write_csv_content($fd, $model, $fields, $datas)
    {
        $schema = $this->_get_schema($model);
        $data = array();
        $value = '';
        $f = '';
        $d = '';
        $fd->open('w');
        $fd_handle = $fd->handle;
        if (isset($this->params['form']['with_headers']) && $this->params['form']['with_headers'] == 'on')
        {
            foreach ($fields as $field)
            {
                # 前面已经 验证 field 的合法性了， 这个地方就省了
                $f = $schema[$field];
                $data[] = isset($f['name']) ? Inflector::humanize($f['name']) : Inflector::humanize($field);
//				$data[] =  '"'.$this->_format_csv_string($d).'"';
            }
            fputcsv($fd_handle, $data);
//			$fd->write(join(self::CSV_DELIMITER,$data)."\n");	
        }
        $model_name = $model->alias;
        foreach ($datas as $d)
        {
            $data = array();
            foreach ($fields as $field)
            {
                $m = isset($d[$model_name]) ? $d[$model_name] : $d[0];
                $value = isset($m[$field]) ? $m[$field] : '';
                $method = 'format_' . $field . '_for_download';
                if (method_exists($model, $method))
                {
                    $data[] = $model->{$method}($value, $d);
                }
                else
                {
                    $f = $schema[$field];
                    if (!empty($f) && isset($f['type']) && $f['type'] == 'boolean')
                    {
                        if ($value)
                        {
                            $data[] = 'Yes';
                        }
                        else
                        {
                            $data[] = 'No';
                        }
                    }
                    else
                    {
                        $data[] = $value;
                    }
                }
            }
            fputcsv($fd_handle, $data, self::CSV_DELIMITER, '"');
//			$fd->write(join(self::CSV_DELIMITER,$data)."\n");
        }
    }

    function _write_csv_content2($fd, $model, $fields, $datas)
    {
        $schema = $this->_get_schema2($model);
        $data = array();
        $value = '';
        $f = '';
        $d = '';
        $fd->open('w');
        $fd_handle = $fd->handle;
        if (isset($this->params['form']['with_headers']) && $this->params['form']['with_headers'] == 'on')
        {
            foreach ($fields as $field)
            {
                # 前面已经 验证 field 的合法性了， 这个地方就省了
                $f = $schema[$field];
                $data[] = isset($f['name']) ? Inflector::humanize($f['name']) : Inflector::humanize($field);
//				$data[] =  '"'.$this->_format_csv_string($d).'"';
            }
            fputcsv($fd_handle, $data);
//			$fd->write(join(self::CSV_DELIMITER,$data)."\n");	
        }
        $model_name = $model->alias;
        foreach ($datas as $d)
        {
            $data = array();
            foreach ($fields as $field)
            {
                $m = isset($d[$model_name]) ? $d[$model_name] : $d[0];
                $value = isset($m[$field]) ? $m[$field] : '';
                $method = 'format_' . $field . '_for_download';
                if (method_exists($model, $method))
                {
                    $data[] = $model->{$method}($value, $d);
                }
                else
                {
                    $f = $schema[$field];
                    if (!empty($f) && isset($f['type']) && $f['type'] == 'boolean')
                    {
                        if ($value)
                        {
                            $data[] = 'T';
                        }
                        else
                        {
                            $data[] = 'F';
                        }
                    }
                    else
                    {
                        $data[] = $value;
                    }
                }
            }
            fputcsv($fd_handle, $data, self::CSV_DELIMITER, '"');
//			$fd->write(join(self::CSV_DELIMITER,$data)."\n");
        }
    }

    function _write_xls_content($fd, $model, $fields, $datas)
    {
        #TODO XLS
        $schema = $this->_get_schema($model);
        $data = array();
        $value = '';
        $f = '';
        $d = '';
        $fd->open('w');
        $fd_handle = $fd->handle;
        if (isset($this->params['form']['with_headers']) && $this->params['form']['with_headers'] == 'on')
        {
            foreach ($fields as $field)
            {
                # 前面已经 验证 field 的合法性了， 这个地方就省了
                $f = $schema[$field];
                $data[] = isset($f['name']) ? Inflector::humanize($f['name']) : Inflector::humanize($field);
//				$data[] =  '"'.$this->_format_csv_string($d).'"';
            }
            fputcsv($fd_handle, $data, chr(9), '"');
            fwrite($fd_handle, chr(13));
            //$fd->write(join(self::CSV_DELIMITER,$data)."\n");	
        }
        $model_name = $model->alias;
        foreach ($datas as $d)
        {
            $data = array();
            foreach ($fields as $field)
            {
                $m = isset($d[$model_name]) ? $d[$model_name] : $d[0];
                $value = isset($m[$field]) ? $m[$field] : '';
                $method = 'format_' . $field . '_for_download';
                if (method_exists($model, $method))
                {
                    $data[] = $model->{$method}($value, $d);
                }
                else
                {
                    $f = $schema[$field];
                    if (!empty($f) && isset($f['type']) && $f['type'] == 'boolean')
                    {
                        if ($value)
                        {
                            $data[] = 'T';
                        }
                        else
                        {
                            $data[] = 'F';
                        }
                    }
                    else
                    {
                        $data[] = $value;
                    }
                }
            }
            fputcsv($fd_handle, $data, chr(9), '"');
            fwrite($fd_handle, chr(13));
//			$fd->write(join(self::CSV_DELIMITER,$data)."\n");
        }
    }

    function _write_xls_content2($fd, $model, $fields, $datas)
    {
        #TODO XLS
        $schema = $this->_get_schema2($model);
        $data = array();
        $value = '';
        $f = '';
        $d = '';
        $fd->open('w');
        $fd_handle = $fd->handle;
        if (isset($this->params['form']['with_headers']) && $this->params['form']['with_headers'] == 'on')
        {
            foreach ($fields as $field)
            {
                # 前面已经 验证 field 的合法性了， 这个地方就省了
                $f = $schema[$field];
                $data[] = isset($f['name']) ? Inflector::humanize($f['name']) : Inflector::humanize($field);
//				$data[] =  '"'.$this->_format_csv_string($d).'"';
            }
            fputcsv($fd_handle, $data, chr(9), '"');
            fwrite($fd_handle, chr(13));
            //$fd->write(join(self::CSV_DELIMITER,$data)."\n");	
        }
        $model_name = $model->alias;
        foreach ($datas as $d)
        {
            $data = array();
            foreach ($fields as $field)
            {
                $m = isset($d[$model_name]) ? $d[$model_name] : $d[0];
                $value = isset($m[$field]) ? $m[$field] : '';
                $method = 'format_' . $field . '_for_download';
                if (method_exists($model, $method))
                {
                    $data[] = $model->{$method}($value, $d);
                }
                else
                {
                    $f = $schema[$field];
                    if (!empty($f) && isset($f['type']) && $f['type'] == 'boolean')
                    {
                        if ($value)
                        {
                            $data[] = 'Yes';
                        }
                        else
                        {
                            $data[] = 'No';
                        }
                    }
                    else
                    {
                        $data[] = $value;
                    }
                }
            }
            fputcsv($fd_handle, $data, chr(9), '"');
            fwrite($fd_handle, chr(13));
//			$fd->write(join(self::CSV_DELIMITER,$data)."\n");
        }
    }

    function _write_footer($fd, $format, $col_num)
    {
        $text = $this->params['form']['footer'];
        if (!empty($text))
        {
            if ($format == 'csv')
            {
                $text = $this->_format_csv_string($text);
                $fd->write("\"{$text}\"\n");
            }
            if ($format == 'xls')
            {
                #TODO XLS
            }
        }
    }

    function test()
    {

        $tz = $this->get_sys_timezone();
        list($date, $time) = explode(' ', date('Y-m-d H:i:s'));
        list($hour, $min, $sec) = explode(':', $time);
    }

    function _new_file($filename, $format)
    {
        App::import("Core", "File");

        $file = APP . 'tmp' . DS . 'download' . DS . $format . DS . '' . gmdate("Y-m-d", time()) . DS . $filename . '_' . gmdate("Y-m-d", time()) . '_' . time() . '.' . $format;
        $file = new File($file, true, 0777);
        if ($file)
        {
            return $file;
        }
        else
        {
            throw new Exception("Create File Error,Please Contact Administrator.");
        }
    }

    function _get_schema($model)
    {


        if (isset($model->download_schema))
        {
            return $model->download_schema;
        }
        if (isset($model->default_schema))
        {
            $schema = $model->default_schema;
            /*
              $schema['trunk'] = array('type'=>'array', 'null'=>'', 'default'=>'', 'length'=>'');
              $schema['percentage'] = array('type'=>'array', 'null'=>'', 'default'=>'', 'length'=>'');

             */
            return $schema;
        }
        return $model->schema();
    }

    function _get_schema2($model)
    {
        if (isset($model->download_schema))
        {
            return $model->download_schema;
        }
        if (isset($model->default_schema))
        {
            $schema = $model->default_schema;
            $schema['trunk'] = array('type' => 'array', 'null' => '', 'default' => '', 'length' => '');
            $schema['percentage'] = array('type' => 'array', 'null' => '', 'default' => '', 'length' => '');
            return $schema;
        }
        return $model->schema();
    }

    function _download_null()
    {

    }

//	function _log($model_name,$download_file, $file_name){		
//		if (! file_exists ( $download_file )) {
//			$status = Importlog::ERROR_STATUS_DOWNLOAD_FAIL;
//			$file_size = 0;						
//		} else {
//			$status = Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS;			
//			$file_size = filesize ( $download_file );			
//		}
//		$user_id = 0;
//		if(isset($_SESSION ['sst_user_id'])){
//			$user_id = $_SESSION ['sst_user_id'];
//		}
//		$import_log = new Importlog ();
//		$data = array();
//		$data ['Importlog'] = array();
//		$data ['Importlog']['downloadtime']=gmtnow();
//		$data ['Importlog']['objectives']=$model_name; 
//		$data ['Importlog']['filepath']=$download_file;		
//  		$data ['Importlog']['realfilename']=$file_name; 
//		$data ['Importlog']['user_id']=$user_id; 
//		$data ['Importlog']['status']=$status;		
//		$data ['Importlog']['type']= Importlog::ERROR_TYPE_DOWNLOAD;
//		$data ['Importlog']['filesize']=$file_size; 
//		$import_log->save ( $data ['Importlog'] );
//	}
    function _log($model_name, $upload_file, $jid, $status = ImportExportLog::STATUS_SUCCESS)
    {
        $user_id = 0;
        if (isset($_SESSION ['sst_user_id']))
        {
            $user_id = $_SESSION ['sst_user_id'];
        }
        $export_log = new ImportExportLog ();
        $data = array();
        $data ['ImportExportLog'] = array();
        $data ['ImportExportLog']['status'] = $status;
        $data ['ImportExportLog']['time'] = gmtnow();
        $data ['ImportExportLog']['obj'] = $model_name;
        $data ['ImportExportLog']['file_path'] = $upload_file;
        $data ['ImportExportLog']['user_id'] = $user_id;
        $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_EXPORT;
        $data ['ImportExportLog']['foreign_name'] = $this->get_foreign_name($model_name, $jid);
        $export_log->save($data);
    }

    function _filter_all_valid_fields($schema, $fields)
    {
        $all_valid_fields = array();
        foreach ($fields as $f)
        {
            if (in_array($f, $schema))
            {
                /*
                  if ($f == 'intra_rate')
                  $f = 'intra_rate as intrastate_rate';
                  if ($f == 'inter_rate')
                  $f = 'inter_rate as interstate_rate';
                 * 
                 */
                $all_valid_fields[] = $f;
            }
        }
        return array_values(array_unique($all_valid_fields));
    }

    public function select_name($id, $modelName = null)
    {
        $sql = '';
        $name = '';
        if ($modelName == 'DigitTranslation')
        {
            $sql = "select translation_name as name from digit_translation where translation_id = $id";
        }
        else if ($modelName == 'RateTable')
        {
            $sql = "select name from rate_table where rate_table_id=$id";
        }
        else if ($modelName == 'Productitem')
        {
            $sql = "select name from  product where product_id=$id";
        }
        else if ($modelName == "Route")
        {
            $sql = "  select name from route_strategy where  route_strategy_id=$id";
        }
        else if ($modelName == 'Code')
        {
            $sql = "select name from code_deck where code_deck_id=$id";
        }
        $this->loadModel($modelName);
        if (!empty($id))
        {
            $name = $this->$modelName->query($sql);
        }
        return $name;
    }

    function get_foreign_name($model_name, $id)
    {
        if (empty($id))
        {
            return '';
        }

        if (class_exists($model_name) && method_exists($model_name, 'get_foreign_name'))
        {
            App::import("Model", $model_name);
            $model = new $model_name();
            $id = intval($id);
            $list = $model->get_foreign_name($id);
            if (!empty($list[0][0]['name']))
            {
                return $list[0][0]['name'];
            }
        }
        else
        {

            return '';
        }
    }

}

?>
