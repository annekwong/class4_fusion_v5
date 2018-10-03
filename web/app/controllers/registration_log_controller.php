<?php

class RegistrationLogController extends AppController
{
    var $name = "RegistrationLog";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('RegistrationLog');
    
    public function index()
    {
        $this->pageTitle = "Statistics/Registration Log";
        $order_sql = "ORDER BY time desc";
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
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page')))
        {
            $currPage = $this->params['url']['page'];
        }
        $pageSize = 20;
        $search = isset($this->params['url']['search']) ? $this->params['url']['search'] : "";
        $search_sql = "";
        if($search)
        {
            $search_sql = "WHERE username like '%{$search}%'";
        }
        
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;
        $totalrecords = $this->RegistrationLog->totalrecords($search_sql);

        $page->setTotalRecords($totalrecords); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "SELECT * FROM register_of_record $search_sql $order_sql";
        $sql .= "  limit '$pageSize' offset '$offset'";
        $data_arr = $this->RegistrationLog->query($sql);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

}
