<?php
class MismatchesreportsController extends AppController {
	var $name = 'Mismatchesreports';
	var $uses = array ('Cdr' );
	var $helpers = array ('javascript', 'html' );
	
	/**
	 * 
	 * 
	 * 异常话单分析 
	 */
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	
function get_org_no_carrier($start,$end){
   $org_sql="select  
        (sum ( case  call_duration  when '0' then 0 else call_duration::numeric end))/60  ::numeric(20,2) as total_duration,
        count(*) as org_total_calls,
        count(NULLIF(call_duration , '0')) as notzero_calls,
        from client_cdr   where time  between   '$start'  and  '$end'";

}

	
function mismatches_report() {
	$this->pageTitle="Statistics/Billing Mismatch Report
	";
		$t = getMicrotime();
		//date_default_timezone_set ( 'Asia/Shanghai' );
		$login_type = $_SESSION ['login_type'];
		$start = date ( "Y-m-d  00:00:00" );
		$end = date ( "Y-m-d 23:59:59" );
		$start_day = date ( "Y-m-d  " );
		$end_day = date ( "Y-m-d " );

		if (isset ( $_POST ['searchkey'] )) {
			
			//日期条件
			$start_date = $_POST ['start_date']; //开始日期
			$start_time = $_POST ['start_time']; //开始时间
			$stop_date = $_POST ['stop_date']; //结束日期
			$stop_time = $_POST ['stop_time']; //结束时间
			$start_day = $start_date;
			$end_day = $stop_date;
			$start = $start_date . '  ' . $start_time; //开始时间
			$end = $stop_date . '  ' . $stop_time; //结束时间
		}
		

		$this->set ( "start", $start );
		$this->set ( "end", $end );
		$this->set ( "start_day", $start_day );
		$this->set ( "end_day", $end_day );
		$this->set ( 'post', $this->data );
		$sql=" select  *  from   class4_func_mismatch_report('$start','$end');";		
		if (isset ( $_POST ['query'] ['output'] )) {
			//下载
			if ($_POST ['query'] ['output'] == 'csv') {
				Configure::write ( 'debug', 0 );
				$this->layout = 'csv';
				//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
				$this->Cdr->export__sql_data ( 'Download MismatchRrport', $sql, 'report' );
				exit ();
			
			} else {
				//web显示
				$org_list = $this->Cdr->query ( $sql );
				$this->set ( "client_org", $org_list );
			
			
			}
		
		} else {
			//get 请求
//web显示
				$org_list = $this->Cdr->query ( $sql );
				$this->set ( "client_org", $org_list );
	
		}
	 $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
	}


}