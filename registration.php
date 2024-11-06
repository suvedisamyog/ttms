<?php
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	die( 'Vendor directory not found. Please run composer install.' );
	}
use App\TTMS\Database\Operations\UserOperations;
template_header('Registration');
?>
<div class="container">
    <div class="card card-pop">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="formTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="user-tab" >User Registration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="admin-tab" >Admin Registration</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
        <form id="registration_form" data-url="<?php  echo '/src/Ajax.php' ?>" >
			<div class="row">
			<div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
						<label class="form-label" for="username">User Name <spam class="text-danger" >*</spam></label>
                      	<input type="text" name="username" class="form-control form-control-lg" required/>
						  <span class="text-danger m-2" id="username-error"></span>

                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
						<label class="form-label" for="email">Email <spam class="text-danger" >*</spam></label>
                      <input type="email" name="email" class="form-control form-control-lg" required/>
					  <span class="text-danger m-2" id="email-error"></span>

                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
						<label class="form-label" for="password">Password <spam class="text-danger" >*</spam></label>
                      	<input type="password" name="password" class="form-control form-control-lg" required/>
						  <span class="text-danger m-2" id="password-error"></span>

                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
						<label class="form-label" for="confirm_password">Confirm Password <spam class="text-danger" >*</spam></label>
                      <input type="password" name="confirm_password" class="form-control form-control-lg" required/>
					  <span class="text-danger m-2" id="confirm_password-error"></span>

                    </div>
                  </div>
                  <div class="col-md-6 mb-4 d-none">
                    <div data-mdb-input-init class="form-outline">
						<label class="form-label" for="address">Address </label>
                      	<input type="text" name="address" class="form-control form-control-lg" />
						  <span class="text-danger m-2" id="password-error"></span>

                    </div>
                  </div>
                  <div class="col-md-6 mb-4 d-none">
                    <div data-mdb-input-init class="form-outline">
						<label class="form-label" for="site_url">Web Site</label>
                      <input type="url" name="site_url" class="form-control form-control-lg" />
					  <span class="text-danger m-2" id="site_url-error"></span>

                    </div>
                  </div>
				  <div class="form-outline mb-4">
					<label class="form-label" for="profile_pic">Profile Picture<spam class="text-danger" >*</spam></label>
                 	 <input type="file" name="profile_pic" class="form-control form-control-lg " />
					  <span class="text-danger m-2" id="email-error"></span>
                </div>
            </div>
			<button type="submit" class="btn btn-primary btn-lg">Register</button>

        </form>
        </div>
    </div>
</div>




<?php
template_footer();
?>
