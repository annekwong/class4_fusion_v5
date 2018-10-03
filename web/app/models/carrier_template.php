<?php
class CarrierTemplate extends AppModel
{
    var $name = 'CarrierTemplate';
    var $useTable = 'carrier_template';
    var $primaryKey = 'id';
    var $no_client_fields = array(
        'template_name','create_by','update_on','create_on','id'
    );
    /**
     * 查询currency
     */
    function findCurrency()
    {

        $sql = "select currency_id ,code from currency where active=true";


        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['currency_id'];
            $l [$key] = $r [$i] [0] ['code'];
        }

        natcasesort($l);
        return $l;
    }

    /**
     * 查询mail_sender
     */

    function findsendemailTerm()
    {
        $r = $this->query("select id,name from mail_sender  ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['id'];
            $l [$key] = $r [$i] [0] ['name'];
        }

        asort($l);
        return $l;
    }

    /**
     * 查询paymentTrem
     */
    function findPaymentTerm()
    {
        $r = $this->query("select payment_term_id,name from payment_term  ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $r [$i] [0] ['payment_term_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        asort($l);
        return $l;
    }


    function saveOrUpdate($data, $post_arr){
        $msgs = $this->validate_client($data, $post_arr); //验证客户信息

        if ($msgs)
        {//echo 1;exit(1);
            return false; //add fail
        }

        $data['Template'] = $data['Client'];
        unset($data['Client']);

        $data ['Template']['usage_detail_fields'] = isset($data['Template']['usage_detail_fields']) ? implode(',', $data['Template']['usage_detail_fields']) : '';

        $data ['Template']['is_send_trunk_update'] = $data ['Template']['is_send_trunk_update']==1 ? 'true' : 'false';



        //pr($data);exit;
        if (!empty($data['Template']['id']))
        {
            $data['Template']['update_on'] = date("Y-m-d H:i:s");
            $this->save($data['Template']);

        }
        else
        {
                $data['Template']['create_on'] = date("Y-m-d H:i:s");
                $data['Template']['update_on'] = date("Y-m-d H:i:s");
                $data['Template']['create_by'] = $_SESSION['sst_user_name'];
                $this->save($data['Template']);

        }

        return true;

    }







    /**
     * 验证客户信息
     * @return true 有错误信息
     * false 没有错误信息
     */
    function validate_client($data, $post_arr)
    {

        //	return $this->xvalidated($data['Client']);
        $error_flag = false; //错误信息标志
        $client_id = $this->getkeyByPOST('client_id', $post_arr);
        $name = $data ['Client'] ['template_name'];

        $allowed_credit = $data ['Client'] ['allowed_credit']; //容许欠费
        //$profit_margin = $data ['Client'] ['profit_margin'];
        $login = isset($data ['Client'] ['login']) ? $data ['Client'] ['login'] : "";
//		$notify_admin_balance = $data ['Client'] ['notify_admin_balance'];
        $notify_client_balance = isset($data ['Client'] ['notify_client_balance']) ? $data ['Client'] ['notify_client_balance'] : "";
        $service_id = isset($data ['Client'] ['service_charge_id']) ? $data ['Client'] ['service_charge_id'] : "";


        if (!empty($allowed_credit))
        {
            if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $allowed_credit))
            {
                $this->create_json_array('#ClientAllowedCredit', 101, 'Please fill Allowed Credit field correctly (only  digits allowed).');
                $error_flag = true;
            }
        }
        /*
          if (!empty($profit_margin)) {
          if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $profit_margin)) {
          $this->create_json_array('#ClientProfitMargin', 101, 'Please fill Min. Profitability field correctly (only  digits allowed).');
          $error_flag = true;
          }
          }
         *
         */
//		if (  !empty($notify_admin_balance)) {
//			if(!preg_match('/^[+\-]?\d+(.\d+)?$/',$notify_admin_balance)){
//					$this->create_json_array ('#ClientNotifyAdminBalance', 101, 'Please fill Notify admin field correctly (only  digits allowed).');
//					$error_flag = true;
//			}
//		}
        if (!empty($notify_client_balance))
        {
            if (!preg_match('/^[+\-]?\d+(.\d+)?$/', $notify_client_balance))
            {
                $this->create_json_array('#ClientNotifyClientBalance', 101, 'Please fill Notify client: field correctly (only  digits allowed).');
                $error_flag = true;
            }
        }
        /* 		if (empty ( $orig_rate_table_id )) {
          $this->create_json_array ( '#ClientOrigRateTableId', 101, __ ( 'selectratetable', true ) );
          $error_flag = true;
          } */
        /*
          if (empty ( $service_id )) {
          $this->create_json_array ( '#ClientServiceChargeId', 101, 'Please fill Service Charge field' );
          $error_flag = true;
          } */
        $sql = "select count(1) from carrier_template where template_name = '" . addslashes($data['Client']['template_name']) . "'";
        if(isset($data['Client']['id']) && !empty($data['Client']['id'])){
            $sql .= " and id != {$data['Client']['id']}";
        }
        $c = $this->query();

        if (!empty($c))
        {
            $this->create_json_array('#TemplateName', 301, __('Check Template Name', true));
            $error_flag = true;
        }
        /*
          $valie_data=$this->query("select * from client where name='".$data['Client']['name']."' and client_id <> {$client_id}");
          if(!empty($valie_data)){

          $this->create_json_array ( '#ClientName', 101, 'Client Name Exists' );
          $error_flag = true;
          }
         *
         */
        return $error_flag;
    }

    public function get_client_fields($template_id)
    {
        $data = $this->findById($template_id);
        foreach ($data['CarrierTemplate'] as $key => $item)
        {
            if(in_array($key,$this->no_client_fields))
                unset($data['CarrierTemplate'][$key]);
        }
        return $data['CarrierTemplate'];
    }

}
