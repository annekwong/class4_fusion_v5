<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9 fluid top-full menuh-top"> <![endif]-->
<!--[if gt IE 8]> <html class="animations ie gt-ie8 fluid top-full menuh-top"> <![endif]-->
<!--[if !IE]><!--><html class="animations fluid top-full menuh-top"><!-- <![endif]-->
    <head>
        <title><?php __('Retrieve Password :: Class4') ?></title>

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

        <!-- Validation Engine -->
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>common/validationEngine/validationEngine.jquery.css">
        <link href="<?php echo $this->webroot ?>css/jquery.jgrowl.css"	media="all" rel="stylesheet" type="text/css" />

        <!-- JQuery -->
        <script src="<?php echo $this->webroot; ?>js/jquery-1.10.1.min.js"></script>
        <script src="<?php echo $this->webroot; ?>js/jquery-migrate-1.2.1.min.js"></script>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
      <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/html5shiv.js"></script>
    <![endif]-->

        <!-- Main Theme Stylesheet :: CSS -->
        <link href="<?php echo $this->webroot; ?>common/theme/css/style-default-menus-dark.css?1374506526" rel="stylesheet" type="text/css" />


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
    </head>
    <body class="document-body login">

        <!-- Wrapper -->
        <div id="login">

            <div class="container">

                <div class="wrapper" style="max-width:600px;margin-top:40px;">

                    <h1 class="glyphicons lock">
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
                        <img src="<?php echo $logo ?>" alt=""/>

                    </h1>

                    <!-- Box -->
                    <div class="widget widget-heading-simple widget-body-gray">
                        
                        <div class="widget-body">
                            
                            <!-- Form -->
                            <form id="myform" method="post" action="<?php echo $this->webroot ?>homes/send_email_to_retrieve_password">
                                <input type="hidden" name="lang" value="eng">
                                <label for="username"><b style="font-size: 16px;"><?php __('Please enter your username to have your password reset and emailed to you.'); ?></b></label>
                                <input id="username" type="text" name="username" style="max-width:100%;" class="input-block-level validate[required]" placeholder="<?php __('Your Username'); ?>" required/> 
                                
                                <div class="separator bottom"></div> 
                                <div class="row-fluid">
                                    <div class="span4">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>homes/login"><i></i> <?php __('Back Login')?></a>
                                    </div>
                                    <div class="span4">
                                        
                                    </div>
                                    <div class="span4">
                                        <button class="btn btn-block btn-inverse" type="submit"><?php __('Retrieve Password') ?></button>
                                    </div>
                                </div>
                            </form>
                            <!-- // Form END -->

                        </div>

                    </div>
                    <!-- // Box END -->

                </div>

            </div>

        </div>
        <!-- // Wrapper END -->	

        <!-- jQuery Event Move -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.event.move/js/jquery.event.move.js"></script>

        <!-- jQuery Event Swipe -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery.event.swipe/js/jquery.event.swipe.js"></script>

        <!-- jQuery ScrollTo Plugin -->

        <!-- History.js -->

        <!-- jQuery Ajaxify -->
        <!--[if gt IE 8]><!--><script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/jquery-ajaxify/ajaxify-html5.js"></script><!--<![endif]-->


        <!-- Code Beautify -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/js-beautify/beautify.js"></script>
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/js-beautify/beautify-html.js"></script>

        <!-- PrettyPhoto -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>


        <!-- Modernizr -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/system/modernizr.js"></script>

        <!-- Bootstrap -->
        <script src="<?php echo $this->webroot; ?>common/bootstrap/js/bootstrap.min.js"></script>

        <!-- SlimScroll Plugin -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.js"></script>

        <!-- Holder Plugin -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/other/holder/holder.js?1374506526"></script>

        <!-- Uniform Forms Plugin -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

        <!-- MegaMenu -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/megamenu.js?1374506526"></script>

        <!-- Common Demo Script -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/demo/common.js?1374506526"></script>

        <!-- Validation Engine -->
        <script src="<?php echo $this->webroot; ?>common/validationEngine/jquery.validationEngine.js"></script>
        <script src="<?php echo $this->webroot; ?>common/validationEngine/jquery.validationEngine-en.js"></script>
        <script src="<?php echo $this->webroot; ?>common/validationEngine/validateExtend.js"></script>

        <!--<script src="<?php echo $this->webroot; ?>common/theme/scripts/class4/common.js"></script>-->
        <script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>
        <!-- Notyfy Notifications Plugin -->
        <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.css" rel="stylesheet" />
        <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/themes/default.css" rel="stylesheet" />
        <!-- Notyfy Notifications Plugin -->
        <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.js"></script>
        <script src="<?php echo $this->webroot; ?>js/bb-functions.js"></script>
        
       
<?php
    if (empty($m) || $m == '')
    {
        $m = $session->read('m');
        $session->del('m');
    }
    if (!empty($m))
    {
        ?>	
        <script type="text/javascript">
            showMessages_new("<?php echo $m ?>");
        </script>
    <?php } ?>

        <script type="text/javascript">
            //
            $('#myform').validationEngine();

            //
        </script>

    </body>
</html>