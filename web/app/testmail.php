<?php

require_once("config/database.php");
require_once('vendors/nmail/phpmailer.php');

$class_dbconfig = new DATABASE_CONFIG();

$conn_config = $class_dbconfig->default;

$dbconn = pg_connect("host={$conn_config['host']} port={$conn_config['port']} dbname={$conn_config['database']} user={$conn_config['login']} password={$conn_config['password']}")
    or die('Could not connect: ' . pg_last_error());

$sql = 'SELECT fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname as "name",smtp_secure,realm,workstation,loginemail FROM system_parameter';

$result = pg_query($dbconn, $sql);

$row = pg_fetch_assoc($result);

pg_close($dbconn);

$host = $row['smtphost'];
$port = $row['smtpport'];
$username = $row['username'];
$from = $row['from'];
$password = $row['password'];
$name = $row['name'];
$smtp_secure = $row['smtp_secure'];
$realm = $row['realm'];
$workstation = $row['workstation'];
$is_smtp_auth = $row['loginemail'];
$toAdress = $argv[1];

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
  
  $mail->AddReplyTo($from, $from);
  $mail->AddAddress($toAdress, $toAdress);
  $mail->SetFrom($from, $from);
  $mail->Subject = 'For testing';
  $mail->AltBody = 'For testing'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML("For testing");
  $mail->Send();
  echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
?>

?>
