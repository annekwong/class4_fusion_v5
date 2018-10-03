<?php
class AppOrderHelper extends AppHelper {

	var $helpers = array("time",'Session','number','form','xform');
	function format_time($time){
		return preg_replace('/\+(\d+)/',"(GMT+\$1)",$time);
	}
	function _genrate_button_options($options=array()){		
		return join( ' ',array_map(create_function('$k,$v','return "$k=\'$v\'";'),array_keys($options),array_values($options)));
	}
	
	function delete_button($options=array()){
		return "<a href=\"#\" ". $this->_genrate_button_options($options) ."><i class='icon-remove'></i></a>";
	}
	
	function format_resources_options($resources) {
		$returning = array ();
		foreach ( $resources as $resource ) {
			$r = isset ( $resource ['Resource'] ) ? $resource ['Resource'] : $resource [0];
			$returning [$r ['resource_id']] = $r ['alias'];
		}
		return $returning;
	}
	
	function format_country_options($countries){
		$returning = array ();
		foreach ( $countries as $country ) {
			$r = isset ( $country ['Code'] ) ? $country ['Code'] : $country [0];
			$returning [$r ['country']] = $r ['country'];
		}
		return $returning;
	}
	
	function format_code_name_options($code_names){
		$returning = array ();
		foreach ( $code_names as $code_name ) {
			$r = isset ( $code_name ['Code'] ) ? $code_name ['Code'] : $code_name [0];
			$returning [$r ['name']] = $r ['name'];
		}
		return $returning;
	}
	
	function format_code_4_options($codes){
		$returning = array ();
		foreach ( $codes as $code ) {
			$r = isset ( $code ['Code'] ) ? $code ['Code'] : $code [0];
			$returning [$r ['code']] = $r ['code'];
		}
		return $returning;
	}
	
	function format_client_options($clients){
		$returning = array ();
		foreach ( $clients as $client ) {
			$r = isset ( $client ['Client'] ) ? $client ['Client'] : $client [0];
			$returning [$r ['client_id']] = $r ['name'];
		}
		return $returning;
	}

	function region_checked_by_order_code($region,$order_codes){
		foreach ($order_codes as $order_code){
			$order_code	= isset ( $order_code ['OrderCode'] ) ? $order_code ['OrderCode'] : $order_code [0];
			if(!empty($order_code['code_name']) && $order_code['code_name'] == $region ){
				return true;
			}
		}
		return false;
	}
	
	function code_checked_by_order_code($code,$order_codes){
		$code = isset ( $code ['Code'] ) ? $code ['Code'] : $code [0];
		foreach ($order_codes as $order_code){
			$order_code	= isset ( $order_code ['OrderCode'] ) ? $order_code ['OrderCode'] : $order_code [0];
			if(!empty($order_code['code_id']) && $order_code['code_id'] == $code['code_id'] ){
				return true;
			}
		}
		return false;
	}
	
	function format_order_commit_minutes($order,$prefix=0){
		return array_keys_value($order,"$prefix.is_commit") ? $this->number->format(array_keys_value($order,"$prefix.commit_minutes")) : '-';
	}
	
	function format_order_expire_time($order,$prefix=0){		
		return array_keys_value($order,"$prefix.is_commit") ? array_keys_value($order,$prefix.".expire_time") : '-';
	}
	
	function format_code_options ($codes) {
		$returning = array ();
		foreach ( $codes as $code ) {
			$r = isset ( $code ['Code'] ) ? $code ['Code'] : $code [0];
			$returning [$r ['code_id']] = $r ['code'];
		}
		return $returning;
	}
	
	function format_order_code_options($codes) {
		$returning = array ();
		foreach ( $codes as $code ) {
			$r = isset ( $code ['OrderCode'] ) ? $code ['OrderCode'] : $code [0];
			$returning [$r ['id']] = $r ['code'];
		}
		return $returning;
	}	
	
	function format_price($price) {
		if (empty ( $price )) {
			return '';
		} else {
			return sprintf ( "%.5f", $price );
		}
	}
	
	function format_radio($array, $value, $default = '') {
		if (isset ( $array [$value] )) {
			return $array [$value];
		} else {
			return $default;
		}
	}
	
	function order_status($status) {
		$array = array(Order::STATUS_READY => "Ready",Order::STATUS_CONTRACT=>"Confirm",Order::STATUS_EXPIRED=> "Expired",Order::STATUS_SOLDOUT => "Soldout",Order::STATUS_HOLD => "Hold");
		return isset($array[$status]) ? $array[$status] : ''; 
	}
	
	
	function format_wdatepicker_time($time){
		if(!empty( $time )) {
			$time = $this->time->fromString($time);
			return date ( 'Y-m-d H:00:00', $time );
		} else {
			return '';
		}	
	}
	
	function show_order_list_col_show($col_name,$default_show = true){
		$v =  $this->Session->read("order.browsers.list.$col_name");
		return $v === null ?  $default_show : $v;
	}
	
	function order_list_col($col_name,$content,$default_show = true,$options=Array()){	
		$options=array_merge(Array('rel'=>''),$options);		
			$display = $this->show_order_list_col_show($col_name,$default_show) ? '' : 'style="display:none"';
			return "<td rel=\"order_list_col_$col_name\" $display {$options['rel']}>$content</td>";
	}
	
	function filter_country($countries = array()){
		if(empty($countries)){
			$countries = ClassRegistry::init('Code')->find_countries();
		}		
		return $this->form->input("filter.country",array('options' => $this->format_country_options($countries),'class'=>'in-text','label'=>false,'div'=>false,'type' => "select",'name' => 'data[filter][country]','empty' => array('' => "Filter Country"),'length' => 12));
	}
	
	function filter_code_name($code_names = array()){
		if(empty($code_names)){
			$code_names = ClassRegistry::init('Code')->find_codenames();
		}		
		return $this->form->input("filter.name",array('options' => $this->format_code_name_options($code_names),'class'=>'in-text','label'=>false,'div'=>false,'type' => "select",'name' => 'data[filter][name]','empty' => array('' => "Filter Code Name"),'length' => 12));
	}
	
	function filter_code($codes = array()){
		if(empty($codes)){
			$codes = ClassRegistry::init('Code')->find_codes();
		}		
		return $this->form->input("filter.code",array('options' => $this->format_code_options($codes),'class'=>'in-text','label'=>false,'div'=>false,'type' => "select",'name' => 'data[filter][code]','empty' => array('' => "Filter Code"),'length' => 12));
	}
	
	function filter_client($clients = array()){
		if(empty($clients)){
			$clients = ClassRegistry::init('Code')->find_order_users();
		}		
		return $this->form->input("filter.client",array('options' => $this->format_client_options($clients),'class'=>'in-text','label'=>false,'div'=>false,'type' => "select",'name' => 'data[filter][client_id]','empty' => array('' => "Filter Client"),'length' => 12));
	}
	
	function filter_acd(){
//		array(1=>"<10s",2=>"10s-20s",3=>"20s-30s",4=>'30s-40s',5=>'40s-50s','custom'=>'custom') 
		$select = $this->form->input("filter.acd",array('options' => array(1=>"<1min",2=>"1-2min",3=>"2-3min",4=>'3-4min',5=>'4-5min',6=>'5-6min',7=>'6-7min',8=>'7-8min',9=>'8-9min',10=>'>9min'), 
			'label'=>false,'div'=>false,'type' => "select",'name' => 'acd','empty' => array('' => "ACD Range"),'style'=>'width:100px'));
		$min_acd = $this->form->input("filter.min_acd",array('name'=>'min_acd','label'=>false,'div'=>false,'style'=>"width:30px",'maxlength'=>"2" ));
		$max_acd = $this->form->input("filter.max_acd",array('name'=>'max_acd','label'=>false,'div'=>false,'style'=>"width:30px",'maxlength'=>"2" ));		
		if(isset($this->data) && !empty($this->data) && array_keys_value($this->data,"filter.acd") === 'custom'){
			$custom =  '<div style="display: inline-block;width:70px;" id="filter_acd_div">' . $min_acd . '&nbsp;-&nbsp;' . $max_acd . '</div>';
		}else{
			$custom =  '<div style="display: none;width:70px;" id="filter_acd_div">' . $min_acd . '&nbsp;-&nbsp;' . $max_acd . '</div>';
		}
		return $select .'&nbsp;' . $custom;
	}
	
	function filter_asr(){
		$select = $this->form->input("filter.asr",array('options' => array(1=>"<10%",2=>"10-20%",3=>"20-30%",4=>'30-40%',5=>'40-50%',6=>'50-60%',7=>'60-70%',8=>'70-80%',9=>'80-90%',10=>'>90%'), 
			'label'=>false,'div'=>false,'type' => "select",'name' => 'asr','empty' => array('' => "ASR Range"),'style'=>'width:100px'));
		$min_asr = $this->form->input("filter.min_asr",array('name'=>'min_asr','label'=>false,'div'=>false,'style'=>"width:30px",'maxlength'=>"2" ));
		$max_asr = $this->form->input("filter.max_asr",array('name'=>'max_asr','label'=>false,'div'=>false,'style'=>"width:30px",'maxlength'=>"2" ));		
		if(isset($this->data) && !empty($this->data) && array_keys_value($this->data,"filter.asr") === 'custom'){
			$custom =  '<div style="display: inline-block;width:70px;" id="filter_asr_div">' . $min_asr . '&nbsp;-&nbsp;' . $max_asr . '</div>';
		}else{
			$custom =  '<div style="display: none;width:70px;" id="filter_asr_div">' . $min_asr . '&nbsp;-&nbsp;' . $max_asr . '</div>';
		}
		return $select ."&nbsp;". $custom;
	}
	
	function find_resource_acd_asr($resource_infos,$resource_id){
		if(empty($resource_id)){
			return null;
		}		
	 	$resource_info = array_filter($resource_infos,create_function('$d','return $d[0]["res_id"] = '.$resource_id.' ;'));
	 	if(!empty($resource_info)){
		 	reset($resource_info);
		 	return current($resource_info);
	 	}
	 	return null;
	}
function _base_path($action){
		$base_path = $this->webroot;
		if(!empty($this->params ['plugin'])){
			$base_path .= $this->params ['plugin'].'/';	
		}
		if(!empty($this->params ['controller'])){
			$base_path .= $this->params ['controller'].'/';	
		}
		$base_path .= $action;
		return $base_path;
	}
	
	function _request_string($filter=null){
		$params = $this->params['url']; 
	    unset($params['url']);     
	    unset($params['ext']);
	    if(!empty($filter)){
	    	unset($params[$filter]);
	    }
       return http_build_query($params);
	}
	
	function show_order($name,$content=null){
		if(empty($content)){
			$content = Inflector::humanize($name);
		}
		$base_href = $this->_base_path($this->params['action']);
		$request_string = $this->_request_string('order_by');		
		$desc_img = "{$this->webroot}images/p.png";
		$asc_img = "{$this->webroot}images/p.png";
		return <<<EOD
		
		<span>$content</span>
		<a class="sort_asc sort_sctive" href="{$base_href}?order_by={$name}-asc&{$request_string}"><img src="$asc_img" /></a>
		<a class="sort_dsc" href="{$base_href}?order_by={$name}-desc&{$request_string}"><img src="$desc_img" /></a>
EOD;
	}
	
}
