<?php
class ActiveRegistrationController extends AppController {

    var $name = 'ActiveRegistration';
    var $uses = array('Client');
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


    public function index() {

        $sql = <<<SQL
select sip_registrations.* from sip_registrations inner join resource_ip on sip_registrations.username =  resource_ip.username
 where id in (select max(id) from sip_registrations group by username);
SQL;
        $data = $this->Client->query($sql);

        $this->data = $data;
    }


}
