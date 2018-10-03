<?php

class DidRepossController extends DidAppController
{

    var $name = 'DidReposs';
    var $uses = array('did.DidRepos', 'ImportExportLog', 'did.DidAssign', "prresource.Gatewaygroup", 'did.DidBillingPlan', 'did.OrigLog');
    var $helpers = array('javascript', 'html', 'Common');

    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    public function _get_data($ingress_id = '')
    {
        $this->set('ingresses', $this->DidRepos->get_ingress($ingress_id));
        $this->set('egresses', $this->DidRepos->get_egress());
    }

    function index($ingress_id = null)
    {
        $this->pageTitle = "Origination/DID Repository";


        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'DidRepos.number' => 'asc',
            ),
        );
        if (isset($_GET['search']) && !empty($_GET['search']) && strcmp($_GET['search'], 'Search....'))
        {
            $this->paginate['conditions'][] = array("DidRepos.number::text like '%{$_GET['search']}%'");
        }


        if ($ingress_id != null)
        {
            $this->paginate['conditions'][] = array("DidRepos.ingress_id = {$ingress_id}");
            $this->set('vendor_name', $this->DidRepos->get_vendor_name($ingress_id));
        }


        if (isset($_GET['advsearch']))
        {
            $ingress_id = $_GET['ingress_id'];
            $egress_id = $_GET['egress_id'];
            $number = $_GET['number'];
            $show = $_GET['show'];

            if (!empty($ingress_id))
            {
                $this->paginate['conditions'][] = array("DidRepos.ingress_id = {$ingress_id}");
            }
            if (!empty($egress_id))
            {
                $this->paginate['conditions'][] = array("DidRepos.egress_id = {$egress_id}");
            }
            if (!empty($number))
            {
                $this->paginate['conditions'][] = array("DidRepos.number like %'{$number}'%");
            }

            if (!empty($show))
            {
                if ($show == 1)
                {
                    $this->paginate['conditions'][] = array("DidRepos.status = 2");
                }
                else
                {
                    $this->paginate['conditions'][] = array("DidRepos.status = 1");
                }
            }
        }


        if ($this->RequestHandler->isPost())
        {
            if (isset($_POST['export_csv']))
            {
                $query = $this->paginate;
                unset($query['limit']);
                $this->data = $this->DidRepos->find('all', $query);
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: aplication/vnd.ms-excel");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=DID_Repository.xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->_get_data();
                $this->render('export_csv');
            }
        }

        $this->_get_data();
        $this->data = $this->paginate('DidRepos');
    }

    public function chech_num($number)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $is_exists = $this->DidRepos->check_num($number);
        if ($is_exists)
            echo 'true';
        else
            echo 'false';
    }

    public function action_edit_panel($ingress_id = '', $number = null)
    {
        Configure::write('debug', 0);
        if ($ingress_id == '0')
            $ingress_id = '';

        $this->_get_data($ingress_id);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if ($number != null)
            {
                $this->data['DidRepos']['number'] = $number;
                $resource_prefix_result = $this->DidRepos->query("SELECT * FROM resource_prefix WHERE resource_id = {$this->data['DidRepos']['ingress_id']}");
                $rate_table_id = $resource_prefix_result[0][0]['rate_table_id'];
                $resource_id = $resource_prefix_result[0][0]['resource_id'];
                $resource = $this->Gatewaygroup->findByResourceId($resource_id);
                $billing_rule_id = $resource['Gatewaygroup']['billing_rule'];
                $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
                $min_price = $billing_rule['DidBillingPlan']['min_price'];
                $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$number}', $min_price, 6, 6, $min_price, $min_price)";
                $this->DidRepos->query($sql);

                $this->Session->write('m', $this->DidRepos->create_json(201, __('The number of [' . $this->data['DidRepos']['number'] . '] is modified successfully!', true)));

                $old_data = $this->DidRepos->findByNumber($number);
                $data = array_diff_assoc($old_data['DidRepos'], $this->data['DidRepos']);
                $match_arr = array(
                    'country' => 'Country',
                    'state' => 'state',
                    'city' => 'City',
                    'ingress_id' => 'DID Vendor',
                );
                $log_detail_arr = array();
                foreach ($data as $diff_key => $value)
                {
                    if (strcmp($diff_key, 'number') && key_exists($diff_key, $match_arr))
                    {
                        if (strcmp($diff_key, 'ingress_id'))
                        {
                            $log_detail_arr[] = $match_arr[$diff_key] . "[" . $old_data['DidRepos'][$diff_key] . "=>" . $this->data['DidRepos'][$diff_key] . "]";
                        }
                        else
                        {
                            $old_ingress_name = $this->DidAssign->query("select  alias  from  resource where ingress=true and resource_id = {$old_data['DidRepos'][$diff_key]}");
                            $new_ingress_name = $this->DidAssign->query("select  alias  from  resource where ingress=true and resource_id = {$this->data['DidRepos'][$diff_key]}");
                            $log_detail_arr[] = $match_arr[$diff_key] . "[" . $old_ingress_name[0][0]['alias'] . "=>" . $new_ingress_name[0][0]['alias'] . "]";
                        }
                    }
                }
                $log_detail = implode(";", $log_detail_arr);
                if ($log_detail)
                {//如果有改变才记录到log中
                    $log_detail = "#{$number};" . $log_detail;
                    $log_flg = TRUE;
                    $action = 2;
                }
            }
            else
            {

                $resource_prefix_result = $this->DidRepos->query("SELECT * FROM resource_prefix WHERE resource_id = {$this->data['DidRepos']['ingress_id']}");
                $rate_table_id = $resource_prefix_result[0][0]['rate_table_id'];
                $resource_id = $resource_prefix_result[0][0]['resource_id'];
                $resource = $this->Gatewaygroup->findByResourceId($resource_id);
                $billing_rule_id = $resource['Gatewaygroup']['billing_rule'];
                $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
                $min_price = $billing_rule['DidBillingPlan']['min_price'];
                $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$this->data['DidRepos']['number']}', $min_price, 6, 6, $min_price, $min_price)";
                $this->DidRepos->query($sql);

                $this->data['DidRepos']['status'] = 1;
                $this->Session->write('m', $this->DidRepos->create_json(201, __('The number of [' . $this->data['DidRepos']['number'] . '] is created successfully!', true)));
                $log_flg = TRUE;
                $action = 0;
                $log_detail = "DID [{$this->data['DidRepos']['number']}]";
            }
            $country_state_arr = $this->OrigLog->get_country_state_by_did($this->data['DidRepos']['number']);
//            $this->data['DidRepos']['country'] = $country_state_arr['country'];
//            $this->data['DidRepos']['state'] = $country_state_arr['state'];
//            $this->data['DidRepos']['state'] = 'AZ';
            $flg = $this->DidRepos->save($this->data);
            if ($flg !== false && isset($log_flg))
            {
                $this->OrigLog->add_orig_log("Ingress DID", $action, $log_detail);
            }
            $this->xredirect("/did/did_reposs/index/" . $ingress_id);
        }
        if ($number != null)
        {
            $this->set('edit', true);
        }
        $this->data = $this->DidRepos->find('first', Array('conditions' => Array('number' => $number)));
    }

    public function change_status($number, $status, $ingress_id = '')
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        /*
          $product_id = $this->DidAssign->check_default_static();
          if ($status == 0)
          {
          $this->DidAssign->delete_number($number, $product_id);
          }
          else
          {
          $item_id = $this->DidAssign->add_new_number($number, $product_id);
          $this->DidAssign->add_new_resouce($item_id, $egress_id);
          $this->DidAssign->add_assign($number, $egress_id);
          }
         * 
         */
        if ($status == 0)
        {
            $this->Session->write('m', $this->DidRepos->create_json(201, __('The  status of  number  [' . $number . '] is inactived successfully!', true)));
        }
        else
        {
            $this->Session->write('m', $this->DidRepos->create_json(201, __('The  status of  number  [' . $number . '] is actived successfully!', true)));
        }
        $sql = "update did_assign set status = {$status} where number = '{$number}';
        update ingress_did_repository set status = {$status} where number = '{$number}';";
        $this->DidRepos->query($sql);
        $this->Session->write('m', $this->DidRepos->create_json(201, __('The  status of  number  [' . $number . '] is changed successfully!', true)));
        $this->xredirect("/did/did_reposs/index/" . $ingress_id);
    }

    public function delete_uploaded()
    {
        if ($this->RequestHandler->ispost())
        {
            Configure::load('myconf');
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
            $data ['ImportExportLog']['obj'] = 'DID Delete Uploaded';
            $data ['ImportExportLog']['file_path'] = $upload_file;
            $error_file = $upload_file . '.error';
            new File($error_file, true, 0777);
            $data ['ImportExportLog']['error_file_path'] = $error_file;
            $data ['ImportExportLog']['user_id'] = $user_id;
            $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
            $data ['ImportExportLog']['upload_type'] = '13';
            $export_log->save($data);
            $script_path = Configure::read('script.path');
            $perl_path = $script_path . DS . 'class4_did_delete_uploaded.pl';
            $perl_conf = CONF_PATH;
            $id = $export_log->id;
            $cmd = "perl $perl_path -c $perl_conf -i {$id}&";
            if (Configure::read('cmd.debug'))
            {
                file_put_contents('/tmp/cmd_debug', $cmd);
            }
            shell_exec($cmd);
            $this->set('upload_id', $id);
        }
        $this->set('example', $this->webroot . 'example' . DS . 'did_delete_uploaded.csv');
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
            //$upload_path = Configure::read('did.upload_path');
            //$file_name = date('Y-m-d_H:i:s') . '_' . uniqid() . '.csv';
            //$dest_file_path = $upload_path . DS . $file_name;
            //$result = move_uploaded_file($_FILES['upfile']['tmp_name'], $dest_file_path);
            //if ($result)
            //{
            //$ingress_id = $_POST['ingress_id'];
            $duplicate_type = $_POST['duplicate_type'];
            $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
            $upload_file = $path . DS . trim($_POST['myfile_guid']) . ".csv";

            //$resource_name = $this->DidRepos->get_resource_name($ingress_id);

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
            //$data ['ImportExportLog']['foreign_id'] = $ingress_id;
            //$data ['ImportExportLog']['foreign_name'] = $resource_name;
            $data ['ImportExportLog']['duplicate_type'] = $duplicate_type;
            $data ['ImportExportLog']['myfile_filename'] = isset($this->params['form']['myfile_filename']) ? $this->params['form']['myfile_filename'] : "";
            $export_log->save($data);
            $script_path = Configure::read('script.path');
            $perl_path = $script_path . DS . 'class4_upload_check.pl';
            $perl_conf = CONF_PATH;
            $id = $export_log->id;
            $cmd = "perl $perl_path -c $perl_conf -i {$id}  &";
            if (Configure::read('cmd.debug'))
            {
                file_put_contents('/tmp/cmd_debug', $cmd);
            }
            shell_exec($cmd);
            $this->set('upload_id', $id);
            //}
        }

        $this->_get_data();
    }

    public function delete($number, $ingress_id = '')
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $product_id = $this->DidAssign->check_default_static();
        $this->DidAssign->delete_number($number, $product_id);
        $this->DidAssign->del($number);
        $this->DidRepos->del($number);
        $log_detail = "#{$number}";
        $this->OrigLog->add_orig_log("Ingress DID", 1, $log_detail);
        $this->Session->write('m', $this->DidRepos->create_json(201, __('The  number  [' . $number . '] is deleted successfully!', true)));
        $this->xredirect("/did/did_reposs/index/" . $ingress_id);
    }

    public function mutiple_delete()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $selecteds = $_POST['selecteds'];
        $product_id = $this->DidAssign->check_default_static();
        foreach ($selecteds as $number)
        {
            $this->DidAssign->delete_number($number, $product_id);
            $this->DidAssign->del($number);
            $this->DidRepos->del($number);
        }
        $log_detail = "DID [" . implode(",", $selecteds) . "]";
        $this->OrigLog->add_orig_log("Ingress DID", 1, $log_detail);
        echo json_encode(array('stauts' => 1));
    }

}
