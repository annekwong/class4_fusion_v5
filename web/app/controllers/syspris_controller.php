<?php

class SysprisController extends AppController
{

    var $name = 'Syspris';
    var $uses = Array('Syspri');
    var $components = array('RequestHandler');

    function index()
    {
        $this->redirect('view_syspri');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    /**
     *  获取模块数组
     */
    public function getModules()
    {
        $return = array();

        $module_list = $this->Syspri->query("select * from sys_module");
        if (!empty($module_list))
        {
            foreach ($module_list as $k => $v)
            {
                $return[$v[0]['id']] = $v[0]['module_name'];
            }
        }

        return $return;
    }

    /**
     * 查看子模块
     */
    function view_syspri($module_id = null)
    {
        $module_id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        //echo $module_id;exit();
        $this->pageTitle = "Configuration/Modules";
        $module_id = base64_decode($module_id);

        $currPage = 1;
        $pageSize = 100;
        $search_arr = array();
        $order_arr = array('sys_pri.flag' => 'desc','pri_val' => 'asc');
        if (!empty($_REQUEST['order_by']))
        {
            $order_by = explode("-", $_REQUEST['order_by']);
            $order_arr[$order_by[0]] = $order_by[1];
        }

        if (!empty($_REQUEST['search']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
        }
        else                      //按条件搜索
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

        $pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        if (!empty($_REQUEST ['size']))
        {
            $pageSize = $_REQUEST ['size'];
        }

        //echo $this->params['pass'][0];exit();
        $results = $this->Syspri->ListSubModule($module_id, $currPage, $pageSize, $search_arr, $search_type, $order_arr);
        $this->set('p', $results);
    }

//初始化查询参数
    function init_query()
    {
        $this->set('modules', $this->getModules());
    }

    function add_syspri($module_id = null)
    {
        $this->pageTitle = "Add module";
        $this->init_query();

        $module_id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];

        if (!empty($this->data ['Syspri']))
        {
            //pr($_POST); exit();
            $pri_name = $this->data ['Syspri']['pri_name'];
            $flg = $this->Syspri->judge_module_name($pri_name);
            if ($flg === FALSE)
            {
                $this->Syspri->create_json_array('', 101, "This privilege name({$pri_name}) is already used!");
            }
            else
            {
                $return = $this->Syspri->saveOrUpdateSubModule($this->data, $_POST); //保存
                if (!empty($return))
                {
                    $this->set('post', $this->data);
                    $this->Syspri->create_json_array('', 201, 'The Sub-module [' . $pri_name  . '] is created successfully!');
                    $this->Session->write('m', Syspri::set_validator());
                    $this->redirect(array('controller' => 'syspris', 'action' => 'view_syspri' . '/' . base64_encode($this->data ['Syspri']['module_id']))); // succ
                }
                else
                {
                    $this->Syspri->create_json_array('', 101, 'Add fail');
                }
            }
        }
    }

    function edit_syspri($id = null)
    {
        $this->pageTitle = "Edit module";
        $this->init_query();
        $id = empty($this->params['pass'][1]) ? null : $this->params['pass'][1];
        $id = base64_decode($id);
        //echo $id;exit();
        $this->_catch_exception_msg(array($this, '_add_syspri_impl'), array('id' => $id));
        $this->_render_syspri_save_options();
        $this->Session->write('m', Syspri::set_validator());
    }

    function _add_syspri_impl($params = array())
    {

        #post
        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_syspri_data($this->params['data']['Syspri']);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $id = base64_decode($this->params['pass'][1]);
                $module_id = base64_decode($this->params['pass'][0]);
                //pr($this->Syspri->find("first", Array('conditions'=>array('Syspri.id'=>$params['id']))));exit();
                $this->data = $this->Syspri->find("first", Array('conditions' => array('Syspri.id' => $id, 'Syspri.module_id' => $module_id)));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                }
                else
                {
                    $this->set('p', $this->data['Syspri']);
                }
            }
            else
            {
                //void
            }
        }
    }

    function del_syspri()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Configuration']['sysmodules']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = empty($this->params['pass'][1]) ? null : $this->params['pass'][1];
        $id = base64_decode($id);
	$name = $this->Syspri->findById($id)['Syspri']['pri_name'];
        $module_id = base64_decode($this->params['pass'][0]);
        if (!empty($id))
        {
            $this->Syspri->query("delete from sys_role_pri where pri_name in (select pri_name from sys_pri where id = " . intval($id) . ")");
            $this->Syspri->query("delete from sys_pri where id = " . intval($id));
        }
	$this->Syspri->create_json_array('', 201, 'The Sub-Module [' . $name . '] is deleted successfully!');
	$this->Session->write('m', Syspri::set_validator());
        $this->redirect(array('controller' => 'syspris', 'action' => 'view_syspri' . '/' . base64_encode($module_id)));
    }

    function _create_or_update_syspri_data($params = array())
    {   #update		
        //var_dump($params);
        if (isset($params['id']) && !empty($params['id']))
        {
            $params['id'] = base64_decode($params['id']);
            $id = (int) $params ['id'];

            $this->data ['Syspri'] ['id'] = $id;
            $pri_info = $this->Syspri->findById($id);
            $pri_name = $pri_info['Syspri']['pri_name'];
            $new_pri_name = $this->data ['Syspri'] ['pri_name'];
            $update_sys_role_pri_sql = "UPDATE sys_role_pri set pri_name = '$new_pri_name' WHERE pri_name = '$pri_name'";
            //echo $update_sys_role_pri_sql;die;
            $this->Syspri->query($update_sys_role_pri_sql);
            if ($this->Syspri->save($this->data))
            {

                $this->Syspri->create_json_array('', 201, 'The Sub-Module [' . $new_pri_name . '] is modified successfully!');
                $this->xredirect('/syspris/view_syspri/' . base64_encode($params['module_id']));
            }
            else
            {
                
            }
        }
        # add
        else
        {
            //void
        }
    }

    function _render_syspri_save_options()
    {
        $this->loadModel('Syspri');

        $this->set('SyspriList', $this->Syspri->find('all'));
    }

}

?>
