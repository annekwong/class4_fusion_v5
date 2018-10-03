<?php

class DialerDetectionController extends AppController
{

    var $name = "DialerDetection";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('DialerDetection');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function add($id = "")
    {
        $this->pageTitle = "Monitoring/Dialer Detection";
        if ($id)
        {
            $id = base64_decode($id);
            $data = $this->DialerDetection->find(array('id' => $id));
            $this->set('data', $data);
        }
        $ingress = $this->DialerDetection->findAll_ingress_id(FALSE);
        $this->set('ingress', $ingress);
        if ($this->RequestHandler->isPost())
        {
            $post_data = $this->params['form'];
            $post_data['id'] = isset($data['DialerDetection']['id']) ? $data['DialerDetection']['id'] : "";
            $judge_flg = $this->ajax_judge_rule_name(false,$post_data['name'],$post_data['id']);
            if($judge_flg)
            {
                $this->Session->write('m', $this->DialerDetection->create_json(101, sprintf(__('The rule name [%s] is already in use!',true),$post_data['name'])));
                $this->redirect('add/'.$id);
            }
            $post_data['action'] = isset($post_data['action']) ? true : false;
            $post_data['trunk'] = implode(',', $post_data['trunk']);
            $result2 = $this->DialerDetection->save($post_data);
            
            if ($result2 !== false)
            {
                if($id){
                    $action = 'modified';
                }
                else{
                    $action = 'created';
                }
                $this->Session->write('m', $this->DialerDetection->create_json(201, 'The rule name [' . $post_data['name'] . '] is ' . $action . ' successfully!'));
            }
            else
            {
                $this->Session->write('m', $this->DialerDetection->create_json(101, 'Save failed!'));
            }
            $this->redirect('/dialer_detection');
        }
//        $sql = "SELECT dialer_detection_subject, dialer_detection_content FROM mail_tmplate limit 1";
//        $mail_info = $this->DialerDetection->query($sql);
//        $this->set('mail_info', $mail_info);
    }

    public function enable($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!isset($encode_id))
        {
            $this->Session->write('m', $this->DialerDetection->create_json(101, __('failed')));
            $this->redirect('/dialer_detection');
            return false;
        }
        $id = base64_decode($encode_id);
        $dialer_deteciton_id = (int) $id;
        $flg = $this->DialerDetection->query("UPDATE dialer_detection SET action = true WHERE id = $dialer_deteciton_id");
        if ($flg === false)
        {
            $this->Session->write('m', $this->DialerDetection->create_json(101, __('failed')));
        }
        else
        {
            $this->Session->write('m', $this->DialerDetection->create_json(201, __('succeed')));
        }
        $this->redirect('/dialer_detection');
    }

    public function disable($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!isset($encode_id))
        {
            $this->DialerDetection->create_json_array("", 101, __('failed'));
            $this->Session->write('m', DialerDetection::set_validator());
            $this->redirect('/dialer_detection');
            return false;
        }
        $id = base64_decode($encode_id);
        $dialer_deteciton_id = (int) $id;
        $flg = $this->DialerDetection->query("UPDATE dialer_detection SET action = false WHERE id = $dialer_deteciton_id");
        if ($flg === false)
        {
            $this->DialerDetection->create_json_array("", 101, __('failed'));
        }
        else
        {
            $this->DialerDetection->create_json_array("", 201, __('succeed'));
        }
        $this->Session->write('m', DialerDetection::set_validator());
        $this->redirect('/dialer_detection');
    }

    public function index()
    {
        $this->set('active', 'index');
        $order_sql = "ORDER BY id DESC";
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
        $data_arr = $this->DialerDetection->data_list($order_sql);

        if (empty($data_arr))
        {
            $msg = "Dialer Detection Rule";
            $add_url = "add";
            $model_name = "DialerDetection";
            $this->to_add_page($model_name,$msg,$add_url);
        }
        $trunk = $this->DialerDetection->query("select resource_id,alias from resource");
        $size = count($trunk);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $trunk [$i] [0] ['resource_id'];
            $l [$key] = $trunk [$i] [0] ['alias'];
        }
        $this->set('trunk', $l);
        $this->set('data', $data_arr);
    }

    public function execution_log()
    {
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page')))
        {
            $currPage = $this->params['url']['page'];
        }
        $get_data = $this->params['url'];

        $now_datetime = date("Y-m-d H:i:s", time());
        $start_datetime = date("Y-m-d", time() - 7 * 24 * 3600) . " 00:00:00";
        if ($this->isnotEmpty($get_data, "start_datetime"))
        {
            $start_datetime = $get_data['start_datetime'];
        }
        if ($this->isnotEmpty($get_data, "end_datetime"))
        {
            $now_datetime = $get_data['end_datetime'];
        }
        $get_data['start_datetime'] = $start_datetime;
        $get_data['end_datetime'] = $now_datetime;
        $where = " WHERE start_time BETWEEN '{$start_datetime}' AND '{$now_datetime}'";
        if ($this->isnotEmpty($get_data, "rule_name"))
        {
            $where .= " AND rule_name like '%{$get_data['rule_name']}%'";
        }
        $pageSize = 20;
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = $this->DialerDetection->execution_log_count($where);

        $page->setTotalRecords($totalrecords); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "ORDER BY id DESC";
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

        $data_arr = $this->DialerDetection->execution_log_list($where, $order_sql, $pageSize, $offset);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
        $this->set('active', 'execution');
        $this->set('get_data', $get_data);
        $status_arr = array(
            0 => 'failed',
            1 => 'succeed',
            2 => '--'
        );
        $type_arr = array(
            0 => '--',
            1 => 'block',
            2 => 'send email'
        );
        $this->set("status_arr",$status_arr);
        $this->set("type_arr",$type_arr);
    }

    public function ani_blocking_log()
    {
        $currPage = 1;
        if ($this->isnotEmpty($this->params['url'], array('page')))
        {
            $currPage = $this->params['url']['page'];
        }
        $get_data = $this->params['url'];

        $now_datetime = date("Y-m-d H:i:s", time());
        $start_datetime = date("Y-m-d", time() - 7 * 24 * 3600) . " 00:00:00";
        if ($this->isnotEmpty($get_data, "start_datetime"))
        {
            $start_datetime = $get_data['start_datetime'];
        }
        if ($this->isnotEmpty($get_data, "end_datetime"))
        {
            $now_datetime = $get_data['end_datetime'];
        }
        $get_data['start_datetime'] = $start_datetime;
        $get_data['end_datetime'] = $now_datetime;
        $where = " WHERE start_time BETWEEN '{$start_datetime}' AND '{$now_datetime}'";
        if ($this->isnotEmpty($get_data, "rule_name"))
        {
            $where .= " AND rule_name like '%{$get_data['rule_name']}%'";
        }

        if (isset($get_data['log_type']) && $get_data['log_type'] !== "")
        {
            $where .= " AND type = {$get_data['log_type']}";
            $get_data['log_type'] = (int) $get_data['log_type'];
        }

        $pageSize = 20;
        $search = null;
        require_once MODELS . DS . 'MyPage.php';
        $page = new MyPage();

        $totalrecords = $this->DialerDetection->ani_blocking_log_count($where);

        $page->setTotalRecords($totalrecords); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "ORDER BY id DESC";
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

        $data_arr = $this->DialerDetection->ani_blocking_log_list($where, $order_sql, $pageSize, $offset);
//        $ruleNames = $this->DialerDetection->query("SELECT DISTINCT rule_name FROM ani_blocking_log ORDER BY rule_name ASC");
        $ruleNames = array();
        $page->setDataArray($data_arr);
        $this->set('p', $page);
        $this->set('ruleNames', $ruleNames);
        $this->set('active', 'ani_blocking');
        $type_arr = array(
            '0' => 'block',
            '1' => 'unblock',
        );
        $this->set('type_arr', $type_arr);
        $this->set('get_data', $get_data);
    }

    public function delete_rule($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($encode_id);
        if ($this->DialerDetection->del($id) === false)
        {
            $this->DialerDetection->create_json_array("", 101, __('failed'));
        }
        else
        {
            $this->DialerDetection->create_json_array("", 201, __('succeed'));
        }
        $this->Session->write('m', DialerDetection::set_validator());
        $this->redirect('index');
    }

    public function ajax_judge_rule_name($is_ajax = true,$name = '',$id = '')
    {
        if($is_ajax)
        {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->autoLayout = false;
            $rule_id = $_POST['rule_id'];
            $rule_name = $_POST['rule_name'];
        }
        else
        {
            $rule_id = $id;
            $rule_name = $name;
        }
        $conditions = array(
            'name' => $rule_name
        );
        if($rule_id)
            $conditions['id != ?'] = $rule_id;
        return $this->DialerDetection->find('count',array('conditions' => $conditions));
    }

}
