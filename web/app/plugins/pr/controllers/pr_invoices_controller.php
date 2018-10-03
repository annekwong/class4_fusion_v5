<?php

class PrInvoicesController extends PrAppController
{

    var $name = 'PrInvoices';
    var $helpers = array('Pr.AppPrInvoices');
    var $uses = array("pr.Invoice", 'pr.InvoiceLog', 'Systemparam', 'Transaction', 'ApiLog');
    var $components = array('RequestHandler');
    var $invoiceAddress;

    public function add_invocie_item($invoice_id)
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
        {
            $this->redirect_denied();
        }

        $flag = true;

        if (isset($_POST['positions']))
        {
            foreach ($_POST['positions'] as $key => $value)
            {
                $name = isset($value['name']) ? $value['name'] : '';
                $price = isset($value['price']) ? $value['price'] : '0';
                if (empty($name) || empty($price))
                {
                    continue;
                }
                else
                {
                    $sql = "insert into invoice_item(invoice_id,item,price)values($invoice_id,'$name',$price);";
                    $r = $this->Invoice->query($sql);
                    //插入异常
                    if (!is_array($r))
                    {
                        $flag = false;
                        break;
                    }
                }
            }
        }
        return $flag;
    }

    public function download_invoice($invoice_number)
    {
//        $invoice_number = base64_decode($invoice_number);
        $this->createpdf_invoice($invoice_number);
    }

    public function mail_invoice()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $ids = $_POST['ids'];
        $sendResult = true;

        foreach ($ids as $id)
        {
            $result = $this->resend($id, 1, false);

            if ($result != 'OK') {
                $sendResult = false;
                break;
            }
        }

        ob_clean();
        if ($sendResult) {
            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
        }
        exit;

    }

    public function apply_payment($invoice_id, $create_type)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $invoice = $this->Invoice->findByInvoiceId($invoice_id);
        if ($this->RequestHandler->isPost())
        {
            $payment_ids = $_POST['payment_ids'];
            if (empty($payment_ids)){
                $this->Invoice->create_json_array('', 101, __('apply_nothing_to_do',true));
                $this->Session->write("m", Invoice::set_validator());
                if ($create_type == 'incoming')
                    $this->redirect('/pr/pr_invoices/incoming_invoice');
                else
                    $this->redirect('/pr/pr_invoices/view/' . $create_type);
            }
            $should_pay_amount = $invoice['Invoice']['total_amount'] - $invoice['Invoice']['pay_amount'];
            if(!is_array($payment_ids)) {
                $payment_ids = explode(',', $payment_ids);
            }
//            $saveArray = array();
//            foreach ($payment_ids as $payment_id)
//            {
//                $payment = $this->Transaction->find('first', array(
//                    'conditions' => array(
//                        'client_payment_id' => $payment_id
//                    )
//                ));
//                $payment['Transaction']['invoice_number'] = $invoice_id;
//                $payment['Transaction']['payment_time'] = date('Y-m-d H:i:s');
//                $payment['Transaction']['receiving_time'] = date('Y-m-d H:i:s');
//                $payment['Transaction']['payment_type'] = 4;
//                $payment['Transaction']['approved'] = true;
//                $payment['Transaction']['update_by'] = 'admin';
//                unset($payment['Transaction']['client_payment_id']);
//                array_push($saveArray, $payment['Transaction']);
//            }
//            if(!empty($saveArray)) {
//                $this->Transaction->saveAll($saveArray);
//            }
            if (!$invoice['Invoice']['paid'])
            {
                foreach ($payment_ids as $payment_id)
                {
                    $payment = $this->Invoice->get_client_payment($payment_id);
                    $remain_amount = $payment['amount'] - $payment['used_amount'];
                    if ($remain_amount > 0)
                    {
                        if ($remain_amount >= $should_pay_amount)
                        {
                            $invoice['Invoice']['pay_amount'] += $should_pay_amount;
                            $invoice['Invoice']['paid'] = true;
                            $this->Invoice->save($invoice);
                            $remain_amount -= $should_pay_amount;
                            if ($payment['remain_id'])
                            {
                                if ($remain_amount > 0)
                                    $this->Invoice->update_payment_invoice($payment['remain_id'], $remain_amount);
                                else
                                    $this->Invoice->delete_payment_invoice($payment['remain_id']);
                            } else
                            {
                                if ($remain_amount > 0)
                                    $this->Invoice->insert_remain_payment_invoice($payment_id, $remain_amount);
                            }
                            $this->Invoice->insert_payment_invoice($payment_id, $invoice_id, $should_pay_amount);
                            break;
                        } else
                        {
                            $invoice['Invoice']['pay_amount'] += $remain_amount;
                            if ($payment['remain_id'])
                            {
                                $this->Invoice->delete_payment_invoice($payment['remain_id']);
                            }
                            $this->Invoice->insert_payment_invoice($payment_id, $invoice_id, $remain_amount);
                            $this->Invoice->save($invoice);
                        }
                    }
                }
            }
            $this->Invoice->create_json_array('', 201, 'The payments you selected is applied successfully!');
            $this->Session->write("m", Invoice::set_validator());
            if ($create_type == 'incoming')
            {
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo '1';
                } else {
                    $this->redirect('/pr/pr_invoices/incoming_invoice');
                }

            }
            else
            {
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo '2';
                } else {
                    $this->redirect('/pr/pr_invoices/view/' . $create_type);
                }
            }
            exit;
        }
        $payments = $this->Invoice->get_client_payments($invoice['Invoice']['client_id'], $create_type);
        $this->set('payments', $payments);
        $this->set('invoice_id', $invoice_id);
        $this->set('create_type', $create_type);
    }


    public function invoice_log()
    {
        parent::beforeFilter();

        $type = $this->Session->read('login_type');
        if ($type == 3)
        {
            $this->xredirect('/clients/carrier/');
        }

        $this->pageTitle = "Finance/Invoice Log";

        $this->set('status', array(
            'In Progress',
            'In Progress',
            'Done',
            'Error',
        ));

        $this->set('sub_status', array(
            '-1' => 'Only Support Buy/Sell',
            0 => 'In Progress',
            1 => 'Zero CDR',
            2 => 'Done',
            3 => 'Sent'
        ));


        $get_data = $this->params['url'];

        $conditions = array();

        if (isset($get_data['time']) && $get_data['time'])
        {
            $conditions[] = "start_time <= '" . $get_data['time'] . "'";
            $conditions[] = "end_time >= '" . $get_data['time'] . "'";
        }
        else
            $conditions[] = "start_time >= '".date('Y-m-d 00:00:00',strtotime("-100 day")) ."'";

        $this->set('get_data', $get_data);
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once MODELS . DS . 'MyPage.php';
        $counts = $this->InvoiceLog->get_count($conditions);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->InvoiceLog->get_data($conditions, $pageSize, $offset);
        foreach ($data as $key => &$item)
            $item[0]['invoices'] = $this->InvoiceLog->get_invoices($item[0]['log_id']);

        $page->setDataArray($data);
        $this->set('p', $page);
    }


    public function invoice_log_bak()
    {
        parent::beforeFilter();

        $type = $this->Session->read('login_type');
        if ($type == 3)
        {
            $this->xredirect('/clients/carrier/');
        }

        $this->pageTitle = "Finance/Invoice Log";


        $get_data = $this->params['url'];

        $this->set('get_data', $get_data);

        $conditions_arr = array();

        if (isset($get_data['time']) && $get_data['time'])
        {
            $conditions_arr[] = "start_time <= '" . $get_data['time'] . "'";
            $conditions_arr[] = "end_time >= '" . $get_data['time'] . "'";
        }
        else
            $conditions_arr['start_time >= ?'] = date('Y-m-d 00:00:00',strtotime("-60 day"));

        $conditions_arr[] = 'exists (select 1 from (select sum(total_amount) as total from invoice where invoice_log_id = InvoiceLog.id) as t1 WHERE t1.total > 0)';

        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'InvoiceLog.id' => 'desc',
            ),
            'conditions' => $conditions_arr,
        );

        $this->data = $this->paginate('InvoiceLog');
        foreach ($this->data as $key => &$item)
            $item['InvoiceLog']['invoices'] = $this->InvoiceLog->get_invoices($item['InvoiceLog']['id']);


        $this->set('status', array(
            'In Progress',
            'In Progress',
            'Done',
            'Error',
        ));

        $this->set('sub_status', array(
            '-1' => 'Only Support Buy/Sell',
            0 => 'In Progress',
            1 => 'Zero CDR',
            2 => 'Done',
            3 => 'Sent'
        ));
    }

    function cdr_download($type = 'ingress', $invoice_number = '')
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        if ($type == 'ingress')
        {
            $field = 'ingress_cdr_file';
        }
        elseif ($type == 'egress')
        {
            $field = 'egress_cdr_file';
        }

        if (!empty($invoice_number))
        {
            $invoice_number = base64_decode($invoice_number);
        }
        else
        {
            $key = urldecode($_GET['key']);
            App::import('Helper', 'AppCommon');
            $appCommon = new AppCommonHelper();
            $invoice_number = $appCommon->dencrypt($key);
        }
        $sql = "select $field from invoice where invoice_number = '{$invoice_number}'";
        $result = $this->Invoice->query($sql);
        Configure::load('myconf');

        $file = ROOT . '/../' . 'script/storage/invoice_cdr/' . DS . $result[0][0][$field];

        if (!file_exists($file))
        {//文件不存在 
            $this->Session->write('m', $this->Invoice->create_json(101, 'File is not exists!'));
            $this->xredirect('/invoice_cdr_log/index/');
        }
        $filename = basename($file);

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        @readfile($file);
    }

    function download_cdr()
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        if (!empty($this->params['pass'][0]))
        {
            /* $this->Invoice->invoice_id = $this->params ['pass'] [0];
              $data=$this->Invoice->read(); */
            $data = $this->Invoice->find('first', array('conditions' => array('invoice_id' => intval($this->params['pass'][0]))));
            $start = $data['Invoice']['invoice_start'];
            $end = $data['Invoice']['invoice_end'];
            $client_id = $data['Invoice']['client_id'];
            if (!empty($start) && !empty($end))
            {
                $client_data = $this->Invoice->query("select * from client where client_id = {$client_id}");
                //$sql="select *  from  client_cdr where time  between   '$start'  and  '$end' ";
                $show_fields_arr = array();
                $total_show_fields_arr = array();
                $this->loadModel('Systemparam');
                $incoming_data = $this->Systemparam->get_daily_cdr_fields(0);
                foreach ($incoming_data as $field_sql => $show_name)
                {
                    $show_name = str_replace(' ','_',$show_name);
                    $total_show_fields_arr[] = $show_name;
                    $show_fields_arr[] = $field_sql ." as " . $show_name;
                }
                $show_fields = implode(",", $show_fields_arr);
                $client_cdr_table_arr = $this->x_get_date_result_admin($start,$end,'client_cdr');
                $union_sql_arr = array();
                $where_sql = "WHERE ingress_client_id={$client_id} AND time >= '{$start}' AND time <= '{$end}'";
                foreach ($client_cdr_table_arr as $item_table_name)
                {
                    $union_sql_arr[] = "SELECT {$show_fields} FROM {$item_table_name} {$where_sql}";
                }
                $sql = "SELECT " . implode(",",$total_show_fields_arr) . ' FROM (' . implode(" UNION ALL ",$union_sql_arr) ." ) as t";
                $this->layout = 'csv';
                $compress_format = empty($client_data[0][0]['cdr_list_format']) ? '2' : $client_data[0][0]['cdr_list_format'];

                switch ($compress_format)
                {
                    case '3':
                        $this->Invoice->export__sql_compress('download Cdr', $sql, 'cdr.zip', 'zip');
                        break;
                    case '4':
                        $this->Invoice->export__sql_compress('download Cdr', $sql, 'cdr.tar.gz', 'tar.gz');
                        break;
                    case '2':
                    case "1":
                    default:
                        $this->Invoice->export__sql_data('download Cdr', $sql, 'cdr');
                }
                $this->layout = 'csv';
                exit();
            }
        }
        else
        {
            $this->redirect('/pr/pr_invoices/view/');
        }
    }

    public function download_rate($invoice_id = null)
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $id_where = '';
        if (isset($_POST['ids']))
        {
            $id_str = '';
            foreach ($_POST['ids'] as $key => $value)
            {
                $id_str.="$value,";
            }
            $id_str = substr($id_str, 0, -1);
            $id_where = "where invoice_id in ($id_str)";
        }
        $download_sql = "select *,(select name from client where client_id = invoice.client_id) as client from invoice  $id_where";
        $this->Invoice->export__sql_data('Download Invoice', $download_sql, 'invoice');
        $this->layout = 'csv';
        exit();
    }

    /**
     * 修改invocie的状态
     *
     *
     */
    function mass_update()
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!isset($_POST['ids']))
        {
            $this->redirect("/pr/pr_invoices/view/{$this->params['pass'][0]}");
        }
        $ids = $_POST['ids'];
        $id_str = '';
        foreach ($_POST['ids'] as $key => $value)
        {
            $id_str.="$value,";
        }
        $id_str = substr($id_str, 0, -1);
        $action = $_POST['action'];
        if ($action == '-1' or $action == '0' or $action == '1')
        {
            $this->Invoice->query("update  invoice set state=$action  where invoice_id  in($id_str)");
        }

        if ($action == '9')
        {
            foreach ($ids as $id)
            {
                $this->resend($id, 0, false);
            }
        }

        if ($action == '00')
        {
            $this->Invoice->query("update  invoice set disputed=0  where invoice_id  in($id_str)");
        }
        if ($action == '11')
        {
            $this->Invoice->query("update  invoice set disputed=1  where invoice_id  in($id_str)");
        }

        if ($action == '8')
        {

            Configure::load('myconf');
            $invoice_path = Configure::read('generate_invoice.path');
            $invoice_name = $this->_get_invoice_name();

            $zip = new ZipArchive();
            $zip_path = APP . 'webroot' . DS . 'upload' . DS . 'invoice';
            $zip_file = $zip_path . DS . uniqid() . ".zip";

            $invoice_file_name = $invoice_name . "_" . date("Y-m-d") . "_" . ".zip";

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE) !== TRUE)
            {
                //exit("cannot open <$zip_file>\n");
            }

            foreach ($ids as $id)
            {
                $invoice = $this->Invoice->findByInvoiceId($id);
                $client_name = $this->Invoice->get_client_name($invoice['Invoice']['client_id']);
                $invoice_number = $invoice['Invoice']['invoice_number'];
                $invoice_date = $this->_get_invoice_date($invoice_number);
                $invoice_file = $invoice_path . DS . $invoice_number . '_invoice.pdf';
                $filename = $invoice_name . '_' . $client_name . '_' . $invoice_number . '_' . $invoice_date . '.pdf';
                if (!file_exists($invoice_file))
                {
                    $pdf_contents = file_get_contents($this->getUrl() . "pr/pr_invoices/createpdf_invoice/" . base64_encode($invoice_number));
                    file_put_contents($invoice_file, $pdf_contents);
                }

                $zip->addFile($invoice_file, $filename);
            }

            $zip->close();
            ob_clean();


            if (file_exists($zip_file))
            {
                header("Content-type: application/octet-stream");
                //处理中文文件名
                $ua = $_SERVER["HTTP_USER_AGENT"];
                $encoded_filename = rawurlencode($invoice_file_name);
                if (preg_match("/MSIE/", $ua))
                {
                    header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                }
                else if (preg_match("/Firefox/", $ua))
                {
                    header("Content-Disposition: attachment; filename*=\"utf8''" . $invoice_file_name . '"');
                }
                else
                {
                    header('Content-Disposition: attachment; filename="' . $invoice_file_name . '"');
                }
                readfile($zip_file);
                exit;
            }
            else
            {
                echo "File does not exist!";
            }
        }

        $this->redirect("/pr/pr_invoices/view/{$this->params['pass'][0]}");
    }

    //初始化查询参数
    function init_query()
    {

        //$this->set('currency', $this->Invoice->find_currency());
    }

    public function _get_invoice_name()
    {
        $sql = "SELECT invoice_name FROM system_parameter LIMIT 1";
        $data = $this->Invoice->query($sql);
        return $data[0][0]['invoice_name'];
    }

    public function _get_invoice_date($invoice_number)
    {
        $sql = "SELECT invoice_time::DATE FROM invoice WHERE invoice_number = '{$invoice_number}'";
        $data = $this->Invoice->query($sql);
        return $data[0][0]['invoice_time'];
    }

    public function createpdf_invoice($invoice_number, $did = null)
    {
        Configure::load('myconf');
        Configure::write("debug", "0");

        $database_export_path = Configure::read('invoice_download_dir');
        $invoice_path = Configure::read('invoice_url');
        $int_invoice_number = intval($invoice_number);
        if (strcmp($int_invoice_number, $invoice_number)) {
            $invoice_number = base64_decode($invoice_number);
        }

        $sql = "SELECT * FROM invoice WHERE invoice_number = '{$invoice_number}'";
        $data = $this->Invoice->query($sql);
        $pdfFile = $data[0][0]['pdf_path'];
        $clientId = $data[0][0]['client_id'];
        $invoiceStart = strtotime($data[0][0]['invoice_start']);
        $invoiceEnd = strtotime($data[0][0]['invoice_end']);
        $invoice_date = $this->_get_invoice_date($invoice_number);
        $filename = $invoice_number . '_' . $invoice_date . '.pdf';
        ob_start();

        if ($did) {
            $pdfUrl = explode(":", $invoice_path)[0] . ":18881/invoice/client/{$clientId}/end/{$invoiceEnd}/start/{$invoiceStart}";
            $content = file_get_contents($pdfUrl);
            if ($content != false && strpos($content, "Bad Request") === false) {
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
                $this->Session->write('m', $this->Invoice->create_json(101, 'File not found'));
                $this->redirect($_SERVER["HTTP_REFERER"]);
            }
        } else if (!empty($pdfFile)) {
//            $pdfUrl = $invoice_path .DS. $pdfFile;
            $pdfUrl = $database_export_path .DS. $pdfFile;
            $content = file_get_contents($pdfUrl);

            // generate new file
            if($content === false){
//                $url = $this->getUrl();
//                App::import("Vendor", "other", array('file' => 'tcpdf/tcpdf.php'));
//                $html = $this->Invoice->generate_pdf_content($invoice_number,$url);
//                ob_start();
//                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//                $pdf->AddPage();
//                $pdf->setPageUnit('pt');
//                $font_size = $pdf->pixelsToUnits('14');
//                $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
//                $pdf->writeHTML($html, true, 0, true, 0);
//                $pdf->lastPage();
                $pdfUrl = $invoice_path .DS. $pdfFile;
                $content=file_get_contents($pdfUrl);

                if ($content) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');

                    ob_clean();
                    echo $content; exit;
                } else {
                    $this->Session->write('m', $this->Invoice->create_json(101, 'Unable to get PDF file from API'));
                    $this->redirect('/pr/pr_invoices');
                }
                ob_end_flush();
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');

            ob_clean();
            echo $content; exit;
        } else {
            $this->Session->write('m', $this->Invoice->create_json(101, 'File not found'));
            $this->redirect('/homes/login');
        }

    }

    public function send_pdf($invoice_number)
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        App::import("Model", 'pr.Invoice');
        $invoice_model = new Invoice;
        $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
        $url = $this->getUrl();
        $html = $invoice_model->generate_pdf_content($invoice_number, $url, $num_format);
        file_put_contents('/tmp/invoice.pdf', $html);
    }

//生成xls
    public function createxls_invoice($invoice_number)
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        App::import("Model", 'pr.Invoice');
        $invoice_model = new Invoice;

        $invoice_name = $this->_get_invoice_name();
        $invoice_date = $this->_get_invoice_date($invoice_number);
        $filename = $invoice_name . '_' . $invoice_number . '_' . $invoice_date . '.doc';
        $url = $this->getUrl();
        $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
        $html = $invoice_model->generate_pdf_content($invoice_number, $url, $num_format);

        $html = <<<EOT
   <html xmlns:v="urn:schemas-microsoft-com:vml"  
xmlns:o="urn:schemas-microsoft-com:office:office"  
xmlns:w="urn:schemas-microsoft-com:office:word"  
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"  
xmlns="http://www.w3.org/TR/REC-html40">  
    {$html}
</html>
EOT;
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header('Content-type: application/doc; charset=UTF-8');
        header("Content-Disposition: inline; filename=\"" . $filename . "\"");
        die($html);
    }

//生成html
    public function createhtml_invoice($invoice_number)
    {
        App::import("Model", 'pr.Invoice');
        $invoice_model = new Invoice;
        $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
        $url = $this->getUrl();
        $html = $invoice_model->generate_pdf_content($invoice_number, $url, $num_format);
        Configure::write('debug', 0);
        $this->autoRender = false;
        //App::import("Vendor","tcpdf",array('file'=>"tcpdf/pdf.php"));
        //$invoice_pdf = create_PDF("invoice",$html);
        //return $html;
        echo $html;
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
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
        Configure::load('myconf');
        $this->invoiceAddress = Configure::read('invoice_url');
        parent::beforeFilter();
    }

    public function show_carriers($name = '')
    {
        $this->autoLayout = false;
        if (!empty($name))
        {
            $condition = "where name like '%{$name}%'";
        }
        else
        {
            $condition = '';
        }
        $sql = "select client_id, name, status from client $condition order by name";
        $result = $this->Invoice->query($sql);
        $this->set("clients", $result);
        $this->set('name', $name);
    }

    public function validate()
    {
        $error_flag = 'false';
        $invoice_number = '';
//        $state = isset($_POST['state']) ? $_POST['state'] : '';
        $type = $_POST ['type'];
        $due_date = $_POST ['due_date'];
        //$total_amount = $_POST ['total_amount'];
        $start_date = $_POST ['start_date']; //开始日期
        $stop_date = $_POST ['stop_date']; //结束日期
        $gmt = $_POST["query"]['tz'];
        $carriers = $_POST['client_id'];


        #  check invoice number  Repeatability
        if (!empty($invoice_number))
        {
            // Never here
            $c = $this->Invoice->query("select  count(*)  from invoice  where  invoice_number='$invoice_number';");
            if ($c[0][0]['count'] > 0)
            {
                $this->Invoice->create_json_array('#invoice_number', 101, 'invoice Number Repeatability');
                $error_flag = true;
            }
        }
        else
        {
            //check invoice date duplicate
            $system_settings = $this->Invoice->query("select overlap_invoice_protection from system_parameter limit 1");
            if ($system_settings[0][0]['overlap_invoice_protection'])
            {
                $type_where = $type == 2 ? " and (\"type\" = 0 or \"type\" = 1)" : (" and \"type\" = " . intval($type) );
                $dupli_sql = "select *  from invoice where state != -1 and client_id in ($carriers) and ( (invoice_end >= TIMESTAMP '{$start_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL and invoice_start <= TIMESTAMP '{$start_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL) or (invoice_end >= TIMESTAMP '{$stop_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL and invoice_start <= TIMESTAMP '{$stop_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL) ) {$type_where}";
                $dupli_result = $this->Invoice->query($dupli_sql);
                if (!empty($dupli_result))
                {
                    $message = "The invoice you are trying to generate is overlapping with the following invoice(s), and will not be executed:";

                    $this->Invoice->create_json_array('#query-start_date-wDt', 101, $message);
                    foreach ($dupli_result as $dupli_item)
                    {
                        $message2 = "Invoice:#{$dupli_item[0]['invoice_number']} [{$dupli_item[0]['invoice_start']} ~ {$dupli_item[0]['invoice_end']}]";
                        $this->Invoice->create_json_array('#query-start_date-wDt', 101, $message2);
                    }
                    $error_flag = true;
                }
            }
        }

        if (empty($carriers))
        {
            $this->Invoice->create_json_array('#query-id_clients_name', 101, __('clientnamenull', true));
            $error_flag = true;
        }

        if (empty($due_date))
        {
            $this->Invoice->create_json_array('#due_date', 101, 'Invoice Date/Due (days) is  Empty!');
            $error_flag = true;
        }

        /* 				if(empty($total_amount)){
          $this->Invoice->create_json_array ( '#total_amount', 101, 'Total is  null');
          $error_flag = true;
          } */
        return $error_flag;
    }

    private function manualAdd()
    {
        Configure::write('debug', 0);

        if($this->RequestHeader->isPost()) {

        }

    }

    public function add($type)
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
        {
            $this->redirect_denied();
        }

        $invoiceUrl = Configure::read('invoice_url');

        $this->set('type', $type);
        $this->set('invoiceUrl', $invoiceUrl);
        //$clients = $this->Invoice->query("select client_id, name from client order by name asc");
        //$this->set('clients', $clients);
        //$arrCarriers = $this->Invoice->query("select name from client order by name asc"); //获取运营商name列表
        //$this->set('arrCarriers', $arrCarriers);


        if (!empty($_POST))
        {
            Configure::write("debug", 2);
            $error_flag = $this->validate();
            if ($error_flag != '1')
            {
                $carriers_arr = explode(',', $_POST['client_id']);
                $carriers_arr = array_unique($carriers_arr);
                array_walk($carriers_arr, create_function('&$item, $key', '$item = "-i {$item}";'));
                $carrier_cmd = implode(" ", $carriers_arr);

                // TODO Log
                $sql = "insert into invoice_log(start_time) values (CURRENT_TIMESTAMP(0)) returning id";
                $log_result = $this->Invoice->query($sql);
                $log_id = $log_result[0][0]['id'];

                //$result = $this->Invoice->query("select client_id from client where name = '{$_POST ['query'] ['id_clients']}'");
                //$this->data['Invoice']['client_id'] = $result[0][0]['client_id'];
                //			$seq_list=$this->Invoice->query("select    nextval('class4_seq_invoice_no'::regclass)");
                //		$seq=$seq_list[0][0]['nextval'];
                $this->data['Invoice']['invoice_time'] = !empty($_POST ['invoice_time']) ? $_POST['invoice_time'] : '';
                $this->data['Invoice']['invoice_number'] = !empty($_POST ['invoice_number']) ? $_POST['invoice_number'] : uniqid('invocie');
                //		$this->data['Invoice']['invoice_number'] = !empty($_POST ['invoice_number'])?$_POST['invoice_number']:$seq.substr(time(),0,6);
//                $this->data['Invoice']['state'] = isset($_POST ['state']) ? intval($_POST ['state']) : '';
                $this->data['Invoice']['type'] = intval($_POST ['type']);
                $this->data['Invoice']['create_type'] = 1;
                $this->data['Invoice']['due_date'] = $_POST ['due_date'];
                $this->data['Invoice']['invoice_zone'] = $_POST['query']['tz'];
                $start_date = $_POST ['start_date']; //开始日期
                $start_time = $_POST ['start_time']; //开始时间
                $stop_date = $_POST ['stop_date']; //结束日期
                $stop_time = $_POST ['stop_time']; //结束时间
                $tz = $_POST ['query']['tz']; //结束时间
                $this->data['Invoice']['invoice_start'] = $start_date . ' ' . $start_time . ' ' . $tz; //开始时间
                $this->data['Invoice']['invoice_end'] = $stop_date . ' ' . $stop_time . ' ' . $tz; //结束时间
                $list = $this->Invoice->query("select  balance   from  c4_client_balance  where client_id='{$_POST ['query'] ['id_clients']}'");
                $this->data['Invoice']['current_balance'] = !empty($list[0][0]['balance']) ? $list[0][0]['balance'] : '0.000';
                $this->data['Invoice']['output_type'] = $_POST['output_type'];
                $this->data['Invoice']['include_detail'] = isset($_POST['include_detail']) ? 1 : 0;
                $this->data['Invoice']['invoice_jurisdictional_detail'] = isset($_POST['jur_detail']) ? 1 : 0;
                $this->data['Invoice']['decimal_place'] = $_POST['decimal_place'];
                $this->data['Invoice']['rate_value'] = $_POST['rate_value'];
                $this->data['Invoice']['is_invoice_account_summary'] = isset($_POST['is_invoice_account_summary']) ? 1 : 0;
                $this->data['Invoice']['is_show_daily_usage'] = isset($_POST['is_show_daily_usage']) ? 1 : 0;
                $this->data['Invoice']['is_show_short_duration_usage'] = isset($_POST['is_show_short_duration_usage']) ? 1 : 0;
                $this->data['Invoice']['invoice_include_payment'] = isset($_POST['is_show_payments']) ? 1 : 0;
                $this->data['Invoice']['usage_detail_fields'] = isset($_POST['usage_detail_fields']) ? implode(',', $_POST['usage_detail_fields']) : '';
                $this->data['Invoice']['invoice_use_balance_type'] = isset($_POST['invoice_use_balance_type']) ? $_POST['invoice_use_balance_type'] : '';

                $this->data['Invoice']['is_show_detail_trunk'] = isset($_POST['is_show_detail_trunk']) ? 1 : 0;
                $this->data['Invoice']['is_show_total_trunk'] = isset($_POST['is_show_total_trunk']) ? 1 : 0;

                $this->data['Invoice']['is_show_code_100'] = isset($_POST['is_show_code_100']) ? 1 : 0;
                $this->data['Invoice']['is_show_code_name'] = isset($_POST['is_show_code_name']) ? 1 : 0;
                $this->data['Invoice']['is_show_country'] = isset($_POST['is_show_country']) ? 1 : 0;
                $this->data['Invoice']['is_show_by_date'] = isset($_POST['is_show_by_date']) ? 1 : 0;


                $name = $_SESSION['sst_user_name'];
                Configure::load('myconf');
                $script_path = Configure::read('script.path');
//                $exec_path = $script_path . DS . "class4_invoice_newcdr.pl";
                $exec_path = $script_path . DS . "class4_invoice.pl";
                $exec_conf_path = Configure::read('script.conf');
                $url = $this->getUrl() . "pr/pr_invoices/createpdf_invoice";
                $this->data['Invoice']['invoice_use_balance_type'] = 1;
                $cmd = "perl {$exec_path} -n '{$this->data['Invoice']['invoice_number']}' -c {$exec_conf_path} -s '{$this->data['Invoice']['invoice_start']}' -e '{$this->data['Invoice']['invoice_end']}' {$carrier_cmd} -y {$this->data['Invoice']['type']} -z '{$this->data['Invoice']['invoice_zone']}' -t '{$this->data['Invoice']['invoice_time']}' -d '{$this->data['Invoice']['due_date']}' -u '{$url}' -o {$this->data['Invoice']['output_type']} -l {$this->data['Invoice']['include_detail']} -j {$this->data['Invoice']['invoice_jurisdictional_detail']} -p {$this->data['Invoice']['decimal_place']} -r {$this->data['Invoice']['rate_value']} -f {$this->data['Invoice']['is_invoice_account_summary']} -v {$this->data['Invoice']['is_show_daily_usage']} -k {$this->data['Invoice']['is_show_short_duration_usage']} -g {$this->data['Invoice']['invoice_include_payment']} -w '{$this->data['Invoice']['usage_detail_fields']}' -q '{$name}' -b 1 --show_total_trunk {$this->data['Invoice']['is_show_total_trunk']}  --show_detail_trunk {$this->data['Invoice']['is_show_detail_trunk']}  --is_show_code_100 {$this->data['Invoice']['is_show_code_100']}  --is_show_code_name {$this->data['Invoice']['is_show_code_name']} --is_show_country {$this->data['Invoice']['is_show_country']} --is_show_by_date {$this->data['Invoice']['is_show_by_date']}  --log {$log_id} --btype {$this->data['Invoice']['invoice_use_balance_type']} > /dev/null &";
                //echo $cmd;exit;
                $info = $this->Systemparam->find('first',array(
                    'fields' => array('cmd_debug'),
                ));
                if(Configure::read('cmd_debug'))
                {
                    echo $cmd;
                    file_put_contents($info["Systemparam"]["cmd_debug"],$cmd);
                }
                $result = shell_exec($cmd);
//                die(var_dump($result));
                //$shell = Configure::read('php_exe_path')." ".APP."alert_invoice_email.php  &";
                //shell_exec($shell);
                //$this->Invoice->logging(0, 'Invoice', "Invoice Number:0000");
                $this->Invoice->create_json_array('#credit', 201, 'Manual client invoice is created successfully!');
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect("/pr/pr_invoices/view/{$type}");
            }
            else
            {
                // $this->Invoice->create_json_array('#ClientOrigRateTableId', 101, 'Failed!');
                $this->set('m', Invoice::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                return;
            }
            //pr($r);exit;
            $result_arr = explode("\n", $result);
            $result_line = explode(":", $result_arr[1]);
            $invoice_number = trim($result_line[1]);
            if (!empty($invoice_number))
            {
                if ('xls' == $_POST['output'])
                {
                    $this->createxls_invoice($invoice_number);
                }
                elseif ('html' == $_POST['output'])
                {
                    $this->createhtml_invoice($invoice_number);
                    if (!empty($invoice_number))
                    {
                        $this->createhtml_invoice($r0[0][0]['create_client_invoice']);
                    }
                }
                else
                {
                    $ch = curl_init();
                    $fp = fsockopen($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $errno, $errstr, 30);
                    if (!$fp)
                    {
                        echo "$errstr ($errno)<br />\n";
                    }
                    else
                    {
                        $invoice_number = base64_encode($invoice_number);
                        $out = "GET {$this->webroot}pr/pr_invoices/createpdf_invoice/{$invoice_number} HTTP/1.1\r\n";
                        $out .= "Host: localhost\r\n";
                        $out .= "Connection: Close\r\n\r\n";

                        fwrite($fp, $out);
                        /*
                          忽略执行结果
                          while (!feof($fp)) {
                          echo fgets($fp, 128);
                          }
                         */
                        fclose($fp);
                    }
                    /*
                      echo '111';exit;
                      $pdf_name = $this->createpdf_invoice($invoice_number); //生成invoice
                      pg_query("update invoice set pdf_path='{$pdf_name}'");
                      if (!empty($r0[0][0]['create_client_invoice'])) {
                      $pdf_name0 = $this->createpdf_invoice($invoice_number); //生成invoice
                      pg_query("update invoice set pdf_path='{$pdf_name0}'");
                      }
                     *
                     */
                    $this->redirect("/pr/pr_invoices/view/{$type}");
                }
            }
            else
            {
                $this->Invoice->create_json_array('#ClientOrigRateTableId', 201, 'Create fail!No CDR in this peroid.');
                $this->set('m', Invoice::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                return;
            }
        }
        else
        {
            $this->init_query();
        }

        $this->loadModel('Client');

        $clients = $this->Client->find('all', array(
            'conditions' => array(
                'status' => true,
                'client_type' => null
            ),
            'order' => array('name')
        ));

        $this->set('clients', $clients);

    }

    public function edit()
    {
        //	Configure::write('debug',0);
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($_POST))
        {
            $invoice_id = $_POST ['invoice_id'];


//			 $state = $_POST ['state'];
//			 $credit=$_POST['credit'];
            if (empty($invoice_id))
            {
                $this->Invoice->create_json_array('#credit', 101, 'invoice is corrupted or does not exists.');
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect("/pr/pr_invoices/edit/$invoice_id");
            }
//				$invoice_info = $this->invoice_info($invoice_id);
//				var_dump($invoice_info);exit;
//				if(!preg_match('/^[+\-]?\d+(.\d+)?$/',$credit)){
//					$this->Invoice->create_json_array ( '#credit', 101, 'Invoice has zero credit, no sense in creation.');
//					 $this->Session->write("m",Invoice::set_validator ());
//		     $this->redirect("/pr/pr_invoices/edit/$invoice_id");
//				}
//				else
//				{
//					$this->Invoice->create_json_array ( '#credit', 101, 'Invoice credit must less than invoice amount .');
//					$this->Session->write("m",Invoice::set_validator ());
//		     $this->redirect("/pr/pr_invoices/edit/$invoice_id");
//				}
//			 $r=$this->Invoice->query("update invoice set state=$state ,credit_amt= $credit  where   invoice_id=$invoice_id");
            if (0)
            {//(!is_array($r)) {
                $this->Invoice->create_json_array('#ClientOrigRateTableId', 101, 'Database  Error');
                $this->Session->write("m", Invoice::set_validator());
                // $this->redirect("/invoices/edit/$invoice_id");
            }
            else
            {
                $file_arr = $this->_move_upload_file('Invoice');
                //				if (!empty($file_arr[0]))
                //				{
                //					$this->Invoice->query("update invoice set pdf_path='{$file_arr[0]}'  where  invoice_id=$invoice_id");
                //				}
                if (!empty($file_arr))
                {
                    $this->Invoice->query("update invoice set cdr_path='{$file_arr}'  where  invoice_id=$invoice_id");
                }
                $this->Invoice->create_json_array('#ClientOrigRateTableId', 201, 'Edit success');
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect('/pr/pr_invoices/view');
            }
        }
        else
        {

            $this->init_query();
            $invoice_id = $this->params['pass'][0];
            $list = $this->Invoice->query("SELECT invoice_id,invoice_number,state,type,invoice.client_id,invoice.invoice_time,invoice.invoice_start,
			                            invoice.invoice_end, invoice.send_time, invoice.total_amount::numeric(20,2)  as amount1,   invoice.paid,invoice.due_date,
                                 invoice.pay_amount::numeric(20,2),invoice.credit_amount::numeric(20,2),current_balance::numeric(20,2),client.name as client_name,invoice.cdr_path   from  invoice 
			                             left join client on client.client_id=invoice.client_id where invoice_id=$invoice_id   ");

            if (count($list) == 0)
            {

                $this->Invoice->create_json_array('#ClientOrigRateTableId', 101, ' invoice is corrupted or does not exists.');
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect('/invoices/view');
            }
            $this->set('list', $list);
        }
    }

    function index()
    {
        $this->redirect('view');
    }

    public function voidInvoice()
    {
        Configure::write('debug', 2);
        if($this->RequestHandler->isPost()) {
            $invoiceId = $_POST['invoiceId'];
            $url = "{$this->invoiceAddress}/voidinvoicepost";
            $ch = curl_init();
            $data = array(
                'invoice_number' => $invoiceId
            );
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $this->ApiLog->addRequest($url, $data, null, 2, $httpCode);

            $arrayResult = explode(':', $result);
            if(isset($arrayResult[0]) && $arrayResult[0] == 'Query executed') {
                if(isset($arrayResult[1])) {
                    $this->Invoice->query($arrayResult[1]);
                }
            }

            echo $result;
        }
        exit;
    }

    public function view($clientId = null, $did = false)
    {
        $this->pageTitle = "Finance/Invoices";
        $type = '0';
        if (isset($this->params['pass'][0]))
        {
            $type = $this->params['pass'][0];
        }
        $results = $this->Invoice->getInvoices($type, $clientId, $this->_order_condtions(array('invoice_number', 'paid', 'type', 'client', 'invoice_start', 'total_amount', 'invoice_time', 'pay_amount')));
        $this->set('p', $results);
        $this->set('create_type', $type);
        $this->set('url_get', $this->params['url']);
        $clients = $this->Invoice->query("select client_id, name from client order by name asc");
        $this->set('clients', $clients);
        $this->set('did', $did);
    }

    public function del($id)
    {
        $this->Invoice->query("delete from  invoice_calls  where invoice_no  in (select invoice_number  from  invoice where  invoice_id=$id)");
        if ($this->Invoice->del($id))
        {

            $this->Invoice->create_json_array('', 201, __('del_suc', true));
        }
        else
        {
            $this->Invoice->create_json_array('', 101, __('del_fail', true));
        }
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/invoices/view');
    }

    public function delete_invoice($invoice_id, $type)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql1 = <<<EOT
SELECT 
client_id,
invoice_number,
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (7, 8)) as credit, 
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (11, 12)) as debit
FROM 
invoice 
WHERE invoice_id = {$invoice_id};
EOT;
        $invoice_type = $this->Invoice->query($sql1);
        $credit = $invoice_type[0][0]['credit'];
        $debit = $invoice_type[0][0]['debit'];
        $client_id = $invoice_type[0][0]['client_id'];
        $invoice_number = $invoice_type[0][0]['invoice_number'];
        $sql_s = "SELECT amount FROM client_payment where invoice_number = '{$invoice_number}'";
        $result_s = $this->Invoice->query($sql_s);
        if ($type == '0' || $type == '1')
        {
            if (!empty($result_s))
                $s_amount = "+{$result_s[0][0]['amount']}";
            else
                $s_amount = "";

            $minusBalance = $credit + $debit + $s_amount;
            $this->Invoice->clientBalanceOperation($client_id, $minusBalance, 3);
        }
        $sql3 = "UPDATE payment_invoice set invoice_id = NULL WHERE invoice_id = '{$invoice_id}'";
        $this->Invoice->query($sql3);
        $this->Invoice->query("DELETE  FROM invoice WHERE invoice_id = {$invoice_id}");
        $this->Invoice->logging(1, 'Invoice', "Invoice Number:{$invoice_number}");
        $this->Invoice->create_json_array('', 201, __('The Invoice [%s] is deleted successfully!', true, $invoice_number));
        $this->delete_invoice_file($invoice_number);
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/pr/pr_invoices/view/' . $type);
    }

    public function get_invoice_payments($invoice_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;

        $invoice = $this->Invoice->findByInvoiceId($invoice_id);
        //print_r($invoice);
        $total_amount = $invoice['Invoice']['total_amount'];

        $payments = $this->Invoice->get_invoice_payments($invoice_id);

        $this->set('total_amount', $total_amount);
        $this->set('payments', $payments);
    }

    public function delete_incoming($invoice_id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql1 = <<<EOT
SELECT 
client_id,
invoice_number,
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (7, 8)) as credit, 
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (11, 12)) as debit,
(SELECT COALESCE(sum(amount), 0) FROm client_payment WHERE invoice_number = invoice.invoice_number AND payment_type = 3) as pay_amount
FROM 
invoice 
WHERE invoice_id = {$invoice_id};
EOT;
        $invoice_type = $this->Invoice->query($sql1);
        $credit = $invoice_type[0][0]['credit'];
        $debit = $invoice_type[0][0]['debit'];
        $client_id = $invoice_type[0][0]['client_id'];
        $invoice_number = $invoice_type[0][0]['invoice_number'];
        $pay_amount = $invoice_type[0][0]['pay_amount'];

        $plusBalance = $credit - $debit + $pay_amount;
        $this->Invoice->clientBalanceOperation($client_id, $plusBalance, 4);

        $sql3 = "DELETE FROM client_payment WHERE invoice_number = '{$invoice_number}' AND payment_type in (3, 7, 8, 11, 12)";
        $this->Invoice->query($sql3);
        $this->Invoice->query("DELETE  FROM invoice WHERE invoice_id = {$invoice_id}");
        $this->Invoice->logging(1, 'Invoice', "Invoice Number:{$invoice_number}");
        $this->Invoice->create_json_array('', 201, __('Incoming Invoice [%s] is deleted successfully!', true, $invoice_number));
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/pr/pr_invoices/incoming_invoice');
    }

    function _move_upload_file($model)
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x'])
        {
            $this->redirect_denied();
        }
        $model_name = $model;
        App::import("Core", "Folder");
        $path = APP . 'tmp' . DS . 'upload' . DS . $model_name . DS . gmdate("Y-m-d", time());
        if (new Folder($path, true, 0777))
        {
            //$file[0] = $path . DS . time() . ".pdf";
            $file = $path . DS . time() . ".csv";
            //move_uploaded_file($_FILES ["file"] ["tmp_name"], $file);
//			if (!move_uploaded_file($_FILES ["attach"] ["tmp_name"], $file[0]))
//			{
//				$file[0] = '';
//			}
            if (!move_uploaded_file($_FILES ["attach_cdr"] ["tmp_name"], $file))
            {
                $file = '';
            }
            return $file;
        }
        else
        {
            throw new Exception("Create File Error,Please Contact Administrator.");
        }
    }

    function invoice_info($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $conditions = Array();
        if (!empty($id))
        {
            $conditions[] = "invoice.invoice_id='$id'";
        }
        $invoice_number = $this->_get('invoice_number');
        if ($invoice_number)
        {
            $conditions[] = "invoice.invoice_number='$invoice_number'";
        }
        $conditions = join($conditions, ' and ');
        if (!empty($conditions))
        {
            $conditions = ' and ' . $conditions;
        }
        $sql = "select invoice.client_id, client.name  as client_name, invoice.send_time, invoice_number,invoice_start,invoice_end,pay_amount,current_balance,due_date,paid
from invoice left join client on invoice.client_id =client.client_id where 1=1 $conditions";
        $this->data = $this->Invoice->query($sql);
    }

    function inv_clent($inv_id = null)
    {
        if (!empty($inv_id))
        {
            $sql_client = "select client_id as id from invoice where invoice_id=$inv_id	";
            $client_id = $this->Invoice->query($sql_client);
            $this->redirect('/clients/edit/' . $client_id[0][0]['id']);
        }
    }

    function delete_invoice_file($invoice_number)
    {
        Configure::load('myconf');
        $invoice_path = Configure::read('generate_invoice.path');

        $invoice_name = $this->_get_invoice_name();
        $invoice_date = $this->_get_invoice_date($invoice_number);

        $filename = $invoice_number . '_' . $invoice_date . '.pdf';

        $invoice_file = $invoice_path . DS . $filename;

        unlink($invoice_file);
    }

    function regenerate()
    {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $invoice_id = $_REQUEST['invoice_id'];
        if (empty($invoice_id))
        {
            echo 'Invoice Not Select.';
        }
        else
        {
            $this->data = $this->Invoice->findByInvoiceId($invoice_id);
            $this->Invoice->logging(2, 'Invoice', "Invoice Number:{$this->data['Invoice']['invoice_number']}");
            if (empty($this->data))
            {
                echo 'The Invoice id not found!';
            }
            elseif ($this->data['Invoice']['state'] == -1)
            {
                //re-generate
                $url = "{$this->invoiceAddress}/regenerate";

                $ch = curl_init();
                $data = array(
                    'invoice_number' => $this->data['Invoice']['invoice_number'],
                    'create_type' => 1
                );

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $this->ApiLog->addRequest($url, $data, null, 2, $httpCode);
                curl_close($ch);
                echo 'Re-generate Invoice Success.';
            }
            else
            {
                $this->Invoice->query("update invoice set state = -1 where invoice_id = " . intval($invoice_id));
                echo 'Set Invoice Void Success.';
                /* $this->Invoice->create_json_array('#ClientOrigRateTableId',201,'Set Invoice Void Success.');
                  $this->set ('m', Invoice::set_validator ()); //向界面设置验证信息
                  $this->redirect('pr/pr_invoices/invoices/view'); */
            }
        }
    }

    public function incoming_invoice()
    {

        $this->set('url_get', $this->params['url']);
        $results = $this->Invoice->get_in_invoice($this->_order_condtions(array('invoice_number', 'paid', 'type', 'client', 'invoice_start', 'total_amount', 'invoice_time')));
        $this->set('p', $results);
        $clients = $this->Invoice->query("select client_id, name from client order by name asc");
        $this->set('clients', $clients);
    }


    public function mass_add_incoming()
    {
        $this->loadModel('Transaction');
        if ($this->RequestHandler->isPost())
        {
            $counts = count($_POST['client_id']);
            $start = $_POST['start'];
            $end = $_POST['end'];
            $gmt = $_POST['gmt'];
            for ($i = 0; $i < $counts; $i++)
            {
                $sql = "SELECT nextval('class4_seq_invoice_no'::regclass) AS next_number";
                $invoice_number_result = $this->Invoice->query($sql, false);
                print_r($invoice_number_result);
                $invoice_number = $invoice_number_result[0][0]['next_number'];
                $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
                $invoice_number_result = $this->Invoice->query($sql);
                if ($invoice_number_result[0][0]['count'] > 0)
                {
                    $sql = "SELECT setval('class4_seq_invoice_no', (select max(invoice_number::bigint)+1 from invoice)) as next_number";
                    $invoice_number_result = $this->Invoice->query($sql);
                    $invoice_number = $invoice_number_result[0][0]['next_number'];
                }

                $client_id = $_POST['client_id'][$i];
                $invoice_amount = $_POST['invoice_amount'][$i];
                $due_date = date("Y-m-d");
                $invoice_time = date("Y-m-d");
                $file_path = 'NULL';

                $sql = <<<EOT
    
   INSERT INTO 

invoice(invoice_number,client_id, invoice_time, invoice_start,invoice_end, 
due_date, type, invoice_zone, pdf_path, total_amount, current_balance, pay_amount)

VALUES('{$invoice_number}', '{$client_id}', '{$invoice_time}', TIMESTAMP '{$start} {$gmt}', 

TIMESTAMP '{$end} {$gmt}', '{$due_date}',3, '{$gmt}', '{$file_path}', {$invoice_amount}, 
(SELECT balance::numeric FROM c4_client_balance WHERE client_id = '{$client_id}')
,0)

EOT;
                $this->Transaction->query($sql);
                $this->Transaction->logging(0, 'Invoice', "Invoice Number:{$invoice_number}");
                $this->Transaction->create_json_array('', 201, __('The Invoice[%s] is created successfully!', true, $invoice_number));
            }
            $this->Session->write('m', Transaction::set_validator());
            $this->redirect('/pr/pr_invoices/incoming_invoice');
        }
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
    }

    public function add_incoming()
    {
        $this->loadModel('Transaction');
        if ($this->RequestHandler->isPost())
        {
            //die;
            $invoice_number = $_POST['invoice_number'];
            $client_id = $_POST['client_id'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $gmt = $_POST['gmt'];
            $invoice_amount = $_POST['invoice_amount'];
            $paid_amount = !empty($_POST['paid_amount']) ? $_POST['paid_amount'] : 0;
            $due_date = $_POST['due_date'];
            $invoice_time = $_POST['invoice_date'];
            $file_path = 'NULL';

            if (empty($invoice_number))
            {
                $sql = "SELECT nextval('class4_seq_invoice_no'::regclass) AS next_number";
                $invoice_number_result = $this->Invoice->query($sql);
                $invoice_number = $invoice_number_result[0][0]['next_number'];
                $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
                $invoice_number_result = $this->Invoice->query($sql);
                if ($invoice_number_result[0][0]['count'] > 0)
                {
                    $sql = "SELECT setval('class4_seq_invoice_no', (select max(invoice_number::bigint)+1 from invoice)) as next_number";
                    $invoice_number_result = $this->Invoice->query($sql);
                    $invoice_number = $invoice_number_result[0][0]['next_number'];
                }
            }
            else
            {
                $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
                $invoice_number_result = $this->Invoice->query($sql);
                if ($invoice_number_result[0][0]['count'] > 0)
                {
                    $this->Transaction->create_json_array('', 101, __('Invoice number duplicate!', true));
                    $this->Session->write('m', Transaction::set_validator());
                    $this->redirect('/pr/pr_invoices/add_incoming');
                }
            }

            if (is_uploaded_file($_FILES['invoice_file']['tmp_name']))
            {
                $upload_dir = APP . 'webroot' . DS . 'upload/incoming_invoice/';
                $extension = pathinfo($_FILES['invoice_file']['name'], PATHINFO_EXTENSION);
                $name = "invoice_" . substr(md5(microtime()), 0, 5) . '.' . $extension;
                $destname = $upload_dir . $name;
                $result = move_uploaded_file($_FILES['invoice_file']['tmp_name'], $destname);
                $file_path = $name;
            }



            //$sql = "INSERT INTO invoice (invoice_number, client_id, invoice_start, invoice_end, total_amount, pay_amount, due_date, type, current_balance)
            //       VALUES ('{$invoice_number}',{$client_id}, TIMESTAMP '{$start} {$gmt}', TIMESTAMP '{$end} {$gmt}', {$invoice_amount}, {$paid_amount}, TIMESTAMP '{$due_date}', {$type}, 0)";
            $sql = <<<EOT
    
   INSERT INTO 

invoice(invoice_number,client_id, invoice_time, invoice_start,invoice_end, 
due_date, type, invoice_zone, pdf_path, total_amount, current_balance, pay_amount)

VALUES('{$invoice_number}', '{$client_id}', '{$invoice_time}', TIMESTAMP '{$start} {$gmt}', 

TIMESTAMP '{$end} {$gmt}', '{$due_date}',3, '{$gmt}', '{$file_path}', {$invoice_amount}, 
(SELECT balance::numeric FROM c4_client_balance WHERE client_id = '{$client_id}')
,0)

EOT;
            $this->Transaction->query($sql);
            $this->Transaction->logging(0, 'Invoice', "Invoice Number:{$invoice_number}");
            $this->Transaction->create_json_array('', 201, __('The old vendor invoice is created successfully!', true));
            $this->Session->write('m', Transaction::set_validator());
            $this->redirect('/pr/pr_invoices/incoming_invoice');
        }
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
    }

    public function edit_incoming($id)
    {
        $this->loadModel('Transaction');
        if ($this->RequestHandler->isPost())
        {
            $invoice_number = $_POST['invoice_number'];
            $client_id = $_POST['client_id'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $gmt = $_POST['gmt'];
            $invoice_amount = $_POST['invoice_amount'];
            $paid_amount = !empty($_POST['paid_amount']) ? $_POST['paid_amount'] : 0;
            $due_date = $_POST['due_date'];
            $type = 2;
            $sql = "UPDATE invoice SET invoice_number = '{$invoice_number}', client_id = {$client_id}, invoice_start = TIMESTAMP '{$start} {$gmt}'
                , invoice_end = TIMESTAMP '{$end} {$gmt}', total_amount = {$invoice_amount}, pay_amount = {$paid_amount}, due_date = TIMESTAMP '{$due_date}' 
                WHERE invoice_id = {$id}";
            $this->Transaction->query($sql);
            $this->Transaction->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Transaction::set_validator());
            $this->redirect('/pr/pr_invoices/incoming_invoice');
        }
        $sql = "SELECT invoice_number, client_id, invoice_start, invoice_end, total_amount, pay_amount, due_date FROM invoice WHERE invoice_id = {$id}";
        $data = $this->Transaction->query($sql);
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
        $this->set('data', $data);
    }



    public function change_type($invoice_id, $state, $return)
    {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;

//        加密invoice_id
        if ($invoice_id != (int) $invoice_id)
        {
            $invoice_id = base64_decode($invoice_id);
        }
        $sql = "SELECT invoice_number, client_id, ingress_cdr_file, egress_cdr_file FROM invoice WHERE invoice_id = {$invoice_id}";
        $data = $this->Invoice->query($sql);
        $invoice_number = $data[0][0]['invoice_number'];
        $invoice_client_id = $data[0][0]['client_id'];
        $egress_cdr_file = $data[0][0]['egress_cdr_file'];
        $ingress_cdr_file = $data[0][0]['ingress_cdr_file'];
        $this->Invoice->logging(2, 'Invoice', "Invoice Number:{$data[0][0]['invoice_number']}");

        if ($state == '-1')
        {
            $sql1 = <<<EOT
            SELECT

client_id,

type,

invoice_number,

(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (7, 8)) as credit,

(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (11, 12)) as debit,
(SELECT sum(amount) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type = 3) as pay_amount

FROM

invoice

WHERE invoice_id = {$invoice_id};
EOT;
            $invoice_type = $this->Invoice->query($sql1);
            $type = $invoice_type[0][0]['type'];
            $credit = $invoice_type[0][0]['credit'];
            $debit = $invoice_type[0][0]['debit'];
            $client_id = $invoice_type[0][0]['client_id'];
            $invoice_number = $invoice_type[0][0]['invoice_number'];
//            $pay_amount = (float)$invoice_type[0][0]['pay_amount'];
//            $sql_s = "SELECT amount FROM client_payment where invoice_number = '{$invoice_number}'";
//            $result_s = $this->Invoice->query($sql_s);
//            if (!empty($result_s))
//                $s_amount = "+{$result_s[0][0]['amount']}";
//            else
//                $s_amount = "";
//
//            if ($type == '0') {
//                $sql2 = "UPDATE client_balance SET ingress_balance=ingress_balance::real-{$credit}+{$debit}{$s_amount}, balance=balance::real-{$credit}+{$debit}{$s_amount} WHERE client_id = '{$client_id}'";
//                $this->Invoice->query($sql2);
//            } elseif ($type =='1') {
//                $sql2 = "UPDATE client_balance SET egress_balance=egress_balance::real+{$credit}-{$debit}, balance=balance::real+{$credit}-{$debit} WHERE client_id = '{$client_id}'";
//                $this->Invoice->query($sql2);
//            }
            $sql3 = "UPDATE payment_invoice set invoice_id = NULL WHERE invoice_id = '{$invoice_id}'";
            $this->Invoice->query($sql3);
            $this->delete_invoice_file($invoice_number);

//            $script_path = Configure::read('script.path');
//            $script_conf = Configure::read('script.conf');
//            $script_name = $script_path . DS . "class4_total_balance.pl";
//
//            $cmd = "{$script_name} -c {$script_conf} -i {$client_id} -r -o > /dev/null 2>&1 &";
//            shell_exec($cmd);

        }
        $this->Invoice->query("UPDATE invoice SET state = {$state} WHERE invoice_id = {$invoice_id}");
        if ($state == 9)
        {
            //$sql = "select carrier_invoice_subject,carrier_invoice_content from mail_tmplate";
            $sql = "select invoice_subject,invoice_content,invoice_cc from mail_tmplate";
            $mail_sub_content = $this->Invoice->query($sql);
            $tmpl_sub = $mail_sub_content[0][0]['invoice_subject'];
            $tmpl_cont = $mail_sub_content[0][0]['invoice_content'];
            $tmpl_cc = $mail_sub_content[0][0]['invoice_cc'];
            $sql = "select invoice.invoice_number, client.name,client.billing_email,client.company,invoice.invoice_start,
invoice.invoice_end,invoice.is_send_as_link, invoice.total_amount as invoice_amount, create_type from invoice left join client on invoice.client_id = client.client_id
 where invoice_id = {$invoice_id}";

            $result = $this->Invoice->query($sql);
            $sql = "SELECT invoice_from from mail_tmplate";
            $invoice_from = $this->Invoice->query($sql);
            if ($invoice_from[0][0]['invoice_from'] && strtolower($invoice_from[0][0]['invoice_from']) != 'default')
            {
                $email_info = $this->Invoice->query("SELECT loginemail, smtp_host as smtphost, smtp_port as smtpport, username as username, password,name as name, email as from, secure as smtp_secure FROM mail_sender WHERE id = {$invoice_from[0][0]['invoice_from']}");
            }
            else
            {
                $email_info = $this->Invoice->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
            }
            if ($result[0][0]['billing_email'] == '')
            {
                $this->Invoice->create_json_array('', 101, __('The billing email of [%s] does not exist!', true, $result[0][0]['name']));
                $this->Session->write('m', Invoice::set_validator());
                $this->redirect('/pr/pr_invoices/view/0');
                return;
            }
            $send_address = $result[0][0]['billing_email'];

            $system_paramters_result = $this->Invoice->query("SELECT * FROM system_parameter");
            $system_paramters = $system_paramters_result[0][0];

            Configure::load('myconf');
            $invoice_path = Configure::read('generate_invoice.path');

            $invoice_name = $this->_get_invoice_name();
            $invoice_date = $this->_get_invoice_date($invoice_number);

            $invoice_file = $invoice_path . DS . $invoice_number . '_invoice.pdf';

            $filename = $invoice_number . '_' . $invoice_date . '.pdf';

            $invoice_file = $invoice_path . DS . $filename;

            if (file_exists($invoice_file))
            {

            }
            else
            {
                $invoice_number = base64_encode($invoice_number);
                $pdf_contents = file_get_contents($this->getUrl() . "pr/pr_invoices/createpdf_invoice/" . $invoice_number);
                file_put_contents($invoice_file, $pdf_contents);
            }
            $cdr_down_url = '';
            if (!empty($ingress_cdr_file))
            {
                $cdr_down_url .= $this->getUrl() . 'pr/pr_invoices/cdr_download/ingress/' . base64_encode($invoice_number);
            }

            if (!empty($egress_cdr_file))
            {
                $cdr_down_url .= "\n" . $this->getUrl() . 'pr/pr_invoices/cdr_download/egress/' . base64_encode($invoice_number);
            }

            $abs_file_path = realpath($invoice_file);
            try
            {
                //email_info
                $send_email_info = array();
                $send_email_info['send_email_info'] = $email_info;


                App::import('Vendor', 'nmail/phpmailer');
                $mailer = new phpmailer();
                if ($email_info[0][0]['loginemail'] === 'false')
                {
                    $mailer->IsMail();
                }
                else
                {
                    $mailer->IsSMTP();
                }
                $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
                switch ($email_info[0][0]['smtp_secure'])
                {
                    case 1:
                        $mailer->SMTPSecure = 'tls';
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
                if (strpos($mailer->Host, 'denovolab.com') !== false) {
                    $mailer->Helo = 'demo.denovolab.com';
                }
                //send_email
                $send_email_info['send_email'] = $send_address;
                $addresses = explode(';', $send_address);
                foreach ($addresses as $adress)
                {
                    $mailer->AddAddress($adress);
                }
                //send_cc
                $send_email_info['send_cc'] = $tmpl_cc;
                if ($tmpl_cc != '')
                {
                    $tml_ccs = explode(';', $tmpl_cc);
                    foreach ($tml_ccs as $tml_cc)
                        $mailer->AddCC($tml_cc);
                }

                $sys_switch_alias_arr = $this->Invoice->query('SELECT switch_alias FROM system_parameter limit 1');
                $sys_switch_alias = empty($sys_switch_alias_arr[0][0]['switch_alias']) ? "" : $sys_switch_alias_arr[0][0]['switch_alias'];
                $subject = str_replace(array('{client_name}','{company_name}', '{start_date}', '{end_date}', '{invoice_number}', '{switch_alias}', '{invoice_amount}'), array($result[0][0]['name'] ?: 'Not applicable',$result[0][0]['company'], $result[0][0]['invoice_start'], $result[0][0]['invoice_end'], $result[0][0]['invoice_number'],$sys_switch_alias,$result[0][0]['invoice_amount']), $tmpl_sub );
                $cdr_down_url_a = "&nbsp;<a href='$cdr_down_url'>CDR Download</a>&nbsp;";

                $content = str_replace(array('{client_name}','{company_name}', '{start_date}', '{end_date}', '{invoice_number}', '{cdr_url}', '{switch_alias}', '{invoice_amount}'), array($result[0][0]['name'] ?: 'Not applicable',$result[0][0]['company'], $result[0][0]['invoice_start'], $result[0][0]['invoice_end'], $result[0][0]['invoice_number'], $cdr_down_url_a, $sys_switch_alias,$result[0][0]['invoice_amount']), $tmpl_cont );
                $mailer->ClearAttachments();
                //  Auto invoice by Carrier settings, Manual Invoice by System settings
                if((!$result[0][0]['create_type'] && $result[0][0]['is_send_as_link']) || ($result[0][0]['create_type'] && (int) $system_paramters['invoice_send_mode'] == 1))
                {
                    $invoice_link = $this->getUrl() . 'pr/pr_invoices/createpdf_invoice/' . base64_encode($result[0][0]['invoice_number']);
                    $invoice_link_a = "&nbsp;<a href='$invoice_link'>Download PDF</a>&nbsp;";
                    if (strpos($content,'{invoice_link}') === false)
                        $content .= "<br />PDF Link:".$invoice_link_a;
                    else
                        $content = str_replace(array('{invoice_link}'), array($invoice_link_a), $content);
                }
                else
                {
                    $content = str_replace(array('{invoice_link}'), array(""), $content);
                    $mailer->AddAttachment($invoice_file, $filename);
                    //attachment
                    $send_email_info['send_attachment'] = array($invoice_file,$filename);

                }
                $mailer->IsHTML(true);
                //send_subject,send_content
                $send_email_info['send_subject'] = $subject;
                $send_email_info['send_content'] = $content;

                //序列化
                $send_email_info['send_content'] = htmlspecialchars($send_email_info['send_content'],ENT_QUOTES);
                $send_email_info = serialize($send_email_info);
//

                $mailer->Subject = $subject;
                $mailer->Body = $content;
                if ($mailer->Send())
                {
                    $current_datetime = date("Y-m-d H:i:s");
                    $sql = "insert into email_log (send_time, client_id, email_addresses, files, type, status, resend_email) values('{$current_datetime}', $invoice_client_id, '{$send_address}', '{$abs_file_path}', 7, 0 ,'{$send_email_info}')";
                    $data = $this->Invoice->query($sql);
                }
                else
                {
                    $current_datetime = date("Y-m-d H:i:s");
                    $mail_error = trim($mailer->ErrorInfo);
                    $sql = "insert into email_log (send_time, client_id, email_addresses, files, type, status, error, resend_email) values('{$current_datetime}', $invoice_client_id, '{$send_address}', '{$abs_file_path}', 7, 1,'{$mail_error}','{$send_email_info}')";
                    $data = $this->Invoice->query($sql);
                }
            }
            catch (phpmailerException $e)
            {
                echo $e->errorMessage();
            }
        }
        $message = "voided";
        if ($state == '1')
        {
            $message = "to verify";
        }
        else if ($state == '9')
        {
            $message = "то sent";
        }
        if (isset($mail_error))
        {
            $this->Invoice->create_json_array('', 101, __('The Invoice [%s] was sent error: %s', true, array($invoice_number, $mail_error)));
            $this->Session->write('m', Invoice::set_validator());
        }
        else
        {
            $this->Invoice->create_json_array('', 201, __('The state of the Invoice [%s] was set %s', true, array($invoice_number,$message)));
            $this->Session->write('m', Invoice::set_validator());
        }
        if ($return !== NULL)
//            echo '';
            $this->redirect('/pr/pr_invoices/view/' . $return);
        else
        {
            $this->autoRender = false;
            $this->autoLayout = false;
        }
    }

    public function set_disputed()
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $invoice_id = $_GET['invoice_id'];
        $disputed_amount = $_GET['amount'];
        $sql = "UPDATE invoice SET disputed_amount = {$disputed_amount}, disputed = 1 WHERE invoice_id = {$invoice_id}";
        $this->Invoice->query($sql);
        echo 1;
    }

    public function delete_credit_note($credit_id, $invoice_no)
    {
        $sql_invoice = "SELECT total_amount,type,client_id FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info = $this->Invoice->query($sql_invoice);
        if ($invoice_info[0][0]['type'] == '0')
        {
            $type = 8;
        }
        else
        {
            $type = 7;
        }
        $sql = "SELECT amount FROM client_payment WHERE client_payment_id = {$credit_id}";
        $amount_info = $this->Invoice->query($sql);
        $amount = $amount_info[0][0]['amount'];
        if ($type == '7') {
            $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 4);
        } else {
            $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 3);
        }
        $this->Invoice->query("DELETE FROM client_payment WHERE client_payment_id = {$credit_id}");
        $this->Invoice->logging(1, 'Credit Note', "Invoice Number:{$invoice_no}");
        $this->Invoice->create_json_array('', 201, __('Sucessfully!', true));
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/pr/pr_invoices/credit_note/' . $invoice_no);
    }

    public function credit_note($invoice_no)
    {
        $sql_invoice = "SELECT total_amount,type,client_id,invoice_time FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info = $this->Invoice->query($sql_invoice);
        $field = "credit_note_sent";

        if ($invoice_info[0][0]['type'] == '0')
        {
            $type = 8;
        }
        else
        {
            $field = "credit_note_received";
            $type = 7;
        }
        $sql2 = "SELECT COALESCE(sum(amount), 0) as sum FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type}";
        $result2 = $this->Invoice->query($sql2);
        $total = $result2[0][0]['sum'];
        if ($this->RequestHandler->isPost())
        {
            $note = $_POST["data"]["debit"]["description"];
            $amount = floatval($_POST["data"]["debit"]["amount"]);
            if (($total + $amount + 0.5) > $invoice_info[0][0]['total_amount'])
                $this->Invoice->create_json_array('', 101, __("Invoice amount can not larger than %s!", true, $invoice_info[0][0]['total_amount']));
            else
            {
                $currentDateTime = date('Y-m-d H:i:s');
                $total += $amount;
                $sql = "INSERT INTO client_payment(invoice_number,amount,description, payment_type, payment_time, result, client_id) VALUES ('{$invoice_no}', $amount, '{$note}', {$type}, '{$currentDateTime}', true, {$invoice_info[0][0]['client_id']})";
                $this->Invoice->begin();
                $flg = $this->Invoice->query($sql);
                if($flg === false)
                {
                    $this->Invoice->create_json_array('', 101, __('failed!', true));
                    $this->Session->write('m', Invoice::set_validator());
                    $this->redirect('credit_note/'.$invoice_no);
                }
                $this->Invoice->logging(0, 'Credit Note', "Invoice Number:{$invoice_no}");

                if ($type == '7') {
                    $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 5);
                } else {
                    $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 2);
                }

                if($flg === false)
                {
                    $this->Invoice->rollback();
                    $this->Invoice->create_json_array('', 101, __('failed!', true));
                }
                else
                {
                    $balanceData = $this->Invoice->query("SELECT balance FROM c4_client_balance WHERE client_id = '{$invoice_info[0][0]['client_id']}'");
                    $balance  = $balanceData[0][0]['balance'] ?: 0;
                    $this->Invoice->query("INSERT INTO balance_history_actual (date, client_id, actual_balance, {$field}) VALUES (CURRENT_DATE, {$invoice_info[0][0]['client_id']}, {$balance}, $amount);");

                    $flg = $this->Invoice->query("update client set mail_sended = 0 where client_id = " . $invoice_info[0][0]['client_id']);
                    if($flg === false)
                    {
                        $this->Invoice->rollback();
                        $this->Invoice->create_json_array('', 101, __('failed!', true));
                    }
                    else
                    {
                        $this->Invoice->create_json_array('', 201, __('The credit note is added successfully!', true));
                        $this->Invoice->commit();
                    }
                }
            }
            $this->Session->write('m', Invoice::set_validator());
        }
        $sql = "SELECT client_payment_id,client.client_id,client.name,invoice_number,amount,description,payment_type,payment_time FROM client_payment left join client on client_payment.client_id = client.client_id WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} ORDER BY payment_time DESC, client_payment_id desc";
        $result = $this->Invoice->query($sql);
        $this->set('data', $result);
        $this->set('total', $total);
        $this->set('invoice_no', $invoice_no);
    }

    public function edit_credit_note($client_payment_id,$invoice_no){
        Configure::write('debug', 0);
        $this->autoLayout = false;
//        $this->autoRender = false;
        $sql_invoice = "SELECT total_amount,type,client_id,invoice_time FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info = $this->Invoice->query($sql_invoice);
        if ($invoice_info[0][0]['type'] == '0')
        {
            $type = 8;
        }
        else
        {
            $type = 7;
        }

        $sql = "SELECT client_payment_id,client.client_id,client.name,invoice_number,amount,description,payment_type,payment_time FROM client_payment left join client on client_payment.client_id = client.client_id WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} and client_payment_id = {$client_payment_id}";
        $result = $this->Invoice->query($sql);
        $this->set('data', $result);
    }

    public function action_edit_credit_note($client_payment_id,$invoice_no){
        //Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql_invoice = "SELECT total_amount,type,client_id,invoice_time FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info = $this->Invoice->query($sql_invoice);
        if ($invoice_info[0][0]['type'] == '0')
        {
            $type = 8;
        }
        else
        {
            $type = 7;
        }

        $sql2 = "SELECT COALESCE(sum(amount), 0) as sum FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type}";
        $result2 = $this->Invoice->query($sql2);
        $total = $result2[0][0]['sum'];

        $sql = "SELECT amount FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} and client_payment_id = {$client_payment_id}";
        $result = $this->Invoice->query($sql);
        $ori_amount = $result[0][0]['amount'];


        $note = $_POST['data']['credit']['description'];
        $amount = floatval($_POST['data']['credit']['amount']);
        $sub_amount = $amount - $ori_amount;


        if (($total + $sub_amount + 0.5) > $invoice_info[0][0]['total_amount'])
            $this->Invoice->create_json_array('', 101, __("Invoice amount can not larger than %s!", true, $invoice_info[0][0]['total_amount']));

        else
        {
            $total = $total + $sub_amount;
            $sql = "update client_payment set amount = $amount,description = '$note' WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} and client_payment_id = {$client_payment_id}";
            $this->Invoice->begin();
            $flg = $this->Invoice->query($sql);
            if($flg === false)
            {
                $this->Invoice->create_json_array('', 101, __('failed!', true));
                $this->Session->write('m', Invoice::set_validator());exit;
                $this->redirect('credit_note/'.$invoice_no);
            }
            $this->Invoice->logging(0, 'Credit Note', "Invoice Number:{$invoice_no}");

            if ($type == '7') {
                $flg = $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $sub_amount, 5);
            } else {
                $flg = $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $sub_amount, 2);
            }

            if($flg === false)
            {
                $this->Invoice->rollback();
                $this->Invoice->create_json_array('', 101, __('failed!', true));
            }
            else
            {
                $flg = $this->Invoice->query("update client set mail_sended = 0 where client_id = " . $invoice_info[0][0]['client_id']);
                if($flg === false)
                {
                    $this->Invoice->rollback();
                    $this->Invoice->create_json_array('', 101, __('failed!', true));
                }
                else
                {
                    $this->Invoice->create_json_array('', 201, __('The credit note is modified successfully!', true));
                    $this->Invoice->commit();
                }
            }
        }
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('credit_note/'.$invoice_no);





    }

    public function delete_debit($debit_id, $invoice_no)
    {
        $sql_invoice = "SELECT type,client_id FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info = $this->Invoice->query($sql_invoice);
        if ($invoice_info[0][0]['type'] == '0')
        {
            $type = 12;
        }
        else
        {
            $type = 11;
        }
        $sql = "SELECT amount FROM client_payment WHERE client_payment_id = {$credit_id}";
        $amount_info = $this->Invoice->query($sql);
        $amount = $amount_info[0][0]['amount'];

        if ($type == '12') {
            $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 2);
        } else {
            $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 5);
        }

        $this->Invoice->query("DELETE FROM client_payment WHERE client_payment_id = {$debit_id}");
        $this->Invoice->logging(1, 'Debit Note', "Invoice Number:{$invoice_no}");
        $this->Invoice->create_json_array('', 201, __('The debit is deleted successfully!', true));
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/pr/pr_invoices/debit/' . $invoice_no);
    }

    public function debit($invoice_no)
    {
        $sql_invoice = "SELECT type,client_id,invoice_time FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info = $this->Invoice->query($sql_invoice);
        if ($invoice_info[0][0]['type'] == '0')
        {
            $type = 12;
        }
        else
        {
            $type = 11;
        }
        if ($this->RequestHandler->isPost())
        {
            $description = $_POST["data"]["debit"]["description"];
            $amount = $_POST["data"]["debit"]["amount"];
            $sql = "INSERT INTO client_payment(invoice_number,amount,description, payment_type, payment_time, result, client_id) VALUES ('{$invoice_no}', $amount, '{$description}', {$type}, '{$invoice_info[0][0]['invoice_time']}', true, {$invoice_info[0][0]['client_id']})";
            $this->Invoice->query($sql);

            if ($type == '12') {
                $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 3);
            } else {
                $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 4);
            }

            $this->Invoice->logging(0, 'Debit Note', "Invoice Number:{$invoice_no}");
            $this->Invoice->query("update client set mail_sended = 0 where client_id = " . $invoice_info[0][0]['client_id']);
            $this->Invoice->create_json_array('', 201, __('The debit is added successfully!', true));
            $this->Session->write('m', Invoice::set_validator());
        }
        $sql = "SELECT client_payment_id,client.client_id,client.name, invoice_number,amount,description,payment_type,payment_time FROM client_payment left join client on client_payment.client_id = client.client_id WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} ORDER BY payment_time DESC, client_payment_id desc";
        $sql2 = "SELECT COALESCE(sum(amount), 0) as sum FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type}";
        $result = $this->Invoice->query($sql);
        $result2 = $this->Invoice->query($sql2);
        $this->set('data', $result);
        $this->set('total', $result2[0][0]['sum']);
        $this->set('invoice_no', $invoice_no);
    }

    public function edit_debit_note($client_payment_id,$invoice_no){
        Configure::write('debug', 0);
        $this->autoLayout = false;
//        $this->autoRender = false;
        $sql_invoice = "SELECT total_amount,type,client_id,invoice_time FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info = $this->Invoice->query($sql_invoice);
        if ($invoice_info[0][0]['type'] == '0')
        {
            $type = 12;
        }
        else
        {
            $type = 11;
        }

        $sql = "SELECT client_payment_id,client.client_id,client.name,invoice_number,amount,description,payment_type,payment_time FROM client_payment left join client on client_payment.client_id = client.client_id WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} and client_payment_id = {$client_payment_id}";
        $result = $this->Invoice->query($sql);
        $this->set('data', $result);
    }

    public function action_edit_debit_note($client_payment_id,$invoice_no){
        //Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql_invoice = "SELECT total_amount,type,client_id,invoice_time FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info = $this->Invoice->query($sql_invoice);
        if ($invoice_info[0][0]['type'] == '0')
        {
            $type = 12;
        }
        else
        {
            $type = 11;
        }



        $sql = "SELECT amount FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} and client_payment_id = {$client_payment_id}";
        $result = $this->Invoice->query($sql);
        $ori_amount = $result[0][0]['amount'];


        $note = $_POST['data']['debit']['description'];
        $amount = floatval($_POST['data']['debit']['amount']);
        $sub_amount = $amount - $ori_amount;



        $sql = "update client_payment set amount = $amount,description = '$note' WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} and client_payment_id = {$client_payment_id}";
        $this->Invoice->begin();
        $flg = $this->Invoice->query($sql);
        if($flg === false)
        {
            $this->Invoice->create_json_array('', 101, __('failed!', true));
            $this->Session->write('m', Invoice::set_validator());exit;
            $this->redirect('debit/'.$invoice_no);
        }
        $this->Invoice->logging(0, 'Debit Note', "Invoice Number:{$invoice_no}");

        if ($type == '12') {
            $flg = $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 5);
        } else {
            $flg = $this->Invoice->clientBalanceOperation($invoice_info[0][0]['client_id'], $amount, 2);
        }

        if($flg === false)
        {
            $this->Invoice->rollback();
            $this->Invoice->create_json_array('', 101, __('failed!', true));
        }
        else
        {
            $flg = $this->Invoice->query("update client set mail_sended = 0 where client_id = " . $invoice_info[0][0]['client_id']);
            if($flg === false)
            {
                $this->Invoice->rollback();
                $this->Invoice->create_json_array('', 101, __('failed!', true));
            }
            else
            {
                $this->Invoice->create_json_array('', 201, __('The debit is modified successfully!', true));
                $this->Invoice->commit();
            }
        }

        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('debit/'.$invoice_no);





    }

    public function recon($invoice_id)
    {
        $datas = array();
        $sql = "select client_id,invoice_start,invoice_end,reconcile_state from invoice where invoice_id = {$invoice_id}";
        $info = $this->Invoice->query($sql);
        if ($this->RequestHandler->isPost())
        {
            if (is_uploaded_file($_FILES['upfile']['tmp_name']))
            {
                if (!empty($info))
                {
                    $name = $_FILES['upfile']['name'];
                    $dest = APP . 'webroot' . DS . 'upload' . DS . 'invoice_reconcile' . DS . uniqid('con_') . '.csv';
                    $result = move_uploaded_file($_FILES['upfile']['tmp_name'], $dest);
                    /*
                      if ($result) {
                      $sql = "DELETE FROM invoice_reconcile WHERE invoice_id = {$invoice_id}";
                      $this->Invoice->query($sql);
                      $client_id = $info[0][0]['client_id'];
                      $start_time = $info[0][0]['invoice_start'];
                      $end_time = $info[0][0]['invoice_end'];
                      $handle = fopen($dest, 'r');
                      $i = 0;
                      while ($data = fgetcsv($handle)) {
                      $i++;
                      if ($i == 1)
                      continue;
                      $code = $data[0];
                      $minute = $data[1];
                      $cost = $data[2];
                      list($sys_minute, $sys_cost) = $this->Invoice->get_sys_cdr($start_time, $end_time, $client_id, $code);
                      $diff_minute_amt = $minute - $sys_minute;
                      $diff_minute_per = $diff_minute_amt / $minute;
                      $diff_cost_amt = $cost - $sys_cost;
                      $diff_cost_per = $diff_cost_amt / $cost;
                      $sql = "INSERT INTO invoice_reconcile(code, minute, cost, sys_minute, sys_cost, minute_diff_amt, minute_diff_per,
                      cost_diff_amt, cost_diff_per, invoice_id) VALUES ('{$code}', $minute, $cost, $sys_minute, $sys_cost,
                      $diff_minute_amt, $diff_minute_per, $diff_cost_amt, $diff_cost_per, $invoice_id)";
                      $this->Invoice->query($sql);

                      }
                      fclose($handle);
                      }
                     */
                    if ($result)
                    {
                        $sql = "update invoice set reconcile_file_path = '{$dest}', reconcile_state = 0 WHERE invoice_id = {$invoice_id}";
                        $this->Invoice->query($sql);
                        $info[0][0]['reconcile_state'] = 0;
                    }
                }
                else
                {
                    $this->Invoice->create_json_array('', 101, __('Invoice Not Found!', true));
                    $this->Session->write('m', Invoice::set_validator());
                }
            }
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once MODELS . DS . 'MyPage.php';
        $counts = $this->Invoice->get_reconcile_count($invoice_id);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Invoice->get_reconcile($invoice_id, $pageSize, $offset);
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('invoice_id', $invoice_id);
        $status = array('unexecute', 'executing', 'complete');
        $this->set('status', $status[$info[0][0]['reconcile_state']]);
    }

    public function start_reconcile($invoice_id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $ch = curl_init();
        $fp = fsockopen($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $errno, $errstr, 30);
        if (!$fp)
        {
            echo "$errstr ($errno)<br />\n";
        }
        else
        {
            $out = "GET {$this->webroot}pr/pr_invoices/do_reconcile/{$invoice_id} HTTP/1.1\r\n";
            $out .= "Host: localhost\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            /*
              忽略执行结果
              while (!feof($fp)) {
              echo fgets($fp, 128);
              }
             */
            fclose($fp);
        }
        $this->redirect('/pr/pr_invoices/recon/' . $invoice_id);
    }

    public function do_reconcile($invoice_id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "select client_id,invoice_start,invoice_end,reconcile_file_path from invoice where invoice_id = {$invoice_id}";
        $info = $this->Invoice->query($sql);
        $this->Invoice->query("update invoice set  reconcile_state = 1 WHERE invoice_id = {$invoice_id}");
        if (!empty($info))
        {
            $sql = "DELETE FROM invoice_reconcile WHERE invoice_id = {$invoice_id}";
            $this->Invoice->query($sql);
            $client_id = $info[0][0]['client_id'];
            $start_time = $info[0][0]['invoice_start'];
            $end_time = $info[0][0]['invoice_end'];
            $file_path = $info[0][0]['reconcile_file_path'];
            $handle = fopen($file_path, 'r');
            $i = 0;
            while ($data = fgetcsv($handle))
            {
                $i++;
                if ($i == 1)
                    continue;
                $code = $data[0];
                $minute = $data[1];
                $cost = $data[2];
                list($sys_minute, $sys_cost) = $this->Invoice->get_sys_cdr($start_time, $end_time, $client_id, $code);
                $diff_minute_amt = $minute - $sys_minute;
                $diff_minute_per = round($diff_minute_amt / $minute * 100, 2);
                $diff_cost_amt = $cost - $sys_cost;
                $diff_cost_per = round($diff_cost_amt / $cost, 2);
                $sql = "INSERT INTO invoice_reconcile(code, minute, cost, sys_minute, sys_cost, minute_diff_amt, minute_diff_per,
                    cost_diff_amt, cost_diff_per, invoice_id) VALUES ('{$code}', $minute, $cost, $sys_minute, $sys_cost,
                    $diff_minute_amt, $diff_minute_per, $diff_cost_amt, $diff_cost_per, $invoice_id)";
                $this->Invoice->query($sql);
            }
            fclose($handle);
            $this->Invoice->query("update invoice set reconcile_state = 2 WHERE invoice_id = {$invoice_id}");
        }
    }

    public function get_recom_example_file()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=reconcile.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        readfile(APP . 'webroot' . DS . 'upload' . DS . 'reconcile_example.csv');
    }

    public function down_reconcile_list($invoice_id)
    {
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=reconcile.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT code, minute, cost, sys_minute, sys_cost, minute_diff_amt, minute_diff_per, cost_diff_amt, cost_diff_per FROM invoice_reconcile
            WHERE invoice_id = {$invoice_id} ORDER by code DESC";
        $data = $this->Invoice->query($sql);
        print(",Partner,,System,,Minute Diff,,Cost Diff\r\n");
        print("Code,Minute,Cost,Minute,Cost,Amt,%,Amt,%\r\n");
        foreach ($data as $item)
        {
            print(implode(",", $item[0]) . "\r\n");
        }
    }

    /*
     * 指定位某天Invoice充值
     *
     * @param integer $invoice_id   Invoice ID
     * @param integer $type         Invoice Type 1.incoming  2.outgoing
     * @return NULL
     */

    public function payment_to_invoice($invoice_id, $type, $client_id, $invoice_type)
    {
        if ($this->RequestHandler->isPost())
        {
            $payment = $_POST['payment'];
            $receiving_time = $_POST['payment_date'];
            $note = $_POST['note'];
            if ($type == 1)
            {
                // payment type = 4
                $client_balance_info = $this->Invoice->clientBalanceOperation($client_id, $payment, 2, true);

                $sql2 = "update invoice set pay_amount = pay_amount+{$payment}, current_balance= {$client_balance_info[0][0]['balance']} where invoice_id = {$invoice_id}";
                $this->Invoice->query($sql2);
                $sql3 = "INSERT INTO client_payment(client_id, payment_type, amount, current_balance,invoice_number, payment_time, result, receiving_time)
    VALUES ({$client_id},4,{$payment},{$client_balance_info[0][0]['balance']},(SELECT invoice_number FROM invoice WHERE invoice_id = {$invoice_id}), 'now', TRUE, '{$receiving_time}')";
                $this->Invoice->query($sql3);
            }
            elseif ($type == 2)
            {
                // payment type  = 3
                $client_balance_info = $this->Invoice->clientBalanceOperation($client_id, $payment, 5, true);
                $sql2 = "update invoice set pay_amount = pay_amount+{$payment}, current_balance= {$client_balance_info[0][0]['balance']} where invoice_id = {$invoice_id}";
                $this->Invoice->query($sql2);
                $sql3 = "INSERT INTO client_payment(client_id, payment_type, amount, current_balance,invoice_number, payment_time, result, receiving_time)
    VALUES ({$client_id},3,{$payment},{$client_balance_info[0][0]['balance']},(SELECT invoice_number FROM invoice WHERE invoice_id = {$invoice_id}), 'now', TRUE, '{$receiving_time}')";
                $this->Invoice->query($sql3);
            }
            $this->Invoice->query("update client set daily_balance_notification = low_balance_number where client_id = " . $client_id);
            $this->Invoice->create_json_array('', 201, __('succeeded!', true));
            $this->Session->write('m', Invoice::set_validator());

            if ($invoice_type == 'incoming_invoice')
            {
                $this->redirect("/pr/pr_invoices/incoming_invoice");
            }
            else
            {
                $this->redirect("/pr/pr_invoices/view/{$invoice_type}");
            }
        }
        $invoice_info = $this->Invoice->get_invoice_info($invoice_id);
        $this->set('invoice_info', $invoice_info);
    }

    public function export_invoice_excel()
    {

    }

    public function incoming_invoice_mass_edit()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = isset($_POST['ids']) && count($_POST['ids']) ? $_POST['ids'] : array();
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        if ($action == 1 && $ids)
        {
            foreach ($ids as $id)
            {
                $this->Invoice->delete($id);
            }
            $this->Invoice->create_json_array('', 201, __('The Incoming Invoices you selected is deleted successfully!', true));
        }
        else{
            $this->Invoice->create_json_array('', 101, __('Failed!', true));
        }
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect("/pr/pr_invoices/incoming_invoice");
    }

    public function carrier_invoice()
    {
        $this->pageTitle = "Auto Invoice Management";
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page')))
        {
            $currPage = $this->params['url']['page'];
        }
        $pageSize = 20;
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;
//$this->print_rr($this->params);

        $order_sql = "order by client.client_id asc";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = " order by client.{$field} {$sort}";
            }
        }

        $where = "";
        $client_id = isset($_GET["client_id"])?$_GET["client_id"]:0;
        if ($client_id)
        {
            $where .=" and client.client_id='$client_id'";
        }

        $this->loadModel('Clients');
        $clients_sql = "SELECT client_id, name FROM client WHERE status=true";
        $clients = $this->Clients->query($clients_sql);
        $this->set('clients', $clients);

        if(isset($this->params['url']['payment_term_id']) && !empty($this->params['url']['payment_term_id'])) {
            $where .= " and client.payment_term_id = {$this->params['url']['payment_term_id']}";
        }

        $this->loadModel('Paymentterm');

        $results = $this->Paymentterm->find('all');

        $this->set('payment_terms', $results);
        $this->set('get_data', $this->params['url']);
        // $this->set('search_week', $search_week);
//        $this->set('payment_terms', $search_payment_terms);


//echo $where;die;
        $sql = "SELECT count(*) as sum"
            . " from client inner join payment_term on client.payment_term_id = payment_term.payment_term_id"
            . " where 1=1 and client.status = true {$where}";
        $totalrecords = $this->Invoice->query($sql);
        $page->setTotalRecords($totalrecords[0][0]['sum']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
//$page = $page->checkRange($page);//检查当前页范围
        $sql = "SELECT client.name, client.auto_invoicing, client.client_id, payment_term.days, payment_term.type, payment_term.more_days"
            . " from client inner join payment_term on client.payment_term_id = payment_term.payment_term_id"
            . " where 1=1 and client.status = true {$where} {$order_sql}";


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql .= " limit '$pageSize' offset '$offset'";

        $client_arr = $this->Invoice->query($sql);
        foreach ($client_arr as $key => $value)
        {
            $sql = "select invoice_time,invoice_id, invoice_start, invoice_end, total_amount from invoice "
                . "where client_id = '{$value[0]['client_id']}'  AND type = 0 order by invoice_time desc limit 1";
            $invoice_arr = $this->Invoice->query($sql);

            $client_arr[$key][0]['last_invoice_time'] = "--";
            $client_arr[$key][0]['last_invoice_start'] = "--";
            $client_arr[$key][0]['last_invoice_end'] = "--";
            $client_arr[$key][0]['last_invoice_amount'] = "--";
            $client_arr[$key][0]['next_invoice_date'] = "--";
            if ($invoice_arr)
            {
                $client_arr[$key][0]['last_invoice_time'] = $invoice_arr[0][0]['invoice_time'];
                $client_arr[$key][0]['last_invoice_start'] = $invoice_arr[0][0]['invoice_start'];
                $client_arr[$key][0]['last_invoice_end'] = $invoice_arr[0][0]['invoice_end'];
                $client_arr[$key][0]['last_invoice_amount'] = $invoice_arr[0][0]['total_amount'];
                $client_arr[$key][0]['invoice_id'] = $invoice_arr[0][0]['invoice_id'];

                $lastData = $client_arr[$key][0]['last_invoice_time'];
                $days = $client_arr[$key][0]['days'];
                $type = $client_arr[$key][0]['type'];
                $client_arr[$key][0]['next_invoice_date'] = "--";
                $more_date = $client_arr[$key][0]['more_days'];
                if (!$client_arr[$key][0]['auto_invoicing'])
                {
                    $client_arr[$key][0]['next_invoice_date'] = "--";
                }
                else
                {
                    switch ($type)
                    {

                        case 1:
                            $client_arr[$key][0]['next_invoice_date'] = date("Y-m-d", strtotime("{$lastData}") + "{$days}" * 24 * 3600);
                            break; //隔多少天
                        case 2:
                            $year_month = date('Y-m', time());
                            $client_arr[$key][0]['next_invoice_date'] = date("Y-m-d", strtotime("{$year_month}") + "{$days}" * 24 * 3600);
                            break;
                        case 3:
                            $week = date('w', strtotime("{$lastData}"));
                            $date_interval = abs($week - $days);
                            if($date_interval == 0)
                                $date_interval = 7;
                            $date_interval_str = "{$lastData}+{$date_interval} day";
                            $client_arr[$key][0]['next_invoice_date'] = date("Y-m-d", strtotime($date_interval_str));
                            break;
                        case 4:
                            $more_date_arr = explode(',', $more_date);
                            if (!empty($more_date_arr))
                            {
                                sort($more_date_arr);
                                $first_data = $more_date_arr[0];
                                $day = date('d');
                                $year_month = date('Y-m', time());
                                $timestamp = strtotime($year_month);
                                $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
                                $client_arr[$key][0]['next_invoice_date'] = date('Y-m-d', strtotime("$firstday +1 month +{$first_data} day"));

                                foreach ($more_date_arr as $key => $value)
                                {
                                    $stmp = intval($value);
                                    if ($day < $stmp)
                                    {
                                        $client_arr[$key][0]['next_invoice_date'] = date("Y-m-d", strtotime("{$year_month}") + "{$stmp}" * 24 * 3600);
                                    }
                                }
                            }
                            break;
                        default : $client_arr[$key][0]['next_invoice_date'] = "--";
                    }
                }
            }
        }

        $page->setDataArray($client_arr);
        $this->set('p', $page);
    }

    public function ajax_check_validate()
    {//print_r($_POST);die;
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $error_flag = 'false';
        $invoice_number = '';
        $state = $_POST ['state'];
        $type = $_POST ['type'];
        $due_date = $_POST ['due_date'];
//$total_amount = $_POST ['total_amount'];
        $start_date = $_POST ['start_date']; //开始日期
        $stop_date = $_POST ['stop_date']; //结束日期
        $gmt = $_POST["query"]['tz'];
        $carriers = $_POST['client_id'];


# check invoice number Repeatability
        if (!empty($invoice_number))
        {
// Never here
            $c = $this->Invoice->query("select count(*) from invoice where invoice_number='$invoice_number';");
            if ($c[0][0]['count'] > 0)
            {
                $return['flg'] = 0;
            }
        }
        else
        {
//check invoice date duplicate
            $system_settings = $this->Invoice->query("select overlap_invoice_protection from system_parameter limit 1");
            $return['flg'] = 0;
            if ($system_settings[0][0]['overlap_invoice_protection'])
            {
                $type_where = $type == 2 ? " and (\"type\" = 0 or \"type\" = 1)" : (" and \"type\" = " . intval($type) );
                $dupli_sql = "select invoice_id,invoice_number, invoice_start, invoice_end, due_date"
                    . " from invoice where state != -1 and client_id in ($carriers) and ( (invoice_end >= TIMESTAMP '{$start_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL and invoice_start <= TIMESTAMP '{$start_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL) or (invoice_end >= TIMESTAMP '{$stop_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL and invoice_start <= TIMESTAMP '{$stop_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL) ) {$type_where}";
                $dupli_result = $this->Invoice->query($dupli_sql);
                if (!empty($dupli_result))
                {
//$message = "The invoice you are trying to generate is overlapping with the following invoice(s), and will not be executed:";
                    $return['data'] = $dupli_result;
// foreach ($dupli_result as $dupli_item) {
//
// $message2 = "Invoice:#{$dupli_item[0]['invoice_number']} [{$dupli_item[0]['invoice_start']} ~ {$dupli_item[0]['invoice_end']}]";
// }
                    $return['flg'] = 1;
                }
            }
        }

        if (empty($carriers))
        {
            $return['flg'] = 2;
        }

        if (empty($due_date))
        {
            $return['flg'] = 3;
        }
        echo json_encode($return);
    }



    public function send_invoice_mail($invoice_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        if ($this->RequestHandler->isPost())
        {
            if (isset($this->params['form']['email']))
            {
                $this->Invoice->query("UPDATE invoice SET state = 9 WHERE invoice_id = {$invoice_id}");
                $email = $this->params['form']['email'];
                if (!empty($email))
                {
//                    $sql = "SELECT invoice_number, client_id, ingress_cdr_file, egress_cdr_file FROM invoice WHERE invoice_id = {$invoice_id}";
                    $sql = "SELECT * FROM invoice WHERE invoice_id = {$invoice_id}";
                    $data = $this->Invoice->query($sql);
                    $invoice_number = $data[0][0]['invoice_number'];
                    $invoice_client_id = $data[0][0]['client_id'];
                    $egress_cdr_file = $data[0][0]['egress_cdr_file'];
                    $ingress_cdr_file = $data[0][0]['ingress_cdr_file'];
                    $pdfFile = $data[0][0]['pdf_path'];
                    $this->Invoice->logging(2, 'Invoice', "Invoice Number:{$data[0][0]['invoice_number']}");
                    $sql = "select invoice_subject,invoice_content,invoice_cc from mail_tmplate";
                    $mail_sub_content = $this->Invoice->query($sql);
                    $tmpl_sub = $mail_sub_content[0][0]['invoice_subject'];
                    $tmpl_cont = $mail_sub_content[0][0]['invoice_content'];
                    $tmpl_cc = $mail_sub_content[0][0]['invoice_cc'];
                    $sql = "select invoice.invoice_number, client.name,client.billing_email,client.company,
invoice.invoice_start,invoice.invoice_end,invoice.is_send_as_link from invoice left join client on
invoice.client_id = client.client_id where invoice_id = {$invoice_id}";
                    $result = $this->Invoice->query($sql);
                    $sql = "SELECT invoice_from from mail_tmplate";
                    $invoice_from = $this->Invoice->query($sql);
                    if ($invoice_from[0][0]['invoice_from'] && strtolower($invoice_from[0][0]['invoice_from']) != 'default')
                    {
                        $email_info = $this->Invoice->query("SELECT loginemail, smtp_host as smtphost, smtp_port as smtpport, username as username, password,name as name, email as from, secure as smtp_secure FROM mail_sender WHERE id = {$invoice_from[0][0]['invoice_from']}");
                    }
                    else
                    {
                        $email_info = $this->Invoice->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
                    }

                    $system_paramters_result = $this->Invoice->query("SELECT * FROM system_parameter");
                    $system_paramters = $system_paramters_result[0][0];

                    Configure::load('myconf');
                    $invoice_path = Configure::read('generate_invoice.path');
                    $invoiceApiPath = Configure::read('invoice_url');

                    $invoice_name = $this->_get_invoice_name();
                    $invoice_date = $this->_get_invoice_date($invoice_number);

                    $invoice_file = $invoice_path . DS . $invoice_number . '_invoice.pdf';

                    $filename = $invoice_number . '_' . $invoice_date . '.pdf';

                    $invoice_file = $invoice_path . DS . $filename;

//                    if (file_exists($invoice_file))
//                    {
//
//                    }
//                    else
//                    {
                    $invoice_number = base64_encode($invoice_number);
//                        $pdf_contents = file_get_contents($this->getUrl() . "pr/pr_invoices/createpdf_invoice/" . $invoice_number);
                    $pdfUrl = $invoiceApiPath . $pdfFile;
                    $pdf_contents = file_get_contents($pdfUrl);
                    file_put_contents($invoice_file, $pdf_contents);
//                    }
                    $cdr_down_url = '';
                    if (!empty($ingress_cdr_file))
                    {
                        $cdr_down_url .= $this->getUrl() . 'pr/pr_invoices/cdr_download/ingress/' . $invoice_number;
                    }

                    if (!empty($egress_cdr_file))
                    {
                        $cdr_down_url .= "\n" . $this->getUrl() . 'pr/pr_invoices/cdr_download/egress/' . $invoice_number;
                    }

                    $abs_file_path = realpath($invoice_file);
//                    try
//                    {
//                    $email_info[0][0]['smtphost'] = '111';
                    //email_info
                    $send_email_info = array();
                    $send_email_info['send_email_info'] = $email_info;

                    App::import('Vendor', 'nmail/phpmailer');
                    $mailer = new phpmailer();
                    if ($email_info[0][0]['loginemail'] === 'false')
                    {
                        $mailer->IsMail();
                    }
                    else
                    {
                        $mailer->IsSMTP();
                    }
                    $mailer->SMTPDebug = 2;
                    $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
                    switch ($email_info[0][0]['smtp_secure'])
                    {
                        case 1:
                            $mailer->SMTPSecure = 'tls';
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
                    $mailer->CharSet = "UTF-8";
// foreach ($addresses as $adress) {
                    //send_email
                    $send_email_info['send_email'] = $email;
                    $addresses = explode(';', $email);
                    foreach ($addresses as $address)
                    {
                        $mailer->AddAddress($address);
                    }
//}
                    //send_cc
                    $send_email_info['send_cc'] = $tmpl_cc;

                    if ($tmpl_cc != '')
                    {

                        $tml_ccs = explode(';', $tmpl_cc);
                        foreach ($tml_ccs as $tml_cc)
                        {
                            $mailer->AddCC($tml_cc);
                        }
                    }
                    $sys_switch_alias_arr = $this->Invoice->query('SELECT switch_alias FROM system_parameter limit 1');
                    $sys_switch_alias = empty($sys_switch_alias_arr[0][0]['switch_alias']) ? "" : $sys_switch_alias_arr[0][0]['switch_alias'];
                    $subject = str_replace(array('{company_name}', '{client_name}', '{start_date}', '{end_date}', '{invoice_number}', '{switch_alias}'), array($result[0][0]['company'], $result[0][0]['name'], $result[0][0]['invoice_start'], $result[0][0]['invoice_end'], $result[0][0]['invoice_number'],$sys_switch_alias), $tmpl_sub);
                    $cdr_down_url_a = "&nbsp;<a href='$cdr_down_url'>CDR Download</a>&nbsp;";
                    $content = str_replace(array('{company_name}', '{client_name}', '{start_date}', '{end_date}', '{invoice_number}', '{cdr_url}', '{switch_alias}'), array($result[0][0]['company'], $result[0][0]['name'], $result[0][0]['invoice_start'], $result[0][0]['invoice_end'], $result[0][0]['invoice_number'], $cdr_down_url_a, $sys_switch_alias), $tmpl_cont);
                    $mailer->ClearAttachments();
                    //echo 1;
//                    if ((int) $system_paramters['invoice_send_mode'] == 1)
                    if ($result[0][0]['is_send_as_link'])
                    {
                        $invoice_link = $this->getUrl() . 'pr/pr_invoices/createpdf_invoice/' . base64_encode($result[0][0]['invoice_number']) . '/2';
                        $invoice_link_a = "&nbsp;<a href='$invoice_link'>Download PDF</a>&nbsp;";
                        if (strpos($content,'{invoice_link}') === false)
                            $content .= "<br />PDF Link:".$invoice_link_a;
                        else
                            $content = str_replace(array('{invoice_link}'), array($invoice_link_a), $content);
                    }
                    else
                    {
                        $content = str_replace(array('{invoice_link}'), array(""), $content);
                        $mailer->AddAttachment($invoice_file, $filename);

                        //attachment
                        $send_email_info['send_attachment'] = array($invoice_file,$filename);
                    }
                    //echo 2;
                    $mailer->IsHTML(true);

                    //send_subject,send_content
                    $send_email_info['send_subject'] = $subject;
                    $send_email_info['send_content'] = $content;

                    //序列化
                    $send_email_info['send_content'] = htmlspecialchars($send_email_info['send_content'],ENT_QUOTES);
                    $send_email_info = serialize($send_email_info);
//                    $send_email_info = htmlspecialchars($send_email_info);


                    $mailer->Subject = $subject;
                    $mailer->Body = $content;

//                    die(var_dump($subject, $content));

                    // echo $mailer->ErrorInfo;
                    if ($mailer->Send())
                    {
                        $current_datetime = date("Y-m-d H:i:s");
                        $sql = "insert into email_log (send_time, client_id, email_addresses, files, type,status, resend_email) values('{$current_datetime}', $invoice_client_id, '{$email}', '{$abs_file_path}', 7,0,'{$send_email_info}')";
                        $this->Invoice->query($sql);
                        $this->Invoice->create_json_array('#ClientOrigRateTableId', 201, __('The Invoice [%s] is sent successfully!', true, $invoice_number));
                    }
                    else
                    {
                        $mail_error = $mailer->ErrorInfo;
                        $current_datetime = date("Y-m-d H:i:s");
                        $sql = "insert into email_log (send_time, client_id, email_addresses, files, type, status, error, resend_email) values('{$current_datetime}', $invoice_client_id, '{$email}', '{$abs_file_path}', 7, 1,'{$mail_error}','{$send_email_info}')";
                        $this->Invoice->query($sql);
                        $this->Invoice->create_json_array('#ClientOrigRateTableId', 101,$mail_error);
                    }

                }
                else
                {
                    $this->Invoice->create_json_array('#ClientOrigRateTableId', 101, __('The receiver can not empty!', true));
                }
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect("/pr/pr_invoices/view/0");
            }
        }
        $invoice = $this->Invoice->findByInvoiceId($invoice_id);
        $billing_email_query = $this->Invoice->query("SELECT billing_email FROM client WHERE client_id = {$invoice['Invoice']['client_id']}");
        $billing_email = $billing_email_query[0][0]['billing_email'];
        $this->set("billing_email", $billing_email);
    }


    public function trigger_info()
    {
        $this->pageTitle = "Trigger Auto Invoice";
        $selected_date = "";
        $result_data = array();
        $selected_status = 0;
        if (isset($this->params['url']['invoice_date']) && isset($this->params['url']['status']))
        {
            Configure::load('myconf');
            $test_carrier = Configure::read('check_route.carrier_name');
            $selected_date = $this->params['url']['invoice_date'];
            $selected_status = $this->params['url']['status'];
//            $selected_date = date('Y-m-d');
            $this->loadModel('Clients');
            $find_arr = array(
                'fields' => array(
                    'Clients.client_id','Clients.name','Clients.payment_term_id','Clients.invoice_start_from',
                    'PaymentTerm.type','PaymentTerm.days','PaymentTerm.grace_days','PaymentTerm.notify_days',
                    'PaymentTerm.more_days','PaymentTerm.finance_rate'
                ),
                'conditions' => array(
                    'Clients.auto_invoicing' => true,
                    'Clients.status' => true,
                    'Clients.invoice_start_from <= ?' => $selected_date,
                    'Clients.name != ?' => $test_carrier
                ),
                'joins' => array(
                    array(
                        'alias' => 'PaymentTerm',
                        'table' => 'payment_term',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Clients.payment_term_id = PaymentTerm.payment_term_id',
                        )
                    )
                ),
            );
//        得到所有有自动invoice的client
            $all_auto_invoice_clients = $this->Clients->get_auto_invoice_client($find_arr);
//        pr($all_auto_invoice_clients);
            foreach ($all_auto_invoice_clients as $client_data)
            {
                $type = $client_data['PaymentTerm']['type'];
                $invoice_start_from = $client_data['Clients']['invoice_start_from'];
                $client_id = $client_data['Clients']['client_id'];
                switch ($type){
                    case 1 :
//                    Every
                        $now_date = date('Y-m-d');
                        if($now_date < $selected_date || $selected_date < $invoice_start_from)
                            break;
                        $between_days = (strtotime($invoice_start_from) - strtotime($selected_date))/24*3600;
                        $interval_days = intval($client_data['PaymentTerm']['days']);
                        if($between_days%$interval_days == 0)
                        {
                            $history_sql = "SELECT count(*) as sum FROM invoice_history WHERE client_id = $client_id AND last_invoice_for = '{$selected_date}'";
                            $history_data = $this->Clients->query($history_sql);
                            $client_data['has_history'] = 'No';
                            if($history_data[0][0]['sum'])
                                $client_data['has_history'] = 'Yes';
                            switch ($selected_status){
                                case 0 :
                                    $result_data[] = $client_data;
                                    break;
                                case 1 :
                                    if($history_data[0][0]['sum'] == 0)
                                        $result_data[] = $client_data;
                                    break;
                                case 2 :
                                    if($history_data[0][0]['sum'])
                                        $result_data[] = $client_data;
                                    break;
                            }
                        }
                        break;
                    case 2 :
//                    Day of month
                        $selected_day_number = date('d',strtotime($selected_date));
                        if($selected_day_number == $client_data['PaymentTerm']['days'])
                        {
                            $history_sql = "SELECT count(*) as sum FROM invoice_history WHERE client_id = $client_id AND last_invoice_for = '{$selected_date}'";
                            $history_data = $this->Clients->query($history_sql);
                            $client_data['has_history'] = 'No';
                            if($history_data[0][0]['sum'])
                                $client_data['has_history'] = 'Yes';
                            switch ($selected_status){
                                case 0 :
                                    $result_data[] = $client_data;
                                    break;
                                case 1 :
                                    if($history_data[0][0]['sum'] == 0)
                                        $result_data[] = $client_data;
                                    break;
                                case 2 :
                                    if($history_data[0][0]['sum'])
                                        $result_data[] = $client_data;
                                    break;
                            }
                        }
                        break;
                    case 3 :
//                    Day of Week
                        $selected_week_number = date('w',strtotime($selected_date));
                        if($selected_week_number == $client_data['PaymentTerm']['days'])
                        {
                            $history_sql = "SELECT count(*) as sum FROM invoice_history WHERE client_id = $client_id AND last_invoice_for = '{$selected_date}'";
                            $history_data = $this->Clients->query($history_sql);
                            $client_data['has_history'] = 'No';
                            if($history_data[0][0]['sum'])
                                $client_data['has_history'] = 'Yes';
                            switch ($selected_status){
                                case 0 :
                                    $result_data[] = $client_data;
                                    break;
                                case 1 :
                                    if($history_data[0][0]['sum'] == 0)
                                        $result_data[] = $client_data;
                                    break;
                                case 2 :
                                    if($history_data[0][0]['sum'])
                                        $result_data[] = $client_data;
                                    break;
                            }
                        }
                        break;
                    case 4 :
//                    Some Day of month
                        $selected_day_number = date('d',strtotime($selected_date));
                        $plan_date_arr = explode(",",$client_data['PaymentTerm']['more_days']);
                        if(in_array($selected_day_number,$plan_date_arr))
                        {
                            $history_sql = "SELECT count(*) as sum FROM invoice_history WHERE client_id = $client_id AND last_invoice_for = '{$selected_date}'";
                            $history_data = $this->Clients->query($history_sql);
                            $client_data['has_history'] = 'No';
                            if($history_data[0][0]['sum'])
                                $client_data['has_history'] = 'Yes';
                            switch ($selected_status){
                                case 0 :
                                    $result_data[] = $client_data;
                                    break;
                                case 1 :
                                    if($history_data[0][0]['sum'] == 0)
                                        $result_data[] = $client_data;
                                    break;
                                case 2 :
                                    if($history_data[0][0]['sum'])
                                        $result_data[] = $client_data;
                                    break;
                            }
                        }
                        break;
                }
            }
        }
        $this->set('result_data',$result_data);
        $this->set('selected_date',$selected_date);
        $this->set('selected_status',$selected_status);
    }



    public function trigger()
    {
        Configure::write("debug",'0');
        $this->autoLayout = false;
        $this->autoRender = false;
        if ($this->RequestHandler->isPost())
        {
            $post_arr = $this->params['form'];

            $auto_sending = $post_arr['auto_sending'];
            $trigger_time = $post_arr['invoice_date'];
            $trigger_date_last_mouth_epoch = strtotime($trigger_time ." -1 month");
            $trigger_date_epoch = strtotime($trigger_time);

            Configure::load('myconf');
            $script_path = Configure::read('script.path');
            $exec_path = $script_path . DS . "class4_invoice_newcdr.pl";
            $exec_conf_path = Configure::read('script.conf');
            $url = $this->getUrl() . "pr/pr_invoices/createpdf_invoice";
            $carriers_arr = explode(",",$post_arr['client_ids']);
            array_walk($carriers_arr, create_function('&$item, $key', '$item = "-i {$item}";'));
            $carrier_cmd = implode(" ", $carriers_arr);

//             TODO Log
            $sql = "insert into invoice_log(start_time) values (CURRENT_TIMESTAMP(0)) returning id";
            $log_result = $this->Invoice->query($sql);
            $log_id = $log_result[0][0]['id'];


            $cmd = "perl {$exec_path} -c {$exec_conf_path} {$carrier_cmd} --trigger_date_epoch $trigger_date_epoch --trigger_date_last_mouth_epoch $trigger_date_last_mouth_epoch --log {$log_id} --is_trigger_email {$auto_sending}  > /dev/null &";
            $info = $this->Systemparam->find('first',array(
                'fields' => array('cmd_debug'),
            ));
            if(Configure::read('cmd_debug'))
            {
                file_put_contents($info["Systemparam"]["cmd_debug"],$cmd);
            }
            $result = shell_exec($cmd);
        }
        else
        {
            $this->Session->write('m', $this->Invoice->create_json(101, 'Operation failed!'));
            $this->redirect('/pr/pr_invoices/view/0/');
        }

        $this->Session->write('m', $this->Invoice->create_json(201, 'Successful operation!'));
        $this->redirect('/pr/pr_invoices/view/0/');
    }


    public function vendor_invoice()
    {
        $this->pageTitle = __('Vendor Invoice',true);
        $this->loadModel('pr.VendorInvoice');
        $conditions = array();
        $is_view = $this->_get('viewed',0);
//        if ($is_view != 2)
//            $conditions['is_view'] = $is_view;

        if ($this->_get('type'))
            $conditions['type'] = $this->_get('type');
        if ($this->_get('client'))
            $conditions['client_id'] = $this->_get('client');
        if ($this->_get('query_time_start'))
            $conditions['invoice_time >= ?'] = $this->_get('query_time_start');
        if ($this->_get('query_time_end'))
            $conditions['invoice_time <= ?'] = $this->_get('query_time_end');

        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        } else
            $order_arr = array('vendor_invoice_id' => 'desc');

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;


        $this->paginate = array(
            'fields' => array(

            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions
        );

        $this->data = $this->paginate('VendorInvoice');
        $this->set('status',$this->VendorInvoice->get_status());
        $this->set('clients',$this->VendorInvoice->findClient());
    }

    public function dispute_note_list()
    {
        $this->pageTitle = __('Vendor Invoice Dispute Note',true);
        $this->loadModel('pr.VendorInvoiceDispute');
        $this->loadModel('pr.VendorInvoice');
        $conditions = array();
        $is_view = $this->_get('viewed',0);
//        if ($is_view != 2)
//            $conditions['is_view'] = $is_view;

        if ($this->_get('client'))
            $conditions['VendorInvoice.client_id'] = $this->_get('client');
        if ($this->_get('query_time_start'))
            $conditions['VendorInvoiceDispute.create_on >= ?'] = $this->_get('query_time_start');
        if ($this->_get('query_time_end'))
            $conditions['VendorInvoiceDispute.create_on <= ?'] = $this->_get('query_time_end');

        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        } else
            $order_arr = array('VendorInvoiceDispute.id' => 'desc');

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;


        $this->paginate = array(
            'fields' => array(
                'VendorInvoiceDispute.id','VendorInvoiceDispute.vendor_invoice_id','VendorInvoiceDispute.create_on','VendorInvoiceDispute.create_by',
                'VendorInvoiceDispute.dispute','VendorInvoiceDispute.credit','VendorInvoiceDispute.credit_note',
                'VendorInvoice.client_id','VendorInvoice.invoice_time','VendorInvoice.billing_start','VendorInvoice.billing_end',
                'VendorInvoice.status',
            ),
            'joins' => array(
                array(
                    'alias' => 'VendorInvoice',
                    'table' => 'vendor_invoice',
                    'type' => 'inner',
                    'conditions' => array(
                        'VendorInvoice.vendor_invoice_id = VendorInvoiceDispute.vendor_invoice_id'
                    ),
                ),
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions
        );

        $this->data = $this->paginate('VendorInvoiceDispute');
        $this->set('status',$this->VendorInvoice->get_status());
        $this->set('clients',$this->VendorInvoiceDispute->findClient());
    }

    public function ajax_get_vendor_detail()
    {
        Configure::write("debug",'0');
        $vendor_invoice_id = $this->params['form']['vendor_invoice_id'];
        if (!$vendor_invoice_id)
            return array();
        $this->loadModel('pr.VendorInvoiceDetail');
        $this->data = $this->VendorInvoiceDetail->find('all',array(
            'conditions' => array(
                'vendor_invoice_id' => $vendor_invoice_id
            ),
            'order' => array(
                'report_date' => 'desc',
                'code_name' => 'asc'
            ),
        ));
    }


    public function edit_vendor_invoice($encode_vendor_invoice_id = '')
    {
        $this->pageTitle = __('Vendor Invoice',true);
        $this->loadModel('pr.VendorInvoice');
        if ($this->RequestHandler->isPost()) {
            $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
            if (isset($this->params['form']['myfile_guid']) && $this->params['form']['myfile_guid']) {
                $this->data['file_path'] = $path . DS . trim($this->params['form']['myfile_guid']) . ".pdf";
                $this->data['file_name'] = trim($this->params['form']['myfile_filename']);
            }
            if ($this->VendorInvoice->save($this->data) === false)
                $this->Session->write('m', $this->Invoice->create_json(101, 'Operation failed!'));
            else
                $this->Session->write('m', $this->Invoice->create_json(201, 'Successful operation!'));
            $this->redirect('vendor_invoice');
        }
        else
        {
            $vendor_invoice_id = base64_decode($encode_vendor_invoice_id);
            if (!intval($vendor_invoice_id))
            {
                $this->Session->write('m', $this->Invoice->create_json(101, __('Operation failed!',true)));
                $this->redirect('vendor_invoice');
            }
            $this->data = $this->VendorInvoice->find('first',array(
                'conditions' => array(
                    'vendor_invoice_id' => $vendor_invoice_id
                ),
            ));
        }
        $this->set('status',$this->VendorInvoice->get_status());
        $this->set('clients',$this->VendorInvoice->findClient());

    }

    public function download_vendor_invoice_pdf($encode_vendor_invoice_id)
    {
        $this->loadModel('pr.VendorInvoice');
        $vendor_invoice_id = base64_decode($encode_vendor_invoice_id);
        if (!intval($vendor_invoice_id))
        {
            $this->Session->write('m', $this->Invoice->create_json(101, __('Operation failed!',true)));
            $this->redirect('vendor_invoice');
        }
        $data = $this->VendorInvoice->find('first',array(
            'conditions' => array(
                'vendor_invoice_id' => $vendor_invoice_id
            ),
        ));
        $file_path = $data['VendorInvoice']['file_path'];
        $file_name = $data['VendorInvoice']['file_name'];
        if (!file_exists($file_path))
        {
            $this->Session->write('m', $this->Invoice->create_json(101, __('File is not exist!',true)));
            $this->redirect('vendor_invoice');
        }
        header("Content-type: application/octet-stream");
        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($file_name);
        if (preg_match("/MSIE/", $ua))
        {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        }
        else if (preg_match("/Firefox/", $ua))
        {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $file_name . '"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
        }

        readfile($file_path);
    }


    public function send_vendor_invoice_dispute($encode_vendor_invoice_id)
    {
        $this->loadModel('pr.VendorInvoice');
        $vendor_invoice_id = base64_decode($encode_vendor_invoice_id);
        if (!intval($vendor_invoice_id))
        {
            $this->Session->write('m', $this->Invoice->create_json(101, __('Operation failed!',true)));
            $this->redirect('vendor_invoice');
        }
        $count = $this->VendorInvoice->find('count',array(
            'joins' => array(
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'type' => 'inner',
                    'conditions' => array(
                        'Client.client_id = VendorInvoice.client_id'
                    ),
                ),
            ),
            'conditions' => array(
                'VendorInvoice.vendor_invoice_id' => $vendor_invoice_id,
                '(billing_email is not null or email is not null)'
            ),
        ));
        if (!$count)
        {
            $this->Session->write('m', $this->Invoice->create_json(101, __('Carrier billing mail and mail is not exist!',true)));
            $this->redirect('vendor_invoice');
        }

        $this->loadModel('pr.VendorInvoiceDispute');
        $dispute_info = $this->VendorInvoiceDispute->find('first',array(
            'fields' => array(),
            'conditions' => array(
                'vendor_invoice_id' => $vendor_invoice_id,
            ),
        ));
        $dispute_id = '';
        if ($dispute_info)
            $dispute_id = $dispute_info['VendorInvoiceDispute']['id'];

        $dispute_arr = array(
            'vendor_invoice_id' => $vendor_invoice_id,
            'create_on' => date('Y-m-d H:i:sO'),
            'create_by' => $this->Session->read('sst_user_name'),
            'id' => $dispute_id
        );
        $this->VendorInvoiceDispute->save($dispute_arr);

        Configure::load('myconf');
        $php_path = Configure::read('php_exe_path');
        $cmd = "{$php_path} " . APP . "../cake/console/cake.php send_email 4 $vendor_invoice_id /dev/null";
        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));
        file_put_contents($info["Systemparam"]["cmd_debug"],$cmd);

        shell_exec($cmd);
        $this->Session->write('m', $this->Invoice->create_json(201, 'Email are being sent.!'));
        $this->redirect('vendor_invoice');

    }


    public function ajax_get_vendor_dispute()
    {
        Configure::write("debug",'0');
        $vendor_invoice_id = $this->params['form']['vendor_invoice_id'];
        if (!$vendor_invoice_id)
            return array();
        $this->loadModel('pr.VendorInvoiceDispute');
        $this->data = $this->VendorInvoiceDispute->find('all',array(
            'conditions' => array(
                'vendor_invoice_id' => $vendor_invoice_id
            ),
        ));
    }

    public function ajax_get_vendor_credit()
    {
        Configure::write("debug",'0');
        $dispute_id = $this->params['form']['dispute_id'];
        if (!$dispute_id)
            return array();
        $this->loadModel('pr.VendorInvoiceDispute');
        $this->data = $this->VendorInvoiceDispute->find('first',array(
            'conditions' => array(
                'id' => $dispute_id
            ),
        ));
    }

    public function ajax_save_vendor_credit()
    {
        Configure::write("debug",'0');
        $this->autoRender = false;
        $this->autoLayout = false;
        $dispute_id = $this->params['form']['dispute_info'];
        $credit_value = $this->params['form']['credit_value'];
        $credit_note = $this->params['form']['credit_note'];
        $this->loadModel('pr.VendorInvoiceDispute');
        $save_arr = array(
            'id' => $dispute_id,
            'credit' => $credit_value,
            'credit_note' => $credit_note
        );
        if ($this->VendorInvoiceDispute->save($save_arr) === false)
        {
            $return_arr = array(
                'status' => 0,
                'msg' => __('Save failed',true),
            );
        }
        else
        {
            $return_arr = array(
                'status' => 1,
                'msg' => __('Save successfully',true),
            );
        }
        return json_encode($return_arr);
    }


    public function add_vendor_invoice()
    {
        $this->loadModel('pr.VendorInvoice');
        $arr = array(
            'client_id' => 119,
            'invoice_time' => date('Y-m-d H:i:sO'),
            'billing_start' => '2015-12-12 00:00:00',
            'billing_end' => '2015-12-22 23:59:59',
            'system_mins' => '50.5',
            'system_total' => '5.05',
        );
        $this->VendorInvoice->save($arr);
        die;
    }

    public function add_vendor_invoice_detail()
    {
        $this->loadModel('pr.VendorInvoiceDetail');
        $arr = array(
            array(
                'vendor_invoice_id' => 1,
                'code_name' => 'code name 1',
                'mins' => '10',
                'non_zero_calls' => 20,
                'rate' => '0.1',
            ),
            array(
                'vendor_invoice_id' => 1,
                'code_name' => 'code name 2',
                'mins' => '19.5',
                'non_zero_calls' => 38,
                'rate' => '0.1',
            ),
            array(
                'vendor_invoice_id' => 1,
                'code_name' => 'code name 3',
                'mins' => '15.8',
                'non_zero_calls' => 17,
                'rate' => '0.1',
            ),
            array(
                'vendor_invoice_id' => 1,
                'code_name' => 'code name 4',
                'mins' => '5.2',
                'non_zero_calls' => 2,
                'rate' => '0.1',
            ),
        );
        $this->VendorInvoiceDetail->saveAll($arr);
        die;
    }

    public function checkOverlapInvoices()
    {
        Configure::write('debug', 0);

        if ($this->RequestHandler->isPost()) {
            $startTime = $_POST['start_date'];
            $endTime = $_POST['end_date'];
            $clients = $_POST['client_id'];

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
                    $overlappedIds = $this->Invoice->getOverlappedIds($startTime, $endTime, $clients);

                    if (!empty($overlappedIds)) {
                        $this->Invoice->voidInvoiceByIds($overlappedIds);

                        $response = array(
                            'code' => '101',
                            'field' => '',
                            'msg' => "The invoice for period of {$startTime} to {$endTime} is overlapped with Invoice # ({$overlappedIds})."
                        );
                    }
                }
            }

            if ($response['code'] == 201) {
                if (substr($this->invoiceAddress, -1) == '/') {
                    $this->invoiceAddress = substr($this->invoiceAddress, 0, strlen($this->invoiceAddress) - 1);
                }
                $url = "{$this->invoiceAddress}/ManualInvoiceTest";
                $ch = curl_init();
                $data = $_POST;
//                $data['client_id'] = is_array($data['client_id']) ? implode(',', $data['client_id']) : $data['client_id'];

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $this->ApiLog->addRequest($url, $data, null, 2, $httpcode);
                curl_close($ch);

                if ($httpcode != 200 || $result == false) {
                    $response = array(
                        'code' => '101',
                        'field' => '',
                        'msg' => $result
                    );

                    if ($result == false) {
                        $response['msg'] = "Can't establish connection to {$url}";
                    }
                }elseif($httpcode == 200 && $result !== 'OK'){
                    $response = array(
                        'code' => '101',
                        'field' => '',
                        'msg' => $result
                    );
                }
            }
            echo json_encode($response);
        }
        exit;
    }



    public function resend($invoiceId, $type, $redirect = true )
    {
        $invoice = $this->Invoice->findByInvoiceId($invoiceId);
        $invoiceNumber = $invoice['Invoice']['invoice_number'];
        if ($invoiceNumber) {
            $url = "{$this->invoiceAddress}/resendemail";
            $ch = curl_init();
            $data = array(
                'invoice_number' => $invoiceNumber,
                'email' => isset($this->params['form']['email']) ? $this->params['form']['email'] : ''
            );

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $this->ApiLog->addRequest($url, $data, null, 2, $httpCode);
            curl_close($ch);
            if ($redirect) {
                if ($result == 'OK') {
                    $this->Session->write('m', $this->Invoice->create_json(201, 'Email Sent successfully'));
                } else {
                    $this->Session->write('m', $this->Invoice->create_json(101, 'Send email failed!'));
                }
            } else {
                return $result;
            }
        }

        if ($redirect) {
            $this->redirect('/pr/pr_invoices/view/' . $type);
        }
    }

}