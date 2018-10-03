<?php

class MonitorsreportsController extends AppController
{

    var $name = 'Monitorsreports';
    var $uses = array('Cdr', 'Monitor');
    var $helpers = array('javascript', 'html', 'AppQos', 'common');

    const CASE_WHEN_15MIN = "case when time::bigint <extract(epoch from now())::bigint  and time::bigint>extract(epoch from now())::bigint-(60*15)  then";
    const CASE_WHEN_1H = "case when time::bigint <extract(epoch from now())::bigint  and time::bigint>extract(epoch from now())::bigint-(3600*1) then";
    const CASE_WHEN_24H = "case when time::bigint <extract(epoch from now())::bigint  and time::bigint>extract(epoch from now())::bigint-(3600*24) then";

    function index()
    {
        $this->redirect('globalstats');
    }

    function flash_setting()
    {
        echo file_get_contents(APP . 'webroot' . DS . 'amstock' . DS . "amstock_settings.xml");
    }

    function create_flash_csv($obj)
    {
        Configure::write('debug', 0);
        $this->create_acd_csv($obj);
        $this->create_asr_csv($obj);
        $this->create_call_csv($obj);
        $this->create_cps_csv($obj);
        $this->create_profit_csv($obj);
        $this->create_pdd_csv($obj);
    }

    function create_global_csv($type)
    {
        $name = uniqid() . '.csv';
        $real_path = Configure::read('database_actual_export_path') . DS . $name;
        $real_path_actual = Configure::read('database_export_path') . DS . $name;
        $sql = "COPY(select to_timestamp(a::integer)::timestamp without time zone as time, b as val from qos_chart(1,1,{$type},3) 
                as t(a text,b text) ORDER BY a DESC) to '{$real_path}' csv";
        $this->Monitor->query($sql);
        $web_path = APP . 'webroot' . DS . 'upload' . DS . 'stock' . DS . $name;
        $flg = copy($real_path_actual, $web_path);
        $msg = "real_path_actual: $real_path_actual;web_path:$web_path; result:$flg";
        file_put_contents("/tmp/create_debug.log", $msg);
        chmod($web_path, 0777);
        return $web_path;
    }

    function get_where()
    {
        $active_where = '';
        if (isset($_GET['where']))
        {
            if ($_GET['where'] == 'active')
            {
                extract($this->sum_arr_sql());
                $active_where = "having ($acd_15min > 0 or $pdd_15min>0  or $asr_15min>0 or $ca_15min>0  or $acd_1h>0  or $pdd_1h>0  or $asr_1h>0  or  $ca_1h>0 or $acd_24h>0 or $pdd_24h>0  or $asr_24h>0  or $ca_24h>0)";
            }
        }
        return $active_where;
    }

    function sum_arr_sql()
    {
        $acd_15min = "((sum((" . self::CASE_WHEN_15MIN . " acd::real else 0  end))/(sum(" . self::CASE_WHEN_15MIN . " call_count::integer else 0 end)+0.0000001))/60)::numeric(20,2) ";
        $pdd_15min = "((sum((" . self::CASE_WHEN_15MIN . " pdd::real else 0  end))/(sum(" . self::CASE_WHEN_15MIN . " call_count::integer  else 0 end)+0.0000001)))::numeric(20,0) ";
        $asr_15min = "(sum((" . self::CASE_WHEN_15MIN . " call_count_asr::integer else 0  end)*asr::real)/(sum(" . self::CASE_WHEN_15MIN . " call_count_asr::integer else 0 end )+0.0000001) ) ::numeric(20,2)  ";
        $ca_15min = "sum((" . self::CASE_WHEN_15MIN . " ca::integer else 0  end))  ";

        $acd_1h = "((sum((" . self::CASE_WHEN_1H . " acd::real else 0  end))/(sum(" . self::CASE_WHEN_1H . " call_count::integer else 0 end )+0.0000001))/60)::numeric(20,2) ";
        $pdd_1h = "((sum((" . self::CASE_WHEN_1H . " pdd::real else 0  end))/(sum(" . self::CASE_WHEN_1H . " call_count::integer else 0 end )+0.0000001)))::numeric(20,0) ";
        $asr_1h = "(sum((" . self::CASE_WHEN_1H . " call_count_asr::integer else 0  end)*asr::real)/(sum(" . self::CASE_WHEN_1H . " call_count_asr::integer else 0 end )+0.0000001) ) ::numeric(20,2)";
        $ca_1h = "sum((" . self::CASE_WHEN_1H . " ca::integer else 0  end))  ";

        $acd_24h = "((sum((" . self::CASE_WHEN_24H . " acd::real else 0  end))/(sum(" . self::CASE_WHEN_24H . " call_count::integer else 0 end )+0.0000001))/60)::numeric(20,2) ";
        $pdd_24h = "((sum((" . self::CASE_WHEN_24H . " pdd::real else 0  end))/(sum(" . self::CASE_WHEN_24H . " call_count::integer else 0 end )+0.0000001)))::numeric(20,0) ";
        $asr_24h = "(sum((" . self::CASE_WHEN_24H . " call_count_asr::integer else 0  end)*asr::real)/(sum(" . self::CASE_WHEN_24H . " call_count_asr::integer else 0 end )+0.0000001)) ::numeric(20,2)";
        $ca_24h = "sum((" . self::CASE_WHEN_24H . " ca::integer else 0  end))  ";
        return compact('acd_15min', 'pdd_15min', 'asr_15min', 'ca_15min', 'acd_1h', 'pdd_1h', 'asr_1h', 'ca_1h', 'acd_24h', 'pdd_24h', 'asr_24h', 'ca_24h');
    }

    /**
     * 
     * 
     * 
     * 
     */
    function return_sum_sql()
    {
        return "((sum((" . self::CASE_WHEN_15MIN . " acd::real else 0  end))/(sum(" . self::CASE_WHEN_15MIN . " call_count::integer else 0 end)+0.0000001))/60)::numeric(20,2) as acd_15min,
			((sum((" . self::CASE_WHEN_15MIN . " pdd::real else 0  end))/(sum(" . self::CASE_WHEN_15MIN . " call_count::integer  else 0 end)+0.0000001)))::numeric(20,0) as pdd_15min, 
			(sum((" . self::CASE_WHEN_15MIN . " call_count_asr::integer else 0  end)*asr::real)/(sum(" . self::CASE_WHEN_15MIN . " call_count_asr::integer else 0 end )+0.0000001) ) ::numeric(20,2)  as asr_15min,        
			sum((" . self::CASE_WHEN_15MIN . " ca::integer else 0  end)) as ca_15min ,
			
			((sum((" . self::CASE_WHEN_1H . " acd::real else 0  end))/(sum(" . self::CASE_WHEN_1H . " call_count::integer else 0 end )+0.0000001))/60)::numeric(20,2) as acd_1h,
			((sum((" . self::CASE_WHEN_1H . " pdd::real else 0  end))/(sum(" . self::CASE_WHEN_1H . " call_count::integer else 0 end )+0.0000001)))::numeric(20,0) as pdd_1h, 
			(sum((" . self::CASE_WHEN_1H . " call_count_asr::integer else 0  end)*asr::real)/(sum(" . self::CASE_WHEN_1H . " call_count_asr::integer else 0 end )+0.0000001) ) ::numeric(20,2)  as asr_1h,        
			sum((" . self::CASE_WHEN_1H . " ca::integer else 0  end)
			) as ca_1h ,
			
			((sum((" . self::CASE_WHEN_24H . " acd::real else 0  end))/(sum(" . self::CASE_WHEN_24H . " call_count::integer else 0 end )+0.0000001))/60)::numeric(20,2) as acd_24h,
			((sum((" . self::CASE_WHEN_24H . " pdd::real else 0  end))/(sum(" . self::CASE_WHEN_24H . " call_count::integer else 0 end )+0.0000001)))::numeric(20,0) as pdd_24h, 
			(sum((" . self::CASE_WHEN_24H . " call_count_asr::integer else 0  end)*asr::real)/(sum(" . self::CASE_WHEN_24H . " call_count_asr::integer else 0 end )+0.0000001)) ::numeric(20,2)  as asr_24h,        
			sum((" . self::CASE_WHEN_24H . " ca::integer else 0  end)) as ca_24h ";
    }

    public function beforeFilter()
    {
//        $this->checkSession("login_type"); //核查用户身份
//        parent::beforeFilter();
    }

    public function _get_session_count()
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        $sendStr = "show_count_sip_session";
        if (socket_connect($socket, $ip, $port))
        {
            $content = '';
            socket_write($socket, $sendStr, strlen($sendStr));
            while ($buffer = socket_read($socket, 1024, PHP_NORMAL_READ))
            {
                $content .= $buffer;
                if (strpos($buffer, "~!@#$%^&*()") !== FALSE)
                {
                    break;
                }
                unset($buffer);
            }
        }
        socket_close($socket);
        $content = strstr($content, "~!@#$%^&*()", TRUE);
        echo $cmd;
    }

    function globalstats()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
        $this->pageTitle = "Statistics/QoS Report";
        $sql = "select sip_ip, sip_port, lan_ip, lan_port from switch_profile";
        $limit_servers = $this->Monitor->query($sql);
        $this->set('limit_servers', $limit_servers);
        $historys = $this->Monitor->get_history();
        $this->set('historys', $historys);
    }

    function create_config_xml($name)
    {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        Configure::write('debug', 0);
        $filename = "";
        switch ($name)
        {
            case 'global_stats_call':
                $filename = $this->create_global_csv(1);
                break;
            case 'global_stats_acd':
                $filename = $this->create_global_csv(4);
                break;
            case 'global_stats_asr':
                $filename = $this->create_global_csv(3);
                break;
            case 'global_stats_cps':
                $filename = $this->create_global_csv(2);
                break;
            case 'global_stats_pdd':
                $filename = $this->create_global_csv(5);
                break;
        }


        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Content-type:text/xml");

        echo <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<settings>
  <margins>12</margins>
  <number_format>
    <letters>
      <letter number="1000">K</letter>
      <letter number="1000000">M</letter>
      <letter number="10000000">B</letter>
    </letters>
  </number_format>
  <data_sets>
    <data_set>
      <title>Chart</title>
      <short>ES</short>
      <color>003399</color>
      <file_name>{$filename}</file_name>
      <csv>
        <reverse>1</reverse>
        <separator>,</separator>
        <date_format>YYYY-MM-DD hh-mm-ss</date_format>
        <columns>
          <column>date</column>
          <column>close</column>
        </columns>
      </csv>
    </data_set>
  </data_sets>
  <charts>
    <chart>
      <title>Value</title>
      <height>60</height>
      <column_width>100</column_width>
      <grid/>
      <values>
        <x>
          <bg_color>EEEEEE</bg_color>
        </x>
        <y_left>
          <bg_color>000000</bg_color>
          <unit></unit>
          <unit_position>left</unit_position>
          <digits_after_decimal>
            <data></data>
          </digits_after_decimal>
        </y_left>
      </values>
      <legend>
        <show_date>1</show_date>
      </legend>
      <comparing>
        <recalculate_from_start>0</recalculate_from_start>
        <use_open_value_as_base>0</use_open_value_as_base>
      </comparing>
      <events/>
      <trend_lines/>
      <graphs>
        <graph>
          <bullet>round_outline</bullet>
          <data_sources>
            <close>close</close>
          </data_sources>
          <legend>
            <date title="0" key="0">{close}</date>
            <period title="0" key="0"><![CDATA[open:<b>{open}</b> low:<b>{low}</b> high:<b>{high}</b> close:<b>{close}</b>]]></period>
          </legend>
        </graph>
      </graphs>
    </chart>
  </charts>
  <data_set_selector>
    <enabled>0</enabled>
    <drop_down>
      <scroller_color>C7C7C7</scroller_color>
    </drop_down>
  </data_set_selector>
  <period_selector>
    <periods_title>Zoom:</periods_title>
    <custom_period_title>Custom period:</custom_period_title>
    <periods>
      <period pid="0" type="hh" count="1">1H</period>
      <period pid="0" type="hh" count="24">24H</period>
      <period pid="0" type="DD" count="7">7D</period>
      <period pid="5" type="YTD" count="0">YTD</period>
      <period pid="6" type="MAX" count="0">MAX</period>
    </periods>
  </period_selector>
  <header>
    <enabled>0</enabled>
  </header>
  <balloon>
    <border_color>B81D1B</border_color>
  </balloon>
  <background>
    <alpha>100</alpha>
  </background>
  <scroller>
    <graph_data_source>close</graph_data_source>
    <playback>
      <enabled>1</enabled>
      <speed>3</speed>
    </playback>
  </scroller>
  <context_menu>
    <default_items>
      <print>0</print>
    </default_items>
  </context_menu>
</settings>

EOT;
    }

    public function get_sys_limit()
    {
        Configure::write("debug", 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $ip = $_POST['ip'];
        $port = $_POST['port'];

        $back_obj = $this->get_connect_obj();
        $rst = $back_obj->backend_get_qos_monitor($ip, $port);
        $systemCallStatistics = $back_obj->getSystemCallStatistics($ip, $port);
        $rst['call'] = $systemCallStatistics['call'];
        $rst['o_chan'] = $systemCallStatistics['o_chan'];
        $rst['o_cps'] = $systemCallStatistics['o_cps'];

        echo json_encode($rst);
        exit;
    }

    function point_prefix($pro_id, $prefix_id)
    {
        $data = $this->Monitor->point_prefix_history($pro_id, $prefix_id);
        $this->set('data', $data);
    }

    /**
     * 路由状态
     */
    function productstats()
    {
        require_once 'MyPage.php';
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $page = new MyPage();
        $count = $this->Monitor->count_prefix_history();
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currpage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Monitor->prefix_history($pageSize, $offset);
        $page->setDataArray($data);
        $this->set('p', $page);
    }

    function ipone($gress, $resource_id, $ip_id)
    {
        $data = $this->Monitor->filteripone($gress, $resource_id, $ip_id);
        $this->set('data', $data);
    }

    function ipone2($gress, $resource_id, $ip_id)
    {
        $resource_id = base64_decode($resource_id);
        $data = $this->Monitor->filteripone($gress, $resource_id, $ip_id);
        $this->set('data', $data);
    }

    public function get_trunk_count()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        Configure::write('debug', 0);
        $type = $_POST['type'];
        $resource_id = $_POST['resource_id'];
        $ip = $_POST['ip'];
        $port = $_POST['port'];
        $content = "";
        $cmd = "c4_find_res_limit {$resource_id}";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (@socket_connect($socket, $ip, $port))
        {
            socket_write($socket, $cmd, strlen($cmd));
        }
        else
        {
            return 'error';
        }
        while ($out = socket_read($socket, 2048))
        {
            $content .= $out;
            if (strpos($out, "~!@#$%^&*()") !== FALSE)
            {
                break;
            }
            unset($out);
        }
        $content = strstr($content, "~!@#$%^&*()", TRUE);
        socket_close($socket);
        $content_arr = explode("\n", $content);
        if ($type == 'ingress')
        {
            $line[] = array_pop(explode('=', $content_arr[0]));
            $line[] = array_pop(explode("=", $content_arr[2]));
        }
        elseif ($type == 'egress')
        {
            $line[] = array_pop(explode('=', $content_arr[1]));
            $line[] = array_pop(explode("=", $content_arr[3]));
        }
        echo json_encode($line);
    }

    function filterip($gress, $resource_id)
    {

//        $sql = "SELECT ip,port,info_ip,info_port FROM server_platform WHERE server_type = 2";
        $sql = "select sip_ip, sip_port,lan_ip, lan_port,profile_name from switch_profile";
        $limit_servers = $this->Monitor->query($sql);
        $this->set('limit_servers', $limit_servers);
        //list($cps, $cap) = $this->get_trunk_count($gress, $resource_id,Configure::read("backend.ip"), Configure::read("backend.port"));
        if ($gress == 'ingress')
        {
            $type = 5;
        }
        else
        {
            $type = 6;
        }
        require_once 'MyPage.php';
        $this->set('h_title', "$gress carrier");
        $this->set('host_ip', $this->find_host());
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $page = new MyPage();
        $count = $this->Monitor->count_filterip($resource_id, $type);
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currpage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Monitor->filterip($resource_id, $pageSize, $offset, $type);
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('type', $gress);
        $this->set('resource_id', $resource_id);
    }

    function filterip2($gress, $client_id)
    {
        $client_id = base64_decode($client_id);
        require_once 'MyPage.php';
        $this->set('h_title', "$gress carrier");
        $this->set('host_ip', $this->find_host());
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $page = new MyPage();
        $count = $this->Monitor->count_filterip($client_id, $gress);
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currpage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Monitor->filterip($client_id, $pageSize, $offset, $gress);
        $page->setDataArray($data);
        $this->set('p', $page);
    }

    public function get_trunk_ip_count()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        Configure::write('debug', 0);
        $type = $_POST['type'];
        $ip_id = $_POST['ip_id'];
        $ip = $_POST['ip'];
        $port = $_POST['port'];
        $content = "";
        $cmd = "c4_find_gw_limit {$ip_id}";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (@socket_connect($socket, $ip, $port))
        {
            socket_write($socket, $cmd, strlen($cmd));
        }
        else
        {
            return array(0, 0);
        }
        while ($out = socket_read($socket, 2048))
        {
            $content .= $out;
            if (strpos($out, "~!@#$%^&*()") !== FALSE)
            {
                break;
            }
            unset($out);
        }
        $content = strstr($content, "~!@#$%^&*()", TRUE);
        socket_close($socket);
        $content_arr = explode("\n", $content);
        if ($type == 'ingress')
        {
            $line[] = array_pop(explode('=', $content_arr[0]));
            $line[] = array_pop(explode("=", $content_arr[2]));
        }
        elseif ($type == 'egress')
        {
            $line[] = array_pop(explode('=', $content_arr[1]));
            $line[] = array_pop(explode("=", $content_arr[3]));
        }
        echo json_encode($line);
    }

    public function chart_ip($ip_id, $ctype)
    {
        $sql = "SELECT ip,port,info_ip,info_port FROM server_platform WHERE server_type = 2";
        $limit_servers = $this->Monitor->query($sql);
        $this->set('limit_servers', $limit_servers);
        $this->set('h_title', "$ctype carrier");
        $this->set('type', $ctype);
        $this->set('ip_id', $ip_id);
    }

    /**
     * 分别查询15分钟  一个小时  24小时以内的product infomation
     */
    public function static_sql()
    {
        return " sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr,
       								(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd,
       								(sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd, sum(ca::integer) as ca";
    }

    public function period_where_sql()
    {
        $period = '24h';
        if (!empty($_SESSION['period_select']))
        {
            $period = $_SESSION['period_select'];
        }
        if (!empty($_GET['period_select']))
        {
            $period = $_GET['period_select'];
        }

        $_SESSION['period_select'] = $period;
        $now_time = time();

        if ($period == '15min')
        {
            return " and time between ({$now_time}-(60*15))::text and  {$now_time}::text";
        }
        elseif ($period == '1h')
        {
            return "and time between ({$now_time}-(3600))::text and  {$now_time}::text";
        }
        elseif ($period == '24h')
        {
            return "and time between ({$now_time}-(3600*24))::text and  {$now_time}::text";
        }
        else
        {
            return " and time between ({$now_time}-(60*15))::text and {$now_time}::text";
        }
    }

    private function product_info($currPage = 1, $pageSize = 10, $search = null, $order = null)
    {
        if (empty($order))
        {
            $order = "ca  desc";
        }

        $period_where = $this->period_where_sql();
        $sum_sql = $this->static_sql();
        $active_having = $this->get_where();
        $name_where = !empty($_GET['search']) ? "  and (product.name like '%{$_GET['search']}%')" : '';
        $this->loadModel('Hostinfo');

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $count_sql = "select  count(*)as c from  (select  pro_id   from  prefix_info 
		left join product on product.product_id::text=prefix_info.pro_id  where 1=1 $name_where $period_where   group  by  pro_id) as  a ;";
        $totalrecords = $this->Hostinfo->query($count_sql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $page = $page->checkRange($page); //检查当前页范围
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = intval($pageSize * $currPage);


        $cid_sql = "
		select pro_id as id,product.name,$sum_sql  from  prefix_info left join product on product.product_id::text=prefix_info.pro_id
  	where  1=1 $name_where  $period_where
 		group  by    pro_id ,product.name   $active_having  order by $order";
        $cid_sql .= " limit $pageSize offset '$offset'";
        $finalResults = $this->Hostinfo->query($cid_sql);
        $page->setDataArray($finalResults);
        return $page;
    }

    /**
     * 
     * 
     * 时间安排
     */
    private function gettime()
    {
        $time = time(); //系统当前时间

        $fifteen = $time - 15 * 60; //15分钟之内的

        $ahour = $time - 60 * 60; //一个小时以内的

        $oneday = $time - 24 * 60 * 60; //24小时以内的

        $times = array();
        $times[0] = $fifteen;
        $times[1] = $ahour;
        $times[2] = $oneday;
        $times[3] = $time;

        return $times;
    }

    /**
     * 
     * 查找服务器
     * 
     * 
     */
    public function find_host()
    {
        $sql = "select  ip from  server_platform  ";
        $r = $this->Cdr->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r[$i][0]['ip'];
            $l[$key] = $key;
        }
        return $l;
    }

    /**
     * 
     * 
     * 查找某个product 的prefix
     */
    function prefix($product_id)
    {
        require_once 'MyPage.php';
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $page = new MyPage();
        $count = $this->Monitor->count_prefix_history_info($product_id);
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currpage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Monitor->prefix_history_info($product_id, $pageSize, $offset);
        $page->setDataArray($data);
        $this->set('p', $page);
    }

    private function prefix_info($currPage = 1, $pageSize = 10, $search = null, $order = null)
    {

        if (empty($order))
        {

            $order = "ca3  desc";
        }


        $period_where = $this->period_where_sql();
        $sum_sql = $this->static_sql();
        $active_having = $this->get_where();
        $name_where = !empty($_GET['search']) ? "  and (product_items.digits <@  '{$_GET['search']}')" : '';
        $product_id = $this->params['pass'][0];
        $this->loadModel('Hostinfo');
        $times = $this->gettime();
        //	$ttt = time();
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();

        $period_where = $this->period_where_sql();
        $sum_sql = $this->static_sql();
        $count_sql = "select  count(*)as c from  (select  prefix_id   from  prefix_info 
		left join product_items on product_items.item_id::text=prefix_info.prefix_id  where pro_id::text='$product_id' $name_where $period_where  group  by  prefix_id) as  a ;";
        $totalrecords = $this->Hostinfo->query($count_sql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $cid_sql = "select A.id, A.name,asr24h,acd24h,pdd24h,ca24h,asr1h,acd1h,pdd1h,ca1h,asr15m,acd15m,pdd15m,ca15m from
(select 
prefix_id as id,digits as name, 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr24h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd24h, 
(sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd24h, 
sum(ca::integer) as ca24h
from prefix_info left join product_items on product_items.item_id::text=prefix_info.prefix_id 
where pro_id='$product_id' and time::bigint between extract(epoch from now())::bigint-(3600*24)  
and  extract(epoch from now())::bigint group by prefix_id ,digits ) A 
LEFT JOIN
(select 
prefix_id as id,digits as name, 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr1h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd1h, 
(sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd1h, 
sum(ca::integer) as ca1h 
from prefix_info left join product_items on product_items.item_id::text=prefix_info.prefix_id 
where pro_id='$product_id' 
and time::bigint between extract(epoch from now())::bigint-(3600)  and  extract(epoch from now())::bigint group by prefix_id ,digits) B
ON A.id = B.id
LEFT JOIN
(select 
prefix_id as id,digits as name, 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr15m, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd15m, 
(sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd15m, 
sum(ca::integer) as ca15m 
from prefix_info left join product_items on product_items.item_id::text=prefix_info.prefix_id 
where pro_id='$product_id' 
and time::bigint between extract(epoch from now())::bigint-(60*15)  and  extract(epoch from now())::bigint group by prefix_id ,digits) C 
ON A.id = C.id  order by $order
";
//   $cid_sql = "
//   select    prefix_id as  id,digits   as  name ,$sum_sql  from prefix_info left join product_items on product_items.item_id::text=prefix_info.prefix_id
//   where  pro_id='$product_id' $name_where  $period_where group  by    prefix_id ,digits  $active_having  order by $order
//	 ";
        $cid_sql .= " limit $pageSize offset '$offset'";


        //echo $cid_sql;

        $finalResults = $this->Hostinfo->query($cid_sql);

        $page->setDataArray($finalResults);
        return $page;
    }

    /**
     * 对接网关
     */
    function ingress()
    {
        $this->set('host_ip', $this->find_host());
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pagesize = 10 : $pagesize = $_GET['size'];
        $search = null;
        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $page = $this->gress_info($currpage, $pagesize, $search, $this->_order_condtions(
                        array('acd_15min', 'pdd_15min', 'asr_15min', 'ca_15min', 'acd_1h', 'pdd_1h', 'asr_1h', 'ca_1h', 'acd_24h', 'pdd_24h', 'acd_1h', 'asr_24h', 'ca_24h')
        )); //查询产品信息
        //pr($page->getDataArray());
        $this->set('p', $page);
    }

    function carrier($gress)
    {
        require_once 'MyPage.php';
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        if ($gress == 'ingress')
        {
            $type = 3;
        }
        else
        {
            $type = 4;
        }
        $page = new MyPage();
        $count = $this->Monitor->count_carrier_info($type, $gress);
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currpage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Monitor->carrier_info($type, $gress, $pageSize, $offset);
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('h_title', "$gress carrier");
    }

    function carrier2($gress)
    {
        $this->set('h_title', "$gress carrier");
        $this->set('host_ip', $this->find_host());
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pagesize = 10 : $pagesize = $_GET['size'];
        $search = null;
        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $page = $this->carrier_info2($currpage, $pagesize, $search, $this->_order_condtions(array('acd', 'pdd', 'asr'))); //查询产品信息
        $this->set('p', $page);
    }

    private function carrier_info2($currPage = 1, $pageSize = 10, $search = null, $order = null)
    {
        if (empty($order))
        {

            $order = "ca24  desc";
        }
        $client_name = $_SESSION['carrier_panel']['Client']['name'];
        $active_having = $this->get_where();
        $name_where = !empty($_GET['search']) ? "  and (client.name like '%{$_GET['search']}%')" : '';
        $gress = $this->params['pass']['0'];
        $this->loadModel('Hostinfo');
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $period_where = $this->period_where_sql();
        $sum_sql = $this->static_sql();
        $count_sql = "
		select  count(*) as c  from  (
		select   resource.client_id  as id,client.name as  name  ,$sum_sql  from host_info 
		left join resource on resource.resource_id::text=host_info.res_id  
		left join  client  on  client.client_id=resource.client_id
		where $gress=true   $name_where  $period_where  and resource.alias = '$client_name'
		  group  by    resource.client_id ,client.name 	 $active_having ) as aa;";


        $totalrecords = $this->Hostinfo->query($count_sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        /* 	
          $cid_sql = "
          select   resource.client_id  as id,client.name as  name  ,$sum_sql  from host_info
          left join resource on resource.resource_id::text=host_info.res_id
          left join  client  on  client.client_id=resource.client_id
          where $gress=true   $name_where  $period_where
          group  by    resource.client_id ,client.name 	 $active_having
          order by $order
          ";
         */
        $cid_sql = "select A.id, A.name, asr24h, acd24h, pdd24h, ca24h,asr1h, acd1h, pdd1h, ca1h,asr15m, acd15m, pdd15m, ca15m  from
(select resource.resource_id as id,resource.alias as name , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr24h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd24h, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd24h, 
sum(ca::integer) as ca24h 
from host_info 
left join 
resource on resource.resource_id::text=host_info.res_id 
where $gress=true and time::bigint between extract(epoch from now())::bigint - (3600*24)
and extract(epoch from now())::bigint and resource.alias = '$client_name'
group by resource.resource_id ,resource.alias) A
left join
(select resource.resource_id as id,resource.alias as name , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr1h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd1h, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd1h, 
sum(ca::integer) as ca1h
from host_info 
left join 
resource on resource.resource_id::text=host_info.res_id 
where $gress=true and time::bigint between extract(epoch from now())::bigint - (3600)
and
extract(epoch from now())::bigint and resource.alias = '$client_name'
group by resource.resource_id ,resource.alias) B on A.id = B.id
left join
(select resource.resource_id as id,resource.alias as name , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr15m, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd15m, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd15m, 
sum(ca::integer) as ca15m 
from host_info 
left join 
resource on resource.resource_id::text=host_info.res_id 
 where $gress=true and time::bigint between extract(epoch from now())::bigint - (60*15)
and
extract(epoch from now())::bigint and resource.alias = '$client_name'
group by resource.resource_id ,resource.alias) C on A.id = C.id";

        $cid_sql .= " limit $pageSize offset '$offset'";
        $finalResults = $this->Hostinfo->query($cid_sql);


        $page->setDataArray($finalResults);
        return $page;
    }

    private function carrier_info($currPage = 1, $pageSize = 10, $search = null, $order = null)
    {
        if (empty($order))
        {

            $order = "ca24  desc";
        }
        $active_having = $this->get_where();
        $name_where = !empty($_GET['search']) ? "  and (client.name like '%{$_GET['search']}%')" : '';
        $gress = $this->params['pass']['0'];
        $this->loadModel('Hostinfo');
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $period_where = $this->period_where_sql();
        $sum_sql = $this->static_sql();
        $count_sql = "
		select  count(*) as c  from  (
		select   resource.client_id  as id,client.name as  name  ,$sum_sql  from host_info 
		left join resource on resource.resource_id::text=host_info.res_id  
		left join  client  on  client.client_id=resource.client_id
		where $gress=true   $name_where  $period_where
		  group  by    resource.client_id ,client.name 	 $active_having ) as aa;";


        $totalrecords = $this->Hostinfo->query($count_sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        /* 	
          $cid_sql = "
          select   resource.client_id  as id,client.name as  name  ,$sum_sql  from host_info
          left join resource on resource.resource_id::text=host_info.res_id
          left join  client  on  client.client_id=resource.client_id
          where $gress=true   $name_where  $period_where
          group  by    resource.client_id ,client.name 	 $active_having
          order by $order
          ";
         */
        $cid_sql = "select A.id, A.name, asr24h, acd24h, pdd24h, ca24h,asr1h, acd1h, pdd1h, ca1h,asr15m, acd15m, pdd15m, ca15m  from
(select resource.resource_id as id,resource.alias as name , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr24h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd24h, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd24h, 
sum(ca::integer) as ca24h 
from host_info 
left join 
resource on resource.resource_id::text=host_info.res_id 
where $gress=true and time::bigint between extract(epoch from now())::bigint - (3600*24)
and extract(epoch from now())::bigint
group by resource.resource_id ,resource.alias) A
left join
(select resource.resource_id as id,resource.alias as name , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr1h, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd1h, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd1h, 
sum(ca::integer) as ca1h
from host_info 
left join 
resource on resource.resource_id::text=host_info.res_id 
where $gress=true and time::bigint between extract(epoch from now())::bigint - (3600)
and
extract(epoch from now())::bigint
group by resource.resource_id ,resource.alias) B on A.id = B.id
left join
(select resource.resource_id as id,resource.alias as name , 
sum(call_count_asr::integer*asr::real)/NULLIF(sum(call_count_asr::integer),0) as asr15m, 
(sum(acd::real)/NULLIF(sum(call_count::integer),0))/60 as acd15m, (sum(pdd::real) / NULLIF(sum(call_count::integer),0)) as pdd15m, 
sum(ca::integer) as ca15m 
from host_info 
left join 
resource on resource.resource_id::text=host_info.res_id 
 where $gress=true and time::bigint between extract(epoch from now())::bigint - (60*15)
and
extract(epoch from now())::bigint
group by resource.resource_id ,resource.alias) C on A.id = C.id order by A.name asc";

        $cid_sql .= " limit $pageSize offset '$offset'";

        $finalResults = $this->Hostinfo->query($cid_sql);


        $page->setDataArray($finalResults);
        return $page;
    }

    function gress()
    {
        $gress = $this->params['pass']['0'];

        $this->set('h_title', "$gress trunk");
        $this->set('host_ip', $this->find_host());
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pagesize = 10 : $pagesize = $_GET['size'];
        $search = null;
        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $page = $this->gress_info($currpage, $pagesize, $search, $this->_order_condtions(
                        array('acd', 'pdd', 'asr')
        )); //查询产品信息
        //pr($page->getDataArray());
        $this->set('p', $page);
    }

    private function gress_info($currPage = 1, $pageSize = 10, $search = null, $order = null)
    {
        if (empty($order))
        {

            $order = "ca  desc";
        }
        $active_having = $this->get_where();
        $name_where = !empty($_GET['search']) ? "  and (resource.alias like '%{$_GET['search']}%')" : '';
        $client_where = !empty($this->params['pass']['1']) ? "  and (resource.client_id ={$this->params['pass']['1']})" : '';
        $gress = $this->params['pass']['0'];
        $_SESSION['gress'] = $gress;
        $this->loadModel('Hostinfo');
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $period_where = $this->period_where_sql();
        $sum_sql = $this->static_sql();
        $count_sql = "select  count(*)as c from  (select  res_id   from  host_info 
		left join resource on resource.resource_id::text=host_info.res_id  where $gress=true $name_where   $period_where $client_where  group  by  res_id) as  a ;";


        $totalrecords = $this->Hostinfo->query($count_sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $cid_sql = "
		select   res_id  as id,alias as  name  ,$sum_sql  from host_info left join resource on resource.resource_id::text=host_info.res_id  where $gress=true  
		 $name_where  $client_where $period_where
		  group  by    res_id ,resource.alias 	 $active_having
order by $order
		";

        $cid_sql .= " limit $pageSize offset '$offset'";
        $finalResults = $this->Hostinfo->query($cid_sql);

        $page->setDataArray($finalResults);
        return $page;
    }

    /**
     * 落地网关
     */
    function egress()
    {
        $this->set('host_ip', $this->find_host());
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pagesize = 10 : $pagesize = $_GET['size'];
        $search = null;
        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $page = $this->gress_info($currpage, $pagesize, $search, $this->_order_condtions(
                        array('acd_15min', 'pdd_15min', 'asr_15min', 'ca_15min', 'acd_1h', 'pdd_1h', 'asr_1h', 'ca_1h', 'acd_24h', 'pdd_24h', 'acd_1h', 'asr_24h', 'ca_24h')
        )); //查询产品信息
        //pr($page->getDataArray());
        $this->set('p', $page);
    }

    /**
     * 查看网关的host
     */
    function host()
    {
        $res_id = $this->params['pass'][0];
        $obj = array('table' => 'host_info', 'where' => "  res_id='$res_id'   and");
        $this->create_flash_csv($obj);
        $this->set('host_ip', $this->find_host());
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pagesize = 10 : $pagesize = $_GET['size'];
        $search = null;
        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $page = $this->host_info($currpage, $pagesize, $search, $this->_order_condtions(
                        array('acd_15min', 'pdd_15min', 'asr_15min', 'ca_15min', 'acd_1h', 'pdd_1h', 'asr_1h', 'ca_1h', 'acd_24h', 'pdd_24h', 'acd_1h', 'asr_24h', 'ca_24h')
        )); //查询产品信息
        //pr($page->getDataArray());
        $this->set('p', $page);
    }

    /**
     * 
     * 
     * 查看指定的host
     */
    function host_ip()
    {
        $this->set('host_ip', $this->find_host());
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pagesize = 10 : $pagesize = $_GET['size'];
        $search = null;
        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $page = $this->host_ip_info($currpage, $pagesize, $search); //查询产品信息
        //pr($page);
        $this->set('mydata', $page);
        $this->set('ip_id', $this->params['pass'][0]);
        $this->set('ip', $this->params['pass'][1]);
    }

    private function host_info($currPage = 1, $pageSize = 10, $search = null, $order = null)
    {
        if (empty($order))
        {

            $order = "ca  desc";
        }
        $active_having = $this->get_where();
        $name_where = !empty($_GET['search']) ? "  and (resource_ip.ip::text like '%{$_GET['search']}%')" : '';
        $res_id = $this->params['pass'][0];
        $period_where = $this->period_where_sql();
        $sum_sql = $this->static_sql();
        $this->loadModel('Hostinfo');
        $times = $this->gettime();
        //	$ttt = time();
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $count_sql = "select  count(*)as c from  (select  ip_id   from  host_info 
		left join resource_ip on resource_ip.resource_ip_id::text=host_info.ip_id  where resource_ip.resource_id=$res_id $name_where  $period_where  group  by  ip_id) as  a ;";


        $totalrecords = $this->Hostinfo->query($count_sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //	$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $cid_sql = "
    select    ip_id  as  id,resource_ip.ip  ,$sum_sql from host_info left join resource_ip on resource_ip.resource_ip_id::text=host_info.ip_id
     where resource_ip.resource_id=$res_id  $name_where  $period_where
 group  by    ip_id ,resource_ip.ip  $active_having   	order  by $order ";





        $cid_sql .= " limit $pageSize offset '$offset'";
        $finalResults = $this->Hostinfo->query($cid_sql);
        $page->setDataArray($finalResults);
        return $page;
    }

    /**
     * 
     * 
     * 查看指定的host的统计信息
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     * @param unknown_type $search
     */
    private function host_ip_info($currPage = 1, $pageSize = 10, $search = null)
    {
        $ip_id = $this->params['pass'][0];

        $this->loadModel('Hostinfo');
        $times = $this->gettime();

        $sum_sql = $this->return_sum_sql();
        $cid_sql = "
   select    $sum_sql
   from host_info left join resource_ip on resource_ip.resource_ip_id::text=host_info.ip_id
   where ip_id='$ip_id'  

		
 
	 ";
        $finalResults = $this->Hostinfo->query($cid_sql);
        return $finalResults;
    }

}
