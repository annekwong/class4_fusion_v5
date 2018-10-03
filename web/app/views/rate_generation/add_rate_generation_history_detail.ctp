<style type="text/css">
    .width80{width: 80px;};
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tool') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Apply to Rate Table') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Apply to Rate Table') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add_history_detail" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)">
        <i></i>
        <?php __('Add'); ?>
    </a>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rate_generation/rate_generation_history/<?php echo $this->params['pass'][0] ?>"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <form method="post" id="myform" >
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <?php if($is_us): ?>
                    <tr>
                        <th><?php __('Rate Table') ?></th>
                        <th><?php __('Effective Date') ?></th>
                        <!--th><?php __('End Date') ?></th-->
                        <!--th><?php __('Send Email') ?></th-->
                        <th><?php __('End Date') ?></th>
                        <!--th><?php __('Email Template') ?></th-->
                        <th><?php __('Action') ?></th>
                    </tr>
                    <?php else: ?>
                        <tr>
                            <th rowspan="2"><?php __('Rate Table') ?></th>
                            <th colspan="3"><?php __('Effective Date') ?></th>
                            <!--th rowspan="2"><?php __('End Date') ?></th-->
                            <!--th rowspan="2"><?php __('Send Email') ?></th-->
                            <th rowspan="2"><?php __('End Date Method') ?>
                            <th rowspan="2"><?php __('End Date') ?>
                            <!--th rowspan="2"><?php __('Email Template') ?></th-->
                            <th rowspan="2"><?php __('Action') ?></th>
                        </tr>
                        <tr>
                            <th><?php __('New') ?></th>
                            <th><?php __('Increase') ?></th>
                            <th><?php __('Decrease') ?></th>
                        </tr>
                    <?php endif; ?>
                    </thead>
                    <tbody id="history_detail_table">
                    <tr>
                        <td>
                            <select name="rate_table_id[]" class="rate_table_id">
                                <?php foreach ($rate_table as $rate_table_id => $rate_table_name): ?>
                                    <option value="<?php echo $rate_table_id ?>"><?php echo $rate_table_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <?php if($is_us): ?>
                            <td>
                                <input class=" input in-text width80 validate[required]" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date_new[]">
                            </td>
                        <?php else: ?>
                            <td>
                                <input class=" input in-text width80 validate[required]" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date_new[]">
                            </td>
                            <td>
                                <input class="input in-text width80 validate[required]" type="text" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date_increase[]">
                            </td>
                            <td>
                                <input class="input in-text width80 validate[required]" type="text" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date_decrease[]">
                            </td>
                        <?php endif; ?>
                        <!--td>
                            <input class="input in-text width80" type="text"  onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="new_rate_end_date[]">
                        </td-->
                        <!--td>
                            <input type="hidden" name="is_send_mail[]" value="" />
                            <input type="checkbox" class="is_send_mail" />
                        </td-->
                        <?php if($is_us): ?>
                        <td>
                            <select name="end_date_method[]" id="end_date_method">
                                <?php foreach ($end_date_method as $end_date_method_id => $end_date_method_name): ?>
                                    <option value="<?php echo $end_date_method_id ?>"><?php echo $end_date_method_name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input class="input in-text width80 validate[required]" type="text" value=""
                                   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d",strtotime('-1 days')); ?>'});" id="end_date" name="end_date[]">
                        </td>
                        <?php else:?>
                        <td>
                            <select name="end_date_method[]" id="end_date_method">
                                <?php foreach ($end_date_method as $end_date_method_id => $end_date_method_name): ?>
                                    <option value="<?php echo $end_date_method_id ?>"><?php echo $end_date_method_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input class="input in-text width80 validate[required]" type="text" value=""
                                   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d",strtotime('-1 days')); ?>'});" id="end_date" name="end_date[]">
                        </td>

                        <?php endif;?>
                        <!--td>
                            <select  name="email_template_id[]" class="email_template_select" >
                                <?php foreach ($rate_email_template as $rate_email_template_id => $rate_email_template_name): ?>
                                    <option value="<?php echo $rate_email_template_id ?>"><?php echo $rate_email_template_name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <a href="#myModalViewRateTemplate" class="view_rate_template" data-toggle="modal" title="<?php __('View'); ?>">
                                <i class="icon-edit"></i>
                            </a>
                            <a href="#myModalAddRateTemplate" class="add_rate_template" data-toggle="modal" title="<?php __('create new'); ?>">
                                <i class="icon-plus"></i>
                            </a>
                            <a class="refreshEmailTemplate" href="javascript:void(0)" title="Refresh"><i class="icon-refresh"></i></a>
                        </td-->
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <div class="separator"></div>
                <table style="width: 100%;" >
                    <tr>
                        <td class="buttons-group center">
                            <input type="button" data-send=0 value="<?php __('Apply Rate Only') ?>" class=" myform_sub btn btn-primary"/>
                            <input type="button" data-send=1 value="<?php __('Apply Rate and Send') ?>" class="myform_sub btn btn-primary"/>
                            <input type="reset"  value="<?php __('Revert') ?>" class="btn btn-default" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<div id="myModalAddRateTemplate" class="modal hide" style="min-width: 800px;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Add Rate Email Template'); ?></h3>
    </div>
    <div class="separator"></div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="hidden" class="btn_class" />
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>

<div id="myModalViewRateTemplate" class="modal hide" style="min-width: 800px;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Edit Rate Email Template'); ?></h3>
    </div>
    <div class="separator"></div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="hidden" class="rate_template_id" />
        <input type="hidden" class="btn_class" />
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.base64.min.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script type="text/javascript">
    let is_us = '<?php echo $is_us ? 1 : 0; ?>';
    function refresh_rate_email_template( btn_class,default_selected,update_all ){
        $.ajax({
            'url': '<?php echo $this->webroot; ?>routestrategys/ajax_rate_email_template',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $('.'+btn_class).siblings('select').empty();
                $('.'+btn_class).siblings('select').data('value',btn_class);
                $.each(data, function(index, item) {
                    if ( item[0]['id'] == default_selected ){
                        $('.'+btn_class).siblings('select').append('<option selected value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                    }else{
                        $('.'+btn_class).siblings('select').append('<option value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                    }
                });
                if (update_all) {
                    $(".email_template_select").each(function () {
                        console.log(123);
                        var $this = $(this);
                        var this_select = $(this).val();
                        if ($this.data('value') != btn_class) {
                            $this.empty();
                            $.each(data, function (index, item) {
                                if (item[0]['id'] == this_select) {
                                    $this.append('<option selected value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                                } else {
                                    $this.append('<option value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                                }
                            });
                        }
                    });
                }
            }
        });
    }

    $(function() {
        $(".refreshEmailTemplate").live('click',function(){
            var time = new Date().getTime();
            var btn_class = 'refreshRateTemplate'+time;
            $(this).addClass(btn_class);
            refresh_rate_email_template(btn_class,0,1);
        });

        $(".view_rate_template").live('click',function(){
            var rateTemplateId = $(this).siblings('select').val();
            var time = new Date().getTime();
            var btn_class = 'viewRateTemplate'+time;
            $(this).addClass(btn_class);
            $("#myModalViewRateTemplate").find('.rate_template_id').val(rateTemplateId);
            $("#myModalViewRateTemplate").find('.btn_class').val(btn_class);
            $("#myModalViewRateTemplate").find('.modal-body').load("<?php echo $this->webroot; ?>rate_email_template/add_template/"+$.base64.encode(rateTemplateId)+"?is_ajax=1");
        });

        $("#myModalViewRateTemplate").find('.sub').click(function(){
            var $this = $(this);
            var is_validate = $("#myModalViewRateTemplate").find('form').validationEngine('validate');
            if ( !is_validate ){
                return false;
            }
//            var $thisTemplate = $("#myModalViewRateTemplate").find('.rate_template_id');
            var rateTemplateId = $("#myModalViewRateTemplate").find('.rate_template_id').val();
            var btn_class = $("#myModalViewRateTemplate").find('.btn_class').val();
            var add_content_id = $("#myModalViewRateTemplate").find('.mail_content').attr('id');
            var this_content = CKEDITOR.instances[add_content_id].getData();
            $("#myModalViewRateTemplate").find('.mail_content').val(this_content);
            $.ajax({
                url: "<?php echo $this->webroot ?>rate_email_template/add_template/"+$.base64.encode(rateTemplateId)+"?is_ajax=1",
                type: 'post',
                dataType: 'text',
                data: $("#myModalViewRateTemplate").find('form').serialize(),
                success: function(data) {
                    if (data != 0)
                    {
                        $this.next().click();
                        refresh_rate_email_template(btn_class,data,1);
                        jGrowl_to_notyfy('<?php __('Edit success'); ?>', {theme: 'jmsg-success'});
                    }
                    else
                        jGrowl_to_notyfy('<?php __('Edit failed'); ?>', {theme: 'jmsg-error'});
                }
            });
        });

        $(".add_rate_template").live('click',function(){
            var rateTemplateId = $(this).siblings('select').val();
            var time = new Date().getTime();
            var btn_class = 'addRateTemplate'+time;
            $(this).addClass(btn_class);
            $("#myModalAddRateTemplate").find('.btn_class').val(btn_class);
            $("#myModalAddRateTemplate").find('.modal-body').load("<?php echo $this->webroot; ?>rate_email_template/add_template?is_ajax=1");
        });


        $("#myModalAddRateTemplate").find('.sub').click(function(){
            var $this = $(this);
            var is_validate = $("#myModalAddRateTemplate").find('form').validationEngine('validate');
            if ( !is_validate ){
                return false;
            }
            var btn_class = $("#myModalAddRateTemplate").find('.btn_class').val();
            var add_content_id = $("#myModalAddRateTemplate").find('.mail_content').attr('id');
            var this_content = CKEDITOR.instances[add_content_id].getData();
            $("#myModalAddRateTemplate").find('.mail_content').val(this_content);
            $.ajax({
                url: "<?php echo $this->webroot ?>rate_email_template/add_template?is_ajax=1",
                type: 'post',
                dataType: 'text',
                data: $("#myModalAddRateTemplate").find('form').serialize(),
                success: function(data) {
                    if (data != 0)
                    {
                        $this.next().click();
                        refresh_rate_email_template(btn_class,data,1);
                        jGrowl_to_notyfy('<?php __('Create success'); ?>', {theme: 'jmsg-success'});
                    }
                    else
                        jGrowl_to_notyfy('<?php __('Create failed'); ?>', {theme: 'jmsg-error'});
                }
            });
        });

        jQuery('a[id=add_history_detail]').click(function() {


            $.get("<?php echo $this->webroot; ?>rate_generation/get_apply_row?is_us=<?php echo $is_us ? 1 : 0; ?>", function(data){
//                console.log(data);
                $("#history_detail_table").html($("#history_detail_table").html() + data);
            });
        });

        $(".is_send_mail").live('change',function(){
            if($(this).attr('checked'))
                $(this).siblings("input[name='is_send_mail[]']").val(1);
            else
                $(this).siblings("input[name='is_send_mail[]']").val('');
        });

        $(".myform_sub").click(function() {
            let rate_table_dulp = 0;
            let send = parseInt($(this).attr('data-send'));

            $(".rate_table_id").each(function(i) {
                var $this_value = $(this).val();
                var flg = 0;
                $(".rate_table_id").each(function(j) {
                    if (i >= j)
                        return true;
                    if ($this_value == $(this).val())
                    {
                        flg = 1;
                        return false;
                    }
                });
                if (flg == 1)
                    rate_table_dulp = 1;
                return false;
            });

            if(send){
                $("#myform").append('<input type="hidden" name="send" value=1 />');
            }
            if (rate_table_dulp)
                jGrowl_to_notyfy('<?php __('Exist Duplicate rate table'); ?>', {theme: 'jmsg-error'});
            else
                $("#myform").submit();
        });
    });
</script>