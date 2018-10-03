<?php
App::import("Model", "Cdr");
class AppSipCaptureHelper extends AppHelper {	
	function format_server_options($sersers){
		return $sersers;
	}
        
        function find_file($server_ip,  $server_port, $file_name)
        {
            $model = new Cdr ();
            //$server_ip='192.168.1.115';
            //$dir = Configure::read('sip_capture.pcap_dir');
            //$arr = explode('.', $server_ip);
        // $ip = join('', $arr);
            //$file_name='2011-02-23-0953111804289383.pcap';
            list($y, $m, $d) = explode("-", basename($file_name));
            //$file_dir=$dir.DS.$ip.DS.$d.DS.$file_name;
            //$file_dir=$dir."sip_capture02".DS.$file_name;
            //$file_dir = $dir.DS.$d.DS.basename($file_name);
            //echo $file_dir. "<br />";
            //$filename = $dir  . DS . $ip . DS . $d.  DS . basename($file_name);
            $sql = "select sip_capture_path from switch_profile where sip_ip = '$server_ip' and sip_port = $server_port limit 1";
            $result = $model->query($sql);
            $path = $result[0][0]['sip_capture_path'];
            $filename = $path . DS . $d . DS . basename($file_name);
            $is_debug = Configure::read('sip_capture.is_debug');
            if ($is_debug)
            {
                printf("%s <br />\n", $filename);
            }
            return $filename;
        }
}
?>