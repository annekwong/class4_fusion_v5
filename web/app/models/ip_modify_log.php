<?php

class IpModifyLog extends AppModel
{

    var $name = 'IpModifyLog';
    var $useTable = "ip_modif_log";
    var $primaryKey = "id";

    public static function actions(){
        return [
              __('Host Modified',true),
              __('Host Create',true),
              __('Host Delete',true),
              __('Prefix Modified',true),
              __('Prefix Create',true),
              __('CPS Limit Modified',true),
              __('Call Limit Modified',true),
              __('ANI CPS Limit	',true),
              __('ANI CAP Limit',true),
              __('DNIS CPS Limit',true),
              __('DNIS CAP Limit',true),
        ];
    }

    public function get_action_type($type){
        $actions = $this->actions();
        return isset($actions[$type]) ? $actions[$type] : '';
    }

    public function ListLog($currPage = 1, $pageSize = 20, $sql_where = "", $order_by = "ORDER BY modify_at DESC")
    {

        require_once 'MyPage.php';

        $page = new MyPage();

        
        $sql = "SELECT count(*) as sum FROM ip_modif_log " . $sql_where;
        $totalrecords = $this->query($sql);
        //echo $pageSize;
        $_SESSION['paging_row'] = $pageSize;
        $page->setTotalRecords($totalrecords[0][0]['sum']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT egress,alias,ip_modif_log.trunk_id,resource.client_id,client.name,client.email,ip_modif_log.* FROM ip_modif_log left join resource on
                ip_modif_log.trunk_id = resource.resource_id left join client on resource.client_id = client.client_id {$sql_where} {$order_by}";

        $sql .= " limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

}

?>
