<?php

class DigitsController extends AppController
{

    var $name = 'Digits';
    var $uses = array('Digit', 'Digititem', 'TranslationItem');

    function index()
    {
        $this->redirect('view');
    }

    public function add()
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        $delete_rate_id = $_POST['delete_rate_id'];
        $delete_rate_id = substr($delete_rate_id, 1);
        $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
        $size = count($tmp);
        foreach ($tmp as $el)
        {
            $this->data['Digit'] = $el;
            $this->data['Digit']['translation_name'] = $el['name'];
            $this->data['Digit']['translation_id'] = $el['id'];
            $this->data['Digit']['translation'] = date('Y-m-d H:i:s');
            $this->Digit->save($this->data ['Digit']);
            $this->data['Digit']['translation_id'] = false;
        }
        if (!empty($delete_rate_id))
        {
            $this->Digit->query("delete  from  digit_translation where translation_id in($delete_rate_id)");
        }
        $this->Digit->create_json_array('#ClientOrigRateTableId', 201, 'Digit mapping save successfully !');
        $this->Session->write("m", Digit::set_validator());

        $this->redirect("/digits/view?page={$_GET['page']}&size={$_GET['size']}");
    }

    public function view()
    {
        $this->pageTitle = "Routing/Digit Mapping";
        //$this->set('s',$_get['search']);
        $this->set('p', $this->Digit->view($this->_order_condtions(array('id', 'name', 'trans', 'updateat'))));
    }

    function delete($id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($id))
        {
            $sql = "delete from digit_translation where translation_id=$id";
            //$this->Digit->del($id,true);
            $old_data = $this->Digit->findAll(array('translation_id' => $id));
            $old_detail_data = $this->TranslationItem->findAll(array('translation_id' => $id));
            $translation = $this->Digit->query("select translation_name as name from digit_translation where translation_id = $id ");
            $this->Digit->query($sql);
            if (!$this->Digit->getAffectedRows())
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('The digit mapping [%s] is deleted failed!', true, $translation[0][0]['name'])));
                $this->xredirect("/digits/view");
            }
            foreach ($old_data as $item)
            {
                $filed_arr = array();
                $value_arr = array();
                $old_data_arr = $item['Digit'];
                $id = $old_data_arr['translation_id'];
                unset($old_data_arr['translation_id']);
                foreach ($old_data_arr as $key => $value)
                {
                    if ($value)
                    {
                        $filed_arr[] = $key;
                        if (strcmp(intval($value), $value) || !strcmp($key, 'translation_name'))
                        {
                            $value_arr[] = "'" . $value . "'";
                        }
                        else
                        {
                            $value_arr[] = $value;
                        }
                    }
                }
                $filed_str = implode(',', $filed_arr);
                $value_str_arr[] = "(" . implode(',', $value_arr) . ")";
            }
            $value_str_sql = implode(',', $value_str_arr);
            $rollback_sql = "INSERT INTO digit_translation ({$filed_str}) VALUES {$value_str_sql} RETURNING translation_id;";
            $rollback_sql .= $this->_do_del_rollback($old_detail_data);
            $rollback_msg = 'Delete digit mapping [' . $translation[0][0]['name'] . '] operation have been rolled back!';
            $rollback_extra_info = json_encode(array('type' => 2, 'translation_id' => $id));
            $this->Session->write('m', $this->Digit->create_json(201, __('The digit mapping [%s] is deleted successfully', true, $translation[0][0]['name'])));
            $log_id = $this->Digit->logging('1', 'Digit Mapping', "digit mapping:{$translation[0][0]['name']}", $rollback_sql, $rollback_msg, $rollback_extra_info);
            $url_flug = "digits-view";
            $this->modify_log_noty($log_id, $url_flug);
//            $this->xredirect("/logging/index/{$log_id}/digits-view");
        }
        $this->xredirect("/digits/view");
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
            $limit = $this->Session->read('sst_config_digitTranslation');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function digits_mapping_list()
    {
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        if (!empty($_REQUEST ['page']))
        {
            $currPage = $_REQUEST ['page'];
        }
        if (!empty($_REQUEST ['size']))
        {
            $pageSize = $_REQUEST ['size'];
        }
        if (!empty($_REQUEST['search']))
        {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $results = $this->Digit->getAllDigits($currPage, $pageSize, $search);
        $this->set('p', $results);
    }

    public function add_translation()
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        $name = $_REQUEST ['name'];
        if (empty($name))
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('tran_name_null', true)));
            $this->Session->write('tran_name', ' ');
            $this->redirect(array('controller' => 'digits', 'action' => 'digits_mapping_list'));
        }
        $pattern = '/^[\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff_]+$/';
        if (!preg_match($pattern, $name))
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('tran_name_format_error', true)));
            $this->Session->write('tran_name', $name);
            $this->redirect(array('controller' => 'digits', 'action' => 'digits_mapping_list'));
        }
        if (strlen($name) >= 30)
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('tran_name_len', true)));
            $this->Session->write('tran_name', $name);
            $this->redirect(array('controller' => 'digits', 'action' => 'digits_mapping_list'));
        }
        $ns = $this->Digit->query("select translation_id from digit_translation where translation_name = '$name'");
        if (count($ns) > 0)
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('tran_name_exists', true)));
            $this->Session->write('tran_name', $name);
            $this->redirect(array('controller' => 'digits', 'action' => 'digits_mapping_list'));
        }
        $reseller_id = $this->Session->read('sst_reseller_id');
        if ($this->Digit->addDigitMapping($name, $reseller_id) == true)
        {
            $this->Session->write('m', $this->Digit->create_json(201, __('tran_add_success ', true)));
            $this->redirect(array('controller' => 'digits', 'action' => 'digits_mapping_list'));
        }
        else
        { //添加失败
            $this->Session->write('m', $this->Digit->create_json(101, __('tran_add_failed', true)));
            $this->Session->write('tran_name', $name);
            $this->redirect(array('controller' => 'digits', 'action' => 'digits_mapping_list'));
        }
    }

    public function modifyname()
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        if (!empty($_REQUEST ['id']))
        {
            if (!empty($_REQUEST ['name']))
            {
                $id = $_REQUEST ['id']; //ID
                $name = $_REQUEST ['name']; //Name
                //Characters only
                $pattern = '/^[_\x30-\x39\x41-\x5a\x61-\x7a\x80-\xff]+$/';
                if (!preg_match($pattern, $name))
                {
                    echo __('tran_name_format_error', true);
                    return;
                }

                //Length < 30
                if (strlen($name) >= 30)
                {
                    echo __('tran_name_len', true);
                    return;
                }

                $ns = $this->Digit->query("select translation_id from digit_translation where translation_name = '$name'  and translation_id <>$id");
                if (count($ns) > 0)
                {
                    echo __('tran_name_exists', true);
                    return;
                }


                $r = $this->Digit->modify_name($id, $name);
                if ($r != false)
                {
                    echo $r;
                }
                else
                {
                    echo __('tran_update_failed', true);
                }
            }
            else
            {
                echo __('tran_name_null', true);
            }
        }
    }

    /*
     * 根据ID删除
     */

    public function delbyid($id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        //判断该Digit Mapping是否有子项
        $ps = $this->Digit->query("select ref_id from translation_item where translation_id = '$id'");
        if (count($ps) > 0)
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('tran_has_childs', true)));
            $this->redirect(array('controller' => 'digits', 'action' => 'digits_mapping_list'));
            exit();
        }

        //删除
        if ($this->Digit->del_translation($id) != true)
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('cannot_del', true)));
        }
        else
        {
            $this->Session->write('m', $this->Digit->create_json(201, __('tran_del_success', true)));
        }
        $this->redirect(array('controller' => 'digits', 'action' => 'digits_mapping_list'));
    }

    /*
     * 删除所有Translation
     */

    public function delalltrans()
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        //检查是否有详细信息
        $ts = $this->Digit->query("select ref_id from translation_item");
        if (count($ts) > 0)
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('tran_haschilds', true)));
            $this->redirect('/digits/digits_mapping_list');
            exit();
        }

        //删除
        if ($this->Digit->deleteAll('digit_translation') != true)
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('cannot_del', true)));
        }
        else
        {
            $this->Session->write('m', $this->Digit->create_json(201, __('transs_del_success', true)));
        }
        $this->redirect('/digits/digits_mapping_list');
    }

    /*
     * 删除所有选中translations
     */

    public function delselectedtrans()
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        $ids = $_REQUEST ['ids'];
        $digs = $this->Digit->query("select ref_id from translation_item where translation_id in ($ids)");
        if (count($digs) > 0)
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('tran_haschilds', true)));
            $this->redirect('/digits/digits_mapping_list');
            exit();
        }
        if ($this->Digit->deleteSelected('digit_translation', 'translation_id', $ids) != true)
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('cannot_del', true)));
        }
        else
        {
            $this->Session->write('m', $this->Digit->create_json(201, __('del_some_trans_succ', true)));
        }
        $this->redirect('/digits/digits_mapping_list');
    }

    /**
     * 查询该Product对应的Route
     * @param int $id
     */
    public function translation_details($id = null)
    {
        $this->pageTitle = "Management/Detail ";
        $id = base64_decode($id);
        if (!empty($id))
        {
            $currPage = array_keys_value_empty($_REQUEST, 'page', 1);
            $pageSize = array_keys_value_empty($_REQUEST, 'size', 10);
            $search = array_keys_value($_REQUEST, 'search');
            $rs = $this->Digit->getItemsByDigit($id, $currPage, $pageSize, $search);
            $this->set('p', $rs);
            $this->set('id', $id);
            if (!empty($id))
            {
                $this->set('name', $this->Digit->query("select translation_name as name from digit_translation where translation_id = $id "));
            }
        }
    }

    /*
     * 根据id删除一条详细信息
     */

    public function del_tran_detail($id = null, $tran_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($id))
        {
            if ($this->Digititem->del($id))
            {
                $this->Session->write('m', $this->Digit->create_json(201, __('del_tran_detail_suc', true)));
                $this->redirect('/digits/translation_details/' . base64_encode($tran_id));
            }
            else
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('cannot_del', true)));
            }
        }
    }

    /*
     * 删除某digit mapping 下面所有的详细规则
     */

    public function del_all_details($id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($id))
        {
            //删除	
            if ($this->Digit->deleteAll('translation_item', ' where translation_id = ' . $id) != true)
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('cannot_del', true)));
            }
            else
            {
                $this->Session->write('m', $this->Digit->create_json(201, __('del_all_tran_detail_suc', true)));
            }
            $this->redirect('/digits/translation_details/' . base64_encode($id));
        } else {

            //删除
            if ($this->Digit->deleteAll('translation_item') != true || $this->Digit->deleteAll('digit_translation') != true)
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('cannot_del', true)));
            }
            else
            {
                $this->Session->write('m', $this->Digit->create_json(201, __('del_all_tran_detail_suc', true)));
            }
            $this->redirect('/digits/view');
        }
    }

    /*
     * 删除某digit mapping 下选中的详细规则
     */

    public function del_selected_details()
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = $_REQUEST['id'];
        $ids = $_REQUEST ['ids'];
        if (!empty($id))
        {

            if ($this->Digit->deleteSelected('translation_item', 'ref_id', $id) != true)
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('cannot_del', true)));
            }
            else
            {
                $this->Session->write('m', $this->Digit->create_json(201, __('del_some_tran_detail_suc', true)));
            }
            $this->redirect('/digits/translation_details/' . base64_encode($id));
        }elseif(!empty($ids)) {
            if ($this->Digit->deleteSelected('digit_translation', 'translation_id', $ids) != true)
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('cannot_del', true)));
            }
            else
            {   $this->Digit->deleteSelected('translation_item', 'ref_id', $id);
                $this->Session->write('m', $this->Digit->create_json(201, __('del_some_tran_detail_suc', true)));
            }
            $this->redirect('/digits/view');
        }
    }

    function js_save($translation_id = null, $id = null)
    {
        if ($this->RequestHandler->isPost())
        {
            if (!empty($translation_id))
            {
                $this->data['TranslationItem']['translation_id'] = base64_decode($translation_id);
            }
            if (!empty($id))
            {
                $this->data['TranslationItem']['ref_id'] = $id;
            }
            $count = $this->TranslationItem->check($this->data);
            if ($count)
            {
                $this->TranslationItem->create_json_array('#ClientOrigRateTableId', 302, 'Existing the same data ! ');
                $this->xredirect("translation_details/" . $translation_id);
            }
            else
            {
                if ($this->TranslationItem->save($this->data))
                {

                    $this->TranslationItem->create_json_array('#ClientOrigRateTableId', 201, 'Number Translation added successfully!');
                }
                $this->xredirect("translation_details/" . $translation_id);
                return;
            }
        }
        if (!empty($id))
        {
            $this->data = $this->TranslationItem->find('first', Array('conditions' => Array('ref_id' => $id)));
            $this->set('ref_id', $id);
        }
        Configure::write('debug', 0);
    }

    /*
     * 添加和修改详细号码转换信息时的数据验证
     */

    private function validateDetails($f, $url)
    {
        $id = $f['translation_id'];
        $ani = $f['ani'];
        $has_error = false;
        $dnis = $f['dnis'];
        $ani_method = $f['ani_method'];
        $dnis_method = $f['dnis_method'];
        $action_ani = $f['action_ani'];
        $action_dnis = $f['action_dnis'];
        if (empty($ani) && empty($dnis) && empty($action_ani) && empty($action_dnis))
        {
            $has_error = true;
            $this->Digititem->create_json_array('#ani', 101, __('atleatone', true));
        }
        else if (!empty($ani) && !empty($dnis) && !empty($action_ani) && !empty($action_dnis))
        {
            $sql = "select count(*) as counts  from translation_item  where ani='$ani'  and 
				     dnis ='666' and action_ani ='666' and action_dnis ='$dnis' and ani_method=$action_ani and dnis_method=$action_dnis";
            $count = $this->Digititem->query($sql);
            if ($count[0][0]['counts'] == 0 || empty($count[0][0]['counts']))
            {
                $has_error = true;
                $this->Digititem->create_json_array('#ani,#dnis,#action_ani,#action_dnis', 101, __('Number translation can not be repeated', true));
            }
        }
        if (!empty($ani))
        {
            if (!preg_match('/^[0-9a-zA-Z]+$/', $ani))
            {
                $has_error = true;
                $this->Digititem->create_json_array('#ani', 101, __('tran_ani_format', true));
            }
        }
        if (!empty($dnis))
        {
            if (!preg_match('/^[0-9a-zA-Z]+$/', $dnis))
            {
                $has_error = true;
                $this->Digititem->create_json_array('#dnis', 101, __('tran_dnis_format', true));
            }
        }
        //主叫转换方式不是忽略  需验证
        if ($ani_method != 0)
        {
            if (empty($action_ani))
            {
                $has_error = true;
                $this->Digititem->create_json_array('#action_ani', 101, __('tran_ani_action_null', true));
            }

            if (!preg_match('/^[0-9a-zA-Z]+$/', $action_ani))
            {
                $has_error = true;
                $this->Digititem->create_json_array('#action_ani', 101, __('tran_ani_action_format', true));
            }
        }


        if ($dnis_method != 0)
        {
            if (empty($action_dnis))
            {
                $has_error = true;
                $this->Digititem->create_json_array('#action_dnis', 101, __('tran_dnis_action_null', true));
            }

            if (!preg_match('/^[0-9a-zA-Z]+$/', $action_dnis))
            {
                $has_error = true;
                $this->Digititem->create_json_array('#action_dnis', 101, __('tran_dnis_action_format', true));
            }
        }
        if ($has_error == true)
        {
            $this->Session->write('backform', $f);
            $this->Session->write('m', Digititem::set_validator());
            $this->redirect($url);
        }
    }

    /*
     * 添加转换规则
     */

    public function add_tran_detail($id)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($this->params['form']))
        {
            $f = $this->params['form'];
            $id = $f['translation_id'];
            $this->validateDetails($f, '/digits/add_tran_detail/' . $id); //数据验证
            if ($this->Digititem->save($f))
            {
                $this->Session->write('m', $this->Digit->create_json(201, __('tran_detail_add_suc', true)));
                $this->redirect("/digits/translation_details/{$id}");
            }
            else
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('tran_detail_add_failed', true)));
                $this->Session->write('backform', $f);
            }
        }
        else
        {
            $this->set('id', $id);
        }
    }

    /*
     * 修改转换规则
     */

    public function edit_tran_detail($id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($id))
        {
            $check_sql = "select  digit_translation.translation_name as name  from digit_translation join translation_item on  digit_translation.translation_id=translation_item.translation_id where ref_id=$id";
            $name = $this->Digititem->query($check_sql);
            if (!empty($name))
            {
                $this->set('name', $name[0][0]['name']);
            }
            else
            {
                $this->set('name', '');
            }
        }



        if (!empty($this->params['form']))
        {//修改
            $f = $this->params['form'];
            $id = $f['ref_id'];
            $this->validateDetails($f, '/digits/edit_tran_detail/' . $id); //数据验证
            $bid = $f['translation_id'];
            if ($this->Digititem->save($f))
            {
                $this->Session->write('m', $this->Digit->create_json(201, __('tran_detail_add_suc', true)));
                $this->redirect('/digits/translation_details/' . base64_encode($bid));
            }
            else
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('tran_detail_add_failed', true)));
                $this->Session->write('backform', $f);
            }
        }
        else
        {//查询
            $this->set('detail', $this->Digit->getTranDetailById($id));
        }
    }

    public function download_item()
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_x'])
        {
            $this->redirect_denied();
        }
        $this->_catch_exception_msg(array('DigitsController', '_download_item_impl'));
    }

    public function _download_item_impl($params = array())
    {
        $id = $this->params['pass'][0];
        Configure::write('debug', 0);
//	 	$this->layout='';
//	 	$this->autoRender =  false;
        if (isset($this->params['form']) && isset($this->params['form']['fields']) && !empty($this->params['form']['fields']))
        {
            $fields = array();
            $all_fields = array_keys($this->TranslationItem->schema());
            foreach ($this->params['form']['fields'] as $f)
            {
                if (in_array($f, $all_fields))
                {
                    $fields[] = $f;
                }
            }
            if (empty($fields))
            {
                $download_sql = "select  * from  translation_item   where translation_id=$id";
            }
            else
            {
                $select = join(',', $fields);
                $download_sql = "select  $select from  translation_item   where translation_id=$id";
            }
        }
        else
        {
            $download_sql = "select  * from  translation_item   where translation_id=$id";
        }

        if ($this->TranslationItem->download_by_sql($download_sql, array('objectives' => 'translation_item')))
        {
            exit(1);
        }
    }

    function download_item_view()
    {
        $this->set('fields', $this->TranslationItem->schema());
        $this->set('id', $this->params['pass'][0]);
    }

    //上传code	
    public function import_item()
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_x'])
        {
            $this->redirect_denied();
        }
        $translation_id = $this->params['pass'][0];
        $list = $this->Digit->query("select translation_name   from  digit_translation where   translation_id=$translation_id ");
        $this->set("code_name", $list[0][0]['translation_name']);
        $this->set("translation_id", $translation_id);
    }

//上传成功 记录上传
    public function upload_code2()
    {
        $code_deck_id = $_POST['upload_table_id'];
        $list = $this->Digit->import_data("Upload Digit Mapping "); //上传数据
        $this->Digit->create_json_array("", 201, 'Upload Success');
        $this->Session->write('m', Digit::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    /*
     * 下载某个Digit Mapping 下面所有的Translation详细信息
     */

    public function download($tran_id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = '';

        if (!empty($tran_id))
        {
            $datas = $this->Digit->query("select ref_id,ani,dnis,action_ani,action_dnis,
				case 
					when ani_method = 0 then 'Ignore' 
					when ani_method = 1 then 'Compare'
					when ani_method = 2 then 'Replace'
				  end as ani_method,
			  case 
					when dnis_method = 0 then 'Ignore' 
					when dnis_method = 1 then 'Compare'
					when dnis_method = 2 then 'Replace'
			   end as dnis_method 
			  from translation_item
			where translation_id  =  '$tran_id'");
            //没有数据
            if (count($datas) == 0)
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('no_data_found', true)));
                $this->redirect('/digits/translation_details/' . base64_encode($tran_id));
                exit();
            }

            $title = "ID,ANI,DNIS,ACTION_ANI,ACTION_DNIS,ANI_METHOD,DNIS_METHOD";

            $this->Digit->downLoadFile($title, $datas, time());
        }
    }

    function valireport()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $id = $this->_get('id');
        $ani = $this->_get('ani');
        $dnis = $this->_get('dnis');
        $action_ani = $this->_get('action_ani');
        $action_dnis = $this->_get('action_dnis');
        $sql = "select count(*) from translation_item where ani='$ani' and dnis='$dnis' and action_ani='$action_ani' and action_dnis='$action_dnis'";
        if (!empty($id))
        {
            $sql.="and ref_id <> '$id'";
        }
        $list = $this->Digit->query($sql);
        if ($list[0][0]['count'] > 0)
        {
            echo 'false';
        }
    }

    //查得复
    function check_repeat($ani = null, $dnis = null, $t_ani = null, $t_dnis = null, $ref_id = null)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        //$ch_name = null;
        //$this->layout = 'ajax';
        if (!empty($ani) || !empty($dnis) || !empty($t_ani) || !empty($t_dnis))
        {
            $sql = "select count(*) as num from translation_item where ani= '$ani' and dnis ='$dnis' and action_ani= '$t_ani' and action_dnis = '$t_dnis'";

            if ($ref_id)
                $sql .= " AND ref_id !=  {$ref_id}";
            $num = $this->Digit->query($sql);
            if ($num[0][0]['num'] > 0)
            {
                echo "false";
            }
            else
            {
                echo "true";
            }
        }
    }

    public function tran_upload($tran_id)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_x'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 2);
        $r = $this->Digit->getUploadData('browse');

        $each_count = 1;
        $hasError = false;
        $upload_error = "[";
        $effectRows = count($r);
        $isroll = $this->params['form']['isRoll'];

        $origani_txt = __('origani', true);
        $origdnis_txt = __('origdnis', true);
        $translatedani_txt = __('translatedani', true);
        $translateddnis_txt = __('translateddnis', true);
        $aniaction_txt = __('aniaction', true);
        $dnisaction_txt = __('dnisaction', true);

        $this->Digit->begin();

        foreach ($r as $d)
        {
            $errorFlag = false;

            $origani = $d->origani;
            $origdnis = $d->origdnis;
            $transani = $d->transani;
            $transdnis = $d->transdnis;
            $aniaction = $d->aniaction;
            $dnisaction = $d->dnisaction;

            if (empty($origani) && empty($origdnis) && empty($transani) && empty($transdnis))
            {
                $hasError = true;
                $errorFlag = true;
                $msg = __('atleatone', true);
                $upload_error .= "{row:$each_count,name:'',msg:'$msg'},";
            }

            if (!empty($origani))
            {
                if (!preg_match('/^[0-9a-zA-Z]+$/', $origani))
                {
                    $has_error = true;
                    $errorFlag = true;
                    $msg = __('tran_ani_format', true);
                    $upload_error .= "{row:$each_count,name:'$origani_txt',msg:'$msg'},";
                }
                else
                {
                    $origani = "'" . $origani . "'";
                }
            }
            else
                $origani = 'null';

            if (!empty($origdnis))
            {
                if (!preg_match('/^[0-9a-zA-Z]+$/', $origdnis))
                {
                    $has_error = true;
                    $errorFlag = true;
                    $msg = __('tran_dnis_format', true);
                    $upload_error .= "{row:$each_count,name:'$origdnis_txt',msg:'$msg'},";
                }
                else
                {
                    $origdnis = "'" . $origdnis . "'";
                }
            }
            else
                $origdnis = 'null';

            if ($aniaction != 0 && $aniaction != 1 && $aniaction != 2)
            {
                $has_error = true;
                $errorFlag = true;
                $msg = __('aniaction012', true);
                $upload_error .= "{row:$each_count,name:'$aniaction_txt',msg:'$msg'},";
            }

            if ($dnisaction != 0 && $dnisaction != 1 && $dnisaction != 2)
            {
                $has_error = true;
                $errorFlag = true;
                $msg = __('dnisaction012', true);
                $upload_error .= "{row:$each_count,name:'$dnisaction_txt',msg:'$msg'},";
            }

            //主叫转换方式不是忽略  需验证
            if ($aniaction != 0)
            {
                if (empty($transani))
                {
                    $has_error = true;
                    $errorFlag = true;
                    $msg = __('tran_ani_action_null', true);
                    $upload_error .= "{row:$each_count,name:'$translatedani_txt',msg:'$msg'},";
                }
                else
                {
                    if (!preg_match('/^[0-9a-zA-Z]+$/', $transani))
                    {
                        $has_error = true;
                        $errorFlag = true;
                        $msg = __('tran_ani_action_format', true);
                        $upload_error .= "{row:$each_count,name:'$translatedani_txt',msg:'$msg'},";
                    }
                    else
                    {
                        $transani = "'" . $transani . "'";
                    }
                }
            }


            if ($dnisaction != 0)
            {
                if (empty($transdnis))
                {
                    $has_error = true;
                    $errorFlag = true;
                    $msg = __('tran_dnis_action_null', true);
                    $upload_error .= "{row:$each_count,name:'$translateddnis_txt',msg:'$msg'},";
                }
                else
                {
                    if (!preg_match('/^[0-9a-zA-Z]+$/', $transdnis))
                    {
                        $has_error = true;
                        $errorFlag = true;
                        $msg = __('tran_dnis_action_null', true);
                        $upload_error .= "{row:$each_count,name:'tran_dnis_action_format',msg:'$msg'},";
                    }
                    else
                    {
                        $transdnis = "'" . $transdnis . "'";
                    }
                }
            }

            if (empty($transani))
            {
                $transani = 'null';
            }

            if (empty($transdnis))
            {
                $transdnis = 'null';
            }


            if ($errorFlag == true)
            {
                $effectRows--;
            }

            $this->insert($tran_id, $origani, $origdnis, $transani, $transdnis, $aniaction, $dnisaction);

            $each_count++;
        }

        $this->Session->write('upload_commited_rows', $effectRows);
        if ($hasError == true)
        {
            $upload_error = substr($upload_error, 0, strlen($upload_error) - 1) . "]";
            $this->Session->write('upload_digit_error', $upload_error);

            if ($isroll == 'true')
            { //需要回滚
                $this->Digit->rollback();
                $this->Session->write('upload_commited_rows', 0);
            }
            else
            { //忽略错误提交
                $this->Digit->commit();
            }
        }
        else
        {
            $this->Digit->commit();
        }

        $this->redirect('/digits/translation_details/' . base64_encode($tran_id));
    }

    function j_check_name($data = array(), $id = null)
    {
        $ret = true;
        $name = $data['Digit']['translation_name'];
        if (empty($name))
        {
            $this->Session->write('m', $this->Digit->create_json(101, __('The field Digit mapping cannot be NULL.', true)));
            $ret = false;
        }
        else
        {
            if (empty($id))
            {
                $sql = "select count(*) as c_num  from digit_translation where  translation_name ='$name'";
                $count_num = $this->Digit->query($sql);
                if ($count_num[0][0]['c_num'] > 0 && empty($id))
                {
                    $this->Session->write('m', $this->Digit->create_json(101, __($name . ' is already in use!', true)));
                    $ret = false;
                }
                else if ($count_num[0][0]['c_num'] >= 1 && !empty($id))
                {
                    $this->Session->write('m', $this->Digit->create_json(101, __($name . ' is already in use!', true)));
                    $ret = false;
                }
            }
            if (preg_match("/[^0-9A-Za-z-\_\s]+/", $name))
            {
                $this->Session->write('m', $this->Digit->create_json(101, __('Name,allowed characters:a-z,A-Z,0-9,-,_,space! ', true)));
                $ret = false;
            }
        }
        return $ret;
    }

    function ajax_refresh_digits()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $result = $this->Digit->ajax_refresh_digits();
        echo json_encode($result);
    }

    function ajax_save_digits()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $data = array();
        $data['Digit']['translation_name'] = $_POST['digit_name'];
        $succ = 0;
        if ($this->j_check_name($data))
        {
            if ($this->Digit->save($data))
            {
                $succ = 1;
            }
        }
        echo $succ;
    }

    function js_save_digits($id = null)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';

        if ($this->isPost())
        {
            if ($id)
            {
                $this->data['Digit']['translation_id'] = $id;
                $this->data['Digit']['translation'] = date("Y-m-d h:i:s");
            }
            //print_r($this->data);
            if ($this->j_check_name($this->data, $id))
            {
                $old_data = $this->Digit->findByTranslationId(array($id));
                if ($this->Digit->save($this->data))
                {
                    if (empty($id))
                    {
                        $insert_id = $this->Digit->getLastInsertID();
                        //$this->Session->write('m',$this->Digit->create_json(201,__('Create digit mapping successfully! ',true)));
                        $this->Session->write('m', $this->Digit->create_json(201, __('The digit mapping [%s] is created successfully! ', true, $this->data['Digit']['translation_name'])));
                        $rollback_sql = "DELETE FROM digit_translation WHERE translation_id = {$insert_id}";
                        $rollback_msg = "Delete Digit Mapping [" . $this->data['Digit']['translation_name'] . "] operation have been rolled back!";
                        $log_id = $this->Digit->logging('0', 'Digit Mapping', "digit mapping:{$this->data['Digit']['translation_name']}", $rollback_sql, $rollback_msg);
                    }
                    else
                    {
                        $rollback_data = array();
                        foreach ($this->data['Digit'] as $key => $value)
                        {
                            if ($old_data['Digit'][$key] != $value)
                            {
                                if (strcmp(intval($value), $value) || !strcmp($key, 'digit'))
                                {
                                    $rollback_data[] = $key . " = '" . $old_data['Digit'][$key] . "'";
                                }
                                else
                                {
                                    $rollback_data[] = $key . " = " . $old_data['Digit'][$key];
                                }
                            }
                        }
                        $rollback_update_sql = implode(',', $rollback_data);
                        $rollback_sql = "UPDATE digit_translation SET {$rollback_update_sql} WHERE translation_id = {$id}";
                        $rollback_msg = "Modify digit mapping [" . $this->data['Digit']['translation_name'] . "] operation have been rolled back!";
                        $this->Session->write('m', $this->Digit->create_json(201, __('The digit mapping [%s] is modified successfully!', true, $this->data['Digit']['translation_name'])));
                        $log_id = $this->Digit->logging('2', 'Digit Mapping', "digit mapping:{$this->data['Digit']['translation_name']}", $rollback_sql, $rollback_msg);
                    }
                    $url_flug = "digits-view";
                    $this->modify_log_noty($log_id, $url_flug);
//                    $this->xredirect("/logging/index/{$log_id}/digits-view");
                }
            }
            $this->xredirect("/digits/view");
        }
        $this->data = $this->Digit->find('first', Array('conditions' => Array('translation_id' => $id)));
    }

    private function insert($tran_id, $origani, $origdnis, $transani, $transdnis, $aniaction, $dnisaction)
    {
        if (!$_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            $this->redirect_denied();
        }
        $sql = "insert into translation_item (translation_id,ani,dnis,action_ani,action_dnis,ani_method,dnis_method)
								 values('$tran_id',$origani,$origdnis,$transani,$transdnis,$aniaction,$dnisaction)";
        $qs = $this->Digit->query($sql);
        return $qs;
    }

    public function _do_del_rollback($data_arr)
    {
        foreach ($data_arr as $item)
        {
            $filed_arr = array();
            $value_arr = array();
            $old_data_arr = $item['TranslationItem'];
            unset($old_data_arr['ref_id']);
            $old_data_arr['translation_id'] = "{translation_id}";
            foreach ($old_data_arr as $key => $value)
            {
                if ($value || !strcmp($key, 'ani_method') || !strcmp($key, 'dnis_method'))
                {
                    $filed_arr[] = $key;
                    if (strcmp(intval($value), $value) || !strcmp($key, 'ani') || !strcmp($key, 'dnis'))
                    {
                        $value_arr[] = "'" . $value . "'";
                    }
                    else
                    {
                        $value_arr[] = $value;
                    }
                }
            }
            $filed_str = implode(',', $filed_arr);
            $value_str_arr[] = "(" . implode(',', $value_arr) . ")";
        }
        $value_str_sql = implode(',', $value_str_arr);
        $rollback_sql = "INSERT INTO translation_item ({$filed_str}) VALUES {$value_str_sql}";
        if (empty($value_arr))
        {
            return "";
        }
        return $rollback_sql;
    }

}
