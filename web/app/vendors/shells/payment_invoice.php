<?php

class PaymentInvoiceShell extends Shell
{

    var $uses = array('ImportExportLog', 'Transaction','Invoice');

    function main()
    {
        $log_id = (int) $this->args[0];
        $sql = "SELECT users.name,import_export_logs.* FROM import_export_logs left join users on users.user_id = import_export_logs.user_id WHERE id = {$log_id}";
        $data_info = $this->ImportExportLog->query($sql);
        $update_by = $data_info[0][0]['name'];
        $data_ext_arr = unserialize($data_info[0][0]['ext_attributes']);
        $upload_file = $data_info[0][0]['file_path'];
        $file = fopen($upload_file, 'r');
        $goods_list = array();
        while ($data = fgetcsv($file))
        {
            if (!trim($data)){
                continue;
            }
            $goods_list[] = $data;
        }
        $header_arr = $goods_list[0];
        unset($goods_list[0]);
        sort($goods_list);
        $data_arr = array();
        //$export_log = new ImportExportLog();
        //$this->loadModel('ImportExportLog');
        $type = $data_ext_arr['save_ext_attributes']['upload_type']; // 1: sent payment;2:received payment  3:recived invoice 
        if ($type == '3')
        {
            sleep(1);
            $schema = $this->requestAction('/down/get_schema_invoice');
            foreach ($header_arr as $key => $items)
            {
                if (!array_key_exists($items, $schema))
                {
                    $data ['ImportExportLog']['id'] = $log_id;
                    $data ['ImportExportLog']['finished_time'] = gmtnow();
                    $data ['ImportExportLog']['status'] = '-4';
                    sleep(1);
                    $this->ImportExportLog->save($data);
                    exit;
                }
                foreach ($goods_list as $key2 => $item)
                {
                    $data_arr[$key2][$schema[$items]] = $item[$key];
                }
            }
            $error_sum = 0;
            $total_num = count($data_arr);
//            echo "<pre>";
//                        print_r($data_arr);die;
            foreach ($data_arr as $data_item)
            {
                $client_info = $this->ImportExportLog->query("select client_id from client where name = '{$data_item['name']}'");
                if (!$client_info)
                {//所填运营商不存在 需加入log  error +1  
                    $error_sum ++;
                    $error_file_path = $data_info[0][0]['error_file_path'];
                    $error_content = "Carrier {$data_item['name']} is not exsit!\t\n";
                    file_put_contents($error_file_path, $error_content, FILE_APPEND);
                    continue;
                }
                $client_id = $client_info[0][0]['client_id'];
                $invoice_number = $data_item['invoice_number'];
                if (empty($invoice_number))
                {
                    $sql = "SELECT nextval('class4_seq_invoice_no'::regclass) AS next_number";
                    $invoice_number_result = $this->Invoice->query($sql);
                    $invoice_number = $invoice_number_result[0][0]['next_number'];
                    $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
                    $invoice_number_result = $this->Invoice->query($sql);
                    if ($invoice_number_result[0][0]['count'] > 0)
                    {
                        $sql = "SELECT setval('class4_seq_invoice_no', (select max(invoice_number::bigint)+1 from invoice)) as next_number";
                        $invoice_number_result = $this->Invoice->query($sql);
                        $invoice_number = $invoice_number_result[0][0]['next_number'];
                    }
                }
                else
                {
                    $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
                    $invoice_number_result = $this->Invoice->query($sql);
                    if ($invoice_number_result[0][0]['count'] > 0)
                    {
                        $error_sum ++;
                        $error_file_path = $data_info[0][0]['error_file_path'];
                        $error_content = "Invoice number[{$data_item['invoice_number']}] duplicate!\t\n";
                        file_put_contents($error_file_path, $error_content, FILE_APPEND);
                        continue;
                    }
                }
                $gmt = str_replace("'", "", $data_item['gmt']);
                $gmt_arr = array("+1200", "+1100", "+1000", "+0900", "+0800", "+0700", "+0600", "+0500", "+0400", "+0300", "+0200", "+0100", "+0000",
                    "-1200", "-1100", "-1000", "-0900", "-0800", "-0700", "-0600", "-0500", "-0400", "-0300", "-0200", "-0100");
                if (!in_array($gmt, $gmt_arr))
                {
                    $error_sum ++;
                    $error_file_path = $data_info[0][0]['error_file_path'];
                    $error_content = "GMT format error!\t\n";
                    file_put_contents($error_file_path, $error_content, FILE_APPEND);
                    $gmt = "+0000";
                }
                $invoice_time = $data_item['invoice_date'];
                $start = $data_item['start'];
                $end = $data_item['end'];
                $due_date = $data_item['due_date'];
                $invoice_amount = $data_item['invoice_amount'];
                $sql = <<<EOT
    
   INSERT INTO 

invoice(invoice_number,client_id, invoice_time, invoice_start,invoice_end, 
due_date, type, invoice_zone, pdf_path, total_amount, current_balance, pay_amount)

VALUES('{$invoice_number}', '{$client_id}', '{$invoice_time}', TIMESTAMP '{$start} {$gmt}', 

TIMESTAMP '{$end} {$gmt}', '{$due_date}',3, '{$gmt}', '', {$invoice_amount}, 
(SELECT balance::numeric FROM c4_client_balance WHERE client_id = '{$client_id}')
,0) RETURNING invoice_id;

EOT;
                $insert_data = $this->Transaction->query($sql);
                if(!$insert_data)
                {
                    $error_sum ++;
                    $error_file_path = $data_info[0][0]['error_file_path'];
                    $error_content = "Insert error: {$sql}!\t\n";
                    file_put_contents($error_file_path, $error_content, FILE_APPEND);
                }
            }
        }
        else
        {

            $schema = $this->requestAction('/down/get_schema_payment');
            foreach ($header_arr as $key => $items)
            {
                if (!array_key_exists($items, $schema))
                {
                    $data ['ImportExportLog']['id'] = $log_id;
                    $data ['ImportExportLog']['finished_time'] = gmtnow();
                    $data ['ImportExportLog']['status'] = '-4';
                    sleep(1);
                    $this->ImportExportLog->save($data);
                    exit;
                }
                foreach ($goods_list as $key2 => $item)
                {
                    $data_arr[$key2][$items] = $item[$key];
                }
            }
            $error_sum = 0;
            $total_num = count($data_arr);

            foreach ($data_arr as $data_item)
            {

                $received_at = $data_item['datetime'];
                $note = $data_item['note'];
                $payment_type = 0;
                if (strcmp($data_item['type'], 'Prepayment'))
                {
                    $payment_type = 1;
                }
                $amount = preg_replace('/[\$￥]/', '', $data_item['amount']);
                $invoice_numbers = isset($data_item['invoice_number']) && $data_item['invoice_number'] ? array($data_item['invoice_number']) : array();
                $invoice_paids = isset($data_item['invoice_paid']) && $data_item['invoice_paid'] ? array($data_item['invoice_paid']) : array();

                $client_info = $this->ImportExportLog->query("select client_id from client where name = '{$data_item['name']}'");

                if (!$client_info)
                {//所填运营商不存在 需加入log  error +1  
                    $error_sum ++;
                    $error_file_path = $data_info[0][0]['error_file_path'];
                    $error_content = "Carrier {$data_item['name']} is not exsit!\t\n";
                    file_put_contents($error_file_path, $error_content, FILE_APPEND);
                    continue;
                }
                $client_id = $client_info[0][0]['client_id'];

                $invoice = $this->Transaction->get_one_invoice($client_id, null, 'sent');


                if ($type == '2')
                {
                    // Payment Received
                    //$type = 'received';
                    $pre_or_post = $payment_type == 0 ? 5 : 4;
                    $client_payment_id = $this->Transaction->add_payment($client_id, $pre_or_post, $amount, $received_at, $note, $update_by);
                    $this->Transaction->add_ingress_balance($amount, $client_id);
                }
                else if ($type == '1')
                {
                    // Payment Sent
                    //$type = 'sent';

                    $client_payment_id = $this->Transaction->add_payment($client_id, 3, $amount, $received_at, $note, $update_by);
                    $this->Transaction->minus_egress_balance($amount, $client_id);
                }

                if ($payment_type == 1)
                {
                    $remain_amount = intval($invoice[0][0]['total_amount']) - intval($data_item['invoice_paid']);
                    // Received -> Invoice
                    $count = count($invoice_numbers);

                    for ($i = 0; $i < $count; $i++)
                    {
                        $invoice_number = $invoice_numbers[$i];
                        $invoice_paid = floatval($invoice_paids[$i]);

                        if ($invoice_paid == 0)
                        {
                            continue;
                        }

                        $this->Transaction->paid_invoice($client_payment_id, $invoice_number, $invoice_paid);
                    }

                    if ($remain_amount > 0)
                    {
                        $this->Transaction->paid_invoice($client_payment_id, NULL, $remain_amount);
                    }

                }
                $this->Transaction->change_low_balance_type($client_id);

            }
        }


        $success_numbers = intval($total_num) - intval($error_sum);


        $data ['ImportExportLog']['id'] = $log_id;
        $data ['ImportExportLog']['finished_time'] = gmtnow();
        $data ['ImportExportLog']['status'] = 2;
        $data ['ImportExportLog']['success_numbers'] = intval($success_numbers);
        $data ['ImportExportLog']['error_row'] = intval($error_sum);


        fclose($file);
        var_dump($success_numbers,$error_sum);
        sleep(1);
        $this->ImportExportLog->save($data);

    }

}
