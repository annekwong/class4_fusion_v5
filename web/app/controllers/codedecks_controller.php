<?php

class CodedecksController extends AppController {

    var $name = 'Codedecks';
    var $use = array('Codedeck', 'code');
    var $helper = array('javascript', 'html');

    function index() {
        $this->redirect('codedeck_list');
    }

    public function ajax_check_upload() {
        Configure::write('debug', 0);
        $id = $this->params['pass'][0];
        $this->Codedeck->query("select * from  upload_code($id,'tmp_code');");
    }

    public function check_uploadcodes() {
        $code_deck_id = $this->params['pass'][0];
        $code_name = $this->params['pass'][1];
        $this->set("code_name", $code_name);
        $this->set("code_deck_id", $code_deck_id);
        $this->set("mydata", $this->Codedeck->query("select code,city,state,country   from  tmp_code    limit 10 "));
    }

//上传成功 记录上传
    public function upload_code2() {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['upload_table_id'];
        $code_name = $_POST['code_name'];
        $list = $this->Codedeck->import_data("Upload Code"); //上传数据
        $this->Codedeck->create_json_array("", 201, __('succeeded', true));
        $this->Session->write('m', Codedeck::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    //上传code	
    public function import_code() {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $this->params['pass'][0];
        $list = $this->Codedeck->query("select name   from  code_deck where   code_deck_id=$code_deck_id ");
        $this->set("code_name", $list[0][0]['name']);
        $this->set("code_deck_id", $code_deck_id);
    }

    //code	
    public function download() {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $this->params['pass'][0];
        $list = $this->Codedeck->query("select name   from  code_deck where   code_deck_id=$code_deck_id ");
        $this->set("code_name", $list[0][0]['name']);
        $this->set("code_deck_id", $code_deck_id);
    }

    /*
     * 下载号码数据
     */

    public function download_code() {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['id_code_decks'];
        $download_sql = "select      code   ,     name ,country    from  code  where code_deck_id=$code_deck_id";
        $this->Codedeck->export__sql_data('download code', $download_sql, 'code');
        Configure::write('debug', 0);
        $this->layout = '';
    }

    public function js_save($id=null) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!empty($id)) {
            $this->_render_js_save_data($id);
        }
    }

    function _render_js_save_data($id=null) {
        $sql = "select name  from code_deck where code_deck_id=$id";
        $this->data = $this->Codedeck->query($sql);
    }

//读取该模块的执行和修改权限
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
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

    public function codedeck_list() {
        $this->pageTitle = "Switch/Code Deck ";
        $order = $this->_order_condtions(Array('code_deck_id', 'name', 'codes', 'usage'));
        $currPage = 1;
        $pageSize = 100;
        $search = null;
//		if (! empty ( $_REQUEST ['page'] )) {
//			$currPage = $_REQUEST ['page'];
//		}
//		if (! empty ( $_REQUEST ['size'] )) {
//			$pageSize = $_REQUEST ['size'];
//		}
        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (!empty($_REQUEST['edit_id'])) {
            $sql = "select code_deck_id,name,update_at,update_by,
									(
									 select count(code_id) from code where code_deck_id = code_deck.code_deck_id
									) as codes,
									(
									 select count(rate_table_id) from rate_table where code_deck_id = code_deck.code_deck_id
									) as usage
							from code_deck where code_deck_id = {$_REQUEST['edit_id']} ";
            $result = $this->Codedeck->query($sql);
            //分页信息
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        } else {
            $results = $this->Codedeck->getAllCodedecks($currPage, $pageSize, $search, $order);
        }
        $this->set('p', $results);
    }

    /*
     * 添加号码组和修改号码组时的数据验证
     */

    private function validate_codedeck($name, $needcheckrepeat, $flag) {
        $hasError = false;
        $errorInputId = "cname";

        if ($flag == 'editcodedeck')
            $errorInputId .= "_e";
        if ($needcheckrepeat == true) {
            $qs = $this->Codedeck->query("select code_deck_id from code_deck where name = '$name'");
            if (count($qs) > 0) {
                $hasError = true;
                $this->Codedeck->create_json_array("#$errorInputId", 101, __('code_deck_name_exists', true));
                $this->Session->write('backform', array($name));
                $this->Session->write('m', Codedeck::set_validator());
            }
        }
        if ($hasError == true) {
            $this->Session->write('add_or_edit_codedeck', $flag);
        }
        return $hasError;
    }

    /*
     * 添加号码组
     */

    public function add_codedeck($code=null) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 2);
        $name = $this->data['Codedeck']['name'];
        $code = $name;
        //数据验证
        $hasError = $this->validate_codedeck($name, true, 'addcodedeck');
        if ($hasError == false) {
            $code_deck_id = $this->Codedeck->addCodedeck($name);
            if (empty($code_deck_id)) {
                $this->Codedeck->create_json_array("#CodedeckName", 101, __('Fail to create Code Deck.', true));
            } else {
                $this->Codedeck->create_json_array('', 201, __('The Code Deck [%s] is created successfully.', true, $name));
                $this->Session->write('m', Codedeck::set_validator());
                $this->redirect('/codedecks/codes_list/'.base64_encode($code_deck_id));
            }
        }
        $this->Session->write('m', Codedeck::set_validator());
        $this->redirect('/codedecks/codedeck_list');
    }

    /*
     * 修改号码组
     */

    public function edit_codedeck($id=null) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        $name = $this->data['Codedeck']['name'];
        $qs = $this->Codedeck->query("select name from code_deck where code_deck_id='$id'");

        //是否需要检查名字是否存在
        if ($name == $qs[0][0]['name']) {
            //数据验证
            $hasError = $this->validate_codedeck($name, false, 'editcodedeck');
        } else {
            //数据验证
            $hasError = $this->validate_codedeck($name, true, 'editcodedeck');
        }


        if ($hasError == false) {
            if (!$this->Codedeck->updateCodedeck($name, $id)) {
                $this->Codedeck->create_json_array("#CodedeckName", 101, __('edit_code_deck_failed', true));
            } else {
                $this->Codedeck->create_json_array('', 201, __('The Code deck[%s] is modified successfully.', true, $name));
            }
        } else {
            $this->Session->write('code_deck_id_e', $id);
        }
        $this->Session->write('m', Codedeck::set_validator());
       // $this->redirect('/codedecks/codedeck_list?edit_id=' . $id);
        $this->redirect('/codedecks/codedeck_list');
    }

    /*
     * 删除号码组
     */

    public function del_code_deck($id) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        if (!empty($id)) {
            $tip = '';
            switch ($id) {
                case 'all':
                    $tip = ' ';
                    break;
                case 'selected':
                    $arrName = $this->Codedeck->getNameByID($_REQUEST['ids']);
                    foreach($arrName as $name){
                        $tip.= $name[0]['name'].",";
                    }
                    $tip = "[".substr($tip,0,-1)."]";
                    break;
                default:
                    $this->_render_js_save_data($id);
                    $tip = "[".$this->data[0][0]['name']."] ";
            }

            if ($id == 'all') {
                $id = "select code_deck_id from code_deck where code_deck_id != 1";
                $reseller_id = $this->Session->read("sst_reseller_id");
                if (!empty($reseller_id)) {
                    $id .= " and reseller_id = $reseller_id";
                }
            }

            if ($id == 'selected') {
                $id = $_REQUEST['ids'];
            }
            $product_used = $this->Codedeck->query("select count(*) as sum from product where code_deck_id =$id ");
            if($product_used[0][0]['sum'])
            {
                $this->Codedeck->create_json_array('', 101, __('%s can_not_be_delete_because_be_used',true,array(__('code_deck',true))));
                $this->Session->write('m', Codedeck::set_validator());
                $this->redirect('/codedecks/codedeck_list');
            }
            $qs = $this->Codedeck->query("select rate_table_id from rate_table where code_deck_id in( $id)");
            if (count($qs) > 0) {
                $this->Codedeck->create_json_array('', 101, __('%s can_not_be_delete_because_be_used',true,array(__('code_deck',true))));
            } else {
                if ($this->Codedeck->del($id)) {
                    
                    $this->Codedeck->create_json_array('', 201, __('The Code Deck %s is deleted successfully!', true, $tip));
                } else {
                    $this->Codedeck->create_json_array('', 101, __('Fail to delete Code Deck.', true));
                }
            }
            $this->Session->write('m', Codedeck::set_validator());
        }
        $this->redirect('/codedecks/codedeck_list');
    }

    public function ss_codename_term() {
        $this->layout = '';
        $this->set('p', $this->Codedeck->getAllCodesByCodeDeck(''));
    }

    public function ss_code() {
        $this->layout = '';
        $this->set('p', $this->Codedeck->getAllCodesByCodeDeck(''));
    }

    public function ss_codename() {
        $this->layout = 'ajax';
        $code_deck_id = $this->_get('id');
        $this->set('p', $this->Codedeck->getAllCodesByCodeDeck($code_deck_id));
    }

    public function ss_codename_all() {
        $this->layout = 'ajax';
        $code_deck_id = $this->_get('id');
        $this->set('p', $this->Codedeck->getAllCodesByCodeDeck($code_deck_id));
    }

    public function ss_country_all() {
        $this->layout = 'ajax';
        $code_deck_id = $this->_get('id');
        $this->set('p', $this->Codedeck->getAllCodesByCodeDeck($code_deck_id));
    }

    public function ss_country() {
        $this->layout = '';
        $this->set('p', $this->Codedeck->getAllCodesByCodeDeck(''));
    }

    public function ss_country_term() {
        $this->layout = '';
        $this->set('p', $this->Codedeck->getAllCodesByCodeDeck(''));
    }

    public function codes_list() {
        $this->pageTitle = "Code Deck List";
        $code_deck_id = null;
        if (empty($this->params['pass'][0])) {
            $code_deck_id = '';
            $this->layout = 'pop';
        } else {
            $code_deck_id = base64_decode($this->params['pass'][0]);
        }
        $this->set('code_deck_id', $code_deck_id);
        $this->set('code_name', $this->Codedeck->select_code_name($code_deck_id));
        $this->set('p', $this->Codedeck->getAllCodesByCodeDeck($code_deck_id));
    }

    public function getCodeData ($code) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $result = [];

        if ($code) {
            $this->loadModel('Code');

            $result = $this->Code->find('all', array(
                'fields' => array('country', 'name'),
                'conditions' => array(
                    'code' => $code
                )
            ));
        }
        echo json_encode($result);
        exit;
    }

    /*
     * 添加或修改号码时的数据验证
     */

    private function validate_code($f, $needcheckrepeat, $url) {
        $code = $f['code'];
        $hasError = false;
        $code_deck_id = $f['code_deck_id'];
        if ($code == '' || strlen($code) == 0) {
            $hasError = true;
            $this->Codedeck->create_json_array("#code", 101, __('code_null_error', true));
        }
        if (!preg_match('/^\d+$/', $code)) {
            $hasError = true;
            $this->Codedeck->create_json_array("#code", 101, 'code  field must be  digits');
        }

        $name = $f['name'];
        if (empty($name)) {
            $hasError = true;
            $this->Codedeck->create_json_array("#name", 101, __('code_name_null_error', true));
        }

        $qs = $this->Codedeck->query("select count(prefix_range('$code'::prefix_range)) as c");
        if ($qs[0][0]['c'] != 1) {
            $hasError = true;
            $this->Codedeck->create_json_array("#code", 101, __('code_format_error', true));
        }

        if ($needcheckrepeat == true) {
            $qs1 = $this->Codedeck->query("select code_id from code where code = '$code' ");
            if (count($qs1) > 0) {
                $hasError = true;
                $this->Codedeck->create_json_array("#code", 101, __('code_exists', true));
            }
        }

        if ($hasError == true) {
            $this->Session->write('m', Codedeck::set_validator());
            $this->Session->write('backform', $f);
            $this->redirect($url);
        }
    }

    function js_add_code($code_deck_id) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $this->set('country', $this->Codedeck->find_country());
        $this->loadModel('Code');
        $list = $this->Codedeck->query("select name   from  code_deck where   code_deck_id=$code_deck_id ");
        $this->set("code_name", $list[0][0]['name']);
        $this->set("code_deck_id", $code_deck_id);
        $_SESSION['code_deck_id'] = $code_deck_id;
        $this->set('id', $code_deck_id);
    }

    /*
     * 添加号码
     */

    public function add_code($code_deck_id) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        $this->loadModel('Code');
        if (!empty($this->params['form'])) {
            $f = $this->params['form'];
            $code_deck_id = !empty($f['code_deck_id']) ? $f['code_deck_id'] : $_SESSION['code_deck_id'];
            $this->validate_code($f, true, '/codedecks/codes_list/' . base64_encode($code_deck_id));
            $country = $f['country'];
            if (strpos($country, "选择") != false) {
                $f['country'] = "";
            }
            if (empty($f['code_id'])) {
                unset($f['code_id']);
            }
            $f['code'] = "'" . $f['code'] . "'";
            $f['name'] = "'" . $f['name'] . "'";
            $f['country'] = "'" . $f['country'] . "'";
            if (!$this->Code->query("insert into code (" . implode(", ", array_keys($f)) . ") values (" . implode(", ", $f) . ")")) {
                //if (!$this->Code->save($f)) {
//				$this->Session->write('backform',$f);
//				$this->Code->create_json_array("#",101,__('add_code_failed',true));
//				$this->Session->write('m',Code::set_validator());
//				$this->redirect('/codedecks/codes_list/'.$code_deck_id);			
//			} else {
                $this->Code->create_json_array("", 201, __('add_code_success', true));
                $this->Session->write('m', Code::set_validator());
                $this->redirect('/codedecks/codes_list/' . base64_encode($code_deck_id));
            }
        } else {
            $list = $this->Codedeck->query("select name   from  code_deck where   code_deck_id=$code_deck_id ");
            $this->set("code_name", $list[0][0]['name']);
            $this->set("code_deck_id", $code_deck_id);
            $_SESSION['code_deck_id'] = $code_deck_id;
            $this->set('id', $code_deck_id);
            $this->set('counrty_list', $this->Codedeck->query());
        }
    }

    /*
     * 修改号码
     */

    public function edit_code($ownid) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        $this->set('country', $this->Codedeck->find_country());
        $this->loadModel('Code');
        if (!empty($this->params['form'])) {
            $f = $this->params['form'];
            $code_deck_id = $f['code_deck_id'];
            $ownid = $f['code_id'];
            //是否需要验证重复
            $tmpCode = $this->Codedeck->getCodeById($ownid);
            if ($tmpCode[0][0]['code'] == $f['code']) {
                $this->validate_code($f, false, '/codedecks/codes_list/' . base64_encode($code_deck_id));
            } else {
                $this->validate_code($f, true, '/codedecks/codes_list/' . base64_encode($code_deck_id));
            }
            $country = $f['country'];
            if (strpos($country, "选择") != false) {
                $f['country'] = "";
            }
            $f['code'] = "'" . $f['code'] . "'";
            $f['name'] = "'" . $f['name'] . "'";
            $f['country'] = "'" . $f['country'] . "'";
            $sql_set = array();
            foreach ($f as $k => $v) {
                $sql_set[] = $k . " = " . $v;
            }
            $code_results = $this->Code->query("select code,code_deck_id from code where code_id = {$f['code_id']}");

            if ($code_results[0][0]['code_deck_id'] && $code_results[0][0]['code'] != $f['code_id']) {
                $sql = "delete from rate where rate_table_id in (select rate_table_id from rate_table where code_deck_id = '$code_results[0][0]['code_deck_id']') and code::text like '{$code_results[0][0]['code']}%'";
                $this->Code->query($sql);
            }
            $sql = "update code set " . implode(", ", $sql_set) . " where code_id = " . intval($f['code_id']);
            //if (!$this->Code->save($f)) {
            if (!$this->Code->query($sql)) {
//				$this->Session->write('backform',$f);
//				$this->Code->create_json_array("#",101,__('edit_code_failed',true));
//				$this->Session->write('m',Code::set_validator());
//				$this->redirect('/codedecks/edit_code/'.$ownid);			
//			} else {
                $this->Code->create_json_array("", 201, __('Succeeded', true));
                $this->Session->write('m', Code::set_validator());
                $this->redirect('/codedecks/codes_list/' . base64_encode($code_deck_id) . '?edit_id=' . $ownid);
            }
        } else {
            $this->set('code', $this->Codedeck->getCodeById($ownid));
        }
    }

    /*
     * 删除号码
     */

    public function del_code($ownid, $code_deck_id) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        
        $this->loadModel('Code');
        if (!empty($ownid)) {

            if ($ownid == 'all') {
                $code_resutls = $this->Code->query("select code from code where code_deck_id = {$code_deck_id}");
                foreach ($code_resutls as $code) {
                    $sql = "delete from rate where rate_table_id in (select rate_table_id from rate_table where code_deck_id = '{$code_deck_id}') and code::text like '{$code[0]['code']}%'";
                    $this->Code->query($sql);
                }
                $this->Code->query("delete from  code  where code_deck_id=$code_deck_id");
                $this->Session->write('m', Code::set_validator());
                $this->redirect('/codedecks/codes_list/' . base64_encode($code_deck_id));
            }

            if ($ownid == 'selected') {
                $id = $_REQUEST['ids'];
                $code_resutls = $this->Code->query("select code from code where code_id in ({$id})");
                foreach ($code_resutls as $code) {
                    $sql = "delete from rate where rate_table_id in (select rate_table_id from rate_table where code_deck_id = '{$code_deck_id}') and code::text like '{$code[0]['code']}%'";
                    $this->Code->query($sql);
                }
                $this->Code->query("delete from code  where code_id in ($id)");
                $this->Code->create_json_array('', 201, __('Deleted successfully', true));
                $this->redirect('/codedecks/codes_list/' . base64_encode($code_deck_id));
            }

            $code_results = $this->Code->query("select code,code_deck_id from code where code_id = {$ownid}");

            $sql = "delete from rate where rate_table_id in (select rate_table_id from rate_table where code_deck_id = {{$code_results[0][0]['code_deck_id']}}) and code::text like '{$code_results[0][0]['code']}%'";

            $this->Code->query($sql);

            if ($this->Code->del($ownid)) {
                $this->Code->create_json_array('', 201, __('The Code is deleted successfully!', true));
            } else {
                $this->Code->create_json_array('', 101, __('Deleted unsuccessfully', true));
            }
            $this->Session->write('m', Code::set_validator());
        }
        $this->redirect('/codedecks/codes_list/' . base64_encode($code_deck_id));
    }

    /*
     * 将上传文件中的数据插入到数据库
     */

    private function insert($code, $country, $state, $city, $code_deck_id) {
        $sql = "insert into code (code,country,state,city,code_deck_id)
							values ('$code','$country','$state','$city','$code_deck_id')";

        return $this->Codedeck->query($sql);
    }

    /*
     * 上传号码
     */

    public function upload_code($code_deck_id) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_x']) {
            $this->redirect_denied();
        }
        $query_result_sets = 0; //执行结果

        $r = $this->Codedeck->getUploadData('browse');

        $action = $this->params ['form'] ['handleStyle']; //Overwrite  Remove  Refresh


        $isroll = $this->params ['form'] ['isRoll']; //是否需要Rollback

        $this->Codedeck->begin(); //开启事务

        $code_ids = "";
        if ($action == 3) {
            //原来的路由
            $old_codes = $this->Codedeck->query("select code_id from code where code_deck_id = '$code_deck_id'");
            $loop = count($old_codes);
            for ($i = 0; $i < $loop; $i++) {
                $code_ids .= "'" . $old_codes [$i] [0] ['code_id'] . "',";
            }
            $code_ids = substr($code_ids, 0, strlen($code_ids) - 1);

            $qs = $this->Codedeck->query("delete from code where code_id in ($code_ids)");

            if (count($qs) != 0) {
                $query_result_sets++;
            }
        }

        $each_count = 1; //第几行
        $upload_error = "["; //错误信息字符串
        $hasError = false; //是否有格式错误
        $affectRows = count($r);

        //需要验证的字段
        //从国际化文件读取  方便前台处理错误信息显示时做国际化
        $c = __('code', true);
        $ct = __('city', true);
        $sta = __('state', true);
        $ctry = __('country', true);

        foreach ($r as $d) {
            $errorFlag = false;
            $code = $d->code; //名称
            //文件中的code为空、含有不合法字符、已经存在的情况
            //记录错误信息
            if (empty($code)) {
                $hasError = true;
                $errorFlag = true;
                $msg = __('code_null_error', true);
                $upload_error .= "{row:$each_count,name:'$c',msg:'$msg'},";
            }

            $qs = $this->Codedeck->query("select count(prefix_range('$code'::prefix_range)) as c");
            if ($qs[0][0]['c'] != 1) {
                $hasError = true;
                $errorFlag = true;
                $msg = __('code_format_error', true);
                $upload_error .= "{row:$each_count,name:'$c',msg:'$msg'},";
            }

            //查询是否已经存在该号码的号码
            $as = $this->Codedeck->query("select code_id from code where code = '$code' and code_deck_id = '$code_deck_id'");
            if (count($as) > 0) {
                $hasError = true;
                $errorFlag = true;
                $msg = __('exists', true);
                $upload_error .= "{row:$each_count,name:'$c',msg:'$msg'},";
            }

            $country = $d->country;

            if (empty($country)) {
                $country = 'null';
            }
//			if (!empty($country)) {
//				if (!preg_match('/^[a-zA-Zu4E00-u9FA5]+$/',$country)) {
//					$hasError = true;
//					$errorFlag = true;
//					$msg = __('invalidformat',true);
//					$upload_error .= "{row:$each_count,name:'$ctry',msg:'$msg'},";
//				}
//			}

            $city = $d->city;
            if (empty($city)) {
                $city = 'null';
            }
//			if (!empty($country)) {
//				if (!preg_match('/^[a-zA-Zu4E00-u9FA5]+$/',$city)) {
//					$hasError = true;
//					$errorFlag = true;
//					$msg = __('invalidformat',true);
//					$upload_error .= "{row:$each_count,name:'$ct',msg:'$msg'},";
//				}
//			}

            $state = $d->state;
            if (empty($state)) {
                $state = 'null';
            }
//			if (!empty($country)) {
//				if (!preg_match('/^[a-zA-Zu4E00-u9FA5]+$/',$state)) {
//					$hasError = true;
//					$errorFlag = true;
//					$msg = __('invalidformat',true);
//					$upload_error .= "{row:$each_count,name:'$sta',msg:'$msg'},";
//				}
//			}
            //有错误继续验证下一行
            if ($errorFlag == true) {
                $affectRows--;
            }


            //插入数据
            switch ($action) {
                case 1 : //Overwrite
                    $isexists = $this->Codedeck->query("
																					select code_id from code
																					where code = '$code' and code_deck_id = '$code_deck_id'
																				");
                    if (count($isexists) > 0) {
                        $code_id = $isexists [0] [0] ['code_id'];
                        $qs = $this->Codedeck->query("update code 
																										set country='$country',state='$state',city='$city'
																										where code_id = $code_id");
                        //记录是否出错
                        if (count($qs) != 0) {
                            $query_result_sets++;
                        }
                    } else {
                        $qs = $this->insert($code, $country, $state, $city, $code_deck_id);
                        //记录是否有错误	
                        if (count($qs) != 0) {
                            $query_result_sets++;
                        }
                    }
                    break;

                case 2 : //Remove
                    $isexists = $this->Codedeck->query("
																					select code_id from code 
																					where code = '$code' and code_deck_id = '$code_deck_id'
																				");
                    if (count($isexists) > 0) {
                        continue;
                    } else {
                        $qs = $this->insert($code, $country, $state, $city, $code_deck_id);
                        //记录是否有错误	
                        if (count($qs) != 0) {
                            $query_result_sets++;
                        }
                    }
                    break;

                case 3 : //Refresh
                    $qs = $this->insert($code, $country, $state, $city, $code_deck_id);
                    //记录是否有错误	
                    if (count($qs) != 0) {
                        $query_result_sets++;
                    }
                    break;
            }

            $each_count++;
        }

        $this->Session->write('upload_commited_rows', $affectRows);

        //有行数据格式错误  返回错误信息
        if ($hasError == true) {
            $upload_error = substr($upload_error, 0, strlen($upload_error) - 1) . "]";
            $this->Session->write("upload_route_error", $upload_error);

            if ($isroll == 'true') { //需要回滚
                $this->Codedeck->rollback();
                $this->Session->write('upload_commited_rows', 0);
            } else { //忽略错误提交
                $this->Codedeck->commit();
            }

            $this->redirect('/codedecks/codes_list/' . base64_encode($code_deck_id));
            exit();
        } else {
            $this->Codedeck->commit();
        }

        $this->redirect('/codedecks/codes_list/' . base64_encode($code_deck_id));
    }

    public function view_rb($code_deck_id, $codeck_name) {
        if (!empty($code_deck_id)) {
            $sql = "select name from code_deck where code_deck_id =$code_deck_id";
            $name = $this->Codedeck->query($sql);
            if (empty($name[0][0]['name'])) {
                $this->set('name', '');
            } else {
                $this->set('name', $name[0][0]['name']);
            }
        }


        $currPage = 1;
        $pageSize = 100;
        $search = null;
        if (!empty($_REQUEST ['page']))
            $currPage = $_REQUEST ['page'];
        if (!empty($_REQUEST ['size']))
            $pageSize = $_REQUEST ['size'];
        $results = $this->Codedeck->view_ratetables($currPage, $pageSize, $code_deck_id);
        $this->set('p', $results);
        $this->set('n', $codeck_name);
    }

    function ajax_options() {
        Configure::write('debug', 0);
        $this->layout = '';
        $this->autoRender = false;
        $type = array_keys_value($this->params, 'url.type');
        $term = array_keys_value($this->params, 'url.term');
        $values = Array();
        if (in_array($type, array('code', 'code_name', 'country'))) {
            $method = "find_auto_$type";
            $this->loadModel('Code');
            $t = $this->Code->{$method}($term);
            foreach ($t as $tv) {
                $ttv = array_keys_value($tv, "0.$type");
                if (!empty($ttv)) {
                    $values[] = $ttv;
                }
            }
        }
        echo json_encode($values);
    }

    function ajax_options_ext() {
        Configure::write('debug', 0);
        $this->layout = '';
        $this->autoRender = false;
        $type = array_keys_value($this->params, 'url.type');
        $term = array_keys_value($this->params, 'url.term');
        $values = Array();

        $method = "find_auto_$type";
        $this->loadModel('Code');
        $t = $this->Code->find_code_country($term);
        foreach ($t as $tv) {
            $ttv = array_keys_value($tv, "0.$type");
            if (!empty($ttv)) {
                $values[] = $ttv;
            }
        }


        echo json_encode($values);
    }

    function add_code_country() {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        $this->Codedeck->addCountry($_GET['country']);
        $this->redirect("/codedecks/codedeck_list");
    }

    function js_code_save($ownid=null) {
        if (!$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        if (!empty($ownid)) {
            $this->set('code', $this->Codedeck->getCodeById($ownid));
        } else {
            $code = Array();
            $code[0][0]['code_deck_id'] = $this->_get('id');
            $this->set('code', $code);
        }
    }

    function find_codename($codename=null) {
        $conditions = Array();
        if (!empty($codename)) {
            $conditions[] = "name ='$codename'";
        }
        $this->_find_code_impl($conditions);
        $this->render("find_code");
    }

    function _find_code_impl($conditions) {
        $this->loadModel('Code');
        $this->data = $this->Code->find('all', Array('conditions' => $conditions, 'limit' => '200'));
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }

    function find_country($country=null) {
        $conditions = Array();
        if (!empty($country)) {
            $conditions = "country='$country'";
        }
        $this->_find_code_impl($conditions);
        $this->render("find_code");
    }

    function find_code() {
        $conditions = Array();
        $code_deck_id = $this->_get('code_deck_id');
        if ($code_deck_id) {
            $conditions[] = "code_deck_id='$code_deck_id'";
        }
        $country = $this->_get('country');
        if ($country) {
            $conditions[] = "country='$country'";
        }
        $code_name = $this->_get('code_name');
        if ($code_name) {
            $conditions[] = "name='$code_name'";
        }
        $code = $this->_get('code');
        if ($code) {
            $condtions[] = "code='$code'";
        }
        $this->_find_code_impl($conditions);
        $this->render("find_code");
    }

}
