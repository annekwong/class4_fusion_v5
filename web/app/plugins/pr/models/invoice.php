<?php

class Invoice extends AppModel {

    var $name = 'Invoice';
    var $useTable = 'invoice';
    var $primaryKey = 'invoice_id';
    var $ingress_total_call = 0;
    var $egress_total_call = 0;

    const TYPE_BUY = 0;
    const TYPE_SELL = 1;

    var $cacheQueries = false;

    function get_total_due_info($client_id) {
        $sql = "SELECT * FROM invoice WHERE client_id = {$client_id} ORDER BY invoice_end ASC";
        $total = 0;
        $invoice_time = '';
        foreach ($this->query($sql) as $invoice) {
            $invoice = $invoice[0];
            if ($invoice) {
                $invoice_time = $invoice['invoice_end'];
                $total += (float) $invoice['buy_total'] - (float) $invoice['sell_total'] +
                    (float) $invoice['buy_service_charge'] + (float) $invoice['sell_service_charge'] - (float) $invoice['pay_amount'] + (float) $invoice['lrn_cost'];
            }
        }
        return compact('total', 'invoice_time');
    }

    public function get_invoice($type, $order = null) {
        $sql = "select 
disputed, invoice_id,disputed_amount, invoice_number, state, type, invoice.client_id, send_time, invoice_time, 
case when invoice.invoice_zone is null   or invoice.invoice_zone::text='None' then invoice_start::text else (invoice_start AT TIME ZONE (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT end as invoice_start, case when invoice.invoice_zone is null  or invoice.invoice_zone::text='None' then invoice_end::text else (invoice_end AT TIME ZONE (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT end as invoice_end, 
total_amount::numeric(30,5), paid, due_date, pay_amount::numeric(30,5), 
(select balance from c4_client_balance where client_id=invoice.client_id::text)::numeric(30,5) as balance,
pdf_path, cdr_path, (select COALESCE(sum(amount),0) from client_payment 
where invoice_number = invoice.invoice_number and payment_type=4)::numeric(30,5) as invoice_payment, 
(select sum(total_amount) as past_due 
from invoice as inner_invoice where client_id = invoice.client_id and inner_invoice.invoice_end <= 
invoice.invoice_end) as total, (due_date-now() ) as due_inteval, client.name as client, attach_cdrs_list, 
client.invoice_show_details, (select COALESCE(sum(amount),0) from client_payment 
where invoice_number = invoice.invoice_number and payment_type=2)::numeric(30,5) as client_credit,
(select COALESCE(sum(amount),0) from client_payment 
where invoice_number = invoice.invoice_number and payment_type=3)::numeric(30,5) as client_offset 
from invoice left join client on client.client_id=invoice.client_id where invoice.create_type=0
and ( type = 0 or type = 1 or type = 2) 
and invoice.total_amount > 0 order by invoice_id desc limit '100' offset '0'";
        $type = intval($type);
        $send_receive_type = 0;
        $create_type = 0;
        switch ($create_type) {
            case 0:
                $send_receive_type = 0;
                $create_type = 0;
                break;
            case 1:
                $send_receive_type = 1;
                $create_type = 0;
                break;
            case 2:
                $send_receive_type = 0;
                $create_type = 1;
                break;
            case 3:
                $send_receive_type = 1;
                $create_type = 1;
                break;
            case 4:
                $send_receive_type = 2;
                $create_type = 0;
                break;
            case 5:
                $send_receive_type = 2;
                $create_type = 1;
                break;
        }
    }

    public function get_summary_invoice($url_arr) {
        $type = isset($url_arr['type']) ? intval($url_arr['type']) : "";
        $send_receive_type = 0;
        $create_type = 0;
        switch ($type) {
            case 0:
                $send_receive_type = 0;
                $create_type = 0;
                break;
            case 1:
                $send_receive_type = 0;
                $create_type = 1;
                break;
            case 2:
                $send_receive_type = 1;
                $create_type = 0;
                break;
            case 3:
                $send_receive_type = 1;
                $create_type = 1;
                break;
            case 4:
                $send_receive_type = 2;
                $create_type = 0;
                break;
            case 5:
                $send_receive_type = 2;
                $create_type = 1;
                break;
        }

        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        $create_type_where = "  and invoice.create_type={$create_type} ";
        if ($login_type == 3) {
            $privilege = "  and(invoice.client_id={$_SESSION['sst_client_id']}) ";
            $create_type_where = '';
        }
        //模糊搜索
        $like_where = !empty($url_arr['search']) && strcmp('Search....', $url_arr['search']) ? " and ( client.name ilike '%{$url_arr['search']}%'  or  invoice.client_id::text ilike '%{$url_arr['search']}%'  or
	invoice_number ilike '%{$url_arr['search']}%')" : '';
        $invoice_start_where = !empty($url_arr['invoice_start']) ? "  and (invoice_start::date>='{$url_arr['invoice_start']}')" : '';
        $invoice_end_where = !empty($url_arr['invoice_end']) ? "  and (invoice_end::date<='{$url_arr['invoice_end']}')" : '';
        $number_where = !empty($url_arr['invoice_number']) ? " and (invoice_number='{$url_arr['invoice_number']}')" : '';
        $disputed_where = (isset($url_arr['disputed'])) ? " and (disputed='{$url_arr['disputed']}')" : '';
        $due_inteval_where = "";
        if (isset($url_arr['due_inteval_type'])) {
            $due_inteval_where = (!empty($url_arr['due_inteval'])) ? " and current_date {$url_arr['due_inteval_type']} due_date+{$url_arr['due_inteval']}" : '';
        }
//      如果选择ALL 则不能填数字
//是否付清
        $paid_where = !empty($url_arr['paid']) ? " and (paid={$url_arr['paid']})" : '';
        $type_where = !empty($url_arr['type']) ? " and (type={$url_arr['type']})" : '';
        $state_where = (isset($url_arr['state']) && $url_arr['state'] != '') ? " and (state={$url_arr['state']})" : '';
        $client_where = !empty($url_arr ['query'] ['client']) ? "  and (invoice.client_id={$url_arr ['query'] ['client']})" : '';
        $client_name_where = !empty($url_arr ['query'] ['id_clients_name']) ? "  and (invoice.client_id::integer=(SELECT client_id FROM client WHERE name = '{$url_arr ['query'] ['id_clients_name']}'))" : '';
        $amount_where = (!isset($url_arr['invoice_amount']) || $url_arr['invoice_amount'] == 1) ? " and invoice.total_amount > 0" : "";

        //按时间搜索
        $date_where = '';
        if (!empty($url_arr['start_date']) || !empty($url_arr['end_date'])) {
            $start = !empty($url_arr['start_date']) ? $url_arr['start_date'] : date("Y-m-1  00:00:00");
            $end = !empty($url_arr['end_date']) ? $url_arr['end_date'] : date("Y-m-d 23:59:59");
            $date_where = "  and  (invoice_time  between   '$start'  and  '$end')";
        }

        $pay_mode_condtion = '';
        if (!empty($url_arr['pay_mode'])) {
            $pay_mode_condtion = " and client.mode = {$url_arr['pay_mode']}";
        }

        $name = "Invoice_summary";
        $filename = $name . '_' . date('Y_m_d_H_i_s') . '_' . uniqid() . '.csv';

        $sql = "COPY (select invoice_number,invoice_time,case when invoice.invoice_zone is null   or invoice.invoice_zone::text='None' then invoice_start::text
else
(invoice_start AT TIME ZONE  (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT
end as invoice_start,
case when invoice.invoice_zone is null  or invoice.invoice_zone::text='None' then invoice_end::text
else
(invoice_end AT TIME ZONE   (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT
end as invoice_end,total_amount,name from invoice inner join client
 on invoice.client_id = client.client_id where 1=1    $create_type_where $disputed_where
	  $like_where  $invoice_start_where  $invoice_end_where $number_where
   $paid_where  $type_where and type = {$send_receive_type}  $state_where $due_inteval_where $pay_mode_condtion
	  $client_where $client_name_where  $amount_where    $privilege) TO '" . Configure::read('database_actual_export_path') . "/{$filename}'WITH CSV HEADER";



        $insert_sql = "insert into import_export_logs (log_type, file_path, user_id, status, time, obj) values "
            . "(0, '{$filename}', {$_SESSION['sst_user_id']}, 6, CURRENT_TIMESTAMP(0), '{$name}')";

        $this->query($insert_sql);
        $this->query($sql);

        $db_path = Configure::read('database_export_path');
        $real_path = $db_path . DS . $filename;
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        readfile($real_path);

        exit();
    }

    public function getInvoices($type, $clientId, $order = null) {
        $type = intval($type);
        $send_receive_type = 0;
        $create_type = 0;
        switch ($type) {
            case 0:
                $send_receive_type = 0;
                $create_type = 0;
                break;
            case 1:
                $send_receive_type = 0;
                $create_type = 1;
                break;
            case 2:
                $send_receive_type = 1;
                $create_type = 0;
                break;
            case 3:
                $send_receive_type = 1;
                $create_type = 1;
                break;
            case 4:
                $send_receive_type = 2;
                $create_type = 0;
                break;
            case 5:
                $send_receive_type = 2;
                $create_type = 1;
                break;
        }
        if (empty($order)) {
            $order = "invoice_id  desc";
        }
        $temp = isset($_SESSION['pagging_row']) ? $_SESSION['pagging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['pagging_row'] = $pageSize;

//分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        $create_type_where = "  and invoice.create_type={$create_type} ";
        if ($login_type == 3 || $login_type == 2) {
            $client_id = empty($clientId) ? $_SESSION['sst_client_id'] : $clientId;
            $privilege = "  and(invoice.client_id={$client_id}) ";
            $create_type_where = '';
        }
//模糊搜索
        $like_where = !empty($_GET['search']) && strcmp('Search....', $_GET['search']) ? " and (invoice_number ilike '%{$_GET['search']}%' or invoice.client_id::integer IN(SELECT client_id FROM client WHERE name  ilike '%{$_GET['search']}%' ))" : '';        $invoice_start_where = !empty($_GET['invoice_start']) ? "  and (invoice_start::date>='{$_GET['invoice_start']}')" : '';
        $invoice_start_where = !empty($_GET['invoice_start']) ? "  and (invoice_start::date>='{$_GET['invoice_start']}')" : '';
        $invoice_end_where = !empty($_GET['invoice_end']) ? "  and (invoice_end::date<='{$_GET['invoice_end']}')" : '';
        $number_where = !empty($_GET['invoice_number']) ? " and (invoice_number='{$_GET['invoice_number']}')" : '';
        $disputed_where = (isset($_GET['disputed'])) ? " and (disputed='{$_GET['disputed']}')" : '';
        $due_inteval_where = "";
        if (isset($_GET['due_inteval_type'])) {
            $due_inteval_where = (!empty($_GET['due_inteval'])) ? " and current_date {$_GET['due_inteval_type']} due_date+{$_GET['due_inteval']}" : '';
        }
//      如果选择ALL 则不能填数字
//是否付清
        $paid_where = !empty($_GET['paid']) ? " and (paid={$_GET['paid']})" : '';
        $type_where = !empty($_GET['type']) ? " and (type={$_GET['type']})" : '';
        $state_where = (isset($_GET['state']) && $_GET['state'] != '') ? " and (state={$_GET['state']})" : '';
        $client_where = !empty($_GET ['query'] ['client']) ? "  and (invoice.client_id={$_GET ['query'] ['client']})" : '';
        $client_name_where = !empty($_GET ['query'] ['id_clients_name']) ? "  and (invoice.client_id::integer=(SELECT client_id FROM client WHERE name = '{$_GET ['query'] ['id_clients_name']}'))" : '';
        $amount_where = (!isset($_GET['invoice_amount']) || $_GET['invoice_amount'] == 1) ? " and invoice.total_amount > 0" : "";

        //按时间搜索
        $date_where = '';
        if (!empty($_GET['start_date']) || !empty($_GET['end_date'])) {
            $start = !empty($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-1  00:00:00");
            $end = !empty($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d 23:59:59");
            $date_where = "  and  (invoice_time  between   '$start'  and  '$end')";
        }

        $pay_mode_condtion = '';
        if (!empty($_GET['pay_mode'])) {
            $pay_mode_condtion = " and client.mode = {$_GET['pay_mode']}";
        }

        $totalrecords = $this->query("select count(*) as c from invoice
left join client   on client.client_id=invoice.client_id
	 where 1=1    $create_type_where $disputed_where
	  $like_where  $invoice_start_where  $invoice_end_where $number_where
   $paid_where  $type_where and type = {$send_receive_type}  $state_where $due_inteval_where $pay_mode_condtion
	  $client_where $client_name_where  $amount_where    $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;

        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;



        $sql = "select disputed, invoice_id,disputed_amount,pdf_path, invoice.status,invoice_number, generate_start_time,  generate_copy_time, generate_stats_time, generate_end_time,state, type, invoice.client_id, send_time, invoice_time, case when invoice.invoice_zone is null or invoice.invoice_zone::text='None' then invoice_start::text
else
invoice_start ||invoice.invoice_zone::TEXT
end as invoice_start,
case when invoice.invoice_zone is null or invoice.invoice_zone::text='None' then invoice_end::text
else
invoice_end ||invoice.invoice_zone::TEXT
end as invoice_end, total_amount::numeric(30,5), paid, due_date, pay_amount::numeric(30,5), (select balance from c4_client_balance where cast(client_id as varchar(255)) = cast(invoice.client_id as varchar(255)) limit 1)::numeric(30,5) as balance, pdf_path, cdr_path, (select COALESCE(sum(amount),0) from client_payment where invoice_number = invoice.invoice_number and payment_type=4 limit 1)::numeric(30,5) as invoice_payment,client.include_tax,invoice.buy_total, invoice.sell_total,
(select sum(total_amount) as past_due from invoice as inner_invoice where client_id = invoice.client_id and inner_invoice.invoice_end <= invoice.invoice_end limit 1)  as total, 
     (due_date-now() ) as  due_inteval, client.name as client, attach_cdrs_list, client.invoice_show_details, (select COALESCE(sum(amount),0) from client_payment where invoice_number = invoice.invoice_number and payment_type=2 limit 1)::numeric(30,5) as client_credit,(select COALESCE(sum(amount),0) from client_payment where invoice_number = invoice.invoice_number and payment_type=3 limit 1)::numeric(30,5) as client_offset,
     (select COALESCE(sum(amount),0) from client_payment where invoice_number = invoice.invoice_number and payment_type=8 limit 1)::numeric(30,5) as credit_note,
     invoice.output_type  from invoice	 left join client  on client.client_id=invoice.client_id		where  1=1    $create_type_where $disputed_where	 $like_where  $invoice_start_where  $invoice_end_where  $due_inteval_where $paid_where  $type_where and type = {$send_receive_type}   $state_where  $number_where  $client_where $client_name_where $amount_where $pay_mode_condtion $privilege";

        if (isset($_GET['is_export']) && (bool) ($_GET['is_export']) == TRUE) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->autoLayout = false;
            Configure::load('myconf');
            $download_path = Configure::read('database_export_path');
            $random_filename = $download_path . DS . uniqid('invoice-') . '.csv';

            $sql = <<<EOT
\COPY (
SELECT 
invoice_number as \"Invoice No\", invoice_time as \"Invoice Date\", total_amount as \"Amount\",  (select name from client where client_id = invoice.client_id) as \"Client Name\",
due_date as \"Due Date\", case when invoice.invoice_zone is null  or invoice.invoice_zone::text='None' then invoice_start::text
else
(invoice_start AT TIME ZONE  (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT
end as \"Invoice Begin Date\",
case when invoice.invoice_zone is null  or invoice.invoice_zone::text='None' then invoice_end::text
else
(invoice_end AT TIME ZONE   (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT
end as \"Invoice End Date\"
FROM invoice  where  1=1    $create_type_where $disputed_where	 $like_where  $invoice_start_where  $invoice_end_where  $due_inteval_where $paid_where  $type_where and type = {$send_receive_type}   $state_where  $number_where  $client_where $client_name_where $amount_where $privilege  $pay_mode_condtion  
) TO '$random_filename' CSV HEADER DELIMITER AS ','
EOT;
            $this->_get_psql_cmd($sql);
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=Invoice_record.csv");
            ob_clean();   // discard any data in the output buffer (if possible)
            flush();
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            readfile($random_filename);
            exit;
        }
        $sql .= "   order by $order  	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);
        /*
          foreach ($results as &$item)
          {
          $item[0]['payments'] = $this->get_invoice_payments($item[0]['invoice_id']);
          }
         */
        $page->setDataArray($results);
        return $page;
    }


    public function get_in_invoice($order = null) {
        if (empty($order)) {
            $order = "invoice_id  desc";
        }
        $temp = isset($_SESSION['pagging_row']) ? $_SESSION['pagging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['pagging_row'] = $pageSize;
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        if ($login_type == 3) {
            $privilege = "  and(invoice.client_id={$_SESSION['sst_client_id']}) ";
            $create_type_where = '';
        }

        $conditions = '';
        if (isset($_GET['client'])) {
            if (!empty($_GET['client'])) {
                $conditions .= " and invoice.client_id = {$_GET['client']}";
            }
        }
        $totalrecords = $this->query("select count(*) as c from invoice where type = 3 {$conditions}");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select disputed, invoice_id, invoice_number, state, type, invoice.client_id, send_time, invoice_time, invoice_start, invoice_end, total_amount::numeric(30,5), paid, due_date, pay_amount::numeric(30,5), (select balance from c4_client_balance where client_id=invoice.client_id::text)::numeric(30,5) as balance, pdf_path, cdr_path, (select COALESCE(sum(amount),0) from client_payment where invoice_number = invoice.invoice_number and payment_type=4)::numeric(30,5) as invoice_payment,
(select sum(total_amount) as past_due from invoice as inner_invoice where client_id = invoice.client_id and inner_invoice.invoice_end <= invoice.invoice_end)  as total, 
     (due_date-now() ) as  due_inteval, client.name as client, attach_cdrs_list, client.invoice_show_details, (select COALESCE(sum(amount),0) from client_payment where invoice_number = invoice.invoice_number and payment_type=2)::numeric(30,5) as client_credit,(select COALESCE(sum(amount),0) from client_payment where invoice_number = invoice.invoice_number and payment_type=3)::numeric(30,5) as client_offset  from invoice   left join client  on client.client_id=invoice.client_id WHERE invoice.type = 3 {$conditions} ORDER BY {$order} limit '$pageSize' offset '$offset'";
        if (isset($_GET['is_export']) && (bool) ($_GET['is_export']) == TRUE) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->autoLayout = false;
            $db_path = Configure::read('database_export_path');
            $random_filename = $db_path . DS . uniqid('invoice-') . '.csv';
            $sql = <<<EOT
\COPY (
SELECT 
invoice_time as \"Invoice Date\", total_amount as \"Amount\",  (select name from client where client_id = invoice.client_id) as \"Client Name\",
due_date as \"Due Date\", case when invoice.invoice_zone is null   or invoice.invoice_zone::text='None'  then invoice_start::text
else
(invoice_start AT TIME ZONE  (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT
end as \"Invoice Begin Date\",
case when invoice.invoice_zone is null   or invoice.invoice_zone::text='None' then invoice_end::text
else
(invoice_end AT TIME ZONE   (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT
end as \"Invoice End Date\"
FROM invoice WHERE type = 3  ORDER BY invoice_id  desc
) TO '$random_filename' CSV HEADER DELIMITER AS ','
                   
EOT;
            $this->_get_psql_cmd($sql);
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=Invoice_record.csv");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            readfile($random_filename);
            exit;
        }

        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    public function getUnpaidInvoices($conditions = null) {
        if (!empty($conditions)) {
            $conditions = ' AND ' . $conditions;
        }
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query(
            "SELECT count(*) AS c FROM invoice
		LEFT JOIN client   ON (client.client_id=invoice.client_id)
	WHERE 1=1    $conditions");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select invoice_id,invoice_number,state,type,invoice.client_id,send_time,invoice_time,invoice_start,invoice_end,total_amount::numeric(30,5),paid,due_date,
						pay_amount::numeric(30,5),current_balance::numeric(30,5),
(select sum(total_amount) as past_due from invoice as inner_invoice where client_id = invoice.client_id and inner_invoice.invoice_end <= invoice.invoice_end)  as total,

  (  date_part('day',due_date)-date_part('day',now()))  as  due_inteval 
,client.name as client    ,client.invoice_show_details  from invoice
		left join client  on client.client_id=invoice.client_id
		where  1=1  $conditions";


        $sql .= "   	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    ///////////////INVOICE PDF 用的///////////////////////////

    function pdf_show_number($content) {
        $fcontent = (float) $content;
        if ($fcontent) {
            if ($fcontent < 0) {
                return '(' . str_replace('-', '', $content) . ')';
            } else {
                return $content;
            }
        } else {
            return '0';
        }
    }

    function pdf_get_past_due($invoice) {
//		$total = 0;
//		$invoice_no = '';
//		$sql = "SELECT * FROM invoice WHERE client_id = {$invoice['client_id']} AND invoice_time::date < '{$invoice['invoice_time']}' ORDER BY invoice_time DESC LIMIT 2";
//		$invoices = $this->query($sql);
//		foreach($invoices as $i){
//			if($i[0]['type'] == self::TYPE_BUY){
//				$payment = $this->query("select sum(amount) as val from client_payment where result = true and client_id = {$i['client_id']} and payment_type = 1 and invoice_number = '{$invoice[0]['invoice_number']}'");
//				$total += (float)$i[0]['total_amount'] - (float)$payment[0][0]['val'];
//				$invoice_no = $i[0]['invoice_number'] ;
//			}
//			if($invoice[0]['type'] == self::TYPE_SELL){
//				$total -= (float)$i[0]['total_amount'];
//				$invoice_no = $i[0]['invoice_number'];
//			}
//		}
//
//		if($invoice_no){
//			$sql = "SELECT 	SUM(less_rate_charges) as less_rate_charges,
//							SUM(greater_rate_charges) as greater_rate_charges ,
//							SUM(greater_max_rate_charges) as greater_max_rate_charges
//					FROM invoice_service_charge
//					WHERE invoice_no = '$invoice_no'";
//			foreach($this->query($sql) as $payment){
//				$total += (float)$payment[0]['less_rate_charges'] + (float)$payment[0]['greater_rate_charges'] + (float)$payment[0]['greater_max_rate_charges'];
//			}
//		}
//		return $total;
        $total = 0;
        if($invoice['client_id']) {
            $sql = "SELECT * FROM invoice WHERE client_id = {$invoice['client_id']} AND invoice_end::date < '{$invoice['invoice_end']}'";
            foreach ($this->query($sql) as $invoice) {
                $invoice = $invoice[0];
                if ($invoice) {
                    $total += (float)$invoice['buy_total'] - (float)$invoice['sell_total'] +
                        (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'] - (float)$invoice['pay_amount'];
                }
            }
        }
        return $total;
    }

    /*
      function pdf_get_minutes_bought($invoice){
      $list=$this->query("select   code    from  currency   where  exists (select   currency_id  from  client
      where  client_id=(select  client_id  from  invoice  where  invoice_number='{$invoice['invoice_number']}')  and  currency.currency_id=client.currency_id)
      ");
      $currency=!empty($list[0][0]['code'])?$list[0][0]['code']:'';
      $html =<<<EOD
      <span style=""> </span><br />
      <span style="font-size:12px;font-weight:bold">Summary of Minutes Bought</span><br />
      <table cellpadding="1" cellspacing="0" border="1" nobr="true">
      <tr style="background-color:#ddd;text-align:center;">
      <td>Country</td><td>Code Name</td><td>Completed Calls</td><td>Total Minutes</td> <td>Total Charges({$currency})</td>
      </tr>
      EOD;
      $total_min =0 ;
      $total_calls = 0;
      $total_cost = 0;
      $sql = "SELECT invoice_start,invoice_end,client_id,due_date FROM invoice WHERE  invoice_number = '{$invoice['invoice_number']}'  limit 1";
      $list=$this->query($sql);
      $start=$list[0][0]['invoice_start'];
      $end=$list[0][0]['invoice_end'];
      $client_id=$list[0][0]['client_id'];
      $sql="select  country,  code_name,calls_count,total_minutes,cost  from    invoice_calls    where  invoice_type=0 and invoice_no='{$invoice['invoice_number']}'";
      foreach($this->query($sql) as $invoice_call){
      $mins = $invoice_call[0]['total_minutes'];//number_format($invoice_call[0]['total_minutes']);
      $cost = $invoice_call[0]['cost'];//number_format($invoice_call[0]['cost'],5,'.',',');
      $total_min += $mins;
      $total_calls += $invoice_call[0]['calls_count'];
      $total_cost += $cost;
      $mins = number_format($invoice_call[0]['total_minutes'],0,'.',',');
      $cost = number_format($invoice_call[0]['cost'],5,'.',',');

      $html .= <<<EOD
      <tr>
      <td>{$invoice_call[0]['country']}</td>
      <td>{$invoice_call[0]['code_name']}</td>
      <td style="text-align:center;">{$invoice_call[0]['calls_count']}</td>
      <td style="text-align:center;">{$mins}</td>

      <td style="text-align:center;">{$cost}</td>
      </tr>
      EOD;
      }
      $total_min = number_format($total_min,0,'.',',');
      $total_cost = number_format($total_cost,5,'.',',');
      $html .= <<<EOD
      <tr>
      <td><b>Total</b></td><td></td><td style="text-align:center;">{$total_calls}</td><td  style="text-align:center;">{$total_min}</td><td  style="text-align:center;">{$total_cost}</td>
      </tr>
      </table>
      EOD;
      return $html;
      } */

    function pdf_get_minutes_bought($invoice, $num_format = 5) {
        $rate_decimal_place = $invoice['decimal_place'];
        $usage_detail_fields = explode(',', $invoice['usage_detail_fields']);

        $html = '
		<span style="page-break-before:always;"> </span><br />
		<span style="font-size:12px;font-weight:bold">Usage Detail</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Code Name</td>' . (array_search('completed_calls', $usage_detail_fields) !== false ? '<td>Completed Calls</td>' : '') . (array_search('total_minutes', $usage_detail_fields) !== false ? '<td>Total Minutes</td>' : '') . '<td>Rate(US)</td><td>Effective Date</td>' . (array_search('total_charges', $usage_detail_fields) !== false ? '<td>Total Charges(US)</td>' : '') . '
		</tr>
';

        if ($invoice['invoice_jurisdictional_detail']) {
            $html2 = "
		<span style=''> </span><br />
		<span style='font-size:12px;font-weight:bold'>Jurisdictional Break-down</span><br />
		<table cellpadding='1' cellspacing='0' border='1' nobr='true'>
                <tr style='background-color:#ddd;text-align:center;'>
		<td></td><td colspan='" . (array_search('interstate_minute', $usage_detail_fields) !== false ?
                    "3" : '2') . "'>Interstate</td><td colspan='" . (array_search('intrastate_minute', $usage_detail_fields) !== false ?
                    "3" : '2') . "'>Intrastate</td><td colspan='" . (array_search('indeterminate_minute', $usage_detail_fields) !== false ?
                    "3" : '2') . "'>Indeterminate</td><td colspan='" . (array_search('total_minutes', $usage_detail_fields) !== false ?
                    "3" : '2') . "'>Total</td>
		</tr>
		<tr style='background-color:#ddd;text-align:center;'>
		<td>Code Name</td> " . (array_search('interstate_minute', $usage_detail_fields) !== false ?
                    "<td>Minute</td>" : '') . "<td>Rate</td><td>Cost</td>" . (array_search('intrastate_minute', $usage_detail_fields) !== false ?
                    "<td>Minute</td>" : '') . "<td>Rate</td><td>Cost</td>" . (array_search('indeterminate_minute', $usage_detail_fields) !== false ?
                    "<td>Minute</td>" : '') . "<td>Rate</td><td>Cost</td>" . (array_search('total_minutes', $usage_detail_fields) !== false ?
                    "<td>Minute</td>" : '') . "<td>Rate</td><td>Cost</td>
		</tr>
";
        }

        $total = 0;
        $sql = "SELECT * FROM invoice_calls WHERE invoice_type=0 AND invoice_no = '{$invoice['invoice_number']}' order by code_name";

        foreach ($this->query($sql) as $invoice_call) {
            //$this->ingress_total_call += $invoice_call[0]['calls_count'];
            $calls = number_format($invoice_call[0]['calls_count']);
            $mins = number_format($invoice_call[0]['total_minutes'], 2, '.', ',');
            $avg_rate = number_format($invoice_call[0]['avg_rate'], $rate_decimal_place, '.', ',');
            $effective_date = $invoice_call[0]['effective_date'];
            $cost = number_format($invoice_call[0]['cost'], $num_format, '.', ',');
            $total += $invoice_call[0]['cost'];
            $html .= "
		<tr>
			<td>{$invoice_call[0]['code_name']}</td>
                  " . (array_search('completed_calls', $usage_detail_fields) !== false ?
                    "<td style='text-align:center;'>{$calls}</td>" : '') .
                (array_search('total_minutes', $usage_detail_fields) !== false ?
                    "<td style='text-align:center;'>{$mins}</td>" : '') .
                "<td style='text-align:center;'>{$avg_rate}</td>" .
                "<td style='text-align:center;'>{$effective_date}</td>" .
                (array_search('total_charges', $usage_detail_fields) !== false ?
                    "<td style='text-align:center;'>{$cost}</td>" : '') .
                "
		</tr>
";

            if ($invoice['invoice_jurisdictional_detail']) {
                $inter_minutes = number_format($invoice_call[0]['inter_minutes'], 2, '.', ',');
                $inter_rate = number_format($invoice_call[0]['inter_rate'], $rate_decimal_place, '.', ',');
                $inter_cost = number_format($invoice_call[0]['inter_cost'], $num_format, '.', ',');
                $intra_minutes = number_format($invoice_call[0]['intra_minutes'], 2, '.', ',');
                $intra_rate = number_format($invoice_call[0]['intra_rate'], $rate_decimal_place, '.', ',');
                $intra_cost = number_format($invoice_call[0]['intra_cost'], $num_format, '.', ',');
                $indeter_minutes = number_format($invoice_call[0]['total_minutes'] - $invoice_call[0]['inter_minutes'] - $invoice_call[0]['intra_minutes'], 2, '.', ',');
                $indeter_rate = number_format(($invoice_call[0]['cost'] - $invoice_call[0]['inter_cost'] - $invoice_call[0]['intra_cost']) / ($invoice_call[0]['total_minutes'] - $invoice_call[0]['inter_minutes'] - $invoice_call[0]['intra_minutes']), $rate_decimal_place, '.', ',');
                $indeter_cost = number_format($invoice_call[0]['cost'] - $invoice_call[0]['inter_cost'] - $invoice_call[0]['intra_cost'], $num_format, '.', ',');
                $html2 .= "
               <tr>
			<td>{$invoice_call[0]['code_name']}</td>" .
                    (array_search('interstate_minute', $usage_detail_fields) !== false ?
                        "<td style='text-align:center;'>{$inter_minutes}</td>" : '') .
                    "<td style='text-align:center;'>{$inter_rate}</td>
			<td style='text-align:center;'>{$inter_cost}</td>" .
                    (array_search('intrastate_minute', $usage_detail_fields) !== false ?
                        "<td style='text-align:center;'>{$intra_minutes}</td>" : '') .
                    "<td style='text-align:center;'>{$intra_rate}</td>
			<td style='text-align:center;'>{$intra_cost}</td>" .
                    (array_search('indeterminate_minute', $usage_detail_fields) !== false ?
                        "<td style='text-align:center;'>{$indeter_minutes}</td>" : '') .
                    "<td style='text-align:center;'>{$indeter_rate}</td>
			<td style='text-align:center;'>{$indeter_cost}</td>" .
                    (array_search('total_minutes', $usage_detail_fields) !== false ?
                        "<td style='text-align:center;'>{$mins}</td>" : '') .
                    "<td style='text-align:center;'>{$avg_rate}</td>
			<td style='text-align:center;'>{$cost}</td>
		</tr>
";
            }
        }
        $total = number_format($total, $num_format, '.', ',');

        if (array_search('total_charges', $usage_detail_fields) !== false) {

            $html .= "
		<tr>
		<td><b>Total</b></td>" . (array_search('completed_calls', $usage_detail_fields) !== false ?
                    "<td></td>" : '') . (array_search('total_minutes', $usage_detail_fields) !== false ?
                    "<td></td>" : '') . "<td></td><td></td>" . "<td colspan='2'  style='text-align:center;'>{$total}</td>
		</tr>
";
        }

        $html .= "</table>";

        if ($invoice['invoice_jurisdictional_detail']) {
            $html2 .= "</table>";
            $html .= $html2;
        }
        return $html;
    }

    function pdf_get_minutes_sell($invoice, $num_format = 5) {
        $rate_decimal_place = $invoice['decimal_place'];
        $html = <<<EOD
		<span style=""> </span><br />
		<span style="font-size:12px;font-weight:bold">Summary of Minutes Vendor</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Code Name</td><td>Completed Calls</td><td>Total Minutes</td><td>Average Rate(US)</td><td>Effective Date</td><td>Total Charges(US)</td>
		</tr>
EOD;
        if ($invoice['invoice_jurisdictional_detail']) {
            $html2 = <<<EOD
		<span style=""> </span><br />
		<span style="font-size:12px;font-weight:bold">Jurisdictional Break-down</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
                <tr style="background-color:#ddd;text-align:center;">
		<td></td><td colspan="3">Interstate</td><td colspan="3">Intrastate</td><td colspan="3">Indeterminate</td><td colspan="3">Total</td>
		</tr>
		<tr style="background-color:#ddd;text-align:center;">
		<td>Code Name</td><td>Minute</td><td>Rate</td><td>Cost</td><td>Minute</td><td>Rate</td><td>Cost</td><td>Minute</td><td>Rate</td><td>Cost</td><td>Minute</td><td>Rate</td><td>Cost</td>
		</tr>
EOD;
        }
        $total = 0;
        $sql = "SELECT * FROM invoice_calls WHERE invoice_type=1 AND invoice_no = '{$invoice['invoice_number']}' order by code_name";
        foreach ($this->query($sql) as $invoice_call) {
            //$this->egress_total_call += $invoice_call[0]['calls_count'];
            $calls = number_format($invoice_call[0]['calls_count']);
            $mins = number_format($invoice_call[0]['total_minutes']);
            $avg_rate = number_format($invoice_call[0]['avg_rate'], $rate_decimal_place, '.', ',');
            $effective_date = $invoice_call[0]['effective_date'];
            $cost = number_format($invoice_call[0]['cost'], $num_format, '.', ',');
            $total += $invoice_call[0]['cost'];
            $html .= <<<EOD
		<tr>
			<td>{$invoice_call[0]['code_name']}</td>
			<td style="text-align:center;">{$calls}</td>
			<td style="text-align:center;">{$mins}</td><td  style="text-align:center;">{$avg_rate}</td>
			<td style="text-align:center;">{$effective_date}</td>
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOD;

            if ($invoice['invoice_jurisdictional_detail']) {
                $inter_minutes = number_format($invoice_call[0]['inter_minutes'], 2, '.', ',');
                $inter_rate = number_format($invoice_call[0]['inter_rate'], $rate_decimal_place, '.', ',');
                $inter_cost = number_format($invoice_call[0]['inter_cost'], $num_format, '.', ',');
                $intra_minutes = number_format($invoice_call[0]['intra_minutes'], 2, '.', ',');
                $intra_rate = number_format($invoice_call[0]['intra_rate'], $rate_decimal_place, '.', ',');
                $intra_cost = number_format($invoice_call[0]['intra_cost'], $num_format, '.', ',');
                $indeter_minutes = number_format($invoice_call[0]['total_minutes'] - $invoice_call[0]['inter_minutes'] - $invoice_call[0]['intra_minutes'], 2, '.', ',');
                $indeter_rate = number_format(($invoice_call[0]['cost'] - $invoice_call[0]['inter_cost'] - $invoice_call[0]['intra_cost']) / ($invoice_call[0]['total_minutes'] - $invoice_call[0]['inter_minutes'] - $invoice_call[0]['intra_minutes']), $rate_decimal_place, '.', ',');
                $indeter_cost = number_format($invoice_call[0]['cost'] - $invoice_call[0]['inter_cost'] - $invoice_call[0]['intra_cost'], $num_format, '.', ',');
                $html2 .= <<<EOT
               <tr>
			<td>{$invoice_call[0]['code_name']}</td>
			<td style="text-align:center;">{$inter_minutes}</td>
			<td style="text-align:center;">{$inter_rate}</td>
			<td style="text-align:center;">{$inter_cost}</td>
                        <td style="text-align:center;">{$intra_minutes}</td>
			<td style="text-align:center;">{$intra_rate}</td>
			<td style="text-align:center;">{$intra_cost}</td>
                        <td style="text-align:center;">{$indeter_minutes}</td>
			<td style="text-align:center;">{$indeter_rate}</td>
			<td style="text-align:center;">{$indeter_cost}</td>
			<td style="text-align:center;">{$mins}</td>
			<td style="text-align:center;">{$avg_rate}</td>
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOT;
            }
        }
        $total = number_format($total, $num_format, '.', ',');
        $html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td></td><td  style="text-align:center;">{$total}</td>
		</tr>
		</table>
EOD;

        if ($invoice['invoice_jurisdictional_detail']) {
            $html2 .= "</table>";
            $html .= $html2;
        }
        return $html;
    }

    function pdf_get_service_charge($invoice) {
        $html = <<<EOD
		<span style=""> </span><br />
		<span style="font-size:12px;font-weight:bold">Summary of Service Charge</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Value of trade minute</td><td>Buy/Sell</td><td>Minutes</td><td>Average Fee(US)</td><td>Total Charges(US)</td>
		</tr>
EOD;
        $total = 0;
        $sql = "SELECT * FROM invoice_service_charge WHERE invoice_type=1 AND invoice_no = '{$invoice['invoice_number']}'";
        foreach ($this->query($sql) as $charge) {
            $rate = number_format($charge[0]['rate'], 2, '.', ',');
            $max_rate = number_format($charge[0]['max_rate'], 2, '.', ',');

            $less_rate_usage_fee = number_format($charge[0]['less_rate_usage_fee'], 5, '.', ',');
            $less_rate_charges = number_format($charge[0]['less_rate_charges'], 5, '.', ',');
            $less_rate_minutes = number_format($charge[0]['less_rate_minutes']);

            $greater_rate_usage_fee = number_format($charge[0]['greater_rate_usage_fee'], 5, '.', ',');
            $greater_rate_charges = number_format($charge[0]['greater_rate_charges'], 5, '.', ',');
            $greater_rate_minutes = number_format($charge[0]['greater_rate_minutes']);

            $greater_max_rate_usage_fee = number_format($charge[0]['greater_max_rate_usage_fee'], 5, '.', ',');
            $greater_max_rate_charges = number_format($charge[0]['greater_max_rate_charges'], 5, '.', ',');
            $greater_max_rate_minutes = number_format($charge[0]['greater_max_rate_minutes']);

            $total += $charge[0]['less_rate_charges'];
            $total += $charge[0]['greater_rate_charges'];
            $total += $charge[0]['greater_max_rate_charges'];

            $html .= <<<EOD
		<tr>
			<td>&lt;{$rate}</td>
			<td style="text-align:center;">Sell</td>
			<td style="text-align:center;">{$less_rate_minutes}</td>
			<td style="text-align:center;">{$less_rate_usage_fee}</td>
			<td style="text-align:center;">{$less_rate_charges}</td>
		</tr>
		<tr>
			<td>&gt;={$rate} and &lt;{$max_rate}</td>
			<td style="text-align:center;">Sell</td>
			<td style="text-align:center;">{$greater_rate_minutes}</td>
			<td style="text-align:center;">{$greater_rate_usage_fee}</td>
			<td style="text-align:center;">{$greater_rate_charges}</td>
		</tr>
		<tr>
			<td>&gt;={$max_rate}</td>
			<td style="text-align:center;">Sell</td>
			<td style="text-align:center;">{$greater_max_rate_minutes}</td>
			<td style="text-align:center;">{$greater_max_rate_usage_fee}</td>
			<td style="text-align:center;">{$greater_max_rate_charges}</td>
		</tr>
EOD;
        }
        $sql = "SELECT * FROM invoice_service_charge WHERE invoice_type=0 AND invoice_no = '{$invoice['invoice_number']}'";
        foreach ($this->query($sql) as $charge) {
            $rate = number_format($charge[0]['rate'], 2, '.', ',');
            $max_rate = number_format($charge[0]['max_rate'], 2, '.', ',');

            $less_rate_usage_fee = number_format($charge[0]['less_rate_usage_fee'], 5, '.', ',');
            $less_rate_charges = number_format($charge[0]['less_rate_charges'], 5, '.', ',');
            $less_rate_minutes = number_format($charge[0]['less_rate_minutes']);

            $greater_rate_usage_fee = number_format($charge[0]['greater_rate_usage_fee'], 5, '.', ',');
            $greater_rate_charges = number_format($charge[0]['greater_rate_charges'], 5, '.', ',');
            $greater_rate_minutes = number_format($charge[0]['greater_rate_minutes']);

            $greater_max_rate_usage_fee = number_format($charge[0]['greater_max_rate_usage_fee'], 5, '.', ',');
            $greater_max_rate_charges = number_format($charge[0]['greater_max_rate_charges'], 5, '.', ',');
            $greater_max_rate_minutes = number_format($charge[0]['greater_max_rate_minutes']);

            $total += $charge[0]['less_rate_charges'];
            $total += $charge[0]['greater_rate_charges'];
            $total += $charge[0]['greater_max_rate_charges'];

            $html .= <<<EOD
		<tr>
			<td>&lt;{$rate}</td>
			<td style="text-align:center;">Buy</td>
			<td style="text-align:center;">{$less_rate_minutes}</td>
			<td style="text-align:center;">{$less_rate_usage_fee}</td>
			<td style="text-align:center;">{$less_rate_charges}</td>
		</tr>
		<tr>
			<td>&gt;={$rate} and &lt;{$max_rate}</td>
			<td style="text-align:center;">Buy</td>
			<td style="text-align:center;">{$greater_rate_minutes}</td>
			<td style="text-align:center;">{$greater_rate_usage_fee}</td>
			<td style="text-align:center;">{$greater_rate_charges}</td>
		</tr>
		<tr>
			<td>&gt;={$max_rate}</td>
			<td style="text-align:center;">Buy</td>
			<td style="text-align:center;">{$greater_max_rate_minutes}</td>
			<td style="text-align:center;">{$greater_max_rate_usage_fee}</td>
			<td style="text-align:center;">{$greater_max_rate_charges}</td>
		</tr>
EOD;
        }
        $_total = number_format($total, 5, '.', ',');
        $html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td style="text-align:center;">$_total</td>
		</tr>
		</table>
EOD;
        return compact('html', 'total');
    }

    /* function generate_pdf_content($invoice_number){
      //$invoice = $this->find("invoice_number = '$invoice_number'");
      if (empty($invoice_number))
      {
      return '';
      }
      $invoice = $this->query("select * from invoice where invoice_number = '$invoice_number'");
      //var_dump($invoice);
      $invoice = $invoice ? $invoice[0][0] : null;

      $system_params = $this->query("select * from system_parameter");
      $pdf_tpl = str_replace("\n", "<br />", $system_params[0][0]['pdf_tpl']);

      $invoice_time = date("M j Y",strtotime($invoice['invoice_time']));
      $due_date = date("M j Y",strtotime($invoice['due_date']));
      $billing_period = date("m/j/Y",strtotime($invoice['invoice_start'])).'-'.date("m/j/Y",strtotime($invoice['invoice_end']));
      $bought_total = number_format($invoice['buy_total'],5,'.',',');
      $sold_total= number_format($invoice['sell_total'],5,'.',',');
      $bought_minutes = number_format($invoice['buy_minutes']);
      $sold_minutes = number_format($invoice['sell_minutes']);
      $credit_amount = number_format($invoice['credit_amount']);	//退还

      $past_due = $this->pdf_get_past_due($invoice);
      $summary_of_minutes_bought = $this->pdf_get_minutes_bought($invoice);
      $summary_of_minutes_sell = $this->pdf_get_minutes_sell($invoice);
      //$summary_of_service_charge = $this->pdf_get_service_charge($invoice);

      $serice_charge_total = number_format((float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'],5,'.',',');
      $total = (float)$invoice['buy_total'] - (float)$invoice['sell_total'] + (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'];

      $_total = $this->pdf_show_number(number_format($total,5,'.',','));
      //$_past_due = $this->pdf_show_number(number_format($past_due,5,'.',','));
      //$_total_due = $this->pdf_show_number(number_format($total + $past_due,5,'.',','));
      $client = $this->query("select * from client where client_id = ". $invoice['client_id']);
      $member_name = '';
      $_link_cdr = '';
      if($client){
      $member_name = $client[0][0]['name'];
      if($client[0][0]['is_link_cdr'] && !empty($invoice['link_cdr'])){
      $_link_cdr = '<a href="'.$invoice['link_cdr'].'">My CDR Reports</a><br />';
      }
      }
      //Credit Amount: $credit_amount
      return <<<EOD
      <div style="width:100%;text-align:left;margin-top:0px;"><span style="font-size:12px;">Customer: {$client[0][0]['name']}</span></div>
      <div style="width:100%;text-align:right;margin-top:0px;"><span style="font-size:12px;">
      Invoice: {$invoice_number} <br/>
      Invoice Date: {$invoice_time}<br/>
      Payment Due Date: {$due_date}<br/>
      Billing Period: $billing_period<br/>
      Invoice time zone: GMT +00<br/>
      </span></div>
      <div style="height:10px;"></div>

      $summary_of_minutes_bought
      $summary_of_minutes_sell
      <br />
      {$_link_cdr}
      {$pdf_tpl}
      EOD;
      }
     */

    function generate_pdf_content($invoice_number, $url, $num_format = 5) {
        //$invoice = $this->find("invoice_number = '$invoice_number'");
        //echo "www";
        $stylesheet = <<<EOT
      
   <style type="text/css">
   html, body, table {font-size:10px;} 
   </style>
            
EOT;

        $logoii = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'ilogo.png';
        $logoi = $url . DS . 'upload' . DS . 'images' . DS . 'ilogo.png';

        if (!file_exists($logoii)) {
            //$logoi = APP . 'webroot' . DS . 'images' . DS . 'logo.png';
            $logoi = $url . DS . 'images' . DS . 'logo.png';
        }
        $logo = <<<EOT
   <img src="$logoi" />
EOT;
        if (empty($invoice_number)) {
            return '';
        }
        $invoice = $this->query("select case when invoice.invoice_zone is null   or invoice.invoice_zone::text='None' then invoice_start::text else (invoice_start AT TIME ZONE (substring(invoice.invoice_zone for 3))::INTERVAL)::TEXT end as invoice_start1, case when invoice.invoice_zone is null   or invoice.invoice_zone::text='None'  then invoice_end::text else (invoice_end AT TIME ZONE (substring(invoice.invoice_zone for 3))::INTERVAL)::TEXT end as invoice_end1, *  from invoice where invoice_number = '$invoice_number'");
        //var_dump("select * from invoice where invoice_number = '$invoice_number'", $invoice);
        $invoice = !empty($invoice) ? $invoice[0][0] : null;
        $timezone = $invoice['invoice_zone'];
        $system_params = $this->query("select * from system_parameter");
        $num_format = $system_params[0][0]['invoice_decimal_digits'] ? $system_params[0][0]['invoice_decimal_digits'] : $num_format;
        $pdf_tpl = str_replace("\n", "<br />", $system_params[0][0]['pdf_tpl']);
        $pdf_tpl = $system_params[0][0]['pdf_tpl'];
        $invoice_time = date("m/d/Y", strtotime($invoice['invoice_time']));
        $due_date = date("m/d/Y", strtotime($invoice['due_date']));
        $billing_period = date("m/d/Y", strtotime($invoice['invoice_start1'])) . '-' . date("m/d/Y", strtotime($invoice['invoice_end1']));
        $bought_total = number_format($invoice['buy_total'], $num_format, '.', ',');
        $sold_total = number_format($invoice['sell_total'], $num_format, '.', ',');
        $bought_minutes = number_format($invoice['buy_minutes'], 2, '.', ',');
        $sold_minutes = number_format($invoice['sell_minutes'], 2, '.', ',');

        $past_due = $this->pdf_get_past_due($invoice);
        $summary_of_minutes_bought = '';
        $summary_of_minutes_sell = '';


        if ($invoice['create_type'] == 0) {
            if ($invoice['include_detail']) {
                $summary_of_minutes_bought = ($invoice['type'] == 0 || $invoice['type'] == 2) ? $this->pdf_get_minutes_bought($invoice, $num_format) : '';
                $summary_of_minutes_sell = ($invoice['type'] == 1 || $invoice['type'] == 2) ? $this->pdf_get_minutes_sell($invoice, $num_format) : '';
            }
        } elseif ($invoice['create_type'] == 1 && $invoice['include_detail']) {
            $summary_of_minutes_bought = ($invoice['type'] == 0 || $invoice['type'] == 2) ? $this->pdf_get_minutes_bought($invoice, $num_format) : '';
            $summary_of_minutes_sell = ($invoice['type'] == 1 || $invoice['type'] == 2) ? $this->pdf_get_minutes_sell($invoice, $num_format) : '';
        }

        $summary_of_service_charge = $this->pdf_get_service_charge($invoice);

        $serice_charge_total = number_format((float) $invoice['buy_service_charge'] + (float) $invoice['sell_service_charge'], $num_format, '.', ',');
        //$total = (float)$invoice['buy_total'] - (float)$invoice['sell_total'] + (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'];
        // $total = $invoice['type'] == 0 ? (float) $invoice['buy_total'] : (float) $invoice['sell_total'];
        $old_total = $invoice['type'] == 0 ? (float) $invoice['buy_total'] : (float) $invoice['sell_total'];
        $total = (float) $invoice['total_amount'];
        $total = round($total,2);
        //$total = (float)$invoice['total_amount'];

        $tax_result = "";
        if($invoice['client_id']) {
            $tax_result = $this->query("select include_tax from client where client_id = {$invoice['client_id']}");
        }

        if (isset($tax_result[0][0]['include_tax']) && $tax_result[0][0]['include_tax']) {
            //$tax = round($total - $old_total - $invoice['scc_cost'], 5);
            $tax = round($invoice['tax'], 5);
            $is_tax_other = <<<EOT
    	<tr>
		<td>Tax</td><td></td><td></td><td  style="text-align:center;">{$tax}</td>
		</tr>        
EOT;
        } else {
            $is_tax_other = '';
            //$total = $old_total;
        }
        $_total = $this->pdf_show_number(number_format($total, $num_format, '.', ','));

        $_past_due = $this->pdf_show_number(number_format($past_due, $num_format, '.', ','));
        $_total_due = $this->pdf_show_number(number_format($total + $past_due, $num_format, '.', ','));
        $client = "";
        if($invoice['client_id']) {
            $client = $this->query("select * from client where client_id = " . $invoice['client_id']);
        }
        $member_name = '';
        $_link_cdr = '';
        if ($client) {
            $member_name = $client[0][0]['name'];
            if ($client[0][0]['is_link_cdr'] && !empty($invoice['link_cdr'])) {
                $_link_cdr = '<a href="' . $invoice['link_cdr'] . '">My CDR Reports</a><br />';
            }
        }
        $member_number = 12345 + $invoice['client_id'];


        $company_info_result = $this->query("SELECT company_info FROM system_parameter LIMIT 1");
        $company_info_content = str_replace("\n", "<br />", $company_info_result[0][0]['company_info']);
        $company_info_content = $company_info_result[0][0]['company_info'];

        if ($client[0][0]['include_available_credit']) {
            $allow_credit = "Available Credit:" . number_format(abs($client[0][0]['allowed_credit']), 2) . "<br />";
        } else {
            $allow_credit = '';
        }


        if ($client[0][0]['include_payment_history'] && false) {
            $pretime = (int) $client[0][0]['include_payment_history_days'] - 1;
            $start = date("Y-m-d 00:00:00", strtotime($invoice['invoice_end1'] . " -{$pretime} day"));
            $end = $invoice['invoice_end1'];
            $sql = "SELECT 
amount,receiving_time FROM client_payment WHERE (payment_type = 4 OR payment_type = 5) 
AND payment_time BETWEEN '$start' and '{$end}'
and client_id = {$client[0][0]['client_id']} ORDER BY payment_time ASC";
            $payment_result = $this->query($sql);
            $payment_list = "";
            foreach ($payment_result as $payment_item) {
                $payment_list .= '
                <tr>
			<td style="text-align:center;">' . $payment_item[0]['receiving_time'] . '</td>
			<td style="text-align:center;">' . number_format($payment_item[0]['amount'], 2) . '</td>
		</tr>';
            }
            $payment_content = <<<EOT
            <br />
            <span style="font-size:12px;font-weight:bold">Summary of Payments</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
                    <tr style="background-color:#ddd;text-align:center;">
                        <td>Date</td>
                        <td>Payment Received</td>
                    </tr>
                    {$payment_list}
            </table>
EOT;
            if (empty($payment_result)) {
                $payment_content = '';
            }
        } else {
            $payment_content = '';
        }

        if (!empty($invoice['lrn_numbers'])) {
            $invoice['lrn_rate'] = number_format($invoice['lrn_rate'], $num_format, '.', ',');
            $invoice['lrn_cost'] = number_format($invoice['lrn_cost'], $num_format, '.', ',');


            $summary_of_lrn = <<<EOD
	<span style=""> </span><br />
	<span style="font-size:12px;font-weight:bold">Summary of LRN Charges</span><br />
	<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Number of LRN Calls</td><td>LRN Rate(US)</td><td>LRN Total Cost(US)</td>
		</tr>
		<tr>
			<td style="text-align:center;">{$invoice['lrn_numbers']}</td>
			<td style="text-align:center;">{$invoice['lrn_rate']}</td>
			<td style="text-align:center;">{$invoice['lrn_cost']}</td>
		</tr>
	</table>
EOD;
        } else {
            $summary_of_lrn = "";
        }
        $address = str_replace("\n", "<br />", $client[0][0]['address']);
        /* 	return <<<EOD
          <div style="width:100%;text-align:center;margin-top:0px;"><span style="font-size:42px;font-weight:bold">International Carrier Exchange Ltd</span></div>
          <div style="height:10px;"></div>
          <table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
          <tr>
          <th>Member Name</th>
          <td>{$member_name}</td>
          <th>Invoice Number</th>
          <td>{$invoice['invoice_number']}</td>
          </tr>
          <tr>
          <td>Member Number</td>
          <td>{$member_number}</td>
          <th>Invoice Date</th>
          <td>{$invoice_time}</td>
          </tr>
          <tr>
          <td></td>
          <td></td>
          <th>Payment Due Date</th>
          <td>{$due_date}</td>
          </tr>
          <tr>
          <td></td>
          <td></td>
          <th>Billing Period</th>
          <td>{$billing_period}</td>
          </tr>
          </table>
          <br/>
          </center>
          <tr>
          <td></td><td>Past Due Amount</td><td  style="text-align:center;">{$_past_due}</td>
          </tr>
          <tr>
          <td></td><td>Total Due</td><td style="text-align:center;">{$_total_due}</td>
          </tr>
         */

        $sql = "select * from invoice_did where invoice_number = '{$invoice_number}'";
        $invoice_did_result = $this->query($sql);
        if (!empty($invoice_did_result)) {
            $channel_charge = '';
            $did_charge = '';
            $channel_total = 0;
            $did_total = 0;
            foreach ($invoice_did_result as $invoice_did_item) {
                $channel_charge .= <<<EOT
                <tr>
                    <td>{$invoice_did_item[0]['did_plan']}</td>
                    <td>{$invoice_did_item[0]['channel_total_count']}</td>
                    <td>{$invoice_did_item[0]['channel_rate']}</td> 
                    <td>{$invoice_did_item[0]['channel_total_cost']}</td> 
                </tr>
EOT;

                $did_charge .= <<<EOT
                <tr>
                    <td>{$invoice_did_item[0]['did_plan']}</td>
                    <td>{$invoice_did_item[0]['did_total_count']}</td>
                    <td>{$invoice_did_item[0]['did_rate']}</td> 
                    <td>{$invoice_did_item[0]['did_total_cost']}</td> 
                </tr>
EOT;
                $channel_total+=$invoice_did_item[0]['channel_total_cost'];
                $did_total+=$invoice_did_item[0]['did_total_cost'];
            }
            $channel_charge .= <<<EOT
                <tr>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td>{$channel_total}</td>
                </tr>
EOT;
            $did_charge .= <<<EOT
                <tr>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td>{$did_total}</td>
                </tr>
EOT;

            $channel_table = <<<EOT
         <br />
	<span style="font-size:12px;font-weight:bold">Channel Charge</span><br />
	<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Plan</td><td>Channel Count</td><td>Rate</td><td>Sub-Total</td>
		</tr>
		{$channel_charge}
	</table>        
EOT;

            $did_table = <<<EOT
         <br />
	<span style="font-size:12px;font-weight:bold">DID Charge</span><br />
	<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Plan</td><td>DID Count</td><td>Rate</td><td>Sub-Total</td>
		</tr>
		{$did_charge}
	</table>        
EOT;
            $total_charge_table = <<<EOT
        <br />
	<span style="font-size:12px;font-weight:bold">Total Charge</span><br />
	<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr>
                    <td>Channel Charge</td>
                    <td>{$channel_total}</td>
		</tr>
                <tr>
                    <td>DID Charge</td>
                    <td>{$did_total}</td>
                </tr>
                <tr>
                    <td>Minute Usage Charge</td>
                    <td>{$_total}</td>
                </tr>
	</table>    
EOT;
        } else {
            $channel_table = $did_table = $total_charge_table = '';
        }

        if ($invoice['is_invoice_account_summary']) {
            $previous_balance = abs($invoice['previous_balance']);
//            if ($invoice['invoice_use_balance_type'] == 0) {
//                $previous_balance_time = "<tr><td>New Balances Time</td><td>{$invoice['invoice_balance_time']}</td></tr>";
//            } else {
//                $previous_balance_time = '';
//            }
            if ($invoice['invoice_use_balance_type'] == 0) {
                $previous_balance_time = date('Y-m-d', strtotime($invoice['invoice_balance_time'])) . ' ';
            } else {
                $previous_balance_time = '';
            }
            $payment_credit = 0 - $invoice['payment_credit'];
            $finance_charge = $invoice['finance_charge'];
            $decurring_charge = $invoice['decurring_charge'];
            $non_recurring_charge = $invoice['non_recurring_charge'];
            $tax = $invoice['tax'];
            $balance_forward = $invoice['previous_balance'] + $payment_credit;
            $account_finance_charges = 0;
            $long_distance_charges = $_total - $tax;
            $current_charges = $invoice['current_charge'];
            $new_balances = abs($invoice['new_balance']);

            $previous_balance = number_format($previous_balance, $num_format);
            $payment_and_credits = number_format($payment_credit, $num_format);
            $balance_forward = number_format($balance_forward, $num_format);
            $account_finance_charges = number_format($account_finance_charges, $num_format);
            //$long_distance_charges = number_format($long_distance_charges, $num_format);
            $recurring_charges = number_format($decurring_charge, $num_format);
            $non_recurring_charges = number_format($non_recurring_charge, $num_format);
            $federa_states_local_taxes = number_format($tax, $num_format);
            $current_charges = number_format($current_charges, $num_format);
            $new_balances = number_format($new_balances, $num_format);

            $credit_remaining = number_format($invoice['credit_remaining'], $num_format);
            if ($invoice['unlimited_credit_unlimited'])
                $credit_remaining = 'Unlimited';

            $please_pay = number_format($invoice['please_pay'], $num_format);
            if ($client[0][0]['mode'] == 2) {
                if ($new_balances > 0) {
                    $credit_remaining_please_pay_html = <<<EOT
   <tr>
       <td>Credit Remaining</td>
       <td>$credit_remaining</td>
    </tr>
EOT;
                } else {
                    $credit_remaining_please_pay_html = <<<EOT
   <tr>
       <td>Please pay</td>
       <td>$please_pay</td>
    </tr>
EOT;
                }
            }



            $account_detail = <<<EOT
   <div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold">Account Summary</span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%"  >
<tr>  
    <td>Previous Balance</td>
    <td>$previous_balance</td>
<tr>
<tr>  
    <td>Payments and Credits</td>
    <td>$payment_and_credits</td>
<tr>
<tr>  
    <td>Balance Forward</td>
    <td>$balance_forward</td>
<tr>
<tr>  
    <td>Account Finance Charges</td>
    <td>$account_finance_charges</td>
<tr>
<tr>  
    <td>Long Distance Charges</td>
    <td>$long_distance_charges</td>
<tr>
<tr>  
    <td>Recurring Charges</td>
    <td>$recurring_charges</td>
<tr>
<tr>  
    <td>Non-Recurring Charges</td>
    <td>$non_recurring_charges</td>
<tr>
<tr>  
    <td>Federal, State, and Local Taxes</td>
    <td>$federa_states_local_taxes</td>
<tr>
<tr>
    <td>Current Charges</td>
    <td>$current_charges</td>
</tr>
<tr>
    <td>Balance as of {$previous_balance_time}</td>
    <td>{$new_balances} USD</td>
</tr>
{$credit_remaining_please_pay_html}
<tr>
    <td>Payment Term</td>
    <td>{$invoice['payment_term']}</td>
</tr>
</table>
EOT;
        } else {
            $account_detail = '';
        }

        //$total_call_show_header = $invoice['invoice_jurisdictional_detail'] ? "<td>Total Calls</td>" : '';
        //$total_other_header = ($invoice['invoice_jurisdictional_detail'] ? "<td></td>" : '');
        $total_other_header = empty($invoice['total_calls']) ? '<td></td>' : "<td></td>";
        $total_call_show_header = empty($invoice['total_calls']) ? '<td>Total Calls</td>' : "<td>Total Calls</td>";

        if (!empty($system_params[0][0]['tpl_number']) && 1 == $system_params[0][0]['tpl_number']) {
            if ((int) $system_params[0][0]['company_info_location'] == 1) {
                // left
                $top_info = <<<HEREDOC
                <div style="width:100%;text-align:left;margin-top:10px;">
                    <div style="float:left">
                        <span style="font-size:12px;">{$company_info_content}</span>
                        <br><br>
                        {$pdf_tpl}
                    </div>
                    <div style="float:right">
                        <span style="font-size:12px;">
   Billed to:{$client[0][0]['company']}<br />
   Address:{$address}
          <br />     
	  Invoice: {$invoice_number} <br/>
   Invoice Date: {$invoice_time}<br/>
   Payment Due Date: {$due_date}<br/>
   Billing Period: $billing_period<br/>
   Invoice time zone: GMT {$timezone}<br/>
   $allow_credit
                        </span>
                    </div>
                    <div style="clear:both"></div>
                </div>
HEREDOC;
            } else {
                $top_info = <<<HEREDOC
            <div style="width:100%;text-align:left;margin-top:0px;"><span style="font-size:12px;">{$company_info_content}</span></div>
		<br /><br />
		{$pdf_tpl}
		<br /><br />
		<div style="width:100%;text-align:right;margin-top:0px;"><span style="font-size:12px;">
   Billed to:{$client[0][0]['company']}<br />
   Address:{$address}
          <br />     
	  Invoice: {$invoice_number} <br/>
   Invoice Date: {$invoice_time}<br/>
   Payment Due Date: {$due_date}<br/>
   Billing Period: $billing_period<br/>
   Invoice time zone: GMT {$timezone}<br/>
   $allow_credit
		</span></div>          
HEREDOC;
            }

            $return = <<<EOD
    $stylesheet
    $logo
{$top_info}
		
                {$account_detail}
		<div style="height:10px;"></div>
                {$channel_table}
                {$did_table}
		<span style=""> </span><br />
		<span style="font-size:12px;font-weight:bold">Summary of Charges</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%"  >
		<tr style="background-color:#ddd;text-align:center;">
		<td>&nbsp;</td><td>Total Minutes</td>{$total_call_show_header}<td>Total Charges(US)</td>
		</tr>
EOD;
        } else {
            if ((int) $system_params[0][0]['company_info_location'] == 1) {
                // left
                $top_info = <<<HEREDOC
                <div style="width:100%;text-align:left;margin-top:10px;">
                    <div style="float:left">
                        <span style="font-size:12px;">{$company_info_content}</span>
                    </div>
                    <div style="float:right">
                        <span style="font-size:12px;">
   Billed to:{$client[0][0]['company']}<br />
   Address:{$address}<br />
   <br />
	  Invoice: {$invoice_number} <br/>
   Invoice Date: {$invoice_time}<br/>
   Payment Due Date: {$due_date}<br/>
   Billing Period: $billing_period<br/>
   Invoice time zone: GMT {$timezone}<br/>
   $allow_credit
                        </span>
                    </div>
                    <div style="clear:both"></div>
                </div>
HEREDOC;
            } else {
                $top_info = <<<HEREDOC
            <div style="width:100%;text-align:left;margin-top:0px;"><span style="font-size:12px;">{$company_info_content}</span></div>
		<div style="width:100%;text-align:right;margin-top:0px;"><span style="font-size:12px;">
   Billed to:{$client[0][0]['company']}<br />
   Address:{$address}<br />
   <br />
	  Invoice: {$invoice_number} <br/>
   Invoice Date: {$invoice_time}<br/>
   Payment Due Date: {$due_date}<br/>
   Billing Period: $billing_period<br/>
   Invoice time zone: GMT {$timezone}<br/>
   $allow_credit
		</span></div>           
HEREDOC;
            }
            $return = <<<EOD
    $stylesheet
    $logo
		{$top_info}
                {$account_detail}
		<div style="height:10px;"></div> 
                {$channel_table}
                {$did_table}
		<span style=""> </span><br />
		<span style="font-size:12px;font-weight:bold">Summary of Charges</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%"  >
		<tr style="background-color:#ddd;text-align:center;">
		<td>&nbsp;</td><td>Total Minutes</td>{$total_call_show_header}<td>Total Charges(US)</td>
		</tr>
EOD;
        }
        $short_charges = number_format($invoice['scc_cost'], $num_format);
        $scc_calls = $invoice['scc_calls'];
        $scc_per = $invoice['scc_per'];
        $scc_sec = $invoice['scc_sec'];
        $short_call_html = '';
        if ($invoice['type'] == 0) {



            $total_calls = number_format($invoice['total_calls']);

            //$total_call_show = $invoice['invoice_jurisdictional_detail'] ? "<td  style=\"text-align:center;\">{$total_calls}</td>" : '';

            $total_call_show = empty($invoice['total_calls']) ? '' : "<td  style=\"text-align:center;\">{$total_calls}</td>";

            $return .= <<<EOD
		<tr>
		<td>Minutes Usage</td><td style="text-align:center;">{$bought_minutes}</td>{$total_call_show}<td  style="text-align:center;">{$bought_total}</td>
		</tr>
EOD;

            if ($client[0][0]['scc_type'] == 0) {
                $scc_extra_text = '';
            } else {
                $scc_extra_text = ' exceed threshold';
            }


            if ($invoice['is_short_duration_call_surcharge_detail'])
            {
                $short_call_html = <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold">Short Duration Call Surcharge:</span><br />  
<span>Total calls:  {$total_calls}<br />
<span>Calls equal to or less than {$scc_sec} seconds: {$scc_calls} calls<br />
<span>Percentage of short calls {$scc_extra_text}: {$scc_per}%<br />
<span>Short Duration Call Surcharge: \${$short_charges}<br />
EOT;
                if ($short_charges)
                    $return .= "<td>Short Charges</td><td></td>" . $total_other_header . "<td style='text-align:center;'>{$short_charges}</td>";
            }
        } else {
            $total_calls = number_format($invoice['total_calls']);

            //$total_calls = number_format($this->ingress_total_call);
            //$total_call_show = $invoice['invoice_jurisdictional_detail'] ? "<td  style=\"text-align:center;\">{$total_calls}</td>" : '';
            $total_call_show = empty($invoice['total_calls']) ? '' : "<td  style=\"text-align:center;\">{$total_calls}</td>";

            $return .= <<<EOD
		<tr>
		<td>Minutes Sold</td><td style="text-align:center;">{$sold_minutes}</td>{$total_call_show}<td  style="text-align:center;">{$sold_total}</td>
		</tr>
EOD;
        }


        $payment_html = '';

        if ($invoice['invoice_include_payment']) {
            $payment_html = <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold">Summary of Payments</span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
    <td>Date</td>
    <td>Payment received</td>
<tr>   
EOT;
            $sql = "select * from invoice_payment where invoice_no = '{$invoice_number}' order by payment_time asc";
            $payment_results = $this->query($sql);
            foreach ($payment_results as $payment_item1) {
                $payment_html .= '<tr><td style="text-align:center;">' . $payment_item1[0]['payment_time'] . '</td><td style="text-align:center;">' . number_format($payment_item1[0]['payment_amount'], $number_format) . '</td></tr>';
            }

            $payment_html .= '</table>';
        }



        if ($invoice['is_show_daily_usage'] && $invoice['type'] == 0) {
            $sql = "select *,(intra_mins+inter_mins+other_mins) as total_mins from invoice_daily_cost where invoice_no = '{$invoice['invoice_number']}' order by invoice_date asc";
            $daily_cost_result = $this->query($sql);
            $dialy_cost_array = array();
            foreach ($daily_cost_result as $daily_cost_item) {
                $daily_date = $daily_cost_item[0]['invoice_date'];
                $inter_min = number_format($daily_cost_item[0]['inter_mins'],$num_format);
                $intra_min = number_format($daily_cost_item[0]['intra_mins'],$num_format);
                $other_min = number_format($daily_cost_item[0]['other_mins'],$num_format);
                $total_min = number_format($daily_cost_item[0]['total_mins'],$num_format);
                $inter_cost = number_format($daily_cost_item[0]['inter_cost'], $num_format);
                $intra_cost = number_format($daily_cost_item[0]['intra_cost'], $num_format);
                $other_cost = number_format($daily_cost_item[0]['other_cost'], $num_format);
                $total_cost = number_format($daily_cost_item[0]['inter_cost'] + $daily_cost_item[0]['intra_cost'] + $daily_cost_item[0]['other_cost'], $num_format);
                array_push($dialy_cost_array, "<tr><td>$daily_date</td><td>$inter_min</td><td>$intra_min</td><td>$other_min</td><td>$total_min</td><td>$inter_cost</td><td>$intra_cost</td><td>$other_cost</td><td>$total_cost</td></tr>");
            }

            $daily_cost_lines = implode('', $dialy_cost_array);

            $daily_cost_html = <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold">Daily Usage</span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
    <td rowspan="4">Date</td>
    <td colspan="4">Minute</td>
    <td colspan="4">Cost</td>
<tr>
<tr style="background-color:#ddd;text-align:center;">
    <td>Interstate</td>
    <td>Intrastate</td>
    <td>Indeterminate</td>
    <td>Total</td>
    <td>Interstate</td>
    <td>Intrastate</td>
    <td>Indeterminate</td>
    <td>Total</td>
<tr>
{$daily_cost_lines}
</table>
EOT;
        } else {
            $daily_cost_html = '';
        }


        //var_dump($invoice['is_show_total_trunk']);
        if ($invoice['is_show_total_trunk'])
        {
            $sql = "select *,(select alias from resource where resource.resource_id = invoice_calls_detail_by_trunk.resource_id ) as alias from invoice_calls_detail_by_trunk where invoice_no = '{$invoice['invoice_number']}' order by resource_id asc";
            $daily_cost_result = $this->query($sql);
            $dialy_cost_array = array();
            foreach($daily_cost_result as $value)
            {
                $value = $value[0];
                $amount = number_format($value['amount'], $num_format);
                $value_mins = number_format($value['minutes'], $num_format);
                array_push($dialy_cost_array, "<tr><td>{$value['alias']}({$value['prefix']})</td><td>{$value['calls']}</td><td>{$value_mins}</td><td>{$amount}</td></tr>");
            }

            $daily_cost_lines = implode('', $dialy_cost_array);

            $show_total_trunk = <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold">Summary By Trunk</span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
    <td>Trunk (Prefix)</td>
    <td>Calls</td>
    <td>Total Minutes</td>
    <td>Amount</td>
<tr>

{$daily_cost_lines}
</table>
EOT;
        } else {
            $show_total_trunk = '';
        }

        if ($invoice['is_show_detail_trunk'])
        {
            $sql = "select *,(select alias from resource where resource.resource_id = invoice_calls_total_by_trunk.resource_id ) as alias from invoice_calls_total_by_trunk where invoice_no = '{$invoice['invoice_number']}' order by resource_id asc";
            $daily_cost_result = $this->query($sql);
            $dialy_cost_array = array();
            foreach($daily_cost_result as $value)
            {
                $value = $value[0];

                $value_calls = number_format($value['calls'], $num_format);
                $value_mins = number_format($value['minutes'], $num_format);
                $value_am = number_format($value['amount'], $num_format);

                array_push($dialy_cost_array, "<tr><td>{})</td><td>{$value_calls}</td><td>{$value_mins}</td><td>{$value_am}</td></tr>");

                $inter_amount = number_format($value['inter_amount'], $num_format);
                $intra_amount = number_format($value['intra_amount'], $num_format);
                $indeter_amount = number_format($value['indeter_amount'], $num_format);
                $total_amount = number_format($value['amount'], $num_format);

                $show_detail_trunk .= <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold"> Detail by Trunk [{$value['alias']}({$value['prefix']}]</span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
   <td></td> 
   <td> Calls</td>
    <td>Total Minutes</td>
    <td> ACD</td>
    <td>Amount</td>
<tr>

<tr>
<td>Interstate</td>
<td>{$value['inter_calls']}</td>
<td>{$value['inter_minutes']}</td>
<td>{$value['inter_acd']}</td>
<td>{$inter_amount}</td>
</tr>

<tr>
<td>Intrastate</td>
<td>{$value['intra_calls']}</td>
<td>{$value['intra_minutes']}</td>
<td>{$value['intra_acd']}</td>
<td>{$intra_amount}</td>
</tr>

<tr>
<td>Indeterminate</td>
<td>{$value['indeter_calls']}</td>
<td>{$value['indeter_minutes']}</td>
<td>{$value['indeter_acd']}</td>
<td>{$indeter_amount}</td>
</tr>

<tr>
<td>Short Calls (under 6 seconds) Calls</td>
<td colspan='4' >{$value['no_call_6']}</td>
</tr>
<tr>
<td>Total Amount</td>
<td colspan='4' >{$total_amount}</td>
</tr>

</table>
EOT;

            }

        } else {
            $show_detail_trunk = '';
        }



        if ($invoice['is_show_code_100'])
        {
            $sql = "select * from invoice_show_code_100 where invoice_no = '{$invoice['invoice_number']}' order by code asc";
            $daily_cost_result = $this->query($sql);
            $dialy_cost_array = array();
            foreach($daily_cost_result as $value)
            {
                $value = $value[0];
                $amount = number_format($value['amount'], $num_format);
                $value_mins = number_format($value['minutes'], $num_format);
                array_push($dialy_cost_array, "<tr><td>{$value['code']}</td><td>{$value['calls']}</td><td>{$value_mins}</td><td>{$amount}</td></tr>");
            }

            $daily_cost_lines = implode('', $dialy_cost_array);

            $show_code_100 = <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold">Show Code Summary (top 10)</span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
    <td></td>
    <td>Calls</td>
    <td>Total Minutes</td>
    <td>Amount</td>
<tr>

{$daily_cost_lines}
</table>
EOT;
        } else {
            $show_code_100 = '';
        }

        if ($invoice['is_show_code_name'])
        {
            $sql = "select * from invoice_show_code_name where invoice_no = '{$invoice['invoice_number']}' order by code_name asc";
            $daily_cost_result = $this->query($sql);
            $dialy_cost_array = array();
            foreach($daily_cost_result as $value)
            {
                $value = $value[0];
                $amount = number_format($value['amount'], $num_format);
                $value_mins = number_format($value['minutes'], $num_format);
                array_push($dialy_cost_array, "<tr><td>{$value['code_name']}</td><td>{$value['calls']}</td><td>{$value_mins}</td><td>{$amount}</td></tr>");
            }

            $daily_cost_lines = implode('', $dialy_cost_array);

            $show_code_name = <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold"> Show Traffic Analysis by Code Name </span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
    <td>Code Name</td>
    <td>Calls</td>
    <td>Total Minutes</td>
    <td>Amount</td>
<tr>

{$daily_cost_lines}
</table>
EOT;
        } else {
            $show_code_name = '';
        }

        if ($invoice['is_show_country'])
        {
            $sql = "select * from invoice_show_country where invoice_no = '{$invoice['invoice_number']}' order by country asc";
            $daily_cost_result = $this->query($sql);
            $dialy_cost_array = array();
            foreach($daily_cost_result as $value)
            {
                $value = $value[0];
                $amount = number_format($value['amount'], $num_format);
                $value_mins = number_format($value['minutes'], $num_format);
                array_push($dialy_cost_array, "<tr><td>{$value['country']}</td><td>{$value['calls']}</td><td>{$value_mins}</td><td>{$amount}</td></tr>");
            }

            $daily_cost_lines = implode('', $dialy_cost_array);

            $show_country = <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold">Show Traffic Analysis by Country</span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
    <td>Country</td>
    <td>Calls</td>
    <td>Total Minutes</td>
    <td>Amount</td>
<tr>

{$daily_cost_lines}
</table>
EOT;
        } else {
            $show_country = '';
        }


        if ($invoice['is_show_by_date'])
        {
            $sql = "select * from invoice_show_date where invoice_no = '{$invoice['invoice_number']}' order by time_day";
            $daily_cost_result = $this->query($sql);
            $dialy_cost_array = array();
            foreach($daily_cost_result as $value)
            {
                $value = $value[0];
                $amount = number_format($value['amount'], $num_format);
                $value_mins = number_format($value['minutes'], $num_format);
                array_push($dialy_cost_array, "<tr><td>{$value['time_day']}</td><td>{$value['calls']}</td><td>{$value_mins}</td><td>{$amount}</td></tr>");
            }

            $daily_cost_lines = implode('', $dialy_cost_array);

            $show_by_date = <<<EOT
<div style="height:10px;"></div>
<span style=""> </span><br />
<span style="font-size:12px;font-weight:bold"> Show Calls By Date Summary </span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
    <td>Date</td>
    <td>Calls</td>
    <td>Total Minutes</td>
    <td>Amount</td>
<tr>

{$daily_cost_lines}
</table>
EOT;
        } else {
            $show_by_date = '';
        }



        if (empty($system_params[0][0]['tpl_number'])) {
            $return .= <<<EOD
		{$is_tax_other}
		<tr>
		<td>Total</td><td style="text-align:center;">{$bought_minutes}</td>$total_call_show<td  style="text-align:center;">{$_total}</td>
		</tr>
		</table>
                {$short_call_html}
                {$payment_html}
                {$total_charge_table}
		$summary_of_minutes_bought
                $payment_content
		$summary_of_minutes_sell
		$summary_of_lrn
                $daily_cost_html
                        
                        $show_total_trunk
                        $show_detail_trunk
                        $show_code_100
                        $show_code_name
                        $show_country
                        $show_by_date
                        
		<br /><br /><br /><br />
		{$_link_cdr}
		{$pdf_tpl}
EOD;
        } elseif (!empty($system_params[0][0]['tpl_number']) && 2 == $system_params[0][0]['tpl_number']) {
            $return .= <<<EOD
		{$is_tax_other}
		<tr>
		<td>Total</td><td style="text-align:center;">{$sold_minutes}</td>$total_call_show<td  style="text-align:center;">{$_total}</td>
		</tr>
		</table>
		<br /><br />
		{$pdf_tpl}
		<br /><br />
                {$short_call_html}
                {$payment_html}
                {$total_charge_table}
		$summary_of_minutes_bought
		$summary_of_minutes_sell
                $payment_content
		$summary_of_lrn
                $daily_cost_html
                        $show_total_trunk
                        $show_detail_trunk
                        $show_code_100
                        $show_code_name
                        $show_country
                        $show_by_date
		<br /><br /><br /><br />
		{$_link_cdr}
EOD;
        } else {
            $return .= <<<EOD
        {$is_tax_other}
		<tr>
		<td>Total</td><td style="text-align:center;">{$bought_minutes}</td>$total_call_show<td  style="text-align:center;">{$_total}</td>
		</tr>
		</table>
                {$short_call_html}
                {$$payment_html}
                {$total_charge_table}
		$summary_of_minutes_bought
		$summary_of_minutes_sell
                $payment_content
                        $show_total_trunk
                        $show_detail_trunk
                        $show_code_100
                        $show_code_name
                        $show_country
                        $show_by_date
                        
		<br /><br /><br /><br />
		{$_link_cdr}
EOD;
        }
        return $return;
    }

    public function get_sys_cdr($start_time, $end_time, $client_id, $code) {
        $sql = "SELECT 

sum(egress_bill_time) as bill_time, sum(egress_cost) as call_cost


from client_cdr".date("Ymd")." where egress_client_id = {$client_id} and term_code ='{$code}' 


and time between '{$start_time}' 

and '{$end_time}' GROUP BY term_code ORDER BY 1";

        $result = $this->query($sql);
        if (empty($result)) {
            return array(0, 0);
        } else {
            return array(
                $result[0][0]['bill_time'] / 60,
                $result[0][0]['call_cost']
            );
        }
    }

    public function get_reconcile($invoice_id, $pageSize, $offset) {
        $sql = "SELECT * FROM invoice_reconcile WHERE invoice_id = {$invoice_id} ORDER BY code DESC LIMIT {$pageSize} OFFSET {$offset}";
        $data = $this->query($sql);
        return $data;
    }

    public function get_reconcile_count($invoice_id) {
        $sql = "SELECT count(*) FROM invoice_reconcile WHERE invoice_id = {$invoice_id}";
        $data = $this->query($sql);
        return $data[0][0]['count'];
    }

    public function get_invoice_info($invoice_id) {
        $sql = "SELECT 
 invoice_number,
 total_amount AS invoice_amount,
 CASE  paid 
     WHEN TRUE  THEN 0
     WHEN FALSE THEN total_amount - pay_amount
 END AS due_amount,
 invoice_start,
 invoice_end,
 due_date,
 pay_amount
FROM invoice WHERE invoice_id = {$invoice_id}";
        $data = $this->query($sql);
        return $data;
    }

    public function get_client_name($client_id) {
        $sql = "select name from client where client_id = $client_id";
        $result = $this->query($sql);
        if (empty($result)) {
            return '';
        } else {
            return $result[0][0]['name'];
        }
    }

    public function get_invoice_payments($invoice_id) {
        $sql = "select client_payment.client_payment_id,
payment_invoice.amount as paid_amount, client_payment.amount, client_payment.payment_time
from payment_invoice left join client_payment
on payment_invoice.payment_id = client_payment.client_payment_id
where invoice_id = $invoice_id order by payment_invoice.id asc";
        $result = $this->query($sql);


        return $result;
    }

    public function get_client_payments($client_id, $create_type = '') {
        if ($create_type == 'incoming') {
            $sql = "SELECT client_payment_id,  
    amount,receiving_time,
    (select sum(amount) from payment_invoice where payment_id = client_payment.client_payment_id and invoice_id is not null) as used_amount
    FROM client_payment WHERE (payment_type = 3 OR payment_type = 6) 
    AND client_id = {$client_id}
    ORDER BY payment_time DESC, client_id DESC";
        } else {
            $sql = "SELECT client_payment_id,  
    amount,receiving_time,
    (select sum(amount) from payment_invoice where payment_id = client_payment.client_payment_id and invoice_id is not null) as used_amount
    FROM client_payment WHERE (payment_type = 4 OR payment_type = 5) 
    AND client_id = {$client_id}
    ORDER BY payment_time DESC, client_id DESC";
        }
        $result = $this->query($sql);
        return $result;
    }

    public function get_client_payment($payment_id) {
        $sql = "SELECT 
amount,
(select sum(amount) from payment_invoice where payment_id = client_payment.client_payment_id and invoice_id is not null) as used_amount,
(select id from payment_invoice where payment_id = client_payment.client_payment_id and invoice_id is null) as remain_id
FROM client_payment WHERE client_payment_id = $payment_id";
        $result = $this->query($sql);
        return $result[0][0];
    }

    public function update_payment_invoice($id, $amount) {
        $sql = "update payment_invoice set amount = $amount where id = {$id}";
        $this->query($sql);
    }

    public function insert_remain_payment_invoice($payment_id, $amount) {
        $sql = "insert into payment_invoice (payment_id, amount) values($payment_id, $amount)";
        $this->query($sql);
    }

    public function insert_payment_invoice($payment_id, $invoice_id, $amount) {
        $sql = "insert into payment_invoice (payment_id, invoice_id,amount) values($payment_id, $invoice_id,$amount)";
        $this->query($sql);
    }

    public function delete_payment_invoice($id) {
        $sql = "delete from payment_invoice where id = {$id}";
        $this->query($sql);
    }

    public function getOverlappedIds($startTime, $endTime, $clients)
    {
        $arrayClients = explode(',', $clients);

        $invoices = $this->find('all', array(
            'fields' => array('invoice_id'),
            'conditions' => array(
                'invoice_start >=' => $startTime,
                'invoice_end <=' => $endTime,
                'client_id' => $arrayClients,
                'NOT' => array(
                    'status' => -1
                )
            )
        ));

        $result = array();

        foreach ($invoices as $invoice) {
            array_push($result, "'" . $invoice['Invoice']['invoice_id'] . "'");
        }

        return implode(',', $result);
    }

    public function voidInvoiceByIds($ids)
    {
        $this->query("UPDATE invoice SET status = -1 WHERE invoice_id IN ({$ids})");
    }

}
