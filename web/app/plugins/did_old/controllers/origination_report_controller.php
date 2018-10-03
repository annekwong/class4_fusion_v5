<?php

class OriginationReportController extends DidAppController
{

    var $name = 'OriginationReport';
    var $uses = array('Cdr', 'CdrExportLog');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile', 'AppCommon');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }
    
    public function index()
    {
        
    }

}
