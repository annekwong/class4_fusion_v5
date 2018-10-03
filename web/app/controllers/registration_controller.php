
<?php

class RegistrationController extends AppController
{

    var $name = 'Registration';
    var $helpers = array('javascript', 'html', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('Registration');


    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_ClientGroup');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }




    public function index(){//列表
        Configure::write('debug', 2);
        if (!$_SESSION['role_menu']['Management']['registration']['model_r'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = 'Management/Registration';
        //$start_time = date("Y-m-d 00:00:00");
        //$end_time = date("Y-m-d 23:59:59");

        $conditions =  array();
        if (isset($_GET['start_time'])  && (!empty($_GET['start_time']) || !empty($_GET['end_time']))) //时间过滤
        {
            $start_time = empty($_GET['start_time']) ? date('Y-m-d 0:0:0') : $_GET['start_time'];
            $end_time = empty($_GET['end_time']) ? date('Y-m-d 23:59:59') : $_GET['end_time'];
            $_GET['start_time'] = $start_time;
            $_GET['end_time'] = $end_time;
            $conditions =  array(
                'Registration.signup_time BETWEEN ? and ?' => array($start_time, $end_time)
            );
        }



        if (isset($_GET['status_type']) && $_GET['status_type']!=='') //status状态过滤
        {
            $conditions["Registration.status"] = $_GET['status_type'];
        }

        $order_arr = array();
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
        } else {
            $order_arr = array('Registration.signup_time' => 'desc');
        }
        //$order_arr = array_merge($order_arr,array('RetrievePasswordLog.id' => 'desc'));

        //$temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        //empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        //empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        //$_SESSION['paging_row'] = 100;

        $this->paginate = array( //分页
            'fields' => array(
                "id",
                "login",
                "Registration.email",
                "company",
                "agent_assoc_id",
                "phone",
                "signup_time",
                "modify_time",
                "send_email",
                "status",
                "Users.user_id",
                "Client.name"
            ),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'Users',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Registration.login = Users.name'
                    ),
                ),
                array(
                    'table' => 'client',
                    'alias' => 'Client',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Users.user_id = Client.user_id'
                    ),
                ),
            ),
            'limit' => 100,

            'order' => $order_arr,
            'conditions' => $conditions
        );

        $this->data = $this->paginate('Registration');
       // pre($this->data);
        $this->loadModel('Agent');
        $agentList = $this->Agent->find('all');
        $this->set('agentList', $agentList);

        $status_name = array(
            'Registered', 'Approved', 'Rejected'
        );
        $this->set('status_name',$status_name);
        //$this->set('start_time', $start_time);
        //$this->set('end_time', $end_time);
        $email_status = array('Not','Successful','Failure');
        $this->set('email_status', $email_status);

        //carrier_template
        $carrier_template_arr = $this->Registration->query('select id,template_name from carrier_template');
        $this->set('carrier_template_arr',$carrier_template_arr);
    }

    /**
     * Remove registration
     * @param $id
     * @param null $param
     */
    public function remove($id, $param = null)
    {
        if (!$_SESSION['role_menu']['Management']['registration']['model_w']) {
            $this->redirect_denied();
        }

        $id = base64_decode(trim($id));
        $param = base64_decode(trim($param));
        $return_url = '?' . trim($param);

        if (empty($id)) {
            $this->Registration->create_json_array('', 101, __('Delete failed, please try again.', true));
            $this->Session->write("m", Registration::set_validator());
            if (!empty($return_url)) {

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/$return_url");
        }

        $item = $this->Registration->findById($id);

        if (!$item) {
            $this->Registration->create_json_array('', 101, __('Delete failed, please try again.', true));
            $this->Session->write("m", Registration::set_validator());
            if (!empty($return_url)) {

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/$return_url");
        }

        $this->Registration->begin();
        $deleteResult = $this->Registration->query("DELETE FROM client WHERE login = '{$item['Registration']['login']}'");

        if ($deleteResult !== false) {
            $deleteResult = $this->Registration->delete($id);

            if ($deleteResult !== false) {
                $this->Registration->commit();
                $this->Registration->create_json_array('', 201, __('Deleted successfully!', true));
            } else {
                $this->Registration->rollback();
                $this->Registration->create_json_array('', 101, __('Delete failed!', true));
            }
        } else {
            $this->Registration->rollback();
            $this->Registration->create_json_array('', 101, __('Delete failed!', true));
        }

        if (!empty($return_url)) {

            $this->Session->write("mm", 2);
        }
        $this->Session->write("m", Registration::set_validator());
        $this->xredirect("/registration/$return_url");
    }

    //reject
    public function del($id,$param=null){
        if (!$_SESSION['role_menu']['Management']['registration']['model_w'])
        {
            $this->redirect_denied();
        }

        $id = base64_decode(trim($id));
        $param = base64_decode(trim($param));
        $return_url = '?'.trim($param);
        if(empty($id)){ //id为空
            $this->Registration->create_json_array('', 101, __('Reject failed, please try again.',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){//解决跳转，下拉提示信息不生效问题

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/$return_url");
        }

        $item = $this->Registration->findAllById($id);
        if(!$item){ //id不存在
            $this->Registration->create_json_array('', 101, __('Reject failed, please try again.',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/$return_url");
        }



        //发邮件，读取邮件模板failure字段
        //$send_email = $this->send_email($item[0]['Registration'],false);

        //邮件发送成功
//        if ($send_email)
//        {
            $this->data = array(
                'id' => "$id",
                'status' => '2',
                //'send_email' => '1',
                'modify_time' => date('Y-m-d H:i:s')
            );
            $this->Registration->save($this->data);

            $this->Registration->create_json_array('', 201, __('Reject successful.',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/$return_url");
            //echo json_encode(Registration::set_validator());
        /*} else {//邮件发送失败

            $this->data = array(
                'id' => "$id",
                'status' => '2',
                'send_email' => '2',
                'modify_time' => date('Y-m-d H:i:s')
            );
            $this->Registration->save($this->data);

            $this->Registration->create_json_array('', 101, __('But this e-mail has been sent to fail',true));
            $this->Registration->create_json_array('', 201, __('Reject successful.',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/$return_url");
            //echo json_encode(Registration::set_validator());
        }*/



    }

    //邮件重发
    public function resend($id,$param){
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        if (!$_SESSION['role_menu']['Management']['registration']['model_w'])
        {
            $this->redirect_denied();
        }

        $id = base64_decode(trim($id));
        $param = base64_decode(trim($param));
        $return_url = '?'.trim($param);
        if(empty($id)){
            $this->Registration->create_json_array('', 101, __('Reject failed, please try again.',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/$return_url");
        }

        $item = $this->Registration->findAllById($id);
        if(!$item){
            $this->Registration->create_json_array('', 101, __('Reject failed, please try again.',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/$return_url");
        }


        //当前状态，是approve，还是reject
        if($item[0]['Registration']['status']==1){

            $send_email = $this->send_email($item[0]['Registration'],true);
        } else {

            $send_email = $this->send_email($item[0]['Registration'],false);
        }
        //var_dump($item);exit;

        //成功or失败
        if ($send_email)
        {
            $this->data = array(
                'id' => "$id",
                //'status' => '2',
                'send_email' => '1',
                'modify_time' => date('Y-m-d H:i:s')
            );
            $this->Registration->save($this->data);

            $this->Registration->create_json_array('', 201, __('E-mail resend successful',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
//            pr($this->Session->read('m'));var_dump($item);exit;
            $this->redirect("/registration/$return_url");
            //echo json_encode(Registration::set_validator());
        } else {

            $this->data = array(
                'id' => "$id",
                //'status' => '2',
                'send_email' => '2',
                'modify_time' => date('Y-m-d H:i:s')
            );
            $this->Registration->save($this->data);
            $this->Registration->create_json_array('', 101, __('E-mail resend failure',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->redirect("/registration/$return_url");
            //echo json_encode(Registration::set_validator());
        }



    }

    /*发邮件功能，
        $item,用户信息，一维数组，如$item['login']
        $is_true, 取值 true表示status维approve，false为reject
        return bool
    */
    private function send_email($item,$is_true){
        //邮件配置
        $sql = "SELECT registration_from from mail_tmplate";
        $registration_from = $this->Registration->query($sql);
        if ($registration_from[0][0]['registration_from'] && $registration_from[0][0]['registration_from'] != 'Default')
        {
            $email_info = $this->Registration->query("SELECT loginemail, smtp_host as smtphost, smtp_port as smtpport, username as username, password,name as name, email as from, secure as smtp_secure FROM mail_sender WHERE id = {$registration_from[0][0]['registration_from']}");
        }
        else
        {
            $email_info = $this->Registration->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
        }

        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer();
        if ($email_info[0][0]['loginemail'] === 'false')
        {
            $mailer->IsMail();
        }
        else
        {
            $mailer->IsSMTP();
        }
        $mailer->SMTPDebug = 0;
        $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
        switch ($email_info[0][0]['smtp_secure'])
        {
            case 1:
                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = $email_info[0][0]['realm'];
                $mailer->Workstation = $email_info[0][0]['workstation'];
        }

        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtphost'];
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        $mailer->CharSet = "UTF-8";


        //邮件模板
        $result = $this->Registration->query("select registration_failure,registration_success,registration_from,registration_subject,registration_content from mail_tmplate");
        $username = $item['login'];

        if($is_true){
            $information = $result[0][0]['registration_success'];
        } else {

            $information = $result[0][0]['registration_failure'];
        }
        $content = $result[0][0]['registration_content'];

        $subject =$result[0][0]['registration_subject'];

        //替换关键字
        $content = str_replace('{username}', $username, $content);
        $content = str_replace('{information}', $information, $content);

//pr($item);exit;
        //收件地址
        $mailer->AddAddress($item['email']);





        $mailer->ClearAttachments();

        $mailer->IsHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body = $content;

        return $mailer->Send();
    }


    //approve
    public function approve($en_registration_id,$en_return_url=null,$type=null){
        $registration_id = base64_decode($en_registration_id);
        $return_url = base64_decode($en_return_url);
        $item = $this->Registration->findAllById($registration_id);

        //发邮件，读取邮件模板success字段
        $send_email = $this->send_email($item[0]['Registration'],true);

        //邮件发送成功
//        if ($send_email)
//        {
            $this->data = array(
                'id' => "$registration_id",
                'status' => '1',
                //'send_email' => '1',
                'modify_time' => date('Y-m-d H:i:s')
            );
            $this->Registration->save($this->data);



            $this->Registration->create_json_array('', 201, __('Approve successful.',true));

            if($type==1){
                return true;
            }

            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/?$return_url");
            //echo json_encode(Registration::set_validator());
        /*} else {//邮件发送失败

            $this->data = array(
                'id' => "$registration_id",
                'status' => '1',
                'send_email' => '2',
                'modify_time' => date('Y-m-d H:i:s')
            );
            $this->Registration->save($this->data);



            $this->Registration->create_json_array('', 101, __('But this e-mail has been sent to fail',true));
            $this->Registration->create_json_array('', 201, __('Approve successful.',true));

            if($type==1){
                return;
            }

            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/?$return_url");
            //echo json_encode(Registration::set_validator());
        }*/
        return;

    }



    //edit
    public function edit($encode_id=null,$param=null){

        if (!$_SESSION['role_menu']['Management']['registration']['model_r'])
        {
            $this->redirect_denied();
        }

        //post
        if(!empty($this->data)){
            $id = intval($_POST['edit_id']);
            $return_url = $_POST['return_url'];
            if(!empty($this->data['Registration']['ip'])){

                $ip_arr = $this->data['Registration']['ip'];
                $netmark_arr = $this->data['Registration']['netmark'];
                $port_arr = $this->data['Registration']['port'];
            }else{
                $ip_arr = null;
            }


            //login唯一性
            $name = $this->data['Registration']['login'];
            $sql = "select count(1) as cnt from signup where login = '$name' and id != '$id'";
            $cnt = $this->Registration->query($sql);
            $cnt = $cnt[0][0]['cnt'];
            if($cnt){
                $this->Registration->create_json_array('', 101, __('Username is unique!',true));
                $this->Session->write("m", Registration::set_validator());
//                if(!empty($return_url)){
//
//                    $this->Session->write("mm", 2);
//                }

                //跳转，编码
                $id = base64_encode($id);
                $return_url = base64_encode($return_url);
                $this->xredirect("/registration/edit/{$id}/{$return_url}");
            }
            else
            {//login有效，保存signup
                //是否修改密码
                if(empty($this->data['Registration']['password'])){
                    unset($this->data['Registration']['password']);
                }else{
                    $this->data['Registration']['password'] = $this->data['Registration']['password'];
                }

                $this->data['Registration']['id'] = $id;
                $this->data['Registration']['modify_time'] = date('Y-m-d H:i:s');

                $this->data['Registration']['product_id'] = implode(',', $_POST['product_id']);
                $this->Registration->save($this->data['Registration']);


                //保存signup_ip
                $sql = "delete from signup_ip where signup_id= $id";
                $this->Registration->query($sql);


                $count = count($ip_arr);
                if($count){
                    $sql = 'insert into signup_ip(signup_id,ip,netmark,port) values';
                    $ip_data = array();
                    for($i=0;$i<$count;$i++){
                        if(!filter_var($ip_arr[$i], FILTER_VALIDATE_IP)) {
                            continue;
                        }
                        $arr = array($ip_arr[$i],$netmark_arr[$i],$port_arr[$i]);
                        if(!in_array($arr,$ip_data)){//处理唯一性
                            $ip_data[] = $arr;
                            $sql .= "($id,'$ip_arr[$i]','$netmark_arr[$i]','$port_arr[$i]'),";
                        }
                    }
                    $sql = rtrim($sql,',');
                    $this->Registration->query($sql);
                }



                $this->Registration->create_json_array('', 201, __('User edit successful.',true));
                $this->Session->write("m", Registration::set_validator());
                if(!empty($return_url)){

                    $this->Session->write("mm", 2);
                }
                $this->xredirect("/registration/?$return_url");

            }

        }

        //参数
        $decode_id = intval(base64_decode(trim($encode_id)));
        $param = base64_decode(trim($param));
        $return_url = trim($param);
        if(empty($decode_id)){//id为空
            $this->Registration->create_json_array('', 101, __('Edit failed, please try again.',true));
            $this->Session->write("m", Registration::set_validator());
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/?$return_url");
        }

        $item = $this->Registration->findAllById($decode_id);
        if($item[0]['Registration']['product_id']){

            $item[0]['Registration']['product_id'] = explode(',', $item[0]['Registration']['product_id']);
        }
        $this->set('backsignup',$item[0]['Registration']);

        $sql = "select * from signup_ip where signup_id = $decode_id";
        $ips = $this->Registration->query($sql);


        $this->set('ips',$ips);
        $this->set('return_url',$return_url);


        $country_arr = array(
            "AO" => "Angola",
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AD" => "Andorra",
            "AI" => "Anguilla",
            "AG" => "Barbuda Antigua",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda Is.",
            "BO" => "Bolivia",
            "BW" => "Botswana",
            "BR" => "Brazil",
            "BN" => "Brunei",
            "BG" => "Bulgaria",
            "BF" => "Burkina-faso",
            "MM" => "Burma",
            "BI" => "Burundi",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CO" => "Colombia",
            "CG" => "Congo",
            "CK" => "Cook Is.",
            "CR" => "Costa Rica",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DO" => "Dominica Rep.",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "EI Salvador",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GD" => "Grenada",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HN" => "Honduras",
            "HK" => "Hongkong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KH" => "Kampuchea (Cambodia )",
            "KZ" => "Kazakstan",
            "KE" => "Kenya",
            "KR" => "Korea",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Laos",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MU" => "Mauritius",
            "MX" => "Mexico",
            "MD" => "Moldova",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "MS" => "Montserrat Is.",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "KP" => "North Korea",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PA" => "Panama",
            "PG" => "Papua New Cuinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PL" => "Poland",
            "PF" => "French Polynesia",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RO" => "Romania",
            "RU" => "Russia",
            "LC" => "Saint Lueia",
            "VC" => "Saint Vincent",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Is.",
            "SO" => "Somali",
            "ZA" => "South Africa",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syria",
            "TW" => "Taiwan",
            "TJ" => "Tajikstan",
            "TZ" => "Tanzania",
            "TH" => "Thailand",
            "TG" => "Togo",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kiongdom",
            "US" => "United States of America",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VE" => "Venezuela",
            "VN" => "Vietnam",
            "YE" => "Yemen",
            "YU" => "Yugoslavia",
            "ZW" => "Zimbabwe",
            "ZR" => "Zaire",
            "ZM" => "Zambia"
        );
        asort($country_arr);
        $this->set('country_arr',$country_arr);



        $sql ="select product_name, id, tech_prefix,
(select name from route_strategy where route_strategy.route_strategy_id=product_route_rate_table.route_strategy_id) as routing_plan,
(select name from rate_table where rate_table.rate_table_id=product_route_rate_table.rate_table_id) as rate_table

from product_route_rate_table where is_private=false
";
        $items = $this->Registration->query($sql);

        $product_arr = array();
        $product_name_arr = array();
        foreach($items as $item){
            $product_name_arr[$item[0]['id']] = $item[0]['product_name'];
            $arr = array(
                'product_name' => $item[0]['product_name'],
                'routing_plan' => $item[0]['routing_plan'],
                'rate_table' => $item[0]['rate_table'],
                'tech_prefix' => $item[0]['tech_prefix']

            );

            $product_arr[$item[0]['id']] = $arr;
        }

        $this->set('product_arr', $product_arr);
        $this->set('product_name_arr', $product_name_arr);
    }
}

?>
