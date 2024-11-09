<?php
include_once TEMPLATE_PATH . 'users/navigation.php';
unset($_SESSION['total_travelers']);
unset($_SESSION['package_id']);
unset($_SESSION['total_price']);
?>
 <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg p-5" style="max-width: 500px; width: 100%;">
            <div class="text-center">
                <div class="failure-icon">
                    <i class="bi bi-x-circle"></i> <!-- X Circle icon from Bootstrap Icons -->
                </div>
                <h1 class="text-danger">Payment Failed!</h1>
                <p class="lead">Oops! Something went wrong during the payment process.</p>
                <hr>
                <p><strong>Error Message:</strong> Payment could not be processed. Please try again.</p>
                <hr>
                <div class="d-flex justify-content-center">
                    <a href="index.php" class="btn btn-primary btn-lg">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
