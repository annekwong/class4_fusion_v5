<?php
class RateanalyzesController extends AppController{
	var $name = 'Rateanalyzes';
	var $helpers = array('html','javascript','App');
	var $uses=Array('Rate','Rateanalyze');
	
	
	public function beforeFilter(){
			$this->checkSession ( "login_type" );//核查用户身份
	   parent::beforeFilter();//调用父类方法
	}
	/*
	 * $condition = '';
			$last_conditions = '';
			$rate_tables =  $_REQUEST['ids'];
			$ismatchall = $_REQUEST['ismatchall'];
			if (!empty($_REQUEST['code'])) {
				$code = $_REQUEST['code'];
				$flag = $ismatchall=='true'?" = '$code'":" <@ '$code' or code @> '$code' ";
				$condition .= " and (code $flag)";
				$last_conditions .= "&code=$code";
			}
			if (!empty($_REQUEST['st'])){
				$condition .= " and effective_date >= '{$_REQUEST['st']}'";
				$last_conditions .= "&st={$_REQUEST['st']}";
			}
			if (!empty($_REQUEST['et'])){
				$condition .= " and end_date <= '{$_REQUEST['et']}'";
				$last_conditions .= "&et={$_REQUEST['et']}";
			}
			$last_conditions .= "&ids=$rate_tables&search=true";
			$this->set('last_conditons',$last_conditions);
			$this->set('search','true');
			$currPage = 1;
			$pageSize = 100;
			if (! empty ( $_REQUEST ['page'] )) {
				$currPage = $_REQUEST ['page'];
			}
			if (! empty ( $_REQUEST ['size'] )) {
				$pageSize = $_REQUEST ['size'];
			}
			$results = $this->Rateanalyze->match_rate ( $currPage, $pageSize,$reseller_id,$condition,$rate_tables);
			$this->set('hasData',count($results->getDataArray()));
			$this->set ( 'p', $results );
	 */
	function _render_lcr_table_impl(){
		
	}
	function _render_rate_comparison_impl(){
		
	}
	function _render_rate_analyzes_rate_impl(){
		$this->loadModel('Rate');
		$this->Rate->bindModel(Array('hasMany'=>Array('RateTable')));
		$RateList=$this->Rate->find('all',Array('conditions'=>Array("Rate.rate_table_id='{$this->data['rate_table_id']}'")));
		$this->set('RateList',$RateList);
	}
	function _render_rate_analyzes_code_impl(){
		$this->loadModel('Code');
		$conditions=Array();
		if(array_keys_value($this->data,'code_deck_id')){
			$conditions[]="Code.code_deck_id='{$this->data['code_deck_id']}'";
		}
		if(array_keys_value($this->data,'country')){
			$conditions[]="Code.country='{$this->data['country']}'";
		}
		if(array_keys_value($this->data,'code_name')){
			$conditions[]="Code.name='{$this->data['code_name']}'";
		}
		if(array_keys_value($this->data,'code')){
			$conditions[]="Code.code='{$this->data['code']}'";
		}
		$this->paginate['conditions']=$conditions;
		$CodeList=$this->paginate('Code');
		$this->set('CodeList',$CodeList);
	}
	function _render_rate_analyzes_impl(){
		$this->_render_rate_analyzes_rate_impl();//查询所有符合要求的费率
		$this->_render_rate_analyzes_code_impl();//查询所有符合要求的号码
	}
	public function rate_analyzes(){
		$this->pageTitle="Tools/Rates Analysis";
		$reseller_id = $this->Session->read('ssh_reseller_id');
		if($this->RequestHandler->isPost()){
			$this->_render_rate_analyzes_impl();//执行两种模式公用的代码
			if($this->data['report_type']==1){
				$this->_render_lcr_table_impl();//执行LCR模式的代码
			}elseif($this->data['report_type']==2){
				$this->_render_rate_comparison_impl();//执行comparison模式的代码
			}
		}
		$this->_render_set_options('Rate.Codedeck.Currency.Resource',Array('Resource'=>Array('ingress = true')));
	}
}