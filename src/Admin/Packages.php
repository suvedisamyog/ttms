<?php

namespace App\TTMS\Admin;

use App\TTMS\Database\Operations\UserOperations;


class Packages{

	public static function create_package(){
		$form_title = 'Create Package';
		$btn_name = 'Create New Package';
		$btn_class = 'btn_update';
		$name = '';
		$price = '';
		$no_of_days = '';
		$no_of_nights = '';
		$discount = '';
		$total_travelers = '';
		if(isset($_GET['id']) && !empty($_GET['id'])){
			$id = $_GET['id'];
			// $get_package = new UserOperations('packages');
			// $package = $get_package->get_individual_data_from_id($id);
			$form_title = 'Edit Package';
			$btn_name = 'Update Package';
			$btn_class = 'btn_create';
			$name = $package['name'] ?? '';
			$price = $package['price'] ?? '';
    		$no_of_days = $package['days'] ?? '';
    		$no_of_nights = $package['nights'] ?? '';
    		$discount = $package['discount'] ?? '';
    		$total_travelers = $package['total_travelers'] ?? '';
		}
		?>
		<div class="container mt-5">
			<div class="d-flex">
				<h4><?php $form_title ?></h4>
			</div>
			<form id="packages_form">
				<div class="row">
					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_name">Package Name <spam class="text-danger" >*</spam></label>
							<input type="text" name="package_name" class="form-control form-control-lg" value="<?php echo $name ?>" required/>
							<span class="text-danger m-2" id="package_name-error"></span>
						</div>
					</div>

					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_total_travelers">Total Travelers<span class="text-danger">*</span></label>
							<input type="number" min="1" name="package_total_travelers" class="form-control form-control-lg" value="<?php echo $total_travelers ?>" required/>
							<span class="text-danger m-2" id="package_total_travelers-error"></span>
						</div>
					</div>


					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_days">No of Days <span class="text-danger">*</span></label>
							<input type="number" min="1" name="package_days" class="form-control form-control-lg" value="<?php echo $no_of_days?>" required/>
							<span class="text-danger m-2" id="package_days-error"></span>
						</div>
					</div>

					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_nights">Number of Nights<span class="text-danger">*</span></label>
							<input type="number" min="0" name="package_nights" class="form-control form-control-lg" value="<?php echo $no_of_nights?>" required/>
							<span class="text-danger m-2" id="package_nights-error"></span>
						</div>
					</div>

					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_price">Package Price <spam class="text-danger" >*</spam></label>
							<input type="text" name="package_price" class="form-control form-control-lg" value="<?php echo $price ?>" required/>
							<span class="text-danger m-2" id="package_price-error"></span>
						</div>
					</div>

					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_deadline">Registration Deadline <spam class="text-danger" >*</spam></label>
							<input type="date" name="package_deadline" class="form-control form-control-lg" value="<?php echo $price ?>" required/>
							<span class="text-danger m-2" id="package_deadline-error"></span>
						</div>
					</div>

					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_thumbnail_image">Thumbnail Image <span class="text-danger">*</span></label>
							<input type="file" name="package_thumbnail_image" class="form-control form-control-lg" required/>
							<span class="text-danger m-2" id="package_thumbnail_image-error"></span>
						</div>
					</div>


						<div class="col-md-6 mb-4">
							<div data-mdb-input-init class="form-outline">
								<label class="form-label" for="package_discount">Discount (%)</label>
								<input type="number" min="0" max="100" name="package_discount" class="form-control form-control-lg" value="<?php echo $discount?>" />
								<span class="text-danger m-2" id="package_discount-error"></span>
							</div>
						</div>


					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_other_images">Other Images</label>
							<input type="file" name="package_other_images[]" class="form-control form-control-lg" multiple/>
							<span class="text-danger m-2" id="package_other_images-error"></span>
						</div>
					</div>

					<div class="col-md-6 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_categories">Categories</label>
							<select id="package_form_categories" name="package_categories[]" multiple="multiple" class="form-control form-control-lg multiselect">
               					<?php
									$get_categories = new UserOperations('categories');
									$categories = $get_categories->get_all_data();
									foreach ($categories as $key => $value) {
										?>
										<option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
										<?php
									}
								?>
            				</select>
							<span class="text-danger m-2" id="other_images-error"></span>
						</div>
					</div>

					<div class="col-md-12 mb-4">
						<div data-mdb-input-init class="form-outline">
							<label class="form-label" for="package_description">Description</label>
							<textarea id="package_description" name="package_description" rows="10" class="form-control"></textarea>
						</div>
					</div>


				</div>
					<button type="submit" class="btn btn-primary col-md-12 p-3 <?php echo $btn_class ?>"><?php echo $btn_name ?></button>

			</form>
		</div>
		<?php

	}

	public static function manage_package(){
		?>
		<div class="row row-cols-1 row-cols-md-3 g-4">
		<?php
			$get_all_packages = new UserOperations('packages');
			$packages = $get_all_packages->get_all_data([
				'where_clause' => 'author',
				'where_clause_value' => get_current_user_attr('user_id'),

			]);
			foreach($packages as $package){
				lg($package);
				include TEMPLATE_PATH . '/package-card.php';
			}
		?>
		</div>
		<?php
	}
}

?>
