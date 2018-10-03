<?php
App::import("Model",'Resource');
class Ingress extends Resource{
		var $default_schema = array(
//		'resource_id' => array (  'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => 11 ),
//		'name' => array (  'type' => 'string',  'null' => 1,  'default' => '' ,  'length' => 40, ),
//		'ingress' => array (  'type' => 'boolean',  'null' => 1,  'default' => '' ,  'length' => '' , ),
//		'egress' => array (  'type' => 'boolean',  'null' => 1,  'default' => '' ,  'length' => '' , ,
		'client_id' => array ( 'name' => 'Carrier Name', 'type' => 'integer',  'null' => '' ,  'default' => '' ,  'length' => '' , ),
		'alias' => array ( 'name' => 'Trunk Alias', 'type' => 'string',  'null' => '' ,  'default' => '' ,  'length' => 40, ),
//		'res_strategy' => array (  'name' => 'Host Strategy', 'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
		'media_type' => array (  'type' => 'integer',  'null' => '' ,  'default' => 0,  'length' => '' , ),
		'capacity' => array (  'type' => 'integer',  'null' => 1,  'default' => 0 ,  'length' => '' , ),
		'cps_limit' => array (  'type' => 'integer',  'null' => 1,  'default' => 0 ,  'length' => '' , ),
//		'rfc_2833' => array (  'type' => 'boolean',  'null' => '' ,  'default' => false,  'length' => '' , ),
		'proto' => array (  'type' => 'integer',  'null' => 1,  'default' => 0,  'length' => '' , ),
		'route_strategy_id' => array ( 'name' => 'Routing Plan Name', 'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
		'active' => array (  'type' => 'boolean',  'null' => '' ,  'default' => true,  'length' => '' , ),
		't38' => array ( 'name' => 'T.38', 'type' => 'boolean',  'null' => '' ,  'default' => false,  'length' => '' , ),
		'lnp' => array ( 'name' => 'LRN', 'type' => 'boolean',  'null' => '' ,  'default' => false,  'length' => '' , ),
		'lrn_block' => array ('name' => 'Block LRN',  'type' => 'boolean',  'null' => '' ,  'default' => false,  'length' => '' , ),
//		'rate_table_id' => array ( 'name' => 'Rate Table Name', 'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
		'codec' => array(),

//		'tdm' => array (  'type' => 'boolean',  'null' => '' ,  'default' => true,  'length' => '' , ),
//		'transnexus' => array (  'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
//		'pass_through' => array (  'type' => 'integer',  'null' => 1,  'default' => '' ,  'length' => '' , ),
//		'profit_margin' => array (  'type' => 'float',  'null' => 1,  'default' => '' ,  'length' => '' , ),
//		'enough_balance' => array (  'type' => 'boolean',  'null' => 1,  'default' => false,  'length' => '' , ),
//		'egress_bill_after_action' => array (  'type' => 'boolean',  'null' => 1,  'default' => true,  'length' => '' , ),
		'dnis_only' => array (  'type' => 'boolean',  'null' => '' ,  'default' => true,  'length' => '' , )
);
			var $validate = array(
			'client_id' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'Carrier name cannot be NULL!',
					'last' => true
					),
				'comparison' => array(
					'required' => true,
					'rule' => array('comparison', '>', 0),
					'message' => 'Carrier name not found.'
				)
			),
			'alias' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'Trunk Alias cannot be NULL!',
					'last' => true
					),
				'alphaNumeric' => array(
					'required' => true,
					'rule' => '/^[\w\-\_\s]+$/',
					'message' => 'Trunk Alias must contain numeric characters only.'
				)
			),
			'media_type' => array(
					'between' => array(
						'required' => true,
						'rule' => array('between', 0, 2),
						'message' => 'Proto can be either Proxy , Bypass or Transcode.'
					)
				),	
			'capacity' => array(
				'between' => array(
					'required' => true,
					'rule' => array('between', 0, 9999),
					'message' => 'Capacity must be between 0 to 9999'
				)
			),
			'cps_limit' => array(
				'between' => array(
					'required' => true,
					'rule' => array('between', 0, 999),
					'message' => 'CPS must be between 0 to 999'
				)
			),
			'proto' => array(
				'between' => array(
					'required' => true,
					'rule' => array('between', 0, 2),
					'message' => 'Proto can be either  SIP , H323 or ALL.'
				)
			),
			'route_strategy_id' => array(
				 'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'Routing Plan Name cannot be NULL!',
					'last' => true
					)
				),
			
//			'rate_table_id'=> array(
//				 'blank' => array(
//					'required' => true,
//					'rule' => 'notEmpty',
//					'message' => 'Rate Table Name cannot be NULL!',
//					'last' => true
//					)
//				),
			);
}
?>