<?php
/**
 * 
 * 
 * 路由伙伴汇总统计
 * @author root
 *
 */
class ClientsummarystatisController extends AppController {
	var $name = 'Clientsummarystatis';
	var $uses = array ('Cdr' );
	var $helpers = array ('javascript', 'html' );
	
	
		function  create_amchart_setting_xml($field){
		$humanize_field_arr=$this->inflector_humanize_chart_field($field);
		
	
		
	 $out='<?xml version="1.0" encoding="UTF-8"?>'."\n";
   $out .= '<settings>';
   $out.=	file_get_contents(APP.'views'.DS.'locationreports'.DS."amstock_settings_base.xml");
   $out.= '<data_sets>';
   $i=0;
			foreach($humanize_field_arr as $key => $value){
			$out .= "<data_set did=\"$i\">\n";
			$out .= " <title>$value</title>\n";
			$out .= " <short>$value $value</short>\n";
			$out .= " <description>   $value;</description>\n";
			$out .= "<file_name>summary_report_$key.csv</file_name>\n";
			$out .= "<main_drop_down selected=\"true\"></main_drop_down>\n   	  
       					<compare_list_box selected=\"false\"></compare_list_box>\n
       <csv>
         <reverse>true</reverse>
         <separator>,</separator>
         <date_format>YYYY-MM-DD hh:mm:ss</date_format>
         <decimal_separator>.</decimal_separator>
         <columns>
           <column>date</column>
           <column>close</column>  
         </columns>
       </csv>\n";
			$out .= "</data_set>\n";
			$i++;
		}
	  $out.= '</data_sets>';	
		 $out.=	file_get_contents(APP.'views'.DS.'locationreports'.DS."amstock_settings_charts.xml");
		
   $out .= '</settings>';
   

	 $xml_file=APP.'webroot'.DS.'amstock'.DS."summary_report_$field"."_amstock_settings.xml";
   $fp=fopen($xml_file,'w');
   fwrite($fp,$out);
   fclose($fp);
	
	
	}

	
	
	
		function create_amchart_flash(){
	$this->create_amchart_csv('total_cost');
	$this->create_amchart_csv('bill_time');
	$this->create_amchart_csv('orig_asr_acd');
	$this->create_amchart_csv('term_asr_acd');
	$this->create_amchart_csv('call_count');

	$this->create_amchart_setting_xml ('total_cost');
	$this->create_amchart_setting_xml ('bill_time');
	$this->create_amchart_setting_xml ('orig_asr_acd');
		$this->create_amchart_setting_xml ('term_asr_acd');
	$this->create_amchart_setting_xml ('call_count');
}	



	function  inflector_humanize_chart_field($field){
$arr=array(
	'total_cost'=>array(
	  			'org_total_cost'=> 'Total Cost Orig',
	 				'term_total_cost'=> "Total Cost Term"
	 			
	),
	
	'bill_time'=>array(
	   	'org_bill_minute'=>"Time  Total  Billed Orig",
				'term_bill_minute'=>"Time   Total  Billed Term",
				'total_duration'=>"Time Total ",
			'pdd'	=> 'PDD'
	),

	'orig_asr_acd'=>array(
			'orig_asr_std'=>"ASR std Orig",
		'orig_asr_cur'=>"ASR cur Orig ",
		'orig_acd_std'=>"ACD std Orig",
		'orig_acd_cur'=>"ACD cur Orig"
	
	
	),
	
	
		'term_asr_acd'=>array(
			'term_asr_std'=>"ASR std Term",
		'term_asr_cur'=>"ASR cur Term",
		'term_acd_std'=>"ACD std Term",
		'term_acd_cur'=>"ASR cur Term"
	
	
	),
	'call_count'=>array(
		'orig_total_calls'=>"Calls Total Orig",
	'term_total_calls'=>"Calls 	Total Term",
		'orig_notzero_calls'=>"Calls Not Zero Orig",
		'term_notzero_calls'=>"Calls  Not Zero Term"
		
	
	
	)

	
	);
	
	if(isset($arr[$field])){
	
	return  $arr[$field];
	}else{
	
	return $arr['total_cost'];
	}
	
	}
	function  get_chart_field_sql($field){
	$arr=array(
//	'total_cost'=>array(
//	  			'org_total_cost'=> "sum( case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)::numeric as org_total_cost",
//	 				'term_total_cost'=> "sum(case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end) as org_total_cost"
//	 			
//	),
	'total_cost'=>array(
	  			'org_total_cost'=> "sum(ingress_cost) as org_total_cost",
	 				'term_total_cost'=> "sum(egress_cost) as org_total_cost"
	 			
	),
	
//	'bill_time'=>array(
//	   	'org_bill_minute'=>"sum(ingress_bill_minutes::numeric) as org_bill_minute",
//				'term_bill_minute'=>" sum(egress_bill_minutes::numeric) as org_bill_minute",
//				'total_duration'=>" (sum ( case  call_duration  when '0' then 0 else call_duration::numeric end))/60  ::numeric as total_duration"
//	),
	'bill_time'=>array(
		   	'org_bill_minute'=>"sum(orig_bill_minutes) as org_bill_minute",
					'term_bill_minute'=>" sum(term_bill_minutes) as org_bill_minute",
					'total_duration'=>" sum (call_duration) as total_duration",
					'pdd'	=> " sum(pdd) as pdd"
		),

	'orig_asr_acd'=>array(
		'orig_asr_std'=>"(sum(ok_calls)*100/ NULLIF(sum(ca),0) )::numeric as asr_std",
		'orig_asr_cur'=>"(sum(not_zero_calls)*60*100/NULLIF(sum(ca),0)) as asr_cur ",
		'orig_acd_std'=>"(sum (call_duration)/NULLIF( sum(ok_calls),0 ))::numeric as acd_std",
		'orig_acd_cur'=>"(sum (call_duration)/ NULLIF( sum(not_zero_calls) ,0) )::numeric as acd_cur"
		),
	
	
		'term_asr_acd'=>array(
			'term_asr_std'=>"(sum(ok_calls)*100::numeric/ NULLIF(sum(ca),0)::numeric as asr_std",
		'term_asr_cur'=>"(sum(not_zero_calls)::numeric*100/NULLIF(sum(ca),0)) ::numeric as asr_cur ",
		'term_acd_std'=>"((sum(not_zero_calls)/60) / NULLIF( sum(ok_calls),0 ))::numeric as acd_std",
		'term_acd_cur'=>"((sum (not_zero_calls)/60)/ NULLIF( sum( not_zero_calls) ,0) )::numeric as acd_cur"
	
	
	),
	'call_count'=>array(
		'orig_total_calls'=>"sum(ingress_ca) as org_total_calls",
		'orig_notzero_calls'=>" sum(not_zero_calls) as notzero_calls",
		'term_notzero_calls'=>" sum(not_zero_calls) as notzero_calls",
		'term_total_calls'=>"sum(egress_ca) as org_total_calls"
	
	
	)
	/*'orig_asr_acd'=>array(
				'orig_asr_std'=>"(count(nullif(answer_time_of_date,'0'))*100::numeric/ NULLIF( (count(*)-count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0) )::numeric as asr_std",
			'orig_asr_cur'=>"(count(NULLIF(call_duration , '0'))::numeric *100/NULLIF(count(*),0)) ::numeric as asr_cur ",
			'orig_acd_std'=>"((sum (NULLIF(call_duration,'0')::numeric )/60) / NULLIF( count(NULLIF(answer_time_of_date,'0')),0 ))::numeric as acd_std",
			'orig_acd_cur'=>"((sum (NULLIF(call_duration,'')::numeric )/60)/ NULLIF( count( NULLIF(call_duration , '0') ) ,0) )::numeric as acd_cur"
		
		
		),
	
	
		'term_asr_acd'=>array(
			'term_asr_std'=>"(count(nullif(answer_time_of_date,'0'))*100::numeric/ NULLIF( (count(*)-count(case when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0) )::numeric as asr_std",
		'term_asr_cur'=>"(count(NULLIF(call_duration , '0'))::numeric *100/NULLIF(count(*),0)) ::numeric as asr_cur ",
		'term_acd_std'=>"((sum (NULLIF(call_duration,'0')::numeric )/60) / NULLIF( count(NULLIF(answer_time_of_date,'0')),0 ))::numeric as acd_std",
		'term_acd_cur'=>"((sum (NULLIF(call_duration,'')::numeric )/60)/ NULLIF( count( NULLIF(call_duration , '0') ) ,0) )::numeric as acd_cur"
	
	
	),
	'call_count'=>array(
		'orig_total_calls'=>"count(case ingress_id when '' then null else ingress_id end) as org_total_calls",
		'orig_notzero_calls'=>" count(NULLIF(call_duration , '0')) as notzero_calls",
		'term_notzero_calls'=>" count(NULLIF(call_duration , '0')) as notzero_calls",
		'term_total_calls'=>"count(case egress_id when '' then null else egress_id end) as org_total_calls"
	
	
	)*/
	
	);
	
	if(isset($arr[$field])){
	
	return  $arr[$field];
	}else{
	
	return $arr['total_cost'];
	}
	
	}
		
		function create_amchart_csv($field){
		$where = $this->capture_report_condtions ('summary_report');
		$order = $this->capture_report_order ('summary_report');
		$field_sql_arr=$this->get_chart_field_sql($field);
			extract ($this->capture_report_join ('summary_report',''));
		
		if(is_array($field_sql_arr)){
		   foreach ($field_sql_arr as $key=>$value){
		  	$sql=" copy (
				select to_char(time, 'YYYY-MM-DD HH24') as group_time,$value
		  from client_cdr $join 
		  where $where  group by group_time having 1=1 
		  order by group_time desc
			 ) to " . Configure::read('database_actual_export_path') . "'/summary_report_$key.csv'  csv ";
			$this->Cdr->query($sql);
			copy(Configure::read('database_export_path') . "/summary_report_$key.csv",APP.'webroot'.DS.'amstock'.DS."summary_report_$key.csv");
		   }
		
		
		}					
	}

	
	
	//Origination
		var $report_orig_sql_field=	array(
		'org_total_cost'=>"sum(ingress_cost)::numeric as org_total_cost ",
		'total_duration'=>" sum (call_duration)::numeric as total_duration ",
		'org_bill_minute'=>"sum(orig_bill_minute)::numeric as org_bill_minute",
		'pdd'=>"sum(pdd::integer) as pdd",
		'lnp_dipping_cost'=>'sum(lnp_dipping_cost) as lnp_dipping_cost',
		
		'org_avg_rate'=>"  case when  sum(orig_bill_minute)=0  then 0 else (sum(ingress_cost)
		 /sum(orig_bill_minute)::numeric) end  ::numeric as org_avg_rate",
		'org_total_calls'=>"sum(ingress_ca) as org_total_calls",
	
		'notzero_calls'=>" sum(not_zero_calls) as notzero_calls",
		'succ_calls'=>"sum(ok_calls)  as succ_calls",
		'busy_calls'=>"sum(busy_calls) as busy_calls",
		'notchannel_calls'=>" sum(no_channel_calls)  as channel_unavailable",
		'lrn_calls'=>" sum(lrn_calls) as lrn_calls",

		//'asr_std'=>"(sum(ok_calls)*100::numeric/NULLIF( sum(ingress_ca),0))::numeric as asr_std",
		'asr_cur'=>"( sum(not_zero_calls)::numeric *100/NULLIF(sum(ingress_ca),0))  ::numeric as asr_cur",

		//'acd_std'=>"(sum(call_duration) /NULLIF(sum(ok_calls),0 ))::numeric as  acd_std ",
		'acd_cur'=>"  (sum(call_duration)/ NULLIF( sum(not_zero_calls),0) )::numeric as  acd_cur"
		);
	

		
		//
				var $report_term_sql_field=	array(
		'org_total_cost'=>"sum(egress_cost) as org_total_cost ",
		'total_duration'=>" sum (call_duration)   as total_duration ",
		'org_bill_minute'=>"sum(term_bill_minute) as org_bill_minute",
		'pdd'=>"sum(pdd::integer) as pdd",
                'lnp_dipping_cost'=>'sum(lnp_dipping_cost) as lnp_dipping_cost',
		'org_avg_rate'=>" case when sum(term_bill_minute)=0 then 0 else (sum(egress_cost)/sum(term_bill_minute))  end as org_avg_rate",
		'org_total_calls'=>"sum(egress_ca) as org_total_calls",
	
		'notzero_calls'=>" sum(not_zero_calls) as notzero_calls",
		'succ_calls'=>"sum(egress_ok_calls)  as succ_calls",
		'busy_calls'=>"sum(busy_calls) as busy_calls",
		'notchannel_calls'=>" sum(egress_no_channel_calls)  as notchannel_calls",
		'lrn_calls'=>" 0 as lrn_calls",

		'asr_std'=>"(sum(egress_ok_calls)*100::numeric/NULLIF( sum(egress_ca),0)) as asr_std",
		'asr_cur'=>"( sum(not_zero_calls)::numeric *100/NULLIF(sum(egress_ca),0))   as asr_cur",

		'acd_std'=>"(sum (call_duration) /NULLIF(sum(egress_ok_calls),0 )) as  acd_std ",
		'acd_cur'=>"  (sum (call_duration)/ NULLIF( sum(not_zero_calls),0) ) as  acd_cur"
		);
	//Origination
		/*var $report_orig_sql_field=	array(
		'org_total_cost'=>"sum(case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)::numeric as org_total_cost ",
		'total_duration'=>" (sum ( case  call_duration  when '0' then 0 else call_duration::numeric end))/60  ::numeric as total_duration ",
		'org_bill_minute'=>"sum(ingress_bill_minutes::numeric) as org_bill_minute",
		
		
		'org_avg_rate'=>"  case when  sum(ingress_bill_minutes::numeric)=0  then 0 else (sum(case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)
		 /sum(ingress_bill_minutes::numeric)) end  ::numeric as org_avg_rate",
		'org_total_calls'=>"count(case ingress_id when '' then null else ingress_id end) as org_total_calls",
	
		'notzero_calls'=>" count(NULLIF(call_duration , '0')) as notzero_calls",
		'succ_calls'=>"count( case answer_time_of_date when '0' then null else answer_time_of_date end )  as succ_calls",
		'busy_calls'=>"count(case  when  split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls",
		'notchannel_calls'=>" count(case  when  split_part(release_cause_from_protocol_stack,';',1)='503'  then release_cause_from_protocol_stack else null end)  as notchannel_calls",

		'asr_std'=>"(count(case answer_time_of_date when '0' then null else answer_time_of_date end)*100::numeric/
		NULLIF( (count(case ingress_id when '' then null else ingress_id end)-count(case  when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0))::numeric as asr_std",
		'asr_cur'=>"( count(NULLIF(call_duration , '0'))::numeric *100/NULLIF(count(case ingress_id when '' then null else ingress_id end),0))  ::numeric as asr_cur",

		'acd_std'=>"((sum (NULLIF(call_duration,'0')::numeric )/60) /NULLIF(count(case answer_time_of_date when '0' then null else answer_time_of_date end),0 ))::numeric as  acd_std ",
		'acd_cur'=>"  ((sum (NULLIF(call_duration,'0')::numeric )/60)/ NULLIF( count(NULLIF(call_duration , '0')),0) )::numeric as  acd_cur"
		);
	

		
		//
				var $report_term_sql_field=	array(
		'org_total_cost'=>"sum(case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end) as org_total_cost ",
		'total_duration'=>" (sum ( case  call_duration  when '0' then 0 else call_duration::numeric end))/60   as total_duration ",
		'org_bill_minute'=>"sum(egress_bill_minutes::numeric) as org_bill_minute",
		'org_avg_rate'=>" case when sum(egress_bill_minutes::numeric)=0 then 0 else (sum(case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end)/sum(egress_bill_minutes::numeric))  end as org_avg_rate",
		'org_total_calls'=>"count(case egress_id when '' then null else egress_id end) as org_total_calls",
	
		'notzero_calls'=>" count(NULLIF(call_duration , '0')) as notzero_calls",
		'succ_calls'=>"count(case answer_time_of_date when '0' then null else answer_time_of_date end )  as succ_calls",
		'busy_calls'=>"count(case  when  split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls",
		'notchannel_calls'=>" count(case  when  split_part(release_cause_from_protocol_stack,';',1)='503'  then release_cause_from_protocol_stack else null end)  as notchannel_calls",

		'asr_std'=>"(count(case answer_time_of_date when '0' then null else answer_time_of_date end )*100::numeric/NULLIF( (count(case egress_id when '' then null else egress_id end)-count(case  when split_part(release_cause_from_protocol_stack,';',1)='503' then release_cause_from_protocol_stack else null end)),0)) as asr_std",
		'asr_cur'=>"( count(NULLIF(call_duration , '0'))::numeric *100/NULLIF(count(case egress_id when '' then null else egress_id end),0))   as asr_cur",

		'acd_std'=>"((sum (NULLIF(call_duration,'0')::numeric )/60) /NULLIF(count( case answer_time_of_date when '0' then null else answer_time_of_date end ),0 )) as  acd_std ",
		'acd_cur'=>"  ((sum (NULLIF(call_duration,'0')::numeric )/60)/ NULLIF( count(NULLIF(call_duration , '0')),0) ) as  acd_cur"
		);*/
			
		
		
		
		
			function flash_setting() {
		Configure::write ( 'debug', 0 );
		$field = ! empty ( $_GET ['f'] ) ? $_GET ['f'] : 'duration';
		$xml_file = APP . 'webroot' . DS . 'amstock' . DS . "summary_report_$field" . "_amstock_settings.xml";
		echo file_get_contents ( $xml_file );
	}
		
		
	//读取该模块的执行和修改权限
	public function beforeFilter() {
		$this->checkSession ( "login_type" ); //核查用户身份
		$login_type = $this->Session->read ( 'login_type' );
		if ($login_type == 1) {
			//admin
			$this->Session->write ( 'executable', true );
			$this->Session->write ( 'writable', true );
		} else {
			$limit = $this->Session->read ( 'sst_retail_rcardpools' );
			$this->Session->write ( 'executable', $limit ['executable'] );
			$this->Session->write ( 'writable', $limit ['writable'] );
		}
		parent::beforeFilter();//调用父类方法
	}
	
	//初始化查询参数
	function init_query() {
		$this->set ( 'ingress_carrier', $this->Cdr->findIngressClient () );
		$this->set ( 'egress_carrier', $this->Cdr->findEgressClient () );
		$this->set ( 'code_deck', $this->Cdr->find_code_deck () );
		$this->set ( 'currency', $this->Cdr->find_currency () );
		$this->set ( 'server', $this->Cdr->find_server () );
		$this->set ( 'ingress', $this->Cdr->findAll_ingress_id () );
		$this->set ( 'egress', $this->Cdr->findAll_egress_id () );
	
	}
	
	
	
	
	
		function capture_report_order(){
			$order=$this->_order_condtions(
		Array('group_time','org_total_cost','org_avg_rate','total_duration','org_bill_minute','org_total_calls','notzero_calls',
		'succ_calls','busy_calls','notchannel_calls','asr_std','asr_cur','acd_std','acd_cur')
		);
		if(empty($order))
		{
			if (empty($_GET['group_by_date']))
			{
				$order='  order by 1,2,3';	
			}
			else
			{
				$order = ' order by 1,2,group_time';
			}
		}
		else
		{
			$order='order by '.$order;	
		}
		return $order;
	}
	
	
	
	
		function get_datas($type='',$params=array()) {
		
		extract ( $this->Cdr->get_real_period () );
		$where = $this->capture_report_condtions ('summary_report');

			extract ($this->capture_report_join ('summary_report',''));
		$order = $this->capture_report_order ();

		 $column=implode(',',array_values($this->report_orig_sql_field));
                 
		 	$column_term=implode(',',array_values($this->report_term_sql_field));
                   
		 $statistic_cdr = $this->getTodayStatistic();//client_cdr";//
                 if (!empty($group_by_where))
			{				
				$order .= ',' . $group_by_where;
			}
		  if(!empty($group_by_where)){
		  	
			$org_sql = "select   'orig'  as orig,  $group_by_field,$column	from $statistic_cdr $join  where true and $where group by  $group_by_where  having 1=1   $order";
			$term_sql = "select  'term'  as term, $group_by_field,$column_term	from $statistic_cdr $join  where  true and $where  group by  $group_by_where  having 1=1   $order";
		  
		  }else{
                        $org_sql = "select  'orig'  as orig,  $group_by_field,$column	from $statistic_cdr $join  where  true and  $where having 1=1   $order";
                        $term_sql = "select 'term'  as term,  $group_by_field,$column_term	from $statistic_cdr $join  where true and $where having 1=1   $order";
		  }

				//$org_list = $this->Cdr->query ($org_sql );
	
			//$term_list = $this->Cdr->query ($term_sql );
				if ($type == 'term')
				{
					
					$org_list = array();
					$term_list = $this->Cdr->query ($term_sql );
					
				}
				else
				{
					$org_list = $this->Cdr->query ($org_sql );
					$term_list = array();
				}
		return compact ('org_sql', 'org_list', 'group_by_field_arr','term_sql','term_list');
	}
	
	function get_all_country(){
		extract ( $this->Cdr->get_real_period () );
		$where = $this->capture_report_condtions ('location_report');
			extract ($this->capture_report_join ('location_report',''));

		$org_sql="select  distinct  term_country  from client_cdr $join  where $where";
		//$org_sql="select  distinct  term_country  from statistic_cdr $join  where $where";
		$org_list = $this->Cdr->query ($org_sql );
	
		$tmp_arr=array();
		if(!empty($org_list)){
		$size=count($org_list);
		foreach ($org_list as  $key=>$value){
		    $tmp_arr[]=$value[0]['term_country'];
		
		}

			
		}
	return $tmp_arr;
	}	
		function  index()
	{
		$this->redirect('summary_reports');
	}
	function summary_reports() {
		$this->set('report_type','summary_report');
		//$this->_session_get(isset ( $_GET ['searchkey'] ));
		$this->pageTitle="Statistics/Summary Report";
		$t = getMicrotime();
		//$report_type='orig';
		$report_type = empty($this->params ['pass'][0])?'orig':$this->params ['pass'][0];
		
		/*
		if(!empty($this->params ['pass'][0])){
			$report_type = $this->params ['pass'][0];			
		}	
		*/
		$this->set ( 'report_type', $report_type );
		
		
		//取数据  sql  ==> org_sql  , 数据===>org_list, 分组条件====>'group_by_field_arr');
		extract($this->get_datas($report_type));
		//$this->create_amchart_flash();
		//$image_file = $this->create_image_csv();
		//$this->set('image_file', basename($image_file)); 
		
		$country_arr = $this->get_all_country ();
		$this->set('country_arr', $country_arr);
		$this->set('field', 'ca');
		
		$this->init_query ();

		
		$grpup_size = 0;
		
		$show_comm = true; //普通表格显示
		$show_subgroups = false; //以subgroup方式显示 
		$show_subtotals = false; //以subgtotal方式显示 
		$grpup_size = count ( $group_by_field_arr );
		if (isset ( $_GET ['query'] ['output_subgroups'] )) {
			
			if (! empty ( $_GET ['query'] ['output_subgroups'] ) && $grpup_size > 1) {
				
				$show_comm = false; //普通表格显示
				$show_subgroups = 'true'; //以subgroup方式显示  
				$this->set ( 'show_subgroups', $show_subgroups );
				$this->set ( 'group_size', $grpup_size ); //分组的个数
			}
		
		}
		
		//********************************************************************************************************
		//           subtotals  分组子统计
		//********************************************************************************************************
		$grpup_size = count ( $group_by_field_arr );
		if (isset ( $_GET ['query'] ['output_subtotals'] )) {
			if (! empty ( $_GET ['query'] ['output_subtotals'] ) && $grpup_size > 1) {
				
				$show_subtotals = 'true'; //以subgtotal方式显示 
				$this->set ( 'show_subtotals', $show_subtotals );
				$this->set ( 'group_size', $grpup_size ); //分组的个数
			}
		
		}
		
		$this->set ( 'show_comm', $show_comm );
		$this->set ( 'show_subtotals', $show_subtotals );
		
					extract($this->get_start_end_time());
      $direction = empty($_GET['query']['direction']) ? '' : $_GET['query']['direction'];
	  
		if (isset ( $_GET ['query'] ['output'] )) {
				$file_name=$this->create_doload_file_name('SummaryReport',$start,$end);
			//下载
			if ($_GET ['query'] ['output'] == 'csv') {
					Configure::write('debug',0);
					
					if (!empty($direction) && 'origination' == $direction)
					{
						$download_sql = $org_sql;
					}
					elseif (!empty($direction) && 'termination' == $direction)
					{
						$download_sql = $term_sql;
					}
					else
					{
						$download_sql = "($org_sql)   UNION ALL ($term_sql)";
					}
					$this->_catch_exception_msg(array('ClientsummarystatisController','_summary_reports_download_impl'),array('download_sql' => $download_sql,'file_name'=>$file_name));
				exit ();
			} 
			elseif ($_GET ['query'] ['output'] == 'xls') {
					Configure::write('debug',0);
					if (!empty($direction) && 'origination' == $direction)
					{
						$download_sql = $org_sql;
					}
					elseif (!empty($direction) && 'termination' == $direction)
					{
						$download_sql = $term_sql;
					}
					else
					{
						$download_sql = "($org_sql)   UNION ALL ($term_sql)";
					}
					//$download_sql = $org_sql ."UNION ALL ($term_sql)";
					$this->_catch_exception_msg(array('ClientsummarystatisController','_summary_reports_download_xls'),array('download_sql' => $download_sql,'file_name'=>$file_name));
				exit ();
			}
			elseif ($_GET ['query'] ['output'] == 'delayed') {
//				Configure::write('debug',0);
//					$download_sql = $org_sql ."UNION ALL ($term_sql)";
//					$this->_catch_exception_msg(array('ClientsummarystatisController','_summary_reports_download_impl'),array('download_sql' => $download_sql));
//				exit ();
			}else {
				//web显示
				$this->set ( "client_term", $term_list );
				$this->set ( "client_org", $org_list );
					
				$this->set ( "group_by_field_arr", $group_by_field_arr );
			
			}
		
		} else {
			//get 请求
//web显示
				$this->set ( "client_term", $term_list );
				$this->set ( "client_org", $org_list );
				$this->set ( "group_by_field_arr", $group_by_field_arr );
				
		}
	
		
		 $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));    
	}
	function _summary_reports_download_impl($params=array()){
 		extract($params);
		if($this->Cdr->download_by_sql($download_sql,array('objectives'=>'summary_reports','file_name'=>$file_name))){
			exit(1);
		} 
	}
	
	function _summary_reports_download_xls($params=array()){
 		extract($params);
		if($this->Cdr->download_xls_by_sql($download_sql,array('objectives'=>'summary_reports','file_name'=>$file_name))){
			exit(1);
		} 
	}
	
	function image_test()
	{
		//var_dump($_REQUEST);
		//Configure::write('debug',0);
		//$this->layout='csv';
		require_once("vendors/image_test.php");
		//App::import ( 'Vendor', 'image_test');
		//$this->layout='csv';
	}
	
//生成image所需查询结果文件，返回文件名
	function create_image_csv() 
	{		
		$return = '';
		//create数据文件	
		$where = $this->capture_report_condtions ('location_report');
		extract ($this->capture_report_join ('location_report',''));
		$today_statistic = $this->getTodayStatistic();
		
		 
			$sql="select to_char(time, 'YYYY-MM-DD HH24') as group_time, term_country, sum(ca) as ca, sum(ok_calls) as ok_calls,
			sum(call_duration) as call_duration, sum(ingress_cost) as ingress_cost, sum(egress_cost) as egress_cost, sum(orig_bill_minute)
			 as orig_bill_minute, sum(term_bill_minute) as term_bill_minute, sum(ingress_ca) as ingress_ca, sum(egress_ca) as egress_ca,
			 sum(not_zero_calls) as not_zero_calls, sum(busy_calls) as busy_calls, sum(no_channel_calls) as no_channel_calls
		  from $today_statistic  
		  where $where group by group_time, term_country having 1=1 
		  order by group_time desc";
		$result = $this->Cdr->query($sql);
		if (!empty($result))
		{
			$return = "/tmp/image_".date("Ymd-His")."_".rand(0,99);
			file_put_contents($return, json_encode($result));
		}
		return $return;
	}
	
}