<?php
class OrderAppController extends AppController {
	var $layout = "default";
	var $components = array('RequestHandler','PhpCommon');
	var $uses = array("Order.BuyOrder","Order.SellOrder","Order.BuyOrderResponse","Order.SellOrderResponse","Order.BuyOrder",'Order.Contract','Order.OrderCode','Client','Resource','Codedeck','Code');
	var $helpers = array("Number","Time",'Order.AppOrder');
	
	const TEST_CLIENT_ID = 20;

	function beforeFilter() {
  $this->filter_cariier_url();
		

		
	}
	
	function _empty_redirect($type){
		if($type!=3){$this->redirect_denied();}
	
	}
	
	#only  carrier access url
		function filter_cariier_url(){
		$type=$_SESSION['login_type'];
		if($this->params['plugin']=='order'&&$this->params['controller']=='order_contracts'&&$this->params['action']=='buy')
		{$this->_empty_redirect($type);}
		if($this->params['plugin']=='order'&&$this->params['controller']=='order_contracts'&&$this->params['action']=='sell')
		{$this->_empty_redirect($type);}
			if($this->params['plugin']=='order'&&$this->params['controller']=='order_manages'&&$this->params['action']=='sell')
		{$this->_empty_redirect($type);}
		
		if($this->params['plugin']=='order'&&$this->params['controller']=='order_places')
		{$this->_empty_redirect($type);}
	}
	
	
	function ajax_def_col(){
		$this->autoRender = false;
		$col = $this->params['url']['col_name'];
		$val = $this->params['url']['value'] === 'true' ? true : false;
		if(!empty($col)){
			$this->Session->write("order.browsers.list.$col",$val);
		}		
	}
	function _filter_acd($table){
		$acd = (string)array_keys_value($this->params,"url.acd");
		$acd = trim($acd);
		if(!empty($acd)){
			if($acd === 'custom'){
				$min_acd = (int)array_keys_value($this->data,"filter.min_acd");
				$max_acd = (int)array_keys_value($this->data,"filter.max_acd");
				if($min_acd > $max_acd){
					$t = $max_acd;
					$max_acd = $min_acd;
					$min_acd = $t;
					$this->data['filter']['min_acd'] = $min_acd;
					$this->data['filter']['max_acd'] = $max_acd;
				}
				$returning = array();
				if($min_acd > 0){
					$returning[] = "$table.acd >= $min_acd";
				}
				if($max_acd > 0){
					$returning[] = "$table.acd < $max_acd";
				}
				if(!empty($returning)){
					return "(".join(" AND ",$returning).")";
				}
			}else{
//				array(1=>"<10s",2=>"10s-20s",3=>"20s-30s",4=>'30s-40s',5=>'40s-50s');
				$v = array(1=>array(0,1) ,2=>array(1,2),3=>array(2,3),4=>array(3,4),5=>array(4,5),6=>array(5,6),7=>array(6,7),8=>array(7,8),9=>array(8,9),10=>array(9,100));
				if(array_keys_value($v,'(int)$acd')){
					$v = $v[(int)$acd];
					return "($table.acd >= " . $v[0] . " AND  $table.acd < ".$v[1].")";
				}
			}
		}
		return null;
	}

	function _filter_asr($table){
		$asr = (string)array_keys_value($this->params,"url.asr");
		$asr = trim($asr);
		if(!empty($asr)){
			if($asr === 'custom'){
				$min_asr = (int)array_keys_value($this->data,"filter.min_asr");
				$max_asr = (int)array_keys_value($this->data,"filter.max_asr");
				if($min_asr > $max_asr){
					$t = $max_asr;
					$max_asr = $min_asr;
					$min_asr = $t;
					$this->data['filter']['min_asr'] = $min_asr;
					$this->data['filter']['max_asr'] = $max_asr;
				}
				$returning = array();
				if($min_asr > 0){
					$returning[] = "$table.asr >= $min_asr";
				}
				if($max_asr > 0){
					$returning[] = "$table.asr < $max_asr";
				}
				if(!empty($returning)){
					return "(".join(" AND ",$returning).")";
				}
			}else{
//				array('options' => array(1=>"<10",2=>"10-20",3=>"20-30",4=>'30-40',5=>'40-50',6=>'50-60',7=>'60-70',8=>'70-80',9=>'80-90',10=>'>90')
				$v = array(1=>array(0,10) ,2=>array(10,20),3=>array(20,30),4=>array(30,40),5=>array(40,50),6=>array(50,60),7=>array(60,70),8=>array(70,80),9=>array(80,90),10=>array(90,1000));
				if(array_keys_value($v,(int)$asr)){
					$v = $v[(int)$asr];
					return "($table.asr >= " . $v[0] . " AND  $table.asr <  ". $v[1].")";
				}
			}
		}
		return null;
	}
}
