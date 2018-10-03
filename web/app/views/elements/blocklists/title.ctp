<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>blocklists/index"><?php __('Routing') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>blocklists/index"><?php echo __('Block List') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Block List') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php  if ($_SESSION['role_menu']['Routing']['blocklists']['model_w']) {?>
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)"><i></i> <?php __('Create New')?></a>
            <?php if (!empty($this->data)) { ?>
                <a class="btn btn-primary btn-icon glyphicons remove"  onclick="deleteAll('<?php echo $this->webroot?>blocklists/del_all_blo');" href="javascript:void(0)"><i></i> <?php __('Delete All')?></a>
                <a class="btn btn-primary btn-icon glyphicons remove"  onclick="ex_deleteSelected('list_id','<?php echo $this->webroot?>blocklists/del_selected_blo','block list');" href="javascript:void(0)"><i></i> <?php __('Delete Seleted')?></a>
            <?php  } ?>
        <?php  } ?>
    </div>
    <div class="clearfix"></div>

<script type="text/javascript">
    jQuery('#add').click(
    function(){

        $('.ColVis_collection').find(':checkbox').each(
            function(i,item){
                if(!$(this).is(':checked'))
                    $(this).click();
        });

        $('.msg').hide();
        //	jQuery(this).parent().parent().hide();
        global_option = {};
        global_option.id = '';
        jQuery('table.list').trAdd({
            ajax:"<?php echo $this->webroot?>blocklists/js_save",
            action:"<?php echo $this->webroot?>blocklists/add",
            'insertNumber' : 'first',
            callback:function(options){$('.method_select').trigger('change');return blocklist.trAddCallback(options);},
            onsubmit:function(options){
                var ani_length = $("#ani_length").val();
                var dnis_length = $("#dnis_length").val();
                var ani_max_length = $("#ani_max_length").val();
                var dnis_max_length = $("#dnis_max_length").val();
                if(ani_length){
                    checkint(ani_length,'<?php __('ANI Min Length'); ?>');
                }
                if(ani_max_length){
                    checkint(ani_max_length,'<?php __('ANI Max Length'); ?>');
                }
                if(dnis_length){
                    checkint(dnis_length,'<?php __('DNIS Min Length'); ?>');
                }
                if(dnis_max_length){
                    checkint(dnis_max_length,'<?php __('DNIS Max Length'); ?>');
                }
                if(ani_length && ani_max_length){
                    if(parseInt(ani_length) > parseInt(ani_max_length)){
                        jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), __('ANI Max Length',true),__('ANI Min Length',true)); ?>', {theme: 'jmsg-error'});
                        return false;
                    }
                }
                if(dnis_length && dnis_max_length) {
                    if (parseInt(dnis_length) > parseInt(dnis_max_length)) {
                        jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), __('DNIS Max Length',true),__('DNIS Min Length',true)); ?>', {theme: 'jmsg-error'});
                        return false;
                    }
                }
//                console.log($("#type").val() + " " + $("select[name='data[ResourceBlock][ingress_group_id]']").val() + " " + $("select[name='data[ResourceBlock][egress_group_id]']").val());
//                return false;
                if($("#type").val() == 2 && ($("select[name='data[ResourceBlock][ingress_group_id]']").val() =='' && $("select[name='data[ResourceBlock][egress_group_id]']").val() =='')) {
                    jGrowl_to_notyfy('Please select one of Trunk Groups!', {theme: 'jmsg-error'});
                    return false;
                }
                return blocklist.trAddOnsubmit(options);
            },
            removeCallback:function(){
                if(jQuery('table.list tr').size()==1){
                    jQuery('table.list').hide();
                    $('.msg').show();
                }
            }
        });
        jQuery(this).parent().parent().show();
    }
);

</script>