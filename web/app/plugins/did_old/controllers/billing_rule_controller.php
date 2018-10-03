<?php

class BillingRuleController extends DidAppController
{

    var $name = 'BillingRule';
    var $uses = array('did.DidBillingPlan', 'did.DidSpecialCode', 'did.OrigLog');
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
        $this->pageTitle = "Origination/Billing Rule";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('DidBillingPlan');
    }

    public function plan_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
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
//                $this->compere_arr($old_data, $this->data);
                $this->data['DidBillingPlan']['id'] = $id;
                $this->Session->write('m', $this->DidBillingPlan->create_json(201, __('The Plan [' . $this->data['DidBillingPlan']['name'] . '] is modified successfully!', true)));
            }
            else
            {
                $log_flg = TRUE;
                $action = 0;
                $log_detail = "BILLING RULE name[{$this->data['DidBillingPlan']['name']}]";
                $this->Session->write('m', $this->DidBillingPlan->create_json(201, __('The Plan [' . $this->data['DidBillingPlan']['name'] . '] is created successfully!', true)));
            }
            $flg = $this->DidBillingPlan->save($this->data);
            $new_id = '';
            if ($flg !== false && isset($log_flg))
            {
                $new_id = $this->DidBillingPlan->getLastInsertID();
                $this->OrigLog->add_orig_log("Billing Rule", $action, $log_detail);
            }
            if($this->params['isAjax'])
            {
                echo $new_id;
                $this->autoLayout = false;
                $this->autoRender = false;
                return;
            }
            $this->xredirect("/did/billing_rule/plan");
        }
        $this->data = $this->DidBillingPlan->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }

    public function delete_rule($id)
    {
        $id = base64_decode($id);
        $data = $this->DidBillingPlan->find('first', Array('conditions' => Array('id' => $id)));
        $flg = $this->DidBillingPlan->del($id);
        if ($flg === false)
        {
            $this->Session->write('m', $this->DidBillingPlan->create_json(101, __('The Plan [' . $data['DidBillingPlan']['name'] . '] is deleted failed!', true)));
        }
        else
        {
            $log_detail = "#{$id} name [{$data['DidBillingPlan']['name']}]";
            $this->OrigLog->add_orig_log("Billing Rule", 1, $log_detail);
            $this->Session->write('m', $this->DidBillingPlan->create_json(201, __('The Plan [' . $data['DidBillingPlan']['name'] . '] is deleted successfully!', true)));
        }
        $this->xredirect("/did/billing_rule/plan");
    }

    public function special_code()
    {
        $this->pageTitle = "Origination/Special Code";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'code' => 'asc',
            ),
        );
        $this->data = $this->paginate('DidSpecialCode');
    }

    public function code_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if ($id != null)
            {
                $this->data['DidSpecialCode']['id'] = $id;
                $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [' . $this->data['DidSpecialCode']['code'] . '] is modified successfully!', true)));
            }
            else
            {
                $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [' . $this->data['DidSpecialCode']['code'] . '] is created successfully!', true)));
            }
            $this->DidSpecialCode->save($this->data);
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
        $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [' . $data['DidSpecialCode']['code'] . '] is deleted successfully!', true)));
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

}
