<?php

class PaymenttermsController extends AppController
{

    var $name = 'Paymentterms';
    var $helpers = array('html', 'javascript', 'AppPaymentterms');

    function index()
    {
        $this->redirect('payment_term');
    }

    //读取该模块的执行和修改权限
    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    function js_save()
    {
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $id = $this->_get('id');
        if (!empty($id))
        {
            $this->data = $this->Paymentterm->find('first', Array('conditions' => "payment_term_id=$id"));
        }
    }

    function js_save_1()
    {
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $id = $this->_get('id');
        if (!empty($id))
        {
            $this->data = $this->Paymentterm->find('first', Array('conditions' => "payment_term_id=$id"));
        }
    }

    public function finance_fee()
    {
        $where = "and finance_rate != 0 ";
        $this->pageTitle = "Management/Payment Term";
        $currPage = 1;
        $pageSize = 100;
        $search = null;
//		if (! empty ( $_REQUEST ['page'] )) {
//			$currPage = $_REQUEST ['page'];
//		}
//		if (! empty ( $_REQUEST ['size'] )) {
//			$pageSize = $_REQUEST ['size'];
//		}
        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (!empty($_REQUEST['edit_id']))
        {
            $sql = "select payment_term_id,name,type,days,
							grace_days,notify_days,more_days,finance_rate, 
							(select count(client_id) from client 
							where client.payment_term_id=payment_term.payment_term_id) as clients
							from payment_term where payment_term_id = {$_REQUEST['edit_id']}
	  		" . $where;
            $result = $this->Paymentterm->query($sql);
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
            $results = $this->Paymentterm->getAllTerms($currPage, $pageSize, $search, $this->_order_condtions(array('payment_term_id', 'name', 'grace_days', 'notify_days', 'clients'))
                    , $where);
        }
        $this->set('p', $results);
    }

    public function transaction_fee()
    {

        $order_sql = "order by id desc";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

//        $sql = "select * from service_charge_items {$order_sql}";
//        $res = $this->Paymentterm->query($sql);
        //var_dump($res);
//        $this->set('res', $res);
    }

    public function del_transaction_fee($id = null)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        if (!empty($id))
        {
//            $sql = "delete from service_charge_items where id = {$id}";
//            $res = $this->Paymentterm->query($sql);
            $this->Paymentterm->create_json_array('', 201, "Deleted succesfully");
            //var_dump($res);
            $this->xredirect('/paymentterms/transaction_fee');
        }
    }

    public function update_transaction_fee()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 2);
        $min_rate = $_POST['min'];
        $max_rate = $_POST['max'];
        $charge_value = $_POST['charge'];
        $id = $_POST['id'];
//        $sql = "update service_charge_items set min_rate = {$min_rate} , max_rate = {$max_rate} , charge_value = {$charge_value} where id = {$id}";
//        $res = $this->Paymentterm->query($sql);

//        if (is_array($res))
//        {
//            echo 'yes';
//        }
//        else
//        {
//            echo "no";
//        }
    }

    public function add_transaction_fee()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $min_rate = $_POST['min'];
        $max_rate = $_POST['max'];
        $charge_value = $_POST['charge'];
//        $sql = "insert into service_charge_items (min_rate,max_rate,charge_value) values ({$min_rate}, {$max_rate}, {$charge_value})";
//        $res = $this->Paymentterm->query($sql);
//        if (is_array($res))
//        {
//            echo 'yes';
//        }
//        else
//        {
//            echo "no";
//        }
    }

    public function payment_term()
    {
        $this->pageTitle = "Management/Payment Term";
        $currPage = 1;
        $pageSize = 100;
        $search = null;
//		if (! empty ( $_REQUEST ['page'] )) {
//			$currPage = $_REQUEST ['page'];
//		}
//		if (! empty ( $_REQUEST ['size'] )) {
//			$pageSize = $_REQUEST ['size'];
//		}
        if (!empty($_REQUEST['search']) && strcmp('Search', $_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (!empty($_REQUEST['edit_id']))
        {
            $sql = "select payment_term_id,name,type,days,
							grace_days,notify_days,more_days,finance_rate, 
							(select count(client_id) from client 
							where client.payment_term_id=payment_term.payment_term_id) as clients
							from payment_term where payment_term_id = {$_REQUEST['edit_id']}
	  		";
            $result = $this->Paymentterm->query($sql);
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
            $results = $this->Paymentterm->getAllTerms($currPage, $pageSize, $search, $this->_order_condtions(array('payment_term_id', 'name', 'grace_days', 'notify_days', 'clients'))
            );
        }
        $this->set('p', $results);
    }

    function getuseage($id)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $result = $this->Paymentterm->query("SELECT name FROM client WHERE payment_term_id = {$id} ORDER BY name ASC");
        $arr = array();
        foreach ($result as $val)
        {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    private function validate_terms($f, $needcheckname, $url)
    {
        $name = $f['name'];
        $hasError = false;
        if (empty($name))
        {
            $hasError = true;
            $this->Paymentterm->create_json_array('#name', 101, __('termnamenull', true));
        }
        else
        {
            if (!preg_match('/^[\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff_]+$/', $name))
            {
                $hasError = true;
                $this->Paymentterm->create_json_array('#name', 101, __('termnameformat', true));
            }
        }

        if ($needcheckname == true)
        {
            $names = $this->Paymentterm->query("select payment_term_id from payment_term where name = '$name'");
            if (count($names) > 0)
            {
                $hasError = true;
                $this->Paymentterm->create_json_array('#name', 101, __('termnameexists', true));
            }
        }

        $days = $f['days'];
        if (empty($days))
        {
            $hasError = true;
            $this->Paymentterm->create_json_array('#days', 101, __('termdatenull', true));
        }
        else
        {
            if (!preg_match('/^\d+$/', $days) || $days < 0)
            {
                $hasError = true;
                $this->Paymentterm->create_json_array('#days', 101, __('termdateformat', true));
            }
        }

        $grace_days = $f['grace_days'];
        if (!empty($grace_days))
        {
            if (!preg_match('/^\d+$/', $grace_days) || $grace_days < 0)
            {
                $hasError = true;
                $this->Paymentterm->create_json_array('#grace_days', 101, __('gracedaysformat', true));
            }
        }

        $notify = $f['notify'];
        if (!empty($notify))
        {
            if (!preg_match('/^\d+$/', $grace_days) || $notify < 0)
            {
                $hasError = true;
                $this->Paymentterm->create_json_array('#notify', 101, __('notifyformat', true));
            }
        }

        if ($hasError == true)
        {
            $this->Session->write('backform', $f);
            $this->Session->write('m', Paymentterm::set_validator());
            $this->xredirect($url);
        }
    }

    /*
     * 添加付款规则
     */

    public function add_payment_term($is_config = '')
    {
        if (!$_SESSION['role_menu']['Switch']['paymentterms']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            if ($this->data['Paymentterm']['type'] == 4)
            {
                $more_days_arr = explode(",",$this->data['Paymentterm']['days']);
                sort($more_days_arr);
                $this->data['Paymentterm']['more_days'] = implode(",",$more_days_arr);
                $this->data['Paymentterm']['days'] = -1;
            }
            if ($this->data['Paymentterm']['type'] == 5)
            {
                $this->data['Paymentterm']['days'] = -1;
            }
            $payment_term_name = $this->data['Paymentterm']['name'];
            if ($this->Paymentterm->save($this->data))
            {
                $id = $this->Paymentterm->getlastinsertId();
                $rollback_sql = "DELETE FROM payment_term WHERE payment_term_id = {$id}";
                $rollback_msg = "Create Payment Term [" . $this->data['Paymentterm']['name'] . "] operation have been rolled back!";
                $this->Paymentterm->logging(0, 'Payment Term', "Name:{$payment_term_name}", $rollback_sql, $rollback_msg);
                $this->Paymentterm->create_json_array('', 201, __('The Payment Term [%s] is added successfully.', true, $this->data['Paymentterm']['name']));
            }
            else
            {
                $this->Paymentterm->create_json_array('', 101, __('Fail to create Payment Term.', true));
            }
            if ($is_config)
                $this->xredirect("/necessary_configuration/setup_payment_term/{$_SESSION['sst_user_id']}");
            $this->xredirect('/paymentterms/payment_term');
        }
    }

    public function add_payment_term_exchange()
    {
        if (!$_SESSION['role_menu']['Switch']['paymentterms']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            if ($this->data['Paymentterm']['type'] == 4)
            {
                $this->data['Paymentterm']['more_days'] = $this->data['Paymentterm']['days'];
                $this->data['Paymentterm']['days'] = -1;
            }
            if ($this->Paymentterm->save($this->data))
            {
                $this->Paymentterm->create_json_array('', 201, __('The Payment Term [%s] is created successfully.', true, $this->data['Paymentterm']['name']));
            }
            else
            {
                $this->Paymentterm->create_json_array('', 201, __('Fail to create Payment Term.', true));
            }
            $this->xredirect('/paymentterms/finance_fee');
        }
    }

    /*
     * 修改付款规则
     */

    public function edit_payment_term($id)
    {
        if (!$_SESSION['role_menu']['Switch']['paymentterms']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            if (!empty($id))
            {
                $this->data['Paymentterm']['payment_term_id'] = $id;
            }
            if ($this->data['Paymentterm']['type'] == 4)
            {
                $more_days_arr = explode(",",$this->data['Paymentterm']['days']);
                sort($more_days_arr);
                $this->data['Paymentterm']['more_days'] = implode(",",$more_days_arr);
                $this->data['Paymentterm']['days'] = -1;
            }
            $payment_term = $this->Paymentterm->findByPaymentTermId($id);
            $payment_term_name = $payment_term['Paymentterm']['name'];

            if ($this->Paymentterm->xsave($this->data))
            {
                $rollback_data = array();
                foreach ($this->data['Paymentterm'] as $key => $value)
                {
                    if ($payment_term['Paymentterm'][$key] != $value && strcmp($key, 'update_by'))
                    {
                        if (is_string($value))
                        {
                            $rollback_data[] = $key . " = '" . $payment_term['Paymentterm'][$key] . "'";
                        }
                        else
                        {
                            $rollback_data[] = $key . " = " . $payment_term['Paymentterm'][$key];
                        }
                    }
                }
                $rollback_update_sql = implode(',', $rollback_data);
                $rollback_sql = "UPDATE payment_term SET {$rollback_update_sql} WHERE payment_term_id = {$id}";
                $rollback_msg = "Modify Payment Term [" . $this->data['Paymentterm']['name'] . "] operation have been rolled back!";
                $this->Paymentterm->logging(2, 'Payment Term', "Name:{$payment_term_name}", $rollback_sql, $rollback_msg);
                $this->Paymentterm->create_json_array('', 201, __('The Payment Term [%s] is modified successfully!', true, $this->data['Paymentterm']['name']));
            }
            else
            {
                $this->Paymentterm->create_json_array('', 101, __('%s name is already in use!', true, $this->data['Paymentterm']['name']));
            }
            $this->xredirect('/paymentterms/payment_term');
        }
    }

    public function edit_payment_term_exchange($id)
    {
        if (!$_SESSION['role_menu']['Switch']['paymentterms']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost())
        {
            if (!empty($id))
            {
                $this->data['Paymentterm']['payment_term_id'] = $id;
            }
            if ($this->data['Paymentterm']['type'] == 4)
            {
                $this->data['Paymentterm']['more_days'] = $this->data['Paymentterm']['days'];
                $this->data['Paymentterm']['days'] = -1;
            }
            if ($this->Paymentterm->xsave($this->data))
            {

                $this->Paymentterm->create_json_array('', 201, __('The Payment Term [%s] is modified successfully!', true, $this->data['Paymentterm']['name']));
            }
            else
            {
                $this->Paymentterm->create_json_array('', 101, __('%s name is already in use!', true, $this->data['Paymentterm']['name']));
            }
            $this->xredirect('/paymentterms/finance_fee');
        }
    }

    function checkName()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $name = $this->_get('name');
        $id = $this->_get('id');
        $list = $this->Paymentterm->query("select count(*) from payment_term where name='$name' and payment_term_id<>'$id'");
        $count = array_keys_value($list, '0.0.count', 0);
        if ($count > 0)
        {
            /* pr($this->data);
              $name_count=$this->Paymentterm->query("select count(name) as name_count from payment_term where name='".$this->data['Paymentterm']['name']."'");
              if($name_count[0][0]['name_count']>0){ */
            echo 'false';
        }
    }

    function paymentterm_name($id = null)
    {
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $paymentterm_name = $this->_get('paymentterm_name');
        $conditions = Array("name='$paymentterm_name'");
        if (!empty($id))
        {
            $conditions = Array("payment_term_id <> $id");
        }
        $conditions = join($conditions, ' and ');
        if (!empty($conditions))
        {
            $conditions = " where $conditions";
        }
        $count = $this->Paymentterm->query("select count(*) from payment_term $conditions");
        if ($count[0][0]['count'] > 0)
        {
            echo 'false';
        }
    }

    public function del_term($id)
    {
        if (!$_SESSION['role_menu']['Switch']['paymentterms']['model_w'])
        {
            $this->redirect_denied();
        }
        $used = $this->Paymentterm->query("select count(client_id) as c from client where payment_term_id = '$id'");
        if ($used[0][0]['c'] > 0)
        {
            $this->Paymentterm->create_json_array('', 101, __('termused', true));
        }
        else
        {
            $payment_term = $this->Paymentterm->findByPaymentTermId($id);
            $payment_term_name = $payment_term['Paymentterm']['name'];
            if ($this->Paymentterm->del($id))
            {
                $filed_arr = array();
                $value_arr = array();
                $old_data_arr = $payment_term['Paymentterm'];
                unset($old_data_arr['payment_term_id']);
                foreach ($old_data_arr as $key => $value)
                {
                    if ($value)
                    {
                        $filed_arr[] = $key;
                        if (!strcmp('name', $key) || !strcmp('more_days', $key) || !strcmp('finance_rate', $key))
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
                $rollback_sql = "INSERT INTO payment_term ({$filed_str}) VALUES ({$value_str})";
                $rollback_msg = "Delete Payment Term [" . $payment_term_name . "] operation have been rolled back!";
                $this->Paymentterm->logging(1, 'Payment Term', "Name:{$payment_term_name}", $rollback_sql, $rollback_msg);
                $this->Paymentterm->create_json_array('', 201, __('The payment term [' . $payment_term_name . '] is deleted successfully!', true));
            }
            else
            {
                $this->Paymentterm->create_json_array('', 101, __('Fail to delete Payment Term.', true));
            }
        }
        $this->Session->write("m", Paymentterm::set_validator());
        $this->redirect('/paymentterms/payment_term');
    }

    public function all_transaction_fee()
    {
        $this->pageTitle = 'Transaction/All Transaction Fee';

        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
            $where = "where name like '%{$search}%'";
        }
        else
        {
            $where = "";
        }

        $sql = "select
                count(*)
                from transaction_fee {$where}";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->Paymentterm->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "select * from transaction_fee {$where} order by id LIMIT {$pageSize} OFFSET {$offset}";

        $data = $this->Paymentterm->query($sql);
        $page->setDataArray($data);
        $this->set('p', $page);
    }

    public function add_transaction()
    {

        if (!empty($_REQUEST["name"]))
        {
            $name = $_REQUEST["name"];
            $is_default = $_REQUEST["is_default"];

            $res = $this->Paymentterm->query("insert into transaction_fee (name,is_default) values ('{$name}','{$is_default}')");

            $this->Paymentterm->create_json_array('', 201, __('The Transaction Fee[%s]is added  successfully.', true, $name));
            $this->xredirect('/paymentterms/all_transaction_fee');
        }
    }

    public function check_transaction($name, $is_default)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        if (!empty($name))
        {
            $res1 = $this->Paymentterm->query("select * from transaction_fee where name = '{$name}'");
            if (count($res1) > 0)
            {
                echo "name_no";
            }
            else
            {
                $res2 = $this->Paymentterm->query("select * from transaction_fee where is_default = '{$is_default}'");

                if (count($res2) > 0 && $is_default == 't')
                {
                    echo "default_no";
                }
                else
                {
                    echo "yes";
                }
            }
        }
    }

    public function check_transaction1($name, $is_default, $id)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        if (!empty($name))
        {
            $res1 = $this->Paymentterm->query("select * from transaction_fee where name = '{$name}' and id != {$id}");
            if (count($res1) > 0)
            {
                echo "name_no";
            }
            else
            {
                $res2 = $this->Paymentterm->query("select * from transaction_fee where is_default = '{$is_default}' and id != {$id}");

                if (count($res2) > 0 && $is_default == 't')
                {
                    echo "default_no";
                }
                else
                {
                    echo "yes";
                }
            }
        }
    }

    public function delete_transaction($id = null)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $re = $this->Paymentterm->query("select * from client where transaction_fee_id = {$id}");
        if (count($re) != 0)
        {
            $this->Paymentterm->create_json_array('', 101, __('This Transaction Fee is being used!', true));
            $this->xredirect('/paymentterms/all_transaction_fee');
        }
        else
        {
            $sql = "select name from transaction_fee where id = {$id}";
            $result = $this->Paymentterm->query($sql);
            $res = $this->Paymentterm->query("delete from transaction_fee where id = {$id}");
            $res = $this->Paymentterm->query("delete from transaction_fee_items where transaction_fee_id = {$id}");
            //$this->Paymentterm->create_json_array('', 201,"Successful delete!",true);
            $this->Paymentterm->create_json_array('', 201, __('The Transaction Fee %s is deleted successfully.', true, $result[0][0]['name']));
            $this->xredirect('/paymentterms/all_transaction_fee');
        }
    }

    public function edit_transaction($id = null)
    {
        if (!empty($_REQUEST["name"]))
        {
            $name = $_REQUEST["name"];
            $is_default = $_REQUEST["is_default"];
            $res = $this->Paymentterm->query("update transaction_fee set name = '{$name}', is_default = '{$is_default}' where id = {$id}");
            $this->Paymentterm->create_json_array('', 201, __('The Transaction Fee[%s]is modified  successfully.', true, $name));
            $this->xredirect('/paymentterms/all_transaction_fee');
        }
        else
        {
            if (!empty($id))
            {
                $res = $this->Paymentterm->query("select * from transaction_fee where id = {$id}");
                $this->set('res', $res);
            }
        }
    }

    public function view_finance_fee_item($id = null)
    {
        $this->pageTitle = 'Transaction/View Transaction Fee Item';
        $sql = "select * from transaction_fee_items  tf left join payment_term pt on tf.trans_id = pt.payment_term_id where tf.trans_type = 1 and tf.transaction_fee_id = {$id} order by tf.id ";

        $data = $this->Paymentterm->query($sql);
        $this->set("id", $id);
        $this->set('data', $data);
    }

    public function view_transaction_fee_item($id = null)
    {
        $this->pageTitle = 'Transaction/View Transaction Fee Item';
        $sql = "select tf.id as transaction_item_id,* from transaction_fee_items tf where tf.trans_type = 2  and tf.transaction_fee_id = {$id} order by tf.id ";

        $data = $this->Paymentterm->query($sql);
        $this->set("id", $id);
        $this->set('data', $data);
    }

    public function add_transaction_item($id = null)
    {
        if (!empty($_REQUEST["transaction_fee_id"]))
        {
            $transaction_fee_id = $_REQUEST["transaction_fee_id"];
            $use_fee = $_REQUEST["use_fee"];
            $trans_id = $_REQUEST["trans_id"];

            $res = $this->Paymentterm->query("insert into transaction_fee_items (trans_type,transaction_fee_id,trans_id,use_fee) values (2,{$transaction_fee_id},{$trans_id},{$use_fee})");
            $this->Paymentterm->create_json_array('', 201, __('Succeeded', true));
            //var_dump($res);
            $this->xredirect('/paymentterms/view_transaction_fee_item/' . $transaction_fee_id);
        }
        else
        {
//            $sql = "select * from service_charge_items";
//            $res = $this->Paymentterm->query($sql);

            //var_dump($res);
            $this->set("id", $id);
//            $this->set('service_charge_items', $res);
        }
    }

    public function add_finance_item($id = null)
    {
        if (!empty($_REQUEST["transaction_fee_id"]))
        {
            $transaction_fee_id = $_REQUEST["transaction_fee_id"];
            $use_fee = $_REQUEST["use_fee"];
            $trans_id = $_REQUEST["trans_id"];

            $is_have = $this->Paymentterm->query("select count(*) from transaction_fee_items where trans_type = 1 and transaction_fee_id = {$transaction_fee_id}");

            if ($is_have[0][0]['count'] != 0)
            {
                $this->Paymentterm->create_json_array('', 101, __('can not add!', true));
                $this->xredirect('/paymentterms/view_finance_fee_item/' . $transaction_fee_id);
            }
            else
            {
                $res = $this->Paymentterm->query("insert into transaction_fee_items (trans_type,transaction_fee_id,trans_id,use_fee) values (1,{$transaction_fee_id},{$trans_id},{$use_fee})");
                $this->Paymentterm->create_json_array('', 201, __('Succeeded', true));
                //var_dump($res);
                $this->xredirect('/paymentterms/view_finance_fee_item/' . $transaction_fee_id);
            }
        }
        else
        {
            $sql = "select * from payment_term where finance_rate != 0";
            $res = $this->Paymentterm->query($sql);

            //var_dump($res);
            $this->set("id", $id);
            $this->set('payment_terms', $res);
        }
    }

    public function check_transaction_item($trans_id = null, $transaction_fee_id = null)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        if (!empty($trans_id))
        {
            $res1 = $this->Paymentterm->query("select * from transaction_fee_items where trans_type = 2 and 
                trans_id = {$trans_id} and transaction_fee_id = {$transaction_fee_id}
                ");
            if (count($res1) > 0)
            {
                echo "no";
            }
            else
            {
                echo "yes";
            }
        }
    }

    public function check_finance_item($trans_id = null, $transaction_fee_id = null)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        if (!empty($trans_id))
        {
            $res1 = $this->Paymentterm->query("select * from transaction_fee_items where trans_type = 1 and 
                trans_id = {$trans_id} and transaction_fee_id = {$transaction_fee_id}
                ");

            if (count($res1) > 0)
            {
                echo "no";
            }
            else
            {
                echo "yes";
            }
        }
    }

    public function check_finance_item1($trans_id = null, $transaction_fee_id = null, $id)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        if (!empty($trans_id))
        {
            $res1 = $this->Paymentterm->query("select * from transaction_fee_items where trans_type = 1 and 
                trans_id = {$trans_id} and transaction_fee_id = {$transaction_fee_id} and id != $id
                ");

            if (count($res1) > 0)
            {
                echo "no";
            }
            else
            {
                echo "yes";
            }
        }
    }

    public function check_transaction_item1($trans_id = null, $transaction_fee_id = null, $id)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        if (!empty($trans_id))
        {
            $res1 = $this->Paymentterm->query("select * from transaction_fee_items where trans_type = 2 and 
                trans_id = {$trans_id} and transaction_fee_id = {$transaction_fee_id} and id != $id
                ");
            if (count($res1) > 0)
            {
                echo "no";
            }
            else
            {
                echo "yes";
            }
        }
    }

    public function delete_transaction_item($id = null, $transaction_fee_id = null)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $res = $this->Paymentterm->query("delete from transaction_fee_items where id = {$id}");
        $this->Paymentterm->create_json_array('', 201, __('Succeeded', true));
        $this->xredirect('/paymentterms/view_transaction_fee_item/' . $transaction_fee_id);
    }

    public function delete_finance_item($id = null, $transaction_fee_id = null)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $res = $this->Paymentterm->query("delete from transaction_fee_items where id = {$id}");
        $this->Paymentterm->create_json_array('', 201, __('Succeeded', true));
        $this->xredirect('/paymentterms/view_finance_fee_item/' . $transaction_fee_id);
    }

    public function edit_transaction_item($id = null)
    {
        if (!empty($_REQUEST["transaction_fee_id"]))
        {
            $transaction_fee_id = $_REQUEST["transaction_fee_id"];
            $use_fee = $_REQUEST["use_fee"];
            $trans_id = $_REQUEST["trans_id"];

            $res = $this->Paymentterm->query("update transaction_fee_items  set trans_id = {$trans_id},use_fee = {$use_fee} where id = {$id}");
            $this->Paymentterm->create_json_array('', 201, __('Succeeded', true));
            //var_dump($res);
            $this->xredirect('/paymentterms/view_transaction_fee_item/' . $transaction_fee_id);
        }
        else
        {
            $sql = "select * from transaction_fee_items where id = {$id}";
            $res = $this->Paymentterm->query($sql);

            //var_dump($res);
//            $sql = "select * from service_charge_items";
//            $res1 = $this->Paymentterm->query($sql);

            //var_dump($res);
            $this->set("id", $id);
//            $this->set('service_charge_items', $res1);
            $this->set('transaction_fee_items', $res);
        }
    }

    public function edit_finance_item($id = null)
    {
        if (!empty($_REQUEST["transaction_fee_id"]))
        {
            $transaction_fee_id = $_REQUEST["transaction_fee_id"];
            $use_fee = $_REQUEST["use_fee"];
            $trans_id = $_REQUEST["trans_id"];

            $res = $this->Paymentterm->query("update transaction_fee_items  set trans_id = {$trans_id},use_fee = {$use_fee} where id = {$id}");
            $this->Paymentterm->create_json_array('', 201, __('Succeeded', true));
            $this->xredirect('/paymentterms/view_finance_fee_item/' . $transaction_fee_id);
        }
        else
        {
            $sql = "select * from transaction_fee_items where id = {$id}";
            $res = $this->Paymentterm->query($sql);

            //var_dump($res);
            $sql = "select * from payment_term where finance_rate != 0";
            $res1 = $this->Paymentterm->query($sql);

            //var_dump($res);
            $this->set("id", $id);
            $this->set('payment_terms', $res1);
            $this->set('transaction_fee_items', $res);
        }
    }

}
