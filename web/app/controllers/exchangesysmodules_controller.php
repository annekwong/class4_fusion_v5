<?php

class ExchangesysmodulesController extends AppController
{

    var $name = 'Exchangesysmodules';
    //var $uses=Array('Sysmodule');
    var $components = array('RequestHandler');

    function index()
    {
        $this->redirect('view_sysmodule');
    }

    public function beforeFilter()
    {
        
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    function view_sysmodule()
    {
        $this->pageTitle = "Exchange Manage/Modules";

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
            $search_arr['start_date'] = !empty($_REQUEST['start_date']) ? ($_REQUEST['start_date']) : '';
            $search_arr['end_date'] = !empty($_REQUEST['end_date']) ? ($_REQUEST['end_date']) : '';
            $search_arr['action_type'] = !empty($_REQUEST['tran_type']) ? intval($_REQUEST['tran_type']) : 0;
            $search_arr['status'] = !empty($_REQUEST['tran_status']) ? intval($_REQUEST['tran_status']) : 0;
            $search_arr['descript'] = !empty($_REQUEST['descript']) ? $_REQUEST['descript'] : '';
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

        $results = $this->Exchangesysmodule->ListModule($currPage, $pageSize, $search_arr, $search_type, $order_arr);
        $this->set('p', $results);
    }

    function add_sysmodule()
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        //pr($_POST);exit();
        if (!empty($this->data ['Exchangesysmodule']))
        {
            $flag = $this->Exchangesysmodule->saveOrUpdate($this->data, $_POST); //保存
            $this->Session->write('m', $this->Exchangesysmodule->create_json(201, __('The Module [%s] is created successfully!', true, $this->data['Exchangesysmodule']['module_name'])));
            if (empty($flag))
            {
                $this->set('post', $this->data);
                $this->redirect(array('controller' => 'exchangesysmodules', 'action' => 'view_sysmodule')); // succ
            } else
            {
                //$this->modules->create_json_array('',201,__('Roleshavecreatesuccess',true));

                $this->redirect(array('controller' => 'exchangesysmodules', 'action' => 'view_sysmodule')); // succ
            }
        }
    }

    function edit_sysmodule($id = null)
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Edit module";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_sysmodule_impl'), array('id' => $id));
        $this->_render_sysmodule_save_options();
        $this->render('edit_sysmodule');
        $this->Session->write('m', Exchangesysmodule::set_validator());
    }

    function _add_sysmodule_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_sysmodule_data($this->params['form']);
             $this->Session->write('m', $this->Exchangesysmodule->create_json(201, __('The Module [%s] is modified successfully!', true, $this->data['Sysmodule']['module_name'])));
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $this->data = $this->Exchangesysmodule->find("first", Array('conditions' => array('Exchangesysmodule.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                } else
                {
                    $this->set('p', $this->data['Exchangesysmodule']);
                }
            } else
            {
                //void
            }
        }
    }

    function _create_or_update_sysmodule_data($params = array())
    {   #update		
        //var_dump($params);
        if (isset($params['id']) && !empty($params['id']))
        {
            $id = (int) $params ['id'];

            $this->data ['Exchangesysmodule'] ['id'] = $id;

            if ($this->Exchangesysmodule->save($this->data))
            {

                $this->Exchangesysmodule->create_json_array('', 201, 'Sysmodule , Edit successfully');
                $this->xredirect('/exchangesysmodules/view_sysmodule');
            }
        }
        # add
        else
        {
            //void
        }
    }

    function _render_sysmodule_save_options()
    {
        $this->loadModel('Exchangesysmodule');
        $this->set('ModuleList', $this->Exchangesysmodule->find('all'));
    }

    function del()
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        
        $id = $this->params['pass'][0];
        $sql = "select module_name from exchnage_sys_module where id = {$id}";
        $result = $this->Exchangesysmodule->query($sql);
        $size = $this->Exchangesysmodule->del($id);
        if (empty($size))
        {
            $this->Session->write('m', $this->Exchangesysmodule->create_json(201, "The Module[{$result[0][0]['module_name']}] is deleted successfully."));
        } else
        {
            $this->Session->write('m', $this->Exchangesysmodule->create_json(101, "The Module[{$result[0][0]['module_name']}] is deleted failed."));
        }
        $this->redirect(array('action' => 'view_sysmodule'));
    }

    /**
     * 查看子模块
     */
    function view_submodule($module_id = null)
    {
        $module_id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
//		echo $module_id;exit();
        $this->pageTitle = "Exchange Manage/Modules";

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
            $search_arr['start_date'] = !empty($_REQUEST['start_date']) ? ($_REQUEST['start_date']) : '';
            $search_arr['end_date'] = !empty($_REQUEST['end_date']) ? ($_REQUEST['end_date']) : '';
            $search_arr['action_type'] = !empty($_REQUEST['tran_type']) ? intval($_REQUEST['tran_type']) : 0;
            $search_arr['status'] = !empty($_REQUEST['tran_status']) ? intval($_REQUEST['tran_status']) : 0;
            $search_arr['descript'] = !empty($_REQUEST['descript']) ? $_REQUEST['descript'] : '';
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
        $results = $this->Exchangesysmodule->ListSubModule($module_id, $currPage, $pageSize, $search_arr, $search_type, $order_arr);
        $this->set('p', $results);
    }

    function add_submodule()
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->loadModel('ExchangesubModule');
        if (!empty($this->data ['SubModule']))
        {
            //pr($_POST); exit();
            $flag = $this->ExchangesubModule->saveOrUpdateSubModule($this->data, $_POST); //保存

            if (empty($flag))
            {
                $this->set('post', $this->data);
                $this->SubModule->create_json_array('', 201, 'Add successfully');
                $this->redirect(array('controller' => 'exchangesysmodules', 'action' => 'view_submodule' . '/' . $this->data ['SubModule']['module_id'])); // succ
            } else
            {
                $this->SubModule->create_json_array('', 101, 'Add fail');
            }
        }
    }

    function edit_submodule($id = null)
    {
        if (!$_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Edit module";
        $id = empty($this->params['pass'][1]) ? null : $this->params['pass'][1];
        //echo $id;exit();
        $this->_catch_exception_msg(array($this, '_add_submodule_impl'), array('id' => $id));
        $this->_render_submodule_save_options();
        $this->render('edit_submodule');
        $this->Session->write('m', Module::set_validator());
    }

    function _add_submodule_impl($params = array())
    {
        $this->loadModel('ExchangesubModule');
        #post

        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_submodule_data($this->params['form']);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                //pr($this->SubModule->find("first", Array('conditions'=>array('SubModule.id'=>$params['id']))));exit();
                $this->data = $this->ExchangesubModule->find("first", Array('conditions' => array('SubModule.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                } else
                {
                    $this->set('p', $this->data['SubModule']);
                }
            } else
            {
                //void
            }
        }
    }

    function _create_or_update_submodule_data($params = array())
    {   #update		
        //var_dump($params);
        if (isset($params['id']) && !empty($params['id']))
        {
            $id = (int) $params ['id'];

            $this->data ['Module'] ['id'] = $id;

            if ($this->Module->save($this->data))
            {

                $this->Module->create_json_array('', 201, 'Module , Edit successfully');
                $this->xredirect('/exchangesysmodules/view_submodule');
            }
        }
        # add
        else
        {
            //void
        }
    }

    function _render_submodule_save_options()
    {
        $this->loadModel('ExchangesubModule');

        $this->set('SubModuleList', $this->ExchangesubModule->find('all'));
    }

    function del_submodule()
    {
        if (1)//(!$_SESSION['role_menu']['Configuration']['sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        //pr($this->params['pass'][1]);exit();
        $size = $this->Module->del_submodule($this->params['pass'][1]);
        if (empty($size))
        {
            $this->Session->write('m', $this->Module->create_json(201, "delete module successfully"));
        } else
        {
            $this->Session->write('m', $this->Module->create_json(101, $this->params['pass'][0] . __('thisrole', true) . $size . __('rolecurrentuser', true)));
        }
        $this->redirect(array('action' => 'view_submodule' . '/' . $this->params['pass'][0]));
    }

}

?>
