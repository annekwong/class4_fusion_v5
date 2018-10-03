<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
            <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
            <?php echo __('FTP Configuration'); ?></a></li>
</ul>
<?php $write = $_SESSION['role_menu']['Configuration']['systemparams:ftp_conf']['model_w']; ?>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('FTP Configuration'); ?></h4>
</div>
<?php if ($write) { ?>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>systemparams/ftp_conf_create"><i></i> <?php __('Create New'); ?></a>
</div>
<?php } ?>
<div class="clearfix"></div>
<!--
<dl id="copyratetmp" class="tooltip-styled" style="border-radius: 5px;display:none;position:absolute;left:470px;top:250px;z-idnex:99;width:240px;height:160px; border: 1px solid #7FAF00; background-color: #ffffff;">
    <dd style="border: 1px solid #fff;border-radius: 5px;margin: 0;line-height: 30px;text-align:center;background: #7FAF00;height:30px;color: #fff;font-size: 16px;"><?php echo __('Copy FTP Config') ?>
        <span style="float:right"><a href="javascript:closeCover('copyratetmp');" id="pop-close" class="pop-close"><i class="icon-remove"></i>&nbsp;</a></span>
    </dd>
    <dd style="margin-top:10px;margin-left:5%;"> <?php echo __('Copy FTP Config Name') ?>:
        <input class="input in-text" id="pname" type="text" style="width:220px;"/>
    </dd>
    <dd>
        <input style="display:none" id="tmpid"/>
    </dd>
    <dd style="margin-top: 10px;margin-left: 20%; width:200px;height:auto;">
        <input type="button" onclick="copy_ftp_config();" value="<?php echo __('submit') ?>" class="input in-button btn btn-primary" >
        <input type="button" onclick="closeCover('copyratetmp');" value="<?php echo __('cancel') ?>" class="input in-button btn btn-default"  >
    </dd>
</dl>
-->

<div id="MyModalCopyFtpConf" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Copy FTP Config'); ?></h3>
    </div>
    <div class="modal-body">
        <table class="form table table-condensed">
            <tbody><tr>
                <td class="align_right"><?php __('Copy FTP Config Name') ?></td>
                <td>
                    <input class="input in-text width220" id="pname" type="text">
                    <input type="hidden" id="tmpid" value="">
                </td>
            </tr>
            </tbody></table>
    </div>
    <div class="modal-footer">
        <input type="button" onclick="copy_ftp_config();" class="btn btn-primary" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php
            if (empty($this->data)):
                ?>
                <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped tableTools dynamicTable table-bordered  table-white table-primary" id="TablebillList">
<!--                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">-->

                    <thead>
                    <th><?php echo $appCommon->show_order('alias', __('Alias', true)) ?></th>
                    <th><?php __('Active') ?></th>
                    <th><?php __('Action') ?></th>
                    </thead>
                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['FtpConf']['alias']; ?></td>
                            <td>
                                <a title="<?php if ($item['FtpConf']['active']) echo "Deactivate"; else echo "Activate"; ?>" href="<?php echo $this->webroot ?>systemparams/ftp_conf_change_status/<?php echo base64_encode($item['FtpConf']['id']) ?>">
                                    <?php if ($item['FtpConf']['active']): ?>
                                        <i class="icon-check"></i>
                                    <?php else: ?>
                                        <i class="icon-unchecked"></i>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
                                <a href="###" class="test_ftp" title="<?php __('Test FTP') ?>" control="<?php echo $item['FtpConf']['id'] ?>">
                                    <i class="icon-fire"></i>
                                </a>
                                <?php if ($write) { ?>
                                <a href="<?php echo $this->webroot ?>systemparams/ftp_conf_edit/<?php echo base64_encode($item['FtpConf']['id']) ?>" title="<?php __('Edit') ?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <?php } ?>
                                <a class="copy_ftp_conf" data-value="<?php echo base64_encode($item['FtpConf']['id']); ?>" data-toggle="modal"
                                   title="<?php echo __('Copy FTP conf') ?>" href="#MyModalCopyFtpConf">
                                    <i class="icon-copy"></i>
                                </a>
                                <a class="ftp_trigger" ftp_id="<?php echo base64_encode($item['FtpConf']['id']) ?>" title="<?php echo __('Trigger') ?>" href="javascript:void(0)">
                                    <i class="icon-expand"></i>
                                </a>
                                 <?php if ($write) { ?>
                                <a onclick="return myconfirm('Are you sure to delete it?', this);" href="<?php echo $this->webroot ?>systemparams/ftp_conf_delete/<?php echo base64_encode($item['FtpConf']['id']) ?>" title="<?php __('Delete') ?>">
                                    <i class="icon-remove"></i>
                                </a>
                                <?php }?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class=""></div>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
    <div id="cover"></div>
    <div id="loading" style="display:none;">
        <img src="<?php echo $this->webroot ?>images/progress.gif" />
    </div>
</div>
<link rel="stylesheet" href="http://www.zhangxinxu.com/study/css/smartMenu.css" type="text/css" />
<style type="text/css">
    .smart_menu_box{
        width: 200px;
    }
    /*.smart_menu_a:hover, .smart_menu_a_hover{background-color:#7faf00;}*/
</style>
<script type="text/javascript" src="http://www.zhangxinxu.com/study/js/jquery-smartMenu.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.base64.min.js"></script>
<script type="text/javascript">
    var imageMenuData = [
        [{
            text: "<?php __('Show Egress PCAP'); ?>",
            func: function() {
                $(this).css("padding", "10px");
            }
        }, {
            text: "<?php __('Show Ingress PCAP'); ?>",
            func: function() {
                $(this).css("background-color", "#beceeb");
            }
        }, {
            text: "<?php __('Show Detail Trunk Choices'); ?>",
            func: function() {
                var src = $(this).attr("src");
//                window.open(src.replace("/s512", ""));
            }
        }, {
            text: "<?php __('Show Actual Failover'); ?>",
            func: function() {
                $(this).css("background-color", "#beceeb");
            }
        }, {
            text: "<?php __('Show Actual Failover'); ?>",
            func: function() {
                $(this).css("background-color", "#beceeb");
            }
        }]
    ];
//    $("#TablebillList tbody tr").smartMenu(imageMenuData, {
//        'textLimit':50
//    });


    $(function() {
        $(".ftp_trigger").click(function() {
            var ftp_id = $(this).attr('ftp_id');
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>systemparams/ftp_trigger/'+ftp_id,
                {},
                function(responseText, textStatus, XMLHttpRequest) {
                    $dd.dialog({
                        title: "Trigger FTP Configuration",
                        'width': '500px',
//                                                        'height': 200,
                        'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
                            $form = $('form', $dd);
                            $form.submit();
                            $(this).dialog("close");
                        }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                            $(this).dialog("close");
                        }}]

                    });
                }
            );
        });

        var $test_ftp = $('.test_ftp');
        var $loading = $('#loading');
        $test_ftp.click(function() {
            var control_id = $(this).attr('control');
            $.ajax({
                'url': '<?php echo $this->webroot ?>systemparams/test_ftp/' + control_id,
                'type': 'POST',
                'dataType': 'text',
                'beforeSend': function() {
                    $loading.show();
                },
                'success': function(data) {
                    $loading.hide();
                    var theme = data.indexOf('Connected') == -1 ? 'jmsg-error' : 'jmsg-success';
                    jGrowl_to_notyfy(data, {theme: theme});
                }
            });
        });

        $(".copy_ftp_conf").click(function() {
            var id = $(this).data('value');
            $("#tmpid").val(id);
        });
    });
    function copy_ftp_config() {
        var v = $("#tmpid").val();
        var n = $("#pname").val();
        if (!n) {
            jGrowl_to_notyfy('<?php echo __('Name cannot be empty') ?>', {theme: 'jmsg-error'});
        }
        else {
            location = '<?php echo $this->webroot ?>systemparams/ftp_conf_copy?id=' + v + '&name=' + n;
        }
    }
</script>
