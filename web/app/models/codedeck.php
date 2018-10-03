<?php

class Codedeck extends AppModel {

    var $name = 'Codedeck';
    var $useTable = 'code_deck';
    var $primaryKey = 'code_deck_id';

    function find_all_valid() {
        return $this->findAll();
    }

    public function getAllCodedecks($currPage=1, $pageSize=15, $search=null, $order=null) {
        if (!empty($order)) {
            $order = "order by $order";
        } else {
            $order = "order by name asc ";
        }
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = array();
        $sql = "select count(code_deck_id) as c from code_deck where 1=1";
        if (!empty($search)) {
            $sql.=' and ';
            $search_arr = explode(',', $search);
            $arr_length = count($search_arr);
            for ($i = 0; $i < $arr_length; $i++) {
                $sql .= "code ilike '%$search_arr[$i]%'";
                if (!empty($search_arr[$i + 1]) && $i + 1 != $arr_length) {
                    $sql.=' or ';
                }
            }
        }

        //	pr($sql);
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select code_deck_id,name,update_at,update_by,
									(
									 select count(code_id) from code where code_deck_id = code_deck.code_deck_id
									) as codes,
									(
									 select count(rate_table_id) from rate_table where code_deck_id = code_deck.code_deck_id
									) as usage
							from code_deck where 1=1 ";
        if (!empty($search)) {
            $sql.=' and ';
            $search_arr = explode(',', $search);
            $arr_length = count($search_arr);
            for ($i = 0; $i < $arr_length; $i++) {
                $sql .= "name ilike '%$search_arr[$i]%'";
                if (!empty($search_arr[$i + 1]) && $i + 1 != $arr_length) {
                    $sql.=' or ';
                }
            }
            //$sql .= " and name like '%$search%'";
        }
        $sql .= " $order limit '$pageSize' offset '$offset' ";
        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    /*
     * 添加号码组
     */

    public function addCodedeck($name) {
        $sql = "insert into code_deck (name,  update_by) values ('$name', '{$_SESSION['sst_user_name']}') RETURNING code_deck_id";
        $rs = $this->query($sql);
        if(empty($rs)) {
            return 0;
        } else {
            return $rs[0][0]['code_deck_id'];
        }
    }

    /*
     * 修改号码组
     */

    public function updateCodedeck($name, $id) {
        $date = date("Y-m-d H:i:s");
        $sql = "update code_deck set name = '$name', update_at = '{$date}', update_by = '{$_SESSION['sst_user_name']}' where code_deck_id = '$id'";
        $rs = $this->query($sql);
        return count($rs) == 0;
    }

    //用来查询指定那个运行商
    public function select_code_name($code_deck_id=null) {
        $sql = "select name from code_deck where code_deck_id= $code_deck_id ";
        $code_name = $this->query($sql);
        return $code_name;
    }

    public function getAllCodesByCodeDeck($code_deck_id=null) {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        require_once 'MyPage.php';
        $page = new MyPage();
        $like_where = '';
        $code_deck_id_where = !empty($code_deck_id) ? "and(code_deck_id=$code_deck_id)" : '';
        $search = !empty($_GET['search']) ? trim($_GET['search']) : '';
        $patten = "/[\s]+/";  //正则格式，匹配多个空格
        $result = preg_split($patten, $search);
        if (count($result) > 1) {
            foreach ($result as $key => $value) {
                $like_where.=" and  name  ~* '{$value}'";
            }
        } else {
            $like_where = !empty($search) ? " and (code.code::text  like '{$search}%' 
			 or  country  ilike '{$search}%'
			 or name  ilike '{$search}%')" : '';
        }
        $code = !empty($_GET['code']) ? trim($_GET['code']) : '';
        $code_where = !empty($code) ? "  and (code='{$code}')" : '';
        $code_name = !empty($_GET['code_name']) ? trim($_GET['code_name']) : '';
        $code_name_where = !empty($code_name) ? "     and (name='{$code_name}')" : '';
        $country = !empty($_GET['country']) ? trim($_GET['country']) : '';
        $country_where = !empty($country) ? " and (country='{$country}')" : '';


        $totalrecords = $this->query("select count(*) as c from code  
	  where    1=1    $code_deck_id_where
	  $like_where  $code_where  $code_name_where $country_where   ");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;

        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  code_id,code,name ,country from  code   where    1=1   $code_deck_id_where
	  $like_where  $code_where  $code_name_where $country_where     ";
        $sql .= "limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /*
     * Get a code by its id
     */

    public function getCodeById($ownid) {
        $sql = "select * from code where code_id = '$ownid'";
        return $this->query($sql);
    }

    /**
     * 分页查询Client groups
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function view_ratetables($currPage = 1, $pageSize = 15, $code_deck_id) {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = 0;
        $sql = "select count(rate_table_id) as c from rate_table where code_deck_id=$code_deck_id";
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select rate_table_id,name,modify_time,create_time,
						
							(select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck,
							(select code from currency where currency_id = rate_table.currency_id) as currency,
						
							(select count(client_id) from client where orig_rate_table_id=rate_table.rate_table_id or term_rate_table_id=rate_table.rate_table_id) as client_rate
						
							from rate_table where code_deck_id=$code_deck_id";


        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////


        return $page;
    }

    public function del($id) {
        $qs = $this->query("delete from code_deck where code_deck_id in($id)");
        return count($qs) == 0;
    }

    public function addCountry($country) {
        if (empty($country)) {
            $this->create_json_array('#country', 101, __('Country is required', true));
        } else {
//            $code_country = $this->query("select * from code_country where country= '$country'");
//            if (empty($code_country[0][0]['country'])) {
//                $sql = "insert into code_country values('$country')";
//                $this->query($sql);
//                $this->create_json_array('#country', 202, __('Country Increase the success', true));
//            } else {
//                $this->create_json_array('#country', 101, __('Country The existence of', true));
//            }
        }
    }

    public function query_country($coun=null) {
        $sql = "selcet * from  where %$coun%";
        $country = $this->query($sql);
        return $country;
    }
    
    /*
     * 通过ID获取名称
     */
    public function getNameByID($ids){
        $sql = "select name from code_deck where code_deck_id in ($ids)";
        $result = $this->query($sql);                
        return $result;
    }

}