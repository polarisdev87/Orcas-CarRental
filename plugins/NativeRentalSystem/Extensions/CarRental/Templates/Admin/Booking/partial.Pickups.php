<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<div class="clear">
	<table class="display <?php print('pickups-datatable'); ?>" border="0" style="width:100%">
		<thead>
		<tr>
			<th width="1%">#</th>
			<th width="15%" style="white-space: nowrap">Reservation Code, Name &amp; Cars</th>
			<th style="white-space: nowrap">Pick-Up Date, Time &amp; Location</th>
			<th style="white-space: nowrap">Return Date, Time &amp; Location</th>
			<th width="18%" style="white-space: nowrap">Reservation Date &amp; Status</th>
			<th width="17%" style="white-space: nowrap">Amount</th>
			<th width="14%">Actions</th>
		</tr>
		</thead>
		<tbody>
		 <?php print($pickupsHTML); ?>
		</tbody>
	</table>
</div>
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="name">Name</div>
	<div class="pre-autorized">Pre Autorized</div>
	<div class="charge-id">Charge id</div>
	<input type="text" disabled="disabled" name="preautorize-amount" class="preautorize-amount" value="">
	<div class="input-fields">
		<div class="money-fields">
			<div class="two-flieds">
				<input type="text"  class="description" name="description1" placeholder="Description" value="">
				<input type="text"  class="value" name="value1" placeholder="Amount" value="">
			</div>
			
		</div>
		<button type="button" class="add-more-fields">+ Add More</button>
		<div class="text-field">
			<textarea class="public-notes" placeholder="Public Notes"></textarea>
		</div>
		<div class="customer-will-be-charged">
			<input type="text" disabled="disabled" name="customer-charged" class="customer-charged" value="">
		</div>
		<div class="form-fields">
			<button type="button" class="release-full-deposit">Release Full Deposit</button>
			<button type="button" class="close">Close</button>
			<button type="button" class="submit">Submit</button>
		</div>
	</div>
  </div>

</div>
<script type="text/javascript">

var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("open-modal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal 
btn.onclick = function(e) {
	
	
}

jQuery(".open-modal").click(function(e) {
	e.preventDefault();
	
	/*jQuery("#myModal").html('<div class="modal-content"><div class="name">Name</div><div class="pre-autorized">Pre Autorized</div><div class="charge-id">Charge id</div><input type="text" disabled="disabled" name="preautorize-amount" class="preautorize-amount" value=""><div class="input-fields"><div class="money-fields">		<div class="two-flieds">				<input type="text"  class="description" name="description1" placeholder="Description" value="">				<input type="text"  class="value" name="value1" placeholder="Amount" value="">			</div>					</div>		<button type="button" class="add-more-fields">+ Add More</button>		<div class="text-field">			<textarea class="public-notes" placeholder="Public Notes"></textarea>		</div>		<div class="customer-will-be-charged">			<input type="text" disabled="disabled" name="customer-charged" class="customer-charged" value="">		</div>		<div class="form-fields">			<button type="button" class="release-full-deposit">Release Full Deposit</button>			<button type="button" class="close">Close</button>			<button type="button" class="submit">Submit</button>		</div>	</div>  </div>');
	*/
	var preautorized = jQuery(this).attr("data-preautorized");
	var bookingid = jQuery(this).attr("data-bookingid");
	var bookingstripesecretkey = jQuery(this).attr("data-bookingstripesecretkey");
	var bookingdepositchargeid = jQuery(this).attr("data-bookingdepositchargeid");
	var name = jQuery(this).attr("data-name");
	
	var bookingidHidden = '<input type="hidden" class="bookingIdHidden"  name="bookingIdHidden" value="'+bookingid+'"></input>';
	var bookingstripesecretkeyHidden = '<input type="hidden" class="bookingstripesecretkeyHidden" name="bookingstripesecretkeyHidden" value="'+bookingstripesecretkey+'"></input>';
	var bookingdepositchargeidHidden= '<input type="hidden" class="chargeId" name="chargeId" value="'+bookingdepositchargeid+'"></input>';
	
	jQuery(".modal-content .name").html(name);
	jQuery(".modal-content .charge-id").html(bookingdepositchargeid);
	jQuery(".modal-content .preautorize-amount").val(preautorized);
	jQuery('.modal-content .form-fields').append(bookingidHidden);
	jQuery('.modal-content .form-fields').append(bookingdepositchargeidHidden);
	jQuery('.modal-content .form-fields').append(bookingstripesecretkeyHidden);
	
    modal.style.display = "block";
  
});

// When the user clicks on <span> (x), close the modal
span.onclick = function(e) {
	
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

jQuery(".modal-content .add-more-fields").click(function(e) {
	var moneyFieldAdd = '<div class="two-flieds"><input type="text"  class="description" name="description1" placeholder="Description" value=""><input type="text"  class="value" name="value1" placeholder="Amount" value=""></div>';
	jQuery('.modal-content .money-fields').append(moneyFieldAdd);
});

jQuery(".modal-content .release-full-deposit").click(function(e) {
	var bookingID=jQuery(".modal-content .form-fields .bookingIdHidden").val();
	var bookingSecretKey=jQuery(".modal-content .form-fields .bookingstripesecretkeyHidden").val();
	var stripeChargeId=jQuery(".modal-content .form-fields .chargeId").val();
	
	jQuery.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'release_all_founds',
			bookingID : bookingID,
			bookingSecretKey : bookingSecretKey,
			stripeChargeId : stripeChargeId
		},
		success : function( response ) {
			jQuery(".modal-content").append("<div><b>Founds released succesfully! The page will refresh in 3 seconds.</b></div>");
			setTimeout(location.reload.bind(location), 3000);
		},
		error: function() {

			jQuery(".modal-content").append("<div><b>There was some error, found are not released</b></div>");

		},

		complete: function() {

							       

									

								

		}
	});
});

jQuery(".modal-content .submit").click(function(e) {
	var person={};
	var bookingID=jQuery(".modal-content .form-fields .bookingIdHidden").val();
	var bookingSecretKey=jQuery(".modal-content .form-fields .bookingstripesecretkeyHidden").val();
	var stripeChargeId=jQuery(".modal-content .form-fields .chargeId").val();
	var publicNotes=jQuery(".modal-content .text-field .public-notes").val();
	var customerCharged=jQuery(".modal-content .customer-will-be-charged .customer-charged").val();
	jQuery(".money-fields .two-flieds").each(function(){
       person[jQuery(this).find( ".description" ).val()]=jQuery(this).find( ".value" ).val();
    });
	console.log(person);
	jQuery.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'make_deposit_charge',
			fields : person,
			bookingID : bookingID,
			bookingSecretKey : bookingSecretKey,
			stripeChargeId : stripeChargeId,
			publicNotes : publicNotes,
			customerCharged : customerCharged
		},
		success : function( response ) {
			console.log(response);
			var d = JSON.parse(response);

			if(d.message=='ERROR'){
				jQuery(".modal-content").append("<div><b>One or more fields are empty, Form is not submitted</b></div>");
			}else{
				jQuery(".modal-content").append("<div><b>Form submitted succesfully! The page will refresh in 3 seconds.</b></div>");
				setTimeout(location.reload.bind(location), 3000);
			}
			
		},
		error: function() {

			jQuery(".modal-content").append("<div><b>There was some error, Form is not submitted</b></div>");
			

		},

		complete: function() {

							       

									

								

		}
	});
});

jQuery(document).on("change", ".value", function() {
    var sum = 0;
    jQuery(".value").each(function(){
        sum += +jQuery(this).val();
    });
    jQuery(".customer-charged").val(sum);
});

jQuery(document).ready(function() {
	
	
	jQuery('.<?php print('pickups-datatable'); ?>').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"bSortClasses": false,
		"iDisplayLength": 25,
		"aaSorting": [[0,'asc']],
		"bAutoWidth": true,
		"aoColumns": [
			{ "sWidth": "1%" },
			{ "sWidth": "15%" },
			{ "sWidth": "18%" },
			{ "sWidth": "18%" },
			{ "sWidth": "17%" },
			{ "sWidth": "17%" },
			{ "sWidth": "14%" }
		],
		"bInfo": true,
		"sScrollY": "100%",
		"sScrollX": "100%",
		"bScrollCollapse": true,
		"sPaginationType": "full_numbers",
		"bRetrieve": true,
		"oLanguage": {
			"sSearch": "Search:",
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
			"sInfoEmpty": "Showing 0 to 0 of 0 entries",
			"sZeroRecords": "No matching records found",
			"sInfoFiltered": "(filtered from _MAX_ total entries)",
			"sEmptyTable": "No data available in table",
			"sLengthMenu": "Show _MENU_ entries",
			"oPaginate": {
				"sFirst":    "First",
				"sPrevious": "Previous",
				"sNext":     "Next",
				"sLast":     "Last"
			  }
		 }
	});
});
</script>

<style>
	/* The Modal (background) */
	.modal {
		display: none; /* Hidden by default */
		position: fixed; /* Stay in place */
		z-index: 1; /* Sit on top */
		left: 0;
		top: 0;
		width: 100%; /* Full width */
		height: 100%; /* Full height */
		overflow: auto; /* Enable scroll if needed */
		background-color: rgb(0,0,0); /* Fallback color */
		background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}

	/* Modal Content/Box */
	.modal-content {
		background-color: #fefefe !important;
		margin: 15% auto !important; /* 15% from the top and centered */
		padding: 20px !important;
		border: 1px solid #888 !important;
		width: 80% !important; /* Could be more or less, depending on screen size */
	}

	/* The Close Button */
	button {
		color: #aaa;
		
		font-size: 28px;
		font-weight: bold;
		cursor:pointer;
	}

	button:hover,
	button:focus {
		color: black;
		text-decoration: none;
		cursor: pointer;
	}
</style>