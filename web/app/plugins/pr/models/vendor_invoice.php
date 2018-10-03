<?php

class VendorInvoice extends AppModel
{

    var $name = 'VendorInvoice';
    var $useTable = 'vendor_invoice';
    var $primaryKey = 'vendor_invoice_id';


    public function get_status()
    {
        return array(
            __('Not Billed',true),
            __('Disputed',true),
            __('Accepted',true),
            __('Dispute Resolved',true),
            __('Billed',true),
        );
    }

}