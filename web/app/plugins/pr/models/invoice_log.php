<?php

class InvoiceLog extends AppModel
{

    var $name = 'InvoiceLog';
    var $useTable = 'invoice_log';
    var $primaryKey = 'id';
    
    
    public function get_invoices($log_id)
    {
        $sql = "select invoice.invoice_id,client.name, invoice.total_amount, 
invoice.invoice_start, invoice.invoice_end, invoice.due_date, invoice.status
from invoice
left join client on invoice.client_id 
 = client.client_id
where invoice_log_id = {$log_id}";
        return $this->query($sql);
    }


    public function get_count($conditions)
    {
        $where = '';
        if ($conditions)
        {
            $where = ' WHERE ';
            $where .= implode(' and ',$conditions);
        }
        $sql = <<<SQL
select count(1) as total_count
from (
    select sum(t3.total_amount) as total,log_id
    from(
            select t2.total_amount,t1.id as log_id from invoice_log as t1
            inner join invoice as t2 on t1.id = t2.invoice_log_id $where
        ) as t3 group by t3.log_id
    )as t4
where t4.total > 0;
SQL;
        $data = $this->query($sql);
        return $data[0][0]['total_count'];
    }

    public function get_data($conditions, $pageSize, $offset)
    {
        $where = '';
        if ($conditions)
        {
            $where = ' WHERE ';
            $where .= implode(' and ',$conditions);
        }
        $sql = <<<SQL
select total,log_id,start_time,end_time,status,cnt
from(
        select sum(t3.total_amount) as total,t3.log_id,t3.start_time,t3.end_time,t3.status,t3.cnt
        from
        (
            select t1.id as log_id,t2.total_amount,t1.start_time,t1.end_time,t1.status,t1.cnt from invoice_log as t1
            inner join invoice as t2 on t1.id = t2.invoice_log_id $where
        ) as t3 group by log_id,start_time,end_time,status,cnt
    ) as t4
where t4.total > 0 ORDER BY log_id DESC LIMIT {$pageSize} OFFSET {$offset};
SQL;
        return $this->query($sql);

    }
}