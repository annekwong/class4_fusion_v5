<?php
class AdminAlert extends AppModel
{

    var $name = 'AdminAlert';
    var $useTable = "admin_alert";
    var $primaryKey = "id";

    var $type_arr = array();

    public function get_type_arr()
    {
        $type_arr = array(
            1 => __('Carrier Daily Usage Limit Exceeded',true),
            2 => __('Carrier hourly Usage Limit Exceeded',true),
            3 => __('Invalid Login Alert',true),
            4 => __('Zero Balance Alert',true),
            5 => __('Invoice Unpaid Alert',true),
        );
        return $type_arr;
    }

}
