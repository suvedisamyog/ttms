<div class="col">
    <div class="card">
		<img src="<?php echo isset($package['thumbnail']) ?	 $package['thumbnail'] : ''; ?>" class="card-img-top" alt="...">
		<div class="card-body ">
            <h5 class="card-title"><?php echo isset($package['name']) ? $package['name']  : '' ?></h5>
            <p class="card-text truncate" title="Click to view detail">
				<?php echo isset($package['description']) ? $package['description']  : '' ?>
            </p>
			<p class="card-text">
    			<small class="text-muted">
        			<?php
        				echo isset($package['updated_at']) ? 'Updated at ' . $package['updated_at'] : 'Created at ' . $package['created_at'];
        			?>
    			</small>
			</p>
     	</div>
		<?php if('admin' === get_current_user_attr('role')){
		 ?>
		 <div class="card-footer">
			<?php $id = $package['id'] ?>
			<div class="d-flex justify-content-between">
				<button class="btn btn-warning btn-sm ">
					<a href="<?php echo ADMIN_URL . '?page=packages&tab=create-package&id=' .$id  ?>">Edit</a>
				</button>
				<button class="btn btn-danger btn-sm delete_btn" data-action="packages" data-id="<?php echo $id ?>">Delete</button>
				<button class="btn btn-success btn-sm view_btn" data-id="<?php echo $id ?>">View</button>
			</div>
		</div>
		 <?php }elseif ('user' === get_current_user_attr('role')){
				?>
				<div class="card-footer">
					<?php $id = $package['id'] ?>
					<div class="d-flex justify-content-between">
						<button class="btn btn-primary btn-sm view_btn" data-id="<?php echo $id ?>">View</button>
						<button class="btn btn-success btn-sm booking_btn" data-id="<?php echo $id ?>">Book Now</button>
					</div>
				<?php
		 } ?>

	</div>
</div>
