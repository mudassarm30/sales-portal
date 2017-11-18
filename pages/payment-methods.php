<?php if(checkFeatureElement(FE_Define_Payment_Methods)){ ?>
<html>
   <head>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<script language="javascript">
			$(document).ready(function() {
				$('#pmgrid').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": "api/payment-method/list_script.php",
					lengthMenu: [[5, 10, 15, 20, 50, -1], [5, 10, 15, 20, 50, "All"]],
					columnDefs: [ {
						targets: [ 0 ],
						orderData: [ 0, 1 ]
					}, {
						targets: [ 1 ],
						orderData: [ 1, 0 ]
					} ]
				});
 
				$('#pmgrid tbody').on( 'click', 'tr', function () {
					
					var table = $('#pmgrid').DataTable();
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					var table = $('#pmgrid').DataTable();
					var row = table.row('.selected');
					var data = row.data();
					var name = data[1];
					var id = data[0];
					var fields = $.trim($("#fields_"+id).val());
					
					var json_fields = "";
					
					if(fields === "")
						fields = "{\"fields\": []}";
					
					json_fields = JSON.parse(fields);
					
					for(var i=1; i<=10; i++){
						
						var field = json_fields.fields[i-1];
						$("#field"+i).val(field);
						$("#field"+i).prop('disabled', false);
					}
					
					$("#name").val(name);				
				} );
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
			#fields_form{
				border-top: 5px solid #aaaaaa;
			}
			#fields_form td{
				padding-top: 2px;
				padding-bottom: 2px;
				padding-right: 10px;
				padding-left: 10px;
				font-size: 12px;
				font-weight: bold;
			}
		</style>
   </head>
   <body ng-app="pmApp" ng-controller="pmController">
		<div id="container" align="center" style="width: 100%">
			<h3>Payment Methods</h3>
			<table id="contact_form" style="width: 70%">
				<tr>
					<td style="padding-bottom: 20px">
						<table id="pmgrid" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Id</th>
									<th>Payment Method</th>
									<th>Created On</th>
									<th></th>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table id="fields_form" >
							<?php for($i=1; $i<=10; $i++){ ?>
							<tr>
								<td>Field <?=$i?>:</td>
								<td><input type="text" value="" id="field<?=$i?>" placeholder="Caption for Field <?=$i?>" disabled /></td>
							</tr>
							<?php } ?>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" style="background-color: #aaaaaa; padding-top: 10px">
						<form name="feForm" ng-submit="processForm()">
							<input type="text" value="" id="name" name="name" ng-model="formData.name" placeholder="Type a new payment method" style="width: 50%" required />
							<input type="submit" value="Add" />
							<input type="button" value="Delete" onsubmit="return false" id="delete" style="height: 28px" ng-click="deletePaymentMethod()"/>
							<input type="button" value="Update" onsubmit="return false" id="update" style="height: 28px" ng-click="updatePaymentMethod()"/>
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
			var app = angular.module('pmApp', []);
			
			app.controller('pmController', function ($scope, $http) {

				$scope.formData = {};
				$scope.formData.message = "";
				
				$scope.isSelected = function(row){
					
					if(row[0].length == 0){
						alert("Please select a payment method first");
						return false;
					}
					return true;
				}
				
				$scope.getFields = function(){
					
					var nonempty = "";
					
					for(var i=1; i<=10; i++){
						
						var field = $("#field"+i).val();
						
						if(nonempty !== "")
							nonempty = nonempty + ", ";
						
						nonempty = nonempty + "\"" + (($.trim(field) !== "") ? field : "") + "\"";
					}
					return "{\"fields\": [" + nonempty + "]}";
				}
				
				$scope.processForm = function() {
					
					$scope.formData.fields = $scope.getFields();
					$scope.formData.name = $("#name").val();
					$http({
						method  : 'POST',
						url     : 'api/payment-method/create.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=payment-methods";
					},function (error){
						$scope.formData.message = error.data.data;
					});
				};
				
				$scope.deletePaymentMethod = function(){
					
					var table = $('#pmgrid').DataTable();
					var row = table.row('.selected');
						
					if(!$scope.isSelected(row))
						return;
					
					if(confirm("Are you sure you want to delete the selected payment method?")){
						
						var data = row.data();
						var id = data[0];
						$scope.formData.message = "";
						$scope.formData.id = id;
						$http({
							method  : 'POST',
							url     : 'api/payment-method/delete.php',
							data    : $.param($scope.formData),  
							headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
						}).then(function (data){
							if( (typeof data.data === "string") && (data.data.indexOf("ERROR") !== -1) )
								$scope.formData.message = "Payment method could not be deleted because it is being used by a user";
							else{
								var table = $('#pmgrid').DataTable();
								table.row('.selected').remove().draw( false );
								for(var i=1; i<=10; i++){	
									$("#field"+i).val("");
									$("#field"+i).prop('disabled', true);
								}
							}
						},function (error){
							$scope.formData.message = error.data.data;
						});
					}
				}
				
				$scope.updatePaymentMethod = function(){
					
					var table = $('#pmgrid').DataTable();
					var row = table.row('.selected');
					
					if(!$scope.isSelected(row))
						return;
					
					var data = row.data();
					var id = data[0];
					$scope.formData.message = "";
					$scope.formData.payment_method_id = id;
					$scope.formData.fields = $scope.getFields();
					$scope.formData.name = $("#name").val();
					$http({
						method  : 'POST',
						url     : 'api/payment-method/update.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=payment-methods";
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