<?php

namespace App\TTMS\Admin;

class Dashboard {


	public static function dashboard_header($title = "Dashboard") {
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo $title; ?></title>
		<?php
			get_stylesheet();
			// get_script();
		?>
	</head>
	<body>
	<div class="wrapper">
        <!-- Sidebar  -->

        <!-- Page Content  -->

    </div>

	<?php
	}


	public static function dashboard_footer() {
	?>
	<footer></footer>
		<p>&copy; <?php echo date('Y'); ?> TTMS</p>
	</footer>
	<?php get_script(); ?>
	</body>

	</html>
	<?php

	}



}
