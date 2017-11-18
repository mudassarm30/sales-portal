<?php if(checkFeatureElement(FE_Define_Feature_Elements)){ ?>
<html>
   <head>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<script language="javascript">
			$(document).ready(function() {
				$('#fegrid').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": "api/feature/list_script.php",
					lengthMenu: [[5, 10, 15, 20, 50, -1], [5, 10, 15, 20, 50, "All"]],
					columnDefs: [ {
						targets: [ 0 ],
						orderData: [ 0, 1 ]
					}, {
						targets: [ 1 ],
						orderData: [ 1, 0 ]
					} ]
				});
 
				$('#fegrid tbody').on( 'click', 'tr', function () {
					
					var table = $('#fegrid').DataTable();
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					var table = $('#fegrid').DataTable();
					var row = table.row('.selected');
					var data = row.data();
					var name = data[1];
					
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
		</style>
   </head>
   <body ng-app="feApp" ng-controller="feController">
		<div id="container" align="center" style="width: 100%">
			<h3>Feature Elements</h3>
			<table id="contact_form" style="width: 70%">
				<tr>
					<td style="padding-bottom: 20px">
						<table id="fegrid" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Id</th>
									<th>Feature Element</th>
									<th>Created On</th>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" style="background-color: #aaaaaa; padding-top: 10px">
						<form name="feForm" ng-submit="processForm()">
							<input type="text" value="" id="name" name="name" ng-model="formData.name" placeholder="Type a new feature element" style="width: 50%" required />
							<input type="submit" value="Add" />
							<input type="button" value="Delete" onsubmit="return false" id="delete" style="height: 28px" ng-click="deleteFeatureElement()"/>
							<input type="button" value="Update" onsubmit="return false" id="update" style="height: 28px" ng-click="updateFeatureElement()"/>
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
			var app = angular.module('feApp', []);
			
			app.controller('feController', function ($scope, $http) {

				$scope.formData = {};
				$scope.formData.message = "";
				
				$scope.isSelected = function(row){
					
					if(row[0].length == 0){
						alert("Please select a feature element first");
						return false;
					}
					return true;
				}
				
				$scope.processForm = function() {
					$http({
						method  : 'POST',
						url     : 'api/feature/create.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=feature-elements";
					},function (error){
						$scope.formData.message = error.data.data;
					});
				};
				
				$scope.deleteFeatureElement = function(){
					
					var table = $('#fegrid').DataTable();
					var row = table.row('.selected');
						
					if(!$scope.isSelected(row))
						return;
					
					if(confirm("Are you sure you want to delete the selected feature element?")){
						
						var data = row.data();
						var id = data[0];
						table.row('.selected').remove().draw( false );
						$scope.formData.message = "";
						$scope.formData.id = id;
						$http({
							method  : 'POST',
							url     : 'api/feature/delete.php',
							data    : $.param($scope.formData),  
							headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
						}).then(function (data){
						},function (error){
							$scope.formData.message = error.data.data;
						});
					}
				}
				
				$scope.updateFeatureElement = function(){
					
					var table = $('#fegrid').DataTable();
					var row = table.row('.selected');
					
					if(!$scope.isSelected(row))
						return;
					
					var data = row.data();
					var id = data[0];
					$scope.formData.message = "";
					$scope.formData.feature_element_id = id;
					$http({
						method  : 'POST',
						url     : 'api/feature/update.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=feature-elements";
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