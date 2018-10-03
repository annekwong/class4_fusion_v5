<?php 
	class DisconnectsController extends AppController{
		var $name = 'Disconnects';
		var $uses = array();
		
		
	public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
		
		public function disconnect_report (){
			$this->loadModel("Cdr");
			$reseller_id = $this->Session->read('sst_reseller_id');
			$last_conditions = "";
			$acc=$ani=$dnis=$egress=$ingress=$src=$dst=$sdura=$edura=$st=$et = 'null';
			if (!empty($_REQUEST['res_id'])){
				$reseller_id = $_REQUEST['res_id'];
				$last_conditions .= "&res_id={$_REQUEST['res_id']}";
			}
			
			if (!empty($_REQUEST['acc'])){
				$accs = $this->Cdr->query("select card_id from card where card_number='{$_REQUEST['acc']}'");
				$acc = $accs[0][0]['card_id'];
				$last_conditions .= "&acc={$_REQUEST['acc']}";
			}
			
			if (!empty($_REQUEST['ani'])){
				$ani = "'".$_REQUEST['ani']."'";
				$last_conditions .= "&ani={$_REQUEST['ani']}";
			}
			
			if (!empty($_REQUEST['dnis'])){
				$dnis = "'".$_REQUEST['dnis']."'";
				$last_conditions .= "&dnis={$_REQUEST['dnis']}";
			}
			
			if (!empty($_REQUEST['egress'])){
				$egress = "'".$_REQUEST['egress']."'";
				$last_conditions .= "&egress={$_REQUEST['egress']}";
			}
			
			if (!empty($_REQUEST['ingress'])){
				$ingress = "'".$_REQUEST['ingress']."'";
				$last_conditions .= "&ingress={$_REQUEST['ingress']}";
			}
			
			if (!empty($_REQUEST['src'])){
				$src = "'".$_REQUEST['src']."'";
				$last_conditions .= "&src={$_REQUEST['src']}";
			}
			
			if (!empty($_REQUEST['dst'])){
				$dst = "'".$_REQUEST['dst']."'";
				$last_conditions .= "&dst={$_REQUEST['dst']}";
			}
			
			if (!empty($_REQUEST['sdura'])){
				$sdura = "'".$_REQUEST['sdura']."'";
				$last_conditions .= "&sdura={$_REQUEST['sdura']}";
			}
			
			if (!empty($_REQUEST['edura'])){
				$edura = "'".$_REQUEST['edura']."'";
				$last_conditions .= "&edura={$_REQUEST['edura']}";
			}
			
			if (!empty($_REQUEST['st'])){
				$st = "'".$_REQUEST['st']."'";
				$last_conditions .= "&st={$_REQUEST['st']}";
			}
			
			if (!empty($_REQUEST['et'])){
				$et = "'".$_REQUEST['et']."'";
				$last_conditions .= "&et={$_REQUEST['et']}";
			}
			
			$qs = $this->Cdr->query("select * from disconnect_cause(
												$reseller_id,$acc,$ani,$dnis,$egress,$ingress,$src,$dst,$sdura,$edura,$st,$et) 
												as (trunk character varying,host character varying,sipcode character varying,nums bigint,release_cause character varying)");
												
			$this->set('result',$qs);
			
			//--------------  参数-----------------
			
			//代理商
			$res = $this->Session->read('sst_reseller_id');
			$this->Cdr->generateTree($res);
			$this->set('r_reseller',Cdr::$show_reseller);
			
			//帐户
			$account_sql = "select card_id,card_number from card";
			if (!empty($res)) $account_sql .= " where reseller_id = $res";
			$this->set('accounts',$this->Cdr->query($account_sql));
			
			//对接网关
			$ingress_sql = "select alias,name from resource where ingress = true";
			if (!empty($res)) $ingress_sql .= " and reseller_id = $res";
			$this->set('ingress',$this->Cdr->query($ingress_sql));
			
			//落地网关
			$egress_sql = "select alias,name from resource where egress = true";
			if (!empty($res)) $egress_sql .= " and reseller_id = $res";
			$this->set('egress',$this->Cdr->query($egress_sql));
			
			if (!empty($this->params['form']))
				$this->set('searchform',$this->params['form']);
			
			//-----------------------------------------
		}
	}
?>