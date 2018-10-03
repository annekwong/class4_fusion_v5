<?php
class YeepaysController extends AppController {
	var $name = 'Yeepays';
	var $uses = array ();
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	function callback() {
		include 'YeePayCommon.php';
		#	解析返回参数.
		$return = getCallBackValue ( $r0_Cmd, $r1_Code, $p1_MerId, $p2_Order, $p3_Amt, $p4_FrpId, $p5_CardNo, $p6_confirmAmount, $p7_realAmount, $p8_cardStatus, $p9_MP, $pb_BalanceAmt, $pc_BalanceAct, $hmac );
		#	判断返回签名是否正确（True/False）
		$bRet = CheckHmac ( $r0_Cmd, $r1_Code, $p1_MerId, $p2_Order, $p3_Amt, $p4_FrpId, $p5_CardNo, $p6_confirmAmount, $p7_realAmount, $p8_cardStatus, $p9_MP, $pb_BalanceAmt, $pc_BalanceAct, $hmac );
		#	以上代码和变量不需要修改.
		#	校验码正确.
		if ($bRet) {
			echo "success";
			if ($r1_Code == "1") {
			$this->loadModel('Refill');
			$this->Refill->card_refill($p3_Amt,3);
			$admin_c = $this->Session->read("admin_c");
			if (!empty($admin_c)){
				$this->Session->write('admin_c','');
				$this->set('adm_c',true);
			}
				exit ();
			} else if ($r1_Code == "2") {
				echo "<br>支付失败!";
				echo "<br>商户订单号:" . $p2_Order;
				exit ();
			}
		} else {
			
			$sNewString = getCallbackHmacString ( $r0_Cmd, $r1_Code, $p1_MerId, $p2_Order, $p3_Amt, $p4_FrpId, $p5_CardNo, $p6_confirmAmount, $p7_realAmount, $p8_cardStatus, $p9_MP, $pb_BalanceAmt, $pc_BalanceAct );
			echo "<br>localhost:" . $sNewString;
			echo "<br>YeePay:" . $hmac;
			echo "<br>交易签名无效!";
			exit ();
		}
	}
	function req() {}
	function index() {}
	
	function refill($amt){
		$this->loadModel('Refill');
		if ($this->Refill->card_refill($amt,3)){
			$this->Refill->create_json_array('',201,'充值成功');
		} else {
			$this->Refill->create_json_array('',101,'充值失败');
		}
		$this->Session->write('m',Refill::set_validator());
		
		$admin_c = $this->Session->read("admin_c");
		if (!empty($admin_c)){
			$this->Session->write('admin_c','');
			$this->redirect ( '/resellerpayments/payment_list' );
		} else {
			$this->redirect ( '/selfhelps/payment_list' );
		}
	}
	
	function refill_f($amt,$card,$cause){
		$this->loadModel('Refill');
		$pass = $_SESSION['yp_pass'];
		$this->Refill->refill_fail($amt,3,$card,$pass,$cause);
		$admin_c = $this->Session->read("admin_c");
		if (!empty($admin_c)){
			$this->Session->write('admin_c','');
			$this->redirect ( '/resellerpayments/payment_list' );
		} else {
			$this->redirect ( '/selfhelps/payment_list' );
		}
	}
}