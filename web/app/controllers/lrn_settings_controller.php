<?php

class LrnSettingsController extends AppController
{

    var $name = "LrnSettings";
    var $uses = array('LrnSetting', 'LrnItem', 'LrnItemLogs', 'Client');
    var $components = array('RequestHandler');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
    }

    public function _init_data()
    {
        $strategies = array('Topdown', 'Round Robin', 'Minimal PDD');
        $this->set('strategies', $strategies);
    }

    public function index()
    {
        $this->pageTitle = "Configuration/LRN Setting";
        $condition = array('name' => 'asc');

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $condition = array($field => $sort);
            }
        }

        $this->paginate = array(
            'limit' => 100,
            'order' => $condition,
        );

        $this->_init_data();
        $this->data = $this->paginate('LrnSetting');
    }

    public function edit_group_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if(empty($this->data['LrnSetting']['name']))
            {
                $this->Session->write('m', $this->LrnSetting->create_json(101, __('The LRN Group name can not be empty!', true)));
                $this->xredirect("/lrn_settings/index");
            }
            
            if ($this->LrnSetting->exists_name($this->data['LrnSetting']['name'], $id))
            {
                $this->Session->write('m', $this->LrnSetting->create_json(101, __('The LRN Group [%s] is already exists!', true, $this->data['LrnSetting']['name'])));
                $this->xredirect("/lrn_settings/index");
            }
            if ($id != null)
            {
                $this->data['LrnSetting']['id'] = $id;
                $this->Session->write('m', $this->LrnSetting->create_json(201, __('The LRN Group [%s] is modified successfully!', true, $this->data['LrnSetting']['name'])));
            }
            else
                $this->Session->write('m', $this->LrnSetting->create_json(201, __('The LRN Group [%s] is created successfully!', true, $this->data['LrnSetting']['name'])));
            $this->LrnSetting->save($this->data);
            $this->xredirect("/lrn_settings/index");
        }
        $this->_init_data();
        $this->data = $this->LrnSetting->find('first', Array('conditions' => Array('id' => $id)));
    }

    public function delete_group($id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($id);
        $lrn_group = $this->LrnSetting->findById($id);
        if (count($lrn_group['Item']) > 0)
        {
            $this->Session->write('m', $this->LrnSetting->create_json(101, __('The LRN Group [%s] is not empty!', true, $this->data['LrnSetting']['name'])));
        }
        else
        {
            $this->LrnSetting->del($id);
            $this->Session->write('m', $this->LrnSetting->create_json(201, __('The LRN Group [%s] is deleted successfully!', true, $this->data['LrnSetting']['name'])));
        }
        $this->xredirect("/lrn_settings/index");
    }

    public function items($group_id)
    {
        $this->pageTitle = "Configuration/LRN Setting";
        $group_id = base64_decode($group_id);
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'LrnItem.id' => 'asc',
            ),
            'conditions' => array(
                'group_id' => $group_id
            ),
        );


        $lrn_group = $this->LrnSetting->findById($group_id);

        $this->_init_data();
        $this->data = $this->paginate('LrnItem');

        foreach ($this->data as $item_key =>$this_data_item)
        {
            $item_lan_ip = $this_data_item['LrnItem']['ip'];
            $item_lan_port = $this_data_item['LrnItem']['port'];
            $is_connection = $this->LrnItem->connection_test($item_lan_ip,$item_lan_port);
            $this->data[$item_key]['LrnItem']['is_connection'] = $is_connection;
        }
        $this->set('lrn_group', $lrn_group);
    }

    public function edit_item_panel($group_id, $id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            $this->data['LrnItem']['group_id'] = $group_id;
            if ($id != null)
            {
                $this->data['LrnItem']['id'] = $id;
                $this->Session->write('m', $this->LrnItem->create_json(201, __('The LRN IP [%s] is modified successfully!', true, $this->data['LrnSetting']['name'])));
            }
            else
                $this->Session->write('m', $this->LrnItem->create_json(201, __('The LRN IP [%s] is created successfully!', true, $this->data['LrnSetting']['name'])));
            $this->LrnItem->save($this->data);
            $this->xredirect("/lrn_settings/items/" . base64_encode($group_id));
        }
        $this->_init_data();
        $this->data = $this->LrnItem->find('first', Array('conditions' => Array('LrnItem.id' => $id)));
    }

    public function delete_item($id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($id);
        $lrn_item = $this->LrnItem->findById($id);
        $this->LrnItem->del($id);
        $this->Session->write('m', $this->LrnSetting->create_json(201, __('The LRN IP [%s] is deleted successfully!', true, $this->data['LrnSetting']['name'])));
        $this->xredirect("/lrn_settings/items/" . base64_encode($lrn_item['LrnItem']['group_id']));
    }

    public function change_group_status($group_id, $status)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $group_id = base64_decode($group_id);
        $lrn_group = $this->LrnSetting->findById($group_id);
        $lrn_group['LrnSetting']['active'] = (int) $status;
        $this->LrnSetting->save($lrn_group['LrnSetting']);
        if ($status == 1)
        {
            $this->Session->write('m', $this->LrnSetting->create_json(201, __('The LRN Group [%s] is actived successfully!', true, $this->data['LrnSetting']['name'])));
        }
        else
        {
            $this->Session->write('m', $this->LrnSetting->create_json(201, __('The LRN Group [%s] is inactived successfully!', true, $this->data['LrnSetting']['name'])));
        }

        $this->xredirect("/lrn_settings/index");
    }

    public function change_item_status($item_id, $status)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $item_id = base64_decode($item_id);
        $lrn_item = $this->LrnItem->findById($item_id);
        $lrn_item['LrnItem']['active'] = (int) $status;
        $this->LrnItem->save($lrn_item['LrnItem']);
        if ($status == 1)
        {
            $this->Session->write('m', $this->LrnSetting->create_json(201, __('The LRN IP [%s] is actived successfully!', true, $this->data['LrnSetting']['name'])));
        }
        else
        {
            $this->Session->write('m', $this->LrnSetting->create_json(201, __('The LRN IP [%s] is inactived successfully!', true, $this->data['LrnSetting']['name'])));
        }

        $this->xredirect("/lrn_settings/items/" . base64_encode($lrn_item['LrnItem']['group_id']));
    }

    public function item_logs($item_id)
    {
        $item_id =  base64_decode($item_id);
        $this->pageTitle = "Configuration/LRN Setting";
        $where = " item_id = $item_id ";

//        $sql = "select
//                count(*)
//                from lrn_items_logs
//                where {$where}";

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        
//        $count = $this->Client->query($sql);
//        $count = $count[0][0]['count'];
        $count = 0;
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by time desc";

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

//        $sql = "select * from lrn_items_logs
//                where {$where} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";
//
//        $data = $this->Client->query($sql);
        $data = array();
        $page->setDataArray($data);
        $this->set('p', $page);
        
        $lrn_item = $this->LrnItem->findById($item_id);
        $this->set('lrn_item', $lrn_item);
    }
    
    public function item_logsss($item_id)
    {
        $item_id =  base64_decode($item_id);
        $this->pageTitle = "Configuration/LRN Setting";
        $this->paginate = array(
            'limit' => 1000,
            'order' => array(
                'time' => 'desc',
            ),
            'conditions' => array(
                'item_id' => $item_id
            ),
        );
        $lrn_item = $this->LrnItem->findById($item_id);

        $this->set('lrn_item', $lrn_item);
        $this->data = $this->paginate('LrnItemLogs');
    }

}
