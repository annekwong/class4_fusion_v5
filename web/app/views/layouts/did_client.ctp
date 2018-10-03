<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9 fluid top-full menuh-top"> <![endif]-->
<!--[if gt IE 8]> <html class="animations ie gt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if !IE]><!-->
<html class="animations fluid top-full menuh-top" xmlns="http://www.w3.org/1999/html"><!-- <![endif]-->
<head>
    <title><?php echo $title_for_layout; ?> :: <?php __(Configure::read('project_name')) ?></title>

    <!-- Meta -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!--    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />-->
    <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE"
    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot; ?>common/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->webroot; ?>common/bootstrap/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- Glyphicons Font Icons -->
    <link href="<?php echo $this->webroot; ?>common/theme/fonts/glyphicons/css/glyphicons.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo $this->webroot; ?>common/theme/fonts/font-awesome/css/font-awesome.min.css">
    <!--[if IE 7]><link rel="stylesheet" href="<?php echo $this->webroot; ?>common/theme/fonts/font-awesome/css/font-awesome-ie7.min.css"><![endif]-->

    <!-- Uniform Pretty Checkboxes -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css" rel="stylesheet" />

    <!-- PrettyPhoto -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/gallery/prettyphoto/css/prettyPhoto.css" rel="stylesheet" />

    <!-- Multiselect Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/css/multi-select.css" rel="stylesheet" />

    <!-- JQuery -->
    <script src="<?php echo $this->webroot; ?>js/jquery-1.10.1.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/jquery-migrate-1.2.1.min.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/html5shiv.js"></script>
    <![endif]-->

    <!--[if IE]><!--><script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/excanvas/excanvas.js"></script><!--<![endif]-->
    <!--[if lt IE 8]><script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/json2.js"></script><![endif]-->

    <!-- Bootstrap Extended -->
    <link href="<?php echo $this->webroot; ?>common/bootstrap/extend/jasny-fileupload/css/fileupload.css" rel="stylesheet">
    <link href="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">
    <link href="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-select/bootstrap-select.css" rel="stylesheet" />
    <link href="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" rel="stylesheet" />

    <!-- DateTimePicker Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/bootstrap-datetimepicker/css/datetimepicker.css" rel="stylesheet" />

    <!-- JQueryUI -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />

    <!-- Multiselect Plugin -->
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/js/jquery.multi-select.js"></script>

    <!-- MiniColors ColorPicker Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/color/jquery-miniColors/jquery.miniColors.css" rel="stylesheet" />

    <!-- Notyfy Notifications Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.css" rel="stylesheet" />
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/themes/default.css" rel="stylesheet" />

    <!-- Gritter Notifications Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

    <!-- Easy-pie Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.css" rel="stylesheet" />

    <!-- Google Code Prettify Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/google-code-prettify/prettify.css" rel="stylesheet" />

    <!-- Validation Engine -->
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>common/validationEngine/validationEngine.jquery.css">

    <!-- jGrowl -->
    <link href="<?php echo $this->webroot ?>css/jquery.jgrowl.css"	media="all" rel="stylesheet" type="text/css" />

    <!-- Select2 Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/select2/select2.css" rel="stylesheet" />

    <!-- Pageguide Guided Tour Plugin -->
    <!--[if gt IE 8]><!--><link media="screen" href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/pageguide/css/pageguide.css" rel="stylesheet" /><!--<![endif]-->

    <!-- Bootstrap Image Gallery -->
    <link href="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-image-gallery/css/bootstrap-image-gallery.min.css" rel="stylesheet" />

    <!-- Main Theme Stylesheet :: CSS -->
    <link href="<?php echo $this->webroot; ?>common/theme/css/style-default-menus-dark.css?1374506511" rel="stylesheet" type="text/css" />

    <!-- Footable -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/FooTable/css/footable-0.1.css" rel="stylesheet" />

    <!-- Qtip -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/qtip/jquery.qtip.min.css" rel="stylesheet" />


    <!--  fakeLoader -->
    <link href="<?php echo $this->webroot; ?>js/fakeLoader/css/fakeLoader.css" rel="stylesheet" />

    <!-- FireBug Lite -->
    <!-- <script src="https://getfirebug.com/firebug-lite-debug.js"></script> -->

    <!--Custom-->
    <link href="<?php echo $this->webroot; ?>css/custom.css" rel="stylesheet" />
    <!-- LESS.js Library -->
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/less.min.js"></script>
    <script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>
    <script src="<?php echo $this->webroot ?>js/xtable.js"></script>
    <script src="<?php echo $this->webroot ?>js/bb-functions.js"></script>
    <script src="<?php echo $this->webroot ?>js/bb-interface.js"></script>
    <script src="<?php echo $this->webroot ?>js/util.js"></script>
    <script src="<?php echo $this->webroot ?>js/My97DatePicker/WdatePicker.js" type="text/javascript"></script>

    <!-- DataTables Tables Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/css/TableTools.css" rel="stylesheet" />
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/extras/ColVis/media/css/ColVis.css" rel="stylesheet" />
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.min.js"></script>
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/extras/ColVis/media/js/ColVis.min.js"></script>
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
    <!--  fakeLoader -->
    <script src="<?php echo $this->webroot; ?>js/fakeLoader/js/fakeLoader.js"></script>

    <!--  修改配色方案中的默认配色  -->
    <style>
        ul#show_page_row li:hover{
            background-color: #7faf00;
        }

        .widget ul.dropdown-menu li:hover{
            background-color: #7faf00;
        }

        @media  (max-width: 1343px){
            .admin_nav{
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
        }

        @media  (max-width: 1223px){
            .admin_nav{
                padding-left: 5px !important;
                padding-right: 5px !important;
            }
        }
    </style>

    <!--themer-->
    <link href="<?php echo $this->webroot; ?>css/themer.css" rel="stylesheet" />


    <!-- Global -->
    <cake:nocache>
        <script>

            <?php
            if ((isset($_SESSION['file_permission']) && $_SESSION['file_permission']) || isset($_SESSION['license_date']))
            {
            ?>
            $(function() {
                if (!$('#dd').length) {
                    $(document.body).append("<div id='dd'></div>");
                }
                var $dd = $('#dd');
                $dd.load("<?php echo $this->webroot; ?>homes/permission",
                    {},
                    function(responseText, textStatus, XMLHttpRequest) {
                        $dd.dialog({
                            'title': 'This is a notice!',
                            'width': '30%'
                        });
                    }
                );
            });
            <?php
            }
            ?>
        </script>
    </cake:nocache>
    <script>
        //<![CDATA[
        var basePath = '',
            commonPath = '<?php echo $this->webroot; ?>common/';

        var webroot = '<?php echo $this->webroot ?>';
        var currentTime = '<?php echo time(); ?>';
        var L = {"loadingPanel": "Please Wait...", "deleteConfirm": "Are you sure you want to delete this item?", "hide-all": "hide all"};

        // colors
        var primaryColor = '#7faf00',
            dangerColor = '#b55151',
            successColor = '#609450',
            warningColor = '#ab7a4b',
            inverseColor = '#45484d';

        var themerPrimaryColor = primaryColor;
        //]]>

        //themer
        document.cookie="themerSelectedTheme="+<?php echo empty($themerSelectedTheme) ? 0 : $themerSelectedTheme  ?>;

    </script>
    <?php //echo $this->element("layout/permission") ?>

    <?php
    echo $html->meta('icon');
    echo $scripts_for_layout;
    ?>
    <?php if (isset($appCommon)): ?>
        <?php echo $appCommon->auto_load_js() ?>
    <?php endif; ?>
    <?php
    echo $html->meta('icon');
    echo $scripts_for_layout;
    ?>
</head>
<body class="document-body ">
<div class="fakeloader"></div>
<!-- Main Container Fluid -->
<div class="container-fluid menu-hidden sidebar-hidden-phone fluid menu-left">

    <!-- Content -->
    <div id="content" style="overflow: visible;">

        <?php if(!isset($noMenu)) : ?>

            <div class="navbar main" style="border: 0px;height: 44px;">

                <!-- Menu Toggle Button -->
                <button type="button" class="btn btn-navbar pull-left visible-phone">
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <!-- // Menu Toggle Button END -->

                <!-- Not Blank page -->
                <ul class="topnav pull-left hidden-phone" style="background-color: white;height: 44px;width: 116px;padding: 0px;">
                    <a href='#'>
                        <?php
                        $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';

                        if (file_exists($logo_path))
                        {
                            $logo = $this->webroot . 'upload/images/logo.png';
                        }
                        else
                        {
                            $logo = $this->webroot . 'images/logo.png';
                        }
                        ?>
                        <img src="<?php echo $logo ?>" style="height: 44px;width: 116px;" alt=""/>
                    </a>
                </ul>
                <!-- Full Top Style -->

                <?php echo $this->element("project_menu/did_client_menu") ?>

                <!-- // Full Top Style END -->

                <!-- // Not Blank Page END -->

                <?php if($session->read('sst_user_id')): ?>
                    <!-- Top Menu Right -->
                    <ul class="topnav pull-right hidden-phone" style="background-color: #393939;">

                        <!-- Themer -->
                        <!--                                        --><?php //if (PRI && $session->read('login_type') == 1) :?>
                        <!--                                            <li style="border: 0px"><a href="#themer" data-toggle="collapse" class="glyphicons eyedropper single-icon"><i></i></a></li>-->
                        <!--                                            <li style="border: 0px"><a href="--><?php //echo $this->webroot ?><!--import_export_log/import" class="glyphicons upload single-icon"><i></i><span class="badge fix badge-primary">--><?php //echo count($appImportExportLog->display_upload_tip()); ?><!--</span></a></li>-->
                        <!--                                        --><?php //endif;?>
                        <!-- // Themer END -->


                        <!-- Profile / Logout menu -->
                        <li class="account dropdown dd-1">
                            <a data-toggle="dropdown" href="<?php echo $this->webroot ?>homes/logout" class="glyphicons logout lock"><span class="hidden-tablet hidden-phone hidden-desktop-1"><?php echo $session->read('sst_user_name') ?></span><i></i></a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="<?php echo $this->webroot; ?>did_client/profile" class="glyphicons cogwheel"><?php __('Profile'); ?><i></i></a></li>
                                <li>
                                    <span>
                                        <a class="btn btn-default btn-mini pull-right" style="border-color: #ccc" href="<?php echo $this->webroot ?>homes/logout">Sign Out</a>
                                    </span>
                                </li>
                            </ul>
                        </li>
                        <!-- // Profile / Logout menu END -->

                    </ul>


                <?php endif; ?>
                <div class="clearfix"></div>

            </div>

        <?php endif; ?>

        <!-- Top navbar END -->
        <?php if(!isset($noMenu)) : ?>
            <div class="uptime" style="padding: 0 16px;top: 0px;height: 38px;line-height: 38px;margin: 0px;" mb:format="%m/%d/%Y %H:%M:%S %z" mb:tz="<?php echo $_SESSION['sys_timezone']; ?>" mb:stamp="<?php echo time(); ?>" id="topmenu-time">
                <?php
                echo date("m/d/Y H:i:s O");
                ?>
            </div>
        <?php endif; ?>
        <?php $session->flash(); ?>
        <?php echo $content_for_layout; ?>

    </div>

    <!--    get support START-->
    <form action="" id="support_form" method="post">
        <div id="myModal_support" class="modal hide">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">&times;</button>
                <h3><?php __('Get Support'); ?></h3>
            </div>
            <div class="separator"></div>
            <div class="widget-body">
                <table class="table table-bordered">
                    <tr>
                        <td class="align_right"><?php echo __('subject')?> </td>
                        <td>
                            <input class="input in-text validate[required]" name="support_subject"  id="support_subject" type="text" >
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('content')?> </td>
                        <td><textarea class="input in-textarea validate[required] support_content" style="width: 100%;" name="support_content"  id="support_content"></textarea></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <input type="button" id="support_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
                <a href="javascript:void(0)" id="support_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
            </div>

        </div>
    </form>
    <!-- support end -->

    <!-- change password start -->
    <form action="" id="change_password_form" method="post">
        <div id="myModal_changePassword" class="modal hide">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">&times;</button>
                <h3><?php __('Change Password'); ?></h3>
            </div>
            <div class="separator"></div>
            <div class="widget-body">
            </div>
            <div class="modal-footer">
                <input type="button" id="change_password_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
                <a href="javascript:void(0)" id="change_password_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
            </div>

        </div>
    </form>
    <!-- change password end -->
</div>
<script type="text/javascript">
    $("#support_submit").click(function(){
        var support_subject = $("#support_subject").val();
        var support_content = $("#support_content").val();
        $("#support_form").validationEngine('validate');
        $.ajax({
            'url': '<?php echo $this->webroot ?>homes/get_support',
            'type': 'POST',
            'dataType': 'json',
            'data': {'support_subject': support_subject, 'support_content': support_content, 'curr_url':window.location.href},
            'beforeSend': function(XMLHttpRequest) {

                if(!support_subject || !support_content)
                {
                    return false;
                }
                $('#support_submit').before('<i class="icon-spinner icon-spin icon-large" id="loading_i"></i>');//显示等待消息
                $("#support_submit").val('Sending...').attr('disabled',true);
            },
            'success': function(data) {
                var msg = data.msg;
                var flg = data.flg;
                if(flg){
                    jGrowl_to_notyfy(msg, {theme: 'jmsg-success'});
                }
                else{
                    jGrowl_to_notyfy(msg, {theme: 'jmsg-error'});
                }
                $("#support_submit").val('Submit').attr('disabled',false);
                $("#loading_i").remove();
                $("#support_close").click();
            }
        });
    });

    $("#myModal_changePassword").on('shown',function(){
        var modal = $(this);
        modal.find('.widget-body').load("<?php echo $this->webroot; ?>homes/ajax_change_password");
    });

    $("#change_password_submit").click(function(){
        var UserOld = $("#UserOld").val();
        var UserNew = $("#UserNew").val();
        var UserRetype = $("#UserRetype").val();
        var validate_flg = $("#change_password_form").validationEngine('validate');
        console.log(validate_flg);
        if(!validate_flg){
            return false;
        }
        $.ajax({
            'url': '<?php echo $this->webroot ?>homes/ajax_change_password',
            'type': 'POST',
            'dataType': 'json',
            'data': {'old_pwd': UserOld, 'new_pwd': UserNew,'re_pwd':UserRetype},
            'beforeSend': function(XMLHttpRequest) {
                $('#change_password_submit').before('<i class="icon-spinner icon-spin icon-large" id="loading_i_changePassword"></i>');//显示等待消息
                $("#change_password_submit").val('loading...').attr('disabled','true');
            },
            'success': function(data) {
                var msg = data.msg;
                var flg = data.flg;
                $("#change_password_submit").val('Submit').removeAttr('disabled');
                $("#loading_i_changePassword").remove();
                if(flg){
                    jGrowl_to_notyfy(msg, {theme: 'jmsg-success'});
                    $("#change_password_close").click();
                }
                else{
                    jGrowl_to_notyfy(msg, {theme: 'jmsg-error'});
                }


            }
        });
    });


</script>
<!--    get support END-->

<!-- // Content END -->


<div id="footer" class="hidden-print">

</div>
<!-- // Footer END -->

</div>
<!-- // Main Container Fluid END -->
<!-- Themer -->
<div id="themer" class="collapse">
    <div class="wrapper">
        <span class="close2" id="close_themer">&times; <?php __('close') ?></span>
        <h4><?php __('Themer') ?> <span><?php __('color options') ?></span></h4>
        <ul>
            <li><?php __('Theme') ?>: <select id="themer-theme" class="pull-right"></select><div class="clearfix"></div></li>
            <li><?php __('Primary Color') ?>: <input type="text" data-type="minicolors" data-default="#ffffff" data-slider="hue" data-textfield="false" data-position="left" id="themer-primary-cp" /><div class="clearfix"></div></li>
            <!--            <li>-->
            <!--                <span class="link" id="themer-custom-reset">--><?php //__('reset theme') ?><!--</span>-->
            <!--                <span class="pull-right"><label>--><?php //__('advanced') ?><!-- <input type="checkbox" value="1" id="themer-advanced-toggle" /></label></span>-->
            <!--            </li>-->
        </ul>
        <div>
            <hr class="separator" />
            <button class="btn btn-inverse btn-small pull-right btn-icon glyphicons ok" id="themer_save"><i></i><?php __('Save')?></button>
            <div class="clearfix"></div>
        </div>
    </div>

</div>
<!-- // Themer END -->

<!-- Modal Gallery -->
<div id="modal-gallery" class="modal modal-gallery hide fade hidden-print" tabindex="-1">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&<?php __('times') ?>;</a>
        <h3 class="modal-title"></h3>
    </div>
    <div class="modal-body"><div class="modal-image"></div></div>
    <div class="modal-footer">
        <a class="btn btn-primary modal-next"><?php __('Next') ?> <i class="icon-arrow-right icon-white"></i></a>
        <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> <?php __('Previous') ?></a>
        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> <?php __('Slideshow') ?></a>
        <a class="btn modal-download" target="_blank"><i class="icon-download"></i> <?php __('Download') ?></a>
    </div>
</div>
<!-- // Modal Gallery END -->
<div id="loading"><i class="icon-spinner icon-spin icon-large" style="" id="loading_i"></i></div>


<!-- jQuery Event Move -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.event.move/js/jquery.event.move.js"></script>

<!-- jQuery Event Swipe -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.event.swipe/js/jquery.event.swipe.js"></script>

<?php /*
      <!-- jQuery ScrollTo Plugin -->
      <!--[if gt IE 8]><!--><script src="<?php echo $this->webroot; ?>js/jquery-scrollto.js"></script><!--<![endif]-->
     */ ?>


<!-- Code Beautify -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/js-beautify/beautify.js"></script>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/js-beautify/beautify-html.js"></script>

<!-- PrettyPhoto -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>

<!-- JQueryUI -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

<!-- JQueryUI Touch Punch -->
<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>


<!-- Modernizr -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/modernizr.js"></script>


<!-- Bootstrap -->
<script src="<?php echo $this->webroot; ?>common/bootstrap/js/bootstrap.min.js"></script>

<!-- SlimScroll Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.js"></script>

<!-- Holder Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/holder/holder.js?1374506511"></script>

<!-- Uniform Forms Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

<!-- MegaMenu -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/megamenu.js?1374506511"></script>

<!-- Bootstrap Extended -->
<script src="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
<script src="<?php echo $this->webroot; ?>common/bootstrap/extend/jasny-fileupload/js/bootstrap-fileupload.js"></script>
<script src="<?php echo $this->webroot; ?>common/bootstrap/extend/bootbox.js"></script>
<script src="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
<script src="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>

<!-- Google Code Prettify -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/google-code-prettify/prettify.js"></script>

<!-- Gritter Notifications Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>

<!-- Notyfy Notifications Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.js"></script>

<!-- MiniColors Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/color/jquery-miniColors/jquery.miniColors.js"></script>

<!-- DateTimePicker Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<!-- Cookie Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.cookie.js"></script>

<!-- Select2 Plugin -->
<!--<script src="--><?php //echo $this->webroot; ?><!--common/theme/scripts/plugins/forms/select2/select2.js"></script>-->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/select2/select2.full.js"></script>


<!-- Themer -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/themer.js"></script>


<!-- Bootstrap Image Gallery -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/gallery/load-image/js/load-image.min.js"></script>
<script src="<?php echo $this->webroot; ?>common/bootstrap/extend/bootstrap-image-gallery/js/bootstrap-image-gallery.min.js" type="text/javascript"></script>


<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/tables/FooTable/js/footable.js"></script>

<!-- Responsive Tables Demo Script -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/tables_responsive.js"></script>

<!-- Common Demo Script -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/common.js?1374506511"></script>

<!-- Validation Engine -->
<script src="<?php echo $this->webroot; ?>common/validationEngine/jquery.validationEngine.js"></script>
<script src="<?php echo $this->webroot; ?>common/validationEngine/jquery.validationEngine-en.js"></script>
<script src="<?php echo $this->webroot; ?>common/validationEngine/validateExtend.js"></script>
<script src="<?php echo $this->webroot ?>js/xtable.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/class4/common.js"></script>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/qtip/jquery.qtip.min.js"></script>
<script src="<?php echo $this->webroot ?>js/select.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot ?>js/table-scroll.js" type="text/javascript"></script>
<!-- All messages -->
<?php
if (empty($m) || $m == '')
{
    $m = $session->read('m');
    $mm = $session->check('mm') ? $session->read('mm') : 0;
    $mm -= 1;


    if($mm <= 0){

        $session->del('m');
        $session->del('mm');
    } else {
        $_SESSION['mm'] = $mm;
    }
}
if (!empty($m))
{
    ?>
    <script type="text/javascript">

        showMessages_new("<?php echo $m ?>");
    </script>
<?php } ?>
<?php echo $appCommon->show_form_value(); ?>
<script>
    //下拉选择时间
    $(function(){
        $("#query-start_date-wDt").click(function(){
            var max_date = $('#query-stop_date-wDt').val();
            WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:max_date});});
        $("#query-start_time-wDt").click(function(){WdatePicker({dateFmt:'HH:mm:ss'});});
        $("#query-stop_date-wDt").click(function(){
            var min_date = $("#query-start_date-wDt").val();
            WdatePicker({dateFmt:'yyyy-MM-dd',minDate:min_date,maxDate:'%y-%M-%d'});});
        $("#query-stop_time-wDt").click(function(){WdatePicker({dateFmt:'HH:mm:ss'});});
        startWatchStopTime();
    })
</script>
<script>


    $("#loading").ajaxStart(function() {
        $(this).show();
    }).ajaxStop(function() {
        $(this).hide();
    });

</script>

<script type="text/javascript">
    $(function() {

        $.notyfy.defaults = {
            layout: 'top',
            theme: 'default',
            type: 'alert',
            text: '',
            dismissQueue: true, // If you want to use queue feature set this true
            template: '<div class="notyfy_message style="height:30px;"><span class="notyfy_text"></span>' +
            '<div class="notyfy_close"></div></div>',
            showEffect: function(bar) {
                bar.animate({height: 'toggle'}, 500, 'swing');
            },
            hideEffect: function(bar) {
                bar.animate({height: 'toggle'}, 500, 'swing');
            },
            timeout: false, // delay for closing event. Set false for sticky notifications
            force: false, // adds notification to the beginning of queue when set to true
            modal: false,
            closeWith: ['click'], // ['click', 'button', 'hover']
            events: {
                show: null,
                hide: null,
                shown: null,
                hidden: null
            },
            buttons: false // an array of buttons
        };
        <?php $disable_controller = array(
        'necessary_configuration',
    ); ?>
        <?php $delay_controller = array(
        'reports_db','downloads','lrnreports','down','usagedetails_db'
    ); ?>
        <?php if(in_array($this->params['controller'],$delay_controller)): ?>
        var timeToHide = 3000;
        <?php else: ?>
        var timeToHide = 0;
        <?php endif; ?>
        <?php if(!in_array($this->params['controller'],$disable_controller)): ?>
        <!--  fakeLoader -->
        $(window).bind('beforeunload',function(){
            if (true) {
                //do something
                $(".fakeloader").fakeLoader({
                    timeToHide:timeToHide,
                    bgColor:"rgba(25, 25, 25, .3)",
                    spinner:"spinner2"
                });
//                return "fsdfds";
            } else {
                // do something else
            }
        });
        <?php endif; ?>

        //保持主题配色
        $(function(){
            $('#themer_save').click(function(){
                var val = $('#themer-theme').val();
                var css = $('#themer-stylesheet').text();
                $.post(
                    "<?php echo $this->webroot ?>homes/save_themer",
                    {val:val,css:css},
                    function(data){
                        $('#close_themer').click();
                        showMessages_new(data);
                    }
                )
            })
        });

        setTimeout("$('.minicolors-panel').remove();",100);

    });
</script>
<script src="//cdn.bootcss.com/floatthead/1.4.0/jquery.floatThead.js"></script>
<script>
    $(function(){

        <?php if (!in_array($this->params['controller'],array('reports_db','reports_more'))): ?>
        var $bsort = false;
        <?php else: ?>
        var $bsort = true;
        <?php endif; ?>

        if($('.dynamicTable:visible').find('thead').length > 0){
            $('.dynamicTable:visible').each(function(){
                var $thisTable = $(this);
                var thisDataTable = $(this).DataTable( {
                    "sDom": "<'row-fluid'<'span6'l><'span6'C>>",
                    "bPaginate": false,
                    "bSort": $bsort,
                    "aoColumnDefs": [
                        {
                            sDefaultContent: '',
                            aTargets: [ '_all' ]
                        }
                    ],
                } );
                if ($thisTable.hasClass('no_float')== false){
                    if ($thisTable.css('width') > $thisTable.parent().css('width') && $thisTable.hasClass('coercive-float') == false){
//此时自动有overflow-x 需要另外的方式
                    }else{
                        thisDataTable.floatThead({
                            top:function(){
                                return $(".navbar").height();
                            }
                        });
                    }
                }

            });

            $('.TableTools_Button').attr({'id':'show_hide_columns', 'data-toggle':"dropdown"});
            $('#show_hide_columns').addClass('btn btn-primary');
            $('#show_hide_columns').removeClass('ColVis_Button TableTools_Button ColVis_MasterButton').find('span').eq(0).html('<?php __('Show / Hide columns'); ?>');
            $('<span> </span>').appendTo('#show_hide_columns');
            $('<span class="caret"></span>').appendTo('#show_hide_columns');

            //是否包含分页选项
            if(!$('.dynamicTable:visible').hasClass('no_pages')){
                var html_button = '<button class="btn btn-primary btn-sm innerLR " data-toggle="dropdown" style="margin-right: 25px"><?php __("Page Row") ?>:<span id="show_page_button" value="100">100</span> <span class="caret"></span></button>';
                var html_ul = '<ul class="dropdown-menu" id="show_page_row" role="menu"><li value="10"><?php __("10 rows") ?></li><li value="20"><?php __("20 rows") ?></li><li value="30"><?php __("30 rows") ?></li><li value="50"><?php __("50 rows") ?></li><li value="100"><?php __("100 rows") ?></li></ul>';
                var html_div = $('<div class="btn-group btn-group-sm pull-left" style="margin-bottom: 20px"></div>')
                    .append(html_button).append(html_ul);
                var html_span6 = $('<div class="span6"></div>').html(html_div);

                $('.dataTables_wrapper .row-fluid .span6:eq(0)').html(html_span6);

                <?php
                $arr = array();
                if(isset($this->params['getUrl']) && !empty($this->params['getUrl'])){
                    $arr = explode('&',$this->params['getUrl']);
                    foreach($arr as $k => $item){

                        if( strpos($item, 'size') !== false )
                            unset($arr[$k]);
                    }
                } else {
                    if(isset($this->params['url']['order_by'])){
                        $arr['order_by'] = 'order_by=' . $this->params['url']['order_by'];
                    }

                }

                $page_size = isset($_GET['size']) ? $_GET['size'] : 100;
                //                if(isset($_SESSION['paging_row']))
                //                    $page_size = $_SESSION['paging_row'];
                $get_url = empty($arr) ? '' : implode('&',$arr);
                ?>

                var show_page_row = '<?php echo $page_size ? $page_size : 100 ?>';
                $('#show_page_button').html(show_page_row);
                $('#show_page_button').attr('value', show_page_row);
                $('#show_page_row li').click(function(){
                    var value = $(this).attr('value');


                    var url = '<?php echo $this->webroot . $this->params['url']['url'];?>' + '?size=' + value + '&' + '<?php echo $get_url;?>';
                    location.href = url;

                });
            }



            $('#show_hide_columns').live('click',function(){
                $('.ColVis_collection').css({'width': '182px', 'margin': '0px', 'padding':'0px', 'border':'0px', 'margin-bottom':'50px'});
                $('.ColVis_collection>button').attr({'style':'width: 182px; margin-left: 0px;','class':'btn btn-default'});
                $('.ColVis_collection>button a').each(function(){
                    var html = $(this).html();
                    $(this).replaceWith(html);
                });
                $('.ColVis_collection>button .ColVis_title input').parent().parent().parent().remove();

                if($('#trAdd')){
                    $('#trAdd').find('#delete').click();
                }

            }).trigger('click');

//            refresh floatThead
            $('.ColVis_collection>button').live('click',function(){
                $(".dynamicTable:visible").floatThead('reflow');
            });


            if($('#show_hide_columns').parents().hasClass('overflow_x')){
                //$('#DataTables_Table_0_wrapper').insertBefore($('.overflow_x'));
                var t_id = $('.dynamicTable').attr('id');
                $('#'+t_id+'_wrapper').insertBefore($('.overflow_x'));
            }

            //$('#show_hide_columns').click();
            //根据cookie隐藏列
            var select_all_columns = $.cookie('select_all_columns');
            $.cookie('select_all_columns', null, { path: "/"});

            var url = '<?php echo $this->params['controller'] . '_' . $this->params['action'] ?>';
            var select_columns = {};
            select_columns = $.cookie('select_columns');

            if(select_columns && select_all_columns != 1){
                select_columns = eval('('+select_columns+')');
                var show_columns_arr = select_columns[url];

                if(show_columns_arr && show_columns_arr[0] !== undefined){
                    $(show_columns_arr).each(function(i,item){
                        var ColVis_collection = $('.ColVis_collection:eq('+i+')');
                        var radio = ColVis_collection.find(':checkbox');
                        radio.each(function(i_r){
                            if(show_columns_arr[i][i_r] != 1)
                                radio.eq(i_r).click();

                        });
                    });
                }
            } else {
                select_columns = {};
            }

            $('.ColVis_collectionBackground').click(function(){
                if($('.dynamicTable').parent().hasClass('overflow_x')){

                    var pwidth = $('.dynamicTable').parent().width();
                    var twidth = $('.dynamicTable').width();
                    if((twidth - pwidth) > 10){
                        $('.dynamicTable').width(pwidth);
                    }
                }
            }).trigger('click');


            $(window).unload(function(){
                var is_show_all = $('#show_hide_columns').attr('value');
                var show_columns_arr = {};
                var tem = {};
                $('.ColVis_collection').each(function(i,item){
                    var radio = $(this).find(':checkbox');
                    show_columns_arr[i] = {};
                    radio.each(function(i_r){
                        if($(this).is(':checked'))
                            show_columns_arr[i][i_r] = 1;
                        else
                            show_columns_arr[i][i_r] = 0;
                    });
                });
                select_columns[url] = show_columns_arr;
                select_columns = JSON.stringify(select_columns);
                $.cookie('select_columns',select_columns, { path: "/"});
                if(is_show_all){

                    $.cookie('select_all_columns',1, { path: "/"});
                }

            });

            $('.edit').click(function(){
                if($('#trAdd')){
                    $('.ColVis_collection').find(':checkbox').each(
                        function(i,item){
                            var ii = i +1;
                            if(!$(this).is(':checked'))
                                $('#trAdd').find('td:eq('+ii+')').hide();
                        });
                }
            });

            $('.dataTables_empty').parent().remove();
        }







        //只使用页面选项
        if($('.table_page_num:visible').length > 0){
            var html_button = '<button class="btn btn-primary btn-sm innerLR " data-toggle="dropdown" style="margin-right: 25px"><?php __("Page Row") ?>:<span id="show_page_button" value="100">100</span> <span class="caret"></span></button>';
            var html_ul = '<ul class="dropdown-menu" id="show_page_row" role="menu"><li value="10"><?php __("10 rows") ?></li><li value="20"><?php __("20 rows") ?></li><li value="30"><?php __("30 rows") ?></li><li value="50"><?php __("50 rows") ?></li><li value="100"><?php __("100 rows") ?></li></ul>';
            var html_div = $('<div class="btn-group btn-group-sm pull-left" style="margin-bottom: 20px;margin-top: 20px;"></div>')
                .append(html_button).append(html_ul);

            //var html_span6 = $('<div class="span6"></div>').html(html_div);

//            if($('.table_page_num').parent().hasClass('overflow_x')){
//                $('.table_page_num').parent().before(html_div);
//            } else {
            $('.table_page_num').before(html_div);
            $('.table_page_num').before('<div class="clearfix"></div>');
//            }
            var $pageNumTable = $('.table_page_num');
            if ($pageNumTable.hasClass('dynamicTable') == false){
                if ($pageNumTable.css('width') > $pageNumTable.parent().css('width') && $pageNumTable.hasClass('coercive-float') == false){
//此时自动有overflow-x 需要另外的方式
                }else{
                    $pageNumTable.floatThead({
                        top:function(){
                            return $(".navbar").height();
                        }
                    });
                }
            }

            <?php
            $arr = array();
            if(isset($this->params['getUrl']) && !empty($this->params['getUrl'])){
                $arr = explode('&',$this->params['getUrl']);
                foreach($arr as $k => $item){

                    if( strpos($item, 'size') !== false )
                        unset($arr[$k]);
                }
            } else {
                if(isset($this->params['url']['order_by'])){
                    $arr['order_by'] = 'order_by=' . $this->params['url']['order_by'];
                }

            }

            $page_size = isset($_GET['size']) ? $_GET['size'] : 100;
            //                if(isset($_SESSION['paging_row']))
            //                    $page_size = $_SESSION['paging_row'];
            $get_url = empty($arr) ? '' : implode('&',$arr);
            ?>

            var show_page_row = '<?php echo $page_size ? $page_size : 100 ?>';
            $('#show_page_button').html(show_page_row);
            $('#show_page_button').attr('value', show_page_row);
            $('#show_page_row li').click(function(){
                var value = $(this).attr('value');


                var url = '<?php echo $this->webroot . $this->params['url']['url'];?>' + '?size=' + value + '&' + '<?php echo $get_url;?>';
                location.href = url;

            });
        }


    });



    //select 排序
    $.sort_select = function(id){
        select = $(id);
        var selected_text = select.find('option:selected').text();

        select.find('option').sort(function(a,b){
            var aText = $(a).text().toUpperCase();
            var bText = $(b).text().toUpperCase();


            if(aText>bText) return 1;
            if(aText<bText) return -1;
            return 0;
        }).appendTo(select);



        select.find('option').each(function(){
            if($(this).text().toUpperCase() == 'ALL'){
                $(this).prependTo(select);
            }
            if($(this).text() == selected_text){
                $(this).attr('selected',true);

            }
        });

        return true;
    };

    //code限制时间
    $.limit_code = function(){
        var switch_code = $('.switch_code');
        var switch_select_code = $('select[name = "group_select[]"]').find('option[value $= "code"]');

        if(arguments[0] !== undefined){
            switch_code = arguments[0];
        }

        if(arguments[1] !== undefined){
            switch_select_code = arguments[1];
        }
        $('#query-start_date-wDt').change(function(){
            {
                var start_date = $('#query-start_date-wDt').val();

                var s = new Date(start_date);
                var select = Date.parse(s);

                var a = new Date();
                var y = a.getFullYear();
                var m = a.getMonth() + 1;
                var d = a.getDate();
                var s = y + '/'+ m + '/' +d;
                var n = new Date(s);
                var now = Date.parse(n);
                var b = n.setDate(n.getDate() - <?php echo isset($report_code_save_days) ? $report_code_save_days : 360 ?>);
                var limit = parseInt(b);

                if(select < limit){
                    if(switch_code != undefined && switch_code.val() != ''){
                        switch_code.val('');
                        showMessages_new("[{'field':'','code':101,'msg':'<?php __('If you select the cdrs over [Data Compression -> Number of days to keep code statistics], you can not select field code')?>'}]");
                    }

                    if(switch_select_code != undefined && switch_select_code.is(':selected')){
                        switch_select_code.removeAttr('selected');
                        showMessages_new("[{'field':'','code':101,'msg':'<?php __('If you select the cdrs over [Data Compression -> Number of days to keep code statistics], you can not select field code')?>'}]");
                    }
                    if(switch_code){

                        switch_code.prop('disabled',true);
                    }

                    if(switch_select_code)
                        switch_select_code.prop('disabled', true).css({'cursor':'not-allowed','background-color':'#ccc'})
                } else {
                    if(switch_code)
                        switch_code.prop('disabled',false);
                    if(switch_select_code)
                        switch_select_code.prop('disabled', false).css({'cursor':'pointer','background-color':'#fff'})
                }
            }
        }).trigger('change');
    };

    //限制按时间分组
    $.limit_time = function(){
        var switch_select_time = $('select[name = "group_by_date"]');
        var switch_select_time_hourly = switch_select_time.find('option[value = "YYYY-MM-DD  HH24:00:00"]');
        var switch_select_time_daily = switch_select_time.find('option[value = "YYYY-MM-DD"]');

        $('#query-start_date-wDt').change(function(){
            {
                var start_date = $('#query-start_date-wDt').val();

                var s = new Date(start_date);
                var select = Date.parse(s);

                var a = new Date();
                var y = a.getFullYear();
                var m = a.getMonth() + 1;
                var d = a.getDate();
                var s = y + '/'+ m + '/' +d;
                var n = new Date(s);
                var now = Date.parse(n);
                var b = n.setDate(n.getDate() - <?php echo isset($report_hourly_save_days) ? $report_hourly_save_days : 360 ?>);
                var limit_hourly = parseInt(b);
                var b = n.setDate(n.getDate() - <?php echo isset($report_daily_save_days) ? $report_daily_save_days : 360 ?>);
                var limit_daily = parseInt(b);

                if(select < limit_hourly){
                    if(switch_select_time_hourly.is(':selected')){
                        switch_select_time_hourly.removeAttr('selected');
                        showMessages_new("[{'field':'','code':101,'msg':'<?php __('If you select the cdrs over [Data Compression -> Number of days to keep hourly bucket], you can not select field group by hours')?>'}]");
                    }
                    switch_select_time_hourly.prop('disabled', true).css({'cursor':'not-allowed','background-color':'#ccc'})
                } else {
                    switch_select_time_hourly.prop('disabled', false).css({'cursor':'pointer','background-color':'#fff'})
                }

                if(select < limit_daily){
                    if(switch_select_time_daily.is(':selected')){
                        switch_select_time_daily.removeAttr('selected');
                        showMessages_new("[{'field':'','code':101,'msg':'<?php __('If you select the cdrs over [Data Compression -> Number of days to keep daily bucket], you can not select field group by day')?>'}]");
                    }
                    switch_select_time_daily.prop('disabled', true).css({'cursor':'not-allowed','background-color':'#ccc'})
                } else {
                    switch_select_time_daily.prop('disabled', false).css({'cursor':'pointer','background-color':'#fff'})
                }
            }
        }).trigger('change');
    };

    //sleep函数 毫秒
    $.sleep = function (numberMillis) {
        var now = new Date();
        var exitTime = now.getTime() + numberMillis;
        while (true) {
            now = new Date();
            if (now.getTime() > exitTime)
                return;
        }
    }


    $(function(){
        //处理邮箱地址input
        var input_email = $('input[class *= "custom[email]"]');
        if(input_email.length > 0){
            input_email.each(function(){
                var old = $(this).attr('class');
                var arr = old.split('custom[email]');
                var new_class = arr[0] + 'custom[email],custom[email_chars]' + arr[1];
                $(this).attr('class',new_class);


            });
            input_email.live('input',function() {
                var text = $(this).val();
//                alert(text);
                if(text.indexOf(' ') !== -1 || text.indexOf(',') !== -1){
                    var new_text = text.replace(/\s/g,'');
                    new_text = new_text.replace(/,/g,';');
//                alert(new_text);
                    $(this).val(new_text);
                }

            });
        }

        $(".use-select2").select2({
            dropdownAutoWidth: true,
        });

    });


</script>
<style>

    .ColVis_collection .btn{
        text-align: left;
    }
    .ColVis_collection .btn:hover{
        color:white;
    }

    .btn-group .dropdown-menu > li {
        clear: both;
        color: #333333;
        display: block;
        font-weight: 600;
        line-height: 20px;
        padding: 3px 20px;
        white-space: nowrap;
    }

    .btn-group .dropdown-menu > li:hover, .btn-group .dropdown-menu > li:focus {
        /*background-color: #f5f5f5;*/
        color: #fff;
        text-decoration: none;
        /*font-weight: bolder;*/
    }

    .btn-group > .btn, .btn-group > .dropdown-menu {
        font-size: 14px;
        font-weight: 600;
    }

    ul#show_page_row{
        width: 136px;
        min-width: 136px;
        background-color: #f4f4f4;
    }

    .btn-default:hover{
        color: white;
    }

    #footer{
        position: fixed;
        bottom: 0;left:0;
        width: 100%;
        z-index: 10000;
    }

    #content > .navbar{
        position: fixed;top:0;left:0;
        width: 100%;
        z-index: 10000;
    }

    #content > .breadcrumb{
        margin-top: 45px;
    }

    .cake-sql-log{
        margin-bottom: 60px !important;
    }

    .a_tip:hover{
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
    }

    /*#topmenu-time{*/
    /*margin: 0px;margin-right: 40%;*/
    /*}*/





</style>
<script>
    //最后运行jquery
    $(function(){
        if ($.last_running_function) {
            $.last_running_function();
        }
    })
</script>
</body>
</html>
