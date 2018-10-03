<?php

class TrafficAlertController extends AppController
{

    var $name = "TrafficAlert";
    var $uses = array('TrafficAlert', 'Client', 'Mailtmp');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function index()
    {
        $order_arr = array('TrafficAlert.id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $data = $this->TrafficAlert->find('all', array('order' => $order_arr));
        if (empty($data))
        {
            $msg = "Traffic Alert Rule";
            $add_url = "add_rule";
            $model_name = "TrafficAlert";
            $this->to_add_page($model_name,$msg,$add_url);
        }
        $this->set('data', $data);
    }

    public function ajax_get_carrier_name($rule_id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $data = $this->TrafficAlert->findById($rule_id);
        $carriers = $data['TrafficAlert']['carriers'];
        $carriers_id_arr = explode(",", $carriers);
        $carriers_name_arr = array();
        foreach ($carriers_id_arr as $carrier_id)
        {
            $carrier_info = $this->Client->findByClientId($carrier_id);
            $carriers_name_arr[] = $carrier_info['Client']['name'];
        }
        $this->set('carrier_names', $carriers_name_arr);
    }

    public function add_rule()
    {
        $this->set("carriers", $this->Client->findIngressClient());

        $a_z_code_deck = $this->TrafficAlert->query("select code_deck_id from code_deck where client_id = 0 limit 1");
        if (empty($a_z_code_deck))
        {
            $this->TrafficAlert->create_json_array('', 101, "Can not found the A_Z code deck!");
            $this->Session->write('m', TrafficAlert::set_validator());
            $this->redirect('index');
        }
        $a_z_code_deck_id = $a_z_code_deck[0][0]['code_deck_id'];

        $code_name_arr = $this->TrafficAlert->query("select distinct(name) as name from code where code_deck_id = {$a_z_code_deck_id} ORDER by name ASC ");
//        $code_name_arr = $this->TrafficAlert->query("select distinct(name) as name from code ORDER by name ASC ");

        $this->set('code_names', $code_name_arr);
        $mail_senders = $this->Mailtmp->get_mail_senders();
        $this->set('mail_senders', $mail_senders);
    }

    public function add_save_rule_post()
    {
        if ($this->RequestHandler->ispost())
        {
//            pr($this->params['form']);die;
            $add_arr = array();
            $post_data = $this->params['form'];
            if (isset($post_data['id']))
            {
                $add_arr['id'] = $post_data['id'];
            }
            $add_arr['carriers'] = implode(",", $post_data['carriers']);
            $add_arr['code_name'] = implode(",", $post_data['destination']);
            $add_arr['email'] = $post_data['email_to'];
            $add_arr['less_hour'] = $post_data['less_than_num'];
            $add_arr['greater_hour'] = $post_data['greater_than_num'];
            $add_arr['mail_from'] = $post_data['mail_from'];
            $add_arr['mail_subject'] = $post_data['mail_subject'];
            $add_arr['mail_content'] = $post_data['mail_content'];
            $add_arr['active'] = $post_data['active'];
            $flg = $this->TrafficAlert->save($add_arr);
            if ($flg === false)
            {
                $this->TrafficAlert->create_json_array('', 101, "Failed!");
                $this->Session->write('m', TrafficAlert::set_validator());
                $this->redirect('add_rule');
            }
            else
            {
                $this->TrafficAlert->create_json_array('', 201, "Traffic Alert Rule is created successfully!");
                $this->Session->write('m', TrafficAlert::set_validator());
                $this->redirect('index');
            }
        }
        $this->TrafficAlert->create_json_array('', 101, "Failed!");
        $this->Session->write('m', TrafficAlert::set_validator());
        $this->redirect('add_rule');
    }

    public function delete($encode_id)
    {
        $id = base64_decode($encode_id);
        $flg = $this->TrafficAlert->del($id);
        if ($flg === false)
        {
            $this->TrafficAlert->create_json_array('', 101, "Failed!");
        }
        else
        {
            $this->TrafficAlert->create_json_array('', 201, "Succeed!");
        }
        $this->Session->write('m', TrafficAlert::set_validator());
        $this->redirect('index');
    }

    public function edit_rule($encode_id)
    {
        $id = base64_decode($encode_id);
        $this->set("carriers", $this->Client->findIngressClient());

        $a_z_code_deck = $this->TrafficAlert->query("select code_deck_id from code_deck where client_id = 0 limit 1");
        if (empty($a_z_code_deck))
        {
            $this->TrafficAlert->create_json_array('', 101, "Can not found the A_Z code deck!");
            $this->Session->write('m', Condition::set_validator());
            $this->redirect('index');
        }
        $a_z_code_deck_id = $a_z_code_deck[0][0]['code_deck_id'];

        $code_name_arr = $this->TrafficAlert->query("select distinct(name) as name from code where code_deck_id = {$a_z_code_deck_id} order by name ASC ");

        $this->set('code_names', $code_name_arr);

        $rule_arr = $this->TrafficAlert->findById($id);
        $this->set('rule', $rule_arr);
        $carrier_arr = explode(",", $rule_arr['TrafficAlert']['carriers']);
        $destination_arr = explode(",", $rule_arr['TrafficAlert']['code_name']);
        $this->set('carriers_arr', $carrier_arr);
        $this->set('destination', $destination_arr);
        $mail_senders = $this->Mailtmp->get_mail_senders();
        $this->set('mail_senders', $mail_senders);
    }


    public function disable($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->TrafficAlert->find('count',array('id' => $id));
        if (!$count)
        {
            $this->Session->write('m', $this->TrafficAlert->create_json(101, __('Illegal operation',true)));
            $this->redirect('index');
        }
        $update_arr = array(
            'id' => $id,
            'active' => false
        );
        if ($this->TrafficAlert->save($update_arr) === false)
            $this->Session->write('m', $this->TrafficAlert->create_json(101, __('Disable failed',true)));
        else
            $this->Session->write('m', $this->TrafficAlert->create_json(201, __('Disable successfully',true)));
        $this->redirect('index');
    }


    public function enable($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = intval(base64_decode($encode_id));
        $count = $this->TrafficAlert->find('count',array('id' => $id));
        if (!$count)
        {
            $this->Session->write('m', $this->TrafficAlert->create_json(101, __('Illegal operation',true)));
            $this->redirect('index');
        }
        $update_arr = array(
            'id' => $id,
            'active' => true
        );
        if ($this->TrafficAlert->save($update_arr) === false)
            $this->Session->write('m', $this->TrafficAlert->create_json(101, __('Enable failed',true)));
        else
            $this->Session->write('m', $this->TrafficAlert->create_json(201, __('Enable successfully',true)));
        $this->redirect('index');
    }

}
