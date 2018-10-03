<?php
class AlertRules extends AppModel
{
    var $name = 'AlertRules';
    var $useTable = 'alert_rules';
    var $primaryKey = 'id';

    public function rules_count($useGet = false)
    {
        $ruleName = isset($_GET['rule_name']) ? $_GET['rule_name'] : false;
        $conditions = '';

        if ($useGet && $ruleName) {
            $conditions = "where AND rule_name like '%" . trim($ruleName) . "%'";
        }

        $sql = "SELECT count(*) as sum FROM alert_rules {$conditions}";
        $result = $this->query($sql);
        return $result[0][0]['sum'];
    }

    public function rules_list($pageSize, $offset, $useGet = false)
    {
        $ruleName = isset($_GET['search']) ? $_GET['search'] : false;
        $conditions = '';

        if ($useGet && $ruleName) {
            $conditions = "where AND rule_name like '%" . trim($ruleName) . "%'";
        }

        $order_by = 'active DESC';

        if (isset($_GET['order_by']))
        {
            $order_by_arr = explode('-', $_GET['order_by']);
            if (count($order_by_arr) == 2){
                if($order_by_arr[0] == '_at') $order_by_arr[0] = 'update_at';
                $order_by =  $order_by_arr[0] . ' ' . $order_by_arr[1];
            }

        }

        $sql = "SELECT rule_name, update_by,update_at,id,execution_schedule,specific_minutes,daily_time,weekly_time,weekly_value,last_run_time,active,id,next_run_time FROM alert_rules {$conditions} ORDER BY $order_by, rule_name ASC ";
        if ($pageSize) {
            $sql .= "  limit $pageSize offset $offset";
        }

        $result = $this->query($sql);
        return $result;
    }


    public function rules_count_invalid_number()
    {
        $sql = "SELECT count(*) as sum FROM invalid_number_detection";
        $result = $this->query($sql);
        return $result[0][0]['sum'];
    }

    public function rules_list_invalid_number($pageSize, $offset)
    {
        $sql = "SELECT id,ani_last_run_time,dnis_last_run_time,update_by,update_at,active,rule_name,
date_trunc('min', ani_last_run_time) + (ani_check_cycle || 'min')::interval as ani_next_run_time,
date_trunc('min', dnis_last_run_time) + (dnis_check_cycle || 'min')::interval as dnis_next_run_time,
date_trunc('min',CURRENT_TIMESTAMP(0) + '1 min') as next_min
FROM invalid_number_detection";
        $sql .= "  limit '$pageSize' offset '$offset'";
        $result = $this->query($sql);
        return $result;
    }

    public function check_rule_name($rule_name, $id = "")
    {
        $sql = "SELECT count(*) as sum FROM alert_rules WHERE rule_name = '{$rule_name}' AND id != " . intval($id);
        $result = $this->query($sql);
        return $result[0][0]['sum'];
    }

    public function check_rule_name1($rule_name, $id = "")
    {
        if ($id == '') {
            $sql = "SELECT count(*) as sum FROM invalid_number_detection WHERE rule_name = '{$rule_name}' ";
        } else {
            $sql = "SELECT count(*) as sum FROM invalid_number_detection WHERE rule_name = '{$rule_name}' AND id != $id";
        }

        $result = $this->query($sql);
        return $result[0][0]['sum'];
    }


    public function deleteAll()
    {
        $result = $this->query("DELETE FROM alert_rules RETURNING id");
        if ($result) {
            return TRUE;
        }
        return FALSE;
    }


    public function deleteInvalidNumberSelected($ids = '')
    {
        $result = $this->query("DELETE FROM invalid_number_detection WHERE id in ($ids) RETURNING id");
        if ($result) {
            return TRUE;
        }
        return FALSE;
    }

    public function deleteSelected($ids = '')
    {
        $result = $this->query("DELETE FROM alert_rules WHERE id in ($ids) RETURNING id");
        if ($result) {
            return TRUE;
        }
        return FALSE;
    }

    public function getNameByID($ids = "")
    {
        $sql = "SELECT rule_name FROM alert_rules WHERE id in ($ids)";
        return $this->query($sql);
    }

    public function get_invalid_numberByID($ids = "")
    {
        $sql = "SELECT rule_name FROM invalid_number_detection WHERE id in ($ids)";
        return $this->query($sql);
    }
}