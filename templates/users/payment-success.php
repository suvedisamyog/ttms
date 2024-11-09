<?php
use App\TTMS\Database\Operations\UserOperations;
include_once TEMPLATE_PATH . 'users/navigation.php';
$total_travelers = $_SESSION['total_travelers'] ?? 1;
$package_id = $_SESSION['package_id'] ?? 0;
$total_price = $_SESSION['amount'] ?? 0;

if($package_id == 0){
	header('Location: index.php');
	return;
}
$data= array(
	'package_id' => $package_id,
	'total_travelers' => $total_travelers,
	'total_price' => $total_price,
	'user_id' => get_current_user_attr('user_id'),
	'payment_id' => $_GET['refId'] ?? 'N/A'
);
$bookings = new UserOperations('bookings');
$bookings->insert_data($data);


?>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg p-5" style="max-width: 500px; width: 100%;">
            <div class="text-center">
                <div class="success-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h1 class="text-success">Payment Successful!</h1>
                <p class="lead">Your payment was processed successfully.</p>
                <hr>
                <p><strong>Transaction ID:</strong> <?php echo $_GET['refId'] ?? 'N/A' ?></p>
                <p><strong>Amount Paid:</strong> Rs.<?php echo $total_price  ?></p>
                <p><strong>Package id:</strong> <?php echo $package_id ?></p>
                <hr>
                <div class="d-flex justify-content-center">
                    <a href="index.html" class="btn btn-primary btn-lg">Back to Home</a>
                    <a href="your-bookings.html" class="btn btn-secondary btn-lg ms-3">View My Bookings</a>
                </div>
            </div>
        </div>
    </div>

<?php
unset($_SESSION['total_travelers']);
unset($_SESSION['package_id']);
unset($_SESSION['total_price']);
?>
