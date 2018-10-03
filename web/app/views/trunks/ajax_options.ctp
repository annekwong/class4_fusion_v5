<?php $arr=Array()?>
<?php foreach($this->data as $list):?>
<?php $arr[]=json_encode($list['Resource'])?>
<?php endforeach?>
[<?php echo join($arr,',')?>]