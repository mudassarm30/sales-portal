<?php include_once __DIR__ . "/api/common/common.php"; ?>
<?php
	session_start();
	
	if(isset($_SESSION["loggedIn"]) || ($_SESSION["loggedIn"] === true)){
		$user = $_SESSION["user"];
		$email = $user->{"email"}->{"value"};
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login :: Enterprize Indexing and Search</title>
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="Enteris, Enterprize, Indexing, Search, Desktop" name="keywords">
	<script type="application/x-javascript">
	addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } 
	</script><!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css'><!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css'><!-- Graph CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"><!-- jQuery -->
	<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'><!-- lined-icons -->
	<link href="css/icon-font.min.css" rel="stylesheet" type='text/css'><!-- //lined-icons -->

	<script src="js/jquery-1.10.2.min.js">
	</script><!--clock init-->
	<?php if(checkFeatureElement(FE_Update_Password)){ ?>
	<script language="javascript">

		function changePassword(){
			var email = "<?=$email?>";
			var old_password = $.trim($("#old_password").val());
			var password = $.trim($("#password").val());
			var confirm  = $.trim($("#confirm").val());
			
			if(email !== ""){
				
				if(password !== confirm){
					alert("Passwords do not match");
					return false;
				}
				
				if(password.length < 12){
					alert("Password should contain atleast 12 characters");
					return false;
				}
				
				$.post( "api/password/admin-reset.php", { email: email, password: password, old_password: old_password}).done(function( data ) {
					console.log(data);
					alert("Password updated and sent in email.");
					location.href = "index.php";
				}).error(function(data){
					console.log(data);
					alert("Current password is wrong.");
					return false;
				});
			}
			else{
				alert("Please provide your email address");
			}
		}
	</script>
	<?php } ?>
</head>
<body>
	<!--/login-->
	<?php if(checkFeatureElement(FE_Update_Password)){ ?>
	<div class="error_page">
		<!--/login-top-->
		<div class="error-top">
			<h2 class="inner-tittle page" align="center"></h2>
			<div class="login">
				<h4 class="inner-tittle t-inner">Change password</h4>
				<form onsubmit="return false;">
					<input type="password" value="" id="old_password" title="Enter old password"> <input type="password" value="" id="password" title="Enter password"> <input type="password" value="" id="confirm" title="Confirm password">
					<div class="submit">
						<input onclick="changePassword()" type="submit" value="Submit">
					</div>
					<div class="clearfix"></div>
					<div class="new">
						<p><a href="index.php">Back to dashboard</a></p>
					</div>
				</form>
			</div>
		</div><!--//login-top-->
	</div><!--//login-->
	<!--footer section start-->
	<div class="footer">
		<div class="error-btn">
		</div>
	</div><!--footer section end-->
	<!--/404-->
	<!--js -->
	<script src="js/jquery.nicescroll.js">
	</script> 
	<script src="js/scripts.js">
	</script> <!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js">
	</script>
	<?php } else {?>
	<h3><?=NO_ACCESS_MESSAGE?></h3>
	<?php } ?>
</body>
</html>
<?php 
	} 
	else{
		header("Location: login.php");
	}
?>