<?php echo $this->element("currs/jss") ?>
<?php echo $this->element("currs/title", Array('isWrite' => true, 'is_configuration' => true)) ?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php echo $this->element("currs/step", array('now' => '1')); ?>
        <div class="widget-body" style="min-height: 100px">
            <?php echo $this->element("currs/list", Array('isWrite' => true, 'is_configuration' => true)) ?>
        </div>
        <!--<div class= "center">
            <?php /*if($data_count):*/?>
            <a href="<?php /*echo $this->webroot */?>homes/init_login_url/<?php /*echo $user_id; */?>/1"  class=" btn primary next"><?php /*__('Next')*/?></a>
            <?php /*else: */?>
            <a href="javascript:void(0)"  class=" btn primary next disabled"><?php /*__('Next')*/?></a>
            <?php /*endif; */?>
        </div>-->
        <!--<div class="center separator">

            <a href="<?php /*echo $this->webroot */?>necessary_configuration/codes_deck/<?php /*echo $a_z_code_deck_id . '/' . $user_id; */?>" class="next input in-submit btn btn-primary"><?php /*__('Next') */?></a>
        </div>-->
        <div class="clearfix"></div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        $.gritter.add({
            title: '<?php __('Setup Currency'); ?>',
            text: '<?php __('Your system up and running, you need to set the currency at least one in active.'); ?>',
            sticky: true
        });


        $('.widget-head li').unbind();

    });

    $(document).on('DOMNodeInserted', function(){
        $('.table-white a.disabled').attr('title', 'Activate');
        $('.history').attr('title', "View Change History");
    });

    $(document).ready(function(){
        $('table td .disabled').attr('onclick',"return myconfirm('Are you sure you would like to activate the selected currency?', this)");
    });

</script>