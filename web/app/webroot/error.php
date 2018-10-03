<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7 fluid top-full menuh-top sticky-top sidebar sidebar-full sidebar-collapsible sidebar-width-mini sticky-sidebar sidebar-hat"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8 fluid top-full menuh-top sticky-top sidebar sidebar-full sidebar-collapsible sidebar-width-mini sticky-sidebar sidebar-hat"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9 fluid top-full menuh-top sticky-top sidebar sidebar-full sidebar-collapsible sidebar-width-mini sticky-sidebar sidebar-hat"> <![endif]-->
<!--[if gt IE 8]> <html class="animations ie gt-ie8 fluid top-full menuh-top sticky-top sidebar sidebar-full sidebar-collapsible sidebar-width-mini sticky-sidebar sidebar-hat"> <![endif]-->
<!--[if !IE]><!--><html class="animations fluid top-full menuh-top sticky-top sidebar sidebar-full sidebar-collapsible sidebar-width-mini sticky-sidebar sidebar-hat"><!-- <![endif]-->
    <head>
        <title>Class4</title>

        <!-- Meta -->
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

        <!-- Bootstrap -->
        <link href="common/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="common/bootstrap/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- Glyphicons Font Icons -->
        <link href="common/theme/fonts/glyphicons/css/glyphicons.css" rel="stylesheet" />

        <link rel="stylesheet" href="common/theme/fonts/font-awesome/css/font-awesome.min.css">
        <!--[if IE 7]><link rel="stylesheet" href="common/theme/fonts/font-awesome/css/font-awesome-ie7.min.css"><![endif]-->

        <!-- Uniform Pretty Checkboxes -->
        <link href="common/theme/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css" rel="stylesheet" />

        <!-- PrettyPhoto -->
        <link href="common/theme/scripts/plugins/gallery/prettyphoto/css/prettyPhoto.css" rel="stylesheet" />

        <!-- JQuery -->
        <script src="<?php echo $this->webroot; ?>js/jquery-1.10.1.min.js"></script>
        <script src="<?php echo $this->webroot; ?>js/jquery-migrate-1.2.1.min.js"></script>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
      <script src="common/theme/scripts/plugins/system/html5shiv.js"></script>
    <![endif]-->

        <!-- Main Theme Stylesheet :: CSS -->
        <link href="common/theme/css/style-default-menus-dark.css?1374506528" rel="stylesheet" type="text/css" />


        <!-- FireBug Lite -->
        <!-- <script src="https://getfirebug.com/firebug-lite-debug.js"></script> -->

        <!-- LESS.js Library -->
        <script src="common/theme/scripts/plugins/system/less.min.js"></script>

        <!-- Global -->
        <script>
            //<![CDATA[
            var basePath = '',
                    commonPath = 'common/';

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

                <!-- Box -->
                <div class="hero-unit well">
                    <h1 class="padding-none">Ouch! <span>database error</span></h1>
                    <hr class="separator" />
                    <!-- Row -->
                    <div class="row-fluid">

                        <!-- Column -->
                        <div class="span7">
                            <div class="center">
                                <p class="btn btn-icon-stacked btn-block btn-danger glyphicons circle_question_mark">It seems the system you are looking for is not allowed to load. The database configuration seems to be some mistake.</p>
                            </div>
                        </div>
                        <!-- // Column END -->

                        <!-- Column -->
                        <div class="span5">
                            <div class="center">
                                <p>The configuration has been repaired completed?</p>
                                <div class="row-fluid">
                                    <div class="span8">
                                        <a href="<?php echo str_replace('/error.php', '/homes/login',$_SERVER['REQUEST_URI']); ?>" class="btn btn-icon-stacked btn-block btn-success glyphicons user_add"><i></i><span>Go back to</span><span class="strong">Class4's Homepage</span></a>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- // Column END -->

                    </div>
                    <!-- // Row END -->

                </div>
                <!-- // Box END -->

            </div>

        </div>

    </body>
</html>