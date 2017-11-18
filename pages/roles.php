<?php if(checkFeatureElement(FE_Define_Roles)){ ?>
<html>
   <head>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<script language="javascript">
			$(document).ready(function() {
				$('#rolegrid').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": "api/role/list_script.php",
					lengthMenu: [[5, 10, 15, 20, 50, -1], [5, 10, 15, 20, 50, "All"]],
					columnDefs: [ {
						targets: [ 0 ],
						orderData: [ 0, 1 ]
					}, {
						targets: [ 1 ],
						orderData: [ 1, 0 ]
					} ]
				});
				
				$('#rolegrid tbody').on( 'click', 'tr', function () {

					var table = $('#rolegrid').DataTable();
					var selected = table.$('tr.selected');
					$(".feature_element").prop('disabled', false);
					if(selected.length > 0)
						selected.removeClass('selected');
					
					$(this).addClass('selected');
					var row = table.row('.selected');
					var data = row.data();
					var id = data[0];
					var name = data[1];
					
					$("#name").val(name);
					
					$.get('api/feature/list_by_role_id.php?role_id='+id, function( data ) {
						var features = JSON.parse(data);
						$(".feature_element").prop('checked', false);
						for(var i=0; i<features.length; i++){
							var fe_id = "#fe_" + features[i];
							$(fe_id).prop('checked', true);
						}
					});
				});					
			} );
			
			function featureToggled(id){
				
				var table = $('#rolegrid').DataTable();
				var row = table.row('.selected');
				var data = row.data();
				var role_id = data[0];
				var fe_id = "#fe_" + id;
				var post_data = {role_id: role_id, feature_element_id: id, operation: 'add'};
				
				if(!$(fe_id).prop('checked')){
					post_data.operation = "delete";
				}
				
				$.post('api/feature/update_role_feature.php', post_data).done(function( data ) {
					console.log(data);
				});
			}
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
			.feature_element{
				
			}
		</style>
   </head>
   <body ng-app="roleApp" ng-controller="roleController" id="roleContainer">
		<div id="container" align="center" style="width: 100%">
			<h3>Roles</h3>
			<table id="contact_form" style="width: 70%">
				<tr>
					<td style="padding-bottom: 20px">
						<table id="rolegrid" class="display" cellspacing="0" width="100%">
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
					<td>
						<div width="70%" align="left">
							<div>
								<h4 align="center">Feature Elements</h4>
								<?php include_once(__DIR__ . "/../api/feature/render_feature_elements.php"); ?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td align="right" style="background-color: #aaaaaa; padding-top: 10px">
						<form name="feForm" ng-submit="processForm()">
							<input type="text" value="" id="name" name="name" ng-model="formData.name" placeholder="Type a new role name" style="width: 50%" required />
							<input type="submit" value="Add" />
							<input type="button" value="Delete" onsubmit="return false" id="delete" style="height: 28px" ng-click="deleteRole()"/>
							<input type="button" value="Update" onsubmit="return false" id="update" style="height: 28px" ng-click="updateRole()"/>
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
			var app = angular.module('roleApp', []);
			
			app.controller('roleController', function ($scope, $http) {

				$scope.formData = {};
				$scope.formData.message = "";
				
				$scope.isSelected = function(row){
					
					if(row[0].length == 0){
						alert("Please select a role first");
						return false;
					}
					return true;
				}
				
				$scope.processForm = function() {
					$http({
						method  : 'POST',
						url     : 'api/role/create.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=roles";
					},function (error){
						$scope.formData.message = error.data.data;
					});
				};
				
				$scope.deleteRole = function(){
					
					var table = $('#rolegrid').DataTable();
						var row = table.row('.selected');
						
					if(!$scope.isSelected(row))
						return;
					
					if(confirm("Are you sure you want to delete the selected role?")){
						
						var data = row.data();
						var id = data[0];
						$scope.formData.id = id;
						$scope.formData.message = "";
						$http({
							method  : 'POST',
							url     : 'api/role/delete.php',
							data    : $.param($scope.formData),  
							headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
						}).then(function (data){
							if( (typeof data.data === "string") && (data.data.indexOf("ERROR") !== -1) )
								$scope.formData.message = "Role could not be deleted because it is either connected with a feature element or a user";
							else{
								var table = $('#rolegrid').DataTable();
								var row = table.row('.selected');
								table.row('.selected').remove().draw( false );
							}
						},function (error){
							$scope.formData.message = error.data.data;
						});
					}
				}
				
				$scope.updateRole = function(){
					
					var table = $('#rolegrid').DataTable();
					var row = table.row('.selected');
					
					if(!$scope.isSelected(row))
						return;
					
					var data = row.data();
					var id = data[0];
					$scope.formData.role_id = id;
					$scope.formData.message = "";
					$http({
						method  : 'POST',
						url     : 'api/role/update.php',
						data    : $.param($scope.formData),  
						headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
					}).then(function (data){
						location.href = "index.php?page=roles";
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