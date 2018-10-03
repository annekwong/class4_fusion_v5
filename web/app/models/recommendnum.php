<?php
class Recommendnum extends AppModel{
	var $name = 'Recommendnum';
	var $useTable = 'recommend_record';
	var $primaryKey= 'recommend_record_id';
	
	/**
	 * 帐户推荐号码
	 * @param  $num  号码(数组)
	 */
	public function add_num($num){
		$loop = count($num);
		$account_id = $_SESSION['card_id'];
		
		//该帐户所属的代理商
		$reseller_id = $this->query("select reseller_id from card where card_id = $account_id");
		$qs_count = 0;
		
		$this->begin();
		for ($i=0;$i<$loop;$i++) {
			$sql = "insert into recommend_record(account_id,recommend_number,reseller_id)
								 values($account_id,'$num[$i]',{$reseller_id[0][0]['reseller_id']})";
			$qs = $this->query($sql);
			$qs_count += count($qs);
		}
		
		if ($qs_count == 0){
			$this->commit();
			return true;
		} else {
			$this->rollback();
			return false;
		}
	}
	
	public function view_recommend($currPage,$pageSize,$reseller_id,$adv_search){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		
		$sql = "select count(recommend_record_id) as c from recommend_record where 1=1";
		if (!empty($reseller_id)) $sql .= " and reseller_id = $reseller_id";
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select recommend_record_id,(select card_number from card where card_id=recommend_record.account_id) as account,
		recommend_number,recommend_time ,
		(select count(card_id) from card where number = recommend_record.recommend_number) >0 as reg
		from recommend_record where 1=1 $adv_search";
		
		if (!empty($reseller_id)) $sql .= " and reseller_id = $reseller_id";
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function get_cards($reseller_id){
		$sql = "select card_id,card_number from card";
		if (!empty($reseller_id)){
			$sql .= " where reseller_id = $reseller_id";
		}
		return $this->query($sql);
	}
} 
?>