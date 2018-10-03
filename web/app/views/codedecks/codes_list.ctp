<style>
    input.checkbox-left {
        display: block;
        margin: 0 auto;
    }
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Code Deck List') ?> <?php echo empty($code_name[0][0]['name']) || $code_name[0][0]['name'] == '' ? '' : '[' . $code_name[0][0]['name'] . ']' ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Code Deck List') ?> <?php echo empty($code_name[0][0]['name']) || $code_name[0][0]['name'] == '' ? '' : '[' . $code_name[0][0]['name'] . ']' ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if ($_SESSION['role_menu']['Switch']['codedecks']['model_w'] || $_SESSION['role_menu']['Switch']['codedecks']['model_x']) { ?>
        <a id="addcode" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> Create New</a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteAll('<?php echo $this->webroot ?>codedecks/del_code/all/<?php echo $code_deck_id ?>');" href="##"><i></i> <?php __('Delete All')?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="ex_deleteSelected('producttab','<?php echo $this->webroot ?>codedecks/del_code/selected/<?php echo $code_deck_id ?>','code deck list');" href="##"><i></i> <?php __('deleteselected')?></a>
    <?php  } ?>
    <a class="btn btn-icon glyphicons btn-inverse circle_arrow_left" href="<?php echo $this->webroot; ?>codedecks/codedeck_list">
        <i></i>
        <?php __('Back'); ?>
    </a>
</div>
<div class="clearfix"></div>


<div id="uploadcode"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
    <form action="<?php echo $this->webroot ?>codedecks/upload_code/<?php echo $code_deck_id ?>" method="post" enctype="multipart/form-data" id="productFile">
        <span class="wordFont1 marginSpan1"><?php echo __('selectfile') ?>:</span>
        <div style="height: 100px;" class="up_panel_upload">
            <input style="margin-top:10px;" type="file" value="Upload" size="45" class="input in-text" id="browse" name="browse">
            <div style="margin-top:20px;">
                <input type="radio" title="<?php echo __('upload_overwrite') ?>" checked value="1" name="handleStyle">
                <span><?php echo __('overwrite') ?></span>
                <input style="margin-left:10px;" type="radio" title="<?php echo __('upload_remove') ?>" value="2" name="handleStyle">
                <span><?php echo __('remove') ?></span>
                <input style="margin-left:10px;" type="radio" title="<?php echo __('upload_refresh') ?>" value="3" name="handleStyle">
                <span><?php echo __('clearrefresh') ?></span>
                <input style="margin-left:10px;" type="checkbox" checked onclick="if(this.value=='false')this.value='true';else this.value='false';document.getElementById('isRoll').value=this.value;">
                <input type="hidden" value="true" name="isRoll" id="isRoll"/>
                <span><?php echo __('rollbackonfail') ?> </span>
            </div>
        </div>
        <div class="form_panel_button_upload">
            <span style="float:left"> <?php echo __('downloadtempfile') ?><a href="<?php echo $this->webroot ?>products/downloadtemplate/f" style="color:red"><?php echo __('clickhere') ?></a></span>
            <input type="submit" class="input in-button" value="<?php echo __('upload') ?>"/>
            <input type="button" onclick="closeCover('uploadcode')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel') ?>"/>
        </div>
    </form>
</div>
<div id="uploadcode_error"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
    <span class="wordFont1 marginSpan1"><span style="color:red" id="affectrows"></span>&nbsp;&nbsp;<?php echo __('erroroccured') ?>:</span>
    <div style="height: auto;text-align:left;" id="code_upload_errorMsg" class="up_panel_upload"></div>
    <div class="form_panel_button_upload">
        <span style="float:left"><?php echo __('downloadtempfile') ?> .<a href="<?php echo $this->webroot ?>products/downloadtemplate/f" style="color:red"><?php echo __('clickhere') ?></a></span>
        <input type="button" onclick="closeCover('uploadcode_error')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('close') ?>"/>
    </div>
</div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active"><a href="<?php echo $this->webroot ?>codedecks/codes_list/<?php echo base64_encode($code_deck_id); ?>" class="glyphicons list"><i></i><?php __('Code Deck List'); ?></a></li>
                <?php if ($_SESSION['role_menu']['Switch']['codedecks']['model_w'] || $_SESSION['role_menu']['Switch']['codedecks']['model_x']) { ?>
                    <li><a href="<?php echo $this->webroot ?>uploads/code_deck/<?php echo base64_encode($code_deck_id); ?>" class="glyphicons upload"><i></i><?php __('Import'); ?></a></li>
                    <li><a href="<?php echo $this->webroot ?>down/code_deck/<?php echo base64_encode($code_deck_id); ?>" class="glyphicons download"><i></i><?php __('Export'); ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label><?php __('Code')?>:</label>
                        <input type="text" name="search" id="search-_q" value="<?php if(isset($search)) echo $search; ?>"/>
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                </form>
            </div>
            <?php $d = $p->getDataArray();
            if (count($d) == 0) : ?>
                <h2 class="msg center" id="no_data_found" style="display:<?php echo (count($d) == 0) ? 'block' : 'none' ?>"><?php  echo __('no_data_found') ?></h2>
            <?php endif; ?>
            <div class="clearfix"></div>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display: <?php echo (count($d) == 0) ? 'none' : ' ' ?>" id="listData" >
                <thead>
                <tr>
                    <?php if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) : ?>
                        <th style="padding: 5px;"><input type="checkbox" class="checkAll checkbox-left" value=""/></th>
                    <?php endif; ?>
                    <th><?php echo $appCommon->show_order('code', __('Code', true)) ?></th>
                    <th><?php echo $appCommon->show_order('codenames', __('Code Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('country', __('Country', true)) ?></th>
                    <?php if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) { ?>
                        <th class="last"><?php echo __('Action') ?></th>
                    <?php } ?>

                </tr>
                </thead>
                <tbody id="producttab">
                <?php
                $mydata = $p->getDataArray();
                $loop = count($mydata);
                for ($i = 0; $i < $loop; $i++) : ?>
                    <tr class="row-1">
                        <?php if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) : ?>
                            <td><input type="checkbox" class="checkbox-left" value="<?php echo $mydata[$i][0]['code_id'] ?>"/></td>
                        <?php endif; ?>
                        <td style="font-weight: bold;"><?php echo $mydata[$i][0]['code'] ?></td>
                        <td><?php echo $mydata[$i][0]['name'] ?></td><td><?php echo $mydata[$i][0]['country'] ?></td>

                        <?php if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) { ?>
                            <td>
                                <a class="edit" title="<?php echo __('edit') ?>"id="<?php echo $mydata[$i][0]['code_id'] ?>" href="<?php echo $this->webroot ?>codedecks/edit_code/<?php echo $mydata[$i][0]['code_id'] ?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <a title="<?php echo __('del') ?>" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot ?>codedecks/del_code/<?php echo $mydata[$i][0]['code_id'] ?>/<?php echo $code_deck_id ?>','code deck  <?php echo $mydata[$i][0]['code'] ?>');">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php endfor; ?>
                </tbody>
                <tbody>
                </tbody>
            </table>
            <div class="row-fluid separator" style="display:<?php echo (count($d) == 0) ? 'none' : 'block' ?>">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
<!-- 上传文件 如果有错误信息则显示 -->
<?php
$upload_error = $session->read('upload_route_error');
if (!empty($upload_error)) {
    $session->del('upload_route_error');
    $affectRows = $session->read('upload_commited_rows');
    $session->del('upload_commited_rows');
    ?>
    <script language=JavaScript>
        //提交的行数
        document.getElementById("affectrows").innerHTML = "<?php echo $affectRows ?>";
        //错误信息
        var errormsg = eval("<?php echo $upload_error ?>");
        var loop = errormsg.length;
        var msg = "";
        for (var i = 1;i<=loop; i++) {
            msg += errormsg[i-1].row+"<?php echo __('row') ?>"+" : "+errormsg[i-1].name+errormsg[i-1].msg+"&nbsp;&nbsp;&nbsp;&nbsp;";
            if (i % 2 == 0) {
                msg += "<br/>";
            }
            if (i == loop) {
                msg += "<p>&nbsp;&nbsp;<p/>";
            }
            document.getElementById('code_upload_errorMsg').innerHTML = msg;
        }
        cover('uploadcode_error');
    </script>
    <?php
}
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#addcode').click(
            function(){
                var action="<?php echo $this->webroot ?>codedecks/add_code";
                jQuery('table.list').trAdd({
                    action:action,
                    insertNumber:'first',
                    ajax:'<?php echo $this->webroot ?>codedecks/js_code_save?id=<?php echo $code_deck_id ?>',
                    onsubmit:function(options){return jsAdd.onsubmit(options);}
                });
                jQuery('#Country').autocomplete({source:'<?php echo $this->webroot ?>codedecks/ajax_options_ext?type=country',width:'auto'});
                jQuery('#no_data_found').attr('style','display:none');
                jQuery('#listData').attr('style','display:  ');
                return false;
            });

        //修改方法```
        jQuery('.edit').click(
            function(){
                var id=jQuery(this).attr('id');
                var action="<?php echo $this->webroot ?>codedecks/edit_code/"+id;
                jQuery(this).parent().parent().trAdd(
                    {
                        ajax:"<?php echo $this->webroot ?>codedecks/js_code_save/"+id,
                        action:action,
                        saveType:'edit',
                        onsubmit:function(options){return jsAdd.onsubmit(options);}
                    }
                );
                jQuery('#Country').autocomplete({source:'<?php echo $this->webroot ?>codedecks/ajax_options?type=country',width:'auto'});
                return false;
            }
        );


    });
</script>
<script type="text/javascript">
    var jsAdd={
        onsubmit:function(options){
            var re=true;
            var tr=jQuery('#'+options.log);
            var code=tr.find('#code').val();
            //	  var name=tr.find('#name').val();
            //	  var country = tr.find('#Country').val();
            //	 if(name==''){jQuery.jGrowlError('Code Name is required！');re=false;}else if(/[^0-9A-Za-z-\_\.]+/.test(name)||name.length>16){jQuery.jGrowlError(': Code Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ');re=false;}
            //	 if(/[^0-9A-Za-z-\_\.\s]+/.test(country)||country.length>50){jQuery.jGrowlError(' Country, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 50 characters in length! ');re=false;}
            if(/[^0-9A-Za-z-\_\.\s]+/.test(name)||name.length>16){jQuery.jGrowlError(' Code Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 50 characters in length! ');re=false;}
            if(code==''){
                jQuery.jGrowlError('Code cannot be null!');
                re=false;
            }	else if(/\D/.test(code)){
                jQuery.jGrowlError('Code Only a number!');
                re=false;
            }
            return re;
        }
    }
</script>

<script type="text/javascript">
    /*
     jQuery(document).ready(function(){
     jQuery('#add').click(function(){
     jQuery('table.list').trAdd({});
     });
     });
     */
</script>
<script>
    $(document).ready(function () {
        $('.checkAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });
    });
</script>

