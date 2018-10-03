<?php

class RateGenerationController extends AppController
{
    var $name = "RateGeneration";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common','form');
    var $components = array('RequestHandler');
    var $uses = array('RateGenerationTemplate', 'RateGenerationTemplateMargin', 'RateGenerationTemplateDetail', 'RateGenerationHistory', 'RateGenerationHistoryDetail', 'Systemparam', 'RateUploadTask');
    var $rollback = false;

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect('rate_template');
    }

    public function rate_template()
    {
        $this->paginate = array(
            'fields' => array(),
            'limit' => isset($_GET['size']) ? $_GET['size']: 100,
            'order' => array('name' => 'asc')
        );
        $this->data = $this->paginate('RateGenerationTemplate');
        if (empty($this->data))
        {
            $msg = "Rate Generation Template";
            $add_url = "add_rate_template";
            $model_name = "RateGenerationTemplate";
            $this->to_add_page($model_name,$msg,$add_url);
        }
    }

    public function delete_rate_template($encode_id)
    {
//        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($encode_id);
        $rate_generation_template = $this->RateGenerationTemplate->findById($id);
        if (!isset($rate_generation_template['RateGenerationTemplate']))
            $this->RateGenerationTemplate->create_json_array('', 101, __('Delete failed!', true));
        else
        {
            $flg = $this->RateGenerationTemplate->del($id);
            if ($flg === false)
                $this->RateGenerationTemplate->create_json_array('', 101, __('Delete failed!', true));
            else
                $this->RateGenerationTemplate->create_json_array('', 201, __('The %s[%s] is deleted successfully!', true,array(__('rate_template',true),$rate_generation_template['RateGenerationTemplate']['name'])));
        }
        $this->Session->write('m', RateGenerationTemplate::set_validator());
        $this->redirect('index');
    }

    private function get_rate_table_id($rate_generation_template_data)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $post_data = $this->params['form'];
        $rate_table_name = $post_data['name'] . "_Virtual_Rate_Table";
        if ($rate_generation_template_data['RateGenerationTemplate']['virtual_rate_table_id'])
        {
            $rate_table_id = $rate_generation_template_data['RateGenerationTemplate']['virtual_rate_table_id'];
            $judge_sql = "SELECT name FROM rate_table WHERE rate_table_id = $rate_table_id AND is_virtual = true";
            $data = $this->RateGenerationTemplate->query($judge_sql);
            if (!isset($data[0][0]['name']))
            {
                $sys_currency_result = $this->RateGenerationTemplate->query("SELECT sys_currency FROM system_parameter limit 1");
                $insert_rate_table_sql = "INSERT INTO rate_table (name,update_by,jur_type,is_virtual,currency_id) VALUES "
                    . "('{$rate_table_name}','{$_SESSION['sst_user_name']}',{$post_data['rate_table_type']},true,{$sys_currency_result[0][0]['sys_currency']}) RETURNING rate_table_id";
                $rate_table_arr = $this->RateGenerationTemplate->query($insert_rate_table_sql);
                if ($rate_table_arr === false)
                {
                    $this->RateGenerationTemplate->rollback();
                    $this->RateGenerationTemplate->create_json_array('', 101, __('add failed!', true));
                    $this->Session->write('m', RateGenerationTemplate::set_validator());
                    $this->redirect('index');
                }
                $rate_table_id = $rate_table_arr[0][0]['rate_table_id'];
                $rate_sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES "
                    . "($rate_table_id, '0', 0, 6, 6, 0, 0),($rate_table_id, '1', 0, 6, 6, 0, 0),($rate_table_id, '2', 0, 6, 6, 0, 0)"
                    . ",($rate_table_id, '3', 0, 6, 6, 0, 0),($rate_table_id, '4', 0, 6, 6, 0, 0),($rate_table_id, '5', 0, 6, 6, 0, 0)"
                    . ",($rate_table_id, '6', 0, 6, 6, 0, 0),($rate_table_id, '7', 0, 6, 6, 0, 0),($rate_table_id, '8', 0, 6, 6, 0, 0)"
                    . ",($rate_table_id, '9', 0, 6, 6, 0, 0)";
                $this->RateGenerationTemplate->query($rate_sql);
            }
            elseif (isset($data[0][0]['name']) && strcmp($rate_table_name, $data[0][0]['name']))
                $this->RateGenerationTemplate->query("UPDATE rate_table SET name = '$rate_table_name' WHERE rate_table_id = $rate_table_id");
        }
        else
        {
            $sys_currency_result = $this->RateGenerationTemplate->query("SELECT sys_currency FROM system_parameter limit 1");
            $insert_rate_table_sql = "INSERT INTO rate_table (name,update_by,jur_type,is_virtual,currency_id) VALUES "
                . "('{$rate_table_name}','{$_SESSION['sst_user_name']}',{$post_data['rate_table_type']},true,{$sys_currency_result[0][0]['sys_currency']}) RETURNING rate_table_id";
            $rate_table_arr = $this->RateGenerationTemplate->query($insert_rate_table_sql);
            if ($rate_table_arr === false)
            {
                $this->RateGenerationTemplate->rollback();
                $this->RateGenerationTemplate->create_json_array('', 101, __('add failed', true));
                $this->Session->write('m', RateGenerationTemplate::set_validator());
                $this->redirect('index');
            }
            $rate_table_id = $rate_table_arr[0][0]['rate_table_id'];
            $rate_sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES "
                . "($rate_table_id, '0', 0, 6, 6, 0, 0),($rate_table_id, '1', 0, 6, 6, 0, 0),($rate_table_id, '2', 0, 6, 6, 0, 0)"
                . ",($rate_table_id, '3', 0, 6, 6, 0, 0),($rate_table_id, '4', 0, 6, 6, 0, 0),($rate_table_id, '5', 0, 6, 6, 0, 0)"
                . ",($rate_table_id, '6', 0, 6, 6, 0, 0),($rate_table_id, '7', 0, 6, 6, 0, 0),($rate_table_id, '8', 0, 6, 6, 0, 0)"
                . ",($rate_table_id, '9', 0, 6, 6, 0, 0)";
            $this->RateGenerationTemplate->query($rate_sql);
        }
        return $rate_table_id;
    }

    private function get_dy_route_id($rate_generation_template_data)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if ($rate_generation_template_data['RateGenerationTemplate']['virtual_dy_route_id'])
        {
            $post_data = $this->params['form'];
            $dynamic_route_name = $post_data['name'] . "_Virtual_Dynamic_Routing";
            $dy_route_id = $rate_generation_template_data['RateGenerationTemplate']['virtual_dy_route_id'];
            $judge_sql = "SELECT name FROM dynamic_route WHERE dynamic_route_id = $dy_route_id AND is_virtual = true";
            $data = $this->RateGenerationTemplate->query($judge_sql);
            if (!isset($data[0][0]['name']))
                $dy_route_id = $this->create_dy_route();
            elseif (isset($data[0][0]['name']) && strcmp($dynamic_route_name, $data[0][0]['name']))
                $this->RateGenerationTemplate->query("UPDATE dynamic_route SET name = '$dynamic_route_name' WHERE dynamic_route_id = $dy_route_id");
        }
        else
            $dy_route_id = $this->create_dy_route();
        return $dy_route_id;
    }

    private function create_dy_route()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $post_data = $this->params['form'];
        $dynamic_route_name = $post_data['name'] . "_Virtual_Dynamic_Routing";
        $insert_dy_route_sql = "INSERT INTO dynamic_route (name,update_by,is_virtual) VALUES "
            . "('{$dynamic_route_name}','{$_SESSION['sst_user_name']}',true) RETURNING dynamic_route_id";
        $dy_route_arr = $this->RateGenerationTemplate->query($insert_dy_route_sql);
        if ($dy_route_arr === false)
        {
            $this->RateGenerationTemplate->rollback();
            $this->RateGenerationTemplate->create_json_array('', 101, __('add failed', true));
            $this->Session->write('m', RateGenerationTemplate::set_validator());
            $this->redirect('index');
        }
        return $dy_route_arr[0][0]['dynamic_route_id'];
    }

    private function get_route_plan_id($rate_generation_template_data)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if ($rate_generation_template_data['RateGenerationTemplate']['virtual_route_plan_id'])
        {
            $post_data = $this->params['form'];
            $route_plan_name = $post_data['name'] . "_Virtual_Routing_Plan";
            $route_plan_id = $rate_generation_template_data['RateGenerationTemplate']['virtual_route_plan_id'];
            $judge_sql = "SELECT name FROM route_strategy WHERE route_strategy_id = $route_plan_id AND is_virtual = true";
            $data = $this->RateGenerationTemplate->query($judge_sql);
            if (!isset($data[0][0]['name']))
                $route_plan_id = $this->create_route_plan();
            elseif (isset($data[0][0]['name']) && strcmp($route_plan_name, $data[0][0]['name']))
                $this->RateGenerationTemplate->query("UPDATE route_strategy SET name = '$route_plan_name' WHERE route_strategy_id = $route_plan_id");
        }
        else
            $route_plan_id = $this->create_route_plan();
        return $route_plan_id;
    }

    private function create_route_plan()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $post_data = $this->params['form'];
        $route_plan_name = $post_data['name'] . "_Virtual_Routing_Plan";
        $insert_route_plan_sql = "INSERT INTO route_strategy (name,update_by,is_virtual) VALUES "
            . "('{$route_plan_name}','{$_SESSION['sst_user_name']}',true) RETURNING route_strategy_id";
        $route_plan_arr = $this->RateGenerationTemplate->query($insert_route_plan_sql);
        if ($route_plan_arr === false)
        {
            $this->RateGenerationTemplate->rollback();
            $this->RateGenerationTemplate->create_json_array('', 101, __('add failed', true));
            $this->Session->write('m', RateGenerationTemplate::set_validator());
            $this->redirect('index');
        }
        return $route_plan_arr[0][0]['route_strategy_id'];
    }

    private function judge_route_exsit($dy_route_id, $route_plan_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $judge_sql = "SELECT count(*) AS sum FROM route WHERE dynamic_route_id = $dy_route_id AND route_strategy_id = $route_plan_id";
        $sum = $this->RateGenerationTemplate->query($judge_sql);
        if ($sum[0][0]['sum'])
            return true;
        else
            return false;
    }

    private function create_route($dy_route_id, $route_plan_id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $insert_route_sql = "INSERT INTO route (dynamic_route_id,route_type,route_strategy_id,update_by) VALUES "
            . "({$dy_route_id},1,{$route_plan_id},'{$_SESSION['sst_user_name']}')";
        if ($this->RateGenerationTemplate->query($insert_route_sql) === false)
        {
            $this->RateGenerationTemplate->rollback();
            $this->RateGenerationTemplate->create_json_array('', 101, __('add failed!', true));
            $this->Session->write('m', RateGenerationTemplate::set_validator());
            $this->redirect('index');
        }
    }

    private function get_ingress_id($rate_generation_template_data, $route_plan_id, $rate_table_id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if ($rate_generation_template_data['RateGenerationTemplate']['virtual_ingress_id'])
        {
            $post_data = $this->params['form'];
            $ingress_name = $post_data['name'] . "_Virtual_Ingress";
            $ingress_id = $rate_generation_template_data['RateGenerationTemplate']['virtual_ingress_id'];
            $judge_sql = "SELECT alias FROM resource WHERE resource_id = $ingress_id AND is_virtual = true AND "
                . "route_strategy_id = $route_plan_id AND rate_table_id = $rate_table_id AND enough_balance = true";
            $data = $this->RateGenerationTemplate->query($judge_sql);
            if (!isset($data[0][0]['alias']))
                $ingress_id = $this->create_ingress($route_plan_id, $rate_table_id, $ingress_id);
            elseif (isset($data[0][0]['alias']) && strcmp($ingress_name, $data[0][0]['alias']))
                $this->RateGenerationTemplate->query("UPDATE resource SET alias = '$ingress_name' WHERE resource_id = $ingress_id");
        }
        else
            $ingress_id = $this->create_ingress($route_plan_id, $rate_table_id);
        return $ingress_id;
    }

    private function create_ingress($route_plan_id, $rate_table_id, $ingress_id = '')
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if ($ingress_id)
            $this->RateGenerationTemplate->query("DELETE FROM resource WHERE resource_id = $ingress_id AND is_virtual = true");
        $post_data = $this->params['form'];
        $ingress_name = $post_data['name'] . "_Virtual_Ingress";
        $insert_ingress_sql = "INSERT INTO resource (alias,is_virtual,route_strategy_id,ingress,rate_table_id,enough_balance) VALUES "
            . "('{$ingress_name}',true,{$route_plan_id},true,{$rate_table_id},true) RETURNING resource_id";
        $ingress_arr = $this->RateGenerationTemplate->query($insert_ingress_sql);
        if ($ingress_arr === false)
        {
            $this->RateGenerationTemplate->rollback();
            $this->RateGenerationTemplate->create_json_array('', 101, __('add failed!', true));
            $this->Session->write('m', RateGenerationTemplate::set_validator());
            $this->redirect('index');
        }
        $ingress_id = $ingress_arr[0][0]['resource_id'];
        if ($this->RateGenerationTemplate->query("INSERT INTO resource_ip (resource_id,ip,port) VALUES($ingress_id,'1.1.1.1',5060)") === false)
        {
            $this->RateGenerationTemplate->rollback();
            $this->RateGenerationTemplate->create_json_array('', 101, __('add failed!', true));
            $this->Session->write('m', RateGenerationTemplate::set_validator());
            $this->redirect('index');
        }
        if ($this->RateGenerationTemplate->query("INSERT INTO resource_prefix (resource_id,route_strategy_id,rate_table_id) VALUES($ingress_id,$route_plan_id,$rate_table_id)") === false)
        {
            $this->RateGenerationTemplate->rollback();
            $this->RateGenerationTemplate->create_json_array('', 101, __('add failed!', true));
            $this->Session->write('m', RateGenerationTemplate::set_validator());
            $this->redirect('index');
        }
        return $ingress_id;
    }

    public function add_rate_template($encode_id = '')
    {
        if ( $this->_get('is_ajax') ){
            Configure::write('debug', 0);
        }
        $rate_table_type = array(
            0 => 'A-Z',
            1 => 'US Jurisdictional',
            2 => 'US Non Jurisdictional',
            4 => 'IJ - Inter',
            5 => 'IJ - Intra',
            6 => 'IJ - Max(Inter,Intra)',
            7 => 'IJ - True Math'

        );
        $ij_rate_type = array(
            0 => 'Inter Rate',
            1 => 'Intra Rate',
        );
        $rate_generation_template_data = array();
        $lcr_arr = array(
            '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'
        );
        if ($encode_id) {
            $id = base64_decode($encode_id);
            $rate_generation_template_data = $this->RateGenerationTemplate->find('first', array(
                'conditions' => array(
                    'RateGenerationTemplate.id' => $id
                ),
            ));
            $egress_str = $rate_generation_template_data['RateGenerationTemplate']['egress_str'];
            $egress_count = count(explode(',', $egress_str));
            $lcr_arr = array_combine(range(1,$egress_count),range(1,$egress_count));
        }
        $this->set('rate_table_type', $rate_table_type);

        $this->set('lcr_arr', $lcr_arr);
        $decimal_places_arr = array(
            '0','1', '2',  '3',  '4',  '5',  '6'
        );
        $this->set('decimal_places_arr', $decimal_places_arr);
        $effective_arr = array(
            '0','1', '2',  '3',  '4',  '5',  '6', '7'
        );
        $this->set('effective_arr', $effective_arr);
//        $this->set('egresses_info',$this->RateGenerationTemplate->get_client_egress_group());
        if ($this->RequestHandler->ispost()) {

//            var_dump($this->params['form']);die;
            if ( $this->_get('is_ajax') ) {
                $this->autoLayout = false;
                $this->autoRender = false;
            }
            $post_data = $this->params['form'];
            $this->RateGenerationTemplate->begin();
            if ($encode_id) {
                $delete_margin_flg = $this->RateGenerationTemplate->query("DELETE FROM rate_generation_template_margin WHERE rate_generation_template_id = $id");
                if ($delete_margin_flg === false) {
                    $this->RateGenerationTemplate->rollback();
                    if ( $this->_get('is_ajax') ) {
                        echo 0;
                        return;
                    }else{
                        $this->RateGenerationTemplate->create_json_array('', 101, __('Edit failed!', true));
                        $this->Session->write('m', RateGenerationTemplate::set_validator());
                        $this->redirect('index');
                    }
                }
                $delete_detail_flg = $this->RateGenerationTemplate->query("DELETE FROM rate_generation_template_detail WHERE rate_generation_template_id = $id");
                if ($delete_detail_flg === false) {
                    $this->RateGenerationTemplate->rollback();
                    if ( $this->_get('is_ajax') ) {
                        echo 0;
                        return;
                    }else{
                        $this->RateGenerationTemplate->create_json_array('', 101, __('Edit failed!', true));
                        $this->Session->write('m', RateGenerationTemplate::set_validator());
                        $this->redirect('index');
                    }
                }
            }
            $include_blocked_route = isset($post_data['include_blocked_route']) ? true : false;
            $include_local_rate = isset($post_data['include_local_rate']) ? true : false;
            $template_save_arr = array(
                'name' => $post_data['name'],
                'include_blocked_route' => $include_blocked_route,
                'rate_table_type' => $post_data['rate_table_type'],
                'lcr_digit' => $post_data['lcr_digit'],
                'default_rate' => $post_data['default_rate'],
                'margin_default_type' => $post_data['margin_default_type'],
                'margin_default_value' => $post_data['margin_default_value'],
                'default_interval' => $post_data['default_interval'],
                'default_min_time' => $post_data['default_min_time'],
                'create_on' => date("Y-m-d H:i:sO", time()),
                'create_by' => $_SESSION['sst_user_name'],
                'egress_str' => implode(";", $post_data['egress_id']),
                'include_local_rate' => $include_local_rate,
                'effective_days' => $post_data['effective_days'],
                'decimal_places' => $post_data['decimal_places']
            );

            if ($post_data['rate_table_type'] == 0 || $post_data['rate_table_type'] == 1) {
                $template_save_arr['code_deck_id'] = $post_data['code_deck_id'];
            }

            if ($encode_id) {
                $template_save_arr['id'] = $id;
            }
            $insert_flg = $this->RateGenerationTemplate->save($template_save_arr);
            if ($insert_flg === false) {
                $this->rollback = true;
                $this->RateGenerationTemplate->rollback();
                if ($encode_id)
                    $this->RateGenerationTemplate->create_json_array('', 101, __('Edit failed!', true));
                else
                    $this->RateGenerationTemplate->create_json_array('', 101, __('Save failed!', true));
                if ( $this->_get('is_ajax') ) {
                    echo 0;
                    return;
                }else{
                    $this->Session->write('m', RateGenerationTemplate::set_validator());
                    $this->redirect('index');
                }
            }
            $rate_generation_template_id = $encode_id ? $id : $this->RateGenerationTemplate->getlastinsertId();

            if (isset($post_data['margin'])) {
                $margin_save_arr = array();
                for ($i = 0; $i < count($post_data['margin']['min_rate']); $i++) {
                    $margin_save_arr[$i]['rate_generation_template_id'] = $rate_generation_template_id;
                    $margin_save_arr[$i]['min_rate'] = $post_data['margin']['min_rate'][$i];
                    $margin_save_arr[$i]['max_rate'] = $post_data['margin']['max_rate'][$i];
                    $margin_save_arr[$i]['markup_type'] = $post_data['margin']['markup_type'][$i];
                    $margin_save_arr[$i]['markup_value'] = $post_data['margin']['markup_value'][$i];
                }
                $flg = $this->RateGenerationTemplateMargin->saveAll($margin_save_arr);
                if ($flg === false) {
                    $this->rollback = true;
                    $this->RateGenerationTemplate->rollback();
                }
            }

            if (isset($post_data['interval_mintime'])) {
                $detail_save_arr = array();
                for ($j = 0; $j < count($post_data['interval_mintime']['code']); $j++) {
                    $detail_save_arr[$j]['rate_generation_template_id'] = $rate_generation_template_id;
                    $detail_save_arr[$j]['code'] = $post_data['interval_mintime']['code'][$j];
                    $detail_save_arr[$j]['rate_interval'] = $post_data['interval_mintime']['rate_interval'][$j];
                    $detail_save_arr[$j]['min_time'] = $post_data['interval_mintime']['min_time'][$j];
                }
                $flg = $this->RateGenerationTemplateDetail->saveAll($detail_save_arr);
                if ($flg === false) {
                    $this->rollback = true;
                    $this->RateGenerationTemplate->rollback();
                }
            }
            $this->RateGenerationTemplate->commit();
            if ( $this->_get('is_ajax') ) {

                if ($this->rollback)
                    echo 0;
                else
                    echo $rate_generation_template_id;
                return;
            }
            else
            {
                if ($this->rollback)
                    $this->RateGenerationTemplate->create_json_array('', 101, __('Failed!', true));
                else
                    $this->RateGenerationTemplate->create_json_array('', 201, __('Rate Template created successfully!', true));
                $this->Session->write('m', RateGenerationTemplate::set_validator());
                $this->redirect('index');
            }
        }
        $rate_generation_template_margin_data = array();
        $rate_generation_template_detail_data = array();
        if ($encode_id) {
            $rate_generation_template_margin_data = $this->RateGenerationTemplateMargin->find('all', array('conditions' => array('RateGenerationTemplateMargin.rate_generation_template_id' => $id)));

            $rate_generation_template_detail_data = $this->RateGenerationTemplateDetail->find('all', array('conditions' => array('RateGenerationTemplateDetail.rate_generation_template_id' => $id)));
        }

        $this->set('code_deck', $this->RateGenerationTemplateDetail->find_code_deck());
        $this->set('egresses_info', $this->RateGenerationTemplate->get_with_rate_egress());
        $this->set('data', $rate_generation_template_data);
        $this->set('margin_data', $rate_generation_template_margin_data);
        $this->set('detail_data', $rate_generation_template_detail_data);
        $this->set('ij_rate_type', $ij_rate_type);
    }

    public function judge_template_name($name, $encode_id = '')
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $conditions = array(
            'name' => $name
        );
        if ($encode_id)
        {
            $conditions[] = "id !=" . base64_decode($encode_id);
        }
        $data = $this->RateGenerationTemplate->find('first', array('conditions' => $conditions));
        if ($data === false)
        {
            if (isset($this->params['form']['is_ajax']))
                echo 0;
            else
                return 0;
        }
        else
        {
            if (isset($this->params['form']['is_ajax']))
                echo 1;
            else
                return 1;
        }
    }

    public function rate_generation_history($encode_template_id)
    {
        $id = base64_decode($encode_template_id);
        if (!$id)
            $this->redirect('rate_template');
        $data = $this->RateGenerationHistory->find('all',  array('limit' => isset($_GET['size']) ? $_GET['size'] : 100 ,'conditions' => array('rate_generation_template_id' => $id), 'order' => 'id desc'));
        $this->set('status', array('0' => 'Wating', '1' => 'In Progress', '2' => 'Completed'));
        $this->set("data", $data);
    }

    public function rate_generation_history_detail($encode_template_id, $encode_history_id)
    {
        $id = base64_decode($encode_history_id);
        $data = $this->RateGenerationHistoryDetail->find('all', array(
                'fields' => array(
                    'RateGenerationHistoryDetail.effective_date_new',
                    'RateGenerationHistoryDetail.effective_date_increase',
                    'RateGenerationHistoryDetail.effective_date_decrease',
                    'RateGenerationHistoryDetail.is_send_mail',
                    'RateGenerationHistoryDetail.end_date',
                    'RateGenerationHistoryDetail.create_on',
                    'RateGenerationHistoryDetail.create_by',
                    'RateGenerationHistoryDetail.finished_time',
                    'RateGenerationHistoryDetail.end_date_method',
                    'RateTable.name',
                    'RateEmailTemplate.name'
                ),
                'conditions' => array('rate_generation_history_id' => $id),
                'joins' => array(
                    array(
                        'table' => 'rate_table',
                        'alias' => "RateTable",
                        'type' => 'LEFT',
                        'conditions' => array(
                            'RateTable.rate_table_id = RateGenerationHistoryDetail.rate_table_id',
                        ),
                    ),
                    array(
                        'table' => 'rate_email_template',
                        'alias' => "RateEmailTemplate",
                        'type' => 'LEFT',
                        'conditions' => array(
                            'RateEmailTemplate.id = RateGenerationHistoryDetail.email_template_id',
                        ),
                    ),
                ),
                'order' => 'RateGenerationHistoryDetail.id desc',
            )
        );
        $this->set("data", $data);
        $end_date_method = array(
            1   => 'Duplicated Codes Only',
            2   =>  'Code with Rate Changed Only',
            3   =>  'All Codes'
        );
        $this->set('end_date_method',$end_date_method);
    }

    function file_mode_info($file_path)
    {
        /* 如果不存在，则不可读、不可写、不可改 */
        if (!file_exists($file_path))
        {
            return false;
        }
        $mark = 0;
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
        {
            /* 测试文件 */
            $test_file = $file_path . '/cf_test.txt';
            /* 如果是目录 */
            if (is_dir($file_path))
            {
                /* 检查目录是否可读 */
                $dir = @opendir($file_path);
                if ($dir === false)
                {
                    return $mark; //如果目录打开失败，直接返回目录不可修改、不可写、不可读
                }
                if (@readdir($dir) !== false)
                {
                    $mark ^= 1; //目录可读 001，目录不可读 000
                }
                @closedir($dir);
                /* 检查目录是否可写 */
                $fp = @fopen($test_file, 'wb');
                if ($fp === false)
                {
                    return $mark; //如果目录中的文件创建失败，返回不可写。
                }
                if (@fwrite($fp, 'directory access testing.') !== false)
                {
                    $mark ^= 2; //目录可写可读011，目录可写不可读 010
                }
                @fclose($fp);
                @unlink($test_file);
                /* 检查目录是否可修改 */
                $fp = @fopen($test_file, 'ab+');
                if ($fp === false)
                {
                    return $mark;
                }
                if (@fwrite($fp, "modify test.\r\n") !== false)
                {
                    $mark ^= 4;
                }
                @fclose($fp);
                /* 检查目录下是否有执行rename()函数的权限 */
                if (@rename($test_file, $test_file) !== false)
                {
                    $mark ^= 8;
                }
                @unlink($test_file);
            }
            /* 如果是文件 */
            elseif (is_file($file_path))
            {
                /* 以读方式打开 */
                $fp = @fopen($file_path, 'rb');
                if ($fp)
                {
                    $mark ^= 1; //可读 001
                }
                @fclose($fp);
                /* 试着修改文件 */
                $fp = @fopen($file_path, 'ab+');
                if ($fp && @fwrite($fp, '') !== false)
                {
                    $mark ^= 6; //可修改可写可读 111，不可修改可写可读011...
                }
                @fclose($fp);
                /* 检查目录下是否有执行rename()函数的权限 */
                if (@rename($test_file, $test_file) !== false)
                {
                    $mark ^= 8;
                }
            }
        }
        else
        {
            if (@is_readable($file_path))
            {
                $mark ^= 1;
            }
            if (@is_writable($file_path))
            {
                $mark ^= 14;
            }
        }
        return $mark;
    }

    public function add_rate_generation_history_detail($encode_template_id, $encode_history_id)
    {
        $this->set('rate_table', $this->RateGenerationHistoryDetail->find_all_rate_table());
        $this->set('rate_email_template', $this->RateGenerationHistoryDetail->find_all_rate_email_template());
        $templateId = base64_decode($encode_template_id);
        $rateGenerationTemplate = $this->RateGenerationTemplate->find('first', array(
            'fields' => array('rate_table_type', 'ij_rate_type'),
            'conditions' => array(
                'id' => $templateId
            )
        ));
        $rate_generation_history_id = base64_decode($encode_history_id);
        $handle_dir = ROOT . "/../script/storage/rate_generation/";
        $check_dir = $this->file_mode_info($handle_dir);
//        if ($check_dir != 15)
//        {
//            $this->RateGenerationHistoryDetail->create_json_array('', 101, __('Rate Generation Permission denied!', true));
//            $this->Session->write('m', RateGenerationHistoryDetail::set_validator());
//            $this->redirect('rate_template');
//        }

        $end_date_method = array(
            0   =>  'None',
            3   =>  'All Codes',
        );
        $this->set('is_us',true);

//        $history_info = $this->RateGenerationHistory->findById($rate_generation_history_id);
//        if($history_info['RateGenerationHistory']['rate_table_type']){
//            $this->set('is_us',true);
//            $end_date_method = array(
//                0   =>  'None',
//                3   =>  'All Codes',
//            );
//        }  else {
//            $this->set('is_us', false);
//            $end_date_method = array(
//                1   => 'Duplicated Codes Only',
//                2   =>  'Code with Rate Changed Only',
//                3   =>  'All Codes',
//                4   =>  'Code with no new rate'
//            );
//        }

        $this->set('end_date_method',$end_date_method);
        if ($this->RequestHandler->ispost())
        {
            $post_data = $this->params['form'];
            $send = isset($post_data['send']) ? true : false;
            $insert_data = array();
            $rand_flg = md5($this->create_randon_str(1));
            for ($i = 0; $i < count($post_data['rate_table_id']); $i ++)
            {
                $insert_data[$i]['create_on'] = date('Y-m-d H:i:sO');
                $insert_data[$i]['create_by'] = $_SESSION['sst_user_name'];
                $insert_data[$i]['rate_generation_history_id'] = $rate_generation_history_id;
                $insert_data[$i]['rate_table_id'] = $post_data['rate_table_id'][$i];
                $insert_data[$i]['effective_date_new'] = $post_data['effective_date_new'][$i];
                $insert_data[$i]['effective_date_increase'] = $post_data['effective_date_increase'][$i];
                $insert_data[$i]['effective_date_decrease'] = $post_data['effective_date_decrease'][$i];
                $insert_data[$i]['is_send_mail'] = $post_data['is_send_mail'][$i];
                $insert_data[$i]['end_date'] = $post_data['end_date'][$i];
                $insert_data[$i]['email_template_id'] = $post_data['email_template_id'][$i];
                $insert_data[$i]['end_date_method'] = $post_data['end_date_method'][$i];
                $insert_data[$i]['rand_flg'] = $rand_flg;
            }
            $flg = $this->RateGenerationHistoryDetail->saveAll($insert_data);
            if ($flg === false)
                $this->RateGenerationHistoryDetail->create_json_array('', 101, __('Failed!', true));
            else
            {
                $info = $this->Systemparam->find('first',array(
                    'fields' => array('cmd_debug'),
                ));
                if(Configure::read('cmd.debug'))
                {
                    file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
                }

                $configPath = Configure::read('database_export_path');

                // $pid = shell_exec($cmd);
                $filename = uniqid('rate') . '.csv';
                $upload = $configPath;
                $uploadPath = $configPath . DS . $filename;

                // Calculate IJ Rate based on
                if($encode_template_id && $rate_generation_history_id) {
                    $temp_id = base64_decode($encode_template_id);
                    $temp_data = $this->RateGenerationTemplate->find('first',array(
                        'fields' => array('rate_table_type', 'ij_rate_type'),
                        'conditions' => array(
                            'id' => $temp_id
                        ),
                    ));
                    $rate_table_type = $temp_data['RateGenerationTemplate']['rate_table_type'];
                    $ij_rate_type = $temp_data['RateGenerationTemplate']['ij_rate_type'];
                    // US JD
                    if($rate_table_type == 1){
                        $this->loadModel('RateGenerationRate');
                        $update_field = $ij_rate_type == 1 ? 'intra_rate': 'inter_rate';
                        $sql = "UPDATE rate_generation_rate set rate=$update_field WHERE rate_generation_history_id=$rate_generation_history_id";
                        $this->RateGenerationRate->query($sql);
                    }

                }

                $sql = "\COPY (SELECT distinct on (code) code, country, inter_rate, intra_rate, code_name,'{$post_data['effective_date_new'][0]}' as effective_date, rate, setup_fee, end_date, min_time, grace_time, interval, time_profile_id, seconds FROM rate_generation_rate WHERE rate_generation_history_id = {$rate_generation_history_id}) TO '{$uploadPath}' With HEADER CSV DELIMITER ','";
                $this->RateGenerationHistoryDetail->_get_psql_cmd($sql);
                $saveData = array(
                    'operator_user' => 'admin',
                    'upload_file_path' => $upload,
                    'upload_orig_file' => $filename,
                    'upload_format_file' => $filename,
                    'rate_table_id' => $_POST['rate_table_id'][0],
                    'rate_date_format' => 'yyyy-mm-dd',
                    'reduplicate_rate_action' =>  0,
                    'code_deck_flag' => 0,
                    'use_ocn_lata_code' => 0,
                    'status' => 0,
                    'create_time' => time(),
                    'all_rate_end_date' => $post_data['end_date'][0]
                );
                $this->RateUploadTask->save($saveData);
                $this->RateGenerationHistoryDetail->create_json_array('', 201, sprintf(__('You Job [#%s] is scheduled to execute in the queue.',true)));
            }
            $this->Session->write('m', RateGenerationHistoryDetail::set_validator());
            if($send){
                $this->redirect("/rates/send_rate/".base64_encode($_POST['rate_table_id'][0]));
            }else {
                $this->redirect("rate_generation_history_detail/{$encode_template_id}/{$encode_history_id}");
            }
        }
    }

    public function do_generation($encode_template_id)
    {
        $rate_generation_template_id = base64_decode($encode_template_id);
        $data = $this->RateGenerationTemplate->find('first',array(
            'fields' => array('rate_table_type'),
            'conditions' => array(
                'id' => $rate_generation_template_id
            ),
        ));
        if ($data)
            $rate_table_type = $data['RateGenerationTemplate']['rate_table_type'];
        $insert_data = array(
            'create_by' => $_SESSION['sst_user_name'],
            'rate_generation_template_id' => base64_decode($encode_template_id),
            'status' => 0,
            'rate_table_type' => $rate_table_type
        );
        $flg = $this->RateGenerationHistory->save($insert_data);
        if ($flg === false)
            $this->RateGenerationHistory->create_json_array('', 101, __('Failed!', true));
        else
        {
//            $history_id = $this->RateGenerationHistory->getLastInsertID();
//
//            $loaded_extensions = get_loaded_extensions();
//            if (!in_array('redis', $loaded_extensions))
//            {
//                $this->RateGenerationHistory->create_json_array('', 101, __('Failed!', true));
//                $this->Session->write("m", RateGenerationHistory::set_validator());
//                $this->redirect("rate_template");
//            }
//            $redis = new Redis();
//            if (!$redis->connect(Configure::read('redis.host'), Configure::read('redis.port')))
//            {
//                $this->RateGenerationHistory->create_json_array('', 101, __('Failed!', true));
//                $this->Session->write("m", RateGenerationHistory::set_validator());
//                $this->redirect("rate_template");
//            }
//            $client_name = Configure::read('client.name');
//            $rate_generation_key = "class4_rate_generation[$client_name]";
//            $flg = $redis->Lpush($rate_generation_key, $history_id);
            $this->RateGenerationHistory->create_json_array('#query-smartPeriod', 201, __("The Backend Job is in the queue!", true));
        }
        $this->Session->write('m', RateGenerationHistory::set_validator());
        $this->redirect("rate_generation_history/{$encode_template_id}");
    }


    public function view_rate_result($encode_history_id)
    {
        $history_id = base64_decode($encode_history_id);
        $RateGenerationHistoryInfo = $this->RateGenerationHistory->find('first',array(
            'fields' => array('rate_table_type'),
            'conditions' => array('id' => $history_id)));
        if (!$RateGenerationHistoryInfo)
        {
            $this->Session->write('m', $this->RateGenerationHistory->create_json(201, __('Data not exists!', true)));
            if (isset($this->params['url']['template_id']))
                $this->redirect('rate_generation_history/'.$this->params['url']['template_id']);
            else
                $this->redirect('rate_template');
        }

        $lcr_digit = 1;
        if(isset($this->params['url']['template_id']) && $this->params['url']['template_id']){
            $template_id = base64_decode($this->params['url']['template_id']);

            $data = $this->RateGenerationTemplate->find('first',array(
                'fields' => array('lcr_digit'),
                'conditions' => array(
                    'id' => $template_id
                ),
            ));
            $lcr_digit = $data['RateGenerationTemplate']['lcr_digit'];
        }
        $this->set('lcr_digit', $lcr_digit);

        $this->set('rate_table_type',$RateGenerationHistoryInfo['RateGenerationHistory']['rate_table_type']);
        $this->loadModel('RateGenerationRate');

        $pageSize = $this->_get('size') ? $this->_get('size') : 20;
        $_GET['size'] = $pageSize;
        $conditions = array('rate_generation_history_id' => $history_id);

        if ($this->_get('code_search'))
        {
            $conditions['OR'] = array(
                "code::text ilike '".$this->_get('code_search')."%'",
                "code_name::text ilike '".$this->_get('code_search')."%'",
                "country::text ilike '".$this->_get('code_search')."%'",
            );
        }
//        pr($conditions);die;
        $order_arr = array('code' => 'asc');
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
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('RateGenerationRate');
        $this->set('time_profile',$this->RateGenerationHistory->find_timeprofile(true));
    }


    public function ajax_delete_result()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $this->loadModel('RateGenerationRate');
        $id_str = $this->params['form']['id_str'];
        $id_arr = explode(',',$id_str);
        foreach ($id_arr as &$id)
            $id = intval($id);

        $conditions = array(
            'generation_rate_id ' => $id_arr,
        );

        $return = array(
            'status' => 1,
            'msg' => __('Delete successfully',true),
        );
        if ($this->RateGenerationRate->deleteAll($conditions) === false)
            $return = array(
                'status' => 0,
                'msg' => __('Delete failed',true),
            );
        return json_encode($return);
    }

    public function edit_generation_rate_result()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $rate_data = $this->data['rate'];
        $this->loadModel('RateGenerationRate');
        if($this->RateGenerationRate->saveAll($rate_data) === false)
            $this->Session->write('m', $this->RateGenerationRate->create_json(101, __('Save failed',true)));
        else
            $this->Session->write('m', $this->RateGenerationRate->create_json(201, __('Save successfully',true)));
        $this->redirect('view_rate_result/'.$this->params['form']['url_params']);

    }

    public function get_apply_row()
    {
        Configure::write('debug', 0);
        $this->set('rate_table', $this->RateGenerationHistoryDetail->find_all_rate_table());
        $this->set('rate_email_template', $this->RateGenerationHistoryDetail->find_all_rate_email_template());
        if( $this->params['url']['is_us'] == "1"){
            $end_date_method = array(
                0   =>  'None',
                3   =>  'All Codes',
            );
        }  else {
            $end_date_method = array(
                1   => 'Duplicated Codes Only',
                2   =>  'Code with Rate Changed Only',
                3   =>  'All Codes',
                4   =>  'Code with no new rate'
            );
        }
        $this->set('end_date_method',$end_date_method);
    }

    public function rate_result_panel($rate_id = ''){
        $this->loadModel('RateGenerationRate');
        $data = $this->RateGenerationRate->findByGenerationRateId($rate_id);
        if ($this->RequestHandler->ispost()) {
            Configure::write('debug', 1);
            if($this->RateGenerationRate->save($this->data) === false){
                $this->Session->write('m', $this->RateGenerationRate->create_json(101, __('Save failed',true)));
            }else{
                $this->Session->write('m', $this->RateGenerationRate->create_json(201, __('Save successfully',true)));
            }
            if ($data){
                $this->redirect('view_rate_result/'.base64_encode($data['RateGenerationRate']['rate_generation_history_id']));
            }else{
                $this->redirect('rate_template');
            }
        }
        Configure::write('debug', 0);
        $rate_table_type = 0;
        if ($data){
            $rate_table_type = $data['RateGenerationRate']['rate_type'];
            $this->data = $data['RateGenerationRate'];
        }
        $this->set('rate_table_type',$rate_table_type);
        $this->set('time_profile',$this->RateGenerationHistory->find_timeprofile(true));
    }

    public function ajax_get_lcr_rate(){
        Configure::write('debug', 0);
        $rate_id = $this->_post('rate_id',0);
        $lcr_digit = $this->_post('lcr_digit',1);
        $this->set('lcr_digit',$lcr_digit);

        $this->loadModel('RateGenerationRate');
        $data = $this->RateGenerationRate->find('first',array(
            'fields' => array('lcr_rate','lcr_intra_rate','lcr_inter_rate','lcr_local_rate'),
            'conditions' => array(
                'generation_rate_id' => $rate_id,
            ),
        ));
        $return_arr =array(
            'rate' => array(),
            'inter' => array(),
            'intra' => array(),
            'local' => array(),
        );

        $rate_lcr_arr = explode(';',$data['RateGenerationRate']['lcr_rate']);
        $inter_lcr_arr = explode(';',$data['RateGenerationRate']['lcr_inter_rate']);
        $intra_lcr_arr = explode(';',$data['RateGenerationRate']['lcr_intra_rate']);
        $local_lcr_arr = explode(';',$data['RateGenerationRate']['lcr_local_rate']);

        $egress_arr = $this->RateGenerationRate->findAll_egress_id();

        foreach ($rate_lcr_arr as $rate_lcr_item){
            $result_data =  explode(':',$rate_lcr_item);
            $result_data[0] = isset($egress_arr[$result_data[0]]) ? $egress_arr[$result_data[0]] : $result_data[0];
            $return_arr['rate'][] = $result_data;
        }
        foreach ($inter_lcr_arr as $inter_lcr_item){
            $result_data =  explode(':',$inter_lcr_item);
            $result_data[0] = isset($egress_arr[$result_data[0]]) ? $egress_arr[$result_data[0]] : $result_data[0];
            $return_arr['inter'][] = $result_data;
        }
        foreach ($intra_lcr_arr as $intra_lcr_item){
            $result_data =  explode(':',$intra_lcr_item);
            $result_data[0] = isset($egress_arr[$result_data[0]]) ? $egress_arr[$result_data[0]] : $result_data[0];
            $return_arr['intra'][] = $result_data;
        }
        foreach ($local_lcr_arr as $local_lcr_item){
            $result_data =  explode(':',$local_lcr_item);
            $result_data[0] = isset($egress_arr[$result_data[0]]) ? $egress_arr[$result_data[0]] : $result_data[0];
            $return_arr['local'][] = $result_data;
        }
        $this->set('return_arr',$return_arr);

    }

}
