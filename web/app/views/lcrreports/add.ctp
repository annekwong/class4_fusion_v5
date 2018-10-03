
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LCR Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Add LCR Report')?></h4>
    <div class="buttons pull-right">
        <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>lcrreports">
            <i></i><?php __('Back')?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form action="<?php echo $this->webroot ?>lcrreports/do_add"  method="post" name="myform" id="myform">
                <div class="filter-bar">

                    <div class="block">

                        <label class="block_label"><?php echo __('type', true); ?></label>

                        <select name="type" class="select in-select">
                            <option value="intra_rate"><?php __('intra_rate')?></option>
                            <option value="inter_rate"><?php __('inter_rate')?></option>
                            <option value="rate"><?php __('rate')?></option>
                        </select>
                    </div>
                    <div id="only2">
                        <label class="block_label"><?php echo __('No', true); ?>.:</label>
                        <input type="text" name="no" class="input in-text in-input"/>
                        &nbsp;
                        <?php echo __('The percentage of profit', true); ?>:
                        <input type="text" name="profit" class="input in-text in-input" />
                        %
                    </div>
                </div>
                <div class="rate_table">
                    <div class="block center">
                        <label style="font-weight:bold;; font-size:14px;"><?php echo __('Rate Table', true); ?>:</label>
                        <table class="chkboxgroup center table-primary table-white footable table-striped">
                            <?php foreach ($ratetables as $key => $ratetable): ?>

                                <?php if (intval($key) % 4 == 0) { ?>
                                    <tr>
                                    <?php } ?>
                                    <td>
                                        <input du="<?php echo intval($key) % 4; ?>" type="checkbox" name="rate_table[]" value="<?php echo $ratetable[0]['rate_table_id']; ?>" />
                                    </td>
                                    <td style="text-align: left;">
                                        <span><?php echo $ratetable[0]['name'] ?></span> 
                                    </td>
                                    <?php if (intval($key) % 4 ==3) { ?>
                                    </tr>
                                <?php } ?>

                            <?php endforeach; ?>
                        </table>
                        <br class="clear" />
                    </div>
                </div>
                <div id="form_footer" class="center">
                    <input type="submit" value="<?php echo __('submit', true); ?>" class="btn btn-primary input in-submit" />
                    &nbsp;
                    <input type="reset" value="<?php echo __('reset', true); ?>" class="input in-submit btn btn-default" />
                </div>
            </form>
        </div>
        <script type="text/javascript">
            $(function() {
                $('select[name=choose]').change(function() {
                    if ($(this).val() == '1') {
                        $('#only2').show();
                    } else {
                        $('#only2').hide();
                    }
                });
            });
        </script>