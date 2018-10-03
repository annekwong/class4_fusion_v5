<?php

class ReBalanceShell extends Shell
{

    var $uses = array('RerateCdrTask','RerateReportExecLog');

    function main()
    {
        $task_id = isset($this->args[0]) ? (int) $this->args[0] : "";
        $log_id = isset($this->args[1]) ? (int) $this->args[1] : "";
        if (!$task_id || !$log_id)
        {
            echo "error";
            return;
        }

        $log_info = $this->getExecLog($log_id);
        if (empty($log_info))
        {
            echo "Log not exist";
            return;
        }
        $this->saveExecLogStatus($log_id,1);
        $this->saveTaskStatus($task_id,10);

        $task_info = $this->getTaskInfo($task_id);
        if (empty($task_info))
        {
            echo "task not exist";
            return;
        }

        $start_time = date('Y-m-d H:i:sO',$task_info['RerateCdrTask']['from_time']);
        $end_time = date('Y-m-d H:i:sO',$task_info['RerateCdrTask']['to_time']);
        $client_ids = $task_info['RerateCdrTask']['client_ids'];
        if (!$client_ids)
        {
            echo "Client id is Null";
            return;
        }
//        perl class4_total_balance.pl -i client_id -s start_time -e end_time
        $client_arr = explode(',',$client_ids);
        $this->saveExecLog($log_id,0,count($client_arr));
        $this->saveExecLogStatus($log_id,2);
        $this->saveTaskStatus($task_id,11);

    }

    function getTaskInfo($task_id)
    {
        return $this->RerateCdrTask->find('first',array(
            'conditions' => array(
                'id' => $task_id,
            ),
        ));
    }


    function saveTaskStatus($task_id,$status)
    {
        $save_data = array(
            'status' => $status,
            'id' => $task_id
        );
        $this->RerateCdrTask->save($save_data);
    }

    function getExecLog($log_id)
    {
        return $this->RerateReportExecLog->find('first',array(
            'conditions' => array(
                'id' => $log_id,
            ),
        ));
    }


    function saveExecLogStatus($log_id,$status)
    {
        $save_data = array(
            'status' => $status,
            'id' => $log_id
        );
        if ($status == 1)
            $save_data['start_time'] = date('Y-m-d H:i:sO');
        if ($status == 2)
            $save_data['finish_time'] = date('Y-m-d H:i:sO');
        $this->RerateReportExecLog->save($save_data);
    }

    function saveExecLog($log_id,$success_count,$total_count = '')
    {
        $save_data = array(
            'success_files_count' => $success_count,
            'id' => $log_id
        );
        if ($total_count)
            $save_data['total_files_count'] = $total_count;

        $this->RerateReportExecLog->save($save_data);
    }


}
