<?php

class Websession extends AppModel
{

    var $name = 'Websession';
    var $useTable = 'web_session';
    var $primaryKey = 'id';

    public function findAll($order = null, $where = '')
    {
        if (!empty($order))
        {
            $order = "$order";
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        $role_where = '';
        //权限条件
        if ($login_type == 3)
        {
            $privilege = " and (client_id = {$_SESSION['sst_client_id']})";
        }

        if (isset($_GET['role']) && $_GET['role'] != '')
        {
            $role_where = " and (users.role_id = {$_GET['role']})";
        }

//模糊搜索
        $like_where = !empty($_GET['search']) && strcmp('Search....', $_GET['search']) ? " and (users.name like '%{$_GET['search']}%'  or  users.user_id::text like '%{$_GET['search']}%' 
	 or  users.client_id::text like '%{$_GET['search']}%' or fullname::text like '%{$_GET['search']}%')" : '';

        $name_where = !empty($_GET['name']) ? "  and (users.name::text like '%{$_GET['name']}%')" : '';
        $active_where = !empty($_GET['host']) ? "  and (web_session.host = '{$_GET['host']}')" : '';




        $totalrecords = $this->query("select count(id) as c from web_session
left join users  on  users.user_id=web_session.user_id	
	 where users.name != 'dnl_support' $where
	  $like_where    $name_where    $role_where $active_where   $privilege  ");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  web_session.user_id,users.name as user_name ,web_session.create_time,host,agent,msg from  web_session
left join users  on  users.user_id=web_session.user_id		where (users.name != 'dnl_support' or web_session.user_id is null)   $where
		$like_where    $name_where     $role_where $active_where   $privilege";
        
        $sql .= " $order  	limit '$pageSize' offset '$offset'"; 
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

}