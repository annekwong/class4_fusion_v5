<?php

class SignupController extends Controller
{

    var $name = 'Signup';
    var $uses = array('Signup', 'ProductRouteRateTable', 'Agent');
    var $helper = array('html', 'javascript', 'RequestHandler');
    var $helpers = array('javascript', 'html', 'form', 'Session', 'App', "AppCommon", 'Xpaginator', 'Xform', 'AppImportExportLog');

    /* 	public function beforeFilter(){
      //parent::beforeFilter();//调用父类方法
      } */

    public function index($agentId = null)
    {
        Configure::write('debug', 0);
        Configure::write('Config.language', 'eng');

        $this->loadModel('Systemparam');
        $info = $this->Systemparam->find('first',array(
            'fields' => array('allow_registration'),
        ));
        
        if ($info['Systemparam']['allow_registration'] == 'false') {
            $this->redirect('/homes/login');
        }

        $this->set('noMenu', true);
        $redirectUrl = empty($agentId) ? '/signup' : '/signup/index/' . $agentId;
        $agentId = $agentId ? base64_decode($agentId) : null;
        $rst = $this->Signup->query("select themer, signup_content, sys_timezone from system_parameter limit 1");
        //$tmp_welcome = $this->Signup->query("select welcom_content from mail_tmplate limit 1");
        $themer = $rst[0][0]['themer'];
        $this->set('themerSelectedTheme', $themer);
        if ($agentId) {
            $this->set('agentId', $agentId);
        }

        if (!empty($this->data)) { //提交
            $captcha = $this->data['Signup']['captcha']; //验证码
            if (empty($captcha)) {
                $this->Signup->create_json_array('', 101, __('Please  input  captcha', true));
                $this->Session->write('m', $this->Signup->set_validator_data());
                $this->Session->write('backsignup', $this->data['Signup']);
                $this->redirect($redirectUrl);
                exit();
            }

            if (!$this->Signup->xvalidate($this->data['Signup'])) {//数据验证
                $this->Session->write("m", $this->Signup->set_validator_data());
                $this->Session->write('backsignup', $this->data['Signup']);
                $this->redirect($redirectUrl);
                exit();
            }
            $this->data['Signup']['product_id'] = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? implode(',', array_unique(array_filter($_POST['product_id']))) : '';

            //保持signup
            $this->data['Signup']['signup_time'] = date('Y-m-d H:i:s');
            $this->data['Signup']['password'] = md5($this->data['Signup']['password']);

            if ($agentId) {
                $this->data['Signup']['agent_assoc_id'] = $agentId;
            }
            $this->Signup->begin();
            if ($this->check_name($this->data['Signup']['contact_name'])) {
                $this->Signup->create_json_array('', 101, __('Carrier '.$this->data['Signup']['contact_name'].' already exists!', true));
                $this->Session->write('m', $this->Signup->set_validator_data());
                $this->Session->write('backsignup', $this->data['Signup']);
                $this->redirect($redirectUrl);
                exit();
            }

            $saveResult = $this->Signup->save($this->data['Signup']);
            $signup_id = $this->Signup->getLastInsertId();

            $ip = isset($this->data['Signup']['ip']) ? $this->data['Signup']['ip'] : array('127.0.0.1');
            $netmark = isset($this->data['Signup']['netmark']) ? $this->data['Signup']['netmark'] : array(1);
            $port = isset($this->data['Signup']['port']) ? $this->data['Signup']['port'] : array(27011);


            $count = count($ip);
            $sql = 'insert into signup_ip(signup_id,ip,netmark,port) values';
            $ip_data = array();
            for ($i = 0; $i < $count; $i++) {
                if (!filter_var($ip[$i], FILTER_VALIDATE_IP)) {
                    continue;
                }
                $arr = array($ip[$i], $netmark[$i], $port[$i]);
                if (!in_array($arr, $ip_data)) {//唯一性过滤
                    $ip_data[] = $arr;
                    $sql .= "($signup_id,'$ip[$i]','$netmark[$i]','$port[$i]'),";
                }
            }
            $sql = rtrim($sql, ',');
            $res = $this->Signup->query($sql);

            if ($saveResult != false) {

                $this->loadModel('Client');
                $this->loadModel('Currency');
                $date = date("Y-m-d   H:i:s");
                $firstCurrency = $this->Currency->find('first');
                $saveClient = array(
                    'name' => $this->data['Signup']['contact_name'],
                    'status' => false,
                    'create_time' => $date,
                    'currency_id' => $firstCurrency['Currency']['currency_id'],
                    'company' => $this->data['Signup']['company'],
                    'address' => $this->data['Signup']['address'],
                    'email' => $this->data['Signup']['email'],
                    'login' => $this->data['Signup']['login'],
                    'password' => $this->data['Signup']['password'],
                    'noc_email' => $this->data['Signup']['noc_email'],
                    'billing_email' => $this->data['Signup']['billing_email'],
                    'rate_email' => $this->data['Signup']['rate_email'],
                    'role_id' => 2,
                    'is_panelaccess' => 't',
                    'allowed_credit' => 0
                );

                $this->data['Client'] = $saveClient;

                $this->loadModel('CarrierTemplate');
                $template_info = $this->CarrierTemplate->find('first');
                $this->data['Client'] = array_merge($this->data['Client'], $template_info['CarrierTemplate'] ?: []);
                $this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
                $this->data ['Client']['usage_detail_fields'] = !empty($this->data['Client']['usage_detail_fields']) ? explode(',', $this->data['Client']['usage_detail_fields']) : '';

                $this->data['Client']['carrier_template_id'] = (int)$template_info['CarrierTemplate']['id'];
                $this->loadModel('Client');
                unset($this->data['Client']['usage_detail_fields']);
                Configure::write('debug', 2);
                $result = $this->Client->save($this->data['Client']);
                if($result === false){
                    $this->Signup->create_json_array('', 101, __('Carrier create failed!', true));
                    $this->Session->write('m', $this->Signup->set_validator_data());
                    $this->Session->write('backsignup', $this->data['Signup']);
                    $this->redirect($redirectUrl);
                    exit();
                }
                $client_id = $this->Client->getLastInsertId();

                if (!$client_id) {
                    $this->Signup->rollback();
                    $this->Session->write('m', $this->Signup->create_json(101, 'Creating client failed!'));
                    $this->redirect('/signup');
                }

                $this->Client->clientBalanceOperation($client_id, 0, 0);
                $login = $this->data['Signup']['login'];
                $password = $this->data['Signup']['password'];

                $list = $this->Client->query("select count(*)  from  users  where name='$login'");
                if (empty($list[0][0]['count']) || $list[0][0]['count'] == 0) {
                    $user_id_results = $this->Client->query("insert into users(name,password,client_id,create_time,user_type)values('$login','$password',$client_id,'$date',3) RETURNING user_id");
                    $this->Client->query("update client set user_id = {$user_id_results[0][0]['user_id']} where client_id = {$client_id}");
                }
                if ($result !== false && $agentId && isset($client_id)) {
                    $this->loadModel('AgentClients');
                    $this->AgentClients->associateClientToAgent($client_id, $agentId);
                }
                $this->Signup->commit();
//                $this->Client->query("DELETE from client where name = '{$this->data['Signup']['contact_name']}'");
//                $this->Client->save($saveClient);
//                $clientId = $this->Client->getLastInsertId();
//                $date = date("Y-m-d   H:i:s");
//                $sql = "insert into users(name,password,client_id,create_time,user_type)values('{$saveClient['login']}','{$saveClient['password']}',$clientId,'$date',3) RETURNING user_id";
//                $user_id_results = $this->Client->query($sql);
//                $this->Client->query("update client set user_id = {$user_id_results[0][0]['user_id']} where client_id = {$clientId}");

                $email = $this->data['Signup']['email'];
                $this->send_mail($email, $client_id);

//                $url = "{$_SERVER['HTTP_HOST']}{$this->webroot}homes/login";
//                $replaced_arr = array('{client_name}', '{company_name}', '{username}', '{email}', '{password}', '{url}', '{login_url}');
//                $replace_arr = array(
//                    $this->data['Signup']['contact_name'],
//                    $this->data['Signup']['company'],
//                    $this->data['Signup']['login'],
//                    $this->data['Signup']['email'],
//                    $this->data['Signup']['password'],
//                    "<a href='{$url}'>{$url}</a>",
//                    "<a href='{$url}'>{$url}</a>"
//                );
//                $signupContent = str_replace($replaced_arr,$replace_arr,$tmp_welcome[0][0]['welcom_content']);
//                $this->set('signupContent', $signupContent);
                $this->render('signup_content');
            }
            $this->layout = '';

        }

        $this->paginate = array(
            'fields' => 'ProductRouteRateTable.product_name,ProductRouteRateTable.rate_table_id,ProductRouteRateTable.description,ProductRouteRateTable.update_on,ProductRouteRateTable.update_by,
            ProductRouteRateTable.tech_prefix,RateTable.name,RouteStrategy.name,ProductRouteRateTable.id',
            'limit' => 1000,
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = ProductRouteRateTable.rate_table_id',
                    )
                ),
                array(
                    'table' => 'route_strategy',
                    'alias' => 'RouteStrategy',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RouteStrategy.route_strategy_id = ProductRouteRateTable.route_strategy_id',
                    )
                )
            ),
            'conditions' => array(
                'is_private' => false,
            ),
        );
        $items = $this->paginate('ProductRouteRateTable');
        $product_arr = array();
        $product_name_arr = array();
        $product_rate_table = array();
        foreach ($items as $item) {
            $product_rate_table[$item['ProductRouteRateTable']['id']] = $item['ProductRouteRateTable']['rate_table_id'];
            $product_name_arr[$item['ProductRouteRateTable']['id']] = $item['ProductRouteRateTable']['product_name'];
            $arr = array(
                'product_name' => $item['ProductRouteRateTable']['product_name'],
                'routing_plan' => $item['RouteStrategy']['name'],
                'rate_table' => $item['RateTable']['name'],
                'tech_prefix' => $item['ProductRouteRateTable']['tech_prefix'],
                'description' => $item['ProductRouteRateTable']['description']
            );

            $product_arr[$item['ProductRouteRateTable']['id']] = $arr;
        }
//pr($product_arr);exit;
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

        $agentInfo = $this->Agent->find('all');
        if ($agentInfo !== false) {
            $this->set('agentInfo', $agentInfo);
        }


        $this->set('country_arr', $country_arr);
        $this->set('product_arr', $product_arr);
        $this->set('product_name_arr', $product_name_arr);
        $this->set('product_rate_table', $product_rate_table);
    }

    function check_name($name) {
        $this->loadModel('Client');
        $sql = "select count(*) cnt from client where name='{$name}'";
        $c = $this->Client->query($sql);
        return $c[0][0]['cnt'] ? true: false;
    }


    private function send_mail($to_email, $id)
    {
        App::import('Vendor', 'VendorMailSender', array('file' => 'mailer/mailsender.php'));

        $vendorMailSender = new VendorMailSender;
        $cc = array();
        $sql = " select regletter_from,regletter_subject,regletter_content, regletter_cc from mail_tmplate limit 1 ";
        $tamplete_info = $this->Signup->query($sql);
        $email_info = $this->Signup->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username, 
                 emailpassword as  "password", emailname as "name", loginemail, smtp_secure,realm,workstation,system_admin_email, switch_alias FROM system_parameter');

        if(empty($tamplete_info)){
            return;
        }

        $mail_subject = $tamplete_info[0][0]['regletter_subject'];
        $url = "{$_SERVER['HTTP_HOST']}{$this->webroot}homes/login";
        $replaced_arr = array('{client_name}', '{company_name}', '{username}', '{email}', '{switch_alias}', '{login_url}');
        $replace_arr = array(
            $this->data['Signup']['contact_name'],
            $this->data['Signup']['company'],
            $this->data['Signup']['login'] ?: $this->data['Signup']['contact_name'],
            $this->data['Signup']['email'],
            $email_info[0][0]['switch_alias'],
            "<a href='{$url}'>{$url}</a>"
        );

        $mail_content = str_replace($replaced_arr, $replace_arr, $tamplete_info[0][0]['regletter_content']);
        $mail_subject = str_replace($replaced_arr, $replace_arr, $mail_subject);
        $admin_mail = $this->Signup->query('SELECT system_admin_email FROM system_parameter');

        if (isset($admin_mail[0][0]['system_admin_email']) && !empty($admin_mail[0][0]['system_admin_email'])) {
            array_push($cc, $admin_mail[0][0]['system_admin_email']);
        }
        if ($tamplete_info[0][0]['regletter_cc'] && !empty($tamplete_info[0][0]['regletter_cc'])) {
            array_push($cc, $tamplete_info[0][0]['regletter_cc']);
        }
        $cc = empty($cc) ? null : implode(';', $cc);

        $sendResult = $vendorMailSender->send($mail_subject, $mail_content, $to_email, $cc);
        $current_datetime = date("Y-m-d H:i:s");
        $mail_content = pg_escape_string($mail_content);

        if ($sendResult['status'] == 0) {
            $sql = "insert into email_log (send_time, client_id, email_addresses, type,email_res, status,subject,content) values('$current_datetime',$id, '$to_email', 39,0,0,'$mail_subject','$mail_content')";
            $this->Signup->query($sql);
        } else {
            $sql = "insert into email_log (send_time, client_id, email_addresses, type,email_res, status,subject,content, error) values('$current_datetime',$id, '$to_email', 39,0,1,'$mail_subject','$mail_content', '{$sendResult['error']}')";
            $this->Signup->query($sql);
        }
    }
}
