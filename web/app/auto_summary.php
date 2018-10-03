<?php

require_once("config/database.php");
require_once('vendors/nmail/phpmailer.php');

function send_mail($subject, $content, $to, $row2, $filename) {
    $mail             = new PHPMailer(); //new一个PHPMailer对象出来
    $mail->CharSet ="UTF-8";                   //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();                           // 设定使用SMTP服务
    $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    switch ($row2['smtp_secure']) {
        case 1:
//            $mail->SMTPSecure = 'tls';
            break;
        case 2:
            $mail->SMTPSecure = 'ssl';
            break;
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
    $mail->AddAddress($to);
    $mail->AddAttachment($filename);      // attachment 
    if(!$mail->Send()) {
        echo "Mailer Error:" . $mail->ErrorInfo;
        return FALSE;
    } else {
        echo "Message sent!";
        return TRUE;
    }

}

$class_dbconfig = new DATABASE_CONFIG();
$conn_config = $class_dbconfig->default;

$conn = pg_connect("host={$conn_config['host']} port={$conn_config['port']} dbname={$conn_config['database']} user={$conn_config['login']} password={$conn_config['password']}")
    or die('Could not connect: ' . pg_last_error());

$sql_template = "SELECT auto_summary_subject, auto_summary_content FROM mail_tmplate LIMIT 1";

$query  = pg_query($conn, $sql_template);

$template = pg_fetch_assoc($query);

pg_free_result($query);


$query_email = 'SELECT fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname as "name", smtp_secure FROM system_parameter';
$result_email = pg_query($conn,$query_email);
$row_email = pg_fetch_assoc($result_email);
pg_free_result($result_email);


$sql =<<<EOT
SELECT email, client_id,company,(CURRENT_TIMESTAMP(0) AT TIME ZONE (auto_send_zone::interval))::date - 1 as date,auto_send_zone
FROM client WHERE is_auto_summary = true
AND 
EXTRACT('hour' from  (CURRENT_TIMESTAMP(0) AT TIME ZONE (auto_send_zone::interval))::TIME) = 0
EOT;

$result = pg_query($conn, $sql);

$today_date = date("Ymd");

while($row = pg_fetch_assoc($result))
{
    $email_address = $row['email'];
    $client_id = $row['client_id'];
    $company = $row['company'];
    $date = $row['date'];
    $timezone = $row['auto_send_zone'];
    $sql1 = <<<EOT
SELECT
sum(call_duration::integer) as duration,
sum(ingress_client_bill_time::integer) as bill_time,
sum(ingress_client_cost::real) as call_cost,
sum(lnp_dipping_cost::real) as lnp_cost,
count(*) as total_calls,
count(case when call_duration > '0' then 1 else null end) as not_zero_calls,
count(case when egress_id != '' then 1 else null end) as success_calls,
count(case when release_cause = '15' then 1 else null end) as busy_calls,
count(case when lrn_number_vendor != '0' then 1 else null end) as lrn_calls,
sum(case when call_duration > '0' then pdd::integer else 0 end) as pdd,

count( case when binary_value_of_release_cause_from_protocol_stack like
'487%' then 1 else null end ) as cancel_calls

from client_cdr{$today_date} where is_final_call='1' and time between '{$date}
00:00:00 {$timezone}' and '{$date} 23:59:59 {$timezone}' and ingress_client_id = '{$client_id}'
EOT;
    $result1 = pg_query($conn, $sql1);
    $row1 = pg_fetch_assoc($result1);
    pg_free_result($result1);
    $sql1_1 = <<<EOT
SELECT sum(call_duration::integer) as duration, 
sum(egress_bill_time::integer) as bill_time, sum(egress_cost::numeric(10,4)) as call_cost, 
count(*) as total_calls, 
count(case when call_duration > '0' then 1 else null end) as not_zero_calls, count(case when egress_id != '' then 1 else null end) as success_calls,
count(case when release_cause_from_protocol_stack like '486%' then 1 else null end ) as busy_calls,
sum(case when call_duration > '0' then pdd::integer else 0 end) as pdd,
count( case when release_cause_from_protocol_stack like '487%' then 1 else null end ) as cancel_calls 
from client_cdr{$today_date} where time between '{$date}
00:00:00 {$timezone}' and '{$date} 23:59:59 {$timezone}' and egress_client_id = '{$client_id}'
EOT;
    $result1_1 = pg_query($conn, $sql1_1);
    $row1_1 = pg_fetch_assoc($result1_1);
    pg_free_result($result1_1);
    $item_total = array(
        'total_call_buy' => number_format($row1['total_calls']),
        'total_success_call_buy' => number_format($row1['success_calls']),
        'total_billed_min_buy' => number_format($row1['bill_time'] / 60, 2),
        'total_billed_amount_buy' => number_format($row1['call_cost'] + $row1['lnp_cost'], 5),
        'total_call_sell' => number_format($row1_1['total_calls']),
        'total_success_call_sell' => number_format($row1_1['success_calls']),
        'total_billed_min_sell' => number_format($row1_1['bill_time'] / 60, 2),
        'total_billed_amount_sell' => number_format($row1_1['call_cost'], 5),
        'customer_gmt' => $timezone,
        'start_time' => "'{$date} 00:00:00 {$timezone}'",
        'end_time' => "{$date} 23:59:59 {$timezone}",
    );
    $subject = str_replace(array_keys($item_total), 
                            array_values($item_total), $template['auto_summary_subject']); 
    $content = str_replace(array_keys($item_total), 
                            array_values($item_total), $template['auto_summary_content']); 
    
    $sql2 = <<<EOT
SELECT
orig_code_name,
route_prefix,
sum(call_duration::integer) as duration,
sum(ingress_client_bill_time::integer) as bill_time,
sum(ingress_client_cost::numeric(10,4)) as call_cost,
sum(lnp_dipping_cost::numeric(10,4)) as lnp_cost, count(*) as
total_calls, count(case when call_duration > '0' then 1 else null end)
as not_zero_calls, count(case when egress_id != '' then 1 else null end)
as success_calls,count(case when
binary_value_of_release_cause_from_protocol_stack like '486%' then 1
else null end) as busy_calls,count(case when lrn_number_vendor != '0'
then 1 else null end) as lrn_calls, sum(case when call_duration > '0'
then pdd::integer else 0 end) as pdd,count( case when
binary_value_of_release_cause_from_protocol_stack like '487%' then 1
else null end ) as cancel_calls
from client_cdr{$today_date} where is_final_call='1' and time between '{$date}
00:00:00 {$timezone}' and '{$date} 23:59:59 {$timezone}' and ingress_client_id = '{$client_id}' GROUP BY orig_code_name,route_prefix ORDER BY 1
EOT;
    $result2 = pg_query($conn, $sql2);
    
    $path = APP . 'webroot' . DS . 'upload' . DS . 'summary' .DS;
    
    $filename =  $company . '-' . $start_date . '-' . $end_date . ".xls";
    
    $real_path = $path . $filename;
    
    $fp = fopen($real_path, 'w');
    
    $list_1 = "";
    
    while($row2 = pg_fetch_assoc($result2)) {
        $prefix = $row2['route_prefix'];
        $code_name_group = $row2['orig_code_name'];
        $total_calls_group = $row2['total_calls'];
        $success_calls_group = $row2['success_calls'];
        $total_billed_min = number_format($row2['bill_time'] / 60, 2);
        $avg_rate_group = number_format($row2['bill_time'] == 0 ? 0 : $row2['call_cost'] / ($row2['bill_time'] / 60), 5);
        $cost_group = number_format($row2['call_cost'], 5);
        $list_1 .=<<<EOT
        <tr>
            <td>{$prefix}</td>
            <td>{$code_name_group}</td>
            <td>{$total_calls_group}</td>
            <td>{$success_calls_group}</td>
            <td>{$total_billed_min}</td>
            <td>{$avg_rate_group}</td>
            <td>{$cost_group}</td>
        </tr>
EOT;
    }
    
    pg_free_result($result2);
    
    $table1 =<<<EOT
    <table>
        <thead>
            <tr>
                <th colspan="7">Buy</th>
            </tr>
            <tr>
                <th>Code Name</th>
                <th>Prefix</th>
                <th>Total Call</th>
                <th>Succ Call</th>
                <th>Bill Minute</th>
                <th>Avg Rate</th>
                <th>Cost</th>
            </tr>
        </thead>
        <tbody>
            {$list_1}
        <tbody>
    </table>
EOT;
    fwrite($fp, $table1);
    
    $sql3 = "SELECT term_code_name, sum(call_duration::integer) as duration, 
        sum(egress_bill_time::integer) as bill_time, 
        sum(egress_cost::numeric(10,4)) as call_cost, 
        count(*) as total_calls, count(case when call_duration > '0' then 1 else null end) as not_zero_calls, 
        count(case when egress_id != '' then 1 else null end) as success_calls,
        count(case when release_cause_from_protocol_stack like '486%' then 1 else null end ) as busy_calls,
    sum(case when call_duration > '0' then pdd::integer else 0 end) as pdd,count( case when release_cause_from_protocol_stack like '487%' then 1 else null end ) 
    as cancel_calls 
    from client_cdr 
        where time between '{$date} 00:00:00 {$timezone}' and '{$date} 23:59:59 {$timezone}' and egress_client_id = '{$client_id}' GROUP BY term_code_name ORDER BY 1";
    
    $result3 = pg_query($conn, $sql3);
    
    $list_2 = "";
    
    while($row3 = pg_fetch_assoc($result3)) {
        $code_name_group = $row2['term_code_name'];
        $total_calls_group = $row2['total_calls'];
        $success_calls_group = $row2['success_calls'];
        $total_billed_min = number_format($row2['bill_time'] / 60, 2);
        $avg_rate_group = number_format($row2['bill_time'] == 0 ? 0 : $row2['call_cost'] / ($row2['bill_time'] / 60), 5);
        $cost_group = number_format($row2['call_cost'], 5);
        $list_2 .=<<<EOT
        <tr>
            <td>{$code_name_group}</td>
            <td>{$total_calls_group}</td>
            <td>{$success_calls_group}</td>
            <td>{$total_billed_min}</td>
            <td>{$avg_rate_group}</td>
            <td>{$cost_group}</td>
        </tr>
EOT;
    }
    
    $table2 =<<<EOT
    <table>
        <thead>
            <tr>
                <th colspan="6">Sell</th>
            </tr>
            <tr>
                <th>Code Name</th>
                <th>Total Call</th>
                <th>Succ Call</th>
                <th>Bill Minute</th>
                <th>Avg Rate</th>
                <th>Cost</th>
            </tr>
        </thead>
        <tbody>
            {$list_2}
        <tbody>
    </table>
EOT;
    fwrite($fp, $table2);
    fclose($fp);
    send_mail($subject, $content, $email_address, $row_email, $real_path);
}


pg_close($conn)



?>