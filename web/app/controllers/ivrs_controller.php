<?php
class IvrsController extends AppController {
	var $name = 'ivrs';
	var $uses = array ();
	
	
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	
	
	public function view_list() {
		$this->loadModel ( 'Cdr' );
		$this->set ( 'ivrs', $this->Cdr->query ( "select * from ivr" ) );
	}
	
	public function download_ivr(){
		$this->loadModel('Cdr');
		$file_path = __ ( 'local_upload_path', true );
		$this->Cdr->download_file($file_path.$_REQUEST['filename']);
	}
	
	/**
	 * 上传IVR
	 */
	public function upload_ivr() {
		$this->loadModel ( 'Cdr' );
		require_once 'class_upload.php';
		$upload = new class_upload ();
		$upload->out_file_dir = __ ( 'local_upload_path', true );
		$upload->max_file_size = __ ( 'upload_size', true );
		$upload->make_script_safe = 1;
		$upload->allowed_file_ext = array ('wav' );
		$upload->upload_process ();
		if ($upload->error_no) {
			switch ($upload->error_no) {
				case 1 :
					// No upload
					$this->Cdr->create_json_array ( '', 101, __ ( 'nofiletoupload', true ) );
					break;
				case 2 :
				case 5 :
					// Invalid file ext
					$this->Cdr->create_json_array ( '', 101, __ ( 'onlywav', true ) );
					break;
				case 3 :
					// Too big...
					$this->Cdr->create_json_array ( '', 101, __ ( 'filetoobig', true ) );
					break;
				case 4 :
					// Cannot move uploaded file
					$this->Cdr->create_json_array ( '', 101, __ ( 'nouploaded', true ) );
					break;
				default :
					$file_name = $upload->saved_upload_name;
					$qs = $this->Cdr->query ( "update ivr set filename='$file_name' where type={$this->params['form']['type']}" );
					if (count ( $qs ) == 0) {
						$this->Cdr->create_json_array ( '', 201, __ ( 'uploaded', true ) );
					} else {
						$this->Cdr->create_json_array ( '', 101, __ ( 'haserror', true ) );
					}
			}
		}
		$this->Session->write ( 'm', Cdr::set_validator () );
		$this->redirect ( '/ivrs/view_list' );
	}
}
?>