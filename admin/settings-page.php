<?php
//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}

// display the plugin settings page
function course_certificate_admin_certificate_ui() {

	if ( ! current_user_can( 'manage_options' ) ) return;
	$error = "";
	if( isset($_POST['add_certificate']) ) {
		if(!wp_verify_nonce($_POST['course_nonce'], 'admin_certificate_ui')) {
			echo '<div class="alert alert-danger">Try Again Verification Failed!!</div>';
		} else if( isset($_POST['add_certificate']) && $_POST['add_certificate'] == "Delete" ) {
			$editid = sanitize_text_field($_POST['editid']);
			if (strpos($editid, ',') !== false) {
				$editid = explode(",", $editid);
				foreach( $editid as $edt ) {
					$result = course_certificate_delete_course_certificate( $edt );
				}
			} else {
				$result = course_certificate_delete_course_certificate($editid);
			}
			if( $result == 1 ) {
                $error = '<div class="alert alert-success hide-alert">Certificate deleted successfully!<button type="button" class="close" data-dismiss="alert">x</button></div>';
            } else {
                $error = '<div class="alert alert-danger hide-alert">Error while deleting!<button type="button" class="close" data-dismiss="alert">x</button></div>';
            }
		} else if( empty($_POST['certificate_code']) || empty($_POST['std_name']) || empty($_POST['course_name']) || empty($_POST['course_hours']) || empty($_POST['dob']) || empty($_POST['award_date']) ) {
			$error = '<div class="alert alert-danger hide-alert">All fields are required!<button type="button" class="close" data-dismiss="alert">x</button></div>';
		} else {
			$code = sanitize_text_field($_POST['certificate_code']);
			$name = sanitize_text_field($_POST['std_name']);
			$course = sanitize_text_field($_POST['course_name']);
			$hours = sanitize_text_field($_POST['course_hours']);
			$dob = sanitize_text_field($_POST['dob']);
			$award_date = sanitize_text_field($_POST['award_date']);
			$editid = sanitize_text_field($_POST['editid']);
			$result = course_certificate_add_course_certificate($code, $name, $course, $hours, $dob, $award_date, $editid);
			if( $result == 1 ) {
				if( $editid != "" ) {
	                $error = '<div class="alert alert-success hide-alert">Certificate updated successfully!<button type="button" class="close" data-dismiss="alert">x</button></div>';
				} else {
	                $error = '<div class="alert alert-success hide-alert">Certificate added successfully!<button type="button" class="close" data-dismiss="alert">x</button></div>';
				}
            } else {
                $error = '<div class="alert alert-danger hide-alert">Submission failed!<button type="button" class="close" data-dismiss="alert">x</button></div>';
            }
		}
	}
	global $wpdb;
    $certificates = $wpdb->get_results( "SELECT * FROM segwitz_course_certificates");
    $cpage = sanitize_text_field( $_GET['pg'] );
    $cpage = $cpage != '' ? (($cpage-1)*10) : 0;
	$certificatesNew = array_slice($certificates, $cpage, 10, true);
    ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<style>
		@font-face {
		    font-family: Varela Round;
		    src: url("../wp-content/plugins/certificate-verification/assets/css/fonts/VarelaRound-Regular.otf") format("opentype");
		}
	    body {
	        color: #566787;
			background: #f5f5f5;
			font-family: 'Varela Round', sans-serif;
			font-size: 13px;
		}
		.table-wrapper {
	        background: #fff;
	        padding: 20px 25px;
	        margin: 30px 0;
			border-radius: 3px;
	        box-shadow: 0 1px 1px rgba(0,0,0,.05);
	    }
		.table-title {        
			padding-bottom: 15px;
			background: #435d7d;
			color: #fff;
			padding: 16px 30px;
			margin: -20px -25px 10px;
			border-radius: 3px 3px 0 0;
	    }
	    .table-title h2 {
			margin: 5px 0 0;
			font-size: 24px;
		}
		.table-title .btn-group {
			float: right;
		}
		.table-title .btn {
			color: #fff;
			float: right;
			font-size: 13px;
			border: none;
			min-width: 50px;
			border-radius: 2px;
			border: none;
			outline: none !important;
			margin-left: 10px;
		}
		.table-title .btn i {
			float: left;
			font-size: 21px;
			margin-right: 5px;
		}
		.table-title .btn span {
			float: left;
			margin-top: 2px;
		}
	    table.table tr th, table.table tr td {
	        border-color: #e9e9e9;
			padding: 12px 15px;
			vertical-align: middle;
	    }
		table.table tr th:first-child {
			width: 60px;
		}
		table.table tr th:last-child {
			width: 100px;
		}
	    table.table-striped tbody tr:nth-of-type(odd) {
	    	background-color: #fcfcfc;
		}
		table.table-striped.table-hover tbody tr:hover {
			background: #f5f5f5;
		}
	    table.table th i {
	        font-size: 13px;
	        margin: 0 5px;
	        cursor: pointer;
	    }	
	    table.table td:last-child i {
			opacity: 0.9;
			font-size: 22px;
	        margin: 0 5px;
	    }
		table.table td a {
			font-weight: bold;
			color: #566787;
			display: inline-block;
			text-decoration: none;
			outline: none !important;
		}
		table.table td a:hover {
			color: #2196F3;
		}
		table.table td a.edit {
	        color: #FFC107;
	    }
	    table.table td a.delete {
	        color: #F44336;
	    }
	    table.table td i {
	        font-size: 19px;
	    }
		table.table .avatar {
			border-radius: 50%;
			vertical-align: middle;
			margin-right: 10px;
		}
	    .pagination {
	        float: right;
	        margin: 0 0 5px;
	    }
	    .pagination li a {
	        border: none;
	        font-size: 13px;
	        min-width: 30px;
	        min-height: 30px;
	        color: #999;
	        margin: 0 2px;
	        line-height: 30px;
	        border-radius: 2px !important;
	        text-align: center;
	        padding: 0 6px;
	    }
	    .pagination li a:hover {
	        color: #666;
	    }	
	    .pagination li.active a, .pagination li.active a.page-link {
	        background: #03A9F4;
	    }
	    .pagination li.active a:hover {        
	        background: #0397d6;
	    }
		.pagination li.disabled i {
	        color: #ccc;
	    }
	    .pagination li i {
	        font-size: 16px;
	        padding-top: 6px
	    }
	    .hint-text {
	        float: left;
	        margin-top: 10px;
	        font-size: 13px;
	    }    
		/* Custom checkbox */
		.custom-checkbox {
			position: relative;
		}
		.custom-checkbox input[type="checkbox"] {    
			opacity: 0;
			position: absolute;
			margin: 5px 0 0 3px;
			z-index: 9;
		}
		.custom-checkbox label:before{
			width: 18px;
			height: 18px;
		}
		.custom-checkbox label:before {
			content: '';
			margin-right: 10px;
			display: inline-block;
			vertical-align: text-top;
			background: white;
			border: 1px solid #bbb;
			border-radius: 2px;
			box-sizing: border-box;
			z-index: 2;
		}
		.custom-checkbox input[type="checkbox"]:checked + label:after {
			content: '';
			position: absolute;
			left: 6px;
			top: 0px;
			width: 6px;
			height: 11px;
			border: solid #000;
			border-width: 0 3px 3px 0;
			transform: inherit;
			z-index: 3;
			transform: rotateZ(45deg);
		}
		.custom-checkbox input[type="checkbox"]:checked + label:before {
			border-color: #03A9F4;
			background: #03A9F4;
		}
		.custom-checkbox input[type="checkbox"]:checked + label:after {
			border-color: #fff;
		}
		.custom-checkbox input[type="checkbox"]:disabled + label:before {
			color: #b8b8b8;
			cursor: auto;
			box-shadow: none;
			background: #ddd;
		}
		/* Modal styles */
		.modal .modal-dialog {
			max-width: 400px;
		}
		.modal .modal-header, .modal .modal-body, .modal .modal-footer {
			padding: 20px 30px;
		}
		.modal .modal-content {
			border-radius: 3px;
		}
		.modal .modal-footer {
			background: #ecf0f1;
			border-radius: 0 0 3px 3px;
		}
	    .modal .modal-title {
	        display: inline-block;
	    }
		.modal .form-control {
			border-radius: 2px;
			box-shadow: none;
			border-color: #dddddd;
		}
		.modal textarea.form-control {
			resize: vertical;
		}
		.modal .btn {
			border-radius: 2px;
			min-width: 100px;
		}	
		.modal form label {
			font-weight: normal;
		}
	</style>

	<div class="container">
	  <div class="table-wrapper">
	    <div class="table-title">
	      <div class="row">
	        <div class="col-sm-6">
	          <h2 style="color: white;">Manage <b>Certificates</b></h2>
	        </div>
	        <div class="col-sm-6">
	          <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Certificate</span></a>
	          <a href="javascript:void(0);" class="btn btn-danger deleteMultiple" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>
	        </div>
	      </div>
	    </div>
	    <?php echo $error;?>
		<div class="alert alert-info" role="alert">
		  	<strong>[get_certificate_search_form]</strong> Copy and paste the shortcode on the page you want the search bar and result of the certificates to be.
		</div>
	    <table class="table table-striped table-hover">
	      <thead>
	        <tr>
	          <th>
	            <span class="custom-checkbox">
					<input type="checkbox" id="selectAll">
					<label for="selectAll"></label>
				</span>
	          </th>
	          <th>Student Name</th>
	          <th>Course</th>
	          <th>Hours Completed</th>
	          <th>Certificate No</th>
	          <th>Date Of Birth</th>
	          <th>Award Date</th>
	          <th>Actions</th>
	        </tr>
	      </thead>
	      <tbody>
	        <?php foreach ($certificatesNew as $value) { ?>
	    	 	<tr>
	    	 		<td>
			            <span class="custom-checkbox">
							<input type="checkbox" id="checkbox<?php echo $value->id;?>" value="<?php echo $value->id;?>" class="checkedcert">
							<label for="checkbox<?php echo $value->id;?>"></label>
						</span>
			        </td>
	                <td class="sname"><?php echo $value->student_name; ?></td>
	                <td class="cname"><?php echo $value->course_name; ?></td>
	                <td class="chour"><?php echo $value->course_hours; ?></td>
	                <td class="ccode"><?php echo $value->certificate_code; ?></td>
	                <td class="cdob" date="<?php echo $value->dob; ?>"><?php echo date("d/M/Y", strtotime($value->dob)); ?></td>
	                <td class="cadt" date="<?php echo $value->award_date; ?>"><?php echo date("d/M/Y", strtotime($value->award_date)); ?></td>
			        <td>
			           <a href="javascript:void();" class="edit editModal" data-id="<?php echo $value->id;?>"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
			           <a href="javascript:void(0);" class="delete deleteModal" data-id="<?php echo $value->id;?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
			        </td>
	            </tr>
	        <?php } ?>
	      </tbody>
	    </table>
	    <div class="clearfix">
	    	<?php if( count($certificates) > 0 ) { ?>
		      <div class="hint-text">Showing <b><?php echo count($certificatesNew);?></b> out of <b><?php echo count($certificates);?></b> entries</div>
		      <ul class="pagination">
		        <!--<li class="page-item disabled"><a href="#">Previous</a></li>-->
		        <?php
		        $pages = ceil(count($certificates)/10);
		        $currentpage = sanitize_text_field( $_GET['pg'] );
		        $currentpage = $currentpage != '' ? $currentpage : 1;
		        for($i=1;$i<=$pages;$i++) { ?>
			        <li class="page-item <?php echo ($currentpage==$i) ? 'active' : '';?>"><a href="<?php echo admin_url().'admin.php?page=certificate-codes&pg='.$i;?>" class="page-link"><?php echo $i;?></a></li>
		        <?php } ?>
		        <!--<li class="page-item"><a href="#" class="page-link">Next</a></li>-->
		      </ul>
	    	<?php } ?>
	    </div>
	  </div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
		<form class="mt-40" method="POST">
			<?php wp_nonce_field( 'admin_certificate_ui', 'course_nonce' );?>
	        <div class="modal-header">
	          <h4 class="modal-title">Add Certificate</h4>
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        </div>
	        <div class="modal-body">
	          <div class="form-group">
	            <label>Student Name</label>
	            <input type="text" class="form-control" required name="std_name">
	          </div>
	          <div class="form-group">
				<label>Course Name</label>
				<input type="text" required class="form-control" name="course_name">
	          </div>
	          <div class="form-group">
				<label>Hours Completed</label>
				<input type="text" required class="form-control" name="course_hours">
	          </div>
	          <div class="form-group">
				<label>Certification No</label>
				<input type="text" required class="form-control" value="<?php echo substr(md5(rand()), 0, 7); ?>" name="certificate_code" readonly="readonly">
	          </div>
			  <div class="form-group">
				<label>Date of Birth</label>
				<input type="text" id="dob" required class="form-control" readonly="readonly">
				<input type="hidden" id="adob" name="dob">
			  </div>
			  <div class="form-group">
				<label>Award Date</label>
				<input type="text" id="award_date" required class="form-control" readonly="readonly">
				<input type="hidden" id="aaward_date" name="award_date">
			  </div>
	        </div>
	        <div class="modal-footer">
	          <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
	          <input type="submit" class="btn btn-success" value="Add" name="add_certificate">
	        </div>
	      </form>
	    </div>
	  </div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
		<form class="mt-40" method="POST">
			<?php wp_nonce_field( 'admin_certificate_ui', 'course_nonce' );?>
	        <div class="modal-header">
	          <h4 class="modal-title">Edit Certificate</h4>
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        </div>
	        <div class="modal-body">
	          <div class="form-group">
	            <label>Student Name</label>
	            <input type="text" class="form-control" required name="std_name">
	          </div>
	          <div class="form-group">
				<label>Course Name</label>
				<input type="text" required class="form-control" name="course_name">
	          </div>
	          <div class="form-group">
				<label>Hours Completed</label>
				<input type="text" required class="form-control" name="course_hours">
	          </div>
	          <div class="form-group">
				<label>Certification No</label>
				<input type="text" required class="form-control" value="<?php echo substr(md5(rand()), 0, 7); ?>" name="certificate_code" readonly="readonly">
	          </div>
			  <div class="form-group">
				<label>Date of Birth</label>
				<input type="text" id="editdob" required class="form-control" readonly="readonly">
				<input type="hidden" id="eeditdob" name="dob">
			  </div>
			  <div class="form-group">
				<label>Award Date</label>
				<input type="text" id="editaward_date" required class="form-control" readonly="readonly">
				<input type="hidden" id="eeditaward_date" name="award_date">
			  </div>
	        </div>
	        <div class="modal-footer">
				<input type="hidden" name="editid" value="">
	        	<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
	        	<input type="submit" class="btn btn-success" value="Update" name="add_certificate">
	        </div>
	      </form>
	    </div>
	  </div>
	</div>
	<!-- Delete Modal HTML -->
	<div id="deleteEmployeeModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <form method="POST">
			<?php wp_nonce_field( 'admin_certificate_ui', 'course_nonce' );?>
	        <div class="modal-header">
	          <h4 class="modal-title">Delete Employee</h4>
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        </div>
	        <div class="modal-body">
	          <p>Are you sure you want to delete these Records?</p>
	          <p class="text-warning"><small>This action cannot be undone.</small></p>
	        </div>
	        <div class="modal-footer">
	          <input type="hidden" name="editid" value="">
	          <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
	          <input type="submit" class="btn btn-danger" value="Delete" name="add_certificate">
	        </div>
	      </form>
	    </div>
	  </div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function() {
        	jQuery('#certificates-table').DataTable();
        	jQuery( function() {
			    jQuery( "#dob" ).datepicker({ dateFormat: 'dd/M/yy', changeMonth: true, changeYear: true, yearRange: '1940:' + new Date().getFullYear(), altField: "#adob", altFormat: "mm/dd/yy" });
			    jQuery( "#award_date" ).datepicker({ dateFormat: 'dd/M/yy', changeMonth: true, changeYear: true, yearRange: '1940:' + new Date().getFullYear(), altField: "#aaward_date", altFormat: "mm/dd/yy" });
			    jQuery( "#editdob" ).datepicker({ dateFormat: 'dd/M/yy', changeMonth: true, changeYear: true, yearRange: '1940:' + new Date().getFullYear(), altField: "#eeditdob", altFormat: "mm/dd/yy" });
			    jQuery( "#editaward_date" ).datepicker({ dateFormat: 'dd/M/yy', changeMonth: true, changeYear: true, yearRange: '1940:' + new Date().getFullYear(), altField: "#eeditaward_date", altFormat: "mm/dd/yy" });
			} );

			jQuery(document).on("click", ".editModal", function() {
				var id = jQuery(this).data("id");
				var sname = jQuery(".sname", jQuery(this).closest("tr")).html();
				var cname = jQuery(".cname", jQuery(this).closest("tr")).html();
				var ccode = jQuery(".ccode", jQuery(this).closest("tr")).html();
				var chour = jQuery(".chour", jQuery(this).closest("tr")).html();
				var cdob  = jQuery(".cdob", jQuery(this).closest("tr")).html();
				var cadt  = jQuery(".cadt", jQuery(this).closest("tr")).html();
				var ocdob  = jQuery(".cdob", jQuery(this).closest("tr")).attr("date");
				var ocadt  = jQuery(".cadt", jQuery(this).closest("tr")).attr("date");

				jQuery("#editEmployeeModal input[name=editid]").val( id );
				jQuery("#editEmployeeModal input[name=std_name]").val(sname);
				jQuery("#editEmployeeModal input[name=course_name]").val(cname);
				jQuery("#editEmployeeModal input[name=course_hours]").val(chour);
				jQuery("#editEmployeeModal input[name=certificate_code]").val(ccode);
				jQuery("#editEmployeeModal input[name=dob]").val(ocdob);
				jQuery("#editEmployeeModal #editdob").val(cdob);
				jQuery("#editEmployeeModal input[name=award_date]").val(ocadt);
				jQuery("#editEmployeeModal #editaward_date").val(cadt);

				jQuery("#editEmployeeModal").modal();
			});

			jQuery(document).on("click", ".deleteModal", function() {
				var id = jQuery(this).data("id");
				jQuery("#deleteEmployeeModal input[name=editid]").val( id );
				jQuery("#deleteEmployeeModal").modal();
			});
			jQuery(document).on("click", ".deleteMultiple", function() {
				var allIds = [];
				jQuery('.checkedcert:checkbox:checked').each(function () {
				    allIds.push(this.checked ? jQuery(this).val() : "");
				});
				jQuery("#deleteEmployeeModal input[name=editid]").val( allIds.join(",") );
				jQuery("#deleteEmployeeModal").modal();
			});

			// Select/Deselect checkboxes
			var checkbox = jQuery('table tbody input[type="checkbox"]');
			jQuery("#selectAll").click(function() {
				if(this.checked) {
					checkbox.each(function() {
						this.checked = true;
					});
				} else {
					checkbox.each(function() {
						this.checked = false;
					});
				}
			});
			checkbox.click(function() {
				if(!this.checked){
					jQuery("#selectAll").prop("checked", false);
				}
			});

			setTimeout(function() {
				jQuery(".hide-alert").remove();
			}, 5000);
        } );
	</script>
<?php }

