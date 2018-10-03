<style type="text/css">
    #multiple {display:none;}
   input[type="text"]{margin-bottom: 0;}
 .innerLR textarea{max-width:450px;}
</style>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LNP Request') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Trunk Management>>LNP Request')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li><a href="<?php echo $this->webroot; ?>did/lnp_request" class="glyphicons left_arrow"><i></i><?php __('Log'); ?></a></li>
                <li class="active" ><a href="<?php echo $this->webroot; ?>did/lnp_request/push" class="glyphicons right_arrow"><i></i><?php __('Submit'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">
                <form enctype="multipart/form-data" action="<?php echo $this->webroot; ?>did/lnp_request/push" method="post">
                    <table class="list table dynamicTable tableTools table-bordered  table-white form">
                        <tbody>
                            <tr>
                                <td class="right" width="40%"><?php __('Choose Type of LNP Request')?></td>
                                <td>
                                    <input type="radio" name="request_type" value="0" checked="checked" /> <?php __('Single or Range')?>
                                    <input type="radio" name="request_type" value="1" /> <?php __('Multiple Comma Separated')?>
                                </td>
                            </tr>
                            <tr>
                                <td class="right" width="40%"><?php __('Sample LOA Templates')?></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <table id="single" class="list table dynamicTable tableTools table-bordered  table-white form">
                        <tbody>
                            <tr>
                                <td colspan="2"><h4><?php __('Single or Range Number(s)')?></h4></td>
                            </tr>
                            <tr>
                                <td class="right" width="40%"><?php __('Number to Port')?></td>
                                <td>
                                    <input type="text" name="number_to_port" />
                                </td>
                            </tr>
                            <tr>
                                <td class="right" width="40%"><?php __('Range To')?></td>
                                <td>
                                    <input type="text" name="range_to" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table id="multiple" class="list table dynamicTable tableTools table-bordered  table-white form">
                        <tbody>
                            <tr>
                                <td colspan="2"><h4><?php __('Multiple Number(s) Request')?></h4></td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <textarea name="multiple_numbers_request" style="width:450px;height:100px;"></textarea>
                                </th>
                                <td>
                                    <p>
                                        <?php __('- All Numbers should have SAME BTN and')?> <br />
                                        <?php __('Physical Address.')?>
                                    </p>
                                    <p>
                                        <?php __('- Only for multiple Numbers Orders (Comma')?><br />
                                        <?php __('Separated, Max 49 numbers).')?>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="list table dynamicTable tableTools table-bordered  table-white form">
                        <tbody>
                            <tr>
                                <td style="text-align:center;">
                                    <input type="file" name="upload_file" />
                                </th>
                            </tr>
                            <tr style="text-align:center;">
                                <td colspan="2" class="button-groups center input in-submit">
                                    <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit')?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>






<script>
    $(function() {
        var $single = $('#single');
        var $multiple = $('#multiple');

        $('input[name=request_type]').change(function() {
            var val = $(this).val();
            if (val == 0) {
                $single.show();
                $multiple.hide();
            } else {
                $single.hide();
                $multiple.show();
            }
        });
    });
</script>