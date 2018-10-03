<?php

class BillingRuleController extends ApiAppController
{
    var $name = 'BillingRule';
    var $uses = array('did.DidBillingPlan', 'did.DidSpecialCode');
    var $components = array('RequestHandler');
    
    public function beforeFilter() {
            Configure::write("debug", 0);
            return true;
    }
    
    public function add()
    {
         if ($this->isPost()) {
             $name = $_POST['name'];
             $did_price = $_POST['did_price'];
             $channel_price = $_POST['channel_price'];
             $min_price = $_POST['min_price'];
             $billed_channels = $_POST['billed_channels'];
             $data = array('DidBillingPlan' => array(
                 'name' => $name,
                 'did_price' => $did_price,
                 'channel_price' => $channel_price,
                 'min_price' => $min_price,
                 'billed_channels' => $billed_channels,
             ));
             $this->DidBillingPlan->save($data);
             
             header('HTTP/1.1 200 OK');
            $response = array(
                'status' => 200, 'id' => $this->DidBillingPlan->id,
            );
            $this->set('data', $response);
        }
    }
    
    public function modify($id)
    {
         if ($this->isPost()) {
             $name = $_POST['name'];
             $did_price = $_POST['did_price'];
             $channel_price = $_POST['channel_price'];
             $min_price = $_POST['min_price'];
             $billed_channels = $_POST['billed_channels'];
             $data = array('DidBillingPlan' => array(
                 'name' => $name,
                 'did_price' => $did_price,
                 'channel_price' => $channel_price,
                 'min_price' => $min_price,
                 'billed_channels' => $billed_channels,
                 'id' => $id,
             ));
             
             $this->DidBillingPlan->save($data);
             
             header('HTTP/1.1 200 OK');
            $response = array(
                'status' => 200, 'id' => $id,
            );
            $this->set('data', $response);
         }
    }
    
    public function remove($id)
    {
        if ($this->isPost()) {
            $this->DidBillingPlan->del($id);
            
             
             header('HTTP/1.1 200 OK');
            $response = array(
                'status' => 200, 'id' => $id,
            );
            $this->set('data', $response);
        }
    }
    
    
}