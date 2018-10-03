<?php

class QuickcdrController extends AppController
{
    var $name = 'Quickcdr';
    var $uses = array('Quickcdr');
    
    public function beforeFilter()
    {
        if( $this->params['action'] == 'export')
            return true;
        
        parent::beforeFilter();
    }
    
    
    public function index()
    {
        $this->pageTitle = "Statistics/Simple CDR Export";
        if ($this->RequestHandler->isPost())
        {
            if (isset($_SESSION['sst_client_id'])) {
                $_POST['user_id'] = $_SESSION ['sst_user_id'];
            }    
            
            $this->Quickcdr->save(array('Quickcdr' => $_POST));
            $this->redirect("/quickcdr/logging");
        }
        
        $clients = $this->Quickcdr->get_clients();
        $this->set("clients", $clients);
    }  
    
    public function logging()
    {
        $this->pageTitle = "Statistics/Simple CDR Export";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'Quickcdr.id' => 'desc',
            ),
        );
        
        if (isset($_SESSION['sst_client_id'])) {
            $this->paginate['conditions'] = array('Quickcdr.user_id' => $_SESSION ['sst_user_id']);
        } else {
           $this->paginate['conditions'] = array('Quickcdr.user_id IS NULL'); 
        }
        
        $this->data = $this->paginate('Quickcdr');
        
        $clients = $this->Quickcdr->get_clients();
        $this->set("clients", $clients);
        $this->set("status", array('waiting', 'In progress', 'Done'));
    }
    
    public function export($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $quickcdr = $this->Quickcdr->findById($id);
        if ($quickcdr['Quickcdr']['status'] != 2) {
            echo "The CDRs is still in progress, Please try again later.";
            exit;
        }
        $file_path = $quickcdr['Quickcdr']['file_path'];
        $ftp_uri = "ftp://" . Configure::read('cdr_ftp.username') .":" . Configure::read('cdr_ftp.password') . "@" . 
                Configure::read('cdr_ftp.ip') . ':' . Configure::read('cdr_ftp.port') . "/" . $file_path ;
        $this->redirect($ftp_uri);
    }
          
}