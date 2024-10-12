<?php
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	die( 'Vendor directory not found. Please run composer install.' );
	}

template_header('Login');
?>

<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <!-- Login Form Card -->
                <div class="card mt-5 p-3">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Login</h3>

                        <form id="login_form">
                            <!-- Email Input -->
                            <div class="mb-4">
                                <label for="username" class="form-label">Email<spam class="text-danger" >*</spam></label>
                                <input type="text" class="form-control" name="email" placeholder="Enter your email" >
								<span class="text-danger m-2" id="email-error"></span>
                            </div>

                            <!-- Password Input -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password<spam class="text-danger" >*</spam></label>
                                <input type="password" class="form-control" name="password" placeholder="Enter your password" >
								<span class="text-danger m-2" id="password-error"></span>
                            </div>

                            <!-- Forgot Password Link -->
                            <div class="mb-4 text-end">
                                <a href="#" class="link-secondary">Forgot Password?</a>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>

                        <!-- Register Now Link -->
                        <div class="text-center mt-4">
                            <p class="mb-0">New? <a href="<?php echo '/registration.php'  ?>" class="link-primary">Register Now</a></p>
                        </div>
                    </div>
                </div>
                <!-- End of Login Form Card -->

            </div>
        </div>
    </div>


<?php
template_footer();
?>
