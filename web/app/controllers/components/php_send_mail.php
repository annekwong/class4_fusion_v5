<?php
App::import('Vendor','mail/sendmail',array('file' => 'mail/sendmail.php'));
class PhpSendMailComponent extends Object {
	//初使化邮件发送
	function _init_mail($title,$msgtext){
		$smtp_settings = Configure::read('smtp_settings');
		$mailer=new phpmailer();
		if($smtp_settings['sendmailtype']==1)//smtp
		{
			$mailer->IsSMTP();
			$mailer->Host=$smtp_settings['smtphost'];
			//端口
			if($smtp_settings['smtpport'])
			{
				$mailer->Port=$smtp_settings['smtpport'];
			}
			//SMTP服务器需要认证
			if($smtp_settings['loginemail'])
			{
				$mailer->SMTPAuth=true;
				$mailer->Username=$smtp_settings['emailusername'];
				$mailer->Password=$smtp_settings['emailpassword'];
			}
		}
		else//mail函数
		{
			$mailer->IsMail();
		}
		$mailer->From=$smtp_settings['fromemail'];
		$mailer->FromName=$smtp_settings['emailname'];
		$mailer->IsHTML(true);
		$mailer->Subject=stripSlashes($title);//标题
		$mailer->Body=stripSlashes(nl2br(RepFieldtextNbsp($msgtext)));//内容
		$mailer->CharSet = isset($smtp_settings['charset']) ? $smtp_settings['charset'] : 'utf8';
		return $mailer;
	}
	
	//发送邮件
	function send_mail($email,$title,$text,$attachments=array()){
		$mailer=$this->_init_mail($title,$text);
		if(is_array($email)){
			$count=count($email);
			for($i=0;$i<$count;$i++)
			{
				if($email[$i])
				{
					$mailer->AddAddress($email[$i]);
				}
			}
		}else{
			$mailer->AddAddress($email);
		}
		if(!empty($attachments)){
			if(is_array($attachments)){
				foreach($attachments as $attchment){
					$mailer->AddAttachment($attchment,basename($attchment));
				}
			}else{
				$mailer->AddAttachment($attachments,basename($attachments));
			}
		}
		if(!$mailer->Send())
		{
			return false;
		}
		return true;
	}
	
}
?>