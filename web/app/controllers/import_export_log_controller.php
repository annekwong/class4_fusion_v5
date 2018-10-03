<?php

class ImportExportLogController extends AppController
{

    var $name = 'ImportExportLog';
    var $helpers = array("AppImportExportLog", 'Paginator');
    var $uses = array('ImportExportLog');
    var $components = array();

    function index()
    {
        $this->redirect('import');
    }

    function killed($id, $pid)
    {
        if (!$pid || !$id)
        {
            $this->Session->write('m', $this->ImportExportLog->create_json(101, 'job ID is not exist'));
            $this->redirect('import');
        }

        $out = shell_exec("kill -9 {$pid}");

        if ($out)
        {
            $this->Session->write('m', $this->ImportExportLog->create_json(101, 'shell failed'));
            $this->redirect('import');
        }

        //添加完成时间 修改状态
        $result = $this->ImportExportLog->modifyStatusAndEndtime($id, $pid);

        if ($result)
        {
            $this->Session->write('m', $this->ImportExportLog->create_json(201, 'killed'));
        }
        else
        {
            $this->Session->write('m', $this->ImportExportLog->create_json(101, 'failed'));
        }
        $this->redirect('import');
    }

    public function test()
    {
        $list = $this->ImportExportLog->find_all_process_log();
        pr($list);
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份exprot
        parent::beforeFilter(); //调用父类方法
    }

    function _render_import_conditions($options = Array())
    {

        $filter_conditions = $this->_filter_conditions(Array('search'));
        if (!is_array($filter_conditions))
        {
            $filter_conditions = Array($filter_conditions);
        }
        $conditions = array_merge(array('log_type' => ImportExportLog::LOG_TYPE_IMPORT), $filter_conditions, $options);
        //pr($conditions);
        if (isset($this->params['url']['time']) && $this->params['url']['time'])
        {
            $conditions[] = "ImportExportLog.time <= '" . $this->params['url']['time'] . "'";
            $conditions[] = "ImportExportLog.finished_time >= '" . $this->params['url']['time'] . "'";
        }
        $this->set('get_data', $this->params['url']);
        $this->paginate['conditions'] = $conditions;
    }

    function import()
    {
        $this->pageTitle = "Log/Import Log";
        //		$this->set('logs',$this->ImportExportLog->find_all_import());
        $this->paginate['order'] = $this->_order_condtions(array('id', 'time', 'finished_time'));
        $this->_render_import_conditions();
//                echo '<pre>';
//                                pr($this->paginate('ImportExportLog'));die;
        $this->set('logs', $this->paginate('ImportExportLog'));
    }

    function _render_export_data($options = Array())
    {

        $filter_conditions = $this->_filter_conditions(Array('search'));
        if (!is_array($filter_conditions))
        {
            $filter_conditions = array($filter_conditions);
        }
        if ($filter_conditions[0])
        {
            $filter_conditions[0] = "(" . $filter_conditions[0] . ")";
        }
        $user_id = $_SESSION['sst_user_id'];
        $conditions = array_merge(array('log_type' => ImportExportLog::LOG_TYPE_EXPORT, 'ImportExportLog.user_id' => $user_id), $filter_conditions, $options);
        if (isset($this->params['url']['time_start']) && $this->params['url']['time_start'])
        {
            $conditions[] = "ImportExportLog.time >= '" . $this->params['url']['time_start'] . "'";
        }

        if (isset($this->params['url']['time_end']) && $this->params['url']['time_end'])
        {
            $conditions[] = "ImportExportLog.time <= '" . $this->params['url']['time_end'] . "'";
        }
        //pr($conditions);
        $this->set('get_data', $this->params['url']);
        $this->paginate['conditions'] = $conditions;
    }

    function export()
    {
        $this->pageTitle = "Log/Export Log";
        $this->paginate['order'] = $this->_order_condtions(array('id', 'time'));
        $this->_render_export_data();
        $this->set('logs', $this->paginate('ImportExportLog'));
        $db_path = Configure::read('database_export_path');
        $this->set('dbpath', $db_path);
    }

    function _filter_search()
    {
        $search = $this->_get('search');
        if (!empty($search))
        {
            return "User.name like '%$search%' or ImportExportLog.obj like '%$search%' or ImportExportLog.duplicate_type like '%$search%'";
        }
        return null;
    }

}

?>
