<?php

class MailSenderController extends AppController 
{
    var $name = "MailSender"; 
    var $helpers = array('Javascript','Html', 'Text'); 
    var $components = array('RequestHandler');  
    var $uses = array('MailSender');
    
    public function beforeFilter() 
    {
        $this->checkSession("login_type"); //核查用户身份
        
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }
    
    
    public function index()
    {
        $this->paginate = array(
            'limit' => isset($_GET['size']) ? $_GET['size'] : 100,
            'order' => array(
                'MailSender.id' => 'desc',
            ),
        );
        
        $this->data = $this->paginate('MailSender');
        $this->set('secures', array(0 => '', 1 => 'TLS', 2 => 'SSL', 3 => 'NTLM'));
    }
    
    public function modify_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            //last_modified_on,modified_by
            $now_time = date('Y-m-d H:i:s');
            $user = $this->Session->read('sst_user_name');
            $this->data['MailSender']['last_modified_on'] = $now_time;
            $this->data['MailSender']['modified_by'] = $user;

            $where_arr = array();
            foreach ($this->data['MailSender'] as $key => $value)
            {
                if(strcmp($key,'secure'))
                {
                    $where_arr[] = $key . "=" . "'{$value}'";
                }
                else
                {
                    $where_arr[] = $key . "=" . "{$value}";
                }
            }
            $where = implode(' AND ', $where_arr);


//            $where .= " and last_modified_on = '{$now_time}' and modified_by = '{$user}' ";
//exit($where);
            $where_id = intval($id);
            $judge_sql = "SELECT count(*) as sum FROM mail_sender WHERE {$where} AND id != {$where_id}";
            
            $result = $this->MailSender->query($judge_sql);
            
            if($result[0][0]['sum'])
            {
                $this->Session->write('m', $this->MailSender->create_json(101, __('The Mail Sender record already exists!', true)));
                $this->xredirect("/mail_sender/index/");
            }
	    $notUnique = 0;
            if($id != null)
            {
                $this->data['MailSender']['id'] = $id;
                $this->Session->write('m', $this->MailSender->create_json(201, __('The Mail Sender [%s] is modified successfully!', true, $this->data['MailSender']['name'])));
            }
            else
            {
		$notUnique = $this->MailSender->find('count', Array('conditions' => Array('name' => $this->data['MailSender']['name'])));
		if ($notUnique) {
			$this->Session->write('m', $this->MailSender->create_json(101, __('The Mail Sender [%s] is already in use!', true, $this->data['MailSender']['name'])));
		} else {
			$this->Session->write('m', $this->MailSender->create_json(201, __('The Mail Sender [%s] is created successfully!', true, $this->data['MailSender']['name'])));
		}
            }
	    if (!$notUnique) {
	    	$this->MailSender->save($this->data);
	    }
            $this->xredirect("/mail_sender/index/");
        }
        $this->data = $this->MailSender->find('first', Array('conditions' => Array('id' => $id)));
    }
    
    public function delete($id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($id);
        $mail_sender = $this->MailSender->findById($id);
        $this->MailSender->del($id);
        
        $this->Session->write('m', $this->MailSender->create_json(201, __('The Mail Sender [%s] is deleted successfully!', true, $mail_sender['MailSender']['name'])));
        $this->xredirect("/mail_sender/index/");
    }

    public function sendTestEmail()
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost() && isset($_POST['serverId'])) {

            $hostData = $this->MailSender->find('first', array(
                'conditions' => array(
                    'id' => $_POST['serverId']
                )
            ));
            App::import('Vendor', 'nmail/phpmailer');
            try {
                $mailer = new phpmailer(true);

                if ($hostData['MailSender']['loginemail'] === 'false') {
                    $mailer->IsMail();
                } else {
                    $mailer->IsSMTP();
                }

                $mailer->SMTPAuth = $hostData['MailSender']['loginemail'] === 'false' ? false : true;
                $mailer->IsHTML(true);

                switch ($hostData['MailSender']['secure']) {
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

                $mailer->SMTPDebug = 1;
                $mailer->From = $hostData['MailSender']['email'];
                $mailer->FromName = $hostData['MailSender']['name'];
                $mailer->Host = $hostData['MailSender']['smtp_host'];
                $mailer->Port = intval($hostData['MailSender']['smtp_port']);
                $mailer->Username = $hostData['MailSender']['username'];
                $mailer->Password = $hostData['MailSender']['password'];
                $mailer->AddAddress($_POST['email']);
                $mailer->Subject = "Test message";
                $mailer->Body = 'Hello, this smtp server is verified!';
                
                if (strpos($hostData['MailSender']['smtp_host'], 'denovolab.com') !== false) {
                    $mailer->Helo = 'demo.denovolab.com';
                }

                $mailer->Send();
                echo '1';
            } catch (phpmailerException $e) {
                echo $e->errorMessage();
            }
            exit;
        }
    }
}

