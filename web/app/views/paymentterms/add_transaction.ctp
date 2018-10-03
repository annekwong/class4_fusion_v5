<style>
    .list1 td{ line-height:2;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Transaction Fee') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Add Transaction Fee')?></h4>
     <div class="buttons pull-right">
        <?php echo $this->element('xback', Array('backUrl' => 'paymentterms/all_transaction_fee')) ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div id="container">
                <form method="post" id="addForm">
                    <table class="form list1 table dynamicTable tableTools table-bordered  table-white">
                        <tr>
                            <td width="50%" style="text-align:right;">
                                <?php __('Name')?>:
                            </td>
                            <td width="50%">
                                <input id="name" type="text" name="name">
                            </td>
                        </tr>

                        <tr>

                            <td  width="50%" style="text-align:right;">
                                <?php __('Default')?>:
                                </td>
                            <td width="50%">
                                <select id="default" name="is_default">
                                    <option value="t"><?php __('True')?></option>
                                    <option value="f"><?php __('False')?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="center">
                                <br/>
                                <input type="submit" value="<?php __('submit')?>" class="btn btn-primary">
                                <a href="<?php echo $this->webroot; ?>paymentterms/add_transaction"><input type="button" value="<?php __('reset')?>" class="btn btn-default"></a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {
        $("#addForm").submit(function() {
            var name = $("#name").val();
            var is_default = $("#default").val();


            if (name == '') {
                jGrowl_to_notyfy("This Transaction Fee can not be empty!", {theme: 'jmsg-error'});
                return false;
            }

            var flag = false;
            $.ajax({
                'url': '<?php echo $this->webroot ?>paymentterms/check_transaction/' + name + "/" + is_default,
                'type': 'POST',
                'dataType': 'text',
                'data': {},
                'async': false,
                'success': function(data) {
                    if (data == 'name_no') {
                        flag = false;
                        jGrowl_to_notyfy("This Transaction Fee already exists!", {theme: 'jmsg-error'});
                    } else if (data == 'default_no') {
                        flag = false;
                        jGrowl_to_notyfy("Default Transaction Fee already exists!", {theme: 'jmsg-error'});

                    } else if (data == 'yes') {
                        flag = true;
                    }
                }
            });

            return flag;
        });
    });
</script>


