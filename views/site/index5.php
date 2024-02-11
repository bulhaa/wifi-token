<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<meta name="language" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/screen.css" media="screen, projection">    
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="/ecouncil/css/ie.css" media="screen, projection" />
    <![endif]-->

    <?php //<link rel="shortcut icon" type="image/x-icon" href="">  ?>
    <base href="<?= yii\helpers\Url::base() ?>/web/" target="_blank">
        
<style type="text/css" media = "all">
         @font-face {
		  font-family: FontAwesome;
		  src: url(<?= yii\helpers\Url::base() ?>/homepage_includes/fontawesome-webfont.woff2);
		  font-weight: bold;
		}
         @font-face {
		  font-family: Poppins Medium;
		  src: url(<?= yii\helpers\Url::base() ?>/homepage_includes/Poppins-Medium.ttf);
		  font-weight: bold;
		}
         @font-face {
		  font-family: Poppins Regular;
		  src: url(<?= yii\helpers\Url::base() ?>/homepage_includes/Poppins-Regular.ttf);
		  font-weight: bold;
		}
      </style>

    <link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/animate.css">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/hamburgers.min.css">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/util.css">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/main.css">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/jquery-ui.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/print.css" media="print">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/styles.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/main(1).css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/form.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="<?= yii\helpers\Url::base() ?>/homepage_includes/font-awesome.min.css" media="screen, projection">
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/jquery-3.5.1.min.js.download"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/popper.min.js.download"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/bootstrap.min.js.download"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/tilt.jquery.min.js.download"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/main.js.download"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/jquery.min.js.download"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/jquery-ui.min.js.download"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/jquery.ajaxvalidationmessages.js.download"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/textFit.min.js.download"></script>


<link rel="icon" href="<?= yii\helpers\Url::base() ?>/homepage_includes/cropped-beach-32x32.jpg" sizes="32x32" />
<link rel="icon" href="<?= yii\helpers\Url::base() ?>/homepage_includes/cropped-beach-192x192.jpg" sizes="192x192" />
<link rel="apple-touch-icon" href="<?= yii\helpers\Url::base() ?>/homepage_includes/cropped-beach-180x180.jpg" />
<meta name="msapplication-TileImage" content="<?= yii\helpers\Url::base() ?>/homepage_includes/cropped-beach-270x270.jpg" />


<title>Casa Retreat</title>
</head>

<body style="background-color: rgb(249, 248, 253); height: 696px;">

	
	<form class="login100-form validate-form" id="Login_overlay" action="<?= yii\helpers\Url::base() ?>/<?php echo !Yii::$app->user->isGuest ? '?r=site/logout' : '' ?>" method="post" style="position: absolute; display: block; left: 641.39px; top: 309.17px; width: 360.833px; height: 87.9696px;">
		<!-- <div id="username" class="wrap-input100 validate-input" data-validate="Valid username is required: ex@abc.xyz" style="position: absolute; left: 3.02612px; top: 31.2957px; width: 265.542px; height: 36.9511px;">
			<input id="username_input" class="input100" type="text" name="LoginForm[username]" placeholder="Username" autofocus="" style="position: absolute; left: 0px; top: -0.140362px; width: 266.805px; height: 32.8148px;">
			<span id="username_focus" class="focus-input100" style="position: absolute; left: 0px; top: -0.140362px; width: 266.805px; height: 32.8148px;"></span>
			<span class="symbol-input100">
				<i id="username_envelope" class="fa fa-envelope" aria-hidden="true"></i>
			</span>
		</div> -->

        <?php if (Yii::$app->user->isGuest) {?>
            <div id="password" class="wrap-input100 validate-input" data-validate="Password is required" style="position: absolute; left: 3.02612px; top: 70.7217px; width: 265.542px; height: 37.027px;">
                <input id="password_input" class="input100" type="password" name="password" placeholder="Wifi Token" style="position: absolute; left: 0px; top: -0.882602px; width: 266.805px; height: 33.5881px;">
                <span id="password_focus" class="focus-input100" style="position: absolute; left: 0px; top: -0.882602px; width: 266.805px; height: 33.5881px;"></span>
                <!-- <span class="symbol-input100">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </span> -->
            </div>

            <!-- <input value="1519" type="hidden" name="browser_width" id="browser_width">

                    <div id="forgot_password_div" class="text-center p-t-12 pull-right">
                <a id="forgot_password" class="txt2" href="" style="position: absolute; left: 174.655px; top: 106.872px; width: 89.2825px; height: 12.3855px;"><span class="textFitted" style="display: inline-block; font-size: 8px;">
                    Forgot Password?
                </span></a>
            </div> -->

            <div id="login_button_div" class="container-login100-form-btn" style="padding-top: 0px;">
                <button id="login_button" class="btn btn-primary btn-block" style="position: absolute; left: 3.02612px; top: 142.232px; width: 133.149px; height: 32.5046px;">
                    Login
                </button>
            </div>
        <?php }else{ ?>
            <div id="login_button_div" class="container-login100-form-btn" style="padding-top: 0px;">
                <button id="login_button" class="btn btn-primary btn-block" style="position: absolute; left: 3.02612px; top: 142.232px; width: 133.149px; height: 32.5046px;">
                    Logout
                </button>
            </div>
            <h2 >
                <?php echo round(($token->max_allowed - $token->total_used)/(1024*1024)) ?>/<?php
                echo round($token->max_allowed/(1024*1024)) ?> MB remaining
            </h2>
        <?php } ?>

			<!-- <div id="create_account_div" class="text-center" style="position: absolute; left: 0px; top: 179.292px; width: 268.127px; height: 24.8718px;"><span class="textFitted" style="display: inline-block; font-size: 13px;">
				<a class="txt2" href="">
					Create your Account
					<i id="create_account" class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
				</a>
			</span></div> -->
	</form>



<div>
	<img id="background" style="display: block; margin-left: auto; margin-right: auto; height: 696px;" src="<?= yii\helpers\Url::base() ?>/homepage_includes/login_new.png">
</div>

<a id="loader" style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%; background-color: white; z-index: 100; display: none;">
</a>


<script type="text/javascript" language="JavaScript">
  $(document).ready(function() {
    // $(window).bind('resize', set_body_height);
    document.body.style.backgroundColor = "#f9f8fd";
    initialize_reposition(720, 1084);
    $('#username_input').focus();

  });

    function getWidth() {
      return Math.max(
        document.body.scrollWidth,
        document.documentElement.scrollWidth,
        document.body.offsetWidth,
        document.documentElement.offsetWidth,
        document.documentElement.clientWidth
      );
    }

    $('#browser_width').val(getWidth());

	function repositionElements() { // set body height = window height
		var rectObject = document.body.getBoundingClientRect();

		var zoom = 1;

		adjust_control = "Login_overlay";
        if(document.getElementById(adjust_control) != null){
		    document.getElementById(adjust_control).style.display='block';
		}


		repositionElement("Login_overlay", 118/483, 255/727.171875, 495/483, 501/727.171875, 0, 1);



		// repositionElement("username", 4/611.0625, -677/943, 355/611.0625, -543/943);
		// repositionElement("username_input", 0/637.625, -791/943, 368/637.625, -672/943);
		// repositionElement("username_focus", 0/637.625, -791/943, 368/637.625, -672/943);

		repositionElement("password", 4/611.0625, -521/920, 355/611.0625, -390/920);
		repositionElement("password_input", 0/637.625, -808/960, 368/637.625, -684/960);
		repositionElement("password_focus", 0/637.625, -808/960, 368/637.625, -684/960);

		repositionElement("login_error", 4/628.328125, -405/946, 210/628.328125, -354/946, 1);

		// repositionElement("forgot_password", 268/709.359375, -456.33958163542525/1068, 405/709.359375, -405.4710557390109/1068, 1);

		repositionElement("login_button", 4/611.0625, -268/920, 180/611.0625, -120/920);

		// repositionElement("create_account_div", 0/500, -112/752.765625, 290/500, -40/752.765625, 1);
	}

      // function getCoordsRelativeToImage(a,b) {
      // }












</script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/homepage_includes/reposition.js.download"></script>


</body></html>