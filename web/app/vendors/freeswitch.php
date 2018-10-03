<?php

class FreeSwitch {
	var $PORT = 8021; # the default PORT
	var $CRLF = "\r\n\r\n";  # CRLF pair

	var $conn;      # the socket to the server
	var $error;          # error if any on the last call
	var $timeout;
	var $do_debug;       # the level of debug to perform

	/*
	 *
	 * Initialize the class so that the data is in a known state.
	 */
	function FreeSwitch() {
		$this->conn = 0;
		$this->error = null;
		$this->do_debug = 0;
	}

	/************************************************************
	 *                    CONNECTION FUNCTIONS                  *
	 ***********************************************************/

	/*
	 * Connect($host, $port=0, $tval=30)
	 *
	 * Connect to the server specified on the port specified.
	 * If the port is not specified use the default SMTP_PORT.
	 * If tval is specified then a connection will try and be
	 * established with the server for that number of seconds.
	 * If tval is not specified the default is 30 seconds to
	 * try on the connection.
	 */
	function Connect($host,$port=0,$tval=10) {
		$this->error = null;
		if($this->connected()) {
			$this->error =
				array("error" => "Already connected to a server");
			return false;
		}

		if(empty($port)) {
			$port = $this->PORT;
		}

		$this->conn = fsockopen($host,    # the host of the server
				$port,    # the port to use
				$errno,   # error number if any
				$errstr,  # error message if any
				$tval);   # give up after ? secs
# verify we connected properly
			if(empty($this->conn)) {
				$this->error = array("error" => "Failed to connect to server",
						"errno" => $errno,
						"errstr" => $errstr);
				if($this->do_debug >= 1) {
					echo "FreeSwitch -> ERROR: " . $this->error["error"] .
						": $errstr ($errno)" . $this->CRLF;
				}
				return false;
			}

# sometimes the FreeSwitch server takes a little longer to respond
# so we will give it a longer timeout for the first read
		// Windows still does not have support for this timeout function
#		socket_set_blocking ( $this->conn, false );
		$timeout = empty($this->timeout) ? 15 : $this->timeout;
		if(substr(PHP_OS, 0, 3) != "WIN")
			socket_set_timeout($this->conn, $timeout, 0);

# get any announcement stuff
		$announce = $this->get_lines();

		if($this->do_debug >= 2) {
			echo "FreeSwitch -> FROM SERVER:" . $this->CRLF . $announce;
		}

		return true;
	}

	/*
	 * Authenticate()
	 * Performs FreeSwitch authentication.  Must be run after running the
	 */
	function Authenticate($password) {
		$this->error = null; # so there is no confusion
			if(!$this->connected()) {
				$this->error = array(
						"error" => "Called Authenticate without being connected");
				return false;
			}
		fputs($this->conn,"AUTH ". $password . $this->CRLF);

		$rply = $this->get_lines();
		if(strpos($rply,"+OK"))
			return true;
		else
			return false;
	}

	/*
	 * Connected()
	 *
	 * Returns true if connected to a server otherwise false
	 */
	function Connected() {
		if(!empty($this->conn)) {
			$sock_status = socket_get_status($this->conn);

			#如果流到达文件末尾时为 TRUE
			if($sock_status["eof"]) {
# hmm this is an odd situation... the socket is
# valid but we aren't connected anymore
				if($this->do_debug >= 1) {
					echo "FreeSwitch -> NOTICE:" . $this->CRLF .
						"EOF caught while checking if connected";
				}
				$this->Close();
				return false;
			}
			return true; # everything looks good
		}
		return false;
	}

	/*
	 * Close()
	 *
	 * Closes the socket and cleans up the state of the class.
	 * It is not considered good to use this function without
	 * first trying to use QUIT.
	 */
	function Close() {
		$this->error = null; # so there is no confusion
			if(!empty($this->conn)) {
				$this->_exit();
# close the connection and cleanup
				fclose($this->conn);
				$this->conn = 0;
			}
	}

	/**************************************************************
	 *                        FreeSwitch COMMANDS                       *
	 *************************************************************/
	function command($cmd,$block_num=1) {
		$this->error = null; # so no confusion is caused
			if(!$this->connected()) {
				$this->error = array(
						"error" => "Called command() without being connected");
				return false;
			}
		fputs($this->conn, $cmd . $this->CRLF);
		return $this->get_lines($block_num);
	}

	function _exit() {
		$this->error = null; # so there is no confusion
			if(!$this->connected()) {
				$this->error = array(
						"error" => "Called Quit() without being connected");
				return false;
			}
		fputs($this->conn,"api exit" . $this->CRLF);
	}

	/******************************************************************
	 *                       INTERNAL FUNCTIONS                       *
	 ******************************************************************/
	/*
	 * get_lines()
	 *
	 * __internal_use_only__: read in as many lines as possible
	 * either before eof or socket timeout occurs on the operation.
	 * With FreeSwitch we can tell if we have more lines to read if the
	 * 4th character is '-' symbol. If it is a space then we don't
	 * need to read anything else.
	 */
	function get_lines($block_num = 1) {
		$data = "";
		$i = 0;
		while($str = fgets($this->conn,515)) {
			if($this->do_debug >= 4) {
				echo "FreeSwitch -> get_lines(): \$data was \"$data\"" .
												   $this->CRLF;
				echo "FreeSwitch -> get_lines(): \$str is \"$str\"" .
												   $this->CRLF;
			}
			$data .= $str;
			if($this->do_debug >= 4) {
				echo "FreeSwitch -> get_lines(): \$data is \"$data\"" . $this->CRLF;
			}
# if the 4th character is a space then we are done reading
# so just break the loop
			if(substr_count($data,"\n\n") >= $block_num) {
				break;
			}
		}
		return $data;
	}

}

/*
   $f = new FreeSwitch();
   $f->Connect('192.168.1.116','8021');
   if($f->Authenticate("ClueCon"))
   echo "SUCCESS \n";
   else
   echo "Fail";
   echo "============================================";
   $d =  $f->command("api get_sys_limit",2);
   echo $d;
   echo bin2hex($d);
   $t = _parse_response($d);
   var_dump($t);
   echo "============================================";
   $d = $f->command("api simulate_class4 192.168.1.116:8686 sipp 240",2);
   echo $d;
   echo bin2hex($d);
   $t = _parse_response($d);
   var_dump($t);

function _parse_response($response_text) {
	if($response_text === false || empty($response_text)){
		return array();
	}
	$returning = array();
	$response_text = trim((string)$response_text);
	$t = explode("\n\n",$response_text);
	$returning['header'] = _parse_header($t[0]);
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
*/
?>
