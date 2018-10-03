<?php
class Importlog extends AppModel{
	var $name = 'Importlog';
	var $useTable = "error_info";
	var $primaryKey = "id";
	
	
const ERROR_STATUS_UPLOAD_SUCCESS = 0;# -文件上传成功。 数据还没验证。
const ERROR_STATUS_UPLOAD_EXCEED_INI_FILESIZE = 1; #-上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。 
const ERROR_STATUS_UPLOAD_EXCEED_FORM_FILESIZE = 2; #-上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。 
const ERROR_STATUS_UPLOAD_NOT_COMPLETE = 3 ; #-文件只有部分被上传
const ERROR_STATUS_UPLOAD_NOT_FAIL = 4 ; #-没有文件被上传
const ERROR_STATUS_UPLOAD_TOO_BIG = 5; #-文件上传taida
const ERROR_STATUS_UPLOAD_FILE_EXISTS = 6; #-文件已经存在
const ERROR_STATUS_UPLOAD_SUCCESS_AND_DELETED = 7 ; #-文件上传成功已经被删除		
const ERROR_STATUS_UPLOAD_VERIFIED_AND_SUCCES = 8 ; #-文件已经验证完毕，成功导入主表
const ERROR_STATUS_UPLOAD_UNKNOWN_ERROR = 9; #-有错误
#下载状态
const ERROR_STATUS_DOWNLOAD_FAIL = 11; #-下载失败				
const ERROR_STATUS_DOWNLOAD_SUCCESS = 12; #-下载完成
const ERROR_STATUS_DOWNLOAD_DELETED = 13 ;  #-文件已经被删除';

const ERROR_TYPE_UPLOAD = 1; #" IS '1--上传
const ERROR_TYPE_DOWNLOAD = 2; #--下载';
	
	function ajaxfindIngressbyClientId($client_id){
		if(empty($client_id)){
			$r= $this->query("select resource_id ,alias from resource  where ingress  is true order by alias ");
		return $r;
		}
		$r= $this->query("select resource_id ,alias from resource  where ingress  is true   and client_id =$client_id  order by alias ");
   return $r;
	}
	function findIngressbyClientId($client_id){
		if(empty($client_id)){
			$r= $this->query("select resource_id ,alias from resource  where ingress  is true order by alias ");
	
		}else{
					$r= $this->query("select resource_id ,alias from resource  where ingress  is true   and client_id =$client_id  order by alias ");
		}
		$size=count($r);		
		$l=array();
   for ($i=0;$i<$size ;$i++){
   	$key=$r[$i][0]['resource_id'];
     $l[$key]=$r[$i][0]['alias'];
      }
     return $l;
	}	
	
	
	/*8
	 * 通过对接网关查找客户id
	 */
	function findClient_id($ingress_id){
		
		if(empty($ingress_id)){
		return '';
		}
	$r=$this->query("select client_id from resource where resource_id=$ingress_id");
	$id=$r[0][0]['client_id'];
	if(empty($id)){
	return '';
	}else{
	return $id;
	}
	
	}
	
	

	
		/**
	 * 查询对接网关
	 */
	function findIngress(){
		$r= $this->query("select resource_id ,alias from resource  where ingress  is true order by alias ");
		$size=count($r);		
		$l=array();
   for ($i=0;$i<$size ;$i++){
   	$key=$r[$i][0]['resource_id'];
     $l[$key]=$r[$i][0]['alias'];
      }
     return $l;
	}
	
	
		/**
	 * 查询落地网关
	 */
	function findEgress(){
		$r= $this->query("select alias ,resource_id from resource where egress  is true order by alias");
		$size=count($r);		
		$l=array();
   for ($i=0;$i<$size ;$i++){
   	$key=$r[$i][0]['resource_id'];
     $l[$key]=$r[$i][0]['alias'];
      }
      
     return $l;
	}
	
	
	
		/**
	 * 查询客户
	 */
	function findClient(){
		$r= $this->query("	select  client_id ,name     from   client  where client_id 
		 in (select  client_id  from   resource   where   ingress is true )   order by  client_id");
		$size=count($r);		
		$l=array();
   for ($i=0;$i<$size ;$i++){
   	$key=$r[$i][0]['client_id'];
     $l[$key]=$r[$i][0]['name'];
      }
      
     return $l;
	}	
	


	
	
		public function downlist(){
		 empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 10:	$pageSize = $_GET['size'];
			require_once 'MyPage.php';
			$page = new MyPage();
	 		$login_type=$_SESSION['login_type'];
	 		$privilege='';//权限条件
		 if($login_type==3){
	 			$privilege="  and(client_id::integer={$_SESSION['sst_client_id']}) ";
			 }
		//模糊搜索
	 $like_where=!empty($_GET['search'])?" and (u.name like '%{$_GET['search']}%'  or client.name like '%{$_GET['search']}%'  or  e.client_id::text like '%{$_GET['search']}%'  or
	  id::text like '%{$_GET['search']}%')":'';
 	  //起始金额
	 $start_amount_where=!empty($_GET['start_amount'])?"  and (client_cost::numeric>{$_GET['start_amount']})":'';
	 //结束金额
	 $end_amount_where=!empty($_GET['end_amount'])?"     and (client_cost::numeric<{$_GET['end_amount']})":'';
	 //充值方式
	 $tran_type_where=!empty($_GET['tran_type'])?" and (tran_type={$_GET['tran_type']})":'';
	 $client_where=!empty($_GET ['query'] ['id_clients'])?"  and (client_id::integer={$_GET ['query'] ['id_clients']})":'';
 	 //按时间搜索
 	 $date_where='';
 	 if(!empty($_GET['start_date'])||!empty($_GET['end_date'])){
 	  $start =!empty($_GET['start_date'])?$_GET['start_date']:date ( "Y-m-1  00:00:00" );
	  $end = !empty($_GET['end_date'])?$_GET['end_date']:date ( "Y-m-d 23:59:59" );
    $date_where="  and  (downloadtime  between   '$start'  and  '$end')";
 	 }
	 $totalrecords = $this->query("select count(id) as c from error_info as e
	 	left join client  on client.client_id=e.client_id 
  	left join users  u on u.user_id=e.user_id 
	 	where e.status>10 
	  $like_where  $start_amount_where  $end_amount_where  $client_where    $tran_type_where  $privilege");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$sql = "select e.id,e.type,client.name  as  client_name,u.name as u_name,objectives,downloadtime,e.status from error_info as e
		 left join client  on client.client_id=e.client_id 
		 left join users  u on u.user_id=e.user_id where e.status>10 
		$like_where  $start_amount_where  $end_amount_where  $date_where        $tran_type_where  $privilege  ";
	 $sql .= " order by id     desc  	limit '$pageSize' offset '$offset'";
		$results = $this->query($sql);
		$page->setDataArray($results);
		return $page;
	}	
	
	
	
	

	
	public function findAll(){
	 empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 10:	$pageSize = $_GET['size'];
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 $login_type=$_SESSION['login_type'];
	 $privilege='';//权限条件
		if($login_type==3){
	 		$privilege="  and(client_id::integer={$_SESSION['sst_client_id']}) ";
	 }
	//模糊搜索
	$like_where=!empty($_GET['search'])?" and ( u.name like '%{$_GET['search']}%'  or client.name like '%{$_GET['search']}%'  or  e.client_id::text like '%{$_GET['search']}%'  or
	  id::text like '%{$_GET['search']}%')":'';
 	 //起始金额
	 $start_amount_where=!empty($_GET['start_amount'])?"  and (client_cost::numeric>{$_GET['start_amount']})":'';
//结束金额
	 $end_amount_where=!empty($_GET['end_amount'])?"     and (client_cost::numeric<{$_GET['end_amount']})":'';
   $client_where=!empty($_GET ['query'] ['id_clients'])?"  and (client_id::integer={$_GET ['query'] ['id_clients']})":'';
 	 //按时间搜索
 	 $date_where='';
 	 if(!empty($_GET['start_date'])||!empty($_GET['end_date'])){
 	  $start =!empty($_GET['start_date'])?$_GET['start_date']:date ( "Y-m-1  00:00:00" );
	  $end = !empty($_GET['end_date'])?$_GET['end_date']:date ( "Y-m-d 23:59:59" );
    $date_where="  and  (uploadtime  between   '$start'  and  '$end')";
 	 }
	 $totalrecords = $this->query("select count(id) as c from error_info  as e
	     left join client  on client.client_id=e.client_id 
		    left join users u   on  u.user_id=e.user_id
	 where  e.status<10
	  $like_where  $start_amount_where  $end_amount_where  $client_where      $privilege");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
				$offset=$currPage*$pageSize;
		$sql = "select e.id,e.type,client.name as client_name,u.name as u_name,uploadtime,e.status,upload_param,objectives
		    from  error_info as e
		    left join client  on client.client_id=e.client_id 
		    left join users u   on  u.user_id=e.user_id
		    where   e.status<10
		$like_where  $start_amount_where  $end_amount_where  $date_where          $privilege  ";
	 $sql .= " order by id     desc  	limit '$pageSize' offset '$offset'";
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
function likequery($key,$currPage=1,$pageSize=10){
	
	$condition="'%".$key."%'";
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(res_block_id) as c
	 	from resource_block  as b  
	 	 where digit::varchar   like $condition 
	 	or (select count(*)>0 from resource   where resource_id=b.engress_res_id 	 	and alias like $condition )
	 	
	 	or(select  count(*)>0 from resource where resource_id =b.ingress_res_id and alias like $condition)
	 	");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
   $sql="select b.res_block_id,e.egress_name,i.ingress_name,b.digit
		    from  resource_block   as b
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=b.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=b.ingress_res_id
	 	where digit::varchar   like $condition 
	 	or (select count(*)>0 from resource   where resource_id=b.engress_res_id 	 	and alias like $condition )
	 	or(select  count(*)>0 from resource where resource_id =b.ingress_res_id and alias like $condition)

	order by res_block_id  	limit '$pageSize' offset '$currPage'";
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
}	
	
	
	
	/**
 * 高级搜索
 * @param unknown_type $currPage
 * @param unknown_type $pageSize
 */
	public function Advancedquery($data,$currPage=1,$pageSize=10){
		
		//解析搜索条件
		$condition="where   ";
		$i=0;
		$len=intval(count($data['Blocklist']));
		
		
		foreach ($data['Blocklist'] as $key=>$value){

			//判断是否存在搜索条件
			if($value==''){
		  	continue;
			}
		$tmp= "resource_block.".$key."='".$value."'  and   "; 
		$condition=$condition.$tmp;
		$i++;
		}

		  
$where=substr($condition,0,strrpos($condition, 'a'));
		//pr($where);
		
		
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 	$totalrecords = $this->query("select count(res_block_id) as c from resource_block  $where");
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$sql = "select resource_block.res_block_id,e.egress_name,i.ingress_name,digit
		    from  resource_block
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=resource_block.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=resource_block.ingress_res_id
		
		    $where
	order by res_block_id  	limit '$pageSize' offset '$currPage'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	





}