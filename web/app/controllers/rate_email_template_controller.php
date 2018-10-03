<?php

class RateEmailTemplateController extends AppController
{

    var $name = "RateEmailTemplate";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('RateEmailTemplate');
    var $rollback = false;

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect('email_template');
    }

    public function email_template()
    {
        $get_template_name = $this->_get('template_name');
        $conditions = 'name is not null';
        if($get_template_name)
        {
            $conditions .= " AND name like '%" . trim($this->_get('template_name')) . "%'";
        }

        $this->paginate = array(
            'limit' => isset($_GET['size']) ? $_GET['size'] : 100,
            'order' => 'name asc',
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('RateEmailTemplate');
        if (empty($this->data) && !$get_template_name)
        {
            $msg = "Rate Email Template";
            $add_url = "add_template";
            $model_name = "RateEmailTemplate";
            $this->to_add_page($model_name,$msg,$add_url);
        }
        $sql = "select email,id from mail_sender order by email asc";
        $mail_sender = $this->RateEmailTemplate->query($sql);
        $size = count($mail_sender);
        $mail_sender_arr = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $mail_sender [$i] [0] ['id'];
            $mail_sender_arr [$key] = $mail_sender [$i] [0] ['email'];
        }
        $this->set('mail_sender', $mail_sender_arr);
    }

    public function add_template($encode_id = '')
    {
        if ($this->_get('is_ajax'))
            Configure::write('debug',0);
        if ($encode_id)
        {
            $id = base64_decode($encode_id);
            $data = $this->RateEmailTemplate->findById($id);
            $this->set('data', $data);
        }

        if ($this->RequestHandler->ispost())
        {
            if ($this->_get('is_ajax'))
            {
                $this->autoLayout = false;
                $this->autoRender = false;
            }
            $post_data = $this->params['form'];
//            echo "<pre>";print_r($post_data);die;
            $name = $post_data['name'];
            if ($this->judge_template_name($name, $encode_id))
            {
                if ( $this->_get('is_ajax') ) {
                    return 0;
                }
                else{
                    $this->RateEmailTemplate->create_json_array('', 101, sprintf(__('Template name[%s] is exsit!', true), $name));
                    $this->Session->write('m', RateEmailTemplate::set_validator());
                    $this->redirect('index');
                }
            }

            $insert_arr = array(
                'name' => $name,
                'email_cc' => $post_data['email_cc'],
                'subject' => $post_data['subject'],
                'content' => $post_data['content'],
                'email_from' => $post_data['email_from'],
                'headers' => implode(',',$this->data['headers']),
                'download_method' => $this->data['download_method'],
            );
            if (isset($id))
                $insert_arr['id'] = $id;
            $flg = $this->RateEmailTemplate->save($insert_arr);
            $function_name = __('Rate Email Template',true);
            if(isset($id))
                $action = __('modified',true);
            else
                $action = __('created',true);

            if ($flg === false) {
                if ( $this->_get('is_ajax') ) {
                    return 0;
                }else
                    $this->RateEmailTemplate->create_json_array('', 101, sprintf(__('The %s[%s] is %s Failed!', true), $function_name, $name, $action));
            }
            else
            {
                $saveID = isset($id) ? $id : $this->RateEmailTemplate->getLastInsertID();
                if ( $this->_get('is_ajax') ) {
                    return $saveID;
                }
                else
                    $this->RateEmailTemplate->create_json_array('', 201, sprintf(__('The %s[%s] is %s successfully!', true),$function_name,$name,$action));
            }
            $this->Session->write('m', RateEmailTemplate::set_validator());
            $this->redirect('index');
        }
        $sql = "select email,id from mail_sender order by email asc";
        $mail_sender = $this->RateEmailTemplate->query($sql);
        $this->set('mail_senders', $mail_sender);
        $this->loadModel('RateTable');
        $schema = $this->RateTable->default_schema;
        $options = array();
        $default_fields = array();
        foreach($schema as $field_name => $value){
            $options[$field_name] =  isset($value['name']) ?  Inflector::humanize($value['name']) :  Inflector::humanize($field_name);
            if(isset($value['default_fields']))
                $default_fields[] =  $field_name;
        }
        if($encode_id)
            $default_fields = explode(',',$data['RateEmailTemplate']['headers']);
        $show_options = array();
        foreach ($default_fields as $default_item)
        {
            $show_options[$default_item] = $options[$default_item];
            unset($options[$default_item]);
        }
        $show_options = array_merge($show_options,$options);
//        pr($options,$default_fields,$show_options);die;
        $this->set('schema',$show_options);
        $this->set('default_fields',$default_fields);
    }

    public function delete_template($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($encode_id);
        $flg = $this->RateEmailTemplate->del($id);
        if ($flg === false)
        {
            $this->RateEmailTemplate->create_json_array('', 101, __('Delete failed!', true));
        }
        else
        {
            $this->RateEmailTemplate->create_json_array('', 201, __('Delete successfully!', true));
        }
        $this->Session->write('m', RateEmailTemplate::set_validator());
        $this->redirect('index');
    }

    public function judge_template_name($name, $encode_id = '')
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        if (empty($name))
        {
            if (isset($this->params['form']['is_ajax']))
                echo 0;
            else
                return 0;
        }
        $conditions = array(
            'name' => $name
        );
        if ($encode_id)
        {
            $conditions[] = "id !=" . base64_decode($encode_id);
        }
        $data = $this->RateEmailTemplate->find('first', array('conditions' => $conditions));
        if ($data === false)
        {
            if (isset($this->params['form']['is_ajax']))
                echo 0;
            else
                return 0;
        }
        else
        {
            if (isset($this->params['form']['is_ajax']))
                echo 1;
            else
                return 1;
        }
    }

}
