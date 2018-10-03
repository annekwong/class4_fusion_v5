<?php

class WizardsController extends AppController
{

    var $name = 'Wizards';
    var $uses = array('Wizard','Client');
    var $components = array('RequestHandler');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function index()
    {
        if ($this->RequestHandler->isPost())
        {
            $post_data = $this->params['form'];
            $client_type = $post_data['client_type'];
            $trunk_type = $post_data['trunk_type'];
            $trunk_name = $post_data['trunk_name'];
            $cps_limit = $this->data['cps_limit'] == '' ? 'NULL' : $this->data['cps_limit'];
            $call_limit = $this->data['call_limit'] == '' ? 'NULL' : $this->data['call_limit'];
            $ips = isset($post_data['ips']) && count($post_data['ips']) ? $post_data['ips'] : array();
            $ports = isset($post_data['ports']) && count($post_data['ports']) ? $post_data['ports'] : array();
            $log_detail = "";
            $this->Wizard->begin();
            $error_msg = "";
            $rollback_sql = "";
            if ($client_type == '0')
            {
                $client_name = $post_data['client_name'];
                $this->data['name'] = $client_name;
                $credit_limit = -abs(intval($this->data['allowed_credit']));
                $this->data['allowed_credit'] = $credit_limit;
                $carrier_arr = $this->Wizard->create_carrier($this->data);
                $client_id = $carrier_arr['client_id'];
                $carrier_name = $carrier_arr['name'];
                $action_type = '0';
                $rollback_sql .= "DELETE FROM client WHERE client_id = {$client_id};";
            }
            elseif ($client_type == '1')
            {
                $client_id = $post_data['client'];
                $carrier_name = $this->Wizard->get_clinet_name_by_id($client_id);
                $action_type = '2';
            }
            else
            {
                $error_msg = "Failed!";
                $this->Wizard->rollback();
                $this->Session->write('m', $this->Wizard->create_json(101, __('Operation error',true)));
                $this->redirect('index');
            }

            $create_user_flg = $this->Wizard->create_user($this->data['login'],$this->data['password'],$client_id);
            if($create_user_flg === false)
            {
                $this->Wizard->rollback();
                $this->Session->write('m', $this->Wizard->create_json(101, __('Create User Failed',true)));
                $this->redirect('index');
            }

            $log_detail .= "Carrier''s name:{$carrier_name}; ";

            if($post_data['deploy_carrier'])
            {
                $carrier_template_id = $this->data['carrier_template_id'];
                $this->loadModel('CarrierTemplate');
                $carrier_template_info = $this->CarrierTemplate->get_client_fields($carrier_template_id);
                $update_info = array_merge($carrier_template_info,$this->data);
                $update_info['client_id'] = $client_id;
                $flg = $this->Wizard->save_carrier_by_template($update_info);
                if($flg === false)
                {
                    $this->Wizard->rollback();
                    $this->Session->write('m', $this->Wizard->create_json(101, __('Save carrier by template Failed',true)));
                    $this->redirect('index');
                }

            }


            if ($trunk_type == '0')
            {
                // ingress
                $rate_table = $post_data['rate_table'];
                $routing_type = $post_data['routing_type'];
                $egress_trunks = isset($post_data['egress_trunks']) ? $post_data['egress_trunks'] : array();

                $resource_id = $this->Wizard->create_trunk($trunk_name, 'ingress', $cps_limit, $call_limit, $client_id);

                if($post_data['deploy_ingress'])
                {
                    $ingress_template_id = $post_data['ingress_template'];
                    $this->loadModel('ResourceTemplate');
                    $data = $this->ResourceTemplate->get_resource_fields($ingress_template_id);
                    $field_set_arr = array();
                    foreach ($data as $field => $value)
                    {
                        if($value)
                            $field_set_arr[] = $field . " = " .$value;
                    }
                    $resource_update_flg = $this->Wizard->update_resource_by_template($field_set_arr,$resource_id);
                    if($resource_update_flg === false)
                    {
                        $this->Wizard->rollback();
                        $this->Session->write('m', $this->Wizard->create_json(101, __('Save ingress by template Failed',true)));
                        $this->redirect('index');
                    }
                }

                $rollback_sql .= "DELETE FROM resource WHERE resource_id = {$resource_id};";
                $this->Wizard->create_ip_port($resource_id, $ips, $ports);

                $routing_strategy_name = $trunk_name . '_RS';
                $routing_strategy_id = $this->Wizard->create_route_strategy($routing_strategy_name);
                $rollback_sql .= "DELETE FROM route_strategy WHERE route_strategy_id = {$routing_strategy_id};";
                $this->Wizard->create_resource_prefix($resource_id, $routing_strategy_id, $rate_table);


                if ($routing_type == '0')
                {
                    // static
                    $host_routing = $post_data['host_routing'];
                    $static_route_name = $trunk_name . '_SR';
                    $product_id = $this->Wizard->create_product($static_route_name);
                    $rollback_sql .= "DELETE FROM product WHERE product_id = {$product_id};";
                    $product_item_id = $this->Wizard->create_product_item($product_id, $host_routing);
                    $this->Wizard->create_product_item_egress($product_item_id, $egress_trunks);
                    $route_id = $this->Wizard->create_route_static($product_id, $routing_strategy_id);
                    $rollback_sql .= "DELETE FROM product WHERE route_id = {$route_id};";
                }
                else
                {
                    // dynamic
                    $dynamic_route_name = $trunk_name . '_DR';
                    $dynamic_id = $this->Wizard->create_dynamic($dynamic_route_name);
                    $rollback_sql .= "DELETE FROM dynamic_route WHERE dynamic_route_id = {$dynamic_id};";
                    $this->Wizard->create_dynamic_item_egress($dynamic_id, $egress_trunks);
                    // echo $dynamic_id;die;
                    $this->Wizard->create_route_dynamic($dynamic_id, $routing_strategy_id);
                }
            }
            elseif ($trunk_type == '1')
            {
                // egress
                $rate_table = $post_data['rate_table'];
                $resource_id = $this->Wizard->create_trunk($trunk_name, 'egress', $cps_limit, $call_limit, $client_id, $rate_table);
                if($post_data['deploy_egress'])
                {
                    $egress_template_id = $post_data['egress_template'];
                    $this->loadModel('ResourceTemplate');
                    $data = $this->ResourceTemplate->get_resource_fields($egress_template_id);
                    $field_set_arr = array();
                    foreach ($data as $field => $value)
                    {
                        if($value)
                            $field_set_arr[] = $field . " = " .$value;
                    }
                    $resource_update_flg = $this->Wizard->update_resource_by_template($field_set_arr,$resource_id);
                    if($resource_update_flg === false)
                    {
                        $this->Wizard->rollback();
                        $this->Session->write('m', $this->Wizard->create_json(101, __('Save egress by template Failed',true)));
                        $this->redirect('index');
                    }
                }
                $rollback_sql .= "DELETE FROM resource WHERE resource_id = {$resource_id};";
                $this->Wizard->create_ip_port($resource_id, $ips, $ports);
            }
            else
            {
                $error_msg = "Failed!";
                $this->Wizard->rollback();
            }
            $log_detail .= "Trunk Name:{$trunk_name}; ";

            $this->Wizard->commit();
            if ($error_msg)
            {
                $this->Wizard->create_json_array('#ClientOrigRateTableId', 101, __('Failed!', true));
            }
            else
            {
                $rollback_msg = "$log_detail  operation have been rolled back!";
                $log_id = $this->Wizard->logging($action_type, 'Wizard', $log_detail, $rollback_sql, $rollback_msg);
                $this->Wizard->create_json_array('#ClientOrigRateTableId', 201, __('Success!', true));
            }
            $url_flug = "wizards-index";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/wizards-index");
        }
        $this->init_info();
    }

    public function init_info()
    {
        $clients = $this->Wizard->get_clients();
        $codecses = $this->Wizard->get_codecs();
        $this->set('clients', $clients);
        $this->set('codecses', $codecses);
        $default_currency_id = $this->Wizard->query("SELECT sys_currency FROM system_parameter LIMIT 1");
        $currency_arr = $this->Client->findCurrency();
        $default_currency = isset($currency_arr[$default_currency_id[0][0]['sys_currency']]) ? $currency_arr[$default_currency_id[0][0]['sys_currency']] : $default_currency_id[0][0]['sys_currency'];
        $this->set('default_currency', $default_currency);
        $this->set('carrier_template',$this->Wizard->find_all_carrier_template());
        $this->set('ingress_template',$this->Wizard->find_all_resource_template(0));
        $this->set('egress_template',$this->Wizard->find_all_resource_template(1));
    }

    public function get_ratetable()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT rate_table_id, name FROM rate_table WHERE is_virtual is not true ORDER BY name ASC ";
        $data = $this->Wizard->query($sql);
        echo json_encode($data);
    }

    public function get_egress()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->Wizard->get_egress_trunks();
        echo json_encode($data);
    }

    public function ajax_check()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $trunk_name = $_POST['trunk_name'];
        $client_name = $_POST['client_name'];
        $login_name = $_POST['login_name'];
        $client_id = $_POST['client_id'];
        $sql = "SELECT count(*) FROM resource WHERE alias = '{$trunk_name}'";
        $data = $this->Wizard->query($sql);
        if ($data[0][0]['count'] > 0)
        {
            echo '1';
            return;
        }
        if (!empty($client_name))
        {
            $sql = "SELECT count(*) FROM client WHERE name = '{$client_name}'";
            $data = $this->Wizard->query($sql);
            if ($data[0][0]['count'] > 0)
            {
                echo '2';
                return;
            }
        }
        $sql = "SELECT count(*) FROM users WHERE name = '{$login_name}' AND client_id != $client_id";
        $data = $this->Wizard->query($sql);
        if ($data[0][0]['count'] > 0)
        {
            echo '3';
            return;
        }
        echo '0';
    }

}

?>
