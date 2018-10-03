<?php

class DidRequestController extends DidAppController
{

    var $name = 'DidRequest';
    var $uses = array('did.DidRepos', 'did.DidAssign', 'did.DidRequest', 'did.DidRequestDetail', 'did.DidAssign');
    var $components = array('RequestHandler', 'Session');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile');

    function index($type = 'active')
    {
        $status = (($type == 'active') ? 0 : 1);

        $this->pageTitle = "Request Report";
        $this->paginate = array(
            'limit' => 100,
            'fields' => array('DidRequest.id', 'User.name', 'DidRequest.created_time'),
            'order' => array(
                'DidRequest.created_time' => 'desc',
            ),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => "User",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'DidRequest.user_id = User.user_id',
                    ),
                ),
            ),
            'conditions' => array('DidRequest.status' => $status),
        );
        if ($_SESSION['login_type'] == 3) {
            array_push($this->paginate['conditions'], "DidRequest.user_id = {$_SESSION['sst_user_id']}");
        }
        $this->set('current', $type);
        $this->data = $this->paginate('DidRequest');
    }

    function detail($request_id, $type = 'active')
    {

        $this->pageTitle = "Request Report Detail";
        $this->paginate = array(
            'limit' => 100,
            'fields' => array('DidRequestDetail.number', 'DidRequestDetail.status', 'DidRequestDetail.assigned_time', 'DidRequest.id', 'Resource.alias',
                'DidRespoitory.country', 'DidRespoitory.state', 'DidRespoitory.lata', 'DidRespoitory.rate_center'),
            'order' => array(
                'DidRequestDetail.number' => 'asc',
            ),
            'joins' => array(
                array(
                    'table' => 'did_request',
                    'alias' => "DidRequest",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'DidRequestDetail.did_request_id = DidRequest.id',
                    ),
                ),
                array(
                    'table' => 'resource',
                    'alias' => "Resource",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'DidRequestDetail.egress_id = Resource.resource_id',
                    ),
                ),
                array(
                    'table' => 'ingress_did_repository',
                    'alias' => "DidRespoitory",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'DidRequestDetail.number = DidRespoitory.number',
                    ),
                )
            ),
            'conditions' => array('DidRequestDetail.did_request_id' => $request_id),
        );
        
        
        $status = array('Waiting', 'Routed');
        $this->set('status', $status);
        $this->set('type', $type);
        $this->data = $this->paginate('DidRequestDetail');
    }
    
    function assign($request_id, $type)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->DidRequestDetail->findAll(array(
                'did_request_id' => $request_id,
        ));
        $product_id = $this->DidAssign->check_default_static();
        foreach ($data as $item) {
            $item_id = $this->DidAssign->add_new_number($item['DidRequestDetail']['number'], $product_id);
            $this->DidAssign->add_new_resouce($item_id, $item['DidRequestDetail']['egress_id']);
            $this->DidAssign->add_assign($item['DidRequestDetail']['number'], $item['DidRequestDetail']['egress_id']);
            $item['DidRequestDetail']['status'] = 1;
            $this->DidRequestDetail->save($item);
        }
        
        $request = $this->DidRequest->findById($request_id);
        $request['DidRequest']['status'] = 1;
        $this->DidRequest->save($request);
        
        $this->Session->write('m', $this->DidRequestDetail->create_json(201, __('The DID Request #%s is assigned successfully!', true, $request_id)));
        $this->redirect('/did/did_request/index/'.$type);
    }

    function email($request_id, $type)
    {
        //Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $sql = "select email from client where client_id = {$_SESSION['sst_client_id']}";
        $result = $this->DidRequestDetail->query($sql);
        $email_adress = $result[0][0]['email'];
        if (empty($email_adress)) {
            // 空邮件地址
            $this->Session->write('m', $this->DidRequestDetail->create_json(301, __('You have not configured the email address!', true)));
            $this->redirect('/did/did_request/index/'.$type);
        }

        $unique_files = uniqid('DID_request');
        $db_path = Configure::read('database_export_path');
        $this->DidRequestDetail->get_email_file($request_id, $unique_files);
        $full_filepath = $db_path . DS . $unique_files;
        //$filename = 'DID_Request_#' . $request_id . '.csv';
        
        $email_info = $this->DidRequestDetail->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer();
        if ($email_info[0][0]['loginemail'] === 'false') {
            $mailer->IsMail();
        } else {
            $mailer->IsSMTP();
        }
        $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
        $mailer->IsHTML(true);
        switch ($email_info[0][0]['smtp_secure']) {
            case 1:
//                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = $email_info[0][0]['realm'];
                $mailer->Workstation = $email_info[0][0]['workstation'];
        }
        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtphost'];
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        $mailer->AddAddress($email_adress);
        $mailer->Subject = "DID Request #{$request_id}";
        $mailer->ClearAttachments();
        $mailer->AddAttachment($full_filepath);
        $mailer->Body = "DID Request #{$request_id}";
        $mailer->Send();
        
        $this->Session->write('m', $this->DidRequestDetail->create_json(201, __('The DID Request #%s is sent successfully!', true, $request_id)));
        $this->redirect('/did/did_request/index/'.$type);
        
    }

}