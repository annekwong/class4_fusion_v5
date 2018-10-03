<style type="text/css">
    #error_info {
        background:white;width:300px;height:200px;display:none;
        overflow:hide;word-wrap: break-word; padding:20px;
    }
    table.in-date tr td{border-top: 0;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <?php echo __('Random ANI Group') ?>
        <?php echo isset($random_table['RandomAniTable']['name']) ? "[" . $random_table['RandomAniTable']['name'] . "]" : ""; ?>
    </li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Random ANI Group') ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>random_ani/random_table"><i></i>Back</a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a class="glyphicons justify" href="<?php echo $this->webroot; ?>random_ani/random_generation/<?php echo base64_encode($random_table['RandomAniTable']['id']); ?>">
                        <i></i>
                        <?php __('ANI Number') ?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons upload" href="<?php echo $this->webroot; ?>uploads/random_generation/<?php echo base64_encode($random_table['RandomAniTable']['id']); ?>">
                        <i></i>
                        <?php __('Import') ?> 
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons upload" href="<?php echo $this->webroot; ?>random_ani/auto_populate/<?php echo base64_encode($random_table['RandomAniTable']['id']); ?>">
                        <i></i>
                        <?php __('Auto Populate') ?>  
                    </a>
                </li>
                <li>
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>random_ani/auto_populate_log/<?php echo base64_encode($random_table['RandomAniTable']['id']); ?>">
                        <i></i>
                        <?php __('Auto Populate Log') ?>  
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body">
            <form method="post" action="" id="myform">
                <table class="form table-condensed" width="100%">
                    <tr>
                        <td class="align_right padding-r10"><?php __("Prefix"); ?></td>
                        <td>
                            <input type="text" id="prefix" name="prefix" class="validate[required,custom[onlyLetterNumber]]" />
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __("Number of digits"); ?></td>
                        <td>
                            <input type="text" id="digits"  name="number_of_digits" maxlength="2"  class="validate[required,custom[integer]]"/>
                        </td>
                    </tr>

                </table>
                <div class="row-fluid">
                    <div class="span12 center">
                        <input id="subbtn" class="btn btn-primary" type="button" value="Submit">
                        <input class="input in-submit btn btn-default" type="reset" value="Revert">
                    </div>
                </div>
            </form>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="dd" style="display: none;">
    <div  class="form table-condensed center" width="100%">
        <table class="center">
            <tr>
                <th><?php __("The system will add the following numbers"); ?></th>
            </tr>
            <tr>
                <td id="preview">
                </td>
            </tr>
        </table>
    </div>
</div>
<script type="text/javascript">

    $(function() {
        $("#subbtn").click(function() {
            var prefix = $("#prefix").val();
            var digits = $("#digits").val();
            if ($("#myform").validationEngine('validate') == false){
                return false;
            }
//            reg = /^[0-9a-zA-Z]{1,31}$/;
//            if (!reg.test(prefix)) {
//                jGrowl_to_notyfy('<?php //__("prefix Letters and numbers only"); ?>//', {theme: 'jmsg-error'});
//                return false;
//            }
//            dig_reg = /^[1-9]$|^1[0-9]$|^(20)$/;
//            if (!dig_reg.test(digits)) {
//                jGrowl_to_notyfy('<?php //__("number_of_digits match error"); ?>//', {theme: 'jmsg-error'});
//                return false;
//            }
//            var total_length = prefix.length + parseInt(digits);
//            if (total_length > 32)
//            {
//                jGrowl_to_notyfy('<?php //__("ANI length greater than 32"); ?>//', {theme: 'jmsg-error'});
//                return false;
//            }
            var zero_flg = "";
            var end_flg = "";
            for (var i = 1; i < parseInt(digits); i++)
            {
                zero_flg += 0;
                end_flg += 9;
            }
            var one_flg = zero_flg+1;
            zero_flg += 0;
            end_flg += 9;
//            alert(zero_flg);
//            alert(one_flg);return false;
            var preview_result = prefix+zero_flg+","+prefix+one_flg+",...,"+prefix+end_flg;
            $("#preview").html(preview_result);
            var $dd = $("#dd");
            $dd.dialog({
                'title': "Auto Populate Result Preview",
                'width': '450px',
                'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
                            $(this).dialog("close");
                            $("#myform").submit();
                        }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                            $(this).dialog("close");
                        }}]
            });

        });
    });

</script>



