<?php
/**
 * Class ImportRule
 *
 */
class ImportRuleController extends AppController
{

    var $name = 'ImportRule';
    var $uses = array('RateSendRules', 'prresource.Gatewaygroup', 'Rate');
    var $components = array();

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');

        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
    }

    public function index()
    {
        $this->redirect('view');
    }

    public function view()
    {
        Configure::write('debug', 0);
        $this->pageTitle = 'Import Rule Listing';
        $this->paginate = array(
            'fields' => 'RateSendRules.id,RateSendRules.rule_name,RateSendRules.active,RateSendRules.create_time,
            RateSendRules.update_at,RateSendRules.update_by,Resource.alias, RateTable.name',
            'limit' => isset($this->params['url']['size']) ? $this->params['url']['size'] : 100,
            'joins' => array(
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Resource.resource_id = RateSendRules.resource_id',
                    )
                ),
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = Resource.rate_table_id',
                    )
                )
            ),
            'order' => array(
                'RateSendRules.id' => 'desc',
            ),
            'conditions' => [],
        );
        $this->data = $this->paginate('RateSendRules');
    }

    public function save_rule($encode_rule_id = '')
    {
        if ($encode_rule_id) {
            $this->pageTitle = __('Edit Auto Import Rule', true);
            $rule_id = base64_decode($encode_rule_id);
        }else {
            $this->pageTitle = __('Add Auto Import Rule', true);
        }
        $this->init();
        if ($this->RequestHandler->ispost()) {
            $rule = $this->data;
            $rule['create_at'] = date("Y-m-d   H:i:s");
            $rule['update_at'] = date("Y-m-d   H:i:s");
            $rule['update_by'] = $_SESSION['sst_user_name'];
            $rule['link_in_email'] = !$rule['link_in_email'] ? 'false' : 'true';
            $rule['country_code'] = !empty($rule['country_code']) ? implode(',', $rule['country_code']) : NULL;
            if ($encode_rule_id) {
                $rule['id'] = $rule_id;
                $action = 'modified';
            } else {
                $action = 'created';
            }
            if ($this->RateSendRules->save($rule) === false) {
                $this->Session->write('m', $this->RateSendRules->create_json(101, __('Rule save failed', true)));
            }else{
                $this->Session->write('m', $this->RateSendRules->create_json(201, __('The %s [%s] is ' . $action . ' successfully!', true, array(__('rule', true), $rule['rule_name']))));
            }
            $this->redirect('view');
        }
        if ($encode_rule_id) {
            $rule_id = base64_decode($encode_rule_id);
            $rule_info = $this->RateSendRules->find('first', array(
                'conditions' => array(
                    'id' => $rule_id
                )
            ));
            $this->data = $rule_info['RateSendRules'];
        }
    }

    public function ajax_check_rule_name()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $rule_name = $this->params['form']['rule_name'];
        $rule_id = $this->params['form']['rule_id'];
        if (!$rule_name)
            return json_encode(array('status' => 0, 'msg' => __('[%s]can_not_null', true, array(__('rule name', true)))));

        $sql = "SELECT count(*) as cnt FROM rate_send_rules WHERE rule_name = '{$rule_name}' AND id != ".intval($rule_id);
        $result = $this->RateSendRules->query($sql);
        if ($result[0][0]['cnt'])
            $this->jsonResponse(array('status' => 0, 'msg' => __('The name[%s] has already been taken', true, array($rule_name))));
        else
            $this->jsonResponse(array('status' => 1, 'msg' => ''));
    }

    public function delete($encode_id)
    {
        $id = base64_decode($encode_id);
        $flg = $this->RateSendRules->delete($id);
        if ($flg === false)
        {
            $this->RateSendRules->create_json_array('', 101, __('Failed!', true));
        }
        else
        {
            $this->RateSendRules->create_json_array('', 201, __('Succeed!', true));
        }
        $this->Session->write("m", RateSendRules::set_validator());
        $this->redirect('view');
    }



    public function disable_rule($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->RateSendRules->find('count', array('id' => $id));
        $rule = $this->RateSendRules->find('first', array('id' => $id));
        if (!$count) {
            $this->Session->write('m', $this->RateSendRules->create_json(101, __('Illegal operation', true)));
            $this->redirect('rules');
        }
        $update_arr = array(
            'id' => $id,
            'active' => false
        );
        if ($this->RateSendRules->save($update_arr) === false)
            $this->Session->write('m', $this->RateSendRules->create_json(101, __('Disable failed', true)));
        else
            $this->Session->write('m', $this->RateSendRules->create_json(201, "The Rule [{$rule['RateSendRules']['rule_name']}] is deactivated successfully!"));
        $this->redirect('view');
    }


    public function enable_rule($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->RateSendRules->find('count', array('id' => $id));
        $rule = $this->RateSendRules->find('first', array('id' => $id));
        if (!$count) {
            $this->Session->write('m', $this->RateSendRules->create_json(101, __('Illegal operation', true)));
            $this->redirect('rules');
        }
        $update_arr = array(
            'id' => $id,
            'active' => true
        );
        if ($this->RateSendRules->save($update_arr) === false)
            $this->Session->write('m', $this->RateSendRules->create_json(101, __('Enable failed', true)));
        else
            $this->Session->write('m', $this->RateSendRules->create_json(201, "The Rule [{$rule['RateSendRules']['rule_name']}] is activated successfully!"));
        $this->redirect('view');
    }

    private function init(){
        $this->set('file_format', $this->RateSendRules->file_format());
        $this->set('egress_trunks', $this->Gatewaygroup->get_import_rule_egress());
        $this->set('effective_date_from', $this->RateSendRules->effective_date_from());
        $this->set('start_from', $this->RateSendRules->start_from());
        $this->set('position', $this->RateSendRules->position());
        $this->set('position_opt', $this->RateSendRules->position_opt());
        $this->set('country_code', array_combine(array_keys($this->get_countries()), array_keys($this->get_countries())));
        $this->set('date_pattern', $this->RateSendRules->date_pattern());
        $this->set('violation_action', $this->RateSendRules->violation_action());
        $this->set('multiple_sheet', $this->RateSendRules->multiple_sheet());
        $this->set('tab_index', $this->RateSendRules->tab_index());
        $this->set('filter_by', $this->RateSendRules->filter_by());
        $decks = $this->Rate->get_code_decks();
        $code_decks = [''];
        foreach($decks as $deck){
            $code_decks[$deck[0]['code_deck_id']] = $deck[0]['name'];
        }
        $this->set('code_decks', $code_decks);
    }

}

?>
