<?php

$page = isset( $_GET['page'] ) ? $_GET['page'] : 'home';

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	die( 'Vendor directory not found. Please run composer install.' );
	}
use App\TTMS\Database\Config as DBConfig;
use App\TTMS\Database\Operations\UserOperations;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home</title>
	<?php
		get_stylesheet();
		// get_script();
	?>
</head>
<body>
<?php
session_start();

if(isset($_GET['page']) && 'individual' === $_GET['page'] && isset($_GET['id'])){
	$id = (int)$_GET['id'];
	$get_package = new UserOperations('packages');
	$package = $get_package->get_individual_data_from_id($id);


	include_once TEMPLATE_PATH . 'users/individual-card.php';
	include_once TEMPLATE_PATH . 'footer.php';
	return ;


}else{
	$get_all_packages = new UserOperations('packages');
	$packages = $get_all_packages->get_all_data();
	$get_categories = new UserOperations('categories');
	$all_categories = $get_categories->get_all_data();
	include_once TEMPLATE_PATH . 'users/carousel.php';
	include_once TEMPLATE_PATH . 'users/packages.php';

}
include_once TEMPLATE_PATH . 'footer.php';


?>
