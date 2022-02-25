<?php
/*
Plugin Name: Full Order Form
Description: Creates a page titled "Full Order Form" and adds the form to that page. Setting page allows for email(s)  manipulation.
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
}


// Add the form content to the new page
add_action( 'the_content', 'fof_append_to_content' );
  function fof_append_to_content()
{

	$content = '
		<form id="fof-form" >
		<div class="row">
		<div class="sm-d-none md-d-flex col"></div>
	    <div class="fof-form col-sm-12 col-md-6" style="background-color:#FFF3E4;">
				<div class="row fof-title-row">
			        <div class="col">
						<h5 class="modal-title text-left">Full Order Form</h5>
					</div>
				</div>
				  <div class="row">
		  			<div class="col-sm-12 col-md-6">
		  				<h5 class="fof-section-title">Distributor Information</h5>
						<div class="form-group">
					      <label for="distributorName">Distributor Name</label>
					      <input type="text" class="form-control" id="distributorName" placeholder="ACME, Inc.">

		  			      <label for="distributorEmail">Distributor Email</label>
		  			      <input type="email" class="form-control" id="distributorEmail" placeholder="info@acme.com">
				    </div>
  		  			<div class="col-sm-12 col-md-6"></div>
				</div>
				<div class="row mt-5">
  		  			<div class="col-sm-12 col-md-6 border-end border-dark">
		  				<h5 class="fof-section-title">Account Information</h5>
		  			      <label for="name">Your Name</label>
		  			      <input type="text" class="form-control" id="name" placeholder="Wile E. Coyote">

						  <label for="companyName">Company Name</label>
						  <input type="text" class="form-control" id="companyName" placeholder="Wile E. Corporation">

						  <label for="address">Address</label>
						  <input type="text" class="form-control" id="address" placeholder="123 Any St.">

						  <label for="city">City/Town</label>
						  <input type="text" class="form-control" id="city" placeholder="Desertville">

						  <div class="row">
							  <div class="col-sm-12 col-md-6">
						    	<label for="state">State</label>
						    	<input type="text" class="form-control" id="state" placeholder="AZ">
						  	  </div>
							  <div class="col-sm-12 col-md-6">
							    <label for="zip">Zip Code</label>
							    <input type="text" class="form-control" id="zip" placeholder="00000">
							  </div>
						  </div>


						  <label for="po">Purchase Order Number</label>
						  <input type="text" class="form-control" id="po" placeholder="12345">

						  <label for="phone">Phone Number</label>
						  <input type="phone" class="form-control" id="phone" placeholder="(800) 222-1234">

						  <label for="email">Email Address</label>
						  <input type="email" class="form-control" id="city" placeholder="W.E.Coyote@wileecorp.com">

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
						  <input class="form-check-input" type="radio" name="shippingAddress" id="shippingAddressSame" value="Same Addres" checked>
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
							<label for="attn">Attention</label>
							<input type="text" class="form-control" id="attn" placeholder="John Doe">

							<label for="address">Address</label>
							<input type="text" class="form-control" id="shipaddress" placeholder="123 Any St.">

							<label for="city">City/Town</label>
							<input type="text" class="form-control" id="shipcity" placeholder="Desertville">

							<div class="row">
								<div class="col-sm-12 col-md-6">
								  <label for="state">State</label>
								  <input type="text" class="form-control" id="shipstate" placeholder="AZ">
								</div>
								<div class="col-sm-12 col-md-6">
								  <label for="zip">Zip Code</label>
								  <input type="text" class="form-control" id="shipzip" placeholder="00000">
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
				  	<h5 class="fof-section-title">Requested Items</h5>
				  </div>
			  </div>
			  <div class="row">
				  <div class="col-8">
					  <input type="text" id="search" name="fof-search-input" class="fof-search-input mr-3"/>
					  <!-- <div id="suggestions"></div> -->
				  </div>
				  <div class="col">
					  <button type="button" class="btn btn-secondary fof-search rounded-0">Search</button>
					  <button type="button" class="btn btn-secondary fof-clear rounded-0">Clear</button>
				  </div>
			  </div>
			  <div class="row pt-5">
				  <div class="col-8">
					  <input list="products" name="product" class="fof-select" placeholder="Select One">
					    <datalist id="products">
					  	  <option value=" "  selected="selected"/>
						</datalist>
				  </div>
				  <div class="col">
				         <!--
						  IT MAY BE EASIER TO JUST CREATE AN AUTOCOMPLETE FEATURE RATHER THAN USING THE SELECT/OPTION PATH.
						  -->
					  <p class="fof-section-title text-center">Use drop down to select item then click plus sign (left) to add to that row</p>
				  </div>
			  </div>
			  <div class="row">
			      <div class="col-2"></div>
				  <div class="col-2 text-left">
   					  <h5>Item #</h5>
				  </div>
				  <div class="col text-center">
					 <h5>Description Not Required: also out of your control :) </h5>
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
   					 <input type="text" name="itemnum0" class="item-num" readonly />
   				 </div>
   				 <div class="col">
					<input type="text" name="itemdesc0" class="item-desc" readonly />
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
   					 <input type="text" name="itemnum1" class="item-num" readonly />
   				 </div>
   				 <div class="col">
					<input type="text" name="itemdesc1" class="item-desc" readonly />
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
					 <input type="text" name="itemnum2" class="item-num" readonly />
				 </div>
				 <div class="col">
					<input type="text" name="itemdesc2" class="item-desc" readonly />
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
					 <input type="text" name="itemnum3" class="item-num" readonly />
				 </div>
				 <div class="col">
					<input type="text" name="itemdesc3" class="item-desc" readonly />
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
					 <input type="text" name="itemnum4" class="item-num" readonly />
				 </div>
				 <div class="col">
					<input type="text" name="itemdesc4" class="item-desc" readonly />
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
					 <input type="text" name="itemnum5" class="item-num" readonly />
				 </div>
				 <div class="col">
					<input type="text" name="itemdesc5" class="item-desc" readonly />
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
			  <textarea name="comments" id="fof-comments" rows="5">

			  </textarea>
			 </div>
		<div class="sm-d-none md-d-flex col"></div>
		  </div>
		<div class="row">
	  <div class="sm-d-none md-d-flex col"></div>
	  <div class="fof-form col-sm-12 col-md-6 p-0">
        <button type="button" class="btn btn-primary fof-submit">Submit Order</button>
		<br>
		<p class="fof-section-title ps-2">Freight Will Be Added to Final Invoices</p>
	  </div>
		<div class="sm-d-none md-d-flex col"></div>
		</div>
		</div>
	</form>
	';
    if(get_the_title() === 'Full Order Form' )  {
		return $content;
	}
}
