<?php

class Rate extends AppModel
{
    const RATE_DOWNLOAD_DIR = 'rate_download';

    var $name = 'Rate';
    var $useTable = 'rate_table';
    var $primaryKey = 'rate_table_id';
    var $jurTypeArr = array(
        'A-Z', 'US Non-JD', 'US JD', 'OCN-LATA-JD', 'OCN-LATA-NON-JD'
    );
    var $sentArea = [
        1 => 'Rate',
        2 => 'Product',
        3 => 'Trunk'
    ];

    public function getSentArea($index){
        return $this->sentArea[$index];
    }

    public function checkActive($resourceId)
    {
        $result = $this->query("SELECT active FROM resource WHERE resource_id = {$resourceId}");

        return $result[0][0]['active'];
    }

    public function is_show_jur_rate($rate_table_id)
    {
        $list = $this->query("select  jur_type  from  rate_table  where rate_table_id=$rate_table_id;");
        if
        (($list[0][0]['jur_type']) == 2 || $list[0][0]['jur_type'] == 3)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 分页查询Client groups
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function getAllRates_code($currPage = 1, $pageSize = 15, $search = null, $currency, $adv_search, $order = null)
    {
        $order = $this->_get_order();
        if (empty($order))
        {
            $order = "order by rate_table_id desc";
        }
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = 0;
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        if ($login_type == 3)
        {
            $list = $this->query("select rate_table_id  from  resource  where  client_id={$_SESSION['sst_client_id']}");
            $table_id = '';
            foreach ($list as $key => $value)
            {
                if (empty($value[0]['rate_table_id']))
                {
                    continue;
                }
                $table_id.=$value[0]['rate_table_id'] . ",";
            }
            $table_id = substr($table_id, 0, -1);
            if (empty($table_id))
            {
                $privilege = "  and(1<>1) ";
            }
            else
            {
                $privilege = "  and(rate_table.rate_table_id  in({$table_id})) ";
            }
        }
        $sql = "select count(rate_table_id) as c from rate_table where 1=1  $privilege $adv_search";
        if ($_SESSION['login_type'] == 3)
        {
            $sql = "select count(digits) as c   from  
  (select  digits ,rate_table_id from  route   left  join    resource  on   resource.route_strategy_id=route.route_strategy_id)as  route
  left  join (
select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(resource_id) from resource where rate_table_id=rate_table.rate_table_id )::float as client_rate, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate from rate_table
  )rate_table  on rate_table.rate_table_id=route.rate_table_id  where digits is  not  null  $privilege $adv_search  ";
        }
        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($currency))
            $sql .= " and currency_id = $currency";
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id,
							(select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck,
							(select code from currency where currency_id = rate_table.currency_id) as currency,
								(select count(resource_id) from resource 
							where rate_table_id=rate_table.rate_table_id )::float
							as client_rate
			
							from rate_table where 1=1   $privilege $adv_search";

        if ($_SESSION['login_type'] == 3)
        {
            $sql = "select  route.digits, rate_table.* from  
  (select  digits ,rate_table_id from  route   left  join    resource  on   resource.route_strategy_id=route.route_strategy_id)as  route
  left  join (
select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(resource_id) from resource where rate_table_id=rate_table.rate_table_id )::float as client_rate, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate,  from rate_table
  )rate_table  on rate_table.rate_table_id=route.rate_table_id   where digits is  not  null $privilege $adv_search";
        }
        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($currency))
            $sql .= " and code_deck_id = $currency";
        $sql .= " $order limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    public function checkExists($id, $code = '')
    {
        $result = $this->query("SELECT count(id) FROM rate WHERE rate_table_id = {$id} AND code = ''");
    }

    public function deleteBlackRate($rate_table_id)
    {
        $sql = "DELETE FROM rate WHERE rate_table_id = {$rate_table_id}  and code = ''";
        $this->query($sql);
    }

    public function insertMassEdit($rate_table_id, $code, $codename, $country, $rate, $setupfee, $effectdate, $enddate, $mintime, $gracetime, $seconds, $timezone, $localrate)
    {
        $sql = "INSERT INTO rate (rate_table_id, code, code_name, country, rate,
		setup_fee, effective_date, end_date, min_time, grace_time, seconds, zone, local_rate) VALUES ($rate_table_id, '$code', '$codename', '$country', $rate, $setupfee, '$effectdate', $enddate, $mintime, $gracetime, $seconds, '$timezone', $localrate)";
        $result = $this->query($sql);
        //print_r($result);
    }

    public function matchPrefixEndDate($rate_table_id, $code, $effectdate)
    {
        $end_date_post = date("Y-m-d H:i:s", strtotime($effectdate) - 1);
        $sql = "UPDATE rate SET end_date = '$end_date_post' WHERE  code::varchar LIKE '$code%' AND rate_table_id = $rate_table_id AND effective_date < '$effectdate' AND end_date is  null";
        $result = $this->query($sql);
        //print_r($result);
    }

    public function matchEqualEndDate($rate_table_id, $code, $effectdate)
    {
        $end_date_post = date("Y-m-d H:i:s", strtotime($effectdate) - 1);
        $sql = "UPDATE rate SET end_date = '$end_date_post' WHERE  code::varchar = '$code' AND rate_table_id = $rate_table_id AND effective_date < '$effectdate' AND end_date is null";
        $result = $this->query($sql);
    }

    public function matchPrefixEndDate1($rate_table_id, $code, $enddate)
    {
        //$end_date_post = date("Y-m-d H:i:s", strtotime($effectdate) - 1);
        $sql = "UPDATE rate SET end_date = '$enddate' WHERE  code::varchar LIKE '$code%' AND rate_table_id = $rate_table_id AND effective_date < '$enddate' AND end_date is null";
        $result = $this->query($sql);
        //print_r($sql);
    }

    public function matchEqualEndDate1($rate_table_id, $code, $enddate)
    {
        //$end_date_post = date("Y-m-d H:i:s", strtotime($effectdate) - 1);
        $sql = "UPDATE rate SET end_date = '$enddate' WHERE  code::varchar = '$code' AND rate_table_id = $rate_table_id AND effective_date < '$enddate' AND end_date is null";
        $result = $this->query($sql);
        //print_r($sql);
    }

    /*
     * 获取单个rate_table_id的相关信息
     * @param int $rate_table_id
     */

    public function getOneRate($rate_table_id)
    {
        $sql = "select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id,lnp_dipping_rate,update_at,update_by,jurisdiction_prefix,noprefix_min_length,noprefix_max_length,prefix_min_length,prefix_max_length, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(*) from resource_prefix where rate_table_id = rate_table.rate_table_id) as ingress_count, (select count(*) from resource 
where rate_table_id = rate_table.rate_table_id) as egress_count, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate, rate_type from rate_table where rate_table_id= {$rate_table_id}";
        $results = $this->query($sql);
        return $results;
    }

    public function getAllRates($currPage = 1, $pageSize = null, $search = null, $currency = null, $adv_search = null, $order = null)
    {
        if (!$pageSize) {
            $pageSize = $this->find('count');
        }

        $order = $this->_get_order();
        if (empty($order))
        {
            $order = "order by rate_table.name asc";
        }
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = 0;
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        if ($login_type == 3)
        {
            $list = $this->query("select rate_table_id  from  resource  where  client_id={$_SESSION['sst_client_id']}");
            $table_id = '';
            foreach ($list as $key => $value)
            {
                if (empty($value[0]['rate_table_id']))
                {
                    continue;
                }
                $table_id.=$value[0]['rate_table_id'] . ",";
            }
            $table_id = substr($table_id, 0, -1);
            if (empty($table_id))
            {
                $privilege = "  and(1<>1) ";
            }
            else
            {
                $privilege = "  and(rate_table.rate_table_id  in({$table_id})) ";
            }
        }
        $sql = "select count(rate_table_id) as c from rate_table where 1=1  $privilege $adv_search";
        if ($_SESSION['login_type'] == 3)
        {
            $sql = "select count(digits) as c   from  
  (select  digits ,rate_table_id from  route   left  join    resource  on   resource.route_strategy_id=route.route_strategy_id)as  route
  left  join (
select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(resource_id) from resource where rate_table_id=rate_table.rate_table_id )::float as client_rate, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate from rate_table
  )rate_table  on rate_table.rate_table_id=route.rate_table_id  where digits is  not  null  $privilege $adv_search  ";
        }
        //        只显示非origination 的rate table
//        $sql .= " AND origination = false";
//        $sql .= " AND is_virtual is not true";
        if (!empty($search))
            $sql .= " and name ilike '%$search%'";
        if (!empty($currency))
            $sql .= " and currency_id = $currency";
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id,lnp_dipping_rate,update_at,update_by,jurisdiction_prefix,noprefix_min_length,noprefix_max_length,prefix_min_length,prefix_max_length,define_by,
                (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck,jur_type,
                (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(*) from resource_prefix where rate_table_id = rate_table.rate_table_id  and resource_id is not null ) as ingress_count, (select count(*) from resource 
where rate_table_id = rate_table.rate_table_id) as egress_count,
                (select count(resource_id) from resource  where  egress=true and  rate_table_id=rate_table.rate_table_id) 
                as egress_rate, rate_type
                from rate_table where 1=1   $privilege $adv_search";

        if ($_SESSION['login_type'] == 3)
        {
            $sql = "select  route.digits, rate_table.* from  
  (select  digits ,rate_table_id from  route   left  join    resource  on   resource.route_strategy_id=route.route_strategy_id)as  route
  left  join (
select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(*) from resource_prefix where rate_table_id = rate_table.rate_table_id) as ingress_count, (select count(*) from resource 
where rate_table_id = rate_table.rate_table_id) as egress_count, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate from rate_table
  )rate_table  on rate_table.rate_table_id=route.rate_table_id   where digits is  not  null $privilege $adv_search";
        }
        //        只显示非origination 的rate table
//        $sql .= " AND origination = false";
//        $sql .= " AND is_virtual is not true";
        if (!empty($search))
            $sql .= " and name ilike '%$search%'";
        if (!empty($currency))
            $sql .= " and currency_id = $currency";
        $sql .= " $order limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    public function deleteAll()
    {
        $returnValue = false;
        $result = $this->query("select array_to_string(array_agg(distinct rate_table_id), ',') as rate_table_ids  from rate_table");

        if ($result) {
            $this->query("UPDATE resource SET rate_table_id = null WHERE rate_table_id IN ({$result[0][0]['rate_table_ids']})");
            $this->query("UPDATE resource_prefix SET rate_table_id = null WHERE rate_table_id IN ({$result[0][0]['rate_table_ids']})");
            $this->query("DELETE FROM rate WHERE rate_table_id IN ({$result[0][0]['rate_table_ids']})");
            $this->query("DELETE FROM rate_table WHERE rate_table_id IN ({$result[0][0]['rate_table_ids']})");
            $returnValue = true;
        }
        return $returnValue;
    }

    public function deleteSelected($ids)
    {
        $explodedIds = explode(',', $ids);
        if (count($explodedIds))
        {
            $this->query("UPDATE resource SET rate_table_id = null WHERE rate_table_id IN ({$ids})");
            $this->query("UPDATE resource_prefix SET rate_table_id = null WHERE rate_table_id IN ({$ids})");
            $this->query("DELETE FROM rate WHERE rate_table_id IN ({$ids})");
            $this->query("DELETE FROM rate_table WHERE rate_table_id IN ({$ids})");
            return true;
        }
        else
        {
            return false;
        }
    }

    public function delete_one($id)
    {

        //$sql_2 = "select client_id from client where orig_rate_table_id = $id or term_rate_table_id = $id ";
        //$res_2 = $this->query ( $sql_2 );

        if (1)
        {// count ( $res_2 )  == 0) {
            $this->query("delete  from  rate where rate_table_id=$id ");
            if ($this->del($id))
                return true;
            return false;
        }
        return false;
    }

    public function copy_rate($id, $name)
    {
        # check name
        $list = $this->query("select count(*) from rate_table where name= '$name' ");
        if ($list[0][0]['count'] > 0)
        {
            $this->create_json_array('', 101, 'Rate Table Name exists');
            return false;
        }
        $old_rate = $this->query("select code_deck_id,jur_type,jurisdiction_country_id,rate_type,currency_id from rate_table where rate_table_id= '$id'");
        $modify_time = date('Y-m-d H:i:s', time() + 6 * 60 * 60);
        $code_deck = empty($old_rate [0] [0] ['code_deck_id']) ? NULL : $old_rate [0] [0] ['code_deck_id'];
        $currency_id = empty($old_rate [0] [0] ['currency_id']) ? NULL : $old_rate [0] [0] ['currency_id'];
        $rate_type = empty($old_rate [0] [0] ['rate_type']) ? 0 : $old_rate [0] [0] ['rate_type'];
        $jurisdiction_country_id = empty($old_rate [0] [0] ['jurisdiction_country_id']) ? NULL : $old_rate [0] [0] ['jurisdiction_country_id'];
        $jur_type = empty($old_rate [0] [0] ['jur_type']) ? 0 : $old_rate [0] [0] ['jur_type'];
        $data = array();
        $data['Rate']['name'] = $name;
        $data['Rate']['modify_time'] = $modify_time;
        $data['Rate']['create_time'] = $modify_time;
        $data['Rate']['code_deck_id'] = $code_deck;
        $data['Rate']['currency_id'] = $currency_id;
        $data['Rate']['rate_type'] = $rate_type;
        $data['Rate']['jurisdiction_country_id'] = $jurisdiction_country_id;
        $data['Rate']['jur_type'] = $jur_type;

        if ($this->save($data['Rate']))
        {
            $newtable_id = $this->getLastInsertID();
        }
        /*
          $dbpath = Configure::read('database_export_path');
          $rand_name = uniqid('copy_rate');
          $sql = "COPY (select {$newtable_id}, code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds,
          code_name,rate_type,intra_rate,inter_rate
          ,country,zone from rate  where rate_table_id={$id}) TO '/tmp/exports/{$rand_name}' WITH DELIMITER ',';
          COPY rate(
          rate_table_id, code,rate,setup_fee,effective_date,
          end_date,min_time,grace_time,
          interval,time_profile_id,seconds,code_name,rate_type,intra_rate,inter_rate,country,
          zone
          )
          FROM '/tmp/exports/{$rand_name}' WITH DELIMITER ',';";
         *
         */
        $sql = "insert into rate  (
            rate_table_id, code,rate,setup_fee,effective_date,end_date,min_time,grace_time,
            interval,time_profile_id,seconds,code_name,rate_type,intra_rate,inter_rate,country,zone)
            select $newtable_id, code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds,code_name,rate_type,intra_rate,inter_rate 
            ,country,zone from rate  where rate_table_id=$id";

        //copy rate
        $qs = $this->query($sql);
        if (count($qs) == 0)
            return true;
        return false;
    }

    /*
     * 查找代理商   号码组   和 币率
     */

    public function getAddInfo()
    {
        $codec_sql = "select code_deck_id,name from code_deck where 1=1 ";
        $curr_sql = "select currency_id,code from currency where 1=1 ";
        $jur_country_sql = "select  distinct   id,  name from jurisdiction_country";
        return array(array(), $this->query($codec_sql), $this->query($curr_sql), $this->query($jur_country_sql));
    }

    public function add($n, $c, $cu, $country)
    {

        $list = $this->query("select count(*)  from  rate_table where name='$n'");
        if (!empty($list[0][0]['count']) && $list[0][0]['count'] > 0)
        {
            return false;
        }



        $c = empty($c) ? 'null' : $c;
        $cu = empty($cu) ? 'null' : $cu;
        $country = empty($country) ? 'null' : $country;

        $qs = $this->query("insert into rate_table (name,modify_time,create_time,
													code_deck_id,currency_id,jurisdiction_country_id)
												 values('$n',current_timestamp(0),current_timestamp(0),$c,$cu,$country)");

        if (count($qs) == 0)
            return true;
        return false;
    }

    public function update($n, $c, $cu, $id, $country)
    {

        $c = empty($c) ? 'null' : $c;
        $country = empty($country) ? 'null' : $country;
        $qs = $this->query("update rate_table set  name='$n',code_deck_id=$c,currency_id=$cu,jurisdiction_country_id=$country,modify_time=current_timestamp(0) where rate_table_id = '$id'");
        if (count($qs) == 0)
            return true;
        return false;
    }

    /**
     * 分页查询Client groups
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function getRates($currPage = 1, $pageSize = 15, $search = null, $table_id, $adv_search)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();

        $totalrecords = 0;

        $sql = "select count(rate_id) as c from rate where 1=1 and rate_table_id = '$table_id' $adv_search";
        if (!empty($search))
            $sql .= " and (code  <@ '$search' )";

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select *,(select name from time_profile where time_profile_id = rate.time_profile_id)as tf from rate where rate_table_id = '$table_id' $adv_search";

        if (!empty($search))
            $sql .= " and (code  <@ '$search' )";
        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////


        return $page;
    }

    public function getTimeProfile($reseller_id)
    {
        $sql = "select time_profile_id,name from time_profile";
        if (!empty($reseller_id))
        {
            $sql .= " where reseller_id = $reseller_id";
        }
        $sql .= ' order by name asc';
        return $this->query($sql);
    }

    public function add_rate()
    {
        $code = strlen($_REQUEST ['code']) > 0 ? "'" . $_REQUEST ['code'] . "'" : 'null';
        $codename = empty($_REQUEST ['codename']) ? 'null' : "'" . $_REQUEST ['codename'] . "'";
        $rate = $_REQUEST ['rate'];
        $setup_fee = $_REQUEST ['setup_fee'];
        $effective_date = $_REQUEST ['effective_date'];
        $end_date = empty($_REQUEST ['end_date']) ? 'null' : "'" . $_REQUEST ['end_date'] . "'";
        $min_date = $_REQUEST ['min_date'];
        $interval = $_REQUEST ['interval'];
        $time_profile = empty($_REQUEST ['time_profile']) ? 'null' : $_REQUEST ['time_profile'];
        $seconds = $_REQUEST ['seconds'];
        $tmp_id = $_REQUEST ['tmp_id'];

        $intra_rate = isset($_REQUEST ['intra_rate']) ? $_REQUEST ['intra_rate'] : 'null';
        $inter_rate = isset($_REQUEST ['inter_rate']) ? $_REQUEST ['inter_rate'] : 'null';

        $grace_time = $_REQUEST ['grace_time'];

        $check_sql = "select time_profile_id from rate where code = $code and rate_table_id = $tmp_id ";
        $exists = $this->query($check_sql);
        $add_profile = array();
        if (!empty($time_profile))
        {
            $add_profile = $this->query("select * from time_profile where time_profile_id = $time_profile");

            $sametype = $this->query("select rate_id from rate join time_profile
on rate.time_profile_id = time_profile.time_profile_id
where code = $code  and rate_table_id = $tmp_id and time_profile.type != (select type from time_profile where time_profile_id =$time_profile )");
            if (count($sametype) > 0)
            {
                //	return __('samecodedifftime',true)."|false";
            }
        }
        for ($i = 0; $i < count($exists); $i++)
        {
            if (empty($time_profile) && empty($exists[$i][0]['time_profile_id']))
            {
                //	return __('samepreifxintime',true)."|false";
            }
            if ($exists[$i][0]['time_profile_id'] == $time_profile)
            {
                //return __('samepreifxintime',true)."|false";
            }

            $t = $this->query("select * from time_profile where time_profile_id = {$exists[$i][0]['time_profile_id']}");
            if ($t[0][0]['type'] == $add_profile[0][0]['type'])
            {
                if ($t[0][0]['type'] == 0)
                {
                    //	return __('samepreifxintime',true)."|false";
                }

                if ($t[0][0]['type'] == 1)
                {
                    if ($add_profile[0][0]['start_week'] <= $t[0][0]['end_week'] || $add_profile[0][0]['end_week'] <= $t[0][0]['end_week'])
                    {
                        //	return __('samepreifxintime',true)."|false";
                    }
                }

                if ($t[0][0]['type'] == 2)
                {
                    if (strtotime("2011-01-01 {$add_profile[0][0]['start_time']}") < strtotime("2011-01-01 {$t[0][0]['end_time']}"))
                    {
                        //		return __('samepreifxintime',true)."|false";
                    }

                    if (strtotime("2011-01-01 {$add_profile[0][0]['end_time']}") < strtotime("2011-01-01 {$t[0][0]['end_time']}"))
                    {
                        //	return __('samepreifxintime',true)."|false";
                    }
                }
            }
        }

        $qs = $this->query("insert into rate (rate_table_id,code,code_name,rate,setup_fee,
											 effective_date,end_date,min_time,
											 interval,time_profile_id,seconds,grace_time,intra_rate,inter_rate)
											 values ($tmp_id,$code,$codename,'$rate','$setup_fee','$effective_date',$end_date,$min_date,$interval,$time_profile,$seconds,$grace_time
											 ,$intra_rate,$inter_rate)");
        if (count($qs) == 0)
            return __('addratesucc', true) . "|true";
        else
            return __('addratefailed', true) . "|false";
    }

    //判断是否有号码组
    public function hasCodedeck($table_id)
    {
        $qs = $this->query("select code_deck_id from rate_table where rate_table_id = '$table_id'");
        if (count($qs) > 0)
            return $qs [0] [0] ['code_deck_id'];
        else
            return false;
    }

    /**
     * 分页查询Reseller
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function choose_codes($currPage = 1, $pageSize = 15, $search = null, $code_deck_id = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();

        $totalrecords = 0;

        $sql = "select count(code_id) as c from code where code_deck_id = '$code_deck_id'";
        if (!empty($search))
            $sql .= " and (code <@ '$search' )";

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select * from code where code_deck_id = '$code_deck_id'";

        if (!empty($search))
            $sql .= " and (code <@ '$search' )";
        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////


        return $page;
    }

    public function del_rate($id)
    {
        $qs = $this->query("delete from rate where rate_id = {$id} returning name");
        return $qs;
    }

    public function update_rate()
    {
        /* 			$code = '111';
          $codename = 'null';
          $rate = '0';
          $setup_fee = '0';
          $effective_date = '2010-11-14 12:31:43';
          $end_date =  'null';
          $min_date = '0';
          $interval = '60';
          $time_profile = '18';
          $seconds = '60';
          $id = '315';
          $grace_time = '0';
          $ratetype ='1'; */
        $code = strlen($_REQUEST ['code']) > 0 ? "'" . $_REQUEST ['code'] . "'" : 'null';
        $codename = empty($_REQUEST ['codename']) ? 'null' : "'" . $_REQUEST ['codename'] . "'";
        $rate = $_REQUEST ['rate'];
        $setup_fee = $_REQUEST ['setup_fee'];
        $effective_date = $_REQUEST ['effective_date'];
        $end_date = empty($_REQUEST ['end_date']) ? 'null' : "'" . $_REQUEST ['end_date'] . "'";
        $min_date = $_REQUEST ['min_date'];
        $interval = $_REQUEST ['interval'];
        $time_profile = empty($_REQUEST ['time_profile']) ? 'null' : $_REQUEST ['time_profile'];
        $seconds = $_REQUEST ['seconds'];
        $id = $_REQUEST ['id'];
        $grace_time = $_REQUEST ['grace_time'];
        $intra_rate = !empty($_REQUEST ['intra_rate']) ? $_REQUEST ['intra_rate'] : 'null';
        $inter_rate = !empty($_REQUEST ['inter_rate']) ? $_REQUEST ['inter_rate'] : 'null';
        $oldcodes = $this->query("select code,rate_table_id,time_profile_id from rate where rate_id = $id");

        if ($oldcodes[0][0]['time_profile_id'] != $time_profile)
        {
            $check_sql = "select time_profile_id from rate where code = $code and rate_table_id = {$oldcodes[0][0]['rate_table_id']} ";
            $exists = $this->query($check_sql);
            $add_profile = $this->query("select * from time_profile where time_profile_id = $time_profile");
//		if (!empty($add_profile)){
//			$add_profile = $this->query("select * from time_profile where time_profile_id = $time_profile");
//			$sametype = $this->query("select rate_id from rate join time_profile
//on rate.time_profile_id = time_profile.time_profile_id
//where code = '$code'  and rate_table_id = {$oldcodes[0][0]['rate_table_id']} and time_profile.type != (select type from time_profile where time_profile_id =$time_profile )");
//			if (count($sametype) > 0){
//				return __('samecodedifftime',true)."|false";
//			}
//		}
            for ($i = 0; $i < count($exists); $i++)
            {
                if (empty($time_profile) && empty($exists[$i][0]['time_profile_id']))
                {
                    //return __('samepreifxintime',true)."|false";
                }
                if ($exists[$i][0]['time_profile_id'] == $time_profile)
                {
                    //	return __('samepreifxintime',true)."|false";
                }

                $t = $this->query("select * from time_profile where time_profile_id = {$exists[$i][0]['time_profile_id']}");
                if ($t[0][0]['type'] == $add_profile[0][0]['type'])
                {
                    if ($t[0][0]['type'] == 0)
                    {
                        //		return __('samepreifxintime',true)."|false";
                    }

                    if ($t[0][0]['type'] == 1)
                    {
                        if ($add_profile[0][0]['start_week'] <= $t[0][0]['end_week'] || $add_profile[0][0]['end_week'] <= $t[0][0]['end_week'])
                        {
                            //		return __('samepreifxintime',true)."|false";
                        }
                    }

                    if ($t[0][0]['type'] == 2)
                    {
                        if (strtotime("2011-01-01 {$add_profile[0][0]['start_time']}") < strtotime("2011-01-01 {$t[0][0]['end_time']}"))
                        {
                            //			return __('samepreifxintime',true)."|false";
                        }

                        if (strtotime("2011-01-01 {$add_profile[0][0]['end_time']}") < strtotime("2011-01-01 {$t[0][0]['end_time']}"))
                        {
                            //			return __('samepreifxintime',true)."|false";
                        }
                    }
                }
            }
        }

        if ($oldcodes[0][0]['code'] == $code)
        {
            $sql = "update rate set code_name=$codename, rate=$rate,setup_fee=$setup_fee,effective_date='$effective_date',end_date=$end_date,
											min_time='$min_date',grace_time='$grace_time',interval='$interval',time_profile_id=$time_profile,seconds='$seconds'
											where rate_id = '$id'";
            $qs = $this->query($sql);
        }
        else
        {
            $sql = "update rate set code_name=$codename, code = $code,rate=$rate,setup_fee=$setup_fee,effective_date='$effective_date',end_date=$end_date,
											min_time='$min_date',grace_time='$grace_time',interval='$interval',time_profile_id=$time_profile,seconds='$seconds',
											intra_rate=$intra_rate,inter_rate=$inter_rate
											
											where rate_id = '$id'";
            $qs = $this->query($sql);
        }

        if (count($qs) == 0)
        {
            //return $sql;
            return __('update_suc', true) . "|true";
        }
        else
        {
            return $sql;
            //	return __ ( 'update_fail', true ) . "|false";
        }
    }

    public function generate_by_codedeck()
    {
        $rate = $_REQUEST ['rate'];
        $setup_fee = $_REQUEST ['setup_fee'];
        $effective_date = $_REQUEST ['effective_date'];
        $end_date = empty($_REQUEST ['end_date']) ? 'null' : "'" . $_REQUEST ['end_date'] . "'";
        ;
        $min_date = $_REQUEST ['min_date'];
        $interval = $_REQUEST ['interval'];
        $time_profile = empty($_REQUEST ['time_profile']) ? 'null' : $_REQUEST ['time_profile'];
        $seconds = $_REQUEST ['seconds'];
        $code_deck_id = $_REQUEST ['code_deck'];
        $grace_time = $_REQUEST ['grace_time'];
        $tmp_id = $_REQUEST ['tmp_id'];
        $codes = $this->query("select code from code where code_deck_id = '$code_deck_id'");
        if (count($codes) == 0)
            return __('nocodedeck', true) . "|false";
        else
        {
            $qs_counter = 0;
            $this->begin();
            $loop = count($codes);
            for ($i = 0; $i < $loop; $i++)
            {
                $code = $codes [$i] [0] ['code'];
                //检查前缀是否已经存在  如果存在则跳过
                $exists = $this->query("select rate_id from rate where rate_table_id='$tmp_id' and code='$code'");
                if (count($exists) > 0)
                    continue;
                $qs = $this->query("insert into rate (rate_table_id,code,rate,setup_fee,
											 effective_date,end_date,min_time,
											 interval,time_profile_id,seconds,grace_time)
											 values ($tmp_id,'$code','$rate','$setup_fee','$effective_date',$end_date,$min_date,$interval,$time_profile,$seconds,$grace_time)");
                $qs_counter += count($qs);
            }
            if ($qs_counter == 0)
            {
                $this->commit();
                return __('generatedsuc', true) . "|true";
            }
            else
            {
                $this->rollback();
                return __('generatedfail', true) . "|false";
            }
        }
    }

    //public function simulated1($date, $time, $tz, $ani, $dnis, $duration, $table_id)
    public function simulated1($date, $time, $tz, $dnis, $duration, $table_id)
    {
        $anisql = "";
        /* if (!empty($ani))
          {
          $anisql = "(code @>  '{$ani}'  ) and";
          } */
        $sql = "select rate,seconds from rate where $anisql (code @> '{$dnis}' ) and rate_table_id = {$table_id}  and   
effective_date < '{$date} {$time}{$tz}' and  (end_date > '{$date} {$time}{$tz}' or end_date is null)";
        $results = $this->query($sql);
        $arr = array();
        $rate = isset($results[0][0]['rate']) ? $results[0][0]['rate'] : '';
        $arr['date'] = "{$date} {$time}{$tz}";
        $arr['ani'] = '';
        $arr['dnis'] = $dnis;
        $arr['rate'] = !empty($rate) ? $rate : 'Not Found';
        $arr['cost'] = !empty($rate) ? number_format($rate * ($duration / $results[0][0]['seconds']), 5) : 'Not Found';
        return $arr;
    }

    /*
     * 模拟计费
     */

    public function simulated($date, $number, $durations, $tab_id)
    {
        $sql = "select rate.*,time_profile.name from rate left join time_profile on rate.time_profile_id=time_profile.time_profile_id   where (code @>  '$number'  ) and rate_table_id = $tab_id";
        echo $sql;
        exit();
        //查找最接近号码的费率
        $codes = $this->query("select rate.*,time_profile.name from rate left join time_profile on rate.time_profile_id=time_profile.time_profile_id   where (code @>  '$number'  ) and rate_table_id = $tab_id");
        if (count($codes) == 0)
        {

            $this->create_json_array('', 101, 'Sorry, can not find the number of simulation billing! ');

            return "";
        }
        else
        {
            $this->create_json_array('', 201, 'Simulate Billing Successfully!');

            #if (strtotime ( $date ) < strtotime ( $codes [0] [0] ['effective_date'] ) || strtotime ( $date ) > strtotime ( $codes [0] [0] ['end_date'] ))
            #return "{}";

            $code = $codes [0] [0] ['code'];
            $cost = 0;
            $rate = $codes [0] [0] ['rate'];
            $min_time = $codes [0] [0] ['min_time']; //首次时长
            $interval = $codes [0] [0] ['interval']; //计费周期
            $seconds = $codes [0] [0] ['seconds']; //每分钟多少秒
            $billed = __('second', true);
            $bill_time = 0;
            //通话时间在赠送时间以内  不计费
            if ($durations <= $codes [0] [0] ['grace_time'])
            {
                $cost = 0;
            }
            else if ($durations > $codes [0] [0] ['grace_time'] && $durations < $min_time)
            { //通话时间小于首次通话时间
                if ($min_time % $interval == 0)
                {
                    $cost = $min_time / $seconds * $rate;
                }
                else
                {
                    $cost = (($min_time / $interval) + 1) * $interval / $seconds * $rate;
                }
            }

            //大于首次时长
            else if ($durations > $min_time)
            {
                if ($durations % $interval == 0)
                    $cost = $durations / $seconds * $rate;
                else
                    $cost = (($durations / $interval) + 1) * $interval / $seconds * $rate;
            }

            $cost = number_format($cost, 3);
            //return "{code:'$code',cost:'$cost',rate:'$rate',bill_way:'$billed'}";
            return array('code' => $code, 'cost' => $cost, 'rate' => $rate, 'bill_way' => $billed, 'rate_info' => $codes[0][0]);
        }
    }

    public function get_rate_tables($reseller_id)
    {
        $sql = "select rate_table_id,name from rate_table";
        if (!empty($reseller_id))
            $sql .= " where reseller_id = $reseller_id";
        return $this->query($sql);
    }

    public function getSearchInfo()
    {
        $sql_code_deck = "select code_deck_id,name from code_deck";
        $sql_currency = "select currency_id,code from currency";

        return array(
            $this->query($sql_code_deck),
            $this->query($sql_currency),
        );
    }

    public function select_name($id = null)
    {
        $where = '';
        if (!empty($id))
        {
            $where = " where  currency_id=$id";
        }
        $sql = "select code from  currency $where";
        $code = $this->query($sql);
        return $code;
    }

    public function ready_rate()
    {
        $sql = "SELECT rate_table_id || ':' || name AS id,name FROM rate_table ORDER BY name ASC";
        return $this->query($sql);
    }

    public function ready_resource()
    {
        $sql = "SELECT resource_id, alias FROM resource WHERE ingress = true ORDER BY alias";
        return $this->query($sql);
    }

    /*
     * 通过ID获取名称
     */

    public function getNameByID($ids)
    {
        $sql = "SELECT name FROM rate_table where rate_table_id in ($ids)";
        $result = $this->query($sql);
        return $result;
    }

    public function get_code_decks()
    {
        $sql = "SELECT code_deck_id, name FROM code_deck ORDER BY name ASC";
        return $this->query($sql);
    }

    public function get_currencies()
    {
        $sql = "SELECT currency_id, code FROM currency WHERE active = true ORDER by currency_id ASC";
        return $this->query($sql);
    }

    public function get_jurisdictions()
    {
        $sql = "SELECT id, name FROM jurisdiction_country";
        return $this->query($sql);
    }

    public function get_timeprofiles()
    {
        $sql = "SELECT time_profile_id,name FROM time_profile ORDER by name ASC";
        return $this->query($sql);
    }

    public function alreay_exists_ratetable($name)
    {
        $sql = "SELECT count(*) FROM rate_table WHERE name = '{$name}'";
        $result = $this->query($sql);
        return $result[0][0]['count'] > 0;
    }

    public function create_ratetable($name, $code_deck_id, $currency_id, $rate_type, $isus, $rate_type1,$is_origination = "false", $define_by = 0)
    {
        if ($is_origination !== "false") {
            $is_origination = "true";
        }

        if ($isus) {
            $sql = "INSERT INTO rate_table(name, code_deck_id, currency_id, rate_type, jurisdiction_country_id, create_time, jurisdiction_prefix, 
                noprefix_min_length, noprefix_max_length, prefix_min_length, prefix_max_length, jur_type,origination, define_by)
VALUES ('{$name}', $code_deck_id, $currency_id, $rate_type, 1, CURRENT_TIMESTAMP, '1', '10', '10', '11', '11', $rate_type1,$is_origination, $define_by) RETURNING rate_table_id";
        } else {
            $sql = "INSERT INTO rate_table(name, code_deck_id, currency_id, rate_type, jurisdiction_country_id, create_time, jur_type,origination, define_by)
VALUES ('{$name}', $code_deck_id, $currency_id, $rate_type, NULL, CURRENT_TIMESTAMP, $rate_type1,$is_origination, $define_by) RETURNING rate_table_id";
        }

        $result = $this->query($sql);

        return $result ? $result[0][0]['rate_table_id'] : false;
    }

    public function has_exists_code($rate_table_id, $code, $effective_date, $effective_date_gmt)
    {
        $sql = "SELECT count(*) FROM rate WHERE rate_table_id = {$rate_table_id} AND code = '{$code}' AND effective_date = '{$effective_date}{$effective_date_gmt}'";
        $result = $this->query($sql);
        return $result[0][0]['count'] > 0;
    }

    public function is_exists_name($name)
    {
        $sql = "SELECT count(*) FROM rate_table WHERE name='{$name}'";
        $result = $this->query($sql);
        return $result[0][0]['count'] > 0;
    }

    public function end_early_date($rate_table_id, $code, $end_date, $effective_date, $effective_date_gmt)
    {
        $sql = "UPDATE rate SET end_date = '$end_date' WHERE  end_date is null AND rate_table_id = {$rate_table_id}
AND code = '{$code}' AND effective_date::timestamp with time zone < timestamp with time zone '{$effective_date}{$effective_date_gmt}';";
        $this->query($sql);
    }

    public function rate_tables()
    {
        $sql = "SELECT rate_table_id, name FROM rate_table ORDER BY name ASC";
        return $this->query($sql);
    }

    public function code_names($rate_table_id)
    {
        $sql = "SELECT DISTINCT code_name FROM rate WHERE rate_table_id = {$rate_table_id} ORDER BY code_name ASC";
        return $this->query($sql);
    }

    public function get_carriers()
    {
        $sql = "SELECT client_id, name FROM client ORDER BY name ASC";
        return $this->query($sql);
    }

    public function template_lists()
    {
        $sql = "SELECT id, name FROM send_rate_template ORDER BY name ASC";
        return $this->query($sql);
    }

    public function get_rate_sending_logging_count()
    {
        $sql = "SELECT count(*) FROM rate_send_logging";
        $data = $this->query($sql);
        return $data[0][0]['count'];
    }

    public function get_rate_sending_logging($pageSize, $offset)
    {
        $sql = "SELECT * FROM rate_send_logging ORDER BY id DESC LIMIT $pageSize OFFSET $offset";
        return $this->query($sql);
    }

    public function create_rate_file($rate_table_id, $format, $flg_zip = 0,$headers_sql = '',$start_effective_date = '',$download_sql = '', $jur_type = 0, $options = array())
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::load("myconf");

        // Set zero values for total, completed records
        if (isset($options['logId'])) {
            $this->query("UPDATE rate_send_log SET total_records = 0, completed_records = 0 WHERE id = {$options['logId']}");
        }

        $database_export_path = Configure::read('database_export_path');
        // conf for big files
        ini_set('max_execution_time', '600');

        if (empty($database_export_path))
        {
            $database_export_path = "/tmp/exports";
        }

        $database_export_path = $database_export_path. DS . self::RATE_DOWNLOAD_DIR;

        if ($format == 1)
        {
            $random_filename = uniqid('rate_') . '.csv';
        }
        else
        {
            $random_filename = uniqid('rate_') . '.xls';
        }

//        $time_limit = date('Y-m-d 00:00:00');
        $time_limit = null;
        if ($start_effective_date)
            $time_limit = $start_effective_date;

        if(!$headers_sql)
        {
            if($jur_type == 0){
                $headers = [ "code_name", "country", "rate", "effective_date AT TIME ZONE INTERVAL '+00:00' as effective_date"];
            }else{
                $headers = [ "code_name", "country", "rate", "effective_date AT TIME ZONE INTERVAL '+00:00' as effective_date",
                    "min_time", "interval", "intra_rate", "inter_rate", "local_rate"];

                $empty_cols = ["intra_rate", "inter_rate", "local_rate", "country", "code_name"];
                foreach($empty_cols as $col){
                    // check empty cols
                    $sql = "select count($col) from rate where rate_table_id = {$rate_table_id}";

                    if ($time_limit) {
                        $sql .= " and effective_date <= '$time_limit'
                        and (end_date is null or end_date >= '$time_limit')";
                    }

                    $cnt = $this->query($sql);
                    if(isset($cnt[0][0]["count"]) && !$cnt[0][0]["count"]){
                        $headers = array_diff($headers, [$col]);
                    }
                }

            }

            $headers_sql = implode(',', $headers);
        }

        $headers_sql = str_replace('new_rate', '(SELECT r.rate FROM rate r WHERE r.rate = rate AND r.rate_id > rate_id ORDER BY r.rate_id LIMIT 1) ', $headers_sql);
//        $headers_sql = str_replace('code AS Code,', '', $headers_sql);

        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        if ($jur_type == 0) {
            $timeLimitSql = !$time_limit ? '' : " and (end_date is null or end_date >= '$time_limit') ";

            $search_sql = <<<EOT
select DISTINCT on (code)  $headers_sql from rate where rate_table_id = {$rate_table_id} {$timeLimitSql}
 order by code ASC, effective_date DESC
EOT;

            $count_sql =  <<<EOT
select count(DISTINCT code ) cnt from rate where rate_table_id = {$rate_table_id}  {$timeLimitSql}
EOT;
        } else {
            $timeLimitSql = !$time_limit ? '' : " and effective_date <= '$time_limit' and (end_date is null or end_date >= '$time_limit') ";

            $search_sql = <<<EOT
select DISTINCT on (code)   $headers_sql from rate where rate_table_id = {$rate_table_id} {$timeLimitSql}
order by code ASC, effective_date DESC
EOT;
            $count_sql =  <<<EOT
select count(DISTINCT code ) cnt from rate where rate_table_id = {$rate_table_id} {$timeLimitSql}
EOT;
        }
        $total = $this->query($count_sql);

        $total = isset($total[0][0]["cnt"]) ? $total[0][0]["cnt"] : 0;

        // Set total records
        if (isset($options['logId'])) {
            $this->query("UPDATE rate_send_log SET total_records = {$total} WHERE id = {$options['logId']}");
        }

        if($download_sql)
            $search_sql = $download_sql;

        $rate_file = "{$database_export_path}/{$random_filename}";
        $handle = fopen($rate_file, 'w');
        $offset = 0;
        $limit = 2000;
        $search_sql = str_replace('\"', '"', $search_sql);
        set_time_limit(0);
        ini_set('memory_limit', -1);

        while($offset < $total){

            $limit_cond = " LIMIT ". $limit." OFFSET ".$offset;
            $search_sql1 = $search_sql.$limit_cond;
            $rates = $this->query($search_sql1);
            // header
            if(!$offset){
                fputcsv($handle, array_keys($rates[0][0]));
            }
            // data
            if(!empty($rates)) {
                foreach ($rates as $rate) {
                    fputcsv($handle, $rate[0]);
                }
            }
//            if($offset > 50000){
//                sleep(2);
//            }

            $offset += $limit;

            // Update completed records
            if (isset($options['logId'])) {
                $tempOffset = $offset > $total ? $total : $offset;

                $this->query("UPDATE rate_send_log SET completed_records = {$tempOffset} WHERE id = {$options['logId']}");
            }
        }
        fclose($handle);
        if (!$flg_zip || !class_exists('ZipArchive'))
        {
            return $rate_file;
        }

        $zip = new ZipArchive();
        $zip_path = $database_export_path;
        $zip_file = $zip_path . DS . uniqid('rate_') . ".zip";

        if ($zip->open($zip_file, ZIPARCHIVE::CREATE) !== TRUE)
        {
            return false;
        }
        $zip->addFile($rate_file, $random_filename);
        $zip->close();
        return $zip_file;
    }

    public function get_client_email_by_ratetable($id, $status = NULL)
    {
        $extra_cond = "";
        if (isset($status)) {
            $extra_cond = " AND resource.active=$status ";
        }
        $sql = "SELECT DISTINCT resource.resource_id,resource.active,resource.client_id,resource.alias,client.rate_email,client.email,resource_prefix.tech_prefix,client.name FROM resource INNER JOIN
client on resource.client_id = client.client_id INNER JOIN resource_prefix on resource.resource_id = resource_prefix.resource_id
WHERE resource_prefix.rate_table_id = {$id} AND resource.ingress = TRUE $extra_cond";
        $ingress_mail_arr = $this->query($sql);
        //pr($ingress_mail_arr);
        return $ingress_mail_arr;
    }

    public function get_us_rate_table()
    {
        $data = $this->find('all',array(
                'fields' => array('rate_table_id','name'),
                'conditions' => array(
                    'jur_type' => array(1,2),
                    'origination' => false,
                ),
                'order' => array('name' => 'asc'),
            )
        );
        return $data;
    }

    public function get_effective_date($rate_table_id,$start_effective_date = '')
    {
        $time_limit = 'NOW()';
        if ($start_effective_date)
            $time_limit = $start_effective_date;
        $sql = "SELECT max(effective_date) as max_date,min(effective_date) as min_date FROM rate WHERE
rate_table_id = $rate_table_id AND (end_date is null or end_date >= '$time_limit') and effective_date <= '$time_limit' ";
        return $this->query($sql);
    }

    public function get_ip_prefix($rate_table_id,$resource_id = '')
    {
        $sql = <<<SQL
SELECT t2.ip,t2.port,t1.tech_prefix,t3.alias from resource_prefix as t1 left join (select ip,port,resource_id from resource_ip
where reg_type = 0  ) as t2 on  t1.resource_id = t2.resource_id inner join resource as t3 on t1.resource_id = t3.resource_id
 where  t1.resource_id = $resource_id
SQL;
        return $this->query($sql);
    }

    public function generateRateSendSql($rateTableId, $headers, $effectiveDate, $resultFile)
    {
        if(empty($headers)) {
            $headers = "*";
        }

        $wheres = "WHERE rate_table_id = {$rateTableId}";
        if(!empty($effectiveDate)) {
//            $wheres .= " AND effective_date >= '{$effective_date}'";
        }

        $sql = "COPY (SELECT {$headers} FROM rate {$wheres}) TO '{$resultFile}' WITH HEADER CSV DELIMITER ','";

        return $sql;
    }

    public function generateFile($sql, $format, $zipFlag, $newRateSql, $logId = null)
    {
        Configure::load("myconf");
        $database_export_path = Configure::read('database_export_path');
        $database_export_path = $database_export_path . DS . self::RATE_DOWNLOAD_DIR;

        if ($format == 1) {
            $random_filename = uniqid('rate_') . '.csv';
            $delimiter = ",";
        } else {
            $random_filename = uniqid('rate_') . '.xls';
            $delimiter = "\t";
        }
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        set_time_limit(0);
        // Bad way, let's try without this
//        ini_set('memory_limit', -1);

        $rates = $this->query($sql);
        $newRates = array();

        if ($newRateSql) {
            $newRates = $this->query($newRateSql);
        }

        if ($logId) {
            // Update total_records, completed_records
            $totalRecords = count($rates);
            $updateLimit = 1000;
            $updateLimit = $updateLimit > $totalRecords ? $totalRecords : $updateLimit;
            $this->query("UPDATE rate_send_log SET total_records = {$totalRecords}, completed_records = 0 WHERE id = {$logId}");
        }

        $rate_file = "{$database_export_path}/{$random_filename}";

        if (!file_exists($database_export_path)) {
            mkdir($database_export_path, 0777, true);
        }

        $handle = fopen($rate_file, 'w');

        if (!empty($rates)) {
            $first = $rates[0][0];
            unset($first['rate_id']);
            $headers = array_keys($first);

            foreach ($headers as &$header) {
                if ($header == 'rate') {
                    $header = 'Current Rate';
                    break;
                }
            }

            fputcsv($handle, $headers, $delimiter);
            $newConvertedArray = $this->arrayGroup($newRates);
//            die(var_dump($newConvertedArray));

            foreach ($rates as $key => &$item) {
                if (array_key_exists('new_rate', $item[0])) {
                    if (isset($newConvertedArray[$item[0]['code']])) {
                        $newRate = null;

                        foreach ($newConvertedArray[$item[0]['code']] as $convertedItem) {
                            if ($convertedItem['rate_id'] != $item[0]['rate_id'] && strtotime($convertedItem['effective_date']) > strtotime($item[0]['effective_date'])) {
                                $newRate = $convertedItem['rate'];
                                break;
                            }
                        }
                        $item[0]['new_rate'] = $newRate ? : '';
                    }
                }
                if (array_key_exists('change_status', $item[0])) {
                    if (isset($newConvertedArray[$item[0]['code']])) {
                        $newRate = array();

                        foreach ($newConvertedArray[$item[0]['code']] as $convertedItem) {
                            if ($convertedItem['rate_id'] != $item[0]['rate_id'] && strtotime($convertedItem['effective_date']) > strtotime($item[0]['effective_date'])) {
                                $newRate = $convertedItem;
                                break;
                            }
                        }
                        $item[0]['new_rate'] = $newRate['rate'] ? : '';

                        if (empty($newRate)) {
                            $oldRate = null;

                            for ($i = count($newConvertedArray[$item[0]['code']]) - 1; $i >= 0; $i--) {
                                if ($newConvertedArray[$item[0]['code']][$i]['rate_id'] != $item[0]['rate_id'] && strtotime($newConvertedArray[$item[0]['code']][$i]['effective_date']) <  strtotime($item[0]['effective_date'])) {
                                    $oldRate = $newConvertedArray[$item[0]['code']][$i]['rate'];
                                    break;
                                }
                            }

                            if (!$oldRate || empty($oldRate)) {
                                $item[0]['change_status'] = 'New Code';
                            } elseif (!empty($item['end_date'])) {
                                $item[0]['change_status'] = 'Delete';
                            } else {
                                $item[0]['change_status'] = 'No Change';
                                $item[0]['new_rate'] = $item[0]['rate'];
                            }
                        } else {
                            if ($newRate['rate'] > $item[0]['rate']) {
                                $item[0]['change_status'] = 'Increase';
                            } else if ($newRate['rate'] < $item[0]['rate']) {
                                $item[0]['change_status'] = 'Decrease';
                            } else if ($newRate['rate'] == $item[0]['rate'] || empty($item[0]['end_date'])) {
                                $item[0]['new_rate'] = $item[0]['rate'];
                                $item[0]['change_status'] = 'No Change';
                            } else {
                                if(empty($item[0]['rate'])) {
                                    $item[0]['change_status'] = 'New Code';
                                } else {
                                    $item[0]['change_status'] = 'No Change';
                                }
                            }
                            $item[0]['effective_date'] = $newRate['effective_date'];
                        }

                        if ($item[0]['change_status'] == 'New Code' && strtotime($item[0]['effective_date']) < strtotime(date('Y-m-d H:i:s'))) {
                            $item[0]['change_status'] = 'No Change';
                            $item[0]['new_rate'] = $item[0]['rate'];
                        }
                    }
                }
                unset($item[0]['rate_id']);

                if (isset($item[0]['end_date']) && isset($item[0]['change_status']) && $item[0]['change_status'] != 'Delete') {
                    $item[0]['end_date'] = '';
                }

                fputcsv($handle, $item[0], $delimiter);

                if ($logId) {
                    // Update completed_records
                    $completedRecords = $key + 1;
                    if (($completedRecords % $updateLimit == 0) || ($totalRecords == $completedRecords)) {
                        $this->query("UPDATE rate_send_log SET completed_records = {$completedRecords} WHERE id = {$logId}");
                    }
                }
            }
            fclose($handle);
            if (!$zipFlag || !class_exists('ZipArchive')) {
                return $rate_file;
            }
            $zip = new ZipArchive();
            $zip_path = $database_export_path;
            $zip_file = $zip_path . DS . uniqid('rate_') . ".zip";

            if ($zip->open($zip_file, ZIPARCHIVE::CREATE) !== TRUE) {
                return false;
            }
            $zip->addFile($rate_file, $random_filename);
            $zip->close();

            return $zip_file;
        } else {
            fclose($handle);
            return $rate_file;
        }
    }

    public function arrayGroup($array)
    {
        $convertedArray = array();

        foreach ($array as $item) {
            $convertedArray[$item[0]['code']] = array();
        }
        foreach ($array as $item) {
            $convertedArray[$item[0]['code']][] = array(
                'rate_id' => $item[0]['rate_id'],
                'rate' => $item[0]['rate'],
                'effective_date' => $item[0]['effective_date']
            );
        }

        return $convertedArray;
    }

}
