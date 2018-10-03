<?php  echo $form->create("Dynamicroute")?>
<table style="width:100%">
    <tbody class="add">
    <tr>
        <td></td>
        <td>
            <img title="findegress" src="<?php echo $this->webroot?>images/+.gif" class="jsp_resourceNew_style_1" onclick="pull('<?php echo $this->webroot?>',this,-1)" id="image-1"/>
        </td>
        <td>
            <?php echo $form->input('name',Array('div'=>false,'label'=>false,'class'=>'input in-input input-small','value'=>array_keys_value($post,'Dynamicroute.name')))?>

        </td>
        <td>
            <?php $arr1=array('4'=>__('routerule1',true),'5'=>__('routerule2',true),'6'=>__('routerule3',true));?>

            <?php echo $form->input('routing_rule',Array('div'=>false,'label'=>false,'type'=>'select','options'=>$arr1,'class'=>'select in-select route_rule','selected'=>array_keys_value($post,'Dynamicroute.routing_rule')))?>
        </td>
        <td>
            <?php echo $form->input('Dynamicroute.time_profile_id',	array('options'=>$user,'label'=>false ,	'empty'=>'','div'=>false,'type'=>'select','class'=>'select in-select','selected'=>array_keys_value($post,'Dynamicroute.time_profile_id')));?>
            <!--                <input name="hide_time_profile_id" type="hidden" class="input in-input input-small" value="" maxlength="100" id="hide_time_profile_id">-->
        </td>
        <td>
        </td>
        <td>
            <?php $arr2=array('1'=>__('15 Minutes',true),'2'=>__('30 Minutes',true),'3'=>__('1 Hour',true),'4'=>__('1 Day',true));?>
            <?php echo $form->input('lcr_flag',Array('div'=>false,'label'=>false,'type'=>'select','options'=>$arr2,
                'class'=>'select in-select lcr_flag','selected'=>array_keys_value($post,'Dynamicroute.lcr_flag'),
                'disabled' => (array_keys_value($post,'Dynamicroute.routing_rule') == 6)
            ))?>
        </td>
        <td></td>
        <td>
        </td>
        <td>
            <a id="save" href="#" title="Save">
                <i class="icon-save"></i>
            </a>
            <a id="delete" href="#" title="Delete">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>

    <tr style="height: auto;" class="row-2">
        <td colspan="10" class="last">
            <div style="padding: 5px;display:none" class="jsp_resourceNew_style_2" id="ipInfo-1">
                <table id="tblwa" class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <tr>
                        <td colspan=7 style="text-align:left">
                            <a class="btn btn-primary btn-icon glyphicons circle_plus" id="additem" href="javascript:void(0)">
                                <i></i> <?php echo __('Add Egress',true);?>
                            </a>
                            <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add_all" href="javascript:void(0)">
                                <i></i> <?php echo __('Add All Egress',true);?>
                            </a>
                            <a class="btn btn-primary btn-icon glyphicons circle_plus" id="delete_all" href="javascript:void(0)">
                                <i></i> <?php echo __('Remove All',true);?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th style="width:13%"><?php echo __('ID',true);?></th>
                        <th style="width:13%"><?php echo __('Carriers',true);?></th>
                        <th style="width:13%"><?php echo __('Trunk Name',true);?></th>
                        <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?><th style="width:13%"><?php echo __('Active',true);?></th><?php }?>
                    </tr>
                    <?php
                    if(isset($sel) && count($sel) > 0 ) {
                        foreach($sel as $val) {
                            ?>
                            <tr id="cloned">
                                <td></td>
                                <td style="width:27%"><?php echo $xform->search('Carriers1',Array('options'=>$appProduct->_get_select_options($ClientList,'Client','client_id','name'),'id'=>'Carriers1','value'=>$val[0]['client_id'],'style'=>'width:280px', 'class' => 'client_options'))?></td>
                                <td style="width:27%"><?php echo $xform->search('engress_res_id[]',Array('id'=>'egressSelect','style'=>'width:280px','options'=>$client_resources[$val[0]['client_id']],'empty'=>'','value'=>$val[0]['resource_id']))?></td>
                                <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
                                    <td><a href="#" title="Cancel" onclick="if(jQuery('#tblwa tr').length > 3) {jQuery(this).parent().parent().remove();} return false;"><i class="icon-remove"></i></a></td>
                                <?php }?>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr id="cloned">
                            <td></td>
                            <td style="width:27%"><?php echo $xform->search('Carriers1',Array('options'=>$appProduct->_get_select_options($ClientList,'Client','client_id','name'),'value'=>array_keys_value($this->data,'client1.client_id'),'style'=>'width:280px', 'class' => 'client_options'))?></td>
                            <td style="width:27%"><?php echo $xform->search('engress_res_id[]',Array('id'=>'egressSelect','style'=>'width:280px','options'=>array(),'empty'=>''))?></td>
                            <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
                                <td><a href="#" title="Cancel" onclick="if(jQuery('#tblwa tr').length > 3) {jQuery(this).parent().parent().remove();} return false;"><i class="icon-remove"></i></a></td>
                            <?php }?>
                        </tr>
                    <?php
                    }
                    ?>
                </table>

            </div>
        </td>
    </tr>
    <!--        <input name="hide_aaaa" type="hidden" value="" maxlength="100" id="hide_aaaa">-->
    </tbody>
</table>

<?php echo $form->end()?>

