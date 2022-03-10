jQuery( document ).ready( function( $ ) {
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
		$('#products').empty();
		$('[name=products]').val('');
		// AJAX url
		var fofSearchInput = $(this).val();
		$.ajax({
		    type: "POST",
		    dataType: "json",
		    url: fof_ajax_object.ajax_url,
		    data: {
				'action': 'fof_search',
				'fof-search-input': fofSearchInput
			},
		    success: function(response){
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
		console.log('adding :'+value+' -> '+ds);
		// console.log(sku);
		// console.log(ds);
		// console.log($('[name=product]').val());
		// console.log($('[name=product]' ).text());
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
	//send ajax request
	$.ajax({
		type: "POST",
		dataType: "json",
		url: fof_ajax_object.ajax_url,
		data: {
			action: 'send_message',
			// 'distributorName':'distributorName',
			// 'distributorEmail':'distributorEmail',
			// 'name':'name',
			// 'address':'address',
			// 'city':'city',
			// 'state':'state',
			// 'zip':'zip',
			// 'po':'po',
			// 'phone':'phone',
	        // 'email':'email'
		},
		success: function(response){
			alert('message sent.')
		},
		error: function(response) {
			alert('error');
		}
	});

// 	$.post(
//     fof_ajax_object.ajax_url,
//     {
//         'action': 'send_message',
//         'distributorName':'distributorName',
// 		'distributorEmail':'distributorEmail',
// 		'name':'name',
// 		'address':'address',
// 		'city':'city',
// 		'state':'state',
// 		'zip':'zip',
// 		'po':'po',
// 		'phone':'phone',
//         'email':'email'
//
//     },
//     function(response){
//         alert('The server responded: ' + response);
//     }
// );
	});
});
