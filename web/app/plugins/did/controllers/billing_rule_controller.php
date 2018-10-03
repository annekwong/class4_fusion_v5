<?php

class BillingRuleController extends DidAppController
{

    var $name = 'BillingRule';
    var $uses = array('did.DidBillingPlan', 'did.DidSpecialCode', 'did.OrigLog', 'Rate');
    var $components = array('RequestHandler');

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect('/did/billing_rule/plan');
    }

    public function plan()
    {
        Cache::clear();
        $this->pageTitle = "Origination/Billing Rule";


        $this->paginate = array(
            'fields' => array(
                'id', 'did_price', 'min_price', 'name', 'monthly_charge', 'rate_table_id',
                'payphone_subcharge', 'rate_type', 'pay_type', 'fee_per_port', 'price_type'
            ),
            'limit' => isset($_GET['size']) ? $_GET['size'] : 100,
            'order' => array(
                'id' => 'desc',
            ),
        );

        $this->data = $this->paginate('DidBillingPlan');
        
        foreach ($this->data as &$item) {
            if(!empty($item['DidBillingPlan']['rate_table_id'])) {
                $tmpData = $this->Rate->find('first', array(
                    'conditions' => array(
                        'rate_table_id' => $item['DidBillingPlan']['rate_table_id']
                    )
                ));
                $item['DidBillingPlan']['rate_table_name'] = $tmpData['Rate']['name'];
                $item['DidBillingPlan']['pay_type'] = $this->DidBillingPlan->payTypes[$item['DidBillingPlan']['pay_type']];
                $item['DidBillingPlan']['price_type'] = $this->DidBillingPlan->payTypes[$item['DidBillingPlan']['price_type']];
                $item['DidBillingPlan']['rate_type'] = $item['DidBillingPlan']['rate_type'] ? $this->DidBillingPlan->rateTypes[$item['DidBillingPlan']['rate_type']] : '';
            }
        }
    }

    public function plan_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if ($this->check_username_exist($this->data['DidBillingPlan']['name'],$id)){
                $this->Session->write('m', $this->DidBillingPlan->create_json(101, __('The billing rule [%s] is already in use!', true, $this->data['DidBillingPlan']['name'])));
                $this->redirect("plan");
            }

            if (!isset($this->data['DidBillingPlan']['min_price']) || empty($this->data['DidBillingPlan']['min_price'])) {
                $this->data['DidBillingPlan']['min_price'] = 0.0000000001;
            }

            if ($id != null)
            {
                $old_data = $this->DidBillingPlan->findById($id);
                $data = array_diff_assoc($old_data['DidBillingPlan'], $this->data['DidBillingPlan']);
                $match_arr = array(
                    'name' => 'Name',
                    'did_price' => 'Price/DID/Month',
                    'channel_price' => 'Price/Channel Limit',
                    'min_price' => 'Price/Minute',
                    'billed_channels' => 'Price/Max Channel Usage',
                );
                $log_detail_arr = array();
                foreach ($data as $diff_key => $value)
                {
                    if (strcmp($diff_key, 'id') && key_exists($diff_key, $match_arr))
                    {
                        $log_detail_arr[] = $match_arr[$diff_key] . "[" . $old_data['DidBillingPlan'][$diff_key] . "=>" . $this->data['DidBillingPlan'][$diff_key] . "]";
                    }
                }
                $log_detail = implode(";", $log_detail_arr);
                if ($log_detail)
                {//如果有改变才记录到log中
                    $log_detail = "#{$id};" . $log_detail;
                    $log_flg = TRUE;
                    $action = 2;
                }

                // update assigned rates
                $min_price = isset($this->data['DidBillingPlan']['min_price']) ? $this->data['DidBillingPlan']['min_price'] : '';
                $rateTableId = $data['rate_table_id'] ? : '';
                if(($min_price || $min_price === '0') && $rateTableId){
                    $this->loadModel("Clientrate");
                    $update_sql = "UPDATE rate SET rate = '$min_price' WHERE  rate_table_id = {$rateTableId}";
                    $this->Clientrate->query($update_sql);
                }
                if($rateTableId){
                    $this->loadModel("RateTable");
                    $new_rate_table_name = uniqid($this->data['DidBillingPlan']['name'] . "_");
                    $update_sql = "UPDATE rate_table SET name = '$new_rate_table_name' WHERE  rate_table_id = {$rateTableId}";
                    $this->RateTable->query($update_sql);
                }

//                $this->compere_arr($old_data, $this->data);
                $this->data['DidBillingPlan']['id'] = $id;
                $this->Session->write('m', $this->DidBillingPlan->create_json(201, __('The Billing Rule [%s] is modified successfully!', true, $this->data['DidBillingPlan']['name'])));
            } else {
                $this->loadModel('Currency');

                $currency = $this->Currency->find('first', array(
                    'fields' => 'currency_id',
                    'conditions' => array(
                        'code' => 'USA'
                    )
                ));
                $log_flg = TRUE;
                $action = 0;
                $saveRate = array(
                    'name' => uniqid($this->data['DidBillingPlan']['name'] . "_"),
                    'origination' => true,
                    'currency_id' => $currency['Currency']['currency_id']
                );

                if ($_POST['type_rate'] == 3) {
                    $saveRate['rate_type'] = 1;
                    $saveRate['jur_type'] = 1;
                } else {
                    $saveRate['rate_type'] = 0;
                }

                $this->Rate->begin();

                $saveResult = $this->Rate->save($saveRate);
                if ($saveResult == false) {
                    $this->Session->write('m', $this->DidBillingPlan->create_json(101, __(' Create rate table failed!', true)));
                    $this->Rate->rollback();
                    $this->redirect("plan");
                } else {
                    $log_detail = "BILLING RULE name[{$this->data['DidBillingPlan']['name']}]";
                    $this->Session->write('m', $this->DidBillingPlan->create_json(201, __(' The billing rule [%s] is created successfully!', true, $this->data['DidBillingPlan']['name'])));
                    $rateTableId = $this->Rate->getLastInsertId();
                    $this->data['DidBillingPlan']['rate_table_id'] = $rateTableId;

                    if ($_POST['type_rate'] == 1) {
                        foreach (range('a', 'z') as $item) {
                            $this->Rate->query("INSERT INTO rate (rate_table_id, code, rate) VALUES ({$rateTableId}, '{$item}', {$this->data['DidBillingPlan']['min_price']})");
                        }
                        foreach (range('A', 'Z') as $item) {
                            $this->Rate->query("INSERT INTO rate (rate_table_id, code, rate) VALUES ({$rateTableId}, '{$item}', {$this->data['DidBillingPlan']['min_price']})");
                        }
                        for ($item = 0; $item < 10; $item ++) {
                            $this->Rate->query("INSERT INTO rate (rate_table_id, code, rate) VALUES ({$rateTableId}, '{$item}', {$this->data['DidBillingPlan']['min_price']})");
                        }
                    }
                }
            }
            $this->data['DidBillingPlan']['rate_type'] = $_POST['type_rate'];
            $this->data['DidBillingPlan']['pay_type'] = $_POST['pay_type'];
            $this->data['DidBillingPlan']['price_type'] = $_POST['price_type'];
            $flg = $this->DidBillingPlan->save($this->data);
            $new_id = '';
            if ($flg == false) {
                $this->Rate->rollback();
                $this->Session->write('m', $this->DidBillingPlan->create_json(101, "Save failed!"));
            } else if ($flg !== false && isset($log_flg)) {
                $new_id = $this->DidBillingPlan->getLastInsertID();

                $this->Rate->commit();
                $this->OrigLog->add_orig_log("Billing Rule", $action, $log_detail);
            }
            if($this->params['isAjax'])
            {
                echo $new_id;
                $this->autoLayout = false;
                $this->autoRender = false;
                return;
            }

            if ($_POST['type_rate'] == 1 || $id) {
                $this->xredirect("/did/billing_rule/plan");
            } else {
                if (isset($rateTableId)) {
                    $this->xredirect("/clientrates/view/" . base64_encode($rateTableId));
                }
            }
        }
        if ($id) {
            $this->data = $this->DidBillingPlan->find('first', Array('conditions' => Array('id' => $id)));
            if(!empty($this->data['DidBillingPlan']['rate_table_id'])) {
                $tmpData = $this->Rate->find('first', array(
                    'conditions' => array(
                        'rate_table_id' => $this->data['DidBillingPlan']['rate_table_id']
                    )
                ));
                $this->data['DidBillingPlan']['rate_table_name'] = $tmpData['Rate']['name'];
                $this->data['DidBillingPlan']['rate_type_text'] = $this->DidBillingPlan->rateTypes[$this->data['DidBillingPlan']['rate_type']];
            }
        }

        $rateTables = $this->Rate->find('all', array(
            'order' => array('name')
        ));
        $this->set('rateTables', $rateTables);
        $this->set('payTypes', $this->DidBillingPlan->payTypes);
        $this->set('id', $id);
    }

    public function apply_details(){

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if(isset($_POST['rate_table_id']) && $_POST['rate_table_id']){
             $this->Rate->query("UPDATE rate SET min_time='{$_POST["min_time"]}', interval='{$_POST["interval"]}' WHERE rate_table_id='{$_POST["rate_table_id"]}'");
             $this->jsonResponse(['status' => true]);
        }
        $this->jsonResponse(['status' => false]);
    }

    public function check_username_exist($user_name,$id = ''){

        $count = $this->DidBillingPlan->find('count',array(
            'conditions' => array(
                'name' => $user_name,
                'id !=?' => intval($id),
            ),
        ));
        return $count;
    }


    public function delete_rule($id)
    {
        $id = base64_decode($id);
        $data = $this->DidBillingPlan->find('first', Array('conditions' => Array('id' => $id)));
        $flg = $this->DidBillingPlan->del($id);
        if ($flg === false)
        {
            $this->Session->write('m', $this->DidBillingPlan->create_json(101, __('The billing rule [%s] is deleted failed!', true, $data['DidBillingPlan']['name'])));
        }
        else
        {
            $log_detail = "#{$id} name [{$data['DidBillingPlan']['name']}]";
            $this->OrigLog->add_orig_log("Billing Rule", 1, $log_detail);
            $this->Session->write('m', $this->DidBillingPlan->create_json(201, __('The billing rule [%s] is deleted successfully!', true, $data['DidBillingPlan']['name'])));
        }
        $this->xredirect("/did/billing_rule/plan");
    }

    public function special_code()
    {
        $this->redirect("/did/billing_rule/plan");
//        $this->pageTitle = "Origination/Special Code";
//        $this->paginate = array(
//            'limit' => 100,
//            'order' => array(
//                'code' => 'asc',
//            ),
//        );
//        $this->data = $this->paginate('DidSpecialCode');
    }

    public function code_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            $existingRecords = $this->DidSpecialCode->find('first', array(
                'conditions'    =>  array(
                    'DidSpecialCode.code'   =>  $this->data['DidSpecialCode']['code'],
                    'DidSpecialCode.pricing'   =>  $this->data['DidSpecialCode']['pricing']
                )
            ));
            if(!empty($existingRecords)) {
                $this->Session->write('m', $this->DidSpecialCode->create_json(101, "The Special code with same ANI Prefix, Pricing is already in use!"));
            } else {
                if ($id != null) {
                    $this->data['DidSpecialCode']['id'] = $id;
                    $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [%s] is modified successfully!', true, $this->data['DidSpecialCode']['code'])));
                } else {
                    $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [%s] is created successfully!', true, $this->data['DidSpecialCode']['code'])));
                }
                $this->DidSpecialCode->save($this->data);
            }
            $this->xredirect("/did/billing_rule/special_code");
        }
        $this->data = $this->DidSpecialCode->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }

    public function delete_code($id)
    {
        $id = base64_decode($id);
        $data = $this->DidSpecialCode->find('first', Array('conditions' => Array('id' => $id)));
        $this->DidSpecialCode->del($id);
        $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [%s] is deleted successfully!', true, $this->data['DidSpecialCode']['code'])));
        $this->xredirect("/did/billing_rule/special_code");
    }

    public function ajax_judge_billing_rule()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->DidBillingPlan->query("SELECT count(*) as sum FROM did_billing_plan");
        echo $data[0][0]['sum'];
    }

    public function delete_codes($id)
    {
        if (!empty($id)) {
            $tip = '';
            switch ($id) {
                case 'all':
                    $rules = $this->DidSpecialCode->find('all');
                    $deleteIds = array();
                    foreach ($rules as $rule) {
                        array_push($deleteIds, $rule['DidSpecialCode']['id']);
                    }
                    $this->DidSpecialCode->deleteAll(array(
                        'DidSpecialCode.id' =>  $deleteIds
                    ));
                    $this->Session->write('m', $this->DidSpecialCode->create_json(201, "All Special Codes are deleted successfully!"));
                    $this->xredirect("/did/billing_rule/special_code");
                    break;
                case 'selected':
                    $deleteIds = explode(',', $_REQUEST['ids']);
                    $rules = $this->DidSpecialCode->find('all', array(
                        'fields'        =>  array('DidSpecialCode.code'),
                        'conditions'    =>  array('DidSpecialCode.id' => $deleteIds)
                    ));
                    $ruleNames = array();
                    foreach ($rules as $rule) {
                        array_push($ruleNames, $rule['DidSpecialCode']['code']);
                    }
                    $this->DidSpecialCode->deleteAll(array(
                        'DidSpecialCode.id' =>  $deleteIds
                    ));
                    $countRules = count($deleteIds);
                    if($countRules == 1) {
                        $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [%s] is deleted successfully!', true, implode(',', $ruleNames))));
                    } else {
                        $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Codes [%s] are deleted successfully!', true, implode(',', $ruleNames))));
                    }
                    $this->xredirect("/did/billing_rule/special_code");
                    break;
                default:
                    $this->redirect('/did/billing_rule/special_code');
                    break;
            }
        }
        $this->redirect('/did/billing_rule/special_code');
    }

    public function delete_rules($id)
    {
        if (!empty($id)) {
            $tip = '';
            switch ($id) {
                case 'all':
                    $rules = $this->DidBillingPlan->find('all');
                    $deleteIds = array();
                    foreach ($rules as $rule) {
                        array_push($deleteIds, $rule['DidBillingPlan']['id']);
                    }
                    $this->DidBillingPlan->deleteAll(array(
                        'DidBillingPlan.id' =>  $deleteIds
                    ));
                    $this->Session->write('m', $this->DidSpecialCode->create_json(201, "All Billing Rules are deleted successfully!"));
                    $this->xredirect("/did/billing_rule/plan");
                    break;
                case 'selected':
                    $deleteIds = explode(',', $_REQUEST['ids']);
                    $rules = $this->DidBillingPlan->find('all', array(
                        'fields'        =>  array('DidBillingPlan.name'),
                        'conditions'    =>  array('DidBillingPlan.id' => $deleteIds)
                    ));
                    $ruleNames = array();
                    foreach ($rules as $rule) {
                        array_push($ruleNames, $rule['DidBillingPlan']['name']);
                    }
                    $this->DidBillingPlan->deleteAll(array(
                        'DidBillingPlan.id' =>  $deleteIds
                    ));
                    $countRules = count($deleteIds);
                    if($countRules == 1) {
                        $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Billing Rule [%s] is deleted successfully!', true, implode(',', $ruleNames))));
                    } else {
                        $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Billing Rules [%s] are deleted successfully!', true, implode(',', $ruleNames))));
                    }
                    $this->xredirect("/did/billing_rule/plan");
                    break;
                default:
                    $this->redirect('/did/billing_rule/plan');
                    break;
            }
        }
        $this->redirect('/did/billing_rule/plan');
    }

    public function view_rates($encodedId)
    {
//        $this->requestAction("/clientrates/view/{$encodedId}", ['data' => [
//            'breadcrumbs' => ['Origination', 'Billing Rules']
//        ]]);
        $this->redirect("/clientrates/view/{$encodedId}?breadcrumbs=Origination,Billing Rules");
    }

}
