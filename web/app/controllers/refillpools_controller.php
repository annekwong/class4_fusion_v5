<?php
class RefillpoolsController extends AppController{
	var $name = 'Refillpools';
	var $helper = array('html','javascript');
	
	
	//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_retail_refillcardseries');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
	/*
	 * 池列表
	 */
	
	
public function 	pools_list(){
		$this->set('p',$this->Refillpool->getAllSeries ());
	
}
	
	public function pools_list_tmp(){
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		if (!empty($_REQUEST['search'])) {
			$search = $_REQUEST['search'];
			$this->set('search',$search);
		}
		
		if (!empty($_REQUEST['edit_id'])){
				$sql = "select credit_card_series_id,name,value,password_length,
							(select count(credit_card_id) from credit_card where credit_card_series_id = c.credit_card_series_id) as cards,
							prefix,start_num from credit_card_series as c where credit_card_series_id = {$_REQUEST['edit_id']}
	  		";
			$result = $this->Refillpool->query ( $sql );
			//分页信息
				require_once 'MyPage.php';
				$results = new MyPage ();
				$results->setTotalRecords ( 1 ); //总记录数
				$results->setCurrPage ( 1 ); //当前页
				$results->setPageSize ( 1 ); //页大小
				$results->setDataArray ( $result );
			$this->set('edit_return',true);
			} else {
		
			$reseller_id = $this->Session->read('sst_reseller_id');
			$results = $this->Refillpool->getAllSeries ( $currPage, $pageSize,$search,$reseller_id);
			}
		
		$this->set ( 'p', $results );
	}
	
	/*
	 * 添加帐号池的数据验证
	 */
	public function validate_series($f,$needcheckname,$url){
		$name = $f['name'];
		$hasError = false;
		if (empty($name)) {
			$hasError = true;
			$this->Refillpool->create_json_array('#name',101,__('refillseriesnamenull',true));
		} else {
			if (!preg_match('/^[\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff_]+$/',$name)) {
				$hasError = true;
				$this->Refillpool->create_json_array('#name',101,__('refillseriesnameformat',true));	
			}
		}
		
		
		if ($needcheckname == true) {
			$names = $this->Refillpool->query("select credit_card_series_id from credit_card_series where name = '$name'");
			if (count($names) > 0) {
				$hasError = true;
				$this->Refillpool->create_json_array('#name',101,__('refillseriesnameexists',true));
			}
		}
		
		
		$value = $f['value'];
		if (empty($value)) {
			$hasError = true;
			$this->Refillpool->create_json_array('#value',101,__('amountnull',true));
		} else {
			if (!preg_match('/^[0-9]+(\.[0-9]{1,3})?$/',$value)) {
				$hasError = true;
				$this->Refillpool->create_json_array('#value',101,__('amountformat',true));
			}
		}
		
		$expire_days = $f['expire_date'];
		if (empty($expire_days)) {
			$hasError = true;
			$this->Refillpool->create_json_array('#expire_date',101,__('expiredaysnull',true));
		} 
		
		$start_num = $f['start_num'];
		if (empty($start_num)) {
			$hasError = true;
			$this->Refillpool->create_json_array('#start_num',101,__('startnumnull',true));
		} else {
			if (!preg_match('/^\d{6}$/',$start_num)) {
				$hasError = true;
			$this->Refillpool->create_json_array('#start_num',101,__('startnumlen',true));
			}
		}
		
		$password_length = $f['password_length'];
		if (empty($password_length)) {
			$hasError = true;
			$this->Refillpool->create_json_array('#password_length',101,__('passlennull',true));
		} else {
			if (!preg_match('/^\d+$/',$password_length) || $password_length > 12) {
				$hasError = true;
				$this->Refillpool->create_json_array('#password_length',101,__('passlenformat',true));
			}
		}
		
		if ($hasError == true) {
			$this->Session->write('backform',$f);
			$this->Session->write('m',Refillpool::set_validator());
			$this->redirect($url);
		}
	}
	
	public function add_card_series(){
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			$this->validate_series($f,true,'/refillpools/add_card_series');
			
			$rs = $this->Session->read('sst_reseller_id');
			if (!empty($rs)) {
				$f['reseller_id'] = $this->Session->read('sst_reseller_id');
			}
			
			if ($this->Refillpool->save($f)) {
				$this->Refillpool->create_json_array('',201,__('createrefillsucc',true));
			} else {
				$this->Refillpool->create_json_array('',101,__('createrefillfail',true));
			}
			
			$this->Session->write('m',Refillpool::set_validator());
			$this->redirect('/refillpools/pools_list');
		} else {
			$reseller_id = $this->Session->read('sst_reseller_id');
			$this->set('rates',$this->Refillpool->getRates($reseller_id));
			$this->set('prefix',$this->Refillpool->randStr(6,'NUMBER'));
		}
	}
	
	public function edit_card_series($id){
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			$id = $f['credit_card_series_id'];
			$name = $f['name'];
			$names = $this->Refillpool->query("select name from credit_card_series where credit_card_series_id = '$id'");
			if ($names[0][0]['name'] != $name) {
				$this->validate_series($f,true,'/refillpools/edit_card_series/'.$id);
			} else {
				$this->validate_series($f,false,'/refillpools/edit_card_series/'.$id);	
			}
			
			if ($this->Refillpool->save($f)) {
				$this->Refillpool->create_json_array('',201,__('editrefillsuc',true));
			} else {
				$this->Refillpool->create_json_array('',101,__('editrefillfail',true));
			}
			
			$this->Session->write('m',Refillpool::set_validator());
			$this->redirect('/refillpools/pools_list?edit_id='.$id);
		} else {
//			$rs = $this->Refillpool->getRates();
//			$this->set('rates',$rs);
			$this->set('cardpool',$this->Refillpool->getById($id));
		}
	}
	
	public function del_card_series($id){
		$used = $this->Refillpool->query("select count(credit_card_id) as c  from credit_card where credit_card_series_id = '$id'");
		if ($used[0][0]['c'] > 0) {
			$this->Refillpool->create_json_array('',101,__('refhascards',true));
		} else {
			if ($this->Refillpool->del($id)) {
				$this->Refillpool->create_json_array('',201,__('del_suc',true));	
			} else {
				$this->Refillpool->create_json_array('',101,__('del_fail',true));
			}
		}
		
		$this->Session->write('m',Refillpool::set_validator());
		$this->redirect('/refillpools/pools_list');
	}
	
	
  
	public function generated(){
		Configure::write('debug',0);
		$pool_id = $_REQUEST['id'];
		$nums = $_REQUEST['num'];
		
		if (empty($nums)) {
			echo __('generatednumnull',true)."|false";
			exit();
		} else {
			if (!preg_match('/^[0-9]{1,10}$/',$nums)) {
				echo __('generatednumformat',true)."|false";
				exit();
			}
		}
		
		$pool = $this->Refillpool->getById($pool_id);
		
		$card_number = '';
		$hasMore = $this->Refillpool->query("select count(credit_card_id) as c from credit_card where credit_card_series_id = '$pool_id'");
		if ($hasMore[0][0]['c'] == 0) {
			$card_number = $pool[0][0]['prefix'].$pool[0][0]['start_num'];			
		} else {
			$mc = $this->Refillpool->query("select max(card_number) as mc from credit_card where credit_card_series_id = '$pool_id'");
			$card_number = $mc[0][0]['mc'];
		}
		
		$expire_date = empty($pool[0][0]['expire_date'])?'null':"'".$pool[0][0]['expire_date']."'";
		
		$value = $pool[0][0]['value'];
		
		$pass_len = $pool[0][0]['password_length'];
		if (empty($pass_len))$pass_len = 12;
		
		
		$this->Refillpool->begin();
		$qs_res = 0;
		for ($i = 0;$i<$nums;$i++) {
			$card_number = $card_number + 1;
			$pin = $this->Refillpool->randStr($pass_len,'NUMBER');
			$sql = "insert into credit_card (card_number,credit_card_series_id,
									expire_date,create_date,value,pin)
									values('$card_number','$pool_id',
									$expire_date,current_timestamp(0),'$value','$pin')";
			
			$qs = $this->Refillpool->query($sql);
			$qs_res += count($qs);
		}
		
		if ($qs_res == 0) {
			$this->Refillpool->commit();
			echo __('generatedsuc',true)."|true";
		} else {
			$this->Refillpool->rollback();
			echo __('generatedfail',true)."|false";
		}
	}
	
	
/*
	 * Card列表
	 */
	public function cards_list($id){
		$currPage = 1;
		$pageSize = 100;
		$search = null;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		if (!empty($_REQUEST['search'])) {
			$search = $_REQUEST['search'];
			$this->set('search',$search);
		}
		
		$last_conditons = null;
		$advance_search = '';
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			if (!empty($f['rate_id'])) {$last_conditons .= "&rate_id=".$f['rate_id'];$advance_search .=" and rate = ".$f['rate_id'];}
			if (!empty($f['reseller_id'])) {$last_conditons .= "&reseller_id=".$f['reseller_id'];$advance_search .=" and reseller_id = ".$f['reseller_id'];}
			if (!empty($f['card_value_s'])) {$last_conditons .= "&card_value_s=".$f['card_value_s'];$advance_search .=" and value >= ".$f['card_value_s'];}
			if (!empty($f['card_value_e'])) {$last_conditons .= "&card_value_e=".$f['card_value_e'];$advance_search .=" and value <= ".$f['card_value_e'];}
			if (!empty($f['create_date_s'])) {$last_conditons .= "&create_date_s=".$f['create_date_s'];$advance_search .=" and create_date >= '".$f['create_date_s']."'";}
			if (!empty($f['create_date_e'])) {$last_conditons .= "&create_date_e=".$f['create_date_e'];$advance_search .=" and create_date <= '".$f['create_date_e']."'";}
			if (!empty($f['effective_date_s'])) {$last_conditons .= "&effective_date_s=".$f['effective_date_s'];$advance_search .=" and effective_date >= '".$f['effective_date_s']."'";}
			if (!empty($f['effective_date_e'])) {$last_conditons .= "&effective_date_e=".$f['effective_date_e'];$advance_search .=" and effective_date <= '".$f['effective_date_s']."'";}
			if (!empty($f['used_date_s'])) {$last_conditons .= "&used_date_s=".$f['used_date_s'];$advance_search .=" and used_date >= '".$f['used_date_s']."'";}
			if (!empty($f['used_date_e'])) {$last_conditons .= "&used_date_e=".$f['used_date_e'];$advance_search .=" and used_date <= '".$f['used_date_e']."'";}
			if (!empty($f['iseff'])) {$last_conditons .= "&iseff=".$f['iseff'];if($f['iseff'] == 'y')$advance_search .= " and effective_date is not null"; else $advance_search .= " and effective_date is null";};
			
			$this->set('searchform',$f);
			$this->set('last_conditons',$last_conditons);
		}
		
		if (!empty($_REQUEST['edit_id'])){
				$sql = "select credit_card_id,card_number,used_date,pin,
							(select name from credit_card_series where credit_card_series_id = credit_card.credit_card_series_id) as series,
							expire_date,create_date,effective_date,
							(select name from reseller where reseller_id = credit_card.reseller_id) as reseller,value from credit_card
							where credit_card_id = {$_REQUEST['edit_id']}
	  		";
			$result = $this->Refillpool->query ( $sql );
			//分页信息
				require_once 'MyPage.php';
				$results = new MyPage ();
				$results->setTotalRecords ( 1 ); //总记录数
				$results->setCurrPage ( 1 ); //当前页
				$results->setPageSize ( 1 ); //页大小
				$results->setDataArray ( $result );
			$this->set('edit_return',true);
			} else {
		$results = $this->Refillpool->getAllCards ( $currPage, $pageSize,$search,$id,$advance_search);
			}
		$this->set ( 'p', $results );
		$this->set('credit_card_series_id',$id);
		
		$reseller_id = $this->Session->read('sst_reseller_id');
		$this->set('reseller',$this->Refillpool->getAllResellers($reseller_id));
//		$rs = $this->Refillpool->getRates();
//		$this->set('rates',$rs);
	}
	
	/*
	 * Delete a card
	 */
	public function del_card($id,$series_id){
		if ($id == 'all'){
			$id = "select credit_card_id from credit_card where credit_card_series_id = $series_id";
		}
		
		if ($id== 'selected'){
			$id = $_REQUEST['ids'];
		}
		$qs = $this->Refillpool->query("delete from credit_card where credit_card_id in ($id)");
		if (count($qs) == 0) $this->Refillpool->create_json_array('',201,__('delcardsuc',true));
		else $this->Refillpool->create_json_array('',101,__('delcardfail',true));
		
		$this->Session->write('m',Refillpool::set_validator());
		$this->redirect('/refillpools/cards_list/'.$series_id);
	}
	
	public function validate_card($f,$url){
		$hasError = false;
		
		$value = $f['value'];
		
		if (empty($value)) {
			$hasError = true;
			$this->Refillpool->create_json_array('#value',101,__('amountnull',true));
		} else {
			if (!preg_match('/^[0-9]+(\.[0-9]{1,3})?$/',$value)) {
				$hasError = true;
				$this->Refillpool->create_json_array('#value',101,__('amountformat',true));
			}
		}
		
		$expire_date = $f['expire_date'];
		if (empty($expire_date)) {
			$hasError = true;
			$this->Refillpool->create_json_array('#expire_date',101,__('expiredatenull',true));
		} else {
			if (strtotime($expire_date) - strtotime(date('Y-m-d H:i:s',time())) < 0) {
				$hasError = true;
				$this->Refillpool->create_json_array('#expire_date',101,__('expiredatetoosmall',true));
			}
		}
		
		
		if ($hasError == true) {
			$this->Session->write('backform',$f);
			$this->Session->write('m',Refillpool::set_validator());
			$this->redirect($url);
		}
	}
	
	public function add_card($series_id){
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			$series_id = $f['credit_card_series_id'];
			$this->validate_card($f,'/refillpools/add_card/'.$series_id);
			
			$po = $this->Refillpool->getById($series_id);
			
			$f['pin'] = $this->Refillpool->randStr($po[0][0]['password_length'],'NUMBER');
			
			if (!empty($f['reseller_id'])) {
				$f['effective_date'] = date('Y-m-d H:i:s',time()+6*60*60);
			}
		
			$f['create_date'] = date('Y-m-d H:i:s',time()+6*60*60);
			
			$this->loadModel('Creditcard');
			if ($this->Creditcard->save($f)) {
				$this->Refillpool->create_json_array('',201,__('addcardsuc',true));
			} else {
				$this->Refillpool->create_json_array('',101,__('addcardfail',true));
			}
			
			$this->Session->write('m',Refillpool::set_validator());
			$this->redirect('/refillpools/cards_list/'.$series_id);
		} else {
//			$rs = $this->Refillpool->getRates();
//			$this->set('rates',$rs);
			
			$card_number = '';
			$pool = $this->Refillpool->getById($series_id);
			$hasMore = $this->Refillpool->query("select count(credit_card_id) as c from credit_card where credit_card_series_id = '$series_id'");
			if ($hasMore[0][0]['c'] == 0) {
				$card_number = $pool[0][0]['prefix'].$pool[0][0]['start_num'];			
			} else {
				$mc = $this->Refillpool->query("select max(card_number) as mc from credit_card where credit_card_series_id = '$series_id'");
				$card_number = $mc[0][0]['mc'];
			}
			
			$this->set('cn',$card_number+1);
			$this->set('series_id',$series_id);
			$this->set('cardpool',$pool);
			
			$reseller_id = $this->Session->read('sst_reseller_id');
			$this->set('reseller',$this->Refillpool->getAllResellers($reseller_id));
		}
	}
	
	
public function edit_card($card_id){
		if (!empty($this->params['form'])) {
			$f = $this->params['form'];
			$card_id = $f['credit_card_id'];
			$this->validate_card($f,'/refillpools/edit_card/'.$card_id);
			
			
			if (empty($f['reseller_id'])) {
				$f['effective_date'] = null;
			}
		
			$this->loadModel('Creditcard');
			if ($this->Creditcard->save($f)) {
				$this->Refillpool->create_json_array('',201,__('editcardsuc',true));
			} else {
				$this->Refillpool->create_json_array('',101,__('editcardfail',true));
			}
			
			$this->Session->write('m',Refillpool::set_validator());
			$this->redirect('/refillpools/cards_list/'.$f['credit_card_series_id'].'?edit_id='.$card_id);
		} else {
//			$rs = $this->Refillpool->getRates();
//			$this->set('rates',$rs);

			$reseller_id = $this->Session->read('sst_reseller_id');
			$this->set('reseller',$this->Refillpool->getAllResellers($reseller_id));
			$this->set('card',$this->Refillpool->getCardById($card_id));
		}
	}
	
	public function buck_update(){
		Configure::write('debug',0);
		$card_ids = $_REQUEST['card_ids'];
		if (empty($card_ids)) {
			echo __('nocardsselected',true)."|false";
			exit();
		}
		
		if (!empty($_REQUEST['value'])) {
			if (!preg_match('/^[0-9]+(\.[0-9]{1,3})?$/',$_REQUEST['value'])) {
				echo __('amountformat',true)."|false";;exit();
			}
		}
		
		$changed = true;
		$set_sql = ' set ';
		if (!empty($_REQUEST['res'])) {$changed=false;$s = $_REQUEST['res'];$set_sql .= "reseller_id='$s',";}
		if (!empty($_REQUEST['value'])) {$changed=false;$b = $_REQUEST['value'];$set_sql .= "value='$b',";}
		if (!empty($_REQUEST['ed'])) {$changed=false;$e = $_REQUEST['ed'];$set_sql .= "expire_date='$e',";}
		if (!empty($_REQUEST['buck_eff'])) {
				$changed=false;
				$ef = $_REQUEST['buck_eff'];
				if ($ef == 'y') {
					//$exp_d = date('Y-m-d H:i:s',time()+6*60*60);
					$set_sql .= "effective_date= current_timestamp(0),";
				} else if ($ef == 'n') {
					$set_sql .= "effective_date=null,";
				}
		}
		
		if (!empty($_REQUEST['buck_used'])) {
				$changed=false;
				$us = $_REQUEST['buck_used'];
				if ($us == 'y') {
					//$used_d = date('Y-m-d H:i:s',time()+6*60*60);
					$set_sql .= "used_date= current_timestamp(0),";
				} else if ($us == 'n') {
					$set_sql .= "used_date=null,";
				}
		}

		if ($changed == false) {
			$set_sql = substr($set_sql,0,strlen($set_sql)-1);
			$qs = $this->Refillpool->query("update credit_card $set_sql where credit_card_id in ($card_ids)");
			if (count($qs) == 0) echo __('buckupdatesuc',true)."|true";
			else echo __('buckupdatefail',true)."|false";
		} else {
			echo __('nochanged',true)."|false";
		}
	}
	
	public function effective(){
		Configure::write('debug',0);
		$card_ids = $_REQUEST['card_ids'];
		if (empty($card_ids)) {
			echo __('nocardsselected',true)."|false";
			exit();
		}
		
		$res = $_REQUEST['res'];
		//$d = date('Y-m-d H:i:s',time()+6*60*60);
		
		$pool_id = $_REQUEST['pool_id'];
		
		$pool = $this->Refillpool->getById($pool_id);
		
		$sql = "update credit_card set reseller_id='$res',effective_date = current_timestamp(0) where credit_card_id in ($card_ids)";
//		if ($pool[0][0]['expire_type'] == 2) {
//			$days = $pool[0][0]['expire_days'];
//			//$expire_date = date('Y-m-d H:i:s',time()+6*60*60+$days*60*60*24);
//			$sql = "update credit_card set reseller_id='$res',effective_date = current_timestamp(0),expire_date = current_timestamp(0) + interval '$days days' where credit_card_id in ($card_ids)";
//		}
		
		$qs = $this->Refillpool->query($sql);
		if (count($qs) == 0) echo __('effectivesuc',true)."|true";
		else echo __('effectivefail',true)."|false";
	}
	
	/**
	 * 删除所有卡号池
	 */
	public function delete_all(){
		$reseller_id = $this->Session->read('sst_reseller_id');
		
		$sub_sql = "select credit_card_series_id from credit_card_series";
		!empty($reseller_id)?$sub_sql.=" where reseller_id = $reseller_id":null;
		$sql = "select count(credit_card_id) from credit_card where credit_card_series_id in ($sub_sql)";
		$qs = $this->Refillpool->query("$sql");
		
		if (count($qs) > 0) {
			$this->Refillpool->create_json_array('',101,__('deletecrediseries',true));
		} else {
			$qs = $this->Refillpool->query("delete from credit_card_series where credit_card_series_id in ($sub_sql)");
			if (count($qs)==0){
				$this->Refillpool->create_json_array('',201,__('del_suc',true));
			} else {
				$this->Refillpool->create_json_array('',101,__('del_fail',true));
			}
		}
		
		$this->Session->write('m',Refillpool::set_validator());
		
		$this->redirect('/refillpools/pools_list');
	}
	
	public function delete_selected(){
		$ids = $_REQUEST['ids'];
		$sql = "select count(credit_card_id) from credit_card where credit_card_series_id in ($ids)";
		
		$qs = $this->Refillpool->query($sql);
		
		if (count($qs) > 0) {
			$this->Refillpool->create_json_array('',101,__('deletecrediseries',true));
		} else {
			$qs = $this->Refillpool->query("delete from credit_card_series where credit_card_series_id in ($ids)");
			if (count($qs)==0){
				$this->Refillpool->create_json_array('',201,__('del_suc',true));
			} else {
				$this->Refillpool->create_json_array('',101,__('del_fail',true));
			}
		}
		
		$this->Session->write('m',Refillpool::set_validator());
		$this->redirect('/refillpools/pools_list');
	}
}
