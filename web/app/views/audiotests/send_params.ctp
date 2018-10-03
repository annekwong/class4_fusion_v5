<?php
//class Message
//{  
//    private  $server_ip;  
//    private  $server_port;  
//    private  $sock; 
//    private  $protocol = 0; 
//           
//    function  __construct($server_ip="192.168.1.51", $server_port=5060, $protocolName = "udp")  
//		  {  
//         $this->server_ip = $server_ip;  
//         $this->server_port = $server_port;  
//         $this->protocol = getprotobyname($protocolName); 
//         $this->sock=@socket_create(AF_INET, SOCK_DGRAM, $this->protocol); 
//         socket_set_option($this->sock,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>5, "usec"=>0 ) );
//         if(!$this->sock)  
//						{  
//              //echo "socket create failure";  
//         				}  
//         //else echo "start\n";         
//     		}  
//       
//     function sendMessage($buf)  
//     		{  
//        if(!@socket_sendto($this -> sock,$buf,strlen($buf),0,$this -> server_ip, $this -> server_port))  
//        			{  
//             echo "send error\n";  
//             socket_close($this -> sock);  
//             exit();  
//       				}  
//        $msg = "";  
//        if(!@socket_recvfrom($this -> sock, $msg, 256, 0, $this->server_ip, $this->server_port))  
// 			        {  
//             echo "recvieve error!";  
//             socket_close($this ->sock);  
//             exit();  
//    			     }         
//        return $msg;   
//   		   }  
//    function closeMessage()  
//    		{  
//         socket_close($this -> sock);   
//  			}  
//}  
//
//$m = new Message("192.168.1.51",5060); 
//$message = "api c4_audio_test 1001 1000 192.168.1.51 5060 /home/piao-PCM-8000-16b-1hour.wav PCMU 8000 30\r\n"; 
//$msg = $m -> sendMessage($message);  
//$msg = "<Result>
//<Country>yht</Country>
//<Code-Name>baohq</Code-Name>
//<DNIS>9911000</DNIS>
//<Result>success</Result>
//<Release-Cause>NORMAL_CLEARING</Release-Cause>
//<Call-Time>2011-03-03 16:58:47</Call-Time>
//<Connect-Time>2011-03-03 16:58:50</Connect-Time>
//<Hangup-Time>2011-03-03 16:58:55</Hangup-Time>
//<PDD>370ms</PDD>
//<Ring-Back-Tone-Byes></Ring-Back-Tone-Byes>
//<Media-Bytes>0</Media-Bytes>
//<Duration>8</Duration>
//<Packet-Loss></Packet-Loss>
//<Codec>PCMU</Codec>
//</Result>
//<QOS-Report>
//<Average-PDD>370</Average-PDD>
//<Average-ASR>1.000000</Average-ASR>
//<Average-ACD>8</Average-ACD>
//</QOS-Report>";
//echo $msg, "<br />";
//$m -> closeMessage(); 
$msg = str_replace('\n', '', $msg);
//$msg = str_replace(' ', '', $msg);
$msg = html_entity_decode($msg);
$msg = preg_replace("/[^<]*/", '', trim($msg), 1);
$xmlStr = "<Document>";
$xmlStr .= $msg;
$xmlStr .= "</Document>";
//var_dump($xmlStr);
$xml = simplexml_load_string($xmlStr);
//var_dump($xml);
$out = "";
foreach ($xml as $k0=>$v0)
{
	$out .= "<div style=\"width: 100%;margin: 0px \">
    <fieldset><legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> $k0</legend>";	
	$out .= "<table class=\"list list-form\">";
	$out .= "</thead><tr>";
	foreach($v0 as $k=>$v)
	{
	    $out .= "<td>$k</td>";
	 }
	$out .= "</tr></thead>";
	$out .= "<tbody><tr style=\"background-color: #EDF0F5;\">";
	foreach($v0 as $k=>$v)
	{
	    $out .= "<td>$v</td>";
	 }
	$out .= "</tr></tbody>";
	$out .= "</table>";
	$out .= "</fieldset></div>";
	
}

//$out .= "<div style=\"width: 100%;margin: 0px \">
//    <fieldset><legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'></legend>";	
//	$out .= "<table class=\"list list-form\">";
//	$out .= "</thead><tr>";
//	foreach($xml as $k=>$v)
//	{
//	    $out .= "<td>$k</td>";
//	 }
//	$out .= "</tr></thead>";
//	$out .= "<tbody><tr style=\"background-color: #EDF0F5;\">";
//	foreach($xml as $k=>$v)
//	{
//	    $out .= "<td>$v</td>";
//	 }
//	$out .= "</tr></tbody>";
//	$out .= "</table>";
//	$out .= "</fieldset></div>";
echo $out;
//foreach($xml as $k=>$v)
//{
//	echo $k,':',$v,'<br />';
//	//var_dump($v);
//}
?>