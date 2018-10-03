
<ul class="breadcrumb">
    <li><a href="<?php echo $this->webroot ?>currs/index"><?php __('Switch') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Currency') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if ($_SESSION['role_menu']['Switch']['currs']['model_w'])
    { ?>
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>currs/add"><i></i> <?php __('Create New') ?></a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#add').click(
                function () {
                    jQuery('table.list').show();
                    var action = jQuery(this).attr('href');
                    var is_configuration = "<?php echo isset($is_configuration) ? 1 : ""; ?>";
                    if(is_configuration)
                    {
                        action = "<?php echo $this->webroot ?>necessary_configuration/currs";
                    }
                    jQuery('table.list').first().trAdd({
                        action: action,
                        ajax: '<?php echo $this->webroot ?>currs/js_save',
                        'insertNumber': 'first',
                        removeCallback: function () {
                            if (jQuery('table.list tr').size() == 1) {
                                jQuery('table.list').hide();
                                $('.msg').show();
                            }
                        },
                        onsubmit: function (options) {
                            var re = true
                            re = jsAdd.onsubmit(options);
                            if (jQuery('#CurrCode').val() != '') {
                                var data = jQuery.ajaxData("<?php echo $this->webroot ?>currs/check_repeat_name/" + jQuery('#CurrCode').val());
                                if (!data.indexOf("false")) {
                                    jQuery.jGrowlError(jQuery('#CurrCode').val() + " is already in use! ");
                                    re = false;
                                }
                            }
                            return re;
                        }
                    });
                    return false;
                }
        );
    });
</script>


