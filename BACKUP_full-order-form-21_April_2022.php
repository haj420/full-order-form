<?php
/*
Plugin Name: Full Order Form
Description: Creates a page titled "Full Order Form" and adds the form to that page. Settings page allows for email(s) manipulation.
Version: 1.0.0
Contributors: haj420
Author: Wm. Kroes
Author URI: https://charwebs.com
License: GPLv2 or later
Text Domain: full_order_form
*/


global $wpdb;
define( 'FOF_PLUGIN_FILE', __FILE__ );
register_activation_hook( FOF_PLUGIN_FILE, 'fof_plugin_activation' );


function fof_plugin_activation() {

  if ( ! current_user_can( 'activate_plugins' ) ) return;

  global $wpdb;

  if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'full-order-form'", 'ARRAY_A' ) ) {

    $current_user = wp_get_current_user();

    // create post object
    $page = array(
      'post_title'  => __( 'Full Order Form' ),
      'post_status' => 'publish',
      'post_author' => $current_user->ID,
      'post_type'   => 'page',
    );

    // insert the post into the database
    wp_insert_post( $page );
  }




}


add_action( 'wp_enqueue_scripts', 'load_plugin_css' );
function load_plugin_css() {
  $plugin_url = plugin_dir_url( __FILE__ );

  wp_enqueue_style( 'fof-style', $plugin_url . 'css/styles.css' );

  wp_enqueue_script( 'fof-js', $plugin_url . 'js/full-order-form.js',  array( 'jquery' ) );
  wp_localize_script( 'fof-js', 'fof_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}

// Add action to allow AJAX to access fof_search function
add_action( "wp_ajax_fof_search", "fof_search" );
add_action( "wp_ajax_nopriv_fof_search", "fof_search" );
// create the function fof_search
function fof_search() {
	global $wpdb;
	//access passed variable
	$term = $_POST['fof-search-input'];
	//search db for term
	// while($result = $wpdb->get_results("SELECT sku, ds FROM catalogNew  WHERE ds LIKE '%".$term."%'")) {
	while($result = $wpdb->get_results("SELECT sku, ds FROM catalogNew")) {

		// return results
		wp_send_json ( $result );
	}
}


// Add action to allow AJAX to access send_message function
add_action( "wp_ajax_send_message", "send_message" );
add_action( "wp_ajax_nopriv_send_message", "send_message" );
// create the function to send email
function send_message() {

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
						';
				if(isset($_POST['distributorName'])) {
					$message .= '
						Distributor Name:'. $_POST['distributorName'].'<br>
						Distributor Email:'. $_POST['distributorEmail'].'<br>
					';
				}
				$message .=	'
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
	if ($_POST['shippingAddress'] == 'Same Address') {
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
						'.get_bloginfo( 'name' ).'
		';
	//Tracking Code
	$message .= '
		<img src="http://autoformsandsupplies.com/fx/email_track.php?code='.$track_code.'"/>
						</body>
						</html>';


	$send_to = "charwebsllc@gmail.com";
	if(isset($_POST['distributorName'])) {
    	$subject = "Distributor Order from ".$_POST['distributorName'];
	} elseif (get_bloginfo('name') !== null) {
		$subject = "Distributor Order from distributor ".get_bloginfo( 'name' );
	}else {
		$subject = "Distributor Order from distributor"; // WHY DOESN'T THIS WORK? .get_bloginfo( 'name' )." ";
	}

$headers = array( 'Content-Type: text/html; charset=UTF-8' );
    $success = wp_mail($send_to,$subject,$message, $headers);
	// wp_mail('charwebsllc@gmail.com', 'test', 'test');
            if ($success) return true;
            else return false;

}


// Add the form content to the new page
add_action( 'the_content', 'fof_append_to_content' );
  function fof_append_to_content()
{

	$content = '
		<form id="fof-form" >
		<input type="hidden" name="action" value="send_message"/>
		<div class="row">
		<div class="sm-d-none md-d-flex col"></div>
	    <div class="fof-form col-sm-12 col-md-6" style="background-color:#FFF3E4;">
				<div class="row fof-title-row">
			        <div class="col">
						<h5 class="modal-title text-left">Full Order Form</h5>
					</div>
				</div>';

				if( 1 === get_current_blog_id() ) :
			    	$content .= '
					<div class="row">
		  			<div class="col-sm-12 col-md-6">
		  				<h5 class="fof-section-title">Distributor Information</h5>
						<div class="form-group">
					      <label for="distributorName">Distributor Name</label>
					      <input type="text" class="form-control" id="fofDistributorName" name="distributorName">

		  			      <label for="distributorEmail">Distributor Email</label>
		  			      <input type="email" class="form-control" id="fofDistributorEmail" name="distributorEmail" >
				    </div>
  		  			<div class="col-sm-12 col-md-6"></div>
				</div>';
			else:
				$content .= '
				<div class="row">
				<div class="col-sm-12 col-md-6">
				<div class="col-sm-12 col-md-6"></div>
			</div>';
			endif;
				$content .= '
				<div class="row mt-5">
  		  			<div class="col-sm-12 col-md-6 border-end border-dark">
		  				<h5 class="fof-section-title">Account Information</h5>
		  			      <label for="name">Your Name</label>
		  			      <input type="text" class="form-control" id="name" name="name">

						  <label for="companyName">Company Name</label>
						  <input type="text" class="form-control" id="companyName" name="accountno">

						  <label for="address">Address</label>
						  <input type="text" class="form-control" id="address" name="add">

						  <label for="city">City/Town</label>
						  <input type="text" class="form-control" id="city" name="city">

						  <div class="row">
							  <div class="col-sm-12 col-md-6">
						    	<label for="state">State</label>
						    	<input type="text" class="form-control" id="state" name="state" >
						  	  </div>
							  <div class="col-sm-12 col-md-6">
							    <label for="zip">Zip Code</label>
							    <input type="text" class="form-control" id="zip" name="zip" >
							  </div>
						  </div>


						  <label for="po">Purchase Order Number</label>
						  <input type="text" class="form-control" id="po" name="customerpo" >

						  <label for="phone">Phone Number</label>
						  <input type="phone" class="form-control" id="phone" name="phonenumber">

						  <label for="email">Email Address</label>
						  <input type="email" class="form-control" id="email" name="emailadd">

					    </div>
					<div class="col-sm-12 col-md-6">
						<h5 class="fof-section-title">Shipping Information</h5>
						<div class="form-group">
						  <label for="shippingMethod">Prefered Shipping Method</label>
						  <select name="shippingMethod" id="shippingMethod" required="">
							  <option value="">Choose Shipping Method</option>
							  <option value="UPS/FedEx Ground">UPS/FedEx Ground</option>
							  <option value="Next Day Air">Next Day Air</option>
							  <option value="Second Day Air">Second Day Air</option>
							  <option value="Three Day Air">Three Day Air</option>
						  </select>

						<div class="form-check">
						  <input class="form-check-input" type="radio" name="shippingAddress" id="shippingAddressSame" value="Same Address" checked>
						  <label class="form-check-label" for="shippingAddressSame">
						    Same Address
						  </label>
						</div>
						<div class="form-check">
						  <input class="form-check-input" type="radio" name="shippingAddress" id="shippingAddressBelow" value="Below">
						  <label class="form-check-label" for="shippingAddressBelow">
						    Shipping Address Below
						  </label>
						</div>
						</div>

						<div id="shipAddressGroup" class="form-group" style="display:none;">
						<label for="attn">Company</label>
						<input type="text" class="form-control" id="shipcompany">

							<label for="shipattn">Attention</label>
							<input type="text" class="form-control" id="attn" name="shipattn">

							<label for="shipaddress">Address</label>
							<input type="text" class="form-control" id="shipaddress" name="shipadd">

							<label for="city">City/Town</label>
							<input type="text" class="form-control" id="shipcity" name="shipcity">

							<div class="row">
								<div class="col-sm-12 col-md-6">
								  <label for="shipstate">State</label>
								  <input type="text" class="form-control" id="shipstate">
								</div>
								<div class="col-sm-12 col-md-6">
								  <label for="shipzip">Zip Code</label>
								  <input type="text" class="form-control" id="shipzip">
								</div>
							</div>
						</div>
					</div>
				</div>
		      </div>
			  <div class="row pt-4"></div>
			  <div class="row bg-white pt-3"></div>
			  <div class="row">
			      <div class="col-12">
				  	<h5 class="fof-section-title">Search For Your Item</h5>
				  </div>
			  </div>
			  <div class="row">
				  <div class="col-10">
					  <input type="text" id="search" name="fof-search-input" list="products" class="fof-search-input mr-3" style="width:97%;" autocomplete="off"/>
					  <datalist id="products"></datalist>
					   <!-- <div id="suggestions"></div> -->
				  </div>
				  <div class="col-2 p-0 text-center">
					 <!-- <button type="button" class="btn btn-secondary fof-search rounded-0">Search</button> -->
					  <button type="button" class="btn btn-secondary fof-clear rounded-0" style="width:76%;">Clear</button>
				  </div>
			  </div>
			  <div class="row pt-5">
				  <div class="col-8">
					 <!-- <input list="products" name="product" class="fof-select">
					    <datalist id="products">
						</datalist> -->
				  </div>
				  <div class="col">
					  <p class="fof-section-title text-center">Use drop down to select item then click plus sign (left) to add to that row</p>
				  </div>
			  </div>
			  <div class="row">
			      <div class="col-2"></div>
				  <div class="col-2 text-left">
   					  <h5>Item #</h5>
				  </div>
				  <div class="col text-center">
					 <h5>Description (Not Required) </h5>
				 </div>
				 <div class="col-2 text-right">
					 <h5>Quantity</h5>
				 </div>
			  </div>
			  <div class="fof-item-list">
   			  <div class="row mt-1">
   				  <div class="col-2 text-center">
					<button class="add-item-btn">+</button>
   				  </div>
   				  <div class="col-2">
   					 <input type="text" name="itemnum0" class="item-num" />
   				 </div>
   				 <div class="col">
					<input type="text" name="itemdesc0" class="item-desc" />
   				 </div>
   				 <div class="col-2">
					<input type="number" name="itemquan0" class="item-quan" />
   				 </div>
			  </div>
   			  <div class="row mt-1">
   				  <div class="col-2 text-center">
					<button class="add-item-btn">+</button>
   				  </div>
   				  <div class="col-2">
   					 <input type="text" name="itemnum1" class="item-num" />
   				 </div>
   				 <div class="col">
					<input type="text" name="itemdesc1" class="item-desc" />
   				 </div>
   				 <div class="col-2">
					<input type="number" name="itemquan1" class="item-quan" />
   				 </div>
			  </div>
			  <div class="row mt-1">
				  <div class="col-2 text-center">
					<button class="add-item-btn">+</button>
				  </div>
				  <div class="col-2">
					 <input type="text" name="itemnum2" class="item-num" />
				 </div>
				 <div class="col">
					<input type="text" name="itemdesc2" class="item-desc" />
				 </div>
				 <div class="col-2">
					<input type="number" name="itemquan2" class="item-quan" />
				 </div>
			  </div>
			  <div class="row mt-1">
				  <div class="col-2 text-center">
					<button class="add-item-btn">+</button>
				  </div>
				  <div class="col-2">
					 <input type="text" name="itemnum3" class="item-num" />
				 </div>
				 <div class="col">
					<input type="text" name="itemdesc3" class="item-desc" />
				 </div>
				 <div class="col-2">
					<input type="number" name="itemquan3" class="item-quan" />
				 </div>
			  </div>
			  <div class="row mt-1">
				  <div class="col-2 text-center">
					<button class="add-item-btn">+</button>
				  </div>
				  <div class="col-2">
					 <input type="text" name="itemnum4" class="item-num" />
				 </div>
				 <div class="col">
					<input type="text" name="itemdesc4" class="item-desc" />
				 </div>
				 <div class="col-2">
					<input type="number" name="itemquan4" class="item-quan" />
				 </div>
			  </div>
			  <div class="row mt-1">
				  <div class="col-2 text-center">
					<button class="add-item-btn">+</button>
				  </div>
				  <div class="col-2">
					 <input type="text" name="itemnum5" class="item-num" />
				 </div>
				 <div class="col">
					<input type="text" name="itemdesc5" class="item-desc" />
				 </div>
				 <div class="col-2">
					<input type="number" name="itemquan5" class="item-quan" />
				 </div>
				 </div>
			  </div>
			  <div class="row mt-2 mb-3">
			    <div class="col text-left">
				  <button class="add-row-btn">Add Row</button>
				  </div>
				  </div>
	  		</div>
		  <div class="sm-d-none md-d-flex col"></div>
		  </div>
		  <div class="row">
		<div class="sm-d-none md-d-flex col"></div>
			  <div class="col sm-col-12 col-md-6 p-0">
			  <label for="comments" class="ps-2">Additional Comments: </label>
			  <textarea name="addcomments" id="fof-comments" rows="5"></textarea>
			 </div>
		<div class="sm-d-none md-d-flex col"></div>
		  </div>
		<div class="row">
	  <div class="sm-d-none md-d-flex col"></div>
	  <div class="fof-form col-sm-12 col-md-6 p-0">
        <button type="button" class="btn btn-danger fof-submit">Submit Order</button>
		<br>
		<p class="fof-section-title ps-2">Freight Will Be Added to Final Invoices</p>
	  </div>
		<div class="sm-d-none md-d-flex col"></div>
		</div>
		</div>
	</form>
	';
	//make sure we are working on our form
    if(get_the_title() === 'Full Order Form' )  {
		return $content;
	}

	// email form

}
