<?php
class Route extends AppModel
{
    var $name = 'Route';
    var $useTable = 'route';
    var $primaryKey = 'route_id';
    var $order = "route_id DESC";


    var $default_schema = Array(
        'digits' => array('type' => 'string', 'null' => 1, 'default' => '', 'length' => 100),
        'route_type' => array('type' => 'integer', 'null' => '', 'default' => '', 'length' => ''),
        'static_route_id' => array('name' => 'Static Route Name', 'type' => 'integer', 'null' => '', 'default' => '', 'length' => ''),
        'dynamic_route_id' => array('name' => 'Dynamic Routing Name', 'type' => 'integer', 'null' => '', 'default' => '', 'length' => ''),


    );


    static $static_route_ids = null;

    function format_static_route_id_for_download($value, $data)
    {
        if (!self::$static_route_ids) {
            App::import("Model", 'Product');
            $model = new Product;
            self::$static_route_ids = $model->find("all");
        }
        foreach (self::$static_route_ids as $time_profile) {
            if ($time_profile['Product']['product_id'] == $value) {
                return $time_profile['Product']['name'];
            }
        }
    }


    static $dynamic_route_ids = null;

    function format_dynamic_route_id_for_download($value, $data)
    {
        if (!self::$dynamic_route_ids) {
            App::import("Model", 'DynamicRoute');
            $model = new DynamicRoute;
            self::$dynamic_route_ids = $model->find("all");
        }
        foreach (self::$dynamic_route_ids as $time_profile) {
            if ($time_profile['DynamicRoute']['dynamic_route_id'] == $value) {
                return $time_profile['DynamicRoute']['name'];
            }
        }
    }

    function get_foreign_name($id)
    {
        return $this->query("select name from route_strategy where  route_strategy_id=$id;");

    }


    function find_all_valid()
    {
        return $this->findAll();
    }

    public function add_route($data)
    {
        $data_info = $data['Routestrategys'];
        $data['Routestrategys']['update_at'] = date("Y-m-d H:i:sO");
        $data['Routestrategys']['update_by'] = $_SESSION['sst_user_name'];

        unset($data['Routestrategys']['route_id']);
        $fieldsArr = '';
        $dataArr = '';
        foreach ($data['Routestrategys'] as $key => $routestrategy) {
            if (empty($routestrategy)) {
                $data['Routestrategys'][$key] = 'NULL';
                $fieldsArr .= $key . ',';
                $dataArr .= $data['Routestrategys'][$key] . ',';
            } else {
                $fieldsArr .= $key . ',';
                if ($key == 'update_at' || $key == 'update_by' || $key == 'ani_prefix' || $key == 'digits')
                    $dataArr .= "'" . $routestrategy . "',";
                else
                    $dataArr .= $routestrategy . ',';
            }
        }
        $fieldsArr = trim($fieldsArr, ',');
        $dataArr = trim($dataArr, ',');
        $sql = "INSERT INTO route ({$fieldsArr}) VALUES ({$dataArr})";
        $res = $this->query($sql);
        $name_result = $this->query("select name from  route_strategy where route_strategy_id= {$data_info['route_strategy_id']};");
        if ($res === false)
            return false;
        $route_id = $this->getLastInsertID();
        $rollback_sql = "DELETE FROM route WHERE route_id = {$route_id}";
        $rollback_msg = "Create Routing [" . $name_result[0][0]['name'] . "] operation have been rolled back!";
        $this->logging(0, 'Route', "Route Plans Name:" . $name_result[0][0]['name'], $rollback_sql, $rollback_msg);
        return true;
    }

    public function update_route($data, $route_id)
    {
        $no_null = ['static_route_id'];
        foreach ($data as $field => $value) {
            if (in_array($field, $no_null) && !$value) {
                continue;
            }
            $fields[] = $value ? "$field = '$value'" : "$field = NULL";
        }

        $query = "UPDATE route SET " . join(', ', $fields) . " WHERE route_id = '$route_id'";
        $this->query($query);
        return $this->getAffectedRows();
    }

    public function get_us_country_id()
    {
        $sql = "select id,name from jurisdiction_country where name = 'US'";
        $data = $this->query($sql);
        if (empty($data)) {
            $sql = "INSERT INTO jurisdiction_country (name) VALUES ('US') returning id";
            $data = $this->query($sql);
        }
        return $data[0][0]['id'];
    }
}