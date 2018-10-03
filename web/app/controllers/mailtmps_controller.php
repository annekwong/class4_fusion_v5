<?php

class MailtmpsController extends AppController
{
    var $components = array('RequestHandler');
    function index()
    {
        $this->redirect('mail');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    public function mail()
    {
//        $tmpRes = $this->Mailtmp->query("ALTER TABLE mail_tmplate ADD COLUMN zerobalance_from text");
//        $tmpRes = $this->Mailtmp->query("ALTER TABLE mail_tmplate ADD COLUMN zerobalance_cc text");
//        $tmpRes = $this->Mailtmp->query("ALTER TABLE mail_tmplate ADD COLUMN zerobalance_subject text");
//        $tmpRes = $this->Mailtmp->query("ALTER TABLE mail_tmplate ADD COLUMN zerobalance_content text");

        $this->pageTitle = "Switch/Mail Template";
        if ($this->RequestHandler->isPost())
        {
            if (!$_SESSION['role_menu']['Configuration']['mailtmps']['model_w'])
            {
                $this->redirect_denied();
            }
            if ($this->Mailtmp->save($this->data))
                $this->Mailtmp->create_json_array('', 201, __('configmailtmpsuc', true));
            else
                $this->Mailtmp->create_json_array('', 101, __('configmailtmpfail', true));
            $this->set('m', Mailtmp::set_validator());
        }
        $this->set('mail_senders', $this->Mailtmp->find_mail_senders());

        $this->set('mail_template_arr',$this->Mailtmp->get_mail_template_arr());
        $mail_data = $this->Mailtmp->query("select * from mail_tmplate limit 1");

        $this->data = $mail_data[0][0];
    }

    public function ajax_judge_invoice_mailtmp()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->Mailtmp->query("select COUNT(*) AS sum from mail_tmplate where"
                . " invoice_from ='' OR invoice_cc = '' OR invoice_subject = '' OR invoice_content = '' ");

        if ($data[0][0]['sum'])
        {
            //需要添加
            echo json_encode(array('flg' => 1));
        }
        else
        {
            echo json_encode(array('flg' => 2));
        }
    }

    public function ajax_get_invoice_mailtmp()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $mail_senders = $this->Mailtmp->get_mail_senders();
        $this->set('mail_senders', $mail_senders);
        $data = $this->Mailtmp->query("select invoice_from,invoice_cc,invoice_subject,invoice_content from mail_tmplate");
        $this->set('tmp', $data);
        $this->jsonResponse(['status' => true, 'data' => $this->render('ajax_get_invoice_mailtmp')]);
    }

    public function ajax_save_invoice_mailtmp()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->params['form'];
        $mail_tmplate_id = $this->Mailtmp->query("select id from mail_tmplate limit 1");
        $update_arr = array(
            'Mailtmp' => array(
                'id' => $mail_tmplate_id[0][0]['id'],
                'invoice_from' => $data['invoice_from'],
                'invoice_cc' => $data['invoice_cc'],
                'invoice_subject' => $data['invoice_subject'],
                'invoice_content' => $data['invoice_content'],
            ),
        );
        $flg = $this->Mailtmp->save($update_arr);
        if ($flg === false)
        {
            echo 1; //失败
        }
        else
        {
            echo 2; //成功
        }
    }


    public function ajax_save_single_template()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->Mailtmp->find('first',array('fields' => 'id'));
        $this->data['id'] = $data['Mailtmp']['id'];
        if ($this->Mailtmp->save($this->data) === false)
            echo 0;
        else
            echo 1;
    }
}

?>