<?php

class UsOcnLataController extends AppController
{

    var $name = 'UsOcnLata';
    var $uses = array('UsOcnLata');

    function index()
    {

        $search = isset($this->params['url']['search']) ? $this->params['url']['search'] : "";
        $where = "";
        if ($search)
        {
            $where = "WHERE ocn like '%{$search}%' or lata like '%{$search}%'  or npa like '%{$search}%'  or nxx like '%{$search}%' or a_block like '%{$search}%'";
        }

        $this->set('search', $search);

        $order_sql = "ORDER BY id desc";
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
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

//        $total_sql = "SELECT COUNT(*) as sum FROM us_ocn_lata {$where}";
//        $totalrecords = $this->UsOcnLata->query($total_sql);

//        $page->setTotalRecords($totalrecords[0][0]['sum']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
//        $sql = "SELECT * FROM us_ocn_lata  {$where} {$order_sql}  limit '$pageSize' offset '$offset'";
//        $data_arr = $this->UsOcnLata->query($sql);
        $data_arr = array();
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

    public function delete_item($encode_id)
    {
        $id = base64_decode($encode_id);
        $flg = $this->UsOcnLata->del($id);
        if ($flg === false)
        {
            $this->Session->write('m', $this->UsOcnLata->create_json(101, __('The item is deleted failed!', true)));
        }
        else
        {
            $this->Session->write('m', $this->UsOcnLata->create_json(201, __('The item is deleted successfully!', true)));
        }
        $log_id = $this->UsOcnLata->logging('1', 'US OCN LATA', "US OCN LATA:#{$id}");
        $url_flug = "us_ocn_lata-index";
        $this->modify_log_noty($log_id, $url_flug);
//        $this->xredirect("/logging/index/{$log_id}/us_ocn_lata-index");
    }

    public function item_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            $msg = "created";
            $log_type = 0;
            if ($id != null)
            {
                $msg = "modified";
                $log_type = 2;
                $this->data['UsOcnLata']['id'] = $id;
            }
            $flg = $this->UsOcnLata->save($this->data);
            if ($flg === false)
            {
                $this->Session->write('m', $this->UsOcnLata->create_json(101, __("The item is %s failed!", true, $msg)));
            }
            else
            {
                $this->Session->write('m', $this->UsOcnLata->create_json(201, __("The item is %s successfully!", true, $msg)));
                $log_id = $this->UsOcnLata->logging($log_type, 'US OCN LATA', "US OCN LATA:#{$id}");
                $url_flug = "us_ocn_lata-index";
                $this->modify_log_noty($log_id, $url_flug);
//                $this->xredirect("/logging/index/{$log_id}/us_ocn_lata-index");
            }
            $this->xredirect("index");
        }
        $this->data = $this->UsOcnLata->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }

    public function del_all()
    {
////        $flg = $this->UsOcnLata->query("DELETE FROM us_ocn_lata");
//        if ($flg === false)
//        {
//            $this->Session->write('m', $this->UsOcnLata->create_json(101, __("The all US OCN/LATA delete failed!", true)));
//        }
//        else
//        {
//            $this->Session->write('m', $this->UsOcnLata->create_json(201, __("The all US OCN/LATA delete successfully!", true)));
//        }
        $this->xredirect("index");
    }

    public function del_selected()
    {
        $get_data = $this->params['url'];
        $ids = isset($get_data['ids']) ? $get_data['ids'] : array();
        $id_arr = explode(",", $ids);
        $success_num = 0;
        $error_num = 0;
        foreach ($id_arr as $id)
        {
            $flg = $this->UsOcnLata->del($id);
            if ($flg === false)
            {
                $error_num ++;
            }
            else
            {
                $success_num ++;
            }
        }
        $this->Session->write('m', $this->UsOcnLata->create_json(201, __("The %s item delete successfully && The %s item delete failed!", true, array($success_num, $error_num))));
        $this->xredirect("index");
    }

}

?>
