<?php
class TransactionsController extends AppController
{

    var $name = 'Transactions';
    var $helpers = array('javascript', 'html', 'appTransactions', 'common');
    var $uses = array('Client', 'Transaction', 'Client', 'Systemparam');

    //private $Transaction;

    function index()
    {
        $this->redirect('client_tran_view');
    }

    public function add_payment($type = 'received')
    {
        Configure::write('debug', 0);
        $this->pageTitle = "Finance/Add Payment";
        $log_id = "";

        if ($this->RequestHandler->isPost()) {

            $flag = true;
            $client_id = $_POST['client_id'];
            $received_at = $_POST['received_at'];
            $note = @$_POST['note'];
            $payment_type = $_POST['type'];
            $amount = substr($_POST['amount'], 0, 15);
            $amount = preg_replace('/[\$￥,]/', '', $amount);
            $invoice_numbers = isset($_POST['invoice_number']) && count($_POST['invoice_number']) ? $_POST['invoice_number'] : array();
            $invoice_paids = isset($_POST['invoice_paid']) && count($_POST['invoice_paid']) ? $_POST['invoice_paid'] : array();

            $client_name_info = $this->Transaction->query("select name from client where client_id = {$client_id}");
            $client_name = $client_name_info[0][0]['name'];

            if ($type == 'received') {
                // Payment Received
                $pre_or_post = $payment_type == 0 ? 5 : 4;
                $client_payment_id = $this->Transaction->add_payment($client_id, $pre_or_post, $amount, $received_at, $note);
                if($client_payment_id !== false) {
                    $this->Transaction->add_ingress_balance($amount, $client_id);
                } else {
                    $flag = false;
                }
            } else {
                // Payment Sent
                $client_payment_id = $this->Transaction->add_payment($client_id, 3, $amount, $received_at, $note);
                if ($client_payment_id !== false) {
                    $this->Transaction->minus_egress_balance($amount, $client_id);
                } else {
                    $flag = false;
                }
            }
            if(!$flag) {
                $this->Transaction->create_json_array('', 101, "Payment for [{$client_name}] with the amount of [{$amount}] failed!");
                $this->Session->write('m', Transaction::set_validator());
                $this->redirect('/transactions/payment');
            }

            if ($payment_type == 1) {
                $remain_amount = $_POST['remain_amount'];
                // Received -> Invoice
                $count = count($invoice_numbers);
                for ($i = 0; $i < $count; $i++) {
                    $invoice_number = $invoice_numbers[$i];
                    $invoice_paid = floatval($invoice_paids[$i]);

                    if ($invoice_paid == 0) {
                        continue;
                    }

                    $this->Transaction->paid_invoice($client_payment_id, $invoice_number, $invoice_paid);
                }

                if ($remain_amount > 0) {
                    $this->Transaction->paid_invoice($client_payment_id, NULL, $remain_amount);
                }
            }


            $this->Transaction->change_low_balance_type($client_id);

            $log_id = $this->Transaction->logging(0, 'Payment', "Payment For:{$client_name} Amount:{$amount}");
            $this->Transaction->create_json_array('', 201, "Payment for [{$client_name}] with the amount of [{$amount}] is added successfully!");
            $this->Session->write('m', Transaction::set_validator());

//            发送邮件
            $auto_email_flg = $this->Transaction->query("select notify_carrier from system_parameter limit 1");
            $enable_payment_alert = $this->Transaction->query("select enable_payment_alert from client where client_id = {$client_id}");
            $enable_payment = false;
            if(isset($enable_payment_alert[0][0]['enable_payment_alert']) && $enable_payment_alert[0][0]['enable_payment_alert']){
                $enable_payment = true;
            }
            if ($auto_email_flg[0][0]['notify_carrier'] || $enable_payment){
                $php_path = Configure::read('php_exe_path');
                $cmd = "{$php_path} " . APP . "../cake/console/cake.php send_email 6 $client_payment_id $type /dev/null &";
                $info = $this->Systemparam->find('first',array(
                    'fields' => array('cmd_debug'),
                ));
                if(Configure::read('cmd.debug') && $info["Systemparam"]["cmd_debug"])
                {
                    file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
                }
                shell_exec($cmd);
            }
            $this->redirect('/transactions/payment');

        }

        if ($type == 'received') {
            $sql = "(SELECT  count(*) as has_invoice FROM  invoice WHERE client_id = \"Client\".client_id AND paid = FALSE
                        AND state in (0, 9) and total_amount > 0 AND type = 0 LIMIT 1) as has_invoice";
        } else {
            $sql = "(SELECT  count(*) as has_invoice FROM  invoice WHERE client_id = \"Client\".client_id AND paid = FALSE
                        AND state = 0 AND type = 3 and total_amount > 0 LIMIT 1) as has_invoice";
        }
        $carriers = $this->Client->find('all', array(
            'fields' => array(
                'client_id', 'name', 'mode', $sql
            ),
            'conditions' => array(
                'status' => true,
            ),
            'order' => 'name asc',
        ));

        $this->set('carriers', $carriers);
        $this->set('type', $type);
        if ($log_id) {
            $url_flug = "transactions-payment-incoming";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/transactions-payment-incoming");
        }

    }

    public function get_one_invoice()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $invoices = $_POST['invoices'];
        $client_id = $_POST['client_id'];
        $type = $_POST['type'];

        $invoice = $this->Transaction->get_one_invoice($client_id, $invoices, $type);

        echo json_encode($invoice);
    }

    public function exchange()
    {
        if (isset($_GET['start_time'])) {
            $start_time = $_GET['start_time'];
        } else {
            $start_time = date("Y-m-d 00:00:00");
        }
        $this->set('start_time', $start_time);
        if (isset($_GET['end_time'])) {
            $end_time = $_GET['end_time'];
        } else {
            $end_time = date("Y-m-d 23:59:59");
        }
        $this->set('end_time', $end_time);
        if (isset($_GET['is_search'])) {
            $search_type = $_GET['is_search'];
            $client_id = $_GET['client_id'];

            $begin_balance = $this->Client->get_exchange_begin_balance($start_time, $client_id);
            $data = array();
            $dur_records = $this->Client->get_exchange_transaction($client_id, $start_time, $end_time);
            foreach ($dur_records as $item) {
                $data[$item[0]['transaction_time']][$item[0]['transaction_type']] = $item[0]['amount'];
            }
            ksort($data);
            $client_name = $this->Client->get_client_name($client_id);
            if ($search_type == 2) {
                Configure::write('debug', 0);
                $this->autoRender = false;
                $this->autoLayout = false;
                $file = $this->_exchange_transaction_pdf($data, $client_id, $start_time, $end_time, $begin_balance);
                //echo $file;exit;

                $filename = $client_name . "_{$start_time}~{$end_time}" . '.pdf';

                header("Content-type: application/octet-stream");

                //处理中文文件名
                $ua = $_SERVER["HTTP_USER_AGENT"];
                $encoded_filename = rawurlencode($filename);
                if (preg_match("/MSIE/", $ua)) {
                    header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                } else if (preg_match("/Firefox/", $ua)) {
                    header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
                } else {
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                }

                //让Xsendfile发送文件
                readfile($file);
                exit;
            } elseif ($search_type == 3) {
                Configure::write('debug', 0);
                $this->autoRender = false;
                $this->autoLayout = false;
                $html = $this->_generate_exchange_transaction_html($data, $client_id, $start_time, $end_time, $begin_balance);
                $filename = $client_name . "_{$start_time}~{$end_time}" . '.xls';

                header("Content-type: application/octet-stream");

                //处理中文文件名
                $ua = $_SERVER["HTTP_USER_AGENT"];
                $encoded_filename = rawurlencode($filename);
                if (preg_match("/MSIE/", $ua)) {
                    header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                } else if (preg_match("/Firefox/", $ua)) {
                    header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
                } else {
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                }

                //让Xsendfile发送文件
                echo $html;
                exit;
            }
        } else {
            $data = NULL;
            $begin_balance = 0;
        }
        $this->set('begin_balance', $begin_balance);
        $this->set('data', $data);
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
    }

    /*
      public function exchange()
      {
      if (isset($_GET['start_time']))
      {
      $start_time = $_GET['start_time'];
      } else
      {
      $start_time = date("Y-m-d 00:00:00");
      }
      $this->set('start_time', $start_time);
      if (isset($_GET['end_time']))
      {
      $end_time = $_GET['end_time'];
      } else
      {
      $end_time = date("Y-m-d 23:59:59");
      }
      $this->set('end_time', $end_time);
      if (isset($_GET['is_search']))
      {
      $search_type = $_GET['is_search'];
      $client_id = $_GET['client_id'];

      $registe_time = $this->Client->get_create_time($client_id);

      $begin_balance = $this->Client->get_exchange_begin_balance($registe_time, $start_time, $client_id);
      $data = array();
      $dur_records = $this->Client->get_exchange_transaction($client_id, $start_time, $end_time);
      foreach ($dur_records as $item)
      {
      $data[$item[0]['time']][$item[0]['type']] = $item[0]['amount'];
      }
      ksort($data);
      $client_name = $this->Client->get_client_name($client_id);
      if ($search_type == 2)
      {
      Configure::write('debug', 0);
      $this->autoRender = false;
      $this->autoLayout = false;
      $file = $this->_exchange_transaction_pdf($data, $client_id, $start_time, $end_time, $begin_balance);
      $pdf_data = file_get_contents($file);
      header('Content-Type: application/pdf');
      header('Content-Length: ' . strlen($pdf_data));
      header('Content-Disposition: inline; filename="' . $client_name . "_{$start_time}~{$end_time}" . '.pdf"');
      header('Cache-Control: private, max-age=0, must-revalidate');
      header('Pragma: public');
      ini_set('zlib.output_compression', '0');
      die($pdf_data);
      }
      elseif ($search_type == 3)
      {
      Configure::write('debug', 0);
      $this->autoRender = false;
      $this->autoLayout = false;
      $html = $this->_generate_exchange_transaction_html($data, $client_id, $start_time, $end_time, $begin_balance);
      header('Content-Type: application/octet-stream');
      header('Content-Length: ' . strlen($pdf_data));
      header('Content-Disposition: inline; filename="' . $client_name . "_{$start_time}~{$end_time}" . '.xls"');
      header('Cache-Control: private, max-age=0, must-revalidate');
      header('Pragma: public');
      ini_set('zlib.output_compression', '0');
      die($html);
      }

      } else {
      $data = NULL;
      $begin_balance = 0;
      }
      $this->set('begin_balance', $begin_balance);
      $this->set('data', $data);
      $clients = $this->Transaction->get_clients();
      $this->set('clients', $clients);
      }
     * 
     */

    public function total_exchange_client_balance($deposit, $withdraw, $ingress, $egress, &$balance)
    {
        $balance = $deposit - $withdraw + $egress - $ingress + $balance;
        return $balance;
    }

    public function _generate_exchange_transaction_html($data, $client_id, $start_time, $end_time, $begin_balance)
    {
        $balance = number_format($begin_balance, 3);
        $row_array = array();
        $buy_total = 0;
        $sell_total = 0;
        $deposit_total = 0;
        $withdraw_total = 0;
        foreach ($data as $key => $item) {
            $temp1 = round((isset($item[1]) ? $item[1] : 0), 3);
            $temp2 = round((isset($item[2]) ? $item[2] : 0), 3);
            $temp3 = round((isset($item[3]) ? $item[3] : 0), 3);
            $temp4 = round((isset($item[4]) ? $item[4] : 0), 3);
            $r_balance = number_format((isset($item[0]) ? $item[0] : 0), 3);
            $row = <<<EOT
            <tr>
                <td>{$key}</td>
                <td>{$temp1}</td>
                <td>{$temp2}</td>
                <td>{$temp3}</td>
                <td>{$temp4}</td>
                <td>{$r_balance}</td>
            </tr>
EOT;
            array_push($row_array, $row);
            $buy_total += $temp1;
            $sell_total += $temp2;
            $deposit_total += $temp3;
            $withdraw_total += $temp4;
        }

        $rows = implode('', $row_array);
        $content = <<<EOT
        <h5>Begin Time: {$start_time} &nbsp; Begin Balance: {$balance}</h5>
        <table border="1" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th></td>
                    <th colspan="2">Cost</th>
                    <th colspan="2">Payment</th>
                    <th></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Buy</th>
                    <th>Sell</th>
                    <th>Deposit</th>
                    <th>Withdraw</th>
                    <th>Balance</th>
                </tr>
            </thead>  

            <tbody>
                {$rows}
            </tbody>
        </table>
        <br />
        <table border="1" style="width:100%;border-collapse:collapse;">
        <tr>
            <td>Buy Total</td>
            <td>$buy_total</td>
            <td>Sell Total</td>
            <td>$sell_total</td>
            <td>Deposit Total</td>
            <td>$deposit_total</td>
            <td>Withdraw Total</td>
            <td>$withdraw_total</td>
        </tr>
    </table>
EOT;
        return $content;
    }

    public function _exchange_transaction_pdf($data, $client_id, $start_time, $end_time, $begin_balance)
    {
        $content = $this->_generate_exchange_transaction_html($data, $client_id, $start_time, $end_time, $begin_balance);
        Configure::load('myconf');
        $balance_path = Configure::read('generate_balance.path');
        $actual_balance = $balance_path . DS . 'actual.pdf';
        $binexe = APP . 'binexec' . DS . 'wkhtmltopdf' . DS . 'wkhtmltopdf-amd64';
        $randomhtml = WWW_ROOT . 'upload' . DS . 'html' . DS . uniqid() . '.html';
        file_put_contents($randomhtml, $content);
        $blah = shell_exec("$binexe $randomhtml $actual_balance");
        return $actual_balance;
        /*
          $data = file_get_contents($actual_balance);
          header('Content-Type: application/pdf');
          header('Content-Length: ' . strlen($data));
          header('Content-Disposition: inline; filename="' . $client_name . "_{$start_time}~{$end_time}" . '.pdf"');
          header('Cache-Control: private, max-age=0, must-revalidate');
          header('Pragma: public');
          ini_set('zlib.output_compression', '0');
          die($data);
         * 
         */
    }

    public function exchange_result()
    {
        if ($this->RequestHandler->isPost()) {

        }
    }

    function edit_payment($id)
    {
        if (!$_SESSION['role_menu']['Management']['transactions']['model_w']) {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost()) {
            //------------edit alt
            $old_payment_data = $this->Transaction->find('first', Array('conditions' => Array('client_payment_id' => $id)));
            $this->data['Transaction']['client_payment_id'] = $id;
            if ($this->Transaction->xsave($this->data)) {//pr($old_payment_data);exit;
                $amount = $old_payment_data['Transaction']['amount'];
                if ($old_payment_data['Transaction']['approved'] == 't') {
                    $this->Transaction->clientBalanceOperation($old_payment_data['Transaction']['client_id'], $amount, 3);
                }
                if ($this->data['Transaction']['approved']) {
                    $this->Transaction->approve_payment(1, $this->data['Transaction']['client_payment_id'], $this->data['Transaction']['client_id']);
                }
                $this->Transaction->create_json_array('', 201, __('The payment is modified successfully', true));
            }
            $this->xredirect("/transactions/client_tran_view/"); // succ
        }
        $this->_render_edit_payment_data($id);
        $this->_render_edit_payment_options($id);
    }

    function _render_edit_payment_options($id)
    {
        $this->loadModel('Client');
        $this->set('ClientList', $this->Client->find('all', Array('fields' => Array('client_id', 'name'))));
    }

    function _render_edit_payment_data($id = '')
    {
        $this->data = $this->Transaction->find('first', Array('conditions' => Array('client_payment_id' => $id)));
    }

    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    /**
     * 初始化信息
     */
    function init_info()
    {
        $this->set('currency', $this->Transaction->find_currency());
        $this->set('user', $this->Transaction->findAllUser());
        $this->set('client', $this->Transaction->findClient());
    }

    public function notify_carrier($payment_id, $type)
    {
        ob_clean();
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "select receiving_time, amount, client.billing_email as email, client.company as company,
                client.name as client_name, client_payment.client_id
                 from client_payment 
                 left join client
                 on client_payment.client_id = client.client_id
                 where client_payment_id = {$payment_id}";
//            echo $sql;die;
        $result = $this->Transaction->query($sql);
//        pr($result);die;
        $receiving_time = strstr($result[0][0]['receiving_time'], ' ', TRUE);
//        $amount = sprintf("%.2f", substr(sprintf("%.3f", $result[0][0]['amount']), 0, -2));
        $amount = number_format( $result[0][0]['amount'], 2);
        $client_name = $result[0][0]['client_name'];
        $company = $result[0][0]['company'];


        $send_client_id = $result[0][0]['client_id'];
        $email = $result[0][0]['email'];
//        die(var_dump($result));
        if (!$email) {
            $this->Transaction->create_json_array('', 101, __('carriers_email_address_isnt_set', true));
            $this->Session->write('m', Transaction::set_validator());
            $this->xredirect("/transactions/payment/{$type}");
        }
        if ($type == 'incoming') {
            $sql = "select payment_received_subject as subject, payment_received_content as content, payment_received_cc as cc from mail_tmplate";
        } else {
            $sql = "select payment_sent_subject as subject, payment_sent_content as content, payment_sent_cc as cc from mail_tmplate";
        }
        $result = $this->Transaction->query($sql);
        $mail_subject = $result[0][0]['subject'];
        $mail_content = $result[0][0]['content'];
        $email_cc = $result[0][0]['cc'];

        $convert_table = array(
            '{amount}' => round(floatval($amount),2),
            '{receiving_time}' => $receiving_time,
            '{client_name}' => $client_name,
            '{company_name}' => $company,
            '{payment_sent_on}' => $receiving_time
        );

        $mail_subject = strtr($mail_subject, $convert_table);
        $mail_content = strtr($mail_content, $convert_table);

        $email_info = $this->Transaction->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username, 
                 emailpassword as  "password", emailname as "name", loginemail, smtp_secure,realm,workstation,system_admin_email FROM system_parameter');
        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer(true);
        if ($email_info[0][0]['loginemail'] === 'false') {
            $mailer->IsMail();
        } else {
            $mailer->IsSMTP();
        }
        //$mailer->SMTPDebug   = 2;
        $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
        $mailer->IsHTML(true);
        //$mailer->Mailer = 'mail';

        switch ($email_info[0][0]['smtp_secure']) {
            case 1:
//                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = $email_info[0][0]['realm'];
                $mailer->Workstation = $email_info[0][0]['workstation'];
        }
        try {
            $mailer->From = $email_info[0][0]['from'];
            $mailer->FromName = $email_info[0][0]['name'];
            $mailer->Host = $email_info[0][0]['smtphost'];
            $mailer->Port = intval($email_info[0][0]['smtpport']);
            $mailer->Username = $email_info[0][0]['username'];
            $mailer->Password = $email_info[0][0]['password'];
            $mailer->Subject = $mail_subject;
            $mailer->Body = $mail_content;
            $mailer->IsHTML(true);
            $mail_list = explode(';', trim($email));
            if (!empty($email_cc))
                $cc_list = explode(';', $email_cc);
            else
                $cc_list = array();

            $save_address = array();
            foreach ($mail_list as $email_address) {
                $save_address[] = $email_address;
                $mailer->AddAddress(trim($email_address));
            }
            foreach ($cc_list as $cc_item) {
                if (!empty($cc_item))
                    $mailer->AddCC($cc_item);
            }

            $billing_email = $email_info[0][0]['system_admin_email'];
            if (!empty($billing_email)) {
                $billing_email = explode(';', $billing_email);
                foreach($billing_email as $receiver){
                    $save_address[] = $receiver;
                    $mailer->AddAddress($receiver);
                }
            }

            if (strpos($email_info[0][0]['smtphost'], 'denovolab.com') !== false) {
                $mailer->Helo = 'demo.denovolab.com';
            }

            $save_address = implode(';', $save_address);
            if ($mailer->Send()) {
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "insert into email_log (send_time, client_id, email_addresses, type,email_res, status,subject,content) values('$current_datetime',{$send_client_id}, '$save_address', 21,0,0,'$mail_subject','$mail_content')";
                $this->Transaction->query($sql);
                $this->Transaction->create_json_array('', 201, __('Payment notification sent successfully!', true));
                $this->Session->write('m', Transaction::set_validator());
            } else {
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "insert into email_log (send_time, client_id, email_addresses, type,email_res, status,subject,content) values('$current_datetime',{$send_client_id}, '$save_address', 21,0,1,'$mail_subject','$mail_content')";
                $this->Transaction->query($sql);
                $this->Transaction->create_json_array('', 101, __('Failed!', true));
                $this->Session->write('m', Transaction::set_validator());
            }
        } catch (phpmailerException $e) {
            $error_message = $e->errorMessage();
            $test = htmlentities($error_message);
            $test1 = substr($test, 14);
            $message = substr($test1, 0, strpos($test1, '&lt'));
            $this->Transaction->create_json_array('', 101, __($message, true));
            $this->Session->write('m', Transaction::set_validator());
        }
//        echo "<script>window.history.back();</script>";
        $this->xredirect("/transactions/payment/{$type}");
    }

    /**
     * find sms_record
     */
    public function view($user_type = null, $search_res_id = null)
    {


        $this->init_info();
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 13 : $pageSize = $_GET['size'];

        if (!empty($_REQUEST['search'])) {
            $this->set('search', $_REQUEST['search']); //搜索设置
            $results = $this->Transaction->findAll($currPage, $pageSize, $search_res_1 - 01 - id, $user_type, $_REQUEST['search']);
        } else {
            $adv_search = '';
            $last_conditons = '&advsearch=1';
            if (!empty($this->params['form']) || !empty($_REQUEST['advsearch'])) {
                $f = empty($this->params['form']) ? $_REQUEST : $this->params['form'];
                if (!empty($f['start_amount'])) {
                    $adv_search .= " and a.amount::numeric(10,3) >= {$f['start_amount']}";
                    $last_conditons .= "&start_amount={$f['start_amount']}";
                }

                if (!empty($f['end_amount'])) {
                    $adv_search .= " and a.amount::numeric(10,3) <= {$f['end_amount']}";
                    $last_conditons .= "&end_amount={$f['end_amount']}";
                }

                if (!empty($f['start_date'])) {
                    $adv_search .= " and a.create_time >= '{$f['start_date']}'";
                    $last_conditons .= "&start_date={$f['start_date']}";
                }

                if (!empty($f['end_date'])) {
                    $adv_search .= " and a.create_time <= '{$f['end_date']}'";
                    $last_conditons .= "&end_date={$f['end_date']}";
                }

                if (!empty($f['tran_type'])) {
                    if ($f['tran_type'] == 'cdr') {
                        $adv_search .= " and a.tran_type = '1'";
                    } else {
                        $adv_search .= " and a.tran_type in ('0','2')";
                    }

                    $last_conditons .= "&tran_type={$f['tran_type']}";
                }


                if (!empty($f['tran_status'])) {
                    $adv_search .= " and a.approved = {$f['tran_status']}";
                    $last_conditons .= "&tran_status={$f['tran_status']}";
                }


                if (!empty($f['res_name'])) {
                    $rs_type = $f['res_type'];
                    if ($rs_type == 0) {//client
                        $adv_search .= " and id = (select client_id from client where name = '{$f['res_name']}')";
                    } else if ($rs_type == 2) {//reseller
                        $adv_search .= " and id = (select reseller_id from reseller where name = '{$f['res_name']}')";
                    } else if ($rs_type == 1) {//account
                        $adv_search .= " and id = (select card_id from card where card_number = '{$f['res_name']}')";
                    }
                    $last_conditons .= "&res_name={$f['res_name']}";
                }

                $this->set('last_conditons', $last_conditons);
                $this->set('searchForm', $f);
            }

            $results = $this->Transaction->findAll($currPage, $pageSize, $search_res_id, $user_type, null, $adv_search);
        }


        $this->set('p', $results);

        $this->set('user_type', $user_type);
        if (!empty($search_res_id)) {
            $this->set('extraSearch', true);
            if ($user_type == 2) {
                $this->set('backurl', '/exchange/resellers/reseller_list');
            } else if ($user_type == 3) {
                $this->set('backurl', '/exchange/clients/view');
            } else if ($user_type == 4) {
                $this->set('extraSearch', null);
            }
        }
    }

    /**
     * 路由伙伴交易记录
     */
    function _render_client_tran_view_data()
    {
        $this->set('p', $this->Transaction->client_tran_findAll(array('is_download' => false), $this->_order_condtions(array('time', 'client_name', 'client_cost', 'tran_type', 'current_balance'))));
        $this->loadModel('User');
        $user = $this->User->find('all', Array('fields' => Array('user_id', 'name')));
        $this->set('user', $user);
        if (array_keys_exists($this->params, 'url.advsearch')) {
            $this->set('extraSearch', $this->webroot . 'transactions/client_tran_view/');
        }
    }

    public function client_tran_view()
    {
        $this->pageTitle = "Management/Transaction";
        $this->_render_client_tran_view_data();
        extract($this->Transaction->get_real_period());
        $start_date = date('Y-m-d');
        $stop_date = date('Y-m-d');
        $start_time = date('00:00:00');
        $stop_time = date('23:59:59');
        $tz = $this->Transaction->get_sys_timezone();
        if (isset($_GET ['searchkey'])) {
            $start_date = $_GET ['start_date']; //开始日期
            $start_time = $_GET ['start_time']; //开始时间
            $stop_date = $_GET ['stop_date']; //结束日期
            $stop_time = $_GET ['stop_time']; //结束时间
            $tz = $_GET ['query'] ['tz']; //时区					
            $start_day = $start_date;
            $end_day = $stop_date;
        }
        $start = $start_date . '  ' . $start_time . "  " . $tz; //开始时间
        $end = $stop_date . '  ' . $stop_time . "  " . $tz; //结束时间

        $this->set("start", $start);
        $this->set("end", $end);
        $this->set("start_day", $start_day);
        $this->set("end_day", $end_day);
    }

    function client_tran_download()
    {
        Configure::write('debug', 0);
        $this->_catch_exception_msg(array('TransactionsController', '_client_tran_download_impl'));
    }

    function _client_tran_download_impl()
    {

        $this->Transaction->client_tran_findAll(array('is_download' => true));
        $this->layout = 'csv';
    }

    public function client_pay_view()
    {
        $this->pageTitle = "Management/Transaction";
        $this->set('invoice', $this->Transaction->query("select * from invoice where total_amount<0"));
        $this->set('p', $this->Transaction->client_pay_findAll(
            $this->_order_condtions(array('payment_time', 'amount', 'client_payment_id'))
        ));
        extract($this->Transaction->get_real_period());
        $start_date = date('Y-m-d');
        $stop_date = date('Y-m-d');
        $start_time = date('00:00:00');
        $stop_time = date('23:59:59');
        $tz = $this->Transaction->get_sys_timezone();
        if (isset($_GET ['searchkey'])) {
            $start_date = $_GET ['start_date']; //开始日期
            $start_time = $_GET ['start_time']; //开始时间
            $stop_date = $_GET ['stop_date']; //结束日期
            $stop_time = $_GET ['stop_time']; //结束时间
            $tz = $_GET ['query'] ['tz']; //时区					
            $start_day = $start_date;
            $end_day = $stop_date;
        }
        $start = $start_date . '  ' . $start_time . "  " . $tz; //开始时间
        $end = $stop_date . '  ' . $stop_time . "  " . $tz; //结束时间

        $this->set("start", $start);
        $this->set("end", $end);
        $this->set("start_day", $start_day);
        $this->set("end_day", $end_day);
    }

    function client_pay_download()
    {
        Configure::write('debug', 0);
        $this->_catch_exception_msg(array('TransactionsController', '_client_pay_download_impl'));
    }

    function _client_pay_download_impl($params = array())
    {
        $this->Transaction->client_pay_findAll($this->_order_condtions(array('payment_time', 'amount')), array('is_download' => true));
    }

    /**
     * 查看一个路由伙伴交易记录
     */
    public function client_tran_view_one()
    {

        $client_id = !empty($this->params['pass'][0]) ? $this->params['pass'][0] : '';
        $this->init_info();
        $this->set('p', $this->Transaction->client_tran_findAll_one($client_id));
    }

    public function refill_view_fail()
    {
        $this->init_info();
        $this->set('p', $this->Transaction->refill_findAll('false'));
    }

    public function refill_view_succ()
    {
        $this->init_info();

        $this->set('p', $this->Transaction->refill_findAll('true'));
    }

    public function refill_view()
    {
        $this->init_info();
        pr($_POST);
        $this->set('p', $this->Transaction->refill_findAll(''));
    }

    function export_csv()
    {

        $sql = "
 SELECT  name,  2 AS user_type, ra.reseller_id AS id, ra.time AS create_time, ra.cost AS amount, ra.cost_type AS tran_type, a.balance, ra.cause, ra.description
   FROM reseller_cost ra
   
   LEFT JOIN ( SELECT name, reseller_id  FROM reseller) re  ON re.reseller_id = ra.reseller_id
   
   LEFT JOIN ( SELECT reseller_balance.balance, reseller_balance.reseller_cost_id
           FROM reseller_balance) a ON a.reseller_cost_id = ra.reseller_cost_id
UNION 


 SELECT  name,  2 AS user_type, rb.reseller_id AS id, rb.payment_time AS create_time, rb.amount, rb.payment_method AS tran_type, b.balance, rb.cause, rb.description
   FROM reseller_payment rb
   LEFT JOIN ( SELECT name, reseller_id  FROM reseller) re  ON re.reseller_id = rb.reseller_id
   
   LEFT JOIN ( SELECT reseller_balance.balance, reseller_balance.reseller_payment_id
           FROM reseller_balance) b ON b.reseller_payment_id = rb.reseller_payment_id
UNION 


 SELECT   name,3 AS user_type, ca.client_id AS id, ca.time AS create_time, ca.cost AS amount, ca.cost_type AS tran_type, a.balance, ca.cause, ca.description
   FROM client_cost ca

   LEFT JOIN ( SELECT name, client_id  FROM client) ce  ON ce.client_id = ca.client_id
   LEFT JOIN ( SELECT c4_client_balance.balance, client_balance.client_cost_id
           FROM c4_client_balance) a ON a.client_cost_id = ca.client_cost_id
UNION 
 SELECT  name,  3 AS user_type, cb.client_id AS id, cb.payment_time AS create_time, cb.amount, cb.payment_method AS tran_type, b.balance, cb.cause, cb.description
   FROM client_payment cb
   
     LEFT JOIN ( SELECT name, client_id  FROM client) be  ON be.client_id = cb.client_id
   LEFT JOIN ( SELECT c4_client_balance.balance, client_balance.client_payment_id
           FROM c4_client_balance) b ON b.client_payment_id = cb.client_payment_id
UNION 


 SELECT  name,  4 AS user_type, ca.account_id AS id, ca.time AS create_time, ca.cost AS amount, ca.cost_type AS tran_type, a.balance, ca.cause, ca.description
   FROM account_cost ca

  LEFT JOIN ( SELECT card_number  as  name , card_id  FROM card) ce  ON ce.card_id = ca.account_id
   
   LEFT JOIN ( SELECT account_balance.balance, account_balance.account_cost_id
           FROM account_balance) a ON a.account_cost_id = ca.account_cost_id
UNION 
 SELECT  name, 4 AS user_type, cb.account_id AS id, cb.payment_time AS create_time, cb.amount, cb.payment_method AS tran_type, b.balance, cb.cause, cb.description
   FROM account_payment cb

     LEFT JOIN ( SELECT card_number  as  name , card_id  FROM card) ceb  ON ceb.card_id = cb.account_id
   LEFT JOIN ( SELECT account_balance.balance, account_balance.account_payment_id
           FROM account_balance) b ON b.account_payment_id = cb.account_payment_id



		";
        //$this->Transaction->query($sql);
        $this->Transaction->query(" COPY  ($sql)  TO   '/tmp/transation.csv'  CSV HEADER ;"); //daochu
        header('Content-type: application/html');
        header('Content-Disposition: attachment; filename="tmp.csv"');
        readfile('downloaded.txt');
        echo "这是一个下载文件";
    }

    public function update_refill()
    {
        if ($this->Transaction->update_refill($this->params['form'])) {
            $this->Transaction->create_json_array('', 201, __('update_suc', true));
        } else {
            $this->Transaction->create_json_array('', 101, __('update_fail', true));
        }

        $this->Session->write('m', Transaction::set_validator());
        $this->redirect('/transactions/refill_view');
    }

    /**
     *
     * 审核充值
     * @param unknown_type $user_type
     * @param unknown_type $payment_id
     * @param unknown_type $user_id
     */
    public function approved_client_tran_view($user_type, $payment_id, $user_id)
    {
        $this->_render_approved_impl($user_type, $payment_id, $user_id);
        $this->redirect("/transactions/client_tran_view/");
    }

    function _render_approved_impl($user_type, $payment_id, $user_id)
    {
        if ($this->Transaction->approve_payment($user_type, $payment_id, $user_id)) {
            $this->Transaction->create_json_array('', 201, __('manipulated_suc', true));
        } else {
            $this->Transaction->create_json_array('', 101, __('manipulated_fail', true));
        }
        $this->Session->write('m', Transaction::set_validator());
    }

    public function approved($user_type, $payment_id, $user_id)
    {
        $this->_render_approved_impl($user_type, $payment_id, $user_id);
        $this->redirect('/transactions/client_pay_view');
    }

    public function approve_selected($user_type, $all = null)
    {
        $ids = "{" . $_REQUEST['ids'] . "}";

        if (!empty($all)) {
            $ids = "{}";
        }

        if ($user_type == 2) {
            $qs = $this->Transaction->query("select * from approve_reseller_payment('$ids');");
            if ($qs[0][0]['approve_reseller_payment'] == 'true') {
                $this->Transaction->create_json_array('', 201, __('manipulated_suc', true));
            } else {
                $this->Transaction->create_json_array('', 101, __('manipulated_fail', true));
            }
        } else {
            $qs = $this->Transaction->query("select * from approve_client_payment('$ids');");
            if ($qs[0][0]['approve_client_payment'] == 'true') {
                $this->Transaction->create_json_array('', 201, __('manipulated_suc', true));
            } else {
                $this->Transaction->create_json_array('', 101, __('manipulated_fail', true));
            }
        }

        $this->Session->write('m', Transaction::set_validator());
        $this->redirect('/transactions/view/' . $user_type);
    }

    public function payment($type = "incoming")
    {
        //是否选择Upload
        if ($type == 'upload') {

            $this->render('payment_upload');
            return;
        }
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $gmt = '+00';
        if (isset($_GET['start'])) {
            $start = $_GET['start'] . " 00:00:00";
        }
        if (isset($_GET['end'])) {
            $end = $_GET['end'] . " 23:59:59";
        }
        if (isset($_GET['gmt'])) {
            $gmt = $_GET['gmt'];
        }
        if (isset($_GET['is_export'])) {
            $is_export = $_GET['is_export'];
        } else {
            $is_export = false;
        }

        $where = '';
        if (isset($_GET['client_id']) && !empty($_GET['client_id'])) {
            $where .= " and client_id = {$_GET['client_id']}";
        }
        if (isset($_GET['amount_a']) && !empty($_GET['amount_a'])) {
            $amount_a = preg_replace('/,/', '', $_GET['amount_a']);

            $where .= " and amount >= {$amount_a}";
            $amount_a = $_GET['amount_a'];
        } else {
            $amount_a = '';
        }
        if (isset($_GET['amount_b']) && !empty($_GET['amount_b'])) {
            $amount_b = preg_replace('/,/', '', $_GET['amount_b']);
            $where .= " and amount <= {$amount_b}";
            $amount_b = $_GET['amount_b'];
        } else {
            $amount_b = '';
        }
        if ($this->Session->read('login_type') == 2)
            $where .= "and exists (select 1 from agent_clients where client_id = client_payment.client_id)";


        $this->set("amount_a", $amount_a);
        $this->set("amount_b", $amount_b);
        if ($type == "incoming") {
            $payment_type = "(payment_type = 4 OR  payment_type = 5)";
        } else {
            $payment_type = "(payment_type = 3 OR payment_type = 6)";
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $counts = $this->Transaction->get_count_payments($payment_type, $start, $end, $gmt, $where);
//        die(var_dump($counts));
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "ORDER BY payment_time DESC, client_id DESC";

        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }
        $data = $this->Transaction->get_payments($payment_type, $start, $end, $gmt, $where, $pageSize, $offset, $is_export, $order_sql);
        foreach ($data as &$item) {
            $item[0]['invoices'] = $this->Transaction->get_invoice_payment($item[0]['client_payment_id']);
            $item[0]['sel_payment_type'] = ($item[0]['payment_type'] == '4') ? 'Invoice Payment' : 'Prepayment';
        }
        $page->setDataArray($data);
//        die(var_dump($data));
        $this->set('p', $page);
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
        $this->set('type', $type);

        //是否提交
        $is_get = false;
        if ((isset($_GET['is_get']) && $_GET['is_get'] == 1) || $type == 'incoming') {
            $is_get = true;
        }
        $this->set('is_get', $is_get);
    }

    public function payment_to_invoice($payment_invoice_id, $client_id, $type)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;

        if ($this->RequestHandler->isPost()) {
            $invoice_numbers = isset($_POST['invoice_number']) && count($_POST['invoice_number']) ? $_POST['invoice_number'] : array();
            $invoice_paids = isset($_POST['invoice_paid']) && count($_POST['invoice_paid']) ? $_POST['invoice_paid'] : array();
            $count = count($invoice_numbers);

            $payment_invoice = $this->Transaction->get_payment_invoice($payment_invoice_id);
            $remain_amount = $payment_invoice['amount'];

            for ($i = 0; $i < $count; $i++) {
                $invoice_number = $invoice_numbers[$i];
                $invoice_paid = floatval($invoice_paids[$i]);
                if ($invoice_paid < 0)
                    continue;

                $remain_amount -= $invoice_paid;

                $this->Transaction->paid_invoice($payment_invoice['payment_id'], $invoice_number, $invoice_paid);
            }

            if ($remain_amount == 0) {
                $this->Transaction->delete_payment_invoice($payment_invoice_id);
            } else {
                $this->Transaction->update_remain_payment_invoice($payment_invoice_id, $remain_amount);
            }
            $client_name_info = $this->Transaction->query("select name from client where client_id = {$client_id}");
            $client_name = $client_name_info[0][0]['name'];

            $this->Transaction->create_json_array('', 201, "Payment for  [{$client_name}] with the amount of [{$remain_amount}] is added successfully!");
            $this->Session->write('m', Transaction::set_validator());

            $this->xredirect('/transactions/payment/' . ($type == 'received' ? 'incoming' : 'outgoing'));
        }

        $this->set('payment_invoice_id', $payment_invoice_id);
        $this->set('client_id', $client_id);
        $this->set('type', $type);
    }

    public function offset()
    {
        $where = '';
        if (isset($_GET['client_id']) && !empty($_GET['client_id'])) {
            $where = " AND client_id = {$_GET['client_id']}";
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $counts = $this->Transaction->get_offsets_count($where);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $offsets = $this->Transaction->get_offsets($pageSize, $offset, $where);
        $page->setDataArray($offsets);
        $this->set('p', $page);
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
    }

    public function create_offset()
    {
        if ($this->RequestHandler->isPost()) {
            $client = $_POST['client'];
            $amount = $_POST['amount'];
            $createby = $_SESSION['sst_user_name'];
            $this->Transaction->insert_offset($client, $amount, $createby);
            $this->Transaction->create_json_array('', 201, __('The offset of [%s] is created successfully', true, $amount));
            $this->Session->write('m', Transaction::set_validator());
            $this->redirect('/transactions/offset');
        }
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
    }

    public function delete_payment($payment_id)
    {
        $userName = $_SESSION['sst_user_name'];
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->Transaction->begin();
        $sql1 = "SELECT payment_type, amount, client_id, payment_time, receiving_time - interval '24 hours' as start_time	 FROM client_payment WHERE client_payment_id = {$payment_id}";
        $ba_result = $this->Transaction->query($sql1);
        $type = $ba_result[0][0]['payment_type'];
        $amount = $ba_result[0][0]['amount'];
        $client_id = $ba_result[0][0]['client_id'];
        $paymentTime = $ba_result[0][0]['payment_time'];
        $beginTime = $ba_result[0][0]['start_time'];

        if (date('Y-m-d', strtotime($paymentTime)) == date('Y-m-d')) {
            if (in_array($type, array('4', '5'))) {
                $this->Transaction->clientBalanceOperation($client_id, $amount, 3);
            } elseif (in_array($type, array('3', '6'))) {
                $this->Transaction->clientBalanceOperation($client_id, $amount, 4);
            }
        } else {
            $this->Transaction->balanceDailyResetTask($beginTime, 1, $client_id);
        }



        $sql = "SELECT name FROM client where client_id = {$client_id}";
        $data = $this->Transaction->query($sql);
        $client_name = $data[0][0]['name'];
        $sql = "select invoice_id, amount from payment_invoice where payment_id  = {$payment_id} and invoice_id is not null";
        $result = $this->Transaction->query($sql);
        foreach ($result as $item) {
            $sql_invoice = "update invoice set pay_amount = pay_amount - {$item[0]['amount']}, paid = false where invoice_id = {$item[0]['invoice_id']}";
            $this->Transaction->query($sql_invoice);
        }

        $sql = "DELETE from payment_invoice where payment_id  = {$payment_id}";
        $this->Transaction->query($sql);
        $sql = "DELETE FROM client_payment WHERE client_payment_id = {$payment_id}";
        $this->Transaction->query($sql);
        $this->Transaction->commit();
        $log_amount = number_format($amount, 2);
        $log_id = $this->Transaction->logging(1, 'Payment', "Payment For:{$client_name}[amount:{$log_amount}]");
        $this->Transaction->create_json_array('', 201, 'The payment of ' . $log_amount . ' is deleted succesfully!');
        $this->Session->write('m', Transaction::set_validator());

        $url_flug = "transactions-payment?".$this->params['getUrl'];
        $this->modify_log_noty($log_id, $url_flug);
//        $this->xredirect("/logging/index/{$log_id}/transactions-payment");
    }

    public function mutual_transaction()
    {

        $client_id = 1257;
        if (isset($_GET['start_time'])) {
            $start_time = $_GET['start_time'];
        } else {
            $start_time = date("Y-m-d 00:00:00");
        }
        $this->set('start_time', $start_time);
        if (isset($_GET['end_time'])) {
            $end_time = $_GET['end_time'];
        } else {
            $end_time = date("Y-m-d 23:59:59");
        }
        $this->set('end_time', $end_time);
        $reset_balance_info = $this->Client->get_reset_balance($client_id);
        if (!empty($reset_balance_info)) {
            $reset_balance = $reset_balance_info[0][0]['amount'];
            $reset_time = $reset_balance_info[0][0]['payment_time'];
        } else {
            $reset_balance = 0;
            $reset_time = $this->Client->get_create_time($client_id);
        }

        if (strtotime($start_time) <= strtotime($reset_time)) {
            $start_time = $reset_time;
        }

        if (strtotime($end_time) <= strtotime($reset_time)) {
            $end_time = $reset_time;
        }
        // 获取begin_balance
        if ($start_time == $reset_time) {
            $begin_balance = $reset_balance;
        } else {
            $begin_balance = $this->Client->get_begin_balance($reset_time, $start_time, $client_id) + $reset_balance;
        }
    }

    public function mutual()
    {
        $all_type = array(
            'all', 'payment received', 'payment sent', 'invoice received', 'invoice sent', 'credit note received', 'credit not sent',
            'debit note received', 'debit note sent', 'reset', 'egress actual usage', 'ingress actual usage'
        );

        $start_time = $start = date("Y-m-01 00:00:00");
        $end_time = $end = date("Y-m-d 23:59:59");
        $gmt = "+0000";
        $type = 0;
        if (isset($_GET['search'])) {
            if (isset($_GET['start'])) {
                $start_time = $start = $_GET['start'];
            }

            if (isset($_GET['end'])) {
                $end_time = $end = $_GET['end'];
            }
            if (isset($_GET['gmt'])) {
                $gmt = $_GET['gmt'];
                $start .= $gmt;
                $end .= $gmt;
            }

            if (isset($_GET['client_id'])) {
                $client_id = $_GET['client_id'];
            }

            if (isset($_GET['type'])) {
                $type = $_GET['type'];
            }


            $this->loadModel('Client');
            $reset_balance_info = $this->Client->get_reset_balance($client_id, $start);
            if (!empty($reset_balance_info)) {
                $reset_balance = $reset_balance_info[0][0]['amount'];
                $reset_time = $reset_balance_info[0][0]['payment_time'];
            } else {
                $reset_balance = 0;
                $reset_time = $this->Client->get_create_time($client_id);
            }

            if ($start == $reset_time) {
                $begin_balance = $reset_balance;
            } else {
                $begin_balance = $this->Client->get_begin_balance_mutual($reset_time, $start, $client_id) + $reset_balance;
            }

            $this->set('begin_balance', $begin_balance);
            $data = $this->Transaction->get_client_mutual_transaction($start, $end, $client_id, $type);
//            pr($this->params);die;
            if ($_GET['is_down'] == '1') {
                $this->generate_mutual_pdf($data, $client_id, $begin_balance, $all_type, $start, $start_time, $end_time);
            }
            $flg = 1;
        } else {
            $data = '';
            $flg = '';
        }

        //修改弹出效果
        if (empty($data) && $flg) {
            $this->Session->write('m', $this->Transaction->create_json(101, __('No Data Matches Search Criteria.', true)));

        }
        $clients = $this->Transaction->get_clients();
        $this->set('data', $data);
        $this->set('flg', $flg);
        $this->set('clients', $clients);
        $this->set('startdate', $start_time);
        $this->set('enddate', $end_time);
        $this->set('all_type', $all_type);
        $this->set('gmt', $gmt);
    }

    public function actual()
    {
        $all_type = array(
            'all', 'payment received', 'payment sent', '', '', 'credit note received', 'credit not sent',
            'debit note received', 'debit note sent', 'reset', 'egress actual usage', 'ingress actual usage', 'short charges'
        );
        $start_time = $start = '';
        $end_time = $end = '';
        $gmt = "+0000";
        $type = 0;
        if (isset($_GET['search'])) {
            if (isset($_GET['start'])) {
                $start = $_GET['start'];
            }

            if (isset($_GET['end'])) {
                $end = $_GET['end'];
            }
            $start_time = $start;
            $end_time = $end;
            if (isset($_GET['query']['tz'])) {
                $gmt = $_GET['query']['tz'];
                $start .= $gmt;
                $end .= $gmt;
            }


            if (isset($_GET['client_id'])) {
                $client_id = $_GET['client_id'];
            }

            if (isset($_GET['type'])) {
                $type = $_GET['type'];
            }


            $this->loadModel('Client');
            $reset_balance_info = $this->Client->get_reset_balance($client_id, $start);
            if (!empty($reset_balance_info)) {
                $reset_balance = $reset_balance_info[0][0]['amount'];
                $reset_time = $reset_balance_info[0][0]['payment_time'];
            } else {
                $reset_balance = 0;
                $reset_time = $this->Client->get_create_time($client_id);
            }
            if ($start == $reset_time) {
                $begin_balance = $reset_balance;
            } else {
                $begin_balance = $this->Client->get_begin_balance($reset_time, $start, $client_id) + $reset_balance;
            }

            $this->set('begin_balance', $begin_balance);
            $data = $this->Transaction->get_client_actual_transaction($start, $end, $client_id, $type);
            $totalValues = $this->Transaction->getActualTotalValues($client_id, $start, $end);

            if ($_GET['is_down'] == '1') {
                $this->generate_actual_pdf($data, $client_id, $begin_balance, $all_type, $start, $start_time, $end_time);

            }
            $flg = 1;
        } else {
            $data = '';
            $flg = "";
            $totalValues = null;
        }
        $clients = $this->Transaction->get_clients();

        $this->set('data', $data);
        $this->set('totalValues', $totalValues);
        $this->set('flg', $flg);
        $this->set('clients', $clients);
        $this->set('startdate', $start_time);
        $this->set('enddate', $end_time);
        $this->set('all_type', $all_type);
        $this->set('gmt', $gmt);
    }

    public function generate_actual_pdf($data, $client_id, $begin_balance, $all_type, $begin_date, $start_time, $end_time)
    {
        Configure::write('debug', 0);
        Configure::load('myconf');
        //$logo = APP . 'webroot/images/logo.png';
        $logo_path = '../webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';

        if (file_exists($logo_path)) {
            $logo = $logo_path;
        } else {
            $logo = '../webroot' . DS . 'images' . DS . 'logo.png';
        }
        $balance_forward = round($begin_balance, 2);

        list($client_name, $company, $address) = $this->Transaction->get_company_address($client_id);

        $date = date("m/d/y");
        $stylesheet = <<<EOT
<style type="text/css">  
* {font-size:12px;}
.clear {clear:both;}
#header h1 {float:left;}
#header h2 {float:right;}  
#content table {border-collapse:collapse;}
</style>   
EOT;
        $type_total = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $content = "";

        foreach ($data as $item) {
            $i_date = date("Y-m-d H:i:sO", strtotime($item[0]['a']));
            $i_type = $all_type[$item[0]['b']];
            $i_amount = round($item[0]['d'], 2);
            $type_total[$item[0]['b']] += $item[0]['d'];
            $i_balance = round($this->total_balance_for_actual($item[0]['d'], $item[0]['b'], $begin_balance), 2);
            $i_carrier = $item[0]['c'];
            $content .= "<tr><td>$i_date</td><td>$i_type</td><td>$i_carrier</td><td>$i_amount</td><td>$i_balance</td></tr>";
        }
        $payment_received_total = round($type_total[1], 2);
        $payment_sent_total = round($type_total[2], 2);
        $credit_note_received = round($type_total[5], 2);
        $credit_note_sent = round($type_total[6], 2);
        $ingress_reset = round($type_total[9], 2);
        $debit_note_received = round($type_total[7], 2);
        $debit_note_sent = round($type_total[8], 2);
        $egress_actual_usage = round($type_total[10], 2);
        $ingress_actual_usage = round($type_total[11], 2);
        $short_charges = round($type_total[12], 2);


        $html = <<<EOT
    $stylesheet
    <div id="header">
        <h1><img src="$logo" /></h1>
        <h2>Statement</h2>
    </div>
    <br class="clear" />
    <div id="content">
        <table border="1" style="float:left;width:25%">
             <thead>
                <tr>
                    <th>To:</th>
                </tr>
             </thead>
             <tbody>
                <tr>
                    <td>
$company <br />
Accounts Payable <br />
$address
                    </td>
                </tr>
             </tbody>
        </table>
        <br class="clear" />
        <table border="1" style="text-align:center;float:right;">
             <thead>
                <tr>
                    <th>Date</th>
                </tr>
             </thead>
             <tbody>
                <tr>
                    <td>
                        $date
                    </td>
                </tr>
             </tbody>
        </table>
        <br class="clear" />
        <table border="1" style="border:none;width:100%">
            <tr>
                <td>Begin Date</td>
                <td>$begin_date</td>
                <td>End Date</td>
                <td>$end_time</td>
                <td>Begin Balance</td>
                <td>$balance_forward</td>
            </tr>
        </table>
        <br class="clear" />
        <table border="1" style="border:none;width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Carrier</th>
                    <th>Amount</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                $content
            </tbody>
            
        </table>
        <br class="clear" />
        <table border="1" style="border:none;width:100%">
                    <tr>
                        <td>payment received total:</td>
                        <td>$payment_received_total</td>
                        <td>payment sent total:</td>
                        <td>$payment_sent_total</td>
                        <td>credit note received:</td>
                        <td>$credit_note_received</td>
                        <td>credit note sent:</td>
                        <td>$credit_note_sent</td>
                        <td>ingress reset:</td>
                        <td>$ingress_reset</td>
                    </tr>
                    <tr>
                        <td>debit note received:</td>
                        <td>$debit_note_received</td>
                        <td>debit note sent:</td>
                        <td>$debit_note_sent</td>
                        <td>egress actual usage:</td>
                        <td>$egress_actual_usage</td>
                        <td>ingress actual usage:</td>
                        <td>$ingress_actual_usage</td>
                        <td>short charges</td>
                        <td>$short_charges</td>
                    </tr>
                </table>
    </div>

EOT;
        ob_start();
        App::import("Vendor", "other", array('file' => 'tcpdf/tcpdf.php'));
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->setPageUnit('pt');
        $font_size = $pdf->pixelsToUnits('14');
        $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
        $pdf->writeHTML($html, true, 0, true, 0);
        $pdf->lastPage();
        $pdf->Output('htmlout.pdf', 'I');
        ob_end_flush();
        exit;


//        $balance_path = Configure::read('generate_balance.path');
//        $actual_balance = $balance_path . DS . 'actual_' . rand() . '.pdf';
//        $binexe = APP . 'binexec' . DS . 'wkhtmltopdf' . DS . 'wkhtmltopdf-amd64';
//        $randomhtml = WWW_ROOT . 'upload' . DS . 'html' . DS . uniqid() . '.html';
//        file_put_contents($randomhtml, $html);
//        $blah = shell_exec("$binexe $randomhtml $actual_balance");
//        $data = file_get_contents($actual_balance);
//        header('Content-Type: force-download');
//        header('Content-Length: ' . strlen($data));
//        header('Content-Disposition: inline; filename="' . $client_name . "_{$start_time}~{$end_time}" . '.pdf"');
//        header('Cache-Control: private, max-age=0, must-revalidate');
//        header('Pragma: public');
//        ini_set('zlib.output_compression', '0');
//        die($data);
    }

    public function generate_mutual_pdf($data, $client_id, $begin_balance, $all_type, $begin_date, $start_time, $end_time)
    {
        $url = $this->getUrl();
        Configure::write('debug', 0);
        Configure::load('myconf');
        $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';
        if (file_exists($logo_path)) {
            $logo = $logo_path;
        } else {
            $logo = $url . DS . 'images' . DS . 'logo.png';
        }
        $balance_forward = round($begin_balance, 2);
        list($client_name, $company, $address) = $this->Transaction->get_company_address($client_id);

        $date = date("m/d/y");
        $stylesheet = <<<EOT
<style type="text/css">  
* {font-size:12px;}
.clear {clear:both;}
#header h1 {float:left;}
#header h2 {float:right;}  
#content table {border-collapse:collapse;}
</style>   
EOT;
        $type_total = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $content = "";

        foreach ($data as $item) {
            $i_date = date("Y-m-d H:i:sO", strtotime($item[0]['a']));
            $i_type = $all_type[$item[0]['b']];
            $i_amount = round($item[0]['d'], 2);
            $type_total[$item[0]['b']] += $item[0]['d'];
            $i_carrier = $item[0]['c'];
            $i_balance = round($this->total_balance_for_mutual($item[0]['d'], $item[0]['b'], $begin_balance), 2);
            $content .= "<tr><td>$i_date</td><td>$i_type</td><td>$i_carrier</td><td>$i_amount</td><td>$i_balance</td></tr>";
        }

        $payment_received_total = round($type_total[1], 2);
        $payment_sent_total = round($type_total[2], 2);
        $credit_note_received = round($type_total[5], 2);
        $credit_note_sent = round($type_total[6], 2);
        $ingress_reset = round($type_total[9], 2);
        $debit_note_received = round($type_total[7], 2);
        $debit_note_sent = round($type_total[8], 2);
        $invoice_received = round($type_total[3], 2);
        $invoice_sent = round($type_total[4], 2);


        $html = <<<EOT
    $stylesheet
    <div id="header">
        <h1><img src="$logo" /></h1>
        <h2>Statement</h2>
    </div>
    <br class="clear" />
    <div id="content">
        <table border="1" style="float:left;width:25%">
             <thead>
                <tr>
                    <th>To:</th>
                </tr>
             </thead>
             <tbody>
                <tr>
                    <td>
$company <br />
Accounts Payable <br />
$address
                    </td>
                </tr>
             </tbody>
        </table>
        <br class="clear" />
        <table border="1" style="text-align:center;float:right;">
             <thead>
                <tr>
                    <th>Date</th>
                </tr>
             </thead>
             <tbody>
                <tr>
                    <td>
                        $date
                    </td>
                </tr>
             </tbody>
        </table>
        <br class="clear" />
        <table border="1" style="border:none;width:100%">
            <tr>
                <td>Begin Date</td>
                <td>$begin_date</td>
                <td>Begin Balance</td>
                <td>$balance_forward</td>
            </tr>
        </table>
        <br class="clear" />
        <table border="1" style="border:none;width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Carrier</th>
                    <th>Amount</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                $content
            </tbody>
            
        </table>
        <br class="clear" />
        <table border="1" style="border:none;width:100%">
            <thead></thead>
            <tr>
                <td>Payment Received Total:</td>
                <td>$payment_received_total</td>
                <td>Payment Sent Total:</td>
                <td>$payment_sent_total</td>
                <td>Credit Note Received:</td>
                <td>$credit_note_received</td>
                <td>Credit Note Sent:</td>
                <td>$credit_note_sent</td>
                <td>Ingress Reset:</td>
                <td>$ingress_reset</td>
            </tr>
            <tr>
                <td>Debit Note Received:</td>
                <td>$debit_note_received</td>
                <td>Debit Note Sent:</td>
                <td>$debit_note_sent</td>
                <td>Invoice Received:</td>
                <td>$invoice_received</td>
                <td>Invoice Sent:</td>
                <td>$invoice_sent</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

EOT;
        ob_start();
        App::import("Vendor", "other", array('file' => 'tcpdf/tcpdf.php'));
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->setPageUnit('pt');
        $font_size = $pdf->pixelsToUnits('14');
        $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
        $pdf->writeHTML($html, true, 0, true, 0);
        $pdf->lastPage();
        $pdf->Output('htmlout.pdf', 'I');
        ob_end_flush();
        exit;

//        $balance_path = Configure::read('generate_balance.path');
//        $actual_balance = $balance_path . DS . 'actual.pdf';
//        $binexe = APP . 'binexec' . DS . 'wkhtmltopdf' . DS . 'wkhtmltopdf-amd64';
//        $randomhtml = WWW_ROOT . 'upload' . DS . 'html' . DS . uniqid() . '.html';
//        file_put_contents($randomhtml, $html);
//        $blah = shell_exec("$binexe $randomhtml $actual_balance");
//        $data = file_get_contents($actual_balance);
//        header('Content-Type: force-download');
//        header('Content-Length: ' . strlen($data));
//        header('Content-Disposition: inline; filename="' . $client_name . "_{$start_time}~{$end_time}" . '.pdf"');
//        header('Cache-Control: private, max-age=0, must-revalidate');
//        header('Pragma: public');
//        ini_set('zlib.output_compression', '0');
//        die($data);
    }

    public function getUrl()
    {
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
        {

            $url = 'https://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $this->webroot;
        }
        else
        {
            $url = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $this->webroot;
        }
        return $url;
    }

    public function total_balance_for_actual($val, $type, &$balance)
    {
        switch ((int)$type) {
            case 1:
                $balance += $val;
                break;
            case 2:
                $balance -= $val;
                break;
            case 5:
                $balance -= $val;
                break;
            case 6:
                $balance += $val;
                break;
            case 7:
                $balance += $val;
                break;
            case 8:
                $balance -= $val;
                break;
            case 10:
                $balance += $val;
                break;
            case 11:
                $balance -= $val;
                break;
        }
        return $balance;
    }

    public function total_balance_for_mutual($val, $type, &$balance)
    {
        switch ((int)$type) {
            case 1:
                $balance += $val;
                break;
            case 2:
                $balance -= $val;
                break;
            case 3:
                $balance += $val;
                break;
            case 4:
                $balance -= $val;
                break;
            case 5:
                $balance -= $val;
                break;
            case 6:
                $balance += $val;
                break;
            case 7:
                $balance += $val;
                break;
            case 8:
                $balance -= $val;
                break;
            case 9:
                $balance = $val;
                break;
        }
        return $balance;
    }

    public function re_transaction()
    {
        if ($this->RequestHandler->isPost()) {
            /*
              $cmd = "php " . APP. "transaction.php {$_POST['from']} {$_POST['to']}";
              $result = shell_exec($cmd);
             */
            $client_id = $_POST['client'];
            $start_date = $_POST['from'];
            $end_date = $_POST['to'];
            $this->Transaction->del_history_actual_balance($client_id, $start_date, $end_date);
            $this->Transaction->update_to_actual_balance($client_id, $start_date, $end_date);
            $actual_balances = $this->Transaction->get_select_actual_balance($client_id, $start_date, $end_date);
            foreach ($actual_balances as $actual_balance) {
                $last_balance = $this->Transaction->get_last_actual_balance($client_id, $actual_balance[0]['id']);
                $balance = 0;
                switch ($actual_balance[0]['type']) {
                    case 1:
                        $balance = $last_balance + $actual_balance[0]['amount'];
                        break;
                    case 2:
                        $balance = $last_balance - $actual_balance[0]['amount'];
                        break;
                    case 3:
                        $balance = $last_balance - $actual_balance[0]['amount'];
                        break;
                    case 4:
                        $balance = $last_balance + $actual_balance[0]['amount'];
                        break;
                    case 5:
                        $balance = $last_balance + $actual_balance[0]['amount'];
                        break;
                    case 6:
                        $balance = $last_balance - $actual_balance[0]['amount'];
                        break;
                }

                $this->Transaction->update_actual_balance($actual_balance[0]['id'], $balance);
            }

            $this->Transaction->del_history_mutual_balance($client_id, $start_date, $end_date);
            $this->Transaction->update_to_mutual_balance($client_id, $start_date, $end_date);
            $mutual_balances = $this->Transaction->get_select_mutual_balance($client_id, $start_date, $end_date);
            foreach ($mutual_balances as $mutual_balance) {
                $last_balance = $this->Transaction->get_last_mutual_balance($client_id, $mutual_balance[0]['id']);
                $balance = 0;
                switch ($mutual_balance[0]['type']) {
                    case 1:
                        $balance = $last_balance + $mutual_balance[0]['amount'];
                        break;
                    case 2:
                        $balance = $last_balance - $mutual_balance[0]['amount'];
                        break;
                    case 3:
                        $balance = $last_balance - $mutual_balance[0]['amount'];
                        break;
                    case 4:
                        $balance = $last_balance + $mutual_balance[0]['amount'];
                        break;
                    case 5:
                        $balance = $last_balance + $mutual_balance[0]['amount'];
                        break;
                    case 6:
                        $balance = $last_balance - $mutual_balance[0]['amount'];
                        break;
                }

                $this->Transaction->update_mutual_balance($mutual_balance[0]['id'], $balance);
            }


            $this->Transaction->create_json_array('', 201, __("Successfully", true));
            $this->Session->write('m', Transaction::set_validator());
        }
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
    }

    function get_note()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = $_POST['id'];
        $sql = "select description from client_payment where client_payment_id = {$id}";
        $result = $this->Transaction->query($sql);
        echo $result[0][0]['description'];
    }

}

?>
