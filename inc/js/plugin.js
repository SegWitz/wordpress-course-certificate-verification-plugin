jQuery(document).ready(function() {
	
	jQuery('#certificates-table').DataTable();
	jQuery( function() {
	    jQuery( "#dob" ).datepicker();
	    jQuery( "#award_date" ).datepicker();
	} );

	// Hide alerts
	setTimeout(function() {
        jQuery(".alert-success").alert('close');
        jQuery(".alert-danger").alert('close');
    }, 10000);
} );
