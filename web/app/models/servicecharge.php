<?php
class Servicecharge extends AppModel
{
//	var $name = 'Servicecharge';
    var $useTable = false;

//	var $primaryKey = 'service_charge_id';

    public function validate_data($data)
    {
        $error_flag = 'false'; //错误信息标志
        $name = $data ['Servicecharge'] ['name'];
        if (empty ($name)) {
            $this->create_json_array('#ServicechargeName', 101, __('clientnamenull', true));
            $error_flag = 'true'; //有错误信息
        }

        return $error_flag;
    }


    public function findAll($order)
    {
        if (!empty($order)) {
            $order = "order dy" . $order;

        }


        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];
        $order = $this->_get_order();

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';//权限条件

        if ($login_type == 3) {
            $privilege = "  and(client_id::integer={$_SESSION['sst_client_id']}) ";

        }
    }
}

