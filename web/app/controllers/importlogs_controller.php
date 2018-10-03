<?php
class ImportlogsController extends AppController{
	
	var $name = 'Importlogs';
	var $helpers = array('javascript','html');
	

public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}


function check_data(){
			$id=$this->params['pass'][0];
			$list=	$this->Importlog->query("select  upload_table,upload_table_id,upload_real_table  from error_info  where id=$id");
			$upload_table=$list[0][0]['upload_table'];
			$upload_table_id=intval($list[0][0]['upload_table_id']);
			$upload_real_table=$list[0][0]['upload_real_table'];
			$this->Session->write('m',$this->Importlog->create_json(201,'Check Success'));
			if($upload_real_table=='rate'){
				$notice=$this->Importlog->query("select *  from upload_rate ($upload_table_id,'$upload_table',$id)");
				$this->redirect("/clientrates/view/$upload_table_id");
			}
			if($upload_real_table=='code'){
				$this->Importlog->query("select *  from upload_code ($upload_table_id,'$upload_table')");
				$this->redirect("/codedecks/codes_list/$upload_table_id");
			}
			if($upload_real_table=='conf_member_list'){
				$this->Importlog->query("select *  from upload_conf_member_list ($upload_table_id,'$upload_table')");
				$this->redirect("/accountsips/conf_member_list/$upload_table_id");
			}
			if($upload_real_table=='translation_item'){
				$this->Importlog->query("select *  from upload_translation_item ($upload_table_id,'$upload_table')");
				$this->redirect("/digits/translation_details/$upload_table_id");
			}
			if($upload_real_table=='code_part'){
				$this->Importlog->query("select *  from upload_codepart ($upload_table_id,'$upload_table')");
				$this->redirect("/codeparts/codepart/$upload_table_id");
			}
			if($upload_real_table=='route'){
				$this->Importlog->query("select *  from upload_route ($upload_table_id,'$upload_table')");
				$this->redirect("/routestrategys/routes_list/$upload_table_id");
			}
			if($upload_real_table=='resource_block'){
				$this->Importlog->query("select *  from upload_resource_block ('$upload_table')");
				$this->redirect("/blocklists/view/");
			}
			if($upload_real_table=='product_items'){
				$this->Importlog->query("select *  from upload_product_items ($upload_table_id,'$upload_table')");
				$this->redirect("/products/route_info/$upload_table_id");
			}
			if($upload_real_table=='jurisdiction_prefix'){
				$this->Importlog->query("select *  from upload_jurisdiction_prefix ($upload_table_id,'$upload_table')");
				$this->redirect("/systemlimits/jurisdiction_view_prefix/$upload_table_id");
			}
			$this->redirect (array ('action' => 'view' ) );
}


//下载原文件(上传记录)
function again_download2(){

		$id=$this->params['pass'][0];
   $list=	$this->Importlog->query("select  filepath,realfilename,filename,filesize  from error_info  where id=$id");
  	$download_path=$list[0][0]['filepath'];
  	$realfilename=$list[0][0]['realfilename'];
   $status=$this->Importlog->careate_download_log('下载code上传原文件',$download_path,$realfilename);//生成下载记录
   if($status==14){
   	$this->Importlog->query("update  error_info set status=7  where id=$id");
   $this->Session->write('m',$this->Importlog->create_json(101,'你下载的文件已经被删除,无法下载 '));
		$this->redirect (array ('action' => 'view' ) );
   	
   }
  	Configure::write('debug',0);
  	$this->layout='';

	
}

/**
 * 重新下载
 */
function again_download(){
		$id=$this->params['pass'][0];
 $list=	$this->Importlog->query("select  download_sql  from error_info  where id=$id");
 $download_sql=$list[0][0]['download_sql'];
	
 //pr($download_sql);
 $this->Importlog->export__sql_data('Again Download',$download_sql,'tmp');
	  	Configure::write('debug',0);
  	$this->layout='';
}

/**
 * 下载原文件
 */
function download_oldfile(){
	$user_id=$_SESSION['sst_user_id'];
	$reseller_id=$_SESSION['sst_reseller_id'];
	$id=$this->params['pass'][0];
 $list=	$this->Importlog->query("select  filepath,realfilename ,download_sql from error_info  where id=$id");
 $download_file=$list[0][0]['filepath'];
 $file_name=$list[0][0]['realfilename'];
 $download_sql=$list[0][0]['download_sql'];


  	$date=date('Y-m-d H:i:s');
  	$download_path=__('local_download_path',true);
  	$download_file=$download_path.$file_name;// /tmp/exports/
 
	   if(!file_exists($download_file)){
       echo('对不起,你要下载的文件不存在。');
       $status=11;//下载失败
   }else{
 
   	$file_size=filesize($download_file);
   	 $status=12;//下载完成
   }
    	$sql2="insert  into   error_info
  	   (downloadtime,objectives,filepath,filesize,realfilename,user_id,status,reseller_id,download_sql)
values('$date','下载原文件','$download_path',$file_size,'$file_name',$user_id,$status,$reseller_id,'$download_sql');  ";
  	$this->Importlog->query($sql2);
    $this->Importlog->download_csv($download_file,$file_name);//下载
  	Configure::write('debug',0);
  	$this->layout='';
}



	
	function del(){
		$id=$this->params['pass'][0];
	
   $list=	$this->Importlog->query("select  filepath,realfilename ,download_sql from error_info  where id=$id");
   
   $download_file=$list[0][0]['filepath'];
   $file_name=$list[0][0]['realfilename'];
    $download_sql=$list[0][0]['download_sql'];
   	$this->Importlog->query("update error_info  set  status=13, download_sql='$download_sql' where realfilename='$file_name'");
		//删除上传的文件
		
  if(file_exists($download_file)){
   unlink($download_file.$file_name);
  }
  
		$this->Session->write('m',$this->Importlog->create_json(101,__('alluploadsuccdel',true)));
		$this->redirect (array ('action' => 'downlist' ) );
	}
	
	
	
	/**
	 * 按时间端删除
	 */
		function delbytime(){
		$start=$this->params['pass'][0];
		$end=$this->params['pass'][1];
		$this->Importlog->query("update error_info  set  status=13    where   downloadtime  between  '$start'  and  '$end'");
		$r=	$this->Importlog->query("select  filepath  from error_info  where downloadtime  between  '$start'  and  '$end'");
		
				$size=count($r);
				for($i=0;$i<$size;$i++){
					 $download_file=$r[$i][0]['filepath'];
				  if(file_exists($download_file)){
   unlink($download_file);
  }
					
				}		
		$this->Session->write('m',$this->Importlog->create_json(101,__('alluploadsuccdel',true)));
		$this->redirect (array ('action' => 'downlist' ) );
	}
	
	
	
	function delall(){
		$this->Importlog->query("update error_info  set  status=13  ");
		$r=	$this->Importlog->query("select  filepath  from error_info ");
				$size=count($r);
				for($i=0;$i<$size;$i++){
					 $download_file=$r[$i][0]['filepath'];
				  if(file_exists($download_file)){
   unlink($download_file);
  }
					
				}		
		$this->Session->write('m',$this->Importlog->create_json(101,__('alluploadsuccdel',true)));
		$this->redirect (array ('action' => 'downlist' ) );
	}
		

	
	/**
	 * 查询
	 */
	public function view(){
	$this->set('p', $this->Importlog->findAll());
			
	}
	
	
	
	public function downlist(){
		$this->set('p', $this->Importlog->downlist());
	}	
}
	


?>
