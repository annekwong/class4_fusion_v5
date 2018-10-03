<?php

class RandomAniController extends AppController
{

    var $name = 'RandomAni';
    var $uses = Array('RandomAniTable', 'RandomAniGeneration', 'RandomAniPopulatedLog', 'Systemparam');
    var $helpers = array('javascript', 'html', 'appBlocklists');
    var $components = array('RequestHandler');

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
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect("random_table");
    }

    public function random_table()
    {

        $this->pageTitle = "Switch/Random ANI Group";
        $get_data = $this->params['url'];
//        $start_date = isset($get_data['start_date']) ? $get_data['start_date'] : date("Y-m-d",strtotime('-1 month'));
//        $end_date = isset($get_data['stop_date']) ? $get_data['stop_date'] : date("Y-m-d");
//
//        $start_time = isset($get_data['start_time']) ? $get_data['start_time'] : "00:00:00";
//        $end_time = isset($get_data['stop_time']) ? $get_data['stop_time'] : "23:59:59";
//
//        $tz = isset($get_data['gmt']) ? $get_data['gmt'] : "+0000";
//
//
//        $start_datetime = $start_date . ' ' . $start_time . $tz;
//        $end_datetime = $end_date . ' ' . $end_time . $tz;

        $order_arr = array('RandomAniTable.id' => 'ASC');
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
        $this->paginate = array(
            'limit' => 100,
            'order' => $order_arr,
//            'conditions' => array(
//                "RandomAniTable.create_time BETWEEN '{$start_datetime}' AND '{$end_datetime}'",
//            ),
        );

        if (isset($get_data['name']))
        {
            array_push($this->paginate['conditions'], "RandomAniTable.name ilike '%{$get_data['operator']}%'");
        }


        $this->data = $this->paginate('RandomAniTable');
//        pr($this->data);die;
    }

//    public function ajax_judge_random_table_name()
//    {
//        Configure::write('debug', 0);
//        $this->autoLayout = false;
//        $this->autoRender = false;
//        $name = $_POST['name'];
//        $id = isset($_POST['id']) ? $_POST['id'] : "";
//        $conditions = array(
//            'name' => $name,
//        );
//        if ($id)
//        {
//            $conditions[] = "id != $id";
//        }
//        $count = $this->RandomAniTable->find('first', array('conditions' => $conditions));
//        if($count)
//        {
//            echo 1;
//        }
//        else
//        {
//            echo 0;
//        }
//    }

    
    public function js_save_table($id = '')
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost())
        {
            if ($id)
            {
                $this->data['RandomAniTable']['id'] = $id;
                $count = $this->RandomAniTable->find('first', array('conditions' => array("id !=" . $id, 'name' => $this->data['RandomAniTable']['name'])));
                if ($count)
                {
                    $this->Session->write('m', $this->RandomAniTable->create_json(101, sprintf(__('The name[%s] has already been taken',true),$this->data['RandomAniTable']['name'])));
                    $this->redirect("random_table");
                }
            }
            else
            {
                $count = $this->RandomAniTable->find('first', array('conditions' => array('name' => $this->data['RandomAniTable']['name'])));
                if ($count)
                {
                    $this->Session->write('m', $this->RandomAniTable->create_json(101, __('The name [' . $this->data['RandomAniTable']['name'] . '] has already been taken', true)));
                    $this->redirect("random_table");
                }
            }
            $this->data['RandomAniTable']['create_time'] = date("Y-m-d H:i:sO", time());
            $flg = $this->RandomAniTable->save($this->data);
            if ($flg === false)
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(101, __('Failed!', true)));
            }
            else
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(201, __('The ANI [' . $this->data['RandomAniTable']['name'] . '] is added successfully', true)));
            }
            $this->redirect("random_table");
        }
        if ($id)
        {
            $this->data = $this->RandomAniTable->find('first', Array('conditions' => Array('id' => $id)));
        }
    }

    public function delete_table($encode_random_table_id)
    {
        Configure::write('debug', 0);
        $random_table_id = base64_decode($encode_random_table_id);
	$tableName = $this->RandomAniTable->findById($random_table_id)['RandomAniTable']['name'];
        $flg = $this->RandomAniTable->del($random_table_id);
        if ($flg === false)
        {
            $this->Session->write('m', $this->RandomAniTable->create_json(101, __('Failed!', true)));
        }
        else
        {
            $this->Session->write('m', $this->RandomAniTable->create_json(201, __('The ANI [' . $tableName . '] is deleted successfully', true)));
        }
        $this->redirect("random_table");
    }

    public function delete_alltable()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $flg = $this->RandomAniTable->deleteAll('1=1');
        if ($flg === false)
        {
            $this->Session->write('m', $this->RandomAniTable->create_json(101, __('Failed!', true)));
        }
        else
        {
            $this->Session->write('m', $this->RandomAniTable->create_json(201, __('All ANI are deleted successfully!', true)));
        }
        $this->redirect("random_table");
    }

    public function delete_selectedtable()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $ids = $this->params['url']['ids'];
        if ($ids)
        {
            $flg = $this->RandomAniTable->query("DELETE FROM random_ani_table WHERE id in ($ids)");
            if ($flg === false)
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(101, __('Failed!', true)));
            }
            else
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(201, __('Selected ANI are deleted successfully!', true)));
            }
        }
        else
        {
            $this->Session->write('m', $this->RandomAniTable->create_json(101, __('Failed!', true)));
        }
        $this->redirect("random_table");
    }

    public function random_generation($encode_random_table_id)
    {
        $random_table_id = base64_decode($encode_random_table_id);
        $random_table = $this->RandomAniTable->findById($random_table_id);
        $this->set('random_table', $random_table);
        $this->pageTitle = "Switch/Random ANI Group";
        $get_data = $this->params['url'];

        $order_arr = array('RandomAniGeneration.id' => 'ASC');
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
        $this->paginate = array(
            'limit' => 100,
            'order' => $order_arr,
            'conditions' => array(
                "RandomAniGeneration.random_table_id = $random_table_id",
            ),
        );

        if (isset($get_data['name']))
        {
//            array_push($this->paginate['conditions'], "RandomAniGeneration.ani_number ilike '%{$get_data['operator']}%'");
        }


        $this->data = $this->paginate('RandomAniGeneration');
    }

    public function js_save_generation($random_table_id, $id = '')
    {
        Configure::write('debug', 0);
        if (empty($random_table_id))
        {
            $this->redirect("random_table");
        }
        if ($this->RequestHandler->isPost())
        {
            $this->data['RandomAniGeneration']['random_table_id'] = $random_table_id;
            $action = __('created',true);
            if ($id)
            {
                $this->data['RandomAniGeneration']['id'] = $id;
                $count = $this->RandomAniGeneration->find('first', array('conditions' => array("id !=" . $id, 'ani_number' => $this->data['RandomAniGeneration']['ani_number'])));
                if ($count)
                {
                    $this->redirect("random_generation/" . base64_encode($random_table_id));
                }
                $action = __('modified',true);
            }
	    $alreadyExist = $this->RandomAniGeneration->find('count', array('conditions' => array('ani_number' => $this->data['RandomAniGeneration']['ani_number'])));
	 if (!$alreadyExist) {
            $flg = $this->RandomAniGeneration->save($this->data);
            $function_name = __('ANI Number',true);
            $name = $this->data['RandomAniGeneration']['ani_number'];
            if ($flg === false)
                $this->Session->write('m', $this->RandomAniGeneration->create_json(101, sprintf(__('The %s [%s] is %s Failed!', true),$function_name,$name,$action)));
            else
                $this->Session->write('m', $this->RandomAniGeneration->create_json(201, sprintf(__('The %s [%s] is %s successfully!', true),$function_name,$name,$action)));
         } else {
    		$this->Session->write('m', $this->RandomAniGeneration->create_json(101, sprintf(__('The ANI Number [' . $this->data['RandomAniGeneration']['ani_number'] . '] is already exist!', true))));
	 }	
	$this->redirect("random_generation/" . base64_encode($random_table_id));
        }
        if ($id)
        {
            $this->data = $this->RandomAniGeneration->find('first', Array('conditions' => Array('id' => $id)));
        }
    }

    public function delete_generation($encode_random_table_id, $encode_id)
    {
        Configure::write('debug', 0);
        $id = base64_decode($encode_id);
	$ragName = $this->RandomAniGeneration->findById($id)['RandomAniGeneration']['ani_number'];
        $flg = $this->RandomAniGeneration->del($id);
        if ($flg === false)
        {
            $this->Session->write('m', $this->RandomAniGeneration->create_json(101, __('Failed!', true)));
        }
        else
        {
            $this->Session->write('m', $this->RandomAniGeneration->create_json(201, __('The ANI [' . $ragName . '] is deleted successfully!', true)));
        }
        $this->redirect("random_generation/" . $encode_random_table_id);
    }

    public function delete_all_generation($random_table_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $flg = $this->RandomAniGeneration->query("DELETE FROM random_ani_generation WHERE random_table_id = {$random_table_id}");
        if ($flg === false)
            $this->Session->write('m', $this->RandomAniGeneration->create_json(101, __('Delete Failed!', true)));
        else
            $this->Session->write('m', $this->RandomAniGeneration->create_json(201, __('All ANI are deleted successfully!', true)));
        $this->redirect("random_generation/" . base64_encode($random_table_id));
    }

    public function delete_selected_generation($random_table_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $ids = $this->params['url']['ids'];
        $flg = false;
        if ($ids)
            $flg = $this->RandomAniGeneration->query("DELETE FROM random_ani_generation WHERE random_table_id = {$random_table_id} AND id in ($ids) ");
        if ($flg === false)
            $this->Session->write('m', $this->RandomAniGeneration->create_json(101, __('Delete Failed!', true)));
        else
            $this->Session->write('m', $this->RandomAniGeneration->create_json(201, __('Delete Succeed!', true)));
        $this->redirect("random_generation/" . base64_encode($random_table_id));
    }

    public function auto_populate($encode_random_table_id)
    {
        $random_table_id = base64_decode($encode_random_table_id);
        $random_table = $this->RandomAniTable->findById($random_table_id);
        $this->set('random_table', $random_table);
        $this->pageTitle = "Switch/Random ANI GROUP";
        if ($this->RequestHandler->ispost())
        {
            $data = $this->params['form'];
            if (!$this->isnotEmpty($data, array('prefix', 'number_of_digits')))
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(101, __('Failed', true)));
                $this->redirect("auto_populate/$encode_random_table_id");
            }
            $prefix = $data['prefix'];
            $number_of_digits = $data['number_of_digits'];
            $pattern = '/^0-9a-zA-Z$/';
            $prefix_flg = preg_match($pattern, $prefix);
            if ($prefix_flg === false)
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(101, __("prefix match error", true)));
                $this->redirect("auto_populate/$encode_random_table_id");
            }

            $pattern2 = '/^[1-9]$|^1[0-9]$|^(20)$/';
            $digits_flg = preg_match($pattern2, $number_of_digits);
            if ($digits_flg === false)
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(101, __("number_of_digits match error", true)));
                $this->redirect("auto_populate/$encode_random_table_id");
            }

            $total_length = strlen($prefix) + intval($number_of_digits);
            if ($total_length > 32)
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(101, __("ANI length greater than 32", true)));
                $this->redirect("auto_populate");
            }

            $log_sql = "INSERT INTO random_ani_populated_log (start_time,prefix,number_of_digits,random_table_id,status) "
                    . "VALUES(CURRENT_TIMESTAMP(0),'{$prefix}',{$number_of_digits},{$random_table_id},0) returning id";
            $log = $this->RandomAniTable->query($log_sql);
            if ($log === false)
            {
                $this->Session->write('m', $this->RandomAniTable->create_json(101, __('log failed', true)));
                $this->redirect("auto_populate");
            }
            $log_id = $log[0][0]['id'];
            $php_path = Configure::read('php_exe_path');
            $cmd = "{$php_path} " . APP . "../cake/console/cake.php ani_populate {$log_id} > /tmp/test & echo $!";

            $info = $this->Systemparam->find('first',array(
                'fields' => array('cmd_debug'),
            ));

            if (Configure::read('cmd.debug'))
                file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
            $pid = shell_exec($cmd);
            $update_log_sql = "UPDATE random_ani_populated_log SET pid = $pid WHERE id = {$log_id}";
            $this->RandomAniTable->query($update_log_sql);
            $pid = intval($pid);
            $this->Session->write('m', $this->RandomAniTable->create_json(201, sprintf(__('You Job [#%s] is scheduled to execute in the queue.',true),$pid)));
            $this->redirect("auto_populate_log/$encode_random_table_id");
        }
    }

    public function auto_populate_log($encode_random_table_id = "")
    {
        $order_arr = array('RandomAniPopulatedLog.start_time' => 'desc');
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
        $this->paginate = array(
            'fields' => array(
                'RandomAniPopulatedLog.*', 'RandomAniTable.name'
            ),
            'limit' => 100,
            'order' => $order_arr,
            'conditions' => array(),
            'joins' => array(
                array(
                    'table' => 'random_ani_table',
                    'alias' => "RandomAniTable",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RandomAniTable.id = RandomAniPopulatedLog.random_table_id',
                    ),
                )
            )
        );


        if ($encode_random_table_id)
        {
            $random_table_id = base64_decode($encode_random_table_id);
            array_push($this->paginate['conditions'], array('random_table_id' => $random_table_id));
            $random_table = $this->RandomAniTable->findById($random_table_id);
            $this->set('random_table', $random_table);
        }

        $this->data = $this->paginate('RandomAniPopulatedLog');
        $status = array("Waiting", "In Progress", "Done", 'Killed');
        $this->set('status', $status);
    }

    public function auto_populate_kill($encode_log_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $log_id = base64_decode($encode_log_id);
        $log_info = $this->RandomAniPopulatedLog->findById($log_id);
        $random_table_id = $log_info['RandomAniPopulatedLog']['random_table_id'];
        if (in_array($log_info['RandomAniPopulatedLog']['status'], array(0, 1)))
        {
            $pid = $log_info['RandomAniPopulatedLog']['pid'];
            $cmd = "kill -9 $pid";
            shell_exec($cmd);
            $update_log_sql = "UPDATE random_ani_populated_log SET status = 3 WHERE id = {$log_id}";
            $this->RandomAniPopulatedLog->query($update_log_sql);
        }
            $this->Session->write('m', $this->RandomAniPopulatedLog->create_json(201, __('success', true)));
        $this->redirect("auto_populate_log/".base64_encode($random_table_id));
    }

}

?>
