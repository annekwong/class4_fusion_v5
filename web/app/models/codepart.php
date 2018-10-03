<?php
class Codepart extends AppModel {
	var $name = 'Codepart';
	var $useTable = "code_part";
	var $primaryKey = "code_part_id";
	
	
	
	
		/**
	 * 验证信息
	 */
	function validate_res($data,$post_arr){
    $error_flag='false';//错误信息标志
    $code_part_id=$this->getkeyByPOST('code_part_id',$post_arr);
			$start_code=$data['Codepart']['start_code'];
		 	$end_code=$data['Codepart']['end_code'];
		 	$rate=$data['Codepart']['rate'];
		 	$setup_fee=$data['Codepart']['setup_fee'];
		 	$min_time=$data['Codepart']['min_time'];
		 	$grace_time=$data['Codepart']['grace_time'];
		 	$interval=$data['Codepart']['interval'];
		 	$seconds=$data['Codepart']['seconds'];
			$month_fee=$data['Codepart']['month_fee'];
			$active_fee=$data['Codepart']['active_fee'];

		 	

		 	
		 		//判空 
	    if(empty($start_code)){
    	 $this->create_json_array('#CodepartStartCode',101,'请输入开始号段');
    	 $error_flag='true';//有错误信息
               }
		    if(empty($end_code)){
    	 $this->create_json_array('#CodepartEndCode',101,'请输入结束号段');
    	 $error_flag='true';//有错误信息
               }
		    if(empty($rate)){
    	 $this->create_json_array('#CodepartRate',101,'请输入每分钟费用');
    	 $error_flag='true';//有错误信息
               }
		    if(empty($setup_fee)){
    	 $this->create_json_array('#CodepartSetupFee',101,'请输入通话费用');
    	 $error_flag='true';//有错误信息
               }
		    if(empty($min_time)){
    	 $this->create_json_array('#CodepartMinTime',101,'请输入首次时长');
    	 $error_flag='true';//有错误信息
               }
		    if(empty($grace_time)){
    	 $this->create_json_array('#CodepartGraceTime',101,'请输入免费时长');
    	 $error_flag='true';//有错误信息
               }
               
			    if(empty($interval)){
    	 $this->create_json_array('#CodepartInterval',101,'请输入计费周期');
    	 $error_flag='true';//有错误信息
               }
               
			    if(empty($seconds)){
    	 $this->create_json_array('#CodepartSeconds',101,'1分钟多少秒');
    	 $error_flag='true';//有错误信息
               }
               
				    if(empty($month_fee)){
    	 $this->create_json_array('#CodepartMonthFee',101,'月费');
    	 $error_flag='true';//有错误信息
               }
               
				    if(empty($active_fee)){
    	 $this->create_json_array('#CodepartActiveFee',101,'启动费用');
    	 $error_flag='true';//有错误信息
               }
               
			 		//判空 
	    if(!empty($start_code)){
	    	    		    if (!ereg ( "^[0-9]+$", $start_code )){
				 $this->create_json_array('#CodepartStartCode',101,'开始号段必须为数字');
    	 $error_flag='true';//有错误信息
		} 
	    	
    
               }
               
				 		//判空 
	    if(!empty($end_code)){
	    		    if (!ereg ( "^[0-9]+$", $end_code )){
				 $this->create_json_array('#CodepartEndCode',101,'结束号段必须为数字');
    	 $error_flag='true';//有错误信息
		} 
	    	
    
               }
               

	 return $error_flag;
	}
	
	
function saveOrUpdate($data,$post_arr){
	  $msgs=$this->validate_res($data,$post_arr);//错误信息
	 
	  if($msgs=='true'){
	  return 'fail';//add fail
	  }
	  
	  $res_id=$this->saveOrUpdate_resource($data,$post_arr);//添加或者更新
	  if (!empty($res_id)){
     return $res_id;
	  }
   
}
	
/**
 * 添加网关组
 */	
	
	function saveOrUpdate_resource($data,$post_arr){
$data['Codepart']['ingress_id']=$_SESSION['codepartengress'];
	  $code_part_id=$this->getkeyByPOST('code_part_id',$post_arr);
		if(!empty($code_part_id)){
			//更新resource
		 $data['Codepart']['code_part_id']=$code_part_id;
		 $this->save ( $data ['Codepart']);
	}else{
		//添加resource
		$this->save ( $data ['Codepart'] );
		$res_id = $this->getlastinsertId ();
	}
return $res_id;
	
	}
	
		//查询号段管理
	public function getcodeparts($currPage=1,$pageSize=15,$search=null,$egress_id){
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql = "select count(code_part_id) as c from code_part where 1=1";
		if (!empty($search)){
			$sql .= " and start_code like '%$search%'";
		} 
		if (!empty($egress_id)) {
			$sql .= " and ingress_id = '$egress_id'";
		}
		
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;

		$sql = "select code_part_id,start_code,end_code,rate,setup_fee,min_time,grace_time,interval,seconds,month_fee,active_fee
							from code_part where 1=1";
		
		if (!empty($search)){ $sql .= " and start_code like '%$search%'";}
		if (!empty($egress_id)){ $sql .= " and ingress_id = '$egress_id'";}
		$sql .= " limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	
	

}