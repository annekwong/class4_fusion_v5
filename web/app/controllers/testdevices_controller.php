<?php
class TestdevicesController extends AppController{
	var $name = 'Testdevices';
	var $uses = array('Productitem');
	var $helper = array('html','javascript');
	var $components =array('PhpTelnet');
	function  index()
	{
		$this->redirect('test_device');
	}
	//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_tools_testDevice');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
	
	public function test_device(){
		$this->pageTitle="Tools/Test Ingress Trunk";
		//查询所有的Resource
		$ingress = $this->Productitem->query("select resource_id,alias  as name from resource where ingress = true order by alias asc");
		$this->set('ingress',$ingress);
		$this->set('codecs',$this->get_codecs());
	}
	
	
	
	
	public function get_codecs(){
  	$r=$this->Productitem->query('select  name  from  codecs where 1=1 order by name asc');
	  		$size = count ( $r );
		$l = array ();
		for($i = 0; $i < $size; $i ++) {
			$key = $r [$i] [0] ['name'];
			$l [$key] = $r [$i] [0] ['name'];
		}
		return $l;
	
	}
	
	public function save_device(){
		Configure::write('debug' , 0);
//		$_REQUEST['n']='admin';
//		$_REQUEST['p']='123456';
//		$_REQUEST['r_id']='4';

		//var_dump($_POST);		
		$flag = true;
		
		$host = $_REQUEST['host'];
		if (empty($host))
		{
			$flag = false;
		}
		$ani = $_REQUEST['ani'];
		if (empty($ani))
		{
			$flag = false;
		}
		$dnis = $_REQUEST['dnis'];
		if (empty($dnis))
		{
			$flag = false;
		}
		
		$ivr_path = $_REQUEST['ivr_path'];
		$codecs = $_REQUEST['codecs'];#PCMA
		$itv = $_REQUEST['itv'];#20
		if (empty($itv))
		{
			$flag = false;
		}
		$duration = $_REQUEST['duration'];
		if (empty($duration))
		{
			$flag = false;
		}
		

		//$cmd = "ingress_trunk_simulate 1001|9911000|10|192.168.1.51|PCMA@20i@8000h||";
		$cmd = "ingress_trunk_simulate $ani|$dnis|$duration|$host|$codecs@$itv".''."i@8000h|$ivr_path|";
	//		$cmd = "ingress_trunk_simulate 1001|9911000|10|192.168.1.51|PCMA@20i@8000h||";		
		if ($flag)
		{
			$result = $this->send($cmd);//var_dump($result);
		}
/*		$result = "<Dnis>9911000</Dnis>
									<Result>Success</Result>
									<Release-Cause>Normal</Release-Cause>
									<Call-Time>2010-3-1 05:45:33</Call-Time>
									<Connect-Time>2010-3-1 05:45:34</Connect-Time>
									<Hangup-Time>2010-3-1 05:46:34</Hangup-Time>
									<PDD>200ms</PDD>";*/
		if (count($result) > 0) 
		{
			echo __('register_t',true);
			$this->set('result',$result);
			$this->render('save_device');
		} 
		else
		{
			echo __('register_f',true);
		} 
	}
	
	
	


	
	private function send($cmd=null){
		$result = $this->PhpTelnet->getResult("api ".$cmd);
		return $result;
	}
}