<?php 

class Executelog_Controller extends AppController
{
    var $name = "Executelog";
    var $uses = array('Executelog');
    var $helpers = array ('Javascript', 'Html');
    
    public function beforeFilter() 
    {
        $this->checkSession("login_type");      //核查用户身份
    }
    
    public function index()
    {
        
    }
}

?>
