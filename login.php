<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
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
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
	<script src="js/jquery-1.10.2.min.js"></script>
</head>
<body ng-app="loginApp" ng-controller="loginController" style="overflow-y: hidden">
	<!--/login-->
	<div class="error_page">
		<!--/login-top-->
		<div class="error-top">
			<h2 class="inner-tittle page" align="center"></h2>
			<div class="login">
				<h3 class="inner-tittle t-inner">Login</h3>
				<form name="loginForm" ng-submit="processForm()">
					<input name="email" ng-model="formData.email" type="email" class="text" value="" placeholder="E-mail address" required> 
					<span ng-show="regForm.email.$invalid" style="color: red; font-size: 10px">Email not valid</span>
					<input ng-model="formData.password" name="password" type="password" value="" ng-required="true">
					<span ng-show="formData.message !== ''" style="color: red; font-size: 10px">{{formData.message}}</span>
					<div class="submit">
					<input type="submit" value="Login">
					</div>
					<div class="clearfix"></div>
					<div class="new">
						<p><a href="reset.php">Forgot Password ?</a></p>
						<p class="sign">Do not have an account ? <a href="register.php">Sign Up</a></p>
						<div class="clearfix"></div>
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
	<script>
		var loginApp = angular.module('loginApp', []);
		
		loginApp.controller('loginController', function ($scope, $http) {

			$scope.formData = {};
			$scope.formData.message = "";
			$scope.processForm = function() {
				$http({
					method  : 'POST',
					url     : 'api/user/login.php',
					data    : $.param($scope.formData),  
					headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
				}).then(function (data){
					location.href = "index.php";
			    },function (error){
					$scope.formData.message = error.data.data;
			    });
			};
		});
	</script>
</body>
</html>