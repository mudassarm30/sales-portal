<?php
	session_start();
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(checkFeatureElement(FE_Renew_Subscription)){
		$url = DB_API_BASE_URL . "/subscription";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
										
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			foreach($rows as $index => $data){
				?>
				<div align="center" style="width: 250px; float: left; margin: 20px;">
					<br/>
					<table id="package" width="250px" border="1">
						<tr>
							<td>
								<?php echo $data->{"values"}->{"name"}->{"value"}; ?>
							</td>
						</tr>
						<tr>
							<td class="package_title" style="font-size: 25px">
								 <?php echo $data->{"values"}->{"currency"}->{"value"} . $data->{"values"}->{"cost"}->{"value"}; ?>
							</td>
						</tr>
						<tr>
							<td>
								Multiple machines license
							</td>
						</tr>
						<tr>
							<td>
								Monthly Payments
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $data->{"values"}->{"storage"}->{"value"} . $data->{"values"}->{"units"}->{"value"}; ?> Storage
							</td>
						</tr>
						<tr>
							<td>
								Private & Public Contents
							</td>
						</tr>
						<tr>
							<td>
								Technical Support
							</td>
						</tr>
					</table>
					<br/>
					<div style="padding-top: 3px; padding-bottom: 3px">
						<select id="payment_method" name="payment_method" ng-model="formData.payment_method" style="height: 24px; width: 130px; font-size: 12px; font-weight: bold"><?php include(__DIR__ . "/../payment-detail/list_options.php"); ?></select>
					</div>
					<br/>
					<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
					  <input type="hidden" name="cmd" value="_xclick">
					  <input type="hidden" name="business" value="<?=ENTERIS_BUSINESS_MARCHANT?>">
					  <input type="hidden" name="item_name" value="<?=$data->{"values"}->{"name"}->{"value"}?> Subscription">
					  <input type="hidden" name="item_number" value="<?php echo $data->{"values"}->{"id"}->{"value"};?>">
					  <input type="hidden" name="custom" value="<?php echo $email; ?>">
					  <input type="hidden" name="amount" value="<?=$data->{"values"}->{"cost"}->{"value"}?>">
					  <input type="hidden" name="tax" value="0">
					  <input type="hidden" name="quantity" value="1">
					  <input type="hidden" name="no_note" value="1">
					  <input type="hidden" name="currency_code" value="USD">
					  <input type="hidden" name="notify_url" value='<?php echo THIS_SERVICE_BASE_URL;?>/api/user/callback.php'>
					  <input type='hidden' name='cancel_return' value='<?php echo THIS_SERVICE_BASE_URL;?>/index.php?page=renew&cancel=true'>
					  <input type='hidden' name='return' value='<?php echo THIS_SERVICE_BASE_URL;?>/index.php?page=renew&return=true'>

					  <!-- Enable override of buyers's address stored with PayPal . -->
					  <input type="hidden" name="address_override" value="1">
					  <!-- Set variables that override the address stored with PayPal. -->
					  <input type="hidden" name="first_name" value="">
					  <input type="hidden" name="last_name" value="">
					  <input type="hidden" name="address1" value="">
					  <input type="hidden" name="city" value="">
					  <input type="hidden" name="state" value="">
					  <input type="hidden" name="zip" value="">
					  <input type="hidden" name="country" value="">
					  <input type="image" name="submit"
						src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_paynow_86x21.png"
						alt="PayPal - The safer, easier way to pay online">
					</form>
				</div>
				<?php
			}
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}