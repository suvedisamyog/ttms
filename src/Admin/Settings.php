<?php

namespace App\TTMS\Admin;

use App\TTMS\Database\Operations\UserOperations;

class Settings {

	public function __construct() {
		self::payment_settings();
		self::appearance_settings();
	}

	public static function payment_settings() {
		$get_all_currencies =  new UserOperations('currencies');;
		$currencies = $get_all_currencies->get_all_data();
		lg($currencies);
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
           				   data-page-size="2"
           				   data-page-list="[2, 4, 6]"
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
										?>
										<tr>
											<td><?php echo $key + 1; ?></td>
											<td><?php echo $value['name']; ?></td>
											<td><?php echo $value['symbol']; ?></td>
											<td>
												<?php  $id = $value['id'] ?>
												<button class="btn btn-primary btn-sm" data-id="<?php echo $id ?>">Edit</button>
												<button class="btn btn-danger btn-sm" data-id="<?php echo $id ?>">Delete</button>
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

	public static function appearance_settings() {
		?>
			<div class="container mt-5 ">
				<h4 class="mb-5">Appearance Settings</h4>

				<div class="d-flex">
					<h5>Color Scheme</h5>
					<button class="btn btn-success btn-md ms-auto" id="add_currency">Add New Currency</button>

				</div>
			</div>

		<?php
	}

}
