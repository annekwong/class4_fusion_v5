<?php
class InterfacesController extends AppController {
	var $name = 'Interfaces';
	var $uses = array ();
	var $components = array ('PhpTelnet' );
	var $layout = '';
	
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	/**
	 * @author chuqi zhang
	 * @method acc_refill 帐户充值
	 * @version 1.0
	 */
	public function acc_refill() {
		$this->loadModel ( 'Refill' );
		
		//-----提交过来的参数------
		$acc = $_REQUEST ['acc']; //帐号
		$card = $_REQUEST ['card']; //充值卡号
		$pin = $_REQUEST ['pin']; //充值卡密码
		//-----------------------
		

		//-----验证参数是否为空
		if (empty ( $acc ) || empty ( $card ) || empty ( $pin )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		
		//-----验证帐户是否存在
		$account = $this->Refill->query ( "select card_id from card where card_number = '$acc'" );
		if (count ( $account ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		//----验证充值卡
		$refillCards = $this->Refill->query ( "select credit_card_id,value,effective_date,expire_date,used_date from credit_card where card_number = '$card' and pin = '$pin'" );
		if (count ( $refillCards ) == 0) { //充值卡不存在
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		//充值卡未激活
		if (empty ( $refillCards [0] [0] ['effective_date'] )) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		//过期
		if (! empty ( $refillCards [0] [0] ['expire_date'] ) && strtotime ( $refillCards [0] [0] ['expire_date'] ) < time () + 6 * 60 * 60) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		//已经使用
		if (! empty ( $refillCards [0] [0] ['used_date'] )) {
			echo "<reply result='3'></reply>";
		} else {
			if ($this->Refill->card_refill ( $refillCards [0] [0] ['value'], 4,2 )) { //成功
				echo "<reply result='0'></reply>";
			} else { //系统错误
				echo "<reply result='255'></reply>";
			}
		}
	}
	
	/**
	 * @author chuqi zhang
	 * @method acc_balance 查询帐户余额
	 * @version 1.0
	 */
	public function acc_balance() {
		$this->loadModel ( 'Refill' );
		//----提交过来的参数信息------
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		//-------------------------
		

		//--验证参数是否为空
		if (empty ( $acc ) || empty ( $pass )) {
			echo "<reply result='5' rec='f'><balance></balance></reply>";
			exit ();
		}
		
		//--验证帐户是否存在
		$account = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $account ) == 0) {
			echo "<reply result='3' rec='f'><balance></balance></reply>";
			exit ();
		}
		
		//--查询余额
		$balance = $this->Refill->query ( "select balance from account_balance 
																	where account_id = {$account[0][0]['card_id']} 
																	order by account_balance_id desc limit 1" );
		
		echo "<reply result='0' rec='f'><balance>{$balance[0][0]['balance']}</balance></reply>";
	}
	
	/**
	 * @author chuqi zhang
	 * @method change_pwd 修改帐户密码
	 * @version 1.0
	 */
	public function change_pwd() {
		$this->loadModel ( 'Refill' );
		
		//--提交过来的参数-------
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		$newpass = $_REQUEST ['newpass'];
		
		//--验证参数是否为空
		if (empty ( $acc ) || empty ( $pass ) || empty ( $newpass )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		
		$account = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $account ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		//验证新密码格式
		if (! preg_match ( '/^[0-9a-zA-Z]{6,12}$/', $newpass )) {
			echo "<reply result='1'></reply>";
			exit ();
		}
		
		//修改密码
		$result = $this->Refill->query ( "update card set pin = '$newpass' where card_id = {$account[0][0]['card_id']}" );
		if (count ( $result ) == 0) {
			echo "<reply result='0'></reply>";
		} else {
			echo "<reply result='255'></reply>";
		}
	}
	
	/**
	 * @author chuqi zhang
	 * @method change_num 修改帐户绑定号码
	 * @version 1.0
	 */
	public function change_num() {
		$this->loadModel ( 'Refill' );
		
		//--提交过来的参数-------
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		$num = $_REQUEST ['num'];
		
		//--验证参数是否为空
		if (empty ( $acc ) || empty ( $pass ) || empty ( $num )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		
		$account = $this->Refill->query ( "select card_id,can_update_ani from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $account ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		if ($account[0][0]['can_update_ani'] == false){
			echo "<reply result='1'></reply>";
			exit();
		}
		
		//修改密码
		$result = $this->Refill->query ( "update card set ani_bind_num = '$num' where card_id = {$account[0][0]['card_id']}" );
		if (count ( $result ) == 0) {
			echo "<reply result='0'></reply>";
		} else {
			echo "<reply result='255'></reply>";
		}
	}
	
	/**
	 * @author chuqi zhang
	 * @method cdr_list 查询帐户话单
	 * @version 1.0
	 */
	public function cdr_list() {
		$this->loadModel ( 'Refill' );
		$acc = $_REQUEST ['acc']; //帐户
		$pass = $_REQUEST ['pass']; //密码
		$start = $_REQUEST ['start']; //开始时间
		$end = $_REQUEST ['end']; //结束时间
		

		//--验证参数是否为空
		if (empty ( $acc ) || empty ( $pass ) || empty ( $start )) {
			echo "<reply result='5'><list></list></reply>";
			exit ();
		}
		
		$account = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $account ) == 0) {
			echo "<reply result='3'><list></list></reply>";
			exit ();
		}
		
		$sub_query = "select cdr_id from account_cost 
													where account_id = {$account[0][0]['card_id']}
													and time >= '$start'";
		if (! empty ( $end )) {
			$sub_query .= " and time <= '$end'";
		}
		
		$cdrs = $this->Refill->query ( "select call_type,start_time_of_date as st,release_tod as et,
																			origination_source_number as ani,termination_source_number as dnis,
																			call_duration,(select cost from account_cost where cdr_id = cdr.cdr_id) as costs
																			from cdr
																			where cdr_id in ($sub_query)" );
		
		$cdr_info = "<reply result='0'><list>";
		$cdr_count = count ( $cdrs );
		for($i = 0; $i < $cdr_count; $i ++) {
			$cdr_info .= "<cdr>{$cdrs[$i][0]['call_type']},{$cdrs[$i][0]['st']},{$cdrs[$i][0]['et']},{$cdrs[$i][0]['ani']},{$cdrs[$i][0]['dnis']},{$cdrs[$i][0]['call_duration']},{$cdrs[$i][0]['costs']}</cdr>";
		}
		$cdr_info .= "</list></reply>";
		echo $cdr_info;
	}
	
	/**
	 * @author chuqi zhang
	 * @method find_passwd 找回密码
	 * @version 1.0
	 */
	public function find_passwd() {
		$this->loadModel ( 'Refill' );
		$acc = $_REQUEST ['acc']; //帐户
		$num = $_REQUEST ['num']; //手机号码
		

		if (empty ( $acc ) || empty ( $num )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		
		$pwd = $this->Refill->query ( "select card_id,pin from card where card_number = '$acc'" );
		
		if (count ( $pwd ) != 0) {
			$msg = $this->Refill->query("select tmp_content from msg_template where tmp_type = 2");
			if ($this->Refill->check_balance ( 2, $num, str_ireplace('{findpwd}',$pwd[0][0]['pin'],$msg), $pwd [0] [0] ['card_id'] )) {
				$this->Refill->msg_cost ( 2, $num, str_ireplace('{findpwd}',$pwd[0][0]['pin'],$msg), $pwd [0] [0] ['card_id'] );
				$send_msg = str_ireplace('{findpwd}',$pwd[0][0]['pin'],$msg);
				$interface_url = $this->Refill->query("select url from msg_interface where status = true");
				echo "<reply result = '0'><acc pass='{$pwd[0][0]['pin']}'></reply>";
				$result = $this->httpRequestGET ( "{$interface_url[0][0]['url']}&mobile=$num&service=BZ&mtype=XXXF&msgid=" . time () . "&message=$send_msg" );
			} else {
				echo "<reply result = '11'><acc pass=''></reply>";
			}
		} else {
			echo "<reply result = '255'><acc pass=''></reply>";
		}
	}
	
	/**
	 * 根据主持人号码查询会议
	 */
	public function get_conf_by_owner() {
		$this->loadModel ( 'Refill' );
		$num = $_REQUEST ['num'];
		$confs = $this->Refill->query ( "select conf_id,dnis,(select kick from conf_member_list where account_id = real_cdr.account_id) as kick,
									(select invite from conf_member_list where account_id = real_cdr.account_id) as invite,
									(select rec from conf_member_list where account_id = real_cdr.account_id) as rec
									from real_cdr where call_type = 2 and ani = '$num'" );
		
		if (count ( $confs ) != 0) {
			$conf_info = "<reply result='0' rec='true'>
   												<confs>
								        		<conf owner = '$num' id= '{$confs[0][0]['conf_id']}'>
								      				<nums>";
			
			$loop = count ( $confs );
			for($i = 0; $i < $loop; $i ++) {
				$kick = $confs [$i] [0] ['kick'] == 1 ? 'true' : 'false';
				$invite = $confs [$i] [0] ['invite'] == 1 ? 'true' : 'false';
				$rec = $confs [$i] [0] ['rec'] == 1 ? 'true' : 'false';
				$conf_info .= "<num='{$confs[$i][0]['dnis']}' kick='$kick' invite='$invite' rec='$rec'/>";
			}
			
			$conf_info .= "</nums></conf></confs></reply>";
		} else {
			$conf_info = "<reply result='255' rec='false'><confs></confs></reply>";
		}
		echo $conf_info;
	}
	
	public function get_conf_by_acc(){
		$this->loadModel ( 'Refill' );
		$confid = $_REQUEST ['confid'];
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		
		if (empty($acc) || empty($acc)) {
			echo "<reply result='5'></reply>";
			exit();
		}
		
		$account = $this->Refill->query("select card_id from card where card_number= '$acc' and pin = '$pass'");
		if (count($account) == 0){
			echo "<reply result='3'></reply>";
			exit();
		}
		
		$confs = $this->Refill->query ( "select conf_id,dnis,(select kick from conf_member_list where account_id = real_cdr.account_id) as kick,
									(select invite from conf_member_list where account_id = real_cdr.account_id) as invite,
									(select rec from conf_member_list where account_id = real_cdr.account_id) as rec
									from real_cdr where call_type = 2 and conf_id = '$confid'" );
		
		if (count ( $confs ) > 0) {
			$conf_info = "<reply result='0' rec='true'>
   												<confs>
								        		<conf owner = '{$confs[0][0]['ani']}' id= '{$confs[0][0]['conf_id']}'>
								      				<nums>";
			
			$loop = count ( $confs );
			for($i = 0; $i < $loop; $i ++) {
				$kick = $confs [$i] [0] ['kick'] == 1 ? 'true' : 'false';
				$invite = $confs [$i] [0] ['invite'] == 1 ? 'true' : 'false';
				$rec = $confs [$i] [0] ['rec'] == 1 ? 'true' : 'false';
				$conf_info .= "<num='{$confs[$i][0]['dnis']}' kick='$kick' invite='$invite' rec='$rec'/>";
			}
			
			$conf_info .= "</nums></conf></confs></reply>";
		} else {
			$conf_info = "<reply result='0' rec='false'><confs></confs></reply>";
		}
		echo $conf_info;
	}
	
	/**
	 * 发起会议
	 */
	public function start_conf() {
		$this->loadModel ( 'Refill' );
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		$num = explode('#',$_REQUEST ['num']);
		$c_num = "'{".str_ireplace('#',',',$_REQUEST ['num'])."}'";
		$title = $_REQUEST ['title']; 
		$owner_num = $_REQUEST ['owner_num'];
		
		if (empty ( $acc ) || empty ( $pass ) || empty ( $num ) || empty ( $owner_num )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		
		$basic_info = $this->Refill->query ( "select enough_balance,min_amount,callback,conference,active,international,long_distance,card_id,rate_table_id,reseller_id,account_level_id,
																		route_strategy_id from card
																		where card_number = '$acc' and pin = '$pass'" );
		
		if (count ( $basic_info ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		if (empty ( $basic_info [0] [0] ['static_route_id'] )) {
			$basic_info [0] [0] ['static_route_id'] = 'none';
		}
		
		if (empty ( $basic_info [0] [0] ['dynamic_route_id'] )) {
			$basic_info [0] [0] ['dynamic_route_id'] = 'none';
		}
		
		if ($basic_info[0][0]['active'] == false) {
			echo "<reply result='2'></reply>";
			exit();
		}
		
		if ($basic_info[0][0]['enough_balance'] == false) {
			echo "<reply result='2'></reply>";
			exit();
		}
		
		$now_balance = $this->Refill->query("select balance from account_balance where account_id = '{$basic_info[0][0]['card_id']}' order by account_balance_id desc limit 1");
		
		if (empty($now_balance[0][0]['balance']) || $now_balance[0][0]['balance'] < $basic_info[0][0]['min_amount']){
			echo "<reply result='2'></reply>";
			exit();
		}
		
		//当该帐户没有国际长途权限的时候  检查会议成员号码是否有国际长途
		if ($basic_info[0][0]['international'] == false) {
			$c_result = $this->Refill->query("select * from check_conf_num($c_num,1)");
			if ($c_result[0][0]['check_conf_num'] == 'false'){
				echo "<reply result='1'></reply>";
				exit();
			}
		}
		
		if ($basic_info[0][0]['long_distance'] == false) {
			$c_result = $this->Refill->query("select * from check_conf_num($c_num,2)");
			if ($c_result[0][0]['check_conf_num'] == 'false'){
				echo "<reply result='1'></reply>";
				exit();
			}
		}
		
		$level = $this->Refill->query("select level_type from account_level where account_level_id = {$basic_info[0][0]['account_level_id']}");
		$cmd = '';
		if (count($num) ==  1){
			if ($basic_info[0][0]['callback'] == false){
				echo "<reply result='1'></reply>";
				exit();
			}
			$cmd = "c4_callback $owner_num,$num[0],{$basic_info[0][0]['card_id']},{$basic_info[0][0]['rate_table_id']},{$basic_info[0][0]['reseller_id']},{$level[0][0]['level_type']},{$basic_info[0][0]['route_strategy_id']}";
		} else {
			if ($basic_info[0][0]['conference'] == false){
				echo "<reply result='1'></reply>";
				exit();
			}
			
			$cmd = "c4_conf {$basic_info[0][0]['card_id']}|{$basic_info[0][0]['rate_table_id']}|{$basic_info[0][0]['reseller_id']}|{$level[0][0]['level_type']}|{$basic_info[0][0]['route_strategy_id']} $owner_num $owner_num,t,t,t";
			for ($i=0;$i<count($num);$i++){
				$privicy = $this->Refill->query("select rec,invite,kick from conf_member_list where num = '{$num[$i]}' and account_id = {$basic_info[0][0]['card_id']}");
				if (count($privicy) > 0){
					$cmd .= " {$num[$i]},{$privicy[0][0]['rec']},{$privicy[0][0]['invite']},{$privicy[0][0]['kick']}";
				} else {
					$cmd .= " {$num[$i]},f,f,f";
				}
			}
		}
		
		echo $cmd;
		
		
		$result = $this->PhpTelnet->getResult ( "api " . $cmd );
	}
	
	/**
	 * 平台更新
	 */
	public function renew_server() {
		$this->loadModel ( 'Refill' );
		$server_id = $_REQUEST ['server_id'];
		
		$ips = $this->Refill->query ( "select ip from server_platform  where server_id = $server_id" );
		
		if (count ( $ips ) > 0) {
			echo "<reply result='0'><ip>{$ips[0][0]['ip']}</ip></reply>";
		} else {
			echo "<reply result='255'><ip></ip></reply>";
		}
	}
	
	/**
	 * 查询会议列表
	 */
	public function conf_list() {
		$this->loadModel ( 'Refill' );
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		
		if (empty ( $acc ) || empty ( $pass )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		
		$account_id = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		
		if (count ( $account_id ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		$confs = $this->Refill->query ( "select distinct conf_id from real_cdr where call_type = 2  and account_id = {$account_id[0][0]['card_id']}" );
		$msg = "<reply result='0'><conf>";
		$loop = count ( $confs );
		for($i = 0; $i < $loop; $i ++) {
			$msg .= "<id>{$confs[$i][0]['conf_id']}</id>";
		}
		$msg .= "</conf></reply>";
		echo $msg;
	}
	
	/**
	 * 邀请参会人员
	 */
	public function conf_invite() {
		$this->loadModel ( 'Refill' );
		$confid = $_REQUEST ['confid'];
		$num = $_REQUEST ['num'];
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		if (empty ( $pass ) || empty ( $acc )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		$accounts = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $accounts ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		$cmd = "conference $confid dial $num";
		$result = $this->PhpTelnet->getResult ( "api " . $cmd );
	}
	
	/**
	 * 踢除参会人员
	 */
	public function conf_kick() {
		$this->loadModel ( 'Refill' );
		$confid = $_REQUEST ['confid'];
		$num = $_REQUEST ['num'];
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		if (empty ( $pass ) || empty ( $acc )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		$accounts = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $accounts ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		$cmd = "conference $confid kick $num";
		$result = $this->PhpTelnet->getResult ( "api " . $cmd );
	}
	
	/**
	 * 帐户注册
	 */
	public function acc_reg() {
		$this->loadModel ( 'Refill' );
		$resid = $_REQUEST ['resid'];
		$num = $_REQUEST ['num'];
		$key = $_REQUEST ['key'];
		if (empty ( $num ) || empty ( $resid ) || empty ( $key )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		
		$private_key = $this->Refill->query("select key from reseller where reseller_id = $resid");
		if ($private_key[0][0]['key'] != $key) {
			echo "<reply result='9'></reply>";
			exit ();
		}
		
		$trial = $this->Refill->query ( "select card_id,init_amount,card_number,pin from card
																			where trial = true and can_command=true and effective_date is null
																			and expire_date::timestamp > current_timestamp(0) limit 1" );
		
		$this->Refill->query ( "update card set number='$num',effective_date = current_timestamp(0),reseller_id = $resid where card_id = {$trial[0][0]['card_id']}" );
		
		if (count ( $trial ) == 0) {
			echo "<reply result='4'></reply>";
		} else {
			//查看推荐人,依据推荐策略送基础金或其他
			$rec_accounts = $this->Refill->query("select  account_id from recommend_record where recommend_number = '$num' order by recommend_time asc limit 1");
			if (!empty($rec_accounts)) {
				$this->Refill->calc_recommend($rec_accounts[0][0]['account_id'],1);
			}
			
			echo "<reply result='0'>
  							 <acc id='{$trial[0][0]['card_number']}' pass='{$trial[0][0]['pin']}' balance='{$trial[0][0]['init_amount']}'> 
								</reply>";
			
			$interface_url = $this->Refill->query("select url from msg_interface where status = true");
			
			$this->httpRequestGET ( "{$interface_url[0][0]['url']}&mobile=$num&service=BZ&mtype=XXXF&msgid=" . time () . "&message=卡号:{$trial[0][0]['card_number']}  密码:{$trial[0][0]['pin']} 卡内余额:{$trial[0][0]['init_amount']}" );
		}
	}
	
	/**
	 * 开始录音
	 */
	public function conf_rec() {
		$this->loadModel ( 'Refill' );
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		$confid = $_REQUEST ['confid'];
		
		if (empty ( $acc ) || empty ( $pass ) || empty ( $confid )) {
			echo "<reply result='5'></reply>";
		}
		
		$accounts = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $accounts ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		$cmd = "conference $confid record /tmp/$confid.wav";
		$result = $this->PhpTelnet->getResult ( "api " . $cmd );
	}
	
	/**
	 * 停止录音
	 */
	public function conf_stop_rec() {
		$this->loadModel ( 'Refill' );
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		$confid = $_REQUEST ['confid'];
		
		if (empty ( $acc ) || empty ( $pass ) || empty ( $confid )) {
			echo "<reply result='5'></reply>";
		}
		
		$accounts = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $accounts ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		$cmd = "conference $confid norecord /tmp/$confid.wav";
		$result = $this->PhpTelnet->getResult ( "api " . $cmd );
	}
	
	//群发短信
	public function sms_snd() {
		Configure::write('debug',0);
		$acc = $_REQUEST ['acc'];
		$pass = $_REQUEST ['pass'];
		$recpnum = $_REQUEST ['recpnum'];
		$content = $_REQUEST ['content'];
		$this->loadModel ( 'Refill' );
		
		if (empty ( $acc ) || empty ( $pass ) || empty ( $recpnum )) {
			echo "<reply result='5'></reply>";
			exit ();
		}
		
		$account = $this->Refill->query ( "select card_id from card where card_number = '$acc' and pin = '$pass'" );
		if (count ( $account ) == 0) {
			echo "<reply result='3'></reply>";
			exit ();
		}
		
		if ($this->Refill->check_balance ( 1, $recpnum, $content, $account [0] [0] ['card_id'] )) {
			$cost_rs = $this->Refill->msg_cost ( 1, $recpnum, $content, $account [0] [0] ['card_id'] );
			if ($cost_rs != false){
				$interface_url = $this->Refill->query("select url from msg_interface where status = true");
				$result = $this->httpRequestGET ( "{$interface_url[0][0]['url']}&mobile=$recpnum&service=BZ&mtype=XXXF&msgid=" . time () . "&message=$cost_rs" );
			} else {
				echo "<reply result='255'></reply>";
			}
		} else {
			echo "<reply result = '11'></reply>";
		}
	}
	
public function httpRequestGET($url) {
		$url2 = parse_url ( $url );
		$url2 ["path"] = ($url2 ["path"] == "" ? "/" : $url2 ["path"]);
		$url2 ["port"] = ($url2 ["port"] == "" ? 80 : $url2 ["port"]);
		$host_ip = @gethostbyname ( $url2 ["host"] );
		$fsock_timeout = 20;
		if (($fsock = fsockopen ( $host_ip, 80, $errno, $errstr, $fsock_timeout )) < 0) {
			return false;
		}
		
		$request = $url2 ["path"] . ($url2 ["query"] != "" ? "?" . $url2 ["query"] : "") . ($url2 ["fragment"] != "" ? "#" . $url2 ["fragment"] : "");
		$in = "GET " . $request . " HTTP/1.0\r\n";
		$in .= "Accept: */*\r\n";
		$in .= "User-Agent: Payb-Agent\r\n";
		$in .= "Host: $host_ip\r\n";
		$in .= "Connection: Close\r\n\r\n";
		if (! @fwrite ( $fsock, $in, strlen ( $in ) )) {
			fclose ( $fsock );
			return false;
		}
		unset ( $in );
		
		$out = "";
		while ( $buff = @fgets ( $fsock, 2048 ) ) {
			$out .= $buff;
		}
		fclose ( $fsock );
		$pos = strpos ( $out, "\r\n\r\n" );
		$head = substr ( $out, 0, $pos ); //http head
		$status = substr ( $head, 0, strpos ( $head, "\r\n" ) ); //http status line
		$body = substr ( $out, $pos + 4, strlen ( $out ) - ($pos + 4) ); //page body
		if (preg_match ( "/^HTTP\/\d\.\d\s([\d]+)\s.*$/", $status, $matches )) {
			if (intval ( $matches [1] ) / 100 == 2) {
				return $body;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>