<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9 fluid top-full menuh-top"> <![endif]-->
<!--[if gt IE 8]> <html class="animations ie gt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if !IE]><!--><html class="animations fluid top-full menuh-top"><!-- <![endif]-->
    <head>
        <title><?php echo $title_for_layout; ?> :: <?php __(Configure::read('project_name')) ?></title>

        <!-- Meta -->
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

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
        <link href="<?php echo $this->webroot?>css/jquery.jgrowl.css"	media="all" rel="stylesheet" type="text/css" />

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
        
        <!-- FireBug Lite -->
        <!-- <script src="https://getfirebug.com/firebug-lite-debug.js"></script> -->

        <!-- LESS.js Library -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/less.min.js"></script>
        <script src="<?php echo $this->webroot?>js/jquery.jgrowl.js" type="text/javascript"></script>
        <script src="<?php echo $this->webroot?>js/xtable.js"></script>
        <script src="<?php echo $this->webroot?>js/bb-functions.js"></script>
        <script src="<?php echo $this->webroot?>js/bb-interface.js"></script>
        <script src="<?php echo $this->webroot?>js/util.js"></script>
        <script src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js" type="text/javascript"></script>

    <script type="text/javascript">
    //<![CDATA[
    var currentTime = 1285402039;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]>
    </script>
    
    <style>
/*
.list-meta-ipp, .list-meta-ippa {
	margin-left: 0;
	width: 50px;
}
#advsearch {
    display: block;
    margin-bottom: 10px;
    margin-top: -10px;
    position: relative;
}
#title-search-adv {
	background: url("../images/title-search-adv.png") no-repeat scroll 0 5px transparent;
	cursor: pointer;
	display: none;
	height: 23px;
	opacity: 0.5;
	width: 16px;
}
.in-text, .in-password, .in-textarea {
	-moz-border-radius: 4px 4px 4px 4px;
	background: url("../images/input-bg.png") repeat-x scroll 0 0 #FEFEFD;
	border: 1px solid #AEBAC3;
	color: #30353A;
	padding: 2px;
	width: 100px;
	height:25px;
}
*/
</style>
    </head>
    <body>