<?php

class RealcdrreportsController extends AppController
{

    var $name = 'Realcdrreports';
    var $uses = array('Cdr');
    var $helpers = array('javascript', 'html');

    //查询封装
    function index()
    {
        $this->redirect('summary_reports');
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else
        {
            $limit = $this->Session->read('sst_retail_rcardpools');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    function js_save()
    {
//        Configure::write('debug', 0);
//        $this->layout = "ajax";
////$this->_session_get(isset ( $_GET ['searchkey'] ));
//        $this->pageTitle = "Statistics/Active Call Report ";
//        $t = getMicrotime();
//        //权限条件
//        $privilege = '';
//        if ($_SESSION['login_type'] == 3)
//        {
//            $privilege = !empty($_SESSION['sst_client_id']) ? " and (  client_id  = '{$_SESSION['sst_client_id']}'  )" : '';
//        }
//
//        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
//        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
//        $this->init_query();
//
//
//        //单个的where查询条件
//        $orig_client_where = '';
//        $term_client_where = '';
//        $code_name_where = '';
//        $code_where = '';
//        $code_deck_where = '';
//        $server_where = '';
//        $currency_where = '';
//        $egress_where = '';
//        $ingress_where = '';
//        $interval_from_where = '';
//        $interval_to_where = '';
//        $discon_where = '';
//        $dst_number_where = '';
//        $duration_where = "";
//        $disconnect_cause_where = '';
//        $cost_where = '';
//        $src_number_where = '';
//        $call_id_where = '';
//
//
//
//
//        //$show_field ='ani,dnis,caller_ip_address,ingress_codec,egress_codec,server_ip';
//        $show_field = 'to_timestamp(substring(ans_time_a from 1 for 10) ::bigint) as ans_time_a, to_timestamp(substring(ans_time_b from 1 for 10) ::bigint) as ans_time_b, ani, dnis, EXTRACT(EPOCH from current_timestamp(0))-ans_time_b::bigint/1000000 as duration,(select alias from resource where resource_id::varchar = real_cdr.egress_id and egress = true limit 1) as egress_id, (select name from client where client_id::text = real_cdr.client_id) as client_id, (select alias from resource where resource_id::varchar = real_cdr.ingress_id  and ingress = true limit 1) as ingress_id, caller_ip_address, callee_ip_address, ingress_codec, egress_codec, a_rate, b_rate';
////        $show_field_array = array('EXTRACT(EPOCH from current_timestamp(0))-ans_time_b::bigint/1000000 as duration', 'ans_time_b', 'ani', 'dnis', '(select alias from resource where resource_id::varchar = real_cdr.egress_id and egress = true limit 1) as egress_id', '(select name from client where client_id::text = real_cdr.client_id) as client_id', '(select alias from resource where resource_id::varchar = real_cdr.ingress_id  and ingress = true limit 1) as ingress_id', 'caller_ip_address', 'callee_ip_address', 'ingress_codec', 'egress_codec', 'a_rate', 'b_rate');
//        //$show_field_array=array('ani','dnis','caller_ip_address','ingress_codec','egress_codec','server_ip');
////        $order_by = "order by   real_cdr_id desc";
//        if (isset($_GET ['searchkey']))
//        {
//
//
//
//            //********************************************************************************************************
//            //            普通单个条件查询(按照代理商,帐号卡)
//            //********************************************************************************************************
//
//            pr($_GET ['query'] ['id_clients']);
//
//            $term_client_where = !empty($_GET ['query'] ['id_clients_term']) ? "and client_id='{$_GET ['query'] ['id_clients_term']}'" : '';
//            $orig_client_where = !empty($_GET ['query'] ['id_clients']) ? "and   ingress_id  in (select  resource_id::text from resource  where  ingress=true  and   client_id={$_GET ['query'] ['id_clients']})" : '';
//
//            pr($orig_client_where);
//            if (isset($_GET ['query'] ['code_name_term']))
//            {
//                $code_name = $_GET ['query'] ['code_name_term'];
//                if (!empty($code_name))
//                {
//                    $code_name_where = "and code.name='$code_name'";
//                }
//            }
//
//            if (isset($_GET ['query'] ['code_term']))
//            {
//                $code = $_GET ['query'] ['code_term'];
//                if (!empty($code))
//                {
//                    $code_where = "and code.code='$code'";
//                }
//            }
//
//
//
//
//
//
//
//
//
//
//
//
//
//            //被叫号
//            if (isset($_GET ['query'] ['dst_number']))
//            {
//                $dst_number = $_GET ['query'] ['dst_number'];
//                if (!empty($dst_number))
//                {
//                    $dst_number_where = "and dnis::prefix_range<@'$dst_number'";
//                    $this->set("dst_number", $_GET ['query'] ['dst_number']);
//                }
//            }
//
//
//            //主叫号
//            if (isset($_GET ['query'] ['src_number']))
//            {
//                $src_number = $_GET ['query'] ['src_number'];
//                if (!empty($src_number))
//                {
//                    $src_number_where = "and ani::prefix_range<@'$src_number'";
//                    $this->set("src_number", $_GET ['query'] ['src_number']);
//                }
//            }
//
//
//
//
//            $server_ip = $_GET ['server_ip'];
//            if (!empty($server_ip))
//            {
//                $server_where = "and server_ip='$server_ip'";
//                $this->set("server_ip", $_GET ['server_ip']);
//            }
//
//
//            $egress_alias = $_GET ['egress_alias'];
//
//            if (!empty($egress_alias))
//            {
//                $egress_where = "  and egress_id='$egress_alias'";
//                $this->set("egress_post", $_GET ['egress_alias']);
//            }
//            $ingress_alias = $_GET ['ingress_alias'];
//            if (!empty($ingress_alias))
//            {
//                $ingress_where = "  and ingress_id='$ingress_alias'";
//                $this->set("egress_post", $_GET ['egress_alias']);
//            }
//
//
//
//
//
//
//            //cdr 显示字段
//            if (isset($_GET ['query'] ['fields']))
//            {
//
//                $show_field = '';
//                $show_field_array = $_GET ['query'] ['fields'];
//                //$sql_field_array=$show_field_array;
//                $sql_field_array = $this->sql_field_array_help($sql_field_array);
//                if (!empty($sql_field_array))
//                {
//                    $show_field = join(',', $sql_field_array);
//                }
//            } else
//            {
//                //$show_field ='time,origination_source_number,origination_destination_number,origination_source_host_name,ingress_client_cost';
//                //$show_field ='to_timestamp(substring(ans_time_a from 1 for 10) ::bigint) as ans_time_a, to_timestamp(substring(ans_time_b from 1 for 10) ::bigint) as ans_time_b, ani, dnis, egress_id, client_id, ingress_id, caller_ip_address, callee_ip_address, ingress_codec, egress_codec, a_rate, b_rate';
//                $show_field_array = array('EXTRACT(EPOCH from current_timestamp(0))-ans_time_b::bigint/1000000 as duration', 'ans_time_b', 'ani', 'dnis', '(select alias from resource where resource_id::varchar = real_cdr.egress_id and egress = true limit 1) as egress_id', '(select name from client where client_id::text = real_cdr.client_id) as client_id', '(select alias from resource where resource_id::varchar = real_cdr.ingress_id  and ingress = true limit 1) as ingress_id', 'caller_ip_address', 'callee_ip_address', 'ingress_codec', 'egress_codec', 'a_rate', 'b_rate');
//                $sql_field_array = $this->sql_field_array_help($sql_field_array);
//                if (!empty($sql_field_array))
//                {
//                    $show_field = join(',', $sql_field_array);
//                }
//            }
//        }
//
//
//        //查看client的cdr
//        if (isset($this->params['pass'][0]))
//        {
//            if ($this->params['pass'][0] == 'egress')
//            {
//                if (!empty($this->params['pass'][1]))
//                {
//                    $egress_where = "  and egress_id='{$this->params['pass'][1]}'";
//                }
//            }
//
//            if ($this->params['pass'][0] == 'ingress')
//            {
//                if (!empty($this->params['pass'][1]))
//                {
//                    $ingress_where = "  and ingress_id='{$this->params['pass'][1]}'";
//                }
//            }
//
//
////查看断开码对应的cdr
//            if ($this->params['pass'][0] == 'disconnect')
//            {
//                if (!empty($this->params['pass'][1]))
//                {
//                    $discon_where = "and release_cause_from_protocol_stack='{$this->params['pass'][1]}'";  //断开码条件
//                }
//            }
//        }
//
//
//
//
//
//        $this->set('post', $this->data);
//        $this->set('show_field_array', $show_field_array);
//
//
//
//
//        //********************************************************************************************************
//        //                                                                  基本sql
//        //********************************************************************************************************
//
//        $where = "$interval_from_where	$interval_to_where  $orig_client_where
//			$term_client_where $code_name_where $code_where   $server_where
//    $currency_where  $egress_where  $ingress_where $dst_number_where
//    $duration_where $disconnect_cause_where $cost_where  $src_number_where $discon_where
//    $call_id_where   $privilege
//			";
//        require_once 'MyPage.php';
//        $page = new MyPage ();
////        $totalrecords = $this->Cdr->query("select count(*) as c from   real_cdr
////		LEFT JOIN code code ON real_cdr.dnis_code_id = code.code_id
////    where  1=1 $where
////      ");
////        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
//        $page->setCurrPage($currPage); //当前页
//        $page->setPageSize($pageSize); //页大小
//        //$page = $page->checkRange($page);//检查当前页范围
//
//
//        $currPage = $page->getCurrPage() - 1;
//        $pageSize = $page->getPageSize();
//
////通话费用
////        $sql = "select $show_field,       code.name as term_code_name,code.code as term_code    , code.code_deck_id as term_deck_id  from   real_cdr
////                LEFT JOIN  code ON real_cdr.dnis_code_id = code.code_id
////   where 1=1
////   $where  $order_by  ";
////        $page_where = "limit '$pageSize' offset '$currPage'";
////
////        $org_sql = $sql . $page_where;
//
//
//        if (isset($_GET ['query'] ['output']))
//        {
//            //下载
//            if ($_GET ['query'] ['output'] == 'csv')
//            {
//                Configure::write('debug', 0);
//                $this->_catch_exception_msg(array('RealcdrreportsController', '_reports_download_impl'), array('download_sql' => $sql));
////						 	$this->layout='csv';
////					//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
////						$this->Cdr->export__sql_data('download Cdr',$sql,'cdr');
////							$this->layout='csv';
////						exit();
//            } elseif ($_GET ['query'] ['output'] == 'xls')
//            {
//                Configure::write('debug', 0);
//                $this->_catch_exception_msg(array('RealcdrreportsController', '_reports_download_xls'), array('download_sql' => $sql));
//            } elseif ($_GET ['query'] ['output'] == 'delayed')
//            {
//
//            } else
//            {
//                //web显示
//                $results = $this->Cdr->query($org_sql);
//                $page->setDataArray($results);
//                $this->set('p', $page);
//            }
//        } else
//        {
//            $results = $this->Cdr->query($org_sql);
//            $page->setDataArray($results);
//            $this->set('p', $page);
//        }
//
//
//        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
    }

    //初始化查询参数
    function init_query()
    {

        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('code_deck', $this->Cdr->find_code_deck());
        //$this->set ( 'currency', $this->Cdr->find_currency () );
        $this->set('currency', $this->Cdr->find_currency1());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_id());

        $this->set('egress', $this->Cdr->findAll_egress_id());


        $this->set('cdr_field', $this->Cdr->find_realcdrfield());
    }

    function summary_reports()
    {
        //	$this->_session_get(isset ( $_GET ['searchkey'] ));
        $this->pageTitle = "Statistics/Active Call Report ";
        $t = getMicrotime();
        //权限条件
        $privilege = '';
        if ($_SESSION['login_type'] == 3)
        {
            $privilege = !empty($_SESSION['sst_client_id']) ? " and (  client_id  = '{$_SESSION['sst_client_id']}'  )" : '';
        }

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $this->init_query();


        //单个的where查询条件
        $orig_client_where = '';
        $term_client_where = '';
        $code_name_where = '';
        $code_where = '';
        $code_deck_where = '';
        $server_where = '';
        $currency_where = '';
        $egress_where = '';
        $ingress_where = '';
        $interval_from_where = '';
        $interval_to_where = '';
        $discon_where = '';
        $dst_number_where = '';
        $duration_where = "";
        $disconnect_cause_where = '';
        $cost_where = '';
        $src_number_where = '';
        $call_id_where = '';
        $ingress_host_where = '';
        $egress_host_where = '';
        $client_id_query_where = '';


        //$show_field ='ani,dnis,caller_ip_address,ingress_codec,egress_codec,server_ip';
        //$show_field_array=array('ani','dnis','caller_ip_address','ingress_codec','egress_codec','server_ip');
        $show_field = 'to_timestamp(substring(ans_time_a from 1 for 10) ::bigint) as ans_time_a, server_ip,caller_media_ip,callee_media_ip,to_timestamp(substring(ans_time_b from 1 for 10) ::bigint) as ans_time_b, ani, dnis, EXTRACT(EPOCH from current_timestamp(0))-ans_time_b::bigint/1000000 as duration,(select alias from resource where resource_id::varchar = real_cdr.egress_id and egress = true limit 1) as egress_id, (select name from client where client_id::text = real_cdr.client_id) as client_id, (select alias from resource where resource_id::varchar = real_cdr.ingress_id  and ingress = true limit 1) as ingress_id, caller_ip_address, callee_ip_address, ingress_codec, egress_codec, a_rate, b_rate';
        $show_field_array = array('EXTRACT(EPOCH from current_timestamp(0))-ans_time_b::bigint/1000000 as duration', 'ans_time_b', 'ani', 'dnis', '(select alias from resource where resource_id::varchar = real_cdr.egress_id and egress = true limit 1) as egress_id', '(select name from client where client_id::text = real_cdr.client_id) as client_id', '(select alias from resource where resource_id::varchar = real_cdr.ingress_id  and ingress = true limit 1) as ingress_id', 'caller_ip_address', 'callee_ip_address', 'ingress_codec', 'egress_codec', 'a_rate', 'b_rate', 'server_ip', 'caller_media_ip', 'callee_media_ip');

        $order_by = "order by   real_cdr_id desc";
        if (isset($_GET ['searchkey']))
        {



            //********************************************************************************************************
            //            普通单个条件查询(按照代理商,帐号卡)
            //********************************************************************************************************


            $term_client_where = !empty($_GET ['query'] ['id_clients_term']) ? "and client_id='{$_GET ['query'] ['id_clients_term']}'" : '';
            $orig_client_where = !empty($_GET ['query'] ['id_clients']) ? "and   ingress_id  in (select  resource_id::text from resource  where  ingress=true  and   client_id={$_GET ['query'] ['id_clients']})" : '';



            if (isset($_GET ['query'] ['code_name_term']))
            {
                $code_name = $_GET ['query'] ['code_name_term'];
                if (!empty($code_name))
                {
                    $code_name_where = "and code.name='$code_name'";
                }
            }

            if (isset($_GET ['orig_carrier_select']))
            {
                $client_id_query = $_GET ['orig_carrier_select'];
                if (!empty($client_id_query))
                {
                    $client_id_query_where = "and client_id='$client_id_query'";
                }
            }

            if (isset($_GET['query']['ingress_host']))
            {
                $ingress_host = $_GET['query']['ingress_host'];
                if (!empty($ingress_host))
                {
                    $ingress_host_where = " and caller_ip_address = '{$ingress_host}'";
                }
            }

            if (isset($_GET['query']['egress_host']))
            {
                $egress_host = $_GET['query']['egress_host'];
                if (!empty($ingress_host))
                {
                    $egress_host_where = " and callee_ip_address = '{$egress_host}'";
                }
            }

            if (isset($_GET ['query'] ['code_term']))
            {
                $code = $_GET ['query'] ['code_term'];
                if (!empty($code))
                {
                    $code_where = "and code.code='$code'";
                }
            }



            //被叫号
            if (isset($_GET ['query'] ['dst_number']))
            {
                $dst_number = $_GET ['query'] ['dst_number'];
                if (!empty($dst_number))
                {
                    $dst_number_where = "and dnis::prefix_range<@'$dst_number'";
                    $this->set("dst_number", $_GET ['query'] ['dst_number']);
                }
            }


            //主叫号
            if (isset($_GET ['query'] ['src_number']))
            {
                $src_number = $_GET ['query'] ['src_number'];
                if (!empty($src_number))
                {
                    $src_number_where = "and ani::prefix_range<@'$src_number'";
                    $this->set("src_number", $_GET ['query'] ['src_number']);
                }
            }




            $server_ip = isset($_GET ['server_ip']) ? $_GET ['server_ip'] : '';
            if (!empty($server_ip))
            {
                $server_where = "and server_ip='$server_ip'";
                $this->set("server_ip", $_GET ['server_ip']);
            }


            $egress_alias = isset($_GET ['egress_alias']) ? $_GET ['egress_alias'] : '';

            if (!empty($egress_alias))
            {
                $egress_where = "  and egress_id='$egress_alias'";
                $this->set("egress_post", $_GET ['egress_alias']);
            }
            $ingress_alias = isset($_GET ['ingress_alias']) ? $_GET ['ingress_alias'] : '';
            if (!empty($ingress_alias))
            {
                $ingress_where = "  and ingress_id='$ingress_alias'";
                $this->set("egress_post", $_GET ['egress_alias']);
            }






            //cdr 显示字段
            if (isset($_GET ['query'] ['fields']))
            {

                $show_field = '';
                $show_field_array = $_GET ['query'] ['fields'];
                $temp = $this->Cdr->find_realcdrfield();

                $sql_field_array = $show_field_array;
                $sql_field_array = $this->sql_field_array_help($sql_field_array);
                if (!empty($sql_field_array))
                {
                    $show_field = join(',', $sql_field_array);
                }
            } else
            {
                //$show_field ='time,origination_source_number,origination_destination_number,origination_source_host_name,ingress_client_cost';
                $show_field = 'to_timestamp(substring(ans_time_a from 1 for 10) ::bigint) as ans_time_a, to_timestamp(substring(ans_time_b from 1 for 10) ::bigint) as ans_time_b, ani, dnis, egress_id, client_id, ingress_id, caller_ip_address, callee_ip_address, ingress_codec, egress_codec, a_rate, b_rate';
            }
        }

        //查看client的cdr
        if (isset($this->params['pass'][0]))
        {
            if ($this->params['pass'][0] == 'egress')
            {
                if (!empty($this->params['pass'][1]))
                {
                    $egress_where = "  and egress_id='{$this->params['pass'][1]}'";
                }
            }

            if ($this->params['pass'][0] == 'ingress')
            {
                if (!empty($this->params['pass'][1]))
                {
                    $ingress_where = "  and ingress_id='{$this->params['pass'][1]}'";
                }
            }


//查看断开码对应的cdr
            if ($this->params['pass'][0] == 'disconnect')
            {
                if (!empty($this->params['pass'][1]))
                {
                    $discon_where = "and release_cause_from_protocol_stack='{$this->params['pass'][1]}'";  //断开码条件
                }
            }
        }





        $this->set('post', $this->data);
        $this->set('show_field_array', $show_field_array);




        //********************************************************************************************************
        //                                                                  基本sql
        //********************************************************************************************************

        $where = "$interval_from_where	$interval_to_where  $orig_client_where
			$term_client_where $code_name_where $code_where   $server_where
    $currency_where  $egress_where  $ingress_where $dst_number_where
    $duration_where $disconnect_cause_where $cost_where  $src_number_where $discon_where
    $call_id_where $ingress_host_where $egress_host_where $client_id_query_where   $privilege 
			";
        /*
          require_once 'MyPage.php';
          $page = new MyPage ();
          $totalrecords = $this->Cdr->query ( "select count(*) as c from   real_cdr
          LEFT JOIN code code ON real_cdr.dnis_code_id = code.code_id
          where  1=1 $where
          " );
          $page->setTotalRecords ( $totalrecords [0] [0] ['c'] ); //总记录数
          $page->setCurrPage ( $currPage ); //当前页
          $page->setPageSize ( $pageSize ); //页大小


          //$page = $page->checkRange($page);//检查当前页范围


          $currPage = $page->getCurrPage () - 1;
          $pageSize = $page->getPageSize ();
         */
//通话费用
        $sql = "select $show_field,	  code.name as term_code_name,code.code as term_code,uuid_a  from   real_cdr  
		LEFT JOIN  code ON real_cdr.dnis_code_id = code.code_id
   where 1=1
   $where  $order_by";



        //$page_where="limit '$pageSize' offset '$currPage'"; 
        $page_where = '';

        $org_sql = $sql . $page_where;
        $this->set('show_nodata', true);

        if (isset($_GET ['query'] ['output']))
        {
            //下载
            if ($_GET ['query'] ['output'] == 'csv')
            {
                $sql = "select $show_field,	  code.name as term_code_name,code.code as term_code from   real_cdr  
		LEFT JOIN  code ON real_cdr.dnis_code_id = code.code_id
   where 1=1
   $where  $order_by  ";
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('RealcdrreportsController', '_reports_download_impl'), array('download_sql' => $sql));
//						 	$this->layout='csv';
//					//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
//						$this->Cdr->export__sql_data('download Cdr',$sql,'cdr');
//							$this->layout='csv';
//						exit();
            } elseif ($_GET ['query'] ['output'] == 'xls')
            {
                $sql = "select $show_field,	  code.name as term_code_name,code.code as term_code from   real_cdr  
		LEFT JOIN  code ON real_cdr.dnis_code_id = code.code_id
   where 1=1
   $where  $order_by  ";
                Configure::write('debug', 0);
                $this->_catch_exception_msg(array('RealcdrreportsController', '_reports_download_xls'), array('download_sql' => $sql));
            } elseif ($_GET ['query'] ['output'] == 'delayed')
            {
                
            } else
            {
                
                //web显示
                $results = $this->Cdr->query($org_sql);
                
                //$page->setDataArray($results);
                //$this->set('p',$page);
                $this->set('data', $results);
            }
        } else
        {
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];

            if($is_preload)
            {
                $results = $this->Cdr->query($org_sql);
            }
            else
            {
                $this->set('show_nodata', false);
                $results = array();
            }
            $this->set('data', $results);
            //$page->setDataArray($results);
            //$this->set('p',$page);
        }

        // print_r($results);	

        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
    }

    function _reports_download_impl($params = array())
    {
        extract($params);
        if ($this->Cdr->download_by_sql($download_sql, array('objectives' => 'real_cdr')))
        {
            exit(1);
        }
    }

    function _reports_download_xls($params = array())
    {
        extract($params);
        if ($this->Cdr->download_xls_by_sql($download_sql, array('objectives' => 'real_cdr')))
        {
            exit(1);
        }
    }

    function sql_field_array_help($arr)
    {
        $t_arr = array();
        foreach ($arr as $key => $value)
        {
            $t_arr[$key] = $value;
            if ($value == 'ans_time_a')
            {
                $t_arr[$key] = "to_timestamp(substring(ans_time_a from 1 for 10) ::bigint) as ans_time_a";
            }
            if ($value == 'ans_time_b')
            {
                $t_arr[$key] = "to_timestamp(substring(ans_time_b from 1 for 10) ::bigint) as ans_time_b";
            }
            if (isset($_GET['currency']) && !empty($_GET['currency']))
            {
                $sql = "SELECT rate FROM currency_updates WHERE currency_id = {$_GET['currency']}";
                $cur_info = $this->Cdr->query($sql);
                $rate = $cur_info[0][0]['rate'];
                if ($value == 'a_rate')
                {
                    $t_arr[$key] = "round(a_rate::numeric / (SELECT rate FROM currency_updates WHERE currency_id::text = real_cdr.ingress_currency_id) * {$rate}, 5) as a_rate";
                }
                if ($value == 'b_rate')
                {
                    $t_arr[$key] = "round(b_rate::numeric / (SELECT rate FROM currency_updates WHERE currency_id::text = real_cdr.egress_currency_id) * {$rate}, 5) as b_rate";
                }
            }
        }

        return $t_arr;
    }

    /*
     * 通过Socket通信将通话记录kill掉
     * @param string $killid
     * @return back
     */

    function breakone($killid)
    {
        set_time_limit(0);
        //ob_implicit_flush();
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        $sendStr = "kill_channel {$killid}";
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port")))
        {
            //$content = '';
            socket_write($socket, $sendStr, strlen($sendStr));
            $buffer = socket_read($socket, 1024, PHP_NORMAL_READ);
        }
        socket_close($socket);
        $this->Cdr->query("DELETE FROM real_cdr WHERE uuid_a = '{$killid}'");
        $this->xredirect("/realcdrreports/summary_reports");
    }

}