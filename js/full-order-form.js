jQuery( document ).ready( function( $ ) {
	$('.fof > a').attr('href', 'full-order-form/')

	// alert('Jquery and plugin js loaded.');
    $('input[name="shippingAddress"]').change(function() {
		// alert('BTN CLICKED.');
		$('div#shipAddressGroup').toggle();
	})

	// Event listener for fof-clear BTN
	$('.fof-clear').on('click', function() {
		$('.fof-search-input').val('');
		$('.fof-select').val('');
		// $('#products').empty();
		console.log('clearing products');

	});

	// SEARCH ITEMS FUNCTION
	//set global variables
	var sku;
	var ds;

	$(document).ready(function() {
		console.log('fof loading data');
		var _this = $(this);
		var fofSearchInput = $(this).val();
		// AJAX url
		$.ajax({
		    type: "POST",
		    dataType: "json",
		    url: fof_ajax_object.ajax_url,
		    data: {
				'action': 'fof_search',
				'fof-search-input': fofSearchInput
			},
		    success: function(response){
			$('#products').empty();
			$(response).each(function(i) {
				// sku = response[i].sku;
				// ds = response[i].ds;
				// console.log(sku+":"+ds);
				var option = "<option value='"+response[i].sku+" | "+response[i].ds+"'></option>";
					$('#products').append(option);
					_this.focus();
					console.log('fof data ready.')
			});
		    },
			error: function(response) {
				console.log(response);
			}
		});
	})

	$('.item-num').on('blur', function() {
		var skuField = $(this).closest('.row').find('.item-num').val();
		var nextField = $(this).closest('.row').find('.item-desc');
		console.log('sku val '+$(this).closest('.row').find('.item-num').val())

			console.log('fof sku lookup started.')
			var _this = $(this);
			var fofSearchInput = $(this).val();
			// AJAX url
			$.ajax({
				type: "POST",
				dataType: "json",
				url: fof_ajax_object.ajax_url,
				data: {
					'action': 'fof_searchDS',
					'fof-search-input': fofSearchInput
				},
				success: function(response){
						console.log('fof sku lookup done: '+response[0].ds)
						$(nextField).val(response[0].ds);
				// });
				},
				error: function(response) {
					console.log(response);
				}
			});
	})

	// add item to row next to button CLICKED
	$('.fof-item-list').on('click', '.add-item-btn', function() {
    	event.preventDefault();
		var value = $('.fof-search-input').val();
		const myArray = value.split("|");
		$(this).closest('.row').find('.item-num').val(myArray[0]);
		$(this).closest('.row').find('.item-desc').val(myArray[1]);
		$(this).closest('.row').find('.item-quan').focus();
		// alert($(this).val());
	});

	// ADD ROWS FUNCTION
	var i = 5;
	$('.add-row-btn').click(function() {
		event.preventDefault();
		// alert('adding row');
		i = ++i;
		var row = '<div class="row mt-1">' +
				'<div class="col-2 text-center">' +
				  '<button class="add-item-btn">+</button>' +
				'</div>' +
				'<div class="col-2">' +
				   '<input type="text" name="itemnum'+i+'" class="item-num"  />' +
			  ' </div>' +
			   '<div class="col">' +
				  '<input type="text" name="itemdesc'+i+'" class="item-desc"  />' +
			   '</div>' +
			   '<div class="col-2">' +
				  '<input type="number" name="itemquan'+i+'" class="item-quan" />' +
			   '</div>' +
		   '</div>';
		$('.fof-item-list').append(row);
	});

	//SUBMIT FORM FUNCTION

	//.fof-submit event listener
	// $('.fof-submit').click(function() {



});
function send_fof() {
	//prevent default action
	event.preventDefault();
	jQuery("#formValues").empty();
	var data = jQuery('#fof-form').serialize();
	var dataA = jQuery('#fof-form').serializeArray();

	if(distributorName === undefined) { var distributorName = jQuery('#fofDistributorName').val(); }
	function cancel() {
		 jQuery('#confirmationModal').hide();
		 return false;
	}


	var modal = '<div id="confirmationModal" class="modal" tabindex="-1" role="dialog">';
		modal += '<div class="modal-dialog" role="document" >';
		modal += '<div class="modal-content">';
		modal += '<div class="modal-header">';
		modal += '<h5 class="modal-title text-center">Confirm Your Order</h5><br>';
		modal += '</div>';
		modal += '<div class="modal-body">';
		modal += '<h5 class="text-center rmText">Is the order below correct?</h5><br>';
		modal += '<h5 class="text-center rmText">(Complete your order below by pressing submit)</h5><br>'
		modal += '<div id="formValues" class="mx-auto" style="width:80%"></div>';
		modal += '</div>';
		modal += '<div class="modal-footer">';
		modal += '<button type="button" id="modalSubmit" class="btn btn-primary">Submit Order</button>';
		modal += '<button type="button" id="modalCancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
		modal += '</div>';
		modal += '</div>';
		modal += '</div>';
		modal += '</div>';





   jQuery('body').append(modal);
   jQuery.each(dataA, function(i, field){
		   if(field.name == 'distributorName') { jQuery("#formValues").append("<b>Distributor Name</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'distributorEmail') { jQuery("#formValues").append("<b>Distributor Email</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'name') { jQuery("#formValues").append("<b>Name</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'add') { jQuery("#formValues").append("<b>Address</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'accountno') { jQuery("#formValues").append("<b>Company Name</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'city') { jQuery("#formValues").append("<b>City</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'state') { jQuery("#formValues").append("<b>State</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'zip') { jQuery("#formValues").append("<b>Zip</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'customerpo') { jQuery("#formValues").append("<b>Purchase Order</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'phonenumber') { jQuery("#formValues").append("<b>Phone Number</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'email') { jQuery("#formValues").append("<b>Email Address</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'shippingMethod') { jQuery("#formValues").append("<b>Shipping Method</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'shippingAddress') { jQuery("#formValues").append("<b>Same Address</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'shipattn') { jQuery("#formValues").append("<b>Shipping Attn</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
		   if(field.name == 'shipcity') { jQuery("#formValues").append("<b>Shipping City</b>  :  <span class='text-right'>" + field.value + "</span><br>"); };
			// console.log(field.name + ":" + field.value);
			// jQuery("#formValues").append("<b>"+field.name + "</b>  :  <span class='text-right'>" + field.value + "</span><br>");
			// jQuery("#formValues").append("  <span class='text-right'>" + field.value + "</span><br>");

		   if( field.value !== ''
				&& field.name !== 'action'
				&& field.name !== 'fof-search-input'
				&& field.name !== 'distributorName'
				&& field.name !== 'distributorEmail'
				&& field.name !== 'name'
				&& field.name !== 'accountno'
				&& field.name !== 'add'
				&& field.name !== 'city'
				&& field.name !== 'state'
				&& field.name !== 'zip'
				&& field.name !== 'customerpo'
				&& field.name !== 'phonenumber'
				&& field.name !== 'emailadd'
				&& field.name !== 'shippingMethod'
				&& field.name !== 'shippingAddress'
				&& field.name !== 'shipattn'
				&& field.name !== 'shipadd'
				&& field.name !== 'shipcity'
				&& field.name !== 'g-recaptcha-response'
			) {
				 jQuery("#formValues").append("<b>"+field.name + "</b>  :  <span class='text-right'>" + field.value + "</span><br>");
		   }
	});
   jQuery("#confirmationModal").show();
   console.log('modal added');
	jQuery('#modalCancel').click(function() {
		jQuery('#confirmationModal').css('display', 'none');
		return false;
	})
	jQuery('#modalSubmit').click(function() {
		console.log('form submitted.');
		// jQuery('#confirmationModal').css('display', 'none');
		//send ajax request
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: fof_ajax_object.ajax_url,
			data: data,
			success: function(response){
				jQuery('#modalSubmit').hide();
				jQuery('#modalCancel').text('Close');
				jQuery('.rmText').hide();
				// jQuery('#').text('Close');
				jQuery('#formValues').html('<h4 class="text-center">Thank you for your order!</h4><br><h5>(If you do not receive an order acknowledgement within 24 hours, please contact us)</h5>');
				jQuery("#fof-form")[0].reset();
					// location.reload();

			},
			error: function(response) {
				jQuery('#modalCancel').text('Close');
				jQuery('#formValues').html('<h4 class="text-center">There was an error processing your order!</h4>')
			}
		});
	});

}

function FonSubmit(token) {
	console.log('fof form submitted. Token received: '+token);
	 // document.getElementById("qof-form").submit();
	 send_fof();
   }
