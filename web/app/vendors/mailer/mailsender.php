<?php

class VendorMailSender
{
    private $Systemparam;

    public function __construct()
    {
        App::import('Model', 'Systemparam');

        $this->Systemparam = new Systemparam;
    }

    public function send($subject, $body, $to, $cc = null, $from = null, $attachments = null, $mailLogId = null)
    {
        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer(true);

        $email_info = $this->Systemparam->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');

        if ($from && strcasecmp($from, 'Default') !== 0) {
            $email_info = $this->Systemparam->query('SELECT email as "from", smtp_host as smtphost, smtp_port as smtpport, username,loginemail, password, name, secure as "smtp_secure", NULL as "realm", NULL as "workstation" FROM mail_sender WHERE id = ' . $from);
        }
        $response = array();
        try {
            if ($email_info[0][0]['loginemail'] === 'false')
            {
                $mailer->IsMail();
            }
            else
            {
                $mailer->IsSMTP();
            }
            $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
            switch ($email_info[0][0]['smtp_secure'])
            {
                case 1:
//                    $mailer->SMTPSecure = 'tls';
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
            $mailer->IsHTML(true);
            $mailer->SMTPDebug = 1;
            $mailer->From = $email_info[0][0]['from'];
            $mailer->FromName = $email_info[0][0]['name'];
            $mailer->Host = $email_info[0][0]['smtphost'];
            $mailer->Port = intval($email_info[0][0]['smtpport']);
            $mailer->Username = $email_info[0][0]['username'];
            $mailer->Password = $email_info[0][0]['password'];

            if (strpos($mailer->Host, 'denovolab.com') !== false) {
                $mailer->Helo = 'demo.denovolab.com';
            }

            $toMails = explode(';', trim($to));

            foreach ($toMails as $toMail) {
                if (trim($toMail)) {
                    $mailer->AddAddress(trim($toMail));
                }
            }

            if ($cc) {
                $ccMails = explode(';', trim($cc));

                foreach ($ccMails as $ccMail) {
                    if (trim($ccMail)) {
                        $mailer->AddCC(trim($ccMail));
                    }
                }
            }

            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $mailer->AddAttachment($attachment);
                }
            }
            $mailer->SetLanguage( 'en', 'phpmailer/language/' );
            $mailer->Subject = $subject;
            $mailer->Body = $body;
            $mailer->Send();
            $response['status'] = 0;
            $response['error'] = '';
        } catch (phpmailerException $e) {
            $response['status'] = 1;
            $response['error'] = $e->errorMessage();
        } catch (Exception $e) {
            $response['status'] = 1;
            $response['error'] = $e->getMessage();
        }

        // Update log status
        if ($mailLogId) {
            $this->Systemparam->query("UPDATE email_log set status = {$response['status']}, error = '{$response['error']}' WHERE id = {$mailLogId}");
        }

        return $response;
    }
}