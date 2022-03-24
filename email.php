<?php// Start our message
$message = '<!DOCTYPE html><html><head>
						<style>
						* {
							font-size: 14px!important;
							font-family: Arial;
						}
						</style>
						<!– [if gte mso 9]>
						<style>
						li {list-style-type:none;}
						</style>
						<![endif]–>

					</head>
				<body>
				<p style="">Hello '.$_POST["name"].',
	<br>
	<br>
	Thank you for your order, we really appreciate your business.</p>
	<table style="width:100%;max-width:720px;margin:0px;">
		<tbody>
			<tr>
				<td style="text-align:left;vertical-align:top;width:60%;">
					<h4 style="margin:0px;">Account Information</h4>
						Name: '.$_POST['name'] .'<br>
						Company Name: '. $_POST['accountno'].'<br>
						Address: '. $_POST['add'] .'<br>
						City: '.$_POST['city'].'<br>
						State: '.$_POST['state'].'<br>
						Zip: '.$_POST['zip'].'<br>
						PO: '.$_POST['customerpo'].'<br>
						Phone: '.$_POST['phonenumber'].'<br>
						Email: '.$_POST['emailadd'].'<br>
					</ul>
				</td>
				<td style="text-align:left;vertical-align:top;width:40%;">
					<h4 style="margin:0px;">Shipping Information</h4>
						Preferred Shipping: '.$_POST['shipmethod'].'<br>
	';

//  Find out if shipping to same address
if ($_POST['shipto'] == 'same') {
	$message .= '
						Company Name: '.$_POST['accountno'].'<br>
						Attn: '.$_POST['name'].'<br>
						Address: '.$_POST['add'].'<br>
						City: '.$_POST['city'].'<br>
						State: '.$_POST['state'].'<br>
						Zip: '.$_POST['zip'].'<br>
					';
} else {
	$message .= '
						Company Name: '.$_POST['shipaccountno'].'<br>
						Attn: '.$_POST['shipattn'].'<br>
						Address: '.$_POST['shipadd'].'<br>
						City: '.$_POST['shipcity'].'<br>
						State: '.$_POST['shipstate'].'<br>
						Zip: '.$_POST['shipzip'].'<br>
					';
}
//  Continue message
$message .= '
				</td>
			</tr>
		</tbody>
	</table>
	<table style="width:100%;max-width:720px;margin:0px;">
		<tbody>
			<tr>
				<td colspan="3">
					<h4 style="text-align:center;margin:0px;">Requested Items</h4>
				</td>
			</tr>
			<tr>
				<td style="width:200px;">
					<h4 style="margin:0px;">Item Number</h4>
				</td>
				<td style="width:400px;">
					<h4 style="margin:0px;">Description</h4>
				</td>
				<td style="width:200px;">
					<h4 style="margin:0px;">Quantity</h4>
				</td>
			</tr>
	';
// Extract $_POST values for each item added to cart
$i = 0;
foreach ($_POST as $key => $value) {
	if (preg_match('@^itemnum@', $key)) {
		$message .= '
			<tr>
				<td>
				<!-- <input required type="text" name="itemnum'.$i.'" id="itemnum'.$i.'" style="border:0px; -size:10pt; font-weight: normal" size="15" tabindex="19" maxlength="30" autocomplete="on" value="'. $value.'">-->
				'.$value.'
				</td>
		';
		if (isset($items)) {
			$items++;
		} else {
			$items=1;
		};
	}

	if (preg_match('@^itemdes@', $key)) {
		$message .= '
				<td>
					<!--<input required type="text" name="itemdesc'.$i.'" id="itemdesc'.$i.'" style="border:0px; font-size:10pt; font-weight: normal;" size="56" tabindex="20" maxlength="250" autocomplete="on" value="'.$value.'">-->
				'.$value.'
				</td>
		';
	}

	if (preg_match('@^itemquan@', $key)) {
		$message .= '
				<td>
					<!--<input required type="text" name="itemquan'.$i.'" id="itemquan'.$i.'" style="border:0px; font-size:10pt; font-weight: normal" size="7" tabindex="21" maxlength="10" autocomplete="on" value="'.$value.'">-->
				'.$value.'
				</td>
		';
	}
	/*  Only for use when using with prices
	if(preg_match('@^itemprice@', $key)) {
	$message .= '
			<td>
				<input required type="text" name="itemprice'.$i.'" id="itemprice'.$i.'" style="font-size:10pt; font-weight: normal" size="7" tabindex="21" maxlength="10" autocomplete="on" value="'.$value.'">
			</td>
	';
	}
	*/
	$i++;
}
$message .= '
			</tr>
			<tr>
				<td colspan="3">
					<h4 style="text-align:center;margin:0px;">Additional Comments</h4>
					<p style="text=align:left;margin:0px;">'.$_POST['addcomments'].'</p>
				</td>
			</tr>
		</tbody>
	</table>
	';
$message .= '    <br>
					<p style="margin:0px;">Please keep this for your records.
					<br>
	';
$message .= '
					Thank you,
	';
//Tracking Code
$message .= '
	<img src="http://autoformsandsupplies.com/fx/email_track.php?code='.$track_code.'"/>
					</body>
					</html>';

?>
