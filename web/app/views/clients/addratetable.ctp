
<div id="add_rate">
    <form method="post" name="myform" id="myform1">
        <table class="form table table-condensed">
            <tr>
                <td><?php echo __('name', true); ?>:</td>
                <td><input type="text" class="input in-text" name="name"/></td>
                <td><?php echo __('Code Deck', true); ?>:</td>
                <td><select class="select in-select" name="codedeck">
                        <option value=""></option>
                        <?php foreach ($code_deck_result as $code_deck): ?>
                            <option value="<?php echo $code_deck[0]['id']; ?>"><?php echo $code_deck[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select></td>
            </tr>
            <tr>
                <td><?php echo __('Currency', true); ?>:</td>
                <td>
                    <select class="select in-select" name="currency">
                        <?php foreach ($currency_result as $currency): ?>
                            <option value="<?php echo $currency[0]['id']; ?>"><?php echo $currency[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><?php echo __('Jurisdiction Country', true); ?>:</td>
                <td>
                    <select class="select in-select" name="jurcountry">
                        <option value=""></option>
                        <?php foreach ($jurcountry_result as $jurcountry): ?>
                            <option value="<?php echo $jurcountry[0]['id']; ?>"><?php echo $jurcountry[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Billing Method', true); ?>:</td>
                <td>
                    <select class="select in-select" name="ratetype">
                        <option value="0"><?php __('DNIS') ?></option>
                        <option value="1"><?php __('LRN') ?></option>
                        <option value="2"><?php __('LRN BLOCK') ?></option>
                    </select>
                </td>
                <td><?php echo __('Rate Type', true); ?>:</td>
                <td>
                    <select class="select in-select" name="jur_type">
                        <option selected="selected" value="0"><?php __('A-Z') ?></option>
                        <option value="1"><?php __('US Non-JD') ?></option>
                        <option value="2"><?php __('US JD') ?></option>
                        <option value="3"><?php __('OCN-LATA-JD') ?></option>
                        <option value="4"><?php __('OCN-LATA-NON-JD') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="center">
                    <button id="firstbtn" class="btn btn-primary"><?php __('Continue') ?></button>
                </td>
            </tr>
        </table>

    </form>
</div>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.base64.min.js"></script>
<script type="text/javascript">
    jQuery(function($) {

        var rate_table_id;
        $('#firstbtn').click(function() {
            $.ajax({
                url: "<?php echo $this->webroot; ?>clients/addratetable_first",
                type: "POST",
                dataType: "text",
                data: $('#myform1').serialize(),
                success: function(data) {
                    data = data.replace(/(^\s*)|(\s*$)/g, "");
                    if (data == "0") {
                        jGrowl_to_notyfy('<?php echo __("The name exists!",true); ?>', {theme: 'jmsg-error'});
                    }
                    else if (data == 'namenone') {
                        jGrowl_to_notyfy('<?php echo __("Name cannot be empty!",true); ?>', {theme: 'jmsg-error'});
                    }
                    else {
                        rate_table_id = data;

                        bootbox.dialog("<?php echo __('Rate table create successfully!'); ?>", [
                            {
                                "label": "Cancel",
                                'class': 'btn-cancel',
                                "callback": function () {
                                }
                            }, {
                                "label": "<?php echo ucwords(__('import data',true)); ?>",
                                "class": "btn-primary",
                                "callback": function () {
                                    var href = "<?php echo $this->webroot; ?>clientrates/import/" + $.base64.encode(rate_table_id);
                                    window.open(href);
                                }
                            }, {
                                "label": "<?php echo ucwords(__('add data',true)); ?>",
                                "class": "btn-primary",
                                "callback": function () {
                                    var href = "<?php echo $this->webroot; ?>clientrates/view/" + $.base64.encode(rate_table_id);
                                    window.open(href);
                                }
                            }]);
                        $('#dd').dialog('close');
                        test3(rate_table_id);
                    }
                }
            });
            return false;
        });

    });

</script>