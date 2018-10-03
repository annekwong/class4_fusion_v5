<?php
	App::import('Vendor','sipcapture');
	class PhpSipcaptureInterfaceComponent extends Object {
		/*
		 * $cmd 为命令
		 * $block_num 为返回的数据格式中，有多少个 两个回车(0x0a) 
		 * 两个回车表示 一个 block 的结束
		 * eg:
Content-Type: api/response
Content-Length: 774

<monitor name="system">
	<stat name="system_max_cps" value="1000"/>
	<stat name="system_max_calls" value="10000"/>
	<stat name="system_peak_w_media_calls" value="0"/>
	<stat name="system_peak_wo_media_calls" value="1"/>
	<stat name="system_peak_cps" value="2"/>
	<stat name="last_24hr_peak_w_media_calls" value="0"/>
	<stat name="last_24hr_peak_wo_media_calls" value="1"/>
	<stat name="last_24hr_peak_cps" value="2"/>
	<stat name="last_7d_peak_w_media_calls" value="0"/>
	<stat name="last_7d_peak_wo_media_calls" value="1"/>
	<stat name="last_7d_peak_cps" value="2"/>
	<stat name="current_cps" value="0"/>
	<stat name="current_calls" value="0"/>
	<stat name="current_w_media_calls" value="0"/>
	<stat name="current_wo_media_calls" value="0"/>
</monitor>

有两个block,  774 后一个， 结束的地方一个， 故为 2
		 */		
		function getResult($cmd,$block_num=2) {
			$host = Configure::read('freeswitch_settings.host');
			$port = Configure::read('freeswitch_settings.port');
			$pwd  = Configure::read('freeswitch_settings.pass');	
			$fs = new SipCapture();
			$fs->timeout = 5;
			$fs->Connect($host,$port);
			if(!$fs->Connected()){
				throw new Exception('connect fail');
			}
			if($fs->Authenticate($pwd)){			
				$response = $fs->command($cmd,$block_num);
				$fs->Close();
				return $this->_parse_response($response);
			}else{
			    throw new Exception('auth fail');
			}			 
		}
		
		function _parse_response($response_text) {
			if($response_text === false || empty($response_text)){
				return array();
			}
			$returning = array();
			$response_text = trim((string)$response_text);
			$t = explode("\n\n",$response_text);
			$returning['header'] = $this->_parse_header($t[0]);
			$returning['content'] = $t[1];
			return $returning;
		}
		
		function _parse_header($header_text){
			$header = array();
			$header_text = trim((string)$header_text);
			$items = explode("\n",$header_text);
			foreach($items as $item){
				$item = trim((string)$item);
				$t = explode(":",$item);
				if(count($t)>=2){					
					$header[array_shift($t)] = trim(join(':',$t)); 
				}
			}
			return $header;
		}
	}	
?>