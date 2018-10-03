<?php 
class XformHelper extends AppHelper {
	var $helpers = array('Form');	
	function search($id,$options=Array()){
            //print_r($options);
                if($id == 'Carriers1') {
                    $arr = array(
                        "options" => array(
                            "0" => ""
                        )
                    );
                    $options['options'] = $arr['options'] + $options['options'];
                }
                
		$options=array_merge(Array('value'=>array_keys_value($this->params,"search.$id"),'selected'=>array_keys_value($this->params,"search.$id"),'div'=>false,'label'=>false,'name'=>$id),$options);
		//array_unshift($options['options'], "");
                
                return $this->Form->input($id,$options);
	}
	function input($id,$options=Array()){
		$options=array_merge(Array('div'=>false,'label'=>false),$options);
		return $this->Form->input($id,$options);
	}
}
?>
