<?php $d = $p->getDataArray(); ?>
<?php $w = $session->read('writable'); ?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>codedecks/codedeck_list">
        <?php __('Switch') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>codedecks/codedeck_list">
        <?php echo __('Code Deck') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Code Deck') ?></h4>

</div>

<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    if ($_SESSION['role_menu']['Switch']['codedecks']['model_w'])
    {
        ?>
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>codedecks/add_codedeck"><i></i> <?php __('Create New') ?></a>

        <?php if (count($d) > 0): ?>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteAll('<?php echo $this->webroot ?>codedecks/del_code_deck/all');" href="##"><i></i> <?php __('Delete All') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="ex_deleteSelected('producttab', '<?php echo $this->webroot ?>codedecks/del_code_deck/selected', 'code deck');"
           href="javascript:void(0)">
            <i></i>
            <?php __('deleteselected') ?>
        </a>
    <?php endif; ?>
    <?php } ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php
            if (count($d) == 0)
            {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
            <?php
            }
            else
            {
                ?>
                <div class="msg"  id="msg_div"  style="display: none;"><?php echo __('no_data_found') ?></div>
            <?php } ?>
            <?php
            if (count($d) == 0)
            {
            ?>
            <div  id="list_div"  style="display: none;">
                <?php
                }
                else
                {
                ?>
                <div id="list_div">
                    <?php } ?>

                    <div id="addcodedeck" style="display:none;background:buttonface;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:130px;border:2px solid lightgray;">
                        <div style="background:lightblue;width:100%;height:25px;font-size: 16px;"><?php echo __('newcodedeck') ?></div>
                        <div style="margin-top:10px;margin-left:10px;">
                            <p><?php echo __('codedeckname') ?>:<input class="input in-text" id="cname"/></p>
                        </div>
                        <div style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
                            <input type="button" onclick="add('cname', '<?php echo $this->webroot ?>codedecks/add_codedeck');" value="<?php echo __('submit') ?>" class="input in-button">
                            <input type="button" onclick="closeCover('addcodedeck');" value="<?php echo __('cancel') ?>" class="input in-button">
                        </div>
                    </div>
                    <div id="editcodedeck" style="display:none;background:buttonface;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:130px;border:2px solid lightgray;">
                        <div style="background:lightblue;width:100%;height:25px;font-size: 16px;"><?php echo __('editcodedeck') ?></div>
                        <div style="margin-top:10px;margin-left:10px;">
                            <p><?php echo __('codedeckname') ?>:<input class="input in-text" id="cname_e"/></p>
                        </div>
                        <div style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
                            <input type="button" onclick="add(['cname_e', 'codedeckid'], '<?php echo $this->webroot ?>codedecks/edit_codedeck');" value="<?php echo __('submit') ?>" class="input in-button">
                            <input type="button" onclick="closeCover('editcodedeck');" value="<?php echo __('cancel') ?>" class="input in-button">
                        </div>
                    </div>
                    <input type="hidden" value="" id="codedeckid"/>
                    <div class="clearfix"></div>

                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                        <thead>
                        <tr>
                            <?php
                            if ($_SESSION['role_menu']['Switch']['codedecks']['model_w'])
                            {
                                ?><th><input type="checkbox"  class="checkAll"  value=""/></th>
                            <?php } ?>
                            <!--		<th>
<?php echo $appCommon->show_order('code_deck_id', __('ID', true)) ?>
                                                 </th>-->
                            <th>
                                <?php echo $appCommon->show_order('name', __('Name', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('codes', __('Code Count', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('usage', __('Usage Count', true)) ?>
                            </th>
                            <th><?php echo __('Update At', true); ?></th>
                            <th><?php echo __('Update By', true); ?></th>
                            <?php
                            if ($_SESSION['role_menu']['Switch']['codedecks']['model_w'])
                            {
                                ?><th class="last"><?php echo __('Action') ?></th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody id="producttab">
                        <?php
                        $mydata = $p->getDataArray();
                        $loop = count($mydata);
                        for ($i = 0; $i < $loop; $i++)
                        {
                            ?>
                            <tr class="row-1">
                                <?php if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']): ?>

                                    <td>
                                        <?php if ($mydata[$i][0]['code_deck_id'] != 1): ?>
                                            <input type="checkbox" value="<?php echo $mydata[$i][0]['code_deck_id'] ?>"/>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <!--				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['code_deck_id'] ?></td>-->
                                <td style="font-weight: bold;"><a style="width:80%;display:block" href="<?php echo $this->webroot ?>codedecks/codes_list/<?php echo base64_encode($mydata[$i][0]['code_deck_id']) ?>" class="link_width"><?php echo $mydata[$i][0]['name'] ?></a></td>


                                <td>

                                    <?php echo $mydata[$i][0]['codes'] ?>

                                </td>
                                <td>
                                    <?php
                                    if ($mydata[$i][0]['usage'])
                                    {
                                        ?>
                                        <a style="width:80%;display:block" href="<?php echo $this->webroot ?>rates/rates_list?search_code_deck=<?php echo $mydata[$i][0]['code_deck_id'] ?>" class="link_width" >
                                            <?php echo $mydata[$i][0]['usage'] ?></a>
                                    <?php
                                    }
                                    else
                                    {
                                        ?>
                                        0
                                    <?php } ?>
                                </td>
                                <td>

                                    <?php echo $mydata[$i][0]['update_at'] ?>

                                </td>
                                <td>

                                    <?php echo $mydata[$i][0]['update_by'] ?>

                                </td>
                                <?php
                                if ($_SESSION['role_menu']['Switch']['codedecks']['model_w'])
                                {
                                    ?>
                                    <td class="last">
                                        <?php
                                        if ($w == true)
                                        {
                                            ?><a title="<?php echo __('edit') ?>" id="<?php echo $mydata[$i][0]['code_deck_id'] ?>" style="" class="edit" href="<?php echo $this->webroot ?>codedecks/edit_codedeck" >

                                                <i class="icon-edit"></i>
                                            </a>
                                            <?php if ($mydata[$i][0]['code_deck_id'] != 1): ?>
                                            <a title="<?php echo __('del') ?>"  href="javascript:void(0)" onclick="ex_delConfirm(this, '<?php echo $this->webroot ?>codedecks/del_code_deck/<?php echo $mydata[$i][0]['code_deck_id'] ?>', 'code deck  <?php echo $mydata[$i][0]['name'] ?>');">
                                                <i class="icon-remove"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tbody>
                        </tbody>
                    </table>
                    <?php #增加国家****************************************    ?>
                    <div id="country" style="display:none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 350px; top: 319px; left: 461px;" class="ui-dialog ui-widget ui-widget-content ui-corner-all  ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-labelledby="ui-dialog-title-dialog-form"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" unselectable="on" style="-moz-user-select: none;"><span class="ui-dialog-title" id="ui-dialog-title-dialog-form" unselectable="on" style="-moz-user-select: none;">&nbsp;</span><a href="#" id="close" class="ui-dialog-titlebar-close ui-corner-all" role="button" unselectable="on" style="-moz-user-select: none;"><span class="ui-icon ui-icon-closethick" unselectable="on" style="-moz-user-select: none;">close</span></a></div><div id="dialog-form" class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 0px; height: 121px;">
                            <form action="<?php echo $this->webroot ?>codedecks/add_code_country" method="get" id="countryForm">
                                <fieldset>
                                    <label for="country"><?php echo __('Add Country', true); ?></label><br>
                                    <input type="text" class="text ui-widget-content ui-corner-all input in-input in-text" value="" id="addcoun" name="country">
                                </fieldset>
                                <fieldset style="text-align: center;">
                                    <button id="create-country" type="submit" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text"><?php echo __('submit', true); ?></span></button> <button id="create-cancel" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text"><?php echo __('cancel', true); ?></span></button>
                                </fieldset>
                            </form>
                            <p class="validateTips"></p>
                        </div>
                        <div class="ui-resizable-handle ui-resizable-n" unselectable="on" style="-moz-user-select: none;"></div>
                        <div class="ui-resizable-handle ui-resizable-e" unselectable="on" style="-moz-user-select: none;"></div><div class="ui-resizable-handle ui-resizable-s" unselectable="on" style="-moz-user-select: none;"></div>
                        <div class="ui-resizable-handle ui-resizable-w" unselectable="on" style="-moz-user-select: none;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se" style="z-index: 1001; -moz-user-select: none;" unselectable="on"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 1002; -moz-user-select: none;" unselectable="on"></div>
                        <div class="ui-resizable-handle ui-resizable-ne" style="z-index: 1003; -moz-user-select: none;" unselectable="on"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 1004; -moz-user-select: none;" unselectable="on"></div>
                    </div>
                    <?php #＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊   ?>
                    <div class="row-fluid separator">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        $('.checkAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });

        jQuery('#add').click(
            function() {
                jQuery('#list_div').show();
                jQuery('#msg_div').remove();
                var action = jQuery(this).attr('href');
                jQuery('table.list').trAdd({
                    action: action,
                    insertNumber: 'first',
                    ajax: '<?php echo $this->webroot ?>codedecks/js_save',
                    onsubmit: function(options) {
                        return jsAdd.onsubmit(options);
                    }
                });
                return false;
            }
        );


        //修改方法```
        jQuery('.edit').click(
            function() {
                var id = jQuery(this).attr('id');
                var action = jQuery(this).attr('href') + "/" + id;
                jQuery(this).parent().parent().trAdd(
                    {
                        ajax: "<?php echo $this->webroot ?>codedecks/js_save/" + id,
                        action: action,
                        saveType: 'edit',
                        onsubmit: function(options) {
                            return jsAdd.onsubmit(options);
                        }
                    }
                );
                return false;
            }
        );
    });
</script>

<script type="text/javascript">
    var jsAdd = {
        onsubmit: function(options) {
            var tr = jQuery('#' + options.log);
            var flg =  tr.find('#CodedeckName').validationEngine('validate');
            if(flg){
                return false;
            }
            return true;
        }
    }
</script>




