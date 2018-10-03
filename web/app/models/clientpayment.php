<?php
class Clientpayment extends AppModel
{
    var $name = 'Clientpayment';
    var $useTable = 'client_payment';
    var $primaryKey = 'client_payment_id';

    #check data
    function validates_pay($data)
    {
        if (!empty($data['Clientpayment']['invoice_number'])) {
        }
    }

    function get_invoice_options($invoice_id = null)
    {
        $invoice_where = '';
        if (!empty($invoice_id)) {
            $invoice_where = "and invoice_id =$invoice_id";
        }
        $lists = $this->query("select * from invoice where 1=1 $invoice_where");
        $options = Array();
        foreach ($lists as $list) {
            $options[$list[0]['invoice_id']] = $list[0]['invoice_number'];
        }
        return $options;
    }

# payment
    function invoice_options($conditions = Array())
    {
        $invoice_where = '';
        if (is_array($conditions)) {
            $conditions = join($conditions, ' and ');
        }
        $lists = $this->query("select * from invoice where $conditions");
        $options = Array();
        foreach ($lists as $list) {
            $options[$list[0]['invoice_number']] = $list[0]['invoice_number'];
        }
        return $options;
    }

    function clientName($client_id)
    {
        $titleName = $this->query("select name from client where client_id=  $client_id");
        return $titleName;
    }


    function _add_payment_no_invoice($data)
    {
        $data['Clientpayment']['client_payment_id'] = false;
        $client_id = $data['Clientpayment']['client_id'];
        $data['Clientpayment']['approved'] = true;
        $data['Clientpayment']['result'] = true;
        $amount = $data['Clientpayment']['amount'];
        if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $amount)) {
            $this->create_json_array('#amt', 101, 'Please fill Amount field correctly (only digits allowed).');
            return;
        }

        try {
            $this->begin();
            $this->save($data['Clientpayment']);
            $this->clientBalanceOperation($client_id, $amount, 1);
        } catch (Exception $e) {
            $this->rollback();

        }
        $this->commit();
    }


    function check_money($money)
    {
        if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $money)) {
            $this->create_json_array('#amt', 101, 'Please fill Amount field correctly (only digits allowed).');
            return false;
        }
        return true;
    }

    # pr  project payment  to invoice
    function add_payment_pr($data)
    {
        if ($this->check_money($data['Clientpayment']['amount'])) {
            $this->do_pr_invoice($data);
        }
    }


#为invoice payment	
    function do_pay_for_invoice($data)
    {
        $data['Clientpayment']['payment_type'] = 4;
        $this->save($data['Clientpayment']);

    }


//付清invoice
    function paid_true_invoice($data, $invoice_amount)
    {
        try {
            $amount = $data['Clientpayment']['amount'];
            $invoice_number = $data['Clientpayment']['invoice_number'];
            $this->begin();
            $this->query("update invoice set total_amount=0, paid=true,pay_amount=pay_amount::numeric+$invoice_amount  where invoice_number='$invoice_number'");
            # invoice payment
            $data['Clientpayment']['amount'] = $invoice_amount;
            $this->do_pay_for_invoice($data);
            # add credit
            $credit = $amount - $invoice_amount;
            $data['Clientpayment']['amount'] = $credit;
            $this->_add_payment_no_invoice($data);

        } catch (Exception $e) {
            $this->rollback();

        }


    }


    function paid_false_invoice($data, $invoice_number, $invoice_amount)
    {
        $amount = $data['Clientpayment']['amount'];
        $this->do_pay_for_invoice($data);
        $credit = $invoice_amount - $amount;
        $this->query("update invoice set paid=false,total_amount=$credit,pay_amount=pay_amount::numeric+$amount  where invoice_number='$invoice_number'");


    }

# process pr project  invoice
    function do_pr_invoice($data)
    {
        $client_id = $data['Clientpayment']['client_id'];
        $amount = $data['Clientpayment']['amount'];
        $invoice_number = $data['Clientpayment']['invoice_number'];
        $list = $this->query("select invoice_id, total_amount  from   invoice  where invoice_number='$invoice_number'  limit 1;");
        $invoice_amount = isset($list[0][0]['total_amount']) ? $list[0][0]['total_amount'] : 0;
        #付清
        if ($invoice_amount < $amount) {
            $this->paid_true_invoice($data, $invoice_amount);
        }
        if ($invoice_amount < $amount) {
            #未付清
            $this->paid_false_invoice($data, $invoice_number, $invoice_amount);
        }
        if ($invoice_amount == $amount) {

            #刚刚付清
            $this->do_pay_for_invoice($data);
            $this->query("update invoice set paid=true,pay_amount=pay_amount::numeric+$invoice_amount  where invoice_number='$invoice_number'");

        }
        $this->commit();
    }

    function add_payment_exchange($data)
    {
        $data['Clientpayment']['client_payment_id'] = false;
        $client_id = $data['Clientpayment']['client_id'];
        $data['Clientpayment']['approved'] = true;
        $data['Clientpayment']['result'] = true;
        $payment_type = $data['Clientpayment']['payment_type'];
        $amount = $data['Clientpayment']['amount'];
        $invoice_number = $data['Clientpayment']['invoice_number'];
        $payment_time = $data['Clientpayment']['payment_time'];
        if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $amount)) {
            $this->create_json_array('#amt', 101, 'Please fill Amount field correctly (only digits allowed).');
            return;
        }
//		if(empty($invoice_number)){
//				$this->create_json_array('#amt',101,'Please fill Amount field correctly (only digits allowed).');
//			return;
//		}
        $this->begin();
        $list = $this->query("select invoice_id, total_amount  from   invoice  where invoice_number='$invoice_number'  limit 1;");
        $list2 = $this->query("select  sum(total_amount)as owe_amount  from   invoice  where client_id=$client_id and paid=false;");
        $credit = $amount - $list2[0][0]['owe_amount'];
        if (!empty($list[0][0]['total_amount'])) {
            $invoice_amt = $list[0][0]['total_amount'];
            $invoice_id = $list[0][0]['invoice_id'];
            if ($invoice_amt == 0) {
                return;
            }
            $balance = (float)$amount - (float)$invoice_amt;//付清之后剩余的钱
            if ((float)$amount > (float)$invoice_amt) {
                $data['Clientpayment']['amount'] = $amount;
                if ($this->save($data['Clientpayment'])) {
                    $pay_id = $this->getLastInsertID();
                    $this->query("update invoice set paid=true,total_amount=0,pay_amount=pay_amount::numeric+$invoice_amt  where invoice_number='$invoice_number'");
                    $list = $this->query("select  invoice_id, total_amount  from  invoice  where client_id=$client_id  and invoice_id>$invoice_id");
                    $pay_amount = $balance;//付清之后剩余的钱

                    // ************************************************* 递归付后面的    invoice***************************************
                    $s_balance = 0;
                    foreach ($list as $key => $value) {
                        $s_invoice_id = $list[$key][0]['invoice_id'];
                        $s_invoice_amt = $list[$key][0]['total_amount'];//发票金额
                        $s_balance = (float)$pay_amount - (float)$s_invoice_amt;//付清之后剩余的钱
                        if ((float)$pay_amount > (float)$s_invoice_amt) {
                            #payment >invoice 又付清一个invocie
                            $this->query("update invoice set paid=true,pay_amount=total_amount,total_amount=0  where invoice_id=$s_invoice_id;");
                            $pay_amount = $s_balance;//付清之后剩余的钱

                            continue;

                        } else {
                            #payment <invoice 未付清这个invocie
                            $last_ = abs($s_balance);
                            $this->query("update invoice set paid=false,total_amount=$last_,pay_amount=pay_amount::numeric+$pay_amount  where invoice_id=$s_invoice_id");
                            break;
                        }
                    }

                    // ************************************************* 递归付清所有的    invoice***************************************
                    if ($s_balance > 0) {
                        #add 赠送金
                        $data['Clientpayment']['payment_time'] = date("Y-m-d   H:i:s");
                        $data['Clientpayment']['amount'] = $credit;
                        $data['Clientpayment']['payment_type'] = 2;
                        $data['Clientpayment']['client_payment_id'] = false;
                        if ($this->save($data['Clientpayment'])) {
                            $list = $this->clientBalanceOperation($client_id, $credit, 1, true);

                            if (!is_array($list)) {
                                $this->rollback();
                                $data['Clientpayment']['client_payment_id'] = false;
                            } else {
                                $this->commit();
                                $data['Clientpayment']['client_payment_id'] = false;
                            }
                        }
                    }
                }


                $this->commit();
                $data['Clientpayment']['client_payment_id'] = false;;

            } else {
                #payment <invoice
                $balance = (float)$invoice_amt - (float)$amount;//未付清的钱
                if ($this->save($data['Clientpayment'])) {
                    $pay_id = $this->getLastInsertID();
                    #update invocie paid=true
                    $list = $this->query("update invoice set paid=false,total_amount=$balance,pay_amount=pay_amount::numeric+$amount  where invoice_number='$invoice_number'");
                    //   $this->query("update invoice set total_amount=total_amount-$amount  where client_id=$client_id  and invoice_id>$invoice_id");
                    if (!is_array($list)) {
                        $this->rollback();
                        $data['Clientpayment']['client_payment_id'] = false;
                    } else {
                        $this->commit();
                        $data['Clientpayment']['client_payment_id'] = false;
                    }
                }
            }
        }
    }


    #
    public function get_invoice($client_id)
    {
        $client_where = '';
        if (!empty($client_id)) {
            $client_where = "and  client_id={$client_id}";
        }
        $r = $this->query("select  invoice_number,invoice_start,
			(select sum(total_amount) as past_due from invoice as inner_invoice where client_id = invoice.client_id and inner_invoice.invoice_end <= invoice.invoice_end)
			as owe_amount
			 from  invoice  where paid=false   and total_amount<>0  $client_where  order by invoice.invoice_id");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['invoice_number'];
            $name = $r[$i][0]['owe_amount'];
            $l [$key] = $r[$i][0]['invoice_start'] . "--" . $r[$i][0]['invoice_number'] . "(\$" . $name . ")";
        }
        return $l;

    }

    function add_payment($data)
    {
        $invoice_number = array_keys_value($data, 'invoice_number');
        $client_id = array_keys_value($data, 'client_id');
        $time = array_keys_value_empty($data, 'payment_time', date('Y-m-d'));
        $payment_type = array_keys_value_empty($data, 'payment_type');
        $result = array_keys_value_empty($data, 'result', 'true');
        $approved = array_keys_value_empty($data, 'approved', 'true');
        $payment_balance = 0;//给路由伙伴的冲值的钱
        if (!empty($invoice_number)) {
            $list = $this->query("select * from invoice where invoice_number ='$invoice_number'");
            $invoice_payment = $data['amount'];//给INVOICE冲了多少钱
            $payment_arr = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
            $payment_list = $this->query("select COALESCE(sum(amount),0)::numeric(30,5) as payment_amount,payment_type from client_payment where invoice_number = '" . addslashes($invoice_number) . "' group by payment_type");
            foreach ($payment_list as $k => $v) {
                $payment_arr[$v[0]['payment_type']] = $v[0]['payment_amount'];
            }
            //var_dump($list);[0][0]['pay_amount'] + $invoice_payment > $list[0][0]['total_amount']);exit;
            if ($payment_type == 3) {
                if ($invoice_payment > abs($list[0][0]['total_amount'])) {
                    $this->create_json_array('', 101, 'Payment total amount must less than Invoice total amount.');
                    return false;
                }
            } elseif ($payment_arr[2] + $payment_arr[4] - $payment_arr[3] + $invoice_payment > abs($list[0][0]['total_amount']) && abs($payment_arr[2] + $payment_arr[4] - $payment_arr[3] + $invoice_payment - abs($list[0][0]['total_amount'])) > 0.05) {
                //充值金额超过了invoice的total_amount
                $this->create_json_array('', 101, 'Payment total amount must less than Invoice total amount.');
                return false;
            } else {
                //void
            }
            switch ($payment_type) {
                case 3:                                        //offset
                    $current_balance = 0 + $list[0][0]['pay_amount'] - $invoice_payment;
                    $this->query("update invoice set current_balance=current_balance+$invoice_payment,pay_amount=$current_balance where invoice_number ='$invoice_number' ");
                    $this->query("insert into client_payment(payment_time,amount,result,client_id,invoice_number,approved,payment_type) values('$time','$invoice_payment',$result,'$client_id','$invoice_number',$approved,$payment_type) ");
                    break;
                case 2:                                        //credit
                    $current_balance = 0 + $list[0][0]['pay_amount'] + $invoice_payment;
                    if ($current_balance >= abs($list[0][0]['total_amount'])) {
                        $paid = 'true';
                    } else {
                        $paid = 'false';
                    }
                    $this->query("update invoice set current_balance=current_balance+$invoice_payment, paid=$paid, pay_amount=$current_balance where invoice_number ='$invoice_number' ");
                    $this->query("insert into client_payment(payment_time,amount,result,client_id,invoice_number,approved,payment_type) values('$time','$invoice_payment',$result,'$client_id','$invoice_number',$approved,$payment_type) ");
                    break;
                case 1:                                                                //payment
                default:
                    $current_balance = 0 + $invoice_payment + $list[0][0]['pay_amount']; //invoice余额
                    if (1)//($list[0][0]['type'] == 0)//充值只有buy了
                    {
                        $payment_balance = $invoice_payment;
                    } else {
                        //$payment_balance=0-$invoice_payment;
                    }
                    if ($current_balance >= abs($list[0][0]['total_amount'])) {
                        $paid = 'true';
                    } else {
                        $paid = 'false';
                    }
                    //$pay_amount=$invoice_payment+$list[0][0]['pay_amount'];
                    $this->query("update invoice set current_balance=current_balance+$invoice_payment, paid=$paid, pay_amount=$current_balance where invoice_number ='$invoice_number' ");
                    //add payment for invoice here,payment_type is 4
                    $this->query("insert into client_payment(payment_time,amount,result,client_id,invoice_number,approved,payment_type) values('$time','$invoice_payment',$result,'$client_id','$invoice_number',$approved,4) ");


                    $this->clientBalanceOperation($client_id, $payment_balance, 2);
            }
        } else {
            $payment_balance = $data['amount'];

            $this->clientBalanceOperation($client_id, $payment_balance, 2);
            $this->query("insert into client_payment(payment_time,amount,result,client_id,invoice_number,approved,payment_type) values('$time','$payment_balance',$result,$client_id,null,$approved,$payment_type) ");
        }
        return true;
    }
}