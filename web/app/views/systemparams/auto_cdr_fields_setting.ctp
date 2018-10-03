<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot ?>systemparams/auto_cdr_fields_setting">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/auto_cdr_fields_setting">
        <?php echo __('Auto CDR Generation Format') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Auto CDR Generation Format') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form method="post" id="myform">
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Field Names') ?></th>
                            <th>
                                <input type="checkbox" class="ingress_fields_all" onclick="check_all_allow(this,'ingress_fields')" />
                                <?php __('Incoming CDR') ?>
                            </th>
                            <th>
                                <input type="checkbox" class="egress_fields_all" onclick="check_all_allow(this,'egress_fields')" />
                                <?php __('Outgoing CDR') ?>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($all_cdr_fields as $all_cdr_field_key => $arr_cdr_field): ?>
                            <tr>
                                <td><?php echo $arr_cdr_field; ?></td>

                                <td>
                                    <?php if (in_array($arr_cdr_field, $incoming_cdr_fields)): ?>
                                    <input type="checkbox" class="ingress_fields" name="ingress_fields[]" value="<?php echo $all_cdr_field_key; ?>" <?php if (in_array($arr_cdr_field, $incoming_data)): ?>checked="checked"<?php endif; ?> />
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (in_array($arr_cdr_field, $outgoing_cdr_fields)): ?>
                                    <input type="checkbox" class="egress_fields" name="egress_fields[]" value="<?php echo $all_cdr_field_key; ?>" <?php if (in_array($arr_cdr_field, $outgoing_data)): ?>checked="checked"<?php endif; ?> />
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4" class="button-groups center">
                                <input type="submit" value="<?php __('Submit') ?>" class="btn btn-primary" />
                                <input type="reset" value="<?php __('Revert') ?>" class="btn btn-inverse" />
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
function check_all_allow(obj,check_class)
{
    var s = obj.checked;
    $("."+check_class).attr('checked',s);
}
$(document).ready(function(){
    if($('.ingress_fields').length == $('.ingress_fields:checked').length){
        $('.ingress_fields_all').attr('checked', 'checked');
    }

    if($('.egress_fields').length == $('.egress_fields:checked').length){
        $('.egress_fields_all').attr('checked', 'checked');
    }
    let ingress_all = $('.ingress_fields_all').is(':checked');
    let egress_all = $('.egress_fields_all').is(':checked');
    let ingress_fields = [];
    let egress_fields = [];
    $('.ingress_fields').each(function () {
        ingress_fields.push($(this).is(':checked'));
    });
    $('.egress_fields').each(function () {
        egress_fields.push($(this).is(':checked'));
    });

     $('input[type="reset"]').on('click', function(e){
           e.preventDefault();
           $('.ingress_fields_all').attr('checked',ingress_all);
           $('.egress_fields_all').attr('checked',egress_all);
           $('.ingress_fields').each(function (i,v) {
               $(this).attr('checked', ingress_fields[i]);
           });
           $('.egress_fields').each(function (i,v) {
                $(this).attr('checked', egress_fields[i]);
           });
     })





})


</script>

