<?php

class RatesController extends AppController
{

    var $name = 'Rates';
    var $helpers = array('javascript', 'html', 'AppRate', 'AppDownload');
    var $uses = array('Rate', 'Jurisdictionprefix', 'Mailtmp', "RateTable", 'Systemparam');

    function index()
    {
        $this->redirect('rates_list');
    }

    public function beforeFilter()
    {

        if ($this->params['action'] == 'upload_email') {
            return true;
        }
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_tools_outboundTest');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function upload_email()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $targetFolder = WWW_ROOT . "upload/email_list";;

        if (!empty($_FILES)) {
            $fileName = uniqid() . '.txt';
            $targetFile = $targetFolder . '/' . $fileName;

            // Validate the file type
            $fileTypes = array('txt'); // File extensions
            $fileParts = pathinfo($_FILES['Filedata']['name']);

            if (in_array($fileParts['extension'], $fileTypes)) {
                move_uploaded_file($_FILES['Filedata']['tmp_name'], $targetFile);

                $content = file_get_contents($targetFile);

                $search_arr = array(',', ';',);

                $content = str_replace($search_arr, "\n", $content);

                file_put_contents($targetFile, $content);

                //echo '1';
                echo $fileName;
            } else {
                echo 'Invalid file type.';
            }
        }
    }

    function currency($currency = null)
    {
        if (!empty($courrency)) {
            $courrency = $courrency + 0;
        }
        $this->rates_list($currency);
    }

    function save($id = null)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost()) {
            $name = $this->data['Rate']['name'];
            //pr($name);
            if ($id) {
                $this->data['Rate']['rate_table_id'] = $id;
            }
            /* if($this->data['Rate']['jurisdiction_country_id']){
              $this->data['Rate']['rate_type']=2;
              }else{
              $this->data['Rate']['rate_type']=1;
              } */
            $this->data['modify_time'] = time();
            $count = $this->Rate->query("select count(name) as name_num from rate_table where name='$name'");

            $this->data['Rate']['update_at'] = date("Y-m-d H:i:s");;
            $this->data['Rate']['update_by'] = $_SESSION['sst_user_name'];

            if ($this->data['Rate']['jur_type'] == 2) {
                $sql = "SELECT id FROM jurisdiction_country WHERE name = 'US'";
                $jur_data = $this->Rate->query($sql);
                if (empty($jur_data)) {
                    $sql = "INSERT INTO jurisdiction_country(name) VALUES ('US') returning id";
                    $jur_data = $this->Rate->query($sql);
                }
                $this->data['Rate']['jurisdiction_country_id'] = $jur_data[0][0]['id'];
            } else {
                $this->data['Rate']['jurisdiction_country_id'] = NULL;
            }
            $old_data = $this->Rate->findByRateTableId($id);
            if ($this->data['Rate']['name'] == '') {
                $this->Rate->create_json_array('', 101, __('The field Name cannot be NULL.', true));
                $this->xredirect("/rates/rates_list");
            } elseif ($count[0][0]['name_num'] > 0 && empty($id)) {
                $this->Rate->create_json_array('', 101, __('Name is already in used!', true));
                $this->xredirect("/rates/rates_list");
            } elseif ($this->Rate->save($this->data)) {
                if (empty($id)) {
                    $this->Rate->create_json_array('', 201, __('The Rate Table [%s] is created successfully!', true, $this->data['Rate']['name']));
                } else {
                    $old_data['Rate']['update_by'] = $_SESSION['sst_user_name'];
                    $rollback_data = array();
                    foreach ($this->params['data']['Rate'] as $key => $value) {
                        if ($old_data['Rate'][$key] != $value && strcmp($key, 'update_by') && strcmp($key, 'update_at')) {
                            $str_arr = array(
                                'name', 'modify_time', 'create_time', 'jurisdiction_prefix'
                            );
                            if (in_array($key, $str_arr)) {
                                $rollback_data[] = $key . " = '" . $old_data['Rate'][$key] . "'";
                            } else {
                                $rollback_data[] = $key . " = " . $old_data['Rate'][$key];
                            }
                        }
                    }
                    $rollback_data[] = "update_by = '{$_SESSION['sst_user_name']}'";
                    $rollback_update_sql = implode(',', $rollback_data);
                    $rollback_sql = "UPDATE rate_table SET {$rollback_update_sql} WHERE rate_table_id = {$id}";
                    $rollback_msg = "Modify Rate Table [" . $this->data['Rate']['name'] . "] operation have been rolled back!";

                    $this->Rate->create_json_array('', 201, __('The Rate Table [%s] is modified successfully!', true, $this->data['Rate']['name']));
                    $this->Rate->logging(2, 'Rate Table', "Rate Table Name:{$this->data['Rate']['name']}", $rollback_sql, $rollback_msg);
                }
                $this->xredirect("/rates/rates_list");
            } else {
                $this->redirect("/rates/rates_list");
            }
        }
    }

    public function formatted($file_name)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        ($this->params['url']['ext'] != 'html') and ($file_name = $file_name . "." . $this->params['url']['ext']);

        $target = WWW_ROOT . "upload/email_list" . DIRECTORY_SEPARATOR . $file_name;
        $content = file_get_contents($target);
        ob_clean();
        echo $content;
    }

    public function delete_rate()
    {

        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }

        if ($this->RequestHandler->isPost()) {//123Array ( [code] => 1 [codeName] => [Country] => [effectiveDate] => [endDate] => [ids] => 11187,11188 [submit] => )
            $this->data = $_POST;

            if (!$this->data['ids'] || empty($this->data)) {
                $this->redirect('/rates/rates_list');
            }
            $ids_arr = explode(',', $this->data['ids']);
            $where_sql = "";
            if ($this->data['code']) {
                $where_sql .= " AND code = '{$this->data['code']}'";
            }
            if ($this->data['codeName']) {
                $where_sql .= " AND code_name = '{$this->data['codeName']}'";
            }
            if ($this->data['Country']) {
                $where_sql .= " AND country = '{$this->data['Country']}'";
            }
            if ($this->data['effectiveDate']) {
                $where_sql .= " AND effective_date = '{$this->data['effectiveDate']}'";
            }
            if ($this->data['endDate']) {
                $where_sql .= " AND end_date = '{$this->data['endDate']}'";
            }
            $total_count = 0;
            foreach ($ids_arr as $id) {
                $sql = "delete from rate where rate_table_id = '{$id}' {$where_sql}";
                // echo $sql;die;
                $this->Rate->query($sql);

                $count = $this->Rate->getAffectedRows();


                // = count($result);

                $total_count += $count;
            }
            if ($total_count) {

                $this->Rate->create_json_array('', 201, __("The %s rate records are deleted successfully", true, $total_count));
                $this->Session->write('m', Rate::set_validator());
                $this->redirect('/rates/rates_list');
            } else {
                $this->Rate->create_json_array('', 101, 'Delete failed');
                $this->Session->write('m', Rate::set_validator());
                $this->redirect("/rates/massedit/{$this->data['ids']}");
            }
        } else {
            $this->redirect('/rates/rates_list');
        }
    }

    public function massedit($ids)
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $this->set("ids", $ids);
        $ids = explode(',', $ids);
        if ($this->RequestHandler->isPost()) {
            $data = $_POST;
            $count = count($data['code']);
            for ($i = 0; $i < $count; $i++) {
                $code = $data['code'][$i];
                $codename = $data['codename'][$i];
                $country = $data['country'][$i];
                $rate = $data['rate'][$i];
                $setupfee = $data['setupfee'][$i];
                $effectdate = $data['effectdate'][$i];
                $enddate = empty($data['enddate'][$i]) ? 'NULL' : "'" . $data['enddate'][$i] . "'";
                $endbreakouts = $data['endbreakouts'][$i];
                $mintime = $data['mintime'][$i];
                $gracetime = $data['gracetime'][$i];
                $seconds = $data['seconds'][$i];
                $timezone = $data['timezone'][$i];
                $localrate = $data['localrate'][$i];
                if (empty($code)) {
                    // 如果code为空，直接进行替换插入操作。
                    foreach ($ids as $rate_table_id) {
                        $this->Rate->matchEqualEndDate($rate_table_id, $code, $effectdate);
                        $this->Rate->insertMassEdit($rate_table_id, $code, $codename, $country, $rate, $setupfee, $effectdate, $enddate, $mintime, $gracetime, $seconds, $timezone, $localrate);
                    }
                } else {
                    if ($endbreakouts == 'true') {
                        foreach ($ids as $rate_table_id) {
                            // 如果按下了End Break-out
                            $this->Rate->matchPrefixEndDate($rate_table_id, $code, $effectdate);
                            $this->Rate->insertMassEdit($rate_table_id, $code, $codename, $country, $rate, $setupfee, $effectdate, $enddate, $mintime, $gracetime, $seconds, $timezone, $localrate);
                        }
                    } else {
                        foreach ($ids as $rate_table_id) {
                            // 如果没有按下
                            $this->Rate->matchEqualEndDate($rate_table_id, $code, $effectdate);
                            $this->Rate->insertMassEdit($rate_table_id, $code, $codename, $country, $rate, $setupfee, $effectdate, $enddate, $mintime, $gracetime, $seconds, $timezone, $localrate);
                        }
                    }
                }
            }
            $this->Rate->create_json_array('', 201, __('Succeeded', true));
            $this->Session->write('m', Rate::set_validator());
        }
    }

    public function masseditend($ids)
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $ids = explode(',', $ids);
        if ($this->RequestHandler->isPost()) {
            $data = $_POST;
            $count = count($data['code']);
            for ($i = 0; $i < $count; $i++) {
                $code = $data['code'][$i];
                $enddate = $data['enddate'][$i];
                $endbreakouts = $data['endbreakouts'][$i];
                if (empty($code)) {
                    // 如果code为空，直接进行替换插入操作。
                    foreach ($ids as $rate_table_id) {
                        $this->Rate->matchEqualEndDate1($rate_table_id, $code, $enddate);
                    }
                } else {
                    if ($endbreakouts == 'true') {
                        foreach ($ids as $rate_table_id) {
                            // 如果按下了End Break-out
                            $this->Rate->matchPrefixEndDate1($rate_table_id, $code, $enddate);
                        }
                    } else {
                        foreach ($ids as $rate_table_id) {
                            // 如果没有按下
                            $this->Rate->matchEqualEndDate1($rate_table_id, $code, $enddate);
                        }
                    }
                }
            }
            $this->Rate->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect('/rates/rates_list');
        }
    }

    public function code_rates_list($currency = null)
    {
        $this->set('table_name', $this->Rate->find_rate_table_name());
        $this->pageTitle = "Switch/Rate Table";
        $_SESSION['curr_url'] = '';
        if (isset($this->params['pass'][1]) && isset($this->params['pass'][2])) {
            $p1 = $this->params['pass'][1];
            $p2 = $this->params['pass'][2];
            $_SESSION['curr_url'] = "/$p1/$p2";
        }
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        if (!empty($_REQUEST ['page']))
            $currPage = $_REQUEST ['page'];
        if (!empty($_REQUEST ['size']))
            $pageSize = $_REQUEST ['size'];
        if (!empty($_REQUEST['search']) && empty($_REQUEST['advsearch'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $adv_search = '';
        if (!empty($_REQUEST['advsearch'])) {
            $last_conditions = '&advsearch=1';
            $f = empty($this->params['form']) ? $_REQUEST : $this->params['form'];
            if (!empty($f['search_code_deck'])) {
                $adv_search .= " and code_deck_id = {$f['search_code_deck']}";
                $last_conditions .= "&search_code_deck={$f['search_code_deck']}";
            }
            if (!empty($f['name'])) {
                $adv_search .= " and name like '%{$f['name']}%'";
                $last_conditions .= "&name={$f['name']}";
            }
            if (!empty($f['search_currency'])) {
                $adv_search .= " and currency_id = {$f['search_currency']}";
                $last_conditions .= "&search_currency={$f['search_currency']}";
            }
            if (!empty($f['search_res'])) {
                $adv_search .= " and reseller_id = {$f['search_res']}";
                $last_conditions .= "&search_res={$f['search_res']}";
            }
            $this->set('last_conditions', $last_conditions);
            $this->set('searchForm', $f);
        }
        if (!empty($this->params['url']['id'])) {
            $id = $this->params['url']['id'];
            $adv_search .= " and rate_table_id=$id";
        }
        $results = $this->Rate->getAllRates_code($currPage, $pageSize, $search, $currency, $adv_search, $this->_order_condtions(array('rate_table_id', 'name', 'code_deck', 'currency', 'client_rate')));
        $rs = $this->Rate->getAddInfo();
        $this->set('codecs', str_ireplace("\"", "'", json_encode($rs[1])));
        $this->set('currs', str_ireplace("\"", "'", json_encode($rs[2])));
        $this->set('jur_country', str_ireplace("\"", "'", json_encode($rs[3])));
        $search_info = $this->Rate->getSearchInfo();
        $this->set('codecs_s', $search_info[0]);
        $this->set('currs_s', $search_info[1]);
        $this->set('p', $results);
        $this->set('jurisdiction_countries', $this->Jurisdictionprefix->find_all_valid());
        $this->set('code_name', $this->Rate->select_name(array_keys_value($this->params, 'pass.0')));
        if (!empty($currency)) {
            $this->set('curr_search', true);
        }
    }

    public function indeteminate($rate_table_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $sql = "SELECT jurisdiction_prefix, noprefix_min_length, noprefix_max_length, prefix_min_length, prefix_max_length FROM rate_table
WHERE rate_table_id = {$rate_table_id}";
        $result = $this->Rate->query($sql);
        $this->set('rate_table_id', $rate_table_id);
        $this->set('data', $result[0][0]);
    }

    public function rates_list($currency = null)
    {
        $this->set('table_name', $this->Rate->find_rate_table_name());
        $this->pageTitle = "Switch/Rate Table";
        $_SESSION['curr_url'] = '';
        if (isset($this->params['pass'][1]) && isset($this->params['pass'][2])) {
            $p1 = $this->params['pass'][1];
            $p2 = $this->params['pass'][2];
            $_SESSION['curr_url'] = "/$p1/$p2";
        }
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (!empty($_REQUEST['search']) && empty($_REQUEST['advsearch']) && strcmp('Search....', $_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }


        $adv_search = '';
//        if (empty($_REQUEST['advsearch'])) {

        $last_conditions = '&advsearch=1';
        $f = empty($this->params['form']) ? $_REQUEST : $this->params['form'];
        if (!empty($f['search_code_deck'])) {
            $adv_search .= " and code_deck_id = {$f['search_code_deck']}";
            $last_conditions .= "&search_code_deck={$f['search_code_deck']}";
            $this->set('search_code_deck', $f['search_code_deck']);
        }
        if (!empty($f['name'])) {
            $adv_search .= " and name ilike '%{$f['name']}%'";
            $last_conditions .= "&name={$f['name']}";
        }
        if (!empty($f['search_currency'])) {
            $adv_search .= " and currency_id = {$f['search_currency']}";
            $last_conditions .= "&search_currency={$f['search_currency']}";
            $this->set('search_currency', $f['search_currency']);
        }
        if (!empty($f['search_res'])) {
            $adv_search .= " and reseller_id = {$f['search_res']}";
            $last_conditions .= "&search_res={$f['search_res']}";
        }

        // Skip origination rates
        $adv_search .= " and origination = false";

        $this->set('last_conditions', $last_conditions);
        //$this->set('searchForm', $f);
//        }
//        var_dump($adv_search);
        if (!empty($this->params['url']['id'])) {
            $id = $this->params['url']['id'];
            $adv_search .= " and rate_table_id in ($id)";
        }
        $results = $this->Rate->getAllRates($currPage, $pageSize, $search, $currency, $adv_search, $this->_order_condtions(array('rate_table_id', 'name', 'code_deck', 'currency', 'client_rate', 'rate_type')));
//        echo "<pre>";
//                print_r($results);
        $rs = $this->Rate->getAddInfo();
        $this->set('codecs', str_ireplace("\"", "'", json_encode($rs[1])));
        $this->set('currs', str_ireplace("\"", "'", json_encode($rs[2])));
        $this->set('jur_country', str_ireplace("\"", "'", json_encode($rs[3])));
        $search_info = $this->Rate->getSearchInfo();
        $this->set('codecs_s', $search_info[0]);
        $this->set('currs_s', $search_info[1]);
        $this->set('p', $results);
        $this->set('jurisdiction_countries', $this->Jurisdictionprefix->find_all_valid());
        $this->set('code_name', $this->Rate->select_name(array_keys_value($this->params, 'pass.0')));
        $this->set('jur_lists', $this->Rate->jurTypeArr);
        $this->set('define_by_arr', ['Code', 'Code Name']);
        if (!empty($currency)) {
            $this->set('curr_search', true);
        }
        $this->set('billing_methods', array('DNIS', 'LRN', 'LRN BLOCK'));


    }

    public function delete_all()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        if ($this->Rate->deleteAll() != true) {
            $this->Rate->create_json_array('', 101, __('ratetmpusing', true));
        } else {
            $this->Rate->create_json_array('', 201, __('All Rate Tables has been deleted successfully.', true));
        }
        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }

    public function delete_selected()
    {
        Configure::write('debug', 0);
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];
        $type = $_REQUEST['type'];

        if (!$ids) {
            $this->Rate->create_json_array('', 101, __('You have not select the rate table you want to delete!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect('/rates/rates_list');
        }
        $delete_failed_name = array();
        $been_used = array();
        $succ_arr = array();
        foreach ($ids as $id) {
            $sql = "SELECT rate_table_id, name  FROM rate_table WHERE rate_table_id = {$id} and 
(exists (select * from resource where resource.rate_table_id = rate_table.rate_table_id) or exists 
(select * from resource_prefix where resource_prefix.rate_table_id = rate_table.rate_table_id))";
            $result = $this->Rate->query($sql);

            if ($type == 1 || ($type == 2 && !count($result)) || ($type == 3 && count($result))) {
                $rate_table = $this->Rate->query("SELECT name,rate_table_id FROM rate_table WHERE rate_table_id = {$id} limit 1");
                $flg = $this->Rate->del($rate_table[0][0]['rate_table_id']);
                if ($flg === false)
                    $delete_failed_name[] = $rate_table[0][0]['name'];
                else
                    $succ_arr[] = $rate_table[0][0]['name'];
            }
        }
        if (count($delete_failed_name) || count($been_used)) {
            echo json_encode(array('status' => 0));
        } else {
            echo json_encode(array('status' => 1));
        }
        exit;
    }

    public function checkused()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_table_id = $_POST['rate_table_id'];
        $data = array();
        // Ingress
        $sql = "select client.name, client.status,resource.alias, resource.active from resource_prefix inner join resource 
on resource_prefix.resource_id = resource.resource_id left join client on resource.client_id = client.client_id
where resource_prefix.rate_table_id = {$rate_table_id}";
        $result = $this->Rate->query($sql);
        foreach ($result as $item) {
            array_push($data, array(
                'client_name' => $item[0]['name'],
                'resource_name' => $item[0]['alias'],
                'is_active' => $item[0]['status'] ? ($item[0]['active'] ? 'Yes' : 'No') : 'No',
                'type' => 'Ingress',
            ));
        }

        // Egress
        $sql = "select client.name, client.status, resource.alias, resource.active 
from resource left join client on resource.client_id = client.client_id
where resource.rate_table_id = {$rate_table_id}";
        $result = $this->Rate->query($sql);
        foreach ($result as $item) {
            array_push($data, array(
                'client_name' => $item[0]['name'],
                'resource_name' => $item[0]['alias'],
                'is_active' => $item[0]['status'] ? ($item[0]['active'] ? 'Yes' : 'No') : 'No',
                'type' => 'Egress',
            ));
        }

        echo json_encode($data);
    }

    public function del_rate_tmp($id)
    {
        //pr($this->Rate->query("select rollback from modif_log order by id desc limit 1"));die;
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $this->data = $this->Rate->find('first', Array('conditions' => Array('rate_table_id' => $id)));
        $old_resource_prefix_data = $this->Rate->query("SELECT resource_id FROM resource_prefix  WHERE rate_table_id = {$id}");
        $old_resource_data = $this->Rate->query("SELECT resource_id FROM resource  WHERE rate_table_id = {$id}");
        $resource_prefix_id_arr = array();
        foreach ($old_resource_prefix_data as $resource_prefix_item) {
            $resource_prefix_id_arr[] = $resource_prefix_item[0]['resource_id'];
        }
        $resource_id_arr = array();
        foreach ($old_resource_data as $resource_item) {
            $resource_id_arr[] = $resource_item[0]['resource_id'];
        }
        $resource_id_str = implode(',', $resource_id_arr);

        $sql = "update resource_prefix set rate_table_id = null  where rate_table_id = {$id};update resourse set rate_table_id = null where rate_table_id = {$id}";
        //$this->Rate->query($sql);

        /*
          $ingress_count = $this->Rate->query("select count(*) from resource_prefix
          inner join resource
          on resource.resource_id = resource_prefix.resource_id and resource_prefix.rate_table_id = {$id} and
          active = true");
          $egress_count  = $this->Rate->query("select count(*) from resource where rate_table_id = $id and active = true");
         *
         */
        if (false) {
            $this->Rate->create_json_array('', 101, __('Rate Table is being used; therefore, it cannot be deleted.', true));
        } else {
            $old_data = $this->Rate->findByRateTableId(array($id));
            $old_item_data = $this->RateTable->findAll(array('rate_table_id' => $id));
            if ($this->Rate->delete_one($id) != true)
                $this->Rate->create_json_array('', 101, __('ratetmpusing', true));
            else {
                $filed_arr = array();
                $value_arr = array();
                $old_data_arr = $old_data['Rate'];
                unset($old_data_arr['rate_table_id']);
                unset($old_data_arr['update_at']);
                $old_data_arr['update_by'] = $_SESSION['sst_user_name'];
                foreach ($old_data_arr as $key => $value) {
                    if ($value) {
                        $filed_arr[] = $key;
                        $str_arr = array(
                            'name', 'modify_time', 'create_time', 'jurisdiction_prefix', 'update_by'
                        );
                        if (in_array($key, $str_arr)) {
                            $value_arr[] = "'" . $value . "'";
                        } else {
                            $value_arr[] = $value;
                        }
                    }
                }
                $filed_str = implode(',', $filed_arr);
                $value_str = implode(',', $value_arr);

                $rollback_sql = "INSERT INTO rate_table ({$filed_str}) VALUES ({$value_str}) RETURNING rate_table_id;&&";
                foreach ($resource_prefix_id_arr as $value) {
                    $rollback_sql .= "INSERT INTO resource_prefix (resource_id,rate_table_id) VALUES ($value,{rate_table_id});";
                }
                if ($resource_id_str) {
                    $rollback_sql .= "UPDATE resource SET rate_table_id = {rate_table_id} WHERE resource_id in($resource_id_str);";
                }
                $rollback_msg = "Delete Rate Table [" . $this->data['ResourceBlock']['ani_prefix'] . "] operation have been rolled back!";
                $rollback_sql .= $this->_do_del_rollback($old_item_data);
                $rollback_extra_info = json_encode(array('type' => 4));
                $this->Rate->create_json_array('', 201, __('The Rate Table [%s] is deleted successfully!', true, $this->data['Rate']['name']));
                $this->Rate->logging(1, 'Rate Table', "Rate Table Name:{$this->data['Rate']['name']}", $rollback_sql, $rollback_msg, $rollback_extra_info);
            }
        }
        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }

    public function getCodeDeckData()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            if (isset($_POST['codeDeckId'])) {
                $codeDeckId = $_POST['codeDeckId'];
                $sql = "SELECT * FROM code_deck WHERE code_deck_id={$codeDeckId}";
                $res = $this->Rate->query($sql);
                die(var_dump($res));
            }
        }
    }

    public function copy_tmp()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $old_id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        if ($this->Rate->copy_rate($old_id, $name) != true)
            $this->Rate->create_json_array('', 101, __('copyfail', true));
        else
            $this->Rate->create_json_array('', 201, __('copysuc', true));

        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }

    function add_curr_tmp()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $n = $_REQUEST['n'];
        $c = $_REQUEST['c'];
        $cu = $_REQUEST['cu'];
        $order = 'null';
        if ($this->Rate->add($n, $c, $cu) != true)
            $this->Rate->create_json_array('', 101, 'Rate table name is exists');
        else
            $this->Rate->create_json_array('', 201, __('addratesuc', true));

        $this->Session->write('m', Rate::set_validator());
        $this->redirect("/rates/currency/" . $cu . "/currs/currency_list");
    }

    public function add_tmp()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $n = $_REQUEST['n'];
        $c = $_REQUEST['c'];
        $cu = $_REQUEST['cu'];
        $country = $_REQUEST['country'];
        //	$use_gf = $_REQUEST['use_gf'];
        $order = 'null';

        if ($this->Rate->add($n, $c, $cu, $country) != true)
            $this->Rate->create_json_array('', 101, 'Rate table name is exists');
        else
            $this->Rate->create_json_array('', 201, __('addratesuc', true));

        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }

    public function update_tmp()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $n = $_REQUEST['n'];

        $c = $_REQUEST['c'];
        $cu = $_REQUEST['cu'];
        $id = $_REQUEST['id'];
        $country = $_REQUEST['country'];


        if ($this->Rate->update($n, $c, $cu, $id, $country) != true)
            $this->Rate->create_json_array('', 101, __('modifyratefail', true));
        else
            $this->Rate->create_json_array('', 201, __('modifyratesuc', true));

        $this->Session->write('m', Rate::set_validator());

        $list = $this->Rate->query("select name  from rate_table  where  rate_table_id=$id");
        $this->redirect("/rates/rates_list?search={$list[0][0]['name']}");
    }

    /**
     * 上传数据验证
     */
    public function check_uploadcodes()
    {
        $code_deck_id = $this->params['pass'][0];
        $list = $this->Rate->query("select name   from  rate_table where   rate_table_id=$code_deck_id ");
        $this->set("code_name", $list[0][0]['name']);
        $this->set("rate_table_id", $code_deck_id);
        $this->set("mydata", $this->Rate->query("select *   from  tmp_rate    limit 10 "));
    }

    //上传成功 记录上传
    public function upload_code2()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['upload_table_id'];
        $code_name = $_POST['code_name'];
        $list = $this->Rate->import_data(__('UploadRate', true)); //上传数据
        $this->Rate->create_json_array("", 201, __('rateUploadSuccess', true));
        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    //上传code
    public function import_rate()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_x']) {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params['pass'][0];
        $list = $this->Rate->query("select name   from  rate_table where   rate_table_id=$rate_table_id ");
        $this->set("code_name", $list[0][0]['name']);
        $this->set("rate_table_id", $rate_table_id);
    }

    public function download_rate()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_x']) {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params['pass'][0];
        $download_sql = "select   code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds,code_name,intra_rate,
		inter_rate,local_rate
		from  rate  where rate_table_id=$rate_table_id";
        $this->Rate->export__sql_data(__('DownloadRate', true), $download_sql, 'rate');
        Configure::write('debug', 0);
        $this->layout = 'csv';
    }

    public function r_rates_list($table_id, $type = null)
    {
        if (!empty($type)) {
            $this->set('extraClient', true);
        }
        $this->set('rate_table_id', $table_id);
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        $adv_search = '';
        //高级搜索
        if (!empty($_REQUEST['advsearcht'])) {
            $last_conditions = '';
            $adv_form = $this->params['form'];
            if (!empty($adv_form['startrate'])) {
                $adv_search .= " and rate >= {$adv_form['startrate']}";
                $last_conditions .= "&startrate={$adv_form['startrate']}";
            }
            if (!empty($adv_form['endrate'])) {
                $adv_search .= " and rate <= {$adv_form['endrate']}";
                $last_conditions .= "&endrate={$adv_form['endrate']}";
            }

            if (!empty($adv_form['startsetupfee'])) {
                $adv_search .= " and setup_fee >= {$adv_form['startsetupfee']}";
                $last_conditions .= "&startsetupfee={$adv_form['startsetupfee']}";
            }

            if (!empty($adv_form['endsetupfee'])) {
                $adv_search .= " and setup_fee <= {$adv_form['endsetupfee']}";
                $last_conditions .= "&endsetupfee={$adv_form['endsetupfee']}";
            }

            if (!empty($adv_form['startmint'])) {
                $adv_search .= " and min_time >= {$adv_form['startmint']}";
                $last_conditions .= "&startmint={$adv_form['startmint']}";
            }

            if (!empty($adv_form['endmint'])) {
                $adv_search .= " and min_time <= {$adv_form['endmint']}";
                $last_conditions .= "&endmint={$adv_form['endmint']}";
            }

            if (!empty($adv_form['startinterv'])) {
                $adv_search .= " and interval >= {$adv_form['startinterv']}";
                $last_conditions .= "&startinterv={$adv_form['startinterv']}";
            }

            if (!empty($adv_form['endinterv'])) {
                $adv_search .= " and interval <= {$adv_form['endinterv']}";
                $last_conditions .= "&endinterv={$adv_form['endinterv']}";
            }

            if (!empty($adv_form['startgrace'])) {
                $adv_search .= " and grace_time >= {$adv_form['startgrace']}";
                $last_conditions .= "&startgrace={$adv_form['startgrace']}";
            }

            if (!empty($adv_form['endgrace'])) {
                $adv_search .= " and grace_time <= {$adv_form['endgrace']}";
                $last_conditions .= "&endgrace={$adv_form['endgrace']}";
            }

            if (!empty($adv_form['searchtf'])) {
                $adv_search .= " and time_profile_id = {$adv_form['searchtf']}";
                $last_conditions .= "&searchtf={$adv_form['searchtf']}";
            }
            $this->set('last_conditons', $last_conditions);
            $this->set('searchForm', $this->params['form']);
        }

        //批量修改rate  应用或者预览
        if (!empty($_REQUEST['updateForm'])) {
            $f = $this->params['form'];
            $type = $f['type']; //执行方式
            $ids = $f['ids']; //需要操作的rate 的id  格式:125,526,542
            //-------参数-------------------
            $rate = $f['rate_per_min_action']; //费率
            $mintime = $f['min_time_action']; //最小时长
            $starttime = $f['effective_from_action']; //开始时间
            $setupfee = $f['pay_setup_action']; //一分钟的费用
            $interval = $f['pay_interval_action']; //计费周期
            $endtime = $f['end_date_action']; //结束时间
            $gracetime = $f['grace_time_action']; //赠送时长
            $sql = "update rate set rate_id = rate_id";
            $sql_select = "select rate_id,code,seconds,rate_table_id,(select name from time_profile where time_profile_id = rate.time_profile_id) as tf";
            ////////////////////////////////////
            if (!empty($rate)) {
                $rate_v = $f['rate_per_min_value'];
                //设置为该提交的值
                if ($rate == 'set') {
                    $sql .= ",rate=$rate_v";
                    $sql_select .= " ,$rate_v as rate";
                } //在基础上加
                else if ($rate == 'inc') {
                    $sql .= ",rate = rate + $rate_v";
                    $sql_select .= " ,rate+$rate_v as rate";
                } //在基础上减
                else if ($rate == 'dec') {
                    $sql .= ",rate = rate - $rate_v";
                    $sql_select .= " ,rate-$rate_v as rate";
                } //按百分比加
                else if ($rate == 'perin') {
                    $sql .= ",rate = rate +(rate*$rate_v/100)";
                    $sql_select .= " ,rate+(rate*$rate_v/100) as rate";
                } //按百分比减
                else if ($rate == 'perde') {
                    $sql .= ",rate = rate -(rate*$rate_v/100)";
                    $sql_select .= " ,rate-(rate*$rate_v/100) as rate";
                }
            } else {
                $sql_select .= ",rate";
            }

            if (!empty($mintime)) {
                $mintime_v = $f['min_time_value'];
                //设置为该提交的值
                if ($mintime == 'set') {
                    $sql .= ",min_time=$mintime_v";
                    $sql_select .= " ,$mintime_v as rate";
                } //在基础上加
                else if ($mintime == 'inc') {
                    $sql .= ",min_time=min_time+$mintime_v";
                    $sql_select .= " ,min_time+$mintime_v as min_time";
                } //在基础上减
                else if ($mintime == 'dec') {
                    $sql .= ",min_time=min_time-$mintime_v";
                    $sql_select .= " ,min_time-$mintime_v as min_time";
                }
            } else {
                $sql_select .= ",min_time";
            }

            if (!empty($starttime)) {
                if (!empty($f['effective_from_value'])) {
                    $sql .= ",effective_date='{$f['effective_from_value']}'";
                    $sql_select .= " ,{$f['effective_from_value']} as effective_date";
                } else {
                    $sql_select .= ",effective_date";
                }
            }

            if (!empty($setupfee)) {
                $setupfee_v = $f['pay_setup_value'];
                //设置为该提交的值
                if ($setupfee == 'set') {
                    $sql .= ",setup_fee=$setupfee_v";
                    $sql_select .= " ,$setupfee_v as setup_fee";
                } //在基础上加
                else if ($setupfee == 'inc') {
                    $sql .= ",setup_fee=setup_fee+$setupfee_v";
                    $sql_select .= " ,setup_fee+$setupfee_v as setup_fee";
                } //在基础上减
                else if ($setupfee == 'dec') {
                    $sql .= ",setup_fee=setup_fee-$setupfee_v";
                    $sql_select .= " ,setup_fee-$setupfee_v as setup_fee";
                } //按百分比加
                else if ($setupfee == 'perin') {
                    $sql .= ",setup_fee = setup_fee +(setup_fee*$setupfee_v/100)";
                    $sql_select .= " ,setup_fee+(setup_fee*$setupfee_v/100) as setup_fee";
                } //按百分比加
                else if ($setupfee == 'perde') {
                    $sql .= ",setup_fee = setup_fee -(setup_fee*$setupfee_v/100)";
                    $sql_select .= " ,setup_fee-(setup_fee*$setupfee_v/100) as setup_fee";
                }
            } else {
                $sql_select .= ",setup_fee";
            }

            if (!empty($interval)) {
                $sql .= ",interval={$f['pay_interval_value']}";
                $sql_select .= " ,{$f['pay_interval_value']} as interval";
            } else {
                $sql_select .= ",interval";
            }

            if (!empty($endtime)) {
                $sql .= ",end_date='{$f['end_date_value']}'";
                $sql_select .= " ,{$f['pay_interval_value']} as end_date";
            } else {
                $sql_select .= ",end_date";
            }

            if (!empty($gracetime)) {
                $sql .= ",grace_time={$f['grace_time_value']}";
                $sql_select .= " ,{$f['grace_time_value']} as grace_time";
            } else {
                $sql_select .= ",grace_time";
            }

            if ($type == 'apply') {//应用
                $sql .= " where rate_id in($ids)";

                $qs = $this->Rate->query($sql);
                if (count($qs) == 0) {
                    $this->Rate->create_json_array('', 201, __('manipulated_suc', true));
                } else {
                    $this->Rate->create_json_array('', 101, __('manipulated_fail', true));
                }
                $this->set('m', Rate::set_validator());
            } else {//预览
                $this->set('previewForm', $f);
                $this->set('previewRates', $this->Rate->query($sql_select . " from rate where rate_id in ($ids)"));
            }
        }

        if (!empty($_REQUEST ['page']))
            $currPage = $_REQUEST ['page'];

        if (!empty($_REQUEST ['size']))
            $pageSize = $_REQUEST ['size'];

        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }

        $results = $this->Rate->getRates($currPage, $pageSize, $search, $table_id, $adv_search);

        $this->set('p', $results);

        $reseller_id = $this->Session->read('sst_reseller_id');
        $times = $this->Rate->getTimeProfile($reseller_id);
        $this->set('times', str_ireplace("\"", "'", json_encode($times)));
        $this->set('timeswithoutencode', $times);

        $changerate = $this->Rate->get_rate_tables($reseller_id);
        $this->set('changerate', $changerate);
        $this->set('table_id', $table_id);
        $this->set('now', date('Y-m-d H:i:s', time() + 6 * 60 * 60));
        $ss = $this->Rate->hasCodedeck($table_id);
        if ($ss != false) {
            $this->set('code_deck', $ss);
        } else {
            $this->set('code_deck', '-1');
        }
    }

    public function update_indeter()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_table_id = $_GET['rate_table_id'];
        $jurisdiction_prefix = !empty($_GET['jurisdiction_prefix']) ? "'{$_GET['jurisdiction_prefix']}'" : 'NULL';
        $noprefix_max_length = !empty($_GET['noprefix_max_length']) ? $_GET['noprefix_max_length'] : 'NULL';
        $noprefix_min_length = !empty($_GET['noprefix_min_length']) ? $_GET['noprefix_min_length'] : 'NULL';
        $prefix_max_length = !empty($_GET['prefix_max_length']) ? $_GET['prefix_max_length'] : 'NULL';
        $prefix_min_length = !empty($_GET['prefix_min_length']) ? $_GET['prefix_min_length'] : 'NULL';
        $sql = "UPDATE rate_table SET jurisdiction_prefix = {$jurisdiction_prefix}, noprefix_max_length = {$noprefix_max_length},
                noprefix_min_length = {$noprefix_min_length}, prefix_max_length = {$prefix_max_length}, prefix_min_length = {$prefix_min_length} WHERE
                rate_table_id = {$rate_table_id}";
        $this->Rate->query($sql);
        echo 1;
    }

    public function add_rate()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Rate->add_rate();
        echo $qs;
    }

    public function update_rate()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Rate->update_rate();
        echo $qs;
    }

    public function choose_codes($code_deck_id)
    {
        $this->layout = '';
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        if (!empty($_REQUEST ['page']))
            $currPage = $_REQUEST ['page'];
        if (!empty($_REQUEST ['size']))
            $pageSize = $_REQUEST ['size'];
        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $results = $this->Rate->choose_codes($currPage, $pageSize, $search, $code_deck_id);
        $this->set('p', $results);
    }

    public function del_rate($id, $table_id)
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $old_data = $this->Rate->findAll(array("rate_table_id" => $id));
        $del_arr = $this->Rate->del_rate($id);
        if ($del_arr) {
            $rollback_sql = $this->_do_del_rollback($old_data);
            $rollback_msg = "Delete Rate Table [" . $del_arr[0][0]['name'] . "] operation have been rolled back!";
            $log_id = $this->Rate->logging('1', 'Rate Table', "Rate Table:{$del_arr[0][0]['name']} ", $rollback_sql, $rollback_msg);
            $url_flug = "rates-rates_list";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/rates-rates_list");
            $this->Rate->create_json_array('', 201, __('del_suc', true));
        } else {
            $this->Rate->create_json_array('', 101, __('del_fail', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect('/rates/r_rates_list/' . $table_id);
        }
    }

    public function generate_by_codedeck()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Rate->generate_by_codedeck();
        echo $qs;
    }

    public function simulated()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $date = $_REQUEST['date'];
        $number = $_REQUEST['number'];
        $durations = $_REQUEST['durations'];
        $tab_id = $_REQUEST['tab_id']; //费率模板

        $qs = $this->Rate->simulated($date, $number, $durations, $tab_id);

        echo $qs;
    }

    function js_save($id = null)
    {
        Configure::write('debug', 0);
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        if ($id) {
            $this->data = $this->Rate->find('first', Array('conditions' => Array('rate_table_id' => $id)));
        }
        $this->set('define_by_arr', ['Code', 'Code Name']);
        $this->_render_set_options(Array('Currency', 'Codedeck'));
        $this->layout = 'ajax';

    }

    public function test()
    {
        $a = $this->Rate->find('first', array('conditions' => array('name' => 'test_b')));
    }

    public function generate_tmp()
    {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $rate_model = $this->Rate->find('first', array('conditions' => array('name' => $this->data['name'])));
        $data = Array();
        $data['name'] = $this->data['name']; #rable name
        $data['rate_type'] = $this->data['rate_type']; #rable name
        $data['currency_id'] = $this->data['currency'];
        $data['create_time'] = date('Y-m-d h:i:s');
        $data['modify_time'] = date('Y-m-d h:i:s');
        if (empty($rate_model)) {
            $this->Rate->save($data);
            $this->data['rate_table_id'] = $this->Rate->getLastInsertId();
        } else {
            $this->data['rate_table_id'] = $rate_model['Rate']['rate_table_id'];
        }

        if (empty($this->data['type_num'])) {
            $this->data['type_num'] = 0;
        }
        if ($this->data['type'] == 4) {
            $this->data['type_num'] = $this->data['type_num'] / 100;
        }
        $this->Rate->query("SELECT egress_profit_fun('{$this->data['ids']}',{$this->data['type']},{$this->data['rate_table_id']},{$this->data['type_num']},'{$this->data['code_type']}'); ");
        $this->Rate->create_json_array('', 201, __('Auto create successfully!', true));
        $this->xredirect("/rates/rates_list");
    }

    public function create_ratetable()
    {
        if ($this->_get('is_ajax'))
            Configure::write('debug', 0);
        if ($this->RequestHandler->IsPost()) {
            if ($this->_get('is_ajax')) {
                $this->autoLayout = false;
                $this->autoRender = false;
            }
            $rate_table_name = $_POST['rate_table_name'];
            $code_deck = empty($_POST['code_deck']) ? 'NULL' : $_POST['code_deck'];
            $currency = empty($_POST['currency']) ? 'NULL' : $_POST['currency'];
            $define_by = empty($_POST['define_by']) ? 0 : $_POST['define_by'];
            $type = $_POST['type'];
            $rate_type = (int)$_POST['rate_type'];
            //$jurisdiction = $_POST['jurisdiction'] == '' ? 'NULL' : $_POST['jurisdiction'];
            $isus = $rate_type == 2 ? true : false;
            if ($currency == 'NULL') {
                if ($this->_get('is_ajax')) {
                    return 0;
                }
                $this->Rate->create_json_array('', 101, __('You must create Currency first!', true));
                $this->xredirect("/currs/index");
            }
            $jurisdiction = 'NULL';
            if (!$this->Rate->alreay_exists_ratetable($rate_table_name)) {
                $rate_table_id = $this->Rate->create_ratetable($rate_table_name, $code_deck, $currency, $type, $isus, $rate_type, "false", $define_by);
                if ($rate_table_id == false) {
                    if ($this->_get('is_ajax')) {
                        return 0;
                    }
                    $this->Rate->create_json_array('', 101, __('The Rate Table[%s] is added unsuccessfully.', true, $rate_table_name));
                    $this->xredirect("/rates/create_ratetable");
                }
                if (isset($_POST['code'])) {
                    $count = count($_POST['code']);
                    for ($i = 0; $i < $count; $i++) {
                        $code = $_POST['code'][$i];
                        $code_name = $_POST['code_name'][$i];
                        $country = $_POST['country'][$i];
                        $rate = $_POST['rate'][$i];
                        $intra_rate = !empty($_POST['intra_rate'][$i]) ? $_POST['intra_rate'][$i] : 'NULL';
                        $inter_rate = !empty($_POST['inter_rate'][$i]) ? $_POST['inter_rate'][$i] : 'NULL';
                        $effective_rate = $_POST['effective_date'][$i];
                        $effective_date_gmt = $_POST['effective_date_gmt'][$i];
                        $end_date = $_POST['end_date'][$i];
                        $end_date_gmt = $_POST['end_date_gmt'][$i];
                        $end_date = !empty($end_date) ? "'" . $end_date . $end_date_gmt . "'" : 'NULL';
                        $setup_fee = $_POST['setup_fee'][$i];
                        $min_time = $_POST['min_time'][$i];
                        $interval = $_POST['interval'][$i];
                        $grace_time = $_POST['grace_time'][$i];
                        $second = $_POST['second'][$i];
                        $profile = empty($_POST['profile'][$i]) ? 'NULL' : $_POST['profile'][$i];
                        $local_rate = empty($_POST['local_rate'][$i]) ? 'NULL' : $_POST['local_rate'][$i];
                        $ocn = empty($_POST['ocn'][$i]) ? 'NULL' : $_POST['ocn'][$i];
                        $lata = empty($_POST['lata'][$i]) ? 'NULL' : $_POST['lata'][$i];
                        // 检测是否存在同code和同生存时间
                        if ($this->Rate->has_exists_code($rate_table_id, $code, $effective_rate, $effective_date_gmt)) {
                            $this->Rate->create_json_array('', 101, __("[%s] has exists.", true, $code));
                            continue;
                        }

                        $set_early_date = date("Y-m-d H:i:sO", strtotime($effective_rate . $effective_date_gmt) - 1);
                        $this->Rate->end_early_date($rate_table_id, $code, $set_early_date, $effective_rate, $effective_date_gmt);
                        $sql = "INSERT INTO rate(rate_table_id, code, rate, setup_fee, effective_date, end_date, min_time, grace_time,
                            interval, time_profile_id, seconds, code_name, intra_rate, inter_rate, local_rate, country, ocn, lata)
                            VALUES ($rate_table_id, '$code', $rate, $setup_fee, '{$effective_rate}{$effective_date_gmt}', {$end_date}, 
                            $min_time, $grace_time, $interval, $profile, $second, '{$code_name}', $intra_rate, $inter_rate, $local_rate, '{$country}', {$ocn}, {$lata})";
                        $res = $this->Rate->query($sql);

                    }
                }
                $rollback_sql = "DELETE FROM rate_table WHERE rate_table_id = {$rate_table_id}";
                $rollback_msg = "Create Rate Table [" . $rate_table_name . "] operation have been rolled back!";

                $this->Rate->logging(0, 'Rate Table', "Rate Table Name:{$rate_table_name}", $rollback_sql, $rollback_msg);
                if ($this->_get('is_ajax')) {
                    return $rate_table_id;
                }
                $this->Rate->create_json_array('', 201, __('The Rate Table[%s] is added successfully.', true, $rate_table_name));
                if (!$define_by) {
                    $this->xredirect("/clientrates/view/" . base64_encode($rate_table_id));
                } else {
                    $this->xredirect("/clientrates/view_code_name_rate/" . $rate_table_id);
                }
            } else {
                $this->Rate->create_json_array('', 101, __('The Rate Table[%s] already exist!', true, $rate_table_name));
            }
        }
        $code_decks = $this->Rate->get_code_decks();
        $currencies = $this->Rate->get_currencies();
        $jurisdictions = $this->Rate->get_jurisdictions();
        $timeprofiles = $this->Rate->get_timeprofiles();
        $rate_type_arr = array(
            __('A-Z', true),
            __('US Non-JD', true),
            __('US JD', true),
            // __('OCN-LATA-JD',true),
            // __('OCN-LATA-NON-JD',true)
        );
        $default_currency = $this->Rate->query("SELECT sys_currency,default_us_ij_rule FROM system_parameter LIMIT 1");
        $this->set('default_currency', $default_currency[0][0]['sys_currency']);
        $this->set('default_us_ij_rule', $default_currency[0][0]['default_us_ij_rule']);
        $this->set('code_decks', $code_decks);
        $this->set('currencies', $currencies);
        $this->set('jurisdictions', $jurisdictions);
        $this->set('timeprofiles', $timeprofiles);
        $this->set('rate_type_arr', $rate_type_arr);
    }

    public function get_code_name()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $query = $_GET['q'];
        $limit = $_GET['limit'];
        $sql = "SELECT DISTINCT name FROM code WHERE code_deck_id = (SELECT code_deck_id FROM code_deck WHERE client_id IS NULL LIMIT 1) AND name ilike '{$query}%' ORDER BY name ASC LIMIT {$limit}";
        $results = $this->Rate->query($sql);
        foreach ($results as $result) {
            echo $result[0]['name'] . '|' . $result[0]['name'] . "\n";
        }
    }

    public function add_template()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $content = $_POST['content'];
        $sql = "INSERT INTO send_rate_template(name, subject, content) VALUES ('{$name}', '{$subject}', '{$content}') RETURNING id";
        $data = $this->Rate->query($sql);
        echo json_encode($data[0]);
    }

    public function getcodenames()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_table_id = $_POST['rate_table_id'];
        $data = $this->Rate->code_names($rate_table_id);
        echo json_encode($data);
    }

    public function rate_templates()
    {
        $sql = "SELECT * FROM send_rate_template ORDER BY id DESC";
        $data = $this->Rate->query($sql);
        $this->set('data', $data);
    }

    public function rate_sending_logging()
    {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $count = $this->Rate->get_rate_sending_logging_count();
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Rate->get_rate_sending_logging($pageSize, $offset);
        $page->setDataArray($data);
        $this->set('p', $page);
        $status = array('succeed', 'db error', 'smtp error', 'other error');
        $this->set('status', $status);
    }

    public function get_file($file)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $file = base64_decode($file);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
    }

    public function rate_sending()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::load('myconf');
            $ip = Configure::read("sendrate.ip");
            $port = Configure::read("sendrate.port");

            $_POST['myfile_guid'] = WWW_ROOT . "upload" . DIRECTORY_SEPARATOR . "email_list" . DIRECTORY_SEPARATOR . $_POST['myfile_guid'];

            $data = json_encode($_POST);
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket === false) {
                socket_close($socket);
                echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
            }
            $result = socket_connect($socket, $ip, $port);
            if ($result === false) {
                socket_close($socket);
                echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
            }
            socket_write($socket, $data, strlen($data));

            socket_close($socket);
            $this->Rate->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Rate::set_validator());
        }
        // 取得数据
        $rate_tables = $this->Rate->rate_tables();
        $carriers = $this->Rate->get_carriers();
        $templates = $this->Rate->template_lists();
        $this->set('rate_tables', $rate_tables);
        $this->set('carriers', $carriers);
        $this->set('templates', $templates);
        $fields = array(
            'code', 'inter_rate', 'intra_rate', 'current_rate', 'new_rate', 'interval', 'min_time', 'effective_date', 'country', 'code_name', 'status'
        );
        $this->set('fields', $fields);
    }

    public function edit_template($id)
    {
        if ($this->RequestHandler->isPost()) {
            $name = $_POST['name'];
            $subject = $_POST['subject'];
            $content = $_POST['content'];
            $sql = "update send_rate_template set name = '{$name}', subject = '{$subject}', content = '{$content}' WHERE id = {$id}";
            $this->Rate->query($sql);
            $this->Rate->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Rate::set_validator());
        }
        $sql = "SELECT * FROM send_rate_template WHERE id = {$id}";
        $data = $this->Rate->query($sql);
        $this->set('data', $data);
    }

    public function delete_template($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "delete from send_rate_template where id = {$id}";
        $this->Rate->query($sql);
        $this->Rate->create_json_array('', 201, __('Successfully!', true));
        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rate_templates');
    }

    public function ajax_get_rate_email_template($template_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->loadModel('RateEmailTemplate');
        $data = $this->RateEmailTemplate->find('first', array(
            'fields' => 'email_from,subject,content,email_cc,headers, download_method',
            'conditions' => array(
                'id' => $template_id
            ),
        ));
        if ($data)
            echo json_encode($data['RateEmailTemplate']);
        else
            echo '';
    }

    public function download_error_file($encode_log_id, $is_detail = '')
    {
        Configure::write('debug', 0);
        $log_id = base64_decode($encode_log_id);
        if (!$is_detail)
            $sql = "SELECT error FROM rate_send_log WHERE id = $log_id";
        else
            $sql = "SELECT error FROM rate_send_log_detail WHERE id = $log_id";
        $data = $this->Rate->query($sql);
        $error_info = $data[0][0]['error'];

        $file_name = "send_rate_log_error.log";
        $file_path = Configure::read('database_export_path') . DS . $file_name;
        file_put_contents($file_path, $error_info);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        ob_clean();
        flush();
        readfile($file_path);
    }

    public function send_rate($id = null, $route_wizard_id = '')
    {
        if ($id) {
            if (strcmp($id, intval($id)))
                $id = base64_decode($id);
        }

        $mail_arr = $this->Rate->get_client_email_by_ratetable($id);
        $arr = array();
        foreach ($mail_arr as $key => $mail_item) {
            $send_mail = $mail_item[0]['rate_email'];

            if (empty($mail_item[0]['rate_email']))
                $send_mail = $mail_item[0]['email'];
            $mail_arr[$key][0]['send_mail'] = $send_mail;
            $mail_arr[$key][0]['active'] = $mail_arr[$key][0]['active'] ? 'Yes' : 'No';
            if (!$send_mail)
                continue;
            if (!in_array($send_mail, $arr))
                $arr[] = $send_mail;
        }
        if ($id) {
            $sql = "SELECT name,jur_type FROM rate_table WHERE rate_table_id = {$id}";
            $rate_info = $this->Rate->query($sql);
            if (empty($rate_info)) {
                $is_empty_flg = 1;
            }
        }
        if (isset($is_empty_flg) || empty($id)) {
            $this->Rate->create_json_array('', 101, __('There is no assignee for rate deck', true));
            $this->Session->write('m', Rate::set_validator());
            if ($route_wizard_id) {
                $this->redirect('/routestrategys/wizard');
            }
            $this->redirect("/rates/rates_list");
        }
        $this->set('rate_table_name', $rate_info[0][0]['name']);
        $this->set('rate_table_id', $id);
        $mail_senders = $this->Mailtmp->get_mail_senders();
        $this->set('mail_senders', $mail_senders);
        $this->loadModel('RateEmailTemplate');
        $rate_email_template = $this->Mailtmp->find_all_rate_email_template();
        $rate_email_template['save_temporary'] = __('Do Not Use Template', true);
        $this->set('rate_email_template', $rate_email_template);
        $this->loadModel('SendRatePreservedData');
        $preservedData = $this->SendRatePreservedData->query("SELECT * from send_rate_preserved_data WHERE rate_id = {$id}");
        if (!empty($preservedData)) {
            $preservedData = $preservedData[0][0];
        } else {
            $preservedData = array('email_cc' => '', 'subject' => '', 'content' => '',
                'format' => '', 'zipped' => '', 'start_effective_date' => '', 'email_template' => '');
        }
        $this->set('preservedData', $preservedData);

        $schema = $this->RateTable->get_schema($rate_info[0][0]['jur_type']);
        $options = array();
        $default_fields = array();
        foreach ($schema as $field_name => $value) {
            $options[$field_name] = isset($value['name']) ? Inflector::humanize($value['name']) : Inflector::humanize($field_name);
            if (isset($value['default_fields']))
                $default_fields[] = $field_name;
        }

        $this->set('schema', $options);
        $this->set('default_fields', $default_fields);
        $this->set('ereturn_url', $route_wizard_id);
        $this->set('use_info', $mail_arr);
        $this->set('rate_id', $id);
    }

    public function filterResource($id, $status)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $mail_arr = $this->Rate->get_client_email_by_ratetable($id, $status);
        foreach ($mail_arr as $key => $mail_item) {
            $send_mail = $mail_item[0]['rate_email'];

            if (empty($mail_item[0]['rate_email']))
                $send_mail = $mail_item[0]['email'];
            $mail_arr[$key][0]['send_mail'] = $send_mail;
            $mail_arr[$key][0]['active'] = $mail_arr[$key][0]['active'] ? 'Yes' : 'No';
        }

        $this->jsonResponse(['status' => true, 'data' => $mail_arr]);

    }

    public function save_new_template()
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            $new_email_template_arr = $_POST;
            if (isset($new_email_template_arr['name']) && !empty($new_email_template_arr['name'])) {
                $headers = implode(",", $_POST['headers']);
                $new_email_template_arr['headers'] = $headers;
                $this->loadModel('RateEmailTemplate');
                $flg = $this->RateEmailTemplate->save($new_email_template_arr);
                if ($flg) {
                    echo 'Success!';
                } else {
                    echo 'Failed!';
                }
            }
        }
        exit;
    }

    public function save_template_changes()
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            $new_email_template_arr = $_POST;
            $headers = implode(",", $_POST['headers']);
            $new_email_template_arr['headers'] = $headers;
            $this->loadModel('RateEmailTemplate');
            $flg = $this->RateEmailTemplate->save($new_email_template_arr);
            if ($flg) {
                $this->jsonResponse(['status' => true]);
            } else {
                $this->jsonResponse(['status' => false]);
            }
        }
    }

    //初步处理发送rate_table事件
    public function send_rate_record($route_wizard_id = '', $sent_log_id = '')
    {
        $download_method = isset($_POST['data']['download_method']) ? $_POST['data']['download_method'] : 0;
        $headers = $this->data['headers'];
        // set default headers if empty
        if (!$headers) {
            $headers = ['code', 'code_name', 'country', 'effective_date', 'rate'];
            $headers = implode(',', $headers);
        }
        $extra_params = [
            'content' => base64_encode($this->data['content']),
            'subject' => base64_encode($this->data['subject']),
            'email_cc' => $this->data['email_cc'],
            'headers' => $headers
        ];
        $extra_params = escapeshellarg(serialize($extra_params));
        if ($sent_log_id) {

            $sent_log_id = base64_decode($sent_log_id);
            $cmd = APP . "../cake/console/cake.php ratesend {$sent_log_id} $download_method $extra_params $route_wizard_id  > /dev/null &";
            $info = $this->Systemparam->find('first', array(
                'fields' => array('cmd_debug'),
            ));

            if (Configure::read('cmd.debug')) {
                file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
            }

            shell_exec($cmd);
            $this->Rate->create_json_array('', 201, "Resend successfully!");
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/rates/send_rate_log");

        }
        $this->autoRender = false;
        $this->autoLayout = false;
        $post_data = $this->params['form'];

        $rate_table_id = $post_data['rate_table_id'];
        $send_type = $this->_post('send_type');

        // deadline when sending to own rec.
        if ($send_type == 1) {
            $post_data['download_deadline'] = date('Y-m-d', strtotime('+1 year'));
        }
        if (empty($this->data['content']) || empty($this->data['subject'])) {
            $this->Rate->create_json_array('', 101, __('The information should not be null', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/rates/send_rate/" . base64_decode($rate_table_id) . "/$route_wizard_id");
        }

        if (empty($post_data['resource_id']) && !$send_type) {
            $this->Rate->create_json_array('', 101, __('There is no Carrier using this rate table!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/rates/rates_list");
        }

        if (!$this->isnotEmpty($post_data, array('format', 'rate_table_id'))) {
            $this->Rate->create_json_array('', 101, __('The keyword not found!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/rates/send_rate/" . base64_decode($rate_table_id) . "/$route_wizard_id");
        }

        if (($_POST['data']['download_method'] == 2) && (empty($post_data['download_deadline']))) {
            $this->Rate->create_json_array('', 101, __('The Field download_deadline should not be null', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/rates/send_rate/" . base64_decode($rate_table_id) . "/$route_wizard_id");
        }
        // rate_id | email_cc | subject | content
        $this->loadModel('SendRatePreservedData');

        $preservedDataExist = $this->SendRatePreservedData->find('count', array(
            'conditions' => array(
                'rate_id' => $rate_table_id
            ),
        ));

        $temp_id = $_POST['email_template'];
        if (strcmp($_POST['email_template'], 'save_temporary')) {
            $temp_id = 0;
        }


        $_POST['zipped'] = isset($_POST['zipped']) ? $_POST['zipped'] : 'false';
        if (!is_null($preservedDataExist) && $preservedDataExist) { // update existing data
            $this->SendRatePreservedData->query("UPDATE send_rate_preserved_data
                SET email_cc = '{$this->data['email_cc']}', subject = '{$this->data['subject']}', content = '{$this->data['content']}',
                format = '{$_POST['format']}', zipped = '{$_POST['zipped']}', start_effective_date = '{$_POST['start_effective_date']}', email_template = '{$temp_id}'
                WHERE rate_id = {$rate_table_id}");
        } else { // add new record
            $this->SendRatePreservedData->query("INSERT INTO send_rate_preserved_data(rate_id, email_cc, subject, content, format, zipped, start_effective_date, email_template)
                VALUES({$rate_table_id}, '{$this->data['email_cc']}', '{$this->data['subject']}', '{$this->data['content']}', '{$_POST['format']}', '{$_POST['zipped']}', '{$_POST['start_effective_date']}', '{$temp_id}')");
        }

        // get client emails
        $client_emails = [];
        foreach ($post_data['client_emails'] as $res_email) {
            $res_email_info = explode('::', $res_email);
            if (!empty($res_email_info) && in_array($res_email_info[0], $post_data['resource_id'])) {
                $client_emails[] = $res_email_info[1];
            }
        }

        $flg_zip = 0;
        if (isset($post_data['zipped'])) {
            $flg_zip = 1;
        }
        $start_effective_date = isset($post_data['start_effective_date']) && $post_data['start_effective_date'] ? $post_data['start_effective_date'] : date('Y-m-d', strtotime('+1 day'));
        $email_template = $post_data['email_template'];
        $download_deadline = isset($post_data['download_deadline']) ? $post_data['download_deadline'] : '';
        $is_email_alert = isset($post_data['is_email_alert']) ? true : false;
        $is_disable = isset($post_data['is_disable']) ? true : false;
        $send_specify_email = isset($post_data['send_specify_email']) && $post_data['send_specify_email'] ? $post_data['send_specify_email'] : '';
        $resource_id_unique = array_unique($post_data['resource_id']);
        // only one resource when sending to own recipient
        if ($send_type) {
            $resource_id_unique = [$resource_id_unique[0]];
        }

        $send_specify_email = $send_specify_email ?: implode(';', array_unique($client_emails));
        $new_email_template_arr = $this->data;
        $isTemporaryTemplate = false;

        if (!$email_template || !strcmp($email_template, 'save_temporary')) {
            $new_email_template_arr['name'] = "rate_email_template" . time();
            $isTemporaryTemplate = true;
        }


        if (isset($headers['change_status'])) {
            unset($headers['change_status']);
        }
        $headers = implode(',', $headers);
        $new_email_template_arr['headers'] = $headers;
        if (strcmp($email_template, 'save_temporary'))
            $new_email_template_arr['id'] = 0;
        $this->loadModel('RateEmailTemplate');
        $flg = $this->RateEmailTemplate->save($new_email_template_arr);

        if ($flg === false) {
            $this->Rate->create_json_array('', 101, __('Failed', true));
            $this->Session->write('m', Rate::set_validator());
            if ($route_wizard_id) {
                $this->redirect('/routestrategys/wizard');
            }
            $this->redirect("/rates/rates_list");
        }
        if (!$send_type) {
//            保存client的email
            $client_info_save_arr = array();
            foreach ($post_data['client_info']['client_id'] as $key_item => $client_info_id) {
                $rate_email = $post_data['client_info']['rate_email'][$key_item];
                if ($rate_email) {
                    $client_info_save_arr[$client_info_id] = array(
                        'client_id' => $client_info_id,
                        'rate_email' => $rate_email
                    );
                }
            }
            sort($client_info_save_arr);
            $this->loadModel('Client');
            $this->Client->saveAll($client_info_save_arr);
        }
        // we have resend action
        // $is_temp = strcmp($email_template,'save_temporary') == 0;

        $email_template = $this->RateEmailTemplate->getLastInsertID();
        $format = $post_data['format'];
        $rate_table_id = $post_data['rate_table_id'];

        $RateSendLogArr = array(
            'rate_table_id' => $rate_table_id,
            'format' => $format,
            'zip' => $flg_zip,
            'status' => 1,
            'email_template_id' => $email_template,
            'create_time' => date('Y-m-d H:i:sO'),
            'start_effective_date' => $start_effective_date,
            'download_deadline' => $download_deadline,
            'download_method' => $download_method,
            'is_email_alert' => $is_email_alert,
            'is_disable' => $is_disable,
            'is_temp' => $isTemporaryTemplate,
            'headers' => $headers,
            'send_type' => $send_type,
            'send_specify_email' => $send_specify_email,
            'resource_ids' => implode(',', $resource_id_unique),
            'sent_area' => 1
        );
        $this->loadModel('RateSendLog');
        $insert_flg = $this->RateSendLog->save($RateSendLogArr);
        if ($insert_flg === false) {
            $this->Rate->create_json_array('', 101, __('Insert log failed!', true));
            $this->Session->write('m', Rate::set_validator());
            if ($route_wizard_id) {
                $this->redirect('/routestrategys/wizard');
            }
            $this->redirect("/rates/rates_list");
        }
        $log_id = $this->RateSendLog->getLastInsertID();
        // > /dev/null &
        $cmd = APP . "../cake/console/cake.php ratesend {$log_id} $download_method $extra_params $route_wizard_id > /dev/null &";
        $info = $this->Systemparam->find('first', array(
            'fields' => array('cmd_debug'),
        ));

        if (Configure::read('cmd.debug')) {
            file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
        }
        $res = shell_exec($cmd);
//        echo '<pre>';
//        die(var_dump($res));
        $this->Rate->create_json_array('', 201, "Your Rate Update has been delivered");
        $this->Session->write('m', Rate::set_validator());
        /*if($ereturn_url){
            $this->redirect(base64_decode($ereturn_url).'_send_rate_log');
        }*/
        $this->redirect("/rates/send_rate_log");
    }

    public function send_rate_log()
    {
        $this->loadModel('RateSendLog');

        $this->pageTitle = "Send Rate Log";
        $conditions = array();
        $order_arr = array('RateSendLog.id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr_orig = explode('-', $order_by);
            if (count($order_arr_orig) == 2) {
                $field = $order_arr_orig[0];
                $sort = $order_arr_orig[1];
                $order_arr = array($field => $sort);
            }
        }
        if ($this->isnotEmpty($this->params['url'], array('time_start')))
            $conditions[] = "RateSendLog.create_time > '{$this->params['url']['time_start']}'";
        if ($this->isnotEmpty($this->params['url'], array('time_start')))
            $conditions[] = "RateSendLog.create_time < '{$this->params['url']['time_end']}'";

        $this->paginate = array(
            'fields' => array('RateSendLog.create_time', 'RateSendLog.status', 'RateSendLog.send_specify_email', 'RateSendLog.error',
                'RateSendLog.id', 'RateTable.name', 'RateSendLog.file', 'RateSendLog.download_method', 'RateSendLog.resource_ids',
                'RateSendLog.completed_records', 'RateSendLog.total_records', 'RateSendLog.sent_area'
            ),
            'limit' => 100,
            'order' => $order_arr,
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = RateSendLog.rate_table_id'
                    ),
                )
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('RateSendLog');
        foreach ($this->data as &$item) {
            $item['RateSendLog']['emails_count'] = count(array_filter(explode(';', $item['RateSendLog']['send_specify_email'])));
            $item['RateSendLog']['recipient_count'] = $item['RateSendLog']['resource_ids'] ? count(array_filter(explode(',', $item['RateSendLog']['resource_ids']))) : $item['RateSendLog']['emails_count'];
            $item['RateSendLog']['sent_area'] = $this->Rate->getSentArea($item['RateSendLog']['sent_area']);
        }
        $status = array(
            '' => "Waiting",
            1 => 'In Progress',
            2 => 'completed',
            3 => 'failed',
            4 => 'Downloaded',
            5 => 'Not Yet Downloaded',
        );

        $this->set('status', $status);
        $this->set('get_data', $this->params['url']);
    }

    public function send_rate_log_detail($encode_send_log_id)
    {
        $this->loadModel('RateSendLogDetail');
        $send_log_id = base64_decode($encode_send_log_id);
        $order_arr = array('RateSendLogDetail.id' => 'desc');
        $this->paginate = array(
            'fields' => array('RateSendLogDetail.id', 'RateSendLogDetail.status', 'RateSendLogDetail.error',
                'Resource.alias', 'RateSendLogDetail.send_to'
            ),
            'limit' => 100,
            'order' => $order_arr,
            'joins' => array(
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Resource.resource_id = RateSendLogDetail.resource_id'
                    ),
                )
            ),
            'conditions' => array('RateSendLogDetail.log_id' => $send_log_id),
        );
        $this->data = $this->paginate('RateSendLogDetail');
    }

    public function rate_log_detail($log_id)
    {
        $this->loadModel('RateSendLogDetail');
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->RateSendLogDetail->find('all', array(
            'fields' => array('RateSendLogDetail.id', 'RateSendLogDetail.status', 'RateSendLogDetail.error',
                'Resource.alias', 'RateSendLogDetail.send_to', 'RateSendLogDetail.sent_on', 'Client.name',
                'RateSendLog.download_deadline', 'RateSendLog.download_method', 'RateSendLog.send_type'
            ),
            'limit' => 100,
            'joins' => array(
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Resource.resource_id = RateSendLogDetail.resource_id'
                    ),
                ),
                array(
                    'table' => 'client',
                    'alias' => 'Client',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Resource.client_id = Client.client_id'
                    ),
                ),
                array(
                    'table' => 'rate_send_log',
                    'alias' => 'RateSendLog',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateSendLog.id = RateSendLogDetail.log_id'
                    ),
                ),
            ),
            'conditions' => array('RateSendLogDetail.log_id' => $log_id),
        ));

        foreach ($data as $key => $item) {
            $data[$key]['RateSendLogDetail']['orig_status'] = $item['RateSendLogDetail']['status'];
            $data[$key]['RateSendLogDetail']['status'] = $this->RateSendLogDetail->get_status($item['RateSendLogDetail']['status']);
        }

        if ($data)
            echo json_encode($data);
        else
            echo '';
    }

    public function resend_email()
    {
        $this->loadModel('RateSendLogDetail');
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = $this->params['form']['id'];
        $id = intval($id);
        if (!$id) {
            $this->jsonResponse(['status' => false, 'msg' => __('Email Sent Failed!', true)]);
        }

        $data = $this->RateSendLogDetail->find('first', array(
            'fields' => array('RateSendLogDetail.log_id', 'RateSendLog.download_method',
            ),
            'joins' => array(
                array(
                    'table' => 'rate_send_log',
                    'alias' => 'RateSendLog',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateSendLog.id = RateSendLogDetail.log_id'
                    ),
                ),
            ),
            'conditions' => array('RateSendLogDetail.id' => $id),
        ));

        $download_method = $data['RateSendLog']['download_method'];
        $sent_log_id = $data['RateSendLogDetail']['log_id'];
        $cmd = APP . "../cake/console/cake.php ratesend {$sent_log_id} $download_method null null {$id}   > /dev/null &";
        shell_exec($cmd);

        $this->jsonResponse(['status' => true, 'msg' => __('Email Sent Successfully!', true)]);
    }

    public function download_send_rate_file()
    {
        $this->autoRender = false;
        $this->autoLayout = false;

        if (!isset($this->params['url']['flg'])) {
            $this->Rate->create_json_array('', 101, __('The id is not exsit!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect("/rates/send_rate_log");
        }
        $log_id = base64_decode($this->params['url']['flg']);

        if (isset($this->params['url']['download'])) {
            $sql = "SELECT file_path FROM rate_download_log WHERE id = {$log_id}";
            $file_data = $this->Rate->query($sql);
            $file = isset($file_data[0][0]['file_path']) ? $file_data[0][0]['file_path'] : false;
            if (false && !file_exists($file)) {
                $this->Rate->create_json_array('', 101, __('The file is not exist!', true));
                $this->Session->write('m', Rate::set_validator());
                $this->redirect("rate_download_log");
            }
        } else {
            $sql = "SELECT file FROM rate_send_log WHERE id = {$log_id}";
            $file_data = $this->Rate->query($sql, false);
            $file = isset($file_data[0][0]['file']) ? $file_data[0][0]['file'] : false;
            if ($file && !file_exists($file)) {
                $sql = "UPDATE rate_send_log SET file = '' WHERE id = {$log_id}";
                $this->Rate->query($sql, false);
                $this->Rate->create_json_array('', 101, __('The file is not exsit!', true));
                $this->Session->write('m', Rate::set_validator());
                $this->redirect("/rates/send_rate_log");
            }
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }

//    处理rate表的数据
    function _do_del_rollback($data_arr, $flg = "")
    {
        $rollback_sql = "";
        foreach ($data_arr as $item) {
            $filed_arr = array();
            $value_arr = array();
            $old_data_arr = $item['RateTable'];
            unset($old_data_arr['rate_id']);
            if (!$flg) {
                $old_data_arr['rate_table_id'] = "{rate_table_id}";
            }
            foreach ($old_data_arr as $key => $value) {
                if ($value) {
                    $filed_arr[] = $key;
                    $str_arr = array(
                        'code', 'effective_date', 'end_date', 'code_name', 'country', 'zone', 'ocn', 'lata'
                    );
                    if (in_array($key, $str_arr)) {
                        $value_arr[] = "'" . $value . "'";
                    } else {
                        $value_arr[] = $value;
                    }
                }
            }
            $filed_str = implode(',', $filed_arr);
            $value_str = "(" . implode(',', $value_arr) . ")";
            $rollback_sql .= "INSERT INTO rate ({$filed_str}) VALUES {$value_str};";
        }

        if (empty($value_arr)) {
            return "";
        }
        return $rollback_sql;
    }

    //assign
    public function assign_rate_table($encode_rate_table_id)
    {
        //所有组合
        $rate_table_id = base64_decode($encode_rate_table_id);
        if (!strcmp($encode_rate_table_id, intval($encode_rate_table_id)))
            $rate_table_id = $encode_rate_table_id;
        $sql = "select client.name ,resource.alias, resource.resource_id,resource_prefix.tech_prefix,resource_prefix.id from resource_prefix
                LEFT JOIN resource on resource.resource_id = resource_prefix.resource_id LEFT JOIN client ON resource.client_id = client.client_id
                WHERE ingress=true  and is_virtual is not true and trunk_type2 = 0";
        $rst = $this->Rate->query($sql);


        $this->pageTitle = 'Assign Rate Deck';

        $conditions = array(
            'ingress=true  and is_virtual is not true and trunk_type2 = 0'
        );

        if (isset($_GET['client_id']) && !empty($_GET['client_id'])) {
            $this->set("client_id", $_GET["client_id"]);
            $conditions["client.client_id"] = $_GET["client_id"];
        }

        if (isset($_GET['resource_id']) && !empty($_GET['resource_id'])) {
            $conditions["resource.resource_id"] = $_GET['resource_id'];
        }

        $order_arr = array('client.name' => 'asc', "resource.alias" => 'asc');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $page = $this->_get('size', 100);
        $this->paginate = array(
            'fields' => array(
                "client.name", "client.client_id", "resource.alias", "resource.resource_id", "ResourcePrefix.tech_prefix", "ResourcePrefix.id"
            ),
            'limit' => $page,
            'joins' => array(
                array(
                    'table' => 'resource',
                    'type' => 'left',
                    'conditions' => array(
                        'resource.resource_id = ResourcePrefix.resource_id'
                    ),
                ),
                array(
                    'table' => 'client',
                    'type' => 'left',
                    'conditions' => array(
                        'resource.client_id = client.client_id'
                    ),
                )
            ),
            'order' => $order_arr,
            'conditions' => $conditions
        );
        $this->loadModel('ResourcePrefix');
        $this->data = $this->paginate('ResourcePrefix');

        //已有
        $sql = "select rp.id,rt.name from resource_prefix rp left join rate_table rt on rp.rate_table_id = rt.rate_table_id WHERE rp.rate_table_id = $rate_table_id";
        $rst = $this->Rate->query($sql);
        $selected = array();
        foreach ($rst as $item) {
            $selected[] = $item[0]['id'];
        }
        $this->set('current_rate_table', isset($rst[0][0]["name"]) ? $rst[0][0]["name"] : '');
        $this->set('selected', $selected);

    }

    public function assign_selected()
    {


        $return_url = isset($_GET['rate_table_id']) ? "/rates/assign_rate_table/{$_GET['rate_table_id']}?" : '/rates/rates_list?';
        $return_url .= isset($_GET['carrier_name']) ? "carrier_name={$_GET['carrier_name']}&" : '';
        $return_url .= isset($_GET['ingress_name']) ? "ingress_name={$_GET['ingress_name']}" : '';

        if (empty($_GET['rate_table_id'])) {
            $this->Rate->create_json_array('', 101, __('Fail!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->Session->write('mm', 2);
            $this->redirect($return_url);
        }

        $ids = trim($_GET['ids']);
        if (empty($ids)) {
            $this->Rate->create_json_array('', 101, __('Please Select The Items!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->Session->write('mm', 2);
            $this->redirect($return_url);
        }

        $rate_table_id = base64_decode($_GET['rate_table_id']);

        $sql = "update resource_prefix set rate_table_id = {$rate_table_id}  where id in ($ids)";
        $rst = $this->Rate->query($sql);
        if ($rst === false) {
            $this->Rate->create_json_array('', 101, __('Fail!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->Session->write('mm', 2);
            $this->redirect($return_url);
        }
        $this->Rate->create_json_array('', 201, __('Success!', true));
        $this->Session->write('m', Rate::set_validator());
        $this->Session->write('mm', 2);
        $this->redirect($return_url);


    }

    public function rate_download_log()
    {
        $this->loadModel('RateDownloadLog');
        $this->pageTitle = "Rate Download Log";
        $conditions = array();
        $order_arr = array('RateDownloadLog.id' => 'desc');
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr_orig = explode('-', $order_by);
            if (count($order_arr_orig) == 2) {
                $field = $order_arr_orig[0];
                $sort = $order_arr_orig[1];
                $order_arr = array($field => $sort);
            }
        }
        if ($this->isnotEmpty($this->params['url'], array('time_start')))
            $conditions[] = "RateDownloadLog.download_time > '{$this->params['url']['time_start']}'";
        if ($this->isnotEmpty($this->params['url'], array('time_start')))
            $conditions[] = "RateDownloadLog.download_time < '{$this->params['url']['time_end']}'";

        $this->paginate = array(
            'fields' => array('RateDownloadLog.download_time', 'RateDownloadLog.download_ip', 'RateDownloadLog.file_path',
                'Client.name', 'RateDownloadLog.id', 'Resource.alias', 'RateTable.name'
            ),
            'limit' => 100,
            'order' => $order_arr,
            'joins' => array(
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'inner',
                    'conditions' => array(
                        'Resource.resource_id = RateDownloadLog.resource_id'
                    ),
                ),
                array(
                    'table' => 'client',
                    'alias' => 'Client',
                    'type' => 'inner',
                    'conditions' => array(
                        'Resource.client_id = Client.client_id'
                    ),
                ), array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'left',
                    'conditions' => array(
                        'Resource.rate_table_id = RateTable.rate_table_id'
                    ),
                ),
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('RateDownloadLog');
    }

    public function getFileDownloads()
    {
        Configure::write('debug', 0);

        if ($this->RequestHandler->isPost()) {
            $detailId = $_POST['detailId'];
            $logs = $this->Rate->query("SELECT rate_send_log_detail.download_date, rate_download_log.download_ip FROM rate_send_log_detail LEFT JOIN rate_download_log ON rate_download_log.log_detail_id=rate_send_log_detail.log_id WHERE rate_send_log_detail.id = {$detailId}");
            $resultArray = array();
            foreach ($logs as $log) {
                array_push($resultArray, array(
                    'ip' => $log[0]['download_ip'],
                    'time' => $log[0]['download_date']
                ));
            }

            echo json_encode($resultArray);
        }
        exit;
    }

    public function getCodeData()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->loadModel('Codedeck');
        $code = $this->params['pass'][0];
        $codeDeck = $this->params['pass'][1];

        if (!$code || !$codeDeck) {
            $this->jsonRespnse(['status' => false]);
        }
        $data = $this->Codedeck->query("select country,name from code where code_deck_id = '{$codeDeck}' and code = '{$code}'");
        $this->jsonResponse(['status' => true, 'data' => !empty($data) ? $data[0][0] : '']);

    }

}
