<?php
class Credit extends AppModel
{
//	var $name = 'Credit';
    var $useTable = false;

//	var $primaryKey = 'id';

    public function ListCredit($currPage = 1, $pageSize = 15, $search_arr = array(), $search_type = 0, $order_arr = array())
    {
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;

        $sql_where = '';
        if ($search_type == 1) {
            if (!empty($search_arr['action_type'])) {
                $sql_where .= " and  action_type = " . intval($search_arr['action_type']);
            }
            if (!empty($search_arr['status'])) {
                $sql_where .= " and  status = " . intval($search_arr['status']);
            }
            if (!empty($search_arr['descript'])) {
                $sql_where .= " and descript like '%" . addslashes($search_arr['descript']) . "%'";
            }
            if (!empty($search_arr['start_date'])) {
                $sql_where .= " and  action_time >= '" . addslashes($search_arr['start_date']) . "'";
            }
            if (!empty($search_arr['end_date'])) {
                $sql_where .= " and  action_time <= '" . addslashes($search_arr['end_date']) . "'";
            }
        } else {
            if (!empty($search_arr['search'])) {
                $sql_where .= " and  (action_number ilike '%" . addslashes($search_arr['search']) . "%' or descript like '%" . addslashes($search_arr['search']) . "%' or client.name ilike '%" . addslashes($search_arr['search']) . "%')";
            }
        }

        $sql_order = '';
        if (!empty($order_arr)) {
            $sql_order = ' order by ';
            foreach ($order_arr as $k => $v) {
                $sql_order .= $k . ' ' . $v;
            }
        }

//        $sql = "select count(id) as c from credit_application where true" . $sql_where;
//        $totalrecords = $this->query($sql);
//
//        $page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
        $page->setCurrPage($currPage);//当前页
        $page->setPageSize($pageSize);//页大小

        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
//        $sql = "select credit_application.*,client.name from credit_application left join client on credit_application.client_id=client.client_id where 1=1" . $sql_where . $sql_order;

//        $sql .= " limit '$pageSize' offset '$offset'";
        //echo $sql;
//        $results = $this->query($sql);
        $results = array();
        $page->setDataArray($results);//Save Data into $page
        //////////////////////////////////////////

        return $page;
    }


    function generate_pdf_content($id, $num_format = 5)
    {
        if (empty($id)) {
            return '';
        }
//        $credit = $this->query("select * from credit_application where id = '$id'");
        $credit = array();
        $credit = $credit ? $credit[0][0] : null;
        //print_r($credit);
        $system_params = $this->query("select * from system_parameter");
        $pdf_tpl = str_replace("\n", "<br />", $system_params[0][0]['pdf_tpl']);

        $legal_name = $credit['legal_name'];
        $company_name_1 = $credit['company_name_1'];
        $years_doing_business_1 = $credit['years_doing_business_1'];
        $contact_person_1 = $credit['contact_person_1'];
        $position_1 = $credit['position_1'];
        $trade_phone_1 = $credit['trade_phone_1'];
        $trade_fax_1 = $credit['trade_fax_1'];
        $trade_email_1 = $credit['trade_email_1'];

        $company_name_2 = $credit['company_name_2'];
        $years_doing_business_2 = $credit['years_doing_business_2'];
        $contact_person_2 = $credit['contact_person_2'];
        $position_2 = $credit['position_2'];
        $trade_phone_2 = $credit['trade_phone_2'];
        $trade_fax_2 = $credit['trade_fax_2'];
        $trade_email_2 = $credit['trade_email_2'];

        $company_name_3 = $credit['company_name_3'];
        $years_doing_business_3 = $credit['years_doing_business_3'];
        $contact_person_3 = $credit['contact_person_3'];
        $position_3 = $credit['position_3'];
        $trade_phone_3 = $credit['trade_phone_3'];
        $trade_fax_3 = $credit['trade_fax_3'];
        $trade_email_3 = $credit['trade_email_3'];

        $client = $this->query("select * from client where client_id = " . $credit['client_id']);
        $member_name = '';
        if ($client) {
            $member_name = $client[0][0]['name'];
        }
        $return = <<<EOD
		<div style="width:100%;text-align:left;margin-top:0px;"><span style="font-size:32px;">Customer: {$client[0][0]['name']}</span></div>
		<div style="width:100%;text-align:right;margin-top:0px;"><span style="font-size:32px;">
	  Credit ID: {$id}
		</span></div>
		<div style="height:10px;"></div>
		<span style="font-size:32px;font-weight:bold">Credit Application</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%"  >
		<tr style="background-color:#ddd;text-align:center;">
		<td>&nbsp;</td><td>Reference #1</td><td>Reference #2</td><td>Reference #3</td>
		</tr>
EOD;

        $return .= <<<EOD
		<tr>
		<td>Company Name</td><td style="text-align:center;">{$company_name_1}</td>
		<td style="text-align:center;">{$company_name_2}</td>
		<td style="text-align:center;">{$company_name_3}</td>
		</tr>
		<tr>
		<td>Years doing business</td><td style="text-align:center;">{$years_doing_business_1}</td>
		<td style="text-align:center;">{$years_doing_business_2}</td>
		<td style="text-align:center;">{$years_doing_business_3}</td>
		</tr>
		<tr>
		<td>Contact Person</td><td style="text-align:center;">{$contact_person_1}</td>
		<td style="text-align:center;">{$contact_person_2}</td>
		<td style="text-align:center;">{$contact_person_3}</td>
		</tr>
		</table>

		<br /><br />
		{$pdf_tpl}
EOD;
        return $return;
    }

}

?>