<?php 

class OrigController extends AppController
{
    public $name = "Orig";
    public $uses = array('Orig');
    public $components = array('RequestHandler');
    public $helper = array('Xml','JavaScript');
    public $TYPE_LIST = array('Local' => 1, 'TollFree' => 2);
    
    
    function beforeFilter()
    {
        Configure::write('debug', 0);
        return true;
    }
    
    
    public function index($account_id, $country_code, $type)
    {
    	$format_data = array();
        if(strlen($account_id) == 32)
        {
        	$ingress_id = $this->Orig->get_ingress_id($account_id);
        	if($ingress_id != NULL)
        	{
	        	$conditions = array();
	        	$type = $this->TYPE_LIST[$type];
	        	$conditions[] = "ingress_id = {$ingress_id}";
	        	$conditions[] = "country = '{$country_code}'";
	        	$conditions[] = "type = {$type}";
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
    		array_push($number_list, array(
    			'friendly_name' => array('110'),
    			'phone_number'  => array($item['Orig']['number']),
    			'lata' => array($item['Orig']['number']),
    			'rate_center' => array($item['Orig']['rate_center']),
    			'region' => array($item['Orig']['state']),
    			'iso_country' => array($item['Orig']['country']),
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
    
    
}