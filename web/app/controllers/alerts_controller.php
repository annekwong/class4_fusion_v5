<?php

class AlertsController extends AppController
{

    var $name = 'Alerts';
    //var $helpers = array('javascript','html','AppAlerts');
    var $components = array('RequestHandler');
    var $uses = array("Condition", "BlockLog", "BlockTroubleTicket", "Action", "AlertRule", "AlertReport", 'BlockAni', 'ResourceBlock',
        'TroubleTicketsTemplate', 'BlockTicket', 'AlertRules', 'InvalidNumber', 'AlertRulesLog', 'AlertRulesLogDetail');

    function index()
    {
        $this->redirect('condition');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    public function put_into_exclude_anis($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $block_ani = $this->BlockAni->findById($id);
        $ani = $block_ani['BlockAni']['ani'];
        $action_id = $block_ani['BlockAni']['action_id'];

        $sql = "select exclude_ani from alert_action where id = {$action_id}";
        $result = $this->Action->query($sql);
        $excluded_anis = $result[0][0]['exclude_ani'];
        $excluded_anis = explode(',', $excluded_anis);
        array_push($excluded_anis, $ani);

        $excluded_anis = implode(',', $excluded_anis);
        $sql = "update alert_action set exclude_ani = '{$excluded_anis}' where id = {$action_id}";
        $this->Action->query($sql);

        $this->BlockAni->del($id);

        $this->BlockAni->create_json_array('', 201, __('The Block ANI [%s] is excluded successfully.', true, $block_ani['BlockAni']['ani']));
        $this->Session->write('m', BlockAni::set_validator());
        $this->xredirect("/alerts/block_ani");
    }

    public function change_exclude_ani()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = $_POST['id'];
        $anis = $_POST['anis'];
        $anis_arr = explode("\n", $anis);
        foreach ($anis_arr as $key => $anis_item) {
            if (!trim($anis_item))
                unset($anis_arr[$key]);
        }
        $anis = implode(',', $anis_arr);
        $sql = "update alert_action set exclude_ani = '{$anis}' where id = {$id}";
        $this->Action->query($sql);
        echo 1;
    }

    public function get_exclude_anis()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = $_POST['id'];
        $sql = "select exclude_ani from alert_action where id = {$id}";
        $result = $this->Action->query($sql);
        $excluded_anis = $result[0][0]['exclude_ani'];
        $excluded_anis = str_replace(',', "\n", $excluded_anis);
        echo json_encode(array(
            'data' => $excluded_anis,
        ));
    }

    public function trouble_tickets()
    {
        $this->pageTitle = "Trouble Tickets";

        $conditions = array();

        if (isset($_GET['search_type'])) {
            $search_type = (int)$_GET['search_type'];
            if ($search_type == 0) {
                $conditions['code_name ILIKE'] = '%' . $_GET['search'] . '%';
            } elseif ($search_type == 1) {
                $conditions['rule_name ILIKE'] = '%' . $_GET['search'] . '%';
            }
        }
        if (isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] != 0)
            $conditions['ingress'] = $_GET['ingress_trunk'];
        if (isset($_GET['egress_trunk']) && $_GET['egress_trunk'] != 0)
            $conditions['egress'] = $_GET['egress_trunk'];
        if (isset($_GET['code']) && $_GET['code'])
            $conditions['dnis'] = $_GET['code'];

        $this->paginate = array(
            'fields' => array('BlockTicket.ingress', 'BlockTicket.egress', 'MAX("BlockTicket"."unblock_time") as "BlockTicket__unblock_time"',
                'MAX("BlockTicket"."blocked_time") as "BlockTicket__blocked_time"',
                'BlockTicket.code_name', 'BlockTicket.rule_name', 'BlockTicket.block', 'max("BlockTicket"."start_time") as "BlockTicket__start_time"', 'max("BlockTicket"."end_time") as "BlockTicket__end_time"', 'sum("BlockTicket"."calls") as "BlockTicket__calls"', 'sum("BlockTicket"."not_zero_calls") as "BlockTicket__not_zero_calls"'),
            'limit' => 100,
            'order' => array(//'id' => 'desc',
            ),
            'group' => array('BlockTicket.code_name', 'BlockTicket.ingress', 'BlockTicket.egress', 'BlockTicket.rule_name', 'BlockTicket.block'),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('BlockTicket');
        $this->set('resources', $this->BlockAni->get_resources());
        $this->set('ingresses', $this->BlockAni->get_resource_by_type('ingress'));
        $this->set('egresses', $this->BlockAni->get_resource_by_type('egress'));
    }

    public function trouble_tickets_template()
    {
        $this->pageTitle = "Trouble Tickets/Mail Templates";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('TroubleTicketsTemplate');
    }

    public function trouble_tickets_template_create()
    {
        $this->pageTitle = "Trouble Tickets/Mail Templates";
        if ($this->RequestHandler->isPost()) {
            $name = $_POST['name'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $updated_by = $_SESSION['sst_user_name'];
            $data = array(
                'TroubleTicketsTemplate' => array(
                    'name' => $name,
                    'title' => $title,
                    'content' => $content,
                    'updated_by' => $updated_by,
                ),
            );
            $this->TroubleTicketsTemplate->save($data);
            $this->TroubleTicketsTemplate->create_json_array('', 201, __('The Email Template [%s] is created successfully!', true, $name));
            $this->Session->write('m', TroubleTicketsTemplate::set_validator());
            $this->redirect('/alerts/trouble_tickets_template');
        }
    }

    public function trouble_tickets_template_edit($id)
    {
        $this->pageTitle = "Trouble Tickets/Mail Templates";
        if ($this->RequestHandler->isPost()) {
            $name = $_POST['name'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $updated_at = date("Y-m-d H:i:sO");
            $updated_by = $_SESSION['sst_user_name'];
            $data = array(
                'TroubleTicketsTemplate' => array(
                    'name' => $name,
                    'title' => $title,
                    'content' => $content,
                    'updated_by' => $updated_by,
                    'updated_at' => $updated_at,
                    'id' => $id,
                ),
            );
            $this->TroubleTicketsTemplate->save($data);
            $this->TroubleTicketsTemplate->create_json_array('', 201, __('The Email Template [%s] is modified successfully!', true, $name));
            $this->Session->write('m', TroubleTicketsTemplate::set_validator());
            $this->redirect('/alerts/trouble_tickets_template');
        }
        $template = $this->TroubleTicketsTemplate->findById($id);
        $this->set('template', $template);
    }

    public function trouble_tickets_template_delete($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($id);
        $template = $this->TroubleTicketsTemplate->findById($id);
        $this->TroubleTicketsTemplate->create_json_array('', 201, __('The Email Template [%s] is deleted successfully!', true, $template['TroubleTicketsTemplate']['name']));
        $this->TroubleTicketsTemplate->del($id);
        $this->redirect('/alerts/trouble_tickets_template');
    }

    public function block_ani()
    {
        $this->pageTitle = "Monitoring/Block ANI";

        $conditions = array();

        if (isset($_GET['search']) && $_GET['search'] != 'Search')
            $conditions['ani ILIKE'] = '%' . $_GET['search'] . '%';

        if (isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] != 0)
            $conditions['ingress'] = $_GET['ingress_trunk'];
        if (isset($_GET['egress_trunk']) && $_GET['egress_trunk'] != 0)
            $conditions['egress'] = $_GET['egress_trunk'];

        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('BlockAni');


        foreach ($this->data as &$item) {
            $item = array_merge($item, $this->AlertRule->findById($item['BlockAni']['rule_id']));
        }


        $this->set('resources', $this->BlockAni->get_resources());
        $this->set('ingresses', $this->BlockAni->get_resource_by_type('ingress'));
        $this->set('egresses', $this->BlockAni->get_resource_by_type('egress'));
    }

    public function block_ani_delete($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = (int)$id;
        $block_ani = $this->BlockAni->findById($id);
        $condition = array();
        if (!empty($block_ani['BlockAni']['ingress'])) {
            $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
            $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
        }
        if (!empty($block_ani['BlockAni']['egress'])) {
            $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
            $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
        }
        if (!empty($block_ani['BlockAni']['ani']))
            $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];
        $this->ResourceBlock->deleteAll($condition);
        $this->BlockAni->del($id);
        $this->BlockAni->create_json_array('', 201, __('The Block ANI [%s] is deleted successfully.', true, $block_ani['BlockAni']['ani']));
        $this->Session->write('m', BlockAni::set_validator());
        $this->xredirect("/alerts/block_ani");
    }

    public function trouble_tickets_delete($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        //$id   = (int)$id;
        $block_anis = $this->BlockTicket->findAllByCodeName($id);
        $condition = array();
        foreach ($block_anis as $block_ani) {
            if (!empty($block_ani['BlockTicket']['ingress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
            }
            if (!empty($block_ani['BlockTicket']['egress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
            }
            if (!empty($block_ani['BlockTicket']['dnis']))
                $condition['digit'] = $block_ani['BlockTicket']['dnis'];
            $this->ResourceBlock->deleteAll($condition);
            $this->BlockTicket->del($block_ani['BlockTicket']['id']);
        }
        $this->BlockTicket->create_json_array('', 201, __('The Trouble Ticket Code Name [%s] is deleted successfully.', true, $block_anis));
        $this->Session->write('m', BlockTicket::set_validator());
        $this->xredirect("/alerts/trouble_tickets");
    }

    public function block_ani_delete_selected()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = $_POST['ids'];
        $type = (int)$type;
        foreach ($ids as $id) {
            $block_ani = $this->BlockAni->findById($id);
            $condition = array();
            if (!empty($block_ani['BlockAni']['ingress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
            }
            if (!empty($block_ani['BlockAni']['egress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
            }
            if (!empty($block_ani['BlockAni']['ani']))
                $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];
            $this->ResourceBlock->deleteAll($condition);
            $this->BlockAni->del($id);
        }
    }

    public function block_ani_delete_all()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        /*
          $block_anis = $this->BlockAni->find('all');
          foreach($block_anis as $block_ani)
          {
          $condition = array();
          if (!empty($block_ani['BlockAni']['ingress']))
          {
          $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
          $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
          }
          if (!empty($block_ani['BlockAni']['egress']))
          {
          $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
          $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
          }
          if (!empty($block_ani['BlockAni']['ani']))
          $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];
          $this->ResourceBlock->deleteAll($condition);
          $this->BlockAni->del($block_ani['BlockAni']['id']);
          }
         *
         */
        $this->ResourceBlock->query("DELETE FROM resource_block WHERE block_log_id IS NOT NULL");
        $this->BlockAni->query("DELETE FROM block_ani");
        $this->BlockAni->create_json_array('', 201, __('All block are deleted successfully', true));
        $this->Session->write('m', BlockAni::set_validator());
        $this->xredirect("/alerts/block_ani");
    }

    public function trouble_tickets_delete_selected()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = $_POST['ids'];
        $type = (int)$type;
        foreach ($ids as $id) {
            $block_anis = $this->BlockTicket->findAllById($id);
            $condition = array();
            foreach ($block_anis as $block_ani) {
                if (!empty($block_ani['BlockTicket']['ingress'])) {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
                }
                if (!empty($block_ani['BlockTicket']['egress'])) {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
                }
                if (!empty($block_ani['BlockTicket']['dnis']))
                    $condition['digit'] = $block_ani['BlockTicket']['dnis'];
                $this->ResourceBlock->deleteAll($condition);
                $this->BlockTicket->del($block_ani['BlockTicket']['id']);
            }
        }
    }

    public function trouble_tickets_delete_all()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $block_anis = $this->BlockTicket->find('all');
        foreach ($block_anis as $block_ani) {
            $condition = array();
            if (!empty($block_ani['BlockTicket']['ingress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
            }
            if (!empty($block_ani['BlockTicket']['egress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
            }
            if (!empty($block_ani['BlockTicket']['dnis']))
                $condition['digit'] = $block_ani['BlockTicket']['dnis'];
            $this->ResourceBlock->deleteAll($condition);
            $this->BlockTicket->del($block_ani['BlockTicket']['id']);
        }
        $this->BlockTicket->create_json_array('', 201, __('All trouble tickets are deleted successfully', true));
        $this->Session->write('m', BlockTicket::set_validator());
        $this->xredirect("/alerts/trouble_tickets");
    }

    public function block_ani_change($id, $type)
    {
        //Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = (int)$type;
        $id = (int)$id;

        $block_ani = $this->BlockAni->findById($id);
        $condition = array();
        if (!empty($block_ani['BlockAni']['ingress'])) {
            $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
            $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
        }
        if (!empty($block_ani['BlockAni']['egress'])) {
            $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
            $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
        }
        if (!empty($block_ani['BlockAni']['ani']))
            $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];

        if ($type === 1) {
            $this->ResourceBlock->deleteAll($condition);
            $block_ani['BlockAni']['block'] = false;
            $block_ani['BlockAni']['unblock_time'] = 'now()';
            $this->BlockAni->create_json_array('', 201, __('UnBlocked successfully', true));
        } else if ($type === 2) {
            $new_block = array(
                'ResourceBlock' => $condition,
            );
            $this->ResourceBlock->save($new_block);
            $block_ani['BlockAni']['block'] = true;
            $block_ani['BlockAni']['unblock_time'] = null;
            $this->BlockAni->create_json_array('', 201, __('Blocked successfully', true));
        }
        $this->BlockAni->save($block_ani);
        $this->Session->write('m', BlockAni::set_validator());
        $this->xredirect("/alerts/block_ani");
    }

    public function trouble_block_ani_change($id, $type)
    {
        //Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = (int)$type;
        //$id   = (int)$id;

        $block_anis = $this->BlockTicket->findAllByCodeName($id);
        foreach ($block_anis as $block_ani) {
            $condition = array();
            if (!empty($block_ani['BlockTicket']['ingress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
            }
            if (!empty($block_ani['BlockTicket']['egress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
            }
            if (!empty($block_ani['BlockTicket']['dnis']))
                $condition['digit'] = $block_ani['BlockTicket']['dnis'];


            if ($type === 1) {
                $this->ResourceBlock->deleteAll($condition);
                $block_ani['BlockTicket']['block'] = false;
                $block_ani['BlockTicket']['unblock_time'] = 'now()';
            } else if ($type === 2) {
                $new_block = array(
                    'ResourceBlock' => $condition,
                );
                $this->ResourceBlock->save($new_block);
                $block_ani['BlockTicket']['block'] = true;
                $block_ani['BlockTicket']['unblock_time'] = null;
            }
            $this->BlockTicket->save($block_ani);
            $this->Session->write('m', BlockTicket::set_validator());
        }
        if ($type == 1)
            $this->BlockTicket->create_json_array('', 201, __('Unblocked successfully', true));
        else
            $this->BlockTicket->create_json_array('', 201, __('Blocked successfully', true));
        $this->xredirect("/alerts/trouble_tickets");
    }

    public function block_unblock_all($type = 1)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = (int)$type;
        if ($type == 1) {
            $condition_d = array(
                'block' => true,
            );
            $this->BlockAni->create_json_array('', 201, __('Unblocked successfully', true));
            $sql = "delete from resource_block where log_id is not null";
            //$this->ResourceBlock->deleteAll(
            //    array('ResourceBlock.block_log_id' => 'IS NOT NULL')
            //);
            $this->ResourceBlock->query($sql);
            $sql = "update block_ani set block = false ,unblock_time = CURRENT_TIMESTAMP(0)";
            //$this->BlockAni->updateAll(
            //     array('BlockAni.block' => FALSE)
            //);
            $this->BlockAni->query($sql);
            $this->Session->write('m', BlockAni::set_validator());
            $this->redirect('/alerts/block_ani');
            return;
        } else {
            $condition_d = array(
                'block' => false,
            );
            $this->BlockAni->create_json_array('', 201, __('Blocked successfully', true));
        }
        $block_anis = $this->BlockAni->find('all', array('conditions' => $condition_d));
        foreach ($block_anis as $block_ani) {
            $condition = array();
            if (!empty($block_ani['BlockAni']['ingress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
            }
            if (!empty($block_ani['BlockAni']['egress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
            }
            if (!empty($block_ani['BlockAni']['ani']))
                $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];

            if ($type === 1) {
                $this->ResourceBlock->deleteAll($condition);
                $block_ani['BlockAni']['block'] = false;
            } else if ($type === 2) {
                $new_block = array(
                    'ResourceBlock' => $condition,
                );
                $this->ResourceBlock->save($new_block);
                $block_ani['BlockAni']['block'] = true;
            }
            $this->BlockAni->save($block_ani);
        }
        $this->Session->write('m', BlockAni::set_validator());
        $this->redirect('/alerts/block_ani');
    }

    public function trouble_block_unblock_all($type = 1)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = (int)$type;
        if ($type == 1) {
            $condition_d = array(
                'block' => true,
            );
            $this->BlockTicket->create_json_array('', 201, __('Unblocked successfully', true));
        } else {
            $condition_d = array(
                'block' => false,
            );
            $this->BlockTicket->create_json_array('', 201, __('Blocked successfully', true));
        }
        $block_anis = $this->BlockTicket->find('all', array('conditions' => $condition_d));
        foreach ($block_anis as $block_ani) {
            $condition = array();
            if (!empty($block_ani['BlockTicket']['ingress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
            }
            if (!empty($block_ani['BlockTicket']['egress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
            }
            if (!empty($block_ani['BlockTicket']['dnis']))
                $condition['digit'] = $block_ani['BlockTicket']['dnis'];

            if ($type === 1) {
                $this->ResourceBlock->deleteAll($condition);
                $block_ani['BlockTicket']['block'] = false;
            } else if ($type === 2) {
                $new_block = array(
                    'ResourceBlock' => $condition,
                );
                $this->ResourceBlock->save($new_block);
                $block_ani['BlockTicket']['block'] = true;
            }
            $this->BlockTicket->save($block_ani);
        }
        $this->Session->write('m', BlockTicket::set_validator());
        $this->redirect('/alerts/trouble_tickets');
    }

    public function block_unblock_selected($type = 1)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = $_POST['ids'];
        $type = (int)$type;
        foreach ($ids as $id) {
            $id = (int)$id;
            $block_ani = $this->BlockAni->findById($id);
            $condition = array();
            if (!empty($block_ani['BlockAni']['ingress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
            }
            if (!empty($block_ani['BlockAni']['egress'])) {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
            }
            if (!empty($block_ani['BlockAni']['ani']))
                $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];

            if ($type === 1) {
                if ($block_ani['BlockAni']['block'] == false)
                    continue;
                $this->ResourceBlock->deleteAll($condition);
                $block_ani['BlockAni']['block'] = false;
                $block_ani['BlockAni']['unblock_time'] = date("Y-m-d H:i:s");
                $this->BlockAni->create_json_array('', 201, __('Unblocked successfully', true));
            } else if ($type === 2) {
                if ($block_ani['BlockAni']['block'] == true)
                    continue;
                $new_block = array(
                    'ResourceBlock' => $condition,
                );
                $this->ResourceBlock->save($new_block);
                $block_ani['BlockAni']['block'] = true;
                $this->BlockAni->create_json_array('', 201, __('Blocked successfully', true));
            }
            $this->BlockAni->save($block_ani);
        }
    }

    public function trouble_block_unblock_selected($type = 1)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = $_POST['ids'];
        $type = (int)$type;
        foreach ($ids as $id) {
            $id = (int)$id;
            $block_anis = $this->BlockTicket->findAllByCodeName($id);
            $condition = array();
            foreach ($block_anis as $block_ani) {
                if (!empty($block_ani['BlockTicket']['ingress'])) {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
                }
                if (!empty($block_ani['BlockTicket']['egress'])) {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
                }
                if (!empty($block_ani['BlockTicket']['dnis']))
                    $condition['digit'] = $block_ani['BlockTicket']['dnis'];

                if ($type === 1) {
                    if ($block_ani['BlockTicket']['block'] == false)
                        continue;
                    $this->ResourceBlock->deleteAll($condition);
                    $block_ani['BlockTicket']['block'] = false;
                } else if ($type === 2) {
                    if ($block_ani['BlockTicket']['block'] == true)
                        continue;
                    $new_block = array(
                        'ResourceBlock' => $condition,
                    );
                    $this->ResourceBlock->save($new_block);
                    $block_ani['BlockTicket']['block'] = true;
                }
            }
            if ($type == 1) {
                $this->BlockTicket->create_json_array('', 201, __('Unblocked successfully', true));
            } else if ($type == 2) {
                $this->BlockTicket->create_json_array('', 201, __('Blocked successfully', true));
            }
            $this->BlockTicket->save($block_ani);
        }
    }

    private function _getResourceHostArr()
    {
        $return[0] = 'ALL';
        $sql = "select resource_ip_id as id, ip from resource_ip";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v) {
            $return[$v[0]['id']] = $v[0]['ip'];
        }
        return $return;
    }

    private function _getResourcePortArr()
    {
        $return[0] = 'All';
        $sql = "select resource_ip_id as id ,port from resource_ip";
        $port_list = $this->Action->query($sql);
        foreach ($port_list as $port_value) {
            $return[$port_value[0]['id']] = $port_value[0]['port'];
        }
        return $return;
    }

    private function _getResourceHostPortArr()
    {
        $return[0] = 'ALL';
        $sql = "select resource_ip_id as id, ip, port from resource_ip";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v) {
            $return[$v[0]['id']] = $v[0]['ip'] . ":" . $v[0]['port'];
        }
        return $return;
    }

    private function _getResourceNameArr($type = 'all')
    {
        $return = array();
        switch ($type) {
            case 'ingress':
            case 'egress':
                $sql = "select resource_id as id, alias from resource where " . addslashes(trim($type)) . " = true ORDER by alias ASC"; // and disable_by_alert = false";
                break;
            case 'all':
            default:
                $sql = "select resource_id as id, alias from resource ORDER by alias ASC";
        }
        $results = $this->Action->query($sql);
        $return[0] = 'All';
        foreach ($results as $k => $v) {
            $return[$v[0]['id']] = $v[0]['alias'];
        }
        return $return;
    }

    private function _getRouteNameArr()
    {
        $return = array();
        $sql = "select route_strategy_id as id, name from route_strategy";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v) {
            $return[$v[0]['id']] = $v[0]['name'];
        }
        return $return;
    }

    private function _getProductNameArr()
    {
        $return = array();
        $sql = "select product_id as id, name from product";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v) {
            $return[$v[0]['id']] = $v[0]['name'];
        }
        return $return;
    }

    private function _getResourceRouteInfoArr()
    {
        $sql = "select * from route";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v) {
            $return[$v[0]['route_strategy_id']] = $v[0];
        }
        return $return;
    }

    //-----------------------alert condition
    public function condition_used($condition_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "SELECT name FROM alert_rule WHERE alert_condition_id = {$condition_id}";
        $result = $this->Action->query($sql);
        $arr = array();
        foreach ($result as $item)
            array_push($arr, $item[0][name]);
        echo json_encode($arr);
    }

    public function action_used($action_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "SELECT name FROM alert_rule WHERE alert_action_id = {$action_id}";
        $result = $this->Action->query($sql);
        $arr = array();
        foreach ($result as $item)
            array_push($arr, $item[0][name]);
        echo json_encode($arr);
    }

    public function condition()
    {
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']) && strcmp('Search...', $_REQUEST['searchkey']))   //模糊查询
        {
            $this->set('searchkey', $_REQUEST['searchkey']);
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
            $search_arr['acd'] = (!empty($_REQUEST['searchkey']) && is_numeric($_REQUEST['searchkey'])) ? $_REQUEST['searchkey'] : 0;
            $search_arr['asr'] = (!empty($_REQUEST['searchkey']) && is_numeric($_REQUEST['searchkey'])) ? $_REQUEST['searchkey'] / 100 : 0;
            $search_arr['margin'] = (!empty($_REQUEST['searchkey']) && is_numeric($_REQUEST['searchkey'])) ? $_REQUEST['searchkey'] / 100 : 0;
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
            $search_arr['acd'] = (!empty($_REQUEST['acd']) && is_numeric($_REQUEST['acd'])) ? $_REQUEST['acd'] : 0;
            $search_arr['asr'] = (!empty($_REQUEST['asr']) && is_numeric($_REQUEST['asr'])) ? $_REQUEST['asr'] / 100 : 0;
            $search_arr['margin'] = (!empty($_REQUEST['margin']) && is_numeric($_REQUEST['margin'])) ? $_REQUEST['margin'] / 100 : 0;
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        $results = $this->Condition->ListCondition($currPage, $pageSize, $search_arr, $search_type);
        $this->set('p', $results);
    }

    public function add_condition($id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add/Action Condition";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_condition_impl'), array('id' => $id));
        $this->_render_condition_save_options();
        $this->render('add_condition');
        $this->Session->write('m', Condition::set_validator());
    }

    public function del_condititon($dele_id = null, $fist_id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {
            $this->redirect_denied();
        }
        //删除
        if (!empty($dele_id)) {
            $del_sql = "delete  from alert_condition where   alert_condition.id=$dele_id";
            $this->Condition->query($del_sql);
            $this->Condition->create_json_array('', 201, 'Succeeded');
        }
        $this->Session->write('m', Condition::set_validator());
        $this->referer("/alerts/condition/$fist_id");
    }

//J删除
    public function ex_dele_condititon($dele_id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {
            $this->redirect_denied();
        }
        if (!empty($dele_id)) {
            $delete_sql = "delete from alert_condition where id =$dele_id";
            $this->Condition->query($delete_sql);
            $this->Condition->create_json_array('', 201, 'Deleted successfully');
        }
        $this->xredirect("/alerts/condition");
    }

    public function delete_alert_action($alert_action_id = null)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Monitoring']['alerts:action']['model_w']) {
            $this->redirect_denied();
        }
        if (!empty($alert_action_id)) {
            $ani_sql = "SELECT id FROM block_ani where action_id =$alert_action_id";
            $ani_result = $this->Condition->query($ani_sql);
            if ($ani_result) {
                $this->Condition->create_json_array('', 101, 'The action is a block ani application');
            } else {
                $dele_sql = "delete from alert_action where id=$alert_action_id RETURNING id";
                $result = $this->Condition->query($dele_sql);
                if ($result) {
                    $this->Condition->create_json_array('', 201, 'Deleted successfully');
                } else {
                    $this->Condition->create_json_array('', 201, 'Deleted failed');
                }
            }
        }
        $this->xredirect("/alerts/action");
    }

    public function delete_alert_rule($alert_rule_id = null)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {
            $this->redirect_denied();
        }
        if (!empty($alert_rule_id)) {
            $sql = "select name from alert_rule where id = {$alert_rule_id}";
            $result = $this->Condition->query($sql);
            $sql = "delete from alert_rule where id =$alert_rule_id";
            $this->Condition->query($sql);
            $this->Condition->create_json_array('', 201, __('The Rule[%s] is deleted successfully.', false, $result[0][0]['name']));
            $this->xredirect("/alerts/rule");
        }
    }

    public function delete_alert_rules($encode_rule_id = "")
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rules']['model_w']) {
            $this->redirect_denied();
        }
        if (!empty($encode_rule_id)) {
            $id = base64_decode($encode_rule_id);
            $old_rule = $this->AlertRules->find(array('id' => $id));
            if (isset($old_rule['AlertRules']['rule_name'])) {
                switch ($old_rule['AlertRules']['execution_schedule']) {
                    case '1':
                        $every_min = (int)$old_rule['AlertRules']['specific_minutes'];
                        if ($every_min == 1) {
                            $min_time = "*";
                        } else {
                            $min_time = "*/{$every_min}";
                        }
                        $crontab_time_remove = "{$min_time} * * * * ";
                        break;
                    case '2':
                        $hour = (int)$old_rule['AlertRules']['daily_time'];
                        $crontab_time_remove = "0 {$hour} * * * ";
                        break;
                    case '3':
                        $hour = (int)$old_rule['AlertRules']['weekly_time'];
                        $week = (int)$old_rule['AlertRules']['weekly_value'];
                        $crontab_time_remove = "0 {$hour} * * {$week} ";
                        break;
                    default :
                        $crontab_time_remove = "";
                }
                if ($crontab_time_remove) {
                    App::import("Vendor", "crontab", array('file' => "crontab.php"));
                    $crontab = new Crontab();
                    $php_path = Configure::read('php_exe_path');
                    $cmd = "{$php_path} " . APP . "../cake/console/cake.php alert_rule {$id}";
                    $crontab->remove_cronjob($crontab_time_remove, $cmd);
                }
                $sql = "delete from alert_rules where id =$id";
                $flg = $this->Condition->query($sql);
                if ($flg === false) {
                    $this->Condition->create_json_array('', 101, __('failed', true));
                } else {
                    $this->Condition->create_json_array('', 201, sprintf(__("The Rule[%s] is deleted successfully.", true), $old_rule['AlertRules']['rule_name']));
                }
            } else {
                $this->Condition->create_json_array('', 101, __('failed', true));
            }
            $this->xredirect("/alerts/rules");
        }

        $ruleNames = array();
        $rulesList = $this->AlertRules->rules_list(1000, 0);
        foreach ($rulesList as $rl) {
            // if ($rl[0]['active']) { .//uncomment for the case if we want to show only active rules
            $ruleNames[] = $rl[0];
            // }
        }
        $this->set('ruleNames', $ruleNames);
    }

    function edit_condititon($id = null)
    {
        //EDIT
        if (!$_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {
            $this->redirect_denied();
        }
    }

    function _add_condition_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost()) {

            //var_dump($this->params['form']);exit;
            $this->_create_or_update_condition_data($this->params['form']);
        } #get
        else {
            if (isset($params['id']) && !empty($params['id'])) {
                //$this->data = $this->Condition->query("select * from alert_condition where id = {$params['id']}");
                $this->data = $this->Condition->find("first", Array('conditions' => array('Condition.id' => $params['id'])));
                if (empty($this->data)) {
                    throw new Exception("Permission denied");
                } else {
                    $this->set('p', $this->data['Condition']);
                }
            } else {

            }
        }
    }

    function _create_or_update_condition_data($params = array())
    {   #update
        if (isset($params['condition_id']) && !empty($params['condition_id'])) {
            $id = (int)$params ['condition_id'];
//							if(!$this->check_form($id)){
//								return;
//							}
            $this->data['Condition'] = $this->data['Alert'];
            $this->data ['Condition'] ['id'] = $id;
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            if ($this->data['Condition']['acd_comparator'] == 1) {
                $this->data['Condition']['acd_value_min'] = $params['acd_min_1'];
                $this->data['Condition']['acd_value_max'] = $params['acd_max_1'];
            } else {
                $this->data['Condition']['acd_value_min'] = $params['acd_min_0'];
            }
            if ($this->data['Condition']['asr_comparator'] == 1) {
                $this->data['Condition']['asr_value_min'] = $params['asr_min_1'] / 100;
                $this->data['Condition']['asr_value_max'] = $params['asr_max_1'] / 100;
            } else {
                $this->data['Condition']['asr_value_min'] = $params['asr_min_0'] / 100;
            }
            if ($this->data['Condition']['margin_comparator'] == 1) {
                $this->data['Condition']['margin_value_min'] = $params['margin_min_1'] / 100;
                $this->data['Condition']['margin_value_max'] = $params['margin_max_1'] / 100;
            } else {
                $this->data['Condition']['margin_value_min'] = $params['margin_min_0'] / 100;
            }

            if ($this->data['Condition']['abr_comparator'] == 1) {
                $this->data['Condition']['abr_value_min'] = $params['abr_min_1'] / 100;
                $this->data['Condition']['abr_value_max'] = $params['abr_max_1'] / 100;
            } else {
                $this->data['Condition']['abr_value_min'] = $params['abr_min_0'] / 100;
            }

            if ($this->data['Condition']['special_ani_comparator'] == 1) {
                $this->data['Condition']['special_ani_value'] = $params['special_ani_value_1'];
            } else {
                $this->data['Condition']['special_ani_value'] = $params['special_ani_value_0'];
            }
            $this->data['Condition']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Condition']['update_at'] = date('Y-m-d H:i:sO');
            if ($this->Condition->save($this->data)) {
                //$this->Condition->create_json_array('',201,'Condition,Edit successfully');
                $this->Condition->create_json_array('', 201, __('The Condition [%s] is modified successfully.', true, $this->data['Condition']['name']));
                $this->Session->write('m', Condition::set_validator());

                $this->xredirect("/alerts/condition");
                //$this->redirect ( array ('id' => $id ) );
            }
        } # add
        else {
//						if(!$this->check_form('')){
//								return;
//							}
            $this->data['Condition'] = $this->data['Alert'];
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            if ($this->data['Condition']['acd_comparator'] == 1) {
                $this->data['Condition']['acd_value_min'] = $params['acd_min_1'];
                $this->data['Condition']['acd_value_max'] = $params['acd_max_1'];
            } else {
                $this->data['Condition']['acd_value_min'] = $params['acd_min_0'];
            }
            if ($this->data['Condition']['asr_comparator'] == 1) {
                $this->data['Condition']['asr_value_min'] = $params['asr_min_1'] / 100;
                $this->data['Condition']['asr_value_max'] = $params['asr_max_1'] / 100;
            } else {
                $this->data['Condition']['asr_value_min'] = $params['asr_min_0'] / 100;
            }
            if ($this->data['Condition']['margin_comparator'] == 1) {
                $this->data['Condition']['margin_value_min'] = $params['margin_min_1'] / 100;
                $this->data['Condition']['margin_value_max'] = $params['margin_max_1'] / 100;
            } else {
                $this->data['Condition']['margin_value_min'] = $params['margin_min_0'] / 100;
            }
            $this->data['Condition']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Condition']['update_at'] = date('Y-m-d H:i:sO');
            if ($this->Condition->save($this->data)) {
                $id = $this->Condition->getlastinsertId();
                if (isset($_GET['flag'])) {
                    $this->xredirect("/alerts/add_rule");
                }
                $this->Condition->create_json_array('', 201, 'Condition，
create successfully');
                $this->Session->write('m', Condition::set_validator());
                $this->xredirect('/alerts/condition');
                //		$this->redirect ( array ('id' => $id ) );
            }
        }
    }

    function _render_condition_save_options()
    {
        $this->loadModel('Condition');
        $this->set('ConditionList', $this->Condition->find('all')); //,Array('fields'=>Array('id','name'))));
    }

//--------------------------------alert condition end
//--------------------------------alert action start
    public function action()
    {
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']) && strcmp('Keyword', $_REQUEST['searchkey']))   //模糊查询
        {
            $this->set('searchkey', $_REQUEST['searchkey']);
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = $this->Action->ListAction($currPage, $pageSize, $search_arr, $search_type);
        $this->set('p', $results);

//        echo "<pre>";
//                print_r($results);
        $send_mail_type = array(
            'None', 'System\'s NOC', 'Partner\'s NOC', 'Both NOC'
        );

        $disable_route_target = array(
            'None', 'Entire Trunk', 'Entire Host'
        );

        $change_prioprity = array(
            'None', 'Trunk', "Host"
        );
        $templates = $this->TroubleTicketsTemplate->find('list');
        $templates = array(null => '') + $templates;
        $this->set('templates', $templates);

        $this->set('send_mail_type', $send_mail_type);
        $this->set('disable_route_target', $disable_route_target);
        $this->set('change_prioprity', $change_prioprity);
    }

    public function add_action($id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:action']['model_w']) {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add/Edit Action";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_action_impl'), array('id' => $id));
        $this->_render_action_save_options();
        $this->render('add_action');
        $this->Session->write('m', Action::set_validator());
    }

    function _add_action_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost()) {
            $this->_create_or_update_action_data($this->params['form']);
        } #get
        else {
            if (isset($params['id']) && !empty($params['id'])) {
                $this->data = $this->Action->find("first", Array('conditions' => array('Action.id' => $params['id'])));
                if (empty($this->data)) {
                    throw new Exception("Permission denied");
                } else {
                    $this->set('p', $this->data['Action']); //pr($this->data['Action']);
                }
            } else {

            }
        }
    }

    function _create_or_update_action_data($params = array())
    {   #update
        if (isset($params['action_id']) && !empty($params['action_id'])) {

            $id = (int)$params ['action_id'];
//							if(!$this->check_form($id)){
//								return;
//							}
            $this->data['Action'] = $this->data['Alert'];
            $this->data ['Action'] ['id'] = $id;
            $this->data['Alert'] = null;
            unset($this->data['Alert']);

            if (!preg_match("/^[0-9]+$/", $this->data['Action']['disable_duration'])) {
                $this->Action->create_json_array('#AlertDisableDuration', 101, 'disable duration must be integer.');
                $this->Session->write('m', Action::set_validator());
                $this->redirect(array('id' => $id));
            }
            if (!preg_match("/^[0-9]+$/", $this->data['Action']['pri_chg_duration'])) {
                $this->Action->create_json_array('#AlertPriChgDuration', 101, 'pri_chg duration must be integer.');
                $this->Session->write('m', Action::set_validator());
                $this->redirect(array('id' => $id));
            }
            if (!preg_match("/^[0-9]+$/", $this->data['Action']['code_trunk_disable_duration'])) {
                $this->Action->create_json_array('#AlertCodeTrunkDisableDuration', 101, 'Code Trunk Disable Duration must be integer.');
                $this->Session->write('m', Action::set_validator());
                $this->redirect(array('id' => $id));
            }
            if ($this->data['Action']['email_notification'] == 0) {
                $this->data['Action']['email_to_noc'] = TRUE;
                $this->data['Action']['email_to_carrier'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 1) {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 2) {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = TRUE;
            }
            $this->data['Action']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Action']['update_at'] = date("Y-m-d H:i:sO");
            if ($this->Action->save($this->data)) {
                //$this->Action->create_json_array('',201,'Action,Edit successfully');
                $this->Action->create_json_array('', 201, __('The Condition [%s] is modified successfully.', false, $this->data['Action']['name']));
                $this->Session->write('m', Action::set_validator());
                $this->xredirect('/alerts/action');
                //	$this->redirect ( array ('id' => $id ) );
            }
        } # add
        else {
//						if(!$this->check_form('')){
//								return;
//							}
            $this->data['Action'] = $this->data['Alert'];
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            if ($this->data['Action']['email_notification'] == 0) {
                $this->data['Action']['email_to_noc'] = TRUE;
                $this->data['Action']['email_to_carrier'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 1) {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 2) {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = TRUE;
            }
            $this->data['Action']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Action']['update_at'] = date("Y-m-d H:i:sO");
            if ($this->Action->save($this->data)) {
                $id = $this->Action->getlastinsertId();
                if (isset($_GET['flag']))
                    $this->xredirect('/alerts/add_rule');
                $this->Action->create_json_array('', 201, 'The ' . $this->data['Action']['name'] . ' is added successfully.');
                $this->Session->write('m', Action::set_validator());

                $this->xredirect('/alerts/action');
                //$this->redirect ( array ('id' => $id ) );
            }
        }
    }

    function _render_action_save_options()
    {
        $this->loadModel('Action');
        $this->set('ActionList', $this->Condition->find('all')); //,Array('fields'=>Array('id','name'))));
    }

    //-------------------------------alert action end
    //--------------------------------alert rule start
    public function rule()
    {
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }
//
//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = $this->AlertRule->ListRule($currPage, $pageSize, $search_arr, $search_type);
        $this->set('p', $results);
        $name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['host'] = $this->_getResourceHostArr();
        $name_join_arr['port'] = $this->_getResourcePortArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function add_rule($id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {
            $this->redirect_denied();
        }
        //print_r($_POST);exit;
        $this->pageTitle = "Add/Edit Rule";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_rule_impl'), array('id' => $id));
        $this->_render_rule_save_options();
        $this->render('add_rule');
        $this->Session->write('m', AlertRule::set_validator());
    }

    public function invalid_number()
    {
        $currPage = 1;
        $pageSize = 10;

        if ($this->isnotEmpty($this->params['url'], array('page'))) {
            $currPage = $this->params['url']['page'];
        }
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->AlertRules->rules_count_invalid_number();
        if (empty($totalrecords)) {
            $msg = "Invalid Number Detection Rule";
            $add_url = "add_invalid_number";
            $model_name = "AlertRules";
//            $this->to_add_page($model_name,$msg,$add_url);
        }

        $page->setTotalRecords($totalrecords); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data_arr = $this->AlertRules->rules_list_invalid_number($pageSize, $offset);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

//    public function rules()
//    {
//        $currPage = 1;
//        $pageSize = 10;
//
//        if ($this->isnotEmpty($this->params['url'], array('page')))
//        {
//            $currPage = $this->params['url']['page'];
//        }
//        require_once MODELS . DS . 'MyPage.php';
//        $page = new MyPage();
//        $totalrecords = $this->AlertRules->rules_count();
//        if (empty($totalrecords))
//        {
//            $msg = "Rule";
//            $add_url = "add_rules";
//            $model_name = "AlertRules";
//            $this->to_add_page($model_name,$msg,$add_url);
//        }
//        $page->setTotalRecords($totalrecords); //总记录数
//        $page->setCurrPage($currPage); //当前页
//        $page->setPageSize($pageSize); //页大小
//        //$page = $page->checkRange($page);//检查当前页范围
//
//        $currPage = $page->getCurrPage() - 1;
//        $pageSize = $page->getPageSize();
//        $offset = $currPage * $pageSize;
//        $data_arr = $this->AlertRules->rules_list($pageSize, $offset);
////        pr($data_arr);die;
//        foreach ($data_arr as &$data_item)
//        {
//            $data_item[0]['next_rule_time'] = '--';
//            $data_item[0]['run_info'] = '--';
////            if (!$data_item[0]['active'])
////                continue;
//            switch ($data_item[0]['execution_schedule'])
//            {
//                case 1 :
////                    run every specific minutes
//                    $specific_minutes = $data_item[0]['specific_minutes'];
//                    $data_item[0]['run_info'] = __('Run every %s minute',true,array($specific_minutes));
//                    $last_rule_time = $data_item[0]['last_run_time'];
//                    if (!$last_rule_time)
//                        $data_item[0]['next_rule_time'] = date('Y-m-d H:i:00+00',strtotime($specific_minutes.' minute'));
//                    else
//                        $data_item[0]['next_rule_time'] = date('Y-m-d H:i:00+00',strtotime($last_rule_time.$specific_minutes.' minute'));
//                    break;
//                case 2 :
////                    run every day
//                    $show_time = str_pad($data_item[0]['daily_time'],2,0,STR_PAD_LEFT).":00";
//                    $data_item[0]['run_info'] = __('Run on %s of every day',true,array($show_time));
//                    $today_hour = date('H');
//                    if (intval($data_item[0]['daily_time']) <= $today_hour)
//                        $data_item[0]['next_rule_time'] = date('Y-m-d H:00:00+00',strtotime(date('Y-m-d '.intval($data_item[0]['daily_time']).':00:00', strtotime("+1 day"))));
////                        $data_item[0]['next_rule_time'] = date('Y-m-d 00:00:00+00',strtotime('+1 day '.intval($data_item[0]['daily_time']).' hour'));
//                    else
//                        $data_item[0]['next_rule_time'] = date('Y-m-d H:00:00+00',strtotime(date('Y-m-d '.intval($data_item[0]['daily_time']).':00:00')));
//                    break;
//                case 3 :
////                    run every week
//                    $today_week = date("w");
//                    $today_hour = date('H');
//                    $show_time = str_pad($data_item[0]['weekly_time'],2,0,STR_PAD_LEFT).":00";
//                    $week_day_arr = array(
//                        __('Sunday',true),__('Monday',true),__('Tuesday',true),__('Wednesday',true),__('Thursday',true),__('Friday',true),__('Saturday',true),
//                    );
//                    $data_item[0]['run_info'] = __('Run on %s of every %s',true,array($show_time,$week_day_arr[intval($data_item[0]['weekly_value'])]));
//
////                    die(var_dump($today_week, intval($data_item[0]['weekly_value'])));
//
//                    if ($today_week < intval($data_item[0]['weekly_value']))
//                    {
//                        $add_days = $data_item[0]['weekly_value'] - $today_week;
//                        $next_day = date('Y-m-d 00:00:00',strtotime($add_days.' day'));
//                        $data_item[0]['next_rule_time'] = date('Y-m-d H:00:00+00',strtotime($next_day.intval($data_item[0]['weekly_time']).' hour'));
//                    }
//                    else if ($today_week > intval($data_item[0]['weekly_value']))
//                    {
//                        $add_days = 7 - $today_week + $data_item[0]['weekly_value'];
//                        $next_day = date('Y-m-d 00:00:00',strtotime($add_days.' day'));
//                        $data_item[0]['next_rule_time'] = date('Y-m-d H:00:00+00',strtotime($next_day.intval($data_item[0]['weekly_time']).' hour'));
//                    }
//                    else
//                    {
//                        if (intval($data_item[0]['weekly_time']) <= $today_hour)
//                            $data_item[0]['next_rule_time'] = date('Y-m-d H:00:00+00',strtotime('+7 day '.intval($data_item[0]['weekly_time']).' hour'));
//                        else
//                            $data_item[0]['next_rule_time'] = date('Y-m-d ' . intval($data_item[0]['weekly_time']) . ':00:00+00');
//                    }
//                    break;
//                default :
//                    $data_item[0]['next_rule_time'] = '--';
//                    $data_item[0]['run_info'] = __('Never',true);
//            }
//        }
//        $page->setDataArray($data_arr);
//        $this->set('p', $page);
//    }

    public function rules()
    {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->AlertRules->rules_count(true);
        if (empty($totalrecords)) {
            $msg = "Rule";
            $add_url = "add_rules";
            $model_name = "AlertRules";
            $this->to_add_page($model_name, $msg, $add_url);
        }
        $page->setTotalRecords($totalrecords); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;


        $data_arr = $this->AlertRules->rules_list($pageSize, $offset, true);
//        pr($data_arr);die;
        foreach ($data_arr as &$data_item) {
            $data_item[0]['run_info'] = '--';
//            if (!$data_item[0]['active'])
//                continue;
            switch ($data_item[0]['execution_schedule']) {
                case 1 :
//                    run every specific minutes
                    $specific_minutes = $data_item[0]['specific_minutes'];
                    $data_item[0]['run_info'] = __('Run every %s minute', true, array($specific_minutes));
                    break;
                case 2 :
//                    run every day
                    $show_time = str_pad($data_item[0]['daily_time'], 2, 0, STR_PAD_LEFT) . ":00";
                    $data_item[0]['run_info'] = __('Run on %s of every day', true, array($show_time));
                    break;
                case 3 :
//                    run every week
                    $show_time = str_pad($data_item[0]['weekly_time'], 2, 0, STR_PAD_LEFT) . ":00";
                    $week_day_arr = array(
                        __('Sunday', true), __('Monday', true), __('Tuesday', true), __('Wednesday', true), __('Thursday', true), __('Friday', true), __('Saturday', true),
                    );
                    $data_item[0]['run_info'] = __('Run on %s of every %s', true, array($show_time, $week_day_arr[intval($data_item[0]['weekly_value'])]));
                    break;
                default :
                    $data_item[0]['next_rule_time'] = '--';
                    $data_item[0]['run_info'] = __('Never', true);
            }
        }
        $page->setDataArray($data_arr);
        $this->set('p', $page);

        $ruleNames = array();
        $rulesList = $this->AlertRules->rules_list(1000, 0);
        foreach ($rulesList as $rl) {
            // if ($rl[0]['active']) { .//uncomment for the case if we want to show only active rules
            $ruleNames[] = $rl[0];
            // }
        }
        $this->set('ruleNames', $ruleNames);
    }

    function _test1()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        //App::import('Vendor', 'nmail/phpmailer');


        $ch = curl_init();

        $data = array('name' => 'Foo', 'file' => '@/opt/sdfsdf.pcap');

        curl_setopt($ch, CURLOPT_URL, 'http://108.165.2.42/api/v1/76d0215911d46d6690e55d5fcfe454dd/upload');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $res = curl_exec($ch);

        curl_close($ch);

//var_dump($res);

        exit;


        $rule_id = 2;
        if (!$rule_id) {
            return false;
        }
        $rule_info_item = $this->InvalidNumber->find(array('id' => $rule_id));

        $now_time = date("Y-m-d H:i:s");
        $execution_schedule = $rule_info_item['InvalidNumber']['execution_schedule'];

        if (!$execution_schedule) {
            return false;
        }
        $time = $rule_info_item['InvalidNumber']['sample_size'] * 60;
        $start_time = date("Y-m-d H:i:s", strtotime($now_time) - $time * 2);
        $end_time = date("Y-m-d H:i:s", strtotime($now_time) - $time);
//        $start_time = '2014-10-16 08:31:01';
//        $end_time = '2014-10-16 08:32:01';
        //$trunk_type = $rule_info_item['InvalidNumber']['trunk_type'];
        $ingress_id = $rule_info_item['InvalidNumber']['ingress_id'];

        $error_msg = array(
            1 => '404',
            2 => '480',
            3 => '200',
            4 => '400'
        );

        $cause_code_criteria = key_exists($rule_info_item['InvalidNumber']['cause_code_criteria'], $error_msg) ? $error_msg[$rule_info_item['InvalidNumber']['cause_code_criteria']] : $error_msg[1];

        $error_flg = "";
        //$all_trunk = $rule_info_item['InvalidNumber']['all_trunk'];
        //$where_time = "WHERE time > '{$start_time}' AND time <=  '{$end_time}' ";
        $where_time = "WHERE time  between  '{$start_time}'   and  '{$end_time}'   ";
        $where_time = " where time  between  '2014-10-20 10:30:52'   and  '2014-10-27 10:52:52'  ";
        $group = "GROUP BY ingress_id,routing_digits";
        $group_field = "ingress_id,count(*) as count,routing_digits";
        $where_trunk = " AND ingress_id in ($ingress_id) and binary_value_of_release_cause_from_protocol_stack ilike '{$cause_code_criteria}%' ";


        $sql_2 = "SELECT {$group_field} FROM client_cdr  {$where_time}  {$where_trunk} "
            . "{$group}";
        file_put_contents('/tmp/invalid_number.log', "\r\n\r\n" . date('Y-m-d H:i:s') . " Start Script \r\n" . $sql_2 . "\r\n", FILE_APPEND);


        $first_data = $this->InvalidNumber->query($sql_2);


        $block_by = $rule_info_item['InvalidNumber']['rule_name'];
        $create_time = date("Y-m-d H:i:s");
        $flag = false;
        if (!empty($first_data)) {
            $second_data = array();
            $block_log_id = 0;
            foreach ($first_data as $first_data_item) {
                $first_data_item = $first_data_item[0];
                if ($first_data_item['count'] < $rule_info_item['InvalidNumber']['threshold']) {
                    continue;
                }

                $res = $this->InvalidNumber->query("select count(*) from resource_block where ingress_res_id = '{$first_data_item['ingress_id']}' and digit = '{$first_data_item['routing_digits']}' ");
                var_dump($res);
                if ($res[0][0]['count'] != 0) {
                    continue;
                }

                $flag = true;
                if (empty($block_log_id)) {
                    $log_sql = "INSERT INTO block_log (block_by,type) "
                        . "VALUES('{$block_by}',1) "
                        . "RETURNING log_id";
                    $block_log_arr = $this->InvalidNumber->query($log_sql, false);
                    $fle = print_r($block_log_arr, true);
                    file_put_contents('/tmp/invalid_number.log', $fle . "\r\n\r\n", FILE_APPEND);
                    $block_log_id = intval($block_log_arr[0][0]['log_id']);
                }
                $sql = "INSERT INTO resource_block (ingress_res_id,digit,create_time,block_log_id) VALUES('{$first_data_item['ingress_id']}','{$first_data_item['routing_digits']}','{$create_time}',$block_log_id);";
                $this->InvalidNumber->query($sql);
            }
        }

        if (!$flag) {
            file_put_contents('/tmp/invalid_number.log', date('Y-m-d H:i:s') . " End Script   \r\n", FILE_APPEND);
        } else {
            file_put_contents('/tmp/invalid_number.log', date('Y-m-d H:i:s') . " End Script block_log_id = $block_log_id  \r\n", FILE_APPEND);
        }
    }

    function unblock($id)
    {
        //Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $this->InvalidNumber->query("delete from resource_block where block_log_id = {$id}  ");
        $this->InvalidNumber->query("delete from block_log where log_id = {$id}  ");
        $this->AlertRules->create_json_array('', 201, 'Unblock successfully.');
        $this->xredirect('/alerts/block_log_invalid_number');
    }

    function get_result()
    {
        Configure::write('debug', 0);
        $call_id = $this->_post('log_id');

        $res = $this->InvalidNumber->query("select ingress_res_id,(select alias from resource where resource_id = ingress_res_id ) as alias  from resource_block where block_log_id = {$call_id} group by ingress_res_id    ");
        $result = array();
        if (count($res) != 0) {
            $result = $res[0];
        }
        $this->set('results', $result);
        //echo json_encode($result);
    }

    public function add_invalid_number($encode_id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:invalid_number']['model_w'])
            $this->redirect_denied();
        $id = '';
        if ($encode_id)
            $id = base64_decode($encode_id);
        if ($this->RequestHandler->isPost()) {
            $save_data = $this->data;
            if ($save_data['ani_check_all'])
                $save_data['ani_ingress'] = '';
            else
                $save_data['ani_ingress'] = implode(',', $save_data['ani_ingress']);

            if ($save_data['dnis_check_all'])
                $save_data['dnis_ingress'] = '';
            else
                $save_data['dnis_ingress'] = implode(',', $save_data['dnis_ingress']);

            $save_data['ani_return_codes'] = implode(',', $save_data['ani_return_codes']);
            $save_data['dnis_return_codes'] = implode(',', $save_data['dnis_return_codes']);

            $save_data['update_by'] = $_SESSION['sst_user_name'];
            $save_data['update_at'] = date("Y-m-d H:i:sO");
            if ($id)
                $save_data['id'] = $id;
            if ($this->InvalidNumber->save($save_data)) {
                if ($id)
                    $this->AlertRules->create_json_array('', 201, __('The Rule [%s] is modified successfully.', true, $save_data['rule_name']));
                else
                    $this->AlertRules->create_json_array('', 201, __('The Rule [%s] is added successfully.', true, $save_data['rule_name']));
            } else {
                if ($id)
                    $this->AlertRules->create_json_array('', 101, __('The Rule [%s] is modified failed.', true, $save_data['rule_name']));
                else
                    $this->AlertRules->create_json_array('', 101, __('The Rule [%s] is added failed.', true, $save_data['rule_name']));
            }
            $this->xredirect('/alerts/invalid_number');
        }
        if ($id) {
            $rule_info = $this->InvalidNumber->find(array('id' => $id));
            if (!$rule_info) {
                $this->AlertRule->create_json_array('', 101, 'failed.');
                $this->xredirect('/alerts/invalid_number');
            }
            $this->data = $rule_info['InvalidNumber'];
            $this->data['ani_ingress'] = explode(',', $rule_info['InvalidNumber']['ani_ingress']);
            $this->data['dnis_ingress'] = explode(',', $rule_info['InvalidNumber']['dnis_ingress']);
            $this->data['ani_return_codes'] = explode(',', $rule_info['InvalidNumber']['ani_return_codes']);
            $this->data['dnis_return_codes'] = explode(',', $rule_info['InvalidNumber']['dnis_return_codes']);
        }
        $ingress_trunk = $this->InvalidNumber->get_client_ingress_group();
        $this->set('ingress_trunk', $ingress_trunk);
        $this->set('check_cycle', $this->InvalidNumber->check_cycle);
        $this->set('return_codes', $this->InvalidNumber->return_codes);
        $this->set('mailTemplateTags', $this->InvalidNumber->mailTemplateTags);
    }

    function _remove_crontab($ids)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $old_rule = $this->InvalidNumber->find(array('id' => $id));

            switch ($old_rule['InvalidNumber']['execution_schedule']) {
                case '1':
                    $every_min = (int)$old_rule['InvalidNumber']['specific_minutes'];
                    if ($every_min == 1) {
                        $min_time = "*";
                    } else {
                        $min_time = "*/{$every_min}";
                    }
                    $crontab_time_remove = "{$min_time} * * * * ";
                    break;
                case '2':
                    $hour = (int)$old_rule['InvalidNumber']['daily_time'];
                    $crontab_time_remove = "0 {$hour} * * * ";
                    break;
                case '3':
                    $hour = (int)$old_rule['InvalidNumber']['weekly_time'];
                    $week = (int)$old_rule['InvalidNumber']['weekly_value'];
                    $crontab_time_remove = "0 {$hour} * * {$week} ";
                    break;
                default :
                    $crontab_time_remove = "";
            }
            if ($crontab_time_remove) {
                App::import("Vendor", "crontab", array('file' => "crontab.php"));
                $crontab = new Crontab();
                $php_path = Configure::read('php_exe_path');
                $cmd = "{$php_path} " . APP . "../cake/console/cake.php alert_invalid_number {$id}";
                $crontab->remove_cronjob($crontab_time_remove, $cmd);
            }
        }
    }

    public function add_rules($id = null)
    {
//        $tmpData = $this->AlertRules->find('first');
//        die(var_dump($tmpData));
        //pr($this->params);
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rules']['model_w']) {
            $this->redirect_denied();
        }
        $post_data = array(
            'id' => '',
            'rule_name' => '',
            'in_type' => 0,
            'ex_type' => 1,
            'in_codes' => '',
            'ex_codes' => '',
            'in_code_deck' => '',
            'ex_code_deck' => '',
            'in_code_name' => array(),
            'ex_code_name' => array(),
            'trunk_type' => 1,
            'ingress_trunk' => array(),
            'egress_trunk' => array(),
            'all_trunk' => '',
            'monitor_by' => 0,
            'acd' => 1,
            'asr' => 1,
            'abr' => 1,
            'pdd' => 1,
            'profitability' => 1,
            'revenue' => 1,
            'acd_value' => '',
            'asr_value' => '',
            'abr_value' => '',
            'pdd_value' => '',
            'profitability_value' => '',
            'revenue_value' => '',
            'min_call_attempt' => '',
            'step3_type' => 1,
            'trouble_ticket_sent_to' => '',
            'trouble_ticket_sent_from' => '',
            'trouble_ticket_subject' => '',
            'trouble_ticket_content' => '',
            'auto_enable_type' => 1,
            'auto_enable' => '',
            'execution_schedule' => '',
            'specific_minutes' => '',
            'sample_size' => '',
            'daily_time' => '',
            'weekly_time' => '',
            'weekly_value' => '',
            'active' => true,
            'is_block' => false,
            'is_email' => false,
            'sdp_value' => '',
            'sdp_sign' => '',
            'sdp_type' => ''
        );
        if ($id) {
            $id = base64_decode($id);
            $rule_info = $this->AlertRules->find(array('id' => $id));
            if (!$rule_info) {
                $this->AlertRule->create_json_array('', 101, 'failed.');
                $this->xredirect('/alerts/rules');
            }
            foreach ($rule_info['AlertRules'] as $key => $value) {
                $post_data[$key] = $value;
            }
            if ($post_data['trunk_type'] == 1) {
                $post_data['ingress_trunk'] = explode(',', $post_data['res_id']);
            } else {
                $post_data['egress_trunk'] = explode(',', $post_data['res_id']);
            }
            $post_data['in_code_name'] = explode(',', $post_data['in_code_name_id']);
            $post_data['ex_code_name'] = explode(',', $post_data['ex_code_name_id']);
            $post_data['id'] = $id;
            $exCodeDeck = $post_data['ex_code_deck'];
            if (!empty($exCodeDeck)) {
                $code_sql = "SELECT code_id,name FROM code WHERE code_deck_id = $exCodeDeck order by name ASC ";
                $code_result = $this->BlockLog->query($code_sql);
                $this->set('ex_code_arr', $code_result);
            }
        }

//        step 3 init
        $post_data['disable_scope'] = 1;
        $mail_sender_sql = "SELECT id,email FROM mail_sender";
        $mail_sender_result = $this->AlertRules->query($mail_sender_sql);
        $this->set('mail_senders', $mail_sender_result);

//        step 4 init
        $post_data['step4_type'] = 0;

        $code_arr = array();
        $this->set('code_arr', $code_arr);
        $this->set('post_data', $post_data);
        $code_deck_sql = "SELECT code_deck_id,name FROM code_deck";
        $code_deck_result = $this->AlertRules->query($code_deck_sql);
        $this->set('code_deck', $code_deck_result);
        $egress_trunk = $this->AlertRules->findAll_egress(false, ' and resource.active = true ');
        $this->set('egress_trunk', $egress_trunk);
        $ingress_trunk = $this->AlertRules->findAll_ingress(false, ' and resource.active = true ');
        $this->set('ingress_trunk', $ingress_trunk);
        //print_r($_POST);exit;

        if (isset($this->params['form']['AlertRules'])) {
            $save_data = $this->params['form']['AlertRules'];
            $save_data['active'] = isset($save_data['active']) && $save_data['active'] ? true : false;
            $save_data['is_block'] = isset($save_data['is_block']) && $save_data['is_block'] ? true : false;
            $save_data['is_email'] = isset($save_data['is_email']) && $save_data['is_email'] ? true : false;
            $save_data['all_trunk'] = isset($save_data['all_trunk']) ? true : false;

            if ($save_data['all_trunk']) {
                $save_data['res_id'] = null;
            } else {
                if (isset($save_data['ingress_trunk']) || isset($save_data['egress_trunk'])) {
                    switch ($save_data['trunk_type']) {
                        case 1:
                            $save_data['res_id'] = implode(',', $save_data['ingress_trunk']);
                            break;
                        case 2:

                            $save_data['res_id'] = implode(',', $save_data['egress_trunk']);
                            break;
                    }
                }
            }
            unset($save_data['ingress_trunk']);
            unset($save_data['egress_trunk']);
            if ($save_data['include'] == 2) {
                $save_data['in_code_name_id'] = implode(',', $save_data['in_code_name']);
                unset($save_data['in_code_name']);
            }
            if ($save_data['exclude'] == 2) {
                $save_data['ex_code_name_id'] = implode(',', $save_data['ex_code_name']);
                unset($save_data['ex_code_name']);
            }
            $save_data['update_by'] = $_SESSION['sst_user_name'];
            $save_data['update_at'] = date("Y-m-d H:i:sO");
            if ($id) {
                $save_data['id'] = $id;
                $old_rule = $this->AlertRules->find(array('id' => $id));
            }
//            echo '<pre>';
//            $tmpRes = $this->AlertRules->query("SELECT * FROM alert_rules ORDER BY id desc limit 1");
//            die(var_dump($tmpRes)); 
            if ($save_data['monitor_by'] == 3) {
                $save_data['trunk_type'] = 3;
//                $save_data['monitor_by'] = 1;
            } else if ($save_data['monitor_by'] == 4) {
                $save_data['trunk_type'] = 3;
//                $save_data['monitor_by'] = 2;
            }

            if ($this->AlertRules->save($save_data)) {
                if ($id)
                    $this->AlertRules->create_json_array('', 201, __('The Rule [%s] is modified successfully.', true, $save_data['rule_name']));
                else
                    $this->AlertRules->create_json_array('', 201, __('The Rule [%s] is created successfully!', true, $save_data['rule_name']));
            } else {
                if ($id)
                    $this->AlertRules->create_json_array('', 101, __('The Rule [%s] is modified failed.', true, $save_data['rule_name']));
                else
                    $this->AlertRules->create_json_array('', 101, __('The Rule [%s] is add failed.', true, $save_data['rule_name']));
            }
            $this->xredirect('/alerts/rules');
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
        $count = $this->AlertRules->check_rule_name($rule_name, $rule_id);
        if ($count)
            return json_encode(array('status' => 0, 'msg' => __('The name[%s] has already been taken', true, array($rule_name))));
        else
            return json_encode(array('status' => 1, 'msg' => ''));
    }

    public function ajax_check_rule_name1()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rule_name = $this->params['form']['rule_name'];
        $rule_id = $this->params['form']['rule_id'];
        if (!$rule_name) {
            echo "Rule name cannot be empty";
            return;
        }
        $count = $this->AlertRules->check_rule_name1($rule_name, $rule_id);
        if ($count) {
            echo "Rule name is occupied";
            return;
        } else {
            ob_clean();
            ob_flush();
            echo '';
        }
    }

    public function add_rule_1()
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add/Edit Rule";
    }

    public function get_ingress_prefix()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $res_id = $_POST['res_id'];
        $sql = "SELECT id, tech_prefix FROM resource_prefix WHERE resource_id = {$res_id}";
        $data = $this->AlertRule->query($sql);
        echo json_encode($data);
    }

    public function get_code_name_by_prefix()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $prefix_id = $_POST['prefix_id'];
        $sql = "SELECT distinct code_name FROM rate WHERE rate_table_id = (select rate_table_id from resource_prefix where id = {$prefix_id})";
        $data = $this->AlertRule->query($sql);
        echo json_encode($data);
    }

    function _add_rule_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost()) {
            $post = array();
            parse_str(file_get_contents("php://input"), $post);
            $this->data = $post['data'];

            $this->_create_or_update_rule_data($this->params['form']);
        } #get
        else {
            if (isset($params['id']) && !empty($params['id'])) {
                $this->data = $this->AlertRule->find("first", Array('conditions' => array('AlertRule.id' => $params['id'])));
                if (empty($this->data)) {
                    throw new Exception("Permission denied");
                } else {
                    $this->set('pp', $this->data['AlertRule']); //pr($this->data['Action']);
                }
            } else {

            }
            $name_join_arr['action'] = $this->Action->getActionNameArr();
            $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
            $name_join_arr['resource'] = $this->_getResourceNameArr();
            $name_join_arr['resource_ingress'] = $this->_getResourceNameArr('ingress');
            $name_join_arr['resource_egress'] = $this->_getResourceNameArr('egress');
            $name_join_arr['resource_all'] = $this->_getResourceNameArr('all');
            //$name_join_arr['host'] = $this->_getResourceHostArr();
            //$name_join_arr['port']=$this->_getResourcePortArr();
            $name_join_arr['host_port'] = $this->_getResourceHostPortArr();
            $this->set('name_join_arr', $name_join_arr);
        }
    }

    function _create_or_update_rule_data($params = array())
    {   #update
        if (isset($params['action_id']) && !empty($params['action_id'])) {
//							}
            $id = (int)$params ['action_id'];
//							if(!$this->check_form($id)){
//								return;
//							}
            $this->data['AlertRule'] = $this->data['Alert'];
            if (empty($this->data['Alert']['switch_ip'])) {

                unset($this->data['Alert']['switch_ip']);
                unset($this->data['AlertRule']['switch_ip']);
            }
            $this->data ['AlertRule'] ['id'] = $id;
            //echo $this->data['AlertRule']['is_origin'];exit;
            //$this->data['AlertRule']['is_origin'] = $this->data['AlertRule']['is_origin'] == '0' ? true : false;
            if ($this->data['AlertRule']['is_origin'] === '1') {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id'];
            } elseif ($this->data['AlertRule']['is_origin'] === '0') {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id_1'];
            } elseif ($this->data['AlertRule']['is_origin'] == '2') {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id_2'];
            }
            if ($this->data['AlertRule']['freq_type'] == 1) {
                $this->data['AlertRule']['freq_value'] = $this->data['AlertRule']['freq_value_0'];
            } elseif ($this->data['AlertRule']['freq_type'] == 2) {
                $weeks = implode(',', $this->data['AlertRule']['week']);
                $time = implode(',', $this->data['AlertRule']['time']);
                $this->data['AlertRule']['weekday_time'] = $weeks . '!' . $time;
            }
            $this->data['AlertRule']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['AlertRule']['update_at'] = date("Y-m-d H:i:sO");
            $this->data['AlertRule']['destination_code_name'] = implode(',', $this->data['Alert']['destination_code_name']);
            $this->data['AlertRule']['mail_duration'] = empty($this->data['Alert']['mail_duration']) ? NULL : $this->data['Alert']['mail_duration'];
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            if ($this->AlertRule->save($this->data)) {
                //pr('add');
                //$this->AlertRule->create_json_array('',201,'Rule , Edit successfullyfully');
                $this->AlertRule->create_json_array('', 201, __('The Condition [%s] is modified successfully.', true, $this->data['AlertRule']['name']));
                $this->xredirect('/alerts/rule');
                //	$this->redirect ( array ('id' => $id ) );
            }
        } # add
        else {
//						if(!$this->check_form('')){
//								return;
            $this->data['AlertRule'] = $this->data['Alert'];


            if (empty($this->data['Alert']['switch_ip'])) {

                unset($this->data['Alert']['switch_ip']);
                unset($this->data['AlertRule']['switch_ip']);
            }

            //$this->data['AlertRule']['monitor_type'] = $_POST['monitor_type'];


            if ($this->data['AlertRule']['is_origin'] === '1') {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id'];
            } elseif ($this->data['AlertRule']['is_origin'] === '0') {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id_1'];
            } elseif ($this->data['AlertRule']['is_origin'] == '2') {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id_2'];
            }
            if ($this->data['AlertRule']['freq_type'] == 1) {
                $this->data['AlertRule']['freq_value'] = $this->data['AlertRule']['freq_value_0'];
            } elseif ($this->data['AlertRule']['freq_type'] == 2) {
                $weeks = implode(',', $this->data['AlertRule']['week']);
                $time = implode(',', $this->data['AlertRule']['time']);
                $this->data['AlertRule']['weekday_time'] = $weeks . '!' . $time;
            }
            $this->data['AlertRule']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['AlertRule']['update_at'] = date("Y-m-d H:i:sO");
            $this->data['AlertRule']['destination_code_name'] = implode(',', $this->data['Alert']['destination_code_name']);
            $this->data['AlertRule']['mail_duration'] = empty($this->data['Alert']['mail_duration']) ? NULL : $this->data['Alert']['mail_duration'];
            $this->data['Alert'] = null;
            unset($this->data['Alert']);


            if ($this->AlertRule->save($this->data)) {
                $id = $this->AlertRule->getlastinsertId();
                $this->AlertRule->create_json_array('', 201, 'The ' . $this->data['AlertRule']['name'] . ' is created successfully.');

                $this->xredirect('/alerts/rule');
                //	$this->redirect ( array ('id' => $id ) );
            }
        }
    }

    function _render_rule_save_options()
    {
        $this->loadModel('AlertRule');
        $option['joins'] = array(
            array(
                'table' => 'resource_prefix',
                'alias' => 'Prefix',
                'type' => 'LEFT',
                'conditions' => array(
                    'Prefix.resource_id = AlertRule.ingress_trunk_prefix'
                )
            )
        );
        $data = $this->AlertRule->find('all');
        $this->set('RuleList', $data); //,Array('fields'=>Array('id','name'))));

        $sql = "select distinct name from code where
code_deck_id  = (select code_deck_id from code_deck where client_id = 0)
ORDER BY name";
        $codenames = $this->AlertRule->query($sql);

        $this->set('codenames', $codenames);
    }

    function find_host()
    {
        Configure::write('debug', 0);
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 0;
        $res_id = intval($_REQUEST['res_id']);

        if (!empty($res_id)) {
            $this->set('host', $this->AlertRule->query("select resource_ip_id,ip,port from resource_ip where resource_id=" . intval($res_id)));
        }
    }

    //-------------------------------alert rule end
    //--------------------------------alert report start
    public function report()
    {
        $this->pageTitle = "Disable Trunk Report";
        $event_type = empty($this->params['pass'][0]) ? 1 : $this->params['pass'][0];

        if ($event_type == 1) {
            $header = 'Disabled Ingress Trunk';
        } else {
            $header = 'Disabled Egress Trunk';
        }

        $this->set('header', $header);

        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        if ($event_type == 1) {
            $res_type = empty($this->params['url']['res_type']) ? 1 : $this->params['url']['res_type'];
            $results = $this->AlertReport->disable_trunk_report($currPage, $pageSize, $search_arr, $search_type, $event_type, $res_type);
        }
        $this->set('p', $results);
        $name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        $name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function problem_report()
    {
        $this->pageTitle = "Problem Trunk Report";
        $event_type = 0;
        $res_type = empty($this->params['pass'][0]) ? 1 : $this->params['pass'][0];

        if ($res_type == 1) {
            $header = 'Problem Ingress Trunk';
        } else {
            $header = 'Problem Egress Trunk';
        }
        $this->set('header', $header);

        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

        $search_where = "";

        if (isset($_GET['adv_search'])) {
            if (!empty($_GET['s_rule']))
                $search_where .= " and alert_event.alert_rule_id = {$_GET['s_rule']}";
            if (!empty($_GET['s_client']))
                $search_where .= " and resource.client_id = {$_GET['s_client']}";
            if (!empty($_GET['s_trunk']))
                $search_where .= " and alert_event.res_id = {$_GET['s_trunk']}";
            if (!empty($_GET['start_date']))
                $search_where .= " and event_time >= '{$_GET['start_date']}'";
            if (!empty($_GET['end_date']))
                $search_where .= " and event_time < '{$_GET['end_date']}'";
            if ($_GET['isDelete'] == 1) {
                $sql = "DELETE FROM alert_event WHERE alert_event.id in (SELECT

alert_event.id

FROM alert_event JOIN alert_rule

ON alert_event.alert_rule_id = alert_rule.id JOIN resource

ON alert_event.res_id = resource.resource_id

WHERE resource.ingress = true {$search_where})";
                $this->AlertReport->query($sql);
                $this->AlertReport->create_json_array('', 201, 'Succeeded');
            }
        }


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $results = $this->AlertReport->disable_trunk_report($currPage, $pageSize, $search_arr, $search_type, $event_type, $res_type, $search_where);
        $this->set('p', $results);
        $name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['client'] = $this->Action->getClientNameArr();
        $name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        $name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function priority_report()
    {
        $this->pageTitle = "Priority Report";
        $event_type = empty($this->params['pass'][0]) ? 7 : $this->params['pass'][0];
        $header = 'Priority Trunk';
        $this->set('header', $header);
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        if ($event_type == 7) {
            $results = $this->AlertReport->disable_trunk_report($currPage, $pageSize, $search_arr, $search_type, $event_type);
        }
        $this->set('p', $results);
        $name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        $name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function alternative_route_report()
    {
        $this->pageTitle = "Alternative Route Report";
        $event_type = empty($this->params['pass'][0]) ? 9 : $this->params['pass'][0];

        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        if ($event_type == 9) {
            $results = $this->AlertReport->disable_trunk_report($currPage, $pageSize, $search_arr, $search_type, $event_type);
        }
        $this->set('p', $results);
        //$name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['product'] = $this->_getProductNameArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function no_destination_report()
    {
        $this->pageTitle = "No Destination Report";
        $currPage = 1;
        $pageSize = 10;
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        /*
          if ($event_type == 10)
          {
          $results = $this->AlertReport->disable_trunk_report ($currPage, $pageSize, $search_arr, $search_type, $event_type);
          }
         */
        $results = $this->AlertReport->non_trunk($currPage, $pageSize);
        $this->set('p', $results);

        $name_join_arr['product'] = $this->_getProductNameArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    //-------------------------------view execution log end

    public function get_log_info($id)
    {
        $this->autoLayout = false;
        $sql = <<<EOT
   select
event_time as "time",
destination_code_name as destination,
(select alias from resource where resource_id = alert_event.res_id) as trunk,
(select name from client where client_id = (select client_id from resource where resource_id = alert_event.res_id)) as carrier,
alert_event.event_type,
alert_event.email_addr
from
alert_event
left join alert_rule on alert_event.alert_rule_id = alert_rule.id
where alert_exec_id = $id order by 1 desc
EOT;
        $result = $this->AlertReport->query($sql);
        $this->set('result', $result);
    }

    public function delete_log($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "delete from alert_exec_log where id = {$id}";
        $this->AlertReport->query($sql);
        $this->AlertRule->create_json_array('', 201, 'The Rule Execution Log is deleted successfully!');
        $this->Session->write('m', AlertRule::set_validator());
        $this->xredirect("/alerts/view_log");
    }

    public function view_log()
    {
        $this->pageTitle = "View Execution Log";
        //$event_type = empty($this->params['pass'][0]) ? 10 : $this->params['pass'][0];

        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        $results = $this->AlertReport->ViewExecutionLog($currPage, $pageSize, $search_arr, $search_type);

        $this->set('p', $results);
        //$name_join_arr['action'] = $this->Action->getActionNameArr();
        //$name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        //$name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        //$name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        //$name_join_arr['resource'] = $this->_getResourceNameArr();
        //$name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        //$name_join_arr['product'] = $this->_getProductNameArr();
        //$this->set('name_join_arr', $name_join_arr);
    }

    public function list_action()
    {
        $this->pageTitle = "View Execution Log Action";
        $search_arr['alert_exec_id'] = empty($this->params['pass'][0]) ? 0 : $this->params['pass'][0];

        $currPage = 1;
        $pageSize = 10;

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        $results = $this->AlertReport->ListEvent($currPage, $pageSize, $search_arr);
        $this->set('p', $results);
        //$name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        //$name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        //$name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        //$name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        $name_join_arr['product'] = $this->_getProductNameArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    //-------------------------------view execution log end

    public function get_condition($name)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "
SELECT
	CASE acd_comparator WHEN 0 THEN  acd_value_min || ' <= ACD'
			    WHEN 1 THEN  acd_value_min || ' <= ACD <= ' || acd_value_max
			    WHEN 2 THEN  'Ignore'
	END AS acd,
	CASE asr_comparator WHEN 0 THEN  asr_value_min || ' <= ASR'
			    WHEN 1 THEN  asr_value_min || ' <= ASR <= ' || asr_value_max
			    WHEN 2 THEN  'Ignore'
	END AS asr,
	CASE margin_comparator WHEN 0 THEN  margin_value_min || ' <= Margin'
			    WHEN 1 THEN  margin_value_min || ' <= Margin <= ' || margin_value_max
			    WHEN 2 THEN  'Ignore'
	END AS margin
FROM alert_condition WHERE name = '{$name}'";
        $result = $this->Condition->query($sql);
        echo json_encode($result);
    }

    public function get_action($name)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "
SELECT
	CASE WHEN email_to_noc = true THEN 'email to noc,' ELSE '' END ||
	CASE WHEN email_to_carrier = true THEN 'email to carrier,' ELSE '' END ||
	CASE WHEN disable_host = true THEN 'disable host,' ELSE '' END ||
	CASE WHEN disable_resource = true THEN 'disable resource,' ELSE '' END ||
	CASE WHEN disable_code_trunk = true THEN 'disable code trunk' ELSE '' END AS content
FROM alert_action WHERE name = '{$name}'";
        $result = $this->Condition->query($sql);
        echo json_encode($result);
    }

    public function rule_status($id, $status)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "update alert_rule set status = {$status} where id = {$id}";
        $this->AlertRule->query($sql);
        $this->AlertRule->create_json_array('', 201, 'The status is changed successfully!');
        $this->Session->write('m', AlertRule::set_validator());
        $this->xredirect("/alerts/rule");
    }

    public function get_events($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "SELECT
    CASE event_type
        WHEN 1 THEN 'disable trunk'
	WHEN 2 THEN 'disable host'
	WHEN 3 THEN 'enable trunk'
	WHEN 4 THEN 'enable host'
	WHEN 5 THEN 'disable code trunk'
	WHEN 6 THEN 'enable code trunk'
	WHEN 7 THEN 'change priority'
	WHEN 8 THEN 'email'
	WHEN 9 THEN 'change to old priority'
    END AS event,email_addr
FROM
	alert_event
WHERE
	alert_exec_id = {$id}";
        $result = $this->AlertReport->query($sql);
        echo json_encode($result);
    }

    public function delete_all()
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {
            $this->redirect_denied();
        }

        if ($this->AlertReport->deleteAll() != true)
            $this->Condition->create_json_array('', 101, "can not delete");
        else
            $this->Condition->create_json_array('', 201, __('All alert_rule is deleted successfully.', true));

        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/rule');
    }

    public function delete_selected()
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];
        $arrName = $this->AlertReport->getNameByID($ids);


        foreach ($arrName as $name) {
            $tip .= $name[0]['name'] . ",";
        }
        $tip = '[' . substr($tip, 0, -1) . ']';
        $r = $this->AlertReport->deleteSelected($ids);

        if ($r != true)
            $this->Condition->create_json_array('', 101, "can not delete");
        else
            $this->Condition->create_json_array('', 201, 'The alert_rule ' . $tip . ' is deleted successfully!');

        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/rule');
    }

    public function delete_rules_invalid_all()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Monitoring']['alerts:invalid_number']['model_w']) {
            $this->redirect_denied();
        }

        $ids = $this->AlertRules->query("select id from invalid_number_detection ");
        $ids_array = array();
        foreach ($ids as $id) {
            $ids_array[] = $id[0]['id'];
        }
        $ids_str = implode(',', $ids_array);
        $this->_remove_crontab($ids_str);

        $res = $this->AlertRules->query("delete from invalid_number_detection");


        $this->AlertRules->create_json_array('', 201, __('All Rules are deleted successfully!', true));
        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/invalid_number');
    }

    public function delete_rules_invalid_selected_id($ids)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:invalid_number']['model_w']) {
            $this->redirect_denied();
        }
        $ids = base64_decode($ids);
        $arrName = $this->AlertRules->get_invalid_numberByID($ids);
        $this->_remove_crontab($ids);
        $tip = '';
        foreach ($arrName as $name) {
            $tip .= $name[0]['rule_name'] . ",";
        }
        $tip = '[' . substr($tip, 0, -1) . ']';
        $r = $this->AlertRules->deleteInvalidNumberSelected($ids);
        if ($r != true)
            $this->Condition->create_json_array('', 101, "can not delete");
        else
            $this->Condition->create_json_array('', 201, 'The rule ' . $tip . ' is deleted successfully!');

        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/invalid_number');
    }

    public function delete_rules_invalid_selected()
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:invalid_number']['model_w']) {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];

        $this->_remove_crontab($ids);
        $arrName = $this->AlertRules->get_invalid_numberByID($ids);
        foreach ($arrName as $name) {
            $tip .= $name[0]['rule_name'] . ",";
        }
        $tip = '[' . substr($tip, 0, -1) . ']';
        $r = $this->AlertRules->deleteInvalidNumberSelected($ids);
        if ($r != true)
            $this->Condition->create_json_array('', 101, "can not delete");
        else
            $this->Condition->create_json_array('', 201, 'The rule ' . $tip . ' is deleted successfully!');

        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/invalid_number');
    }

    public function delete_rules_all()
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rules']['model_w']) {
            $this->redirect_denied();
        }

        if ($this->AlertRules->deleteAll() != true)
            $this->AlertRules->create_json_array('', 101, "can not delete");
        else
            $this->AlertRules->create_json_array('', 201, __('All Rules are deleted successfully!', true));

        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/rules');
    }

    public function delete_rules_selected()
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rules']['model_w']) {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];
        $arrName = $this->AlertRules->getNameByID($ids);


        foreach ($arrName as $name) {
            $tip .= $name[0]['rule_name'] . ",";
        }
        $tip = '[' . substr($tip, 0, -1) . ']';
        $r = $this->AlertRules->deleteSelected($ids);
        if ($r != true)
            $this->Condition->create_json_array('', 101, "can not delete");
        else
            $this->Condition->create_json_array('', 201, 'The rule ' . $tip . ' is deleted successfully!');

        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/rules');
    }

    public function action_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            if ($id != null)
                $this->data['Action']['id'] = $id;
            $this->data['Action']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Action']['update_at'] = date('Y-m-d H:i:sO');
            if ($this->data['Action']['email_notification'] == 1) {
                $this->data['Action']['email_to_noc'] = TRUE;
                $this->data['Action']['email_to_carrier'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 2) {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 3) {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = TRUE;
            }

//         echo "<pre>";
//                       print_r($this->data);die;
            $this->Action->save($this->data);
            if ($id != null)
                $this->Session->write('m', $this->Action->create_json(201, __('The alert action [%s] is modified successfully!', true, $this->data['Action']['name'])));
            else
                $this->Session->write('m', $this->Action->create_json(201, __('The alert action [%s] is created successfully!', true, $this->data['Action']['name'])));
            $this->xredirect("/alerts/action");
        }

        $templates = $this->TroubleTicketsTemplate->find('list', array('fields' => array("TroubleTicketsTemplate.name")));

        $templates = array(null => '') + $templates;
        $this->set('templates', $templates);
        $this->data = $this->Action->find('first', Array('conditions' => Array('id' => $id)));
    }

    public function block_log_invalid_number()
    {
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page'))) {
            $currPage = $this->params['url']['page'];
        }
        $pageSize = 20;
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = $this->BlockLog->log_count_invalid_number();

        $page->setTotalRecords($totalrecords); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "ORDER BY block_on DESC";
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $data_arr = $this->BlockLog->log_list_invalid_number($order_sql, $pageSize, $offset);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

    public function block_log()
    {
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page'))) {
            $currPage = $this->params['url']['page'];
        }
        $pageSize = 20;
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = $this->BlockLog->log_count();

        $page->setTotalRecords($totalrecords); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "ORDER BY block_on DESC";
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $data_arr = $this->BlockLog->log_list($order_sql, $pageSize, $offset);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

    public function block_trouble_ticket()
    {
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page'))) {
            $currPage = $this->params['url']['page'];
        }
        $pageSize = 20;
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = $this->BlockTroubleTicket->data_count();

        $page->setTotalRecords($totalrecords); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "ORDER BY block_on DESC";
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $data_arr = $this->BlockTroubleTicket->data_list($order_sql, $pageSize, $offset);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

    public function test()
    {
        App::import("Vendor", "crontab", array('file' => "crontab.php"));
        $crontab = new Crontab();
        $crontab_time = "*/2 * * * *";
        $crontab->get_list();
    }


    public function ajax_get_code_name()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($this->RequestHandler->isPost()) {
            $code_deck_id = $_POST['code_deck_id'];
            $code_sql = "SELECT code_id,name FROM code WHERE code_deck_id = $code_deck_id order by name ASC ";
            $code_result = $this->BlockLog->query($code_sql);
            $code_arr = array();
//            var_dump($code_result);
            foreach ($code_result as $items)
                $code_arr[$items[0]['code_id']] = $items[0]['name'];
            echo json_encode($code_arr);
        } else {
            echo 0;
        }
    }


    public function invalid_number_exec_log()
    {
        $this->loadModel('InvalidDetectionLog');
        if (!isset($this->params['pass'][0]) || !in_array($this->params['pass'][0], array(1, 2)))
            $this->redirect('invalid_number');
        $conditions = array(
            'code_type' => $this->params['pass'][0],
        );
        if ($this->_get('show_type', 1) == 1)
            $conditions['total_num > ?'] = 0;
        $start_time = $this->_get('query_time_start', date('Y-m-d H:i:s', strtotime("-60 days")));
        $end_time = $this->_get('query_time_end', date('Y-m-d H:i:s'));
        if ($start_time)
            $conditions['start_time >= ?'] = $start_time;
        if ($end_time)
            $conditions['start_time <= ?'] = $end_time;

        if ($this->_get('rule_name'))
            $conditions['InvalidNumber.rule_name like ?'] = "%" . $this->_get('rule_name') . "%";

        if ($this->_get('ingress'))
            $conditions['Resource.resource_id'] = $this->_get('ingress');
        $limit = isset($_REQUEST['size']) ? $_REQUEST['size'] : 100;

        $this->paginate = array(
            'fields' => array('InvalidDetectionLog.start_time', 'InvalidDetectionLog.finished_time', 'Resource.alias',
                'InvalidDetectionLog.total_num', 'InvalidDetectionLog.invalid_num', 'InvalidDetectionLog.detection_id',
                'InvalidNumber.rule_name', 'InvalidDetectionLog.id'),
            'limit' => $limit,
            'order' => array(
                'start_time' => 'desc',
            ),
            'joins' => array(
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type' => 'inner',
                    'conditions' => array(
                        'Resource.resource_id = InvalidDetectionLog.ingress_id'
                    ),
                ),
                array(
                    'alias' => 'InvalidNumber',
                    'table' => 'invalid_number_detection',
                    'type' => 'inner',
                    'conditions' => array(
                        'InvalidNumber.id = InvalidDetectionLog.detection_id'
                    )
                )
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('InvalidDetectionLog');
        $this->params['url']['query_time_start'] = $start_time;
        $this->params['url']['query_time_end'] = $end_time;
        $ingress_trunk = $this->InvalidNumber->findAll_ingress_id(true, 'and status = 1');
        $this->set('ingress_trunk', $ingress_trunk);
    }


    public function invalid_number_log_detail()
    {
        $this->loadModel('InvalidDetectionLogDetail');
        if (!isset($this->params['url']['log']))
            $this->redirect('invalid_number');
        $conditions = array(
            'log_id' => intval(base64_decode($this->params['url']['log']))
        );
        $order_sql = "start_time desc";
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "{$field} {$sort}";
            }
        }

        $this->paginate = array(
            'fields' => array('InvalidDetectionLogDetail.start_time', 'InvalidDetectionLogDetail.end_time', 'Resource.alias',
                'InvalidDetectionLogDetail.total_call', 'InvalidDetectionLogDetail.count404', 'InvalidDetectionLogDetail.count503',
                'InvalidDetectionLogDetail.count200', 'InvalidDetectionLogDetail.others_call', 'InvalidDetectionLogDetail.code_type',
                'InvalidDetectionLogDetail.number', 'InvalidDetectionLogDetail.id'),
            'limit' => 100,
            'order' => $order_sql,
            'joins' => array(
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type' => 'inner',
                    'conditions' => array(
                        'Resource.resource_id = InvalidDetectionLogDetail.ingress_id'
                    ),
                ),
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('InvalidDetectionLogDetail');
    }


    public function invalid_number_block()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->loadModel('InvalidDetectionLogDetail');
        if (!isset($this->params['pass'][0]))
            $this->params['pass'][0] = 1;
        if (!isset($this->params['url']['log']))
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        $log_id = base64_decode($this->params['url']['log']);
        $log_info = $this->InvalidDetectionLogDetail->find('first', array(
            'fields' => array(
                'InvalidDetectionLogDetail.number', 'InvalidDetectionLogDetail.ingress_id',
                'InvalidDetectionLogDetail.code_type', 'InvalidDetection.id',
            ),
            'joins' => array(
                array(
                    'alias' => 'InvalidDetectionLog',
                    'table' => 'invalid_number_detection_log',
                    'conditions' => array(
                        'InvalidDetectionLog.id = InvalidDetectionLogDetail.log_id'
                    ),
                ),
                array(
                    'alias' => 'InvalidDetection',
                    'table' => 'invalid_number_detection',
                    'conditions' => array(
                        'InvalidDetectionLog.detection_id = InvalidDetection.id'
                    ),
                ),
            ),
            'conditions' => array(
                'InvalidDetectionLogDetail.id' => $log_id,
            ),
        ));
        if (empty($log_info))
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        $log_data = $log_info['InvalidDetectionLogDetail'];
        if ($log_data['code_type'] == 1) {
            $conditions = array(
                'ani_prefix' => $log_data['number'],
                'ingress_res_id' => $log_data['ingress_id'],
                "(digit is null or digit = '')",
                'engress_res_id is null'
            );
        } else {
            $conditions = array(
                'digit' => $log_data['number'],
                'ingress_res_id' => $log_data['ingress_id'],
                "(ani_prefix is null or ani_prefix = '')",
                'engress_res_id is null'
            );
        }
        $this->loadModel('ResourceBlock');
        $resource_count = $this->ResourceBlock->find('count', array(
            'conditions' => $conditions
        ));
        if ($resource_count) {
            $this->Session->write('m', $this->InvalidDetectionLogDetail->create_json(201, __('Resource Block records already exist', true)));
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        }
        $resource_client = $this->InvalidDetectionLogDetail->query("select client_id from resource where resource_id = {$log_data['ingress_id']}");
        $resource_data = array(
            'ingress_res_id' => $log_data['ingress_id'],
            'ingress_client_id' => $resource_client[0][0]['client_id'],
            'action_type' => 4,
            'update_by' => $_SESSION['sst_user_name'],
        );
        if ($log_data['code_type'] == 1)
            $resource_data['ani_prefix'] = $log_data['number'];
        else
            $resource_data['digit'] = $log_data['number'];
        if ($this->ResourceBlock->save($resource_data) === false) {
            $this->Session->write('m', $this->InvalidDetectionLogDetail->create_json(101, __('Resource Block records save failed', true)));
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        }
        $block_log_data = array(
            'rule_id' => $log_info['InvalidDetection']['id'],
            'ingress_id' => $log_data['ingress_id'],
            'number' => $log_data['number'],
            'number_type' => $log_data['code_type'],
            'block_type' => 1,
            'created_on' => date('Y-m-d H:i:sO'),
        );
        $this->loadModel('InvalidDetectionBlockLog');
        if ($this->InvalidDetectionBlockLog->save($block_log_data) === false) {
            $this->Session->write('m', $this->InvalidDetectionLogDetail->create_json(101, __('Resource Block log save failed', true)));
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        }
        $this->Session->write('m', $this->InvalidDetectionLogDetail->create_json(201, __('Block data successfully', true)));
        $this->redirect('invalid_number_block_log');

    }


    public function invalid_number_unblock()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->loadModel('InvalidDetectionLogDetail');
        if (!isset($this->params['pass'][0]))
            $this->params['pass'][0] = 1;
        if (!isset($this->params['url']['log']))
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        $log_id = base64_decode($this->params['url']['log']);
        $log_info = $this->InvalidDetectionLogDetail->find('first', array(
            'fields' => array(
                'InvalidDetectionLogDetail.number', 'InvalidDetectionLogDetail.ingress_id',
                'InvalidDetectionLogDetail.code_type', 'InvalidDetection.id',
            ),
            'joins' => array(
                array(
                    'alias' => 'InvalidDetectionLog',
                    'table' => 'invalid_number_detection_log',
                    'conditions' => array(
                        'InvalidDetectionLog.id = InvalidDetectionLogDetail.log_id'
                    ),
                ),
                array(
                    'alias' => 'InvalidDetection',
                    'table' => 'invalid_number_detection',
                    'conditions' => array(
                        'InvalidDetectionLog.detection_id = InvalidDetection.id'
                    ),
                ),
            ),
            'conditions' => array(
                'InvalidDetectionLogDetail.id' => $log_id,
            ),
        ));
        if (empty($log_info))
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        $log_data = $log_info['InvalidDetectionLogDetail'];
        if ($log_data['code_type'] == 1) {
            $conditions = array(
                'ani_prefix' => $log_data['number'],
                'ingress_res_id' => $log_data['ingress_id'],
                "(digit is null or digit = '')",
                'engress_res_id is null'
            );
        } else {
            $conditions = array(
                'digit' => $log_data['number'],
                'ingress_res_id' => $log_data['ingress_id'],
                "(ani_prefix is null or ani_prefix = '')",
                'engress_res_id is null'
            );
        }
        $this->loadModel('ResourceBlock');
        $resource_exist = $this->ResourceBlock->find('first', array(
            'fields' => array('res_block_id'),
            'conditions' => $conditions
        ));
        if (!isset($resource_exist['ResourceBlock']['res_block_id'])) {
            $this->Session->write('m', $this->InvalidDetectionLogDetail->create_json(201, __('Resource Block records not exist', true)));
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        }
        $flg = $this->ResourceBlock->del($resource_exist['ResourceBlock']['res_block_id']);
        $block_log_data = array(
            'rule_id' => $log_info['InvalidDetection']['id'],
            'ingress_id' => $log_data['ingress_id'],
            'number' => $log_data['number'],
            'number_type' => $log_data['code_type'],
            'block_type' => 2,
            'created_on' => date('Y-m-d H:i:sO'),
        );
        $this->loadModel('InvalidDetectionBlockLog');
        if ($this->InvalidDetectionBlockLog->save($block_log_data) === false) {
            $this->Session->write('m', $this->InvalidDetectionLogDetail->create_json(101, __('Resource Block log save failed', true)));
            $this->redirect('invalid_number_exec_log/' . $this->params['pass'][0]);
        }
        $this->Session->write('m', $this->InvalidDetectionLogDetail->create_json(201, __('UnBlock data successfully', true)));
        $this->redirect('invalid_number_block_log');

    }


    public function invalid_number_block_log()
    {
        $this->loadModel('InvalidDetectionBlockLog');
        $this->paginate = array(
            'fields' => array('InvalidDetectionBlockLog.created_on', 'InvalidDetectionBlockLog.number_type', 'Resource.alias',
                'InvalidDetectionBlockLog.number', 'InvalidDetectionBlockLog.block_type', 'InvalidDetection.rule_name'),
            'limit' => 100,
            'order' => array(
                'created_on' => 'desc',
            ),
            'joins' => array(
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type' => 'inner',
                    'conditions' => array(
                        'Resource.resource_id = InvalidDetectionBlockLog.ingress_id'
                    ),
                ),
                array(
                    'alias' => 'InvalidDetection',
                    'table' => 'invalid_number_detection',
                    'conditions' => array(
                        'InvalidDetectionBlockLog.rule_id = InvalidDetection.id'
                    ),
                ),
            ),
            'conditions' => array(),
        );
        $this->data = $this->paginate('InvalidDetectionBlockLog');
    }


    public function disable_invalid($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->InvalidNumber->find('count', array('id' => $id));
        if (!$count) {
            $this->Session->write('m', $this->InvalidNumber->create_json(101, __('Illegal operation', true)));
            $this->redirect('index');
        }
        $update_arr = array(
            'id' => $id,
            'active' => false
        );
        if ($this->InvalidNumber->save($update_arr) === false)
            $this->Session->write('m', $this->InvalidNumber->create_json(101, __('Disable failed', true)));
        else
            $this->Session->write('m', $this->InvalidNumber->create_json(201, __('Disable successfully', true)));
        $this->redirect('invalid_number');
    }


    public function enable_invalid($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->InvalidNumber->find('count', array('id' => $id));
        if (!$count) {
            $this->Session->write('m', $this->InvalidNumber->create_json(101, __('Illegal operation', true)));
            $this->redirect('index');
        }
        $update_arr = array(
            'id' => $id,
            'active' => true
        );
        if ($this->InvalidNumber->save($update_arr) === false)
            $this->Session->write('m', $this->InvalidNumber->create_json(101, __('Enable failed', true)));
        else
            $this->Session->write('m', $this->InvalidNumber->create_json(201, __('Enable successfully', true)));
        $this->redirect('invalid_number');
    }

    public function disable_rule($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->AlertRules->find('count', array('id' => $id));
        $rule = $this->AlertRules->find('first', array('id' => $id));
        if (!$count) {
            $this->Session->write('m', $this->AlertRules->create_json(101, __('Illegal operation', true)));
            $this->redirect('rules');
        }
        $update_arr = array(
            'id' => $id,
            'active' => false
        );
        if ($this->AlertRules->save($update_arr) === false)
            $this->Session->write('m', $this->AlertRules->create_json(101, __('Disable failed', true)));
        else
            $this->Session->write('m', $this->AlertRules->create_json(201, "The Rule [{$rule['AlertRules']['rule_name']}] is deactivated successfully!"));
        $this->redirect('rules');
    }


    public function enable_rule($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->AlertRules->find('count', array('id' => $id));
        $rule = $this->AlertRules->find('first', array('id' => $id));
        if (!$count) {
            $this->Session->write('m', $this->AlertRules->create_json(101, __('Illegal operation', true)));
            $this->redirect('rules');
        }
        $update_arr = array(
            'id' => $id,
            'active' => true
        );
        if ($this->AlertRules->save($update_arr) === false)
            $this->Session->write('m', $this->AlertRules->create_json(101, __('Enable failed', true)));
        else
            $this->Session->write('m', $this->AlertRules->create_json(201, "The Rule [{$rule['AlertRules']['rule_name']}] is activated successfully!"));
        $this->redirect('rules');
    }

    public function alert_rules_log()
    {
        $this->pageTitle = __('Alert Rules Log', true);
        $conditions = array();


        $start_time = $this->_get('start_time', date("Y-m-d 00:00:00"));
        $end_time = $this->_get('end_time', date("Y-m-d 23:59:59"));
        $conditions = "AlertRulesLog.create_on >= '{$start_time}' AND AlertRulesLog.create_on <= '{$end_time}'";

        $get_rule_name = $this->_get('rule_name');
        if ($get_rule_name)
            $conditions .= " AND AlertRules.rule_name like '%" . trim($this->_get('rule_name')) . "%'";

        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_arr = array('create_on' => 'DESC');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $this->paginate = array(
            'fields' => array('AlertRulesLog.*', 'AlertRules.rule_name'),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'alias' => 'AlertRules',
                    'table' => 'alert_rules',
                    'type' => 'inner',
                    'conditions' => array(
                        'AlertRules.id = AlertRulesLog.alert_rules_id'
                    ),
                ),
            ),
        );
        $this->data = $this->paginate('AlertRulesLog');
        foreach ($this->data as &$item) {
            $item[0]['create_on'] = date("Y-m-d H:i:s", strtotime($item[0]['create_on']));
            $item[0]['finish_time'] = date("Y-m-d H:i:s", strtotime($item[0]['finish_time']));
        }
        $ruleNames = array();
        $rulesList = $this->AlertRules->rules_list(1000, 0);
        foreach ($rulesList as $rl) {
            // if ($rl[0]['active']) { .//uncomment for the case if we want to show only active rules
            $ruleNames[] = $rl[0];
            // }
        }
        $this->set('ruleNames', $ruleNames);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
//        $this->set('create_by_arr',$this->AlertRulesLog->get_create_by_arr());
//        $this->set('status_arr',$this->AlertRulesLog->get_status_arr());

    }

    public function alert_rules_log_detail($encode_log_id)
    {
        $this->pageTitle = __('Alert Rules Log Detail', true);
        if (!$encode_log_id)
            $this->redirect('alert_rules_log');
        $conditions = array(
            'AlertRulesLog.id' => base64_decode($encode_log_id)
        );
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;
        $order_arr = array('AlertRulesLogDetail.id' => 'asc');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $sdp_sign = ['', '=', '<', '>'];
        $this->set('sdp_sign', $sdp_sign);
        $this->paginate = array(
            'fields' => array(
                'AlertRulesLogDetail.country', 'AlertRulesLogDetail.id', 'AlertRulesLogDetail.*', 'AlertRulesLog.*',
                'AlertRules.rule_name', 'Resource.alias', 'AlertRules.res_id', 'AlertRules.unblock_after_min',
                'AlertRulesLogDetail.sdp_value', 'AlertRules.sdp_value', 'AlertRules.sdp_sign'
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'alias' => 'AlertRulesLog',
                    'table' => 'alert_rules_log',
                    'type' => 'inner',
                    'conditions' => array(
                        'AlertRulesLog.id = AlertRulesLogDetail.alert_rules_log_id'
                    ),
                ),
                array(
                    'alias' => 'AlertRules',
                    'table' => 'alert_rules',
                    'type' => 'inner',
                    'conditions' => array(
                        'AlertRules.id = AlertRulesLog.alert_rules_id'
                    ),
                ),
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type' => 'left',
                    'conditions' => array(
                        'Resource.resource_id = AlertRulesLogDetail.resource_id'
                    ),
                ),
            ),
        );
        $this->data = $this->paginate('AlertRulesLogDetail');
        foreach ($this->data as &$item) {
            switch ($item[0]['monitor_by']) {
                case '0':
                    $item[0]['monitor_by'] = ['Trunk' => $item['Resource']['alias']];
                    break;
                case '1':
                    $item[0]['monitor_by'] = ['Trunk' => $item['Resource']['alias'], 'DNIS' => $item[0]['routing_digits']];
                    break;
                case '2':
                    $item[0]['monitor_by'] = ['Trunk' => $item['Resource']['alias'], 'ANI' => $item[0]['origination_source_number']];
                    break;
                case '3':
                    $item[0]['monitor_by'] = ['DNIS' => $item[0]['routing_digits']];
                    break;
                case '4':
                    $item[0]['monitor_by'] = ['ANI' => $item[0]['origination_source_number']];
                    break;
                case '5':
                    $item[0]['monitor_by'] = ['Trunk' => $item['Resource']['alias'], 'Destination' => ''];
                    break;
                case '6':
                    $item[0]['monitor_by'] = ['Trunk' => $item['Resource']['alias'], 'Country' => $item['AlertRulesLogDetail']['country']];
                    break;
                case '7':
                    $item[0]['monitor_by'] = ['Trunk' => $item['Resource']['alias'], 'Code' => $item[0]['code']];
                    break;
                default :
                    $item[0]['monitor_by'] = ['Trunk' => $item['Resource']['alias']];
            }
        }
    }

}