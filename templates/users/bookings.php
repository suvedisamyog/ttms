<?php
use App\TTMS\Database\Operations\UserOperations;
if(!is_logged_in()){
	header('Location: login.php');
	return;
}
$bookings = new UserOperations('bookings');
$user_id = get_current_user_attr('user_id');

$all_bookings = $bookings->get_all_data(
    array(
        'where_clause' => 'user_id',
        'where_clause_value' => $user_id
    )
);

include_once TEMPLATE_PATH . 'users/navigation.php';
?>

<div class="container mt-5">
    <h2 class="text-center">Your Bookings</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Total Users</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_bookings as $booking): ?>
                    <?php
                    $package = new UserOperations('packages');
                    $package_data = $package->get_individual_data_from_id($booking['package_id']);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($package_data['name']); ?></td>
                        <td>Rs. <?php echo number_format($booking['total_price'], 2); ?></td>
                        <td><?php echo $booking['total_travelers']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
