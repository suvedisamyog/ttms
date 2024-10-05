<?php

$page = isset( $_GET['page'] ) ? $_GET['page'] : 'home';

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	die( 'Vendor directory not found. Please run composer install.' );
	}
use App\TTMS\Database\Config as DBConfig;

print_r (DBConfig::getConnection() );
// switch ( $page ) {
// 	case 'home':
// 		include 'home.php';
// 		break;
// 	case 'about':
// 		include 'about.php';
// 		break;
// 	case 'contact':
// 		include 'contact.php';
// 		break;
// 	default:
// 		include '404.php';
// 		break;
// }

?>
