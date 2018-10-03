<?php

class DexsController extends AppController
{

    var $name = 'Dexs';
    var $components = array('RequestHandler');

    function index()
    {
        $this->redirect('view');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    function view()
    {
        $this->pageTitle = "Exchange Manage/Domestic Exchange";

        $currPage = 1;
        $pageSize = 100;
        $search_arr = array();
        $order_arr = array();
        if (!empty($_REQUEST['order_by']))
        {
            $order_by = explode("-", $_REQUEST['order_by']);
            $order_arr[$order_by[0]] = $order_by[1];
        }

        if (!empty($_REQUEST['search']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
        }

        if (!empty($_REQUEST ['page']))
        {
            $currPage = $_REQUEST ['page'];
        }
        $pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 15;

        if (!empty($_REQUEST ['size']))
        {
            $pageSize = $_REQUEST ['size'];
        }

        //echo $this->params['pass'][0];exit();
        $results = $this->Dex->ListDex($currPage, $pageSize, $search_arr, $search_type, $order_arr);
        $this->set('p', $results);
    }

    function add($id = null)
    {
        if (!$_SESSION['role_menu']['Finance']['dexs']['model_w'])
        {die;
            $this->redirect_denied();
        }
        $this->pageTitle = "Add Domestic Exchange";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_dex_impl'), array('id' => $id));
        $this->_render_dex_save_options();

        $egress = array();
        if (empty($id))
        {
            $egress_results = $this->Dex->query("select resource_id, alias from resource where egress = true and active = true order by alias");
        } else
        {
            $egress_results = $this->Dex->query("select resource_id, alias from resource where egress = true and active = true order by alias");
        }
        foreach ($egress_results as $k => $egress_arr)
        {
            $egress[$egress_arr[0]['resource_id']] = $egress_arr[0]['alias'];
        }
        $this->set('egress', $egress);
        $this->Session->write('m', Dex::set_validator());
    }

    public function del_dex($dele_id = null, $fist_id = null)
    {
        if (!$_SESSION['role_menu']['Finance']['dexs']['model_w'])
        {
            $this->redirect_denied();
        }
        //删除
        if (!empty($dele_id))
        {
            $del_sql = "delete  from dex where   dex.id=$dele_id";
            $this->Dex->query($del_sql);
            $this->Dex->create_json_array('', 201, ' delete  success');
        }
        $this->Session->write('m', Dex::set_validator());
        $this->referer("/dexs/view");
    }

//J删除
    public function ex_dele_dex($dele_id = null)
    {
        if (!$_SESSION['role_menu']['Finance']['dexs']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($dele_id))
        {
            $delete_sql = "delete from dex where id =$dele_id";
            $this->Dex->query($delete_sql);
            $this->Dex->create_json_array('', 201, 'delete success');
        }
    }

//J删除
    public function ex_dele_dex_resource($dele_id = null)
    {
        if (!$_SESSION['role_menu']['Finance']['dexs']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->layout = '';
        if (!empty($dele_id))
        {
//            $delete_sql = "delete from dex_resource where id=$dele_id";
//            $this->Dex->query($delete_sql);
            //$this->Dex->create_json_array('',201,'delete success');
            $this->xredirect('/dexs/view');
        }
    }

    function _add_dex_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost())
        {
            //var_dump($this->params['form']);exit;
            $this->_create_or_update_dex_data($this->params['form']);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                //$this->data = $this->Condition->query("select * from alert_condition where id = {$params['id']}");
                $this->data = $this->Dex->find("first", Array('conditions' => array('Dex.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                } else
                {
                    $this->set('p', $this->data['Dex']);
                }
            } else
            {
                
            }
        }
    }

    function _create_or_update_dex_data($params = array())
    {   #update
        //var_dump($params, $this->data);exit;
        if (isset($params['id']) && !empty($params['id']))
        {
            $id = (int) $params ['id'];
            $this->data['id'] = $id;
            if ($this->Dex->save($this->data))
            {
                if (!empty($_REQUEST['data']['resource']))
                {
                    foreach ($_REQUEST['data']['resource'] as $k => $trunk)
                    {
//                        $dex_resource = $this->Dex->query("insert into dex_resource (dex_id, resource_id) values ($id, $trunk) returning id");
                    }
                }
                $this->Dex->create_json_array('', 201, 'Dex,Edit successfully');
                $this->Session->write('m', Dex::set_validator());
                if (empty($dex_resource[0][0]['id']))
                {
                    $this->Dex->create_json_array('', 201, 'Dex Resource, Add Fail!');
                    $this->Session->write('m', Dex::set_validator());
                }

                $this->xredirect("/dexs/view");
                //$this->redirect ( array ('id' => $id ) );  
            }
        }
        # add
        else
        {
            if ($this->Dex->save($this->data))
            {
                $id = $this->Dex->getlastinsertId();
                if (!empty($_REQUEST['data']['resource']))
                {
                    foreach ($_REQUEST['data']['resource'] as $k => $trunk)
                    {
//                        $dex_resource = $this->Dex->query("insert into dex_resource (dex_id, resource_id) values ($id, $trunk) returning id");
                    }
                }
                $this->Dex->create_json_array('', 201, 'Dex，
create successfully');
                $this->Session->write('m', Dex::set_validator());
                $this->xredirect('/dexs/view');
                //		$this->redirect ( array ('id' => $id ) );
            }
        }
    }

    function _render_dex_save_options()
    {
        $this->loadModel('Dex');
        $this->set('DexList', $this->Dex->find('all')); //,Array('fields'=>Array('id','name'))));
    }

}