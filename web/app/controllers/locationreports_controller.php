<?php

/**
 * 
 * 
  地区汇总统计
 * @author root
 *
 */
class LocationreportsController extends AppController {

    var $name = 'Locationreports';
    var $uses = array('Cdr');
    var $helpers = array('javascript', 'html');
    var $report_query_time = array();


    #report field
//	var $report_sql_field =	array(
//		'total_duration'=>"(sum(case when call_duration='' then 0 else call_duration::numeric  end )/60  )   as total_duration",
//		'total_calls'=>"count(*) as  total_calls  ",
//		'total_cdr_cost'=>"	sum( case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end) ::numeric  as total_cdr_cost",
//		'total_egress_cost'=>"sum(case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end)  ::numeric as total_egress_cost",
//		'profit'=>"	sum(case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)-sum(case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end)  ::numeric as profit",
//		'profit_percentage'=>"	case when sum(case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)=0  then 0 else 
//			((sum(case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end)-sum(case  egress_cost  when   'nan'  then null else  egress_cost::numeric   end))*100/sum(case   ingress_client_cost  when   'nan'  then null else  ingress_client_cost::numeric   end))  end ::numeric  as profit_percentage"
//		);
    var $report_sql_field = array(
        'total_duration' => "sum(call_duration)   as total_duration",
        'total_calls' => "sum(ca) as  total_calls  ",
        'total_cdr_cost' => "	sum(ingress_cost)  as total_cdr_cost",
        'total_egress_cost' => "sum(egress_cost)  ::numeric as total_egress_cost",
        'profit' => "	sum(ingress_cost)-sum(egress_cost) as profit",
        'profit_percentage' => "	case when sum(ingress_cost)=0  then 0 else 
			((sum(ingress_cost)-sum(egress_cost))*100/sum(ingress_cost))  end ::numeric  as profit_percentage"
    );

    function get_field_sql($field) {
        $arr = array(
            'duration' => "(sum(case when call_duration='' then 0 else call_duration::numeric end )/60 ) ::numeric as total_duration",
            'call_count' => 'count(*) as total_calls ',
            'revenue' => 'sum(ingress_client_cost::numeric) ::numeric as total_cdr_cost',
            'total_cost' => ' sum(egress_cost::numeric) ::numeric as total_egress_cost',
            'profit' => 'sum(ingress_client_cost::numeric)-sum(egress_cost::numeric) ::numeric as profit',
            'profit_percentage' => 'case when sum(ingress_client_cost::numeric)=0 then 0 else ((sum(ingress_client_cost::numeric)-sum(egress_cost::numeric))*100/sum(ingress_client_cost::numeric)) end ::numeric as profit_percentage ',
        );
        $arr = array(
            'duration' => "sum(call_duration) ::numeric as total_duration",
            'call_count' => 'sum(ca) as total_calls ',
            'revenue' => 'sum(ingress_cost) as total_cdr_cost',
            'total_cost' => ' sum(egress_cost) as total_egress_cost',
            'profit' => 'sum(ingress_cost)-sum(egress_cost) as profit',
            'profit_percentage' => 'case when sum(ingress_cost)=0 then 0 else ((sum(ingress_cost)-sum(egress_cost))*100/sum(ingress_cost)) end ::numeric as profit_percentage ',
        );
        if (isset($arr[$field])) {
            return $arr[$field];
        } else {
            return $arr['duration'];
        }
    }

    function create_amchart_setting_xml($country_arr, $field) {
        $humanize_field = Inflector::humanize($field);
        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $out.= '<settings>';
        $out.= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_base.xml");
        $out.= '<data_sets>';

        foreach ($country_arr as $key => $vule) {
            $out .= "<data_set did=\"$key\">\n";
            $out .= " <title>$vule</title>\n";
            $out .= " <short>$vule $humanize_field</short>\n";
            $out .= " <description>   $humanize_field;</description>\n";
            $out .= "<file_name>location_report_$field" . "$key.csv</file_name>\n";
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
        }
        $out.= '</data_sets>';
        $out.= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_charts.xml");

        $out .= '</settings>';
        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "locationreports_$field" . "_amstock_settings.xml";
        $fp = fopen($xml_file, 'w');
        fwrite($fp, $out);
        fclose($fp);
    }

    function create_amchart_flash() {

        //create数据文件
        $country_arr = $this->get_all_country();
        $this->create_amchart_csv($country_arr, 'duration');
        $this->create_amchart_csv($country_arr, 'call_count');
        $this->create_amchart_csv($country_arr, 'revenue');
        $this->create_amchart_csv($country_arr, 'total_cost');
        $this->create_amchart_csv($country_arr, 'profit');
        $this->create_amchart_csv($country_arr, 'profit_percentage');

        //create  xml
        $this->create_amchart_setting_xml($country_arr, 'duration');
        $this->create_amchart_setting_xml($country_arr, 'call_count');
        $this->create_amchart_setting_xml($country_arr, 'revenue');
        $this->create_amchart_setting_xml($country_arr, 'total_cost');
        $this->create_amchart_setting_xml($country_arr, 'profit');
        $this->create_amchart_setting_xml($country_arr, 'profit_percentage');
    }

    function flash_setting() {
        Configure::write('debug', 0);
        $field = !empty($_GET ['f']) ? $_GET ['f'] : 'duration';
        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "locationreports_$field" . "_amstock_settings.xml";
        echo file_get_contents($xml_file);
    }

    # set title

    function set_title() {
        $this->set('title1', __('Statistics', true));
        $this->set('title2', __('LocationReport', true));
    }

    function get_all_country() {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));

        $org_sql = "select  distinct  term_country  from client_cdr $join  where $where";
        //$org_sql="select  distinct  term_country  from statistic_cdr $join  where $where";
        $org_list = $this->Cdr->query($org_sql);

        $tmp_arr = array();
        if (!empty($org_list)) {
            $size = count($org_list);
            foreach ($org_list as $key => $value) {
                $tmp_arr[] = $value[0]['term_country'];
            }
        }
        return $tmp_arr;
    }

    /**
     * 
     * @param $type
     * @param $params  	$params=compact('group_by_field','group_by_where','column');
     */
    function get_datas($type = '', $params = array()) {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));
        $order = $this->capture_report_order();
        $duration_where = '';
        if (isset($_GET ['searchkey'])) {
            if (isset($_GET ['query'] ['duration'])) {
                $duration = $_GET ['query'] ['duration'];
                if (!empty($duration)) {
                    if ($duration == 'nonzero') {
                        //$duration_where = "and (sum(case when call_duration='' then 0 else call_duration::numeric  end )/60  )>0";
                        $duration_where = "and sum(call_duration)>0";
                    }
                    if ($duration == 'zero') {
                        //$duration_where = "and (sum(case when call_duration='' then 0 else call_duration::numeric  end )/60  )=0";
                        $duration_where = "and sum(call_duration)=0";
                    }
                }
            }
        }

        $column = implode(',', array_values($this->report_sql_field));
        $statistic_cdr = $this->getTodayStatistic();
//			$org_sql = "select   $group_by_field,$column	from client_cdr $join  where $where  group by  $group_by_where  having 1=1  $duration_where $order";
//			$org_list = $this->Cdr->query ($org_sql );
//	  	$sum_sql="select  $column  from  client_cdr $join   where $where";
//	   $sum_list = $this->Cdr->query ($sum_sql );
        if (!empty($group_by_where)) {
            $order .= ',' . $group_by_where;
        }
        $org_sql = "select   $group_by_field,$column	from $statistic_cdr $join  where $where  group by  $group_by_where  having 1=1  $duration_where $order";
        $org_list = $this->Cdr->query($org_sql);
        $sum_sql = "select  $column  from $statistic_cdr $join   where $where";
        $sum_list = $this->Cdr->query($sum_sql);
        $this->set('sum_list', $sum_list);
        return compact('org_sql', 'org_list', 'group_by_field_arr', 'sum_list');
    }

    function create_amchart_csv($country_arr, $field) {
        $field_sql = $this->get_field_sql($field);
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));
        $today_statistic = $this->getTodayStatistic();

        foreach ($country_arr as $key => $value) {
//			$sql=" copy (
//			select to_char(time, 'YYYY-MM-DD HH24') as group_time,$field_sql
//		  from client_cdr $join 
//		  where $where and term_country='$value' group by group_time having 1=1 
//		  order by group_time desc
//			 ) to '/tmp/exports/location_report_$field"."$key.csv'  csv ";
            $sql = " copy (
			select to_char(statistic_time, 'YYYY-MM-DD HH24') as group_time,$field_sql
		  from (statistic_cdr $today_statistic) as cdr  $join 
		  where $where and term_country='$value' group by group_time having 1=1 
		  order by group_time desc
			 ) to '" .Configure::read('database_actual_export_path') . "/location_report_$field" . "$key.csv'  csv ";
            $this->Cdr->query($sql);
            copy( Configure::read('database_export_path') . "/location_report_$field" . "$key.csv", APP . 'webroot' . DS . 'amstock' . DS . "location_report_$field" . "$key.csv");
        }
    }

    function index() {
        $this->redirect('summary_reports');
    }

    function summary_reports() {

        $this->_session_get(isset($_GET ['searchkey']));
        $this->set('show_comm', true);
        $this->set_title();

        $this->pageTitle = "Statistics/Location Report";
        $t = getMicrotime();
        $this->init_query();

        //取数据  	 sql  ==> org_sql  , 数据===>org_list, 分组条件====>'group_by_field_arr');
        extract($this->get_datas());
        //画图的数据文件
        $image_file = $this->create_image_csv();
        $this->set('image_file', basename($image_file));

        $country_arr = $this->get_all_country();
        //var_dump($country_arr);
        $this->set('country_arr', $country_arr);
        //$this->create_amchart_flash();
        $this->set('field', 'ca');

        extract($this->get_start_end_time());
        $this->set("group_by_field_arr", $group_by_field_arr);
        if (isset($_GET ['query'] ['output'])) {
            extract($this->report_query_time);
            $file_name = $this->create_doload_file_name('LocationReport', $start, $end);
            //下载
            if ($_GET ['query'] ['output'] == 'csv') {
                Configure::write('debug', 0);
                $this->layout = 'csv';
                $this->Cdr->export__sql_data(__('ExportLocationReport', true), $org_sql, $file_name);
                $this->layout = 'csv';
                exit();
            } elseif ($_GET['query']['output'] == 'xls') {
                Configure::write('debug', 0);
                $this->Cdr->download_xls_by_sql($org_sql, array('objectives' => 'ExportLocationReport', 'file_name' => $file_name));
                exit(1);
            } elseif ($_GET['query']['output'] == 'delayed') {
                //void
            } else {

                $this->set("client_org", $org_list);
            }
        } else {

            $this->set("client_org", $org_list);
        }
        $_SESSION['report_list'] = $org_list;


        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
    }

    //读取该模块的执行和修改权限
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_retail_rcardpools');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }

        $this->params['start_time'] = getmicrotime();
        parent::beforeFilter();
    }

    function beforeRender() {
        $this->params['start'] = getmicrotime();
        parent::beforeRender();
    }

    //初始化查询参数
    function init_query() {
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('currency', $this->Cdr->find_currency());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());

        if (!empty($_GET['ingress_alias'])) {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_alias']);
            $this->set('tech_perfix', $res);
        }
    }

    function image_test() {
        //var_dump($_REQUEST);
        //Configure::write('debug',0);
        //$this->layout='csv';
        require_once("vendors/image_test.php");
        //App::import ( 'Vendor', 'image_test');
        //$this->layout='csv';
    }

    //生成image所需查询结果文件，返回文件名
    function create_image_csv() {
        $return = '';
        //create数据文件	
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));
        $today_statistic = $this->getTodayStatistic();


        $sql = "select to_char(time, 'YYYY-MM-DD HH24') as group_time, term_country, sum(ca) as ca, sum(ok_calls) as ok_calls,
			sum(call_duration) as call_duration, sum(ingress_cost) as ingress_cost, sum(egress_cost) as egress_cost, sum(orig_bill_minute)
			 as orig_bill_minute, sum(term_bill_minute) as term_bill_minute, sum(ingress_ca) as ingress_ca, sum(egress_ca) as egress_ca,
			 sum(not_zero_calls) as not_zero_calls, sum(busy_calls) as busy_calls, sum(no_channel_calls) as no_channel_calls
		  from $today_statistic  
		  where $where group by group_time, term_country having 1=1 
		  order by group_time desc";
        $result = $this->Cdr->query($sql);
        if (!empty($result)) {
            $return = "/tmp/image_" . date("Ymd-His") . "_" . rand(0, 99);
            file_put_contents($return, json_encode($result));
        }
        return $return;
    }

}