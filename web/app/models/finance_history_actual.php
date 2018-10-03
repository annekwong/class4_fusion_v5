<?php

class FinanceHistoryActual extends AppModel
{
    var $name = 'FinanceHistoryActual';
    var $useTable = 'balance_history_actual';
    var $primaryKey = 'id';

    public function get_current_finance_detail($client_id)
    {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $reportDate = date('Ymd');

        $sql = <<<SQL
SELECT sum(t1.payment_received) as payment_received, sum(t1.payment_sent) as payment_sent,
       sum(t1.credit_note_received) as credit_note_received, sum(t1.credit_note_sent) as credit_note_sent,
       sum(t1.debit_sent) as debit_sent, sum(t1.debit_received) as debit_received,
--        (SELECT balance FROM c4_client_balance WHERE c4_client_balance.client_id = '{$client_id}') as actual_balance,
       sum(t2.ingress_call_cost) as unbilled_incoming_traffic,
       sum(t3.egress_call_cost) as unbilled_outgoing_traffic,
       0 as short_charges
FROM (
        SELECT 	case when payment_type in (4,5) then
		sum(amount) end as payment_received,
		case when payment_type in (3,6) then
		sum(amount) end as payment_sent,
		case when payment_type = 7 then
		sum(amount) end as credit_note_received,
		case when payment_type = 8 then
		sum(amount) end as credit_note_sent,
		case when payment_type = 11 then
		sum(amount) end as debit_sent,
		case when payment_type = 12 then
		sum(amount) end as debit_received
FROM client_payment
WHERE payment_time BETWEEN '{$start}' AND '{$end}' AND client_payment.client_id = {$client_id}
GROUP BY payment_type
) as t1,
(
SELECT sum(ingress_call_cost) as ingress_call_cost FROM cdr_report_detail{$reportDate} WHERE ingress_client_id = {$client_id}
) as t2,
(
SELECT sum(egress_call_cost) as egress_call_cost FROM cdr_report_detail{$reportDate} WHERE egress_client_id = {$client_id}
) as t3
SQL;
        $data = $this->query($sql);

        return $data[0][0];
    }

    public function get_finance_detail($client_id,$date)
    {

//        $sql = <<<EOT
//   select * from balance_detail({$client_id},'$date') as (actual_ingress_balance numeric,actual_egress_balance numeric,
//actual_balance numeric,mutual_ingress_balance numeric, mutual_egress_balance numeric,
//mutual_balance numeric,payment_received numeric,credit_note_sent numeric,debit_note_sent numeric,unbilled_incoming_traffic numeric,
//short_charges numeric,payment_sent numeric,credit_note_received numeric,
//debit_note_received numeric,unbilled_outgoing_traffic numeric,	invoice_set numeric,invoice_received numeric)
//EOT;

        $sql = <<<EOT
    SELECT * FROM balance_history_actual WHERE client_id = {$client_id} AND date = '{$date}'
EOT;

        $data = $this->query($sql);
//        die(var_dump($sql));
        return isset($data[0][0]) ? $data[0][0] : '';
    }

    public function get_last_day_balance($client_id){
        $current_date = date('Y-m-d');
        $sql = <<<EOT
        SELECT actual_balance FROM balance_history_actual WHERE client_id = '{$client_id}' AND date <='{$current_date}' order by date DESC limit 1
EOT;
        $data = $this->query($sql);
        return !empty($data) ? $data[0][0]['actual_balance'] : 0;
    }

}