<?php
namespace App\TTMS\Admin;

use App\TTMS\Database\Operations\UserOperations;

class Dashboard {

    public function __construct() {
        $this->displayDashboard();
    }

    public function displayDashboard() {
        // Fetch Packages and Bookings Data
        $packageOperations = new UserOperations('packages');
        $bookingOperations = new UserOperations('bookings');

        $allPackages = $packageOperations->get_all_data([
            'where_clause' => 'author',
            'where_clause_value' => get_current_user_attr('user_id')
        ]);

        $allBookings = $bookingOperations->get_all_data();

        // Filter bookings for packages owned by the current admin
        $adminPackageIds = array_column($allPackages, 'id');
        $filteredBookings = array_filter($allBookings, function($booking) use ($adminPackageIds) {
            return in_array($booking['package_id'], $adminPackageIds);
        });

        // Calculate Summary Metrics
        $totalPackages = count($allPackages);
        $totalBookings = count($filteredBookings);
        $totalRevenue = array_sum(array_column($filteredBookings, 'total_price'));
        $totalTravelers = array_sum(array_column($filteredBookings, 'total_travelers'));

        // Aggregate Top Packages by Package ID
        $packageStats = [];
        foreach ($filteredBookings as $booking) {
            $packageId = $booking['package_id'];
            if (!isset($packageStats[$packageId])) {
                $packageStats[$packageId] = [
                    'total_travelers' => 0,
                    'total_revenue' => 0
                ];
            }
            $packageStats[$packageId]['total_travelers'] += $booking['total_travelers'];
            $packageStats[$packageId]['total_revenue'] += $booking['total_price'];
        }

        ?>
        <!-- Dashboard Layout -->
        <div class="container mt-5">
            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Packages</h5>
                            <p class="card-text"><?php echo $totalPackages; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Active Bookings</h5>
                            <p class="card-text"><?php echo $totalBookings; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Revenue</h5>
                            <p class="card-text">Rs. <?php echo number_format($totalRevenue, 2); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Travelers</h5>
                            <p class="card-text"><?php echo $totalTravelers; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings Table -->
            <div class="card mt-5">
                <div class="card-header">
                    Recent Bookings
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Package ID</th>
                                <th>User ID</th>
                                <th>Total Travelers</th>
                                <th>Total Price (Rs.)</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filteredBookings as $booking): ?>
                                <tr>
                                    <td><?php echo $booking['package_id']; ?></td>
                                    <td><?php echo $booking['user_id']; ?></td>
                                    <td><?php echo $booking['total_travelers']; ?></td>
                                    <td>Rs. <?php echo number_format($booking['total_price'], 2); ?></td>
                                    <td><?php echo $booking['created_at']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Packages Table -->
            <div class="card mt-5">
                <div class="card-header">
                    Top Packages
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Package ID</th>
                                <th>Total Travelers</th>
                                <th>Total Revenue (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($packageStats as $packageId => $stats): ?>
                                <tr>
                                    <td><?php echo $packageId; ?></td>
                                    <td><?php echo $stats['total_travelers']; ?></td>
                                    <td>Rs. <?php echo number_format($stats['total_revenue'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
}

?>
