<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title><?php __('Login :: Class4') ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="<?php echo $this->webroot ?>assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="<?php echo $this->webroot ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo $this->webroot ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo $this->webroot ?>assets/css/animate.min.css" rel="stylesheet" />
	<link href="<?php echo $this->webroot ?>assets/css/style.min.css" rel="stylesheet" />
	<link href="<?php echo $this->webroot ?>assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="<?php echo $this->webroot ?>assets/css/theme/default.css" rel="stylesheet" id="theme" />
    <!-- Notyfy Notifications Plugin -->
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.css" rel="stylesheet" />
    <link href="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/themes/default.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo $this->webroot ?>assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->

    <style>
        .banner_description h1 {
            font-size: 44px;
            color: #fff;
            font-family: "RobotoLight", "Arial";
        }

        .colored {
            color: #96bf07;
            font-size: 14px;
        }

        .center-block {
            display: block;
            margin-right: auto;
            margin-left: auto;
        }

        .input-lg-50 {
            width: 50%;
            display: inline-block;
        }

    </style>
</head>
<body class="pace-top bg-white">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade">
	    <!-- begin login -->
        <div class="login login-with-news-feed">
            <!-- begin news-feed -->
            <div class="news-feed">
                <div class="news-image">
                    <?php if (!empty($background)) { ?>
                        <img src="<?=$background?>" class="cover" data-id="login-cover-image" alt="" />
                    <?php } else { ?>
                        <img src="<?php echo $this->webroot; ?>assets/img/login_bg.jpg" class="cover" data-id="login-cover-image" alt="" />
                    <?php } ?>
                </div>
                <div class="news-caption">
                    <?php
                        echo $admin_content;
                    ?>
                </div>
            </div>
            <!-- end news-feed -->
            <!-- begin right-content -->
            <div class="right-content">
                <!-- begin login-header -->
                <div class="login-header">
                    <div class="brand">
                        <!--<span class="logo"></span> Color Admin
                        <small>responsive bootstrap 3 admin template</small>-->
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
                    </div>
                    <!--<div class="icon">
                        <i class="fa fa-sign-in"></i>
                    </div>-->
                </div>
                <!-- end login-header -->
                <!-- begin login-content -->
                <div class="login-content">
                    <form id="myform" action="<?php echo $this->webroot ?>homes/auth_user" method="POST" class="margin-bottom-0">
                        <div class="form-group m-b-15">
                            <input type="text" id="login" class="form-control input-lg" placeholder="Username" name="username"/>
                        </div>
                        <div class="form-group m-b-15">
                            <input type="password" id="password" class="form-control input-lg" placeholder="Password" name="password"/>
                        </div>
                        <div class="checkbox m-b-15">
                            <label>
                                <input type="checkbox" name="remember" id="remember"/> Remember Me
                            </label>
                            <a id="forget-password" href="<?php echo $this->webroot ?>homes/forgot_password" class="text-success">Forgot password</a>
                        </div>

                        <?php if (isset($login_captcha) && $login_captcha !== 'false'): ?>
                            <div class="form-group m-b-15">
                                <div class="input-append">
                                    <?php echo $form->input('captcha', array('maxlength' => 4, 'label' => false, 'div' => false, 'class' => 'form-control input-lg-50 validate[required]','id' =>'captcha', 'placeholder' => 'Verify Code')) ?>
                                    <img style="margin-top:2px;margin-left: 30px;" src="<?php echo $this->webroot ?>homes/validate_code" required/>
                                </div>
                            </div>
                        <?php endif; ?>

<!--                        <div class="row">-->
<!--                            <div class="form-group col-xs-8">-->
<!--                                <input type="text" class="form-control input-lg" placeholder="Captcha" name="captcha"/>-->
<!--                            </div>-->
<!---->
<!--                            <div style="padding-top: 10px; text-align:center;" class="form-group col-xs-4">-->
<!--                                <img src="--><?php //echo $this->webroot ?><!--homes/validate_code"/>-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-success btn-block btn-lg">Sign me in</button>
                        </div>
                        <?php if (isset($allow_registration) && $allow_registration !== 'false'): ?>
                            <div class="m-t-20 m-b-40 p-b-40">
                                Not a member yet? Click <a href="<?php echo $this->webroot ?>signup" class="text-success">here</a> to register.
                            </div>
                        <?php endif; ?>
                        <hr />
                        <?php if ($is_copyright_hypelink): ?>

                        <p class="text-center text-inverse">
                            &copy; DeNovoLab@<?php echo date('Y');?> All Rights Reserved
                        </p>

                        <?php endif;?>
                    </form>
                </div>
                <!-- end login-content -->
            </div>
            <!-- end right-container -->
        </div>
        <!-- end login -->

	</div>
	<!-- end page container -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo $this->webroot ?>assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo $this->webroot ?>assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo $this->webroot ?>assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo $this->webroot ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="<?php echo $this->webroot ?>assets/crossbrowserjs/html5shiv.js"></script>
		<script src="<?php echo $this->webroot ?>assets/crossbrowserjs/respond.min.js"></script>
		<script src="<?php echo $this->webroot ?>assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="<?php echo $this->webroot ?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo $this->webroot ?>assets/plugins/jquery-cookie/jquery.cookie.js"></script>
    <script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>
    <!-- Notyfy Notifications Plugin -->
    <script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.js"></script>
    <script src="<?php echo $this->webroot ?>js/bb-functions.js"></script>
	<!-- ================== END BASE JS ================== -->

	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo $this->webroot ?>assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

    <?php if (isset($login_captcha) && $login_captcha == true): ?>
        <script>
            $(function () {
                $("#myform").submit(function (e) {
                    if ($("#captcha").val().length == 0) {
                        showMessages_new("[{'code':101,'msg':' Please input Verify Code!'}]");
                        return false;
                    }
                    return true;
                });
            });
        </script>
    <?php endif; ?>

    <?php
    $msg = $session->read('login_failed');
    if (!empty($msg))
    {
        $session->del('login_failed');
        ?>
        <?php if (isset($msg)): ?>
        <div id="showmessages" style="text-align: center;"></div>
        <script type="text/javascript">

            jQuery.jGrowl.defaults.position = 'top-center';
            notyfy({
                text: "<?php echo $msg ?>",
                type: 'error' // alert|error|success|information|warning|primary|confirm
            });
            //            jGrowl_to_notyfy("<?php echo $msg ?>", {theme: 'jmsg-error'});

        </script>
    <?php endif ?>
        <?php
    }
    ?>

	<script>
		$(document).ready(function() {
			App.init();
		});

        <?php if ($login_fit_image) { ?>
        // image fill start
        $("head").append('<style type="text/css"></style>');
        var newStyleElement = $("head").children(':last');
        $(window).resize(function () {
            newStyleElement.html('.cover{height: '+$('.news-feed').height()+'px;object-fit: cover;}');
        });
        $(window).resize();
        // image fill end
        <?php } ?>
	</script>
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
    <script>
        /**
         *
         * @param name
         * @param value
         * @param days
         */
        function createCookie(name,value,days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime()+(days*24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
            }
            else var expires = "";
            document.cookie = name+"="+value+expires+"; path=/";
        }

        /**
         *
         * @param name
         * @returns {*}
         */
        function readCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }

        /**
         *
         * @param name
         */
        function eraseCookie(name) {
            createCookie(name,"",-1);
        }
    </script>
    <script>
        $( function() {
            let login = readCookie("login");
            let password = readCookie("password");
            if(login && password) {
                $("#remember").prop("checked", true);
                $("#login").val(login);
                $("#password").val(password);
            }
        });

        $(document).ready(function(){
           $("#myform").submit(function(e) {
               if($("#remember").is(':checked')) {
                   createCookie("login", $("#login").val(), 3600);
                   createCookie("password", $("#password").val(), 3600);
               }
           });
        });
    </script>

</body>
</html>