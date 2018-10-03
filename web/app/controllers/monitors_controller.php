<?php
class MonitorsController extends AppController{
	var $name = 'Monitors';
	var $uses = array('Hostinfo');
	var $helpers = array('javascript','html');
	var $components =array('MonitorTelnet');  
	
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
	   parent::beforeFilter();//调用父类方法
	}
	/**
	 * 负责Monitor模块所有页面的跳转
	 * @param unknown_type $type //表示跳到的页面的代号
	 * @param unknown_type $pro_id //Product Id  在 Resource 点击Resource 选项卡 则代表resource Id
	 * @param unknown_type $gresstype //网关类型 Egress Or Ingress
	 */
	function monitor($type=null,$pro_id=null,$gresstype=null){
		Configure::write('debug' , 0);
		
		switch ($type) {
			case 1 ://选中Gobal Stats选项卡
				break;
				
			case 12 ://选中Product Stats 选项卡
				$currpage = $_GET['page'] == null ? 1 : $_GET['page'];
				$pagesize = $_GET['size'] == null ? 10 : $_GET['size'];
				$search = null;
				if (!empty($_REQUEST['search'])) {
					$search = $_REQUEST['search'];
					$this->set('search',$search);
				}
				$page = $this->product_info($currpage,$pagesize,$search);//查询产品信息
				$this->set('p',$page);
				break;
				
			case 11 ://跳转到Product的报表界面
				$this->set('pro_id',$pro_id);
				break;
				
			case 21://选中Resource Stats 选项卡中的Egress选项卡
				$grs = 'egress';
				$gressIndex = 1;//代表选中Egress选项卡
				$this->set('grs',$grs);
				$this->set('gressIndex',$gressIndex);
				
				$currpage = $_GET['page'] == null ? 1 : $_GET['page'];
				$pagesize = $_GET['size'] == null ? 10 : $_GET['size'];
				
				$search = null;
			 if (!empty($_REQUEST['search'])) {
					$search = $_REQUEST['search'];
					$this->set('search',$search);
				}
				
				$page = $this->getgressinfo(1,$currpage,$pagesize,$search);//查询Egress的信息
				$this->set('p',$page);
				break;
				
		 case 22://选中Resource Stats 选项卡中的Ingress选项卡
				$grs = 'ingress';
				$gressIndex = 2;//代表选中Ingress选项卡
				$this->set('grs',$grs);
				$this->set('gressIndex',$gressIndex);
				
				$currpage = $_GET['page'] == null ? 1 : $_GET['page'];
				$pagesize = $_GET['size'] == null ? 10 : $_GET['size'];
				
			 $search = null;
			 if (!empty($_REQUEST['search'])) {
					$search = $_REQUEST['search'];
					$this->set('search',$search);
				}
				
				
				$page = $this->getgressinfo(0,$currpage,$pagesize,$search);//查询Ingress的信息
				$this->set('p',$page);
				break;
				
		case 23://跳转到Resource的报表界面
		 	$this->set('res_id',$pro_id);//Resource ID
		 	$this->set('gress_type',$gresstype);
		 	break;
		 	
		case 24://跳转到Resource 对应的Ip的报表界面
		 	$this->set('ip_addr',$pro_id);
		 	break;
		}
		$this->set('stats' ,$type);
	}
	
	/**
	 * 
	 * @param $whichTime 
	 *  等于1代表：查询24小时以内的记录
	 *  等于2代表：查询3小时以内的记录
	 *  等于3代表：查询1小时以内的记录
	 *  等于4代表：查询3天以内的记录
	 *  等于5代表：查询7天以内的记录
	 */
	public function report(){
		Configure::write('debug' , 0);
		//$this->layout = '';//不使用layout
		$whichTime = $_REQUEST['whichTime'];//表示查询哪个时间段的参数
		$time = time();//当前时间
		
		$times = array();//存储所有的拆分后的时间段
		//计算要查询的时间段
		switch ($whichTime){
			case 1 :
				//20 * 24 代表24个小时有多少个3分钟
				//20 代表1小时是20个3分钟
				//+2 我也不知道为什么
				//每次算出来都少算了6分钟 所以加了2  造成原因应该是时区的问题
				for ($i = 0;$i<20*24+2;$i++) {
					$times[$i] = ($time) - ($i)*180;
				}
				
				break;
			
			case 2 :
				//20 * 3 代表3个小时有多少个3分钟
				//20 代表1小时是20个3分钟
				//+2 我也不知道为什么
				//每次算出来都少算了6分钟 所以加了2  造成原因应该是时区的问题
				for ($i = 0;$i<20*3+2;$i++) {
					$times[$i] = ($time) - ($i)*180;
				}
				
				break;
			
			case 3 :
				//20 * 1 代表1个小时有多少个3分钟
				//20 代表1小时是20个3分钟
				//+2 我也不知道为什么
				//每次算出来都少算了6分钟 所以加了2  造成原因应该是时区的问题
				for ($i = 0;$i<20*1+2;$i++) {
					$times[$i] = ($time) - ($i)*180;
				}
				
				break;
			
			case 4 :
				//3*(20*24+2) 代表3天由多少个3分钟组成
				for ($i = 0;$i<3*(20*24+2);$i++) {
					$times[$i] = ($time) - ($i)*180;
				}
				
				break;
				
			case 5 :
				
				//7*(20*24+2) 代表7天由多少个3分钟组成
				for ($i = 0;$i<7*(20*24+2);$i++) {
					$times[$i] = ($time) - ($i)*180;
				}
				
				break;
			
			default :
				//20 * 24 代表24个小时有多少个3分钟
				//20 代表1小时是20个3分钟
				//+2 我也不知道为什么
				//每次算出来都少算了6分钟 所以加了2  造成原因应该是时区的问题
				for ($i = 0;$i<20*24+2;$i++) {
					$times[$i] = ($time) - ($i)*180;
				}
				
				break;
		}
		
		
		$this->loadModel('Hostinfo');
		
		$finalData = "[";
		
		for ($i=0;$i<count($times);$i++) {
			
			//记录当前的小时间段和下一个小时间段
			if ($i == count($times) - 1) {
				$timeiP1 = $times[$i];
			} else {
				$timeiP1 = $times[$i+1];
			}
			
		/*
		 * $type = 1：查询全局报表数据
		 * $type = 2：查询product的报表数据
		 * $type = 3：查询resource的报表数据
		 * $type = 4：查询IP的报表数据
		 */ 
		$callsql = '';//查询总call数的sql
		$aacpsql = '';//查询ACD、ASR、CPS、PDD
		$type = $_REQUEST['type'];
		switch ($type) {
			case 1:
				//查询Calls
				$callsql = "select round(sum(product.value)/count(product.value)) as totalcall 
												from product_info as product 
												where product.time < $times[$i] and product.time >= $timeiP1";
				
				//查询ACD、ASR、CPS、PDD
				$aacpsql = "select (sum(acd) / sum(call_count)) as acd, 
												(sum(pdd) / sum(call_count)) as pdd,
												round((sum(asr * call_count_asr) / sum(call_count_asr))) as asr,
												round((sum(ca) / (60*3))) as cps from host_info as host
												where  host.direction = 0 and host.time < $times[$i]000000 and host.time >= $timeiP1"."000000";
			break;
			
			case 2:
				$pid = $_REQUEST['pro_id'];
				//查询Calls
				$callsql = "select round(sum(product.value)/count(product.value)) as totalcall 
												from product_info as product 
												where product.product_id = $pid and product.time < $times[$i] and product.time >= $timeiP1";
				
				//查询ACD、ASR、CPS、PDD
				$aacpsql = "select (sum(acd) / sum(call_count)) as acd, 
												(sum(pdd) / sum(call_count)) as pdd,
												round((sum(asr * call_count_asr) / sum(call_count_asr))) as asr,
												round((sum(ca) / (60*3))) as cps from prefix_info as prefix
												where prefix.pro_id = $pid and direction = 1 and prefix.time < $times[$i]000000 and prefix.time >= $timeiP1"."000000";
				break;
				
			case 3:
				$res_id = $_REQUEST['res_id'];//Resource Id
				$gress_type = $_REQUEST['gress_type'];//1:Egress 0:Ingress
				//查询Calls
				$callsql = "select round(sum(resource.value)/count(resource.value)) as totalcall 
												from resource_info as resource 
												where resource.resource_id = $res_id and resource.time < $times[$i] and resource.time >= $timeiP1";
				
				//查询ACD、ASR、CPS、PDD
				$aacpsql = "select (sum(acd) / sum(call_count)) as acd, 
												(sum(pdd) / sum(call_count)) as pdd,
												round((sum(asr * call_count_asr) / sum(call_count_asr))) as asr,
												round((sum(ca) / (60*3))) as cps from host_info as host
												where host.res_id = $res_id and direction = $gress_type and host.time < $times[$i]000000 and host.time >= $timeiP1"."000000";
				break;
				
			case 4:
				$ip_addr = $_REQUEST['ip_addr']; //IP Address
				$callsql = "select round(sum(ip_info.value)/count(ip_info.value)) from ip_info  
												where ip_id = 
												(select resource_ip_id from resource_ip 
													where ip = '$ip_addr'
												)";
				
				//查询ACD、ASR、CPS、PDD
				$aacpsql = "select (sum(acd) / sum(call_count)) as acd, 
												(sum(pdd) / sum(call_count)) as pdd,
												round((sum(asr * call_count_asr) / sum(call_count_asr))) as asr,
												round((sum(ca) / (60*3))) as cps from host_info as host
												where host.ip_id = 
												(
													select resource_ip_id from resource_ip 
													where ip = '$ip_addr'
												)
												and host.time < $times[$i]000000 and host.time >= $timeiP1"."000000";
				break;
		}
			
			
			
			
			$calls = $this->Hostinfo->query($callsql);
			
			$aacps = $this->Hostinfo->query($aacpsql);
			
			$call = $calls[0][0]['totalcall'] == null ? 0 : $calls[0][0]['totalcall'];//Calls
			
			$acd = $aacps[0][0]['acd'] == null ? 0 : $aacps[0][0]['acd'];//ACD
			$cps = $aacps[0][0]['cps'] == null ? 0 : $aacps[0][0]['cps'];//CPS
			$pdd = $aacps[0][0]['pdd'] == null ? 0 : $aacps[0][0]['pdd'];//PDD
			$asr = $aacps[0][0]['asr'] == null ? 0 : $aacps[0][0]['asr'];//ASR
			$tt = date('Y-m-d H:i:s',$times[$i]);
			
			$finalData .= "{date:'$tt',call:$call,cps:$cps,asr:$asr,acd:$acd,pdd:$pdd},";
		}
		
		echo $finalData;
	}
	
	
	/*
	 * 生成指定的时间段
	 */
	private function gettime(){
		$time = time();
		
		$fifteen = $time - 15*60;//15分钟之内的
		
		$ahour = $time - 60*60;//一个小时以内的
		
	 $oneday = $time - 24*60*60;//24小时以内的
	 
	 $times = array();
	 
	 $times[0] = $fifteen;
	 $times[1] = $ahour;
	 $times[2] = $oneday;
	 
/*	 $times[0] = $fifteen."000000";
	 $times[1] = $ahour."000000";
	 $times[2] = $oneday."000000";*/
	 
	 return $times;
	}
	
	/*
	 * 
	 * 
	 * 
	 * 数据库查询历史信息
	 * 查询Global Stats Historycal信息
	 */
	function history(){
		Configure::write('debug' , 0);
                $this->autoLayout = false;
                $this->autoRender = false;
		$this->loadModel('Hostinfo');
	 	$times = $this->gettime();
   $ip='';
	 if(isset($_REQUEST['request_ip'])){
	   if(!empty($_REQUEST['request_ip']) && $_REQUEST['request_ip']!=''){
	    $ip = "'{$_REQUEST['request_ip']}'";
			}
	  }
	 if(empty($ip)&&$ip==''){
	 		$aacpsql = " select *  from  class4_func_monitor_report();";
	 }else{
	 		$aacpsql = " select *  from  class4_func_monitor_ip_report($ip);";
	  }
	 $finalData = '';
	 $aacps = $this->Hostinfo->query($aacpsql);
	 $acd = $aacps[0][0]['acd_15min'] == null ? 0 : $aacps[0][0]['acd_15min'];//ACD
	 $ca = $aacps[0][0]['ca_15min'] == null ? 0 : $aacps[0][0]['ca_15min'];//CA
	 $pdd = $aacps[0][0]['pdd_15min'] == null ? 0 : $aacps[0][0]['pdd_15min'];//PDD
	 
	 $profit = $aacps[0][0]['profit_15min'] == null ? 0 : $aacps[0][0]['profit_15min'];//PDD
	 $asr = $aacps[0][0]['asr_15min'] == null ? 0 : $aacps[0][0]['asr_15min'];//ASR
	 $finalData = "{acd:$acd,asr:$asr,ca:$ca,pdd:$pdd,profit:$profit},";
						 			
 	 $acd = $aacps[0][0]['acd_1h'] == null ? 0 : $aacps[0][0]['acd_1h'];//ACD
	 $ca = $aacps[0][0]['ca_1h'] == null ? 0 : $aacps[0][0]['ca_1h'];//CA
	 $pdd = $aacps[0][0]['pdd_1h'] == null ? 0 : $aacps[0][0]['pdd_1h'];//PDD
	 $profit = $aacps[0][0]['profit_1h'] == null ? 0 : $aacps[0][0]['profit_1h'];//PDD
	 $asr = $aacps[0][0]['asr_1h'] == null ? 0 : $aacps[0][0]['asr_1h'];//ASR
	 $finalData .= "{acd:$acd,asr:$asr,ca:$ca,pdd:$pdd,profit:$profit},";
	
	 $acd = $aacps[0][0]['acd_24h'] == null ? 0 : $aacps[0][0]['acd_24h'];//ACD
	 $ca = $aacps[0][0]['ca_24h'] == null ? 0 : $aacps[0][0]['ca_24h'];//CA
	 $pdd = $aacps[0][0]['pdd_24h'] == null ? 0 : $aacps[0][0]['pdd_24h'];//PDD
	 $profit = $aacps[0][0]['profit_24h'] == null ? 0 : $aacps[0][0]['profit_24h'];//PDD
	 $asr = $aacps[0][0]['asr_24h'] == null ? 0 : $aacps[0][0]['asr_24h'];//ASR
	 $finalData .= "{acd:$acd,asr:$asr,ca:$ca,pdd:$pdd,profit:$profit}";
	  echo '['.$finalData."]";
	}
        
        
      
	
		/*
	 * 查询Product Stats Historycal信息
	 */
	function product_history() {
		Configure::write('debug' , 0);
		$times = $this->gettime();
		$proid = $_REQUEST['pro_id'];
		$finalData = "[";
		
		//查询该Product下所有的Prefix Id
	 	$tsql = "select item_id as pre_id from product_items where product_id = $proid";
	 	$prefixIds = $this->Hostinfo->query($tsql);
	 	$ttt = time()."000000";
	 	/*
	 	 * 查询每个Prefix的ACD、ASR、PDD、CA信息
	 	 */
	 	for ($i = 0;$i<count($prefixIds);$i++) {
	 			$tempData = "[";
	 			$pre_id = $prefixIds[$i][0]['pre_id'];//Prefix Id
	 			
	 			for ($j = 0;$j<count($times);$j++) {
	 				$aacpsql = "select (sum(acd) / sum(call_count)) as acd, 
											(sum(pdd) / sum(call_count)) as pdd,
											round((sum(asr * call_count_asr) / sum(call_count_asr))) as asr,
											sum(ca) as ca,
											(select alias from product_items  where item_id = $pre_id) as pr_name
											from prefix_info as prefix
											where prefix.prefix_id = $pre_id and prefix.time >= $times[$j] and prefix.time < $ttt";
	 					
	 					$aacps = $this->Hostinfo->query($aacpsql);
				 		$acd = $aacps[0][0]['acd'] == null ? 0 : $aacps[0][0]['acd'];//ACD
						$ca = $aacps[0][0]['ca'] == null ? 0 : $aacps[0][0]['ca'];//CA
						$pdd = $aacps[0][0]['pdd'] == null ? 0 : $aacps[0][0]['pdd'];//PDD
						$asr = $aacps[0][0]['asr'] == null ? 0 : $aacps[0][0]['asr'];//ASR
						$pr_name = $aacps[0][0]['pr_name'] == null ? '' : $aacps[0][0]['pr_name'];//Prefix Alias
						$tempData .= "{pr_name:$pr_name,acd:$acd,asr:$asr,ca:$ca,pdd:$pdd},";
	 			}
	 			
	 			$tempData = substr($tempData,0,strlen($tempData)-1);//去掉最后一个逗号
	 			$tempData .= "],";
	 			$finalData .= $tempData;
	 	}
	 	
	 	echo $finalData;
	}
	
		/*
	 * 查询Resource Stats Historycal信息
	 */
function resource_history() {
		Configure::write('debug' , 0);
		$times = $this->gettime();
		$resid = $_REQUEST['res_id'];
		$finalData = "[";
		$ttt = time()."000000";
		//查询该Resource下所有的IP 的Id
	 	$tsql = "select resource_ip_id as ip_id from resource_ip where resource_id = $resid";
	 	$ipIds = $this->Hostinfo->query($tsql);
	 	
	 	for ($i = 0;$i<count($ipIds);$i++) {
	 			$tempData = "[";
	 			$ip_id = $ipIds[$i][0]['ip_id'];//IP Id
	 			
	 			for ($j = 0;$j<count($times);$j++) {
	 				$aacpsql = "select (sum(acd) / sum(call_count)) as acd, 
											(sum(pdd) / sum(call_count)) as pdd,
											round((sum(asr * call_count_asr) / sum(call_count_asr))) as asr,
											sum(ca) as ca,
											(select ip from resource_ip where resource_ip_id = $ip_id) as pr_name
											from host_info as host
											where host.ip_id = $ip_id and host.time >= $times[$j] and host.time < $ttt";
	 					
	 					$aacps = $this->Hostinfo->query($aacpsql);
				 		$acd = $aacps[0][0]['acd'] == null ? 0 : $aacps[0][0]['acd'];//ACD
						$ca = $aacps[0][0]['ca'] == null ? 0 : $aacps[0][0]['ca'];//CA
						$pdd = $aacps[0][0]['pdd'] == null ? 0 : $aacps[0][0]['pdd'];//PDD
						$asr = $aacps[0][0]['asr'] == null ? 0 : $aacps[0][0]['asr'];//ASR
						$pr_name = $aacps[0][0]['pr_name'] == null ? "" : $aacps[0][0]['pr_name'];//IP Address
						$tempData .= "{pr_name:'$pr_name',acd:$acd,asr:$asr,ca:$ca,pdd:$pdd},";
	 			}
	 			
	 			
	 			$tempData = substr($tempData,0,strlen($tempData)-1);//去掉最后一个逗号
	 			$tempData .= "],";
	 			$finalData .= $tempData;
	 	}
	 	
	 	echo $finalData;
	}
	
	/**
	 * 分别查询15分钟  一个小时  24小时以内的product infomation
	 */
	private function product_info($currPage=1,$pageSize=10,$search=null){
		Configure::write('debug' , 0);
		$this->loadModel('Hostinfo');
		$times = $this->gettime();
	 	$ttt = time();
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$count_sql = "select count(product_id) as c from product";
		if (!empty($search)) $count_sql .= " where name like '%$search%'";
		
	 	$totalrecords = $this->Hostinfo->query($count_sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		//////////////////////////////////////////
		
		
	 $finalResults = array();
	 $cid_sql = "select product_id  as count_id from product";
	 if (!empty($search)) $cid_sql .= " where name like '%$search%'";
	 $cid_sql .= " limit $pageSize offset '$currPage'";
	 
	 $cids = $this->Hostinfo->query($cid_sql);
	 for ($i = 0; $i<count($cids); $i++) {
	 	$pid = $cids[$i][0]['count_id'];
	 	$results = array();
	 	for ($j = 0; $j<count($times); $j++) {
	 			$sql = "select (sum(acd) / sum(call_count)) as acd,
	 						(sum(pdd) / sum(call_count)) as pdd,
	 						round((sum(asr * call_count_asr) / sum(call_count_asr))) as asr,
	 						sum(ca)  as ca,
	 						$pid as pro_id,
	 						(select name from product where product_id = $pid) as pro_name,
	 						(
							select round(sum(product.value)/count(product.value))
							from product_info as product 
							where product.product_id = $pid
							and product.time <= $times[2]
							) as totalcall
							from prefix_info as prefix
							where prefix.pro_id = $pid and prefix.time >= $times[$j] and prefix.time < $ttt"."000000";
	 						

	 			array_push($results,$this->Hostinfo->query($sql));
		 }
		 array_push($finalResults,$results);
	 }
	 $page->setDataArray($finalResults);
	 return $page;
	}

	//通过Ajax请求存储值到Session中
	function writesession($value = 'false',$type=null){
		Configure::write('debug' , 0);
		$this->Session->write($type,$value);
	}
	
	/*
	 * 查询15分钟 一个小时 24小时以内的Egress或Ingress的信息
	 */
	private function getgressinfo($eorin,$currPage=null,$pageSize=null,$search=null){
		Configure::write('debug' , 0);
		$this->loadModel('Hostinfo');
		$times = $this->gettime();
	 //分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$count_res = "select resource_id from resource";
		if ($eorin == 1)
				$count_res .= " where egress = true";
	 	else
	 			$count_res .= " where ingress = true";
	 			
	 	if (!empty($search))
	 		$count_res .= " and name like '%$search%'";
	 		
	 	$totalrecords = $this->Hostinfo->query($count_res);
	 	
		$page->setTotalRecords(count($totalrecords));//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		//////////////////////////////////////////
		
	 $finalResults = array();
	 
	 $f = time();
	 
	 $cid_sql = "select resource_id as count_id from resource ";
	 if ($eorin == 1)
	 		$cid_sql .= " where egress = true ";
	 	else
	 		$cid_sql .= " where ingress = true ";
	 		
	 if (!empty($search))
	 	 $cid_sql .= " and name like '%$search%'";
	 	
	 	 $cid_sql .= " limit $pageSize offset $currPage";
	 	 
	 $cids = $this->Hostinfo->query($cid_sql);
	 	 
	 for ($i = 0; $i<count($cids); $i++) {
	 	$pid = $cids[$i][0]['count_id'];
	 	$results = array();
	 	for ($j = 0; $j<count($times); $j++) {
	 			$sql = "select (sum(acd) / sum(call_count)) as acd,
	 						(sum(pdd) / sum(call_count)) as pdd,
	 						round((sum(asr * call_count_asr) / sum(call_count_asr))) as asr,
	 						sum(ca)  as ca,
	 						$pid as res_id,
	 						(select name from resource where resource_id = $pid) as res_name,
	 						(
							select round(sum(resource.value)/count(resource.value))
							from resource_info as resource
							where resource.resource_id = $pid
							and resource.time <= $times[2]
							) as totalcall
							from host_info as host
							where host.res_id = $pid and host.time >= $times[$j] and host.time < $f"."000000";

	 			array_push($results,$this->Hostinfo->query($sql));
		 }
		 array_push($finalResults,$results);
	 }
	 $page->setDataArray($finalResults);
	 return $page;
	}
	/*
	 * Ajax 发送命令到Socket 并返回执行命令结果
	 */
	function send($cmd=null){
		Configure::write('debug' , 0);
		$options=Array();
		$host=$this->_get('host');
		if(!empty($host)){
			$options['host']=$host;
                        $options['port']=1024;
		}
		try{
		$result = $this->MonitorTelnet->getResult("api ".$cmd,$options);
		}catch(Exception $e){
			pr("socket 发送失败！");
		}
		if(empty($result)){
			$result='
			<monitor name="system">
			<stat name="system_max_cps" value="0"/>  
			<stat name="system_max_calls" value="0"/>  
			<stat name="system_peak_w_media_calls" value="0"/>  
			<stat name="system_peak_wo_media_calls" value="0"/>  
			<stat name="system_peak_cps" value="0"/>  
			<stat name="last_24hr_peak_w_media_calls" value="0"/>  
			<stat name="last_24hr_peak_wo_media_calls" value="0"/>  
			<stat name="last_24hr_peak_cps" value="0"/>  
			<stat name="last_7d_peak_w_media_calls" value="0"/>  
			<stat name="last_7d_peak_wo_media_calls" value="0"/>  
			<stat name="last_7d_peak_cps" value="0"/>  
			<stat name="current_cps" value="0"/>  
			<stat name="current_calls" value="0"/>  
			<stat name="current_w_media_calls" value="0"/>  
			<stat name="current_wo_media_calls" value="0"/>  
			</monitor> 
			';
		}
		echo $result;
	}
}