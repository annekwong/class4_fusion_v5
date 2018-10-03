<?php

class TimeprofilesController extends AppController
{

    var $name = 'Timeprofiles';
    var $uses = array('Timeprofile');
    var $helper = array('javascript', 'html');

    function index()
    {
        $this->redirect('profile_list');
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
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
            $limit = $this->Session->read('sst_config_timeProfiles');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function profile_list()
    {
        $this->pageTitle = "Switch/Time Profile ";
        $order = $this->_order_condtions(Array('time_profile_id', 'name', 'type', 'start_week', 'end_week', 'start_time', 'end_time'));
        $currPage = 1;
        $pageSize = 100;
        $search = null;
//		if (! empty ( $_REQUEST ['page'] )) {
//			$currPage = $_REQUEST ['page'];
//		}
//		if (! empty ( $_REQUEST ['size'] )) {
//			$pageSize = $_REQUEST ['size'];
//		}
        if (!empty($_REQUEST['search']) && strcmp('Search....', $_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (!empty($_REQUEST['edit_id']))
        {
            $sql = "select * from time_profile where time_profile_id = {$_REQUEST['edit_id']} ORDER by name ASC";
            $result = $this->Timeprofile->query($sql);
            //分页信息
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        }
        else
        {
            $reseller_id = $this->Session->read('sst_reseller_id');
            $results = $this->Timeprofile->getAllProfiles($currPage, $pageSize, $search, $reseller_id, $order);
        }
        $this->set('p', $results);
        if(!$results->totalRecords){
            $this->Session->write('m', $this->Timeprofile->create_json(101, __('No Data Found', true)));
        }
    }

    /*
     * 添加或修改timeprofile时的数据验证
     */

    private function validate_profile($f, $url, $needcheckname)
    {
        $hasError = false;
        $name = $f['name'];
        if (empty($name))
        {
            $hasError = true;
            $this->Timeprofile->create_json_array("#name", 101, __('namenotnull', true));
        }
        else
        {
            if (!preg_match('/^[_\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff]+$/', $name))
            {
                $hasError = true;
                $this->Timeprofile->create_json_array("#name", 101, __('nameformaterror', true));
            }
        }

        if ($needcheckname == true)
        {
            if (!empty($name))
            {
                $names = $this->Timeprofile->query("select time_profile_id from time_profile where name = '$name'");
                if (count($names) > 0)
                {
                    $hasError = true;
                    $this->Timeprofile->create_json_array("#name", 101, __('tfnameexists', true));
                }
            }
        }

//			$st = $f['start_'];
//			$et = $f['end_date'];
//			
//			if (!empty($st) && !empty($et)) {
//				if (strtotime($et) - strtotime($st) <= 0) {
//					$hasError = true;
//					$this->Timeprofile->create_json_array("#end_date",101,__('datenotsuitable',true));
//				}
//			}
//			
//			if (empty($st) && !empty($et)) {
//				$hasError = true;
//				$this->Timeprofile->create_json_array("#start_date",101,__('entersd',true));
//			}
//			
        if ($hasError == true)
        {
            $this->Session->write("m", Timeprofile::set_validator());
            $this->Session->write('backform', $f);
            $this->redirect($url);
        }
    }

    /*
     * 添加Time profile
     */

    public function add_profile()
    {
        if (!$_SESSION['role_menu']['Switch']['timeprofiles']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($this->params['form']))
        {
            $f = $this->params['form'];
            $this->validate_profile($f, '/timeprofiles/add_profile', true);

            $rs = $this->Session->read('sst_reseller_id');
            if (!empty($rs))
            {
                $f['reseller_id'] = $this->Session->read('sst_reseller_id');
            }
            if ($f['type'] == 0)
            {
                $f['start_week'] = '';
                $f['end_week'] = '';
                $f['start_time'] = '';
                $f['end_time'] = '';
            }
            else if ($f['type'] == 2)
            {
                $f['start_week'] = '';
                $f['end_week'] = '';
            }
            if ($this->Timeprofile->save($f))
            {
                $this->Timeprofile->create_json_array('', 201, __('addtfsuc', true));
            }
            else
            {
                $this->Timeprofile->create_json_array('', 101, __('addtffailed', true));
            }
            $this->Session->write('m', Timeprofile::set_validator());
            $this->redirect('/timeprofiles/profile_list');
        }
    }

    /*
     * 修改Time profile
     */

    public function edit_profile($id = null)
    {
        if (!$_SESSION['role_menu']['Switch']['timeprofiles']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($this->params['form']))
        {
            $f = $this->params['form'];
            $newname = $f['name'];
            $id = $f['time_profile_id'];
            $qs = $this->Timeprofile->query("select name from time_profile where time_profile_id = '$id'");
            //判断是否需要检查时间段名称重复
            if ($qs[0][0]['name'] == $newname)
            {
                $this->validate_profile($f, '/timeprofiles/edit_profile', false);
            }
            else
            {
                $this->validate_profile($f, '/timeprofiles/edit_profile', true);
            }
            if ($f['type'] == '2')
            {
                $f['start_week'] = null;
                $f['end_week'] = null;
            }
            if ($f['type'] == '0')
            {
                $f['start_week'] = null;
                $f['end_week'] = null;
            }
            if ($this->Timeprofile->save($f))
            {
                $this->Timeprofile->create_json_array('', 201, __('edittfsuc', true));
            }
            else
            {
                $this->Timeprofile->create_json_array('', 101, __('edittffailed', true));
            }
            $this->Session->write('m', Timeprofile::set_validator());
            $this->redirect('/timeprofiles/profile_list?edit_id=' . $id);
        }
        else
        {
            $this->set('profile', $this->Timeprofile->getProfileById($id));
            $this->set('id', $id);
        }
    }

    /*
     * 删除时间段
     */

    public function delbyid($id)
    {
        if (!$_SESSION['role_menu']['Switch']['timeprofiles']['model_w'])
        {
            $this->redirect_denied();
        }
        $qs1 = $this->Timeprofile->query("select time_profile_id from resource_block where time_profile_id = '$id'");
        $qs2 = $this->Timeprofile->query("select time_profile_id from product_items where time_profile_id = '$id'");
        $qs3 = $this->Timeprofile->query("select time_profile_id from rate where time_profile_id = '$id'");

        if (count($qs1) > 0 || count($qs2) > 0 || count($qs3) > 0)
        {
            $this->Timeprofile->create_json_array('', 101, __('tfusing', true));
            $this->Session->write('m', Timeprofile::set_validator());
        }
        else
        {
            $this->data = $this->Timeprofile->find('first', Array('conditions' => Array('time_profile_id' => $id)));
            if ($this->Timeprofile->del($id))
                $this->Timeprofile->create_json_array('', 201, __('The Time profile [%s] is deleted successfully!', true, $this->data['Timeprofile']['name']));
            else
                $this->Timeprofile->create_json_array('', 101, __('Fail to delete Time profile.', true));

            $this->Session->write('m', Timeprofile::set_validator());
        }

        $this->redirect('/timeprofiles/profile_list');
    }

    function save($id = null)
    {
        Configure::write('debug', 0);
        if (!$_SESSION['role_menu']['Switch']['timeprofiles']['model_w'])
        {
            $this->redirect_denied();
        }
        if ($this->data['Timeprofile']['type'] == 0)
        {
            $this->data['Timeprofile']['start_week'] = '';
            $this->data['Timeprofile']['end_week'] = '';
            $this->data['Timeprofile']['start_time'] = '';
            $this->data['Timeprofile']['end_time'] = '';
        }
        if ($this->data['Timeprofile']['type'] == 2)
        {
            $this->data['Timeprofile']['start_week'] = '';
            $this->data['Timeprofile']['end_week'] = '';
        }
        if (!empty($id))
        {
            $this->data['Timeprofile']['time_profile_id'] = $id;
        }

        if (!empty($this->data['Timeprofile']['name'])) {
            if ($this->Timeprofile->save($this->data))
            {
                if (empty($id))
                {
                    $this->Timeprofile->create_json_array('', 201, __('The Time Profile [%s] is added successfully !', true, $this->data['Timeprofile']['name']));
                }
                else
                {
                    $this->Timeprofile->create_json_array('', 201, __('The Time Profile [%s] is modified successfully !', true, $this->data['Timeprofile']['name']));
                }
            }
        }
        $this->xredirect('/timeprofiles/profile_list');
    }

    function js_save($id = null)
    {
        if (!$_SESSION['role_menu']['Switch']['timeprofiles']['model_w'])
        {
            $this->redirect_denied();
        }
        $tz = $this->Timeprofile->get_sys_timezone();
        if (!empty($id))
        {
            $this->data = $this->Timeprofile->find('first', Array('conditions' => Array('time_profile_id' => $id),'order'=>array('name asc')));
            //	pr($this->data);
            if ($this->data['Timeprofile']['type'])
            {
                $tz = $this->data['Timeprofile']['time_zone'];
            }
        }
        $this->set('tz',$tz);
        $this->layout = 'ajax';
        Configure::write('debug', 0);
    }

    function check_name($id = null)
    {
        $this->layout = 'ajax';
        if (empty($id))
        {
            $id = $this->_get('id');
        }
        $condtions = Array();
        if (!empty($id))
        {
            $conditions[] = "time_profile_id <> $id";
        }
        $name = $this->_get('name');
        if (!empty($name))
        {
            $conditions = "name = '$name'";
        }
        $count = $this->Timeprofile->find('count', Array('conditions' => $conditions));
        if ($count > 0)
        {
            echo 'false';
        }
        Configure::write('debug', 0);
    }

}
