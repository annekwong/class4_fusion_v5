<?php
class AjaxvalidatesController extends AppController {
	var $name = 'Ajaxvalidates';
	var $uses=Array('Code');
	
	function ip4r($type=null){
		Configure::write('debug',0);
		$ips=$this->_get('ip');
		$ips=split(',',$ips);
		echo "{";
		foreach($ips as $key=>$ip){
                    if (empty($ip)) continue;

            if (strpos($ip, 'undefined') != false) {
                $list=@$this->Code->query("select '$ip'::ip4r");
                $re=!empty($list);
                if($type=='noDomain'){
                    $ip=split('/',$ip);
                    if(array_keys_value($ip,'1') && array_keys_value($ip,'1')!='undefined'){
                        $ip=$ip[0];
                        $list=@$this->Code->query("select '$ip'::ip4r");
                        if(empty($list)){
                            $re=true;
                        }
                    }else{
                        $re=true;
                    }
                }
                if($re){
                    echo "$key:true,";
                }else{
                    echo "$key:false,";
                }
            }
		}
		echo "a:true}";
		$this->layout='ajax';
	}
}
?>