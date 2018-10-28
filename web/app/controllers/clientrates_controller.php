<?php

class ClientratesController extends AppController
{

    var $name = 'Clientrates';
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'AppRate');
    var $uses = array("OcnLata", "Clientrate", "Rate",'RateUploadTask');

    public function ajax_delete_rate()
    {
        Configure::write('debug', 0);
        $rate_id = $_POST['rate_id'];

        $rate_table = "SELECT jur_type,rate_table_id FROM rate_table WHERE rate_table_id = (SELECT rate_table_id FROM rate where rate_id = $rate_id)";
        if ($rate_table[0][0]['jur_type'] == 3 || $rate_table[0][0]['jur_type'] == 4)
        {
            $rate_info = $this->Clientrate->query("SELECT ocn, lata from rate  where rate_id = $rate_id");
            $sql1 = "DELETE FROM rate WHERE rate_table_id = {$rate_table[0][0]['rate_table_id']} and ocn='{$rate_info[0][0]['ocn']}' and lata = '{$rate_info[0][0]['lata']}'";
            $this->Clientrate->query($sql1);
        }
        else
        {
            $sql1 = "delete  from  rate  where  rate_id=$rate_id ";
            $this->Clientrate->query($sql1);
        }

        try
        {
            $this->set('extensionBeans', 1);
        }
        catch (Exception $e)
        {
            echo "Server Exception";
        }
    }

//    public function checkuploading()
//    {
//        Configure::write('debug', 0);
//        $this->autoRender = false;
//        $this->autoLayout = false;
//        $rate_table_id = $_POST['ratetable_id'];
//        $sql = "select status from rate_upload_queue where rate_table_id = {$rate_table_id}";
//        $result = $this->Clientrate->query($sql);
//        $response_data = array(
//            'waiting' => 0,
//            'progressing' => 0,
//            'ending_date' => 0,
//        );
//        
//        foreach($result as $item)
//        {
//            if ($item[0]['status'] == '0')
//                $response_data['waiting'] += 1;
//            else if($item[0]['status'] == '1')
//                $response_data['ending_date'] += 1;
//            else
//                $response_data['progressing'] += 1;
//        }
//        
//        echo json_encode($response_data);
//    }
//    
//    public function upload()
//    {
//        
//        Configure::write('debug', 0);
//        $this->autoRender = false;
//        $this->autoLayout = false;
//        $targetFolder = Configure::read('rateimport.put');
//
//        try
//        {
//            $this->set('extensionBeans', 1);
//        } catch (Exception $e)
//        {
//            echo "Server Exception";
//        }
//    }

    public function checkuploading()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_table_id = $_POST['ratetable_id'];
        $sql = "select status from rate_upload_queue where rate_table_id = {$rate_table_id}";
        $result = $this->Clientrate->query($sql);
        $response_data = array(
            'waiting' => 0,
            'progressing' => 0,
            'ending_date' => 0,
        );

        foreach ($result as $item)
        {
            if ($item[0]['status'] == '0')
                $response_data['waiting'] += 1;
            else if ($item[0]['status'] == '1')
                $response_data['ending_date'] += 1;
            else
                $response_data['progressing'] += 1;
        }

        echo json_encode($response_data);
    }

    private function fileUploadErrorMessage($code)
    {
        switch ($code) {
            case 1:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case 2:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case 3:
                $message = "The uploaded file was only partially uploaded";
                break;
            case 4:
                $message = "No file was uploaded";
                break;
            case 6:
                $message = "Missing a temporary folder";
                break;
            case 7:
                $message = "Failed to write file to disk";
                break;
            case 8:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }

    public function upload()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $targetFolder = Configure::read('rateimport.put');
        $result = array(
            'code' => 101,
            'msg' => ''
        );

        if (!empty($_FILES)) {
            if ($_FILES['Filedata']['error'] == 0) {

                if (!empty($_FILES['Filedata']['tmp_name'])) {
                    $fileName = time() . '_' . uniqid();
                    $targetFile = $targetFolder . '/' . $fileName . ".csv";

                    if (!is_writable($targetFolder)) {
                        $result['msg'] = 'Directory "' . $targetFolder . '" doesn\'t have write permissions.';
                    } else {
                        // Validate the file type
                        $fileTypes = array('csv', 'xls', 'xlsx'); // File extensions
                        $fileParts = pathinfo($_FILES['Filedata']['name']);


                        if (in_array($fileParts['extension'], $fileTypes)) {
                            if ($fileParts['extension'] == 'xls' || $fileParts['extension'] == 'xlsx') {
                                $excelFile = $targetFolder . '/' . $fileName . "." . $fileParts['extension'];
                                $csvFile = $targetFolder . '/' . $fileName . ".csv";
                                move_uploaded_file($_FILES['Filedata']['tmp_name'], $excelFile);


                                App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/Classes/PHPExcel.php'));
                                App::import('Vendor', 'PHPExcel_IOFactory', array('file' => 'PHPExcel/Classes/PHPExcel/IOFactory.php'));
                                if (!class_exists('PHPExcel')) {
                                    return false;
                                }

                                $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                                $cacheSettings = array('memoryCacheSize' => '2GB');
                                PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
                                $inputFileType = PHPExcel_IOFactory::identify($excelFile);
                                $reader = PHPExcel_IOFactory::createReader($inputFileType);
                                // $reader->setReadDataOnly(true);

                                $excel = $reader->load($excelFile);

                                $excel->setActiveSheetIndex(0);
                                $objWorksheet = $excel->getActiveSheet();
                                $resArray = array();
                                $col_count = 0;
                                ini_set('memory_limit', '-1');
                                foreach ($objWorksheet->getRowIterator() as $key => $row) {
                                    $cellIterator = $row->getCellIterator();

                                    $cellIterator->setIterateOnlyExistingCells(false);
                                    $tmpArray = array();
                                    foreach ($cellIterator as $cell) {
                                        array_push($tmpArray, $cell->getFormattedValue());
                                    }
                                    if ($key == 1) {
                                        $tmpArray = array_filter($tmpArray);
                                        $col_count = count($tmpArray);
                                    } else {
                                        $tmpArray = array_slice($tmpArray, 0, $col_count);
                                    }
                                    array_push($resArray, implode(',', $tmpArray));
                                }
                                if ($h = fopen($csvFile, 'a')) {
                                    foreach ($resArray as $item) {
                                        fwrite($h, $item . "\r\n");
                                    }
                                    fclose($h);
                                }
                            } else {
                                move_uploaded_file($_FILES['Filedata']['tmp_name'], $targetFile);
                            }

                            $result['msg'] = $fileName;
                            $result['code'] = 201;
                        } else {
                            $result['msg'] = 'Sorry! We are unable to recognize your file format.';
                        }
                    }
                } else {
                    $result['msg'] = 'Size of a File exceeds server limit.';
                }
            } else {
                $result['msg'] = $this->fileUploadErrorMessage($_FILES['Filedata']['error']);
            }
        } else {
            $result['msg'] = 'Size of a File exceeds server limit.';
        }

        ob_clean();
        return json_encode($result);
    }

    public function detectErrors()
    {

        $this->autoRender = false;
        $this->autoLayout = false;
        $get_data = $this->params['form'];
        $targetFile = $get_data['abspath'];
        $position = $get_data['code_col_number'];
        $start_from = $get_data['start_from'];

        if(file_exists($targetFile) && ($handle = fopen($targetFile, "r")) !== false){

            $this->startFromFile($targetFile, $start_from);

            $row = 0;
            $duplicate_rows = [];
            $wrong_rows = [];
            while (($data = fgetcsv($handle, 8192, ",")) !== false) {
                // get code col. number and count of cols
                if (!$row) {
                    $count = count($data);
                }
                $duplicate_rows[$data[$position]][] = $row + $start_from -1;
                if (count($data) !== $count) {
                    $wrong_rows[] = $row;
                }
                $row ++ ;
            }

            $duplicate_rows = array_filter($duplicate_rows,
                function ($element)  {
                    return count($element) > 1;
                }
            );
            if (!empty($duplicate_rows) || !empty($wrong_rows)) {
                $this->jsonResponse(['status' => false, 'errors' => ['duplicated' => $duplicate_rows, 'wrong_rows' => $wrong_rows]]);
            }else{
                $this->jsonResponse(['status' => true, 'errors' => false]);
            }
        }
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
        if (isset($_POST['PHPSESSID']))
        {
            session_id($_POST['PHPSESSID']);
            session_start();
        }
        if ($this->params['action'] == 'upload')
        {
            Configure::load('myconf');
            return true;
        }
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_wholesale');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        if ($this->RequestHandler->isGet())
        {
            $url = $this->get_curr_url();
            if (!isset($_SESSION['back_url']))
            {
                $last_url = $url;
                $curr_url = $url;
                $_SESSION['back_url'] = $last_url;
                $_SESSION['curr_url'] = $curr_url;
            }
            else
            {
                if ($_SESSION['curr_url'] != $url)
                {
                    $_SESSION['curr_url'] = $url;
                }
                if (strpos($url, "view"))
                {
                    $_SESSION['back_url'] = $url;
                }
            }
        }
        parent::beforeFilter(); //调用父类方法
    }

    /**
     *
     * 模拟计费
     */
    function simulate()
    {
        $this->pageTitle = "Editing rates";
        $table_id = base64_decode($this->params['pass'][0]);
        if (!strcmp($this->params['pass'][0],intval($this->params['pass'][0])))
            $table_id = $this->params['pass'][0];

        $defualt_zone = $this->Clientrate->query("select sys_timezone from system_parameter");
        $this->set('dzone', $defualt_zone[0][0]['sys_timezone']);
        $this->set('table_id', $table_id);
        $this->set('name', $this->select_client_name($table_id));
        $this->loadModel('Rate');
        if (isset($_REQUEST['process']) && $_REQUEST['process'] == '1')
        {
            $arr = array();
            for ($i = 0; $i < count($_POST['date']); $i++)
            {

                $result = $this->Rate->simulated1($_POST['date'][$i], $_POST['time'][$i], $_POST['tz'][$i], $_POST['dnis'][$i], $_POST['duration'][$i], $table_id);
                array_push($arr, $result);
            }
            $this->set('data', $arr);


            //$this->set('data',$this->Rate->simulated($date,$number,$durations,$table_id));
        }
        $list = $this->Rate->query('select jur_type, currency_id from rate_table where rate_table_id = ' . $table_id);
        $this->set('jur_type', $list[0][0]['jur_type']);
        $this->set('currency', $list[0][0]['currency_id']);
    }

    public function test()
    {
        $this->Clientrate->get_prefix_rate(array('89009', '5656'));
    }

//public  function   view
//_carrier_rate($type='false'){
//	if(!empty($_GET['route_prefix'])){
//		$prefix=$_GET['route_prefix'];
//		$prefix_arr=array($prefix);
//	}else{
//		$prefix_arr=$this->Clientrate->find_client_route_prefix();
//	}
//	$table_id=$this->Clientrate->get_prefix_rate($prefix_arr);
//	if(empty($table_id)){
//		$this->set('p','');
//	}else{
//		$this->set('table_id',$table_id);		
//		$type=_filter_array(Array('true'=>true,'false'=>false),$type,false);
//		if($type){
//			$sql=$this->Clientrate->find_all_rate($table_id,$this->_order_condtions(array('code','code_name','rate','setup_fee','effective_date','end_date')),Array('getSql'=>true));
//			$this->Clientrate->export__sql_data('download Cdr',$sql,'cdr');
//			$this->layout='csv';
//			Configure::write('debug',0);
//			exit();
//		}
//		$this->set('p',$this->Clientrate->find_all_rate($table_id,$this->_order_condtions(array('code','code_name','rate','setup_fee','effective_date','end_date'))));
//		
//	}
//	$this->set('route_prefix',$this->Clientrate->find_client_route_prefix());
//}

    public function view_rate($gress = 0)
    {
        $this->redirect('/clients/carrier_dashboard');

        $client_id = $_SESSION['sst_client_id'];
        $rate_table_ids = array();
        if (!$gress)
        {
            $rate_table_ids = $this->Clientrate->query("select rate_table_id from resource where client_id = {$client_id} and egress = true and rate_table_id is not null");
        }
        else
        {
            $rate_table_ids = $this->Clientrate->query("select resource_id from resource where client_id = {$client_id} and ingress = true");
            if (count($rate_table_ids))
            {
                $temp_arr = array();
                foreach ($rate_table_ids as $rate_table_id)
                {
                    array_push($temp_arr, $rate_table_id[0]['resource_id']);
                }

                $rate_table_ids = $this->Clientrate->query("select rate_table_id from resource_prefix where resource_id in (" . implode(",", $temp_arr) . ") and rate_table_id is not null");
            }
        }
        $rate_table_ids_join = array();
        $page = "";

        if (count($rate_table_ids))
        {

            $search_where = '';

            if (isset($_GET['name']))
            {
                $search_where .= " and rate_table.name ilike '{$_GET['name']}%'";
            }

            foreach ($rate_table_ids as $rate_table_id)
            {
                array_push($rate_table_ids_join, $rate_table_id[0]['rate_table_id']);
            }

            $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

            empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
            empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

            $_SESSION['paging_row'] = $pageSize;

            require_once 'MyPage.php';

            $page = new MyPage();

            $totalrecords = $this->Clientrate->query("select count(rate_table_id) as c from rate_table where rate_table.rate_table_id in (" . implode(",", $rate_table_ids_join) . ")");

            $page->setTotalRecords($totalrecords[0][0]['c']);
            $page->setCurrPage($currPage);
            $page->setPageSize($pageSize);

            $currPage = $page->getCurrPage() - 1;
            $pageSize = $page->getPageSize();
            $offset = $currPage * $pageSize;

            $rate_tables = $this->Clientrate->query("select 
                                                         rate_table.rate_table_id, rate_table.name, code_deck.name as code_deck, currency.code as currency, jurisdiction.name as jurisdiction
                                                         from rate_table 
                                                         left join code_deck on rate_table.code_deck_id = code_deck.code_deck_id
                                                         left join currency on rate_table.currency_id = currency.currency_id
                                                         left join jurisdiction on rate_table.jurisdiction_country_id  = jurisdiction.jurisdiction_country_id
                                                         where rate_table.rate_table_id in (" . implode(",", $rate_table_ids_join) . ") {$search_where}
                                                         limit $pageSize offset $offset");
            if ($totalrecords[0][0]['c'] == 1)
            {
                $this->redirect("/clientrates/view_rate_detail/" . base64_encode($rate_tables[0][0]['rate_table_id']));
            }
            $page->setDataArray($rate_tables);
        }
        $this->set('active_type', $gress);
        $this->set("p", $page);
    }

    public function view_rate_ingress()
    {
        $client_id = $_SESSION['sst_client_id'];
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';

        $page = new MyPage();

        $totalrecords = $this->Clientrate->query("SELECT count(*) FROM resource_prefix 
                INNER JOIN resource ON resource_prefix.resource_id = resource.resource_id WHERE  resource.client_id = $client_id");
        $page->setTotalRecords($totalrecords[0][0]['count']);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "SELECT resource_prefix.rate_table_id, resource_prefix.tech_prefix FROM resource_prefix 
                INNER JOIN resource ON resource_prefix.resource_id = resource.resource_id WHERE  resource.client_id = $client_id limit $pageSize offset $offset";
        $data = $this->Clientrate->query($sql);

        if ($totalrecords[0][0]['count'] == 1)
        {
            $this->redirect('/clientrates/view_rate_detail/' . base64_encode($data[0][0]['rate_table_id']));
        }
        $page->setDataArray($data);
        $this->set("p", $page);
    }

    public function view_rate_egress()
    {
        ob_start();
        //Configure::write('debug', '0');

        $client_id = $_SESSION['sst_client_id'];
        $rate_tables = array();
        $sql = "select rate_table_id from resource where client_id = {$client_id} and egress = true";
        $result = $this->Clientrate->query($sql);
        foreach ($result as $item)
        {
            array_push($rate_tables, $item[0]['rate_table_id']);
        }
        $rate_tables_str = implode(',', $rate_tables);

        $rates = array();

        $search_where = '';

        if (isset($_GET['code']))
        {
            $search_where .= " and rate.code::text ilike '{$_GET['code']}%'";
        }

        if (isset($_GET['adv_search']) && $_GET['adv_search'] == 1)
        {
            if (isset($_GET['rate_begin']) && !empty($_GET['rate_begin']))
            {
                $search_where .= " and rate.rate > {$_GET['rate_begin']}";
            }
            if (isset($_GET['rate_end']) && !empty($_GET['rate_end']))
            {
                $search_where .= " and rate.rate <= {$_GET['rate_end']}";
            }
            if (isset($_GET['profile']) && !empty($_GET['profile']))
            {
                $search_where .= " and rate.time_profile_id = {$_GET['profile']}";
            }
            if (isset($_GET['code_name']) && !empty($_GET['code_name']))
            {
                $search_where .= " and rate.code_name = '{$_GET['code_name']}'";
            }
            if (isset($_GET['country']) && !empty($_GET['country']))
            {
                $search_where .= " and rate.country = '{$_GET['country']}'";
            }
            if (isset($_GET['time']) && !empty($_GET['time']))
            {
                switch ($_GET['time'])
                {
                    case 'current':
                        $search_where = " and rate.effective_date <= '{$_GET['time_val']}'";
                        break;
                    case 'new':
                        $search_where = " and rate.effective_date >= '{$_GET['time_val']}'";
                        break;
                    case 'old':
                        $search_where = " and rate.end_date <= '{$_GET['time_val']}'";
                        break;
                    case 'in':
                        $search_where = " and (rate.effective_date <= '{$_GET['time_val']}' and rate.end_date >= '{$_GET['time_val']}' or rate.effective_date <= '{$_GET['time_val']}' and rate.end_date is null)";
                        break;
                }
            }
        }


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';

        $page = new MyPage();
        if (empty($rate_tables_str))
        {
            $rate_tables_str = 0;
        }
        $totalrecords = $this->Clientrate->query("select count(rate_id) as c from rate where rate_table_id in ({$rate_tables_str}) $search_where");

        $page->setTotalRecords($totalrecords[0][0]['c']);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "select rate.*, time_profile.name as time_profile from rate left join time_profile on rate.time_profile_id = time_profile.time_profile_id where rate_table_id in ({$rate_tables_str}) {$search_where}";



        if (isset($_GET['getcsv']) && $_GET['getcsv'])
        {
            $rates = $this->Clientrate->query($sql);
            ob_end_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=rate.csv");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            echo "Code,Code Name,Country,Rate,Intra Rate,Inter Rate,Setup Fee,Effective Date,End Date,Min Time,Interval,Grace Time,Seconds,Profile,Time Zone\n";
            foreach ($rates as $rate)
            {
                $temp_zone = empty($rate[0]['zone']) ? '"+00"' : $rate[0]['zone'];
                echo "{$rate[0]['code']},{$rate[0]['code_name']},{$rate[0]['country']},{$rate[0]['rate']},{$rate[0]['intra_rate']},{$rate[0]['inter_rate']},{$rate[0]['setup_fee']},{$rate[0]['effective_date']},{$rate[0]['end_date']},{$rate[0]['min_time']},{$rate[0]['interval']},{$rate[0]['grace_time']},{$rate[0]['seconds']},{$rate[0]['time_profile']},{$temp_zone}\n";
            }
            exit;
        }

        $sql .= " limit $pageSize offset $offset";


        $rates = $this->Clientrate->query($sql);

        $page->setDataArray($rates);

        $this->set("p", $page);

        $profiles = $this->Clientrate->query("select time_profile_id, name from time_profile");

        $this->set("profiles", $profiles);
    }

    public function view_rate_detail($id)
    {
        ob_start();


        $id = base64_decode($id);

        $rates = array();

        $search_where = '';

        if (isset($_GET['code']))
        {
            $search_where .= " and rate.code::text ilike '{$_GET['code']}%'";
        }

        if (isset($_GET['adv_search']) && $_GET['adv_search'] == 1)
        {
            if (isset($_GET['rate_begin']) && !empty($_GET['rate_begin']))
            {
                $search_where .= " and rate.rate > {$_GET['rate_begin']}";
            }
            if (isset($_GET['rate_end']) && !empty($_GET['rate_end']))
            {
                $search_where .= " and rate.rate <= {$_GET['rate_end']}";
            }
            if (isset($_GET['profile']) && !empty($_GET['profile']))
            {
                $search_where .= " and rate.time_profile_id = {$_GET['profile']}";
            }
            if (isset($_GET['code_name']) && !empty($_GET['code_name']))
            {
                $search_where .= " and rate.code_name = '{$_GET['code_name']}'";
            }
            if (isset($_GET['country']) && !empty($_GET['country']))
            {
                $search_where .= " and rate.country = '{$_GET['country']}'";
            }
            if (isset($_GET['time']) && !empty($_GET['time']))
            {
                switch ($_GET['time'])
                {
                    case 'current':
                        $search_where .= " and rate.effective_date <= '{$_GET['time_val']}'";
                        break;
                    case 'new':
                        $search_where .= " and rate.effective_date >= '{$_GET['time_val']}'";
                        break;
                    case 'old':
                        $search_where .= " and rate.end_date <= '{$_GET['time_val']}'";
                        break;
                    case 'in':
                        $search_where .= " and (rate.effective_date <= '{$_GET['time_val']}' and rate.end_date >= '{$_GET['time_val']}' or rate.effective_date <= '{$_GET['time_val']}' and rate.end_date is null)";
                        break;
                }
            }
        }


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';

        $page = new MyPage();

        $totalrecords = $this->Clientrate->query("select count(rate_id) as c from rate where rate_table_id = {$id} $search_where");

        $page->setTotalRecords($totalrecords[0][0]['c']);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "select rate.*, time_profile.name as time_profile from rate left join time_profile on rate.time_profile_id = time_profile.time_profile_id where rate_table_id = {$id} {$search_where}";


        $sql .= " limit $pageSize offset $offset";


        $rates = $this->Clientrate->query($sql);

        $page->setDataArray($rates);

        $this->set("p", $page);

        $profiles = $this->Clientrate->query("select time_profile_id, name from time_profile");

        $this->set("profiles", $profiles);
    }

    public function ajax_code_name_rate()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w'])
        {
            $this->redirect_denied();
        }
        $get_data = $this->params['url'];
        $code_name = $get_data['code_name'];
        $rate = $get_data['rate'];
        $effective_date = trim($get_data['date']);
        $test = urldecode($effective_date);
        $effective_date_time = substr($test, 0, -3);

        $effective_date_timezone = trim(substr($test, -3));
        $this->set('effective_date', $effective_date_time);
        $this->set('code_name', $code_name);
        $this->set('rate', $rate);
        $this->set("effective_date_timezone", $effective_date_timezone);
        $this->layout = 'ajax';
        Configure::write('debug', 0);
    }

    public function save_code_name_rate($table_id, $massEdit = null, $npa = '')
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $get_data = $this->params['form'];

        $old_code_name = isset($get_data['old_code_name']) ? $get_data['old_code_name'] : "null";
        $old_rate = isset($get_data['old_rate']) ? $get_data['old_rate'] : "null";
        $old_effective_date = isset($get_data['old_effective_date']) ? $get_data['old_effective_date'] : "null";
        $old_effective_date_timezone = isset($get_data['old_effective_date_timezone']) ? $get_data['old_effective_date_timezone'] : "+00";

//        判断是否修改
        if (!strcmp($get_data['code_name'], $old_code_name) && !strcmp($get_data['rate'], $old_rate) && !strcmp($get_data['effective_date'], $old_effective_date) && !strcmp($get_data['effective_date_timezone'], $old_effective_date_timezone))
        {
            $this->Session->write('m', $this->Clientrate->create_json(201, __('success.', true)));
            $this->redirect("view_code_name_rate/{$table_id}/{$massEdit}/{$npa}");
        }
        if (strlen($old_effective_date_timezone) == 2)
        {
            $old_effective_date_timezone = "+" . $old_effective_date_timezone;
        }
        if ($old_effective_date)
        {
            $old_effective_date .= $old_effective_date_timezone;
        }

        $effective_date = $get_data['effective_date'] . $get_data['effective_date_timezone'];

        $table_id = trim($table_id);
        $where_sql = " WHERE rate_table_id = {$table_id} AND code_name = '{$old_code_name}' AND rate = {$old_rate} AND effective_date = '{$old_effective_date}'";
        $sql = "UPDATE rate SET code_name = '{$get_data['code_name']}',rate = {$get_data['rate']}, effective_date = '{$effective_date}' {$where_sql}";
        $flg = $this->Clientrate->query($sql);

        if ($flg === false)
        {
            $this->Session->write('m', $this->Clientrate->create_json(101, __('Save Failed!', true)));
        }
        else
        {
            $this->Session->write('m', $this->Clientrate->create_json(201, __('Saved Successfully.', true)));
        }
        $this->redirect("view_code_name_rate/{$table_id}/{$massEdit}/{$npa}");
    }

    public function create_code_name_rate($table_id){
        $this->autoRender = false;
        $this->autoLayout = false;
        $get_data = $this->params['form'];
        $effective_date = $get_data['effective_date'] . $get_data['effective_date_timezone'];
        $save_data = [
            'rate_table_id' => $table_id,
            'code_name' => $get_data['code_name'],
            'rate' => $get_data['rate'],
            'effective_date' => $effective_date
        ];
        $flg = $this->Clientrate->save($save_data);
        if ($flg === false){
            $this->Clientrate->create_json_array('#ClientOrigRateTableId', 101, __('Create Rate Failed!', true));
        }else{
            $this->Clientrate->create_json_array('#ClientOrigRateTableId', 200, __('Created Successfully.', true));
        }
        $this->Session->write("m", Clientrate::set_validator());
        $this->redirect("view_code_name_rate/".$table_id);
    }

    public function view_code_name_rate($table_id, $massEdit = null, $npa = '')
    {
        $this->pageTitle = 'Switch/Rate Table';
        $order_sql = "ORDER BY code_name DESC";
        $this->set('name', $this->select_client_name($table_id));
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }
        $get_data = $this->params['url'];

        if (isset($get_data['rate_group']) && $get_data['rate_group'] == 1)
        {
            $this->redirect("view/".base64_encode($table_id) ."/{$massEdit}/{$npa}");
        }

        $where_sql = "";
        if (isset($get_data['effectiveDate']) && $get_data['effectiveDate'])
        {
            $where_sql .= " AND effective_date <= '{$get_data['effectiveDate']}' AND (end_date > '{$get_data['effectiveDate']}' or end_date is null) ";
        }

        if (empty($where_sql))
        {
            if (isset($get_data['filter_effect_date']) && $get_data['filter_effect_date'] == 'all')
            {
                $where_sql = "WHERE 1=1 ";
            }
            else
            {
                $where_sql = "WHERE effective_date <= NOW() and (end_date is null or end_date >= NOW())";
            }
        }
        else
        {
            $where_sql = "WHERE 1=1 " . $where_sql;
        }
        if (isset($get_data['search']['_q']) && $get_data['search']['_q'])
        {
            $where_sql .= " AND code_name ilike '%{$get_data['search']['_q']}%'";
        }
        $where_sql .= " AND rate_table_id = {$table_id}";

        $sql = "select count(*) from rate {$where_sql}";
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Clientrate->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT code_name,effective_date,rate,country from rate {$where_sql} GROUP BY code_name,effective_date,rate,country {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";
        $data = $this->Clientrate->query($sql);

        $page->setDataArray($data);
        $this->set('massEdit', $massEdit);
        $this->set('npa', $npa);
        $this->set('p', $page);
        $this->set('get_data', $get_data);
        $this->set('table_id', $table_id);
        $list = $this->Clientrate->query("select name ,jurisdiction_country_id,(select code from currency where currency_id = rate_table.currency_id) as currency, jur_type from  rate_table  where  rate_table_id=$table_id");

        if (($list[0][0]['jur_type'] == 3 || $list[0][0]['jur_type'] == 4) && $npa !== 'npan')
        {
            $this->xredirect('/clientrates/ocn_lata/' . $table_id);
        }

        $this->set('table_name', $list[0][0]['name']);
        $this->set('jurisdiction_country_id', $list[0][0]['jurisdiction_country_id']);
        $this->set('currency', $list[0][0]['currency']);
        $this->set('jur_type', $list[0][0]['jur_type']);
    }

    public function view($encode_table_id, $massEdit = null, $npa = '')
    {
        if (isset($_GET['breadcrumbs'])) {
            $this->set('breadcrumbs', explode(',', $_GET['breadcrumbs']));
        }

        $table_id = base64_decode($encode_table_id);
        $rate_table_info = $this->Clientrate->query("SELECT count(*) as sum FROM rate_table WHERE rate_table_id = $table_id AND is_virtual = true");
        $this->set("is_virtual", $rate_table_info[0][0]['sum']);
        $tz = $this->Clientrate->get_sys_timezone();
        $gmt = "+00";
        if ($tz)
        {
            $gmt = substr($tz, 0, 3);
        }
        $this->set('gmt', $gmt);

        $this->loadModel('RateTable');
        $schema = $this->RateTable->default_schema;
        $fields = array_keys($schema);
        $this->set('fields', $fields);

        $search_flg = "";
        if (isset($_GET['search_flg']))
        {
            if (isset($this->params['url']['rate_group']) && $this->params['url']['rate_group'] == 2)
            {
                $this->redirect("view_code_name_rate/{$table_id}/{$massEdit}/{$npa}");
            }
            $search_flg = "1";
            $search_q = isset($_GET['search']['_q']) ? $_GET['search']['_q'] : 0;
            $this->set('search_q', $search_q);
            $this->set('search_flg', $search_flg);
        }
        $table_id+=0;

        $action_type = isset($_POST['stage']) ? $_POST['stage'] : '';
        $this->pageTitle = "Editing rates";
        $list = $this->Clientrate->query("select name ,jurisdiction_country_id,(select code from currency where currency_id = rate_table.currency_id) as currency, jur_type from  rate_table  where  rate_table_id=$table_id");

        if ($massEdit == 'massEdit')
        {
            $this->massEdit($table_id, $list[0][0]['jur_type']);
        }
        $times = $this->Clientrate->getTimeProfile();
        $t = '';
        foreach ($times as $key => $value)
        {
            $time_profile_id = $value[0]['time_profile_id'];
            $name = $value[0]['name'];
            $t.="\"$time_profile_id\": \"$name\",";
        }
        $t = "{" . substr($t, 0, -1) . "}";
        $this->set('table_id', $table_id);
        $this->loadModel('Rate');
        $result = $this->Rate->getOneRate($table_id);

        $addShowResult = $result[0][0];
        switch ($addShowResult)
        {
            case 0:
                $addShowResult['rate_type'] = "DNIS";
                break;
            case 1:
                $addShowResult['rate_type'] = "lrn";
                break;
            case 2:
                $addShowResult['rate_type'] = "lrn block";
                break;
            default:
                $addShowResult['rate_type'] = "DNIS";
        }
        $this->set('addShowResult', $addShowResult);

        if (($list[0][0]['jur_type'] == 3 || $list[0][0]['jur_type'] == 4) && $npa !== 'npan')
        {
            $this->xredirect('/clientrates/ocn_lata/' . $table_id);
        }

        $this->set('table_name', $list[0][0]['name']);
        $this->set('jurisdiction_country_id', $list[0][0]['jurisdiction_country_id']);
        $this->set('currency', $list[0][0]['currency']);
        $this->set('t', $t);
        $this->set('timepro', $this->Clientrate->find_timeprofile1());
        $show = empty($_GET['search']['show']) ? 'html' : $_GET['search']['show'];
        $rate_list = $this->Clientrate->find_all_rate($table_id, $list[0][0]['jur_type'], $npa == 'npan', $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array(), $show);

        if ($show == 'csv' /*|| ($massEdit == 'massEdit' && $action_type == 'preview')*/)
        {
//            if($action_type == 'preview'){
//                $this->Clientrate->rollback();
//            }

            Configure::write('debug', 0);
            $this->layout = 'csv';
            $csv_name = "rate_table" . date("YmdHis") . "_" . rand(0, 99) . ".csv";
            header("Content-type: application/octet-stream;charset=utf8");
            header("Accept-Ranges: bytes");
            header("Content-Disposition: attachment; filename=" . $csv_name);
            if (!empty($rate_list->dataArray))
            {
                $rate_arr = $rate_list->dataArray;
                echo implode(",", array_keys($rate_arr[0][0])), "\n";
                foreach ($rate_arr as $k => $v)
                {
                    echo implode(",", $v[0]), "\n";
                }
            }
        }

        $this->set('jur_type', $list[0][0]['jur_type']);
        $this->set('p', $rate_list);
        $this->set('name', $this->select_client_name($table_id));



        if ($npa == 'npan')
        {
            $this->render('view2');
        }
    }

    public function ocn_lata($rate_table_id)
    {
        $this->pageTitle = "Switch/Rates";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'asc',
            ),
            'conditions' => 'WHERE rate_table_id = ' . $rate_table_id
        );

        if (isset($_GET['search']['_q']) && !empty($_GET['search']['_q']))
        {
            $this->paginate['conditions'] .= " and ocn = '{$_GET['search']['_q']}' or lata = '{$_GET['search']['_q']}'";
        }

        $this->data = $this->paginate('OcnLata');
        $list = $this->Clientrate->query("select name ,jurisdiction_country_id,(select code from currency where currency_id = rate_table.currency_id) as currency, jur_type from  rate_table  where  rate_table_id=$rate_table_id");
        $this->set('table_name', $list[0][0]['name']);
        $this->set('table_id', $rate_table_id);
        $this->set('currency', $list[0][0]['currency']);
    }

    public function validate_rate()
    {
        $flag = 'true';
        $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
        $size = count($tmp);

        return $flag;
    }

    /**
     *
     * # previously
     * effective date
     * end date
     *
     *
     */
    public function pre_deal_time()
    {

        if (isset($_POST ['rates']))
        {
            $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
            $size = count($tmp);

            if ($size > 0)
            {

                foreach ($tmp as $key => $value)
                {
                    $_POST ['rates'][$key]['effective_date'] = $this->time_tools($tmp[$key]['effective_date'], date("Y-m-d"), '00:00:00');

                    if (!empty($_POST ['rates'][$key]['end_date']))
                    {
                        $_POST ['rates'][$key]['end_date'] = $this->time_tools($tmp[$key]['end_date'], date("Y-m-d"), '23:59:59');
                    }
                }
            }
        }
    }

    public function time_tools($date_str, $default_date, $deault_time)
    {
        $arr = explode(" ", $date_str);


        $arr = filter_empty_value($arr);

        if (isset($arr[0]))
        {
            $date = $arr[0];
        }
        else
        {
            $date = $default_date;
        }


        if (isset($arr[1]))
        {
            $time = $arr[1];
        }
        else
        {
            $time = $deault_time;
        }


        if (!exchange_isDate($date))
        {

            $date = $default_date;
        }

        if (empty($time))
        {

            $time = $deault_time;
        }

        return $date . ' ' . $time;
    }

    public function test_time()
    {
        $default_start_date = date("Y-m-d");
        $default_end_date = '';
        $default_start_time = '00:00:00';
        $default_end_time = '23:59:59';
        $a = $this->time_tools('2010-02-01    ', $default_start_date, $default_start_time);
        pr($a);
    }

    public function test_cache()
    {
        $sql = "select  count(*) as c  from client ";
        $list = $this->Clientrate->query($sql);
        $list = $this->Clientrate->query($sql);
    }

    function check_restrict($key, $rate_table_id, $data)
    {
        $code_where = !empty($data ['code']) ? " and code='{$data ['code']}'   " : "and( code  is  null  or code='') ";
        $effective_date_where = !empty($data ['effective_date']) ? " and effective_date='{$data ['effective_date']}'  " : "and effective_date  is  null ";
        $end_date = !empty($data ['end_date']) ? " and end_date='{$data ['end_date']}'  " : "and end_date  is  null ";

        // $zone_where = !empty($data ['zone']) ? " and zone='{$data ['zone']}'  " : "and ( zone  =''  or zone is  null)";
        // $time_profile_id_where = !empty($data ['time_profile_id']) ? " and time_profile_id={$data ['time_profile_id']}  " : "and time_profile_id  is  null ";

        $rate_id_where = !empty($data ['rate_id']) ? " and rate_id<>{$data ['rate_id']}  " : "";

        $random_where = "$key=$key";

        $sql = "select  count(*) as c  from rate  where  $random_where and  rate_table_id =$rate_table_id $code_where $effective_date_where  $end_date  $rate_id_where";
        $list = $this->Clientrate->query($sql);

        // echo $list[0][0]['c'];exit();

        if (($list[0][0]['c']))
        {

            return false;
        }
        else
        {

            return true;
        }
    }

    private function checkRate($rate)
    {
        if(!ctype_alpha($rate['country'])) {
            return false;
        }
        return true;
    }

    public function add_rate()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $jm = bin2hex(gzcompress("page={$_GET['page']}&size={$_GET['size']}", 9));
        $this->pre_deal_time();


        if (empty($_POST['id']))
        {
            $this->redirect('/rates/rates_list/');
        }
        $rate_table_id = $_POST['id'];


        $delete_rate_id = $_POST['delete_rate_id'];
        $delete_rate_id = substr($delete_rate_id, 1);
        if (!empty($delete_rate_id))
        {
            $this->Clientrate->query("delete  from  rate where rate_id in($delete_rate_id)");
        }



        if ($this->validate_rate() == 'false')
        {
            $this->Session->write("m", Clientrate::set_validator());
            $this->redirect("/clientrates/view/".base64_encode($rate_table_id) ."?qs={$jm}");
        };
        //$allow_code_results = $this->Clientrate->checkCodedeck($rate_table_id);

        $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
        //$this->Clientrate->query("begin");
        $size = count($tmp);
        $update_sql = "";
        $success_count = 0;
        $error_count = 0;
        $total_count = count($tmp);

        foreach ($tmp as $key => &$el)
        {
            $this->data['Clientrate'] = $el;
            $this->data['Clientrate']['rate_table_id'] = $rate_table_id;

            $flag = $this->check_restrict($key, $rate_table_id, $el);
            if (!$flag)
            {
                $this->Clientrate->create_json_array('#ClientOrigRateTableId', 101, __('duplicated', true));
                $this->Session->write("m", Clientrate::set_validator());
                $this->redirect("/clientrates/view/".base64_encode($rate_table_id) ."?qs={$jm}");
            }

            $flag = $this->valid($this->data['Clientrate']['code'], $this->data['Clientrate']['effective_date'], $this->data['Clientrate']['effective_date_timezone'], $this->data['Clientrate']['time_profile_id'], $rate_table_id, $this->data['Clientrate']['rate_id']);

            if ($flag)
            {
                //$this->Clientrate->query("rollback");
                $this->Clientrate->create_json_array('#ClientOrigRateTableId', 101, $this->data['Clientrate']['code'] . " is duplicated");
                $this->Session->write("m", Clientrate::set_validator());
                $this->redirect("/clientrates/view/".base64_encode($rate_table_id) ."?qs={$jm}");
            }
            $code_name = $el['code_name'];
            // rate update
            $rate = $el['rate'];
            if( isset($el['new_rate']) && $el['new_rate']){
                $rate = $el['new_rate'];
            }
            // end date update
            if( isset($el['new_effective_date']) && $el['new_effective_date']){
                $end_date_post = date("Y-m-d H:i:sO", strtotime($el['new_effective_date'] . $el['new_effective_date_timezone']) - 1);
            }else{
                $end_date_post = date("Y-m-d H:i:sO", strtotime($el['effective_date'] . $el['effective_date_timezone']) - 1);
            }

            $time_profile_id_sql = " and time_profile_id IS NULL";
            if($el['time_profile_id']){
                $time_profile_id_sql = " and time_profile_id = {$el['time_profile_id']}" ;
            }
            $update_sql .= "UPDATE rate SET end_date='{$end_date_post}', rate='{$rate}' WHERE  end_date is null AND rate_table_id = {$rate_table_id}
                            AND code = '{$el['code']}' AND effective_date::timestamp with time zone < timestamp with time zone '{$end_date_post}' $time_profile_id_sql;";

            if (!empty($this->data ['Clientrate']['effective_date_timezone']) && !empty($this->data ['Clientrate']['effective_date']))
                $this->data ['Clientrate']['effective_date'] = $this->data ['Clientrate']['effective_date'] . $this->data ['Clientrate']['effective_date_timezone'];
            if (!empty($this->data ['Clientrate']['end_date_timezone']) && !empty($this->data ['Clientrate']['end_date']))
                $this->data ['Clientrate']['end_date'] = $this->data ['Clientrate']['end_date'] . $this->data ['Clientrate']['end_date_timezone'];

            unset($this->data['Clientrate']['new_rate']);
            unset($this->data['Clientrate']['new_effective_date']);
            unset($this->data['Clientrate']['new_effective_date_timezone']);
            if (isset($el['new_intra_rate']) && $el['new_intra_rate'] != '') {
                $this->data['Clientrate']['intra_rate'] = $el['new_intra_rate'];
                unset($this->data['Clientrate']['new_intra_rate']);
            }
            if (isset($el['new_inter_rate']) && $el['new_intra_rate'] != '') {
                $this->data['Clientrate']['inter_rate'] = $el['new_inter_rate'];
                unset($this->data['Clientrate']['new_inter_rate']);
            }
            if ($this->Clientrate->save($this->data ['Clientrate']) === false)
                $error_count += 1;
            else
                $success_count += 1;
            $this->data['Clientrate']['rate_id'] = false;
        }
        $this->Clientrate->query($update_sql);
        //$this->Clientrate->query("commit");
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $this->Clientrate->query("update rate_table set update_at = '{$update_at}', update_by = '{$update_by}' WHERE rate_table_id = {$rate_table_id}");
        $rate_table_info = $this->Clientrate->query("select name from rate_table where rate_table_id = {$rate_table_id}");
        $rate_table_name = $rate_table_info[0][0]['name'];
        $this->Clientrate->create_json_array('#ClientOrigRateTableId', 201, __('You have modified %s records for rate table [%s].Success %s records,error %s records', true,array($total_count,$rate_table_name,$success_count,$error_count)));
        $this->Session->write("m", Clientrate::set_validator());
        $this->Session->write("mm", 2);
        $this->redirect("/clientrates/view/".base64_encode($rate_table_id) ."?qs={$jm}");
    }

    public function valid($code, $effective_date, $effective_timezone, $time_profile_id, $rate_table_id, $rate_id)
    {

        return false;

        $effective = strtotime($effective_date . $effective_timezone);

        $sql = "SELECT effective_date, time_profile_id FROM rate WHERE code = '{$code}' AND rate_table_id = {$rate_table_id}";
        if (!empty($rate_id))
            $sql .= " AND rate_id != {$rate_id}";

        $data = $this->Clientrate->query($sql);
        foreach ($data as $item)
        {
            $item_effective = strtotime($item[0]['effective_date']);
            if ($effective == $item_effective)
            {
                $flag = $effective == $item_effective;
                if (empty($time_profile_id))
                {
                    if ($flag)
                        return true;
                } else
                {
                    if ($time_profile_id == $item[0]['time_profile_id'])
                        return true;
                }
            }
        }
        return false;
    }

    public function isMixTimeProfile($type1, $start_time1, $end_time1, $start_week1, $end_week1, $type2, $start_time2, $end_time2, $start_week2, $end_week2)
    {
        if ($type1 == 0 && $type2 == 0)
        {
            return true;
        }

        if (($type1 == 1 && $type2 == 2) || ($type1 == 2 && $type2 == 1))
        {
            return true;
        }


        if ($type1 == 2)
        {
            if ($this->isMixTime($start_time1, $end_time1, $start_time2, $end_time2))
            {
                return true;
            }
        }
        elseif ($type1 == 1)
        {
            $start_week_1 = max($start_week1, $end_week1);
            $end_week_1 = min($start_week1, $end_week1);
            $start_week_2 = max($start_week2, $end_week2);
            $end_week_2 = min($start_week2, $end_week2);
            if ($this->isMixTime($start_week_1, $end_week_1, $start_week_2, $end_week_2))
            {
                return true;
            }
        }
        return false;
    }

    public function isMixTime($begintime1, $endtime1, $begintime2, $endtime2)
    {
        $status = $begintime2 - $begintime1;
        if ($status > 0)
        {
            $status2 = $begintime2 - $endtime1;
            if ($status2 > 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            $status2 = $begintime1 - $endtime2;
            if ($status2 > 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    public function mass_code_name_insert($code_name, $data)
    {
        $list = $this->Clientrate->query(" select code from  code  where  name='{$code_name}';");
        $count = count($list);
        if (!empty($list) && $count > 0)
        {
            for ($index = 0; $index < $count; $index++)
            {

            }
        }
    }

    /**
     * 批量更新
     */
    public function massEdit($rate_table_id, $jur_type)
    {
        $this->cacheAction = false;
        $this->disableCache();

        $action = empty($_REQUEST['action']) ? '' : $_REQUEST['action'];
        $action_type = $_POST['stage'];

        $before_data = $this->rate_save_before_data($action,$rate_table_id);



        $preview_data = array();
        if ($action == 'delete')
        {
            if($action_type == "process") {
                if (!empty($_REQUEST['rate_ids'])) {
                    $this->mass_delete($rate_table_id, $_REQUEST['rate_ids']);
                } else {
                    //$this->mass_delete($rate_table_id);
                }
            }
            $sql_type = 0;
        }
        if ($action == 'insert')
        {
            if (!empty($_REQUEST['rate_ids']))
            {
                $preview_data = $this->mass_insert($rate_table_id, $_REQUEST['rate_ids']);


                /* $ids = explode(",", $_REQUEST['rate_ids']);
                  foreach ($ids as $k=>$v)
                  {
                  $this->mass_insert($rate_table_id, intval($v));
                  } */
            }
            $sql_type = 1;
        }

        if ($action == 'update')
        {
            if (!empty($_REQUEST['rate_ids']))
            {
                $ids = explode(",", $_REQUEST['rate_ids']);

                foreach ($ids as $k => $v)
                {
                    $preview_data[] = $this->mass_update($rate_table_id, intval($v));
                }

            }
            else
            {
                //$this->mass_update($rate_table_id);
            }
            $sql_type = 2;
        }

        if ($action == 'updateall')
        {

            $this->mass_update_all($rate_table_id, $_REQUEST['searchstr']);
            $sql_type = 3;
        }


        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $this->Clientrate->query("update rate_table set update_at = '{$update_at}', update_by = '{$update_by}' WHERE rate_table_id = {$rate_table_id}");

        /*if($action_type == "process"){
            //$this->Clientrate->commit();
        } else {
            //$this->Clientrate->query("savePoint p2");

        }*/



        if($action_type == "process"){
            $after_data = $this->rate_save_after_data($action,$rate_table_id);
        } else {
            $after_data = $preview_data;
        }

        if($action != 'updateall'){
            $true_file_path = $this->save_rate_csv($before_data,$after_data, $jur_type);
            $ids = explode(",", $_REQUEST['rate_ids']);
            $action_rate_rows = count($ids);
        } else {

            $true_file_path = $before_data . ';' . $after_data;
            $action_rate_rows = -1;
        }
        if($action_type == "process"){

            //log
            $client_id = $_SESSION['sst_user_id'];
            $rate_table_id = $rate_table_id;


            $sql = "insert into rate_mass_edit_log(action_time,client_id,rate_table_id,action_type,action_rate_rows, down_file_path) values(current_timestamp(0),$client_id,$rate_table_id,
                    $sql_type,$action_rate_rows,'$true_file_path')";

            $this->Clientrate->query($sql);


        } else {
            $rate_table_name = $this->Clientrate->query("select name from rate_table where rate_table_id = $rate_table_id");
            $file_name = 'preview_rate_table[' . $rate_table_name[0][0]['name'] . ']_' . date('Ymd') . '.csv';
            Configure::write('debug', 0);

            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename='.$file_name);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($true_file_path));
            ob_clean();
            flush();
            readfile($true_file_path);
            exit;
        }

        if ($action == 'delete')
        {
            $this->xredirect("/clientrates/view/" . $rate_table_id);
        }

        return "/clientrates/view/$rate_table_id?page={$this->params['pass'][2]}&size={$this->params['pass'][3]}";
    }

    function mass_insert($table_id, $ids = null)
    {
        $data = array();
        $f = $_REQUEST;
        $data['Clientrate']['rate_table_id'] = $table_id;
        $rate = $f['rate_per_min_action']; //费率
        $mintime = $f['min_time_action']; //最小时长
        $starttime = $f['effective_from_action']; //开始时间
        $setupfee = $f['pay_setup_action']; //一分钟的费用
        $interval = $f['pay_interval_action']; //计费周期
        $endtime = $f['end_date_action']; //结束时间
        $gracetime = $f['grace_time_action']; //赠送时长
        $time_profile = $f['id_time_profiles_action'];
        $seconds = $f['seconds_action'];
        $inter_rate = $f['inter_rate_action'];
        $intra_rate = $f['intra_rate_action'];
        $local_rate = $f['local_rate_action'];

        //----------------------------------
        if ($time_profile != 'none' && !empty($f['id_time_profiles_value']))
        {
            $data['Clientrate']['time_profile_id'] = $f['id_time_profiles_value'];
        }
        if ($seconds != 'none')
        {
            $data['Clientrate']['seconds'] = $f['seconds_value'];
        }
        if ($inter_rate != 'none')
        {
            $data['Clientrate']['inter_rate'] = $f['inter_rate_value'];
        }
        if ($intra_rate != 'none')
        {
            $data['Clientrate']['intra_rate'] = $f['intra_rate_value'];
        }
        if ($local_rate != 'none')
        {
            $data['Clientrate']['local_rate'] = $f['local_rate_value'];
        }
        #rate
        if ($rate != 'none')
        {
            $rate_v = $f['rate_per_min_value'];
            $data['Clientrate']['rate'] = $rate_v;
//					//设置为该提交的值
//					if ($rate == 'set'){$data['Clientrate']['rate']=$rate_v;}
//					//在基础上加
//					else if ($rate == 'inc'){$data['Clientrate']['rate'] = "rate + $rate_v";}
//					//在基础上减
//					else if ($rate == 'dec'){$data['Clientrate']['rate'] = "rate - $rate_v";}
//					//按百分比加
//					else if ($rate == 'perin'){$data['Clientrate']['rate'] = "rate +(rate*$rate_v/100)";}
//					//按百分比减
//					else if ($rate == 'perde'){$data['Clientrate']['rate'] = "rate -(rate*$rate_v/100)";}
//					
//					else if ($rate == 'mul'){$data['Clientrate']['rate'] = "rate*$rate_v";}
        }
        #min_time 首次时长
        if ($mintime != 'none')
        {
            $mintime_v = $f['min_time_value'];
            $data['Clientrate']['min_time'] = $mintime_v;
            //设置为该提交的值
//					if ($mintime == 'set'){$sql .= ",min_time=$mintime_v";$sql_select.=" ,$mintime_v as rate";}
//					//在基础上加
//					else if ($mintime == 'inc'){$sql .= ",min_time=min_time+$mintime_v";$sql_select.=" ,min_time+$mintime_v as min_time";}
//					//在基础上减
//					else if ($mintime == 'dec'){$sql .= ",min_time=min_time-$mintime_v";$sql_select.=" ,min_time-$mintime_v as min_time";}
        }


#开始时间
        if ($starttime != 'none')
        {
            if (!empty($f['effective_from_value']))
                $data['Clientrate']['effective_date'] = "'" . $f['effective_from_value'] . "'";
        }

        if ($setupfee != 'none')
        {
            $setupfee_v = $f['pay_setup_value'];
            $data['Clientrate']['setup_fee'] = $setupfee_v;
            //设置为该提交的值
//							if ($setupfee == 'set'){$sql .= ",setup_fee=$setupfee_v";$sql_select.=" ,$setupfee_v as setup_fee";}
//							//在基础上加
//							else if ($setupfee == 'inc'){$sql .= ",setup_fee=setup_fee+$setupfee_v";$sql_select.=" ,setup_fee+$setupfee_v as setup_fee";}
//							//在基础上减
//							else if ($setupfee == 'dec'){$sql .= ",setup_fee=setup_fee-$setupfee_v";$sql_select.=" ,setup_fee-$setupfee_v as setup_fee";}
//							//按百分比加
//							else if ($setupfee == 'perin'){$sql .= ",setup_fee = setup_fee +(setup_fee*$setupfee_v/100)";$sql_select.=" ,setup_fee+(setup_fee*$setupfee_v/100) as setup_fee";}
//							//按百分比加
//							else if ($setupfee == 'perde'){$sql .= ",setup_fee = setup_fee -(setup_fee*$setupfee_v/100)";$sql_select.=" ,setup_fee-(setup_fee*$setupfee_v/100) as setup_fee";}
//				
        }

        if ($interval != 'none')
        {
            $data['Clientrate']['interval'] = $f['pay_interval_value'];
        }

        if ($endtime != 'none')
        {
            $data['Clientrate']['end_date'] = "'" . $f['end_date_value'] . "'";
        }

        if ($gracetime != 'none')
        {
            $data['Clientrate']['grace_time'] = $f['grace_time_value'];
        }
        //------------------------------

        $ids_arr = explode(",", $ids);

        $choose_rate = false;
        if (isset($data['Clientrate']['rate']))
        {
            $choose_rate = true;
        }
        $choose_min_time = false;
        if (isset($data['Clientrate']['min_time']))
        {
            $choose_min_time = true;
        }
        $choose_interval = false;
        if (isset($data['Clientrate']['interval']))
        {
            $choose_interval = true;
        }
        $choose_grace_time = false;
        if (isset($data['Clientrate']['grace_time']))
        {
            $choose_grace_time = true;
        }
        $choose_seconds = false;
        if (isset($data['Clientrate']['seconds']))
        {
            $choose_seconds = true;
        }
        $choose_time_profile_id = false;
        if (isset($data['Clientrate']['time_profile_id']))
        {
            $choose_time_profile_id = true;
        }
//        $choose_end_date = false;
//        if (isset($data['Clientrate']['end_date']))
//        {
//            $choose_end_date = true;
//        }
        $return_id = array();
        $preview_data = array();
        foreach ($ids_arr as $k => $v)
        {
            $rate_old_info = $this->Clientrate->query("select * from rate where rate_id = " . intval($v));
            $data['Clientrate']['code'] = "'" . $rate_old_info[0][0]['code'] . "'";
            $data['Clientrate']['code_name'] = "'" . $rate_old_info[0][0]['code_name'] . "'";
            $data['Clientrate']['country'] = "'" . $rate_old_info[0][0]['country'] . "'";

            if (!$choose_rate)
            {
                $data['Clientrate']['rate'] = $rate_old_info[0][0]['rate'];
            }
            if (!$choose_min_time)
            {
                $data['Clientrate']['min_time'] = $rate_old_info[0][0]['min_time'];
            }
            if (!$choose_interval)
            {
                $data['Clientrate']['interval'] = $rate_old_info[0][0]['interval'];
            }
            if (!$choose_grace_time)
            {
                $data['Clientrate']['grace_time'] = $rate_old_info[0][0]['grace_time'];
            }
            if (!$choose_seconds)
            {
                $data['Clientrate']['seconds'] = $rate_old_info[0][0]['seconds'];
            }
//            if (!$choose_end_date)
//            {
//                $data['Clientrate']['end_date'] = "'".$rate_old_info[0][0]['end_date'] . "'";
//            }
            if (!$choose_time_profile_id)
            {

                if($rate_old_info[0][0]['time_profile_id']){

                    $data['Clientrate']['time_profile_id'] = $rate_old_info[0][0]['time_profile_id'];
                }
            }
            //$this->data['Clientrate'] = $data['Clientrate'];
            //$return = $this->Clientrate->save( $this->data['Clientrate']);
            if (strstr($rate_old_info[0][0]['effective_date'], '+', TRUE) == $f['effective_from_value'])
            {
                $this->Session->write('m', $this->Clientrate->create_json(101, __('The effective date can not be the same!', true)));
                $this->xredirect("/clientrates/view/" . $table_id);
            }

            $action_type = $_POST['stage']; //执行方式
            if($action_type == 'process'){
                $sql_in = implode(",", array_keys($data['Clientrate']));
                $sql_value = implode(",", $data['Clientrate']);

                $end_date_new = date("Y-m-d H:i:s", strtotime($f['effective_from_value']) - 1);
                $this->Clientrate->query("update rate set end_date = '{$end_date_new}' where rate_id = {$v}");
                $return = $this->Clientrate->query("insert into rate ({$sql_in}) values ({$sql_value}) returning rate_id");
                $return_id[] = $return[0][0]['rate_id'];
            } else {
                if(!isset($data['Clientrate']['effective_date']))
                    $data['Clientrate']['effective_date'] = date('Y-m-d H:i:s');
                $preview_data[][0] = $data['Clientrate'];
            }

        }
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $this->Clientrate->query("update rate_table set update_at = '{$update_at}', update_by = '{$update_by}' WHERE rate_table_id = {$table_id}");
        $this->Session->write('m', $this->Clientrate->create_json(201, __('success', true)));
        $_POST['after_insert_ids'] = implode(',', $return_id);
        return $preview_data;
    }

    /**
     *
     * 批量更新
     * @param $table_id
     */
    public function mass_update($table_id, $id = null)
    {
        $data = array();
        $f = $_POST;
        $type = $f['stage'] ?: 'none';
        $rate = $f['rate_per_min_action'] ?: 'none';
        $mintime = $f['min_time_action'] ?: 'none';
        $starttime = $f['effective_from_action'] ?: 'none';
        $setupfee = $f['pay_setup_action'] ?: 'none';
        $interval = $f['pay_interval_action'] ?: 'none';
        $endtime = $f['end_date_action'] ?: 'none';
        $gracetime = $f['grace_time_action'] ?: 'none';
        $time_profile = $f['id_time_profiles_action'] ?: 'none';
        $seconds = isset($f['seconds_action']) ? $f['seconds_action'] : 'none';
        $inter_rate = $f['inter_rate_action'] ?: 'none';
        $intra_rate = $f['intra_rate_action'] ?: 'none';
        $local_rate = isset($f['local_rate_action']) ? $f['local_rate_action'] : 'none';
        $sql = "update rate set rate_id = rate_id";
        $sql_select = "select rate_id,code,rate_table_id,code_name";
        if ($time_profile != 'none' && !empty($f['id_time_profiles_value']))
        {
            $sql .= ",time_profile_id={$f['id_time_profiles_value']}";
            $sql_select .= ",(select name from time_profile where time_profile_id = {$f['id_time_profiles_value']}) as time_profile_name";
        }
        else
        {
            $sql_select .= ",(select name from time_profile where time_profile_id = rate.time_profile_id) as time_profile_name";
        }
        if ($seconds != 'none')
        {
            $sql .= ",seconds={$f['seconds_value']}";
            $sql_select.=" ,{$f['seconds_value']} as seconds";
        }
        else
        {
            $sql_select .= ",seconds";
        }
        if ($inter_rate != 'none')
        {
            $sql .= ",inter_rate={$f['inter_rate_value']}";
            $sql_select.=" ,{$f['inter_rate_value']} as inter_rate";
        }
        else
        {
            $sql_select .= ",inter_rate";
        }
        if ($intra_rate != 'none')
        {
            $sql .= ",intra_rate={$f['intra_rate_value']}";
            $sql_select.=" ,{$f['intra_rate_value']} as intra_rate";
        }
        else
        {
            $sql_select .= ",intra_rate";
        }
        if ($local_rate != 'none')
        {
            $sql .= ",local_rate={$f['local_rate_value']}";
            $sql_select.=" ,{$f['local_rate_value']} as local_rate";
        }
        else
        {
            $sql_select .= ",local_rate";
        }
        #rate
        if ($rate != 'none')
        {
            $rate_v = $f['rate_per_min_value'];
            //设置为该提交的值
            if ($rate == 'set')
            {
                $sql .= ",rate=$rate_v";
                $sql_select.=" ,$rate_v as rate";
            }
            //在基础上加
            else if ($rate == 'inc')
            {
                $sql .= ",rate = rate + $rate_v";
                $sql_select.=" ,rate+$rate_v as rate";
            }
            //在基础上减
            else if ($rate == 'dec')
            {
                $sql .= ",rate = rate - $rate_v";
                $sql_select.=" ,rate-$rate_v as rate";
            }
            //按百分比加
            else if ($rate == 'perin')
            {
                $sql .= ",rate = rate +(rate*$rate_v/100)";
                $sql_select.=" ,rate+(rate*$rate_v/100) as rate";
            }
            //按百分比减
            else if ($rate == 'perde')
            {
                $sql .= ",rate = rate -(rate*$rate_v/100)";
                $sql_select.=" ,rate-(rate*$rate_v/100) as rate";
            }
            else if ($rate == 'mul')
            {
                $sql .= ",rate = rate*$rate_v";
                $sql_select.=" ,rate*$rate_v as rate";
            }
        }
        else
        {
            $sql_select .= ",rate";
        }
        #min_time 首次时长
        if ($mintime != 'none')
        {
            $mintime_v = $f['min_time_value'];
            //设置为该提交的值
            if ($mintime == 'set')
            {
                $sql .= ",min_time=$mintime_v";
                $sql_select.=" ,$mintime_v as min_time";
            }
            //在基础上加
            else if ($mintime == 'inc')
            {
                $sql .= ",min_time=min_time+$mintime_v";
                $sql_select.=" ,min_time+$mintime_v as min_time";
            }
            //在基础上减
            else if ($mintime == 'dec')
            {
                $sql .= ",min_time=min_time-$mintime_v";
                $sql_select.=" ,min_time-$mintime_v as min_time";
            }
        }
        else
        {
            $sql_select .= ",min_time";
        }

#开始时间
        if ($starttime != 'none')
        {
            if (!empty($f['effective_from_value']))
            {
                $sql .= ",effective_date='{$f['effective_from_value']}'";
                $sql_select.=" ,'{$f['effective_from_value']}' as effective_date";
            }
            else
            {
                $sql_select .= ",effective_date";
            }
        }
        else
        {
            $sql_select .= ",effective_date";
        }

        if ($setupfee != 'none')
        {
            $setupfee_v = $f['pay_setup_value'];
            //设置为该提交的值
            if ($setupfee == 'set')
            {
                $sql .= ",setup_fee=$setupfee_v";
                $sql_select.=" ,$setupfee_v as setup_fee";
            }
            //在基础上加
            else if ($setupfee == 'inc')
            {
                $sql .= ",setup_fee=setup_fee+$setupfee_v";
                $sql_select.=" ,setup_fee+$setupfee_v as setup_fee";
            }
            //在基础上减
            else if ($setupfee == 'dec')
            {
                $sql .= ",setup_fee=setup_fee-$setupfee_v";
                $sql_select.=" ,setup_fee-$setupfee_v as setup_fee";
            }
            //按百分比加
            else if ($setupfee == 'perin')
            {
                $sql .= ",setup_fee = setup_fee +(setup_fee*$setupfee_v/100)";
                $sql_select.=" ,setup_fee+(setup_fee*$setupfee_v/100) as setup_fee";
            }
            //按百分比加
            else if ($setupfee == 'perde')
            {
                $sql .= ",setup_fee = setup_fee -(setup_fee*$setupfee_v/100)";
                $sql_select.=" ,setup_fee-(setup_fee*$setupfee_v/100) as setup_fee";
            }
        }
        else
        {
            $sql_select .= ",setup_fee";
        }

        if ($interval != 'none')
        {
            $sql .= ",interval={$f['pay_interval_value']}";
            $sql_select.=" ,{$f['pay_interval_value']} as interval";
        }
        else
        {
            $sql_select .= ",interval";
        }

        if ($endtime != 'none')
        {
            $sql .= ",end_date='{$f['end_date_value']}'";
            $sql_select.=" ,{$f['pay_interval_value']} as end_date";
        }
        else
        {
            $sql_select .= ",end_date";
        }

        if ($gracetime != 'none')
        {
            $sql .= ",grace_time={$f['grace_time_value']}";
            $sql_select.=" ,{$f['grace_time_value']} as grace_time";
        }
        else
        {
            $sql_select .= ",grace_time";
        }

        if ($type == 'process')
        {//应用
            $sql .= " where rate_table_id =$table_id";

            $sql = str_replace('rate_id = rate_id,', '', $sql);
            if (!empty($id))
            {
                $sql .= " and rate_id = $id";
            }

            $qs = $this->Clientrate->query($sql);
            if (count($qs) == 0)
            {
                $this->Session->write('m', $this->Clientrate->create_json(201, __('success', true)));
            }
            else
            {
                $this->Session->write('m', $this->Clientrate->create_json(101, __('Failed', true)));
            }
        } else {//预览
            $rst = $this->Clientrate->query($sql_select . " from rate where rate_table_id =$table_id and rate_id = $id");
            if($rst)
                $rst = $rst[0];
            return $rst;
        }
    }

    public function mass_update_all($table_id, $searchstr)
    {
        $data = array();
        $f = $_POST;
        $type = $f['stage']; //执行方式
        $rate = isset($f['rate_per_min_action']) ? $f['rate_per_min_action'] : ''; //费率
        $mintime = isset($f['min_time_action']) ? $f['min_time_action'] : ''; //最小时长
        $starttime = isset($f['effective_from_action']) ? $f['effective_from_action'] : ''; //开始时间
        $setupfee = isset($f['pay_setup_action']) ? $f['pay_setup_action'] : ''; //一分钟的费用
        $interval = isset($f['pay_interval_action']) ? $f['pay_interval_action'] : ''; //计费周期
        $endtime = isset($f['end_date_action']) ? $f['end_date_action'] : ''; //结束时间
        $gracetime = isset($f['grace_time_action']) ? $f['grace_time_action'] : ''; //赠送时长
        $time_profile = isset($f['id_time_profiles_action']) ? $f['id_time_profiles_action'] : '';
        $seconds = isset($f['seconds_action']) ? $f['seconds_action'] : '';
        $inter_rate = isset($f['inter_rate_action']) ? $f['inter_rate_action'] : '';
        $intra_rate = isset($f['intra_rate_action']) ? $f['intra_rate_action'] : '';
        $local_rate = isset($f['local_rate_action']) ? $f['local_rate_action'] : '';
        $sql = "update rate set rate_id = rate_id";

        $sql_select = "select rate_id,code,rate_table_id,code_name";
        if ($time_profile && $time_profile != 'none' && !empty($f['id_time_profiles_value']))
        {
            $sql .= ",time_profile_id={$f['id_time_profiles_value']}";
            $sql_select .= ",(select name from time_profile where time_profile_id = {$f['id_time_profiles_value']}) as tf";
        }
        else
        {
            $sql_select .= ",(select name from time_profile where time_profile_id = rate.time_profile_id) as tf";
        }
        if ($seconds && $seconds != 'none')
        {
            $sql .= ",seconds={$f['seconds_value']}";
            $sql_select.=" ,{$f['seconds_value']} as seconds";
        }
        else
        {
            $sql_select .= ",seconds";
        }
        if ($inter_rate && $inter_rate != 'none')
        {
            $sql .= ",inter_rate={$f['inter_rate_value']}";
            $sql_select.=" ,{$f['inter_rate_value']} as inter_rate";
        }
        else
        {
            $sql_select .= ",inter_rate";
        }
        if ($intra_rate && $intra_rate != 'none')
        {
            $sql .= ",intra_rate={$f['intra_rate_value']}";
            $sql_select.=" ,{$f['intra_rate_value']} as intra_rate";
        }
        else
        {
            $sql_select .= ",intra_rate";
        }
        if ($local_rate && $local_rate != 'none')
        {
            $sql .= ",local_rate={$f['local_rate_value']}";
            $sql_select.=" ,{$f['local_rate_value']} as local_rate";
        }
        else
        {
            $sql_select .= ",local_rate";
        }
        #rate
        if ($rate && $rate != 'none')
        {
            $rate_v = $f['rate_per_min_value'];
            //设置为该提交的值
            if ($rate == 'set')
            {
                $sql .= ",rate=$rate_v";
                $sql_select.=" ,$rate_v as rate";
            }
            //在基础上加
            else if ($rate == 'inc')
            {
                $sql .= ",rate = rate + $rate_v";
                $sql_select.=" ,rate+$rate_v as rate";
            }
            //在基础上减
            else if ($rate == 'dec')
            {
                $sql .= ",rate = rate - $rate_v";
                $sql_select.=" ,rate-$rate_v as rate";
            }
            //按百分比加
            else if ($rate == 'perin')
            {
                $sql .= ",rate = rate +(rate*$rate_v/100)";
                $sql_select.=" ,rate+(rate*$rate_v/100) as rate";
            }
            //按百分比减
            else if ($rate == 'perde')
            {
                $sql .= ",rate = rate -(rate*$rate_v/100)";
                $sql_select.=" ,rate-(rate*$rate_v/100) as rate";
            }
            else if ($rate == 'mul')
            {
                $sql .= ",rate = rate*$rate_v";
                $sql_select.=" ,rate*$rate_v as rate";
            }
        }
        else
        {
            $sql_select .= ",rate";
        }
        #min_time 首次时长
        if ($mintime && $mintime != 'none')
        {
            $mintime_v = $f['min_time_value'];
            //设置为该提交的值
            if ($mintime == 'set')
            {
                $sql .= ",min_time=$mintime_v";
                $sql_select.=" ,$mintime_v as rate";
            }
            //在基础上加
            else if ($mintime == 'inc')
            {
                $sql .= ",min_time=min_time+$mintime_v";
                $sql_select.=" ,min_time+$mintime_v as min_time";
            }
            //在基础上减
            else if ($mintime == 'dec')
            {
                $sql .= ",min_time=min_time-$mintime_v";
                $sql_select.=" ,min_time-$mintime_v as min_time";
            }
        }
        else
        {
            $sql_select .= ",min_time";
        }

#开始时间
        if ($starttime && $starttime != 'none')
        {
            if (!empty($f['effective_from_value']))
            {
                $sql .= ",effective_date='{$f['effective_from_value']}'";
                $sql_select.=" ,'{$f['effective_from_value']}' as effective_date";
            }
            else
            {
                $sql_select .= ",effective_date";
            }
        }
        else
        {
            $sql_select .= ",effective_date";
        }

        if ($setupfee && $setupfee != 'none')
        {
            $setupfee_v = $f['pay_setup_value'];
            //设置为该提交的值
            if ($setupfee == 'set')
            {
                $sql .= ",setup_fee=$setupfee_v";
                $sql_select.=" ,$setupfee_v as setup_fee";
            }
            //在基础上加
            else if ($setupfee == 'inc')
            {
                $sql .= ",setup_fee=setup_fee+$setupfee_v";
                $sql_select.=" ,setup_fee+$setupfee_v as setup_fee";
            }
            //在基础上减
            else if ($setupfee == 'dec')
            {
                $sql .= ",setup_fee=setup_fee-$setupfee_v";
                $sql_select.=" ,setup_fee-$setupfee_v as setup_fee";
            }
            //按百分比加
            else if ($setupfee == 'perin')
            {
                $sql .= ",setup_fee = setup_fee +(setup_fee*$setupfee_v/100)";
                $sql_select.=" ,setup_fee+(setup_fee*$setupfee_v/100) as setup_fee";
            }
            //按百分比加
            else if ($setupfee == 'perde')
            {
                $sql .= ",setup_fee = setup_fee -(setup_fee*$setupfee_v/100)";
                $sql_select.=" ,setup_fee-(setup_fee*$setupfee_v/100) as setup_fee";
            }
        }
        else
        {
            $sql_select .= ",setup_fee";
        }

        if ($interval && $interval != 'none')
        {
            $sql .= ",interval={$f['pay_interval_value']}";
            $sql_select.=" ,{$f['pay_interval_value']} as interval";
        }
        else
        {
            $sql_select .= ",interval";
        }

        if ($endtime && $endtime != 'none')
        {
            $sql .= ",end_date='{$f['end_date_value']}'";
            $sql_select.=" ,{$f['pay_interval_value']} as end_date";
        }
        else
        {
            $sql_select .= ",end_date";
        }

        if ($gracetime && $gracetime != 'none')
        {
            $sql .= ",grace_time={$f['grace_time_value']}";
            $sql_select.=" ,{$f['grace_time_value']} as grace_time";
        }
        else
        {
            $sql_select .= ",grace_time";
        }
        /*if ($type == 'process')
        {//应用*/
        //$sql .= " where rate_table_id =$table_id";
        $sql .= " where rate_table_id =$table_id";
        if ($_POST['isQuery'])
        {
            if (!$_POST['isAll'])
            {
                $sql .= " and end_date is null";
            }
        }

        if (!empty($searchstr))
        {
            $sql .= " and rate.code::text ilike '{$searchstr}%' or rate.code_name::text ilike '{$searchstr}%' or rate.country::text ilike '{$searchstr}%'";
        }
        $sql = str_replace('rate_id = rate_id,', '', $sql);
        $qs = $this->Clientrate->query($sql);
        if (count($qs) == 0)
        {
            $this->Session->write('m', $this->Clientrate->create_json(201, __('success', true)));
        }
        else
        {
            $this->Session->write('m', $this->Clientrate->create_json(101, __('failed', true)));
        }
        /*}
        else
        {//预览
            $this->set('previewForm', $f);
            $this->set('previewRates', $this->Clientrate->query($sql_select . " from rate where rate_table_id =$table_id"));
        }*/
    }

    function _get_update_all_trunk_set($i, $data)
    {
        $trunk = $data['trunk' . $i];
        return "resource_id_{$i} ={$trunk}";
    }

    function _get_update_all_percentage_set($i, $data)
    {
        $percentage = $data['percentage' . $i];
        return "percentage=$percentage";
    }

    function _render_update_all_update($product_id, $type)
    {
        $this->Clientrate->begin();
        $data = $this->_get('data');
        $sets = Array();
        if ($this->_get('route_strategy_options') != 'none')
        {
            $sets[] = 'strategy=' . $this->_get('strategy');
            if ($this->_get('strategy') == '0')
            {
                for ($i = 1; $i < 0; $i++)
                {
                    $this->_get_update_all_percentage_set($i, $data);
                }
            }
        }
        if ($this->_get('route_time_profile_options') != 'none')
        {
            $sets[] = 'time_profile_id=' . $this->_get('time_profile');
        }
        for ($i = 1; $i < 9; $i++)
        {
            if ($this->_get('route_trunk' . $i . '_options') != 'none')
            {
                $sets[] = $this->_get_update_all_trunk_set($i, $data);
            }
        }
        if (!empty($sets))
        {
            $sets = "set " . join($sets, ',');
            $this->Clientrate->query("update product_items $sets where product_id=$product_id");
        }
        if ($type == 'view')
        {
            $currPage = 1;
            $pageSize = 100;
            if (!empty($_REQUEST ['page']))
            {
                $currPage = $_REQUEST ['page'];
            }
            if (!empty($_REQUEST ['size']))
            {
                $pageSize = $_REQUEST ['size'];
            }
            $search = null;
            if (!empty($_REQUEST ['search']))
            {
                $search = $_REQUEST ['search'];
                $this->set('search', $search);
            }
            if (!empty($_REQUEST['edit_id']))
            {
                $sql = "select item_id,alias,digits,strategy,
									(select name from time_profile where time_profile_id = product_items.time_profile_id) as time_profile,
									(select alias from resource where resource_id = product_items.resource_id_1) as route1,
									(select alias from resource where resource_id = product_items.resource_id_2) as route2,
									(select alias from resource where resource_id = product_items.resource_id_3) as route3,
									(select alias from resource where resource_id = product_items.resource_id_4) as route4,
									(select alias from resource where resource_id = product_items.resource_id_5) as route5,
									(select alias from resource where resource_id = product_items.resource_id_6) as route6,
									(select alias from resource where resource_id = product_items.resource_id_7) as route7,
									(select alias from resource where resource_id = product_items.resource_id_8) as route8
								from product_items
								where item_id = {$_REQUEST['edit_id']}
		  			";
            }
            $result = $this->Product->query($sql);
            $this->Session->write('viewListUpdate', $result);
        }
        if ($type == 'view')
        {
            $this->Clientrate->rollback();
        }
        else
        {
            $this->Clientrate->commit();
        }
    }

    function _render_update_all_delete($product_id)
    {
        $data = $this->_get('data');
        $sets = Array();
        if ($this->_get('route_strategy_options') != 'none')
        {
            $sets[] = 'strategy=' . $this->_get('strategy');
            if ($this->_get('strategy') == '0')
            {
                for ($i = 1; $i < 0; $i++)
                {
                    $this->_get_update_all_percentage_set($i, $data);
                }
            }
        }
        if ($this->_get('route_time_profile_options') != 'none')
        {
            $sets[] = 'time_profile_id=' . $this->_get('time_profile');
        }
        for ($i = 1; $i < 9; $i++)
        {
            if ($this->_get('route_trunk' . $i . '_options') != 'none')
            {
                $sets[] = $this->_get_update_all_trunk_set($i, $data);
            }
        }
        if (!empty($sets))
        {
            $sets = join($sets, ' and ');
            $this->Clientrate->query("delete from product_items where product_id'=>$product_id and $sets");
        }
    }

    function _render_update_all_insert($product_id)
    {
        $this->loadModel('Productitem');
        $this->Productitem->find('all', Array('conditions' => Array('product_id' => $product_id)));
    }

    function updateAll($id = null)
    {
        $type = $this->_get('type');
        $action = $this->_get('action');
        if ($action == 'insert')
        {
            $this->_render_update_all_insert($id);
            $this->Session->write('m', $this->Clientrate->create_json(201, __('Your options are created successfully', true)));
        }
        if ($action == 'update')
        {
            $this->_render_update_all_update($id, $type);
            $this->Session->write('m', $this->Clientrate->create_json(201, __('Your options are modified successfully', true)));
        }
        if ($action == 'delete')
        {
            $this->_render_update_all_delete($id);
            $this->Session->write('m', $this->Clientrate->create_json(201, __('Your options are deleted successfully', true)));
        }
        $this->xredirect("/products/route_info/" . $id);
    }

    function delete_all($rate_table_id)
    {


        if (!empty($rate_table_id))
        {
            $ids = $this->_get('ids');

            $conditions = Array("rate_table_id='$rate_table_id'");
            if (!empty($ids))
            {
                $conditions[] = "rate_id in ($ids)";
                $this->Session->write('m', $this->Clientrate->create_json(201, __('You options are deleted successfully', true)));
            }
            else
            {
                $mesg_result = $this->Clientrate->query("select name from rate_table where rate_table_id = {$rate_table_id}");
                $this->Session->write('m', $this->Clientrate->create_json(201, __('The rates of Rate Table [%s] are deleted successfully', true, $mesg_result[0][0]['name'])));
            }
            $conditions = join($conditions, ' and ');

            $this->Clientrate->query("delete from rate where $conditions");
        }
        $this->xredirect("/clientrates/view/" . $rate_table_id);
    }

    function rate_delete($id = null)
    {
        if (!empty($id))
        {
            $ids = $this->_get('ids');
            $conditions = Array("rate_table_id=$id");
            if (!empty($ids))
            {
                $conditions[] = "rate_id in ($ids)";
            }
            $conditions = join($conditions, ' and ');
            $sql = "delete from rate where $conditions";

            $this->Clientrate->query($sql);
        }
        $this->Session->write('m', $this->Clientrate->create_json(201, __('success', true)));
        $this->xredirect("/clientrates/view/" . $id);
    }

    public function mass_delete($table_id, $ids = null, $search_q = '', $effectiveDate = '')
    {
        if (!isset($ids))
        {
            $ids = $_REQUEST['ids'];
        }
        if (!empty($table_id))
        {
            if (strcmp($table_id,intval($table_id)))
                $table_id = base64_decode($table_id);
            $where_sql = "";
            if ($search_q)
            {
                $where_sql .= " and (code::text ilike '{$search_q}%'  or  code_name::text ilike '{$search_q}%' or country::text ilike '{$search_q}%' or  ocn ilike '{$search_q}%' or  lata ilike '{$search_q}%')";
            }
            if ($effectiveDate)
            {
                $where_sql .= " and effective_date = '{$effectiveDate}' ";
            }
            if (empty($ids))
            {
                $mesg_result = $this->Clientrate->query("select name from rate_table where rate_table_id = {$table_id}");

                $sql = "delete from  rate  where  rate_table_id=$table_id {$where_sql}";

                $this->Clientrate->query($sql);
                if ($where_sql)
                {
                    $count = $this->Clientrate->getAffectedRows();
                    $message = "{$count} rates record is deleted";
                }
                else
                {
                    $message = 'The rates of Rate Table [' . $mesg_result[0][0]['name'] . '] are deleted successfully';
                }
                $this->Session->write('m', $this->Clientrate->create_json(201, __($message, true)));
            }
            else
            {
                $this->Clientrate->query("delete  from  rate  where  rate_table_id=$table_id and rate_id in ($ids) {$where_sql}");
                $this->Session->write('m', $this->Clientrate->create_json(201, __('Your options are deleted succesfully', true)));
            }
            $this->redirect('view/'.base64_encode($table_id));
        }




    }

    public function select_client_name($id = null)
    {
        if (!empty($id))
        {
            $sql = "select name from rate_table where rate_table_id=$id";
            return $this->Clientrate->query($sql);
        }
    }

    public function change_header($rate_table_id)
    {
        if ($this->RequestHandler->ispost())
        {
            $postData = $this->params['form'];
            $targetFolder = Configure::read('rateimport.put');
            $rates_filepath = $targetFolder . DIRECTORY_SEPARATOR . trim($postData['myfile_guid']) . ".csv";
            if (!file_exists($rates_filepath))
            {
                $this->Session->write('m', $this->Clientrate->create_json(201, __('File not exist,please try again', true)));
                $this->xredirect("/clientrates/view/" . base64_encode($rate_table_id));
            }
            $all_end_date = $postData['all_end_date'];
            $exist_end_date = $postData['exist_end_date'];
            $all_end_date_tz = $postData['all_end_date_tz'];
            $exist_end_date_tz = $postData['exist_end_date_tz'];
            $date_format = trim($postData['effective_date_format']);
            $sample_do = $postData['method'];
            $filename = $postData['myfile_filename'];
            $with_header = isset($postData['with_header']);
            $default_value = isset($postData['default_value']);
            $code_name_match = isset($postData['code_name_match']) ? $postData['code_name_match'] : "";
            $append_prefix = isset($this->params['form']['append_prefix']);
            $append_prefix_value = isset($this->params['form']['append_prefix_value']) ? $this->params['form']['append_prefix_value'] : "";
            $send_error_email_to = '';
            $defaultMinTime = isset($this->params['form']['default_min_time_value']) ? $this->params['form']['default_min_time_value'] : '';
            $defaultInterval = isset($this->params['form']['default_interval_value']) ? $this->params['form']['default_interval_value'] : '';
//            check effective end

            $set_exist_end_date = 'NULL';

            if ($sample_do == '2')
            {
                if (!empty($exist_end_date))
                    $set_exist_end_date = $exist_end_date. $exist_end_date_tz;
                else
                    $set_exist_end_date = '';
            }
            else if ($sample_do == '0')
                $all_end_date .= $all_end_date_tz;


            $rate_table = $this->Clientrate->query("select jur_type, code_deck_id from rate_table where rate_table_id = {$rate_table_id}");
            $rates_filepath_cmd = $rates_filepath;
            if ($rate_table[0][0]['jur_type'] == 3 || $rate_table[0][0]['jur_type'] == 4)
                $is_ocn_lata = 1;
            else
                $is_ocn_lata = 0;

            $this->set('rate_table_id', $rate_table_id);
            $this->set("is_ocn_lata", $is_ocn_lata);
            $this->set("date_format", $date_format);
            $this->set('rates_file_cmd', $rates_filepath_cmd);
            $this->set('with_header', $with_header);
            $this->set('code_name_match', $code_name_match);
            $this->set('send_error_email_to',$send_error_email_to);
            $this->set('append_prefix',$append_prefix);
            $this->set('append_prefix_value',$append_prefix_value);
            $this->set('code_deck_id',$rate_table[0][0]['code_deck_id']);
            $this->set('import_file_name',$filename);
            $this->set('sample_do',$sample_do);
            $this->set('exist_end_date',$set_exist_end_date);
            $this->set('all_end_date',$all_end_date);
            $this->set('defaultMinTime', $defaultMinTime);
            $this->set('defaultInterval', $defaultInterval);

            $this->loadModel('RateTable');
            $schema = $this->RateTable->import_list;

            if ($rate_table[0][0]['jur_type'] != 2) {
                 unset($schema['local_rate']);
            }
            $fields = array_keys($schema);

            $abspath = $rates_filepath;

            $cmds = array();

            array_push($cmds, "'s/","/"|"/g'");
            array_push($cmds, "'s/\\r/\\n/g'");
            array_push($cmds, "'/^$/d'");
            $replace_double_quotes = "'s/\"//g'";
            array_push($cmds, $replace_double_quotes);
            array_push($cmds, "'s/\?//g'");
            $cmd_str = implode(' -e ', $cmds);
            $cmd2 = "sed -i -e {$cmd_str} {$abspath}";

            shell_exec($cmd2);

            $table = array();

            $row = 1;

            $handle = popen("head -n 21 {$abspath}", "r");
            while ($row <= 21 && $data = fgetcsv($handle, 1000, ","))
            {   
                if($row == 1 && !in_array('code', $data) && count(array_filter($data)) <=1) {
                    $this->removeFirstRow($abspath);
                    continue;
                }
                $row++;
                array_push($table, $data);
            }
            pclose($handle);
            if (!$with_header && isset($table[0]))
            {
                $fields_count = count($table[0]);
                $empty_header_line = array();
                for ($i = 0;$i < $fields_count; $i++)
                {
                    $empty_header_line[] = '';
                }
                array_unshift($table,$empty_header_line);
            }

            foreach ($table[0] as &$table_header)
            {
                $table_header = strtolower($table_header);
                $table_header = str_replace(' ','_',$table_header);
            }
            if ($with_header && isset($table[0]))
            {
                if (in_array("Effective_Date", $table[0]) || in_array("effective_date", $table[0]))
                {
                    $this->set('effective_date_flg', 1);
                }
                if (in_array("interval", $table[0]))
                {
                    $this->set('interval_flg', 1);
                }
                if (in_array("min_time", $table[0]))
                {
                    $this->set('min_time_flg', 1);
                }
            }

            $this->set('table', $table);
            $this->set('columns', $fields);
            $this->set('abspath', $abspath);
        }
    }

    private function removeFirstRow($path){
        if(file_exists($path)){
            $input = explode("\n", file_get_contents($path));
            $output = array_slice($input, 1);
            file_put_contents($path, implode("\n", $output));
        }
        return ;
    }

    public function change_header_start(){
        $this->autoRender = false;
        $this->autoLayout = false;
        $get_data = $this->params['form'];
        $start_from =  $get_data['start_from'];
        $abspath =  $get_data['abspath'];
        $table = [];
        $row = 0;
        if(file_exists($abspath) && ($handle = fopen($abspath, "r")) !== false){
            while ($row <= ($start_from + 20) && $data = fgetcsv($handle, 1000, ","))
            {
                $row++;
                if ($row > $start_from) {
                    $table[] = $data;
                }
            }
            pclose($handle);
        }
        $this->jsonResponse(['status' => true, 'data' => $table]);
    }

    public function import($encode_rate_table_id)
    {
        $rate_table_id = base64_decode($encode_rate_table_id);
        if (!strcmp($encode_rate_table_id,intval($encode_rate_table_id)))
            $rate_table_id = $encode_rate_table_id;

        $this->set('name', $this->select_client_name($rate_table_id));
        Configure::load('myconf');
        if ($this->RequestHandler->ispost())
        {
            $postData = $this->params['form'];
            $abspath = $postData['abspath'];
            $with_header = $postData['with_header'];
            $code_name_match = isset($postData['code_name_match']) ? $postData['code_name_match'] : NULL;
//            默认值start
            $cmds = array();
            array_push($cmds, "'/^$/d'");

            if (!$with_header)
            {
                // sed 插入第一行插入空行
                //$cmd = "'1i\\\\'";
                $cmd_awk = "awk -F ',' 'NR==1 {print NF}' {$abspath}";
                $awk_result = shell_exec($cmd_awk);
                $line_rows = (int) $awk_result - 1;
                $quote_str = str_repeat(',', $line_rows);

                array_push($cmds, "'1i\\{$quote_str}\\'");
                //$cmd = "sed -i '1i\\\\' {$abspath}";
            }
            $interval_min_time_flg = isset($this->params['form']['interval_mintime']['code']) && $this->params['form']['interval_mintime']['code'] != '';
            $default_headers = array();
            $default_values = array();
            $is_effective_date = !empty($_POST['default_effective_date']);
            $is_min_time = trim($postData['is_min_time']);
            $is_interval = trim($postData['is_interval']);
            $default_info_arr = array();

            if ($postData['zero_rate'] == 1)
            {
                array_push($postData['columns'], 'rate');
                array_push($default_headers, 'rate');
                array_push($default_values, '');
            }

            if ($is_effective_date)
            {
                array_push($postData['columns'], 'effective_date');
                array_push($default_headers, 'effective_date');
                array_push($default_values, $_POST['default_effective_date']);
                $default_info_arr[] = __('Effective Date',true).":".$_POST['default_effective_date'];
            }

            if (!$interval_min_time_flg)
            {
                if ($is_min_time)
                {
                    array_push($postData['columns'], 'min_time');
                    array_push($default_headers, 'min_time');
                    array_push($default_values, $this->params['form']['default_min_time']);
                    $default_info_arr[] = __('Min Time',true).":".$this->params['form']['default_min_time'];
                }
                if ($is_interval)
                {
                    array_push($postData['columns'], 'interval');
                    array_push($default_headers, 'interval');
                    array_push($default_values, $this->params['form']['default_interval']);
                    $default_info_arr[] = __('Interval',true).":".$this->params['form']['default_interval'];
                }
            }
            else
            {
                foreach ($postData['columns'] as &$columns_item)
                {
                    if(!strcmp($columns_item,'min_time'))
                        $columns_item = '';
                    if(!strcmp($columns_item,'interval'))
                        $columns_item = '';
                }
                array_push($postData['columns'], 'min_time');
                array_push($default_headers, 'min_time');
                array_push($postData['columns'], 'interval');
                array_push($default_headers, 'interval');
            }

            if(isset($this->params['is_auto']))
            {
                $this->loadModel('RateManagementOption');
                $option_id_item = $this->RateManagementOption->find('first',array(
                    'fields' => array('id'),
                    'conditions' => array(
                        'rate_table_id' => $rate_table_id
                    ),
                ));
                $insert_option_arr = array(
                    'with_header' => boolval($with_header),
                    'effective_date_default' => $postData['effetive_date'],
                    'min_time_default'  => $postData['min_time'],
                    'interval_default'  => $postData['interval'],
                );
                if(isset($option_id_item[0][0]['id']))
                    $insert_option_arr['id'] = $option_id_item[0][0]['id'];

                $this->RateManagementOption->save($insert_option_arr);
            }

            $cmd_str = implode(' -e ', $cmds);

            $cmd2 = "sed -i -e {$cmd_str} {$abspath}";
            shell_exec($cmd2);

            $old_columns = $postData['columns'];
            $code_columns_pos = false;
            $column_key_arr = array();
            foreach ($old_columns as $column_key => $column) {
                $column_key_arr[] = "$" . ($column_key + 1);
                if (!strcmp('code', strtolower($column)))
                    $code_columns_pos = $column_key + 1;
            }
            if (isset($this->params['form']['append_prefix']) && $this->params['form']['append_prefix']) {
                $append_prefix_value = $this->params['form']['append_prefix_value'];
                $value_str = implode('","', $column_key_arr);

                if ($code_columns_pos == 1)
                    $value_str = '"' . $append_prefix_value . '"' . $value_str;
                else {
                    $replace_search = '","$' . $code_columns_pos;
                    $replace = '",' . $append_prefix_value . '"$' . $code_columns_pos;
                    $value_str = str_replace($replace_search, $replace, $value_str);
                }
                $old_abspath = $abspath;
                $tmp_path = str_replace(".csv",'_new.csv',$abspath);
                $add_prefix_cmd = "awk -F ',' '{if(NR==1){print $0}else{print " . $value_str . "}}' " . $abspath . " > $tmp_path";
                shell_exec($add_prefix_cmd);
                $abspath = $tmp_path;
            }

            $cmds_3 = array();

            $default_headers_c = implode(',', $default_headers);
            $default_values_c = implode(',', $default_values);
            if ($with_header)
            {
                // sed 第一行插入 headers
                //$cmd = "'1s/$/,{$default_headers}/g'";
                array_push($cmds_3, "'1s/$/,{$default_headers_c}/g'");
            }
            //$cmd = "'2,\$s/$/BBB/g'";
//            pr($default_values,$default_values_c);
            if (!empty($default_values))
            {
                array_push($cmds_3, "'2,\$s/$/,{$default_values_c}/g'");
            }
            // sed 插入 values
            if (!empty($cmds_3))
            {
                $cmd_str = implode(' -e ', $cmds_3);
                $cmd3 = "sed -i -e 's/\r//' -e $cmd_str $abspath";
//                echo $cmd3."<br />";die;
                shell_exec($cmd3);
            }

            if ($interval_min_time_flg)
            {
                //            添加默认interval_mintime
                $awk_conditions_arr = array();
                for ($j = 0; $j < count($this->params['form']['interval_mintime']['code']); $j++)
                {
                    $code = $this->params['form']['interval_mintime']['code'][$j];
                    $interval = $this->params['form']['interval_mintime']['interval'][$j];
                    $min_time = $this->params['form']['interval_mintime']['min_time'][$j];
                    $awk_conditions_arr[] = 'else if($'.$code_columns_pos.'~/^'.$code.'/){print $0","'.$min_time.'","'.$interval.'}';
                }
                $awk_conditions_str = implode(' ',$awk_conditions_arr);
                $tmp_name = 'rate_import_'.time() . '.csv';
                $old_name = basename($abspath);
                $tmp_path = str_replace($old_name,$tmp_name,$abspath);
                $default_min_time = $this->params['form']['default_min_time'];
                $default_interval = $this->params['form']['default_interval'];
                $awk_str = <<<AWK
awk -F ',' '{if(NR==1){print $0}$awk_conditions_str else{print $0"," $default_min_time","$default_interval}}' $abspath > $tmp_path
AWK;
                shell_exec($awk_str);
                $old_abspath = $abspath;
                $abspath = $tmp_path;
            }

//            默认值end
            $date_arr = explode(',', $this->params['form']['date_check']);
            if ($this->params['form']['date_check'])
            {
                $data_format_select = array();
                $date_format = str_replace('yyyy', 'Y', $this->params['form']['date_format']);

                $php_date_format1 = str_replace('mm', 'm', $date_format);
                $data_format_select[] = str_replace('dd', 'd', $php_date_format1);
                $data_format_select[] = str_replace('dd', 'd', $php_date_format1) . " H:i:s";

                $php_date_format2 = str_replace('mm', 'n', $date_format);
                $data_format_select[] = str_replace('dd', 'd', $php_date_format2);
                $data_format_select[] = str_replace('dd', 'd', $php_date_format2) . " H:i:s";

                $php_date_format3 = str_replace('mm', 'n', $date_format);
                $data_format_select[] = str_replace('dd', 'j', $php_date_format3);
                $data_format_select[] = str_replace('dd', 'j', $php_date_format3) . " H:i:s";

                $php_date_format4 = str_replace('mm', 'm', $date_format);
                $data_format_select[] = str_replace('dd', 'j', $php_date_format4);
                $data_format_select[] = str_replace('dd', 'j', $php_date_format4) . " H:i:s";

                $success_flg = "";
                foreach ($date_arr as $date_item)
                {
                    $date_item = trim($date_item);
                    $date_items = explode(' ',$date_item);
                    $date_item = $date_items[0];
                    $timestamp = strtotime($date_item);
                    if (!strcmp($this->params['form']['date_format'], "dd/mm/yyyy"))
                    {
                        $ex_date_time = explode(" ", $date_item);
                        $ex_date_item_arr = explode("/", $ex_date_time[0]);
                        $item_hour = 0;
                        $item_minute = 0;
                        $item_second = 0;
                        if (isset($ex_date_time[1]) && !empty($ex_date_time[1]))
                        {
                            $ex_time_item_arr = explode(":", $ex_date_time[1]);
                            $item_hour = $ex_time_item_arr[0];
                            $item_minute = $ex_time_item_arr[1];
                            $item_second = $ex_time_item_arr[2];
                        }
                        $timestamp = mktime($item_hour, $item_minute, $item_second, $ex_date_item_arr[1], $ex_date_item_arr[0], $ex_date_item_arr[2]);
                    }
                    foreach ($data_format_select as $data_format_select_item)
                    {
                        if (!strcmp($date_item, date($data_format_select_item, $timestamp)))
                        {
                            $success_flg = 1;
                            break;
                        }
                        $success_flg = 0;
                    }
                    if (!$success_flg)
                    {
                        break;
                    }
                }
                if (!$success_flg)
                {
                    $this->Clientrate->create_json_array('#ClientOrigRateTableId', 101, "You have selected {$this->params['form']['date_format']} as the effective date format, but your upload file has different format. Please make change and retry again!");
                    $this->Session->write("m", Clientrate::set_validator());
                    $this->redirect("/clientrates/import/$rate_table_id");
                }
            }
            $date_format = $postData['date_format'];
            if ($postData['is_effective_date'] == 1)
            {
                $date_format = "yyyy-mm-dd";
            }
            $new_columns = $postData['columns'];
            $new_columns_str = implode(',', $new_columns);

            $cmd_ = "sed -i '1s/.*/{$new_columns_str}/g' {$abspath}";
//            echo $cmd_."<br />";
            shell_exec($cmd_);
            $default_info = implode('<br />',$default_info_arr);

            $script_path = Configure::read('script.path');
            $script_conf = Configure::read('script.conf');
            $is_ocn_lata = $postData['is_ocn_lata'];

            $rate_end_date = '';
            if ($this->params['form']['sample_do'] == 2 || $this->params['form']['sample_do'] == 0){
                $rate_end_date = $this->params['form']['exist_end_date'];
            }

            $checkTask = $this->RateUploadTask->find('count', array(
                'conditions' => array(
                    'rate_table_id' => $rate_table_id,
                    'status' => array(0, 1, 2, 3)
                )
            ));

            $this->startFromFile($abspath, $this->params['form']['start_from']);
            $rateTable = $this->Rate->findByRateTableId($rate_table_id);
            $task_arr = array(
                'operator_user' => $this->Session->read('sst_user_name'),
                'upload_file_path' => dirname($abspath),
                'upload_format_file' => basename($abspath),
                'upload_orig_file' => $this->params['form']['import_file_name'],
                'rate_table_id' => $rate_table_id,
                'rate_date_format' => $date_format,
                'create_time' => time(),
                'rate_table_code_deck_id' => $this->params['form']['code_deck_id'],
                'default_info' => $default_info,
                'use_ocn_lata_code' => $is_ocn_lata,
                'reduplicate_rate_action' => $this->params['form']['sample_do'],
                'rate_end_date' => $rate_end_date,
                'all_rate_end_date' => $this->params['form']['all_end_date'],
                'status' => 0,
                'code_deck_flag' => $rateTable['Rate']['code_deck_id'] ? 1 : 0,
                'code_name_match' => $code_name_match,
                'start_from' => $this->params['form']['start_from'],
            );
            $this->RateUploadTask->save($task_arr);
            $log_id = $this->RateUploadTask->getLastInsertID();
            $this->Rate->save(array('rate_table_id' => $rate_table_id, 'update_at' => date('Y-m-d H:i:sO'), 'update_by' => $this->Session->read('sst_user_name')));

            $php_path = Configure::read('php_exe_path');
            $cmd = "{$php_path} " . APP . "../cake/console/cake.php rate_upload $log_id  /dev/null &";
            shell_exec($cmd);

            $this->Session->write('m', $this->Clientrate->create_json(201, __('You Job is scheduled to execute in the queue.',true)));

            $this->loadModel('RateManagementDetail');
            $detail_save_arr = array(
                'status' => 1,
                'upload_time'=> date('Y-m-d H:i:sO'),
                'log_id' => $log_id,
                'file_path' => $abspath,
                'orig_file_name' => $this->params['form']['import_file_name'],
                'rate_table_id' => $rate_table_id
            );
            $this->RateManagementDetail->save($detail_save_arr);

            $this->redirect('/rate_log/import');
        }
        $sql = "select jurisdiction_country_id, jur_type, currency_id,code_deck_id from rate_table where rate_table_id = {$rate_table_id}";
        $result = $this->Clientrate->query($sql);
        $is_us = $result[0][0]['jurisdiction_country_id'];
        $jur_type = $result[0][0]['jur_type'];
        if ($jur_type == 0 || $jur_type == 1)
        {
            $example = $this->webroot . "example" . DS . 'example1.csv';
        }
        elseif ($jur_type == 2)
        {
            $example = $this->webroot . "example" . DS . 'example2.csv';
        }
        elseif ($jur_type == 3)
        {
            $example = $this->webroot . "example" . DS . 'example4.csv';
        }
        elseif ($jur_type == 4)
        {
            $example = $this->webroot . "example" . DS . 'example5.csv';
        }

        $this->loadModel('RateUploadTemplate');
        $rate_upload_template = $this->RateUploadTemplate->get_template();
        $this->set('rate_upload_templates',$rate_upload_template);
        $this->set('example', $example);
        $this->set('rate_table_id', $rate_table_id);
        $this->set('table_id', $rate_table_id);
        $this->set('jur_type', $jur_type);
        $this->set('currency', $result[0][0]['currency_id']);
        $this->set('code_deck_id', $result[0][0]['code_deck_id']);
    }

    private function startFromFile($path, $start_from){
        if ($start_from <= 1) {
            return;
        }

        if(file_exists($path)){
            $input = explode("\n", file_get_contents($path));
            $output = array_slice($input, $start_from);
            $output = array_merge([$input[0]], $output);
            file_put_contents($path, implode("\n", $output));
        }
        return ;
    }

    public function clean_queue($rate_table_id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $rate_table_result = $this->Clientrate->query("SELECT name FROM rate_table WHERE rate_table_id = {$rate_table_id}");
        $rate_table_name = $rate_table_result[0][0]['name'];
        $sql = "DELETE FROM rate_upload_queue WHERE rate_table_id = {$rate_table_id}";
        $this->Clientrate->query($sql);
        $this->Session->write('m', $this->Clientrate->create_json(201, __('The Rate Table [%s]  background jobs are deleted successfully!', true, $rate_table_name)));
        $this->redirect('/clientrates/import/' . $rate_table_id);
    }

    public function checkstatus()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        set_time_limit(10); //请求超时时间  
        $ratetable_id = $_POST['ratetable_id'];
        $status = $this->Clientrate->get_rate_import_status($ratetable_id);
        if (!empty($status) && $status['status'] == 5)
        {
            $this->Clientrate->update_import_status_over($status['id']);
        }
        echo json_encode($status);
        ob_flush();
        flush();
        $this->db->close();
    }

    public function down_import_log($id, $type)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $path = $this->Clientrate->get_log_file($id, $type);
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        readfile($path);
    }


    public function import_with_template($rate_table_id)
    {
        $rate_upload_template_id = $this->params['form']['rate_upload_template'];
        $this->loadModel('RateUploadTemplate');
        $template_data = $this->RateUploadTemplate->findById($rate_upload_template_id);
        $targetFolder = Configure::read('rateimport.put');
        $rates_filepath = $targetFolder . DIRECTORY_SEPARATOR . trim($_POST['myfile_guid']) . ".csv";
        $end_date = $template_data['RateUploadTemplate']['end_date_all'];
        $end_date1 = $template_data['RateUploadTemplate']['end_date'];

        $tz = $template_data['RateUploadTemplate']['end_date_all_gmt'];
        $dz1 = $template_data['RateUploadTemplate']['end_date_gmt'];
        $date_format = trim($template_data['RateUploadTemplate']['effective_date_format']);
        $sample_do = $template_data['RateUploadTemplate']['dup_method'];
        $filename = $_POST['myfile_filename'];
        $with_header = $template_data['RateUploadTemplate']['with_header'];
        $code_name_match = $template_data['RateUploadTemplate']['code_name_match'];
        $header_fields = $template_data['RateUploadTemplate']['header_fields'];
        $effective_date_default = $template_data['RateUploadTemplate']['effective_date_default'];
        $interval_default = $template_data['RateUploadTemplate']['interval_default'];
        $min_time_default = $template_data['RateUploadTemplate']['min_time_default'];
        //            check effective
        $check_effective = $template_data['RateUploadTemplate']['check_effective'];
        $rate_increase_days = $template_data['RateUploadTemplate']['rate_increase_days'];
        $new_code_days = $template_data['RateUploadTemplate']['new_code_days'];
        $reject_rate = $template_data['RateUploadTemplate']['reject_rate'];
        $send_error_email_to = $template_data['RateUploadTemplate']['send_error_email_to'];
//            check effective end

        $end_effective_date = 'NULL';
        $end_date = 'NULL';
        if ($sample_do == '2')
        {
            if (!empty($end_date1))
                $end_date = "-T " . str_replace(" ", "_", $end_date1) . $dz1;
            else
                $end_date = '';
        } else if ($sample_do == '0')
        {
            $end_effective_date = "'" . $end_date . $tz . "'";
            $end_date = '';
        }
        else
            $end_date = '';


        $binpath = Configure::read('rateimport.bin');
        $confpath = Configure::read('rateimport.conf');
        $confpath_info = pathinfo($confpath);
        $confpath = $confpath_info['dirname'];

        $outpath = Configure::read('rateimport.out');

        $cmd_parm = '-u 0';
        $rate_table = $this->Clientrate->query("select jur_type, code_deck_id from rate_table where rate_table_id = {$rate_table_id}");
        if ($rate_table[0][0]['jur_type'] == 3 || $rate_table[0][0]['jur_type'] == 4)
        {
            //$cmd_parm = '-u 1';
            $is_ocn_lata = 1;
            $rates_filepath_cmd = $targetFolder . DIRECTORY_SEPARATOR . trim($_POST['myfile_guid']) . '_by_ocn_lata' . ".csv";
        }
        else
        {
            //$cmd_parm = '-u 0';
            $is_ocn_lata = 0;
            $rates_filepath_cmd = $rates_filepath;
        }

        if (empty($rate_table[0][0]['code_deck_id']))
        {
            $cmd_codek = '-C 0';
        }
        else
        {
            $cmd_codek = '-C 1';
        }
        $cmd_check_effective = "";
//        if($check_effective)
//        {
//            if($reject_rate)
//                $cmd_check_effective = "-n reject 0 -N reject 0 -o reject 0";
//            else
//                $cmd_check_effective = "-n delay {$new_code_days} -N delay {$new_code_days} -o delay {$rate_increase_days}";
//        }

        $system_type = Configure::read('system.type');

        $cmd = "{$binpath} $end_date -F '{$filename}' -t $system_type -d {$confpath} -r {$rate_table_id} -c {$date_format} -f '{$rates_filepath_cmd}' -o {$outpath} -m {$sample_do} -U {$_SESSION['sst_user_id']} {$cmd_parm} {$cmd_codek} {$cmd_check_effective}";

        if (Configure::read('cmd.debug'))
        {
            file_put_contents('/tmp/cmd_debug1', $cmd);
        }

        $abspath = $rates_filepath;

        $cmds = array();
        array_push($cmds, "'s/\\r/\\n/g'");

//        删除空行
        array_push($cmds, "'/^$/d'");

        $replace_double_quotes = "'s/\"//g'";
        array_push($cmds, $replace_double_quotes);
        array_push($cmds, "'s/\?//g'");
        $cmd_str = implode(' -e ', $cmds);
        $cmd2 = "sed -i -e {$cmd_str} {$abspath}";
        shell_exec($cmd2);


//        import

//            默认值start
        $cmds = array();
        array_push($cmds, "'/^$/d'");

        if (!$with_header)
        {
            // sed 插入第一行插入空行
            //$cmd = "'1i\\\\'";
            $cmd_awk = "awk -F ',' 'NR==1 {print NF}' {$abspath}";
            $awk_result = shell_exec($cmd_awk);
            $line_rows = (int) $awk_result - 1;
            $quote_str = str_repeat(',', $line_rows);
            array_push($cmds, "'1i\\{$quote_str}\\'");
        }
        $default_headers = array();
        $default_values = array();
        $default_info_arr = '';
        $header_fields_arr = explode(",",$header_fields);
        if (strpos($header_fields,'effective_date') === false)
        {
            array_push($header_fields_arr, 'effective_date');
            array_push($default_headers, 'effective_date');
            array_push($default_values, $effective_date_default);
            $default_info_arr[] = __('Effective Date',true).":".$effective_date_default;
        }
        if (strpos($header_fields,'min_time') === false)
        {
            array_push($header_fields_arr, 'min_time');
            array_push($default_headers, 'min_time');
            array_push($default_values, $min_time_default);
            $default_info_arr[] = __('Min Time',true).":".$min_time_default;
        }
        if (strpos($header_fields,'interval') === false)
        {
            array_push($header_fields_arr, 'interval');
            array_push($default_headers, 'interval');
            array_push($default_values, $interval_default);
            $default_info_arr[] = __('Interval',true).":".$interval_default;
        }
        $cmd_str = implode(' -e ', $cmds);
        $cmd2 = "sed -i -e {$cmd_str} {$abspath}";
        shell_exec($cmd2);

        $cmds_3 = array();
        $default_headers_c = implode(',', $default_headers);
        $default_values_c = implode(',', $default_values);
        if ($with_header)
        {
            // sed 第一行插入 headers
            //$cmd = "'1s/$/,{$default_headers}/g'";
            array_push($cmds_3, "'1s/$/,{$default_headers_c}/g'");
        }
        //$cmd = "'2,\$s/$/BBB/g'";
        if ($default_values_c)
        {
            array_push($cmds_3, "'2,\$s/$/,{$default_values_c}/g'");
        }

        // sed 插入 values
        if (!empty($cmds_3))
        {
            $cmd_str = implode(' -e ', $cmds_3);
            $cmd3 = "sed -i -e 's/\r//' -e $cmd_str $abspath";
            shell_exec($cmd3);
        }

        if ($template_data['RateUploadTemplate']['append_prefix']) {
            $append_prefix_value = $template_data['RateUploadTemplate']['append_prefix_value'];
            $old_columns = explode(',',$header_fields);
            $code_columns_pos = false;
            $column_key_arr = array();
            foreach ($old_columns as $column_key => $column) {
                $column_key_arr[] = "$" . ($column_key + 1);
                if (!strcmp('code', strtolower($column)))
                    $code_columns_pos = $column_key + 1;
            }
            $value_str = implode('","', $column_key_arr);

            if ($code_columns_pos == 1)
                $value_str = '"' . $append_prefix_value . '"' . $value_str;
            else {
                $replace_search = '","$' . $code_columns_pos;
                $replace = '",' . $append_prefix_value . '"$' . $code_columns_pos;
                $value_str = str_replace($replace_search, $replace, $value_str);
            }
            $old_abspath = $abspath;
            $tmp_path = str_replace(".csv",'_new.csv',$abspath);
            $add_prefix_cmd = "awk -F ',' '{if(NR==1){print $0}else{print " . $value_str . "}}' " . $abspath . " > $tmp_path";
            shell_exec($add_prefix_cmd);
            $abspath = $tmp_path;
            $rates_filepath_cmd = str_replace(".csv",'_new.csv',$rates_filepath_cmd);
            $cmd = str_replace($old_abspath,$abspath,$cmd);
        }



//            默认值end
//        删除判断日期

        if (strpos($header_fields,'effective_date') === false)
            $date_format = "yyyy-mm-dd";

        $new_columns = $header_fields_arr;
        $new_columns_str = implode(',', $new_columns);
        $cmd_ = "sed -i '1s/.*/{$new_columns_str}/g' {$abspath}";
//            echo $cmd_."<br />";
        shell_exec($cmd_);
        $replacement = "-c {$date_format} -f";
        $preg = "/-c .+ -f/";
        $cmd = preg_replace($preg, $replacement, $cmd);
        $default_info = implode('<br />',$default_info_arr);
        $sql = "insert into import_rate_status(rate_table_id, status,default_info) values ({$rate_table_id}, -1,'$default_info') returning id";
        $log_result = $this->Clientrate->query($sql);
        $log_id = $log_result[0][0]['id'];
        $cmd .= " -I {$log_id}";

        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        $cmd = addslashes($cmd);

        $rates_file_cmd = $rates_filepath_cmd;

//        $sql_record = "insert into rate_upload_queue(cmd, rate_table_id, status, end_date, log_id, is_ocn_lata, date_format, rates_file_cmd, rates_file,code_name_match) values ($$$cmd$$, $rate_table_id, 0, {$end_effective_date}, {$log_id}, {$is_ocn_lata}, '{$date_format}', '{$rates_file_cmd}', '{$abspath}','{$code_name_match}')";
//        $this->Clientrate->query($sql_record);

        $time = date("Y-m-d H:i:s", time()) . "+00";
        $update_by = $_SESSION['sst_user_name'];
        $this->Rate->Save(array('rate_table_id' => $rate_table_id, 'update_at' => $time, 'update_by' => $update_by));
        $task_arr = array(
            'operator_user' => $this->Session->read('sst_user_name'),
            'upload_file_path' => dirname($rates_file_cmd),
            'upload_format_file' => basename($rates_file_cmd),
            'upload_orig_file' => $this->params['form']['import_file_name'],
            'rate_table_id' => $rate_table_id,
            'rate_date_format' => $date_format,
            'create_time' => time(),
            'rate_table_code_deck_id' => $this->params['form']['code_deck_id'],
            'default_info' => $default_info,
            'rate_end_date' => $end_date,
            'use_ocn_lata_code' => $is_ocn_lata
        );
        $this->RateUploadTask->save($task_arr);

        $this->Session->write('m', $this->Clientrate->create_json(201, sprintf(__('You Job is scheduled to execute in the queue.',true))));
        $this->redirect('/rate_log/import');


    }

    public function rate_mass_edit_log(){
        $this->pageTitle = 'Rate Mass Edit Log';
        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");

        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
            $end_time = $_GET['end_time'];
        }

        $conditions = array(
            'RateMassEditLog.action_time BETWEEN ? and ?' => array($start_time, $end_time)
        );
        if (isset($_GET['type']) && !empty($_GET['type']))
        {
            $conditions["RateMassEditLog.action_type"] = $_GET['type'];
        }
        $order_arr = array('RateMassEditLog.action_time' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $this->loadModel('RateMassEditLog');

        $this->paginate = array(
            'fields' => array(
                'RateMassEditLog.id','RateMassEditLog.action_time', 'RateMassEditLog.action_rate_rows',
                'RateMassEditLog.action_type','RateMassEditLog.down_file_path','Client.name','RateMassEditLog.rate_table_id','RateTable.name'
            ),
            'limit' => 100,
            'joins' => array(
                array(
                    'table' => 'client',
                    'alias' => 'Client',
                    'type' => 'left',
                    'conditions' => array(
                        'RateMassEditLog.client_id = Client.client_id'
                    ),
                ),
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'left',
                    'conditions' => array(
                        'RateMassEditLog.rate_table_id = RateTable.rate_table_id'
                    ),
                )
            ),
            'order' => $order_arr,
            'conditions' => $conditions
        );
        $types = array(
            '0' => 'delete found rates',
            '1' => 'insert as new rates',
            '2' => 'update current rates',
            '3' => 'update all rates',
        );
        $this->data = $this->paginate('RateMassEditLog');
        $this->set('types', $types);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
    }


//    //处理preview及process log
//    public function rate_save_file_and_log($preview_process, $begin_end){
//
//
//    }

    //保持mass之前数据
    public function rate_save_before_data($action,$rate_table_id){

        if ($action != 'updateall')
        {
            if (!empty($_REQUEST['rate_ids']))
            {
                $ids = $_REQUEST['rate_ids'];
                $sql = $this->Clientrate->find_all_rate($rate_table_id, '', 'npan', $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array('getSql','rate_ids' => $ids), 'csv');
                $before_data = $this->Clientrate->query($sql);

            } else {
                $before_data = array();
            }

        } else {
            $fields = "code, rate, effective_date AT TIME ZONE INTERVAL '+00:00' as effective_date, end_date AT TIME ZONE INTERVAL '+00:00' as end_date,min_time,interval";
            $sql = $this->Clientrate->find_all_rate($rate_table_id, '', 'npan', $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array('getSql', 'fields' => $fields), 'csv');
            $sql = str_replace(array("\t","\n"), ' ', $sql);
            $xls_file_path = Configure::read('database_export_path') . "/rate_mass_edit";
            if (!is_dir($xls_file_path))
            {
                mkdir($xls_file_path);
            }
            $file_name = 'download_before_' . uniqid() . '.csv';
            $true_file_path = $xls_file_path . DS . $file_name;




            $sql = "\COPY ($sql) TO '" . $true_file_path . "' CSV HEADER DELIMITER AS ',' ";

            $this->Clientrate->_get_psql_cmd($sql);
            $before_data = $true_file_path;
        }

        return $before_data;

    }
    //保持mass之后的数据
    public function rate_save_after_data($action,$rate_table_id){
        if ($action == 'delete')
        {
            $after_data = array();
        }
        if ($action == 'insert')
        {
            if (!empty($_REQUEST['rate_ids']))
            {
                $ids = $_REQUEST['rate_ids'] . ',' . $_POST['after_insert_ids'];
                $sql = $this->Clientrate->find_all_rate($rate_table_id, '', 'npan', $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array('getSql','rate_ids' => $ids), 'csv');
                $after_data = $this->Clientrate->query($sql, false);
            } else {
                $after_data = array();
            }
        }
        if ($action == 'update')
        {
            if (!empty($_REQUEST['rate_ids']))
            {
                $ids = $_REQUEST['rate_ids'];
                $sql = $this->Clientrate->find_all_rate($rate_table_id, '', 'npan', $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array('getSql','rate_ids' => $ids), 'csv');
                $after_data = $this->Clientrate->query($sql, false);
            }
            else
            {
                $after_data = array();
            }
        }
        if ($action == 'updateall')
        {
            $fields = "code, rate, effective_date AT TIME ZONE INTERVAL '+00:00' as effective_date, end_date AT TIME ZONE INTERVAL '+00:00' as end_date,min_time,interval";
            $sql = $this->Clientrate->find_all_rate($rate_table_id, '', 'npan', $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array('getSql', 'fields' => $fields), 'csv');
            $sql = str_replace(array("\t","\n"), ' ', $sql);
            $xls_file_path = Configure::read('database_export_path') . "/rate_mass_edit";


            if (!is_dir($xls_file_path))
            {
                mkdir($xls_file_path);
            }
            $file_name = 'download_after_' . uniqid() . '.csv';
            $true_file_path = $xls_file_path . DS . $file_name;




            $sql = "\COPY ($sql) TO '" . $true_file_path . "' CSV HEADER DELIMITER AS ',' ";
            $this->Clientrate->_get_psql_cmd($sql);
            $after_data = $true_file_path;
        }

        return $after_data;
    }
    /*
     * 生成文件
     */

    function save_rate_csv($before_data, $after_data, $rate_type = null)
    {
        $csv_file_path = Configure::read('database_export_path') . "/rate_mass_edit";
        if (!is_dir($csv_file_path))
        {
            mkdir($csv_file_path);
        }
        $file_name = 'download_' . uniqid() . '.csv';
        $true_file_path = $csv_file_path . DS . $file_name;
        $handle = fopen($true_file_path, "w");
        $size = count($before_data) >= count($after_data) ? count($before_data) : count($after_data);
        $fields = [
            'rate' => 'Rate',
            'effective_date' => 'Effective Date',
            'end_date' => 'End Date',
            'setup_fee' => 'Setup Fee',
            'min_time' => 'Min Time',
            'interval' => 'Interval',
            'time_profile_name' => 'Profile',
        ];
        // non A-Z
        if($rate_type){
            $fields = $fields + [
                    'intra_rate' => 'Intra Rate',
                    'inter_rate' => 'Inter Rate'
                ];
        }

        $first_row = array_merge(['Before Mass Edit'], array_fill(0, count($fields) + 1, '') ,['After Mass Edit'], array_fill(0, count($fields), ''));
        fputcsv($handle, $first_row);
        fputcsv($handle, array_merge($fields, ['',''], array_values($fields)));
        if ($size > 0)
        {
            for ($i = 0; $i < $size; $i++)
            {   $row = [];
                foreach(array_keys($fields) as $field){
                    $row[] = @$before_data[$i][0][$field];
                }
                $row[] = '';
                $row[] = '';
                foreach(array_keys($fields) as $field){
                    $row[] = @$after_data[$i][0][$field];
                }
                fputcsv($handle, $row);
            }
        }
        fclose($handle);
        return $true_file_path;
    }

    public function download_rate_log_file($path,$option){
        Configure::write('debug', 0);

        $path = base64_decode($path);
        $path_arr = explode(';', $path);

        $option = base64_decode($option);
        $option_arr = explode(';', $option);

        $rate_table_name = $this->Clientrate->query("select name from rate_table where rate_table_id = $option_arr[0]");
        if(@$option_arr[1] == 3){
            $xls_file_path = Configure::read('database_export_path') . "/rate_mass_edit";
            if (!is_dir($xls_file_path))
            {
                mkdir($xls_file_path);
            }

            $zip_file_name = 'download_' . uniqid() . '.zip';
            $zip_path = $xls_file_path . DS . $zip_file_name;
            $cmd = "zip -j $zip_path $path_arr[0] $path_arr[1]";

            shell_exec($cmd);
            sleep(5);

            $file_name = 'down_mass_rate_table[' . $rate_table_name[0][0]['name'] . ']_' . date('Ymd') . '.zip';
            $after_file_name = 'down_after_mass_rate_table[' . $rate_table_name[0][0]['name'] . ']_' . date('Ymd') . '.csv';
            $file_size = filesize($zip_path);

            header("Content-Type: application/force-download");
            header("Content-Transfer-Encoding: binary");
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename='.$file_name);
            header('Content-Length: '.$file_size);
            readfile($zip_path);

            //$this->Clientrate->download_csv($path_arr[0],$before_file_name);
            //$this->Clientrate->download_csv($path_arr[1],$after_file_name);
        } else {
            $file_name = 'down_rate_table[' . $rate_table_name[0][0]['name'] . ']_' . date('Ymd') . '.xls';

            $this->Clientrate->download_xls($path_arr[0],$file_name);
        }

        exit;



    }

}

?>