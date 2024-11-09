<?php
namespace App\TTMS\Admin;

use App\TTMS\Database\Operations\UserOperations;

class Bookings {

    public function __construct() {
        $this->bookings();
    }

    public static function bookings() {
        $packages = new UserOperations('packages');
        $all_packages = $packages->get_all_data(
            array(
                'where_clause' => 'author',
                'where_clause_value' => get_current_user_attr('user_id')
            )
        );

        $get_all_bookings = new UserOperations('bookings');
        $bookings = $get_all_bookings->get_all_data();

        ?>
        <div class="container mt-5">
            <h3>Packages Summary with Bookings</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Package ID</th>
                        <th scope="col">Package Name</th>
                        <th scope="col">Total Travelers</th>
                        <th scope="col">Total Payments (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $has_bookings = false; // Track if any packages with bookings are displayed

                    foreach ($all_packages as $package) {
                        $package_id = $package['id'];
                        $package_name = $package['name']; // Assuming 'name' column exists in `packages`

                        // Initialize counters for each package
                        $total_travelers = 0;
                        $total_payments = 0;

                        // Check bookings for the current package
                        foreach ($bookings as $booking) {
                            if ($booking['package_id'] == $package_id) {
                                $total_travelers += $booking['total_travelers'];
                                $total_payments += $booking['total_price'];
                            }
                        }

                        // Display package only if it has bookings
                        if ($total_travelers > 0 || $total_payments > 0) {
                            $has_bookings = true;
                            ?>
                            <tr>
                                <td><?php echo $package_id; ?></td>
                                <td><?php echo htmlspecialchars($package_name); ?></td>
                                <td><?php echo $total_travelers; ?></td>
                                <td>Rs. <?php echo number_format($total_payments, 2); ?></td>
                            </tr>
                            <?php
                        }
                    }

                    // Show a message if no packages have bookings
                    if (!$has_bookings) {
                        echo '<tr><td colspan="4" class="text-center">No packages with bookings available.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
?>
