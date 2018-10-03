<?php

class CdrDownloadController extends AppController
{

    var $name = 'CdrDownload';
    var $uses = array('Cdr');
    var $helpers = array('javascript', 'html', 'AppCdr', 'AppCommon');


    public function index()
    {
        if($this->params['hasPost'])
        {
            pr($this->params);
            $start_time = $this->params['form']['start'];
            $end_time = $this->params['form']['end'];
            if (strtotime($start_time) > strtotime($end_time))
            {
                $this->Session->write('m', $this->Cdr->create_json(101, __('Start time can not greater than end time',true)));
                $this->redirect('index');
            }

        }
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
    }


    public function beforeFilter1()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        parent::beforeFilter();
    }

    public function basic(){
        $this->beforeFilter1();

        echo 1;die;

    }

}