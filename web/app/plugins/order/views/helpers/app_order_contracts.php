<?php
App::import('Helper','Order.AppOrder');
class AppOrderContractsHelper extends AppOrderHelper{
	
	function format_order_commit_minutes($contract){
		return array_keys_value($contract,"0.contract_is_commit") ? $this->number->format(array_keys_value($contract,"0.contract_commit_minutes")) : '-';
	}
	function format_order_expire_time($contract){		
		return array_keys_value($contract,"0.contract_is_commit") ? array_keys_value($contract,"0.contract_expire_time") : '-';
	}
	
	function order_status($status) {
		$array = array(Contract::CONTRACT_STATUS_CONTRACT => "Confirm",Contract::CONTRACT_STATUS_EXPIRED => "Expired",
		Contract::CONTRACT_STATUS_HOLD_BY_CREATOR=>"Hold",Contract::CONTRACT_STATUS_HOLD_BY_FOLLOW => "Hold",Contract::CONTRACT_STATUS_HOLD_FOR_ORDER_CHANGE =>"Hold   ");
		return isset($array[$status]) ? $array[$status] : ''; 
	}
	
	function type($contract){
		$contract_type = array_keys_value($contract,"0.contract_type");
		$contract_client_id = array_keys_value($contract,"0.contract_client_id");
		$client_id = array_keys_value($contract,"0.client_id");
		if($contract_client_id == $_SESSION['sst_client_id']){
			return 'Select';
		}else{
			return 'Place';
		}
//		if((int)$contract_type == Contract::CONTRACT_TYPE_BUY){
//			if($contract_client_id == $_SESSION['sst_client_id']){
//				return "Sell";
//			}else{
//				return "Buy";
//			}
//		}
//		if((int)$contract_type == Contract::CONTRACT_TYPE_SELL){
//			if($contract_client_id == $_SESSION['sst_client_id']){
//				return "Buy";				
//			}else{
//				return "Sell";				
//			}
//		}
	}
	
	function contract_status_operation($contract,$t){
		$opts  = array(			
			array('action' => "hold" , 'label' => "Hold",'img' => "images/no.png"),
			array('action' => "contract" , 'label' => "Confirm",'img' => "images/status_closed.gif"),
			array('action' => "delete" , 'label' => "Delete",'img' => "images/del.gif"),						
		);		
		$m = new Contract();
		$returning = array();
		foreach($opts as $opt){
			$method = "is_ready_to_{$opt['action']}";
			if($m->{$method}($contract)){
				$returning[] = $this->_contract_status_operation_button($contract,$opt,$t);
			}
		}
		return join("\n",$returning);
	}
	
	function _contract_status_operation_button($contract,$opt,$t){
		$contract = array_keys_value($contract,"0");
		$contract['action'] = $opt['action'];
		$contract['buy_or_sell'] = $t;
		
		$onclick = "return App.Common.updateDivByAjax(\"".Router::url(array('plugin' => "order",'controller'=>"order_contracts",'action'=>"ajax_contract_operation"))."\",\"#{$t}_".array_keys_value($contract,"contract_id")."\",".json_encode($contract).",{\"confirm\":\"Are you sure?\"})";
		$img = array_keys_value($opt,"img");
		if(empty($img)){
			return "<a href=\"#\" onclick='$onclick'>{$opt['label']}</a>" ;
		}else{
			return "<a href=\"#\" onclick='$onclick'><img src=\"{$this->webroot}{$opt['img']}\">{$opt['label']}</a>" ;
		}
	}
}
