<?php

if ( ! defined ( 'ASSETS_URL') ){

	define('ASSETS_URL', '/assets'); //Path for assets.
}

if ( ! defined ( 'TEMPLATE_PATH') ){

	define('TEMPLATE_PATH', __DIR__ . '/templates/'); //Path for templates.
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

if ( ! function_exists ( 'get_current_user' ) ){
	//Get the current user attribute.
	function get_current_user( $attr = 'user_id' ){
		switch ( $attr ){
			case 'id':
				return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
				break;
			case 'username':
				return isset($_SESSION['user_name']) ? $_SESSION['username'] : false;
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
	}
}

if ( ! function_exists ('get_script') ){
	//Get the script URL.
	function get_script(  ){
		$jsDir = __DIR__ . '/assets/js/';
		$js_files = scandir($jsDir);
		$js_files = array_filter($js_files, function($file){
			return preg_match('/\.min\.js$|\.js$|^bundle\.js$/', $file);
		});
		foreach ($js_files as $script) {
			echo"<script src='" . ASSETS_URL . "/js/$script''></script>";
			echo  "\n";
		}

	}
}

if ( ! function_exists ('admin_header') ){
	//Get the header.
	function admin_header($header = 'Dashboard'){
		include_once TEMPLATE_PATH . 'admin/header.php';
	}
}

if ( ! function_exists ('admin_sidebar') ){
	//Admin panel sidebar
	function admin_sidebar(  ){
		include_once TEMPLATE_PATH . 'admin/sidebar.php';
	}
}

if ( ! function_exists ('admin_header_nav') ){
	//Admin panel header navigation
	function admin_header_nav(  ){
		include_once TEMPLATE_PATH . 'admin/header-nav.php';
	}
}

if ( ! function_exists ('admin_footer') ){
	//Admin panel footer
	function admin_footer(  ){
		include_once TEMPLATE_PATH . 'admin/footer.php';
	}
}
