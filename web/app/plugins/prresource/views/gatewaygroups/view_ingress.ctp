<style type="text/css">
    .analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
</style>
<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>
<?php $d = $p->getDataArray(); ?>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if (isset($_GET['query']['id_clients'])): ?>
        <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress">
            <?php __('Management') ?></a></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress">
            <?php echo __('Carrier') . ' [' . $c[$_GET['query']['id_clients']] . '] ';?></li>
    <?php else:?>
        <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress">
        <?php __('Routing') ?></a></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress">
        <?php __('Ingress Trunk') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Ingress Trunk') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if($_SESSION['login_type'] != 2): ?>
        <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
        {
            ?>
            <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" id="add" href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_resouce_ingress?<?php echo $this->params['getUrl'] ?>">
                <i></i> <?php echo __('addvoipgateway') ?>
            </a>
            <?php if (count($d) > 0): ?>
            <a  class="link_btn btn btn-primary btn-icon glyphicons remove"rel="popup" class="link_btn" href="javascript:void(0)" onclick="deleteSelected('list', '<?php echo $this->webroot ?>prresource/gatewaygroups/del_selected?type=view_ingress', 'ingress trunk');">
                <i></i> <?php echo __('deleteselected') ?>
            </a>
        <?php endif; ?>
        <?php } ?>
    <?php endif; ?>

    <?php if (isset($_GET['viewtype']) && $_GET['viewtype'] == 'client')
        {
            ?>
            <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>clients/index">
                <i></i><?php echo __('goback', true); ?>
            </a>
        <?php } ?>
    </div>
    <div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
<?php if (isset($_GET['viewtype']) && $_GET['viewtype'] == 'client')
{
    ?>
                    <li><a class="glyphicons coffe_cup" href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($_GET ['query'] ['id_clients']) ?>?<?php echo $this->params['getUrl'] ?>"><i></i> <?php __('basicinfo') ?></a></li>   
                    <li><a class="glyphicons right_arrow"  href="###" onclick="javascript:(window.location.href= $(this).attr('url'));" url="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?<?php echo $this->params['getUrl'] ?>?>&viewtype=client"><i></i> <?php __('egress') ?></a></li> 
                    <li class="active"><a class="glyphicons left_arrow"  href="###" onclick="javascript:(window.location.href= $(this).attr('url'));" url="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?<?php echo $this->params['getUrl'] ?>?>&viewtype=client"><i></i><?php __('ingress') ?></a></li> 
<?php
}
else
{
    ?>
                    <li><a class="glyphicons right_arrow"  href="###" onclick="javascript:(window.location.href= $(this).attr('url'));" url="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?<?php echo $this->params['getUrl'] ?>"><i></i> <?php __('egress') ?></a></li> 
                    <li class="active"><a class="glyphicons left_arrow"  href="###" onclick="javascript:(window.location.href= $(this).attr('url'));" url="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress"><i></i><?php __('ingress') ?></a></li> 
<?php } ?>


            </ul>
        </div>
        <div class="widget-body">
            <form method="get">
                <div class="filter-bar">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search') ?>..." 
                               value="<?php
                               if (isset($searchkey))
                               {
                                   echo $searchkey;
                               }
                               else
                               {
                                   echo __('pleaseinputkey');
                               }
                               ?>"  onclick="this.value = ''" name="search">
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label><?php __('Status')?>:</label>
                        <select name="status" >
                            <option value=""><?php __('All')?></option>
                            <option value="1" <?php if(isset($_GET['status']) && $_GET['status'] == '1'){ echo "selected='selected'";} ?>><?php __('Active')?></option>
                            <option value="2" <?php if(isset($_GET['status']) && $_GET['status'] == '2'){ echo "selected='selected'";} ?>><?php __('Inactive')?></option>
                        </select>
                    </div>
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->

                </div>
                <div class="clearfix"></div>
            </form>
            <div class="clearfix"></div>


            <dl id="edit_ip" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:auto;">
                <dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('register') ?></dd>
                <dd style="margin-top:10px;">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('username') ?></span>:<input id="ip_username" name="ip_username" style="height:20px;width:200px;float:right">
                    <input id="ip_id" style="display:none"/>
                </dd>
                <dd style="margin-top:20px;">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('password') ?></span>:<input id="ip_pass" name="ip_pass" style="height:20px;width:200px;float:right">
                </dd>
                <dd style="margin-top:10px; margin-left:26%;width:150px;height:auto;">
                    <input type="button" onclick="updateIp();" value="<?php echo __('submit') ?>" class="input in-button">
                    <input type="button" onclick="closeCover('edit_ip');" value="<?php echo __('cancel') ?>" class="input in-button">
                </dd>
            </dl>
            <?php if($_SESSION['login_type'] != 2): ?>
            <div class="tabsbar">
                <ul class="tabs">
                    <li class="hover  glyphicons list active"><a hit="" onclick="second_tab_change('list', this);
                            return false;" href="#"><i></i> <?php __('Ingress List')?></a></li> 
                                                                <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x'])
                                                                {
                                                                    ?>
                        <li class="glyphicons no-js file_import"><a hit="myfile" show_type="10" onclick="second_tab_change('ingress_import', this);
                                return false;" href="#<?php echo $this->webroot ?>uploads/ingress"><i></i><?php echo __('Ingress Import', true); ?></a></li>
                        <li class="glyphicons no-js file_export"><a hit="" onclick="second_tab_change('ingress_export', this);
                                return false;" href="#<?php echo $this->webroot ?>downloads/ingress"><i></i><?php echo __('Ingress Export', true); ?></a></li>      
                        <li class="glyphicons no-js file_import"><a hit="myfile2" show_type="9" onclick="second_tab_change('import_host', this);
                                return false;" href="#<?php echo $this->webroot ?>uploads/ingress_host"><i></i> <?php echo __('Import Host', true); ?></a></li> 
                        <li class="glyphicons no-js file_export"><a onclick="second_tab_change('export_host', this);
                                return false;" href="#<?php echo $this->webroot ?>downloads/ingress_host"> <i></i><?php echo __('Export Host', true); ?></a></li>   
                        <li class="glyphicons no-js file_import"><a hit="myfile3" show_type="8" onclick="second_tab_change('ingress_import_action', this);
                                return false;" href="#<?php echo $this->webroot ?>uploads/ingress_action"><i></i> <?php echo __('Import Action', true); ?></a></li> 
                        <li class="glyphicons no-js file_export"><a hit="" onclick="second_tab_change('ingress_export_action', this);
                                return false;" href="#<?php echo $this->webroot ?>downloads/ingress_action"><i></i> <?php echo __('Export Action', true); ?></a></li>
                        <li class="glyphicons no-js file_import"><a hit="myfile4" show_type="6" onclick="second_tab_change('ingress_import_mapping', this);
                                return false;" href="#<?php echo $this->webroot ?>uploads/ingress_tran"><i></i> <?php echo __('Import Digit Mapping', true); ?> </a></li> 
                        <li class="glyphicons no-js file_export"><a hit="" onclick="second_tab_change('ingress_export_mapping', this);
                                return false;" href="#<?php echo $this->webroot ?>downloads/ingress_tran"><i></i> <?php echo __('Export Digit Mapping', true); ?> </a></li>
                <?php } ?>
                </ul>
            </div>

            <div id="list"  style="display:none" class='second_tab'>
                <?php echo $this->element("ingress_list") ?>
            </div>
            <div id="import_host" style="display:none" class='second_tab'>
                <?php echo $this->element("ingress_import_host") ?>
            </div>
            <div id="export_host" style="display:none" class='second_tab'>
                <?php echo $this->element("ingress_export_host") ?>
            </div>
            <div id="ingress_import_action" style="display:none" class='second_tab'>
                <?php echo $this->element("ingress_import_action") ?>
            </div>
            <div id="ingress_export_action" style="display:none" class='second_tab'>
                <?php echo $this->element("ingress_export_action") ?>
            </div>
            <div id="ingress_import_mapping" style="display:none" class='second_tab'>
                <?php echo $this->element("ingress_import_mapping") ?>
            </div>
            <div id="ingress_export_mapping" style="display:none" class='second_tab'>
                <?php echo $this->element("ingress_export_mapping") ?>
            </div>
            <div id="ingress_import" style="display:none" class='second_tab'>
<?php echo $this->element("ingress_import") ?>
            </div>
            <div id="ingress_export" style="display:none" class='second_tab'>
<?php echo $this->element("ingress_export") ?>
            </div>
            <?php else: ?>
                <div id="list"  style="display:none" class='second_tab'>
                    <?php echo $this->element("ingress_list") ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>   
<script type="text/javascript">
    jQuery('#list').show();
    var $ckb = [];
    var $form = $('#ingress_export').find('form');
    $form.append('<div id="trunks"></div>');
    $form.prepend('<div id="ids_list"></div>');
    function second_tab_change(id, obj) {
        var i = 0;
        $ckb = [];
        if (id == 'ingress_export') {
            $('#trunks').html('');
            $('#ids_list').html('');
            $('#list input:checkbox:checked').each(function(){
                $ckb[i] = $(this).val();
                i++;
            });
            if ($ckb.length>0){
                $('#ids_list').html('<h3 align="center">Selected ids: '+$ckb+'</h3>');
            }
            for (i=0;i<$ckb.length;i++) {
                $('#trunks').append('<input type="hidden" name="trunks[]" value="'+$ckb[i]+'">');
            }
        }
        jQuery('.second_tab').hide();
        jQuery('#' + id).show();
        jQuery(obj).parent().parent().find('li').removeClass('hover');
        jQuery(obj).parent().addClass('hover');
    }

    $(function() {
        $('.selectAll').live('change', function(){
            var checkState = $(this).prop('checked');
            $('#send_rate_form').find('input[type="checkbox"]').prop('checked',checkState);
        });

        $('.tabsbar ul li a').bind('click', function() {
            var $this = $(this);
            $('.tabsbar ul li').removeClass('active');
            $this.parent().addClass('active');
            var flg = $(this).attr('hit');
            var show_type = $(this).attr('show_type');
            if (flg)
            {
                $("#"+flg).makeAsyncUploader({
                    upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
                    flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
                    button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
                    post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
                    file_size_limit: '1024 MB',
                    upload_success_handler: function(file, response) {
                        var container = $('#content');
                        $("#analysis").empty();
                        $("input[name="+flg+"_filename]", container).val(file.name);
                        //$("input[name$=_guid]", container).val(response).after('<span id="analysis"><a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response +'">After the analysis of the results</a></span>');
                        $("input[name="+flg+"_guid]", container).val(response);
                        $("input[name=flg]", container).val(flg);
                        $("#analysis_"+flg).html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/'+show_type+'/' + response + '">Show and modify</a>');
                        $("span[id="+flg+"_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                                .replace("{0}", file.name)
                                .replace("{1}", (file.size / 1024).toFixed(3))
                                );
                    }
                });
            }

        });

    });
</script>
<script type="text/javascript">
    //<![CDATA[
    tz = $('#query-tz').val();
    var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
    function showClients()
    {
        ss_ids_custom['client'] = _ss_ids_client;
        winOpen('<?php echo $this->webroot ?>clients/ss_client?types=2&type=0', 500, 530);
    }
    jQuery(document).ready(
            function() {
<?php if (!empty($_GET['search'])): ?>
                    jQuery('td.last div').each(function(index) {
                        var url = jQuery('a:first-child', jQuery(this)).attr('href');
                        jQuery('a:first-child', jQuery(this)).attr('href', url + '?jump=no&search=<?php echo $_GET['search']; ?>');
                    });
<?php endif; ?>
                jQuery('table tbody:nth-child(2n) tr').addClass('row-1').removeClass('row-2');
                jQuery('table tbody:nth-child(2n+1) tr').addClass('row-2').removeClass('row-1');
            }
    );
    //]]>
</script>


