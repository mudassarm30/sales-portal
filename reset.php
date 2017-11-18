<?php include_once __DIR__ . "/api/common/common.php"; ?>
<?php
include_once __DIR__ . "/api/common/common.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login :: Enterprize Indexing and Search</title>
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="Enteris, Enterprize, Indexing, Search, Desktop" name="keywords">
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css'><!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css'><!-- Graph CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"><!-- jQuery -->
	<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'><!-- lined-icons -->
	<link href="css/icon-font.min.css" rel="stylesheet" type='text/css'><!-- //lined-icons -->

	<script src="js/jquery-1.10.2.min.js">
	</script><!--clock init-->
	<script language="javascript">
		function resetPassword(){
			var email = $.trim($("#email").val());
			
			if(email !== ""){
				$.post( "api/password/user-reset.php", { email: email}).done(function( data ) {
					console.log(data);
					alert("Password reset email is sent");
					location.href = "login.php";
				}).error(function(data){
					alert("User does not exist");
				});
			}
			else{
				alert("Please provide your email address");
			}
		}
	</script>
</head>
<body>
	<!--/login-->
	<div class="error_page">
		<!--/login-top-->
		<div class="error-top">
			<h2 class="inner-tittle page" align="center"></h2>
			<div class="login">
				<h4 class="inner-tittle t-inner">Enter your email that is used for signup</h4>
				<form onsubmit="return false;">
					<input class="text" id="email" placeholder="Email Address" type="text" required> 
					<div class="submit">
						<input onclick="resetPassword()" type="submit" value="Submit">
					</div>
					<div class="clearfix"></div>
					<div class="new">
						<p><a href="login.php">Back to login page</a></p>
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
</body>
</html>