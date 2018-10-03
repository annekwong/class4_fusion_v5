<?php

class OrigInvoiceController extends DidAppController
{

    var $name = 'OrigInvoice';
    var $uses = array('Client', 'CdrExportLog', 'Did.OrigInvoice', 'ApiLog', 'Systemparam', 'Paymentterm');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile', 'AppCommon', 'AppPaymentterms');
    var $invoiceAddress;

    public function beforeFilter()
    {
        Configure::load('myconf');
        $this->invoiceAddress = Configure::read('invoice_orig_url');

        if ($this->params['action'] == 'do_reconcile' || $this->params['action'] == 'createpdf_invoice' || $this->params['action'] == 'cdr_download' || 'createxls_invoice' == $this->params['action'] || 'download_invoice' == $this->params['action'])
            return true;
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');

        if($login_type == 2 && $this->params['action'] == 'view') {
            return true;
        }

        if ($login_type == 1)
        {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_account_invoice');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }

        parent::beforeFilter();
    }

    function index()
    {
        $this->redirect('view');
    }

    public function view($clientId = null)
    {
        Configure::write('debug', 2);
        $this->pageTitle = "Origination Invoices";
        $type = 0;
        $loginType = $_SESSION['login_type'];

        if (isset($this->params['pass'][0])) {
            $type = $this->params['pass'][0];
        } else {
            $this->params['pass'][0] = $type;
        }

        $conditions = array(
//            'Client.client_type' => 1,
            'OrigInvoice.create_type' => $type
        );

        if ($loginType != 1) {
            $conditions['OrigInvoice.client_id'] = $_SESSION['sst_client_id'];
        }
        if (isset($this->params['url']['query']['client']) && $this->params['url']['query']['client']) {
            $conditions['OrigInvoice.client_id'] = $this->params['url']['query']['client'];
        }
        if (isset($this->params['url']['invoice_start']) && $this->params['url']['invoice_start']) {
            $this->set('start_time', $this->params['url']['invoice_start']);
            $conditions[] = " (OrigInvoice.invoice_start::date>='{$this->params['url']['invoice_start']}') ";
        }
        if (isset($this->params['url']['invoice_end']) && $this->params['url']['invoice_end']) {
            $this->set('end_time', $this->params['url']['invoice_end']);
            $conditions[] = " (OrigInvoice.invoice_end::date<='{$this->params['url']['invoice_end']}') ";
        }

        $limit = isset($_REQUEST['size']) ? $_REQUEST['size'] : 100;
        $this->paginate = array(
            'fields' => array(
                'OrigInvoice.invoice_id', 'OrigInvoice.invoice_number', 'OrigInvoice.invoice_start', 'OrigInvoice.invoice_end',
                'OrigInvoice.total_amount', 'OrigInvoice.pdf_path',  'OrigInvoice.state', 'Client.name'
            ),
            'limit' => $limit,
            'conditions' => $conditions,
            'order' => array(
                'invoice_id DESC'
            ),
            'joins' => array(
                array(
                    'table' => 'client',
                    'alias' => 'Client',
                    'type' => 'left',
                    'conditions' => array(
                        'Client.client_id = OrigInvoice.client_id'
                    )
                )
            )
        );
        $this->data = $this->paginate('OrigInvoice');
        $this->set('create_type', $type);
        $this->set('clients', $this->Client->get_origination_clients());
    }

    public function add($type)
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->set('clients', $this->Client->get_origination_clients());
        $invoiceUrl = Configure::read('invoice_url');

        $this->set('type', $type);
        $this->set('invoiceUrl', $invoiceUrl);
    }

    public function checkOverlapInvoices()
    {
        Configure::write('debug', 0);

        if ($this->RequestHandler->isPost()) {
            $startTime = $_POST['start_date'];
            $endTime = $_POST['end_date'];
            $clients = $_POST['client_id'];
            $due = $_POST['due'];
            $includeFields = isset($_POST['did_invoice_include']) ? $_POST['did_invoice_include'] : null;

            $response = array(
                'code' => '201',
                'field' => '',
                'msg' => 'Successfully'
            );

            if (empty($clients)) {
                $response = array(
                    'code' => '101',
                    'field' => '',
                    'msg' => 'Please select any carrier!'
                );
            } else {
                $checkOverlap = $this->Systemparam->find('first', array(
                    'fields' => array('overlap_invoice_protection')
                ));

                if ($checkOverlap['Systemparam']['overlap_invoice_protection']) {
                    $overlappedIds = $this->OrigInvoice->getOverlappedIds($startTime, $endTime, $clients);

                    if (!empty($overlappedIds)) {
                        $this->OrigInvoice->voidInvoiceByIds($overlappedIds);

                        $response = array(
                            'code' => '101',
                            'field' => '',
                            'msg' => "The invoice for period of {$startTime} to {$endTime} is overlapped with Invoice # ({$overlappedIds})."
                        );
                    }
                }
            }

            if ($response['code'] == 201) {
                $startTime = strtotime($_POST['start_date']);
                $endTime = strtotime($_POST['end_date']);
                $url = "{$this->invoiceAddress}/invoice/client/{$clients}/end/{$endTime}/start/{$startTime}/type/1/due/{$due}";

                if ($includeFields) {
                    $url .= "/params/" . implode(',', $includeFields);
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                $scriptResponse = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $this->ApiLog->addRequest($url, null, null, 1, $httpcode);
                curl_close($ch);

                if ($httpcode != 200 ) {
                    $msg = 'Failed';

                    if ($scriptResponse) {
                        $scriptResponse = json_decode($scriptResponse, true);
                        $msg = $scriptResponse['msg'];
                    }

                    $response = array(
                        'code' => '101',
                        'field' => '',
                        'msg' => $msg
                    );
                } else if ($httpcode == 200) {
                    $response = array(
                        'code' => '201',
                        'field' => '',
                        'msg' => 'Successfully'
                    );
                }
            }
            echo json_encode($response);
        }
        exit;
    }

    function delete_invoice($invoiceId, $type)
    {
        $result = $this->OrigInvoice->del($invoiceId);

        if ($result !== false) {
            $this->Session->write('m', $this->OrigInvoice->create_json(201, "Invoice deleted successful!"));
        } else {
            $this->Session->write('m', $this->OrigInvoice->create_json(101, "Delete failed!"));
        }
        $this->redirect("view/$type");
    }

    function void_invoice($invoiceId, $type)
    {
        $result = $this->OrigInvoice->voidInvoiceByIds($invoiceId);

        if ($result !== false) {
            $this->Session->write('m', $this->OrigInvoice->create_json(201, "Invoice voided successful!"));
        } else {
            $this->Session->write('m', $this->OrigInvoice->create_json(101, "Void failed!"));
        }
        $this->redirect("view/$type");
    }

    public function createpdf_invoice($invoiceNumber)
    {
        Configure::write("debug", 0);
        $sql = "SELECT * FROM orig_invoice WHERE invoice_number = '{$invoiceNumber}'";
        $data = $this->OrigInvoice->query($sql);
        $invoiceId = $data[0][0]['invoice_id'];
        $invoice_date = $data[0][0]['invoice_time'];
        $filename = $invoiceNumber . '_' . $invoice_date . '.pdf';

        $pdfUrl = $this->invoiceAddress . "/get_pdf/{$invoiceId}";
        $content = file_get_contents($pdfUrl);

        if ($content != false) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');

            ob_clean();
            echo $content;
        } else {
            $this->Session->write('m', $this->OrigInvoice->create_json(101, 'File not found'));
            $this->redirect("view/0");
        }
        die;
    }

    public function send_invoice_mail($invoice_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;

        $invoice = $this->OrigInvoice->findByInvoiceId($invoice_id);
        $billing_email_query = $this->OrigInvoice->query("SELECT billing_email FROM client WHERE client_id = {$invoice['OrigInvoice']['client_id']}");
        $billing_email = $billing_email_query[0][0]['billing_email'];
        $this->set("billing_email", $billing_email);
    }

    public function resend($invoiceId)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;

        if ($this->RequestHandler->isPost()) {
            if (isset($this->params['form']['email'])) {
                $to = $this->params['form']['email'];
                $invoiceData = $this->OrigInvoice->find('first', array(
                    'fields' => array(
                        'OrigInvoice.total_amount', 'OrigInvoice.invoice_number', 'OrigInvoice.invoice_start', 'OrigInvoice.invoice_end',
                        'Client.name', 'Client.company'
                    ),
                    'conditions' => array(
                        'invoice_id' => $invoiceId
                    ),
                    'joins' => array(
                        array(
                            'table' => 'client',
                            'alias' => 'Client',
                            'type' => 'left',
                            'conditions' => array(
                                'Client.client_id = OrigInvoice.client_id'
                            )
                        )
                    )
                ));

                $this->loadModel('Mailtmp');
                $mailData = $this->Mailtmp->find('first', array(
                    'fields' => array(
                        'invoice_subject', 'invoice_from', 'invoice_cc', 'invoice_content'
                    )
                ));

                $from = $mailData['Mailtmp']['invoice_from'];
                $cc = $mailData['Mailtmp']['invoice_cc'];
                $subject = $mailData['Mailtmp']['invoice_subject'];
                $body = $mailData['Mailtmp']['invoice_content'];
                $link = $this->getUrl() . 'did/orig_invoice/createpdf_invoice/' . $invoiceData['OrigInvoice']['invoice_number'];
                $link = "<a href='{$link}'>Download Invoice here</a>";

                $body = str_replace(
                    array(
                        '{company_name}',
                        '{client_name}',
                        '{invoice_amount}',
                        '{invoice_number}',
                        '{invoice_link}',
                        '{start_date}',
                        '{end_date}',
                    ),
                    array(
                        $invoiceData['Client']['company'],
                        $invoiceData['Client']['name'],
                        $invoiceData['OrigInvoice']['total_amount'],
                        $invoiceData['OrigInvoice']['invoice_number'],
                        $link,
                        $invoiceData['OrigInvoice']['invoice_start'],
                        $invoiceData['OrigInvoice']['invoice_end'],
                    ),
                    $body
                );

                $sendResult = $this->VendorMailSender->send($subject, $body, $to, $cc, $from);

                $this->loadModel('EmailLog');
                $this->EmailLog->save(array(
                    'client_id' => $invoiceData['Client']['name'],
                    'send_time' => date('Y-m-d H:i:s'),
                    'email_addresses' => $to,
                    'type' => 7,
                    'status' => $sendResult['status'],
                    'subject' => $subject,
                    'content' => $body
                ));

                if ($sendResult['status'] == 0) {
                    $this->Session->write('m', $this->OrigInvoice->create_json(201, 'Invoice sent successfully!'));
                } else {
                    $this->Session->write('m', $this->OrigInvoice->create_json(101, $sendResult['error']));
                }
            } else {
                $this->Session->write('m', $this->OrigInvoice->create_json(101, 'The receiver can not empty!'));
            }
            $this->redirect("/did/orig_invoice/view/0");
        }
    }

    public function regenerate($invoiceId, $type)
    {
        $this->OrigInvoice->voidInvoiceByIds($invoiceId);

        $invoiceData = $this->OrigInvoice->findByInvoiceId($invoiceId);
        $startTime = strtotime($invoiceData['OrigInvoice']['invoice_start']);
        $endTime = strtotime($invoiceData['OrigInvoice']['invoice_end']);
        $clientId = $invoiceData['OrigInvoice']['client_id'];
        $url = "{$this->invoiceAddress}/invoice/client/{$clientId}/end/{$endTime}/start/{$startTime}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->ApiLog->addRequest($url, null, null, 1, $httpcode);
        curl_close($ch);

        if ($httpcode != 200 ) {
            $this->Session->write('m', $this->OrigInvoice->create_json(101, 'Failed!'));
        } else if ($httpcode == 200) {
            $this->Session->write('m', $this->OrigInvoice->create_json(201, 'Regenerated successfully!'));
        }
        $this->redirect("view/$type");
    }
    
    public function getClientPaymentTerm($clientId)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = <<<SQL
SELECT payment_term.*, client.did_invoice_include 
FROM client 
LEFT JOIN payment_term ON payment_term.payment_term_id = client.payment_term_id 
WHERE client.client_id = {$clientId}
LIMIT 1
SQL;

        $data = $this->OrigInvoice->query($sql);

        echo json_encode($data[0][0]);
        exit;
    }
}