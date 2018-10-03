<?php

class IpModifyLogController extends AppController
{

    var $name = "IpModifyLog";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('IpModifyLog');

    function index()
    {
        $this->pageTitle = "Management/IP Modify Logs";

        $currPage = 1;
        $sql_where = "WHERE 1 =1 ";
        
        $type_arr = array(
            'all'  => 'All',
            '0' =>  'Modification',
            '1' =>  'Add',
            '2' =>  'Delete',
        );
        
        $this->set('type_arr',$type_arr);
        
        $trunk_list = $this->IpModifyLog->query("SELECT alias,resource_id FROM resource WHERE egress = true or ingress = true order by alias ");
        
        $size = count($trunk_list);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $trunk_list[$i][0]['resource_id'];
            $l[$key] = $trunk_list[$i][0]['alias'];
        }
        $this->set('trunk_list',$l);
               
        $order_sql = "ORDER BY update_at DESC ";
        
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }
        if ($this->isnotEmpty($this->params['url'], array('page')))
        {
            $currPage = $this->params['url']['page'];
        }

        $pageSize = isset($_GET['paging_row']) ? $_GET['paging_row'] : 20;
        
        
        $get_data = $this->params['url'];
        
        $this->set('get_data',$get_data);
        
        if (isset($get_data['time_start']) && $get_data['time_start'])
        {
            $sql_where .= " AND ip_modif_log.update_at >= '" . $get_data['time_start'] . "'";
        }

        if (isset($get_data['time_end']) && $get_data['time_end'])
        {
            $sql_where .= " AND ip_modif_log.update_at <= '" . $get_data['time_end'] . "'";
        }
        
        if (isset($get_data['data']['type']) && $get_data['data']['type'] != 'all')
        {
            $sql_where .= " AND ip_modif_log.modify = " . intval($get_data['data']['type']);
        }
        
        if (isset($get_data['trunk_id']) && $get_data['trunk_id'])
        {
            $sql_where .= " AND ip_modif_log.trunk_id = " . intval($get_data['trunk_id']);
        }
        
        if (isset($get_data['data']['new']) && $get_data['data']['new'])
        {
            $sql_where .= " AND ip_modif_log.new like '%" . $get_data['data']['new']."%'";
        }
        $sql_where .=" AND ip_modif_log.modify IN ('0','1','2') ";
//        pr($get_data);
//        pr($sql_where);
        
        $results = $this->IpModifyLog->ListLog($currPage, $pageSize, $sql_where, $order_sql);
        $this->set('p', $results);
    }

}

?>
