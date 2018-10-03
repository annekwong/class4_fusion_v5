<script src="<?php echo $this->webroot?>js/BubbleSort.js" type="text/javascript"></script>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Digit Mapping Detail') ?> <font class="editname" title="Name"> <?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''? '': "[".$name[0][0]['name']."]" ; ?> </font></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Digit Mapping Detail') ?> <font class="editname" title="Name"> <?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''? '': "[".$name[0][0]['name']."]" ; ?> </font></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']) {?>
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)"><i></i> Create New</a>
        <a class="link_btn btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>digits/del_all_details/<?php echo $id?>');" ><i></i> <?php echo __('Delete All')?></a>
        <a class="link_btn btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="ex_deleteSelected('tran_details_tab','<?php echo $this->webroot?>digits/del_selected_details?id='+<?php echo $id?>,'digit translation records');"><i></i> <?php echo __('Delete Selected')?></a>
    <?php  } ?>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>digits/view"><i></i> <?php echo __('Back')?> </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li  class="active"><a class="glyphicons list" href="<?php echo $this->webroot ?>digits/translation_details/<?php echo base64_encode($id)?>"><i></i> <?php __('List')?></a></li>
                <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_x']) {?>
                    <li><a class="glyphicons upload" href="<?php echo $this->webroot ?>uploads/digit_translation/<?php echo $id?>"><i></i> <?php __('Import')?></a></li>
                    <li ><a class="glyphicons download" href="<?php echo $this->webroot ?>down/digit_mapping_down/<?php echo $id?>"><i></i> <?php __('Export')?></a></li>
                <?php }?>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('prefixsearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>


            <?php $d = $p->getDataArray();if (count($d) == 0) {?>
                <h2 class="msg center" id="no_data_found"><br/><?php echo __('no_data_found')?></h2>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none">
                    <thead>
                    <tr>
                        <th rowspan="2"><input type="checkbox" onclick="checkAllOrNot(this,'tran_details_tab');" value=""/></th>
                        <th colspan="3"><?php __('ANI')?></th>
                        <th colspan="3"><?php __('DNIS')?></th>
                        <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']):?>
                            <th rowspan="2"><?php echo __('Action')?></th>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <th><?php __('Action')?></th>
                        <th> <?php echo $appCommon->show_order('ani', __('Match Prefix', true)) ?></th>
                        <th> <?php echo $appCommon->show_order('action_ani', __('Replace With', true)) ?></th>
                        <th><?php __('Action')?></th>
                        <th> <?php echo $appCommon->show_order('dnis', __('Match Prefix', true)) ?></th>
                        <th> <?php echo $appCommon->show_order('action_dnis', __('Replace With', true)) ?></th>
                    </tr>
                    </thead>
                    <tbody id="tran_details_tab">
                    </tbody>
                </table>
            <?php } else {?>
                <div class="clearfix"></div>
                <div id="cover"></div>
                <div id="uploadroute"  style="overflow:hidden;width:500px;display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
                    <form action="<?php echo $this->webroot?>digits/tran_upload/<?php echo $id?>" method="post" enctype="multipart/form-data" id="productFile">
                        <span class="wordFont1 marginSpan1"><?php echo __('selectfile')?>:</span>
                        <div style="width:100%;height:60px;" class="up_panel_upload">
                            <input style="margin-top:10px;" type="file" value="Upload" size="45" class="input in-text" id="browse" name="browse">
                            <div>
                                <input style="margin-left:50%;" type="checkbox" checked onclick="if(this.value=='false')this.value='true';else this.value='false';document.getElementById('isRoll').value=this.value;">
                                <input type="hidden" value="true" name="isRoll" id="isRoll"/>
                                <span><?php echo __('rollbackonfail')?> </span> </div>
                        </div>
                        <div class="form_panel_button_upload"> <span style="float:left"><?php echo __('downloadtempfile')?> .<a href="<?php echo $this->webroot?>products/downloadtemplate/t" style="color:red"><?php echo __('clickhere')?></a></span>
                            <input type="submit" class="input in-button" value="<?php echo __('upload')?>"/>
                            <input type="button" onclick="closeCover('uploadroute')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel')?>"/>
                        </div>
                    </form>
                </div>
                <div id="uploadroute_error"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload"> <span class="wordFont1 marginSpan1"><span style="color:red" id="affectrows"></span>&nbsp;&nbsp;<?php echo __('erroroccured')?>:</span>
                    <div style="height: auto;text-align:left;" id="route_upload_errorMsg" class="up_panel_upload"></div>
                    <div class="form_panel_button_upload"> <span style="float:left"><?php echo __('downloadtempfile')?> .<a href="<?php echo $this->webroot?>products/downloadtemplate/t" style="color:red"><?php echo __('clickhere')?></a></span>
                        <input type="button" onclick="closeCover('uploadroute_error')" style="margin-bottom:6px;" class="input in-button" value="Close"/>
                    </div>
                </div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <thead>
                    <tr>
                        <th rowspan="2"><input type="checkbox" onclick="checkAllOrNot(this,'tran_details_tab');" value="" class="checkAll"/></th>
                        <th colspan="3"><?php __('ANI')?></th>
                        <th colspan="3"><?php __('DNIS')?></th>
                        <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']):?>
                            <th rowspan="2"><?php echo __('Action')?></th>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <th><?php __('Action')?></th>
                        <th> <?php echo $appCommon->show_order('ani', __('Match Prefix', true)) ?></th>
                        <th> <?php echo $appCommon->show_order('action_ani', __('Replace With', true)) ?></th>
                        <th><?php __('Action')?></th>
                        <th> <?php echo $appCommon->show_order('dnis', __('Match Prefix', true)) ?></th>
                        <th> <?php echo $appCommon->show_order('action_dnis', __('Replace With', true)) ?></th>
                    </tr>
                    </thead>
                    <tbody id="tran_details_tab">
                    <?php
                    $mydata = $p->getDataArray();
                    $loop = count($mydata);
                    for ($i = 0;$i<$loop;$i++) {
                        ?>
                        <tr class="row-1">
                            <td><input type="checkbox" value="<?php echo $mydata[$i][0]['ref_id']?>"/></td>
                            <td>
                                <?php
                                if ($mydata[$i][0]['ani_method'] == 0) echo __('ignore');
                                else if ($mydata[$i][0]['ani_method'] == 1) echo __('Replace the Matched Portion');
                                else if ($mydata[$i][0]['ani_method'] == 2) echo __('Replace the Entire Number');
                                ?>
                            </td>
                            <td  style="font-weight: bold;"><?php echo $mydata[$i][0]['ani']?></td>
                            <td><?php echo $mydata[$i][0]['action_ani']?></td>
                            <td>
                                <?php
                                if ($mydata[$i][0]['dnis_method'] == 0) __('ignore');
                                else if ($mydata[$i][0]['dnis_method'] == 1) echo __('Replace the Matched Portion');
                                else if ($mydata[$i][0]['dnis_method'] == 2) __('Replace the Entire Number');
                                ?>
                            </td>
                            <td><?php echo $mydata[$i][0]['dnis']?></td>
                            <td><?php echo $mydata[$i][0]['action_dnis']?></td>
                            <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']) {?>
                                <td><a title="Edit" ref_id="<?php echo $mydata[$i][0]['ref_id']?>" style="float:left;margin-left:5px;" href="#<?php echo $this->webroot?>digits/edit_tran_detail/<?php echo $mydata[$i][0]['ref_id']?>"> <i class="icon-edit"></i> </a> <a title="delete"  href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>digits/del_tran_detail/<?php echo $mydata[$i][0]['ref_id'];?>/<?php echo $id?>','number translation');"> <i class="icon-remove"></i> </a></td>
                            <?php }?>
                        </tr>
                    <?php }?>
                    </tbody>
                    <tbody>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php }?>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery('#add').click(function(){
        jQuery('.msg').hide();
        jQuery('table.list').show().trAdd({
            action:'<?php echo $this->webroot?>digits/js_save/<?php echo $$hel->getPass(0) ?>',
            ajax:'<?php echo $this->webroot?>digits/js_save/<?php echo $$hel->getPass(0) ?>',
            callback:trAdd_callback,
            onsubmit:trAdd_onsubmit,
            removeCallback:function(){if(jQuery('table.list tbody tr').size()==0){jQuery('table.list').hide();jQuery('.msg').show();}}
        });
        return false;
    });
    jQuery('a[title=Edit]').click(function(){
        var ref_id=jQuery(this).attr('ref_id');
        jQuery(this).parent().parent().trAdd({
            action:'<?php echo $this->webroot?>digits/js_save/<?php echo $$hel->getPass(0) ?>/'+ref_id,
            ajax:'<?php echo $this->webroot?>digits/js_save/<?php echo $$hel->getPass(0) ?>/'+ref_id,
            saveType:'edit',
            callback:trAdd_callback,
            onsubmit:trAdd_onsubmit
        });
        return false;
    });
    function trAdd_callback(options){
        //jQuery('table.list').find('input[id!=TranslationItemAni]').xkeyvalidate({type:'Num'});
    }
    function trAdd_onsubmit(options){
        var ani =jQuery('table.list').find('input[id=TranslationItemAni]').val();
        var dnis = jQuery('table.list').find('input[id=TranslationItemDnis]').val();
        var t_ani = jQuery('table.list').find('input[id=TranslationItemActionAni]').val();
        var t_dnis = jQuery('table.list').find('input[id=TranslationItemActionDnis]').val();
        var ref_id = jQuery('table.list').find('input[id=check_repeat_ref_id]').val();
        var ani_type =jQuery('table.list').find('select[id=TranslationItemAniMethod]').val();
        var dnis_type =jQuery('table.list').find('select[id=TranslationItemDnisMethod]').val();
        if (ani_type == 1 && ani == ''){
            jQuery.jGrowlError('<?php __('When Action are Replace Matched Prefix,Match Prefix can not be null'); ?>');
            return false;
        }
        if (ani_type == 2 && t_ani == ''){
            jQuery.jGrowlError('<?php __('When Action are Replace,Replace With can not be null'); ?>');
            return false;
        }
        if (dnis_type == 1 && dnis == ''){
            jQuery.jGrowlError('<?php __('When Action are Replace Matched Prefix,Match Prefix can not be null'); ?>');
            return false;
        }

        if (dnis_type == 2 && t_dnis == ''){
            jQuery.jGrowlError('<?php __('When Action are Replace Matched Prefix,Match Prefix can not be null'); ?>');
            return false;
        }

        if(jQuery('table.list').find('input[id=TranslationItemAni]').val()=='' && jQuery('table.list').find('input[id=TranslationItemDnis]').val()=='' && jQuery('table.list').find('input[id=TranslationItemActionAni]').val()==''&&jQuery('table.list').find('input[id=TranslationItemActionDnis]').val()==''){
            jQuery.jGrowlError('The ANI, DNIS, Translated ANI,and Translated DNIS, Cannot Be Null !');
            return false;
        }
        check_ani_dnis=/^(\w|#|\+)*$/;
        if(!check_ani_dnis.test(jQuery('table.list').find('input[id=TranslationItemAni]').val())){
            jQuery.jGrowlError('ANI must be (a-z,A-Z,0-9,#,+)!');
            return false;
        }

        if(!check_ani_dnis.test(jQuery('table.list').find('input[id=TranslationItemDnis]').val())){
            jQuery.jGrowlError('DNIS must be (a-z,A-Z,0-9,#,+)!');
            return false;
        }

        if(!check_ani_dnis.test(jQuery('table.list').find('input[id=TranslationItemActionAni]').val())){
            jQuery.jGrowlError('Translated ANI must be (a-z,A-Z,0-9,#,+)!');
            return false;
        }


        if(!check_ani_dnis.test(jQuery('table.list').find('input[id=TranslationItemActionDnis]').val())){
            jQuery.jGrowlError('Translated DNIS must be (a-z,A-Z,0-9,#,+)!');
            return false;
        }

        var data=  jQuery.ajaxData("<?php echo $this->webroot?>digits/check_repeat/"+ani+"/"+dnis+"/"+t_ani+"/"+t_dnis+"/"+ref_id);
        if(!data.indexOf('false')){
            jQuery.jGrowlError("The ANI, DNIS, Translated ANI,and Translated DNIS combination cannot be duplicate!");
            return false;
        }else{
            return true;
        }
    }
    $(document).ready(function () {
        $('.checkAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });
    });
</script>
<!-- 上传文件 如果有错误信息则显示 -->
<?php
$upload_error = $session->read('upload_digit_error');
if (!empty($upload_error)) {
    $session->del('upload_digit_error');
    $affectRows = $session->read('upload_commited_rows');
    $session->del('upload_commited_rows');
    ?>
    <script type="text/javascript">
        //<![CDATA[
        //提交的行数
        document.getElementById("affectrows").innerHTML = "<?php echo $affectRows?>";
        //错误信息
        var errormsg = eval("<?php echo $upload_error?>");
        var loop = errormsg.length;
        var msg = "";
        for (var i = 1;i<=loop; i++) {
            msg += errormsg[i-1].row+"<?php echo __('row')?>"+":"+errormsg[i-1].name+errormsg[i-1].msg+"&nbsp;&nbsp;&nbsp;&nbsp;";
            if (i % 2 == 0) {
                msg += "<br/>";
            }

            if (i == loop) {
                msg += "<p>&nbsp;&nbsp;<p/>";
            }
            document.getElementById('route_upload_errorMsg').innerHTML = msg;
        }
        cover('uploadroute_error');
        //]]>
    </script>
<?php
}
?>
