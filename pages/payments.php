<?php if(checkFeatureElement(FE_See_Payments)){ ?>
<html>
   <head>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
		<script language="javascript">
			$(document).ready(function() {
				$('#example').DataTable( {
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
   <body>
		<div id="container" align="center">
			<h3>Payments</h3>
			<table id="contact_form" style="width: 70%">
				<tr>
					<td style="padding-bottom: 20px">
						<table id="example" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Id</th>
									<th>Payment Date</th>
									<th>Due Date</th>
									<th>Payment Method</th>
									<th>User</th>
									<th>Amount</th>
									<th>Currency</th>
									<th>Subscription</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>3rd Oct 2014</td>
									<td>3rd Oct 2014</td>
									<td>Paypal</td>
									<td>Azhar Majeed</td>
									<td>450</td>
									<td>USD</td>
									<td>Basic</td>
								</tr>
								<tr>
									<td>2</td>
									<td>3rd Oct 2014</td>
									<td>3rd Oct 2014</td>
									<td>Paypal</td>
									<td>Azhar Majeed</td>
									<td>450</td>
									<td>USD</td>
									<td>Basic</td>
								</tr>
								<tr>
									<td>3</td>
									<td>3rd Oct 2014</td>
									<td>3rd Oct 2014</td>
									<td>Paypal</td>
									<td>Azhar Majeed</td>
									<td>450</td>
									<td>USD</td>
									<td>Basic</td>
								</tr>
								<tr>
									<td>4</td>
									<td>3rd Oct 2014</td>
									<td>3rd Oct 2014</td>
									<td>Paypal</td>
									<td>Azhar Majeed</td>
									<td>450</td>
									<td>USD</td>
									<td>Basic</td>
								</tr>
								<tr>
									<td>5</td>
									<td>3rd Oct 2014</td>
									<td>3rd Oct 2014</td>
									<td>Paypal</td>
									<td>Azhar Majeed</td>
									<td>450</td>
									<td>USD</td>
									<td>Basic</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" style="background-color: #aaaaaa; padding-top: 10px">
						<input type="button" value="Delete" />
					</td>
				</tr>
			</table>
		</div>
   </body>
</html>
<?php } else {?>
<h3><?=NO_ACCESS_MESSAGE?></h3>
<?php } ?>