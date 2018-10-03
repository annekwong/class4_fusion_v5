<?php

class Digit extends AppModel
{

    var $name = 'Digit';
    var $useTable = 'digit_translation';
    var $primaryKey = 'translation_id';

    public function view($order = null)
    {

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (empty($order))
        {
            $order = 'name asc';
        }
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        //模糊搜索
        $like_where = !empty($_GET['search']) && strcmp('Search', $_GET['search']) ? " and (translation_name ilike '%{$_GET['search']}%'  or  translation_id::text ilike '%{$_GET['search']}%')" : '';
        $name_where = !empty($_GET['name']) ? " and (translation_name='{$_GET['name']}')" : '';
        $totalrecords = $this->query("select count(translation_id) as c from digit_translation where 1=1 
	  	$like_where     $name_where  $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select translation_id as id,translation_name as name, (select count(ref_id) from translation_item where translation_id = digit_translation.translation_id) as trans, translation as updateat from digit_translation where 1=1 
			$like_where  $name_where  $privilege  ";
        $sql.=" order by $order";
        $sql .= " 	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    /**
     *  分页查询Digit mappings
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function getAllDigits($currPage = 1, $pageSize = 15, $search = null)
    {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();

        $totalrecords = 0;

        $sql = "select count(translation_id) as c from digit_translation where 1=1";
        if (!empty($search))
            $sql .= " and translation_name like '%$search%'";


        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        //查询Digits mapping

        $sql = "
							select translation_id as id,translation_name as name,
							(select count(ref_id) from translation_item where translation_id = digit_translation.translation_id)
							as trans,
							translation as updateat from digit_translation where 1=1";

        if (!empty($search))
            $sql .= " and translation_name like '%$search%'";


        $sql .= "   limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////

        return $page;
    }

    /*
     * 添加Translation
     */

    public function addDigitMapping($name = null, $reseller_id)
    {
        $nowtime = date('Y-m-d H:i:s', time() + 6 * 60 * 60);
        $sql = "insert into digit_translation (translation_name,translation) values('$name','$nowtime')";
        if (!empty($reseller_id))
            $sql = "insert into digit_translation (translation_name,translation,reseller_id) values('$name','$nowtime','$reseller_id')";
        $c = $this->query($sql);
        if (count($c) == 0)
        {
            return true;
        }
        return false;
    }

    /**
     * 修改Digit Mapping的名字
     * @param $id Translation ID
     * @param $name Translation Name
     */
    public function modify_name($id = null, $name = null)
    {
        $t = time() + 6 * 60 * 60;
        $lastmodify = date('Y-m-d H:i:s', $t);
        $sql = "update digit_translation set translation_name = '$name',translation = '$lastmodify' where translation_id = '$id'";
        $rs = $this->query($sql);
        if (count($rs) == 0)
        {
            return $lastmodify;
        }
        else
        {
            return false;
        }
    }

    /**
     * 根据ID删除某Digit Mapping
     * @param $id Translation ID
     */
    public function del_translation($id = null)
    {
        if (!empty($id))
        {
            if ($this->del($id))
            {
                return true;
            }
            return false;
        }
    }

    /*
     * 删除所有
     */

    public function deleteAll($table_name, $where)
    {
        if (empty($table_name))
        {
            return false;
        }
        $sql = "delete from $table_name ";
        //where 条件
        if (!empty($where))
        {
            $sql .= $where;
        }

        $rs = $this->query($sql);
        if (count($rs) == 0)
        {
            return true;
        }
        return false;
    }

    /*
     * 删除选中的
     */

    public function deleteSelected($table_name, $p_k, $ids = null)
    {
        $sql = "delete from $table_name where $p_k in ($ids)";
        $rs = $this->query($sql);
        if (count($rs) == 0)
        {
            return true;
        }
        return false;
    }

    /**
     *  分页查询Digit Mapping对应的 详细信息
     * @param int $id Translation ID
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function getItemsByDigit($id = null, $currPage = 1, $pageSize = 15, $search = null)
    {
        $countsql = "select count(ref_id) as c from translation_item where translation_id = '$id'";
        if (!empty($search))
        {
            $countsql .= " and (ani <@ '$search' or dnis <@ '$search')";
        }
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query($countsql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        if ($totalrecords[0][0]['c'] == 0)
        {
            $page->setDataArray(array());
            return $page;
        }
        $sql = "
							select ref_id,ani,dnis,action_ani,action_dnis,ani_method,dnis_method 
							from translation_item where translation_id = '$id'
						 ";
        if (!empty($search))
        {
            $sql .= " and (ani <@ '$search' or dnis <@ '$search') limit '$pageSize' offset '$offset'";
        }
        else
        {
            $sql .= " limit '$pageSize' offset '$offset'";
        }
        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    /*
     * 根据ID获得一条详细规则 
     */

    function beforeDelete()
    {
        $this->bindModel(Array('hasMany' => Array('TranslationItem')));
        $translation_id = $this->id;
        $this->TranslationItem->deleteAll("translation_id=$translation_id");
    }

    public function getTranDetailById($id)
    {
        if (!empty($id))
        {
            return $this->query("select ref_id,translation_id,ani,dnis,
																	action_ani,action_dnis,ani_method,dnis_method 
																	from translation_item where ref_id = '$id'");
        }
    }
    
    public function ajax_refresh_digits()
    {
        $data = array();
        $result = $this->query("SELECT translation_id,translation_name FROM digit_translation ORDER BY translation_id DESC");
        foreach ($result as $row)
        {
            $data[] = array(
                'id'      => $row[0]['translation_id'],
                'name' => $row[0]['translation_name'],
            );
        }
        return $data;
    }

}
