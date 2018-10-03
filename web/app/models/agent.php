<?php

class Agent extends AppModel {
    var $name = 'Agent';
    var $useTable = "agent";
    var $primaryKey = "agent_id";
    
    var $frequency_type = array();
    var $method_type = array();

    function get_frequency_type()
    {
        $this->frequency_type = array(
            0 => __('daily',true),
            1 => __('weekly',true),
            2 => __('monthly',true),
        );
        return $this->frequency_type;
    }

    function get_method_type()
    {
        $this->method_type = array(
            0 => __('By Profit',true),
            1 => __('By Revenue',true),
        );
        return $this->method_type;
    }

    public function get_agents(){
        $sql = "SELECT agent_id, agent_name FROM agent where status = true";
        $res = $this->query($sql);
        $agents = [];
        foreach($res as $item){
            $agents[$item[0]['agent_id']] = $item[0]['agent_name'];
        }
        return $agents;
    }

    function delByClient($client_id)
    {
        $sql = "DELETE FROM agent_client where client_id = $client_id";
        $this->query($sql);
    }

    function change_status_agent($agent_id,$status)
    {
        $agent_info = $this->findByAgentId($agent_id);
        pr($agent_info);
        $this->begin();
        if ($agent_info['Agent']['user_id'])
        {
            $sql = 'UPDATE users SET active = '.$status.' WHERE user_id = '.$agent_info['Agent']['user_id'];
            $flg = $this->query($sql);
            if ($flg === false)
            {
                $this->rollback();
                return false;
            }
        }
        $sql = 'UPDATE agent SET status = '.$status.' WHERE agent_id =' . $agent_id;
        $flg = $this->query($sql);
        if ($flg === false)
        {
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    function findClientsInfo($where,$order_by, $pageSize, $offset)
    {
        $sql = <<<SQL
SELECT Client.name,Client.client_id,Client.status,Client.create_time,Client.company,
(select last_login_time from order_user where client_id = Client.client_id) as last_login_time,Client.allowed_credit,
(SELECT count(*) FROM resource WHERE client_id = client.client_id AND egress = TRUE) as egress_count,
(SELECT count(*) FROM resource WHERE client_id = client.client_id AND ingress = TRUE) as ingress_count,
Client.update_at, Client.update_by,Signup.status as approved,Signup.signup_time as registered_on, Balance.balance as balance
FROM agent_clients as AgentClients
inner join client as Client ON Client.client_id::text  = AgentClients.client_id::text
left join c4_client_balance as Balance on Client.client_id::text  = Balance.client_id::text
left join signup as Signup on AgentClients.agent_id::text  = Signup.agent_assoc_id::text  AND Client.name=Signup.contact_name $where
order by $order_by LIMIT $pageSize OFFSET $offset
SQL;

        return $this->query($sql);

    }

    function setPortalRule($user_id)
    {

    }
}

