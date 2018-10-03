<?php

class MailtmpsexchangeController extends AppController {

    var $uses = array('Mailtmp');
    function index() { 
        $this->redirect('mail');
    }

    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    public function mail() { 
        //Configure::write('debug', 2);
        $this->pageTitle = "Switch/Exchange Mail Template";
        if (!empty($this->params['form'])) {
            if (!$_SESSION['role_menu']['Configuration']['mailtmps']['model_w']) {
                $this->redirect_denied();
            }
            //$this->Mailtmp->query("delete from mail_tmplate");

            //pr($this->params['form']);
            //exit();
            if ($this->Mailtmp->save($this->params['form'])) {
                $this->Mailtmp->create_json_array('', 201, __('configmailtmpsuc', true));
            } else {
                $this->Mailtmp->create_json_array('', 101, __('configmailtmpfail', true));
            }
            $this->set('m', Mailtmp::set_validator());
        }
        
        $mail_senders = $this->Mailtmp->get_mail_senders();
        $this->set('mail_senders', $mail_senders);
        
        $this->set('tmp', $this->Mailtmp->query("select * from mail_tmplate"));
        
    }

}

?>