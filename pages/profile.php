<?php if(checkFeatureElement(FE_Update_Profile)){ ?>
<?php
include_once(__DIR__ . "/../api/user/get.php");
$user = getUser();
if($user !== null){
?>
<html>
   <head>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<style>
			#container{
				background-color: #ffffff;
				padding: 10px 10px 10px 10px;
			}
			#contact_form{
				border-top: 5px solid #aaaaaa;
			}
			#contact_form td{
				background-color: #dddddd;
				padding-top: 20px;
				padding-bottom: 10px;
				padding-right: 50px;
				padding-left: 50px;
				font-size: 12px;
				font-weight: bold;
			}
		</style>
   </head>
   <body ng-app="profileApp" ng-controller="profileController">
		<div id="container" align="center">
			<h3>User Details</h3>
			<form name="profileForm" ng-submit="processForm()">
				<table id="contact_form" >
					<tr>
						<td>Name:</td>
						<td><input type="text" value="" ng-model="formData.firstname" placeholder="First Name" />&nbsp;&nbsp;<input type="text" value="" ng-model="formData.lastname" placeholder="Last Name" /></td>
					</tr>
					<tr>
						<td>Address:</td>
						<td><input type="text" value="<?=$user["address1"]?>" ng-model="formData.address1" placeholder="Address 1" />&nbsp;&nbsp;<input type="text" value="<?=$user["address2"]?>" ng-model="formData.address2" placeholder="Address 2" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="text" value="<?=$user["zipcode"]?>" ng-model="formData.zipcode" placeholder="Zip Code" />&nbsp;&nbsp;<input type="text" value="<?=$user["city"]?>" ng-model="formData.city" placeholder="City" /></td>
					</tr>
					<tr>
						<td style="padding-bottom: 20px"></td>
						<td style="padding-bottom: 20px"><input type="text" value="<?=$user["state"]?>" ng-model="formData.state" placeholder="State" />&nbsp;&nbsp;<input type="text" value="<?=$user["country"]?>" ng-model="formData.country" placeholder="Country" /></td>
					</tr>
					<tr>
						<td style="background-color: #aaaaaa; padding-top: 10px"></td>
						<td align="right" style="background-color: #aaaaaa; padding-top: 10px"><input type="submit" value="Update" /></td>
					</tr>
				</table>
			</form>
		</div>
		<script>
			var app = angular.module('profileApp', []);
			
			app.controller('profileController', function ($scope, $http) {

				$scope.formData = {"firstname": "<?=$user["firstname"]?>",
								   "lastname":  "<?=$user["lastname"]?>",
								   "address1":  "<?=$user["address1"]?>",
								   "address2":	"<?=$user["address2"]?>",
								   "zipcode":	"<?=$user["zipcode"]?>",
								   "city":		"<?=$user["city"]?>",
								   "state":		"<?=$user["state"]?>", 
								   "country":	"<?=$user["country"]?>"};
								   
				$scope.formData.message = "";
				
				$scope.processForm = function() {
					$http({
						method  : 'POST',
						url     : 'api/user/profile.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=profile";
					},function (error){
						$scope.formData.message = error.data.data;
					});
				};
			});
		</script>
   </body>
</html>
<?php 
}
else{
	header("Location login.php");
}
?>
<?php } else {?>
<h3><?=NO_ACCESS_MESSAGE?></h3>
<?php } ?>