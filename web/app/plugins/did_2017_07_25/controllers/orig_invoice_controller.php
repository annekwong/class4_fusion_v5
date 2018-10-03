<?php

class OrigInvoiceController extends DidAppController
{

    var $name = 'OrigInvoice';
    var $uses = array('Client', 'CdrExportLog');
    var $helpers = array('javascript', 'html', 'AppCdr', 'Searchfile', 'AppCommon');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    function index()
    {
        $this->redirect('view');
    }

    public function view($clientId = null)
    {
        $this->pageTitle = "Origination Invoices";
        $type = '0';
        if (isset($this->params['pass'][0]))
        {
            $type = $this->params['pass'][0];
        }
//        $results = $this->Invoice->getInvoices($type, $clientId, $this->_order_condtions(array('invoice_number', 'paid', 'type', 'client', 'invoice_start', 'total_amount', 'invoice_time', 'pay_amount')));
//        $this->set('p', $results);
          $this->set('create_type', $type);
//        $this->set('url_get', $this->params['url']);
//        $clients = $this->Invoice->query("select client_id, name from client order by name asc");
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
//                $this->data['Invoice']['invoice_use_balance_type'] = isset($_POST['invoice_use_balance_type']) ? $_POST['invoice_use_balance_type'] : '';


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

    }


}
