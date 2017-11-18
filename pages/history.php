<?php if(checkFeatureElement(FE_See_History)){ ?>
<html>
   <head>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<script language="javascript">
			$(document).ready(function() {
				$('#historygrid').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": "api/history/list_script.php",
					lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]],
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
				
				$('#historygrid tbody').on( 'click', 'tr', function () {
					
					var table = $('#historygrid').DataTable();
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					var table = $('#historygrid').DataTable();
					var row = table.row('.selected');
					var data = row.data();
					var id = data[0];
					var message = $("#message_"+id).val();
					
					$("#message").val(message);				
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
   <body ng-app="historyApp" ng-controller="historyController">
		<div id="container" align="center">
			<h3>History</h3>
			<table id="contact_form"  style="width: 70%">
				<tr>
					<td style="padding-bottom: 20px">
						<table id="historygrid" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Id</th>
									<th>Type</th>
									<th>Message</th>
									<th>Created On</th>
									<th>User</th>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<textarea id="message" style="width: 100%; height: 250px"></textarea>
					</td>
				</tr>
				<tr>
					<td align="right" style="background-color: #aaaaaa; padding-top: 10px">
						<input type="button" value="Delete"  ng-click="deleteHistoryItem()"/>
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
			var app = angular.module('historyApp', []);
			
			app.controller('historyController', function ($scope, $http) {

				$scope.formData = {};
				$scope.formData.message = "";
				
				$scope.isSelected = function(row){
					
					if(row[0].length == 0){
						alert("Please select a history item first");
						return false;
					}
					return true;
				}
				
				$scope.deleteHistoryItem = function(){
					
					var table = $('#historygrid').DataTable();
					var row = table.row('.selected');
						
					if(!$scope.isSelected(row))
						return;
					
					if(confirm("Are you sure you want to delete the selected history item?")){
						
						var data = row.data();
						var id = data[0];
						table.row('.selected').remove().draw( false );
						$scope.formData.message = "";
						$scope.formData.id = id;
						$http({
							method  : 'POST',
							url     : 'api/history/delete.php',
							data    : $.param($scope.formData),  
							headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' }  
						}).then(function (data){
							console.log(data);
						},function (error){
							$scope.formData.message = error.data.data;
						});
					}
				}
			});
		</script>
   </body>
</html>
<?php } else {?>
<h3><?=NO_ACCESS_MESSAGE?></h3>
<?php } ?>