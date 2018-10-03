<?php

class Blocklist extends AppModel {

    var $name = 'Blocklist';
    var $useTable = "resource_block";
    var $primaryKey = "res_block_id";

    /**
     * 验证
     * @return true 有错误信息
     *     false 没有错误信息
     */
    function validate_block($data, $post_arr) {

        $error_flag = false; //错误信息标志
        $res_block_id = $this->getkeyByPOST('res_block_id', $post_arr);
        $engress_res_id = $data['Blocklist']['engress_res_id'];
        $digit = $data['Blocklist']['digit'];
        $egressclient_id = $data['Blocklist']['egress_client_id'];
        $ingressclient_id = $data['Blocklist']['ingress_client_id'];
        $ingress_res_id = $data['Blocklist']['ingress_res_id'];
        $time_profile_id = $data['Blocklist']['time_profile_id'];

        //验证落地网关
        if (empty($engress_res_id) && empty($egressclient_id)) {
            $this->create_json_array('#BlocklistEngressResId', 101, 'egress and EgressCarriers  must  choose one');
            $error_flag = true; //有错误信息
        }


        if (empty($ingress_res_id) && empty($ingressclient_id)) {
            $this->create_json_array('#BlocklistIngressResId', 101, 'ingress and ingressCarriers  must  choose one');
            $error_flag = true; //有错误信息
        }

        if (!empty($digit)) {
            if (!preg_match('/[0-9]+/', $digit)) {
                $this->create_json_array('#BlocklistDigit', 101, 'Please fill Prefix field correctly (only  digits allowed).');
                $error_flag = true; //有错误信息
            }
        }

        if (empty($time_profile_id)) {
            $this->create_json_array('#BlocklistTimeProfileId', 101, 'Please fill Time Profile field');
            $error_flag = true; //有错误信息
        }


        return $error_flag;
    }

    /**
     * 验证客户名字不能重复
     * @param unknown_type $res_id
     * @param unknown_type $a
     */
    function check_name($client_id, $name) {


        $name = "'" . $name . "'";
        empty($client_id) ? $sql = "select count(*) from client where name=$name " :
                        $sql = "select count(*) from client where name=$name  and client_id<>$client_id";
        $c = $this->query($sql);
        if (empty($c)) {
            return 0;
        } else {
            return $c[0][0]['count'];
        }
    }

    /**
     * 添加Client or 更新Client
     * @param unknown_type $data
     * @param unknown_type $post_arr
     * @return 
     */
    function saveOrUpdate($data, $post_arr) {
        $msgs = $this->validate_block($data, $post_arr);
        if (!empty($msgs)) {
            return false; //add fail
        } else {
            $res_block_id = $this->saveOrUpdate_block($data, $post_arr); //添加或者更新
            return true; //add succ
        }
    }

    /**
     * 添加Client or 更新Client
     * @param unknown_type $data
     * @param unknown_type $post_arr
     */
    function saveOrUpdate_block($data, $post_arr) {

        $res_block_id = $this->getkeyByPOST('res_block_id', $post_arr);
        //  pr($data);
        //
	  if (empty($data['Blocklist']['ingress_res_id'])) {
            $data['Blocklist']['ingress_res_id'] = NULL;
        }
        if (!empty($res_block_id)) {
            //更新
            $data['Blocklist']['res_block_id'] = $res_block_id;
            $this->save($data ['Blocklist']);
        } else {
            //添加
            $this->save($data ['Blocklist']);
            $res_block_id = $this->getlastinsertId();
        }
        return $res_block_id;
    }

    /**
     * 查询某个客户的对接网关
     */
    function ajaxfindIngressbyClientId($client_id) {
        if (empty($client_id)) {
            $r = $this->query("select resource_id ,alias from resource  where ingress  is true order by alias ");
            return $r;
        }
        $r = $this->query("select resource_id ,alias from resource  where ingress  is true   and client_id =$client_id  order by alias ");
        return $r;
    }

    function findIngressbyClientId($client_id) {
        if (empty($client_id)) {
            $r = $this->query("select resource_id ,alias from resource  where ingress  is true order by alias ");
        } else {
            $r = $this->query("select resource_id ,alias from resource  where ingress  is true   and client_id =$client_id  order by alias ");
        }
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['resource_id'];
            $l[$key] = $r[$i][0]['alias'];
        }
        return $l;
    }

    /* 8
     * 通过对接网关查找客户id
     */

    function findClient_id($ingress_id) {

        if (empty($ingress_id)) {
            return '';
        }
        $r = $this->query("select client_id from resource where resource_id=$ingress_id");
        $id = $r[0][0]['client_id'];
        if (empty($id)) {
            return '';
        } else {
            return $id;
        }
    }

    /**
     * 查询对接网关
     */
    function findIngress() {
        $r = $this->query("select resource_id ,alias from resource  where ingress  is true order by alias ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['resource_id'];
            $l[$key] = $r[$i][0]['alias'];
        }
        return $l;
    }

    /**
     * 查询落地网关
     */
    function findEgress() {
        $r = $this->query("select alias ,resource_id from resource where egress  is true order by alias");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['resource_id'];
            $l[$key] = $r[$i][0]['alias'];
        }

        return $l;
    }

    /**
     * 查询客户
     */
    function findClient() {
        $r = $this->query("	select  client_id ,name     from   client  where client_id 
		 in (select  client_id  from   resource   where   ingress is true )   order by  client_id");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['client_id'];
            $l[$key] = $r[$i][0]['name'];
        }

        return $l;
    }

    /**
     * 删除
     * @param unknown_type $id
     */
    public function del($id) {

        $delRes = $this->query("delete from resource_block where res_block_id = $id");
        return $delRes;
    }

    /**
     * 普通查询
     * @paranknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function findAll($currPage=1, $pageSize=10, $order=null) {
        if (empty($order)) {
            $order = "res_block_id  desc";
        }
        $login_type = $_SESSION['login_type'];
        //admin
        if ($login_type == 1) {
            $sql1 = "select count(res_block_id) as c from resource_block  ";
            $sql2 = "";
        }
        if ($login_type == 3) {
            $client_id = $_SESSION['sst_client_id'];
        }
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query($sql1);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select resource_block.res_block_id,e.egress_name,i.ingress_name,digit,
		(select name from time_profile where time_profile_id = resource_block.time_profile_id) as time_profile
		    from  resource_block
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=resource_block.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=resource_block.ingress_res_id
		$sql2
		order by  $order   
 	limit   '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 模糊查询
     * @param unknown_type $condition
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    function likequery($key, $currPage=1, $pageSize=10) {

        $condition = "'%" . $key . "%'";
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(res_block_id) as c
	 	from resource_block  as b  
	 	 where digit::varchar   like $condition 
	 	or (select count(*)>0 from resource   where resource_id=b.engress_res_id 	 	and alias like $condition )
	 	
	 	or(select  count(*)>0 from resource where resource_id =b.ingress_res_id and alias like $condition)
	 	");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select b.res_block_id,e.egress_name,i.ingress_name,b.digit
		    from  resource_block   as b
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=b.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=b.ingress_res_id
	 	where digit::varchar like $condition 
	 	or (select count(*)>0 from resource where resource_id=b.engress_res_id and alias like $condition )
	 	or (select count(*)>0 from resource where resource_id =b.ingress_res_id and alias like $condition)

	order by res_block_id  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 高级搜索
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function Advancedquery($data, $currPage=1, $pageSize=10) {

        //解析搜索条件
        $condition = "where   ";
        $i = 0;
        $len = intval(count($data['Blocklist']));


        foreach ($data['Blocklist'] as $key => $value) {

            //判断是否存在搜索条件
            if ($value == '') {
                continue;
            }
            $tmp = "resource_block." . $key . "='" . $value . "'  and   ";
            $condition = $condition . $tmp;
            $i++;
        }


        $where = substr($condition, 0, strrpos($condition, 'a'));
        //pr($where);
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(res_block_id) as c from resource_block  $where");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select resource_block.res_block_id,e.egress_name,i.ingress_name,digit
		    from  resource_block
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=resource_block.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=resource_block.ingress_res_id
		
		    $where
	order by res_block_id  	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 查询时间段
     * @param integer $reseller_id 代理商ID
     */
    public function getTimeProfiles() {
        $sql = "select time_profile_id,name from time_profile order BY name ASC ";
        return $this->query($sql);
    }
    
    /*
     * 通过ID查名称
     */
    public function getDigitByID($ids){
        $sql = "SELECT digit from resource_block where res_block_id in ($ids)";
        $result = $this->query($sql);
        return $result;
    }

}