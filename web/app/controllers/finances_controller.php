<?php

class FinancesController extends AppController
{

    var $name = 'Finances';
    //var $helpers = array('javascript','html','appFinances');
    var $components = array('RequestHandler');
    var $helpers = array('common');
    var $uses = array('Cdr', 'Finance', 'Client', 'FinanceHistory', 'Refill', 'pr.Invoice', 'FinanceHistoryActual', 'Systemparam');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        Configure::load('myconf');
        return true;
        if ($this->params['action'] == 'get_mutual_ingress_detail' || $this->params['action'] == 'get_mutual_egress_detail' || $this->params['action'] == 'get_mutual_ingress_egress_detail' || $this->params['action'] == 'get_actual_ingress_detail' || $this->params['action'] == 'get_actual_egress_detail' || $this->params['action'] == 'get_actual_ingress_egress_detail' || $this->params['action'] == 'regenerate' || $this->params['action'] == 'regenerate_reset' || $this->params['action'] == 'mass_add')
        {
            Configure::load('myconf');
            return true;
        }
        parent::beforeFilter();
    }

    function index()
    {
        $this->redirect('view');
    }

    public function notify_carrier($payment_id)
    {
        ob_clean();
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
//        $sql = "select action_time as receiving_time, actual_amount as amount,
//(select email from client where client.client_id = exchange_finance.client_id) as email
//             from exchange_finance  where id =  {$payment_id}";
//        $result = $this->Finance->query($sql);
        $result = array();
        $receiving_time = strstr($result[0][0]['receiving_time'], ' ', TRUE);
        $amount = sprintf("%.2f", substr(sprintf("%.3f", $result[0][0]['amount']), 0, -2));
        $email = $result[0][0]['email'];

        $sql = "select payment_received_subject as subject, payment_received_content as content from mail_tmplate";
        $result = $this->Finance->query($sql);
        $mail_subject = $result[0][0]['subject'];
        $mail_content = $result[0][0]['content'];

        $convert_table = array(
            '{amount}' => round(floatval($amount),2),
            '{receiving_time}' => $receiving_time,
        );

        $mail_subject = strtr($mail_subject, $convert_table);
        $mail_content = strtr($mail_content, $convert_table);

        $email_info = $this->Finance->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username, 
                 emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation,system_admin_email, loginemail FROM system_parameter');
        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer(true);
        $mailer->IsSMTP();
        //$mailer->SMTPDebug   = 2;
        $mailer->SMTPAuth = $email_info[0][0]['loginemail'];
        //$mailer->Mailer = 'mail';
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
        $mailer->IsHTML(true);
        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtphost'];
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        $mailer->Subject = $mail_subject;
        $mailer->Body = $mail_content;
        $mailer->IsHTML(false);
        $mail_list = explode(';', $email);
        foreach ($mail_list as $email_address)
        {
            $mailer->AddAddress($email_address);
        }
        $billing_email = $email_info[0][0]['system_admin_email'];
        $mailer->AddAddress($billing_email);

        if ($mailer->Send())
        {
            $this->Finance->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Finance::set_validator());
        }
        else
        {
            $this->Finance->create_json_array('', 201, __('Failed!', true));
            $this->Session->write('m', Finance::set_validator());
        }

        $this->xredirect("/finances/view");
    }

    /**
     * 	finance列表并根据查询条件分页搜索
     */
    function view()
    {
        Configure::write('debug', 0);
        $this->pageTitle = "Management/Finance";

        $currPage = 1;
        $pageSize = 100;
        $search_arr = array();
        $order_arr = array();


        if (!empty($_REQUEST['order_by']))
        {
            $order_by = explode("-", $_REQUEST['order_by']);
            $order_arr[$order_by[0]] = $order_by[1];
        }
        else
        {
            $order_arr['action_time'] = 'desc';
        }

        if (!empty($_REQUEST['search']))
        {   //模糊查询
            $search_type = 0;
            $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
        }
        else
        {                      //按条件搜索
            $search_type = 1;
            $search_arr['start_date'] = !empty($_REQUEST['start_date']) ? ($_REQUEST['start_date']) : '';
            $search_arr['end_date'] = !empty($_REQUEST['end_date']) ? ($_REQUEST['end_date']) : '';
            $search_arr['action_type'] = !empty($_GET['action_type']) ? intval($_GET['action_type']) : 0;
            $search_arr['status'] = isset($_REQUEST['tran_status']) ? $_REQUEST['tran_status'] : '';
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

        if (!empty($_GET['out_put']) && $_GET['out_put'] == 'csv')
        {
            $action_type = !empty($_GET['action_type']) ? intval($_GET['action_type']) : 0;
            $data = $this->Finance->ListAllFinance($search_arr, $search_type, $order_arr);
            //var_dump($data);
            //exit();
            $status_val = array(0 => 'Confirmed', 1 => 'Waiting', 2 => 'Complete', 3 => 'Refused', 4 => 'In Process');
            if ($action_type == 2)
            {
                $file_name = "wire_in.csv";
            }
            else
            {
                $file_name = "wire_out.csv";
            }

            $file_name = str_ireplace(' ', '_', $file_name);
            ob_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename={$file_name}");
            header("Content-Transfer-Encoding: binary ");
            echo "Serail Number" . ",";
            echo "Type" . ",";
            echo "Method" . ",";
            echo "Amount" . ",";
            echo "Fee" . ",";
            echo "Transaction Date" . ",";
            echo "Status" . ",";
            echo "Completed Date" . ",";
            echo "Carrier";
            if ($action_type == 2)
            {
                echo ",Company";
                echo ",Email";
            }

            echo "\n";
            foreach ($data as $value)
            {
                echo $value[0]['action_number'] . ",";
                echo ($value[0]['action_type'] == 2 ? 'Wire In' : 'Wire Out') . ",";
                echo ($value[0]['action_method'] == 1 ? 'Bank Wire' : 'Paypal') . ",";
                echo round($value[0]['amount'], 2) . ",";
                echo round($value[0]['action_fee'], 2) . ",";
                echo $value[0]['action_time'] . ",";
                echo $status_val[$value[0]['status']] . ",";
                echo $value[0]['complete_time'] . ",";
                echo $value[0]['name'] . ",";
                if ($action_type == 2)
                {
                    echo ",{$value[0]['payer_company']}";
                    echo ",{$value[0]['payer_email']},";
                }
                echo "\n";
            }
            exit();
        }
        else if (!empty($_GET['out_put']) && $_GET['out_put'] == 'xls')
        {
            $action_type = !empty($_GET['action_type']) ? intval($_GET['action_type']) : 0;
            $data = $this->Finance->ListAllFinance($search_arr, $search_type, $order_arr);
            $status_val = array(0 => 'Confirmed', 1 => 'Waiting', 2 => 'Complete', 3 => 'Refused', 4 => 'In Process');
            if ($action_type == 2)
            {
                $file_name = "wire_in.xls";
            }
            else
            {
                $file_name = "wire_out.xls";
            }
            $file_name = str_ireplace(' ', '_', $file_name);
            ob_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename={$file_name}");
            header("Content-Transfer-Encoding: binary ");
            echo "Serail Number" . "	";
            echo "Type" . "	";
            echo "Method" . "	";
            echo "Amount" . "	";
            echo "Fee" . "	";
            echo "Transaction Date" . "	";
            echo "Status" . "	";
            echo "Completed Date" . "	";
            echo "Carrier";
            if ($action_type == 2)
            {
                echo "	";
                echo "Company   ";
                echo "Email";
            }

            echo "\n";
            foreach ($data as $value)
            {
                echo $value[0]['action_number'] . "	";
                echo ($value[0]['action_type'] == 2 ? 'Wire In' : 'Wire Out') . "	";
                echo ($value[0]['action_method'] == 1 ? 'Bank Wire' : 'Paypal') . "	";
                echo round($value[0]['amount'], 2) . "	";
                echo round($value[0]['action_fee'], 2) . "	";
                echo $value[0]['action_time'] . "	";
                echo $status_val[$value[0]['status']] . "	";
                echo $value[0]['complete_time'] . "	";
                echo $value[0]['name'] . "	";
                if ($action_type == 2)
                {
                    echo "{$value[0]['payer_company']}" . "	";
                    echo "{$value[0]['payer_email']}" . "	";
                }
                echo "\n";
            }
            exit();
        }
        else
        {
            $results = $this->Finance->ListFinance($currPage, $pageSize, $search_arr, $search_type, $order_arr);
            $this->set('p', $results);
        }
    }

//function  edit_finance($id){
    function edit_finance($id = null)
    {
        if (!$_SESSION['role_menu']['Finance']['finances']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Edit Finance";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_finance_impl'), array('id' => $id));
        $this->_render_finance_save_options();
        $this->render('edit_finance');
        $this->Session->write('m', Finance::set_validator());
    }

    public function mass_add($client_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;

        $incomings = $this->Refill->get_unpaid_invoices($client_id, 'incoming');
        $outgoings = $this->Refill->get_unpaid_invoices($client_id, 'outgoing');

        $this->set('incomings', $incomings);
        $this->set('outgoings', $outgoings);
        $this->set('client_id', $client_id);
    }

    public function quickadd()
    {
        $this->autoLayout = false;
        $this->autoRender = false;

        $back_url = $_POST['back_url'];
        $payment_receiveds = isset($_POST['payment_receiveds']) ? count($_POST['payment_receiveds']) : 0;
        $payment_sents = isset($_POST['payment_sents']) ? count($_POST['payment_sents']) : 0;
        $incoming_invoices = isset($_POST['incoming_invoices']) ? count($_POST['incoming_invoices']) : 0;
        $client_id = $_POST['client_id'];

        for ($i = 0; $i < $payment_receiveds; $i++)
        {
            $payment_received_type = $_POST['payment_received_types'][$i];
            $payment_received_number = $_POST['payment_received_numbers'][$i];
            $payment_received_date = $_POST['payment_received_dates'][$i];
            $payment_received_amount = $_POST['payment_received_amounts'][$i];

            if ($payment_received_type == 0)
            {
                $c_ba = $this->Refill->clientBalanceOperation($client_id, $payment_received_amount, 2, true);
                $sql = "INSERT INTO client_payment(client_id, payment_type, amount,  current_balance,payment_time, result, receiving_time, description)
VALUES ({$client_id},5,{$payment_received_amount}, {$c_ba[0][0]['balance']},'now', TRUE, '{$payment_received_date}', '')";
                $this->Refill->query($sql);
            }
            else
            {
                $this->Refill->update_unpaid_invoice($payment_received_number, $payment_received_amount, 'true');
                $current_balance = $this->Refill->update_carrier_balance($payment_received_amount, $client_id, '+', 'ingress_balance');
                $this->Refill->create_payment_record($client_id, 4, $payment_received_amount, $current_balance, $payment_received_number, $payment_received_date, '');
            }
        }

        for ($i = 0; $i < $payment_sents; $i++)
        {
            $payment_sent_type = $_POST['payment_sent_types'][$i];
            $payment_sent_number = $_POST['payment_sent_numbers'][$i];
            $payment_sent_date = $_POST['payment_sent_dates'][$i];
            $payment_sent_amount = $_POST['payment_sent_amounts'][$i];

            if ($payment_sent_type == 0)
            {
                $c_ba = $this->Refill->clientBalanceOperation($client_id, $payment_sent_amount, 5, true);
                $sql = "INSERT INTO client_payment(client_id, payment_type, amount,  current_balance,payment_time, result, receiving_time, description)
    VALUES ({$client_id},6,{$payment_sent_amount}, {$c_ba[0][0]['balance']},'now', TRUE, '{$payment_sent_date}', '')";
                $this->Refill->query($sql);
            }
            else
            {
                $this->Refill->update_unpaid_invoice($payment_sent_number, $payment_sent_amount, 'true');
                $current_balance = $this->Refill->update_carrier_balance($payment_sent_amount, $client_id, '-', 'egress_balance');
                $this->Refill->create_payment_record($client_id, 3, $payment_sent_amount, $current_balance, $payment_received_number, $payment_received_date, '');
            }
        }
        ;

        for ($i = 0; $i < $incoming_invoices; $i++)
        {
            $incoming_invoice_period = $_POST['incoming_invoice_periods'][$i];
            $incoming_invoice_to = $_POST['incoming_invoice_tos'][$i];
            $incoming_invoice_timezone = $_POST['incoming_invoice_timezones'][$i];
            $incoming_invoice_date = $_POST['incoming_invoice_dates'][$i];
            $incoming_invoice_due_dates = $_POST['incoming_invoice_due_dates'][$i];
            $incoming_invoice_amounts = $_POST['incoming_invoice_amounts'][$i];


            $sql = "SELECT nextval('class4_seq_invoice_no'::regclass) AS next_number";
            $invoice_number_result = $this->Invoice->query($sql, false);
            $invoice_number = $invoice_number_result[0][0]['next_number'];
            $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
            $invoice_number_result = $this->Invoice->query($sql);
            if ($invoice_number_result[0][0]['count'] > 0)
            {
                $sql = "SELECT setval('class4_seq_invoice_no', (select max(invoice_number::bigint)+1 from invoice)) as next_number";
                $invoice_number_result = $this->Invoice->query($sql);
                $invoice_number = $invoice_number_result[0][0]['next_number'];
            }

            $sql = <<<EOT
    
   INSERT INTO 

invoice(invoice_number,client_id, invoice_time, invoice_start,invoice_end, 
due_date, type, invoice_zone, pdf_path, total_amount, current_balance, pay_amount)

VALUES('$invoice_number', '{$client_id}', '{$incoming_invoice_date}', TIMESTAMP '{$incoming_invoice_period} {$incoming_invoice_timezone}', 

TIMESTAMP '{$incoming_invoice_to} {$incoming_invoice_timezone}', '{$incoming_invoice_due_dates}',3, '{$incoming_invoice_timezone}', '', {$incoming_invoice_amounts}, 
(SELECT balance::numeric FROM c4_client_balance WHERE client_id = '{$client_id}')
,0)

EOT;

            $this->Invoice->query($sql);
        }

        $this->Refill->create_json_array("", 201, 'Succeeded!');
        $this->Session->write('m', Refill::set_validator());
        $this->redirect("/$back_url");
    }

    /**
     * 	对提交表单的处理
     * @param $params 传递的表单值
     */
    function _add_finance_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_finance_data($this->params['form']);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $this->data = $this->Finance->find("first", Array('conditions' => array('Finance.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                }
                elseif ($this->data['Finance']['action_type'] == 1 && $this->data['Finance']['status'] == 3)
                {
                    $this->redirect_denied();
                    //throw new Exception("Permission denied");//Waiting For Mail Confirm or Already Completed");
                }
                else
                {
                    $this->set('p', $this->data['Finance']); //pr($this->data['Finance']);									
                }
            }
            else
            {
                //void
            }
        }
    }

    /**
     * 	处理传递的form信息并保存或创建finance信息（这里只有保存，创建部分在exchange）
     * @param unknown_type $params 传递的form信息
     */
    function _create_or_update_finance_data($params = array())
    {   #update		
        //var_dump($params);
        if (isset($params['id']) && !empty($params['id']))
        {
            $banace_validate = false;
            $id = (int) $params ['id'];
//							if(!$this->check_form($id)){
//								return;
//							}			
            //$this->data ['Finance'] = $params['data']['Finance'];
            $this->data ['Finance'] ['id'] = $id;
            $this->data ['Finance'] ['complete_time'] = date("Y-m-d H:i:s +00");
            //var_dump($this->data['Finance']);exit;							
            //$finance_old = $this->Finance->query("select * from exchange_finance where id = " . $this->data['Finance']['id']);
            $finance_old = $this->Finance->getFinanceInfo($this->data['Finance']['id']);
            //var_dump($finance_old);exit;			
            $this->data ['Finance']['amount'] = floatval($this->data ['Finance']['amount']);
            $this->data ['Finance']['actual_amount'] = floatval($this->data ['Finance']['actual_amount']);
            $this->data ['Finance']['action_fee'] = floatval($this->data ['Finance']['action_fee']);

            if ($this->data['Finance']['action_type'] == 2)
            {//wire in
                if ($this->data ['Finance']['amount'] > 0 && $this->data ['Finance']['actual_amount'] == $this->data ['Finance']['amount'] - $this->data ['Finance']['action_fee'] && $this->data ['Finance']['action_fee'] >= 0 && $this->data ['Finance']['action_fee'] <= $this->data ['Finance']['actual_amount'])
                {
                    $banace_validate = true;
                }
                else
                {
                    $this->Finance->create_json_array('', 101, 'Finance , Wire Out edit Fail! Transaction balance is not correct.');
                }
            }
            elseif ($this->data['Finance']['action_type'] == 1)
            { //wire out
                if ($this->data ['Finance']['amount'] == $this->data ['Finance']['actual_amount'])
                {
                    $banace_validate = true;
                }
                elseif ($this->data ['Finance']['status'] == 3)
                {  //refuse不需要验证
                    $banace_validate = true;
                }
                elseif ($this->data ['Finance']['status'] == 2)
                {
                    //echo 'wwww';
                    //var_dump($this->data ['Finance']['amount'] > 0 && $this->data ['Finance']['actual_amount'] == $this->data ['Finance']['amount'] + $this->data ['Finance']['action_fee'] && $this->data ['Finance']['action_fee'] >= 0 && $this->data ['Finance']['action_fee'] <= $this->data ['Finance']['actual_amount']);
                    if ($this->data ['Finance']['amount'] > 0 && $this->data ['Finance']['actual_amount'] == $this->data ['Finance']['amount'] - $this->data ['Finance']['action_fee'] && $this->data ['Finance']['action_fee'] >= 0 && $this->data ['Finance']['action_fee'] <= $this->data ['Finance']['actual_amount'])
                    {
                        $banace_validate = true;
                    }
                }
                else
                {
                    $this->Finance->create_json_array('', 101, 'Finance , Wire In edit Fail! Transaction balance is not correct.');
                }
            }
            else
            {
                //void
            }

            /* if ($finance_old[0][0]['status'] > $this->data ['Finance']['status'] || ($this->data['Finance']['action_type'] == 2) && $finance_old[0][0]['status'] == 0 && $this->data ['Finance']['status'] == 1) {  //必须状态保持原来的或修改为完成/refuse
              $banace_validate = false;
              $this->Finance->create_json_array('', 101, 'Finance , edit Fail! Transaction status is not correct.');
              } */


            if ($banace_validate)
            {
                if ($this->Finance->save($this->data))
                {
                    //wire in
                    if (!empty($this->data['Finance']['action_type']) && $this->data['Finance']['action_type'] == 2)
                    {
                        $balance = 0;

                        if ($finance_old[0][0]['status'] == 2)
                        {
                            $balance -= $finance_old[0][0]['actual_amount'];
                        }
                        if ($this->data ['Finance']['status'] == 2)
                        {
                            $balance += $this->data['Finance']['actual_amount'];
                        }
                        //var_dump($balance);
                        if (!empty($balance))
                        {
                            $this->Finance->addClientBalance($balance, $finance_old[0][0]['client_id']);
                        }
                    }
                    elseif (!empty($this->data['Finance']['action_type']) && $this->data['Finance']['action_type'] == 1)
                    {
                        $balance = 0;
                        if ($this->data ['Finance']['status'] == 3 && $finance_old[0][0]['status'] != 3)
                        {   //refuse
                            $balance = $balance + $this->data['Finance']['amount'] + $this->data['Finance']['action_fee'];
                        }
                        if (!empty($balance))
                        {
                            $this->Finance->addClientBalance($balance, $finance_old[0][0]['client_id']);
                        }
                    }
                    else
                    {
                        //void
                    }

                    if ($this->data ['Finance']['status'] == 2)
                    {
                        //发送finance邮件
                        $finance_id = $id;
                        require_once(APP . 'vendors/finance_mail.php');
                    }

                    //$this->Finance->log('edit_finance');
                    //$this->Finance->create_json_array('',201,'Finance , Edit successfullyfully!');
                    $this->Finance->create_json_array('', 201, __('The Finance id [%s] is modified successfully.', true, $this->data['Finance']['id']));
                    $this->xredirect('/finances/view');
                    //	$this->redirect ( array ('id' => $id ) );
                }
            }
            else
            {
                //$this->Finance->create_json_array('',101,'Finance , edit Fail! Transaction balance is not correct.');
                $this->xredirect('/finances/edit_finance/' . $id);
            }
        }
        # add
        else
        {
            //void
        }
    }

    /**
     * 	获取所有finance信息
     */
    function _render_finance_save_options()
    {
        $this->loadModel('Finance');
        $this->set('FinanceList', $this->Finance->find('all')); //,Array('fields'=>Array('id','name'))));
    }

    function past_due_log()
    {
        require_once 'MyPage.php';
        empty($_GET['page']) ? $currpage = 1 : $currpage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $page = new MyPage();
        $count = $this->Finance->past_due_log_count();
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currpage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by id DESC";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "order by {$field} {$sort}";
            }
        }
        $data = $this->Finance->past_due_log($pageSize, $offset, $order_sql);
        $page->setDataArray($data);
        $this->set('p', $page);
    }

    public function view_past_due_log($id)
    {
        $sql = "SELECT mail_sub, mail_content, pdf_file FROM invoice_email WHERE id = {$id}";
        $result = $this->Finance->query($sql);
        $this->set('data', $result);
    }

    public function resend_past_due($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT 
invoice_email.invoice_no,
billing_email,
pdf_file, 
mail_sub,
mail_content
FROM invoice_email
JOIN
invoice ON invoice_email.invoice_no = invoice.invoice_number 
LEFT JOIN client ON invoice.client_id = client.client_id
WHERE invoice_email.id =  {$id}";
        $result = $this->Finance->query($sql);
        $email_info = $this->Finance->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname as "name" FROM system_parameter');
        App::import('Vendor', 'mail/sendmail');
        $mailer = new phpmailer();
        $mailer->IsSMTP();
        $mailer->SMTPAuth = true;
        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtphost'];
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        $mailer->AddAddress($result[0][0]['billing_email']);
        $mailer->Subject = $result[0][0]['mail_sub'];
        $mailer->ClearAttachments();
        $real_path = APP . '/webroot/upload/invoice/' . $result[0][0]['pdf_file'];
        $mailer->AddAttachment($real_path);
        $mailer->Body = $result[0][0]['mail_content'];
        if ($mailer->Send())
        {
            $sql = "INSERT INTO invoice_email (invoice_no, send_time, mail_sub, mail_content, send_address, pdf_file) VALUES ('{$result[0][0]['invoice_no']}', CURRENT_TIMESTAMP, '{$result[0][0]['mail_sub']}', '{$result[0][0]['mail_content']}', '{$result[0][0]['billing_email']}', '{$result[0][0]['pdf_file']}')";
            $this->Finance->query($sql);
        }
        $this->Finance->create_json_array('', 201, 'Successfully.');
        $this->redirect('/finances/past_due_log');
    }

    function mass_invoice_generation()
    {
        $this->pageTitle = "Management/Mass Invoice Generation";

        $currPage = 1;
        $pageSize = 100;
        $search_arr = array();
        $order_arr = array();


        if (!empty($_REQUEST['order_by']))
        {
            $order_by = explode("-", $_REQUEST['order_by']);
            $order_arr[$order_by[0]] = $order_by[1];
        }
        else
        {
            $order_arr['name'] = 'desc';
        }

        if (!empty($_REQUEST['carrier_name']))
        {   //模糊查询
            $search_arr['carrier_name'] = !empty($_REQUEST['carrier_name']) ? $_REQUEST['carrier_name'] : '';
        }

        if (!empty($_REQUEST['payment_term']))
        {   //模糊查询
            $search_arr['payment_term'] = !empty($_REQUEST['payment_term']) ? $_REQUEST['payment_term'] : '';
        }

        if (!empty($_REQUEST['balance']) && !empty($_REQUEST['bal']))
        {   //模糊查询
            $search_arr['balance'] = !empty($_REQUEST['balance']) ? $_REQUEST['bal'] . ' ' . $_REQUEST['balance'] : '';
        }

        if (!empty($_REQUEST['unin_amount']))
        {   //模糊查询
            $search_arr['unin_amount'] = !empty($_REQUEST['unin_amount']) ? $_REQUEST['unin_amount'] : '';
        }

        if (!empty($_REQUEST['next_invoice_date']))
        {   //模糊查询
            $search_arr['next_invoice_date'] = !empty($_REQUEST['next_invoice_date']) ? $_REQUEST['next_invoice_date'] . ' CURRENT_DATE' : '';
        }


        if (!empty($_REQUEST['invoice_compare']) && !empty($_REQUEST['invoice_till_date']) && !empty($_REQUEST['invoice_till_time']))
        {   //模糊查询
            $search_arr['invoice_compare'] = !empty($_REQUEST['invoice_till_date']) ? $_REQUEST['invoice_compare'] . " '" . $_REQUEST['invoice_till_date'] . ' ' . $_REQUEST['invoice_till_time'] . ' ' . $_REQUEST ['query']['tz'] . "'" : '';
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

        //var_dump($search_arr);

        $this->set('payment_terms', $this->Finance->query('select payment_term_id ,name from payment_term'));
        $results = $this->Finance->ListInvoice($currPage, $pageSize, $search_arr, $order_arr);
        $this->set('p', $results);
    }

    function generate_invoice($ids)
    {
        $this->autoRender = false;

        $ids = explode(',', $ids);

        $error_flag = $this->validate($ids);
        if ($error_flag != '1')
        {

            $this->data['Invoice']['invoice_time'] = !empty($_POST ['invoice_time']) ? $_POST['invoice_time'] : '';
            $this->data['Invoice']['invoice_number'] = !empty($_POST ['invoice_number']) ? $_POST['invoice_number'] : '';
            //		$this->data['Invoice']['invoice_number'] = !empty($_POST ['invoice_number'])?$_POST['invoice_number']:$seq.substr(time(),0,6);
            $this->data['Invoice']['state'] = intval(0);
            $this->data['Invoice']['type'] = intval($_POST ['type']);
            $this->data['Invoice']['create_type'] = 1;
            $this->data['Invoice']['due_date'] = $_POST ['due_date'];
            $this->data['Invoice']['invoice_zone'] = $_POST['query']['tz'];
            $start_date = $_POST ['start_date']; //开始日期
            $start_time = $_POST ['start_time']; //开始时间
            $stop_date = $_POST ['stop_date']; //结束日期
            $stop_time = $_POST ['stop_time']; //结束时间
            $tz = $_POST ['query']['tz']; //结束时间
            $this->data['Invoice']['invoice_start'] = $start_date . '  ' . $start_time . ' ' . $tz; //开始时间
            $this->data['Invoice']['invoice_end'] = $stop_date . '  ' . $stop_time . ' ' . $tz; //结束时间
            foreach ($ids as $id)
            {
                $this->data['Invoice']['client_id'] = $id;
                $list = $this->Finance->query("select  balance   from  c4_client_balance  where client_id='{$id}'");
                $this->data['Invoice']['current_balance'] = !empty($list[0][0]['balance']) ? $list[0][0]['balance'] : '0.000';
                $this->Finance->begin();
                if ($this->data['Invoice']['type'] != 2)
                {
                    $r = $this->Finance->query("select  *  from  create_client_invoice(
                                                    {$this->data['Invoice']['client_id']},'{$this->data['Invoice']['invoice_start']}',
                                                    '{$this->data['Invoice']['invoice_end']}','{$this->data['Invoice']['invoice_number']}','{$_POST ['due_date']}',{$this->data['Invoice']['type']},'{$this->data['Invoice']['invoice_time']}')");
                }
                else
                {
                    $r0 = $this->Finance->query("select  *  from  create_client_invoice(
                                                    {$this->data['Invoice']['client_id']},'{$this->data['Invoice']['invoice_start']}',
                                                    '{$this->data['Invoice']['invoice_end']}','{$this->data['Invoice']['invoice_number']}','{$_POST ['due_date']}', 0,'{$this->data['Invoice']['invoice_time']}')");
                    $r = $this->Finance->query("select  *  from  create_client_invoice(
                                                    {$this->data['Invoice']['client_id']},'{$this->data['Invoice']['invoice_start']}',
                                                    '{$this->data['Invoice']['invoice_end']}','{$this->data['Invoice']['invoice_number']}','{$_POST ['due_date']}', 1,'{$this->data['Invoice']['invoice_time']}')");
                }
                if (!is_array($r))
                {
                    $this->Finance->rollback();
                }
                else
                {
                    $this->Finance->query("UPDATE invoice SET invoice_zone = '{$_POST['query']['tz']}' WHERE invoice_number = '{$r[0][0]['create_client_invoice']}'");
                    $this->Finance->commit();
                }
            }
            $this->xredirect('/finances/carrier_invoice/');
        }

        $this->xredirect('/finances/mass_invoice_generation/');
    }

    public function carrier_invoice($invoice_id = null)
    {

        $this->pageTitle = "Management/Carrier Invoice";

        $currPage = 1;
        $pageSize = 100;
        $search_arr = array();
        $order_arr = array();


        if (!empty($_REQUEST['order_by']))
        {
            $order_by = explode("-", $_REQUEST['order_by']);
            $order_arr[$order_by[0]] = $order_by[1];
        }
        else
        {
            $order_arr['name'] = 'desc';
        }

        if (!empty($_REQUEST['search']))
        {   //模糊查询
            $search_type = 0;
            $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
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


        $results = $this->Finance->ListCarrierInvoice($currPage, $pageSize, $search_arr, $order_arr);
        $this->set('p', $results);


        if ($invoice_id != null)
        {
            $sql = "select client.company,invoice.invoice_number,client.billing_email,invoice_start,invoice_end from invoice left join client on invoice.client_id = client.client_id where invoice_id = {$invoice_id}";
            $result = $this->Finance->query($sql);
            $email_info = $this->Finance->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname as "name" FROM system_parameter');
            if ($result[0][0]['billing_email'] == '')
            {
                $this->Finance->create_json_array('', 101, __('Biling email does not exists!', true));
                $this->Session->write('m', Finance::set_validator());
            }
            else
            {
                $send_address = $result[0][0]['billing_email'];

                //$this->createpdf_invoice($result[0][0]['invoice_number']);

                $real_path = $this->createpdf_invoice($result[0][0]['invoice_number']);


                $template = $this->Finance->query("select carrier_invoice_subject,carrier_invoice_content from mail_tmplate limit 1");

                $subject = str_ireplace("{start_date}", $result[0][0]['invoice_start'], $template[0][0]['carrier_invoice_subject']);
                $subject = str_ireplace("{end_date}", $result[0][0]['invoice_end'], $subject);

                $content = str_ireplace("{start_date}", $result[0][0]['invoice_start'], $template[0][0]['carrier_invoice_content']);
                ;
                $content = str_ireplace("{end_date}", $result[0][0]['invoice_end'], $content);
                $content = str_ireplace("{invoice_number}", $result[0][0]['invoice_number'], $content);
                $content = str_ireplace("{company_name}", $result[0][0]['company'], $content);

                App::import('Vendor', 'nmail/phpmailer');
                $mailer = new phpmailer();
                $mailer->IsSMTP();
                $mailer->SMTPAuth = true;
                $mailer->From = $email_info[0][0]['from'];
                $mailer->FromName = $email_info[0][0]['name'];
                $mailer->Host = $email_info[0][0]['smtphost'];
                $mailer->Port = intval($email_info[0][0]['smtpport']);
                $mailer->Username = $email_info[0][0]['username'];
                $mailer->Password = $email_info[0][0]['password'];
                $mailer->AddAddress($send_address);
                $mailer->Subject = $subject;
                $mailer->ClearAttachments();
                //$real_path = APP . '/webroot/upload/invoice/' . $result[0][0]['pdf_file'];
                $mailer->AddAttachment($real_path);
                $mailer->Body = $content;
                if ($mailer->Send())
                {
                    $sql = "INSERT INTO invoice_email (invoice_no, send_time, mail_sub, mail_content, send_address, pdf_file) VALUES ('{$result[0][0]['invoice_no']}', CURRENT_TIMESTAMP, '{$subject}', '{$content}', '{$result[0][0]['billing_email']}', '{$real_path}')";
                    $this->Finance->query($sql);
                    $this->Finance->create_json_array('', 201, __('send email Successfully!', true));
                    $this->Session->write('m', Finance::set_validator());
                }
                else
                {
                    $errorinfo = $mailer->ErrorInfo;
                    $this->Finance->create_json_array('', 101, __($errorinfo, true));
                    $this->Session->write('m', Finance::set_validator());
                }
                $this->xredirect('/finances/carrier_invoice');
            }
        }
    }

    public function createpdf_invoice($invoice_number, $return = true)
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        App::import("Model", 'pr.Invoice');
        $invoice_model = new Invoice;
        $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
        $url = $this->getUrl();
        $html = $invoice_model->generate_pdf_content($invoice_number, $url, $num_format);
        $this->autoRender = false;

        $path = APP . 'webroot' . DS . 'upload' . DS . 'invoice' . DS;
        $filename = uniqid() . '.pdf';
        $invoice_file = $path . $filename;

        App::import("Vendor", "other", array('file' => 'tcpdf/tcpdf.php'));
        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->setPageUnit('pt');
        $font_size = $pdf->pixelsToUnits('14');
        $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
        $pdf->writeHTML($html, true, 0, true, 0);
        $pdf->lastPage();
        $pdf->Output($invoice_file, 'F');
        ob_end_flush();

        $data=file_get_contents($invoice_file);
        if ($return == false)
        {
            header('Content-Type: application/pdf');
            header('Content-Length: ' . strlen($data));
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            ini_set('zlib.output_compression', '0');
            die($data);
        }
        return $invoice_file;
    }

    public function validate($ids)
    {
        $error_flag = 'false';

        $invoice_number = "";
        //$state = $_POST ['state'];
        $type = $_POST ['type'];
        $due_date = $_POST ['due_date'];
        //$total_amount = $_POST ['total_amount'];
        $start_date = $_POST ['start_date']; //开始日期
        $stop_date = $_POST ['stop_date']; //结束日期
        $gmt = $_POST["query"]['tz'];


        #  check invoice number  Repeatability
        if (!empty($invoice_number))
        {
            $c = $this->Finance->query("select  count(*)  from invoice  where  invoice_number='$invoice_number';");
            if ($c[0][0]['count'] > 0)
            {
                $this->Finance->create_json_array('#invoice_number', 101, 'invoice Number Repeatability');
                $error_flag = true;
            }
        }
        else
        {
            $type_where = $type == 2 ? " and (\"type\" = 0 or \"type\" = 1)" : (" and \"type\" = " . intval($type) );
            $carrier_names = array();
            foreach ($ids as $id)
            {
                $dupli_sql = "select count(*) as cnt from invoice where state != -1 and client_id = '{$id}' and ( (invoice_end >= TIMESTAMP '{$start_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL and invoice_start <= TIMESTAMP '{$start_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL) or (invoice_end >= TIMESTAMP '{$stop_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL and invoice_start <= TIMESTAMP '{$stop_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL) ) {$type_where}";
                $c = $this->Finance->query($dupli_sql);
                if ($c[0][0]['cnt'] > 0)
                {

                    $carrier_name = $this->Finance->query("select name from client where client_id = " . $id);
                    $carrier_names[$id] = $carrier_name[0][0]['name'];
                    $error_flag = true;
                }
            }
            if (count($carrier_names) > 0)
            {
                $this->Finance->create_json_array('#query-start_date-wDt', 101, '[' . implode(',', $carrier_names) . ']Invoice Period duplicate');
            }
            //check invoice date duplicate
        }

        if (empty($due_date))
        {
            $this->Finance->create_json_array('#due_date', 101, 'Invoice Date/Due (days) is  Empty!');
            $error_flag = true;
        }

        /* 				if(empty($total_amount)){
          $this->Invoice->create_json_array ( '#total_amount', 101, 'Total is  null');
          $error_flag = true;
          } */
        return $error_flag;
    }



    function invoice_notification_log($invoice_id = null)
    {
        $this->pageTitle = "Management/Invoice Notification Log";

        $currPage = 1;
        $pageSize = 100;
        $order_arr = array();

        $this->set('client', $this->Finance->findClient());
        if (!empty($_REQUEST['order_by']))
        {
            $order_by = explode("-", $_REQUEST['order_by']);
            $order_arr[$order_by[0]] = $order_by[1];
        }
        else
        {
            $order_arr['name'] = 'desc';
        }

        $search_arr = $this->params['url'];

        $this->set('get_data', $search_arr);
        if (!empty($_REQUEST['search']))
        {   //模糊查询
            $search_type = 0;
            $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
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

        $results = $this->Finance->ListCarrierInvoiceLog($currPage, $pageSize, $search_arr, $order_arr);
        $this->set('p', $results);

        if ($invoice_id != null)
        {
            $sql = "select client.company,invoice.invoice_number,client.billing_email,invoice_start,invoice_end from invoice left join client on invoice.client_id = client.client_id where invoice_id = {$invoice_id}";
            $result = $this->Finance->query($sql);
            $email_info = $this->Finance->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname as "name" FROM system_parameter');
            if ($result[0][0]['billing_email'] == '')
            {
                $this->Finance->create_json_array('', 101, __('Biling email does not exists!', true));
                $this->Session->write('m', Finance::set_validator());
            }
            else
            {
                $send_address = $result[0][0]['billing_email'];

                $real_path = $this->createpdf_invoice($result[0][0]['invoice_number']);

                $template = $this->Finance->query("select carrier_invoice_subject,carrier_invoice_content from mail_tmplate limit 1");

                $subject = str_ireplace("{start_date}", $result[0][0]['invoice_start'], $template[0][0]['carrier_invoice_subject']);
                $subject = str_ireplace("{end_date}", $result[0][0]['invoice_end'], $subject);

                $content = str_ireplace("{start_date}", $result[0][0]['invoice_start'], $template[0][0]['carrier_invoice_content']);
                ;
                $content = str_ireplace("{end_date}", $result[0][0]['invoice_end'], $content);
                $content = str_ireplace("{invoice_number}", $result[0][0]['invoice_number'], $content);
                $content = str_ireplace("{company_name}", $result[0][0]['company'], $content);

                App::import('Vendor', 'nmail/phpmailer');
                $mailer = new phpmailer();
                $mailer->IsSMTP();
                $mailer->SMTPAuth = true;
                $mailer->From = $email_info[0][0]['from'];
                $mailer->FromName = $email_info[0][0]['name'];
                $mailer->Host = $email_info[0][0]['smtphost'];
                $mailer->Port = intval($email_info[0][0]['smtpport']);
                $mailer->Username = $email_info[0][0]['username'];
                $mailer->Password = $email_info[0][0]['password'];
                $mailer->AddAddress($send_address);
                $mailer->Subject = $subject;
                $mailer->ClearAttachments();
                if (strpos($mailer->Host, 'denovolab.com') !== false) {
                    $mailer->Helo = 'demo.denovolab.com';
                }
                //$real_path = APP . '/webroot/upload/invoice/' . $result[0][0]['pdf_file'];
                $mailer->AddAttachment($real_path);
                $mailer->Body = $content;
                if ($mailer->Send())
                {
                    $sql = "INSERT INTO invoice_email (invoice_no, send_time, mail_sub, mail_content, send_address, pdf_file) VALUES ('{$result[0][0]['invoice_number']}', CURRENT_TIMESTAMP, '{$subject}', '{$content}', '{$send_address}', '{$filename}')";
                    $this->Finance->query($sql);
                    $this->Finance->create_json_array('', 201, __('Email sent successfully!', true));
                    $this->Session->write('m', Finance::set_validator());
                }
                else
                {
                    $errorinfo = $mailer->ErrorInfo;
                    $this->Finance->create_json_array('', 101, __('Send email failed!', true));
                    $this->Session->write('m', Finance::set_validator());
                }
                $this->xredirect('/finances/invoice_notification_log');
            }
        }
    }

    function view_invoice_email($invoice_number)
    {
        $this->pageTitle = "Management/Invoice Notification Log";
        $currPage = 1;
        $pageSize = 100;
        $search_arr = array();
        $order_arr = array();

        if (!empty($_REQUEST['order_by']))
        {
            $order_by = explode("-", $_REQUEST['order_by']);
            $order_arr[$order_by[0]] = $order_by[1];
        }
        else
        {
            $order_arr['send_time'] = 'desc';
        }

        if (!empty($_REQUEST['search']))
        {   //模糊查询
            $search_type = 0;
            $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
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

        $results = $this->Finance->ListCarrierInvoiceEmail($currPage, $pageSize, $search_arr, $order_arr, $invoice_number);
        $this->set('p', $results);
    }

    public function get_mutual_ingress_detail($client_id)
    {
        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
        }
        else
        {
            $start_time = date("Y-m-d", strtotime("-29 days"));
        }
        if (isset($_GET['end_time']))
        {
            $end_time = $_GET['end_time'];
        }
        else
        {
            $end_time = date("Y-m-d");
        }
        $client_id = base64_decode($client_id);
        $begin_balance_result = $this->FinanceHistory->find('first', array(
            'order' => array('FinanceHistory.date DESC'),
            'conditions' => array('FinanceHistory.date < ' => $start_time, 'client_id' => $client_id),
        ));

        if (!$begin_balance_result)
        {
            $begin_balance_result = $this->FinanceHistory->find('first', array(
                'order' => array('FinanceHistory.date ASC'),
                'conditions' => array('client_id' => $client_id),
            ));
        }

        $begin_balance = $begin_balance_result['FinanceHistory']['mutual_ingress_balance'];
        $start_time = $begin_balance_result['FinanceHistory']['date'];

        $end_balance_result = $this->FinanceHistory->find('first', array(
            'order' => array('FinanceHistory.date DESC'),
            'conditions' => array('FinanceHistory.date <= ' => $end_time, 'client_id' => $client_id),
        ));

        $financehistories = $this->FinanceHistory->find('all', array(
            'order' => array('FinanceHistory.date'),
            'conditions' => array('FinanceHistory.date BETWEEN ? AND ?' => array($start_time, $end_time), 'client_id' => $client_id),
        ));

        $current_date = date("Y-m-d");
        if (strtotime($end_time) >= strtotime($current_date))
        {
            $end_time = $current_date;

            $current_finance = $this->FinanceHistory->get_current_finance_detail($client_id);

            $current_finance['date'] = $current_date;

            $financehistories[] = array(
                'FinanceHistory' => $current_finance
            );

            $end_balance = $current_finance['mutual_ingress_balance'];
        }
        else
        {
            if (count($end_balance_result) == 1)
            {
                $end_balance = $end_balance_result['FinanceHistory']['mutual_ingress_balance'];
                $end_time = $end_balance_result['FinanceHistory']['date'];
            }
            else
            {
                $end_balance = 0;
            }
        }


        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);

        $sum_arr = array();
        foreach ($financehistories as $financehistory)
        {
            foreach ($financehistory['FinanceHistory'] as $key => $value)
            {
                @$sum_arr[$key] += $value;
            }
        }
        $this->set('type_sum', $sum_arr);

        $this->set('financehistories', $financehistories);

        $this->set('client_id', $client_id);
        $this->set('begin_balance', $begin_balance);
        $this->set('end_balance', $end_balance);
        $name = $this->Client->get_client_name($client_id);
        $this->set('client_name', $name);


        if (isset($_GET['export']))
        {
            $name = str_replace(' ', '_', $name);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=balance_{$name}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('get_mutual_ingress_detail_xls');
        }
    }

    public function get_mutual_egress_detail($client_id)
    {
        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
        }
        else
        {
            $start_time = date("Y-m-d", strtotime("-29 days"));
        }
        if (isset($_GET['end_time']))
        {
            $end_time = $_GET['end_time'];
        }
        else
        {
            $end_time = date("Y-m-d");
        }
        $client_id = base64_decode($client_id);
        $begin_balance_result = $this->FinanceHistory->find('first', array(
            'order' => array('FinanceHistory.date DESC'),
            'conditions' => array('FinanceHistory.date < ' => $start_time, 'client_id' => $client_id),
        ));

        if (!$begin_balance_result)
        {
            $begin_balance_result = $this->FinanceHistory->find('first', array(
                'order' => array('FinanceHistory.date ASC'),
                'conditions' => array('client_id' => $client_id),
            ));
        }

        $begin_balance = $begin_balance_result['FinanceHistory']['mutual_egress_balance'];
        $start_time = $begin_balance_result['FinanceHistory']['date'];

        $end_balance_result = $this->FinanceHistory->find('first', array(
            'order' => array('FinanceHistory.date DESC'),
            'conditions' => array('FinanceHistory.date <= ' => $end_time, 'client_id' => $client_id),
        ));


        $financehistories = $this->FinanceHistory->find('all', array(
            'order' => array('FinanceHistory.date'),
            'conditions' => array('FinanceHistory.date BETWEEN ? AND ?' => array($start_time, $end_time), 'client_id' => $client_id),
        ));

        $current_date = date("Y-m-d");
        if (strtotime($end_time) >= strtotime($current_date))
        {
            $end_time = $current_date;

            $current_finance = $this->FinanceHistory->get_current_finance_detail($client_id);

            $current_finance['date'] = $current_date;

            $financehistories[] = array(
                'FinanceHistory' => $current_finance
            );

            $end_balance = $current_finance['mutual_egress_balance'];
        }
        else
        {
            if (count($end_balance_result) == 1)
            {
                $end_balance = $end_balance_result['FinanceHistory']['mutual_egress_balance'];
                $end_time = $end_balance_result['FinanceHistory']['date'];
            }
            else
            {
                $end_balance = 0;
            }
        }


        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);

        $sum_arr = array();
        foreach ($financehistories as $financehistory)
        {
            foreach ($financehistory['FinanceHistory'] as $key => $value)
            {
                @$sum_arr[$key] += $value;
            }
        }
        $this->set('type_sum', $sum_arr);

        $this->set('financehistories', $financehistories);

        $this->set('client_id', $client_id);
        $this->set('begin_balance', $begin_balance);
        $this->set('end_balance', $end_balance);
        $name = $this->Client->get_client_name($client_id);
        $this->set('client_name', $name);

        if (isset($_GET['export']))
        {
            $name = str_replace(' ', '_', $name);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=balance_{$name}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('get_mutual_egress_detail_xls');
        }
    }

    public function get_mutual_ingress_egress_detail($client_id = "")
    {
        if (!$client_id) {
            $this->cakeError('error404');
        }
        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
        }
        else
        {
            $start_time = date("Y-m-d", strtotime("-29 days"));
        }

        if (isset($_GET['end_time']))
        {
            $end_time = $_GET['end_time'];
        }
        else
        {
            $end_time = date("Y-m-d");
        }
        $period_start = $start_time;
        $period_end = $end_time;

        $client_id = base64_decode($client_id);
        $begin_balance_result = $this->FinanceHistory->find('first', array(
            'order' => array('FinanceHistory.date DESC'),
            'conditions' => array('FinanceHistory.date < ' => $start_time, 'client_id' => $client_id),
        ));

        if (!$begin_balance_result)
        {
            $begin_balance_result = $this->FinanceHistory->find('first', array(
                'order' => array('FinanceHistory.date ASC'),
                'conditions' => array('client_id' => $client_id),
            ));
        }

        $begin_balance = $begin_balance_result['FinanceHistory']['mutual_balance'];
        $start_time = $begin_balance_result['FinanceHistory']['date'];

        $end_balance_result = $this->FinanceHistory->find('first', array(
            'order' => array('FinanceHistory.date DESC'),
            'conditions' => array('FinanceHistory.date <= ' => $end_time, 'client_id' => $client_id),
        ));


        $financehistories = $this->FinanceHistory->find('all', array(
            'order' => array('FinanceHistory.date'),
            'conditions' => array('FinanceHistory.date BETWEEN ? AND ?' => array($start_time, $end_time), 'client_id' => $client_id),
        ));


        $yesterday = date('Y-m-d',strtotime('-1 days'));
        $yesterday_balance_count = 1;
        if (strtotime($end_time) >= strtotime($yesterday))
        {
            $yesterday_balance_count = $this->FinanceHistory->find('count', array(
                'order' => array('FinanceHistory.date'),
                'conditions' => array('FinanceHistory.date' => $yesterday, 'client_id' => $client_id),
            ));
            if (!$yesterday_balance_count)
            {
                $yesterday_finance = $this->FinanceHistory->get_finance_detail($client_id,$yesterday);
                $yesterday_finance['date'] = $yesterday;
                $financehistories[] = array(
                    'FinanceHistory' => $yesterday_finance
                );
            }
        }
        $current_date = date("Y-m-d");

        if (strtotime($end_time) >= strtotime($current_date))
        {
            $end_time = $current_date;
            if ($yesterday_balance_count)
                $current_finance = $this->FinanceHistory->get_current_finance_detail($client_id);
            else
                $current_finance = $this->FinanceHistory->get_finance_detail($client_id,$current_date);

            $current_finance['date'] = $current_date;

            $financehistories[] = array(
                'FinanceHistory' => $current_finance
            );

            $end_balance = isset($current_finance['mutual_balance']) ? $current_finance['mutual_balance'] : 0;
        }
        else
        {
            if (count($end_balance_result) == 1)
            {
                $end_balance = $end_balance_result['FinanceHistory']['mutual_balance'];
                $end_time = $end_balance_result['FinanceHistory']['date'];
            }
            else
            {
                $end_balance = 0;
            }
        }


        $this->set('start_time', $period_start);
        $this->set('end_time', $period_end);
        $sum_arr = array();
        if(isset($_GET['non_zero']) && $_GET['non_zero']==1){
            $filter_arr = array('invoice_set','payment_received','credit_note_sent','debit_note_sent','invoice_received',
                'payment_sent','credit_note_received','debit_note_received','mutual_balance');
            foreach ($financehistories as $fk => $financehistory)
            {
                $tmp = 0;
                foreach ($financehistory['FinanceHistory'] as $key => $value)
                {
                    @$sum_arr[$key] += $value;

                    if(in_array($key, $filter_arr)){
                        $tmp += $value;
                    }
                }

                if(!$tmp){
                    unset($financehistories[$fk]);
                }
            }
        } else {
            foreach ($financehistories as $financehistory)
            {
                foreach ($financehistory['FinanceHistory'] as $key => $value)
                {
                    @$sum_arr[$key] += $value;
                }
            }
        }

        $this->set('type_sum', $sum_arr);

        $this->set('financehistories', $financehistories);

        $this->set('client_id', $client_id);
        $this->set('begin_balance', $begin_balance);
        $this->set('end_balance', $end_balance);
        $name = $this->Client->get_client_name($client_id);
        $this->set('client_name', $name);


        if (isset($_GET['export']))
        {
            $name = str_replace(' ', '_', $name);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=balance_{$name}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('get_mutual_ingress_egress_detail_xls');
        }
    }

    public function get_actual_ingress_detail($client_id)
    {
        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
        }
        else
        {
            $start_time = date("Y-m-d", strtotime("-29 days"));
        }
        if (isset($_GET['end_time']))
        {
            $end_time = $_GET['end_time'];
        }
        else
        {
            $end_time = date("Y-m-d");
        }
        $client_id = base64_decode($client_id);
        $begin_balance_result = $this->FinanceHistoryActual->find('first', array(
            'order' => array('FinanceHistoryActual.date DESC'),
            'conditions' => array('FinanceHistoryActual.date < ' => $start_time, 'client_id' => $client_id),
        ));

        if (!$begin_balance_result)
        {
            $begin_balance_result = $this->FinanceHistoryActual->find('first', array(
                'order' => array('FinanceHistoryActual.date ASC'),
                'conditions' => array('client_id' => $client_id),
            ));
        }

        $begin_balance = $begin_balance_result['FinanceHistoryActual']['actual_ingress_balance'];
        $start_time = $begin_balance_result['FinanceHistoryActual']['date'];

        $end_balance_result = $this->FinanceHistoryActual->find('first', array(
            'order' => array('FinanceHistoryActual.date DESC'),
            'conditions' => array('FinanceHistoryActual.date <= ' => $end_time, 'client_id' => $client_id),
        ));



        $financehistories = $this->FinanceHistoryActual->find('all', array(
            'order' => array('FinanceHistoryActual.date'),
            'conditions' => array('FinanceHistoryActual.date BETWEEN ? AND ?' => array($start_time, $end_time), 'client_id' => $client_id),
        ));

        $current_date = date("Y-m-d");
        if (strtotime($end_time) >= strtotime($current_date))
        {
            $end_time = $current_date;

            $current_finance = $this->FinanceHistoryActual->get_current_finance_detail($client_id);

            $current_finance['date'] = $current_date;

            $financehistories[] = array(
                'FinanceHistoryActual' => $current_finance
            );

            $end_balance = $current_finance['actual_ingress_balance'];
        }
        else
        {
            if (count($end_balance_result) == 1)
            {
                $end_balance = $end_balance_result['FinanceHistoryActual']['actual_ingress_balance'];
                $end_time = $end_balance_result['FinanceHistoryActual']['date'];
            }
            else
            {
                $end_balance = 0;
            }
        }


        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);

        $sum_arr = array();
        foreach ($financehistories as $financehistory)
        {
            foreach ($financehistory['FinanceHistoryActual'] as $key => $value)
            {
                @$sum_arr[$key] += $value;
            }
        }
        $this->set('type_sum', $sum_arr);

        $this->set('financehistories', $financehistories);

        $this->set('client_id', $client_id);
        $this->set('begin_balance', $begin_balance);
        $this->set('end_balance', $end_balance);
        $name = $this->Client->get_client_name($client_id);
        $this->set('client_name', $name);

        if (isset($_GET['export']))
        {
            $name = str_replace(' ', '_', $name);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=balance_{$name}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('get_actual_ingress_detail_xls');
        }
    }

    public function get_actual_egress_detail($client_id)
    {
        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
        }
        else
        {
            $start_time = date("Y-m-d", strtotime("-29 days"));
        }
        if (isset($_GET['end_time']))
        {
            $end_time = $_GET['end_time'];
        }
        else
        {
            $end_time = date("Y-m-d");
        }
        $client_id = base64_decode($client_id);
        $begin_balance_result = $this->FinanceHistoryActual->find('first', array(
            'order' => array('FinanceHistoryActual.date DESC'),
            'conditions' => array('FinanceHistoryActual.date < ' => $start_time, 'client_id' => $client_id),
        ));

        if (!$begin_balance_result)
        {
            $begin_balance_result = $this->FinanceHistoryActual->find('first', array(
                'order' => array('FinanceHistoryActual.date ASC'),
                'conditions' => array('client_id' => $client_id),
            ));
        }

        $begin_balance = $begin_balance_result['FinanceHistoryActual']['actual_balance'];
        $start_time = $begin_balance_result['FinanceHistoryActual']['date'];

        $end_balance_result = $this->FinanceHistoryActual->find('first', array(
            'order' => array('FinanceHistoryActual.date DESC'),
            'conditions' => array('FinanceHistoryActual.date <= ' => $end_time, 'client_id' => $client_id),
        ));



        $financehistories = $this->FinanceHistoryActual->find('all', array(
            'order' => array('FinanceHistoryActual.date'),
            'conditions' => array('FinanceHistoryActual.date BETWEEN ? AND ?' => array($start_time, $end_time), 'client_id' => $client_id),
        ));

        $current_date = date("Y-m-d");
        if (strtotime($end_time) >= strtotime($current_date))
        {
            $end_time = $current_date;

            $current_finance = $this->FinanceHistoryActual->get_current_finance_detail($client_id);

            $current_finance['date'] = $current_date;

            $financehistories[] = array(
                'FinanceHistoryActual' => $current_finance
            );

            $end_balance = $current_finance['actual_egress_balance'];
        }
        else
        {
            if (count($end_balance_result) == 1)
            {
                $end_balance = $end_balance_result['FinanceHistoryActual']['actual_egress_balance'];
                $end_time = $end_balance_result['FinanceHistoryActual']['date'];
            }
            else
            {
                $end_balance = 0;
            }
        }


        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);

        $sum_arr = array();
        foreach ($financehistories as $financehistory)
        {
            foreach ($financehistory['FinanceHistoryActual'] as $key => $value)
            {
                @$sum_arr[$key] += $value;
            }
        }
        $this->set('type_sum', $sum_arr);

        $this->set('financehistories', $financehistories);


        $this->set('begin_balance', $begin_balance);
        $this->set('end_balance', $end_balance);
        $name = $this->Client->get_client_name($client_id);
        $this->set('client_name', $name);

        if (isset($_GET['export']))
        {
            $name = str_replace(' ', '_', $name);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=balance_{$name}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('get_actual_egress_detail_xls');
        }
    }

    public function get_actual_ingress_egress_detail($client_id = '')
    {

        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
        }
        else
        {
            $start_time = date("Y-m-d", strtotime("-29 days"));
        }
        if (isset($_GET['end_time']))
        {
            $end_time = $_GET['end_time'];
        }
        else
        {
            $end_time = date("Y-m-d");
        }

        $period_start = $start_time;
        $period_end = $end_time;

        if (empty($client_id))
            $client_id = $_SESSION['sst_client_id'];


        $begin_balance_result = $this->FinanceHistoryActual->find('first', array(
            'order' => array('FinanceHistoryActual.date DESC'),
            'conditions' => array('FinanceHistoryActual.date < ' => $start_time, 'client_id' => $client_id),
        ));


        if (!$begin_balance_result)
        {
            $begin_balance_result = $this->FinanceHistoryActual->find('first', array(
                'order' => array('FinanceHistoryActual.date ASC'),
                'conditions' => array('client_id' => $client_id),
            ));
        }

        $begin_balance = $begin_balance_result['FinanceHistoryActual']['actual_balance'];
        $start_time = $begin_balance_result['FinanceHistoryActual']['date'];

        $end_balance_result = $this->FinanceHistoryActual->find('first', array(
            'order' => array('FinanceHistoryActual.date DESC'),
            'conditions' => array('FinanceHistoryActual.date <= ' => $end_time, 'client_id' => $client_id),
        ));
        $financehistories = $this->FinanceHistoryActual->find('all', array(
            'order' => array('FinanceHistoryActual.date','FinanceHistoryActual.id DESC'),
            'conditions' => array('FinanceHistoryActual.date BETWEEN ? AND ?' => array($start_time, $end_time), 'client_id' => $client_id),
        ));

        $dates = [];
        foreach ($financehistories as $key => $financehistory)
        {
            if(isset($financehistory['FinanceHistoryActual']['date']) && in_array($financehistory['FinanceHistoryActual']['date'], $dates)) {
                unset($financehistories[$key]);continue;
            }
            $dates[] = $financehistory['FinanceHistoryActual']['date'];
        }

        $yesterday = date('Y-m-d',strtotime('-1 days'));
        $yesterday_balance_count = 1;
        if (strtotime($end_time) >= strtotime($yesterday))
        {
            $yesterday_balance_count = $this->FinanceHistoryActual->find('count', array(
                'order' => array('FinanceHistoryActual.date'),
                'conditions' => array('FinanceHistoryActual.date' => $yesterday, 'client_id' => $client_id),
            ));
            if (!$yesterday_balance_count)
            {


                $yesterday_finance = $this->FinanceHistoryActual->get_finance_detail($client_id,$yesterday);
                if($yesterday_finance){
                    $yesterday_finance['date'] = $yesterday;
                }

                // removing $yesterday balance
                foreach ($financehistories as $key => $financehistory)
                {
                    if(isset($financehistory['FinanceHistoryActual']['date']) && $financehistory['FinanceHistoryActual']['date'] == $yesterday){
                        unset($financehistories[$key]);break;
                    }

                }

                $financehistories[] = array(
                    'FinanceHistoryActual' => $yesterday_finance
                );
            }
        }

        $current_date = date("Y-m-d");
        if (strtotime($end_time) >= strtotime($current_date))
        {
            $end_time = $current_date;
            $current_finance = $this->FinanceHistoryActual->get_current_finance_detail($client_id);

            if($current_finance){
                $current_finance['date'] = $current_date;
            }

            // removing $current_date balance
            foreach ($financehistories as $key => $financehistory)
            {
                if(isset($financehistory['FinanceHistoryActual']['date']) && $financehistory['FinanceHistoryActual']['date'] == $current_date) {

                    unset($financehistories[$key]);break;
                }

            }
            $latestDay = end($financehistories);
            $latest_day_balance = isset($latestDay['FinanceHistoryActual']['actual_balance']) ? $latestDay['FinanceHistoryActual']['actual_balance'] : 0;

            $current_finance['actual_balance'] = $latest_day_balance
                + $current_finance['payment_received']
                + $current_finance['credit_note_received']
                + $current_finance['debit_received']
                + $current_finance['unbilled_outgoing_traffic']
                - $current_finance['payment_sent']
                - $current_finance['credit_note_sent']
                - $current_finance['debit_sent']
                - $current_finance['unbilled_incoming_traffic'];

            $financehistories[] = array(
                'FinanceHistoryActual' => $current_finance
            );

            $end_balance = !isset($current_finance['actual_balance']) ? 0 : $current_finance['actual_balance'];
        }
        else
        {
            if (count($end_balance_result) == 1)
            {
                $end_balance = $end_balance_result['FinanceHistoryActual']['actual_balance'];
                $end_time = $end_balance_result['FinanceHistoryActual']['date'];
            }
            else
            {
                $end_balance = 0;
            }
        }

        $this->set('start_time', $period_start);
        $this->set('end_time', $period_end);

        $sum_arr = array();

        foreach ($financehistories as $key => $financehistory)
        {
            if(!empty($financehistory) && is_array($financehistory['FinanceHistoryActual'])) {
                foreach ($financehistory['FinanceHistoryActual'] as $key => $value) {
                    @$sum_arr[$key] += $value;
                }
            }

        }
        $this->set('type_sum', $sum_arr);
        $this->set('financehistories', $financehistories);


        $this->set('begin_balance', $begin_balance);
        $this->set('end_balance', $end_balance);
        $this->set('client_id', $client_id);
        $name = $this->Client->get_client_name($client_id);
        $this->set('client_name', $name);

        if (isset($_GET['export']))
        {
            $name = str_replace(' ', '_', $name);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=balance_{$name}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('get_actual_ingress_egress_detail_xls');
        }
    }

    public function synchronize($client_id, $balance_history_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        //var_dump($client_id);
        if ($this->RequestHandler->isPost())
        {
            $client_id = base64_decode($client_id);
            $date = $_POST['reset_time'];
            if ($balance_history_id === '0')
            {
                $mutual_balance = $this->FinanceHistory->get_current_finance_detail($client_id);
                $ingress_balance = $mutual_balance['mutual_ingress_balance'];
                $egress_balance = $mutual_balance['mutual_egress_balance'];
            }
            else
            {
                $mutual_balance = $this->FinanceHistory->findById($balance_history_id);
                $ingress_balance = !empty($mutual_balance) ? $mutual_balance['FinanceHistory']['mutual_ingress_balance'] : 0;
                $egress_balance = !empty($mutual_balance) ? $mutual_balance['FinanceHistory']['mutual_egress_balance'] : 0;
            }

            $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time, egress_amount)
            VALUES(true, CURRENT_TIMESTAMP, {$ingress_balance}, {$client_id}, 'Synchronize', 14, '{$date}', $egress_balance)";
            $this->FinanceHistory->query($sql);

            $this->FinanceHistory->create_json_array("", 201, 'Succeeded!');
            $this->Session->write('m', Finance::set_validator());
            //var_dump($new_client_id);
            //echo '/finances/get_mutual_ingress_egress_detail/' . base64_encode($new_client_id);
            $client_id = base64_encode($client_id);
            $this->redirect('/finances/get_mutual_ingress_egress_detail/' . $client_id);
        }

        $this->set('client_id', $client_id);
        $this->set('balance_history_id', $balance_history_id);
    }

    public function getUrl()
    {
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
        {

            $url = 'https://' . $_SERVER['SERVER_NAME'] . ':' .
                $_SERVER["SERVER_PORT"] . $this->webroot;
        }
        else
        {
            $url = 'http://' . $_SERVER['SERVER_NAME'] . ':' .
                $_SERVER["SERVER_PORT"] . $this->webroot;
        }
        return $url;
    }

    public function finances_mass_add($encode_client_id)
    {
        $this->set('encode_client_id',$encode_client_id);
        $client_id = base64_decode($encode_client_id);
        $mass_type = isset($this->params['pass'][1]) ? $this->params['pass'][1] : "rec_payment";
        $mass_type_array = array('rec_payment','sent_payment','vendor_invoice');
        if(!in_array($mass_type,$mass_type_array))
            $mass_type = "rec_payment";
        $this->set('mass_type',$mass_type);
    }

    public function test($password = '')
    {
        if (strcmp($password,'123456789(_hzj)'))
        {
            $this->redirect('/clients/index');
        }
        $client_name_info = $this->Client->query("select client_id from client where status = true");
        $arr = array();
        foreach ($client_name_info as $item )
        {
            $tmp_arr = array(
                'client_id' => $item[0]['client_id'],
                'result' => true,
                'receiving_time' => '2015-06-08',
                'amount' => 0,
                'description' => '',
                'payment_type' => 13,
                'payment_time' => '2015-06-09'
            );
            $tmp_arr1 = array(
                'client_id' => $item[0]['client_id'],
                'result' => true,
                'receiving_time' => '2015-06-08',
                'amount' => 0,
                'description' => 'Synchronize',
                'payment_type' => 14,
                'payment_time' => '2015-06-09',
                'egress_amount' => 0
            );
            $arr[] = $tmp_arr;
            $arr[] = $tmp_arr1;
        }
        $this->loadModel('Clientpayment');
        $flg = $this->Clientpayment->saveAll($arr);
        var_dump($flg);die;
    }

    public function sync_balance($password = '')
    {
        if (strcmp($password,'123456789(_hzj)'))
        {
            $this->redirect('regenerate_balance');
        }
        $client_name_info = $this->Client->query("select client_id,name from client where status = true");
        $arr = array();
        foreach ($client_name_info as $item )
        {
            $client_id = $item[0]['client_id'];
            $current_finance = $this->FinanceHistoryActual->get_current_finance_detail($client_id);
            $actual_balance = $current_finance['actual_balance'];
            $actual_ingress_balance = $current_finance['actual_ingress_balance'];
            $actual_egress_balance = $current_finance['actual_egress_balance'];

            $this->Client->clientBalanceOperation($client_id, $actual_balance, 4);
            $flg = $this->Client->clientBalanceOperation($client_id, $actual_balance, 2);

            $arr[] = array(
                'client_id' => $client_id,
                'client_name' => $item[0]['name'],
                'actual_balance' => $actual_balance,
                'actual_ingress_balance' => $actual_ingress_balance,
                'actual_egress_balance' => $actual_egress_balance,
                'sql' => $sql,
                'flg' => $flg
            );
        }
        pr($arr);
        die;

    }

    public function regenerate_balance()
    {
        $clients_data = $this->Finance->findClient();
        if ($this->RequestHandler->isPost())
        {
            $balance = $this->data['finances']['new_balance'];
            $date = $this->data['finances']['balance_on'];
            $description = '';
            $client_id = $this->data['finances']['carrier_name'];
            $is_actual = $this->data['finances']['is_actual'];
            $is_mutual = $this->data['finances']['is_mutual'];
            $this->loadModel('Clientpayment');
            $date_time = $date.' 00:00:00';
            if (!strcmp(strtolower($client_id),'all'))
            {
                $extra_type = "-u ";
                if($is_mutual)
                {
                    $delete_old_sql = "delete from client_payment WHERE payment_type = 13";
                    $this->Client->query($delete_old_sql);
                    $arr = array();
                    foreach ($clients_data as $item_id => $item_name)
                    {
                        $tmp_arr = array(
                            'client_id' => $item_id,
                            'result' => true,
                            'receiving_time' => date('Y-m-d H:i:sO'),
                            'amount' => $balance,
                            'description' => '',
                            'payment_type' => 13,
                            'payment_time' => $date,
                        );
                        $arr[] = $tmp_arr;
                    }
                    $this->Clientpayment->saveAll($arr);
                    if(!$is_actual)
                        $extra_type .= '-o';

                }
                if($is_actual)
                {
                    $delete_old_sql = "delete from client_payment WHERE payment_type = 14";
                    $this->Client->query($delete_old_sql);
                    $arr = array();
                    foreach ($clients_data as $item_id => $item_name)
                    {
                        $tmp_arr = array(
                            'client_id' => $item_id,
                            'result' => true,
                            'receiving_time' => date('Y-m-d H:i:sO'),
                            'amount' => $balance,
                            'description' => 'Synchronize',
                            'payment_type' => 14,
                            'payment_time' => $date,
                        );
                        $arr[] = $tmp_arr;
                    }
                    $this->Clientpayment->saveAll($arr);
                    if(!$is_mutual)
                        $extra_type .= '-p';
                }
                $client_id = 0;
                $this->Client->logging(0, 'Regenerate Balance', "All Client, Balance:[{$balance}]");
                $this->Client->create_json_array('#Finances', 201, __('The balance of [%s] is regenerated successfully!', true, 'All Client'));
            }
            else
            {


                $client_name_info = $this->Client->query("select name from client where client_id = {$client_id}");
                $client_name = $client_name_info[0][0]['name'];

                $extra_type = "-u ";
                if($is_mutual)
                {
                    $delete_old_sql = "delete from client_payment WHERE client_id = $client_id AND payment_type = 13";
                    $this->Client->query($delete_old_sql);
                    $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time)
                    VALUES(true, CURRENT_TIMESTAMP, {$balance}, {$client_id}, '{$description}', 13, '{$date}')";
                    $this->Client->query($sql);
                    if(!$is_actual)
                        $extra_type .= '-o';
                }
                if($is_actual)
                {
                    $delete_old_sql = "delete from client_payment WHERE client_id = $client_id AND payment_type = 14";
                    $this->Client->query($delete_old_sql);
                    $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time, egress_amount)
            VALUES(true, CURRENT_TIMESTAMP, {$balance}, {$client_id}, 'Synchronize', 14, '{$date}', 0)";
                    $this->FinanceHistory->query($sql);
                    if(!$is_mutual)
                        $extra_type .= '-p';

                }

                $this->Client->logging(0, 'Regenerate Balance', "Client:{$client_name}, Balance:[{$balance}]");
                $this->Client->create_json_array('#Finances', 201, __('The balance of [%s] is regenerated successfully!', true, $client_name));
            }

            // CLAS-3366
            $this->loadModel('BalanceDailyResetTask');

            if($is_actual)
            {
                $sql = "insert into balance_daily_reset_task (start_time,reset_balance,actual,client_id) 
                        values ('{$date_time}',1,1,{$client_id})";
                $this->BalanceDailyResetTask->query($sql);
            }
            if($is_mutual){
                $sql = "insert into balance_daily_reset_task (start_time,mutual,client_id) 
                        values ('{$date_time}',1,{$client_id})";
                $this->BalanceDailyResetTask->query($sql);
            }
            $this->Session->write("m", Finance::set_validator());
            $this->redirect("/clients/index");
        }
        $show_clients = array('all' => __('All',true)) + $clients_data;
        $this->set('clients',$show_clients);
    }

    public function ajax_get_current_balance()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $client_id = $_POST['client_id'];
        $date = $_POST['date'];
        $today = date('Y-m-d');
        if($date == $today)
        {
            $current_balance_result = $this->FinanceHistoryActual->get_current_finance_detail($client_id);
            $actual_balance = $current_balance_result['actual_balance'];
        }
        else
        {
            $balance_result = $this->FinanceHistoryActual->find('first', array(
                'fields' => 'actual_balance',
                'conditions' => array('date' => $date, 'client_id' => $client_id),
            ));

            if(!$balance_result)
            {
                $balance_result = $this->FinanceHistoryActual->find('first', array(
                    'fields' => 'actual_balance',
                    'order' => array('FinanceHistoryActual.date DESC'),
                    'conditions' => array('FinanceHistoryActual.date < ' => $date, 'client_id' => $client_id),
                ));
                if (!$balance_result)
                {
                    $balance_result = $this->FinanceHistoryActual->find('first', array(
                        'fields' => 'actual_balance',
                        'order' => array('FinanceHistoryActual.date ASC'),
                        'conditions' => array('client_id' => $client_id),
                    ));
                }
            }
            if(!$balance_result)
            {
                $current_balance_result = $this->FinanceHistoryActual->get_current_finance_detail($client_id);
                $actual_balance = $current_balance_result['actual_balance'];
            }
            else
                $actual_balance = $balance_result['FinanceHistoryActual']['actual_balance'];
        }
        if(!$actual_balance)
            $actual_balance = 0;

        echo json_encode(array('balance'=> $actual_balance));

    }
}

?>
