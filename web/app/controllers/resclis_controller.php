<?php

class ResclisController extends AppController {

    var $name = 'Resclis';
    var $uses = array();
    var $components = array('RequestHandler');
    var $helpers = Array('AppResclis');


    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    function make_payment($type = '') {
        if (!$_SESSION['role_menu']['Finance']['resclis']['model_w']) {
            $this->redirect_denied();
        }
        $this->loadModel('Refill');
        $this->loadModel('Client');
        $client = $this->Client->find('all', Array('fields' => Array('client_id', 'name'), 'order' => array('name')));
        $this->set('client', $client);
        if (!empty($this->params['form'])) {
            $accounts = $this->params['form']['accounts'];
            $result = $this->Refill->x_client_refill($accounts);
            if ($result) {
                $this->Refill->create_json_array('', 201, __('refillsuccess', true));
            } else {
                $this->Refill->create_json_array('', 101, __('refillfail', true));
                $this->Session->write("m", Refill::set_validator());
            }
            $this->Session->write('m', Refill::set_validator());
            if ($type == 'res') {
                $this->redirect("/resellers/reseller_list");
            } elseif ($type == 'transaction') {
                $this->redirect("/transactions/client_tran_view/");
            } else {
                $this->redirect("/clients/index ");
            }
        }
    }

    public function make_payment_one($client = null, $id = null) {
        if (!$_SESSION['role_menu']['Finance']['resclis']['model_w']) {
            $this->redirect_denied();
        }
        if ($id == null) {
            $this->redirect('/homes/permission_denied');
        }
        $this->pageTitle = "Finance/Refill ";
        $this->loadModel('Refill');
        $this->set('type', $this->params['pass'][0]);
        $this->set('type_id', $this->params['pass'][1]);
        if (!empty($this->params['form'])) {
            $f = $this->params['form'];
            $result = false;


            //路由伙伴充值
            $result = $this->Refill->client_refill($f['amt'], $_POST['type_id'], 2, $_POST['approved']);


            if ($result) {
                $this->Refill->create_json_array('', 201, __('Payment,successfully!', true));
            } else {
                $this->Refill->create_json_array('', 101, __('refillfail', true));
            }
            $this->Session->write('m', Refill::set_validator());
            $this->redirect("/clients/view?edit_id={$_POST['type_id']}");
        }
        $this->set('name', $this->Refill->query("select name from  client  where client_id=$id"));
    }

    /**
     *
     * 加费用
     */
    public function make_payment_account() {
        if (!$_SESSION['role_menu']['Finance']['resclis']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 2);
        $this->loadModel('Refill');
        if (!empty($this->params['form'])) {

            $f = $this->params['form'];
            $result = false;
            $card_id = $_POST['query']['id_cards'];
            $tran_type = $_POST['data']['trantype'];
            $list = $this->Refill->query("select reseller_id  from card where card_id=$card_id");
            $reseller_id = $list[0][0]['reseller_id'];
            pr($_POST);
            pr($reseller_id);
            $this->Refill->query("insert into account_rent_cost (account_id,cost,cost_type,reseller_id) values($card_id,{$f['amt']},$tran_type,$reseller_id)");
            $this->Refill->create_json_array('', 101, '扣费成功');
            $this->Session->write('m', Refill::set_validator());
            $this->redirect("/transactions/account_tran_view/");
        }
    }

    public function create_payment($type = null) {
        if (!$_SESSION['role_menu']['Finance']['resclis']['model_w']) {
            $this->redirect_denied();
        }
        $this->loadModel('Transaction');
        $this->loadModel('Refill');
        if ($this->RequestHandler->isPost()) {
            $client_id = $_POST['client'];
            $client_name_info = $this->Refill->query("select name from client where client_id = {$client_id}");
            $client_name = $client_name_info[0][0]['name'];
            $receiving_time = !empty($_POST['receiving_time']) ? $_POST['receiving_time'] : date('Y-m-d H:i:sO');
            $payment_type = $_POST['payment_type'];
            $note = $_POST['note'];
            if ($type == 'outgoing') {
                //$c_ba = $this->Refill->query("update client_balance set balance=balance::real-{$_POST['amount']}, egress_balance=egress_balance::real-{$_POST['amount']} where client_id = '{$client_id}' returning balance");
                if ($payment_type == '1') {

                    $total = (double)$_POST['amount'];
                    $this->Refill->begin();
                    $total_amount = (double)$_POST['amount'];
                    $total_pay_amount = 0;
                    $invoice_numbers = @$_POST['invoice_numbers'] or array();
                    $current_amounts = @$_POST['current_amounts'] or array();
                    $due_amounts     = @$_POST['due_amounts'] or array();

                    $count = count($invoice_numbers);
                    for($i = 0; $i < $count; $i++)
                    {
                        if ($current_amounts[$i] == 0)
                            continue;
                        $invoice_number = $invoice_numbers[$i];
                        $pay_amount = $current_amounts[$i];
                        $due_amount = $due_amounts[$i];
                        if (abs($pay_amount - $due_amount) < 0.001)
                            $is_paid = 'true';
                        else
                            $is_paid = 'false';

                        $this->Refill->update_unpaid_invoice($invoice_number, $pay_amount, $is_paid);
                        $current_balance = $this->Refill->update_carrier_balance($pay_amount, $client_id, '-', 'egress_balance');
                        $this->Refill->create_payment_record($client_id, 3, $pay_amount, $current_balance, $invoice_number, $receiving_time, $note);
                        $total_pay_amount += $pay_amount;
                    }

                    if ($total_amount > $total_pay_amount)
                    {
                        $other_amount = $total_amount - $total_pay_amount;
                        $c_ba = $this->Refill->clientBalanceOperation($client_id, $total_amount, 5, true);
                        $sql = "INSERT INTO client_payment(client_id, payment_type, amount,  current_balance,payment_time, result, receiving_time, description)
        VALUES ({$client_id},6,{$other_amount}, {$c_ba[0][0]['balance']},'now', TRUE, '{$receiving_time}', '{$note}')";
                        $this->Refill->query($sql);
                    }
                    $this->Refill->query("update client set daily_balance_notification = low_balance_number where client_id = " . $client_id);
                    $this->Refill->commit();
                    $this->Refill->create_json_array('', 201, "Payment for [{$client_name}] with the amount of [{$_POST['amount']}] is added successfully!");
                    $this->Session->write('m', Refill::set_validator());
                } else {
                    $c_ba = $this->Refill->clientBalanceOperation($client_id, $_POST['amount'], 5, true);
                    $sql = "INSERT INTO client_payment(client_id, payment_type, amount,  current_balance,payment_time, result, receiving_time, description)
        VALUES ({$client_id},6,{$_POST['amount']}, {$c_ba[0][0]['balance']},'now', TRUE, '{$receiving_time}', '{$note}')";
                    $this->Refill->query($sql);
                    $this->Refill->query("update client set daily_balance_notification = low_balance_number where client_id = " . $client_id);
                }
                //$this->Refill->create_json_array('',201,'Successfully!');
                $this->Session->write('m', Refill::set_validator());
            } else {
                if ($payment_type == '1') {
                    $total_amount = (double)$_POST['amount'];
                    $total_pay_amount = 0;
                    $invoice_numbers = @$_POST['invoice_numbers'] or array();
                    $current_amounts = @$_POST['current_amounts'] or array();
                    $due_amounts     = @$_POST['due_amounts'] or array();

                    $count = count($invoice_numbers);
                    for($i = 0; $i < $count; $i++)
                    {
                        if ($current_amounts[$i] == 0)
                            continue;
                        $invoice_number = $invoice_numbers[$i];
                        $pay_amount = $current_amounts[$i];
                        $due_amount = $due_amounts[$i];
                        if (abs($pay_amount - $due_amount) < 0.001)
                            $is_paid = 'true';
                        else
                            $is_paid = 'false';

                        $this->Refill->update_unpaid_invoice($invoice_number, $pay_amount, $is_paid);
                        $current_balance = $this->Refill->update_carrier_balance($pay_amount, $client_id, '+', 'ingress_balance');
                        $this->Refill->create_payment_record($client_id, 4, $pay_amount, $current_balance, $invoice_number, $receiving_time, $note);
                        $total_pay_amount += $pay_amount;
                    }

                    if ($total_amount > $total_pay_amount)
                    {
                        $other_amount = $total_amount - $total_pay_amount;
                        $c_ba = $this->Refill->clientBalanceOperation($client_id, $other_amount, 2, true);
                        $sql = "INSERT INTO client_payment(client_id, payment_type, amount,  current_balance,payment_time, result, receiving_time, description)
        VALUES ({$client_id},5,{$_POST['amount']}, {$c_ba[0][0]['balance']},'now', TRUE, '{$receiving_time}', '{$note}')";
                        $this->Refill->query($sql);
                    }

                    $this->Refill->query("update client set daily_balance_notification = low_balance_number where client_id = " . $client_id);
                    $this->Refill->create_json_array('', 201, "Payment for [{$client_name}] with the amount of [{$_POST['amount']}] is added successfully!");
                    $this->Session->write('m', Refill::set_validator());
                } else {
                    $c_ba = $this->Refill->clientBalanceOperation($client_id, $_POST['amount'], 2, true);
                    $sql = "INSERT INTO client_payment(client_id, payment_type, amount,  current_balance,payment_time, result, receiving_time, description)
    VALUES ({$client_id},5,{$_POST['amount']}, {$c_ba[0][0]['balance']},'now', TRUE, '{$receiving_time}', '{$note}')";
                    $this->Refill->query($sql);
                    $this->Refill->query("update client set daily_balance_notification = low_balance_number where client_id = " . $client_id);
                    $this->Refill->create_json_array('', 201, "Payment for [{$client_name}] with the amount of [{$_POST['amount']}] is added successfully!");
                    //$this->Session->write('m',Refill::set_validator());
                }
                $this->Refill->logging(0, 'Payment', "Payment For:{$client_name}");
            }
        }
        $client = $this->Transaction->get_clients();
        $this->set('type', $type);
        $this->set('clients', $client);
    }

    public function get_unpaid_invoices($client_id, $type) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $this->loadModel('Refill');
        $result = $this->Refill->get_unpaid_invoices($client_id, $type);
        echo json_encode($result);
    }

    public function invoice_info($invoice_id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "SELECT 
 invoice_number,
 total_amount AS invoice_amount,
 CASE  paid 
     WHEN TRUE  THEN 0
     WHEN FALSE THEN total_amount - pay_amount
 END AS due_amount,
 invoice_start,
 invoice_end,
 due_date,
 pay_amount
FROM invoice WHERE invoice_id = {$invoice_id}";
        $this->loadModel('Refill');
        $results = $this->Refill->query($sql);
        echo json_encode($results);
    }

}

?>
