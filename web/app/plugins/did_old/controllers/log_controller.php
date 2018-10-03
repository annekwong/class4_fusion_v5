<?php

class LogController extends AppController
{

    var $name = "Log";
    var $uses = array('did.OrigLog');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function index()
    {
        $this->pageTitle = "Origination Log";

        $get_data = $this->params['url'];
        if (!isset($get_data['search']))
        {
            $get_data = array(
                'detail' => '',
                'operator' => '',
                'action' => '',
            );
            $get_data['end_time'] = date('Y-m-d H:i:s', time());
            $get_data['start_time'] = date('Y-m-d', (time() - 7 * 3600 * 24)) . " 00:00:00";
        }
        $conditions = array();
        if ($get_data['start_time'])
        {
            $conditions['update_on >='] = $get_data['start_time'];
        }
        if ($get_data['end_time'])
        {
            $conditions['update_on <='] = $get_data['end_time'];
        }
        if ($get_data['detail'])
        {
            $conditions['detail LIKE'] = "%{$get_data['detail']}%";
        }
        if ($get_data['operator'])
        {
            $conditions['update_by'] = $get_data['operator'];
        }
        if ($get_data['action'] !== "")
        {
            $conditions['type'] = $get_data['action'];
        }

        $order_sql_arr = array('log_id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql_arr = array(
                    $field => $sort,
                );
            }
        }
        $this->paginate = array(
            'limit' => 20,
            'order' => $order_sql_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('OrigLog');
        $this->set('type', array('Creation', 'Deletion', 'Modification'));
        $this->set('all_operator', $this->OrigLog->find_all_operator());
        $this->set('get_data', $get_data);
    }

}
