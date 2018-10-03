<?php

class DidController extends DidAppController
{

    var $name = 'Did';
    var $uses = array('RateTable','ImportExportLog', "prresource.Gatewaygroup", 'did.DidBillingPlan', 'did.OrigLog','did.Did', 'Systemparam');
    var $helpers = array('javascript', 'html', 'Common', 'appCommon');

    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect('repository');
    }

    public function repository()
    {
//        if(isset($_POST['export_csv']) && $_POST['export_csv']==1){
//            $this->export();
//        }
        $this->pageTitle = "Origination/DID Repository";
        $pageSize = isset($this->params['url']['size']) ? $this->params['url']['size'] : 100;
        $currPage = isset($this->params['url']['page']) ? $this->params['url']['page'] : 1;
        $vendor_id = isset($this->params['url']['ingress_id']) ? $this->params['url']['ingress_id'] : '';
        $client_id = isset($this->params['url']['client_id']) ? $this->params['url']['client_id'] : '';
        $number = isset($this->params['url']['number']) ? $this->params['url']['number'] : '';
        $show_type = isset($this->params['url']['show']) ? $this->params['url']['show'] : '';

        $count = $this->Did->get_data_count($vendor_id,$client_id,$number,$show_type);


        $search_count = $pageSize;
        if ($count <= $pageSize)
            $search_count = $count;
        require_once MODELS.DS.'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $order_by = 'ORDER BY code ASC';

        if (isset($_GET['order_by']))
        {
            $order_by_arr = explode('-', $_GET['order_by']);
            if (count($order_by_arr) == 2)
                $order_by = "order by " . $order_by_arr[0] . ' ' . $order_by_arr[1];
        }

        $data = $this->Did->get_data($vendor_id,$client_id,$number,$show_type,$order_by, $search_count, $offset);
        $billingRules = $this->Gatewaygroup->getBillingRules();
        $this->_get_data();
        if(isset($_POST['export_csv']) && $_POST['export_csv']==1){
            Configure::write('debug', 0);
            $database_export_path = Configure::read('database_export_path');
            $file_name = 'downdid_' . time() . '.csv';
            $copy_file = $database_export_path . '/' . $file_name;

            $handle = @fopen($copy_file, 'w');

            $export_data = [];
            $ingresses = $this->viewVars['ingresses'];
            foreach ($data as $item) {
                // getting client name
                $code = $item[0]['code'];
                $sql = "select (select alias from resource where resource.resource_id = product_items_resource.resource_id) as alias from product_items left join product_items_resource ON product_items_resource.item_id = product_items.item_id where digits = '$code'";
                $client_arr = $this->RateTable->query($sql);
                $export_data[] = [
                    'did' => $code,
                    'did_vendor' => $ingresses[$item[0]['vendor_id']],
                    'vendor_billing_rule' => $item[0]['vendor_rule'],
                    'did_client' => isset($client_arr[0][0]['alias']) ? $client_arr[0][0]['alias'] : '',
                    'client_billing_rule' => $item[0]['client_rule'],
                    'created_time' => $item[0]['create_time'],
                    'assigned_time' => $item[0]['update_at'],
                ];
            }

            $header = array_keys($export_data[0]);
            fputcsv($handle, $header);
            foreach ($export_data as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);

            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=DID_repository.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            ob_clean();

            readfile($copy_file);
            exit;
        }
        $this->set('billingRules', $billingRules);
        $this->set('del_digits', $this->Did->getDelDigits());
        $this->set('actions', array('0' => '','1' =>'Add Prefix', '3' => 'Remove Prefix', '2' =>'Replace'));
        $page->setDataArray($data);
        $this->set('p', $page);

    }


    public function get_assigned_data()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $code = $this->_post('code');
//        $sql = "SELECT resource.alias FROM rate LEFT JOIN resource ON rate.rate_table_id = resource.rate_table_id WHERE code = '$code' and did_type = 2";
        $sql = "select (select client_id from resource where resource.resource_id = product_items_resource.resource_id) as client_id from product_items left join product_items_resource ON product_items_resource.item_id = product_items.item_id where digits = '$code'";
        $data = $this->RateTable->query($sql);
        $result = array('client_id' => '');
        if ($data) {
            $result['client_id'] = $data[0][0]['client_id'];
        }
        echo json_encode($result);
    }

    public function mapping()
    {
//        ini_set('memory_limit', '-1');
//        $sql = "SELECT * FROM client_cdr20161008, client_cdr20161007 LIMIT 1";
//        $result = $this->RateTable->query($sql);
//        die(var_dump($result));
    }

    public function action_edit_panel($ingress_id = '', $number = null)
    {
        Configure::write('debug', 0);
        if ($ingress_id == '0')
            $ingress_id = '';

        $this->_get_data($ingress_id);
        $this->layout = 'ajax';

        if ($this->isPost()) {
            $vendor_id = $this->data['vendor_id'];
            // ani/dnis manipulation
            if($_POST['ani']['actions'] || $_POST['dnis']['actions']){
                $type = $_POST['ani']['actions'] ? 0 : 1;
                $group = ($type === 0) ? 'ani' : 'dnis';
                $did_action = $_POST[$group]['actions'];
                $digits = $_POST[$group]['digits'];
                $deldigits = $_POST[$group]['deldigits'];

                if($did_action == '1' || $did_action == '3'){
                    $digits =  $did_action == '1' ? $digits :  $deldigits;
                    $this->add_direction($vendor_id, $type, $did_action,$digits );
                }elseif($did_action == '2'){
                    $ani = $_POST[$group]['ani']?:null;
                    $dnis = $_POST[$group]['dnis']?:null;
                    $this->replace_action($vendor_id, $type, $ani,  $dnis);
                }

            } else {
                $this->remove_replace_dir($vendor_id);
            }

            if ($number) {
                $id = $number;
                $number = $this->data['code'];
                $country = $this->data['country'];
                $code_name = $this->data['state'];
                $client_id = $this->data['client_id'];
                $vendorBillingId = $this->data['vendorBillingId'];
                $clientBillingId = $this->data['clientBillingId'];
                $data = $this->Did->get_data_by_id($id);
                $data_info = $data[0][0];
//                pr($this->data);
//                pr($data_info);
                $vendor_changed = 0;
                $client_changed = 0;
                $log_detail = array();

                if ($data_info['vendor_id'] != $vendor_id) {
                    $vendor_changed = 1;
                    $flg = $this->Did->delete_by_vendor($data_info['vendor_id'], $number);

                    if ($flg === false) {
                        $this->Session->write('m', $this->Did->create_json(101, __('Delete Old vendor data Failed', true)));
                        $this->redirect('repository');
                    }

                    $new_rate = $this->Did->insert_to_repository($vendor_id, $number, $vendorBillingId);
                    if (empty($new_rate)) {
                        $this->Did->rollback();
                        $this->Session->write('m', $this->Did->create_json(101, __('Update Vendor Failed', true)));
                        $this->redirect('repository');
                    } else {
                        $new_rate = $new_rate[0][0]['rate_id'];
                    }

                    $vendor_arr = $this->Did->findAll_origination_vendor();
                    $old_vendor_name = $vendor_arr[$data_info['vendor_id']];
                    $new_vendor_name = $vendor_arr[$vendor_id];
                    $log_detail[] = __('vendor [%s] change to [%s]', true, array($old_vendor_name, $new_vendor_name));
                }

                $client_changed = 1;

                if ($data_info['client_id']) {
                    $flg = $this->Did->delete_by_client($data_info['client_id'], $number);
//                        var_dump($flg);
                    if ($flg === false) {
                        $this->Session->write('m', $this->Did->create_json(101, __('Delete Old Client data Failed', true)));
                        $this->redirect('repository');
                    }
                }
                $client_change_flg = false;
                $client_arr = $this->Did->findAll_origination_client();
                $old_client_name = $client_arr[$data_info['client_id']];
                $new_client_name = $client_arr[$client_id];

                if(isset($_POST['client_change_flg']) && $_POST['client_change_flg']){
                    $client_change_flg = true;
                }
                $flg = $this->Did->assign_did($client_id, $vendor_id, $new_rate, $vendorBillingId, $clientBillingId, false, $id);

                if ($flg === false) {
                    $this->Did->rollback();
                    $this->Session->write('m', $this->Did->create_json(101, __('Update Client Failed', true)));
                    $this->redirect('repository');
                }


                $log_detail[] = __('client [%s] change to [%s]', true, array($old_client_name, $new_client_name));

                if (strcmp($data_info['code_name'], $code_name) || strcmp($data_info['country'], $country)) {
                    $flg = $this->Did->update_country_code_name($vendor_id, $client_id, $number, $country, $code_name, $vendor_changed, $client_changed);
                    if ($flg === false) {
                        $this->Did->rollback();
                        $this->Session->write('m', $this->Did->create_json(101, __('Update State and Country Failed', true)));
                        $this->redirect('repository');
                    }
                }

                $this->Did->commit();
                if ($log_detail) {
                    array_unshift($log_detail, __('DID number [%s]', true, $number));
                    $log_detail_str = implode(';', $log_detail);
                }

                $action = 2;
                $this->OrigLog->add_orig_log("DID", $action, $log_detail_str);
                $msg = sprintf(__('The number of [%s] is update successfully!', true), $number);
                $this->Session->write('m', $this->Did->create_json(201, $msg));



                $this->redirect('repository');

            } else {
//               添加did
                $number = $this->data['code'];
                $vendorBillingId = $this->data['vendorBillingId'];
                $clientBillingId = $this->data['clientBillingId'];
//                $country = $this->data['country'];
//                $code_name = $this->data['state'];
                $client_id = $this->data['client_id'];
//                if ($this->Did->check_exist_number($number)) {
//                    $msg = sprintf(__('The number of [%s] is exist!', true), $number);
//                    $this->Session->write('m', $this->Did->create_json(101, $msg));
//                    $this->redirect('repository');
//                }
//                $this->Did->begin();
                $new_rate = $this->Did->insert_to_repository($vendor_id, $number, $vendorBillingId, $clientBillingId);

                if (empty($new_rate)) {
                    $this->Did->rollback();
                    $this->Session->write('m', $this->Did->create_json(101, __('Update Vendor Failed', true)));
                    $this->redirect('repository');
                } else {
                    $new_rate = $new_rate[0][0]['rate_id'];
                }

//                $this->loadModel('RouteStrategy');
//                $this->loadModel('Route');
//
//                $routeStrategyId = $this->RouteStrategy->getOriginStaticRoute();
//                $routeStrategyId = $routeStrategyId['RouteStrategy']['route_strategy_id'];
//                $staticRouteId = $this->Did->get_product_id();
//
//                $saveResult = $this->Route->save(array(
//                    'static_route_id' => $staticRouteId,
//                    'route_strategy_id' => $routeStrategyId,
//                    'digits' => $number,
//                    'route_type' => 2
//                ));
//
//                if ($saveResult === false)
//                {
//                    $this->Did->rollback();
//                    $this->Session->write('m', $this->Did->create_json(101, __('Create Failed',true)));
//                    $this->redirect('repository');
//                }

                $flg = $this->Did->assign_did($client_id, $vendor_id, $new_rate, $vendorBillingId, $clientBillingId);
                if ($flg === false) {
//                         $this->Did->rollback();
                    $this->Session->write('m', $this->Did->create_json(101, __('Create Failed', true)));
                    $this->redirect('repository');
                }
//                    $this->Did->commit();
                $msg = sprintf(__('The number of [%s] is created successfully!', true), $number);
                $this->Session->write('m', $this->Did->create_json(201, $msg));
                $action = 0;
                $log_detail = "DID [{$this->data['code']}]";
                $this->OrigLog->add_orig_log("DID", $action, $log_detail);
                $this->redirect('repository');
            }

        }
        $result = array();
        if ($number) {
            $data = $this->Did->get_data_by_id($number);
            $result = $data[0][0];
            $replace_action = $this->Did->query("SELECT ani,dnis from resource_replace_action where resource_id='{$data[0][0]['vendor_id']}'");
            if (!empty($replace_action)) {
                $ani_dnis =  $replace_action[0][0]['ani'] ? : $replace_action[0][0]['dnis'];
                $group =  $replace_action[0][0]['ani'] ? 1 : 2;
                $action = 2;
            }else{
                $res_dir = $this->Did->query("SELECT action,digits, type from resource_direction where resource_id='{$data[0][0]['vendor_id']}'");
                if(!empty($res_dir)){
                    $ani_dnis =  $res_dir[0][0]['digits'];
                    $group =  !$res_dir[0][0]['type'] ? 1 : 2;
                    $action = $res_dir[0][0]['action'];
                }

            }
            if($action){
                $result = array_merge($data[0][0], ['ani_dnis' => $ani_dnis, 'group' => $group, 'action' => $action]);
            }
            $this->set('edit', 1);

        }

        $billingRules = $this->Gatewaygroup->getBillingRules();
        $this->set('billingRules', $billingRules);
        $this->set('del_digits', $this->Did->getDelDigits());
        $this->set('actions', array('0' => '','1' =>'Add Prefix', '3' => 'Remove Prefix', '2' =>'Replace'));
        $this->data = $result;
    }

    private function add_direction($vendor_id, $type, $did_action,$digits ){
        if($digits){
            $direction = '1'; // ingress
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_direction  where resource_id=$vendor_id");
            $this->Gatewaygroup->query("delete from  resource_replace_action  where resource_id=$vendor_id");
            $this->Gatewaygroup->query("insert into resource_direction (direction,resource_id,type,action,digits,number_type,number_length)  
						  values($direction,$vendor_id,$type,$did_action,'$digits',0,NULL)");
            $this->Gatewaygroup->commit();

        }
    }

    private function replace_action($vendor_id, $type, $ani, $dnis ){
        if($ani || $dnis){
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_direction  where resource_id=$vendor_id");
            $this->Gatewaygroup->query("delete from  resource_replace_action  where resource_id=$vendor_id");
            $insert_arr = array(
                'resource_id' => $vendor_id,
                'ani' => $ani,
                'dnis' => $dnis,
                'type' => $type
            );
            $this->loadModel('ResourceReplaceAction');
            $this->ResourceReplaceAction->save($insert_arr);
            $this->Gatewaygroup->commit();
        }
    }
    private function remove_replace_dir($vendor_id){
            $this->Gatewaygroup->query("delete from  resource_direction  where resource_id=$vendor_id");
            $this->Gatewaygroup->query("delete from  resource_replace_action  where resource_id=$vendor_id");
    }


    public function _get_data($ingress_id = '')
    {
        $this->set('ingresses', $this->Did->get_ingress($ingress_id));
        $this->set('egresses', $this->Did->get_egress());
        $this->set('carriers', $this->Did->get_carriers());
    }

    public function delete_did($encodeId)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($encodeId);
        $number = $this->Did->get_data_by_id($id);
        $number = $number[0][0]['code'];

        if (!is_numeric($id))
        {
            $msg = __('Illegal operation',true);
            $this->Session->write('m', $this->Did->create_json(101, $msg));
            $this->redirect('repository');
        }
        $flg = $this->Did->delete_did_by_id($id);

        if ($flg === false)
        {
            $msg = sprintf(__('The number of [%s] is deleted failed!', true),$number);
            $this->Session->write('m', $this->Did->create_json(101, $msg));
        }
        else
        {
            $msg = sprintf(__('The number of [%s] is deleted successfully!', true),$number);
            $this->Session->write('m', $this->Did->create_json(201, $msg));
            $action = 1;
            $log_detail = __('DID[%s]',true,$number);
            $this->OrigLog->add_orig_log("DID", $action, $log_detail);
        }
        $this->redirect('repository');
    }

    public function multiple_delete()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $selected_arr = $_POST['selected'];
        if (empty($selected_arr))
        {
            $msg = __('Illegal operation',true);
            $this->Session->write('m', $this->Did->create_json(101, $msg));
            $this->redirect('repository');
        }
        $flg = $this->Did->multiple_delete($selected_arr);
        if ($flg === false)
            echo json_encode(array('status' => 0));
        else
            echo json_encode(array('status' => 1));
    }

    public function export(){
        $order = ' order by t1.code asc ';
        $where_arr = array();

        if (isset($_GET['ingress_id']) and !empty($_GET['ingress_id']))
        {
            $where_arr[] = "ingress_id = {$_GET['ingress_id']}";
        }
        if (isset($_GET['egress_id']) and !empty($_GET['egress_id']))
        {
            $where_arr[] = "egress_id = {$_GET['egress_id']}";
        }

        if (isset($_GET['number']) and !empty($_GET['number']))
        {
            $where_arr[] = "t1.code <@ '{$_GET['number']}' or '{$_GET['number']}' <@ t1.code";
        }

        if (isset($_GET['show']) and !empty($_GET['show']))
        {
            if ($_GET['show'] == 1)
            {
                $where_arr[] = "did_type = 2";
            }
            else
            {
                $where_arr[] = "did_type = 1";
            }
        }

        $where = '';
        if(!empty($where_arr))
            $where .= ' where ' . implode(' and ', $where_arr);



        //var_dump($query);

        //pr($order);


        $sql = <<<EOT
SELECT t1.code as did,t1.alias as vendor,t1.create_time,t1.update_at, resource.alias as client from(
SELECT rate.code,resource.resource_id as ingress_id,resource.alias,rate.create_time,product_items.update_at,rate.did_type
from rate
LEFT JOIN resource_prefix ON resource_prefix.rate_table_id = rate.rate_table_id
  LEFT JOIN resource on resource_prefix.resource_id = resource.resource_id
  LEFT JOIN product_items on rate.code = product_items.digits
WHERE rate.did_type = 1 ) as t1 LEFT JOIN (select code,rate_table_id from rate where rate.did_type = 2) as rate ON rate.code = t1.code LEFT JOIN (select resource_id as egress_id,alias,rate_table_id from resource) as resource on resource.rate_table_id = rate.rate_table_id
 $where $order
EOT;


        //数据库导出数据的路径
        $database_export_path = Configure::read('database_export_path');
        $file_name = 'downdid_' . time() . '.csv';
        $copy_file = $database_export_path . '/' . $file_name;
        $copy_sql = "\COPY ($sql)  TO   '$copy_file'  CSV HEADER "; //daochu
        //pr($copy_sql);
        $this->Did->_get_psql_cmd($copy_sql);

        Configure::write('debug', 0);
        $this->Did->download_csv($copy_file, "DID_Repository.csv");


        /*$this->data = $this->DidRepos->find('all', $query);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: aplication/vnd.ms-excel");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=DID_Repository.xls");
        header("Content-Transfer-Encoding: binary ");

        readfile($copy_file);*/
        exit;
        /*Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->_get_data();
        $this->render('export_csv');*/
    }

    public function mass_assign(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $selected_did = $this->params['form']['selected_did'];
        $selected_arr = explode(",",$selected_did);
        $client_id = $this->params['form']['egress_id'];
        $clientBillingId = $this->params['form']['client_billing_rule'];
        $flg = $this->Did->mass_assign($selected_arr,$client_id, $clientBillingId);
        if ($flg === false)
            $this->Session->write('m', $this->Did->create_json(101,__('Assigned Failed',true)));
        else
            $this->Session->write('m', $this->Did->create_json(201, __('The numbers you selected is assigned successfully!', true)));
        $this->redirect('repository');
    }


    public function upload()
    {
        $id = isset($this->params['url']['id']) ? $this->params['url']['id'] : null;
        if ($id)
        {
            $this->set("client_id", $id);
        }
        $this->set('type', 14);
        if ($this->RequestHandler->ispost())
        {
            Configure::load('myconf');
            $duplicate_type = $_POST['duplicate_type'];
            $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
            $upload_file = $path . DS . trim($_POST['myfile_guid']) . ".csv";
            $user_id = 0;
            if (isset($_SESSION ['sst_user_id']))
            {
                $user_id = $_SESSION ['sst_user_id'];
            }
            App::import('Model', 'ImportExportLog');
            $export_log = new ImportExportLog();
            $data = array();
            $data ['ImportExportLog']['ext_attributes'] = array();
            $data ['ImportExportLog']['time'] = gmtnow();
            $data ['ImportExportLog']['obj'] = 'DID';
            $data ['ImportExportLog']['file_path'] = $upload_file;
            $error_file = $upload_file . '.error';
            new File($error_file, true, 0777);
            $data ['ImportExportLog']['error_file_path'] = $error_file;
            $data ['ImportExportLog']['user_id'] = $user_id;
            $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
            $data ['ImportExportLog']['upload_type'] = '13';
            $data ['ImportExportLog']['duplicate_type'] = $duplicate_type;
            $data ['ImportExportLog']['myfile_filename'] = isset($this->params['form']['myfile_filename']) ? $this->params['form']['myfile_filename'] : "";
            $export_log->save($data);
            $script_path = Configure::read('script.path');
            $perl_path = $script_path . DS . 'class4_upload_check.pl';
            $perl_conf = CONF_PATH;
            $id = $export_log->id;

            $php_path = Configure::read('php_exe_path');
            $cmd = "{$php_path} " . ROOT . "/cake/console/cake.php import {$id} > /dev/null &";

            shell_exec($cmd);

            $this->Session->write('m', $this->ImportExportLog->create_json('Import request created successfully!'));

            $this->redirect('/import_export_log/import');

//            $url = 'http://158.69.203.19:6060/api/v1/importfile/';
//            $ch = curl_init();
//            $data = array(
//                'file' => $upload_file,
//                'id'   => $id
//            );
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                'Accept: application/json',
//                'Content-Type: application/json'
//            ));
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
//            curl_exec($ch);
//            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//            curl_close($ch);
//            if($httpcode == 200) {
//                $this->Session->write('m', $this->ImportExportLog->create_json(201, 'File imported successfully!'));
//            } else {
//                $this->Session->write('m', $this->ImportExportLog->create_json(101, 'Import failed!'));
//            }
//            $this->redirect('/import_export_log/import');



//            $cmd = "perl $perl_path -c $perl_conf -i {$id}  &";
//            $info = $this->Systemparam->find('first',array(
//                'fields' => array('cmd_debug'),
//            ));
//            if (Configure::read('cmd.debug'))
//            {
//                file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
//            }
//            shell_exec($cmd);
            $this->set('upload_id', $id);
        }

        $this->_get_data();
    }

    private function _getClientVendorByDid($storage, $did)
    {
        $result = false;
        foreach ($storage as $item) {
            if ($item[0]['code'] == $did) {
                $result = array(
                    'client' => $item[0]['client_id'],
                    'vendor' => $item[0]['vendor_id']
                );

                break;
            }
        }

        return $result;
    }

    public function manipulation()
    {

        $vendor_id = isset($this->params['url']['ingress_id']) ? $this->params['url']['ingress_id'] : '';
        $client_id = isset($this->params['url']['egress_id']) ? $this->params['url']['egress_id'] : '';
        $count = $this->Did->get_data_count($vendor_id, $client_id, '', '');
        $order_by = 'order by code  desc';
        $data = $this->Did->get_data($vendor_id, $client_id, '', '', $order_by, $count, 0);

        if ($this->RequestHandler->isPost()) {

            $count = count($_POST['did']);
            $postData = $_POST;

            for ($i = 0; $i < $count; $i++) {

                if ($postData['aniAction'][$i] == 1 || $postData['aniAction'][$i] == 2) {
                    if ($postData['aniAppendCode'][$i]) {

                        $postData['aniAction'][$i] = $postData['aniAction'][$i] == 2 ? 3 : $postData['aniAction'][$i];
                        $clientVendorArray = $this->_getClientVendorByDid($data, $postData['did'][$i]);

                        if ($clientVendorArray) {
                            foreach ($clientVendorArray as $item) {
                                $trunk = $this->Did->query("select ingress, egress from resource where resource_id = {$item}");

                                if (!empty($trunk[0][0]['egress'])) {
                                    $direction = '2';
                                } else {
                                    $direction = '1';
                                }

                                $type = 0;
                                $digits = $postData['aniAction'][$i] == 3 ? $postData['aniRemoveDigit'][$i] : $postData['aniAppendCode'][$i];
                                $action = $postData['aniAction'][$i];

                                $this->Did->begin();
//                                $this->Did->query("delete from  resource_direction  where resource_id = {$item}");

                                $result = $this->Did->query("insert into resource_direction (direction, resource_id, type, action, digits)  
						            values($direction, {$item}, $type, $action,'$digits') returning *");

                                if (!$result) {
                                    $this->Did->rollback();
                                }

                                $this->Did->commit();
                            }
                        }
                    }
                }

                if ($postData['dnisAction'][$i] == 1 || $postData['dnisAction'][$i] == 2) {
                    if ($postData['dnisAppendCode'][$i]) {

                        $postData['dnisAction'][$i] = $postData['dnisAction'][$i] == 2 ? 3 : $postData['dnisAction'][$i];
                        $clientVendorArray = $this->_getClientVendorByDid($data, $postData['did'][$i]);

                        if ($clientVendorArray) {
                            foreach ($clientVendorArray as $item) {
                                $trunk = $this->Did->query("select ingress, egress from resource where resource_id = {$item}");

                                if (!empty($trunk[0][0]['egress'])) {
                                    $direction = '2';
                                } else {
                                    $direction = '1';
                                }

                                $type = 1;
                                $digits = $postData['dnisAction'][$i] == 3 ? $postData['dnisRemoveDigit'][$i] : $postData['dnisAppendCode'][$i];
                                $action = $postData['dnisAction'][$i];

                                $this->Did->begin();

                                $result = $this->Did->query("insert into resource_direction (direction, resource_id, type, action, digits)  
						            values($direction, {$item}, $type, $action,'$digits') returning *");

                                if (!$result) {
                                    $this->Did->rollback();
                                }

                                $this->Did->commit();
                            }
                        }
                    }
                }

                if ($postData['dnisAction'][$i] == 4 || $postData['aniAction'][$i] == 4 ||
                    $postData['dnisAction'][$i] == 3 || $postData['aniAction'][$i] == 3) {
                    if ($postData['dnisPrefix'][$i] && $postData['dnisReplaceWith'][$i] || $postData['aniPrefix'][$i] && $postData['aniReplaceWith'][$i]) {
                        $clientVendorArray = $this->_getClientVendorByDid($data, $postData['did'][$i]);

                        if ($clientVendorArray) {
                            foreach ($clientVendorArray as $item) {
                                $resource = $this->Did->query("SELECT * FROM resource WHERE resource_id = {$item}");
                                $translationId = $this->Did->query("SELECT translation_id FROM digit_translation WHERE translation_name = '{$resource[0][0]['alias']}'");

                                if (empty($translationId)) {
                                    $translationId = $this->Did->query("INSERT INTO digit_translation (translation_name) VALUES ('{$resource[0][0]['alias']}') returning translation_id");
                                }

                                $currentMethod = $postData['dnisAction'][$i] == 3 ? 2 : 1;
                                $aniMethod = $postData['aniPrefix'][$i] && $postData['aniReplaceWith'][$i] ? $currentMethod : 0;
                                $dniMethod = $postData['dnisPrefix'][$i] && $postData['dnisReplaceWith'][$i] ? $currentMethod : 0;

                                $this->Did->query("DELETE FROM translation_item WHERE translation_id = {$translationId[0][0]['translation_id']}");
                                $this->Did->query("INSERT INTO translation_item (translation_id, ani, dnis, action_ani, action_dnis, ani_method, dnis_method)
                                VALUES ({$translationId[0][0]['translation_id']}, '{$postData['aniPrefix'][$i]}', '{$postData['dnisPrefix'][$i]}', '{$postData['aniReplaceWith'][$i]}', '{$postData['dnisReplaceWith'][$i]}', {$aniMethod}, {$dniMethod})");
                            }
                        }
                    }
                }
            }

            $this->Session->write('m', $this->Did->create_json(201, 'Successfully'));
            $this->redirect('manipulation');
        }

        $this->_get_data();
        $this->set('data', $data);
    }

//    public function delete_all()
//    {
//        $this->Did->deleteAllDidInfo();
//
//        $this->Session->write('m', $this->Did->create_json(201, 'Successfully deleted'));
//        $this->redirect('repository');
//
//    }

}
