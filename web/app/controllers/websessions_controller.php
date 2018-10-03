<?php

class WebsessionsController extends AppController
{

    var $name = 'Websessions';
    var $helpers = array('javascript', 'html');

    function index()
    {
        $this->redirect('view');
    }

    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    public function view()
    {
        $this->pageTitle = "System/User Sign-On History";
        $orderby = '';
        $time = date("Y-m-d H:i:s", strtotime("-7 day"));
        if (isset($_GET['order_by']))
        {
            $order_arr = explode('-', $_GET['order_by']);
            $orderby = "order by $order_arr[0] $order_arr[1]";
        } else
        {
            $orderby = "order by create_time desc";
        }

        $where = '';
        //$this->print_rr($_GET);
        if (isset($_GET['issearch']))
        {
            $start = $_GET['start'];
            $end = $_GET['end'];

            if (!empty($start) && !empty($end))
            {
                $start_time = strtotime($time) > strtotime($start) ? $time : $start;
                $where .= " and web_session.create_time > '$start_time' and web_session.create_time < '$end'";
            } elseif (!empty($start))
            {
                $start_time = strtotime($time) > strtotime($start) ? $time : $start;
                $where .= " and web_session.create_time > '$start_time'";
            } elseif (!empty($end))
            {
                $where .= " and web_session.create_time < '$end'";
            }

            if (empty($start))
            {
                $where .= " and web_session.create_time >= '{$time}' ";
            }
            $user = $_GET['user'];
            if (!empty($user))
            {
                $where .= " and users.name ilike '%{$user}%'";
            }

            $host = $_GET['host'];
            if (!empty($host))
            {
                $where .= " and host = '{$host}'";
            }
        } else
        {
            $where = " and web_session.create_time >= '{$time}' ";
        }

        $this->set('p', $this->Websession->findAll($orderby, $where));
    }

}