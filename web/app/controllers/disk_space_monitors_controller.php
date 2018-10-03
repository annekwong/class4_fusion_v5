<?php 

class DiskSpaceMonitorsController extends AppController
{
    var $name = "DiskSpaceMonitors";
    var $uses = array();
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }
    
    function getSymbolByQuantity($bytes) {
        $symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        $exp = floor(log($bytes)/log(1024));

        return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
    }
    
    public function index()
    {
        
        $web_path = ROOT;
        $web_total_space = disk_total_space($web_path);
        $web_free_space  = disk_free_space($web_path);
        $web_total_space = $this->getSymbolByQuantity($web_total_space);
        $web_free_space = $this->getSymbolByQuantity($web_free_space);
        
        $db_path = Configure::read('export_cdr.path');
        $db_total_space = disk_total_space($web_path);
        $db_free_space  = disk_free_space($web_path);
        $db_total_space = $this->getSymbolByQuantity($db_total_space);
        $db_free_space = $this->getSymbolByQuantity($db_free_space);
        
        $data = array(
            array('Web', $web_path, $web_total_space, $web_free_space),
            array('DB', $db_path, $db_total_space, $db_free_space),
        );
        $this->set('data', $data);
    }
    
}
