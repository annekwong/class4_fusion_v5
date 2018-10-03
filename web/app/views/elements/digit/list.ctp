<?php $mydata = $p->getDataArray(); ?>
<?php if (empty($mydata)) { ?>
    <h2 class="msg center" id="no_data_found"><?php echo __('no_data_found') ?></h2>
    <table style="display:none"
           class="list list-form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"
           id="list_table">
        <thead>
        <tr>
            <!--		   <th> <?php echo $appCommon->show_order('id', __('ID', true)) ?></th>-->
            <th style="text-align:center;" data-class="expand"><input type="checkbox" id="selectAll" value=""/></th>
            <th> <?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
            <th> <?php echo $appCommon->show_order('trans', __('Digit Map Count', true)) ?></th>
            <th> <?php echo $appCommon->show_order('updateat', __('Updated At', true)) ?></th>
            <?php if ($_SESSION['role_menu']['Routing']['products']['model_w']) { ?>
                <th><?php echo __('action') ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody id="rows">
        </tbody>
    </table>
<?php } else { ?>
    <table class="list list-form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"
           id="list_table">

        <thead>
        <tr>
            <!--		   <th> <?php echo $appCommon->show_order('id', __('ID', true)) ?></th>-->

            <th style="text-align:center;" data-class="expand"><input type="checkbox" id="selectAll" value=""/></th>
            <th> <?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
            <th> <?php echo $appCommon->show_order('trans', __('Digit Map Count', true)) ?></th>
            <th> <?php echo $appCommon->show_order('updateat', __('Updated At', true)) ?></th>
            <?php if ($_SESSION['role_menu']['Routing']['products']['model_w']) { ?>
                <th><?php echo __('action') ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody id="rows">


        <?php foreach ($mydata as $list) { ?>
            <tr>
                <!--    <td>
                            <?php echo array_keys_value($list, '0.id') ?>
                    </td>-->
                <td style="text-align:center;"><input type="checkbox"
                                                      value="<?php echo array_keys_value($list, '0.id') ?>" name="id"/>
                </td>
                <td>

                    <a href="<?php echo $this->webroot ?>digits/translation_details/<?php echo base64_encode(array_keys_value($list, '0.id')) ?>"
                       title="View Details" class="link_width" style="width:80%;display:block" id="tpl-trans-text">

                        <?php echo array_keys_value($list, '0.name') ?>

                    </a>

                </td>
                <td>
                    <a href="<?php echo $this->webroot ?>digits/translation_details/<?php echo base64_encode(array_keys_value($list, '0.id')) ?>"
                       title="View Details" class="link_width" style="width:100%;display:block" id="tpl-trans-text">
                        <?php echo array_keys_value($list, '0.trans') ?>
                    </a>


                </td>
                <td> <?php echo array_keys_value($list, '0.updateat') ?> </td>
                <?php if ($_SESSION['role_menu']['Routing']['products']['model_w']) { ?>
                    <td align="center" style="text-align:center" class="last">
                        <a id="edit" href="#" title="<?php __('Edit') ?>"
                           list_id=<?php echo array_keys_value($list, '0.id') ?>>
                            <i class="icon-edit"></i>
                        </a>
                        <a class="delete_digit" href="###"
                           url="<?php echo $this->webroot ?>digits/delete/<?php echo array_keys_value($list, '0.id') ?>"
                           msg="Are you sure to delete ,digit mapping  <?php echo array_keys_value($list, '0.name'); ?> ?"
                           title="<?php __('Delete') ?>">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function () {

        $(".delete_digit").click(function () {
            var url = $(this).attr('url');
            var msg = $(this).attr('msg');
            bootbox.confirm(msg, function (result) {
                if (result) {
                    window.location.href = url;
                }
            });
        });

        jQuery('#objectForm').submit(function () {
            var flag = true;
            jQuery('input[rel=format_number]').map(function () {
                if (/[^0-9A-Za-z-\_\s]+/.test(jQuery(this).val())) {
                    jQuery(this).addClass('invalid');
                    jGrowl_to_notyfy('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ', {theme: 'jmsg-error'});
                    flag = false;
                }
            })


            var arr = new Array();
            $('#list_table').find('input[name*=name]').each(function () {
                arr.push($(this).val());

            });

            var arr2 = $.uniqueArray(arr);

            if (arr.length != arr2.length) {
                $('#list_table').find('select[name*=name]').each(function () {
                    jQuery(this).addClass('invalid');
                    flag = false;

                });

                jGrowl_to_notyfy('Name  Happen  Repeat.', {theme: 'jmsg-error'});
                flag = false;
            }
            return flag;
        });

        jQuery('#add').click(function () {

            jQuery('#noRows').hide();
            jQuery(".msg").hide();
            jQuery('table.list').show().trAdd({
                action: '<?php echo $this->webroot ?>digits/js_save_digits',
                ajax: '<?php echo $this->webroot ?>digits/js_save_digits',
                insertNumber: 'first',
                removeCallback: function () {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                        jQuery(".msg").show();
                    }
                }
            });
            jQuery('#DigitTranslationName').attr('mycheck', 'add', 'maxLength', '256');
        });
        jQuery('a[id=edit]').click(function () {

            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>digits/js_save_digits/' + jQuery(this).attr('list_id'),
                ajax: '<?php echo $this->webroot ?>digits/js_save_digits/' + jQuery(this).attr('list_id'),
                saveType: 'edit'
            });
            jQuery('#DigitTranslationName').attr('mycheck', 'edit', 'maxLength', '256');
        });

        <?php if (empty($mydata) && !isset($_GET['search'])): ?>
        $("#add").click();
        <?php endif; ?>
    });
</script>
