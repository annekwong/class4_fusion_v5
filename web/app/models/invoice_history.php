<?php 

class InvoiceHistory extends AppModel
{
    var $name = 'InvoiceHistory';
    var $useTable = "invoice_history"; 
    var $primaryKey = "id";
    
    function get_max_invoice_date($client_id)
    {
        $sql = "select max(last_invoice_for) from invoice_history where client_id = $client_id";
        $result = $this->query($sql);
        if (isset($result[0][0]['max'])) {
            return $result[0][0]['max'];
        } else {
            return '';
        }
    }
}
