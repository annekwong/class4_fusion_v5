<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot ?>systemparams/allow_cdr_fields">
            <?php __('Configuration') ?>
        </a>
    </li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot ?>systemparams/allow_cdr_fields">
            <?php echo __('Carrier Portal Allowed CDR Fields') ?>
        </a>
    </li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Carrier Portal Allowed CDR Fields') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form method="post" id="myform">
                <h1 style="text-align:left;font-size:14px;">
                    <?php __('Please select the fields that you would like to enable in your user portal') ?>:
                </h1>
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php __('Field Names') ?></th>
                        <th>
                            <input type="checkbox"  class="all_ingress_fields" onclick="check_all_allow(this,'ingress_fields')" />
                            <?php __('Client CDR') ?>
                        </th>
                        <th>
                            <input type="checkbox"  class="all_egress_fields" onclick="check_all_allow(this,'egress_fields')" />
                            <?php __('Vendor CDR') ?>
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
<!--                            <input id="revert" type="button" value="--><?php //__('Revert to Default Setting') ?><!--" class="btn btn-inverse" />-->
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function if_all_checked(){
       var ingress_fields = $('.ingress_fields'),
           ingress_fields_checked = $('.ingress_fields:checked'),
           all_ingress_fields = $('.all_ingress_fields'),
           egress_fields = $('.egress_fields'),
           egress_fields_checked = $('.egress_fields:checked'),
           all_egress_fields = $('.all_egress_fields');

           if (ingress_fields.length == ingress_fields_checked.length) {
               all_ingress_fields.attr('checked', 'checked');
           }else{
               all_ingress_fields.removeAttr('checked');
           }
           if (egress_fields.length == egress_fields_checked.length) {
               all_egress_fields.attr('checked', 'checked');
           }else{
               all_egress_fields.removeAttr('checked');
           }
    }

    if_all_checked();

    $('.ingress_fields, .egress_fields').on('click', function(){
        if_all_checked();
    })
    var ingress_state = {};
    var egress_state = {};
    $('.ingress_fields').each(function( index ) {
        ingress_state[index] = $(this).is(':checked');
    });
    $('.egress_fields').each(function( index ) {
        egress_state[index] = $(this).is(':checked');
    });

    $('#myform').on('reset', function() {
        $.each(ingress_state, function( index, value ) {
           if (value) {
               $('.ingress_fields:eq('+index+')').attr('checked', 'checked');
           }else{
               $('.ingress_fields:eq('+index+')').removeAttr('checked');
           }
        });
        $.each(egress_state, function( index, value ) {
           if (value) {
               $('.egress_fields:eq('+index+')').attr('checked', 'checked');
           }else{
               $('.egress_fields:eq('+index+')').removeAttr('checked');
           }
        });
        if_all_checked();
    })

    function check_all_allow(obj,check_class)
    {
        var s = obj.checked;
        $("."+check_class).attr('checked',s);
    }

    $('#revert').click(function(){
        $('input:checkbox').attr('checked', false);

        // Answer Time
        $("input[value='(case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end)']").attr('checked', true);
        // End Time
        $("input[value='(case release_tod when 0 then null else to_timestamp(release_tod/1000000) end)']").attr('checked', true);
        // Start Time
        $("input[value='(case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end)']").attr('checked', true);
        $("input[value='ingress_client_cost']").attr('checked', true);
        $("input[value='egress_cost']").attr('checked', true);
        $("input[value='egress_rate']").attr('checked', true);
        $("input[value='ingress_client_rate']").attr('checked', true);
        $("input[value='call_duration']").attr('checked', true);
        $("input[value='trunk_id_origination']").attr('checked', true);
        $("input[value='trunk_id_termination']").attr('checked', true);

    });

</script>
