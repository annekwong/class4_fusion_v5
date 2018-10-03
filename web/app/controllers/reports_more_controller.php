<?php

class ReportsMoreController extends AppController
{

    var $name = "ReportsMore";
    var $uses = array('Cdrs', 'Cdr','CdrsRead','CdrRead');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect('capacity');
    }

    public function capacity($type = 0)
    {
        $this->pageTitle = __('No Capacity Report',true);

        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        if ($this->_get('start_date'))
            $start_date = $this->_get('start_date') . ' ' . $this->_get('start_time');
        if ($this->_get('stop_date'))
            $end_date = $this->_get('stop_date') . ' ' . $this->_get('stop_time');
        if ($this->_get('query.tz'))
            $gmt = $this->_get('query.tz');
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (strtotime($start_date) > strtotime($end_date))
            $start_date = $end_date;

        $replace_fields = array(
            'group_time' => 'Group Time',
        );
        $show_fields = array();
        $out_field_arr = array();
        if ($type)
        {
            $cdr_field_arr = array(
                'egress_client_id','egress_id','release_cause'
            );
            $report_field_arr = array(
                'egress_id as trunk_id',
                '(select client_id from resource where resource_id = egress_id) as client_id',
                'sum(total_record) as total_call',
                'sum(carrier_call_limit) as carrier_call_limit',
                'sum(carrier_cps_limit) as carrier_cps_limit',
                'sum(trunk_call_limit) as trunk_call_limit',
                'sum(trunk_cps_limit) as trunk_cps_limit',
            );
            $group_fields = array(
                'egress_id'
            );
        }
        else
        {
            $cdr_field_arr = array(
                'ingress_client_id','ingress_id','release_cause'
            );
            $report_field_arr = array(
                'ingress_client_id','ingress_id','release_cause','ingress_total_calls'
            );
            $group_fields = array(
                'ingress_client_id','ingress_id'
            );
            $out_field_arr = array(
                'count(*) as total_call',
                'ingress_client_id as client_id',
                'ingress_id as trunk_id',
                'sum(case release_cause when 29 then 1 else 0 end) as carrier_call_limit',
                'sum(case release_cause when 30 then 1 else 0 end) as carrier_cps_limit',
                'sum(case release_cause when 6 then 1 when 8 then 1 when 50 then 1 else 0 end) as trunk_call_limit',
                'sum(case release_cause when 7 then 1 when 9 then 1 when 51 then 1 else 0 end) as trunk_cps_limit',
            );
            $report_out_field_arr = array(
                'sum(ingress_total_calls) as total_call',
                'ingress_client_id as client_id',
                'ingress_id as trunk_id',
                'sum(case release_cause when 29 then 1 else 0 end) as carrier_call_limit',
                'sum(case release_cause when 30 then 1 else 0 end) as carrier_cps_limit',
                'sum(case release_cause when 6 then 1 when 8 then 1 when 50 then 1 else 0 end) as trunk_call_limit',
                'sum(case release_cause when 7 then 1 when 9 then 1 when 51 then 1 else 0 end) as trunk_cps_limit',
            );
        }
        if($this->_get('group_by_date'))
        {
            array_unshift($out_field_arr, "group_time");
            array_unshift($report_out_field_arr, "group_time");
            array_unshift($cdr_field_arr, "to_char(time, '{$this->_get('group_by_date')}') as group_time");
            array_unshift($report_field_arr, "to_char(report_time, '{$this->_get('group_by_date')}') as group_time");
            $group_fields['group_time'] = "group_time";
            $show_fields['group_time'] = "group_time";
        }
        $cdr_fields = implode(',',$cdr_field_arr);
        $report_fields = implode(',',$report_field_arr);
        $out_fields = implode(',',$out_field_arr);
        $report_out_fields = implode(',',!empty($report_out_field_arr) ? $report_out_field_arr : []);
        $groups = implode(',',$group_fields);
        $this->set('show_nodata', true);
        if ($type)
        {
            $report_max_time = $this->CdrRead->get_egress_trunk_trace_report_maxtime($start_date, $end_date);

            if (isset($_GET['show_type']) || $is_preload)
            {
                if (empty($report_max_time))
                    $data = array();
                else
                {
                    $sql = $this->CdrsRead->get_egress_trunk_trace_report($start_date,$end_date,$report_fields,$groups);
//                    echo $sql;die;
                    $data = $this->CdrsRead->query($sql);
                }
            }
            else
            {
                $data = array();
                $this->set('show_nodata', false);
            }
        }
        else
        {
            $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);

            $select_time_end = strtotime($end_date);
            $is_from_client_cdr = false;

            if (empty($report_max_time))
            {
                $is_from_client_cdr = true;
                $report_max_time = $start_date;
            }
            $system_max_end = strtotime($report_max_time);
            if (isset($_GET['show_type']) || $is_preload)
            {

                if ($select_time_end > $system_max_end)
                {
                    if ($is_from_client_cdr)
                    {
                        $sql = $this->CdrsRead->get_no_capacity_cdr($start_date,$end_date,$cdr_fields,$out_fields,$groups);
                        $data = $this->CdrsRead->query($sql);
                    }
                    else
                    {
                        $merge_group_arr = $show_fields;
                        array_push($merge_group_arr,'client_id');
                        array_push($merge_group_arr,'trunk_id');
                        $sql1 = $this->CdrsRead->get_no_capacity_report($start_date, $report_max_time,$report_fields,$report_out_fields,$groups);
                        $sql2 = $this->CdrsRead->get_no_capacity_cdr($report_max_time, $end_date,$cdr_fields,$out_fields,$groups);
                        $data = $this->CdrsRead->get_no_capacity_two($sql1, $sql2, $merge_group_arr);
                    }
                }
                else
                {
                    $sql = $this->CdrsRead->get_no_capacity_report($start_date,$end_date,$report_fields,$report_out_fields,$groups);
                    $data = $this->CdrsRead->query($sql);
                }
            }
            else
            {
                $data = array();
                $this->set('show_nodata', false);
            }
        }
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report,all_termination from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $this->set("all_termination", $res[0][0]['all_termination']);
        $this->set('replace_fields',$replace_fields);
        $this->set('client_limit',$this->CdrsRead->get_client_call_cps_limit());
        $this->set('trunk_limit',$this->CdrsRead->get_trunk_call_cps_limit());
        $this->set('show_fields',$show_fields);
        $this->set('data', $data);
        $this->set('start_date',$start_date);
        $this->set('end_date',$end_date);
        $this->set('type',$type);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=no_capacity_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('no_capacity_down_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=no_capacity_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('no_capacity_down_xls');
        }


    }

    public function no_route($type = 0)
    {
        $this->pageTitle = __('No Route Report',true);

        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $is_preload = false;
        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        if ($this->_get('start_date'))
            $start_date = $this->_get('start_date') . ' ' . $this->_get('start_time');
        if ($this->_get('stop_date'))
            $end_date = $this->_get('stop_date') . ' ' . $this->_get('stop_time');
        if ($this->_get('query.tz'))
            $gmt = $this->_get('query.tz');
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (strtotime($start_date) > strtotime($end_date))
            $start_date = $end_date;

        $replace_fields = array(
            'group_time' => 'Group Time',
        );
        $show_fields = array();
        $out_field_arr = array();
        if ($type)
        {
            $cdr_field_arr = array(
                'egress_client_id','egress_id','release_cause'
            );
            $report_field_arr = array(
                'egress_id as trunk_id',
                '(select client_id from resource where resource_id = egress_id) as client_id',
                'sum(total_record) as total_call',
                'sum(no_capacity) as no_capacity',
                'sum(no_profitable_route) as no_profitable_route',
                'sum(code_block) as code_block',
                'sum(egress_trunk_block) as egress_trunk_block',
            );
            $group_fields = array(
                'egress_id'
            );
            $report_out_field_arr = array();
        }
        else
        {
            $cdr_field_arr = array(
                'ingress_client_id','ingress_id','release_cause'
            );
            $report_field_arr = array(
                'ingress_client_id','ingress_id','release_cause','ingress_total_calls'
            );
            $group_fields = array(
                'ingress_client_id','ingress_id'
            );
            $out_field_arr = array(
                'count(*) as total_call',
                'ingress_client_id as client_id',
                'ingress_id as trunk_id',
                'sum(case release_cause when 21 then 1 else 0 end) as no_credit',
                'sum(case release_cause when 4 then 1 when 20 then 1 when 22 then 1 when 23 then 1 when 31 then 1 when 32 then 1 else 0 end) as trunk_not_found',
                'sum(case release_cause when 13 then 1 else 0 end) as no_route',
                'sum(case release_cause when 6 then 1 when 7 then 1 when 8 then 1 when 9 then 1 when 29 then 1 when 30 then 1 when 50 then 1 when 51 then 1 else 0 end) as no_capacity',
            );
            $report_out_field_arr = array(
                'sum(ingress_total_calls) as total_call',
                'ingress_client_id as client_id',
                'ingress_id as trunk_id',
                'sum(case release_cause when 21 then 1 else 0 end) as no_credit',
                'sum(case release_cause when 4 then 1 when 20 then 1 when 22 then 1 when 23 then 1 when 31 then 1 when 32 then 1 else 0 end) as trunk_not_found',
                'sum(case release_cause when 13 then 1 else 0 end) as no_route',
                'sum(case release_cause when 6 then 1 when 7 then 1 when 8 then 1 when 9 then 1 when 29 then 1 when 30 then 1 when 50 then 1 when 51 then 1 else 0 end) as no_capacity',
            );
        }
        if($this->_get('group_by_date'))
        {
            array_unshift($out_field_arr, "group_time");
            array_unshift($report_out_field_arr, "group_time");
            array_unshift($cdr_field_arr, "to_char(time, '{$this->_get('group_by_date')}') as group_time");
            array_unshift($report_field_arr, "to_char(report_time, '{$this->_get('group_by_date')}') as group_time");
            $group_fields['group_time'] = "group_time";
            $show_fields['group_time'] = "group_time";
        }
        $cdr_fields = implode(',',$cdr_field_arr);
        $report_fields = implode(',',$report_field_arr);
        $out_fields = implode(',',$out_field_arr);
        $report_out_fields = implode(',',$report_out_field_arr);
        $groups = implode(',',$group_fields);

        $this->set('show_nodata', true);
        if ($type)
        {
            $report_max_time = $this->CdrRead->get_egress_trunk_trace_report_maxtime($start_date, $end_date);

            if (isset($_GET['show_type']) || $is_preload)
            {
                if (empty($report_max_time))
                    $data = array();
                else
                {
                    $sql = $this->CdrsRead->get_egress_trunk_trace_report($start_date,$end_date,$report_fields,$groups);
//                    echo $sql;die;
                    $data = $this->CdrsRead->query($sql);
                }
            }
            else
            {
                $data = array();
                $this->set('show_nodata', false);
            }
        }
        else {
            $report_max_time = $this->CdrRead->get_report_maxtime($start_date, $end_date);
            $select_time_end = strtotime($end_date);
            $is_from_client_cdr = false;
            if (empty($report_max_time)) {
                $is_from_client_cdr = true;
                $report_max_time = $start_date;
            }
            $system_max_end = strtotime($report_max_time);
            if (isset($_GET['show_type']) || $is_preload) {

                if ($select_time_end > $system_max_end) {
                    if ($is_from_client_cdr) {
                        $sql = $this->CdrsRead->get_no_capacity_cdr($start_date, $end_date, $cdr_fields, $out_fields, $groups);
                        $data = $this->CdrsRead->query($sql);
                    } else {
                        $merge_group_arr = $show_fields;
                        array_push($merge_group_arr, 'client_id');
                        array_push($merge_group_arr, 'trunk_id');
                        $sql1 = $this->CdrsRead->get_no_capacity_report($start_date, $report_max_time, $report_fields, $report_out_fields, $groups);
                        $sql2 = $this->CdrsRead->get_no_capacity_cdr($report_max_time, $end_date, $cdr_fields, $out_fields, $groups);
                        $data = $this->CdrsRead->get_no_route_two($sql1, $sql2, $merge_group_arr);
                    }
                } else {
                    $sql = $this->CdrsRead->get_no_capacity_report($start_date, $end_date, $report_fields, $report_out_fields, $groups);
                    $data = $this->CdrsRead->query($sql);
                }
            } else {
                $data = array();
                $this->set('show_nodata', false);
            }
        }
        $user_id = $_SESSION['sst_user_id'];
        $res = $this->Cdr->query("select report_group,outbound_report,all_termination from users where user_id = {$user_id} ");
        $this->set('report_group', $res[0][0]['report_group']);
        $this->set('outbound_report', $res[0][0]['outbound_report']);
        $this->set("all_termination", $res[0][0]['all_termination']);
        $this->set('replace_fields',$replace_fields);
        $this->set('client_info',$this->CdrsRead->findClient(true));
        $this->set('trunk_info',$this->CdrsRead->findAllTrunk(true));
        $this->set('show_fields',$show_fields);
        $this->set('data', $data);
        $this->set('start_date',$start_date);
        $this->set('end_date',$end_date);
        $this->set('type',$type);
        if ($type)
            $this->render('no_route_egress');

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=no_capacity_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            if ($type)
                $this->render('no_route_egress_down_csv');
            else
                $this->render('no_route_down_csv');
        }
        else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=no_capacity_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            if ($type)
                $this->render('no_route_egress_down_xls');
            else
                $this->render('no_route_down_xls');
        }

    }


    public function test(){

    }


}

?>
