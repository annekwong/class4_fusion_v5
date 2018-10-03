<?php

class AgentCommissionHistory extends AppModel {
    var $name = 'AgentCommissionHistory';
    var $useTable = "agent_commission_history";
    var $primaryKey = "history_id";
    var $hasMany = Array(
        'detail' => array(
            'className' => 'AgentCommissionHistoryDetail',
            'foreignKey' => 'history_id',
        ),
        'payment' => array(
            'className' => 'AgentCommissionPayment',
            'foreignKey' => 'history_id',
        ),
    );

    public function judge_pay_status($history_id)
    {
        $sql = "select sum(amount) as total_pay FROM agent_commission_payment WHERE history_id = " .$history_id;
        $data = $this->query($sql,false);
        $total_pay = $data ? $data[0][0]['total_pay']: 0;
        return $this->find('count',array(
            'conditions' => array(
                'history_id' => $history_id,
                'amount <= ?' => $total_pay
            ),
        ));
    }

    public function change_status_by_pay($history_id)
    {
        $count = $this->judge_pay_status($history_id);
        if ($count)
            return $this->save(array('history_id' => $history_id,'finished' => true));
        else
            return $this->save(array('history_id' => $history_id,'finished' => false));
    }
}

