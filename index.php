<?php

$page = isset( $_GET['page'] ) ? $_GET['page'] : 'home';

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	die( 'Vendor directory not found. Please run composer install.' );
	}
use App\TTMS\Database\Config as DBConfig;
use App\TTMS\Database\Operations\UserOperations;
use App\TTMS\Users\Algorithms;

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
$is_logged_in = is_logged_in();
$nav_style = "position: absolute; top: 0; width: 100%; z-index: 10; background: transparent";

if(isset($_GET['page']) && 'individual' === $_GET['page'] && isset($_GET['id'])){
	$id = (int)$_GET['id'];
	$get_package = new UserOperations('packages');
	$package = $get_package->get_individual_data_from_id($id);

	include_once TEMPLATE_PATH . 'users/individual-card.php';
	include_once TEMPLATE_PATH . 'footer.php';
	return ;
}elseif(isset($_GET['page']) && 'trending' === $_GET['page'] ){
	$nav_style = "background:gray;";
	$get_all_packages = new UserOperations('packages');
	$packages = $get_all_packages->get_all_data();
	$packages = filter_expired_packages($packages);
	$packages = Algorithms::trending_algo($packages);

	$get_categories = new UserOperations('categories');
	$all_categories = $get_categories->get_all_data();
	include_once TEMPLATE_PATH .'users/navigation.php';
}elseif(isset($_GET['page']) && 'recommendation' === $_GET['page']){
	$nav_style = "background:gray;";
	$get_all_packages = new UserOperations('packages');
	$packages = $get_all_packages->get_all_data();
	$packages = filter_expired_packages($packages);

	$get_categories = new UserOperations('categories');
	$all_categories = $get_categories->get_all_data();
	$user_id = $is_logged_in ? get_current_user_attr('user_id') : 0;


	$packages = Algorithms::recommendation_algo($packages , $all_categories , $user_id);


	include_once TEMPLATE_PATH .'users/navigation.php';
}else{
	$get_all_packages = new UserOperations('packages');
	$packages = $get_all_packages->get_all_data();
	$get_categories = new UserOperations('categories');
	$all_categories = $get_categories->get_all_data();
	$packages = filter_expired_packages($packages);
	include_once TEMPLATE_PATH . 'users/carousel.php';

}
include_once TEMPLATE_PATH . 'users/packages.php';
include_once TEMPLATE_PATH . 'footer.php';


function filter_expired_packages($packages){
	$filtered_packages = array();
	foreach($packages as $package){
		$deadline = $package['deadline'];
		$today = date('Y-m-d');
		if($deadline >= $today){
			$filtered_packages[] = $package;
		}
	}
	return $filtered_packages;
}

function trending_algo($packages){

}

?>
