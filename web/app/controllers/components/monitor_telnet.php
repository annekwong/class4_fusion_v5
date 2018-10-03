<?php
	
	class MonitorTelnetComponent extends Object  {
		function event_socket_create($host,$port,$password) {
			$fp = fsockopen ($host,$port, $errno, $errdesc ) or $this->fail();
			socket_set_blocking ( $fp, false );
			if ($fp) {
				while ( ! feof ( $fp ) ) {
					$buffer = fgets ( $fp );
					usleep ( 100 ); //allow time for reponse
					if (trim ( $buffer ) == "Content-Type: auth/request") {
						fputs ( $fp, "auth $password\r\n\r\n" );
						break;
					}
					
					echo $buffer."\n";
				}
				return $fp;
			} else {
				return false;
			}
		}
		function fail(){
			pr("socket 发送失败");
		}
		function event_socket_request($fp, $cmd) {
			
			if ($fp) {
				fputs ( $fp, $cmd . "\r\n\r\n" );
				//usleep ( 100 ); //allow time for reponse
				

				$response = "";
				$i = 0;
				$contentlength = 0;
				while (!feof($fp)) {
					$buffer = fgets($fp);
					if ($contentlength > 0) {
						$response .= htmlspecialchars($buffer);
					}
					
					if ($contentlength == 0) { //if contentlenght is already don't process again
						if (strlen ( trim ( $buffer ) ) > 0) { //run only if buffer has content
								 $reg ='/Content-Length:(.*)?/';
								 if (preg_match_all($reg, $buffer, $len)) {
								 	 $contentlength =$len[1][0];
								 }
						}
				}
					
					//usleep ( 100 ); //allow time for reponse
					

					//optional because of script timeout //don't let while loop become endless
					if ($i > 10000) {
						break;
					}
					
					if ($contentlength > 0) { //is contentlength set
						//stop reading if all content has been read.
						if (strlen ( $buffer ) >= $contentlength) {
							break;
						}
					}
					$i ++;
				}
				
				return $response;
			} else {
				echo "no handle";
			}
		}
		
		function getResult($cmd = null,$options=Array()) {
			$host = Configure::read('freeswitch_settings.host');
			$port = Configure::read('freeswitch_settings.port');
			$pwd  = Configure::read('freeswitch_settings.pass');	
			$def_options=Array('host'=>$host,'port'=>$port,'pwd'=>$pwd);
			$options=array_merge($def_options,$options);
			$fp = @$this->event_socket_create($options['host'],$options['port'],$options['pwd']);
			echo $options['host'];
			if(!$fp){
				throw new Exception("发送失败");
			}
			$response = $this->event_socket_request($fp, $cmd);
			fclose($fp);
			return $response; 
		}
	}
	
?>