<?php
class AudiotestsController extends AppController{
	var $name = 'Audiotests';
	var $uses = array('Productitem');
	var $components =array('PhpTelnet');
	
	function  index()
	{
		$this->redirect('audio_test');
	}
	//读取该模块的执行和修改权限
	public function beforeFilter(){
		parent::beforeFilter();
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
	
	public function audio_test(){
		
		$this->pageTitle="Egress/Trunk Simulationa";
		//查询所有的Resource
		$ingress = $this->Productitem->query("select resource_id,alias as name from resource where egress = true order by alias asc");
		$host=$this->Productitem->query("select  resource_id ,ip,port  from   resource_ip");
		$this->set('ingress',$ingress);
		$this->set('host',$host);
		$this->set('codecs',$this->get_codecs());
	
	}
	
	
	public function test(){
	//	$str="Speex(8000, 16000 or 32000)/dynamic";
//	$str="telephone-event/dynamic";
	$str="66666666666";
if(!preg_match('/\d+/',$str))
{
echo 'no nmber';
}
else
{
echo 'is_number';

}
	
	}
	
	public function get_number($str){
		$arr=array();
		if(stripos($str,'(')){
		list($a,$b)=explode('(', $str);
		$arr[0]=$a;
		list($d,$c)=explode(')', $b);
		if(stripos($d,',')){
		list($a,$b)=explode(',',$d);
		$arr[1]=$a;
		return $arr;
		}
		$arr[1]=$d;
		return $arr;
	}else{
		if(stripos($str,'/'))
		{
			list($a,$b)=explode('/', $str);
			$arr[0]=$a;
			return   $arr ;
		}
	return null;
	}

		
	
	}
	
	public function  send_params0() {
		return true;
	}
	
	
	
	public function test_cmd(){
		//$cmd = "egress_trunk_simulate yht|baohq|192.168.1.52|5060|1001|PCMA@20i@8000h,|9912000,||5||";
		$cmd = "egress_trunk_simulate yht|baohq|192.168.1.115|5065|sipp|PCMU@20i@8000h,|8888,||5||";
		//$cmd = "egress_trunk_simulate yht|baohq|192.168.1.155|5060|1001|PCMU@20i@8000h,|9911000,||5||";
		$result = $this->send($cmd);			pr($result);
	}
	
	/*
	 * 向后台发送命令  返回模拟结果
	 * 
	 * 
	 * 11111111
	 */
	public function  send_params() {
	//	Configure::write('debug',0);
		if (1)	//(!empty($this->params['form']))
		 {
			$f = $_POST;//$this->params['form'];
			$host = explode(":", $f['host']);//Ingress IP
			$ani = $f['orig_ani'];//Origination ANI
			$dnis = $f['orig_dnis'];//Origination DNIS
			$r_id = $f['res'];
			$ivr_path = $f['ivr_path'];
			$codecs = $f['codecs'];
			$interval = $f['interval'];
		 if (!preg_match('/\d+/',$interval)) 
			{
					$interval=60;
			}
			if (intval($interval) <= 0)
			{
				 $interval=60;
			}
				if (!isset($host[1]))
				{
					$ports = $this->Productitem->query("select port from resource_ip where resource_id = '$r_id' and ip = '$host[0]'"); 
					if (!empty($ports))
					{
						$port = $ports[0][0]['port'];	
					}
					else 
					{
						$port = 5060;
					}				
				}
				else
				{
					$port = $host[1];
				}
		$has_error = false;
			if (empty($host[0])) {
				$has_error = true;
				$this->Productitem->create_json_array("#ingress_ip",101,__('no_ingress_found',true));
			} else {
				if (!preg_match('/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/',$host[0])) {
					$has_error = true;
					$this->Productitem->create_json_array("#ingress_ip",101,__('ingress_ip_format',true));
				}
			}
			
			if (empty($dnis)) {
				$has_error = true;
				$this->Productitem->create_json_array("#orig_dnis",101,__('dnis_null',true));
			}
		 if (empty($ani)) {
				$has_error = true;
				$this->Productitem->create_json_array("#orig_dnis",101,__('dnis_null',true));
			}
			
			if ($has_error == true) {
				$this->Session->write('backform',$f);
				//$this->Session->write('m',Product::set_validator());
				$this->redirect('/audiotests/audio_test');
			}
			
			$arr=$this->get_number($codecs);
			if(isset($arr[0]))
			{
				$codecs_prefix=$arr[0];
			}
			
			if(isset($arr[1]))
			{
			$codecs_n=$arr[1];
			}
			$codecs_str = $codecs_prefix.'@20i@8000h';//implode(",", $arr);
			//$cmd = "egress_trunk_simulate yht|baohq|192.168.1.52|5060|1001|PCMA@20i@8000h,PCMU@20i@8000h|9911000,9912000||5||";
			//$cmd =	"egress_trunk_simulate yht|baohq|192.168.1.52|5060|1001|PCMA@20i@8000h,PCMA@20i@8000h|9911000,9911000||5||";
			$cmd = "egress_trunk_simulate yht|baohq|$host[0]|$port|$ani|$codecs_str,|$dnis,||$interval||";
			//$cmd = 'egress_trunk_simulate yht|baohq|192.168.1.52|5060|1001|PCMA@20i@8000h|9911000||5||';
			//pr($cmd);
//			$result = "<Country>111</Country>
//			<Code-Name>abc</Code-Name>
//			<DNIS>9911000</DNIS>
//			<Result>success</Result>
//			<Release-Cause>Normal</Release-Cause>
//			<Call-Time>2010-3-1 05:45:33</Call-Time>
//			<Connect-Time>2010-3-1 05:45:34</Connect-Time>
//			<Hangup-Time>2010-3-1 05:47:34</Hangup-Time>
//			<PDD>200ms</PDD>
//			<Ring-Back-Tone-Byes></Ring-Back-Tone-Byes>
//			<Media-Bytes></Media-Bytes>
//			<Duration>16</Duration>
//			<Packet-Loss></Packet-Loss>
//			<Codec></Codec>";
			$result = $this->send($cmd);	//var_dump($result);
			$this->set('msg',$result);
			$this->render('send_params');
			//
//			$xmlStr = "<Document>";
//			$xmlStr .= $result;

//			$xmlStr .= "</Document>";
//			$xml = simplexml_load_string($xmlStr);
//			$out = "<table class=\"list list-form\">";
//			$out .= "</thead><tr>";
//			foreach($xml as $k=>$v)
//			{
//			    $out .= "<td>$k</td>";
//			 }
//			$out .= "</tr></thead>";
//			$out .= "<tbody><tr style=\"background-color: #EDF0F5;\">";
//			foreach($xml as $k=>$v)
//			{
//			    $out .= "<td>$v</td>";
//			 }
//			$out .= "</tr></tbody>";
//			$out .= "</table>";
//			$this->Session->write('audio_res', $out);
//			$this->redirect('/audiotests/audio_test');
			//echo $out;
			//$result = str_replace("\n","",$result);//替换\n
			
//			if(stripos($result,'OK'))
//			{
//					
//			$this->Session->write('audio_res','Call Success');
//			$this->Session->write('backform',$f);
//			$this->redirect('/audiotests/audio_test');
//			
//			}
			
//			if(stripos($result,'NORMAL_TEMPORARY_FAILURE'))
//			{
//			$this->Session->write('audio_res','Call  failed');
//			
//			}
//			
//			else
//			{
//				$this->Session->write('audio_res','Server Exception');
//			
//			}
		
		}
	}
	
/*
	 * 发送命令到Socket 并返回执行命令结果
	 */
	private function send($cmd=null){
		$result = $this->PhpTelnet->getResult("api ".$cmd);
		return $result;
	}
}