<?php if(checkFeatureElement(FE_Manage_Users)){ ?>
<html>
   <head>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<link href="css/jquery.multiselect.css?id=4" rel="stylesheet" />
		<script src="js/jquery.multiselect.js"></script>
	
		<script language="javascript">
			$(document).ready(function() {
				$('#usergrid').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": "api/user/list_script.php",
					lengthMenu: [[5, 10, 15, 20, 50, -1], [5, 10, 15, 20, 50, "All"]],
					columnDefs: [ {
						targets: [ 0 ],
						orderData: [ 0, 1 ]
					}, {
						targets: [ 1 ],
						orderData: [ 1, 0 ]
					}, {
						targets: [ 4 ],
						orderData: [ 4, 0 ]
					} ]
				});
				
				$('#usergrid tbody').on( 'click', 'tr', function () {

					var table = $('#usergrid').DataTable();
					var selected = table.$('tr.selected');
					$(".feature_element").prop('disabled', false);
					if(selected.length > 0)
						selected.removeClass('selected');
					
					$(this).addClass('selected');
					var row = table.row('.selected');
					var data = row.data();
					var id = data[0];
					var firstname = data[2];
					var lastname = data[3];
					var status = data[4];
					var paymentday = data[6];
					var autopay = data[7];
					
					$("#firstname").prop('disabled', false);
					$("#lastname").prop('disabled', false);
					$("#status").prop('disabled', false);
					$("#paymentday").prop('disabled', false);
					$("#autopay").prop('disabled', false);
					$("#resetBtn").prop('disabled', false);
					$("#deleteBtn").prop('disabled', false);
					$("#updateBtn").prop('disabled', false);
					
					$("#firstname").val(firstname);
					$("#lastname").val(lastname);
					$("#status").val(status);
					$("#paymentday").val(paymentday);
					$("#autopay").prop('checked', (autopay=="true"));
					$('select[multiple]').multiselect('reset');
					
					$.get( "api/role/get_by_user.php?user_id=" + id, function( data ) {
						var roles = $("input[type=checkbox]");
						var rids = JSON.parse(data);
						for(var i=0;i<roles.length; i++){
							var role = roles[i];
							if(jQuery.inArray($(role).val(), rids)>=0)
								$(role).click();
						}
					});				
				});		
			} );
		</script>
		<style>
			#container{
				padding: 10px 10px 10px 10px;
			}
			#contact_form{
				border-top: 5px solid #aaaaaa;
			}
			#contact_form td{
				padding-top: 10px;
				padding-bottom: 10px;
				padding-right: 10px;
				padding-left: 10px;
				font-size: 12px;
				font-weight: bold;
			}
			#regForm input{
				font-size: 12px;
				font-weight: bold;
			}
		</style>
   </head>
   <body ng-app="userApp" ng-controller="userController" id="userContainer">
		<div id="container" align="center">
			<form id="regForm" name="regForm" ng-submit="processUserForm()">
				<input name="firstname" ng-model="formData.firstname" style="height: 24px" type="text" class="text" value="" placeholder="First Name" required>
				<input name="lastname" ng-model="formData.lastname" style="height: 24px" type="text" class="text" value="" placeholder="Last Name" required>
				<input name="email" ng-model="formData.email" style="height: 24px" type="email" class="text" value="" placeholder="E-mail address" required> 
				<input ng-model="formData.password" name="password" type="password" value="" ng-required="true"> 
				<input type="submit" value="Create New" style="height: 26px">
				<p><span ng-show="regForm.email.$invalid" style="color: red; font-size: 10px">Email not valid</span></p>
			</form>
			<h3>Users</h3>
			<table id="contact_form"  style="width: 85%">
				<tr>
					<td style="padding-bottom: 20px">
						<table id="usergrid" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Id</th>
									<th>Email</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Status</th>
									<th>Created On</th>
									<th>Payment Day</th>
									<th>Auto-Pay</th>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" style="padding-top: 20px; padding-bottom: 20px">
						<table>
							<tr>
								<td>
									<div style="height: 24px; width: 350px" align="left" >
										Role: <select id="roles" name="basic[]" multiple="multiple" class="3col active"><?php include_once(__DIR__ . "/../api/role/list_options.php"); ?></select>
									</div>
								</td>
								<td style="padding-bottom: 4px">
									<div style="height: 30px; width: 350px" align="left" >
										Subscription: <select id="subscriptions" name="subscriptions[]" style="height: 30px; width: 350px" class="3col active"><?php include_once(__DIR__ . "/../api/subscription/list_options.php"); ?></select>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" style="background-color: #aaaaaa; padding-top: 10px">
						<form name="userForm" ng-submit="processForm()">
							<input type="text" value="" id="firstname" name="firstname" ng-model="formData.firstname" placeholder="First Name" style="width: 20%" disabled required />
							<input type="text" value="" id="lastname" name="lastname" ng-model="formData.lastname" placeholder="Last Name" style="width: 20%" disabled required />
							<select id="status" name="status" style="height: 24px" ng-model="formData.status" disabled><option value="INACTIVE">Inactive</option><option value="ACTIVE">Active</option></select>
							<select id="paymentday" name="paymentday" style="height: 24px; width: 100px" disabled><option value="">Day of Month for Payment</option><?php for($i=1;$i<29;$i++){?><option value="<?=$i?>"><?=$i?></option><?php }?></select>
							<input type="checkbox" id="autopay" disabled>&nbsp;Autopay</input>
							<input type="button" id="resetBtn" value="Reset Password" ng-click="resetPassword()" disabled />
							<input type="button" id="deleteBtn" value="Delete" ng-click="deleteUser()" disabled />
							<input type="button" id="updateBtn" value="Update" ng-click="updateUser()" disabled />
						</form>
					</td>
				</tr>
			</table>
		</div>
		<div align="center" width="100%"><span ng-show="formData.message !== ''" style="color: red; font-size: 10px; font-weight: bold">{{formData.message}}</span></div>
		<script>
			var app = angular.module('userApp', []);
			
			app.controller('userController', function ($scope, $http) {

				$scope.formData = {};
				$scope.formData.message = "";
				
				$scope.processUserForm = function() {
					$http({
						method  : 'POST',
						url     : 'api/user/register.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=users";
					},function (error){
						alert(error.data.data);
					});
				};
			
				$scope.deleteUser = function(){
					
					if(confirm("Are you sure you want to delete the selected user?")){
						var table = $('#usergrid').DataTable();
						var row = table.row('.selected');
						var data = row.data();
						var id = data[0];
						$scope.formData.user_id = id;
						$http({
							method  : 'POST',
							url     : 'api/user/delete.php',
							data    : $.param($scope.formData),  
							headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
						}).then(function (data){
							if( (typeof data.data === "string") && (data.data.indexOf("ERROR") !== -1) )
								$scope.formData.message = "User could not be deleted";
							else{
								var table = $('#usergrid').DataTable();
								var row = table.row('.selected');
								table.row('.selected').remove().draw( false );
							}
						},function (error){
							$scope.formData.message = error.data.data;
						});
					}
				}
				
				$scope.updateUser = function(){
					
					var table = $('#usergrid').DataTable();
					var row = table.row('.selected');
					var data = row.data();
					var id = data[0];
					$scope.formData.user_id = id;
					$scope.formData.firstname = $("#firstname").val();
					$scope.formData.lastname = $("#lastname").val();
					$scope.formData.status = $("#status").val();
					$scope.formData.paymentday = $("#paymentday").val();
					$scope.formData.autopay = (($("#autopay").prop('checked')===true) ? "1" : "0");
					
					var roles = $("input[type=checkbox]");
					var length = roles.length;
					
					var role_ids = [];
					
					for(var i=0; i<length; i++){
						var role_id = $(roles[i]).val();
						
						if($.isNumeric(role_id) && $(roles[i]).is(":checked"))
							role_ids.push(role_id);
					}
					
					$http({
						method  : 'POST',
						url     : 'api/user/update.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
					},function (error){
						if(typeof error.data === "undefined")
							location.href = "index.php?page=users";
						else
							$scope.formData.message = error.data.data;
					});
					
					$scope.formData = {};
					
					$scope.formData.user_id = id;
					$scope.formData.role_ids = JSON.stringify(role_ids);
					
					$http({
						method  : 'POST',
						url     : 'api/role/set_by_user.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=users";
					},function (error){
						if(typeof error.data === "undefined")
							location.href = "index.php?page=users";
						else
							$scope.formData.message = error.data.data;
					});
				}
				
				$scope.resetPassword = function(){
					
					if(confirm("Are you sure you want to reset password for this user ?")){
						var table = $('#usergrid').DataTable();
						var row = table.row('.selected');
						var data = row.data();
						var id = data[0];
						$scope.formData.user_id = id;
						$http({
							method  : 'POST',
							url     : 'api/password/admin-reset.php',
							data    : $.param($scope.formData),  
							headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
						}).then(function (data){
							console.log(data);
							alert("Password reset email is sent");
						},function (error){
							console.log(error);
							$scope.formData.message = error.data.data;
						});
					}
				}
			});
		</script>
		<script>
			$(function () {
				$('select[multiple].active.3col').multiselect({
					columns: 3,
					placeholder: 'Select Roles',
					search: true,
					searchOptions: {
						'default': 'Search Roles'
					},
					selectAll: true
				});
				
				/*
				$('#subscriptions').multiselect({
					columns: 3,
					placeholder: 'Select Subscription',
					search: true,
					searchOptions: {
						'default': 'Select Subscription'
					},
					selectAll: true
				});
				*/
			});
		</script>
   </body>
</html>
<?php } else {?>
<h3><?=NO_ACCESS_MESSAGE?></h3>
<?php } ?>