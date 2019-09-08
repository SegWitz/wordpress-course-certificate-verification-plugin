<?php
//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}

// display the plugin settings page
function admin_certificate_ui() {

	if ( ! current_user_can( 'manage_options' ) ) return; ?>
	<div class="certificate-tabs">
		<div class="container">
			<div class="alert alert-info" role="alert">
			  	<strong>[get_certificate_search_form]</strong> <?php echo esc_html__('Use this shortcode to get search form', 'seg-witz') ?>.
			</div>
			<?php
			if( isset($_POST['add_certificate']) ){
				if( empty($_POST['certificate_code']) || empty($_POST['std_name']) || empty($_POST['course_name']) || empty($_POST['course_hours']) || empty($_POST['dob']) || empty($_POST['award_date']) ){
					echo '<div class="alert alert-danger">'.esc_html__('All fields are required', 'seg-witz').'!</div>';
				}else{
					$code = sanitize_text_field($_POST['certificate_code']);
					$name = sanitize_text_field($_POST['std_name']);
					$course = sanitize_text_field($_POST['course_name']);
					$hours = sanitize_text_field($_POST['course_hours']);
					$dob = sanitize_text_field($_POST['dob']);
					$award_date = sanitize_text_field($_POST['award_date']);
					
					$result = add_course_certificate($code, $name, $course, $hours, $dob, $award_date);
					if( $result == 1 ){
		                echo '<div class="alert alert-success">'.esc_html__('Certificate added successfully', 'seg-witz').'!</div>';
		            }else{
		                echo '<div class="alert alert-danger">'.esc_html__('Submission failed', 'seg-witz').'!</div>';
		            }
				}
			}
			?>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  	<li class="nav-item">
			    	<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo esc_html__('Add Certificate', 'seg-witz'); ?></a>
			  	</li>
			  	<li class="nav-item">
			    	<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo esc_html__('View Certificates', 'seg-witz'); ?></a>
			  	</li>
			</ul>
			<div class="tab-content" id="myTabContent">
	  			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
	  				<form class="mt-40" autocomplete="off" method="POST">
		  				<div class="row">
		  					<div class="col-md-6">
		  						<div class="form-group">
		  							<label><?php echo esc_html__('Student Name', 'seg-witz'); ?></label>
		  							<input type="text" required class="form-control" name="std_name">
		  						</div>
		  					</div>
		  					<div class="col-md-6">
		  						<div class="form-group">
		  							<label><?php echo esc_html__('Course Name', 'seg-witz'); ?></label>
		  							<input type="text" required class="form-control" name="course_name">
		  						</div>
		  					</div>
		  					<div class="col-md-6">
		  						<div class="form-group">
		  							<label><?php echo esc_html__('Hours Completed', 'seg-witz'); ?></label>
		  							<input type="number" required class="form-control number" name="course_hours">
		  						</div>
		  					</div>
		  					<div class="col-md-6">
		  						<div class="form-group">
		  							<label><?php echo esc_html__('Certification No', 'seg-witz'); ?></label>
		  							<input type="text" required class="form-control" value="<?php echo substr(md5(rand()), 0, 7); ?>" name="certificate_code">
		  						</div>
		  					</div>
		  					<div class="col-md-6">
		  						<div class="form-group">
		  							<label><?php echo esc_html__('Date of Birth', 'seg-witz'); ?></label>
		  							<input type="text" id="dob" required class="form-control" name="dob">
		  						</div>
		  					</div>
		  					<div class="col-md-6">
		  						<div class="form-group">
		  							<label><?php echo esc_html__('Award Date', 'seg-witz'); ?></label>
		  							<input type="text" id="award_date" required class="form-control" name="award_date">
		  						</div>
		  					</div>
		  					<div class="col-md-6 text-right">
		  						<div class="form-group">
		  							<input type="submit" class="mt-10 btn btn-dark w-100" value="<?php echo esc_html__('Submit', 'seg-witz'); ?>" name="add_certificate">
		  						</div>
		  					</div>
		  				</div>
		  			</form>
	  			</div>
	  			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
	  				<?php global $wpdb;
                    $certificates = $wpdb->get_results( "SELECT * FROM segwitz_course_certificates"); ?>
                    <div class="mt-40"></div>
                    <table id="certificates-table" class="table table-striped table-bordered" style="width:100%">
	                    <thead>
	                        <tr>
	                            <th><?php echo esc_html__('Student Name', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Course', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Hours Completed', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Certification No', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Date of Birth', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Award Date', 'seg-witz'); ?></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    <?php foreach ($certificates as $value) { ?>
	                	 	<tr>
	                            <td><?php echo esc_html($value->student_name); ?></td>
	                            <td><?php echo esc_html($value->course_name); ?></td>
	                            <td><?php echo esc_html($value->course_hours); ?></td>
	                            <td><?php echo esc_html($value->certificate_code); ?></td>
	                            <td><?php echo esc_html($value->dob); ?></td>
	                            <td><?php echo esc_html($value->award_date); ?></td>
	                        </tr>
	                    <?php } ?>
	                    </tbody>
	                    <tfoot>
	                        <tr>
	                            <th><?php echo esc_html__('Student Name', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Course', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Hours Completed', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Certification No', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Date of Birth', 'seg-witz'); ?></th>
		                        <th><?php echo esc_html__('Award Date', 'seg-witz'); ?></th>
	                        </tr>
	                    </tfoot>
	                </table>
	  			</div>
			</div>
		</div>
	</div>
<?php }

