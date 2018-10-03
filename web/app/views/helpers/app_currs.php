<?php 
class AppCurrsHelper extends AppHelper {	
	function last_modify($list){
		if (!empty($list['Curr']['last_modify'])){
		 return date('Y-m-d H:i:s',strtotime($list['Curr']['last_modify'])+6*60*60);
		}
		return "";
	}
	function rates($list){
		return "<a href='{$this->webroot}rates/currency/{$list['Curr']['currency_id']}/currs/currency_list'  target='_blank'   style='width:100%;'> 
			<img  src='{$this->webroot}images/bOrigTariffs.gif'/>
			<span>{$list['Curr']['rates']}</span>
			</a>
		";
	}
	function active($list,$config_flg = false){
		$disable=__('Inactive',true);
		$active=__('Active',true);
                $is_config = (int) $config_flg;
		if ($list['Curr']['active'] == true) {
			return "<a class='active'  title='{$disable}' href='{$this->webroot}currs/disabled/{$list['Curr']['currency_id']}/$is_config'>
			    		<i class=\"icon-check\"></i>
			    	</a>";
		} else {
			return "<a class='disabled' title='{$active}'   href='{$this->webroot}currs/active/{$list['Curr']['currency_id']}/$is_config'>
			    		<i class=\"icon-unchecked\"></i>
			    	</a>";
		}
	}
}
?>
