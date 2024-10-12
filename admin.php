<?php

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	die( 'Vendor directory not found. Please run composer install.' );
	}
use App\TTMS\Admin\Dashboard;
print_r($_GET);
$page = isset( $_GET['page'] ) ? $_GET['page'] : 'dashboard';
print_r($page);

template_header( ucfirst( $page ));
admin_sidebar();
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
	default:
		include '404.php';
		break;
}
template_footer();




?>
