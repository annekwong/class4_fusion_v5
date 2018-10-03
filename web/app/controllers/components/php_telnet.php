<?php
	
	class PhpTelnetComponent extends Object {
		function event_socket_create($host,$port,$password) {
			$fp = fsockopen ($host,$port, $errno, $errdesc );
			if(empty($fp))
			{
				throw new Exception("后台无法连接！");
			}
			if ($fp) {
				while ( ! feof ( $fp ) ) {
					$buffer = fgets ( $fp );
					usleep ( 100 ); //allow time for reponse
					if (trim ( $buffer ) == "Content-Type: auth/request") {
						fputs ( $fp, "auth $password\r\n\r\n" );
//						$this->event_socket_request($fp, "Auth $password");						
						break;
					}
					//echo $buffer."\n";
				}
				socket_set_blocking ( $fp, true );
				stream_set_timeout($fp, 120);	
				return $fp;
			} else {
				return false;
			}
		}
		
		function event_socket_request($fp, $cmd) {
			//pr($cmd);
			//$start_time = time();
			if ($fp) {
				//socket_set_blocking ( $fp, true );
				//stream_set_timeout($fp, 10);	
				fputs ( $fp, $cmd . "\r\n\r\n" );
//				usleep ( 1000 ); //allow time for reponse
				

				$response = "";
				//$i = 0;
				$contentlength = 0;
				
				while (!feof($fp)) {
					$buffer = fgets($fp);
					if (strlen($buffer) > 0)//(preg_match("/^<.*/", trim($buffer)))
					{
								//pr($buffer);
								$response .= htmlspecialchars($buffer);
					}
					
					if ($contentlength > 0) {
						
					}
					
					if ($contentlength == 0) { //if contentlenght is already don't process again
						if (strlen ( trim ( $buffer ) ) > 0) { //run only if buffer has content
								 $reg ='/Content-Length:(.*)?/';
								 if (preg_match_all($reg, $buffer, $len)) {
								 	 $contentlength =$len[1][0];
								 }
						}
					}
				
				
					
					//$response .= htmlspecialchars($buffer);
				//	pr($buffer);
			//		usleep ( 100 ); //allow time for reponse
					

					//optional because of script timeout //don't let while loop become endless
					//$now_time = time();
					//$time_p = $now_time - $start_time;
					$status = stream_get_meta_data( $fp ) ;
					if ($status['timed_out']) {//($time_p > 10) {//($i > 10000) {
						break;
					}
					if (strpos($buffer, chr(13)) !== false)//"\r\n"))
					{
						break;
					}
//					if ($contentlength > 0) { //is contentlength set
//						//stop reading if all content has been read.
//						if (strlen ( $buffer ) >= $contentlength) {
//							break;
//						}
//					}
					//$i ++;
				}
				//pr($response);
				return $response;
			} else {
				return "no handle";
			}
		}
		
		function getResult($cmd = null) {
			$host = Configure::read('freeswitch_settings.host');
			$port = Configure::read('freeswitch_settings.port');
			$pwd  = Configure::read('freeswitch_settings.pass');		
			$fp = $this->event_socket_create($host,$port,$pwd);
			$response = $this->event_socket_request($fp, $cmd);
			fclose($fp);
			return $response; 
		}
	}
	
?>