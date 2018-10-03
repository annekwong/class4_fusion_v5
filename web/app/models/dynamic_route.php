<?php
class DynamicRoute extends AppModel{
	var $name = 'DynamicRoute';
	var $useTable = "dynamic_route";
	var $primaryKey = "dynamic_route_id";
	
	CONST DYNAMIC_ROUTE_ROUTING_RULE_1=4;
	CONST DYNAMIC_ROUTE_ROUTING_RULE_2=5;
	CONST DYNAMIC_ROUTE_ROUTING_RULE_3=6;
	function get_routeing_rule(){
		return Array(
			DynamicRoute::DYNAMIC_ROUTE_ROUTING_RULE_1=>__('routerule1',true),
			DynamicRoute::DYNAMIC_ROUTE_ROUTING_RULE_2=>__('routerule2',true),
			DynamicRoute::DYNAMIC_ROUTE_ROUTING_RULE_3=>__('routerule3',true)
		);
	}
}