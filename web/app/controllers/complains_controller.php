<?php

/**
 * 投诉建议
 * @author root
 *
 */
class ComplainsController extends AppController{
	
	var $name = 'Complains';
	var $helpers = array('javascript','html');
	

	//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_sys_complain');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();//调用父类方法
	}
	/**
	 * 编辑客户信息
	 */
	function edit() {
		if (! empty ( $this->data ['Complain'] )) {
	  $this->data['Complain']['user_id']=$this->Session->read('sst_user_id');
				   $this->data['Complain']['reseller_id']=$this->Session->read('sst_reseller_id');
				  $this->data['Complain']['modify_time']=date ( "Y-m-d   H:i:s" );
				  $this->data['Complain']['status']=1;
        if ($this->Complain->save ($this->data)){
        		$this->Complain->create_json_array('',201,__('update_suc',true));
        } else {
        	$this->Complain->create_json_array('',101,__('update_fail',true));
        }
        $this->Session->write('m',Complain::set_validator());
				  $this->redirect("/complains/view");

		} else {
	 $this->Complain->complain_id = $this->params ['pass'][0];
			$this->set('post', $this->Complain->read());
		

		
		}
	}
	
	
	

	
	
	/**
	 * 添加
	 */
	function add() {

			if (! empty ($this->data ['Complain'] )) {
			//	pr($_POST);
				//extract($_POST);
			//	pr($data);
				  $this->data['Complain']['create_time']=date ( "Y-m-d   H:i:s" );
				  $this->data['Complain']['status']=1;
				  $login_type = $this->Session->read('login_type');
				  if ($login_type == 2) {
				  	$this->data['Complain']['user_type'] = 1;
				  	$this->data['Complain']['user_id'] = $this->Session->read('sst_reseller_id');
				  	} else if ($login_type == 3) {
				  		$this->data['Complain']['user_type'] = 2;
				  		$this->data['Complain']['user_id'] = $this->Session->read('sst_client_id');
				  		} else {
				  			$this->data['Complain']['user_type'] = 3;
				  			$this->data['Complain']['user_id'] = $this->Session->read('sst_card_id');
				  		}
       if ($this->Complain->save ($this->data)){//保存
        	$this->Complain->create_json_array('',201,__('add_suc',true));
       } else {
        	$this->Complain->create_json_array('',101,__('add_fail',true));	
        			} 
       $this->Session->write('m',Complain::set_validator());
				 $this->redirect("/complains/view");
			
		} else {
			
		}
    
	
	}

	function del(){
		$id=$this->params['pass'][0];
		
		if ($id == 'all') {
			$id = "select complain_id from complain";
		}
		
		if ($id == 'selected'){
			$id = $_REQUEST['ids'];
		}
		if ($this->Complain->del($id)){
			$this->Complain->create_json_array('',201,__('delcomplainsucc',true));	
		} else {
			$this->Complain->create_json_array('',101,__('del_fail',true));	
		}
		$this->Session->write('m',Complain::set_validator());
		$this->redirect("/complains/view");
	}
	

	function close(){
		$id=$this->params['pass'][0];
				$this->Complain->query("update  complain set status=2 where complain_id=$id");
		$this->Session->write('m',$this->Complain->create_json(101,__('closedcomplainsucc',true)));
		$this->redirect("/complains/view");
		
	}

	
	
	public function view(){
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
			
			$adv_search = '';
			$last_conditons = '&advsearch=1';
			if (!empty($this->params['form']) || !empty($_REQUEST['advsearch'])) {
				$f = empty($this->params['form'])?$_REQUEST:$this->params['form'];
				$this->set('searchform',$f);
				if (!empty($f['search_title'])) {
					$adv_search .= " and title like '%{$f['search_title']}%'";
					$last_conditons .= "&search_title={$f['search_title']}";
				}
				
				if (!empty($f['search_submittime_s'])) {
					$adv_search .= " and create_time >= '{$f['search_submittime_s']}'";
					$last_conditons .= "&search_submittime_s={$f['search_submittime_s']}";
				}
				
				if (!empty($f['search_submittime_e'])) {
					$adv_search .= " and create_time <= '{$f['search_submittime_e']}'";
					$last_conditons .= "&search_submittime_e={$f['search_submittime_e']}";
				}
				
				if (!empty($f['search_modifytime_s'])) {
					$adv_search .= " and modify_time >= '{$f['search_modifytime_s']}'";
					$last_conditons .= "&search_modifytime_s={$f['search_modifytime_s']}";
				}
				
				if (!empty($f['search_modifytime_e'])) {
					$adv_search .= " and modify_time <= '{$f['search_modifytime_e']}'";
					$last_conditons .= "&search_modifytime_e={$f['search_modifytime_e']}";
				}
				
				if (!empty($f['search_status'])) {
					$adv_search .= " and status = {$f['search_status']}";
					$last_conditons .= "&search_status={$f['search_status']}";
				}
				
				if (!empty($f['res_name'])) {
					$us_type = $f['us_type'];
					if ($us_type == 'res') {
						$adv_search .= " and user_id = (select reseller_id from reseller where name = '{$f['res_name']}')";
					}
					
					if ($us_type == 'cli') {
						$adv_search .= " and user_id = (select client_id from client where name = '{$f['res_name']}')";
					}
					
					if ($us_type == 'acc') {
						$adv_search .= " and user_id = (select card_id from card where card_number = '{$f['res_name']}')";
					}
					
					$last_conditons .= "&us_type={$f['us_type']}&res_name={$f['res_name']}";
				}
			}
			
				$results = $this->Complain->findAll($currPage,$pageSize,$adv_search);
				$this->set('last_conditons',$last_conditons);
		

	

		$this->set('p',$results);
			
	}
	
	



}
	


?>
