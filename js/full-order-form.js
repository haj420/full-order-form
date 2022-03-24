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

	});

	// SEARCH ITEMS FUNCTION
	//set global variables
	var sku;
	var ds;
	$('.fof-search-input').keyup(function() {
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
				var option = "<option value="+response[i].sku+">"+response[i].ds+"</option>";
					$('#products').append(option);
					_this.focus();
			});
		    },
			error: function(response) {
				console.log(response);
			}
		});
	})

	// add item to row next to button CLICKED
	$('.fof-item-list').on('click', '.add-item-btn', function() {
    	event.preventDefault();
		var value = $('[name=product]').val();
		var ds = $('[name=product]').text();
		$(this).closest('.row').find('.item-num').val(value);
		$(this).closest('.row').find('.item-desc').val(ds);
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
	$('.fof-submit').click(function() {
		//prevent default action
		event.preventDefault();
		if(distributorName === undefined) { var distributorName = $('#fofDistributorName').val(); }
		var data = $('#fof-form').serialize();
		//send ajax request
		$.ajax({
			type: "POST",
			dataType: "json",
			url: fof_ajax_object.ajax_url,
			data: data,
			success: function(response){
				alert('message sent.')
			},
			error: function(response) {
				alert('error');
			}
		});
	});


});
