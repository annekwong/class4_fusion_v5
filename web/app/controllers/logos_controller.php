<?php
class LogosController extends AppController {
	var $name = 'Logos';
	var $uses = array ();
	var $helpers = array ('javascript', 'html' );

	//读取该模块的执行和修改权限
	public function beforeFilter() {
		$this->checkSession ( "login_type" ); //核查用户身份
		$login_type = $this->Session->read ( 'login_type' );
		if ($login_type == 1) {
			//admin
			$this->Session->write ( 'executable', true );
			$this->Session->write ( 'writable', true );
		} else {
			$limit = $this->Session->read ( 'sst_retail_rcardpools' );
			$this->Session->write ( 'executable', $limit ['executable'] );
			$this->Session->write ( 'writable', $limit ['writable'] );
		}
		parent::beforeFilter();//调用父类方法
	}
	
	function index()
	{
		if (!empty($_POST['upload']))
		{
			$upload_file = $this->_move_upload_file();
		}
	}
	
	function ilogo()
	{
		//$this->layout='ajax';
		//App::import('Vendor', 'ilogo', array('file' => '../vendors/tcpdf/images/ilogo.png'));
		//$this->layout="image";
		header("Content-type: image/png"); 
		echo file_get_contents("../vendors/tcpdf/images/ilogo.png");
	}
	
	function _move_upload_file(){
		if (!$_SESSION['role_menu']['Configuration']['logos']['model_x'])
		{
			$this->redirect_denied();
		}
		App::import("Core","Folder");
		//$path = APP.'tmp'.DS.'upload';				
		//if(new Folder($path,true,0777)){
		$file = "../vendors/tcpdf/images/ilogo.png";
		if (is_writable($file)) {
			
			$image_info = getimagesize($_FILES['ilogo']['tmp_name']);
			//var_dump($image_info);
			//$image_info[0]宽117 $image_info[1]高64
		//var_dump($_FILES['ilogo']);var_dump(strpos($_FILES['ilogo']['type'], 'image'));
		if (strpos($_FILES['ilogo']['type'], 'image') !== false)//($_FILES['ilogo']['type'] != 'image/png')
		{	
			exec("mv {$file} {$file}.old");
			if (!move_uploaded_file($_FILES ["ilogo"] ["tmp_name"], $file))
			{
				exec("mv {$file}.old {$file}");
				//$file = '';
			}
		}
			return $file;
		}else{
			echo '<script>alert("invoice logo folder can not write!");</script>';
			
		}
	}
	
}