<?php

class BlocklistsController extends AppController
{

    var $name = 'Blocklists';
    var $uses = Array('ResourceBlock', 'Blocklist', 'ImportExportLog', 'TrunkGroup', 'Systemparam');
    var $helpers = array('javascript', 'html', 'appBlocklists');
    var $rollback = false;

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_route_blocklist');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    //上传拒绝号码	
    public function import_rate()
    {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_x'])
        {
            $this->redirect_denied();
        }
    }

//上传成功 记录上传
    public function upload_code2()
    {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_x'])
        {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['upload_table_id'];
        $list = $this->Blocklist->import_data(__('UploadBlockList', true)); //上传数据
        $this->Blocklist->create_json_array("", 201, 'UploadBlockList ');
        $this->Session->write('m', Blocklist::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    /**
     * 初始化信息
     */
    function init_info()
    {
        $reseller_id = $this->Session->read('sst_reseller_id');
        $this->set('ingress', $this->Blocklist->findIngress());
        $this->set('egress', $this->Blocklist->findEgress());
        $this->set('client', $this->Blocklist->findClient());
        $this->set('timeprofiles', $this->Blocklist->getTimeProfiles($reseller_id));
    }

    /**
     * 编辑客户信息
     */
    function edit($id = null)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            $check_result = $this->_check_add_save($this->data['ResourceBlock']);
            if (!$check_result['flg'])
            {
                $this->Session->write('m', $this->Blocklist->create_json(101, $check_result['msg']));
                $this->redirect('/blocklists/index');
            }
            $old_data = $this->Blocklist->findByResBlockId($id);

            if ($this->_render_save_impl($id))
            {
                $rollback_data = array();
                foreach ($this->params['data']['ResourceBlock'] as $key => $value)
                {
                    if ($old_data['Blocklist'][$key] != $value && strcmp($key, 'update_by'))
                    {
                        if (strcmp(intval($value), $value) || !strcmp($key, 'digit') || !strcmp($key, 'ani_prefix'))
                        {
                            $rollback_data[] = $key . " = '" . $old_data['Blocklist'][$key] . "'";
                        }
                        else
                        {
                            $rollback_data[] = $key . " = " . $old_data['Blocklist'][$key];
                        }
                    }
                }
                $rollback_update_sql = implode(',', $rollback_data);
                $rollback_sql = "UPDATE resource_block SET {$rollback_update_sql} WHERE res_block_id = {$id}";
                $rollback_msg = "Modify Block List [" . $this->data['ResourceBlock']['ani_prefix'] . "] operation have been rolled back!";

                //$this->Blocklist->create_json_array("",201,'Block List,  Edit successfullyfully !');
                $this->Blocklist->create_json_array("", 201, 'The Block List [' . $this->data['ResourceBlock']['digit'] . '] is modified successfully!');

                $log_id = $this->ResourceBlock->logging('2', 'Block List', "Block digit:{$this->data['ResourceBlock']['digit']} ", $rollback_sql, $rollback_msg);
                $url_flug = "blocklists-index";
                $this->modify_log_noty($log_id, $url_flug);
//                if ($_SESSION['role_menu']['Log']['logging']['model_r'])
//                {
//                    $this->redirect_denied();
//                }
//                $this->xredirect("/logging/index/{$log_id}/blocklists-index-{$type}");
            }
        }
    }

    public function ajax_ingress()
    {

        Configure::write('debug', 0);
        $this->set('extensionBeans', $this->Blocklist->ajaxfindIngressbyClientId($this->params['pass'][0]));
    }

    public function ajax_egress()
    {
        Configure::write('debug', 0);
        $client_id = $this->params['pass'][0];
        if (empty($client_id))
        {
            $r = $this->Blocklist->query("select resource_id ,alias from resource  where egress  is true order by alias ");
        }
        else
        {
            $r = $this->Blocklist->query("select resource_id ,alias from resource  where egress  is true   and client_id =$client_id  order by alias ");
        }


        $html = "<option value=''>ALL</option>";
        foreach ($r as $k => $v)
        {
            $html.="<option value='{$v[0]['resource_id']}'>{$v[0]['alias']}</option>";
        }

        echo $html;
    }

    /**
     * 添加
     */
    function add()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            $check_result = $this->_check_add_save($this->data['ResourceBlock']);

            if (!$check_result['flg'])
            {
                $this->Session->write('m', $this->Blocklist->create_json(101, $check_result['msg']));
                $this->redirect('/blocklists/index');
            }
            if ($this->_render_save_impl_new())
            {
                $id = $this->ResourceBlock->getlastinsertId();
                $rollback_sql = "DELETE FROM resource_block WHERE res_block_id = {$id}";
                $rollback_msg = "Create Block List [" . $this->data['ResourceBlock']['ani_prefix'] . "] operation have been rolled back!";
                //$this->ResourceBlock->create_json_array("",201,'Block List, create successfully !');
                $this->ResourceBlock->create_json_array("", 201, 'The Block List [' . $this->data['ResourceBlock']['ani_prefix'] . '] is created successfully !');

                $log_id = $this->ResourceBlock->logging('0', 'Block List', "Block List:{$this->data['ResourceBlock']['ani_prefix']} ", $rollback_sql, $rollback_msg);
                $url_flug = "blocklists-index";
                $this->modify_log_noty($log_id, $url_flug);
                $this->redirect('/blocklists/index');
//                $this->xredirect("/logging/index/{$log_id}/blocklists-index-{$type}");
            }
        }
    }


    function _check_add_save($data)
    {
//        if (!$data['ingress_res_id'])
//        {
//            $return['msg'] = "Trunk cannot be null";
//            $return['flg'] = false;
//            return $return;
//        }

        if (preg_match('/[\D]/', $data['ani_prefix']) && $data['ani_prefix'])
        {
            //$return['msg'] = "ANI Prefix must be an integer.";
            $return['msg'] = "All those fields should allow numeric input only.";
            $return['flg'] = false;
            return $return;
        }

        $type = $data['type'];
        if($type == 1) {
//            if((!empty($data['ingress_client_id']) && empty($data['ingress_res_id'])) || (!empty($data['egress_client_id']) && empty($data['engress_res_id']))) {
//                $return['msg'] = __('block control notice',true);
//                $return['flg'] = false;
//                return $return;
//            }
//            if (empty($data['ani_prefix']) && empty($data['digit']))
//            {
//                $return['msg'] = "Please enter ANI Prefix or DNIS Prefix!";
//                $return['flg'] = false;
//                return $return;
//            }
        } elseif($type == 2) {

            if(empty($data['ingress_group_id']) && empty($data['egress_group_id'])) {
                $return['msg'] = __('block control notice',true);
                $return['flg'] = false;
                return $return;
            }
        }

        if (preg_match('/[\D]/', $data['digit']) && $data['digit'])
        {
            //$return['msg'] = "DNIS Prefix must be an integer.";
            $return['msg'] = "All those fields should allow numeric input only.";
            $return['flg'] = false;
            return $return;
        }

        if (preg_match('/[\D]/', $data['ani_length']) && $data['ani_method'])
        {
            //$return['msg'] = "ANI Length must be an integer.";
            $return['msg'] = "All those fields should allow numeric input only.";
            $return['flg'] = false;
            return $return;
        }

        if (preg_match('/[\D]/', $data['dnis_length']) && $data['dnis_method'])
        {
            //$return['msg'] = "DNIS Length must be an integer.";
            $return['msg'] = "All those fields should allow numeric input only.";
            $return['flg'] = false;
            return $return;
        }
        $conditions = array(
            'ResourceBlock.time_profile_id' => $data['time_profile_id']?: NULL,
            'ResourceBlock.engress_res_id' => $data['engress_res_id']?: NULL,
            'ResourceBlock.ingress_res_id' => $data['ingress_res_id']?: NULL,
            'ResourceBlock.egress_group_id' => $data['egress_group_id']?: NULL,
            'ResourceBlock.ingress_group_id' => $data['ingress_group_id']?: NULL,
            'ResourceBlock.ani_empty' => $data['ani_empty']?: false,
            'ResourceBlock.ani_prefix' => $data['ani_prefix']?: NULL,
            'ResourceBlock.ani_length' => $data['ani_length']?: NULL,
            'ResourceBlock.ani_max_length' => $data['ani_max_length']?: null,
            'ResourceBlock.dnis_length' => $data['dnis_length']?: null,
            'ResourceBlock.dnis_max_length' => $data['dnis_max_length']?: null,
        );
        if($this->ResourceBlock->hasAny($conditions)){
            $return['msg'] = "Block conditions already exist!";
            $return['flg'] = false;
            return $return;
        }

        return array('flg' => true);
    }

    function _render_save_impl($id = null)
    {
        $this->_format_save_data($id);
        $this->data['ResourceBlock']['update_by'] = $_SESSION['sst_user_name'];
        return $this->ResourceBlock->save($this->data);
    }

    function _format_save_data($id = null)
    {
        if (!empty($id))
            $this->data['ResourceBlock']['res_block_id'] = $id;
    }

    function _render_save_impl_new($id = null)
    {
        $this->_format_save_data($id);
        if($this->data['ResourceBlock']['type'] == 1) {
            unset($this->data['ResourceBlock']['ingress_group_id']);
            unset($this->data['ResourceBlock']['egress_group_id']);
            if ($this->data['ResourceBlock']['ingress_client_id'] && !$this->data['ResourceBlock']['ingress_res_id'])
                $this->data['ResourceBlock']['ingress_res_id'] = 'all';
            if ($this->data['ResourceBlock']['egress_client_id'] && !$this->data['ResourceBlock']['engress_res_id'])
                $this->data['ResourceBlock']['engress_res_id'] = 'all';
            if (!strcmp(strtolower($this->data['ResourceBlock']['ingress_res_id']),'all'))
            {
                $ingress_more_where = "and client_id = ".intval($this->data['ResourceBlock']['ingress_client_id']);
                $ingress_trunks = $this->ResourceBlock->findAll_ingress_id(false,$ingress_more_where);
                $data = array();
                $this->data['ResourceBlock']['update_by'] = $_SESSION['sst_user_name'];
                $this->data['ResourceBlock']['create_by'] = $_SESSION['sst_user_name'];
                $is_egress_all = strcmp(strtolower($this->data['ResourceBlock']['engress_res_id']),'all');
                foreach ($ingress_trunks as $ingress_id => $ingress_trunk)
                {
                    $this->data['ResourceBlock']['ingress_res_id'] = $ingress_id;
                    if (!$is_egress_all)
                    {
                        $egress_more_where = "and client_id = ".intval($this->data['ResourceBlock']['egress_client_id']);
                        $egress_trunks = $this->ResourceBlock->findAll_egress_id(false,$egress_more_where);
                    }
                    else
                        $egress_trunks = array($this->data['ResourceBlock']['engress_res_id'] => $this->data['ResourceBlock']['engress_res_id']);
                    foreach ($egress_trunks as $egress_id => $egress_trunk )
                    {
                        $this->data['ResourceBlock']['engress_res_id'] = $egress_id;
                        $data[] = $this->data['ResourceBlock'];
                    }
                }
                return $this->ResourceBlock->saveAll($data);

            }
            elseif (!strcmp(strtolower($this->data['ResourceBlock']['engress_res_id']),'all'))
            {
                $egress_more_where = "and client_id = ".intval($this->data['ResourceBlock']['egress_client_id']);
                $egress_trunks = $this->ResourceBlock->findAll_egress_id(false,$egress_more_where);
                $this->data['ResourceBlock']['update_by'] = $_SESSION['sst_user_name'];
                $this->data['ResourceBlock']['create_by'] = $_SESSION['sst_user_name'];
                $data = array();
                foreach ($egress_trunks as $egress_id => $egress_trunk)
                {
                    $this->data['ResourceBlock']['engress_res_id'] = $egress_id;
                    $data[] = $this->data['ResourceBlock'];
                }
                return $this->ResourceBlock->saveAll($data);
            } 
            $this->data['ResourceBlock']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['ResourceBlock']['create_by'] = $_SESSION['sst_user_name'];
            unset($this->data['ResourceBlock']['type']);
            return $this->ResourceBlock->save($this->data);
        }
        else
        {
            unset($this->data['ResourceBlock']['ingress_client_id']);
            unset($this->data['ResourceBlock']['egress_client_id']);
            $this->data['ResourceBlock']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['ResourceBlock']['create_by'] = $_SESSION['sst_user_name'];
            return $this->ResourceBlock->save($this->data);
        }
    }



    function del($id, $type='')
    {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->_render_js_save_impl($id);
        $delRes = $this->Blocklist->del($id);
//        $tmpBool = is_array($delRes);
//        die(var_dump(is_array($delRes)));
        if (is_array($delRes))
        {
            $filed_arr = array();
            $value_arr = array();
            $old_data_arr = $this->data['ResourceBlock'];
            unset($old_data_arr['res_block_id']);
            unset($old_data_arr['update_by']);
            foreach ($old_data_arr as $key => $value)
            {
                if ($value)
                {
                    $filed_arr[] = $key;
                    if (strcmp(intval($value), $value) || !strcmp($key, 'digit') || !strcmp($key, 'ani_prefix'))
                    {
                        $value_arr[] = "'" . $value . "'";
                    }
                    else
                    {
                        $value_arr[] = $value;
                    }
                }
            }
            $filed_str = implode(',', $filed_arr);
            $value_str = implode(',', $value_arr);

            $rollback_sql = "INSERT INTO resource_block ({$filed_str}) VALUES ({$value_str})";
            $rollback_msg = "Delete Block List [" . $this->data['ResourceBlock']['digit'] . "] operation have been rolled back!";
            $this->Session->write('m', $this->Blocklist->create_json(201, 'The Block list [' . $this->data['ResourceBlock']['digit'] . '] is deleted successfully!'));
            $log_id = $this->ResourceBlock->logging('1', 'Block List', "Block List:{$this->data['ResourceBlock']['digit']} ", $rollback_sql, $rollback_msg);
            $url_flug = "blocklists-index-{$type}";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/blocklists-index-{$type}");
        }
        else
        {
//            die(var_dump($delRes));
            $this->Session->write('m', $this->Blocklist->create_json(101, 'Fail to delete Block list.'));
            $this->xredirect(array('action' => 'index', $type));
        }
    }

    /**
     * 查询客户
     */
    public function view()
    {
        $this->init_info();
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey']))
        {
            $results = $this->Blocklist->likequery($_POST['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);

            return;
        }

        //高级搜索 
        if (!empty($this->data['Blocklist']))
        {


            $results = $this->Blocklist->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            if (!empty($_REQUEST['edit_id']))
            {
                $sql = "select resource_block.res_block_id,e.egress_name,i.ingress_name,digit,
		(select name from time_profile where time_profile_id = resource_block.time_profile_id) as time_profile
		    from  resource_block
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=resource_block.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=resource_block.ingress_res_id
		    where res_block_id  = {$_REQUEST['edit_id']}
	  		";
                $result = $this->Blocklist->query($sql);
                //分页信息
                require_once 'MyPage.php';
                $results = new MyPage ();
                $results->setTotalRecords(1); //总记录数
                $results->setCurrPage(1); //当前页
                $results->setPageSize(1); //页大小
                $results->setDataArray($result);
                $this->set('edit_return', true);
            }
            else
            {
                //
                $results = $this->Blocklist->findAll($currPage, $pageSize, $this->_order_condtions(
                                array('res_block_id', 'egress_name', 'ingress_name', 'digit', 'time_profile')));
            }
        }
        $this->set('p', $results);
    }

    function _render_save_bindModel()
    {
        $bindModel = Array();
        $bindModel['belongsTo'] = Array();
        $bindModel['belongsTo']['TimeProfile'] = Array('className' => 'TimeProfile', 'fields' => 'name');
        $bindModel['belongsTo']['Egress'] = Array('className' => 'Resource', 'fields' => 'alias', 'foreignKey' => 'engress_res_id');
        $bindModel['belongsTo']['EgressClient'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'), 'foreignKey' => 'egress_client_id');
        $bindModel['belongsTo']['Ingress'] = Array('className' => 'Resource', 'fields' => 'alias', 'foreignKey' => 'ingress_res_id');
        $bindModel['belongsTo']['IngressClient'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'), 'foreignKey' => 'ingress_client_id');
        $this->loadModel('ResourceBlock');
        $this->ResourceBlock->bindModel($bindModel, false);
    }

    function js_save()
    {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $id = array_keys_value($this->params, 'url.id');
        $this->_render_js_save_options();
        $this->_render_js_save_impl($id);
        $ingress_group = $this->TrunkGroup->find('all',array('conditions' => array('trunk_type' => 0)));
        $egress_group = $this->TrunkGroup->find('all',array('conditions' => array('trunk_type' => 1)));
        $this->set('ingress_group', $ingress_group);
        $this->set('egress_group', $egress_group);
        $ingress_trunks = $this->ResourceBlock->findAll_ingress_id();
        $egress_trunks = $this->ResourceBlock->findAll_egress_id();
        $this->set('ingress_trunks', array_filter($ingress_trunks));
        $this->set('egress_trunks', array_filter($egress_trunks));
    }

    function _render_js_save_impl($id = null)
    {
        if (!empty($id))
        {
            $this->_render_save_bindModel();
            $this->data = $this->ResourceBlock->find('first', Array('conditions' => Array('res_block_id' => $id)));
        }
    }

    function _render_js_save_options()
    {
        $this->loadModel('Client');
        $this->loadModel('TimeProfile');
        $this->Client->bindModel(Array('hasOne' => Array('Resource' => Array('className' => 'Resource', 'fields' => Array('resource_id')))));
        $this->set('IngressClient', $IngressClientt = $this->Client->find('all', Array('conditions' => Array('Resource.ingress=true AND Resource.is_virtual is not true and active is true group by "Client"."client_id","Client"."name"'), 'order' => array('Client.name'), 'fields' => Array('Client.client_id', 'Client.name'))));
        $this->Client->bindModel(Array('hasOne' => Array('Resource' => Array('className' => 'Resource', 'fields' => Array('resource_id')))));
        $this->set('EgressClient', $this->Client->find('all', Array('conditions' => Array('Resource.egress=true AND Resource.is_virtual is not true and active is true group by "Client"."client_id","Client"."name"'), 'fields' => Array('Client.client_id', 'Client.name'),'order' => array('Client.name'))));
        $this->set('TimeProfileList', $this->TimeProfile->find('all',array('order'=>'name')));
    }

    function _render_index_bindModel()
    {
        $bindModel = Array();
        $bindModel['belongsTo'] = Array();
        $bindModel['belongsTo']['TimeProfile'] = Array('className' => 'TimeProfile', 'fields' => 'name');
        $bindModel['belongsTo']['Egress'] = Array('className' => 'Resource', 'fields' => 'alias', 'foreignKey' => 'engress_res_id');
        $bindModel['belongsTo']['EgressClient'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'), 'foreignKey' => 'egress_client_id');
        $bindModel['belongsTo']['Ingress'] = Array('className' => 'Resource', 'fields' => array('alias', 'ingress', 'egress'), 'foreignKey' => 'ingress_res_id');
        $bindModel['belongsTo']['IngressClient'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'), 'foreignKey' => 'ingress_client_id');
        $this->loadModel('ResourceBlock');
        $this->ResourceBlock->bindModel($bindModel, false);
    }

    function _order_index_conditions()
    {
        $order_Array = array('res_block_id', 'ingress_client_id', 'egress_client_id', 'ealias' => 'Egress.alias', 'inalias' => 'Ingress.alias', 'ResourceBlock.digit', 'tname' => 'TimeProfile.end_week,TimeProfile.start_week,TimeProfile.end_time,TimeProfile.start_time', 'time_profile');
        $this->paginate['order'] = $this->_order_condtions($order_Array, null, 'IngressClient.name,EgressClient.name, ResourceBlock.ani_prefix,ResourceBlock.dnis_method, ResourceBlock.dnis_method, ResourceBlock.dnis_length');
    }

    function _filter_index_conditions()
    {
        $filter_Array = array('vendor_trunk_id' => 'Egress.alias', 'ingress_res_id' => 'Ingress.alias', 'digit' => 'ResourceBlock.digit', 'ani_prefix' => 'ResourceBlock.ani_prefix', 'action_type' => 'ResourceBlock.action_type', 'search');
        $filter_conditions[] = $this->_filter_conditions($filter_Array);

//        if ($type == 2)
//        {
//            $filter_conditions_t['Ingress.egress'] = true;
//        }
//        else
//        {
//            $filter_conditions_t['Ingress.ingress'] = true;
//        }

//        $filter_conditions['OR'] = array(
//            'ResourceBlock.ingress_res_id' => NULL,
////            $filter_conditions_t,
//        );
        $this->paginate['conditions'] = $filter_conditions;
    }

    function _render_index_data()
    {
//        $this->ResourceBlock->query("ALTER TABLE resource_block ADD COLUMN type integer");
        $this->_order_index_conditions();
        $this->_render_index_bindModel();
        $order_arr =array(
            'ingress_res_id' => 'desc',
            'digit' => 'desc',
            'ani_prefix' => 'desc',
            'ingress_client_id' => 'desc'
        );
        $order = $this->_order_condtions(array('ingress_res_id','engress_res_id','ani_prefix','digit','ingress_client_id','egress_client_id',
            'ResourceBlock.time_profile_id','ani_length','dnis_length'));
        if($order)
            $order_arr = $order;
        if($this->isnotEmpty($this->params['url'], array('order_by')) && !$order){
            $order_by = $this->params['url']['order_by'];
            $order_arr2 = explode('-', $order_by);
            if (count($order_arr2) == 2)
            {
                $field = $order_arr2[0];
                $sort = $order_arr2[1];
                if($field == 'ResourceBlock.time_profile_id'){

                    $order_arr = array($field => $sort);
                }
            }
        }
        $limit = $this->_get('size',100);
        $this->paginate = array(
            'order' => $order_arr,
            'limit' => $limit
        );
        $this->_filter_index_conditions();
        $this->data = $this->paginate('ResourceBlock');
        foreach ($this->data as &$item)
        {
            $item['ResourceBlock']['block_on'] = $this->ResourceBlock->get_resource_block_time($item['ResourceBlock']['block_log_id'], $item['ResourceBlock']['loop_block_id'], $item['ResourceBlock']['ticket_log_id']);
            $ingress_group_name = $this->TrunkGroup->find('first', array(
                'conditions' => array(
                    'group_id' => $item['ResourceBlock']['ingress_group_id']
                )
            ));
            $item['ResourceBlock']['ingress_group_name'] = $ingress_group_name['TrunkGroup']['group_name'];
            $egress_group_name = $this->TrunkGroup->find('first', array(
                'conditions' => array(
                    'group_id' => $item['ResourceBlock']['egress_group_id']
                )
            ));
            $item['ResourceBlock']['egress_group_name'] = $egress_group_name['TrunkGroup']['group_name'];
        }
        
    }

    function _render_index_options()
    {
        $this->loadModel('Resource');
        $this->Resource->hasMany = array();
        $this->set('IngressList', $this->Resource->find('all', Array('conditions' => Array('ingress is true'), 'fields' => Array('resource_id', 'alias'), 'order' => array('alias'))));
        $this->set('EgressList', $this->Resource->find('all', Array('conditions' => Array('egress is true'), 'fields' => Array('resource_id', 'alias'), 'order' => array('alias'))));
        $ingress_trunks = $this->ResourceBlock->findAll_ingress_id();
        $egress_trunks = $this->ResourceBlock->findAll_egress_id();
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
    }

    function index()
    {
//        $this->Blocklist->query("ALTER TABLE resource_block ADD COLUMN type integer");
//        $tmpData = $this->Blocklist->find('all');
//        die(var_dump($tmpData));
        $this->pageTitle = "Routing/Block list";
        $this->_render_index_data();
        $this->_render_index_options();
        $block_action_type = array(
            __('Manual',true),__('Rule',true),__('Dialer Detection',true),__('Fraud Detection',true),__('Invalid Number Detection',true),
            __('Bad ANI / DNIS Detection',true),
        );
        $this->set('block_action_type',$block_action_type);
    }

    public function download()
    {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $reseller_id = $this->Session->read('sst_reseller_id');
        $sql = "select ingress_res_id,engress_res_id,digit,time_profile_id from resource_block";
        $this->Blocklist->export__sql_data(__('downloadBlockList', true), $sql, "block_list");
        $this->layout = '';
    }

    function _filter_search()
    {
        $search = $this->_get('search');
        if (!empty($search))
        {
            return " (\"ResourceBlock\".\"digit\"::text like '$search%' or \"Egress\".\"alias\"::text like '%$search%' or \"Ingress\".\"alias\"::text like '%$search%')";
        }
        return "";
    }

    function _filter_ani_prefix()
    {
        $ani_prefix = $this->_get('filter_ani_prefix');
        if (!empty($ani_prefix))
        {
            return "ResourceBlock.ani_prefix::prefix_range <@ '{$ani_prefix}'";
        }
        return "";
    }

    function _filter_digit()
    {
        $filter_digit = $this->_get('filter_digit');
        if (!empty($filter_digit))
        {
            return "ResourceBlock.digit::prefix_range <@ '{$filter_digit}'";
        }
        return "";
    }

    function ajaxValidateRepeat()
    {
        Configure::write('debug', 0);

        $this->layout = 'ajax';
        $id = $this->_get('id') + 0;
        $digit = $this->_get('digit');
        $ani = $this->_get('ani');
        $ingress_trunk = $this->_get('ingress_trunk');
        if ($ingress_trunk == 'null')
        {
            $ingress_trunk = null;
        }
        $egress_trunk = $this->_get('egress_trunk');
        if ($egress_trunk == 'null')
        {
            $egress_trunk = null;
        }
        $conditions = Array('digit' => $digit, 'ani_prefix' => $ani);
        if (!empty($id))
        {
            $conditions[] = "res_block_id <> '$id'";
        }
        if (!empty($egress_trunk))
        {
            $conditions['engress_res_id'] = $egress_trunk;
        }
        else
        {
            $conditions[] = "engress_res_id is null";
        }
        if (!empty($ingress_trunk))
        {
            $conditions['ingress_res_id'] = $ingress_trunk;
        }
        else
        {
            $conditions[] = "ingress_res_id is null";
        }
        $list = $this->ResourceBlock->find('count', Array('conditions' => $conditions));
        if ($list > 0)
        {
            echo 'false';
        }
    }

    //select delete
    public function del_selected_blo()
    {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w'])
        {
            $this->redirect_denied();
        }
        $ids_request = $_REQUEST['ids'];
        if(strpos($ids_request,',')){
            $mess_list = 'Lists';
            $mess_is = 'are';
        }
        else{
            $mess_list = 'List';
            $mess_is = 'is';
        }
        $ids_arr = explode(",", $ids_request);
        $ids_t = array();
        foreach ($ids_arr as $id_item)
        {
            if (ctype_digit($id_item))
                $ids_t[] = $id_item;
        }
        $ids = implode(",", $ids_t);
        $arrDigit = $this->Blocklist->getDigitByID($ids);
        $tip = "";
        $old_data_arr = $this->ResourceBlock->findAll(array("res_block_id in ($ids)"));
        foreach ($arrDigit as $digit)
        {
            $tip.=$digit[0]['digit'] . ",";
        }
        $tip = "[" . substr($tip, 0, -1) . "]";
        $this->Blocklist->begin();
        $qs_c = 0;
        $qs = $this->Blocklist->query("delete from resource_block where res_block_id in ($ids)");
        if ($qs === false)
        {
            $this->rollback = true;
            $this->Blocklist->rollback();
        }
        $qs_c += count($qs);
//		$qs =	$this->Product->query("delete from resource_product_ref where product_id in ($ids)");
//		$qs_c += count($qs);
        $rollback_sql = "";
        $rollback_msg = "";
        $this->Blocklist->commit();
        if ($this->rollback === false)
        {
            $rollback_sql = $this->_do_del_rollback($old_data_arr);
            $rollback_msg = "Delete Block List [" . $tip . "] operation have been rolled back!";
            $this->Blocklist->create_json_array('', 201, __('The Block ' . $mess_list . ' %s ' . $mess_is . ' deleted successfully!', true, $tip));
            $log_id = $this->ResourceBlock->logging('1', 'Block List', "Block List:{$tip} ", $rollback_sql, $rollback_msg);
            $this->Session->write('m', Blocklist::set_validator());
            $url_flug = "blocklists-index";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/blocklists-index-{$type}");
        }
        else
        {
            $this->Blocklist->create_json_array('', 101, __('Fail to delete Block list.', true));
            $this->Session->write('m', Blocklist::set_validator());
            $this->redirect("index");
        }
    }

//delete all
    public function del_all_blo()
    {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->Blocklist->begin();
        $qs_c = 0;
//        $qs = $this->Blocklist->query("truncate resource_block");
        $qs = $this->Blocklist->query("delete from resource_block");
        $qs_c += count($qs);
//		$qs = $this->Product->query("delete from resource_product_ref");
//		$qs_c += count($qs);
        $rollback_sql = "";
        $rollback_msg = "";
        if ($qs_c == 0)
        {
            $rollback_msg = "Delete Block List all data operation have been rolled back!";
            $this->Blocklist->create_json_array('', 201, __('All Block Lists are deleted successfully!', true));
            $this->Blocklist->commit();
        }
        else
        {
            $this->Blocklist->create_json_array('', 101, __('Deleted All unsuccessfully', true));
            $this->Blocklist->rollback();
        }
        $this->Session->write('m', Blocklist::set_validator());
        $log_id = $this->ResourceBlock->logging('1', 'Block List', "Deleted All ", '', $rollback_msg);
        $url_flug = "blocklists-index";
        $this->modify_log_noty($log_id, $url_flug);
    }

    public function _do_del_rollback($data_arr)
    {
        foreach ($data_arr as $item)
        {
            $filed_arr = array();
            $value_arr = array();
            $old_data_arr = $item['ResourceBlock'];
            unset($old_data_arr['res_block_id']);
            $old_data_arr['update_by'] = $_SESSION['sst_user_name'];
            foreach ($old_data_arr as $key => $value)
            {
                if ($value)
                {
                    $filed_arr[] = $key;
                    if (strcmp(intval($value), $value) || !strcmp($key, 'digit') || !strcmp($key, 'ani_prefix'))
                    {
                        $value_arr[] = "'" . $value . "'";
                    }
                    else
                    {
                        $value_arr[] = $value;
                    }
                }
            }
            $filed_str = implode(',', $filed_arr);
            $value_str_arr[] = "(" . implode(',', $value_arr) . ")";
        }
        $value_str_sql = implode(',', $value_str_arr);
        $rollback_sql = "INSERT INTO resource_block ({$filed_str}) VALUES {$value_str_sql}";
        if (empty($value_arr))
        {
            return "";
        }
        return $rollback_sql;
    }

    /**
     * 
     * 上传block 只上传一个数字
     *  可以选择是ani or dnis
     * 然后选择carrier and trunk
     * 
     */
    public function upload_number()
    {
        $ingress_clients = $this->ResourceBlock->findIngressClient();
        $egress_clients = $this->ResourceBlock->findEgressClient();
        $ingress_trunks = $this->ResourceBlock->findAll_ingress_id();
        $egress_trunks = $this->ResourceBlock->findAll_egress_id();
        $ingress_group = $this->TrunkGroup->find('all',array('conditions' => array('trunk_type' => 0)));
        $egress_group = $this->TrunkGroup->find('all',array('conditions' => array('trunk_type' => 1)));

        $this->set('ingress_group', $ingress_group);
        $this->set('egress_group', $egress_group);
        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);

        if ($this->RequestHandler->ispost())
        {
            $path = APP . 'webroot' . DS . 'upload' . DS . 'block_list';
            $filename = trim($_POST['myfile_guid']);
            $abspath = $path . DS . $filename . ".csv";

            // some trick to avoid non-unicode chars
            $csv_str = file_get_contents($abspath);
            $csv_str = iconv("UTF-8", "UTF-8//IGNORE", trim($csv_str));
            file_put_contents($abspath, $csv_str);

            $number_type = isset($this->params['form']['number_type']) ? $this->params['form']['number_type'] : "1";
            $number_filed = "ani_prefix";
            if (!strcmp($number_type, '1'))
            {
                $number_filed = "ani_prefix";
            }
            elseif (!strcmp($number_type, '2'))
            {
                $number_filed = "digit";
            }



            $ingress_carrier_id = isset($this->params['form']['ingress_carrier']) && $this->params['form']['ingress_carrier'] != 0 ? intval($this->params['form']['ingress_carrier']) : 'null';
            $ingress_id = isset($this->params['form']['ingress']) && $this->params['form']['ingress'] != 0 ? intval($this->params['form']['ingress']) : 'null';
            $ingress_group_id = isset($this->params['form']['ingress_group_id']) && $this->params['form']['ingress_group_id'] != 0 ? intval($this->params['form']['ingress_group_id']) : 'null';
            $egress_carrier_id = isset($this->params['form']['egress_carrier']) && $this->params['form']['egress_carrier'] != 0 ? intval($this->params['form']['egress_carrier']) : 'null';
            $egress_id = isset($this->params['form']['egress']) && $this->params['form']['egress'] != 0 ? intval($this->params['form']['egress']) : 'null';
            $egress_group_id = isset($this->params['form']['egress_group_id']) && $this->params['form']['egress_group_id'] != 0 ? intval($this->params['form']['egress_group_id']) : 'null';
            $carrier_type = isset($this->params['form']['carrier_type']) ? $this->params['form']['carrier_type'] : "1";
            $type = isset($this->params['form']['type']) ? $this->params['form']['type'] : '1';

            if (!strcmp($carrier_type, '1'))
            {
                $ingress_carrier_id = 'null';
                $ingress_group_id = 'null';
                $ingress_id = 'null';

                if ($type == 1) {
                    $egress_group_id = 'null';
                } else {
                    $egress_id = 'null';
                }
            }
            else
            {
                $egress_carrier_id = 'null';
                $egress_group_id = 'null';
                $egress_id = 'null';

                if ($type == 1) {
                    $ingress_group_id = 'null';
                } else {
                    $ingress_id = 'null';
                }
            }

            $create_by = $_SESSION['sst_user_name'];
            $data = array();
            $data['ImportExportLog']['file_path'] = $abspath;
            $data ['ImportExportLog']['ext_attributes'] = array(
                'ingress_carrier_id' => $ingress_carrier_id,
                'ingress_id' => $ingress_id,
                'ingress_group_id' => $ingress_group_id,
                'egress_carrier_id' => $egress_carrier_id,
                'egress_id' => $egress_id,
                'egress_group_id' => $egress_group_id,
                'carrier_type' => $carrier_type,
                'create_by' => $create_by,
                'number_filed' => $number_filed,
                'type' => $type
            );

            $duplicate_type = strtolower(trim($this->params['form']['duplicate_type']));
            if (!in_array($duplicate_type, array('ignore', 'overwrite', 'delete', 'delete all', 'delete_all')))
            {
                $duplicate_type = 'ignore';
            }
            $user_id = 0;
            if (isset($_SESSION ['sst_user_id']))
            {
                $user_id = $_SESSION ['sst_user_id'];
            }
            $data ['ImportExportLog']['duplicate_type'] = $duplicate_type;
            $data ['ImportExportLog']['obj'] = "block list";
            $data ['ImportExportLog']['time'] = gmtnow();
            $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
            $data ['ImportExportLog']['upload_type'] = '21';
            $data ['ImportExportLog']['user_id'] = $user_id;
            $data ['ImportExportLog']['myfile_filename'] = isset($this->params['form']['myfile_filename']) ? $this->params['form']['myfile_filename'] : "";
            $error_file = $abspath . '.error';
            new File($error_file, true, 0777);

            $data ['ImportExportLog']['error_file_path'] = $error_file;

            $this->ImportExportLog->save($data);
            $log_id = $this->ImportExportLog->getLastInsertID();

            /**
             * New Import API
             */

            $php_path = Configure::read('php_exe_path');
            $cmd = "{$php_path} " . ROOT . "/cake/console/cake.php import {$log_id} > /dev/null &";

            shell_exec($cmd);

            $this->Session->write('m', $this->ImportExportLog->create_json('Import request created successfully!'));

            $this->redirect('/import_export_log/import');
            
        }
    }

}

?>
