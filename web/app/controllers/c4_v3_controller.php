<?php 

class C4V3Controller extends AppController
{
    public $name = "C4V3";
    public $uses = array('Orig', 'did.DidAssign', 'did.DidRepos', 'did.DidBillingPlan', 'prresource.Gatewaygroup');
    public $components = array('RequestHandler');
    public $helper = array('Xml','JavaScript');
    public $TYPE_LIST = array('Local' => 1, 'TollFree' => 2, 'International' => 3);
    
    
    function beforeFilter()
    {
        Configure::write('debug', 0);
        return true;
    }
    
    
    public function accounts($account_id, $country_code, $type)
    {
    	$format_data = array();
        if(strlen($account_id) == 32)
        {
        	$ingress_id = $this->Orig->get_ingress_id($account_id);
        	if($ingress_id != NULL)
        	{
	        	$conditions = array();
	        	$type = $this->TYPE_LIST[$type];
	        	//$conditions[] = "ingress_id = {$ingress_id}";
	        	$conditions[] = "country = '{$country_code}'";
	        	$conditions[] = "type = {$type}";
	        	$conditions[] = "status = 1";
	        	if(isset($this->params['url']['AreaCode']))
	        	{
	        		$conditions[] = "substring(number::text from 0 for 4) = '{$this->params['url']['AreaCode']}'";
	        	}
	        	if(isset($this->params['url']['Contains']))
	        	{
	        		$conditions[] = "number like '%{$this->params['url']['Contains']}%'";
	        	}
	        	if(isset($this->params['url']['InRegion']))
	        	{
	        		$conditions[] = "state = '{$this->params['url']['InRegion']}'";
	        	}
	        	if(isset($this->params['url']['Lata']))
	        	{
	        		// TODO I don't understand what is the 'LATA'?
	        	}
	        	if(isset($this->params['url']['InRateCenter']))
	        	{
	        		$conditions[] = "rate_center = '{$this->params['url']['InRateCenter']}'";
	        	}
                        
                        if(isset($this->params['url']['limit']))
                        {
                                $limit = (int)$this->params['url']['limit'];
                        } else {
                                $limit = 0;   
                        }
                        
	        	if(isset($this->params['url']['offset']))
                        {
                                $offset = (int)$this->params['url']['offset'];
                        } else {
                                $offset = 0;   
                        }
                        
                        $arr = array(
	        		'conditions' => $conditions,		
	        	);
                        
                        $total = $this->Orig->find('count', $arr);
                        
                        if($offset != 0)
                        {
                            $arr['offset'] = $offset;
                        }
                        
                        if($limit != 0)
                        {
                            $arr['limit'] = $limit;
                        }
                        
	        	$data = $this->Orig->find('all', $arr);
	        	
	        	$format_data = $this->format($data, $total, $offset, $limit);
        	}
        	
        }
       
        
        $this->set('data', $format_data);
    }
    
    function format($data, $total, $offset, $limit)
    {
    	/*
    	 $data = array(
    	 		'AvailablePhoneNumbers' => array(
    	 				'uri' => '/C4-V3/Accounts/ACde6f1e11047ebd6fe7a55f120be3a900/AvailablePhoneNumbers/US/Local?AreaCode=510',
    	 				'AvailablePhoneNumber' => array(
    	 						array(
    	 								'FriendlyName' => array('110'),
    	 								'PhoneNumber'  => array('110'),
    	 						),
    	 						array(
    	 								'FriendlyName' => array('120'),
    	 								'PhoneNumber'  => array('120'),
    	 						),
    	 				)
    	 		),
    	 );
    	*/
    	$number_list = array();
    	
    	foreach($data as $item)
    	{
		$resource = $this->Gatewaygroup->findByResourceId($item['Orig']['ingress_id']);
                $billing_plan = $this->DidBillingPlan->findById($resource['Gatewaygroup']['billing_rule']); // TODO 有问题
    		array_push($number_list, array(
    			'friendly_name' => ($item['Orig']['number']),
    			'phone_number'  => ($item['Orig']['number']),
    			'lata' => ($item['Orig']['number']),
    			'rate_center' => ($item['Orig']['rate_center']),
    			'region' => ($item['Orig']['state']),
    			'iso_country' => ($item['Orig']['country']),
                        'did_price' => isset($billing_plan['DidBillingPlan']) ? $billing_plan['DidBillingPlan']['did_price'] : '',
                      //  'channel_price' => isset($billing_plan['DidBillingPlan']) ? $billing_plan['DidBillingPlan']['channel_price'] : '',
                        'min_price' => isset($billing_plan['DidBillingPlan']) ? $billing_plan['DidBilingPlan']['min_price'] : '',
                       // 'billed_channels' => isset($billing_plan['DidBillingPlan']) ? $billing_plan['DidBillingPlan']['billed_channels'] : '',
    		));
    	}
    	
    	$format_data = array();
    	$format_data['available_phone_numbers'] = array(
    		'uri' => $_GET['url'],
    		'available_phone_number' => $number_list,	
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
    	);
    	
    	return $format_data;
    }
    
    public function add()
    {
    	
        if(!isset($_POST['number']) || !isset($_POST['account_id']))
        {
            header('HTTP/1.1 404 Not Found'); 
            $response = array(
                array('status' => 404),
                array('message' => 'The requested number was not found'),
            );
            $this->set('data', $response);
            return;
        } 
        $number = $_POST['number'];
        $account_id = $_POST['account_id'];
        $egress_id = $this->Orig->get_egress_id($account_id);
        if($egress_id == NULL)
        {
            header('HTTP/1.1 401 Not Unauthorized'); 
            $response = array(
                array('status' => 401),
                array('message' => 'The account ID was not found'),
            );
            $this->set('data', $response);
            return;
        }
        
        if($this->Orig->is_already_buy_me($number, $egress_id))
        {
        	header('HTTP/1.1 405 Not Unauthorized');
        	$response = array(
        			array('status' => 405),
        			array('message' => 'You have already assigned it'),
        	);
        	$this->set('data', $response);
        	return;
        }
        
        if(!$this->Orig->is_exists_number($number))
        {
            header('HTTP/1.1 503 Not Unauthorized'); 
            $response = array(
                array('status' => 503),
                array('message' => 'The requested number was not available'),
            );
            $this->set('data', $response);
            return;
        }
        
        
        try
        {
            $product_id = $this->DidAssign->check_default_static();
            $item_id = $this->DidAssign->add_new_number($number, $product_id);
            $this->DidAssign->add_new_resouce($item_id, $egress_id);
            $this->DidAssign->add_assign($number, $egress_id);
        } 
        catch (Exception $e)
        {
            $dataSource->rollback();
            header('HTTP/1.1 401 Not Unauthorized'); 
            $response = array(
                array('status' => 401),
                array('message' => 'The account ID was not found'),
            );
            $this->set('data', $response);
            return;
        }
        
        
        header('HTTP/1.1 200 OK'); 
        $response = array(
            array('status' => 200,'phone_number' => $number),
        );
        $this->set('data', $response);
    }
    
    
}
