<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie lt-ie9 lt-ie8 lt-ie7 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 7]>
<html class="ie lt-ie9 lt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 8]>
<html class="ie lt-ie9 fluid top-full menuh-top"> <![endif]-->
<!--[if gt IE 8]>
<html class="animations ie gt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if !IE]><!-->
<html class="animations fluid top-full menuh-top"><!-- <![endif]-->
<head>
    <title><?php __('Download Rate :: Class4') ?></title>

    <!-- Meta -->
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>

    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot; ?>common/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $this->webroot; ?>common/bootstrap/css/responsive.css" rel="stylesheet" type="text/css"/>

    <!-- Glyphicons Font Icons -->
    <link href="<?php echo $this->webroot; ?>common/theme/fonts/glyphicons/css/glyphicons.css" rel="stylesheet"/>

    <link rel="stylesheet" href="<?php echo $this->webroot; ?>common/theme/fonts/font-awesome/css/font-awesome.min.css">
    <!--[if IE 7]>
    <link rel="stylesheet"
          href="<?php echo $this->webroot; ?>common/theme/fonts/font-awesome/css/font-awesome-ie7.min.css"><![endif]-->

    <!-- Uniform Pretty Checkboxes -->
    <link
            href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css"
            rel="stylesheet"/>

    <!-- PrettyPhoto -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/gallery/prettyphoto/css/prettyPhoto.css"
          rel="stylesheet"/>

    <!-- Validation Engine -->
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>common/validationEngine/validationEngine.jquery.css">
    <link href="<?php echo $this->webroot ?>css/jquery.jgrowl.css" media="all" rel="stylesheet" type="text/css"/>

    <!-- JQuery -->
    <script src="<?php echo $this->webroot; ?>js/jquery-1.10.1.min.js"></script>
    <script src="<?php echo $this->webroot; ?>js/jquery-migrate-1.2.1.min.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/html5shiv.js"></script>
    <![endif]-->

    <!-- Main Theme Stylesheet :: CSS -->
    <link href="<?php echo $this->webroot; ?>common/theme/css/style-default-menus-dark.css?1374506526" rel="stylesheet"
          type="text/css"/>


    <!-- FireBug Lite -->
    <!-- <script src="https://getfirebug.com/firebug-lite-debug.js"></script> -->

    <!-- LESS.js Library -->
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/less.min.js"></script>

    <!-- Global -->
    <script>
        //<![CDATA[
        var basePath = '',
            commonPath = '<?php echo $this->webroot; ?>common/';

        // colors
        var primaryColor = '#e5412d',
            dangerColor = '#b55151',
            successColor = '#609450',
            warningColor = '#ab7a4b',
            inverseColor = '#45484d';

        var themerPrimaryColor = primaryColor;
        //]]>
    </script>
    <style>
        .span6{
            font-weight: 400;
        }
    </style>

    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.css"
          rel="stylesheet"/>
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/themes/default.css"
          rel="stylesheet"/>
</head>
<body class="document-body" style="color:#7faf00;">

<!-- Wrapper -->
<div class="login">

    <div class="container">
        <!-- Box -->

        <?php if(isset($error_flg)): ?>
            <div class="hero-unit well">
                <h1 class="padding-none">
                    <?php
                    $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';

                    if (file_exists($logo_path)) {
                        $logo = $this->webroot . 'upload/images/logo.png';
                    } else {
                        $logo = $this->webroot . 'images/logo.png';
                    }
                    ?>
                    <img src="<?php echo $logo ?>" alt=""/>
                    Ouch! <span><?php echo $error_flg; ?></span></h1>
                <hr class="separator" />
                <!-- Row -->
                <div class="row-fluid">

                    <!-- Column -->
                    <div class="span6">
                        <div class="center">
                            <p>It seems the download page you are looking for is seems to be wrong.</p>
                            <p><?php echo $error_msg; ?></p>
                        </div>
                    </div>
                    <!-- // Column END -->

                    <!-- Column -->
                    <div class="span6">
                        <div class="center">
                            <?php if($session->read('sst_user_id')): ?>
                                <p><?php __('Is this a serious error') ?>?<a href="#myModal_support" data-toggle="modal"><?php __('Let us know') ?></a></p>
                            <?php endif; ?>
                            <div class="row-fluid">
                                <div class="span6">
                                    <a href="<?php echo $this->webroot; ?>homes/login" class="btn btn-icon-stacked btn-block btn-success glyphicons user_add"><i></i><span>Go back to</span><span class="strong"><?php __('Landing Page'); ?></span></a>
                                </div>
                                <div class="span6">
                                    <a href="https://support.denovolab.com" target="_blank" class="btn btn-icon-stacked btn-block btn-danger glyphicons circle_question_mark"><i></i><span>Browse through our</span><span class="strong">Support Centre</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- // Column END -->

                </div>
                <hr />
                <div class="row-fluid">
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="span4 offset2">
                                <b><?php __('Current Datetime'); ?></b>:
                            </div>
                            <div class="span6">
                                <?php echo date('Y-m-d H:i:s'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="span4 offset2">
                                <b><?php __('Client IP'); ?></b>:
                            </div>
                            <div class="span6">
                                <?php echo $download_ip; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <!-- // Row END -->

            </div>
        <?php else: ?>
            <div class="hero-unit well">
                <h1 class="padding-none">
                    <?php
                    $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';

                    if (file_exists($logo_path)) {
                        $logo = $this->webroot . 'upload/images/logo.png';
                    } else {
                        $logo = $this->webroot . 'images/logo.png';
                    }
                    ?>
                    <img src="<?php echo $logo ?>" alt=""/>
                    <span style="color:#7faf00;"><?php __('Download Rate'); ?></span>
                </h1>
                <hr class="separator" />
                <!-- Row -->
                <div class="row-fluid">

                    <!-- Column -->
                    <div class="span6">
                        <div class="center">
                            <p><?php echo $message; ?></p>
                        </div>
                    </div>
                    <!-- // Column END -->

                    <!-- Column -->
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="span4 offset2">
                                <b><?php __('Email Address'); ?></b>:
                            </div>
                            <div class="span6">
                                <?php echo $email_address; ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 offset2">
                                <b><?php __('Email Dated'); ?></b>:
                            </div>
                            <div class="span6">
                                <?php echo $email_date; ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 offset2">
                                <b><?php __('Filename'); ?></b>:
                            </div>
                            <div class="span6">
                                <?php echo $download_file_name; ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 offset2">
                                <b><?php __('Effective Date'); ?></b>:
                            </div>
                            <div class="span6">
                                <?php echo $effective_date; ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 offset2">
                                <b><?php __('Your IP Address'); ?></b>:
                            </div>
                            <div class="span6">
                                <?php echo $download_ip; ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span4 offset2">
                                <b><?php __('Download Deadline'); ?></b>:
                            </div>
                            <div class="span6" style="line-height:93px;">
                                <?php echo $download_deadline; ?>
                            </div>
                        </div>

                    </div>
                    <!-- // Column END -->

                </div>
                <!-- // Row END -->
                <div class="separator"></div>
                <div class="row-fluid center">
                    <form action="<?php echo $this->webroot; ?>download_rate/download" method="POST">
                        <input type="hidden" name="salt" value="<?php echo $this->params['url']['salt'] ?>">
                        <button type="submit" class="btn btn-primary btn-large btn-icon glyphicons ok">
                            <i></i> <?php __('confirm & download')?>
                        </button>
                    </form>
                </div>

            </div>
        <?php endif; ?>
        <!-- // Box END -->


    </div>
</div>


<!-- jQuery Event Move -->
<script
        src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.event.move/js/jquery.event.move.js"></script>
<!-- jQuery Event Swipe -->
<script
        src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.event.swipe/js/jquery.event.swipe.js"></script>

<!-- jQuery Ajaxify -->
<!--[if gt IE 8]><!-->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery-ajaxify/ajaxify-html5.js"></script>
<!--<![endif]-->


<!-- Code Beautify -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/js-beautify/beautify.js"></script>
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/js-beautify/beautify-html.js"></script>

<!-- PrettyPhoto -->
<script
        src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>


<!-- Modernizr -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/modernizr.js"></script>

<!-- Bootstrap -->
<script src="<?php echo $this->webroot; ?>common/bootstrap/js/bootstrap.min.js"></script>

<!-- SlimScroll Plugin -->
<script
        src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.js"></script>

<!-- Holder Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/holder/holder.js?1374506526"></script>

<!-- Uniform Forms Plugin -->
<script
        src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

<!-- MegaMenu -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/megamenu.js?1374506526"></script>

<!-- Common Demo Script -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/signup.js"></script>

<!-- Validation Engine -->
<script src="<?php echo $this->webroot; ?>common/validationEngine/jquery.validationEngine.js"></script>
<script src="<?php echo $this->webroot; ?>common/validationEngine/jquery.validationEngine-en.js"></script>
<script src="<?php echo $this->webroot; ?>common/validationEngine/validateExtend.js"></script>

<!--<script src="<?php echo $this->webroot; ?>common/theme/scripts/class4/common.js"></script>-->
<script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>
<!-- Notyfy Notifications Plugin -->
<link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.css"
      rel="stylesheet"/>
<link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/themes/default.css"
      rel="stylesheet"/>
<!-- Notyfy Notifications Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.js"></script>

<script src="<?php echo $this->webroot; ?>js/bb-functions.js"></script>

</body>
</html>