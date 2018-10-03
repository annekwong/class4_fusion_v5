<?php 

class DidAssignController extends ApiAppController
{
    var $name = "DidAssign";
    var $uses = array('did.DidAssign','did.DidRepos', "prresource.Gatewaygroup", 'did.DidBillingPlan');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() {
            Configure::write("debug", 0);
            return true;
    }
    
    public function add()
    {
        if ($this->RequestHandler->isPost()) 
        {
            $client_id = $_POST['client_id'];
            $number = $_POST['did'];
            $client_resource = $this->Gatewaygroup->findByClientId($client_id);
            $egress_id = $client_resource['Gatewaygroup']['resource_id'];
            
            $product_id = $this->DidAssign->check_default_static();
            $resource = $this->Gatewaygroup->findByResourceId($egress_id);
            $rate_table_id = $resource['Gatewaygroup']['rate_table_id'];
            $billing_rule_id = $resource['Gatewaygroup']['billing_rule'];
            $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
            $min_price = $billing_rule['DidBillingPlan']['min_price'];

            $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$number}', $min_price, 6, 6, $min_price, $min_price)";
            $this->DidRepos->query($sql);

            $item_id = $this->DidAssign->add_new_number($number, $product_id);
            $this->DidAssign->add_new_resouce($item_id, $egress_id);
            $this->DidAssign->add_assign($number, $egress_id);
            
            header('HTTP/1.1 200 OK');
            $response = array(
                'status' => 200, 'client_id' => $client_id, 'did' => $number,
            );
            $this->set('data', $response);
        }
    }
    
    public function modify($number)
    {
         if ($this->RequestHandler->isPost()) 
         {
                $client_id = $_POST['client_id'];
                $client_resource = $this->Gatewaygroup->findByClientId($client_id);
                $egress_id = $client_resource['Gatewaygroup']['resource_id'];

                $product_id = $this->DidAssign->check_default_static();
                $resource = $this->Gatewaygroup->findByResourceId($egress_id);
                $rate_table_id = $resource['Gatewaygroup']['rate_table_id'];
                $billing_rule_id = $resource['Gatewaygroup']['billing_rule'];
                $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
                $min_price = $billing_rule['DidBillingPlan']['min_price'];

                $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$number}', $min_price, 6, 6, $min_price, $min_price)";
                $this->DidRepos->query($sql);

                $this->DidAssign->delete_number($number, $product_id);
                $item_id = $this->DidAssign->add_new_number($number, $product_id);
                $this->DidAssign->add_new_resouce($item_id, $egress_id);
                $this->DidAssign->add_assign($number, $egress_id);

                header('HTTP/1.1 200 OK');
                $response = array(
                    'status' => 200, 'client_id' => $client_id, 'did' => $number,
                );
                $this->set('data', $response);
            }
    }
    
    public function remove($number)
    {
            if ($this->RequestHandler->isPost()) 
            {
                $number = $_POST['did'];
                $did_assign = $this->DidAssign->findByNumber($number);
                
                $egress_id = $did_assign['egress_id'];
                
                $product_id = $this->DidAssign->check_default_static();
                $resource = $this->Gatewaygroup->findByResourceId($egress_id);
                $rate_table_id = $resource['Gatewaygroup']['rate_table_id'];
                $billing_rule_id = $resource['Gatewaygroup']['billing_rule'];
                $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
                $min_price = $billing_rule['DidBillingPlan']['min_price'];

                $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$number}', $min_price, 6, 6, $min_price, $min_price)";
                $this->DidRepos->query($sql);
                $this->DidAssign->delete_number($number, $product_id);
                $this->DidAssign->remove_assign($number);
                header('HTTP/1.1 200 OK');
                $response = array(
                    'status' => 200, 'client_id' => $client_id, 'did' => $number,
                );
                $this->set('data', $response);
        }
    }
    
}

?>
