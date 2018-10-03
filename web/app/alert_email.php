<?php
error_reporting(E_STRICT);
require_once('vendors/nmail/phpmailer.php');
require_once("config/database.php");

function send_mail($subject, $content, $to_arr, $row2, $filename) {
    $mail             = new PHPMailer(); //new一个PHPMailer对象出来
    $mail->CharSet ="UTF-8";                   //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    if ($row['loginemail'] === 'false')
    {
        $mail->IsMail();
        } else {
         $mail->IsSMTP(); // telling the class to use SMTP   
         }
    $mail->SMTPDebug  = 2;                     // 启用SMTP调试功能
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = $row['loginemail'] === 'false' ? false : true;                  // 启用 SMTP 验证功能
    switch ($row2['smtp_secure']) {
        case 1:
//            $mail->SMTPSecure = 'tls';
            break;
        case 2:
            $mail->SMTPSecure = 'ssl';
            break; 
        case 3:
            $mail->AuthType = 'NTLM';
            $mail->Realm = $row2['realm'];
            $mail->Workstation = $row2['workstation'];
    }
    //$mail->SMTPSecure = "ssl";                 // 安全协议
    $mail->Host       = $row2['smtphost'];       // SMTP 服务器
    $mail->Port       = intval($row2['smtpport']);                   // SMTP服务器的端口号
    $mail->Username   = $row2['username'];   // SMTP服务器用户名
    $mail->Password   = $row2['password'];              // SMTP服务器密码
    $mail->SetFrom($row2['from']);
    //$mail->AddReplyTo("sisl@mail.yht.com","webtest");
    $mail->Subject    = $subject;
    $mail->Body  =$content;
    foreach($to_arr as $to) {
        $mail->AddAddress($to);
    }
    $mail->AddAttachment($filename);      // attachment 
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return FALSE;
    } else {
        echo "Message sent!恭喜，邮件发送成功！";
        return TRUE;
    }

}



function parse_content($str) {
    /*
        $content = parse_content('name=hewenxiang;age=23');
    */
    $return = array();
    $arr_1 = explode(';', $str);
    foreach($arr_1 as $item) {
        $arr_2 = explode('!', $item);
        $return[$arr_2[0]] = $arr_2[1];
    }
    return $return;
}

$class_dbconfig = new DATABASE_CONFIG();
$conn_config = $class_dbconfig->default;

$dbconn = pg_connect("host={$conn_config['host']} port={$conn_config['port']} dbname={$conn_config['database']} user={$conn_config['login']} password={$conn_config['password']}")
    or die('Could not connect: ' . pg_last_error());

$query = "SELECT alert_email_subject,alert_email_content FROM mail_tmplate";
$result = pg_query($dbconn,$query);
$row1 = pg_fetch_row($result);
$alert_email_subject = $row1[0]; // 邮件模板标题
$alert_email_content = $row1[1]; // 邮件模板内容
pg_free_result($result);


$query = 'SELECT fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation, loginemail FROM system_parameter';
$result = pg_query($dbconn,$query);
$row2 = pg_fetch_assoc($result);
pg_free_result($result);



$query =<<<EOT
SELECT 
        alert_event.id,
	alert_event.mail_subject AS subject,
	alert_event.mail_content AS content,
	alert_action.email_to_noc,
	alert_action.email_to_carrier,
	client.noc_email AS client_noc_email,
	system_parameter.noc_email AS sys_noc_email
FROM 
	alert_event
JOIN
	alert_action
ON
	alert_event.alert_action_id = alert_action.id
JOIN
	resource
ON
	alert_event.res_id = resource.resource_id
JOIN
	client
ON
	resource.client_id = client.client_id
JOIN
	system_parameter
ON
	true
WHERE 
	alert_event.event_type = 8 
        AND
	alert_event.mail_sended = false
EOT;

$result = pg_query($dbconn,$query) or die("Query failed: " . pg_last_error());



while($line = pg_fetch_array($result, null, PGSQL_ASSOC))
{
    $id = $line['id'];
    $subject = parse_content($line['subject']);
    $content = parse_content($line['content']);
    $email_to_noc = $line['email_to_noc'];
    $email_to_carrier = $line['email_to_carrier'];
    $client_noc_email = $line['client_noc_email'];
    $sys_noc_email = $line['sys_noc_email'];
    $filename = $content['{Cdr}'];
    //$cdr_query = 'COPY(' . $content['{Cdr}'] . ") TO '/tmp/exports/{$filename}' WITH DELIMITER AS ',' CSV HEADER";
    //pg_query($dbconn, $cdr_query);
    
    $subjects = str_replace(array_keys($subject), array_values($subject), $alert_email_subject);
    $contents = str_replace(array_keys($content), array_values($content), $alert_email_content);
    
    $subjects = preg_replace("/\{.*\}/", '', $subjects);
    $contents = preg_replace("/\{.*\}/", '', $contents);
    
    $to_arr = array();
    
    if($email_to_noc == 't') {
        array_push($to_arr, $sys_noc_email);
    }
    
    if($email_to_carrier == 't') {
        array_push($to_arr, $client_noc_email);
    }
    
    if(count($to_arr))
    {
        send_mail($subjects, $contents, $to_arr,$row2, $filename);
    }
    
    
    $query = "UPDATE alert_event SET mail_sended = true WHERE id = {$id}";
    pg_query($dbconn, $query);
    
}


pg_free_result($result);

pg_close($dbconn);


?>
