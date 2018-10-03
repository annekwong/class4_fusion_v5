<?php
class CarrierTemplateController extends AppController
{
    var $name = 'CarrierTemplate';
    var $uses=Array('CarrierTemplate','Client');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'common');

    /**
     * 列表
     *
     */
    public function index()
    {
        $this->pageTitle = "Carrier Template";

        $conditions = array(
        );

        $get_template_name = $this->_get('template_name');
        if($get_template_name)
        {
            $conditions = "CarrierTemplate.template_name like '%" . trim($this->_get('template_name')) . "%'";
        }



        $order_arr = array('id' => 'asc');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) //排序
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
                'template_name','create_on','create_by','update_on','id',
                '(select count(*) as sum from client where carrier_template_id = "CarrierTemplate".id) as used_by'
            ),
            'limit' => $_GET['size'] ? $_GET['size'] : 100,
            'order' => $order_arr,
            'conditions' => $conditions
        );
        $this->data = $this->paginate('CarrierTemplate');



        if( empty($this->data)  && !$get_template_name )
        {
            $model_name = "CarrierTemplate";
            $msg = "Carrier Template";
            $add_url = "add";
            $this->to_add_page($model_name, $msg, $add_url);
        }
    }


    /**
     * 初始化信息
     */
    function init_info()
    {
        //	$this->set('rate',$this->Client->findRates());
        $this->set('currency', $this->CarrierTemplate->findCurrency());
        //$this->set('service_charge', $this->CarrierTemplate->findservice_charge());
        //$this->set('product', $this->Client->findAllProducts());
        //$this->set('dyn_route', $this->Client->findDyn_routes());
        $this->set('paymentTerm', $this->CarrierTemplate->findPaymentTerm());
        $this->set('sendemailTerm', $this->CarrierTemplate->findsendemailTerm());
        //$this->set('transation_fees', $this->CarrierTemplate->findTransFees());
    }


    public function check_name($name = null, $id = null)
    {
        Configure::write('debug', 0);
        $ch_name = null;
        $this->layout = 'ajax';
        $this->autoRender = false;
        if (!empty($name))
        {
            $sql = "select count(*) as name_num from carrier_template where template_name='$name'";
            if ($id !== null)
            {
                $sql .= " and client_id != {$id}";
            }
            $ch_name = $this->CarrierTemplate->query($sql);
            $ch_name = $ch_name[0][0]['name_num'];
            //var_dump($ch_name[0][0]['name_num']);exit;
        }
        if ($ch_name)
        {
            echo 'false';
        }
        else
        {
            echo 'true';
        }
        //return !$ch_name;
    }

    function get_payment_term_type()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $result = false;
        $payment_term_id = $_POST['payment_term_id'];

        if ($payment_term_id) {
            $sql = "select type from payment_term where payment_term_id = $payment_term_id";
            $result = $this->CarrierTemplate->query($sql);
        }
        
        echo json_encode($result);
    }



    //添加

    public function add()
    {
//        $tmpData = $this->CarrierTemplate->query('ALTER TABLE carrier_template ADD COLUMN auto_report_type integer');
//        $tmpData = $this->CarrierTemplate->find('first');
//        echo '<pre>';
//        die(var_dump($tmpData));
        if ($this->RequestHandler->isPost())
        {
            if($this->CarrierTemplate->find(array('template_name' => $_POST['data']['Client']['template_name']))){
                $this->Session->write('m', $this->CarrierTemplate->create_json(101, 'The Template Name [' . $_POST['data']['Client']['template_name'] . '] is already in use ! '));
                $this->redirect('/carrier_template/add/');
            }
            //$this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
            $return = $this->CarrierTemplate->saveOrUpdate($this->data, $_POST); //保存
            if($return){
                $this->Session->write('m', $this->CarrierTemplate->create_json(201, 'The Carrier Template [' . $this->data['Client']['template_name'] . '] is added successfully!'));
                $this->redirect('/carrier_template/');
            }
            $this->Session->write('m', $this->CarrierTemplate->create_json(101, 'Carrier Template add failure!'));
        }
        $page_title_type = 'Add';


        $this->pageTitle = $page_title_type . ' Carrier Template';


        $this->init_info();

        $tz = $this->CarrierTemplate->get_sys_timezone();
        $gmt = "+00:00";
        if ($tz)
        {
            $gmt = substr($tz, 0, 3) . ":00";
        }
        $this->set('gmt', $gmt);
        $default_currency = $this->CarrierTemplate->query("SELECT sys_currency FROM system_parameter LIMIT 1");
        $this->set('default_currency', $default_currency[0][0]['sys_currency']);

    }

    //编辑
    public function edit($e_id=''){

        if($this->RequestHandler->isPost()){
            $return = $this->CarrierTemplate->saveOrUpdate($this->data, $_POST); //保存
            //exit('a');
            if($return){
                $this->Session->write('m', $this->CarrierTemplate->create_json(201, 'Carrier Template is modified successfully!'));
                $id = $this->data['Client']['id'];
                $this->redirect('/carrier_template/index');
            }
            //pr(CarrierTemplate::set_validator());exit;
            $this->Session->write('m', CarrierTemplate::set_validator());
//            $id = $this->data['Client']['id'];
//            $e_id
            return;
        }

        //错误跳转
        $err = 0;
        if(!$e_id){
            $err = 1;
        } else {

            $id = base64_decode($e_id);
            $template_info = $this->CarrierTemplate->findAllById($id);

            if(!$template_info){
                $err = 1;
            }
        }

        if($err){
            $this->Session->write('m', $this->CarrierTemplate->create_json(101, 'Carrier Template edit failure!'));
            $this->redirect('/carrier_template/');
        }


        $this->data['Client'] = $template_info[0]['CarrierTemplate'];
//        die(var_dump($this->data['Client']));
        $this->init_info();
        $tz = $this->CarrierTemplate->get_sys_timezone();
        $gmt = "+00:00";
        if ($tz)
        {
            $gmt = substr($tz, 0, 3) . ":00";
        }
        $this->set('gmt', $gmt);
//        $this->data = $template_info;
        //pr($this->data);


    }



    public function delete($e_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if($e_id)
        {
            $id = base64_decode($e_id);
		$ctName = $this->CarrierTemplate->findById($id)['CarrierTemplate']['template_name'];
            $flg = $this->CarrierTemplate->del($id);
            if ($flg === false)
                $this->Session->write('m', $this->CarrierTemplate->create_json(101, __('Failed!', true)));
            else
                $this->Session->write('m', $this->CarrierTemplate->create_json(201, __('The Carrier Template [' . $ctName . '] is removed successfully!', true)));
            $this->redirect('/carrier_template');
        }
        else
        {
            $this->Session->write('m', $this->CarrierTemplate->create_json(101, __('Failed!', true)));
            $this->redirect('/carrier_template');
        }
    }







    //通过carrier创建模板
    public function create_from_carrier()
    {
        //Configure::write('debug', 0);
        //$this->autoRender = false;
        //$this->autoLayout = false;
        $carrier_id  = $this->params['form']['carrier_id'];
        $template_name = $this->params['form']['template_name'];

        if(!$carrier_id || !$template_name)
        {
            $this->Session->write('m', $this->CarrierTemplate->create_json(101, __('Failed!', true)));
            header("location:{$_SERVER['HTTP_REFERER']}");
            exit ;

        }

        $check = $this->CarrierTemplate->query("select count(*) as name_num from carrier_template where template_name='$template_name'");
        //echo $check;
        if($check[0][0]['name_num']){
            $this->Session->write('m', $this->CarrierTemplate->create_json(101, __('Check Template Name!', true)));
            header("location:{$_SERVER['HTTP_REFERER']}");
            exit ;
        }



        $create_by = $_SESSION['sst_user_name'];
        $create_on = date('Y-m-d H:i:s');
        $update_on = $create_on;

        $carrier_info = $this->Client->find('first',array('client_id' => $carrier_id));
        $carrier_info['Client']['allowed_credit'] = 0 - $carrier_info['Client']['allowed_credit'];
        $carrier_info['Client']['create_by'] = $create_by;
        $carrier_info['Client']['create_on'] = $create_on;
        $carrier_info['Client']['update_on'] = $update_on;
        $carrier_info['Client']['template_name'] = $template_name;

        $arr = $carrier_info['Client'];
        foreach($arr as $key => $val){

            if($val === false){
                $arr[$key] = 0;
            }

        }
        $this->CarrierTemplate->save($arr);

        $this->Session->write('m', $this->CarrierTemplate->create_json(201, __('The Template [' . $template_name . '] is added successfully!', true)));
        header("location:{$_SERVER['HTTP_REFERER']}");
        exit ;


    }

    /**
     * @param $id
     */
    public function ajax_get_used($id)
    {
        Configure::write('debug', 0);
        $sql = "SELECT client_id,name,update_at,update_by,create_time FROM client WHERE carrier_template_id = {$id}";
        $data = $this->CarrierTemplate->query($sql);
        $this->set('data',$data);


    }

    //使用模板创建carrier
    public function add_carrier_by_template(){
        $result = $this->CarrierTemplate->query('select id,template_name from carrier_template');
        $size = count($result);
        $template_arr = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $result[$i][0]['id'];
            $template_arr[$key] = $result[$i][0]['template_name'];
        }

        asort($template_arr);
        $this->set('template_arr',$template_arr);

        //registration
        $is_registration = isset($_POST['approve_is_template']);

        $registration_id = null;
        if($is_registration){
            $return_url = $_POST['approve_url'];
            $registration_id = $_POST['approve_id'];

            $sql = "select * from signup where id = $registration_id";
            $registration_info = $this->CarrierTemplate->query($sql);
            $registration_info = $registration_info[0][0];

            $this->data['Client'] = array_merge($registration_info,$this->data['Client']);
            $this->data['Client']['is_panelaccess'] = 1;


            //pr($this->data['Client']);exit;
        }





        if($this->RequestHandler->isPost()){
            $carrier_name = $this->data['Client']['name'];
            $result = $this->CarrierTemplate->query("select client_id from client where name = '$carrier_name'");
            $client_id = $result[0][0]['client_id'];
            if($client_id){
                $this->CarrierTemplate->query("DELETE from client where name = '$carrier_name' and client_id<>'$client_id'");
            }
            if($this->data['Client']['is_panelaccess']){
                $login = $this->data['Client']['login'];
                $result = $this->CarrierTemplate->query("select count(1) as cnt from users where name  = '$login'");
                if($result[0][0]['cnt']){
                    $this->CarrierTemplate->query("delete from users where name  = '$login' and client_id<>'$client_id'");
                }
            }

            $template_id = $this->data['Client']['template_id'];
            $template_info = $this->CarrierTemplate->findAllById($template_id);
            $this->data['Client'] = array_merge($template_info[0]['CarrierTemplate'], $this->data['Client']);
            $this->data['Client']['status'] = true;
            $this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
            $this->data ['Client']['usage_detail_fields'] = !empty($this->data['Client']['usage_detail_fields']) ? explode(',', $this->data['Client']['usage_detail_fields']) : '';

            $this->data['Client']['carrier_template_id'] = $this->data['Client']['template_id'];
            $this->loadModel('Client');
            $_POST['client_id'] =$client_id;

            $result = $this->Client->saveOrUpdate($this->data, $_POST, false, true, true);

            if($result !== false && !empty($this->data['Client']['agent_assoc_id']) && isset($result['client_id'])) {
                $this->loadModel('AgentClients');
                $clientId = $result['client_id'];
                $agentId = $this->data['Client']['agent_assoc_id'];
                $this->AgentClients->associateClientToAgent($clientId, $agentId);
            }


            $this->auto_redirect($result,$is_registration,$registration_id);
        }
    }


    private function auto_redirect($flag_arr,$is_registration=null,$registration_id=null)
    {

        if (isset($flag_arr['client_id']))
        {
            $name = $_POST['data']['Client']['name'];
            //$this->Client->create_json_array('#ClientOrigRateTableId',201,__('Create carriers successfully!',true));

            if($is_registration){
                $this->requestAction('/registration/approve/'.base64_encode($registration_id).'/0/1');
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier [%s] is created successfully', true, $name));

                $this->requestAction('/clients/send_welcom_letter/'.$flag_arr['client_id']);
                if(!$_SESSION['role_menu']['Template']['carrier_template']['model_w']){
                    $this->xredirect("/clients/index");
                }

                $this->xredirect("/clients/step2/{$flag_arr['client_id']}/{$registration_id}");
            }

            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier [%s] is created successfully', true, $name));

            //send welcom letter
            if($_POST['is_send_welcom_letter'] == '1' || $_POST['is_send_welcom_letter'] == 'on'){
                $this->requestAction('/clients/send_welcom_letter/'.$flag_arr['client_id']);
            }

            //$is_registration, approve

            $this->xredirect("/clients/step2/{$flag_arr['client_id']}/{$registration_id}");

        }
        else
        {
            //$this->Client->create_json_array('#ClientOrigRateTableId',101,__('Create carriers Failed!',true));
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Fail to create Carrier', true));

            if($is_registration){
                if(isset($return_url) && !empty($return_url)){
                    $this->Session->write('mm', 2);
                }
                $this->xredirect("/registration/?".base64_encode($return_url));
            }

            $this->xredirect(array('controller' => 'carrier_template', 'action' => 'add_carrier_by_template')); // failed
        }
    }


    //re-apply
    public function reapply($e_id){
//        Configure::write('debug', 0);
//        $this->autoRender = false;
//        $this->autoLayout = false;
        if(!$e_id)
        {
            $this->Session->write('m', $this->CarrierTemplate->create_json(101, __('Failed!', true)));
            $this->redirect('index');
        }
        $id = base64_decode($e_id);

        $template_info = $this->CarrierTemplate->findAllById($id);
        $this->data['Client'] = $template_info[0]['CarrierTemplate'];

        $this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
        $this->data ['Client']['usage_detail_fields'] = !empty($this->data['Client']['usage_detail_fields']) ? explode(',', $this->data['Client']['usage_detail_fields']) : '';


        //找到使用模板的carrier
        $sql = "select client_id,name from client where carrier_template_id = {$id}";
        $carrier_arr = $this->CarrierTemplate->query($sql);
//pr($carrier_arr);exit;

        $save_data = $this->data;
        $this->loadModel('Client');
        foreach($carrier_arr as $carrier){
            $carrier_id = $carrier[0]['client_id'];
            $save_data['Client']['client_id'] = $carrier_id;
            $save_data['Client']['name'] = $carrier[0]['name'];

            $result = $this->Client->save_reapply($save_data);

            if(!$result){
                $this->CarrierTemplate->create_json_array('',101, sprintf(__("The trunk[%s] is re-apply failed.", true), $carrier[0]['name']));
            } else {
                $this->CarrierTemplate->create_json_array('',201, sprintf(__("The trunk[%s] is re-apply successfully.", true), $carrier[0]['name']));
            }
        }//pr($result);//exit;
        $this->Session->write('m', CarrierTemplate::set_validator());
        //pr(CarrierTemplate::$tip_info);exit;
        $this->redirect('index');
        //pr($_POST);exit;


    }
}
