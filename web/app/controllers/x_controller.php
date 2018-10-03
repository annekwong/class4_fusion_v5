<?php
class XController extends AppController {
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
		if($login_type==1){
			$this->Session->write('executable',true);
			$this->Session->write('writable',true);
		}else{
				$limit = $this->Session->read('sst_tools_outboundTest');
				$this->Session->write('executable',$limit['executable']);
				$this->Session->write('writable',$limit['writable']);
		}
		parent::beforeFilter();
	}
	function _render_set_options($models,$options=Array()){
		if(is_string($models)){
			$models=split('[\.\,]',$models);
		}
		foreach($models as $model){
			$model_options=$options;
			if(array_keys_value($options,$model)){
				$model_options=array_merge($model_options,$options[$model]);
			}
			$this->_render_set_option($model,$model_options);
		}
	}
	function _render_set_option($model,$options=Array()){
		$this->set($model.'List',$this->_render_option($model,$options));
	}
	function _render_option($model,$options){
		$this->loadModel($model);
		$conditions=array_keys_value($options,'conditions',Array());
		$order=array_keys_value($options,'order');
		if(array_keys_value($options,'limit')!=null){
			$this->paginate[$model]['limit']=2000;
			$this->paginate[$model]['order']=$order;
			$this->paginate[$model]['conditions']=$conditions;
			return $this->paginate($model);
		}else{
			return $this->$model->find('all',Array('conditions'=>$conditions,'order'=>$order));
		}
	}
}
?>