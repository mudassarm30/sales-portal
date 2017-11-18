<?php if(checkFeatureElement(FE_Define_Subscriptions)){ ?>
<html>
   <head>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<script language="javascript">
			$(document).ready(function() {
				$('#subscriptiongrid').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": "api/subscription/list_script.php",
					lengthMenu: [[5, 10, 15, 20, 50, -1], [5, 10, 15, 20, 50, "All"]],
					columnDefs: [ {
						targets: [ 0 ],
						orderData: [ 0, 1 ]
					}, {
						targets: [ 1 ],
						orderData: [ 1, 0 ]
					}]
				});
				
				$('#subscriptiongrid tbody').on( 'click', 'tr', function () {

					var table = $('#subscriptiongrid').DataTable();
					var selected = table.$('tr.selected');
					$(".feature_element").prop('disabled', false);
					if(selected.length > 0)
						selected.removeClass('selected');
					
					$(this).addClass('selected');
					var row = table.row('.selected');
					var data = row.data();
					var id = data[0];
					var name = data[1];
					var storage = data[2];
					var units = data[3];
					var cost = data[4];
					var currency = data[5];
					
					$("#name").val(name);
					$("#storage").val(storage);
					$("#units").val(units);
					$("#cost").val(cost);
					$("#currency").val(currency);
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
		</style>
   </head>
   <body ng-app="subscriptionApp" ng-controller="subscriptionController" id="subscriptionContainer">
		<div id="container" align="center">
			<h3>Subscriptions</h3>
			<table id="contact_form"  style="width: 70%">
				<tr>
					<td style="padding-bottom: 20px">
						<table id="subscriptiongrid" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Storage</th>
									<th>Units</th>
									<th>Cost</th>
									<th>Currency</th>
									<th>Created On</th>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" style="background-color: #aaaaaa; padding-top: 10px">
						<form name="subscriptionForm" ng-submit="processForm()">
							<input type="text"  value="" id="name" name="name" ng-model="formData.name" placeholder="Unique Name" style="width: 25%" required>
							<input type="text"  value="" id="storage" name="storage" ng-model="formData.storage" placeholder="Storage" style="width: 10%" required />
							<input type="text"  value="" id="units" name="units" ng-model="formData.units" placeholder="Units" style="width: 10%" required />
							<input type="text"  value="" id="cost" name="cost" ng-model="formData.cost" placeholder="Cost" style="width: 10%" required />
							<input type="text"  value="" id="currency" name="currency" ng-model="formData.currency" placeholder="Currency" style="width: 10%" required />
							<input type="submit"  id="addBtn" value="Add" />
							<input type="button"  value="Delete" onsubmit="return false" id="updateBtn" style="height: 28px" ng-click="deleteSubscription()"/>
							<input type="button"  value="Update" onsubmit="return false" id="updateBtn" style="height: 28px" ng-click="updateSubscription()"/>
						</form>
					</td>
				</tr>
				<tr>
					<td align="center">
						<span ng-show="formData.message !== ''" style="color: red; font-size: 11px">{{formData.message}}</span>
					</td>
				</tr>
			</table>
		</div>
		<script>
			var app = angular.module('subscriptionApp', []);
			
			app.controller('subscriptionController', function ($scope, $http) {

				$scope.formData = {};
				$scope.formData.message = "";
				
				$scope.isSelected = function(row){
					
					if(row[0].length == 0){
						alert("Please select a subscription first");
						return false;
					}
					return true;
				}
				
				$scope.processForm = function() {
					$scope.formData.name = $("#name").val();
					$scope.formData.storage = $("#storage").val();
					$scope.formData.units = $("#units").val();
					$scope.formData.cost = $("#cost").val();
					$scope.formData.currency = $("#currency").val();
					$http({
						method  : 'POST',
						url     : 'api/subscription/create.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=subscriptions";
					},function (error){
						$scope.formData.message = error.data.data;
					});
				};
				
				$scope.deleteSubscription = function(){
					
					var table = $('#subscriptiongrid').DataTable();
					var row = table.row('.selected');
					
					if(!$scope.isSelected(row))
						return;
					
					if(confirm("Are you sure you want to delete the selected subscription?")){
						
						var data = row.data();
						var id = data[0];
						$scope.formData.id = id;
						$scope.formData.message = "";
						$http({
							method  : 'POST',
							url     : 'api/subscription/delete.php',
							data    : $.param($scope.formData),  
							headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
						}).then(function (data){
							if( (typeof data.data === "string") && (data.data.indexOf("ERROR") !== -1) )
								$scope.formData.message = "Subscription could not be deleted";
							else{
								var table = $('#subscriptiongrid').DataTable();
								var row = table.row('.selected');
								table.row('.selected').remove().draw( false );
								
								var table = $('#subscriptiongrid').DataTable();
								var selected = table.$('tr.selected');
								
								if(selected.length > 0)
									selected.removeClass('selected');
								
								$("#name").val("");
								$("#storage").val("");
								$("#units").val("");
								$("#cost").val("");
								$("#currency").val("");
							}
						},function (error){
							$scope.formData.message = error.data.data;
						});
					}
				}
				
				$scope.updateSubscription = function(){
					
					var table = $('#subscriptiongrid').DataTable();
					var row = table.row('.selected');
					
					if(!$scope.isSelected(row))
						return;
					
					var data = row.data();
					var id = data[0];
					$scope.formData.message = "";
					$scope.formData.subscription_id = id;
					$scope.formData.name = $("#name").val();
					$scope.formData.storage = $("#storage").val();
					$scope.formData.units = $("#units").val();
					$scope.formData.cost = $("#cost").val();
					$scope.formData.currency = $("#currency").val();
					
					$http({
						method  : 'POST',
						url     : 'api/subscription/update.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=subscriptions";
					},function (error){
						$scope.formData.message = error.data.data;
					});
				}
			});
		</script>
   </body>
</html>
<?php } else {?>
<h3><?=NO_ACCESS_MESSAGE?></h3>
<?php } ?>