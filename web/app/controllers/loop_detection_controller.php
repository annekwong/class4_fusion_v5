<?php

class LoopDetectionController extends AppController
{

    var $name = "LoopDetection";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common','AppCdr');
    var $components = array('RequestHandler');
    var $uses = array('LoopDetection','Resource','LoopDetectionDetail');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function index()
    {
        $this->pageTitle = "Monitoring/Loop Detection";
        $order_arr = array(
            'rule_name' => 'asc'
        );
        if (!$this->LoopDetection->find('count',array())){
            $this->redirect('save');
        }
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_temp_arr = explode('-', $order_by);
            if (count($order_temp_arr) == 2)
            {
                $field = $order_temp_arr[0];
                $sort = $order_temp_arr[1];
                $order_arr[$field] = $sort;
            }
        }
        $page = $this->_get('size',100);
        $this->paginate = array(
            'fields' => array(),
            'limit' => $page,
            'order' => $order_arr,
            'conditions' => array(
            ),
        );
        $this->data = $this->paginate('LoopDetection');
    }

    public function save($encode_rule_id = '')
    {
        $this->pageTitle = "Monitoring/Loop Detection";
        $rule_id = base64_decode($encode_rule_id);

        if ($this->RequestHandler->isPost())
        {
            $rule_name = $this->data['rule_name'];
            $count = $this->LoopDetection->find('count',array(
                'conditions' => array(
                    'rule_name' => $rule_name,
                    'id != ?' => intval($rule_id)
                ),
            ));
            if ($count){
                $this->Session->write('m', $this->LoopDetection->create_json(101, __('Rule name is exist!', true)));
                $this->redirect('save/'.$encode_rule_id);
            }
            if ($this->LoopDetection->save($this->data) === false){
                $this->Session->write('m', $this->LoopDetection->create_json(101, __('Save Failed!', true)));
                $this->redirect('save/'.$encode_rule_id);
            }
            if (!$rule_id){
                $rule_id = $this->LoopDetection->getLastInsertID();
            }
            $detail_arr = array();
            $resource_arr = array();
            foreach ($this->params['form']['ingress_trunk'] as $ingress_id){
                $detail_arr[] = array(
                    'loop_detection_id' => $rule_id,
                    'resource_id' => $ingress_id
                );
                $resource_arr[] = array(
                    'resource_id' => $ingress_id,
                    'counter_time' => $this->data['counter_time'],
                    'number' => $this->data['number'],
                    'block_time' => $this->data['block_time'],
                );
            }

            $this->LoopDetectionDetail->deleteAll(array(
                'loop_detection_id' => $rule_id
            ));
            $this->LoopDetectionDetail->saveAll($detail_arr);

            $this->Resource->saveAll($resource_arr);

            $rule_info = $this->LoopDetection->findById($rule_id);
            if ($rule_info){
                $this->data = $rule_info['LoopDetection'];
            }
            $rule_name = $rule_info['LoopDetection']['rule_name'];

            if($encode_rule_id){
                $action = 'modified';
            }
            else {
                $action = 'created';
            }
            $this->Session->write('m', $this->LoopDetection->create_json(201, __('The rule name [' . $rule_name . '] is ' . $action . ' successfully!', true)));
            $this->redirect("index");
        }

        $rule_id = intval($rule_id);
        $rule_info = $this->LoopDetection->findById($rule_id);
        if ($rule_info){
            $this->data = $rule_info['LoopDetection'];
        }

        $more_where = " AND not exists(select 1 from loop_detection_detail where loop_detection_id != $rule_id and resource_id = resource.resource_id) ";
        $this->set('ingress_group',$this->LoopDetection->get_client_ingress_group());
        $this->set('ingress_arr',$this->LoopDetectionDetail->findIngressByRule($rule_id));

    }

    public function logging()
    {
        $this->pageTitle = "Monitoring/Loop Detection";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('LoopDetectionLog');
    }

    public function logging_detail($id)
    {
        $this->pageTitle = "Monitoring/Loop Detection";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => array(
                'loop_detection_log_id' => $id,
            )
        );
        $this->data = $this->paginate('LoopDetectionLogDetail');
    }

    function js_save($resource_id = null)
    {
        if ($resource_id)
        {
            $data = $this->Resource->find('first', Array('conditions' => Array('resource_id' => $resource_id)));
            $this->data = $data;
            $this->set('name',$data['Resource']['alias']);
        }

//        $this->_render_set_options(Array('Currency', 'Codedeck', 'Jurisdictioncountry'), Array('Jurisdictioncountry' => Array('conditions' => '1=1 group by id,name', 'fields' => Array('id', 'name'))));
        $this->layout = 'ajax';
        Configure::write('debug', 0);
    }

    public function ajax_check_rule_name()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $rule_name = $this->params['url']['fieldValue'];
        $rule_id = $this->params['url']['rule_id'];
        $ajax_id = $this->params['url']['fieldId'];

        $count = $this->LoopDetection->find('count', array(
            'conditions' => array(
                'rule_name' => $rule_name,
                'id != ?' => intval($rule_id)
            ),
        ));
        if ($count)
            return json_encode(array($ajax_id, false));
        else
            return json_encode(array($ajax_id, true));
    }

    public function delete_rule($encode_id){
        $id = base64_decode($encode_id);
        $rule_info = $this->LoopDetection->findById($id);
        if (!$rule_info){
            $this->Session->write('m', $this->LoopDetection->create_json(101, __('Illegal operation!', true)));
            $this->redirect("index");
        }
        $rule_name = $rule_info['LoopDetection']['rule_name'];
        if ($this->LoopDetection->delete_rule($id) === false){
            $this->Session->write('m', $this->LoopDetection->create_json(101, __('Delete failed!', true)));
        }else{
            $this->Session->write('m', $this->LoopDetection->create_json(201, __('The loop detection [%s] is deleted successfully!', true,array($rule_name))));
        }
        $this->redirect("index");


    }

    public function loop_found(){
        $this->pageTitle = "Statistics/Spam Report ";
        $this->loadModel('CdrsRead');
        $t = getMicrotime();

        extract($this->get_datas());
        $this->set('cdr_field', $this->Cdr->find_field());
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';
        $page = new MyPage ();
        //$totalrecords = $this->Cdr->query($count_sql);
        $page->setTotalRecords(1000); //总记录数
        //$page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;
        $page_where = " limit '$pageSize' offset '$offset'";
        $org_page_sql = $org_sql . $page_where;
        $this->set('show_nodata', true);
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdr->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];


        if ($is_preload && $_SESSION['login_type'] == 1)
        {
            $this->set('show_nodata', true);
            $results = $this->CdrsRead->query($org_page_sql);
            $this->set('results_count',count($results));
        }
        else
        {
            $this->set('show_nodata', false);
            $results = array();
        }
        $page->setDataArray($results);
        $this->set('p', $page);

        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        $this->set('ingress', $this->Cdr->findAll_ingress_id());

    }

    function get_datas()
    {
        $this->loadModel('Cdr');
        extract($this->get_start_end_time());
        if(empty($start) || empty($end) )
        {
            if (!empty($_GET ['start_date']) && !empty($_GET ['stop_date']) && !empty($_GET ['query']['tz']))
            {

                if (empty($_GET['start_time']))
                    $_GET['start_time'] = "00:00:00";
                if (empty($_GET['stop_time']))
                    $_GET['stop_time'] = "00:00:00";

                $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
                $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
            } else
            {
                extract($this->Cdr->get_real_period());
            }
        }
        $this->report_query_time = array('start' => $start, 'end' => $end);
        $this->set("start", $start);
        $this->set("end", $end);
        $start = local_time_to_gmt($start);
        $end = local_time_to_gmt($end);


        $where = "time  between  '$start'  and  '$end' and release_cause = 49";
//        $where = "time  between  '$start'  and  '$end'";

        if ($this->_get('ingress_alias')){
            $where .= " AND ingress_id = " .$this->_get('ingress_alias');
        }

        extract($this->capture_report_join('', ''));
        $order = $this->capture_report_order();

        $release_cause = ' release_cause ';

        $binary_value_of_release_cause_from_protocol_stack = "case when is_final_call = 0 then '-' else binary_value_of_release_cause_from_protocol_stack end as binary_value_of_release_cause_from_protocol_stack";


        $route_id = "(select route_id from route where route_strategy_id = client_cdr.route_plan and
            (static_route_id = client_cdr.static_route or (static_route_id is null and client_cdr.static_route is null)) and
            (dynamic_route_id = client_cdr.dynamic_route or (dynamic_route_id is null and client_cdr.dynamic_route is null))
            limit 1) as route_id";
        //default
        $show_field = "tax,call_duration,origination_destination_host_name,trunk_id_termination,trunk_id_origination,origination_destination_number,pdd,origination_source_number,$release_cause,release_cause_from_protocol_stack,$binary_value_of_release_cause_from_protocol_stack,time,orig_call_duration,is_final_call";

        $show_field_array = array('tax', 'call_duration', 'trunk_id_termination', 'trunk_id_origination', 'origination_destination_number', 'pdd', 'origination_source_number', 'release_cause', 'release_cause_from_protocol_stack', 'orig_call_duration', 'binary_value_of_release_cause_from_protocol_stack', 'time', 'origination_destination_host_name');

        //cdr 显示字段
        if (isset($_GET ['query'] ['fields']))
        {
            $show_field = '';
            $show_field_array = $_GET ['query'] ['fields'];
            $sql_field_array = $show_field_array;
            //array_push($sql_field_array, 'id');
            $sql_field_array = $this->sql_field_array_help($sql_field_array);
            if (!empty($sql_field_array))
            {
                $show_field = join(',', $sql_field_array);
            }
        }

        $this->set('show_field_array', $show_field_array);

        //生成分表查询语句

        $time_data = $this->_get_date_result_admin($start, $end, 'client_cdr2%');
        $count_sql = "";
        $org_sql = "";
        $show_field = "id," . $show_field;

        if (count($time_data) < 4)
        {
            foreach($time_data as $key=>$value){
                $table_name = "client_cdr".$value;

                $union = "";
                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $count_sql .= "  select count(*) as c from   {$table_name} $join    where  $where  ";

                $org_sql .= " {$union}  select $show_field  from   {$table_name}  $join     where   $where ";

                $org_sql = str_replace('client_cdr.', $table_name.".", $org_sql);
                $org_sql = str_replace(',tax', ",".$table_name.".tax", $org_sql);
                $count_sql = str_replace('client_cdr.', $table_name.".", $count_sql);

            }
            $count_sql = " select sum(c) as c from ( $count_sql  ) as res ";
        }
        else
        {
            $count_sql = "  select count(*) as c from   client_cdr $join    where  $where  ";
            $org_sql = "select $show_field  from client_cdr  $join     where   $where";
        }

        $org_sql .= " $order ";
        return compact('org_sql', 'count_sql', 'where', 'show_field', 'show_field_array');
    }


    function sql_field_array_help($arr)
    {
        $route_id = "(select route_id from route where route_strategy_id = client_cdr.route_plan and
            (static_route_id = client_cdr.static_route or (static_route_id is null and client_cdr.static_route is null)) and
            (dynamic_route_id = client_cdr.dynamic_route or (dynamic_route_id is null and client_cdr.dynamic_route is null))
            limit 1) as route_id";
        $release_cause = " release_cause ";
        $t_arr = array();
        foreach ($arr as $key => $value)
        {
            $t_arr[$key] = $value;
            if ($value == 'start_time_of_date')
            {
                $t_arr[$key] = "to_timestamp(start_time_of_date/1000000) as start_time_of_date";
            }
            if ($value == 'answer_time_of_date')
            {
                $t_arr[$key] = "case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end as answer_time_of_date";
            }

            if ($value == 'trunk_type')
            {
                $t_arr[$key] = "case trunk_type when 1 then 'class4' when 2 then 'exchange' end as trunk_type";
            }

            if ($value == 'release_tod')
            {
                $t_arr[$key] = "to_timestamp(release_tod/1000000) as release_tod";
            }

            if ($value == 'binary_value_of_release_cause_from_protocol_stack')
            {
                $t_arr[$key] = "case when is_final_call = 0 then '-' else binary_value_of_release_cause_from_protocol_stack end as binary_value_of_release_cause_from_protocol_stack";
            }

            if ($value == "egress_id")
            {
                $t_arr[$key] = "(select alias from resource where resource_id = client_cdr.egress_id and egress = true limit 1) as egress_id";
            }

            if ($value == "ingress_id")
            {
                $t_arr[$key] = "(select alias from resource where resource_id = client_cdr.ingress_id and ingress = true limit 1) as ingress_id";
            }

            if ($value == "egress_rate_table_id")
            {

                $t_arr[$key] = "(select name from rate_table where rate_table_id = client_cdr.egress_rate_table_id and client_cdr.egress_rate_table_id is not null) as  egress_rate_table_id";
            }


            if ($value == "ingress_client_rate_table_id")
            {

                $t_arr[$key] = "(select name from rate_table where rate_table_id = client_cdr.ingress_client_rate_table_id and client_cdr.ingress_client_rate_table_id is not null) as  ingress_client_rate_table_id";
            }

            if ($value == "ingress_client_currency_id")
            {
                $t_arr[$key] = "(select code from currency where currency_id = client_cdr.ingress_client_currency_id and client_cdr.ingress_client_currency_id is not null) as ingress_client_currency_id";
            }

            if ($value == "ingress_client_id")
            {
                $t_arr[$key] = "(select name from client where client_id = client_cdr.ingress_client_id and client_cdr.ingress_client_id is not null) as ingress_client_id";
            }

            if ($value == "egress_client_id")
            {
                $t_arr[$key] = "(select name from client where client_id = client_cdr.egress_client_id and client_cdr.egress_client_id is not null) as egress_client_id";
            }

            if ($value == "route_plan")
            {
                $t_arr[$key] = "(select name from route_strategy where route_strategy_id =  client_cdr.route_plan) as route_plan";
            }
            if ($value == "dynamic_route")
            {
                $t_arr[$key] = "(select name from dynamic_route where dynamic_route_id =  client_cdr.dynamic_route) as dynamic_route";
            }
            if ($value == "static_route")
            {
                $t_arr[$key] = "(select name from product where product_id = client_cdr.static_route) as static_route";
            }

            if ($value == "ingress_dnis_type")
            {
                $t_arr[$key] = "case ingress_dnis_type when '0' then 'dnis' when '1' then 'lrn' when '2' then 'lrn block' end as ingress_dnis_type";
            }
            if ($value == 'lrn_number_vendor')
            {
                $t_arr[$key] = "case lrn_number_vendor when 1 then 'client' when 2 then 'lrn server' when 3 then 'cache' when 0 then 'dnis' else 'others' end as lrn_number_vendor";
            }
            if ($value == 'release_cause')
            {
                $t_arr[$key] = $release_cause;
            }

//            if ($value == 'commission')
//            {
//                $t_arr[$key] = $commission;
//            }

            if ($value == 'ingress_rate_type')
            {
                $t_arr[$key] = "case ingress_rate_type when 1 then 'inter' when 2 then 'intra' when 4 then 'error' when 5 then 'local' else 'others' end as ingress_rate_type";
            }
            if ($value == 'egress_rate_type')
            {
                $t_arr[$key] = "case egress_rate_type when 1 then 'inter' when 2 then 'intra'  when 4 then 'error' when 5 then 'local' else 'others' end as egress_rate_type";
            }
            if ($value == 'route_id')
            {
                $t_arr[$key] = $route_id;
            }

            if (isset($_GET['currency']) && !empty($_GET['currency']))
            {
                $sql = "SELECT rate FROM currency_updates WHERE currency_id = {$_GET['currency']}";
                $cur_info = $this->Cdr->query($sql);
                $rate = $cur_info[0][0]['rate'];
                if ($value == 'egress_cost')
                {
                    $t_arr[$key] = "round(egress_cost / (SELECT rate FROM currency_updates WHERE currency_id = client_cdr.egress_client_currency_id) * {$rate}, 5) as egress_cost";
                }
                if ($value == 'egress_rate')
                {
                    $t_arr[$key] = "round(egress_rate / (SELECT rate FROM currency_updates WHERE currency_id = client_cdr.egress_client_currency_id) * {$rate}, 5) as egress_rate";
                }
                if ($value == 'ingress_client_cost')
                {
                    $t_arr[$key] = "round(ingress_client_cost / (SELECT rate FROM currency_updates WHERE currency_id = client_cdr.ingress_client_currency_id) * {$rate}, 5) as ingress_client_cost";
                }
                if ($value == 'ingress_client_rate')
                {
                    $t_arr[$key] = "round(ingress_client_rate / (SELECT rate FROM currency_updates WHERE currency_id = client_cdr.ingress_client_currency_id) * {$rate}, 5) as ingress_client_rate";
                }
            }
        }
        return $t_arr;
    }


}
