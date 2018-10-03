<?php

class WizardController extends DidAppController
{

    var $name = 'Wizard';
    var $uses = array('Client', 'did.DidRepos', 'ImportExportLog', 'did.DidAssign', "prresource.Gatewaygroup", 'did.DidBillingPlan', 'did.OrigLog');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function init()
    {
        $did_client_arr = $this->DidRepos->get_egress();
        $this->set("did_clients", $did_client_arr);

        $did_vendors_arr = $this->DidRepos->get_ingress();
        $this->set("did_vendors", $did_vendors_arr);


        $did_billing_rule = $this->Client->query("SELECT name,id FROM did_billing_plan order by id desc");
        $did_billing_rule_size = count($did_billing_rule);
        $did_billing_rule_arr = array();
        for ($i = 0; $i < $did_billing_rule_size; $i++)
        {
            $name = $did_billing_rule [$i] [0] ['name'];
            $key = $did_billing_rule [$i] [0] ['id'];
            $did_billing_rule_arr [$key] = $name;
        }
        $this->set("did_billing_rule", $did_billing_rule_arr);

//        $this->redirect("/did/clients/index");
    }

    public function get_vendors()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "select resource_id, alias from resource where ingress=true and trunk_type2=1 order by resource_id DESC";
        $data = $this->Client->query($sql);
        echo json_encode($data);
    }

    public function get_clients()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "select resource_id, alias from resource where egress=true and trunk_type2=1 order by resource_id DESC";
        $data = $this->Client->query($sql);
        echo json_encode($data);
    }

    public function get_billing_rule()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT name,id FROM did_billing_plan order by name ASC";
        $data = $this->Client->query($sql);
        echo json_encode($data);
    }

    public function index()
    {
        $this->init();
        if ($this->RequestHandler->ispost())
        {

            $post_data = $this->params['form'];
            $this->data['DidRepos']['ingress_id'] = $post_data['vendor'];
            $did_arr = preg_split("/[\s,;]+/", $post_data['did_value']);
            $product_id = $this->DidAssign->check_default_static();
            foreach ($did_arr as $value)
            {
                $number = intval($value);
                if (!$number)
                {
                    continue;
                }
                $this->data['DidRepos']['number'] = $number;
                $resource_prefix_result = $this->DidRepos->query("SELECT * FROM resource_prefix WHERE resource_id = {$this->data['DidRepos']['ingress_id']}");
                $rate_table_id = $resource_prefix_result[0][0]['rate_table_id'];
//                $resource_id = $resource_prefix_result[0][0]['resource_id'];
//                $resource = $this->Gatewaygroup->findByResourceId($resource_id);
                $billing_rule_id = $post_data['vendor_billing_rule'];
                $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
                $min_price = $billing_rule['DidBillingPlan']['min_price'];
                $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$this->data['DidRepos']['number']}', $min_price, 6, 6, $min_price, $min_price)";
                $this->DidRepos->query($sql);
                $this->data['DidRepos']['status'] = 1;
                $flg = $this->DidRepos->save($this->data);
                if ($flg !== false)
                {
                    $log_detail = "DID [{$this->data['DidRepos']['number']}]";
                    $this->OrigLog->add_orig_log("Ingress DID", 0, $log_detail);
                }

                $egress_id = $post_data['clients'];
                $resource_e = $this->Gatewaygroup->findByResourceId($egress_id);
                $rate_table_id1 = $resource_e['Gatewaygroup']['rate_table_id'];
//                $billing_rule_id1 = $resource_e['Gatewaygroup']['billing_rule'];
                $billing_rule_id1 = $post_data['vendor_billing_rule'];
                $billing_rule1 = $this->DidBillingPlan->findById($billing_rule_id1);
                $min_price1 = $billing_rule1['DidBillingPlan']['min_price'];

                $sql1 = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id1, '{$number}', $min_price1, 6, 6, $min_price1, $min_price1)";
                $this->DidRepos->query($sql1);

                $item_id = $this->DidAssign->add_new_number($number, $product_id);
                $this->DidAssign->add_new_resouce($item_id, $egress_id);
                $this->DidAssign->add_assign($number, $egress_id);
                $log_detail = "#{$number} assign => {$resource_e['Gatewaygroup']['alias']}";
                $this->OrigLog->add_orig_log("Egress DID", 0, $log_detail);
            }


//        修改client 的billingrule 
            $client_id = intval($post_data['clients']);
            $resourceInfo_client = $this->Gatewaygroup->findByClientId($client_id);
            $resourceInfo_client['Gatewaygroup']['resource_id'] = $client_id;
            $resourceInfo_client['Gatewaygroup']['billing_rule'] = $post_data['client_billing_rule'];
            $this->init_rate($post_data['client_billing_rule'],$rate_table_id1);
            $this->Gatewaygroup->save($resourceInfo_client);

            //        修改vendor 的billingrule 
            $vendor_id = intval($post_data['vendor']);
            $resourceInfo_vendor = $this->Gatewaygroup->findByClientId($vendor_id);
            $resourceInfo_vendor['Gatewaygroup']['resource_id'] = $vendor_id;
            $resourceInfo_vendor['Gatewaygroup']['billing_rule'] = $post_data['vendor_billing_rule'];
            $this->init_rate($post_data['vendor_billing_rule'],$rate_table_id);
            $this->Gatewaygroup->save($resourceInfo_vendor);
//        $this->redirect($resourceInfo_vendor);
            $this->Session->write('m', $this->DidAssign->create_json(201, __('Modify successfully!', true)));
            $this->xredirect("/did/clients/index");
        }
    }

    public function init_rate($billing_rule_id,$rate_table_id)
    {
        $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
        $min_price = $billing_rule['DidBillingPlan']['min_price'];
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '0', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '1', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '2', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '3', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '4', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '5', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '6', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '7', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '8', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
        $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '9', $min_price, 6, 6, $min_price, $min_price)";
        $this->DidBillingPlan->query($sql);
    }

    public function ajax_add_billing_rule()
    {
        Configure::write('debug', 0);
    }

}
