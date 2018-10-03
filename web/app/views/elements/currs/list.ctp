<div>
    <div class="clearfix"></div>
    <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">

        <thead>
            <tr>
    <!--    <td>
                <?php echo $appCommon->show_order('currency_id', __('ID', true)); ?>
        </th>-->
                <th>
                    <?php echo $appCommon->show_order('code', __('Name', true)); ?>
                </th>
                <th>
                    <?php echo $appCommon->show_order('rate', __('Rates', true)); ?>
                </th>
                <th>
                    <?php echo $appCommon->show_order('last_modify', __('Last Updated', true)); ?>
                </th>
                <th>
                    <?php echo $appCommon->show_order('usage', __('Usage Count', true)); ?>
                </th>
                <th><?php __('Update By') ?></th>
                <?php
                if ($_SESSION['role_menu']['Switch']['currs']['model_w'])
                {
                    ?>
                    <th>
                        <?php echo __('Active'); ?>
                    </th>

                    <th><?php echo __('Action') ?></th><?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($this->data)): ?>
                <?php
                foreach ($this->data as $list)
                {
                    ?>
                    <tr>
            <!--		<td><?php echo $list['Curr']['currency_id'] ?></td>-->
                        <td><?php echo $list['Curr']['code'] ?></td>

                        <td style="color:green"><?php echo round($list['Curr']['rate'], 4); ?></td>
                        <td><?php echo $list['Curr']['last_modify'] ?></td>
                        <td>
                            <a href="<?php echo $this->webroot ?>rates/rates_list?search_currency=<?php echo $list['Curr']['currency_id'] ?>&advsearch=1">
                                <?php echo $list['Curr']['rates'] ?>
                            </a>
                        <td><?php echo $list['Curr']['update_by'] ?></td>
                        </td>
                        <?php
                        if ($_SESSION['role_menu']['Switch']['currs']['model_w'])
                        {
                            ?>
                            <td><?php $config_flg = isset($is_configuration); echo $appCurrs->active($list,$config_flg) ?></td>

                            <td>
                                <a class="history"     href="<?php echo $this->webroot ?>currs/history/<?php echo $list['Curr']['currency_id'] ?>" title="<?php __('View change history') ?>">
                                    <i class="icon-money"></i>
                                </a>
                                <?php if (!isset($is_configuration)): ?>
                                <a title="<?php echo __('edit') ?>" class="edit" href="<?php echo $this->webroot ?>currs/edit/<?php echo $list['Curr']['currency_id'] ?>" currency_id="<?php echo $list['Curr']['currency_id'] ?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <?php endif; ?>
                                <a  title="<?php echo __('del') ?>" href="javascript:void(0)" onclick="ex_delConfirm(this, '<?php echo $this->webroot ?>currs/del_currency/<?php echo $list['Curr']['currency_id'] ?>', 'currency <?php echo$list['Curr']['code'] ?>');">
                                    <i class="icon-remove"></i>
                                </a>
                            </td><?php } ?>
                    </tr>
                <?php } ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if (!isset($is_configuration)): ?>
        <div class="row-fluid">
            <div class="pagination pagination-large pagination-right margin-none">
                <?php echo $this->element('xpage'); ?>
            </div> 
        </div>
    <?php endif; ?>
    <div class="clearfix"></div>
</div>

<script type="text/javascript">
<?php if (isset($is_configuration)): ?>
        $(function () {
//            $("div.navbar").hide();
        });
<?php endif; ?>
    jQuery.fn.active = function () {

        jQuery(this).addClass('active');
        jQuery(this).click(function () {

            if (myconfirm("Are you sure you would like to deactivate the selected currency?", this)) {

            }

            return false;
        });
        return jQuery(this);
    }
    jQuery.fn.unactive = function () {
        jQuery(this).unbind('click').remove('active');
        return jQuery(this);
    }
    jQuery.fn.disabled = function () {

        jQuery(this).click(function () {
            if (myconfirm(" Are you sure you would like to active the selected currency?", this)) {

            }

            return false;
        });
        return jQuery(this);

    }
    jQuery.fn.undisabled = function () {
        jQuery(this).unbind('click').remove('disabled');
        return jQuery(this);
    }
    jQuery(document).ready(
            function () {
                jQuery('.active').active();
                jQuery('.disabled').disabled();

                $(".disabled").click(function () {
                    return myconfirm(" Are you sure you would like to activate the selected currency?", this);
                });
            }
    );
</script>
<script type="text/javascript">
    //,
    //onsubmit:function(options){
    // var re =true;
    // re=jsAdd.onsubmit(options);
    // if(jQuery('#CurrCode').val()!=''){
    //var data=jQuery.ajaxData("<?php echo $this->webroot ?>currs/check_repeat_name/"+jQuery('#CurrCode').val()+"/"+currency_id);
    //if(!data.indexOf("false")){
    //jQuery.jGrowlError(jQuery('#CurrCode').val()+" is already in use! ");
    //re=false;
    //					}
    //   	}
    //return re;
    //
    //}
    jQuery('.edit').click(
            function () {
                var action = jQuery(this).attr('href');
                var currency_id = jQuery(this).attr('currency_id');
                jQuery(this).parent().parent().trAdd(
                        {
                            ajax: "<?php echo $this->webroot ?>currs/js_save/" + currency_id,
                            action: action,
                            saveType: 'edit',
                            onsubmit: function (options) {
                                var code = $("#code").val();
                                var rate = $("#rate").val();
                                if (!/^[0-9a-zA-Z\-_.\s]{1,100}$/.test(code)) {
                                    jQuery.jGrowlError('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ');
                                    return false;
                                }
                                return true;
                            }
                        }
                );
                return false;
            }
    );
    jQuery('.history').click(
            function () {
                if (jQuery(this).attr('history') != "true") {
                    jQuery(this).attr('history', "true");
                    var td = jQuery('<tr/>').append('<td colspan=7>').insertAfter(jQuery(this).parent().parent()).find('td:nth-child(1)');
                    var href = jQuery(this).attr('href');
                    jQuery.get(href, function (data) {
                        td.append(data)
                    });
                } else {
                    jQuery(this).removeAttr('history');
                    jQuery(this).parent().parent().next().remove();
                }
                return false;
            }
    );

    $(function () {
<?php if (!count($this->data)): ?>
            $("#add").click();
<?php endif; ?>

    });

    $(document).on('DOMNodeInserted', function(){
        $('tbody a.disabled').attr('title', 'Activate');
    });
</script>




