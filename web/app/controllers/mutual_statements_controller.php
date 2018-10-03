<?php
/**
路由伙伴结算报表
 * @author root
 *
 */
class MutualStatementsController extends AppController {
	var $name = 'MutualStatements';
	var $uses = array ('Client', 'Cdr' );
	var $helpers = array ('javascript', 'html' ,'AppProduct');
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
	function summary_reports() {
			//Configure::write('debug',0);
		$this->pageTitle="Management/Mutual Statements";
		$t = getMicrotime();
		if(isset($this->params['url']['search']))
		{
			require_once 'MyPage.php';

			$page = new MyPage();

			$totalrecords = 0;

			$sql_where = '';
			$currPage = 1;
			$pageSize = 100;


			if (!empty ($_REQUEST ['page']))
			{
				$currPage = $_REQUEST ['page'];
			}
			$pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 15;

			if (!empty ($_REQUEST ['size']))
			{
				$pageSize = $_REQUEST ['size'];
			}
			$sql_where = '';

			if (!empty($_REQUEST['search']) && $_GET['search'] != 'Search....')
			{
				$sql_where .= " and name like '%" . addslashes($_REQUEST['search']) . "%'";
			}

			$overdue = isset($_GET['overdue']) ? (int)$_GET['overdue'] : 0;

			if ($overdue == 0)
				$overdue_condition = " and ((select sum(total_amount-pay_amount)
                        from invoice where state = 9 and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d') . "' and type = '0') > 0 or (select sum(total_amount-pay_amount) from invoice where state = 9 
                            and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d') . "' and type = '1') > 0) ";
			else if ($overdue == 1)
				$overdue_condition = " and ((select sum(total_amount-pay_amount)
                        from invoice where state = 9 and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d') . "' and type = '0') is null and (select sum(total_amount-pay_amount) from invoice where state = 9 
                            and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d') . "' and type = '1') is null) ";
			else
				$overdue_condition = '';

			$sql = "select count(client_id) as c from client where true" . $overdue_condition . $sql_where;
			$totalrecords = $this->Client->query($sql);
			//echo $pageSize;
			$_SESSION['paging_row'] = $pageSize;
			$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
			$page->setCurrPage($currPage);//当前页
			$page->setPageSize($pageSize);//页大小

			//$page = $page->checkRange($page);//检查当前页范围

			$currPage = $page->getCurrPage() - 1;
			$pageSize = $page->getPageSize();
			$offset = $currPage * $pageSize;

			$order_arr = array();
			if (!empty($_REQUEST['order_by']))
			{
				$order_by = explode("-", $_REQUEST['order_by']);
				$order_arr[$order_by[0]] = $order_by[1];
			}
			$sql_order = 'order by client.name';
			if (!empty($order_arr))
			{
				$sql_order = ' order by ';
				foreach ($order_arr as $k => $v)
				{
					$sql_order .= $k . ' ' . $v;
				}
			}


			//查询Client groups
			$sql = "select client_id, name, (select amount||'|'||payment_time from client_payment
                    where client_id = client.client_id order by payment_time desc limit 1) as last_payment, 
                    (select total_amount||'|'||invoice_time from invoice where client_id = client.client_id order by invoice_time desc limit 1) as last_invoice, 
                    (select balance from c4_client_balance where client_id::integer = client.client_id) as balance,
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d') . "' and type = '0') as incoming_overdue, 
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d') . "' and due_date >= '" . date('Y-m-d', strtotime('-7 days')) . "' and type = '0') as incoming_overdue_7, 
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false  and due_date < '" . date('Y-m-d', strtotime('-7 days')) . "' and due_date >= '" . date('Y-m-d', strtotime('-15 days')) . "' and type = '0') as incoming_overdue_15, 
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false  and due_date < '" . date('Y-m-d', strtotime('-15 days')) . "' and due_date >= '" . date('Y-m-d', strtotime('-30 days')) . "' and type = '0') as incoming_overdue_30,
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d', strtotime('-30 days')) . "' and type = '0') as incoming_overdue_gt_30 ,
                    
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d') . "' and type = '1') as outgoing_overdue, 
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d') . "' and due_date >= '" . date('Y-m-d', strtotime('-7 days')) . "' and type = '1') as outgoing_overdue_7, 
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false  and due_date < '" . date('Y-m-d', strtotime('-7 days')) . "' and due_date >= '" . date('Y-m-d', strtotime('-15 days')) . "' and type = '1') as outgoing_overdue_15, 
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false  and due_date < '" . date('Y-m-d', strtotime('-15 days')) . "' and due_date >= '" . date('Y-m-d', strtotime('-30 days')) . "' and type = '1') as outgoing_overdue_30,
                    (select sum(total_amount-pay_amount) from invoice where state = 9 and client_id = client.client_id and paid = false and due_date < '" . date('Y-m-d', strtotime('-30 days')) . "' and type = '1') as outgoing_overdue_gt_30 
                    from client where 1=1 " . $overdue_condition . $sql_where . $sql_order;

			$sql .= " limit '$pageSize' offset '$offset'";
			//echo $sql;
			$results = $this->Client->query($sql);

			$page->setDataArray($results);
			//var_dump($page);
			$this->set('p', $page);
            $this->set('search', $this->params['url']['search']);
		}
		$clientsList = $this->Client->find('all', array(
		    'fields' => array('Client.name'),
            'order'  => array('Client.name')
        ));
        $this->set('clientsList', $clientsList);
		$this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
	}
	
	function detail_report() {
			//Configure::write('debug',0);
		$this->pageTitle="Management/Detail Statements";
		$client_id = intval($this->params['pass'][0]);
		$temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
                        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
                        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
                        $_SESSION['paging_row'] = $pageSize;
                        require_once 'MyPage.php';
                        
                        $where_arr = array();
                        
                        if(isset($_GET['paid_type']) && !empty($_GET['paid_type'])) {
                             $paid_type = $_GET['paid_type'];
                             if($paid_type == '1') 
                                 array_push($where_arr, "pay_amount < total_amount and pay_amount > 0 AND total_amount != 0 ");
                             else if($paid_type == "2")
                                 array_push($where_arr, "pay_amount  = 0 AND total_amount != 0");
                        } else {
                            array_push($where_arr, "pay_amount  = 0 AND total_amount != 0");
                        }
                        
                        if(isset($_GET['carrier']) && !empty($_GET['carrier'])) {
                            $carrier = $_GET['carrier'];
                            array_push($where_arr, "client_id = {$carrier}");
                        }
                        
                        if(isset($_GET['direction']) && !empty($_GET['direction'])) {
                            $direction = $_GET['direction'];
                            if ($direction == '1') 
                                array_push($where_arr, "type = 1");  
                            elseif ($direction == '2')
                                array_push($where_arr, "type = 0");  
                        }
                        
                        if(isset($_GET['invoice_start_date']) && !empty($_GET['invoice_start_date'])) {
                            $invoice_start_date = $_GET['invoice_start_date'];
                            array_push($where_arr, "invoice_time > '{$invoice_start_date}'");
                        }
                        
                        if(isset($_GET['invoice_end_date']) && !empty($_GET['invoice_end_date'])) {
                            $invoice_end_date = $_GET['invoice_end_date'];
                            array_push($where_arr, "invoice_time < '{$invoice_end_date}'");
                        }
                        
                        if(isset($_GET['due_start_date']) && !empty($_GET['due_start_date'])) {
                            $due_start_date = $_GET['due_start_date'];
                            array_push($where_arr, "due_date > '{$due_start_date}'");
                        }
                        
                        if(isset($_GET['due_end_date']) && !empty($_GET['due_end_date'])) {
                            $due_end_date = $_GET['due_end_date'];
                            array_push($where_arr, "due_date < '{$due_end_date}'");
                        }
                        
                        if(isset($_GET['start_amt']) && !empty($_GET['start_amt'])) {
                            $start_amt = $_GET['start_amt'];
                            array_push($where_arr, "total_amount > '{$start_amt}'");
                        }
                        
                        if(isset($_GET['end_amt']) && !empty($_GET['end_amt'])) {
                            $end_amt = $_GET['end_amt'];
                            array_push($where_arr, "total_amount < '{$end_amt}'");
                        }
                        
                        array_push($where_arr, 'invoice.state != -1');
                        array_push($where_arr, "client_id = {$client_id}");
                        $where = implode(' AND ', $where_arr);
                        
                        $counts = $this->Client->get_unpaid_invoice_count($where);
                        $page = new MyPage ();
                        $page->setTotalRecords ($counts); 
                        $page->setCurrPage ($currPage);
                        $page->setPageSize ($pageSize); 
                        $currPage = $page->getCurrPage()-1;
                        $pageSize = $page->getPageSize();
                        $offset=$currPage*$pageSize;
                        $is_download = isset($_GET['is_download']) && $_GET['is_download'] == '1' ? true : false; 
                        if ($is_download)
                        {
                            Configure::write('debug', 0);
                            $data = $this->Client->get_unpaid_invoice($where, 1000000, 0);
                            
                            
                        } else {
                            $data = $this->Client->get_unpaid_invoice($where, $pageSize, $offset);
                        }
                        $page->setDataArray($data);
                        $this->set('p',$page);
                        $this->_render_set_options('Client', Array('Client'=>Array('order'=>'name asc')));
                        
                        
                        if ($is_download)
                        {
                            header("Pragma: public");
                            header("Expires: 0");
                            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                            header("Content-Type: text/csv");
                            header("Content-Type: application/octet-stream");
                            header("Content-Type: application/download");
                            header("Content-Disposition: attachment;filename=Detail_Statements.csv");
                            header("Content-Transfer-Encoding: binary ");
                            Configure::write('debug', 0);
                            $this->autoLayout = false;
                            $this->render('export');
                        }
                        
                        /*
                         
                         
                        $this->pageTitle="Management/Detail Statements";
		$client_id = intval($this->params['pass'][0]);
		$t = getMicrotime();
		require_once 'MyPage.php';
                
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
			$currPage = 1;
			$pageSize = 100;
		
			
			if (! empty ( $_REQUEST ['page'] )) {
				$currPage = $_REQUEST ['page'];
			}
			$pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 15;
                       
			if (! empty ( $_REQUEST ['size'] )) {
				$pageSize = $_REQUEST ['size'];
			}
		
		if(!empty( $_GET ['start_date']) && !empty( $_GET ['start_time'])&& !empty( $_GET ['stop_date'])&&!empty( $_GET ['stop_time'])&&!empty($_GET ['query']['tz']) )
		 {
		 	$start = $_GET ['start_date'] . '  ' . $_GET ['start_time'].' '.$_GET ['query']['tz']; //开始时间
			$end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'].'  '.$_GET ['query']['tz']; //结束时间
		 
		 }else{
		 	 #report deault query time
		 	 extract($this->Cdr->get_real_period());		 	
		 }
		$this->report_query_time=array('start'=>$start,'end'=>$end);
	 	$this->set ( "start", $start );
		$this->set ( "end", $end );
		$start=local_time_to_gmt($start);
		$end=local_time_to_gmt($end);
		$sql_where = " and client_id = {$client_id} and payment_time between '$start' and '$end'";
		
	
		$sql = "select count(*) as c from (select payment_time, invoice_number, (select type from invoice where invoice_number = invoice.invoice_number limit 1) as invoice_type, payment_type, amount, current_balance, client_id from client_payment where result = true and approved = true union select invoice_time as payment_time, invoice_number, \"type\" as invoice_type, 5 as payment_type, total_amount as amount, current_balance, client_id from invoice where \"state\" != -1) as payment_tmp  where true ".$sql_where;
		$totalrecords = $this->Client->query($sql);
	 	//echo $pageSize;
   $_SESSION['paging_row'] = $pageSize;
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		$order_arr = array();
		if (!empty($_REQUEST['order_by']))
		{
				$order_by = explode("-", $_REQUEST['order_by']);
				$order_arr[$order_by[0]] = $order_by[1];
		}
		else
		{
			$order_by = " order by item_time";
		}
		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}
		//查询
		$sql = "select * from (select payment_time, invoice_number, 
                (select type from invoice where invoice_number = invoice.invoice_number limit 1) as invoice_type,
                payment_type, amount, current_balance, client_id 
                from client_payment where result = true and approved = true union select invoice_time as payment_time, invoice_number, \"type\" as invoice_type, 5 as payment_type, total_amount as amount, current_balance, client_id from invoice where  \"state\" != -1) as payment_tmp 
                where true ".$sql_where;
		
		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->Client->query($sql);
		
		$page->setDataArray($results);
		//var_dump($page);
		$this->set ( 'p', $page);		
	
		$this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));*/
	}
	
	
	function _download_impl($params=array()){
			/*	$Invoices_incoming=0.00;
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
		} 	*/
	}
	
	
}
