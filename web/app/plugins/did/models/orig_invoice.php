<?php

class OrigInvoice extends DidAppModel
{
    var $useTable = "orig_invoice";
    var $primaryKey = "invoice_id";

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
            array_push($result, "'" . $invoice['OrigInvoice']['invoice_id'] . "'");
        }

        return implode(',', $result);
    }

    public function voidInvoiceByIds($ids)
    {
        return $this->query("UPDATE orig_invoice SET state = -1 WHERE invoice_id IN ({$ids})");
    }
}