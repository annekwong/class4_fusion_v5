<?php if (empty($this->data)) {?>
<?php echo $this->element('listEmpty')?>
<?php } else {?>
<div>
  <table class="list">
    <col style="width: 5%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">

    <thead>
      <tr>
        <!--<td><?php echo $appCommon->show_order('client_id',__('ID',true))?></td>-->
        <td><?php echo $appCommon->show_order('name',__('Name',true));?></td>
        <td><?php echo $appCommon->show_order('balance',__('Mutual Balance',true))?></td>
        <td><?php echo $appCommon->show_order('balance',__('Available Balance',true));?></td>
        <td><?php echo $appCommon->show_order('mode',__('mode',true));?></td>
        <td><?php echo $appCommon->show_order('ingress_count',__('egress',true));?></td>
        <td><?php echo $appCommon->show_order('egress_count', __('ingress',true));?></td>
      </tr>
    </thead>
    <?php foreach($this->data as $list) {?>
    <tr>
      <!--<td  align="center"><a> <?php echo $list['Client']['client_id']?> </a></td>-->
      <td  align="center"><?php echo $list['Client']['name']?></td>
      <td><?php $my_pi = number_format(array_keys_value($list,'Payment.balance_1') - array_keys_value($list,'Payment.balance_2') + array_keys_value($list,'0.offset') - array_keys_value($list,'0.credit'), 3) ;echo  $my_pi < 0 ? '('.str_replace('-','',$my_pi).')' : $my_pi;?></td>
      <td ><?php $my_pi = number_format($list['ClientBalance']['balance'], 3);  echo  $my_pi;?></td>
      <td align="center"><?php 
		   			if(array_keys_value($list,'Client.mode')==1){echo __('Prepaid');}
		   			elseif(array_keys_value($list,'Client.mode')==2){echo __('postpaid');}
		   			else{echo '';}
		   		?></td>
      <td><?php echo array_keys_value($list,'Client.egress_count')?></td>
      <td><?php echo array_keys_value($list,'Client.ingress_count')?></td>
    </tr>
    <?php }?>
  </table>
</div>
<?php }?>