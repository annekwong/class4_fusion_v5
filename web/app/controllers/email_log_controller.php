<?php

class EmailLogController extends AppController
{

    var $name = "EmailLog";
    var $helpers = array('Javascript', 'Html', 'Text');
    var $components = array('RequestHandler');
    var $uses = array('EmailLog');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
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
        //pr($_SESSION);
        $this->pageTitle = 'Daily Email Delivery';
        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");

        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
            $end_time = $_GET['end_time'];
        }

        $conditions = array(
            'EmailLog.send_time BETWEEN ? and ?' => array($start_time, $end_time)
        );

        if (isset($_GET['client']) && !empty($_GET['client']))
        {
            $conditions["EmailLog.client_id"] = $_GET['client'];
        }

        if (isset($_GET['type']) && !empty($_GET['type']))
        {
            $conditions["EmailLog.type"] = $_GET['type'];
        }
        $email = "";
        if (isset($_GET['email']) && !empty($_GET['email']))
        {
            $email = $_GET['email'];
            $conditions["EmailLog.email_addresses like"] = "%".$_GET['email']."%";
        }

        $order_arr = array('EmailLog.id' => 'desc');
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

        $size = 100;
        if (isset($_GET['size']))
        {
            $size = $_GET['size'];
        }
        $this->paginate = array(
            'fields' => array(
                'EmailLog.id','EmailLog.send_time', 'EmailLog.email_addresses', 'EmailLog.files',
                'EmailLog.type','EmailLog.status','EmailLog.error', 'client.name','EmailLog.client_id'
            ),
            'limit' => $size,
            'joins' => array(
                array(
                    'table' => 'client',
                    'type' => 'left',
                    'conditions' => array(
                        'EmailLog.client_id = client.client_id'
                    ),
                )
            ),
            'order' => $order_arr,
            'conditions' => $conditions
        );
        $types = $this->EmailLog->get_email_log_type();
        $this->data = $this->paginate('EmailLog');
        $this->set('types', $types);
        $this->set('clients', $this->EmailLog->get_carriers());
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('email', $email);
    }

    public function get_file($file)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $file_path = base64_decode($file);
        $basename = basename($file_path);
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$basename}");
        header("Content-Description: CDR Record");
        readfile($file_path);
    }


    //重发邮件
    public function resend_email($id){
        $id = intval($id);


        //邮件信息
        $email_arr = $this->EmailLog->find('first',array('fields'=>'resend_email','conditions'=>array('id'=> $id)));
        if (!$id || empty($email_arr)){
            $this->EmailLog->create_json_array('',101,__('E-mail log is incorrect',true));
            $this->Session->write("m", EmailLog::set_validator());
            $this->redirect("/email_log");
        }

        $email_arr = $email_arr['EmailLog']['resend_email'];
        $email_arr = unserialize($email_arr);

        $email_info = $email_arr['send_email_info'];
        $email = $email_arr['send_email'];
        $tmpl_cc = $email_arr['send_cc'];
        $subject = $email_arr['send_subject'];
        $content = $email_arr['send_content'];
        $content = htmlspecialchars_decode($content);
        $attachment = $email_arr['send_attachment'];

        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer();
        if ($email_info[0][0]['loginemail'] === 'false')
        {
            $mailer->IsMail();
        }
        else
        {
            $mailer->IsSMTP();
        }
        $mailer->SMTPDebug = 2;
        $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
        switch ($email_info[0][0]['smtp_secure'])
        {
            case 1:
                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = $email_info[0][0]['realm'];
                $mailer->Workstation = $email_info[0][0]['workstation'];
        }
        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtphost'];
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        $mailer->CharSet = "UTF-8";

        $addresses = explode(';', $email);
        foreach ($addresses as $adress)
        {
            $mailer->AddAddress($adress);
        }

        

        if ($tmpl_cc != '')
        {

            $tml_ccs = explode(';', $tmpl_cc);
            foreach ($tml_ccs as $tml_cc)
            {
                $mailer->AddCC($tml_cc);
            }
        }

        $mailer->ClearAttachments();

        if(!empty($attachment[0])){
            $mailer->AddAttachment($attachment[0], $attachment[1]);
        }

        $mailer->IsHTML(true);


        $mailer->Subject = $subject;
        $mailer->Body = $content;

        if ($mailer->Send())
        {echo 1;
            $current_datetime = date("Y-m-d H:i:s");
            $sql = "update email_log set send_time = '{$current_datetime}', status = 0,error = ''  where id = {$id}";
            $this->EmailLog->query($sql);
            $this->EmailLog->create_json_array('', 201, __('Resend e-mail successfully', true));
        }
        else
        {echo 2;
            $mail_error = $mailer->ErrorInfo;
            $current_datetime = date("Y-m-d H:i:s");
            $sql = "update email_log set send_time = '{$current_datetime}', error = '{$mail_error}' where id = {$id}";
            $this->EmailLog->query($sql);
            $this->EmailLog->create_json_array('', 101,$mail_error);
        }
        $this->Session->write("m", EmailLog::set_validator());
        $this->redirect("/email_log");






    }

}
