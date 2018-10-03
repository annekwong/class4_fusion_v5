<?php 
class  SipcaptureSock{
	var $PORT=8500;
	var $CRLF="";
	var	$error;
	var $timeout;
	var $do_debug;

	function SipcaptureSock(){
		$this->conn=0;
		$this->error=null;
		$this->do_debug=0;
		
		}
	
	function connect($host,$port,$tval=10){
		$this->error=null;
		if($this->connected()){
			$this->error=
			  array("error"=>'Already Connected to a server');
			  return false;
			}

		if(empty($port)){
			$port=$this->PORT;
			$pto=getprotobyname("udp");
			$this->conn=@socket_create(AF_INET,SOCK_DGRAM,$pto);
			if(empty($this->conn)){
			$this->error="Failed to connect to server";
			

				
				
				}
			}

		}

	function  authenticate(){}
	function connected(){	}
	function close(){}
	function command(){}
	function _exit(){}



}
?>
