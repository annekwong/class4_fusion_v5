<?php

class LrnreportsController extends AppController
{

    var $name = "Lrnreports";
    var $helpers = array('Javascript', 'Html', 'Text');
    var $components = array('RequestHandler');
    var $uses = array('Clientcdr');

    public function index()
    {
//Configure::write('debug', 0);
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $time = '';
//$time_type = isset($_GET['time_type']) ? $_GET['time_type'] : 'day';
        $ingress_trunk = '';
        if (isset($_GET['ingress_trunk']) && !empty($_GET['ingress_trunk']))
        {
            $ingress_trunk = "and ingress_id = {$_GET['ingress_trunk']}";
        }
        $time_group_by = isset($_GET['group_time']) && $_GET['group_time'] == 1 ? 1 : 0;
        /* switch($time_type) {
          case 'day':
          $time = "current_timestamp - interval '1 day' and current_timestamp";
          break;
          case 'week':
          $time = "current_timestamp - interval '1 week' and current_timestamp";
          break;
          case 'month';
          $time = "current_timestamp - interval '1 month' and current_timestamp";
          break;
          } */
        if (isset($_GET['start_date']) && isset($_GET['stop_date']))
        {
            $start_date_time = "{$_GET['start_date']} {$_GET['start_time']} {$_GET['gmt']}";
            $end_date_time = "{$_GET['stop_date']} {$_GET['stop_time']} {$_GET['gmt']}";
            $time = "'{$start_date_time}' and '{$end_date_time}'";
        }
        else
        {
            $date = date('Y-m-d');
            $start_date_time = "{$date} 00:00:00 +0000";
            $end_date_time = "{$date} 23:59:59 +0000";
            $time = "'{$start_date_time}' and '{$end_date_time}'";
        }
        $report_max_time = $this->Clientcdr->get_report_maxtime($time);
        $select_time_end = strtotime($end_date_time);
        $is_from_client_cdr = false;
        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date_time;
        }
        $system_max_end = strtotime($report_max_time);
        $this->set('ingress_trunk', $this->Clientcdr->ingress_trunk());
        $isorder = FALSE;
        if (isset($_GET['order_in']) && $_GET['order_in'] == 1)
        {
            $isorder = TRUE;
        }
        $this->set('show_nodata', true);
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
// 单从client_cdr查询
                    $data = $this->Clientcdr->lrn_report_client_cdr($report_max_time, $end_date_time, $isorder, $ingress_trunk, 100000, 0, $time_group_by);
                }
                else
                {
// 联合查询
                    $data = $this->Clientcdr->lrn_report_two($start_date_time, $report_max_time, $end_date_time, $isorder, $ingress_trunk, 100000, 0, $time_group_by);
                }
            }
            else
            {
// 从预统计表查询
                $data = $this->Clientcdr->lrn_report_prepare($start_date_time, $end_date_time, $isorder, $ingress_trunk, 100000, 0, $time_group_by);
            }
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=lrn_report.csv");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            $this->set('data', $data);
            $this->render('download');
        }
        else
        {
            require_once 'MyPage.php';
            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
// 单从client_cdr查询
                    $counts = $this->Clientcdr->lrn_report_client_cdr_count($report_max_time, $end_date_time, $isorder, $ingress_trunk, $time_group_by);
                }
                else
                {
// 联合查询
                    $counts = $this->Clientcdr->lrn_report_two_count($start_date_time, $report_max_time, $end_date_time, $isorder, $ingress_trunk, $time_group_by);
                }
            }
            else
            {
// 从预统计表查询
                $counts = $this->Clientcdr->lrn_report_prepare_count($start_date_time, $end_date_time, $isorder, $ingress_trunk, $time_group_by);
            }
//$counts = $this->Clientcdr->lrn_report_count($time, $ingress_trunk, $isorder);
            $page = new MyPage ();
            $page->setTotalRecords($counts);
            $page->setCurrPage($currPage);
            $page->setPageSize($pageSize);
            $currPage = $page->getCurrPage() - 1;
            $pageSize = $page->getPageSize();
            $offset = $currPage * $pageSize;
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Clientcdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            if (isset($_GET['show_type']) || $is_preload)
            {
                if ($select_time_end > $system_max_end)
                {
                    if ($is_from_client_cdr)
                    {
// 单从client_cdr查询
                        $data = $this->Clientcdr->lrn_report_client_cdr($report_max_time, $end_date_time, $isorder, $ingress_trunk, $pageSize, $offset, $time_group_by);
                    }
                    else
                    {
// 联合查询
                        $data = $this->Clientcdr->lrn_report_two($start_date_time, $report_max_time, $end_date_time, $isorder, $ingress_trunk, $pageSize, $offset, $time_group_by);
                    }
                }
                else
                {
// 从预统计表查询
                    $data = $this->Clientcdr->lrn_report_prepare($start_date_time, $end_date_time, $isorder, $ingress_trunk, $pageSize, $offset, $time_group_by);
                }
//$data = $this->Clientcdr->lrn_report($pageSize, $offset, $time, $ingress_trunk, $isorder);
            }
            else
            {
                $data = array();
                $this->set('show_nodata', false);
            }
            $page->setDataArray($data);
            $this->set('p', $page);
            $this->set('isorder', $isorder);
        }
    }

}

?>