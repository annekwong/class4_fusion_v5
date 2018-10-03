<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
        <?php echo __('FTP Configuration'); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('FTP Configuration'); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>systemparams/ftp_conf"><i></i> <?php __('Back'); ?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <?php echo $this->element('/systemparams/_ftp_form'); ?>
        </div>
    </div>

</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/fields_sendrate.js"></script>
<script type="text/javascript">
    
    $(function() {
        var $frequency = $('#frequency');
        var $every_day = $('#every_day');
        var $every_hours = $('#every_hours');
        var $every_minutes = $('#every_minutes');
        var $execute_on_tr = $('#execute_on_tr');

        $frequency.change(function() {
            var val = $(this).val();
            $("#file_breakdown").show();
            if (val == '3') {
                $every_hours.show();
                $execute_on_tr.hide();
                $every_minutes.hide();
                $every_day.hide();
            }else if (val == '4') {
                $every_minutes.show();
                $execute_on_tr.hide();
                $every_hours.hide();
                $("#file_breakdown").hide();
                $every_day.hide();
            }else if(val == '2')  {
                $every_day.show();
                $every_minutes.hide();
                $every_hours.hide();
                $execute_on_tr.show();
            }else {
                $every_day.hide();
                $every_minutes.hide();
                $every_hours.hide();
                $execute_on_tr.show();
            }
        }).trigger('change');

        $('#myform').submit(function() {
            $('#columns option').attr('selected', true);
        });
    });
    
</script>