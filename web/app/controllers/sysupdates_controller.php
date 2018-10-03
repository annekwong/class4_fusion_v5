<?php
class SysupdatesController extends AppController {
	
	
	
	
	
	
	var $name = 'Sysupdates';
	
		//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_updatesoft');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					
					parent::beforeFilter();
	}
	
	
	
	
	public function addsystem_update(){
		$id=$this->params['pass'][0];
			$name=$this->params['pass'][1];
		$this->Sysupdate->query("insert into system_update_types(id,name)values($id,'$name')");
		$this->redirect("/sysupdates/link_list");
		
	}
	
	
	public function link_list() {
		
		$r = $this->Sysupdate->query ( "select * from system_update_types" );
		$size = count ( $r );
		$l = array ();
		for($i = 0; $i < $size; $i ++) {
			$key = $r [$i] [0] ['id'];
			$l [$key] = $r [$i] [0] ['name'];
		}
	$this->set('update_type',$l);
		
		
		$currPage = 1;
		$pageSize = 100;
		
		if (! empty ( $_REQUEST ['page'] )) {
			$currPage = $_REQUEST ['page'];
		}
		
		if (! empty ( $_REQUEST ['size'] )) {
			$pageSize = $_REQUEST ['size'];
		}
		
		if (! empty ( $_REQUEST ['edit_id'] )) {
			$sql = "select *,(select name from reseller where reseller_id = system_update.reseller_id) as reseller from system_update where system_update_id = {$_REQUEST['edit_id']}";
			$result = $this->Sysupdate->query ( $sql );
			//分页信息
			require_once 'MyPage.php';
			$results = new MyPage ();
			$results->setTotalRecords ( 1 ); //总记录数
			$results->setCurrPage ( 1 ); //当前页
			$results->setPageSize ( 1 ); //页大小
			$results->setDataArray ( $result );
			$this->set ( 'edit_return', true );
		} else {
			$results = $this->Sysupdate->getList ( $currPage, $pageSize );
		}
		$this->set ( 'p', $results );
		
		$reseller_id = $this->Session->read ( 'sst_reseller_id' );
		$this->Sysupdate->generateTree ( $reseller_id );
		$this->set ( 'r_reseller', Sysupdate::$show_reseller );
	}
	
	public function add(){
		Configure::write('debug',0);
		$qs = $this->Sysupdate->add();
		echo $qs;
	}
	
	public function update(){
		Configure::write('debug',0);
		$qs = $this->Sysupdate->update();
		echo $qs;
	}
	
	public function del($id){
		if ($this->Sysupdate->del($id)) {
			$this->Sysupdate->create_json_array('',201,__('del_suc',true));
		} else {
			$this->Sysupdate->create_json_array('',101,__('del_fail',true));
		}
		$this->Session->write('m',Sysupdate::set_validator());
		$this->redirect('/sysupdates/link_list');
	}
}
?>