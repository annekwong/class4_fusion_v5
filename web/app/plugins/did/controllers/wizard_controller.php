<?php

class WizardController extends DidAppController
{

    var $name = 'Wizard';
    var $uses = array('Client', 'did.Did', 'ImportExportLog', 'did.DidAssign', "prresource.Gatewaygroup", 'did.DidBillingPlan', 'did.OrigLog');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function init()
    {
        $did_client_arr = $this->Did->get_egress();
        $this->set("did_clients", $did_client_arr);

        $did_vendors_arr = $this->Did->get_ingress();
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
            $vendor_id = $post_data['vendor'];
            $client_id = intval($post_data['clients']);
            $did_arr = preg_split("/[\s,;]+/", $post_data['did_value']);
            $product_id = $this->Did->get_product_id();

            //        修改client 的billingrule
            $resourceInfo_client = $this->Gatewaygroup->findByClientId($client_id);
            $client_rate_table_id = $resourceInfo_client['Gatewaygroup']['rate_table_id'];
//            $resourceInfo_client['Gatewaygroup']['resource_id'] = $client_id;
//            $resourceInfo_client['Gatewaygroup']['billing_rule'] = $post_data['client_billing_rule'];
            $this->Gatewaygroup->save($resourceInfo_client);
            $client_billing_rule = $this->DidBillingPlan->findById($post_data['client_billing_rule']);
            $min_price = $client_billing_rule['DidBillingPlan']['min_price'];
            $this->save_did_rate($client_rate_table_id,$min_price);

            //        修改vendor 的billingrule
            $vendor_id = intval($post_data['vendor']);
            $resourceInfo_vendor = $this->Gatewaygroup->findByClientId($vendor_id);
            $vendor_rate_table_id = $resourceInfo_vendor['Gatewaygroup']['rate_table_id'];
//            $resourceInfo_vendor['Gatewaygroup']['resource_id'] = $vendor_id;
//            $resourceInfo_vendor['Gatewaygroup']['billing_rule'] = $post_data['vendor_billing_rule'];
            $this->Gatewaygroup->save($resourceInfo_vendor);
            $vendor_billing_rule = $this->DidBillingPlan->findById($post_data['vendor_billing_rule']);
            $min_price = $vendor_billing_rule['DidBillingPlan']['min_price'];
            $this->save_did_rate($vendor_rate_table_id,$min_price);

            foreach ($did_arr as $value)
            {
                $number = intval($value);
                if (!$number)
                    continue;
                $flg = $this->Did->insert_to_repository($vendor_id, $number, $post_data['vendor_billing_rule']);
                if ($flg === false)
                {
                    $this->Session->write('m', $this->Did->create_json(101, __('Create failed!', true)));
                    $this->xredirect("index");
                }
                $flg = $this->Did->assign_did($client_id, $vendor_id, $number, $post_data['vendor_billing_rule'], $post_data['client_billing_rule']);
                if ($flg === false)
                {
                    $this->Session->write('m', $this->Did->create_json(101, __('Assigned failed!', true)));
                    $this->xredirect("index");
                }
            }
            $this->Session->write('m', $this->Did->create_json(201, __('Create successfully!', true)));
            $this->xredirect("/did/did/index");
        }
    }


    public function ajax_add_billing_rule()
    {
        Configure::write('debug', 0);
    }

}
