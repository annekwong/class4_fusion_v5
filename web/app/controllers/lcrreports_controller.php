<?php

/**
 * 
 * 
 * 

  断开码分析
 * @author root
 *
 */
class LcrreportsController extends AppController {

    var $name = 'Lcrreports';
    var $uses = array('Cdr', 'LcrRecord');
    var $helpers = array('javascript', 'html', 'common');

    //查询封装
//读取该模块的执行和修改权限
    public function beforeFilter() {
        if( $this->params['action'] == 'backend' || $this->params['action'] == 'todo')
            return true;
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
        parent::beforeFilter();
    }

    //初始化查询参数
    function init_query() {

        $this->set('code_deck', $this->Cdr->find_code_deck());
        $this->set('currency', $this->Cdr->find_currency());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_alias());
        $this->set('egress', $this->Cdr->findAll_egress_alias());
    }

    function summary_reports() {

        //权限判断
        $privilege = ($_SESSION['login_type'] == 3) ? (" and (client_id = {$_SESSION['sst_client_id']})") : '';
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $this->init_query();
        //date_default_timezone_set ('Asia/Shanghai');
        $start = date("Y-m-d  00:00:00");
        $end = date("Y-m-d 23:59:59");
        $start_day = date("Y-m-d  ");
        $end_day = date("Y-m-d ");

        //单个的where查询条件
        $client_where = '';
        $code_name_where = '';
        $code_where = '';
        $code_deck_where = '';
        $server_where = '';
        $currency_where = '';
        $egress_where = '';
        $ingress_where = '';
        $interval_from_where = '';
        $interval_to_where = '';

        $dst_number_where = '';
        $duration_where = "";
        $disconnect_cause_where = '';
        $cost_where = '';
        $src_number_where = '';
        $call_id_where = '';




        $show_field = 'term_code_name,term_code, egress_alias';
        $show_field_array = array('0' => 'time', '1' => 'term_code_name', '2' => 'term_code', '3' => 'rate_table_name', '4' => 'cost');
        $group_by_field = 'term_code_name,term_code, egress_alias';
        $order_by = "order by   time desc";
        if (isset($_POST ['searchkey'])) {

            //日期条件
            $start_date = $_POST ['start_date']; //开始日期
            $start_time = $_POST ['start_time']; //开始时间
            $stop_date = $_POST ['stop_date']; //结束日期
            $stop_time = $_POST ['stop_time']; //结束时间
            $tz = $_POST ['query'] ['tz']; //时区
            $start_day = $start_date;
            $end_day = $stop_date;
            $start = $start_date . '  ' . $start_time . "  " . $tz; //开始时间
            $end = $stop_date . '  ' . $stop_time . "  " . $tz; //结束时间
            //********************************************************************************************************
            //            普通单个条件查询(按照代理商,帐号卡)
            //********************************************************************************************************

            $client_id = $_POST ['query'] ['id_clients'];

            if ($client_id != '') {
                $client_where = "and client_id='$client_id'";
                $this->set("client_name", $_POST ['query'] ['id_clients_name']);
            }

            if (isset($_POST ['query'] ['code_name'])) {
                $code_name = $_POST ['query'] ['code_name'];
                if (!empty($code_name)) {
                    $code_name_where = "and term_code_name='$code_name'";
                    $this->set("code_name", $_POST ['query'] ['code_name']);
                }
            }

            if (isset($_POST ['query'] ['code'])) {
                $code = $_POST ['query'] ['code'];
                if (!empty($code)) {
                    $code_where = "and term_code='$code'";
                    $this->set("code", $_POST ['query'] ['code']);
                }
            }

            //断开吗
            if (isset($_POST ['query'] ['disconnect_cause'])) {
                $disconnect_cause = $_POST ['query'] ['disconnect_cause'];
                if (!empty($disconnect_cause)) {
                    $disconnect_cause_where = "and release_cause_from_protocol_stack  like'%$disconnect_cause%'";
                    $this->set("disconnect_cause", $_POST ['query'] ['disconnect_cause']);
                }
            }



            if (isset($_POST ['query'] ['interval_from'])) {
                $interval_from = $_POST ['query'] ['interval_from'];
                if (!empty($interval_from)) {
                    $interval_from_where = "and call_duration::integer>$interval_from";
                    $this->set("interval_from", $_POST ['query'] ['interval_from']);
                }
            }
            if (isset($_POST ['query'] ['interval_to'])) {
                $interval_to = $_POST ['query'] ['interval_to'];
                if (!empty($interval_to)) {
                    $interval_to_where = "and call_duration::integer<$interval_to";
                    $this->set("interval_to", $_POST ['query'] ['interval_to']);
                }
            }


            //通话时长
            if (isset($_POST ['query'] ['duration'])) {
                $duration = $_POST ['query'] ['duration'];
                if (!empty($duration)) {
                    if ($duration == 'nonzero') {
                        $duration_where = "and call_duration::integer>0";
                    }
                    if ($duration == 'zero') {
                        $duration_where = "and call_duration::integer=0";
                    }

                    $this->set("duration", $_POST ['query'] ['duration']);
                }
            }



            //扣费
            if (isset($_POST ['query'] ['cost'])) {
                $cost = $_POST ['query'] ['cost'];
                if (!empty($cost)) {
                    if ($cost == 'nonzero') {
                        $cost_where = "and cost::numeric>0";
                    }
                    if ($cost == 'zero') {
                        $cost_where = "and cost::numeric=0.000";
                    }

                    $this->set("disconnect_cause", $_POST ['query'] ['cost']);
                }
            }








            //被叫号
            if (isset($_POST ['query'] ['dst_number'])) {
                $dst_number = $_POST ['query'] ['dst_number'];
                if (!empty($dst_number)) {
                    $dst_number_where = "and origination_destination_number='$dst_number'";
                    $this->set("dst_number", $_POST ['query'] ['dst_number']);
                }
            }


            //主叫号
            if (isset($_POST ['query'] ['src_number'])) {
                $src_number = $_POST ['query'] ['src_number'];
                if (!empty($src_number)) {
                    $src_number_where = "and origination_source_number='$src_number'";
                    $this->set("src_number", $_POST ['query'] ['src_number']);
                }
            }


            //call_id
            if (isset($_POST ['query'] ['call_id'])) {
                $call_id = $_POST ['query'] ['call_id'];
                if (!empty($call_id)) {
                    $call_id_where = "and origination_call_id='$call_id'";
                    $this->set("call_id", $_POST ['query'] ['call_id']);
                }
            }


            $server_ip = $this->data ['Cdr'] ['server_ip'];
            if (!empty($server_ip)) {
                $server_where = "and termination_source_host_name='$server_ip'";
                $this->set("server_ip", $this->data ['Cdr'] ['server_ip']);
            }
            $code_deck = $this->data ['Cdr'] ['code_deck'];
            if (!empty($code_deck)) {
                $code_deck_where = "and term_deck_name='$code_deck'";
                $this->set("code_deck_name", $code_deck);
            }
            $currency = $this->data ['Cdr'] ['currency'];

            if (!empty($currency)) {
                $currency_where = "and currency='$currency'";
                $this->set("currency_post", $this->data ['Cdr'] ['currency']);
            }
            $egress_alias = $this->data ['Cdr'] ['egress_alias'];

            if (!empty($egress_alias)) {
                $egress_where = "  and trunk_id_termination='$egress_alias'";
                $this->set("egress_post", $this->data ['Cdr'] ['egress_alias']);
            }
            $ingress_alias = $this->data ['Cdr'] ['ingress_alias'];
            if (!empty($ingress_alias)) {
                $ingress_where = "  and trunk_id_origination='$ingress_alias'";
                $this->set("egress_post", $this->data ['Cdr'] ['egress_alias']);
            }






            //cdr 显示字段
            if (isset($_POST ['query'] ['fields'])) {
                $show_field = '';
                $show_field_array = $_POST ['query'] ['fields'];
                if (!empty($show_field_array)) {
                    $show_field = join(',', $show_field_array);
                }
            } else {
                $show_field = 'time,term_code_name,term_code,rate_table_name,cost';
            }
        }


        if (isset($this->params['pass'][0])) {
            $client_where = "and client_id='{$this->params['pass'][0]}'";
        }



        $this->set("start", $start);
        $this->set("end", $end);
        $this->set("start_day", $start_day);
        $this->set("end_day", $end_day);
        $this->set('post', $this->data);
        $this->set('show_field_array', $show_field_array);




        //********************************************************************************************************
        //                                                                  基本sql
        //********************************************************************************************************

        $where = "$interval_from_where	$interval_to_where
			$client_where $code_name_where $code_where  $code_deck_where $server_where
    $currency_where  $egress_where  $ingress_where $dst_number_where
    $duration_where $disconnect_cause_where $cost_where  $src_number_where
    $call_id_where   $privilege
			";
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = $this->Cdr->query("select count(*) as c from   class4_view_lcr   
    where time  between   '$start'  and  '$end'  $where    group by   $group_by_field
      ");
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();

//通话费用
        $org_sql = "select $show_field,sum( bill_minutes::numeric) as total_time,sum(cost::numeric) as cost , 		from   class4_view_lcr   
    where time  between   '$start'  and  '$end'    $where  group by   $group_by_field     limit '$pageSize' offset '$currPage'";

        if (isset($_POST ['query'] ['output'])) {
            //下载
            if ($_POST ['query'] ['output'] == 'csv') {
                Configure::write('debug', 0);
                $this->layout = 'csv';
                //第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
                $this->Cdr->export__sql_data('download Cdr', $org_sql, 'cdr');
                $this->layout = 'csv';
                exit();
            } else {
                //web显示
                $results = $this->Cdr->query($org_sql);
                $page->setDataArray($results);
                $this->set('p', $page);
            }
        } else {
            $results = $this->Cdr->query($org_sql);
            $page->setDataArray($results);
            $this->set('p', $page);
        }
    }

    public function add() {
        $sql = "SELECT rate_table_id, name FROM rate_table ORDER BY name ASC";
        $result = $this->Cdr->query($sql);
        $this->set("ratetables", $result);
    }
    
    public function index() {
        $this->pageTitle = "Statistics/LCR Report";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        
        $this->data = $this->paginate('LcrRecord');
    }
    
    public function backend($id) {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $filename = uniqid('lcr_reports') . ".csv";
        $sql = "SELECT * FROM lcr_record where id = {$id}";
        $result = $this->LcrRecord->query($sql);
        $type = $result[0][0]['type'];
        $rate_table = $result[0][0]['rate_tables'];
        switch ($type) {
            case 'intra_rate':
                $sql_temp1 = "case intra_rate when '' then rate::real else intra_rate::real end";
                break;
            case 'inter_rate':
                $sql_temp1 = "case inter_rate when '' then rate::real else inter_rate::real end";
                break;
            case 'rate':
                $sql_temp1 = "rate::real";
                break;
        }

        // SORT
        $sql = "COPY(SELECT code,count(*),
(array_agg(name||','||{$type}))               
from
(
SELECT code,name,
case intra_rate when '' then rate::real else intra_rate::real end as
intra_rate,
case inter_rate when '' then rate::real else inter_rate::real end as
inter_rate,
rate from
filter_rate('{{$rate_table}}','1970-01-01
00:00:00') as t(code
text,rate_table_id text,effective_date text,code_name text,country
text,rate text,intra_rate text,inter_rate text,local_rate text,end_date
text,time_profile_id text,zone text,min_time text,grace_time
text,interval text,seconds text) left join rate_table on
t.rate_table_id=rate_table.rate_table_id::text
order by code,
{$sql_temp1}
) as r
group by code
order by 1 ) TO " . Configure::read('database_actual_export_path') . "'/$filename' CSV";
        $this->LcrRecord->query($sql);
        $sql = "update lcr_record set file = '{$filename}', status = 1 where id = {$id}";
        $this->LcrRecord->query($sql);
    }
    
    public function get_file($id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "SELECT file FROM lcr_record WHERE id = {$id}";
        $result = $this->LcrRecord->query($sql);
        $filename = $result[0][0]['file'];
        $contents = file_get_contents(Configure::read('database_export_path').DS.$filename);
        $contents = str_replace(array('"', "{", "}"), "", $contents);
        ob_clean();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=LCR_Report.csv");
        echo $contents;
    }
    
    public function delete($id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $this->LcrRecord->query("delete from lcr_record where id = {$id}");
        $this->Session->write('m', $this->LcrRecord->create_json(201, __('The record is deleted successfully!', true)));
        $this->redirect('/lcrreports/index');
    }

    public function do_add() {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        if ($this->RequestHandler->isPost()) {
            $type = $_POST['type'];
            $rate_table = implode(',', $_POST['rate_table']);
            $sql = "insert into lcr_record (type, rate_tables, status) values ('{$type}', '{$rate_table}', 0) returning id";
            $result = $this->LcrRecord->query($sql);
            $id = $result[0][0]['id'];
            $ch = curl_init();
            $fp = fsockopen($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $errno, $errstr, 30);
            if (!$fp) {
                echo "$errstr ($errno)<br />\n";
            } else {
                $out = "GET {$this->webroot}lcrreports/backend/{$id} HTTP/1.1\r\n";
                $out .= "Host: localhost\r\n";
                $out .= "Connection: Close\r\n\r\n";
                fwrite($fp, $out);
                /*
                忽略执行结果
                while (!feof($fp)) {
                    echo fgets($fp, 128);
                }
                */
                fclose($fp);
            }
            $this->Session->write('m', $this->LcrRecord->create_json(201, __('The record is  now in progress.!', true)));
            $this->redirect('/lcrreports/index');
        }
    }

}