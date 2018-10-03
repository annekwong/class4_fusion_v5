<?php

class ToolutilsController extends AppController
{
    var $name = "Toolutils";
    var $uses = array('Cdrs');
    var $components = array('RequestHandler');
    var $helpers = array('javascript','html', 'Common');
    
    
    public function ping_traceroute()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
    }
}


