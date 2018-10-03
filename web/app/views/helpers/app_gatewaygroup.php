<?php 
App::import('Model','Resource');
class AppGatewaygroupHelper extends AppHelper
{
	var $options_proto = Array('' => '', Resource::RESOURCE_PROTO_ALL => 'All', Resource::RESOURCE_PROTO_SIP => 'SIP', Resource::RESOURCE_PROTO_PROTO => 'Proto');

	function proto($list)
	{
		if (isset($this->options_proto[$list[0]['proto']])) {
			return $this->options_proto[$list[0]['proto']];
		} else {
			return '---';
		}
	}


	function echo_resource_hidden($resource_id, $gress)
	{
		return <<<EOD
<input type="hidden" id="resource_id" value="{$resource_id}" name="resource_id" class="input in-hidden">
<input type="hidden" id="gress" value="{$gress}" name="gress" class="input in-hidden">
EOD;

	}

	public function getTextStatus($status)
	{
		$return = 0;

		switch ($status) {
			case 1:
				$return = "Untested";
				break;
			case 2:
				$return = "Tested";
				break;
			case 3:
				$return = "Failed";
				break;
		}

		return $return;
	}
}
