<?php
class Curr extends AppModel{
	var $name = 'Curr';
	var $useTable = 'currency';
	var $primaryKey = 'currency_id';
	/**
	 *  分页查询币率
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	
	
	function add($data){
		
		$data['Curr']['update_by'] = $_SESSION['sst_user_name'];
		$this->bindModel(Array('hasMany'=>Array('Currupdate'=>Array('limit'=>1))));
		$this->begin();
		if(!$this->xsave($data['Curr'])){
			$this->rollback();
			return false;
		}
		$data['Currupdate']['currency_id']=$this->getlastinsertId();
		$data['Currupdate']['rate']=$data['Curr']['rate'];
		if(!$this->Currupdate->xsave($data['Currupdate'])){
			$this->rollback();
			return false;
		}
		$this->commit();
		return true;
	}
	function edit($data){
		$this->bindModel(Array('hasMany'=>Array('Currupdate'=>Array('limit'=>1))));
		$this->begin();
		$CurrupdateList=$this->Currupdate->find('first',Array('conditions'=>Array('currency_id'=>$data['Curr']['currency_id']),'order'=>'modify_time desc'));
		 if(array_keys_value($CurrupdateList,'Currupdate.rate')==$data['Curr']['rate']){
		 	
		 }else{
                        $data['Currupdate']['modify_time'] = date("Y-m-d H:i:s");
		 	$data['Currupdate']['currency_id']=$data['Curr']['currency_id'];
			$data['Currupdate']['rate']=$data['Curr']['rate'];
			$data['Currupdate']['last_rate']=array_keys_value($CurrupdateList,'Currupdate.rate');
                        $sql = "INSERT INTO currency_updates(modify_time,currency_id,rate,last_rate) VALUES ('{$data['Currupdate']['modify_time']}', '{$data['Currupdate']['currency_id']}', '{$data['Currupdate']['rate']}', '{$data['Currupdate']['last_rate']}')";
                        /*
                        $results =  $this->Currupdate->query("INSERT INTO currency_updates SET modify_time = '{$data['Currupdate']['modify_time']}',
                            currency_id = '{$data['Currupdate']['currency_id']}',rate = '{$data['Currupdate']['rate']}', last_rate = '{$data['Currupdate']['last_rate']}' WHERE currency_id = {$data['Currupdate']['currency_id']}
                        ");        
                        print_r($data['Currupdate']);
			if(!$this->Currupdate->save($data['Currupdate'])){
				$this->rollback();
				return false;
			}
                         */
                        $this->Currupdate->query($sql);
		 }
                 $date = date("Y-m-d H:i:s");
                 //$this->query("update currency_updates  set modify_time = '{$date}' where  currency_id = {$data['Curr']['currency_id']}");
                 $data['Curr']['update_by'] = $_SESSION['sst_user_name'];
		 if(!$this->xsave($data)){
		 	$this->rollback();
			return false;
		 }
		$this->commit();
		return true;
	}
	public function getAllCurrencies($currPage=1,$pageSize=15,$search=null,$reseller_id=null,$order=null){
		 if(!empty($order))
		 {
		 	$order="order by $order";
		 }else{
		    $order="order by currency_id desc";
		 }
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		
		$sql = "select count(currency_id) as c from currency where 1=1";
		if (!empty($search)) $sql .= " and code ilike '%$search%'";
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
		$sql = "select currency_id,code,active,
							(select count(rate_table_id) from rate_table where currency_id = curr.currency_id) as rates,
							(select rate from currency_updates 
								where currency_id = curr.currency_id 	
								and modify_time=(select max(modify_time) from currency_updates where currency_id = curr.currency_id)) as rate,
							(select max(modify_time) from currency_updates 
								where currency_id = curr.currency_id 	
							) as last_modify,
							(select count(rate_table_id) from rate_table 
								where currency_id  = curr.currency_id) as usage
							from currency as curr where 1=1 $order";
		
		if (!empty($search)) $sql .= " and code ilike '%$search%'";
		if (!empty($reseller_id)) $sql .= " and reseller_id = '$reseller_id'";
		$sql .= " limit '{$pageSize}' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		pr($page);
		return $page;
	}
	
	
/**
	 *  分页查询币率
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllUpdates($currPage=1,$pageSize=15,$currency_id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$totalrecords = $this->query("select count(currency_id) as c from currency_updates where currency_id = '$currency_id'");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select currency_updates_id,currency_id,modify_time,rate,last_rate from currency_updates where currency_id = '$currency_id' order by modify_time desc limit '$pageSize' offset '$offset' $order";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function del($id){
		$qs = $this->query("delete from currency where currency_id in ($id)");
		return count($qs) == 0;
	}
}