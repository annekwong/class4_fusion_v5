<?php

class OriginationInvoiceController extends DidAppController
{

    var $name = 'OriginationInvoice';
    var $uses = array('Cdr', 'CdrExportLog');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile', 'AppCommon');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function index()
    {
           pre(2222);
    }

}
