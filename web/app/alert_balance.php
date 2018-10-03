<?php
error_reporting(E_STRICT);
date_default_timezone_set("Asia/Shanghai");//设定时区东八区
require_once('vendors/nmail/phpmailer.php');
include("vendors/nmail/class.smtp.php"); 
require_once("config/database.php");

function send_mail($client_name, $client_email, $client_company,$balance, $notify_balance, $row2) {
    $subject = "{$client_name} notification: minimum balance notification threshold";
    $content = <<<EOT
Dear Customer,
We would like to inform you that the balance in your settlement account is running low.
Please arrange payment or contact your account manager in order to avoid service interruptions.

  
        Account:    {$client_name}
          Owner:    {$client_company}
Current Balance:    {$balance}
 Allowed Credit:    {$notify_balance}     
EOT;
    $mail             = new PHPMailer(); //new一个PHPMailer对象出来
    $mail->CharSet ="UTF-8";                   //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();                           // 设定使用SMTP服务
    if ($row2['loginemail'] === "false")
    {
        $mail->IsMail();
    } else {
        $mail->IsSMTP();
    }
    $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = $row2['loginemail'] === "false" ?  false : true;                  // 启用 SMTP 验证功能
    //$mail->SMTPSecure = "ssl";                 // 安全协议
    $mail->Host       = $row2['smtphost'];       // SMTP 服务器
    $mail->Port       = intval($row2['smtpport']);                   // SMTP服务器的端口号
    $mail->Username   = $row2['username'];   // SMTP服务器用户名
    $mail->Password   = $row2['password'];              // SMTP服务器密码
    $mail->SetFrom($row2['from']);
    //$mail->AddReplyTo("sisl@mail.yht.com","webtest");
    $mail->Subject    = $subject;
    $mail->Body  =$content;
    $mail->AddAddress($client_email);
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return FALSE;
    } else {
        echo "Message sent!恭喜，邮件发送成功！";
        return TRUE;
    }

}


$class_dbconfig = new DATABASE_CONFIG();
$conn_config = $class_dbconfig->default;

$dbconn = pg_connect("host={$conn_config['host']} port={$conn_config['port']} dbname={$conn_config['database']} user={$conn_config['login']} password={$conn_config['password']}")
    or die('Could not connect: ' . pg_last_error());


$query = 'SELECT fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname,loginemail as "name" FROM system_parameter';
$result = pg_query($dbconn,$query);
$row2 = pg_fetch_assoc($result);
pg_free_result($result);

$sql =<<<EOT
SELECT 
client.client_id,
client.name,
client.email AS client_email,
client.company,
client.allowed_credit,
(SELECT system_admin_email FROM system_parameter) AS system_email,
c4_client_balance.balance,
client.notify_client_balance,
client.notify_admin_balance,
CASE 
     WHEN  client.notify_client_balance >= client_balance.balance::numeric 
     AND client.notify_admin_balance >= c4_client_balance.balance::numeric THEN 3
     WHEN
	client.notify_admin_balance >= c4_client_balance.balance::numeric THEN 2
      WHEN
	client.notify_client_balance >= client_balance.balance::numeric THEN 1
END AS type
FROM 
client 
LEFT JOIN c4_client_balance ON client.client_id::text = c4_client_balance.client_id
WHERE client.low_balance_notice = true AND mail_sended = 0 AND (client.notify_client_balance >= COALESCE(c4_client_balance.balance::numeric, 0) or client.notify_admin_balance >= COALESCE(c4_client_balance.balance::numeric, 0))
EOT;
$result = pg_query($dbconn,$sql);
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
    $client_id = $row['client_id'];
    $client_name = $row['name'];
    $client_email = $row['client_email'];
    $system_email = $row['system_email'];
    $client_company = $row['company'];
    $balance = $row['balance'];
    $notify_client_balance = $row['notify_client_balance'];
    $notify_admin_balance = $row['notify_admin_balance'];
    $allow_credit = round(abs($row['allowed_credit']), 3);
    $type = $row['type'];
    if($type == 1) {
        send_mail($client_name, $client_email, $client_company, $balance, $allow_credit,  $row2);
    } elseif($type == 2) {
        send_mail($client_name, $system_email, $client_company, $balance, $allow_credit,  $row2);
    } elseif($type == 3) {
        send_mail($client_name, $client_email, $client_company, $balance, $allow_credit,  $row2);
        send_mail($client_name, $system_email, $client_company, $balance, $allow_credit,  $row2);
    }
    $sqlt = "update client set mail_sended = 1 where client_id = {$client_id}";
    pg_query($dbconn, $sqlt);
}

pg_free_result($result);

pg_close($dbconn);

?>
