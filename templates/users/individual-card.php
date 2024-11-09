<?php
use App\TTMS\Database\Operations\UserOperations;

$banner_img = isset($package['other_images']) ? json_decode($package['other_images']) : array();
if (empty($banner_img)) {
    $banner_img = array($package['thumbnail']);
}

$bookings = new UserOperations('bookings');
$all_bookings = $bookings->get_all_data([
	'where_clause' => 'package_id',
	'where_clause_value' => $package['id'],
]);
$total_users_booked = 0;
foreach ($all_bookings as $booking) {
	$total_users_booked += $booking['total_travelers'] ;
}
$remaining_booking = $package['total_travelers'] - $total_users_booked;
?>

<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($banner_img as $key => $img): ?>
            <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                <img src="<?php echo htmlspecialchars($img); ?>" class="" style="width: 100vw; height: 50vh; object-fit: cover" alt="Image <?php echo $key + 1; ?>" />
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev rounded-control" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next rounded-control" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="m-2 d-flex flex-md-column flex-lg-row">
    <!-- Left Aside Panel -->
    <aside class="left-panel flex-grow-1 mr-1 mt-3">
        <div class="card rounded  p-3">
            <h3 class="mb-3 text-primary">Package Details</h3>

            <!-- Package Name and Price with Discount -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-dark"><?php echo htmlspecialchars($package['name']); ?></h4>
                <div class="text-end">
                    <?php if ($package['discount'] > 0): ?>
                        <span class="text-muted text-decoration-line-through me-2">$<?php echo ceil($package['price']); ?></span>
                        <span class="badge bg-warning text-dark fs-6"><?php echo htmlspecialchars($package['discount']); ?>% OFF</span><br />
						<?php $price_per_person = ceil($package['price'] * (1 - $package['discount'] / 100))  ?>
                        <span class="badge bg-primary fs-5 p-2">Discounted Price: Rs <?php echo $price_per_person; ?></span>
                    <?php else:
						$price_per_person = ceil($package['price'], 2);
						?>
                        <span class="badge bg-primary fs-5 p-2">Price: $<?php echo ceil($package['price'], 2); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Package Description with Parsed HTML -->
            <div class="mb-4">
                <h5 class="text-secondary">Description</h5>
                <div class="text-muted" style="line-height: 1.6"><?php echo $package['description']; ?></div>
            </div>

            <!-- Travel Information -->
            <div class="mb-4">
                <h5 class="text-secondary">Travel Information</h5>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Total Travelers:</strong> <?php echo htmlspecialchars($package['total_travelers']);  ?> <span class="ml-1 text-warning">(<?php echo $remaining_booking ?> - Remaining)</span></li>
                    <li class="list-group-item"><strong>Days:</strong> <?php echo htmlspecialchars($package['days']); ?></li>
                    <li class="list-group-item"><strong>Nights:</strong> <?php echo htmlspecialchars($package['nights']); ?></li>
                </ul>
            </div>

            <!-- Deadline -->
            <div class="mb-4">
                <h6 class="text-secondary">Deadline</h6>
                <p class="text-danger fw-bold"><?php echo htmlspecialchars(date("F d, Y", strtotime($package['deadline']))); ?></p>
            </div>

            <!-- Category and Rating -->
            <div class="mb-4">
                <h5 class="text-secondary">Category</h5>
                <?php
                $selected_categories = isset($package['category']) ? json_decode($package['category'], true) : array();
                $get_all_categories = new UserOperations('categories');
                $all_categories = $get_all_categories->get_all_data();
                foreach ($all_categories as $category) {
                    if (in_array($category['id'], $selected_categories)) {
                        echo "<span class='badge text-bg-info'>{$category['name']}</span> ";
                    }
                }
                ?>
            </div>

            <div class="mb-4">
                <h5 class="text-secondary">Rating</h5>
                <div class="text-warning">
                    <?php
					$ratings = new UserOperations('comments_and_ratings');
					$ratings_data = $ratings->get_all_data([
						'where_clause' => 'package_id',
						'where_clause_value' => $package['id'],
					]);
					$average_rating = 0;
					$total_reviews = 0;
					if (!empty($ratings_data)) {

						$total_ratings = array_sum(array_column($ratings_data, 'rating'));
						$user_count = count($ratings_data);
						$average_rating = $total_ratings / $user_count;


						$average_rating = min($average_rating, 5);
						$total_reviews = count($ratings_data);
					}

                    $filledStars = floor($average_rating);
                    for ($i = 0; $i < 5; $i++) {
                        echo $i < $filledStars ? "&#9733;" : "&#9734;";
                    }
                    ?>
                    <span class="text-muted">(<?php echo htmlspecialchars($average_rating) ?> / 5.0)</span>
					<?php
					if ($total_reviews > 0) {
						echo "<span class='text-muted'>($total_reviews reviews)</span>";
					}
					?>
                </div>
            </div>

            <!-- Author and Creation Date -->
            <div class="d-flex justify-content-between mt-4">
                <?php
                $author = $package['author'];
                $users = new UserOperations('users');
                $author = $users->get_individual_data_from_id((int) $author);
                $author_name = $author['username'] ?? 'Unknown';
                $author_email = $author['email'] ?? 'Unknown';
                ?>
                <div class="d-flex">
                    <i class="fas fa-user rounded-circle bg-primary text-white me-3" style="font-size: 20px; padding: 10px">Author</i>
                    <div>
                        <h5 class="text-primary"><?php echo htmlspecialchars($author_name); ?></h5>
                        <span class="text-muted"><?php echo htmlspecialchars($author_email); ?></span>
                    </div>
                </div>
                <span class="text-muted">Created on: <?php echo htmlspecialchars(date("F d, Y", strtotime($package['created_at']))); ?></span>
            </div>

            <?php
			if( ! is_logged_in()){
				?>
            	<a href="<?php 'login.php?redirect=' . SITE_URL . '?page=individual&id=' . $_GET['id'] ?>" class="btn btn-warning mt-4">Login To Book</a>

				<?php
			}else{
				if($remaining_booking <= 0){
					?>
					<button type="button" class="btn btn-danger mt-4 disabled" >Sorry ! No slot left</button>
				<?php
				}
				else{
					?>

				<button type="button" class="btn btn-warning mt-4" data-bs-toggle="modal" data-bs-target="#bookingModal">Proceed Booking</button>
				 <!-- Booking Modal -->
				 <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bookingModalLabel">Book Your Package</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                                <div class="modal-body">
                                    <input type="hidden" id ="hidden_package_id" name="package_id" value="<?php echo htmlspecialchars($package['id']); ?>">
                                    <div class="mb-3">
                                        <label for="adults" class="form-label">Number of Travelers</label>
                                        <input type="number" name="t_travels" id="t_travels" class="form-control" min="1" max=<?php echo $remaining_booking ?> value="1" required>
										<span class="text-danger m-2" id="t_travels-error"></span>
                                    </div>
									<div class="mb-3">
									<label for="adults" class="form-label">Price / Person(RS)</label>
                                        <input type="text" name="p_price" id="p_price" class="form-control" value="<?php echo $price_per_person ?>" required readonly disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Total price(Rs)</label>
										<input type="number" name="t_payment" id="t_payment" class="form-control" value="<?php echo $price_per_person ?>" required readonly disabled>
										</div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="start_payment">Confirm Booking</button>
                                </div>
                        </div>
                    </div>
                </div>

				<?php
				}
			}
            ?>

        </div>

		<!-- Rating and comment section -->
		 <?php
		 if(is_logged_in()){
			include_once TEMPLATE_PATH . 'users/rating-comment-section.php';
		 }else{
			echo "<p class='mt-3'>To rate,view or add comment you must
			 <a class='text-primary' href='login.php?redirect=" . SITE_URL . "?page=individual&id=" . $_GET['id'] . "'>Login</a> first.
			</p>";
		 }

		 ?>

    </aside>

    <!-- Right Aside Panel -->
    <aside class="right-panel p-3 flex-grow-2" style="max-width: 400px">
        <!-- Content for the right panel -->
        <?php include_once TEMPLATE_PATH . 'users/similar-packages.php'; ?>
    </aside>
</div>
