<?php

class TaskSchedulerController extends AppController
{

    var $name = "TaskScheduler";
    var $uses = array('TaskScheduler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->checkSession("login_type");
    }

    public function index()
    {
//        var_dump(pathinfo(ROOT));exit;
//        var_dump(realpath(ROOT)); die;
//        App::import("Vendor", "crontab", array('file' => "crontab.php"));
//        $crontab = new Crontab();
//        $crontab_arr = $crontab->get_list();

        $this->refresh_all();
          
        $order_sql = " scheduler.id ASC";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            $field = $order_arr[0];
            $sort = $order_arr[1];
            $order_sql = "scheduler.{$field} {$sort}";
//            $condition = array('order' => array("{$order_sql}"));
        }

        $sql = "SELECT *, (select start_time from scheduler_log as log where log.script_name = scheduler.name order by id desc limit 1) "
                . "as last_run FROM scheduler AS scheduler   WHERE 1 = 1   ORDER BY {$order_sql}";
        $taskSchedulers = $this->TaskScheduler->query($sql);
        $days = array(
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        );

        foreach ($taskSchedulers as &$taskScheduler)
        {
            $times_arr = array();

            if ($taskScheduler[0]['minute_type'] == 0)
            {
                array_push($times_arr, "every {$taskScheduler[0]['minute']} minute(s)");
            }
            else
            {
                $minute = (int) $taskScheduler[0]['minute'];
                array_push($times_arr, "{$minute} minute(s)");
            }

            if ($taskScheduler[0]['hour_type'] == 0)
            {
                array_push($times_arr, "every {$taskScheduler[0]['hour']} hour(s)");
            }
            else
            {
                $hour = (int) $taskScheduler[0]['hour'];
                array_push($times_arr, "{$hour} hour(s)");
            }

            if ($taskScheduler[0]['day_type'] == 0)
            {
                array_push($times_arr, "every {$taskScheduler[0]['day']} day(s)");
            }
            else
            {
                $day = (int) $taskScheduler[0]['day'];
                array_push($times_arr, "{$day} day(s)");
            }

            if ($taskScheduler[0]['week'] != NULL)
            {
                array_push($times_arr, $days[$taskScheduler[0]['week']]);
            }

            $taskScheduler[0]['run_at'] = implode(', ', $times_arr);
        }

        $this->set('taskSchedulers', $taskSchedulers);
    }

    public function edit($task_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $taskScheduler = $this->TaskScheduler->findById($task_id);
        $script_name = $taskScheduler['TaskScheduler']['name'];
        $scheduler_log = $this->TaskScheduler->query("select start_time from scheduler_log as log where log.script_name = '{$script_name}' order by id desc limit 1");
        if(isset($scheduler_log[0][0]['start_time']) && $scheduler_log[0][0]['start_time'])
        {
            $taskScheduler['TaskScheduler']['last_run'] = $scheduler_log[0][0]['start_time'];
        }
        if ($this->RequestHandler->isPost())
        {
            $this->data['TaskScheduler']['id'] = $task_id;
            if (!$this->data['TaskScheduler']['minute'])
            {
                $this->data['TaskScheduler']['minute'] = null;
            }
            if (!$this->data['TaskScheduler']['hour'])
            {
                $this->data['TaskScheduler']['hour'] = null;
            }
            if (!$this->data['TaskScheduler']['day'])
            {
                $this->data['TaskScheduler']['day'] = null;
            }
            $old_active = $taskScheduler['TaskScheduler']['active'];
            if ($old_active)
            {
//                先删除 
                if (!$this->refresh($task_id, "remove"))
                {
                    $this->TaskScheduler->create_json_array("", 101, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is modified failed !');
                    $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index'));
                }
                else
                {
                    $this->TaskScheduler->query("UPDATE scheduler SET active = false WHERE id = {$task_id}");
                }

//              添加新部分 
                if (isset($this->data['TaskScheduler']['active']))
                {
                    $this->TaskScheduler->begin();
                    if ($this->TaskScheduler->save($this->data))
                    {
                        if (!$this->refresh($task_id, "append"))
                        {
                            $this->TaskScheduler->rollback();
                        }
                        $this->TaskScheduler->commit();
                    }
                    else
                    {
                        $this->TaskScheduler->create_json_array("", 101, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is modified failed !');
                        $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index'));
                    }
                }
                else
                {
                    if (!$this->TaskScheduler->save($this->data))
                    {
                        $this->TaskScheduler->create_json_array("", 101, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is modified failed !');
                        $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index'));
                    }
                }
            }
            else
            {
                $this->TaskScheduler->begin();
                if ($this->TaskScheduler->save($this->data))
                {
                    if (isset($this->data['TaskScheduler']['active']))
                    {
                        if (!$this->refresh($task_id, "append"))
                        {
                            $this->TaskScheduler->rollback();
                        }
                    }
                    $this->TaskScheduler->commit();
                }
                else
                {
                    $this->TaskScheduler->create_json_array("", 101, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is modified failed !');
                    $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index'));
                }
            }

            $this->TaskScheduler->create_json_array("", 201, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is modified successfully !');
            $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index'));
        }
        $this->set('taskScheduler', $taskScheduler);
    }

    public function change_status($task_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $task_id = base64_decode($task_id);
        $taskScheduler = $this->TaskScheduler->findById($task_id);

        if ($taskScheduler['TaskScheduler']['active'])
        {
            $taskScheduler['TaskScheduler']['active'] = false;
            $refresh_flg = "remove";
            $flg = "inactived";
        }
        else
        {
            $refresh_flg = "append";
            $taskScheduler['TaskScheduler']['active'] = true;
            $flg = "actived";
        }
        $error = "";
        $this->TaskScheduler->begin();
        if ($this->TaskScheduler->save($taskScheduler))
        {
            if (!$this->refresh($task_id, $refresh_flg))
            {
                $error = 1;
                $this->TaskScheduler->rollback();
            }
        }
        $this->TaskScheduler->commit();

        if ($error)
        {
            $this->TaskScheduler->create_json_array("", 101, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is ' . $flg . ' failed !');
        }
        else
        {
            $this->TaskScheduler->create_json_array("", 201, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is ' . $flg . ' successfully !');
        }
        $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index'));
    }

    public function run($task_id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $task_id = base64_decode($task_id);
        $taskScheduler = $this->TaskScheduler->findById($task_id);
        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        $cmd = "perl {$script_path}/{$taskScheduler['TaskScheduler']['script_name']} -c {$script_conf} -a > /dev/null 2 >&1 &";
        $file_name = "{$taskScheduler['TaskScheduler']['script_name']}";
        $file_flg = is_file("{$script_path}/{$taskScheduler['TaskScheduler']['script_name']}");
        if (!$file_flg)
        {
            $user_name = $_SESSION['sst_user_name'];
            $detail = "{$file_name} is not exsit!";
            $sql = "INSERT INTO error_log (detail,users) VALUES('{$detail}','{$user_name}') RETURNING id";
            $insert_flg = $this->TaskScheduler->query($sql, false);
            if ($insert_flg)
            {
                $this->send_error_email($insert_flg[0][0]['id'], 1);
            }
            $this->TaskScheduler->create_json_array("", 101, 'The file is not exsit and Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is run failed !');
            $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index'));
        }
        shell_exec($cmd);
        $this->TaskScheduler->create_json_array("", 201, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is run successfully !');
        $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index'));
    }

    public function send_error_email($id, $first_flg = null)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$first_flg)
        {
            $id = base64_decode($id);
        }
        $email_info = $this->TaskScheduler->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
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
//                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = $email_info[0][0]['realm'];
                $mailer->Workstation = $email_info[0][0]['workstation'];
        }
        $mailer->IsHTML(true);
        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtphost'];
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        $send_address = "SUPPORT@DENOVOLAB.com";
        //$send_address = "huangzj@mail.yht.com";
        $mailer->AddAddress($send_address);

        $sql = "SELECT detail,users,error_time FROM error_log WHERE id = {$id}";
        $log_data = $this->TaskScheduler->query($sql, false);

        $content = "{$log_data[0][0]['error_time']}, user '{$log_data[0][0]['users']}' open the script, the error occurred: '{$log_data[0][0]['detail']}'";
        $mailer->Subject = "Error opening the script";
        $mailer->Body = $content;
        $sent_flg = $mailer->Send();
        if ($sent_flg)
        {
            $sql = "UPDATE error_log set sent = true WHERE id = {$id}";
            $this->TaskScheduler->query($sql, false);
        }
        if ($sent_flg && !$first_flg)
        {
            $this->TaskScheduler->create_json_array("", 201, 'The email is sent successfully!');
            $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'error_log'));
        }
        elseif (!$sent_flg && !$first_flg)
        {
            $this->TaskScheduler->create_json_array("", 101, 'The email is sent failed!');
            $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'error_log'));
        }
        return;
    }

    public function error_log()
    {
        $this->pageTitle = 'Log/error_log';

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
        $get_data = $this->params['url'];

        $this->set('get_data', $get_data);

        $where_sql = " WHERE 1=1 ";

        //pr($get_data);die;
        //var_dump(!strcmp($get_data['status'], '0'));

        if (isset($get_data['status']) && !strcmp($get_data['status'], '0'))
        {
            $where_sql .= ' AND sent = false';
        }
        else if (isset($get_data['status']) && !strcmp($get_data['status'], '1'))
        {
            $where_sql .= ' AND sent = true';
        }
//        pr($get_data);

        if (isset($get_data['time_start']) && $get_data['time_start'])
        {
            $where_sql .= " AND error_time >= '" . $get_data['time_start'] . "'";
        }

        if (isset($get_data['time_end']) && $get_data['time_end'])
        {
            $where_sql .= " AND error_time <= '" . $get_data['time_end'] . "'";
        }


        $sql = "select count(*) from error_log {$where_sql}";
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->TaskScheduler->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT * from error_log {$where_sql} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";

        $data = $this->TaskScheduler->query($sql);
        $page->setDataArray($data);

        $this->set('p', $page);
    }

    public function refresh($id = "", $refresh_flg = "")
    {
//        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        if (empty($id) || !in_array($refresh_flg, array('remove', 'append')))
        {
            return FALSE;
        }
        $taskScheduler = $this->TaskScheduler->find(array('id' => $id));
        $timeCode = array();
        if ($taskScheduler['TaskScheduler']['minute_type'] == 0)
            if (empty($taskScheduler['TaskScheduler']['minute']) || $taskScheduler['TaskScheduler']['minute'] == '*')
                array_push($timeCode, "*");
            else
                array_push($timeCode, "*/{$taskScheduler['TaskScheduler']['minute']}");
        else
        {
            $minute = (int) $taskScheduler['TaskScheduler']['minute'];
            array_push($timeCode, "{$minute}");
        }
        if ($taskScheduler['TaskScheduler']['hour_type'] == 0)
            if (empty($taskScheduler['TaskScheduler']['hour']) || $taskScheduler['TaskScheduler']['hour'] == '*')
                array_push($timeCode, "*");
            else
                array_push($timeCode, "*/{$taskScheduler['TaskScheduler']['hour']}");
        else
        {
            $hour = (int) $taskScheduler['TaskScheduler']['hour'];
            array_push($timeCode, "{$hour}");
        }
        if ($taskScheduler['TaskScheduler']['day_type'] == 0)
            if (empty($taskScheduler['TaskScheduler']['day']) || $taskScheduler['TaskScheduler']['day'] == '*')
                array_push($timeCode, "*");
            else
                array_push($timeCode, "*/{$taskScheduler['TaskScheduler']['day']}");
        else
        {
            $day = (int) $taskScheduler['TaskScheduler']['day'];
            array_push($timeCode, "{$day}");
        }

        array_push($timeCode, "*");
        if (empty($taskScheduler['TaskScheduler']['week']) || $taskScheduler['TaskScheduler']['week'] == '*')
            array_push($timeCode, "*");
        else
            array_push($timeCode, "{$taskScheduler['TaskScheduler']['week']}");

        $cronjob_time = implode(" ", $timeCode);

        $script_name = $taskScheduler['TaskScheduler']['script_name'];

        $pos = strrpos($script_name, ".") + 1;
        $suffix = substr($script_name, $pos);

        $error = "";
        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        $root_path_info = pathinfo(ROOT);
        $root_path = $root_path_info['dirname'];
        switch ($suffix)
        {
            case "pl":
                $cronjob_cmd = "perl {$script_path}/{$script_name} -c {$script_conf} -a > /dev/null 2>&1";
                break;
            case "py":
                $cronjob_cmd = "python3 {$script_path}/{$script_name} -c {$script_conf} -a > /dev/null 2>&1";
                break;
            case "php":
                $cronjob_cmd = "php {$root_path}/web/app/../cake/console/cake.php {$script_name} -a > /dev/null 2>&1";
                break;
            default :
                $error = 1;
        }
        if ($error)
        {
            return FALSE;
        }
//        var_dump($cronjob_time);echo "  ";
//        var_dump($cronjob_cmd);
//        die;
        App::import("Vendor", "crontab", array('file' => "crontab.php"));
        $crontab = new Crontab();
        if (!strcmp($refresh_flg, "append"))
        {
            $result = $crontab->append_cronjob($cronjob_time, $cronjob_cmd);
        }
        else
        {
            if (!strcmp($cronjob_time, "0 * * * *"))
            {
                $cronjob_time = "@hourly";
            }
            if (!strcmp($cronjob_time, "0 0 * * *"))
            {
                $cronjob_time = "@daily";
            }
            $result = $crontab->remove_cronjob($cronjob_time, $cronjob_cmd);
        }
        var_dump($cronjob_time);
        echo "<br />";
        var_dump($cronjob_cmd);
        return $result;
    }

    public function refresh_all()
    {
//        Configure::write('debug', 0);
//        $this->autoLayout = false;
//        $this->autoRender = false;

        App::import("Vendor", "crontab", array('file' => "crontab.php"));
        $crontab = new Crontab();
        $error_flg = $crontab->get_error();
        if ($error_flg)
        {
            $this->TaskScheduler->query("UPDATE scheduler SET active = false");
            $this->TaskScheduler->create_json_array("", 101, $error_flg);
            $crontab_arr = array();
        }
        else
        {
            $crontab_arr = $crontab->get_list();
            $this->TaskScheduler->query("UPDATE scheduler SET active = false");
        }
        $taskSchedulers = $this->TaskScheduler->find('all', array(
            'order' => array('TaskScheduler.id'),
//            'conditions' => array('TaskScheduler.active' => true),
        ));

        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
//        pr($crontab_arr);
//        pr($taskSchedulers);
//        die;


        foreach ($taskSchedulers as $taskScheduler)
        {
            $script_name = $taskScheduler['TaskScheduler']['script_name'];
            $id = $taskScheduler['TaskScheduler']['id'];
            foreach ($crontab_arr as $key => $value)
            {
//                pr($crontab_arr);
                $command = $value['command'];
//                echo $command."<br />";
//                echo $script_name."<br />";
//                var_dump(strpos($command, $script_name));
//                echo "<hr />";
                if (strpos($command, $script_name) !== FALSE)
                {
                    unset($crontab_arr[$key]);
                    $this->TaskScheduler->query("UPDATE scheduler SET active = true WHERE id = {$id}");
                    $time_arr = explode(" ", $value['time']);
                    $time_save_arr = $this->translate_crontab_time($time_arr);
                    $set_sql = array();
                    foreach ($time_save_arr as $time_save_key => $time_save_value)
                    {
                        if (empty($time_save_value))
                        {
                            $time_save_value = 'null';
                        }
                        $set_sql[] = $time_save_key . " = " . $time_save_value;
                    }
                    $set_sql = "SET " . implode(",", $set_sql);
                    $this->TaskScheduler->query("UPDATE scheduler {$set_sql} WHERE id = {$id}");
                    break;
                }
            }
        }
    }

    public function translate_crontab_time($crontab_time_arr)
    {
        $return_arr = array(
            'minute_type' => '',
            'minute' => '',
            'hour_type' => '',
            'hour' => '',
            'day_type' => '',
            'day' => '',
            'week' => NULL,
        );

        $type_arr = array(
            'minute', 'hour', 'day', '', 'week'
        );
        if (!strcmp($crontab_time_arr[0], "@daily"))
        {
            $crontab_time_arr = array('0', '0', '*', '*', '*');
        }
        elseif (!strcmp($crontab_time_arr[0], "@hourly"))
        {
            $crontab_time_arr = array('0', '*', '*', '*', '*');
        }
        if (count($crontab_time_arr) != 5)
        {
            return $return_arr;
        }

        //pr($crontab_time_arr);echo "<br />";
        foreach ($crontab_time_arr as $key => $value)
        {
            if ($type_arr[$key])
            {
                if (strcmp("week", $type_arr[$key]))
                {
                    $type = $type_arr[$key] . "_type";
                    $pos = strpos($value, "*/");
//                    var_dump($value);
//                    var_dump($pos);
                    if ($pos !== false)
                    {
                        $return_arr[$type] = 0;
                        $return_arr[$type_arr[$key]] = substr($value, 2);
//                        var_dump(substr($value, $pos));
//                        echo " <br />";
                    }
                    elseif (!strcmp($value, "*"))
                    {
                        $return_arr[$type] = 0;
                        $return_arr[$type_arr[$key]] = null;
                    }
                    elseif (intval($value) == $value)
                    {
                        $return_arr[$type] = 1;
                        $return_arr[$type_arr[$key]] = intval($value);
                    }
                }
                else
                {// 当是第五个 控制week时 
                    if (strcmp($value, "*"))
                    {
                        $return_arr['week'] = $value;
                    }
                }
            }
        }

        return $return_arr;
    }

    public function scheduler_log()
    {
        $this->pageTitle = 'Log/Scheduler Log';

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
        $get_data = $this->params['url'];
        $where_sql = "";

        //pr($get_data);die;
//        pr($get_data);

        if (isset($get_data['start_time_start']) && $get_data['start_time_start'])
        {
            $where_sql .= " AND start_time >= '" . $get_data['start_time_start'] . "'";
        }

        if (isset($get_data['start_time_end']) && $get_data['start_time_end'])
        {
            $where_sql .= " AND start_time <= '" . $get_data['start_time_end'] . "'";
        }

        if (isset($get_data['end_time_start']) && $get_data['end_time_start'])
        {
            $where_sql .= " AND end_time >= '" . $get_data['end_time_start'] . "'";
        }

        if (isset($get_data['end_time_end']) && $get_data['end_time_end'])
        {
            $where_sql .= " AND end_time <= '" . $get_data['end_time_end'] . "'";
        }

        if (isset($get_data['script_name']) && $get_data['script_name'])
        {
            $where_sql .= " AND script_name = '" . $get_data['script_name'] . "'";
        }

        $time = date("Y-m-d H:i:s", time());
        $start_time = date("Y-m-d", time()) . " 00:00:00";
        if (empty($where_sql))
        {
            $where_sql = "WHERE start_time <= '{$time}' AND start_time >= '{$start_time}'";
            $get_data['start_time_start'] = $start_time;
            $get_data['start_time_end'] = $time;
        }
        else
        {
            $where_sql = "WHERE 1=1 " . $where_sql;
        }

        $sql = "select count(*) from scheduler_log {$where_sql}";
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->TaskScheduler->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT * from scheduler_log {$where_sql} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";

        $data = $this->TaskScheduler->query($sql);
        $page->setDataArray($data);

        $this->set('p', $page);
        $this->set('get_data', $get_data);
    }

    public function scheduler_log_by_name()
    {
//         $this->pageTitle = 'Log/Scheduler Log';
        $get_data = $this->params['url'];
        $where_sql = "";

        //pr($get_data);die;
//        pr($get_data);

        if (isset($get_data['start_time_start']) && $get_data['start_time_start'])
        {
            $where_sql .= " AND start_time >= '" . $get_data['start_time_start'] . "'";
        }

        if (isset($get_data['start_time_end']) && $get_data['start_time_end'])
        {
            $where_sql .= " AND start_time <= '" . $get_data['start_time_end'] . "'";
        }

        if (isset($get_data['end_time_start']) && $get_data['end_time_start'])
        {
            $where_sql .= " AND end_time >= '" . $get_data['end_time_start'] . "'";
        }

        if (isset($get_data['end_time_end']) && $get_data['end_time_end'])
        {
            $where_sql .= " AND end_time <= '" . $get_data['end_time_end'] . "'";
        }

        $time = date("Y-m-d H:i:s", time());
        $start_time = date("Y-m-d", time()) . " 00:00:00";
        if (empty($where_sql))
        {
            $where_sql = "WHERE start_time <= '{$time}' AND start_time >= '{$start_time}'";
            $get_data['start_time_start'] = $start_time;
            $get_data['start_time_end'] = $time;
        }
        else
        {
            $where_sql = "WHERE 1=1 " . $where_sql;
        }

        $sql = "SELECT distinct(script_name) as name FROM scheduler_log {$where_sql}";
        $script_name_arrs = $this->TaskScheduler->query($sql);
//        pr($script_name_arrs);

        $result = array();
        foreach ($script_name_arrs as $script_name_item)
        {
            $script_name = $script_name_item[0]['name'];
            $sql = "SELECT * FROM scheduler_log WHERE script_name = '{$script_name}' order by id desc limit 1";
            $data = $this->TaskScheduler->query($sql);
            array_push($result, $data[0]);
        }
//        pr($result);die;
        $this->set('data', $result);
        $this->set('get_data', $get_data);
    }

}
