<?php
if( ! defined ( 'SITE_URL') ){
	define('SITE_URL', 'https://ttms.me'); //URL for the site.
}
if ( ! defined ( 'ASSETS_URL') ){

	define('ASSETS_URL', '/assets'); //URL for assets.
}
if ( ! defined ( 'ASSETS_DIR') ){

	define('ASSETS_DIR', '/assets'); //DIR for assets.
}

if ( ! defined ( 'TEMPLATE_PATH') ){

	define('TEMPLATE_PATH', __DIR__ . '/templates/'); //Path for templates.
}

if ( ! defined ('ADMIN_URL') ){
	define ('ADMIN_URL' , 'https://ttms.me/admin.php');
}


if ( ! function_exists ( 'is_logged_in' ) ){
	//Check if the user is logged in.

	function is_logged_in(){

		if ( isset ( $_SESSION['user_id'] ) ){
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists ( 'get_current_user_attr' ) ){
	//Get the current user attribute.
	function get_current_user_attr( $attr = 'user_id' ){
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		switch ( $attr ){
			case 'user_id':
				return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
				break;
			case 'username':
				return isset($_SESSION['username']) ? $_SESSION['username'] : false;
				break;
			case 'email':
				return isset($_SESSION['email']) ? $_SESSION['email'] : false;
				break;
			case 'role':
				return isset($_SESSION['role']) ? $_SESSION['role'] : false;
				break;
			default:
				return false;
				break;
		}

	}
}

if ( ! function_exists ('get_stylesheet') ){
	//Get the stylesheet URL.
	function get_stylesheet(  ){
		$cssDir = __DIR__ . '/assets/css/';
		$css_files = scandir($cssDir);
		$css_files = array_filter($css_files, function($file){
			return preg_match('/\.min\.css$|\.css$/', $file);
		});
		foreach ($css_files as $style) {
            echo "<link rel='stylesheet' href='" . ASSETS_URL . "/css/$style'>";
			echo  "\n";
		}
		echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
';
	}
}

	if ( ! function_exists ('get_script') ){
		//Get the script URL.
		function get_script(  ){
			// $jsDir = __DIR__ . '/assets/js/';
			// $js_files = scandir($jsDir);
			// $js_files = array_filter($js_files, function($file){
			// 	return preg_match('/\.min\.js$|\.js$|^bundle\.js$/', $file);
			// });
			// foreach ($js_files as $script) {
			// 	echo"<script src='" . ASSETS_URL . "/js/$script''></script>";
			// 	echo  "\n";
			// }
			//commented above code because jquey needs to be loaded before bootstrap

			echo "<script src='" . ASSETS_URL . "/js/jquery.min.js'></script>";
			echo "\n";
			echo "<script src='" . ASSETS_URL . "/js/bootstrap.bundle.min.js'></script>";
			echo "\n";
			echo "<script src='" . ASSETS_URL . "/js/script.js'></script>";
			echo "\n";
			echo "<script src='" . ASSETS_URL . "/js/sweetalert2.all.min.js'></script>";
			echo "\n";
			echo "<script src='" . ASSETS_URL . "/js/bootstrap-table.min.js'></script>";
			echo "\n";
			echo "<script src='" . ASSETS_URL . "/js/bootstrap-multiselect.min.js'></script>";
			echo "\n";

			echo '<script src="https://cdn.tiny.cloud/1/vl1vhdbrftqx9zheijliyzpht1x6g6egh68ubihrle1p7lk5/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>';



		}
	}

if ( ! function_exists ('template_header') ){
	//Get the header.
	function template_header($header = 'Dashboard'){
		include_once TEMPLATE_PATH . 'header.php';
	}
}

if ( ! function_exists ('admin_sidebar') ){
	//Admin panel sidebar
	function admin_sidebar( $page_slug = array() ){
		include_once TEMPLATE_PATH . 'admin/sidebar.php';
	}
}

if ( ! function_exists ('admin_header_nav') ){
	//Admin panel header navigation
	function admin_header_nav(  ){
		include_once TEMPLATE_PATH . 'admin/header-nav.php';
	}
}

if ( ! function_exists ('template_footer') ){
	//template_footer
	function template_footer(  ){
		include_once TEMPLATE_PATH . 'footer.php';
	}
}

if ( ! function_exists ('sidebar_menu') ){
	//template_login
	function sidebar_menu(  ){
		$menu = [
			'Home' => ADMIN_URL,
			'Bookings' => ADMIN_URL .'?page=admin-bookings',
			'Settings' => ADMIN_URL . '?page=settings',
			'Packages' => [
				'id' => 'packages',
				'submenu' => [
					'Create Package' => '?page=packages&tab=create-package',
					'Manage Package' => '?page=packages&tab=manage-package',
				]
			]

		];
		return $menu;
	}
}


if ( ! function_exists ('lg') ){
	ini_set('log_errors', 'On');
	ini_set('error_log', (__DIR__) . '/debug.log');
	//For Debuggin purpose only
	function lg($message) {
		error_log(date('[Y-m-d H:i:s] ') . print_r($message, true) . PHP_EOL, 3, (__DIR__) . '/debug.log');
	}
}
