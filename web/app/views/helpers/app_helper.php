<?php

App::import('Core', 'Helper');
class AppHelper extends Helper {
	function _get_select_options($lists,$model,$key,$value,$options=Array()){
		//$options=Array("0"=>"");
		$options=Array();
		foreach($lists as $list)
		{
			$options[$list[$model][$key]]=$list[$model][$value];
		}
		return $options;
	}
	function _get($key){
		return $this->getParams('url.'.$key);
	}
	function getParams($keys=''){
		if(empty($keys)){
			return $this->params;
		}
		return array_keys_value($this->params,$keys);
	}
	function getPass($key){
		return $this->getParams('pass.'.$key);
	}
	function getAction(){
		return $this->getParams('action');
	}
	function actionIs($key,$type=0){
		$options_type=array(0=>'stripos','i'=>'stripos','n'=>'strpos','strpos'=>'strpos');
		return isNFalse($options_type[$type]($this->getAction(),$key));
	}
	function isIngress($type=null){
		if($type){
			return ($type=='ingress');
		}
		return $this->actionIs('Ingress');
	}
	function isEgress(){
		if($type){
			return ($type='egress');
		}
		return $this->actionIs('Egress');
	}
	function getProductName(){
		return Configure::read('project_name');
	}

    function show_release_cause($id = false)
    {

        $release_cause_arr = [
            '' => 'All',
            0 => "Invalid Argument",
            1 => "System CAP Limit Exceeded",
            2 => "System CPS Limit Exceeded",
            3 => "Unauthorized IP Address",
            4 => "Ingress Prefix Does Not Match",
            5 => "No Product Found",
            6 => "Trunk CAP Limit Exceeded",
            7 => "Trunk CPS Limit Exceeded",
            8 => "IP CAP Limit Exceeded",
            9 => "IP CPS Limit Exceeded",
            10 => "Invalid Codec Negotiation",
            11 => "Block due to LRN",
            12 => "Ingress Rate Not Found",
            13 => "Egress Trunk Not Found",
            14 => "From egress response 404",
            15 => "From egress response 486",
            16 => "From egress response 487",
            17 => "From egress response 200",
            18 => "All egress not available",
            19 => "Normal",
            20 => "Ingress Resource disabled",
            21 => "Balance Use Up",
            22 => "No Routing Plan Route",
            23 => "No Routing Plan Prefix",
            24 => "Ingress Rate No configure",
            25 => "Invalid Codec Negotiation",
            26 => "No Codec Found",
            27 => "All egress no confirmed",
            28 => "LRN response no exist DNIS",
            29 => "Carrier CAP Limit Exceeded",
            30 => "Carrier CPS Limit Exceeded",
            31 => "Host Alert Reject",
            32 => "Resource Alert Reject",
            33 => "Resource Reject H323",
            34 => "180 Negotiation SDP Failed",
            35 => "183 Negotiation SDP Failed",
            36 => "200 Negotiation SDP Failed",
            37 => "LRN Block Higher Rate",
            38 => "Ingress Block ANI",
            39 => "Ingress Block DNIS",
            40 => "Ingress Block ALL",
            41 => "Global Block ANI",
            42 => "Global Block DNIS",
            43 => "Global Block ALL",
            44 => "T38 Reject",
            45 => "Partition CAP Limit Exceeded",
            46 => "Partition CPS Limit Exceeded",
            47 => "LRN Loop Detected",
            48 => "Reject partition",
            49 => "Resource Loop Detected",
            50 => "Code CAP Limit Exceeded",
            51 => "Code CPS Limit Exceeded",
            52 => "Switch Profile CAP Limit Exceeded",
            53 => "Switch Profile CPS Limit Exceeded",
            54 => "Allowed Send To IP",
            55 => "LRN Dipping Failed",
            56 => "System call limit",
            57 => "Egress Block ANI",
            58 => "Egress Block DNIS",
            59 => "Egress Block ALL",
            60 => "Resource Block ANI",
            61 => "Resource Block DNIS",
            62 => "Resource Block ALL",
            63 => "Block 404 Number",
            64 => "No Profitable Egress",
            65 => "Due to Egress CPS or Call Limit",
            66 => "Trunk ANI CPS Limit",
            67 => "Trunk ANI Call Limit",
            68 => "Trunk DNIS CPS Limit",
            69 => "Trunk DNIS Call Limit",
        ];
        
        
        $result = $release_cause_arr;
        if ($id !== false)
            $result = isset($release_cause_arr[$id]) ? $release_cause_arr[$id] : $id;

        return $result;
    }

    public function getUrl()
    {
        $port = $_SERVER["SERVER_PORT"] == 80 ? '' : ':' . $_SERVER["SERVER_PORT"];

        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
        {

            $url = 'https://' . $_SERVER['SERVER_NAME']. $port . $this->webroot;
        }
        else
        {
            $url = 'http://' . $_SERVER['SERVER_NAME'] . $port . $this->webroot;
        }
        return $url;
    }
}
?>