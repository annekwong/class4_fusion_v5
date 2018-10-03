<?php

class Clientrate extends AppModel {

    var $name = 'Clientrate';
    var $useTable = 'rate';
    var $primaryKey = 'rate_id';

    public function get_prefix_rate($str_arr) {
        if (empty($str_arr)) {
            return NULL;
        }

        $r = '';
        foreach ($str_arr as $key => $value) {
            $r.=",'" . $value . "'";
        }
        $r = substr($r, 1);

        $client_id = $_SESSION['sst_client_id'];
        $arr = '';
        $list = $this->query("
						select  rate_table_id  from resource  where   route_strategy_id  in 
						 ( 
						 select route_strategy_id      from  route  where digits  in  ($r)
						 )
		and  client_id=$client_id");

        if (!empty($list)) {
            $size = count($list);
            for ($i = 0; $i < $size; $i++) {
                $table_id = $list[$i][0]['rate_table_id'];
                $arr.=',' . $table_id;
            }
        }
        $arr = substr($arr, 1);


        return $arr;
    }

    public function getTimeProfile() {
        $sql = "select time_profile_id,name from time_profile ORDER by name asc";
        return $this->query($sql);
    }

    public function checkCodedeck($rate_table_id) {
        $sql = "select code from code where code_deck_id = (select code_deck_id from rate_table where rate_table_id = {$rate_table_id})";
        return $this->query($sql);
    }

    /**
     *
     *
     * search  rate
     * @param $tate_table_id
     * @param $order
     * @param $options
     */
    public function find_all_rate($tate_table_id,$jur_type, $npa,$order = null, $options = Array(), $show = 'html') {
        if (empty($order))
        {
            $order = "code  desc";
        }
        if(empty($show))
            $show = 'html';
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 20 : $pageSize = $_GET['size'];
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        $state_where = '';
        if ($login_type == 3) {
            $privilege = " ";
        }

        if (isset($_GET['search'])) {
            $_GET['search']['_q'] = str_replace("\'", "'", $_GET['search']['_q']);
            $searchQ = str_replace("'", "''", $_GET['search']['_q']);
        } else {
            $searchQ = '';
        }

        $like_where = !empty($searchQ) ? " and (code <@ '{$searchQ}' or  code_name::text ilike '{$searchQ}%' or country::text ilike '{$searchQ}%' or  ocn ilike '{$searchQ}%' or  lata ilike '{$searchQ}%')" : '';
        $download_where = !empty($searchQ) ? " and (RateTable.code <@ '{$searchQ}'  or RateTable.code_name::text ilike '{$searchQ}%' or RateTable.country::text ilike '{$searchQ}%')" : '';
        if (empty($searchQ) || $searchQ == 'Search') {
            $like_where = '';
        }

        $_SESSION['rate_search'] = $download_where;

        if (array_keys_value($_GET, 'search.state_eq')) {
            $state = array_keys_value($_GET, 'search.state_eq');
            #将来可以用的费率
            if ($state == 'new') {
                $state_where = " and ( effective_date >='{$_GET['search']['now']}')";
            }
            #过期费率
            if ($state == 'old') {
                $state_where = " and (end_date <='{$_GET['search']['now']}')";
            }
            #目前可以用的费率
            if ($state == 'current') {
                $state_where = "  and (effective_date <='{$_GET['search']['now']}')   ";
            }
            #目前可以用的费率
            if ($state == 'in') {
                $state_where = "  and (effective_date <='{$_GET['search']['now']}' and (end_date is null or end_date >= '{$_GET['search']['now']}'))   ";
            }
            #testers 
            if ($state == 'testers') {
                $state_where = "  and (effective_date ='{$_GET['search']['now']}'" . (empty($_GET['search']['now2']) ? " and end_date is null) " : " and end_date = '{$_GET['search']['now2']}') ");
            }
        } else {
            if (empty($_GET['filter_effect_date']) || 'all' != $_GET['filter_effect_date']) { //filter_effect_date=all

                if (!empty($like_where)) {
                    $state_where = "  and  (end_date is null or end_date >= NOW())   ";
                } else {
                    $state_where = "  and (effective_date <= NOW() and (end_date is null or end_date >= NOW()))   ";
                }
            }
        }

        $effective_date_where = "";
        if(isset($_GET['effectiveDate']) && $_GET['effectiveDate']){
            $dayStart = date('Y-m-d 00:00:00', strtotime($_GET['effectiveDate']));
            $dayEnd = date('Y-m-d 23:59:59', strtotime($_GET['effectiveDate']));
            $effective_date_where = "  and effective_date between '{$dayStart}' and '{$dayEnd}' ";
        }

        $search_where = array_keys_value($_GET, 'code_name');
        $search_where=enumerate_return($search_where," and rate.code_name::text ilike '%$search_where%'",'');
        $rate_gte_where = array_keys_value($_GET, 'search.rate_per_min_gte');
        $rate_gte_where = enumerate_return($rate_gte_where, "  and (rate>='{$rate_gte_where}')", '');
        $rate_lte_where = array_keys_value($_GET, 'search.rate_per_min_lte', '');
        $rate_lte_where = enumerate_return($rate_lte_where, "  and (rate<='{$rate_lte_where}')", '');
        $min_gte_where = array_keys_value($_GET, 'search.min_time_gte');
        ;
        $min_gte_where = enumerate_return($min_gte_where, "  and (min_time>='{$min_gte_where}')", '');
        $min_lte_where = array_keys_value($_GET, 'search.min_time_lte');
        $min_lte_where = enumerate_return($min_lte_where, "  and (min_time<='{$min_lte_where}')", '');
        $setup_gte_where = array_keys_value($_GET, 'search.pay_setup_gte');
        $setup_gte_where = enumerate_return($setup_gte_where, "  and (setup_fee>='{$setup_gte_where}')", '');
        $setup_lte_where = array_keys_value($_GET, 'search.pay_setup_lte');
        $setup_lte_where = enumerate_return($setup_lte_where, "  and (setup_fee<='{$setup_lte_where}')", '');
        $inteval_gte_where = array_keys_value($_GET, 'search.pay_interval_gte');
        $inteval_gte_where = enumerate_return($inteval_gte_where, "  and (interval>='{$inteval_gte_where}')", '');
        $inteval_lte_where = array_keys_value($_GET, 'search.pay_interval_lte');
        $inteval_lte_where = enumerate_return($inteval_lte_where, "  and (interval<='{$inteval_lte_where}')", '');
        $grace_gte_where = array_keys_value($_GET, 'search.grace_time_gte');
        $grace_gte_where = enumerate_return($grace_gte_where, "  and (grace_time>='{$grace_gte_where}')", '');
        $grace_lte_where = array_keys_value($_GET, 'search.grace_time_lte');
        $grace_lte_where = enumerate_return($grace_lte_where, "  and (grace_time<='{$grace_lte_where}')", '');
        $time_profile_where = array_keys_value($_GET, 'id_time_profiles_eq');
        $time_profile_where = enumerate_return($time_profile_where, "  and (rate.time_profile_id={$time_profile_where})", '');


        $where = "$like_where  $search_where  $rate_gte_where $rate_lte_where  $min_gte_where $min_lte_where $setup_gte_where $setup_lte_where $inteval_gte_where $inteval_lte_where
							$grace_gte_where $grace_lte_where $time_profile_where $state_where  $effective_date_where";
        if(isset($options['rate_ids'])){
            $tate_table_id = $tate_table_id . ' and rate_id in ('.$options['rate_ids'].') ';
        }

        $where = trim($where);
        if (!empty($where)) {

            /* $count_sql =" select count(*) as c from (
              select code,max(effective_date) as max_effect from rate   where rate.rate_table_id  in ( $tate_table_id) $where   group by code
              ) as  rate "; */
            $count_sql = " select count(*) as c from rate   where rate_table_id = $tate_table_id $where ";

            if ($show == 'html') {

                $sql_order = "    order by  $order limit '$pageSize' offset '" . ($currPage - 1) * $pageSize . "'";
            } elseif ($show == 'csv') {
                $sql_order = "    order by  $order ";
            } else {
                //void
                $sql_order = "    order by  $order limit '$pageSize' offset '" . ($currPage - 1) * $pageSize . "'";
            }

            /* 					//$order_value = preg_replace("/ASC|DESC/i", '', $order);
              //$group_value = empty($order_value) ? '' : (',' . $order_value);
              $sql = " select rate_a.*, rate_a.code_name as code_name,rate_a.country as country,
              (select  name  from  time_profile    where  time_profile_id=rate_a.time_profile_id)  as time_profile_name
              from (select * from rate where rate_table_id  in ( $tate_table_id)  )  as rate_a right join
              (
              select code,max(effective_date) as max_effect from rate   where rate.rate_table_id  in ( $tate_table_id)
              $where   group by code $group_value $sql_order
              ) as  rate
              on rate.code=rate_a.code where rate_a.effective_date=rate.max_effect "; */
            $sql = " select code,rate_id, rate_table_id, rate, setup_fee, effective_date AT TIME ZONE INTERVAL '+00:00' as effective_date, end_date AT TIME ZONE INTERVAL '+00:00' as end_date, case when end_date < now() then 0 else 1 end as can_edit, min_time, grace_time,interval, rate.time_profile_id, seconds, code_name, basic_percentages, gift_percentages, rate_type, intra_rate, inter_rate, local_rate, country, zone,
					 time_profile.name as time_profile_name,ocn,lata
						from rate 
						LEFT JOIN time_profile
                        ON time_profile.time_profile_id=rate.time_profile_id
						where rate_table_id = $tate_table_id 
	 				$where   $sql_order ";

            if (!empty($_GET['filter_effect_date'])) {

                $count_sql = "select count(*) as c from  rate where rate.rate_table_id  = $tate_table_id  $where";
            }
        } else {
            $sql = " select code,rate_id, rate_table_id, rate, setup_fee, effective_date AT TIME ZONE INTERVAL '+00:00' as effective_date, end_date AT TIME ZONE INTERVAL '+00:00' as end_date, case when end_date < now() then 0 else 1 end as can_edit, min_time, grace_time,interval, time_profile_id, seconds, code_name, basic_percentages, gift_percentages, rate_type, intra_rate, inter_rate, local_rate, country, zone,
            (select  name  from  time_profile    where  time_profile_id=rate.time_profile_id)  as time_profile_name,ocn,lata
						from rate where rate_table_id = $tate_table_id ";
            $count_sql = "select count(*) as c from  rate where rate.rate_table_id = $tate_table_id";
        }

        //只查相关fields;
        if(isset($options['fields'])){
            if (!empty($where)) {
                $sql = " select ".$options['fields']."
						from rate where rate_table_id = $tate_table_id
	 				$where   $sql_order ";
            } else {
                $sql = " select ".$options['fields']."
						from rate where rate_table_id = $tate_table_id ";
            }

        }


        $totalrecords = $this->query($count_sql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;


        if (empty($where)) {

            if ($show == 'html') {
                $sql .= "   order by  $order limit '$pageSize' offset '$offset'";
            } elseif ($show == 'csv') {
                $sql .= "    order by  $order ";
            } else {
                //void
            }
        }
        if (in_array('getSql', $options)) {
            return $sql;
        }
        if ($totalrecords[0][0]['c'] == 0)
            $results = array();
        elseif($totalrecords[0][0]['c'] < $pageSize)
        {
            $sql = str_replace("limit '$pageSize'","limit '{$totalrecords[0][0]['c']}'",$sql);
            $results = $this->query($sql);
        }
        else
            $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    /* 	function beforeSave(){
      $rate_table_id=$this->data['Clientrate']['rate_table_id'];
      $list=$this->query("select code_deck_id from rate_table where rate_table_id='$rate_table_id'");
      $this->data['Clientrate']['code_deck_id']=$list[0][0]['code_deck_id'];
      pr($this->data);
      return true;
      } */

    function beforeSave() {
        $rate_id = isset($this->data['Clientrate']['rate_id']) ? $this->data['Clientrate']['rate_id'] : "";
        if (!empty($rate_id)) {
            $list = $this->query("select rate_id, rate, setup_fee, effective_date, end_date from rate where rate_id=$rate_id");
            $new_rateinfo_arr = array(
                'rate_id' => $this->data['Clientrate']['rate_id'],
                'rate' => $this->data['Clientrate']['rate'],
                'setup_fee' => $this->data['Clientrate']['setup_fee'],
                'effective_date' => date("Y-m-d H:i:s+00", strtotime($this->data['Clientrate']['effective_date'])),
                'end_date' => $this->data['Clientrate']['end_date']
            );
            $validate = false;
            foreach ($new_rateinfo_arr as $k => $v) {
                if ($v != $list[0][0][$k]) {
                    $validate = true;
                    break;
                }
            }
            if ($validate) {
                $old_rateinfo = json_encode($list[0][0]);
                $new_rateinfo_arr['effective_date'] = empty($new_rateinfo_arr['effective_date']) ? 'NULL' : "'" . $new_rateinfo_arr['effective_date'] . "'";
                $new_rateinfo_arr['end_date'] = empty($new_rateinfo_arr['end_date']) ? 'NULL' : "'" . $new_rateinfo_arr['end_date'] . "'";

                $this->query("insert into rate_log (user_id, old_rateinfo, rate_id, rate, setup_fee, effective_date, end_date) values (" . $_SESSION['sst_user_id'] . ", '" . $old_rateinfo . "', " . $new_rateinfo_arr['rate_id'] . ", " . $new_rateinfo_arr['rate'] . ", " . $new_rateinfo_arr['setup_fee'] . ", " . $new_rateinfo_arr['effective_date'] . ", " . $new_rateinfo_arr['end_date'] . ")");
            }
        }
        return true;
    }

    public function ratetable_info_in_rate($rate_table_id) {
        $sql = "SELECT 
rate_table.name,
(SELECT name FROM code_deck WHERE code_deck_id = rate_table.code_deck_id) as code_deck_name,
(SELECT code FROM currency WHERE currency_id = rate_table.currency_id) as currency_name,
rate_type,
(SELECT name FROM jurisdiction_prefix WHERE id = rate_table.jurisdiction_country_id) as jurisdiction_name
FROM 
rate_table
WHERE rate_table_id = {$rate_table_id}";
        $result = $this->query($sql);
        return $result;
    }

    public function get_rate_import_status($ratetable_id) {
        $sql = "SELECT * FROM import_rate_status WHERE rate_table_id = {$ratetable_id} and status != 6";
        $result = $this->query($sql);
        if (isset($result[0][0])) {
            return $result[0][0];
        } else {
            return '';
        }
    }

    public function update_import_status_over($id) {
        $sql = "UPDATE import_rate_status SET status = 6 WHERE id = {$id}";
        $this->query($sql);
    }

    public function get_log_file($id, $type) {
        $sql = "SELECT {$type} FROM import_rate_status WHERE id = {$id} and status = 6 ORDER BY time DESC";
        $result = $this->query($sql);
        if (!empty($result)) {
            return $result[0][0][$type];
        } else {
            return '';
        }
    }

}