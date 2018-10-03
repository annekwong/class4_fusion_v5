<?php

class PaymentHistory extends AppModel
{
	var $name = 'PaymentHistory';
	var $useTable = "payline_history";
	var $primaryKey = "id";
        
        
        public function get_paypal_data($where, $pageSize, $offset,$order_sql = "")
        {
            $sql = "select chargetotal as amount, modified_time as date, invoice_id as paypal_id, cl.name "
                    . "from payline_history as pay inner join client as cl on cl.client_id = pay.client_id"
                    . " where pay.status = '2' {$where} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";
            
            
            $data = $this->query($sql);
            return $data;
        }
        
        public function get_paypal_count($where)
        {
            $sql = "select count(*) as sum from payline_history as pay inner join client as cl on cl.client_id = pay.client_id"
                    . " where pay.status = '2' {$where}";
            
            $data = $this->query($sql);
            
            return $data[0][0]['sum'];
        }
}