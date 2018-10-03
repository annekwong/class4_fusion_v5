<?php

class CodeBasedReportEmailShell extends Shell
{

    var $uses = array('CodeBasedReportLog', 'CodeBasedReportLogStatus');

    function main()
    {
        Configure::load('myconf');
        $exportFolder = Configure::read('database_export_path') . '/cbr_report/';

        $emailSendTo = isset($this->args[0]) ? trim($this->args[0]) : '';
        $reportId = isset($this->args[1]) ? (int) $this->args[1] : 0;

        if (!empty($emailSendTo) && $reportId) {

            $emailData = $this->CodeBasedReportLog->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname as "name",smtp_secure,realm,workstation,loginemail FROM system_parameter');
            $host = $emailData[0][0]['smtphost'];
            $port = $emailData[0][0]['smtpport'];
            $username = $emailData[0][0]['username'];
            $from = $emailData[0][0]['from'];
            $password = $emailData[0][0]['password'];
            $name = $emailData[0][0]['name'];
            $smtp_secure = $emailData[0][0]['smtp_secure'];
            $realm = $emailData[0][0]['realm'];
            $workstation = $emailData[0][0]['workstation'];
            $is_smtp_auth = $emailData[0][0]['loginemail'];

            require_once(APP . 'vendors/nmail/phpmailer.php');

            $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
            if ($is_smtp_auth === 'false')
            {
                $mail->IsMail();
            } else {
                $mail->IsSMTP(); // telling the class to use SMTP
            }

            try {
                $mail->Host        = $host; // SMTP server
                $mail->SMTPDebug   = 2;                     // enables SMTP debug information (for testing)
                $mail->SMTPAuth    = $is_smtp_auth === 'false' ? false : true;                  // enable SMTP authentication
                $mail->Port        = $port;                    // set the SMTP port for the GMAIL server
                $mail->Username    = $username; // SMTP account username
                $mail->Password    = $password;        // SMTP account password

                switch ($smtp_secure) {
                    case 1:
//        $mail->SMTPSecure = 'tls';
                        break;
                    case 2:
                        $mail->SMTPSecure = 'ssl';
                        break;
                    case 3:
                        $mail->AuthType    = "NTLM";
                        $mail->Realm       = $realm;
                        $mail->Workstation = $workstation;
                }

                $data = $this->CodeBasedReportLog->find('first', array(
                        'fields' => array(
                            'CodeBasedReportLog.id',
                            'CodeBasedReportLog.file_name',
                        ),
                        'conditions' => array(
                            'CodeBasedReportLog.id' => $reportId
                        ))
                );

                $filename = $data['CodeBasedReportLog']['file_name'];

                $mail->AddReplyTo($from, $from);
                $mail->AddAddress($emailSendTo, $emailSendTo);
                $mail->AddAttachment($exportFolder . $filename, $filename);

                $mail->SetFrom($from, $from);
                $mail->Subject = 'Code Based Report';
                $mail->AltBody = 'Code Based Report'; // optional - MsgHTML will create an alternate automatically
                $mail->MsgHTML("Code Based Report");
                $mail->Send();
                echo "Message Sent OK\n";
            } catch (phpmailerException $e) {
                echo $e->errorMessage(); //Pretty error messages from PHPMailer
            } catch (Exception $e) {
                echo $e->getMessage(); //Boring error messages from anything else!
            }
        } else {
            echo "Invalid Input Parameters";
            die();
        }
    }

}
