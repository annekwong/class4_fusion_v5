<?php
class DynamicRoutesController extends AppController{
	var $name = 'DynamicRoutes';
	var $helpers = array('javascript','html','AppDynamicRoute');
	var $uses = array('Client','Resource','DynamicRoute');
	
	
	//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
		if($login_type==1){
			$this->Session->write('executable',true);
			$this->Session->write('writable',true);
		}else{
			$limit = $this->Session->read('sst_route_DRPolicies');
			$this->Session->write('executable',$limit['executable']);
			$this->Session->write('writable',$limit['writable']);
		}
		
		parent::beforeFilter();
		
	}
	/**
	 * 编辑客户信息
	 */
	function edit() {
		if (! empty ( $this->params ['form'] )) {
			$flag = $this->Dynamicroute->saveOrUpdate ( $this->data, $_POST); //保存
			if (empty ( $flag )) {
				//有错误信息
				$this->set ( 'm', Dynamicroute::set_validator () ); //向界面设置验证信息
				$this->set ( 'post', $this->data);
				$this->init_info ();
				
			} else {
				//操作成功
				$this->Dynamicroute->create_json_array('',201,__('update_suc',true));
				$this->Session->write('m',Dynamicroute::set_validator());
//				$this->redirect ('/dynamicroutes/view?edit_id='.$this->params['form']['dynamic_route_id']  ); // succ
			}
		} else {
		
		}
			$this->set ( 'post', $this->Dynamicroute->find('first',array('conditions'=>'dynamic_route_id = '. $this->params ['pass'][0])));
			$this->set('res_dynamic',$this->Dynamicroute->findEgressbydynamic_id($this->params ['pass'][0]));
			$this->init_info ();
//		}
	}
	/**
	 * 添加客户信息
	 */
	function add() {
		if (!empty ( $this->params ['form'] )) {
			$flag = $this->Dynamicroute->saveOrUpdate ( $this->data, $_POST ); //保存
			if (empty ( $flag )) {
				$this->set ( 'm', Dynamicroute::set_validator () ); //向界面设置验证信息
				$this->set ( 'post', $this->data );
				$this->init_info ();
			} else {
				$this->Dynamicroute->create_json_array("",201,'Add  Success ');
				$this->Session->write('m',Dynamicroute::set_validator());
				$this->redirect ( array ('controller' => 'dynamicroutes', 'action' => 'view' ) ); // succ
			}
		} else {
			$this->init_info ();
		}
	}

	function del(){
		$name=$this->params['pass'][1];
		$this->Dynamicroute->del($this->params['pass'][0]);
		$this->Session->write('m',$this->Dynamicroute->create_json(201,'Delete Success'));
		$this->redirect (array ('action' => 'view' ) );
	}
	
	function index(){
		$this->_render_index_data();
		$this->_render_index_options();
	}
	function _render_index_order(){
		$this->paginate['order']=$this->_order_condtions(Array('dynamic_route_id','name','routing_rule'));
	} 
	function _render_index_fields(){
		//$this->paginate['fields']=Array('dynamic_route_id','name','routing_rule');
	}
	function _render_index_conditions(){
		$this->paginate['conditions']=$this->_filter_conditions(Array('routing_rule'));
	}
	function _render_index_bindModel(){
/*		$bindModel=Array();
		$bindModel['belongsTo']=Array();
		$bindModel['belongsTo']['ResourceIp1']=Array('className'=>'ResourceIp','foreignKey'=>'resource_id_1');
		$bindModel['belongsTo']['ResourceIp2']=Array('className'=>'ResourceIp','foreignKey'=>'resource_id_2');
		$bindModel['belongsTo']['ResourceIp3']=Array('className'=>'ResourceIp','foreignKey'=>'resource_id_3');
		$bindModel['belongsTo']['ResourceIp4']=Array('className'=>'ResourceIp','foreignKey'=>'resource_id_4');
		$bindModel['belongsTo']['ResourceIp5']=Array('className'=>'ResourceIp','foreignKey'=>'resource_id_5');
		$bindModel['belongsTo']['ResourceIp6']=Array('className'=>'ResourceIp','foreignKey'=>'resource_id_6');
		$bindModel['belongsTo']['ResourceIp7']=Array('className'=>'ResourceIp','foreignKey'=>'resource_id_7');
		$bindModel['belongsTo']['ResourceIp8']=Array('className'=>'ResourceIp','foreignKey'=>'resource_id_8');
		$this->DynamicRoute->bindModel($bindModel,false);*/
	}
	function _render_index_data(){
		$this->_render_index_bindModel();
		$this->_render_index_order();
		$this->_render_index_conditions();
		$this->_render_index_fields();
		$this->loadModel('ResourceIp');
		$bindModel=Array();
		$bindModel['belongsTo']=Array('Resource'=>Array('className'=>'Resource'));
		$this->ResourceIp->bindModel($bindModel,false);
		$this->data=$this->paginate('DynamicRoute');
		$ResourceIpList=$this->ResourceIp->find('all',Array('fields'=>Array('resource_id','ip','port','fqdn','capacity AS "ResourceIp__call"','cps_limit AS "ResourceIp__cps"')));
		$TempResrouceIpList=Array();
		foreach($ResourceIpList as $list){
			$TempResrouceIpList[$list['ResourceIp']['resource_id']]=$list['ResourceIp'];
		}
		for($i=0;$i<count($this->data);$i++){
			$this->data[$i]['ResourceOther']=Array();
			for($ii=1;$ii<9;$ii++){
				if($this->data[$i]['DynamicRoute']["resource_id_$ii"] && array_keys_value($TempResrouceIpList,$this->data[$i]['DynamicRoute']["resource_id_$ii"],false)){
					$this->data[$i]['ResourceOther'][]=array_keys_value($TempResrouceIpList,$this->data[$i]['DynamicRoute']["resource_id_$ii"],false);
				}
			}
		}
	}
	function _render_index_options(){
		$this->set('OptionsRouteingRule',$this->DynamicRoute->get_routeing_rule());
	}
	
	
	/**
	 * 禁用客户
	 */
	function dis_able(){
		 $id =$this->params['pass'][0];
		 $this->Client->dis_able($id);
		 	$this->redirect ( array ('action' => 'view' ) );

		 
		
	}
	
	function active(){
		 $id =$this->params['pass'][0];
		 $this->Client->active($id);
		 $this->redirect ( array ('action' => 'view' ) );
	}



}
	


?>
