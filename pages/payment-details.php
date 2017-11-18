<?php if(checkFeatureElement(FE_Update_Payment_Details)){ ?>
<html>
   <head>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<script language="javascript">
			$(document).ready(function() {
				$('#pmgrid').DataTable( {
					lengthMenu: [[-1], ["All"]],
					columnDefs: [ {
						targets: [ 0 ],
						orderData: [ 0, 1 ]
					}, {
						targets: [ 1 ],
						orderData: [ 1, 0 ]
					} ]
				});
 
				function updateMethodFields(fields){
					for(var i=0; i<fields.length; i++){
						var field = $.trim(fields[i]);
						
						if(field !== ""){
							$("#fieldrow"+(i+1)).show();
							$("#fieldcaption"+(i+1)).html(field);
						}
						else{
							$("#fieldrow"+(i+1)).hide();
							$("#fieldcaption"+(i+1)).html("");
						}
					}
				}
				
				$('#pmgrid tbody').on( 'click', 'tr', function () {
					
					var table = $('#pmgrid').DataTable();
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					var table = $('#pmgrid').DataTable();
					var row = table.row('.selected');
					var data = row.data();
					var id = data[0];
					var name = data[1];
					var payment_method_id = $("#payment_method_id_"+id).data("payment_method_id");
					
					$("#name").val(name);	
					$('#payment_method').val(payment_method_id);
					var fields = $('#payment_method').find(':selected').data('fields').fields;
					updateMethodFields(fields);	

					$.get( "api/payment-detail/get.php?id=" + id, function( data ) {
						data = JSON.parse(data);
						$("#fieldval1").val(data.field1.value);
						$("#fieldval2").val(data.field2.value);
						$("#fieldval3").val(data.field3.value);
						$("#fieldval4").val(data.field4.value);
						$("#fieldval5").val(data.field5.value);
						$("#fieldval6").val(data.field6.value);
						$("#fieldval7").val(data.field7.value);
						$("#fieldval8").val(data.field8.value);
						$("#fieldval9").val(data.field9.value);
						$("#fieldval10").val(data.field10.value);
					});					
				} );
				
				$('#payment_method').on('change', function() {
					
					var value = this.value;
					
					if(value !== ""){
						var data = ($(this).find(':selected').data('fields'));
						var fields = data.fields;
						updateMethodFields(fields);						
					}
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
			#fields_form{
				border-top: 5px solid #aaaaaa;
				width:  80%;
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
			<h3>Payment Details</h3>
			<form name="feForm" ng-submit="processForm()">
				<table id="contact_form" style="width: 70%">
					<tr>
						<td style="padding-bottom: 20px">
							<table id="pmgrid" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Id</th>
										<th>Details Name</th>
										<th>Username</th>
										<th>Payment Method</th>
										<th>Created On</th>
									</tr>
								</thead>
								<tbody>
									<?php include_once(__DIR__ . "/../api/payment-detail/list.php"); ?>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td align="center">
							<div style="padding-top: 3px; padding-bottom: 3px">
								<select id="payment_method" name="payment_method" ng-model="formData.payment_method" style="height: 24px; width: 130px"><option value="">Payment Method</option><?php include_once(__DIR__ . "/../api/payment-detail/list_options.php"); ?></select>
							</div>
							<table id="fields_form" >
								<?php for($i=1; $i<=10; $i++){ ?>
								<tr id="fieldrow<?=$i?>" style="display:none">
									<td id="fieldcaption<?=$i?>">Field <?=$i?>:</td>
									<td><input style="width: 100%" id="fieldval<?=$i?>" type="text" value="" ng-model="formData.field<?=$i?>" placeholder="" /></td>
								</tr>
								<?php } ?>
							</table>
						</td>
					</tr>
					<tr>
						<td align="right" style="background-color: #aaaaaa; padding-top: 10px">
							<input type="submit" value="Add" />
							<input type="button" value="Delete" onsubmit="return false" id="delete" style="height: 28px" ng-click="deletePaymentdetail()"/>
							<input type="button" value="Update" onsubmit="return false" id="update" style="height: 28px" ng-click="updatePaymentdetail()"/>
							<input type="button" value="Cancel" onsubmit="return false" id="cancel" style="height: 28px" ng-click="cancelPaymentdetail()"/>
						</td>
					</tr>
					<tr>
						<td align="center">
							<span ng-show="formData.message !== ''" style="color: red; font-size: 11px">{{formData.message}}</span>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<script>
			var app = angular.module('pmApp', []);
			
			app.controller('pmController', function ($scope, $http) {

				$scope.formData = {};
				$scope.formData.message = "";
				
				$scope.isSelected = function(row){
					
					if(row[0].length == 0){
						alert("Please select a payment detail first");
						return false;
					}
					return true;
				}
				
				$scope.processForm = function() {
					$http({
						method  : 'POST',
						url     : 'api/payment-detail/create.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=payment-details";
					},function (error){
						$scope.formData.message = error.data.data;
					});
				};
				
				$scope.cancelPaymentdetail = function(){
					location.reload();
				}
				
				$scope.deletePaymentdetail = function(){
					
					var table = $('#pmgrid').DataTable();
					var row = table.row('.selected');
						
					if(!$scope.isSelected(row))
						return;
					
					if(confirm("Are you sure you want to delete the selected payment detail?")){
						
						var data = row.data();
						var id = data[0];
						table.row('.selected').remove().draw( false );
						$scope.formData.message = "";
						$scope.formData.id = id;
						$http({
							method  : 'POST',
							url     : 'api/payment-detail/delete.php',
							data    : $.param($scope.formData),  
							headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
						}).then(function (data){
							for(var i=1; i<=10; i++)
								$("#fieldrow"+i).hide();
						},function (error){
							$scope.formData.message = error.data.data;
						});
					}
				}
				
				$scope.updatePaymentdetail = function(){
					
					var table = $('#pmgrid').DataTable();
					var row = table.row('.selected');
					
					if(!$scope.isSelected(row))
						return;
					
					var data = row.data();
					var id = data[0];
					$scope.formData.message = "";
					$scope.formData.id = id;
					$scope.formData.field1 = $("#fieldval1").val();
					$scope.formData.field2 = $("#fieldval2").val();
					$scope.formData.field3 = $("#fieldval3").val();
					$scope.formData.field4 = $("#fieldval4").val();
					$scope.formData.field5 = $("#fieldval5").val();
					$scope.formData.field6 = $("#fieldval6").val();
					$scope.formData.field7 = $("#fieldval7").val();
					$scope.formData.field8 = $("#fieldval8").val();
					$scope.formData.field9 = $("#fieldval9").val();
					$scope.formData.field10 = $("#fieldval10").val();
					$scope.formData.payment_method = $("#payment_method").val();
						
					$http({
						method  : 'POST',
						url     : 'api/payment-detail/update.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=payment-details";
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