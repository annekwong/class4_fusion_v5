<?php

class SysmodulesController extends AppController
{

    var $name = 'Sysmodules';
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
        $this->pageTitle = "Configuration/Modules";

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
        $pageSize = 20;

        $results = $this->Sysmodule->ListModule($currPage, $pageSize, $search_arr, $search_type, $order_arr);
        $this->set('p', $results);
    }

    function add_sysmodule()
    {
        if (!$_SESSION['role_menu']['Configuration']['sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        //pr($_POST);exit();
        if (!empty($this->data ['Sysmodule']))
        {
            $id = $this->data ['Sysmodule']['id'];
            $action = empty($id) ? 'created' : 'modified';
            $module_name = $_POST['data']['Sysmodule']['module_name'];
            $conditions = array('module_name' => $module_name);

            if(!empty($id)) {
                $conditions['NOT']['id'] = $id;
            }

            $check = $this->Sysmodule->find($conditions);
            if($check){
                $this->Session->write('m', $this->Sysmodule->create_json(101, __('The Module Name [' . $module_name . '] is already in use!', true)));
                if($action == 'created'){
                    $this->redirect(array('controller' => 'sysmodules', 'action' => 'add_sysmodule'));
                }else{
                    $this->redirect(array('controller' => 'sysmodules', 'action' => "edit_sysmodule/$id"));
                }
            }
            $flag = $this->Sysmodule->saveOrUpdate($this->data, $_POST); //保存

            if ($flag === false)
            {
                $this->Session->write('m', $this->Sysmodule->create_json(101, __('The Module [%s] is %s failure!', true, array($this->data['Sysmodule']['module_name'], $action))));
                $this->set('post', $this->data);
                $this->redirect(array('controller' => 'sysmodules', 'action' => 'view_sysmodule')); // false
            } else
            {
                //$this->modules->create_json_array('',201,__('Roleshavecreatesuccess',true));
                $this->Session->write('m', $this->Sysmodule->create_json(201, __('The Module [%s] is %s successfully!', true, array($this->data['Sysmodule']['module_name'], $action))));

                $this->redirect(array('controller' => 'sysmodules', 'action' => 'view_sysmodule')); // succ
            }
        }
    }

    function edit_sysmodule($id = null)
    {
        if (!$_SESSION['role_menu']['Configuration']['sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Edit module";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $id = base64_decode($id);
        $this->_catch_exception_msg(array($this, '_add_sysmodule_impl'), array('id' => $id));
        $this->_render_sysmodule_save_options();
        $this->render('edit_sysmodule');
        $this->Session->write('m', Sysmodule::set_validator());
    }

    function _add_sysmodule_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_sysmodule_data($this->params['form']);
             $this->Session->write('m', $this->Sysmodule->create_json(201, __('The Module [%s] is modified successfully!', true, $this->data['Sysmodule']['module_name'])));
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $this->data = $this->Sysmodule->find("first", Array('conditions' => array('Sysmodule.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                } else
                {
                    $this->set('p', $this->data['Sysmodule']);
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

            $this->data ['Sysmodule'] ['id'] = $id;

            if ($this->Sysmodule->save($this->data))
            {

                $this->Sysmodule->create_json_array('', 201, 'Sysmodule , Edit successfully');
                $this->xredirect('/sysmodules/view_sysmodule');
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
        $this->loadModel('Sysmodule');
        $this->set('ModuleList', $this->Sysmodule->find('all'));
    }

    function del()
    {
        if (!$_SESSION['role_menu']['Configuration']['sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        
        $id = base64_decode($this->params['pass'][0]);
        $sql = "select module_name from sys_module where id = {$id}";
        $result = $this->Sysmodule->query($sql);
        
        $usage_result = $this->Sysmodule->query("select count(*) from sys_pri limit module_id = $id");
        if ((int)$usage_result[0][0]['count'] > 0) {
            $this->Session->write('m', $this->Sysmodule->create_json(101, "The Module [{$result[0][0]['module_name']}] has submodules."));
        } else {
            $size = $this->Sysmodule->del($id);
            if (empty($size))
            {
                $this->Session->write('m', $this->Sysmodule->create_json(201, "The Module [{$result[0][0]['module_name']}] is deleted successfully!"));
            } else
            {
                $this->Session->write('m', $this->Sysmodule->create_json(101, "The Module[{$result[0][0]['module_name']}] is deleted failed."));
            }
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
        $this->pageTitle = "Configuration/Modules";

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
        $results = $this->Sysmodule->ListSubModule($module_id, $currPage, $pageSize, $search_arr, $search_type, $order_arr);
        $this->set('p', $results);
    }

    function add_submodule()
    {
        if (!$_SESSION['role_menu']['Configuration']['sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->loadModel('SubModule');
        if (!empty($this->data ['SubModule']))
        {
            //pr($_POST); exit();
            $flag = $this->SubModule->saveOrUpdateSubModule($this->data, $_POST); //保存

            if (empty($flag))
            {
                $this->set('post', $this->data);
                $this->SubModule->create_json_array('', 201, 'Add successfully');
                $this->redirect(array('controller' => 'sysmodules', 'action' => 'view_submodule' . '/' . $this->data ['SubModule']['module_id'])); // succ
            } else
            {
                $this->SubModule->create_json_array('', 101, 'Add fail');
            }
        }
    }

    function edit_submodule($id = null)
    {
        if (!$_SESSION['role_menu']['Configuration']['sysmodules']['model_w'])
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
        $this->loadModel('SubModule');
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
                $this->data = $this->SubModule->find("first", Array('conditions' => array('SubModule.id' => $params['id'])));
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
                $this->xredirect('/sysmodules/view_submodule');
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
        $this->loadModel('SubModule');

        $this->set('SubModuleList', $this->SubModule->find('all'));
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

    function inactivateAjax() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $moduleId = $this->params['form']['moduleId'];
        $mesg_info = $this->Sysmodule->query("select module_name from sys_module where id = {$moduleId}");
        $this->Sysmodule->query("update sys_module set  status='0' where  id='$moduleId'");
        echo json_encode(array('success' => true, 'theme' => 'jmsg-success', 'message' => __('The Module [%s] is disabled successfully!', true, $mesg_info[0][0]['module_name'])));
    }

    function activateAjax() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $moduleId = $this->params['form']['moduleId'];
        $mesg_info = $this->Sysmodule->query("select module_name from sys_module where id = {$moduleId}");
        $this->Sysmodule->query("update sys_module set  status='1' where  id='$moduleId'");

        echo json_encode(array('success' => true, 'theme' => 'jmsg-success', 'message' => __('The Module [%s] is enabled successfully!', true, $mesg_info[0][0]['module_name'])));
    }
    

}

?>
