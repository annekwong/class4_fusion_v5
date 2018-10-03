<?php

class DidRepossController extends ApiAppController
{

    var $name = 'DidReposs';
    var $uses = array('did.DidRepos', 'ImportExportLog', 'did.DidAssign', "prresource.Gatewaygroup", 'did.DidBillingPlan');
    var $helpers = array('javascript', 'html', 'Common');

     public function beforeFilter() {
            Configure::write("debug", 0);
//            $this->autoLayout = false;
//            $this->autoRender = false;
            
            return true;
    }
    
    public function add()
    {
         if ($this->RequestHandler->isPost()) 
         {
                $data = array();
                $did = $_POST['did'];
                $vendor_id = $_POST['vendor_id'];
                $resource = $this->DidRepos->query("SELECT * FROM resource WHERE client_id = $vendor_id");
                $ingress_id = $resource[0][0]['resource_id'];
                $resource_prefix_result = $this->DidRepos->query("SELECT * FROM resource_prefix WHERE client_id = {$ingress_id}");
                $rate_table_id = $resource_prefix_result[0][0]['rate_table_id'];
//                $resource_id = $resource_prefix_result[0][0]['resource_id'];
//                $resource = $this->Gatewaygroup->findByResourceId($resource_id);
                $billing_rule_id = $resource[0][0]['billing_rule'];
                $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
                $min_price = $billing_rule['DidBillingPlan']['min_price'];
                $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$did}', $min_price, 6, 6, $min_price, $min_price)";
                $this->DidRepos->query($sql);
                
                $data['DidRepos']['number'] = $did;
                $data['DidRepos']['ingress_id'] = $ingress_id;
                $data['DidRepos']['status'] = 1;
                $this->DidRepos->save($data);
                
                header('HTTP/1.1 200 OK');
                $response = array(
                    'status' => 200, 'did' => $did
                );
                $this->set('data', $response);
         }
    }
    
    public function modify($did)
    {
        if ($this->RequestHandler->isPost()) 
         {
            $data = array();
            $vendor_id = $_POST['vendor_id'];
            $resource = $this->DidRepos->query("SELECT * FROM resource WHERE client_id = $vendor_id");
            $ingress_id = $resource[0][0]['resource_id'];
            $resource_prefix_result = $this->DidRepos->query("SELECT * FROM resource_prefix WHERE resource_id = {$ingress_id}");
            $rate_table_id = $resource_prefix_result[0][0]['rate_table_id'];
//            $resource_id = $resource_prefix_result[0][0]['resource_id'];
//            $resource = $this->Gatewaygroup->findByResourceId($resource_id);
            $billing_rule_id = $resource[0][0]['billing_rule'];
            $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
            $min_price = $billing_rule['DidBillingPlan']['min_price'];
            $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$did}', $min_price, 6, 6, $min_price, $min_price)";
            $this->DidRepos->query($sql);
            
            $data['DidRepos']['number'] = $did;
            $data['DidRepos']['ingress_id'] = $ingress_id;
            $this->DidRepos->save($data);
                
                header('HTTP/1.1 200 OK');
                $response = array(
                    'status' => 200, 'did' => $did
                );
                $this->set('data', $response);
        }
    }
    
    public function remove($number)
    {
        if ($this->RequestHandler->isPost()) 
         {
            $product_id = $this->DidAssign->check_default_static();
            $this->DidAssign->delete_number($number, $product_id);
            $this->DidAssign->del($number);
            $this->DidRepos->del($number);
            header('HTTP/1.1 200 OK');
            $response = array(
                'status' => 200, 'did' => $number
            );
            $this->set('data', $response);
        }
    }
    
}
