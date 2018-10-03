<?php

class FinanceHistory extends AppModel
{
    var $name = 'FinanceHistory';
    var $useTable = 'balance_history';
    var $primaryKey = 'id';
    
    public function get_current_finance_detail($client_id,$limit = "") {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');

        $sql = <<<SQL
 SELECT COALESCE(sum(amount), 0) as amount, 0 as type FROM client_payment                                                                                
 WHERE receiving_time between '{$start}' and '{$end}'                                                                                                    
 and payment_type in (4,5) AND client_id = {$client_id}                                                                                                        
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(amount), 0) as amount, 1 as type FROM client_payment                                                                                
 WHERE payment_time between '{$start}' and '{$end}'                                                                                                       
 and payment_type = 8 AND client_id = {$client_id}                                                                                                             
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(amount), 0) as amount, 2 as type FROM client_payment                                                                                
 WHERE payment_time between '{$start}' and '{$end}'                                                                                                      
 and  payment_type = 12 AND client_id = {$client_id}                                                                                                           
 UNION                                                                                                                                                                                                                                                                                                          
 select COALESCE(amount, 0) as amount, 3 as type FROM                                                                                                    
 ingress_cost({$client_id}, '{$start}', '{$end}') as (amount numeric)                                                                                                                                                                                                                   
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(amount), 0) as amount, 4 as type FROM client_payment                                                                                
 WHERE payment_time between '{$start}' and '{$end}'                                                                                                     
 and payment_type = 15 AND client_id = {$client_id}                                                                                                            
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(amount), 0) as amount, 5 as type FROM client_payment                                                                                
 WHERE receiving_time between '{$start}' and '{$end}'                                                                                                    
 and payment_type in (3,6) AND client_id = {$client_id}                                                                                                        
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(amount), 0) as amount, 6 as type FROM client_payment                                                                                
 WHERE payment_time between '{$start}' and '{$end}'                                                                                                      
 and payment_type = 7 AND client_id = {$client_id}                                                                                                             
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(amount), 0) as amount, 7 as type FROM client_payment                                                                                
 WHERE payment_time between '{$start}' and '{$end}'                                                                                                      
 and payment_type = 11 AND client_id = {$client_id}                                                                                                            
 UNION                                                                                                                                                                                                                                                                                              
 select COALESCE(amount, 0) as amount, 8 as type FROM                                                                                                    
 egress_cost({$client_id}, '{$start}', '{$end}') as (amount numeric)                                                                                                                                                                                                                 
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(total_amount), 0) as amount, 9 as type FROM invoice                                                                                 
 WHERE invoice_time between '{$start}' and '{$end}'   and                                                                                                
 state != -1 AND type = 0 AND client_id = {$client_id}                                                                                                         
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(total_amount), 0) as amount,10 as type FROM invoice                                                                                 
 WHERE invoice_time between '{$start}' and '{$end}'   and                                                                                                
 type = 3 AND client_id = {$client_id}                                                                                                                         
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(actual_amount), 0) as amount, 11 as type FROM exchange_finance                                                                      
 WHERE complete_time between '{$start}' and '{$end}' and                                                                                                 
 status = 2 and action_type = 2 AND client_id = {$client_id}                                                                                                   
 UNION                                                                                                                                                   
 SELECT COALESCE(sum(actual_amount), 0) as amount, 12 as type FROM exchange_finance                                                                      
 WHERE complete_time between '{$start}' and '{$end}' and                                                                                                 
 status = 2 and action_type = 1 AND client_id = {$client_id}
SQL;

        $data = $this->query($sql);

        $data[0][0]['amount'] += $data[11][0]['amount'];
        $data[5][0]['amount'] += $data[12][0]['amount'];

        $result = array(
            array(
                array(

                )
            )
        );

        $result[0][0]['actual_ingress_balance'] = $data[0][0]['amount'] + $data[1][0]['amount'] - $data[2][0]['amount'] - $data[3][0]['amount'] - $data[4][0]['amount'];
        $result[0][0]['actual_egress_balance'] = - $data[5][0]['amount'] - $data[6][0]['amount'] + $data[7][0]['amount'] + $data[8][0]['amount'];
        $result[0][0]['actual_total_balance'] = $result[0][0]['actual_ingress_balance'] + $result[0][0]['actual_egress_balance'];

        $result[0][0]['mutual_ingress_balance'] = - $data[9][0]['amount'] + $data[0][0]['amount'] + $data[1][0]['amount'] - $data[2][0]['amount'];
        $result[0][0]['mutual_egress_balance'] = $data[10][0]['amount'] - $data[5][0]['amount'] - $data[6][0]['amount'] + $data[7][0]['amount'];
        $result[0][0]['mutual_total_balance'] = $result[0][0]['mutual_ingress_balance'] + $result[0][0]['mutual_egress_balance'];

        $sql = <<<EOT
    SELECT * FROM balance_history_actual WHERE client_id = {$client_id} order by date desc
EOT;
        $clientData = $this->query($sql);

        if ($clientData) {
            $result[0][0]['actual_ingress_balance'] += $clientData[0][0]['actual_ingress_balance'];
            $result[0][0]['actual_egress_balance'] += $clientData[0][0]['actual_egress_balance'];
            $result[0][0]['actual_total_balance'] += $clientData[0][0]['actual_balance'];
        }

        $sql = <<<EOT
    SELECT * FROM balance_history WHERE client_id = {$client_id} order by date desc
EOT;
        $clientData = $this->query($sql);

        if ($clientData) {
            $result[0][0]['mutual_ingress_balance'] += $clientData[0][0]['mutual_ingress_balance'];
            $result[0][0]['mutual_egress_balance'] += $clientData[0][0]['mutual_egress_balance'];
            $result[0][0]['mutual_total_balance'] += $clientData[0][0]['mutual_balance'];
        }

        return empty($result) ? array() : $result[0][0];
        }

    public function get_finance_detail($client_id,$date)
    {
        $sql = <<<EOT
    SELECT * FROM balance_history_actual WHERE client_id = {$client_id} AND date = '{$date}'
EOT;

        $data = $this->query($sql);
        return isset($data[0][0]) ? $data[0][0] : '';
    }
    
}