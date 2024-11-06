
<div class="container packages-card my-4">
    <div class="row">
		<?php
		foreach($packages as $package){
			?>
 			<div class="col-md-4 mb-4">
            <div class="card card-pop">
                <img src="<?php echo $package['thumbnail'] ?>" class="card-img-top" alt="Singapore And Bali">
                <div class="card-body">
				<p class="card-text">
   				 <strong><?php echo $package['days'] ?? 0 ?> days & <?php echo $package['nights'] ?? 0 ?> nights</strong>
   					 <span class="text-success">
       					 ★ <?php echo $package['rating'] ?? 0; ?> (<?php echo $package['total_review'] ?? 0; ?>)
   					 </span>
				</p>
                    <h5 class="card-title"><?php echo $package['name'] ?? 'Package Name' ?></h5>
                    <p><small>
						<?php
						$count = 0;
						$selected_categories =isset($package['category']) ? json_decode($package['category'] ,true) : array();
						foreach($all_categories as $category){

							if(in_array($category['id'], $selected_categories)){
								 if($count < 4){
									 echo "<span class='badge text-bg-info'>{$category['name']}</span>  ";
									 $count++;
								 }
							}
						}
						?>
					</small></p>
					<?php if( isset($package['discount'] ) && $package['discount'] > 0){
						$price = $package['price'] ?? 0;
						$discount = $package['discount'] ?? 0;
						$discounted_price = $price - ($price * $discount / 100);
						?>
						<p class="h5 discount">NPR <?php echo $discounted_price ?>
						<del>NPR <?php echo $price ?></del> <span class="text-success"><?php echo $discount . '%'  ?></span></p>
						<?php
					}else{
						?>
						<p class="h5 discount">NPR <?php echo $package['price'] ?? 0 ?>
						<?php
					} ?>
					<p class="card-text description" title="Click to view detail"><?php echo $package['description'] ?></p>
                    <a href="?page=individual&id=<?php echo (int)$package['id'] ?>" class="btn btn-warning w-100">View Details</a>
                </div>
            </div>
        </div>
			<?php
		}
		?>
    </div>
</div>
