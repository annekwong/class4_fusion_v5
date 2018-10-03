<?php

class ScheduledReportController extends AppController
{

    var $name = 'ScheduledReport';
    var $components = array('RequestHandler');
    var $helpers = array('common');
    var $uses = array('ScheduledReport', 'ScheduledReportLog');

    public function beforeFilter()
    {
//        $this->checkSession("login_type"); //核查用户身份
//        parent::beforeFilter();
    }

    function index()
    {
        $this->pageTitle = "Scheduled Report";

        $get_data = $this->params['url'];
        $data = $this->ScheduledReport->findAll();
        $this->set('data', $data);
//        pr($data);
        $week_arr = array(
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
            7 => 'Sun',
        );
        $this->set('week_arr', $week_arr);
        $frequency_type = array(
            1 => 'Daily',
            2 => 'Weekly',
            3 => 'Monthly',
        );
        $this->set('frequency_type', $frequency_type);
    }

    public function disable($encode_id)
    {
        $id = base64_decode($encode_id);
        $pri_id = intval($id);
        $flg = $this->ScheduledReport->query("UPDATE scheduled_report SET action = false WHERE id = $pri_id");
        if ($flg === false)
        {
            $this->ScheduledReport->create_json_array('', 101, __('failed!', true));
        }
        else
        {
            $this->ScheduledReport->create_json_array('', 201, __('succeed!', true));
        }
        $this->Session->write("m", ScheduledReport::set_validator());
        $this->redirect('index');
    }

    public function eable($encode_id)
    {
        $id = base64_decode($encode_id);
        $pri_id = intval($id);
        $flg = $this->ScheduledReport->query("UPDATE scheduled_report SET action = true WHERE id = $pri_id");
        if ($flg === false)
        {
            $this->ScheduledReport->create_json_array('', 101, __('failed!', true));
        }
        else
        {
            $this->ScheduledReport->create_json_array('', 201, __('succeed!', true));
        }
        $this->Session->write("m", ScheduledReport::set_validator());
        $this->redirect('index');
    }

    public function delete($encode_id)
    {
        $id = base64_decode($encode_id);
        $pri_id = intval($id);
        $flg = $this->ScheduledReport->del($pri_id);
        if ($flg === false)
        {
            $this->ScheduledReport->create_json_array('', 101, __('failed!', true));
        }
        else
        {
            $this->ScheduledReport->create_json_array('', 201, __('succeed!', true));
        }
        $this->Session->write("m", ScheduledReport::set_validator());
        $this->redirect('index');
    }

    public function ajax_option()
    {
        Configure::write('debug', 0);
        $report_name = $this->params['form']['report_name'];
        $this->set('report_name', $report_name);
    }

    public function scheduled_report_log()
    {
        $this->pageTitle = "Log/Scheduled Report Log";
        $start_date = isset($_GET['time_start']) ? $_GET['time_start'] : date("Y-m-d 00:00:00", strtotime("-7 days"));
        $end_date = isset($_GET['time_end']) ? $_GET['time_end'] : date("Y-m-d 23:59:59");

        $tz = isset($_GET['gmt']) ? $_GET['gmt'] : "+0000";
        $get_data = $this->params['url'];
        $get_data['time_start'] = $start_date;
        $get_data['time_end'] = $end_date;
        $this->set('get_data', $get_data);

        $start_datetime = $start_date . $tz;
        $end_datetime = $end_date . $tz;

        $order_arr = array('ScheduledReportLog.execute_time' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $this->paginate = array(
            'limit' => 100,
            'order' => $order_arr,
            'conditions' => array(
                "ScheduledReportLog.execute_time BETWEEN '{$start_datetime}' AND '{$end_datetime}'",
            ),
        );

        if (isset($_GET['search']) && !empty($_GET['search']))
        {
            $_GET['search'] = trim($_GET['search']);
            array_push($this->paginate['conditions'], "(ScheduledReportLog.report_name ilike '%{$_GET['search']}%' or ScheduledReportLog.email_to like '%{$_GET['search']}%')");
        }

        $this->data = $this->paginate('ScheduledReportLog');
    }

    public function download_file($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($encode_id);
        $log_data = $this->ScheduledReportLog->findById($id);
        $file = $log_data['ScheduledReportLog']['attachment_path'];
        if (file_exists($file))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
        else
        {
            $this->ScheduledReportLog->create_json_array('', 101, __('The file is not exsit!', true));
            $this->Session->write("m", ScheduledReportLog::set_validator());
            $this->redirect("scheduled_report_log");
        }
    }

}

?>
