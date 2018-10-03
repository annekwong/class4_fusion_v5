<?php

class AgentClients extends AppModel {
    var $name = 'AgentClients';
    var $useTable = "agent_clients";
    var $primaryKey = "id";


    function get_method_type()
    {
        $this->method_type = array(
            0 => __('By Margin',true),
            1 => __('By Revenue',true),
        );
        return $this->method_type;
    }

    public function findAgentBalance($agent_id = '')
    {
        if (!$agent_id)
            $agent_id = $_SESSION['sst_agent_info']['Agent']['agent_id'];
        $agent_id = intval($agent_id);

        $sql = <<<SQL
SELECT client.name,balance.balance,client.client_id FROM agent_clients INNER JOIN client ON agent_clients.client_id = client.client_id
INNER JOIN c4_client_balance as balance ON balance.client_id::integer = agent_clients.client_id
WHERE agent_clients.agent_id = $agent_id ORDER BY client.name asc
SQL;
        return $this->query($sql);
    }

    function associateClientToAgent($clientId, $agentId)
    {
        $saveArray = array(
            'agent_id' => $agentId,
            'client_id' => $clientId
        );

        return $this->save($saveArray); 
    }

    function getClientByAgents($agentIds)
    {
        $agentIdStr = "('" . implode("','", $agentIds) . "')";

        $sql = <<<SQL
SELECT client_id FROM agent_clients  where agent_id IN $agentIdStr;
SQL;
        $clients = [];
        $res = $this->query($sql);
        foreach($res as $item){
            $clients[] = $item[0]['client_id'];
        }
        return $clients;
    }


}

