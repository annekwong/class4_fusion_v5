<?php

class Jurisdictionprefix extends AppModel
{

    var $name = 'Jurisdictionprefix';
    var $useTable = 'jurisdiction_prefix';
    var $primaryKey = 'id';

    function find_all_valid()
    {
        $r = $this->query('select  distinct id, name   from   jurisdiction_country');
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i ++)
        {
            $key = $r [$i] [0] ['id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    public function view_rate_table($country_id, $order = null)
    {
        $like_prefix = '';
        if (empty($order))
        {
            $order = 'id desc';
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件

        if (!empty($_GET['search']))
        {
            $arr_search = explode(',', $_GET['search']);
            $arr_length = count($arr_search);
            for ($i = 0; $i < $arr_length; $i++)
            {
                $like_prefix.="prefix::text ilike '%$arr_search[$i]%'";
                if ($i + 1 != $arr_length && !empty($arr_search[$i + 1]))
                {
                    $like_prefix.=" or ";
                }
            }
        }


// and (prefix::text like '%{$_GET['search']}%'
//模糊搜索
        $like_where = !empty($_GET['search']) ? " and ( $like_prefix or  jurisdiction_country_name ilike '%{$_GET['search']}%' or alias ilike '%{$_GET['search']}%')" : '';
        $prefix_where = !empty($_GET['name']) ? " and (prefix::text='{$_GET['name']}')" : '';
        $totalrecords = $this->query("select count(*) as c from jurisdiction_prefix where 1=1   $like_where    $prefix_where   $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;

        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  * from  jurisdiction_prefix where 1=1
		$like_where       $prefix_where $privilege  ";


        if ($country_id != '')
        {
            $sql.="and id=$country_id";
        }

        $sql .= " order by  $order  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function view($order = null)
    {
        $like_prefix = '';
        if (empty($order))
        {
            $order = 'jurisdiction_country_name ASC, prefix ASC';
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if (!empty($_GET['search']) && strcmp('Search', $_GET['search']))
        {
            $arr_search = explode(',', $_GET['search']);
            $arr_length = count($arr_search);
            for ($i = 0; $i < $arr_length; $i++)
            {
                $like_prefix.="prefix::text ilike '%$arr_search[$i]%'";
                if ($i + 1 != $arr_length && !empty($arr_search[$i + 1]))
                {
                    $like_prefix.=" or ";
                }
            }
        }


// and (prefix::text like '%{$_GET['search']}%'
//模糊搜索
        $like_where = !empty($_GET['search']) && strcmp('Search', $_GET['search']) ? " and ( $like_prefix or jurisdiction_name ilike '%{$_GET['search']}%' or   jurisdiction_country_name ilike '%{$_GET['search']}%' or alias ilike '%{$_GET['search']}%')" : '';
        $prefix_where = !empty($_GET['name']) ? " and (prefix::text='{$_GET['name']}')" : '';
        $totalrecords = $this->query("select count(*) as c from jurisdiction_prefix where 1=1   $like_where    $prefix_where   $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;

        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  * from  jurisdiction_prefix where 1=1
		$like_where       $prefix_where $privilege  ";
        $sql .= " order by  $order  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function save_jurisdictionprefix($item)
    {
        if (empty($item['id']))
        {
            unset($item['id']);
        }
        $this->create();
        $result5 = $this->save($item);
        $this->commit();
        if (!$result5)
        {
            return array('flg' => 3);
        }
        return array('flg' => 0);
    }

}

?>
