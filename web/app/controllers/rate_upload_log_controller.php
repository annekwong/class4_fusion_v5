<?php

class RateUploadLogController extends AppController {
    
    var $name = "RateUploadLog";
    var $uses = array('RateUploadSuccessLog' , 'RateUploadFailLog');
    var $helpers = array ('Javascript', 'Html');
    
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
    }

    public function success()
    {
        $this->pageTitle = "Log/Rate Upload Success Log";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('RateUploadSuccessLog');
    }
    
    public function fail()
    {
        $this->pageTitle = "Log/Rate Upload Fail Log ";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('RateUploadFailLog');
    }
    
}