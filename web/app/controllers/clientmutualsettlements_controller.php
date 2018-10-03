<?php
/**
路由伙伴结算报表
 * @author root
 *
 */
class ClientmutualsettlementsController extends AppController {
	var $name = 'Clientmutualsettlements';
	var $uses = array ('Transaction' );
	var $helpers = array ('javascript', 'html' );
	//查询封装
//读取该模块的执行和修改权限
	//
 public $jorg_list=null;
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
		if($login_type==1){
			$this->Session->write('executable',true);
			$this->Session->write('writable',true);
		}else{
			$limit = $this->Session->read('sst_retail_rcardpools');
			$this->Session->write('executable',$limit['executable']);
			$this->Session->write('writable',$limit['writable']);
		}
		parent::beforeFilter();//调用父类方法
	}
	//初始化查询参数
	function init_query() {
		$this->set ( 'code_deck', $this->Cdr->find_code_deck () );
		$this->set ( 'currency', $this->Cdr->find_currency () );
		$this->set ( 'server', $this->Cdr->find_server () );
		$this->set ( 'ingress', $this->Cdr->findAll_ingress_alias () );
		$this->set ( 'egress', $this->Cdr->findAll_egress_alias () );
	}
	function  index()
	{
		$this->redirect('summary_reports');
	}
        
        function summary_reports() 
        {
            $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
            empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
            empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
            $_SESSION['paging_row'] = $pageSize;

            $all_type = array(
                'all', 'payment received', 'payment sent', 'invoice received', 'invoice sent', 'credit note received', 'credit not sent',
                'debit note received', 'debit note sent', 'reset', 'egress actual usage', 'ingress actual usage'
            );


            $start = date("Y-m-01");
            $end = date("Y-m-d");
            $type = 0;

            $otherwhere = "";

            if(isset($_GET['start'])) {
                $start = $_GET['start'];
            }

            if(isset($_GET['end'])) {
                $end = $_GET['end'];
            }
            
            
            $client_id = $_SESSION['sst_client_id'];

            if(isset($_GET['type'])) {
                $type = $_GET['type'];
            }

            require_once 'MyPage.php';
            $counts = $this->Transaction->get_client_mutual_transaction_count($start,$end,$client_id,$type);
            $page = new MyPage ();
            $page->setTotalRecords ($counts); 
            $page->setCurrPage ($currPage);
            $page->setPageSize ($pageSize); 
            $currPage = $page->getCurrPage()-1;
            $pageSize = $page->getPageSize();
            $offset=$currPage*$pageSize;
            $data  = $this->Transaction->get_client_mutual_transaction($start,$end,$client_id,$type,$pageSize,$offset);   
            $page->setDataArray($data);
            $clients = $this->Transaction->get_clients();
            $this->set('p',$page);
            $this->set('clients', $clients);
            $this->set('startdate', $start);
            $this->set('enddate', $end);
            $this->set('all_type', $all_type);
        }
        /*
	function summary_reports() {
			//Configure::write('debug',0);
		$this->pageTitle="Management/Mutual Settlements";
			$t = getMicrotime();
				$order=$this->_order_condtions(
					Array('time','client_name','type','amount','balance')
				);
				if(empty($order))
				{
					$order='order by time desc';	
				}
				else
				{
					$order='order by '.$order;	
				}
					//权限条件
				$privilege_where='';
				 if($_SESSION['login_type']==3){
						$privilege_where= !empty($_SESSION['sst_client_id'])?" and ( client_id  = '{$_SESSION['sst_client_id']}' )":'';
				  }
				$this->init_query ();
				//date_default_timezone_set ( 'Asia/Shanghai' );
				//$order=$this->_get_order();
				$login_type = $_SESSION ['login_type'];
				extract($this->Cdr->get_real_period());
				//单个的where查询条件
			 	$client_where = '';
				$reseller_where = '';
				$tran_type_where = '';
				$start_date =date('Y-m-d');
				$stop_date=date('Y-m-d');
				$start_time=date('00:00:00');
				$stop_time=date('23:59:59');
					$tz=$this->Cdr->get_sys_timezone();
				if (isset ( $_GET ['searchkey'] )) {
					//日期条件
					$start_date = $_GET ['start_date']; //开始日期
					$start_time = $_GET ['start_time']; //开始时间
					$stop_date = $_GET ['stop_date']; //结束日期
					$stop_time = $_GET ['stop_time']; //结束时间
					$tz = $_GET ['query'] ['tz'];//时区
					$type = $_GET['type'];
					$start_day = $start_date;
					$end_day = $stop_date;
					//********************************************************************************************************
					//            普通单个条件查询(按照代理商,帐号卡)
					//********************************************************************************************************
					$client_id = $_GET ['query'] ['id_clients'];
					if ($client_id != '') {
								$client_where = "and client_id='$client_id'";
							$this->set ( "client_name", $_GET ['query'] ['id_clients_name'] );
					}
					//$type = $this->data ['Cdr'] ['type'];
					if ($type!='') {
						$tran_type_where = "and type='$type'";
					}
					$currency = $this->data ['Cdr'] ['currency'];
					if (! empty ( $currency )) {
						$currency_where = "and currency='$currency'";
						$this->set ( "currency_post", $this->data ['Cdr'] ['currency'] );
					}
				} 
				$start = $start_date . '  ' . $start_time."  ".$tz; //开始时间
				$end = $stop_date . '  ' . $stop_time."  ".$tz; //结束时间
       
				
		$this->set ( "start", $start );
		$this->set ( "end", $end );
		$this->set ( "start_day", $start_day );
		$this->set ( "end_day", $end_day );
		$this->set ( 'post', $this->data );
		$start=local_time_to_gmt($start);
		$end=local_time_to_gmt($end);
		//********************************************************************************************************
		//                                                                  基本sql
		//********************************************************************************************************

		$paysent_sql="select  sum(amount::numeric)::numeric(20,5) from  class4_view_clientmutualsettlement  where amount::numeric<0
		and   client_name  is  not  null and type::integer = 3 and  time  between   '$start'  and  '$end'   $client_where   $privilege_where   $tran_type_where
		";
		$org_list = $this->Cdr->query ($paysent_sql);
		$jorg_list= $org_list;
		////////////////////////
		$this->set ("payment_sent", $org_list);
//通话费用
		$org_sql = "select *	from   class4_view_clientmutualsettlement   
    where   client_name  is  not  null  and  time  between   '$start'  and  '$end'   $client_where   $privilege_where   $tran_type_where  $order ";
		if(isset($_GET ['query'] ['output'])){
			//下载
				if ($_GET ['query'] ['output']== 'csv'){
						Configure::write('debug',0);
					//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
						$download_sql = "select type,time,client_id as carrier_id,client_name as carrier,amount,balance,result
							from   class4_view_clientmutualsettlement   
    where   client_name  is  not  null  and  time  between   '$start'  and  '$end'   $client_where   $privilege_where   $tran_type_where ";
					$this->_catch_exception_msg(array('ClientmutualsettlementsController','_download_impl'),array('download_sql' => $download_sql,'start_date' => $start_date,'stop_date' => $stop_date,'jorg_list'=>$org_list));
				}else{
					$org_list = $this->Cdr->query ( $org_sql );
					$this->set ("client_org", $org_list);
				}
		}else{
			$org_list = $this->Cdr->query ( $org_sql );
			$this->set ("client_org", $org_list);
		}
		$this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
	}
	*/
        
	function _download_impl($params=array()){
		if (!$_SESSION['role_menu']['Management']['clientmutualsettlements']['model_x'])
		{
			$this->redirect_denied();
		}
				$Invoices_incoming=0.00;
		   $Invoices_outgoing=0.00;
		   $payments_received=0.00;
		   $Paymentsent=0.00;

			extract($params);
			$list=$this->Cdr->query($download_sql);
			$size=count($list);//求数据总条数
			for($i=0;$i<$size;$i++){
			   if($list[$i][0]['type']==3){ 
        	$payments_received=(double)$payments_received+(double)$list[$i][0]['amount'];
			       }
				  if($list[$i][0]['type']==0){
	         $Invoices_outgoing=$Invoices_outgoing+(double)$list[$i][0]['amount'];
					  }
					 if($list[$i][0]['type']==1){ 
		         $Invoices_incoming=$Invoices_incoming+(double)$list[$i][0]['amount'];
					 }
			}
         if(empty( $params['jorg_list'][0][0]['sum'])){
			        $Paymentsent=0.00;
			     }else{
			        $Paymentsent=$params['jorg_list'][0][0]['sum'];
			              }
			$Invoices_outgoing=0-$Invoices_outgoing;
			if($this->Cdr->ex_download_by_sql($download_sql,array('objectives'=>'mutual_settlement','file_name' => "mutual_settlement_{$start_date}_to_{$stop_date}.csv"))){
         echo"\n";
		   		echo "sell invocie:,$Invoices_incoming\n" ;//$appCommon->currency_rate_conversion($Invoices_incoming);
						echo "buy invocie:,$Invoices_outgoing\n";//$appCommon->currency_rate_conversion($Invoices_outgoing);
						echo "Payments received:,$payments_received\n"; //$appCommon->currency_rate_conversion($payments_received); 
						echo "Payments sent:,$Paymentsent\n";
			exit(1);
		} 	
	}
	
	
}
