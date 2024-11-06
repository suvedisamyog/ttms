<?php

namespace App\TTMS\Admin;

use App\TTMS\Database\Operations\UserOperations;

class Settings {

	public function __construct() {
		self::currencies_settings();
		self::category_settings();
	}

	public static function currencies_settings() {
		$get_all_currencies =  new UserOperations('currencies');;
		$currencies = $get_all_currencies->get_all_data();
		?>
			<div class="container mt-5 ">
				<div class="d-flex">
					<h4>Currency Settings</h4>
					<div class="ms-auto">
						<button class="btn btn-success btn-md" id="add_currency">Add New Currency</button>
					</div>
				</div>
    				<table id="myTable" class="table table-bordered"
           				   data-toggle="table"
           				   data-pagination="true"
           				   data-search="true"
           				   data-sortable="true"
           				   data-page-size="5"
           				   data-page-list="[5, 10, 15 ,20 ]"
          				    >
        					<thead	thead class="thead-light">
            					<tr>
            					    <th data-field="sn" data-sortable="true">SN</th>
            					    <th data-field="name" data-sortable="true">Name</th>
            					    <th data-field="symbol" data-sortable="true">Symbol</th>
            					    <th data-field="action">Action</th>
            					</tr>
        					</thead>
        					<tbody>
								<?php
									foreach ($currencies as $key => $value) {
										$id = $value['id']
										?>
										<tr data-action="currencies">
											<td><?php echo $key + 1; ?></td>
											<td><?php echo $value['name']; ?></td>
											<td><?php echo $value['symbol']; ?></td>
											<td>
												<button class="btn btn-primary btn-sm setting_edit_btn"  data-id="<?php echo $id ?>">Edit</button>
												<button class="btn btn-danger btn-sm delete_btn" data-id="<?php echo $id ?>">Delete</button>
											</td>
										</tr>
										<?php
									}
								?>

        					</tbody>
   					 </table>
			</div>
		<?php
	}

	public static function category_settings() {
		$get_all_categories =  new UserOperations('categories');
		$categories = $get_all_categories->get_all_data();
		?>
			<div class="container mt-5 ">
				<div class="d-flex">
					<h4>Category Settings</h4>
					<div class="ms-auto">
						<button class="btn btn-success btn-md" id="add_category">Add New Category</button>
					</div>
				</div>
    				<table id="myTable" class="table table-bordered"
           				   data-toggle="table"
           				   data-pagination="true"
           				   data-search="true"
           				   data-sortable="true"
           				   data-page-size="5"
           				   data-page-list="[5, 10, 15 ,20 ]"
          				    >
        					<thead	thead class="thead-light">
            					<tr>
            					    <th data-field="sn" data-sortable="true">SN</th>
            					    <th data-field="name" data-sortable="true">Name</th>
            					    <th data-field="action">Action</th>
            					</tr>
        					</thead>
        					<tbody>
								<?php
									foreach ($categories as $key => $value) {
										$id = $value['id']
										?>
										<tr data-action="categories">
											<td><?php echo $key + 1; ?></td>
											<td><?php echo $value['name']; ?></td>
											<td>
												<button class="btn btn-primary btn-sm setting_edit_btn"  data-id="<?php echo $id ?>">Edit</button>
												<button class="btn btn-danger btn-sm delete_btn" data-id="<?php echo $id ?>">Delete</button>
											</td>
										</tr>
										<?php
									}
								?>

        					</tbody>
   					 </table>
			</div>

		<?php
	}

}
