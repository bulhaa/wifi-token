<!DOCTYPE html>
<html lang="en">
<head>
	<title>Wifi - Casa Retreat</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
				<form class="login100-form validate-form" action="<?= yii\helpers\Url::base() ?>/index2.php<?php echo !Yii::$app->user->isGuest ? '?r=site/logout' : '' ?>" method="post" >
					<span class="login100-form-title p-b-49">
						<!-- <img style="width: 100%;" src="homepage_includes/Casa-Logo-e1608471105521.png"> -->
					</span>

			        <?php if (Yii::$app->user->isGuest) {?>
						<div class="wrap-input100 validate-input m-b-23" data-validate = "Wifi Token is required">
							<span class="label-input100">Wifi Token</span>
							<input class="input100" type="text" name="token" placeholder="Type your Wifi Token">
							<span class="focus-input100" data-symbol="&#xf206;"></span>
						</div>
			        <?php }else{ ?>


			        	<h4 style="padding-bottom: 20px;" >
			        		<center>
			                <?php 
			                	if($token->daily_allowed > 0){
			                		$used = $token->used_today;
			                		$total = $token->daily_allowed;
			                	}else{
			                		$used = $token->total_used;
			                		$total = $token->max_allowed;
			                	}

			                	$balance = round(($total - $used)/(1024*1024));
			                	$balance = $balance > 0 ? $balance : 0;

			                	$total = round($total/(1024*1024));

			                	if($token->expired)
					                echo 'Wifi Token Expired';
					            else{
					            	if($balance > 0)
					                	echo $balance . ' MB remaining'; 
					                else{
					                	echo 'Speed throttled';
						                $next_reset = strtotime( $token->first_used_at .' +'.($token->last_reset_nDay+1).'days');
						                $next_reset = date_create( date("Y-m-d H:i:s", $next_reset) );
						                $next_reset = date_diff( $next_reset, date_create( date("Y-m-d H:i:s") ) );
						                echo '<br><br>next reset in: '.$next_reset->format('%h hours %i minutes');
						                // echo '<br><br>next reset in: '.$next_reset->format('%h hours %i minutes').' '.$token->first_used_at.date("Y-m-d H:i:s");
					                }
					            }
					                // echo $balance .'/'.$total . ' MB remaining'; 
					            ?>
			            	</center>
			            </h4>
						<!-- <span class="label-input100">Password</span>
						<input class="input100" type="password" name="pass" placeholder="Type your password">
						<span class="focus-input100" data-symbol="&#xf190;"></span> -->
					
					<!-- <div class="text-right p-t-8 p-b-31">
						<a href="#">
							Forgot password?
						</a>
					</div> -->
			        <?php } ?>
					
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn">
								<?php echo !Yii::$app->user->isGuest ? 'Logout' : 'Login' ?>
							</button>
						</div>
					</div>


					<!-- <video width="400" controls>
						<source src="file:///C:/Users/windows/Downloads/videoplayback.mp4" type="video/mp4">
						<source src="mov_bbb.ogg" type="video/ogg">
						Your browser does not support HTML video.
					</video> -->

					<!-- <div class="txt1 text-center p-t-54 p-b-20">
						<span>
							Or Sign Up Using
						</span>
					</div> -->

					<!-- <div class="flex-c-m">
						<a href="#" class="login100-social-item bg1">
							<i class="fa fa-facebook"></i>
						</a>

						<a href="#" class="login100-social-item bg2">
							<i class="fa fa-twitter"></i>
						</a>

						<a href="#" class="login100-social-item bg3">
							<i class="fa fa-google"></i>
						</a>
					</div>

					<div class="flex-col-c p-t-155">
						<span class="txt1 p-b-17">
							Or Sign Up Using
						</span>

						<a href="#" class="txt2">
							Sign Up
						</a>
					</div> -->
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>


</body>
</html>


