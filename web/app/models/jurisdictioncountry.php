<?php
	class Jurisdictioncountry extends AppModel {
		var $name = 'Jurisdictioncountry';
		var $useTable = 'jurisdiction_country';
		var $primaryKey = 'id';
		var $xvalidatevar=Array(
			'name'=>Array(
				'noEmpty'=>'The field name cannot be NULL.',
				'unique'=>'name is already in use!'
			)
		);

	function check_name($id, $name) {
		$name = "'" . $name . "'";
		empty ( $id ) ? $sql = "select count(*) from jurisdiction where name=$name " : $sql = "select count(*) from jurisdiction where name=$name  and id<>$id";
		$c = $this->query ( $sql );
		if (empty ( $c )) {
			return 0;
		} else {
			return $c [0] [0] ['count'];
		}
	}
	
	

	
		public function view($order=null){
	   empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		  empty($_GET['size'])?$pageSize = 10:	$pageSize = $_GET['size'];
		  if(empty($order))
			  {
			  	$order='id desc';
			  }
			require_once 'MyPage.php';
			$page = new MyPage();
	 		$login_type=$_SESSION['login_type'];
	 		$privilege='';//权限条件
			//模糊搜索
	 		$like_where=!empty($_GET['search'])?" and (name like '%{$_GET['search']}%'  or  id::text like '%{$_GET['search']}%')":'';
	  	$name_where=!empty($_GET['name'])?" and (name='{$_GET['name']}')":'';
	  	$id_where=!empty($_GET['edit_id'])?" and (id={$_GET['edit_id']})":'';
	 		$totalrecords = $this->query("select count(*) as c from jurisdiction_country where 1=1 
	  	$like_where  $id_where  $name_where  $privilege");
			$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
			$page->setCurrPage($currPage);//当前页
			$page->setPageSize($pageSize);//页大小
			$currPage = $page->getCurrPage()-1;
			$pageSize = $page->getPageSize();
			$offset=$currPage*$pageSize;
			$sql = "select  * from  jurisdiction_country where 1=1  
			$like_where $id_where $name_where  $privilege  ";
			$sql.=" order by $order";
	 		$sql .= " 	limit '$pageSize' offset '$offset'";
			$results = $this->query($sql);
			$page->setDataArray($results);
			return $page;
	}	
	

	}
?>