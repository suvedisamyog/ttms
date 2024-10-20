<?php

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	die( 'Vendor directory not found. Please run composer install.' );
	}
use App\TTMS\Admin\Dashboard;
use App\TTMS\Admin\Settings;
use App\TTMS\Admin\Packages;
session_start();
if ( ! is_logged_in()  && get_current_user_attr('role') !== 'admin' ) {
	header('Location: login.php');
	exit;
}
$page = isset( $_GET['page'] ) ? $_GET['page'] : 'dashboard';
$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';

template_header( ucfirst( $page ));
admin_sidebar($_GET);
admin_header_nav();
switch ( $page ) {
	case 'dashboard':
		new Dashboard();
		break;
	case 'about':
		include 'about.php';
		break;
	case 'contact':
		include 'contact.php';
		break;
	case 'settings':
		new Settings();
		break;
	case 'packages':
		get_tabs( $tab );
		break;
	default:
		include '404.php';
		break;
}

function get_tabs( $tabs ){
	switch ( $tabs ) {
		case 'create-package':
			Packages::create_package();
			break;
		case 'manage-package':
			Packages::manage_package();
			break;
		default:
			include '404.php';
			break;
	}

}


template_footer();




?>
