<?php
use App\TTMS\Database\Operations\UserOperations;
$all_packages = new UserOperations('packages');
$packages = $all_packages->get_all_data();
$similar_packages = [];
$current_package = $package;
foreach ($packages as $new_package) {
	if($new_package['id'] === $package['id']){
		continue; //skipping same package
	}

	// Initialize similarity score
	$similarity_score = 0;

	 // 1. Check category similarity
	 $current_categories = json_decode($current_package['category']);
	 $new_categories = json_decode($new_package['category']);
	 $common_categories = array_intersect($current_categories, $new_categories);
	 $similarity_score += count($common_categories) * 50;

	 // 2. Check price similarity (inverse scoring based on difference)
	 $price_diff = abs($current_package['price'] - $new_package['price']);
	 $similarity_score += max(0, 25 - $price_diff);

	 // 3. Check rating similarity
	 $rating_diff = abs($current_package['rating'] - $new_package['rating']);
	 $similarity_score += max(0, 25 - $rating_diff * 4);
	 $similar_packages[] = [
        'package' => $new_package,
        'score' => $similarity_score
    ];
}

usort($similar_packages, function($a, $b) {
    return $b['score'] - $a['score'];
});

$top_similar_packages = array_slice($similar_packages, 0, 5);
$top_similar_packages = array_map(function($item) {
	return $item['package'];
}, $top_similar_packages);
$top_similar_packages = (empty($top_similar_packages)) ? array() : $top_similar_packages;
echo '	<h4>Similar Packages</h4>';
foreach($top_similar_packages as $package){
	?>
	<div class="card mt-3">
		<div class="card shadow-sm">
			<div class="card-body">
				<img src="<?php echo $package['thumbnail'] ?>" class="card-img-top" alt="<?php echo $package['name'] ?? 'Image' ?>">
				<h5 class="card-title"><?php  echo $package['name'] ?? '' ?></h5>
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<h6 class="text-decoration-line-through text-muted">$300.00</h6>
						<h5 class="text-success">$250.00</h5>
					</div>
					<a href="#" class="btn btn-primary">Details</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}

?>
