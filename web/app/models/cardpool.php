<?php
class Cardpool extends AppModel{
	var $name = 'Cardpool';
	var $useTable = 'card_series';
	var $primaryKey = 'card_series_id';
	
/**
	 *  分页查询Client groups
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllSeries($currPage=1,$pageSize=15,$search=null,$reseller_id,$adv_search){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql = "select count(card_series_id) as c from card_series where 1=1 $adv_search";
		if (!empty($search)) $sql .= " and name like '%$search%'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select trial,status,card_series_id,name,(select name from rate_table where rate_table_id = c.rate_table_id) as rate,
							(select name from sales_strategy where sales_strategy_id = c.sales_strategy_id) as sales_strategy,
							(select name from bonus_strategy where bonus_strategy_id = c.bonus_strategy_id) as bonus_strategy,
							(select count(card_id) from card where card_series_id = c.card_series_id) as ofcards,
							prefix,password_length,expire_type,expire_days,start_num from card_series as c where 1=1 $adv_search";
		
		if (!empty($search)) $sql .= " and name like '%$search%'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	/*
	 * 得到费率   充值策略   送话费的策略
	 */
	public function getAddInfo($reseller_id){
		$rates = "select rate_table_id,name from rate_table";
		$sales_sql = "select sales_strategy_id,name from sales_strategy";
		$bonus_sql = "select bonus_strategy_id,name from bonus_strategy";
		if (!empty($reseller_id)) {
			$rates .= " where reseller_id = '$reseller_id'";
			$sales_sql .= " where reseller_id = '$reseller_id'";
			$bonus_sql .= " where reseller_id = '$reseller_id'";
		}
		return array($this->query($rates),$this->query($sales_sql),$this->query($bonus_sql));
	}
	
	public function getById($id){
		return $this->query("select * from card_series
																where card_series_id = '$id'");
	}
	
	
	
/**
	 *  分页查询Card
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllCards($currPage=1,$pageSize=15,$search=null,$id,$advanceSearch){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$login_type = $_SESSION['login_type'];
		
		
		if (!empty($search)) {
			$sql = "select count(card_id) as c from card where card_series_id ='$id' and card_number like '%$search%' $advanceSearch";
		} else {
			$sql = "select count(card_id) as c from card where card_series_id ='$id' $advanceSearch";
		}
		
		if ($login_type != 1) {
			$sql .= " and visible=true";
		}
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select card_series_id,blacklist,old_user,card_id,card_number,low_cost_strategy_id,trial,
							(select balance from account_balance where account_id = card.card_id::character varying
							 order by account_balance_id desc limit 1) as balance_now,
							 (select gift_amount_balance from account_balance where account_id = card.card_id::character varying
							  order by account_balance_id desc limit 1) as gift_amount,
							  (select sum(amount) from account_payment where account_id = card.card_id) as total,
							(select count(card_sip_id) from card_sip where card_id = card.card_id) as sips,
							(select count(card_sip_id) from card_sip where card_id = card.card_id and login_status = 0) as logoutsips,
							(select name from package where package_id = card.package_id) as package,
							(select code from currency where currency_id = (select currency_id from rate_table where rate_table_id = card.rate_table_id)) as currency,
							(select point_balance from account_point where account_id = card.card_id order by account_point_id desc limit 1) as points,
							(select name from card_series where card_series_id = card.card_series_id) as series,
							(select name from rate_table where rate_table_id = card.rate_table_id) as rate,
							(select name from sales_strategy where sales_strategy_id =  card.sales_strategy_id) as sales,
							(select name from bonus_strategy where bonus_strategy_id =  card.bonus_strategy_id) as bonus,
							expire_date,create_date,effective_date,pin,active,
							(select * from calc_account_status(card.card_id)) as status,
							(select status from card_series where card_series_id = card.card_series_id) as series_status,
							(select count(account_payment_id) from account_payment where account_id = card.card_id) as a_type,
							(select name from reseller where reseller_id = card.reseller_id) as reseller,number,ani_bind_num from card
							where card_series_id = '$id'";
		
		
		if (!empty($advanceSearch)) {
			$sql .= $advanceSearch;
		} 
		
	if ($login_type != 1) {
			$sql .= " and visible=true";
		}
		
		if (!empty($search)) {
			$sql .= " and card_number like '%$search%' limit '$pageSize' offset '$offset'";
		} else {
			$sql .= " limit '$pageSize' offset '$offset'";
		}
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
/**
	 *  分页查询Card
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllCardsAll($currPage=1,$pageSize=15,$search=null,$advanceSearch){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		$r_id = $_SESSION['sst_reseller_id'];
		$series_ids = '';
		if (!empty($r_id))
			$series_ids = $this->query("select card_series_id from card_series where reseller_id = $r_id");
		else
			$series_ids = $this->query("select card_series_id from card_series");
			
		$loop = count($series_ids);
		$id_str = '-1';
		for ($i=0;$i<$loop;$i++) $id_str .= ",".$series_ids[$i][0]['card_series_id'];
		$totalrecords = 0;
		
		
	$login_type = $_SESSION['login_type'];
		
		$sql = "select count(card_id) as c from card where card_series_id in($id_str) $advanceSearch";
		$sql = "select count(card_id) as c from card where card_series_id in($id_str) and card_number like '%$search%' $advanceSearch";
		if (!empty($search)) {
			$sql = "select count(card_id) as c from card where card_series_id in($id_str) and card_number like '%$search%' $advanceSearch";
		} else {
			$sql = "select count(card_id) as c from card where card_series_id in($id_str) $advanceSearch";
		}
		
	if ($login_type != 1) {
			$sql .= " and visible=true";
		}
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select card_series_id,blacklist,old_user,card_id,card_number,card_series_id,low_cost_strategy_id,trial,(select balance from account_balance 
							where account_id = card.card_id::character varying order by account_balance_id desc limit 1) as balance_now, 
							(select gift_amount_balance from account_balance where account_id = card.card_id::character varying
							  order by account_balance_id desc limit 1) as gift_amount,
							  (select sum(amount) from account_payment where account_id = card.card_id) as total,
							(select count(card_sip_id) from card_sip where card_id = card.card_id) as sips,
							(select count(card_sip_id) from card_sip where card_id = card.card_id and login_status = 0) as logoutsips,
							(select name from package where package_id = card.package_id) as package,
							(select code from currency where currency_id = (select currency_id from rate_table where rate_table_id = card.rate_table_id)) as currency,
							(select point_balance from account_point where account_id = card.card_id order by account_point_id desc limit 1) as points,
							(select name from card_series where card_series_id = card.card_series_id) as series,
							(select name from rate_table where rate_table_id = card.rate_table_id) as rate,
							(select name from sales_strategy where sales_strategy_id =  card.sales_strategy_id) as sales,
							(select name from bonus_strategy where bonus_strategy_id =  card.bonus_strategy_id) as bonus,
							expire_date,create_date,effective_date,pin,active,
							(select * from calc_account_status(card.card_id)) as status,
							(select status from card_series where card_series_id = card.card_series_id) as series_status,
							(select count(account_payment_id) from account_payment where account_id = card.card_id) as a_type,
							(select name from reseller where reseller_id = card.reseller_id) as reseller,number from card
							where (card_series_id in($id_str) or card_series_id is null";
		
		if (!empty($r_id)){
			$sql .= " and reseller_id = $r_id";
		}
		$sql .= ")";
		
		if (!empty($advanceSearch)) {
			$sql .= $advanceSearch;
		} 
		
	if ($login_type != 1) {
			$sql .= " and visible=true";
		}
		
		if (!empty($search)) {
			$sql .= " and (card_number like '%$search%'  or number::prefix_range<@ '$search')limit '$pageSize' offset '$offset'";
		} else {
			$sql .= " limit '$pageSize' offset '$offset'";
		}
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
/**
	 *  分页查询Card
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllCardsByReseller($currPage=1,$pageSize=15,$search=null,$advanceSearch,$reseller_id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
			
		$login_type = $_SESSION['login_type'];
		$sql = "select count(card_id) as c from card where reseller_id ='$reseller_id' and card_number like '%$search%' $advanceSearch";
		if (!empty($search)) {
			$sql = "select count(card_id) as c from card where reseller_id ='$reseller_id' and card_number like '%$search%' $advanceSearch";
		} else {
			$sql = "select count(card_id) as c from card where reseller_id ='$reseller_id' $advanceSearch";
		}
		
		if ($login_type != 1){
			$sql .= " and visible = true";
		}
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select blacklist,old_user,card_id,card_number,card_series_id,low_cost_strategy_id,trial,(select balance from account_balance 
							where account_id = card.card_id::character varying order by account_balance_id desc limit 1) as balance_now,
							(select gift_amount_balance from account_balance where account_id = card.card_id::character varying
							  order by account_balance_id desc limit 1) as gift_amount,
							 (select sum(amount) from account_payment where account_id = card.card_id) as total,
							(select count(card_sip_id) from card_sip where card_id = card.card_id) as sips,
							(select count(card_sip_id) from card_sip where card_id = card.card_id and login_status = 0) as logoutsips,
							(select name from package where package_id = card.package_id) as package,
							(select code from currency where currency_id = (select currency_id from rate_table where rate_table_id = card.rate_table_id)) as currency,
							(select point_balance from account_point where account_id = card.card_id order by account_point_id desc limit 1) as points,
							(select name from card_series where card_series_id = card.card_series_id) as series,
							(select name from rate_table where rate_table_id = card.rate_table_id) as rate,
							(select name from sales_strategy where sales_strategy_id =  card.sales_strategy_id) as sales,
							(select name from bonus_strategy where bonus_strategy_id =  card.bonus_strategy_id) as bonus,
							expire_date,create_date,effective_date,pin,active,
							(select * from calc_account_status(card.card_id)) as status,
							(select status from card_series where card_series_id = card.card_series_id) as series_status,
							(select count(account_payment_id) from account_payment where account_id = card.card_id) as a_type,
							(select name from reseller where reseller_id = card.reseller_id) as reseller,number from card
							where reseller_id = $reseller_id";
		
		
		if (!empty($advanceSearch)) {
			$sql .= $advanceSearch;
		} 
		
	if ($login_type != 1){
			$sql .= " and visible = true";
		}
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function getAllResellers($reseller_id){
		return $this->query("select * from get_reseller($reseller_id)");
	}
	
	public function getAllRates($reseller_id){
		$sql = "select rate_table_id,name from rate_table";
		if (!empty($reseller_id))$sql .= " where reseller_id = '$reseller_id'";
		return $this->query($sql);
	}
	
	public function getCardById($card_id){
		return $this->query("select * from card where card_id = '$card_id'");
	}
	
	public function getRoutePack($reseller_id){
		$pc = "select package_id,name from package";
		$level = "select account_level_id,level_name from account_level";
		if (!empty($reseller_id)) {
			$pc .= " where reseller_id = $reseller_id";
			$level .= " where reseller_id = $reseller_id";
		}
		return array($this->query($pc),$this->query($level));
	}
	
	public function getMsgCharges(){
		$one2many = "select msg_charges_id,charge_name from sms_charges where msg_type = 1";
		$one2one = "select msg_charges_id,charge_name from sms_charges where msg_type = 5";
		$findpwd = "select msg_charges_id,charge_name from sms_charges where msg_type = 2";
		$viewbalance = "select msg_charges_id,charge_name from sms_charges where msg_type = 3";
		$acc_rec = "select msg_charges_id,charge_name from sms_charges where msg_type = 4";
		return array(
			$this->query($one2one),
			$this->query($one2many),
			$this->query($findpwd),
			$this->query($viewbalance),
			$this->query($acc_rec)
		);
	}
	
	public function getLowCostCharges($reseller_id){
		$sql = "select low_cost_strategy_id,strategy_name from low_cost_strategy";
		if (!empty($reseller_id)) $sql .= " where reseller_id = $reseller_id";
		return $this->query($sql);
	}
	
	public function getRoutePolicy(){
		return $this->query("select * from route_strategy");
	}
	
	public function searchInfo($reseller_id){
		$prefix = "select distinct prefix from card_series";
		$start_num = "select distinct start_num from card_series";
		if (!empty($reseller_id)){
			$prefix .= " where reseller_id = $reseller_id";
			$start_num .= " where reseller_id = $reseller_id";
		}
		return array(
			$this->query($prefix),
			$this->query($start_num)
		);
	}
	
	
	public function getGroup($reseller){
		$sql = "select client_group_id,name from client_group";
		if (!empty($reseller)){$sql .= " where reseller_id = $reseller";}
		return $this->query($sql);
	}
	
	
	public function getAccount_type(){
		$sql = "select  account_type_id ,name  from  account_type";
		
		return $this->query($sql);
	}
	
/**
	 *  分页查询Client groups
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllPoints($currPage=1,$pageSize=15,$account_id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql = "select count(account_point_id) as c from account_point where account_id = '$account_id'";
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select (select card_number from card where card_id = account_point.account_id) as account,point_balance,create_time,point_type from account_point where account_id = '$account_id'";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	/**
	 * 查找推荐策略
	 * @param unknown_type $reseller_id
	 */
	public function get_recommend($reseller_id){
		$sql = "select recommend_strategy_id,strategy_name from recommend_strategy";
		if (!empty($reseller_id)) {
			$sql .= " where reseller_id = $reseller_id";
		}
		return $this->query($sql);
	}
	
	/**
	 * 查看帐户的增值业务
	 * @param unknown_type $card_id  帐户ID
	 */
	public function get_account_service($card_id){
		$no = $this->query ( "select * from value_add_service where service_id not in (select service_id from card_service where  card_id = $card_id)" );
		$yes = $this->query ( "select * from value_add_service where service_id in (select service_id from card_service where  card_id = $card_id and result = 1)" );
		return array($no,$yes);
	}
	
	/*
	 * 得到reseller的主管节点
	 */
	public function getParentResellers($reseller_id = null){
		return $this->query("select reseller_id,name from reseller where reseller_id = $reseller_id or reseller_id in (select * from reseller_of_reseller ($reseller_id))");		
	}
	
	public function checkBalanceEnough($card_id,$min_amount,$reseller_id){
		if ($reseller_id != null) {
			$parent_enough = $this->query("select enough_balance from reseller where reseller_id = $reseller_id");
			if ($parent_enough[0][0]['enough_balance'] == false) {
				return false;
			}
		}
		
		$balance = $this->query("select balance from account_balance
											where account_id = '$card_id'
											order by account_balance_id  desc limit 1");
		
		if (count($balance) > 0) {
			if ($balance[0][0]['balance'] < $min_amount)
				return false;
		} else {
			if ($min_amount > 0)
				return false;
		}
		
		return true;
	}
}