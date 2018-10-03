<?php
class TemplateController extends AppController
{
    var $name = 'Template';
    var $uses=Array('ResourceTemplate','ResourceDirectionTemplate','ResourceNextRouteRuleTemplate','ResourceReplaceActionTemplate','RateUploadTemplate');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'common');

    public function index()
    {
        $this->redirect('resource');
    }

    /**
     * @param int $type 0:ingress 1:egress
     *
     */
    public function resource($type = 0)
    {
        if($type)
            $page_name = "Egress Trunk Template";
        else
            $page_name = "Ingress Trunk Template";
        $this->set('type',$type);
        $this->set('page_name',$page_name);
        $this->pageTitle = $page_name;
        $conditions = "trunk_type={$type}";
        $get_template_name = $this->_get('template_name');
        if($get_template_name)
        {
            $conditions .= " AND ResourceTemplate.name like '%" . trim($this->_get('template_name')) . "%'";
        }
        $pageSize = isset($_GET['size']) ? $_GET['size'] : 100;

        $order_arr = array('resource_template_id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $this->paginate = array(
            'fields' => array(
                'name','create_on','create_by','update_on','resource_template_id',
                '(select count(*) as sum from resource where resource.resource_template_id = "ResourceTemplate".resource_template_id) as used_by'
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('ResourceTemplate');
        if(empty($this->data) && !$get_template_name)
        {
            $model_name = "ResourceTemplate";
            $msg = "resource template";
            $add_url = "add_resource/{$type}";
            $this->to_add_page($model_name, $msg, $add_url);
        }
    }

    public function add_resource($type = 0,$encode_template_id = '')
    {
//        $this->ResourceTemplate->query("ALTER TABLE resource_template ADD column t38 boolean");
        $_SESSION['resource_ttype'] = $type;
        $template_id = '';
        $template_info = array();

        $direction_arr = array();
        $fail_over_rule_arr = array();
        $replace_action_arr = array();
        $page_title_type = 'Add';
        if($encode_template_id)
        {
            $page_title_type = 'Edit';
            $template_id = base64_decode($encode_template_id);
            $template_info = $this->ResourceTemplate->find('first',array('conditions' =>array('resource_template_id' => $template_id)));
            $ignore_ring_early_media = $template_info['ResourceTemplate']['ignore_ring'] == 't' ?
                ($template_info['ResourceTemplate']['ignore_early_media'] == 't' ? 1 : 2 ) : ($template_info['ResourceTemplate']['ignore_early_media'] == 't' ? 3 : 0);
            $template_info['ResourceTemplate']['ignore_ring_early_media'] = $ignore_ring_early_media;
            $direction_arr = $this->ResourceDirectionTemplate->find('all',array('conditions'=> array('resource_template_id' => $template_id)));
            $fail_over_rule_arr = $this->ResourceNextRouteRuleTemplate->find('all',array('conditions'=> array('resource_template_id' => $template_id)));
            $replace_action_arr = $this->ResourceReplaceActionTemplate->find('all',array('conditions'=> array('resource_template_id' => $template_id)));
        }
        if($type)
            $page_name = $page_title_type." Egress Trunk Template";
        else
            $page_name = $page_title_type." Ingress Trunk Template";
        $this->pageTitle = $page_name;
//        die(var_dump($template_info));
        $this->init_codes($template_id);
        $this->set('template_info',$template_info);
        $this->set('direction_arr',$direction_arr);
        $this->set('fail_over_rule_arr',$fail_over_rule_arr);
        $this->set('replace_action_arr',$replace_action_arr);
        $this->set('page_name',$page_name);
        $this->set('type',$type);

        $is_did_enable = Configure::read('did.enable');
        $this->set('is_did_enable', $is_did_enable);
        $is_enable_type = Configure::read('system.enable_trunk_type');
        $this->set('is_enable_type', $is_enable_type);
        $this->set('is_egress', true);
        $this->init_info();
        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());

        if($this->RequestHandler->ispost())
        {
            $save_data = $this->data;
            $name = $save_data['name'];
            if(!$name)
            {
                $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Failed',true)));
                $this->redirect('add_resource/'.$type."/".$encode_template_id);
            }
            // $judge_arr = array(
            //     'template_name' => $name,
            //     'template_id' => $template_id
            // );
            // if($this->judge_template_name_unique(false,$judge_arr))
            // {
            //     $error_msg = __('Template name',true)." [{$name}] ".__('already exists',true);
            //     $this->Session->write('m', $this->ResourceTemplate->create_json(101, $error_msg));
            //     $this->redirect('add_resource/'.$type."/".$encode_template_id);
            // }
//            die(var_dump($save_data));
            $save_data['codecs_str'] = !empty($save_data['select2']) ? implode(",",$save_data['select2']) : array();
            unset($save_data['select1']);
            unset($save_data['select2']);
            $now_datetime = date('Y-m-d H:i:sO');
            $save_data['create_on'] = $now_datetime;
            $save_data['update_on'] = $now_datetime;
            $save_data['create_by'] = $_SESSION['sst_user_name'];
            if($type)
                $save_data['trunk_type'] = 1;
            if($template_id)
                $save_data['resource_template_id'] = $template_id;
            if(isset($save_data['ignore_ring_early_media']))
            {
                switch ($save_data['ignore_ring_early_media'])
                {
                    case 0:
                        $save_data['ignore_ring'] = false;
                        $save_data['ignore_early_media'] = false;
                        break;
                    case 1:
                        $save_data['ignore_ring'] = true;
                        $save_data['ignore_early_media'] = true;
                        break;
                    case 2:
                        $save_data['ignore_ring'] = true;
                        $save_data['ignore_early_media'] = false;
                        break;
                    case 3:
                        $save_data['ignore_ring'] = false;
                        $save_data['ignore_early_media'] = true;
                        break;
                    default:
                        $save_data['ignore_ring'] = false;
                        $save_data['ignore_early_media'] = false;
                }
            }
            unset($save_data['ignore_ring_early_media']);
            $flg = $this->ResourceTemplate->save($save_data);
            if ($flg === false)
                $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Failed!', true)));
            else
            {
//                添加action template
                if(!$template_id)
                    $template_id = $this->ResourceTemplate->getLastInsertID();
                $this->ResourceDirectionTemplate->deleteAll(array('resource_template_id' => $template_id));
                $direction_arr = $this->params['form']['accounts'];
                if($direction_arr)
                {
                    $direction = 1;
                    if($type)
                        $direction = 2;
                    foreach ($direction_arr as $direction_key =>$direction_item)
                    {
                        $direction_arr[$direction_key]['direction'] = $direction;
                        $direction_arr[$direction_key]['resource_template_id'] = $template_id;

                        if ($direction_item['action'] == 3 || $direction_item['action'] == 4)
                            $direction_arr[$direction_key]['digits'] = $direction_item['deldigits'];

                        if(isset($direction_arr[$direction_key]['deldigits']))
                            unset($direction_arr[$direction_key]['deldigits']);
                    }
                    $this->loadModel('ResourceDirectionTemplate');
                    $direction_flg = $this->ResourceDirectionTemplate->saveAll($direction_arr);
                }

//                fail over rule
                $this->ResourceNextRouteRuleTemplate->deleteAll(array('resource_template_id' => $template_id));
                $fail_over_rule_arr = $this->params['form']['failOver'];
                if($fail_over_rule_arr)
                    foreach ($fail_over_rule_arr as $fail_over_rule_key => $fail_over_rule)
                        $fail_over_rule_arr[$fail_over_rule_key]['resource_template_id'] = $template_id;
                $fail_over_rule_flg = $this->ResourceNextRouteRuleTemplate->saveAll($fail_over_rule_arr);

//                replace action
                $this->ResourceReplaceActionTemplate->deleteAll(array('resource_template_id' => $template_id));
                $replace_action_arr = $this->params['form']['replaceAction'];
                if($replace_action_arr)
                    foreach ($replace_action_arr as $replace_action_key => $replace_action)
                        $replace_action_arr[$replace_action_key]['resource_template_id'] = $template_id;
                $replace_action_flg = $this->ResourceReplaceActionTemplate->saveAll($replace_action_arr);

                $template_name = $this->ResourceTemplate->find(array('resource_template_id' => $template_id))['ResourceTemplate']['name'];
                if($type == 0){
                    $type_name = 'Ingress';
                }else{
                    $type_name = 'Egress';
                }
                $this->Session->write('m', $this->ResourceTemplate->create_json(201, __('The ' . $type_name . ' Trunk Template [' . $template_name . '] is added successfully!', true)));
                $this->redirect('resource/'.$type);
            }
        }
    }

    public function delete_template($encode_template_id,$type)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if($encode_template_id)
        {
            $template_id = base64_decode($encode_template_id);
            $template_name = $this->ResourceTemplate->find(array('resource_template_id' => $template_id))['ResourceTemplate']['name'];

            $flg = $this->ResourceTemplate->del($template_id);
            if ($flg === false)
                $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Failed!', true)));
            else
                $this->Session->write('m', $this->ResourceTemplate->create_json(201, __('The Trunk Template [' . $template_name . '] is deleted successfully!', true)));
            $this->redirect('resource/'.$type);
        }
        else
        {
            $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Failed!', true)));
            $this->redirect('resource');
        }
    }

    function init_codes($template_id = null)
    {
        if ($template_id)
        {
            $this->set('nousecodes', $this->ResourceTemplate->findNousecodecs($template_id));
            $this->set('usecodes', $this->ResourceTemplate->findUsecodecs($template_id));
        }
        else
        {
            $this->set('nousecodes', $this->ResourceTemplate->findNousecodecs($template_id));
            $this->set('usecodes', $this->ResourceTemplate->findUsecodecs($template_id));
        }
    }

    public function init_info()
    {
        $this->loadModel('prresource.Gatewaygroup');
        $this->set('c', $this->Gatewaygroup->findClient());
        $this->set('r', $this->Gatewaygroup->findDigitMapping());
        $this->set('d', $this->Gatewaygroup->findcodecs());
        $this->set('rate', $this->Gatewaygroup->findAllRate());
        $this->set('switch_profiles', $this->Gatewaygroup->get_gateway_profiles());
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
        $this->set('route_policy', $this->Gatewaygroup->find_routepolicy());
        $this->loadModel('Blocklist');
        $reseller_id = $this->Session->read('sst_reseller_id');
        $this->set('timeprofiles', $this->Blocklist->getTimeProfiles($reseller_id));
        $this->loadModel('Client');
        $this->set('routepolicy', $this->Client->query("select * from route_strategy ORDER by name asc"));
        $this->set('default_timeout', $this->Gatewaygroup->getTimeout());
        $this->loadModel('RandomAniTable');
        $this->set('random_table', $this->RandomAniTable->find_all());
    }

    public function judge_template_name_unique($is_ajax = true,$template_arr = array())
    {
        if($is_ajax)
        {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->autoLayout = false;
            $name = $_POST['template_name'];
            $template_id = $_POST['template_id'];
            $conditions = array(
                'name' => $name,
                'trunk_type' => $_SESSION['resource_ttype'],
            );
            unset($_SESSION['resource_ttype']);
            if($template_id)
                $conditions['resource_template_id != ?'] = $template_id;
            $data = $this->ResourceTemplate->find('count',array(
                'conditions' => $conditions,
            ));
            echo json_encode($data);
        }
        else
        {
            if(empty($template_arr))
                return 1;
            $name = $template_arr['template_name'];
            if(!$name)
                return 1;
            $template_id = $template_arr['template_id'];
            $conditions = array(
                'name' => $name,
            );
            if($template_id)
                $conditions['resource_template_id != ?'] = $template_id;
            $data = $this->ResourceTemplate->find('count',array(
                'conditions' => $conditions,
            ));
            return $data;

        }
    }


    public function add_resource_template_by_trunk()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $resource_id  = $this->params['form']['resource_id'];
        $template_name = $this->params['form']['template_name'];
        $trunk_type = $this->params['form']['trunk_type'];
        if(!$resource_id || !$template_name)
        {
            $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Failed!', true)));
            echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            return ;
        }
        $create_by = $_SESSION['sst_user_name'];
        $create_on = date('Y-m-d H:i:s');
        $update_on = $create_on;
        $codecs_arr = $this->ResourceTemplate->query("select codec_id FROM resource_codecs_ref WHERE resource_id =134");
        $tmp_codecs_arr = array();
        foreach ($codecs_arr as $codecs_item)
        {
            $tmp_codecs_arr[] = $codecs_item[0]['codec_id'];
        }
        $codecs_str = '';
        if($tmp_codecs_arr)
        {
            $codecs_str = implode(",",$tmp_codecs_arr);
        }

        $resource_template_column_sql = "select column_name from information_schema.columns where
table_name = 'resource_template'";
        $resource_template_columns = $this->ResourceTemplate->query($resource_template_column_sql);
        $ignore_arr = array(
            'resource_template_id','name','create_by','update_on','create_on','trunk_type','codecs_str'
        );
        $fields_value_arr = "";
        foreach ($resource_template_columns as $resource_template_column)
        {
            if(in_array($resource_template_column[0]['column_name'],$ignore_arr))
                continue;
            $fields_value_arr[] = $resource_template_column[0]['column_name'];
        }
        unset($ignore_arr[0]);
        $fields_arr = array_merge($fields_value_arr,$ignore_arr);
        $other_value = "'{$template_name}','{$create_by}','{$update_on}','{$create_on}',$trunk_type,'{$codecs_str}'";
        $fields_str = implode(",",$fields_arr);
        $fields_value_str = implode(",",$fields_value_arr);
        $insert_template_sql = "INSERT INTO resource_template ({$fields_str}) SELECT $fields_value_str,{$other_value} FROM resource
WHERE resource_id = {$resource_id} returning resource_template_id";
        $this->ResourceTemplate->begin();
        $flg = $this->ResourceTemplate->query($insert_template_sql);
        if($flg === false)
        {
            $this->ResourceTemplate->rollback();
            $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Faileds!', true)));
            echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            return ;
        }
        $resource_template_id = $flg[0][0]['resource_template_id'];

//        添加action resource_direction_template表
        $action_fields_base = "direction,action,digits,dnis,time_profile_id,type,number_length,number_type";
        $action_fields_str = $action_fields_base . ",resource_template_id";
        $action_fields_value_str = $action_fields_base .",'{$resource_template_id}'";
        $insert_direction_sql ="INSERT INTO resource_direction_template ($action_fields_str) SELECT {$action_fields_value_str}
         FROM resource_direction WHERE resource_id = {$resource_id}";
        $flg = $this->ResourceTemplate->query($insert_direction_sql);
        if($flg === false)
        {
            $this->ResourceTemplate->rollback();
            $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Faileds!', true)));
            echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            return ;
        }

//        添加Fail-over Rule resource_next_route_rule_template表
        $fail_over_rule_fields_base = "route_type,reponse_code,return_code,return_string";
        $fail_over_rule_fields_str = $fail_over_rule_fields_base . ",resource_template_id";
        $fail_over_rule_fields_value_str = $fail_over_rule_fields_base .",'{$resource_template_id}'";
        $insert_fail_over_rule_sql ="INSERT INTO resource_next_route_rule_template ($fail_over_rule_fields_str) SELECT
{$fail_over_rule_fields_value_str} FROM resource_next_route_rule WHERE resource_id = {$resource_id}";
        $flg = $this->ResourceTemplate->query($insert_fail_over_rule_sql);
        if($flg === false)
        {
            $this->ResourceTemplate->rollback();
            $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Faileds!', true)));
            echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            return ;
        }


//                添加replace action resource_replace_action_template表
        $replace_action_fields_base = "ani_prefix,ani,ani_min_length,ani_max_length,type,dnis_prefix,dnis,dnis_min_length,dnis_max_length";
        $replace_action_fields_str = $replace_action_fields_base . ",resource_template_id";
        $replace_action_fields_value_str = $replace_action_fields_base .",'{$resource_template_id}'";
        $insert_replace_action_sql ="INSERT INTO resource_replace_action_template ($replace_action_fields_str) SELECT
{$replace_action_fields_value_str} FROM resource_replace_action WHERE resource_id = {$resource_id}";
        $flg = $this->ResourceTemplate->query($insert_replace_action_sql);
        if($flg === false)
        {
            $this->ResourceTemplate->rollback();
            $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Faileds!', true)));
            echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            return ;
        }
        $this->ResourceTemplate->commit();

        $this->ResourceTemplate->query("UPDATE resource set resource_template_id = {$resource_template_id} WHERE resource_id = {$resource_id}");

        $this->Session->write('m', $this->ResourceTemplate->create_json(201, __('Successfully!', true)));
        echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        return ;

    }

    /**
     * @param $template_id
     * @param int $type  0:ingress 1:egress
     */
    public function ajax_get_resource_used($template_id,$type = 0)
    {
        Configure::write('debug', 0);
        $sql = "SELECT resource_id,alias FROM resource WHERE resource_template_id = {$template_id}";
        $data = $this->ResourceTemplate->query($sql);
        $this->set('data',$data);
        if($type)
            $this->set('function','edit_resouce_egress');
        else
            $this->set('function','edit_resouce_ingress');

    }


    public function rate_upload_template()
    {
        $this->pageTitle = 'Rate Upload Template';

        $get_template_name = $this->_get('template_name');
        $conditions = array();
        if($get_template_name)
        {
            $conditions = "name like '%" . trim($this->_get('template_name')) . "%'";
        }
        $pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 20;
        if (isset($this->params['url']['size'])) {
            $pageSize = $_SESSION['paging_row'] = intval($this->params['url']['size']);
        }
        $order_arr = array('id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $this->paginate = array(
            'fields' => array(
                'name','create_on','create_by','update_on','id',
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('RateUploadTemplate');
        if(empty($this->data) && !$get_template_name)
        {
            $model_name = "RateUploadTemplate";
            $msg = "Rate Upload template";
            $add_url = "add_rate_upload_template";
            $this->to_add_page($model_name, $msg, $add_url);
        }
    }


    public function add_rate_upload_template($encode_template_id = '')
    {
        $this->pageTitle = 'Add Rate Upload Template';
        $template_id = "";
        if($encode_template_id)
        {
            $this->pageTitle = 'Edit Rate Upload Template';

            if(!strcmp($encode_template_id,intval($encode_template_id)))
                $encode_template_id = base64_encode($encode_template_id);
            $template_id = base64_decode($encode_template_id);
        }
        $this->set('template_id',$template_id);
        if(isset($this->data['RateUploadTemplate']))
        {
            $judge_template_arr = array(
                'template_name' => $this->data['RateUploadTemplate']['name'],
                'template_id' => '',
            );
            if($template_id)
            {
                $this->data['RateUploadTemplate']['id'] = $template_id;
                $judge_template_arr['template_id'] = $template_id;
            }
            else
            {
                $this->data['RateUploadTemplate']['create_by'] = $_SESSION['sst_user_name'];
                $now = date('Y-m-d H:i:s');
                $this->data['RateUploadTemplate']['create_on'] = $now;
                $this->data['RateUploadTemplate']['update_on'] = $now;
            }

            if($this->judge_rate_upload_template_name_unique(false,$judge_template_arr))
            {
                $error_msg = __('Rate Upload Template name',true)."[{$this->data['RateUploadTemplate']['name']}]".__('already exists',true);
                $this->Session->write('m', $this->ResourceTemplate->create_json(101, $error_msg));
                $this->redirect("add_rate_upload_template/{$encode_template_id}");
            }

            $flds = $this->data['RateUploadTemplate']['header_fields'];
            $this->data['RateUploadTemplate']['header_fields'] = array();
            $this->data['RateUploadTemplate']['header_fields'] = implode(',',$flds);
            $flg = $this->RateUploadTemplate->save($this->data['RateUploadTemplate']);
            if ($flg === false)
                $this->Session->write('m', $this->RateUploadTemplate->create_json(101, __('Failed!', true)));
            else
                $this->Session->write('m', $this->RateUploadTemplate->create_json(201, __('success', true)));
            $this->redirect('rate_upload_template');
        }
        $this->loadModel('RateTable');
        $schema = $this->RateTable->default_schema;
        $fields = array_keys($schema);
        $this->set('fields',$fields);
//        echo '<pre>';var_dump($fields);die;
        $dup_method = array(
            1 => 'Delete Existing Records',
            2 => 'End-Date Existing Records',
            0 => 'End-Date All Records',
        );
        $this->set('dup_method',$dup_method);

        $gmt_input_arr = array(
            'type' => 'select',
            'label' => false,
            'div' => false,
            'options' => $this->static_gmt_arr
        );
        if(!$template_id)
            $gmt_input_arr['selected'] = $this->ResourceTemplate->get_sys_timezone();
        $this->set('gmt_input_arr',$gmt_input_arr);
        $effective_arr = array(
            'mm/dd/yyyy' => 'mm/dd/yyyy',
            'yyyy-mm-dd' => 'yyyy-mm-dd',
            'dd-mm-yyyy' => 'dd-mm-yyyy',
            'dd/mm/yyyy' => 'dd/mm/yyyy',
            'yyyy/mm/dd' => 'yyyy/mm/dd',
        );
        $this->set('effective_arr',$effective_arr);

        $code_name_match_arr = array(
            1 => 'Re-populate Country and Code Name with Selected Code Deck',
            2 => 'Re-populate Country and Code Name with Selected Code Deck if not available'
        );
        $this->set('code_name_match_arr',$code_name_match_arr);

        $dup_method_input_arr = array(
            'type' => 'radio',
            'label' => false,
            'div' => false,
            'legend' => false,
            'options' => $dup_method,
            'onclick' => 'dup_type_change(this);'
        );
        if(!$template_id)
            $dup_method_input_arr['value'] = 1;
        $this->set('dup_method_input_arr',$dup_method_input_arr);

        $this->data = array();
        if(isset($template_id))
            $this->data = $this->RateUploadTemplate->find('first',array('conditions' => array('id' => $template_id)));
            $this->data['RateUploadTemplate']['header_fields'] = explode(',',$this->data['RateUploadTemplate']['header_fields']);
    }


    public function delete_rate_upload_template($encode_template_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if($encode_template_id)
        {
            $template_id = base64_decode($encode_template_id);
            $flg = $this->RateUploadTemplate->del($template_id);
            if ($flg === false)
                $this->Session->write('m', $this->RateUploadTemplate->create_json(101, __('Failed!', true)));
            else
                $this->Session->write('m', $this->RateUploadTemplate->create_json(201, __('success', true)));
            $this->redirect('rate_upload_template');
        }
        else
        {
            $this->Session->write('m', $this->RateUploadTemplate->create_json(101, __('Failed!', true)));
            $this->redirect('resource');
        }
    }


    public function judge_rate_upload_template_name_unique($is_ajax = true,$template_arr = array())
    {
        if($is_ajax)
        {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->autoLayout = false;
            $name = $_POST['template_name'];
            $template_id = $_POST['template_id'];
            $conditions = array(
                'name' => $name,
            );
            if($template_id)
                $conditions['id != ?'] = $template_id;
            $data = $this->RateUploadTemplate->find('count',array(
                'conditions' => $conditions,
            ));
            echo json_encode($data);
        }
        else
        {
            if(empty($template_arr))
                return 1;
            $name = $template_arr['template_name'];
            if(!$name)
                return 1;
            $template_id = $template_arr['template_id'];
            $conditions = array(
                'name' => $name,
            );
            if($template_id)
                $conditions['id != ?'] = $template_id;
            $data = $this->RateUploadTemplate->find('count',array(
                'conditions' => $conditions,
            ));
            return $data;

        }
    }


    public function reapply_resource($encode_template_id,$type = 0)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if(!$encode_template_id)
        {
            $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Failed!', true)));
            $this->redirect('resource/'.$type);
        }
        $template_id = base64_decode($encode_template_id);

        $template_data = $this->ResourceTemplate->find('first',array('conditions' => array('resource_template_id' => $template_id)));
        $direction_data = $this->ResourceDirectionTemplate->find('all',array('conditions' => array('resource_template_id' => $template_id)));
        $fail_over_rule_data = $this->ResourceNextRouteRuleTemplate->find('all',array('conditions' => array('resource_template_id' => $template_id)));
        $replace_action_data = $this->ResourceReplaceActionTemplate->find('all',array('conditions' => array('resource_template_id' => $template_id)));

        $save_data = $template_data['ResourceTemplate'];
        $ignore_arr = array(
            'resource_template_id','name','create_by','update_on','create_on','trunk_type','codecs_str'
        );
        foreach ($ignore_arr as $ignore_field)
            unset($save_data[$ignore_field]);


        $resource_arr = $this->ResourceTemplate->get_used_resource($template_id);
        $this->loadModel('prresource.Gatewaygroup');
        $this->loadModel('ResourceDirection');
        $this->loadModel('ResourceNextRouteRule');
        $this->loadModel('ResourceReplaceAction');
        $this->loadModel('ResourceCodecsRef');
        foreach ($resource_arr as $resource_item)
        {
            $resource_id = $resource_item[0]['resource_id'];
            $resource_name = $resource_item[0]['alias'];
            $save_data['resource_id'] = $resource_id;
            $this->Gatewaygroup->begin();
            $flg = $this->Gatewaygroup->save($save_data);
            if($flg ===false)
            {
                $this->Gatewaygroup->rollback();
                $this->ResourceTemplate->create_json_array('', 101, sprintf(__("The trunk[%s] is re-apply failed.", true), $resource_name));
                continue;
            }

//            codecs
            $codecs_arr = explode(",",$template_data['ResourceTemplate']['codecs_str']);
            $save_codecs_data = array();
            foreach ($codecs_arr as $codec)
                $save_codecs_data[] = array('resource_id' => $resource_id,'codec_id' => $codec);

            if($save_codecs_data)
            {
                $flg = $this->ResourceCodecsRef->saveAll($save_codecs_data);
                if($flg ===false)
                {
                    $this->Gatewaygroup->rollback();
                    $this->ResourceTemplate->create_json_array('', 101, sprintf(__("The trunk[%s] is re-apply failed.", true), $resource_name));
                    continue;
                }
            }


//            Action
            $this->ResourceDirection->deleteAll(array('resource_id' => $resource_id));
            $save_direction_data = array();
            foreach ($direction_data as $direction_data_item)
            {
                $save_direction_data_item = $direction_data_item['ResourceDirectionTemplate'];
                unset($save_direction_data_item['direction_id']);
                unset($save_direction_data_item['resource_template_id']);
                $save_direction_data_item['resource_id'] = $resource_id;
                $save_direction_data[] = $save_direction_data_item;
            }
            if($save_direction_data)
            {
                $flg = $this->ResourceDirection->saveAll($save_direction_data);
                if($flg ===false)
                {
                    $this->Gatewaygroup->rollback();
                    $this->ResourceTemplate->create_json_array('', 101, sprintf(__("The trunk[%s] is re-apply failed.", true), $resource_name));
                    continue;
                }
            }

//            fail-over Rule
            $this->ResourceNextRouteRule->deleteAll(array('resource_id' => $resource_id));
            $save_fail_over_rule_data = array();
            foreach ($fail_over_rule_data as $fail_over_rule_data_item)
            {
                $save_fail_over_rule_data_item = $fail_over_rule_data_item['ResourceNextRouteRuleTemplate'];
                unset($save_fail_over_rule_data_item['id']);
                unset($save_fail_over_rule_data_item['resource_template_id']);
                $save_fail_over_rule_data_item['resource_id'] = $resource_id;
                $save_fail_over_rule_data[] = $save_fail_over_rule_data_item;
            }
            if($save_fail_over_rule_data)
            {
                $flg = $this->ResourceNextRouteRule->saveAll($save_fail_over_rule_data);
                if($flg ===false)
                {
                    $this->Gatewaygroup->rollback();
                    $this->ResourceTemplate->create_json_array('', 101, sprintf(__("The trunk[%s] is re-apply failed.", true), $resource_name));
                    continue;
                }
            }

//            replace aciton
            $this->ResourceReplaceAction->deleteAll(array('resource_id' => $resource_id));
            $save_replace_action_data = array();
            foreach ($replace_action_data as $replace_action_data_item)
            {
                $save_replace_action_data_item = $replace_action_data_item['ResourceReplaceActionTemplate'];
                unset($save_replace_action_data_item['id']);
                unset($save_replace_action_data_item['resource_template_id']);
                $save_replace_action_data_item['resource_id'] = $resource_id;
                $save_replace_action_data[] = $save_replace_action_data_item;
            }
            if($save_replace_action_data)
            {
                $flg = $this->ResourceReplaceAction->saveAll($save_replace_action_data);
                if($flg ===false)
                {
                    $this->Gatewaygroup->rollback();
                    $this->ResourceTemplate->create_json_array('', 101, sprintf(__("The trunk[%s] is re-apply failed.", true), $resource_name));
                    continue;
                }
            }
            $this->Gatewaygroup->commit();
            $this->ResourceTemplate->create_json_array('', 201, sprintf(__("The trunk[%s] is re-apply successfully.", true), $resource_name));
        }
        $this->Session->write('m', ResourceTemplate::set_validator());
        $this->redirect("resource/".$type);
    }


    public function add_resource_by_template($type = 0,$carrier_id = null, $is_portal = false)
    {
        if ($_SESSION['login_type'] == 3)
        {
            $this->redirect_denied();
        }
        $this->loadModel('prresource.Gatewaygroup');
        $this->set('clients', $this->ResourceTemplate->findClient());
        $this->set('carrier_id', $carrier_id);
        $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());

        $this->loadModel('Rate');
        $results = $this->Rate->getAllRates();
        $rate_t = [];
        foreach($results->dataArray as $item){
            $rate_t[$item[0]['rate_table_id']] = $item[0]['name'];
        }
        $this->set('rate_tables', $rate_t);

        $sql ="select product_name, id, tech_prefix,rate_table_id,route_strategy_id from product_route_rate_table ORDER BY product_name ASC ";
        $items = $this->Gatewaygroup->query($sql);

        $product_arr = array();
        $product_name_arr = array();
        foreach($items as $item){
            $product_name_arr[$item[0]['id']] = $item[0]['product_name'];
            $arr = array(
                'product_name' => $item[0]['product_name'],
                'route_strategy_id' => $item[0]['route_strategy_id'],
                'rate_table_id' => $item[0]['rate_table_id'],
                'tech_prefix' => $item[0]['tech_prefix']
            );
            $product_arr[$item[0]['id']] = $arr;
        }
        $this->set('product_arr', $product_arr);
        $this->set('product_name_arr', $product_name_arr);
        $succeed_url = "/prresource/gatewaygroups/edit_resouce_ingress/";
        $this->pageTitle = "Add Ingress by Template";
        $back_url = "prresource/gatewaygroups/add_resouce_ingress/";

        $set_template_name = __('Ingress Template Name', true);
        $set_name = __('Ingress Name', true);
        if($type)
        {
            $this->pageTitle = "Add Egress by Template";
            $succeed_url = "/prresource/gatewaygroups/edit_resouce_egress/";
            $back_url = "prresource/gatewaygroups/add_resouce_egress/";

            $set_template_name = __('Egress Template Name', true);
            $set_name = __('Egress Name', true);

            $this->set('is_egress', true);
        }


        $this->set('set_template_name',$set_template_name);
        $this->set('set_name',$set_name);
        $this->set('back_url',$back_url);
        $this->set('templates',$this->ResourceTemplate->get_resource_template($type));
        if($this->RequestHandler->ispost())
        {
            if($type){
                $return_portal = "/clients/view_egress";
            } else {
                $return_portal = "/clients/view_ingress";
            }
            $resource_template_data = $this->ResourceTemplate->find('first',array('conditions' =>
                array('resource_template_id' => $this->data['resource_template_id'])
            ));


            if(!$resource_template_data)
            {
                $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Template failed', true)));
                if($is_portal)
                    $this->redirect($return_portal);
                $this->redirect('add_resource_by_template/'.$type);
            }
            $save_data = $resource_template_data['ResourceTemplate'];
            $ignore_arr = array(
                'resource_template_id','name','create_by','update_on','create_on','trunk_type','codecs_str'
            );
            foreach ($ignore_arr as $ignore_field)
                unset($save_data[$ignore_field]);
            $save_data['update_by'] = $_SESSION['sst_user_name'];
            if($type)
                $save_data['egress'] = true;
            else
                $save_data['ingress'] = true;
            $save_data = array_merge($save_data,$this->data);


            $this->loadModel('Rate');

            $rateTableExists = $this->Rate->find('count', array('conditions' => array('rate_table_id' => $save_data['rate_table_id'])));

            if(!$rateTableExists)
            {
                $this->Session->write('m', $this->ResourceTemplate->create_json(101, "Rate Table with ID [{$save_data['rate_table_id']}] does not exists. Please check your template"));
                if($is_portal)
                    $this->redirect($return_portal);
                $this->redirect('add_resource_by_template/'.$type);
            }

            $this->Gatewaygroup->begin();
            $flg = $this->Gatewaygroup->save($save_data);

            if($flg === false)
            {
                $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Create New trunk Failed!', true)));
                if($is_portal)
                    $this->redirect($return_portal);
                $this->redirect('add_resource_by_template/'.$type);
            }
            $resource_id = $this->Gatewaygroup->getLastInsertID();

            //            codecs
            $this->loadModel('ResourceCodecsRef');
            $codecs_arr = explode(",",$resource_template_data['ResourceTemplate']['codecs_str']);
            $save_codecs_data = array();
            foreach ($codecs_arr as $codec)
                $save_codecs_data[] = array('resource_id' => $resource_id,'codec_id' => $codec);

            if($save_codecs_data)
            {
                $flg = $this->ResourceCodecsRef->saveAll($save_codecs_data);
                if($flg ===false)
                {
                    $this->Gatewaygroup->rollback();
                    $this->Session->write('m', $this->ResourceTemplate->create_json(101, __('Create codec Failed!', true)));
                    if($is_portal)
                        $this->redirect($return_portal);
                    $this->redirect('add_resource_by_template/'.$type);
                }
            }

            //Fail-over Rule
            $fail_over_rule_arr = $this->ResourceNextRouteRuleTemplate->find('all',array('conditions'=> array('resource_template_id' => $this->data['resource_template_id'])));
            $tmp = (isset($fail_over_rule_arr)) ? $fail_over_rule_arr : '';
            $size = count($tmp);

            $this->loadModel('ResourceNextRouteRule');
            if (!empty($tmp))
            {
                foreach ($tmp as $k => $el)
                {
                    //$model = new ResourceNextRouteRule;
                    unset($el['ResourceNextRouteRuleTemplate']['id']);
                    unset($el['ResourceNextRouteRuleTemplate']['resource_template_id']);
                    $this->data['ResourceNextRouteRule'][$k] = $el['ResourceNextRouteRuleTemplate'];
                    $this->data['ResourceNextRouteRule'][$k]['resource_id'] = $resource_id;

                }
                $this->ResourceNextRouteRule->saveAll($this->data['ResourceNextRouteRule']);
                //$this->data['ResourceNextRouteRule']['id'] = false;
            }

            //replace_action
            $this->loadModel('ResourceReplaceAction');
            $replace_action_arr = $this->ResourceReplaceActionTemplate->find('all',array('conditions'=> array('resource_template_id' => $this->data['resource_template_id'])));


//            if ($this->params['form']['change_type'])
//                $cnt = count($_POST['dnis_prefix']);
//            else
//                $cnt = count($_POST['ani_prefix']);
            $cnt = count($replace_action_arr);
            $insert_arr = array();
            for ($i = 0; $i < $cnt; $i++)
            {
                $ani_prefix = @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['ani_prefix'];
                $ani = @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['ani'];
                $ani_min_length = @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['ani_min_length'];
                $ani_max_length = @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['ani_max_length'];

                $dnis_prefix = @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['dnis_prefix'];
                $dnis = @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['dnis'];
                $dnis_min_length = @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['dnis_min_length'];
                $dnis_max_length = @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['dnis_max_length'];
                $change_type = (int) @$replace_action_arr[$i]['ResourceReplaceActionTemplate']['type'];
                if ($change_type != 1)
                {//ani 必须有
                    if (empty($ani_prefix) || empty($ani))
                    {
                        continue;
                    }
                }
                elseif ($change_type != 0)
                {//dnis 必须有
                    if (empty($dnis_prefix) || empty($dnis))
                    {
                        continue;
                    }
                }
                $insert_arr[] = array(
                    'resource_id' => $resource_id,
                    'ani_prefix' => $ani_prefix,
                    'ani' => $ani,
                    'ani_min_length' => $ani_min_length,
                    'ani_max_length' => $ani_max_length,
                    'dnis_prefix' => $dnis_prefix,
                    'dnis' => $dnis,
                    'dnis_min_length' => $dnis_min_length,
                    'dnis_max_length' => $dnis_max_length,
                    'type' => $change_type
                );
            }
            $flg = $this->ResourceReplaceAction->saveAll($insert_arr);

            //action
            $list_gress = $this->Gatewaygroup->query("select egress,ingress  from  resource  where resource_id=$resource_id");
            if (!empty($list_gress[0][0]['egress']))
            {
                $direction = '2';
            }
            else
            {
                $direction = '1';
            }

            $direction_arr = $this->ResourceDirectionTemplate->find('all',array('conditions'=> array('resource_template_id' => $this->data['resource_template_id'])));
            $tmp = $direction_arr;
            
            if ($tmp)
            {
                foreach ($tmp as $el)
                {
                    $time_profile_id = isset($el['ResourceDirectionTemplate']['time_profile_id']) ? $el['ResourceDirectionTemplate']['time_profile_id'] : 'null';
                    $type = isset($el['ResourceDirectionTemplate']['type']) ? $el['ResourceDirectionTemplate']['type'] : '0';
                    $dnis = $el['ResourceDirectionTemplate']['dnis'];
                    $action = isset($el['ResourceDirectionTemplate']['action']) ? $el['ResourceDirectionTemplate']['action'] : '1';
                    $digits = $el['ResourceDirectionTemplate']['digits'];
                    if ($action == 3 || $action == 4)
                    {
                        $digits = $el['ResourceDirectionTemplate']['deldigits'];
                    }
                    $number_type = isset($el['ResourceDirectionTemplate']['number_type']) ? $el['ResourceDirectionTemplate']['number_type'] : '0';
                    if (empty($time_profile_id))
                    {
                        $time_profile_id = 'null';
                    }
                    if ($number_type == '0')
                    {
                        $number_length = $el['ResourceDirectionTemplate']['number_length'] ? $el['ResourceDirectionTemplate']['number_length'] : "0";
                        $data = "";
                        $data = $this->Gatewaygroup->query("insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)
						  values($direction,$resource_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',0,$number_length) returning *");
                        if (!$data)
                        {
                            $this->Gatewaygroup->rollback();
                            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 101, 'Failed');
                            $this->Session->write("m", Gatewaygroup::set_validator());
                            if($is_portal)
                                $this->redirect($return_portal);
                            $this->redirect("/prresource/gatewaygroups/add_direction/" . base64_encode($resource_id));
                        }
                    }
                    else
                    {
                        $number_length = $el['ResourceDirectionTemplate']['number_length'] ? $el['ResourceDirectionTemplate']['number_length'] : "0";
                        $insert_data = "";
                        $insert_data = $this->Gatewaygroup->query("insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)
						  values($direction,$resource_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',$number_type,$number_length) returning *");
                        echo "insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)
						  values($direction,$resource_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',$number_type,$number_length) returning *";

                    }
                }
            }


            $account = array_keys_value($this->params, 'form.accounts');
            if($account)
                $this->Gatewaygroup->saveHost($account,$resource_id,$type === 1);
//            die(var_dump($_POST['resource'], $resource_id));
//            if(isset($_POST['resource']))
//                $this->saveResouce($_POST['resource'], $resource_id);
            $this->Gatewaygroup->commit();
            $this->Session->write('m', $this->RateUploadTemplate->create_json(201, __('success', true)));
            if($is_portal)
                $this->redirect($return_portal);
            $this->redirect($succeed_url . base64_encode($resource_id));
        }
    }



}