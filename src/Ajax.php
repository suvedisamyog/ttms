<?php
namespace App\TTMS;

if ( file_exists( dirname(__DIR__)  . '/vendor/autoload.php' ) ) {
	require_once dirname(__DIR__)  . '/vendor/autoload.php';
} else {
	die( 'Vendor directory not found. Please run composer install.' );
	}

use App\TTMS\Database\Operations\UserOperations;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	switch ($_POST['action']){
		case 'register':
			handel_registration();
			break;
		case 'login':
			handel_login();
			break;
		case 'add_currency':
			handel_currency();
		case 'add_category':
			handel_category();
		case 'add_package':
			handel_package('add');
		case 'update_package':
			handel_package('update');
		case 'add_comment':
			handel_comment('add');
		case 'update_comment':
			handel_comment('update');

			break;
	}
}elseif( ($_SERVER['REQUEST_METHOD'] == 'DELETE') ) {
	parse_str(file_get_contents('php://input'), $_DELETE);
    $table = isset($_DELETE['action']) ? $_DELETE['action'] : '';
    $id = isset($_DELETE['id']) ? $_DELETE['id'] : '';
	$index = isset($_DELETE['index']) ? $_DELETE['index'] : '';

		$response = array();
		//return error if id or action is empty
		if( empty($id) || empty($table) ){
			handel_error();
		}

		$delete = new UserOperations($table);
		$result = $delete->delete_data($id);
		if ($result) {
			$response['status'] = 1;
			$response['message'] = "Deleted successfully!";
			$response['action'] = 'delete';
			$response['index'] = $index;
		} else {
			handel_error("Error while deleting ! Please try again.");
		}
		header('Content-Type: application/json');
		echo json_encode($response);
		return;

}elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
	// Get the PUT parameters
	// Get the JSON data

	$input = file_get_contents('php://input');
	$data = json_decode($input, true);
	$action = isset($data['action']) ? $data['action'] : '';
	$id = isset($data['id']) ? $data['id'] : '';
	$name = isset($data['name']) ? $data['name'] : '';
	$table = '';
	$response = array();

	if ( $action === '' || $id === '' ) {
		handel_error();
	}
	$new_data = [
		'name' => $name
	];
	if( $action === 'currencies'){
		$symbol = isset($data['symbol']) ? $data['symbol'] : '';
		$new_data['symbol'] = $symbol;
		$table = 'currencies';
	}elseif( $action === 'categories'){
		$table = 'categories';

	}
	if ( $table === '' ) {
		handel_error();
	}

	$validation_error = validate_data($new_data);
	if ($validation_error !== false){
		handel_error($validation_error);
	}
	$currency = new Database\Operations\UserOperations($table);
	$result = $currency->update_data($id, $new_data);
	if ($result) {
		handel_success(
			"Updated successfully!",
			"update",
			"admin.php?page=settings"

		);

	} else {
		handel_error("Error while updating ! Please try again.");
	}

}


/**
 * Handel error response
 *
 * @param string $message
 * @return void
 */
function handel_error( $message = 'Invalid request.'){
	$response = array();
	$response['status'] = 0;
	$response['message'] = $message;
	header('Content-Type: application/json');
	echo json_encode($response);
	exit();
}

/**
 * Handel success response
 *
 * @param string $message
 * @return void
 */
function handel_success( $message = 'Success' ,$action = '', $redirect_url = ''){
	$response = array_filter([
		'status' => 1,
		'message' => $message,
		'action' => $action !== '' ? $action : null,
		'redirect_url' => $redirect_url !== '' ? $redirect_url : null,
	]);

	header('Content-Type: application/json');
	echo json_encode($response);
	exit();
}

/**
 * Handle user registration
 */
function handel_registration(){
	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
	$address = isset($_POST['address']) ? $_POST['address'] : '';
	$site_url = isset($_POST['site_url']) ? $_POST['site_url'] : '';
	$role =isset( $_POST['user_type']) ? $_POST['user_type'] : '';
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$response = array();



	//Handel file upload
	$profile_pic = handel_file_upload('profile_pic');

	$data = [
		'email' => $email,
		'password' => $password,
		'confirm_password' => $confirm_password,
		'address' => $address,
		'site_url' => $site_url,
		'role' => $role,
		'username' => $username,
		'profile_pic' => $profile_pic
	];
	$validation_error = validate_data($data);
	if ($validation_error !== false){
		handel_error($validation_error);

	}

	$registration = new Database\Operations\UserOperations('users');
	$result = $registration->insert_data($data);
    if ($result) {
		if(is_array($result)){
			handel_error(($result));
		}
		handel_success(
			"User registered successfully!",
			"register",
			"login.php"
		);

    } else {
       handel_error("Failed to register user.");
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}

/**
 * Handle package addition
 */
function handel_package($action = 'add'){
	// session_start();

	$name = isset($_POST['package_name']) ? $_POST['package_name'] : '';
	$total_travelers = isset($_POST['package_total_travelers']) ? $_POST['package_total_travelers'] : '';
	$no_of_days = isset($_POST['package_days']) ? $_POST['package_days'] : '';
	$no_of_nights = isset($_POST['package_nights']) ? $_POST['package_nights'] : '';
	$price = isset($_POST['package_price']) ? $_POST['package_price'] : '';
	$discount = isset($_POST['package_discount']) ? $_POST['package_discount'] : '';
	$deadline = isset($_POST['package_deadline']) ? $_POST['package_deadline'] : '';
	$description = isset($_POST['package_description']) ? $_POST['package_description'] : '';
	$category = isset($_POST['package_categories']) ? $_POST['package_categories'] : array();
	$required_fields = ['package_name', 'package_total_travelers', 'package_days', 'package_nights', 'package_price',  'package_deadline'];

	$author_id = get_current_user_attr('user_id');

	$data = [
		'name' => $name,
		'total_travelers' => $total_travelers,
		'days' => $no_of_days,
		'nights' => $no_of_nights,
		'price' => $price,
		'deadline' => $deadline,
		'discount' => $discount,
		'category' => $category,
		'description' => $description,
		'author' => $author_id,
		'rating' => 0

	];
	switch ($action){
		case 'add':
			$thumbnail = handel_file_upload('package_thumbnail_image');
			$other_images = handel_file_upload('package_other_images');
			$data['thumbnail'] = $thumbnail;
			$data['other_images'] = $other_images;
			$response = array();
			$validation_error = validate_data($data);
			if ($validation_error !== false){
				handel_error($validation_error);
			}

			break;
		case 'update':
			$id = isset($_POST['id']) ? $_POST['id'] : 0;
			if (isset($_FILES['package_thumbnail_image']) && $_FILES['package_thumbnail_image']['error'] !== UPLOAD_ERR_NO_FILE) {
				$thumbnail = handel_file_upload('package_thumbnail_image');
			}
			if (isset($_FILES['package_other_images']) && $_FILES['package_other_images']['error'] !== UPLOAD_ERR_NO_FILE) {
				$total_files = count($_FILES['package_other_images']['name']);
				if( empty ($_FILES['package_other_images']['name'][0])){
					if( ! empty($_POST['existing_other_images'])){
						$other_images = json_decode($_POST['existing_other_images']);
					}
				}else{
					$existing_files = isset($_POST['existing_other_images']) ? json_decode($_POST['existing_other_images'], true) : [];
					$existing_files_count = count($existing_files);

					if ($total_files + $existing_files_count > 5) {
						handel_error('Maximum 5 images are allowed.');
					} else {
						$other_images = handel_file_upload('package_other_images');
						$other_images = array_merge($existing_files, $other_images);
					}
				}

			}
			if( isset($thumbnail) ){
				$data['thumbnail'] = $thumbnail;
			}
			if( isset($other_images) ){
				$data['other_images'] = $other_images;
			}
			foreach ($data as $key => $value) {
				if (is_array($value)) {
					$data[$key] = json_encode($value);
				}
			}
			$response = array();
			$validation_error = validate_data($data);
			if ($validation_error !== false){
				handel_error($validation_error);
			}

			break;
	}
	$query = new Database\Operations\UserOperations('packages');
	$result = ($action === 'add') ? $query->insert_data($data) : (($action === 'update') ? $query->update_data($id, $data) : false);
	if ($result) {
		handel_success(
			($action === 'add') ? "Package added successfully!" : "Updated successfully!",
			($action === 'add') ? "add_package" : "update",
			($action === 'add') ? "admin.php?page=packages&tab=manage-packages" : "admin.php?page=packages&tab=create-package&id=" . $id

		);

	} else {
		handel_error(($action === 'add') ? "Failed to add package." : "Error while updating ! Please try again.");
	}






}



/**
 * Handle user login
 */
function handel_login(){
	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : '';


	$data = [
		'email' => $email,
		'password' => $password
	];

	$response = array();

	$login = new Database\Operations\UserOperations('users');
	$result = $login->get_individual_data_from_email($email);

	if ($result && is_array($result) ) {
		if (!password_verify($password, $result['password'])){
			handel_error('Invalid email or password.');
		}else{
			session_start();
			$_SESSION['user_id'] = $result['id'];
			$_SESSION['username'] = $result['username'];
			$_SESSION['email'] = $result['email'];
			$_SESSION['role'] = $result['role'];
			handel_success(
				"Login successful!",
				"login",
				$redirect_url !== '' ? $redirect_url : ($result['role'] == 'admin' ? 'admin.php' : 'index.php')
			);

		}

	} else {
		handel_error('Invalid email or password.');
	}

	header('Content-Type: application/json');
	echo json_encode($response);

}

/**
 * Handle currency addition
 */

 function handel_currency(){
	$name = isset($_POST['name']) ? $_POST['name'] : '';
	$symbol = isset($_POST['symbol']) ? $_POST['symbol'] : '';
	$response = array();

	$data = [
		'name' => $name,
		'symbol' => $symbol
	];
	$validation_error = validate_data($data);
	if ($validation_error !== false){
		header('Content-Type: application/json');
		echo json_encode($validation_error);
		return;
	}

	$currency = new Database\Operations\UserOperations('currencies');
	$result = $currency->insert_data($data);
	if ($result) {
		if(is_array($result)){
			handel_error(($result));
		}
		handel_success(
			"Package added successfully!",
			"add_package",
			"admin.php?page=packages&tab=manage-packages"
		);

	} else {
		handel_error("Failed to add currency.");
	}

	// Return JSON response
	header('Content-Type: application/json');
	echo json_encode($response);
	exit();
}

/**
 * Handle category addition
 */
function handel_category(){
	$name = isset($_POST['name']) ? $_POST['name'] : '';
	$response = array();

	$data = [
		'name' => $name
	];
	$validation_error = validate_data($data);
	if ($validation_error !== false){
		handel_error($validation_error);
	}

	$category = new Database\Operations\UserOperations('categories');
	$result = $category->insert_data($data);
	if ($result) {
		handel_success(
			"Category added successfully!",
			"add_category",
			"admin.php?page=settings"
		);

	} else {
		handel_error("Failed to add category.");
	}


}

/**
 * Handle comment and rating addition
 */
function handel_comment($action = 'add'){
	$package = $_POST['package_id'] ?? '';
	$rating = $_POST['rating'] ?? 0;
	$comment = $_POST['comment'] ?? '';
	$author_id = get_current_user_attr('user_id');
	$comment_id = $_POST['comment_id'] ?? 0;
	$data = [
		'package_id' => $package,
		'rating' => $rating,
		'comment' => $comment,
		'user_id' => $author_id
	];
	$response = array();
	$validation_error = validate_data($data);
	if ($validation_error !== false){
		handel_error($validation_error);
	}
	$comment = new Database\Operations\UserOperations('comments_and_ratings');
	if($action === 'add'){
		$result = $comment->insert_data($data);
	}elseif ($action= 'update') {
		$data['id'] = $comment_id;
		$result = $comment->update_data( $comment_id,$data);
	}
	if ($result) {
		handel_success(
			"Comment added successfully!",
			"add_comment",
		);

	} else {
		handel_error("Failed to add comment.");
	}

}


/**
 * Validate user registration data
 *
 * @param array $data
 * @return array|bool
 */
function validate_data( $data ){
	//Validate user data for registration
	if(isset ($data['email'] ) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
		handel_error('Invalid email address.');
	}
	if (isset($data['password']) && strlen($data['password']) < 6){
		handel_error('Password must be at least 6 characters long.');
	}

	if (isset($data['password']) && $data['password'] != $data['confirm_password']){
		handel_error('Passwords do not match.');
	}
	if (isset($data['role']) && !in_array($data['role'], ['admin', 'user'])){
		handel_error('Invalid user role.');
	}

	if (isset($data['username']) && strlen($data['username']) < 3){
		handel_error('Username must be at least 3 characters long.');
	}

	if(isset($data['profile_pic']) && empty($data['profile_pic'])){
		handel_error('Profile picture is required field jj.');
	}
	// Validate name data
	if (isset($data['name']) && empty($data['name'])){
		handel_error('Name cannot be empty.');
	}
	if (isset($data['symbol']) && empty($data['symbol'])){
		handel_error('Symbol cannot be empty.');
	}
	//Handel package data validation
	if (isset($data['total_travelers']) && empty($data['total_travelers'])){
		handel_error('Total travelers cannot be empty.');
	}
	if (isset($data['no_of_days']) && empty($data['no_of_days'])){
		handel_error('Number of days cannot be empty.');
	}
	if (isset($data['no_of_nights']) && empty($data['no_of_nights'])){
		handel_error('Number of nights cannot be empty.');
	}
	if (isset($data['price']) && empty($data['price'])){
		handel_error('Price cannot be empty.');
	}
	if (isset($data['deadline'])){
		$deadline = strtotime($data['deadline']);
		if ($deadline === false){
			handel_error('Invalid deadline date.');
		}
		if($deadline < time()){
			handel_error('Deadline date must be in the future.');
		}
	}
	if( isset($data['discount']) && !empty($data['discount']) ){
		if( !is_numeric($data['discount']) ){
			handel_error('Invalid discount percentage.');
		}
	}
	if( isset($data['comment']) && ( empty($data['comment']) || strlen($data['comment']) < 3 ) ){
		handel_error('Comment cannot be empty or less than 3 characters.');
	}
	if( isset($data['rating']) && (  !is_numeric($data['rating']) || $data['rating'] < 0 || $data['rating'] > 5 ) ){
		handel_error('Invalid rating.');
	}


	return false;
}

/**
 * Handel file upload
 */

 function handel_file_upload($image_for){
	$pic = null;
	//Handel single file upload
	if (isset($_FILES[$image_for]) ) {
		$uploadedFiles = [];
		$allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
		if ( is_array($_FILES[$image_for]['name'])) {
			//Handel multiple file upload
			foreach($_FILES[$image_for]['name'] as $key => $fileName){
				$fileTmpPath = $_FILES[$image_for]['tmp_name'][$key];
                $fileSize = $_FILES[$image_for]['size'][$key];
                $fileType = $_FILES[$image_for]['type'][$key];
                $fileError = $_FILES[$image_for]['error'][$key];
				if ($fileError == UPLOAD_ERR_OK) {
					$fileNameComponents = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameComponents));
					if (in_array($fileExtension, $allowedfileExtensions)) {
						$newFileName = uniqid() . '.' . $fileExtension;
                        $uploadFileDir = '../assets/images/uploads/';
                        $dest_path = $uploadFileDir . $newFileName;
						if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            $uploadedFiles[] = $dest_path;
                        } else {
                            handel_error('Error moving the uploaded file: ' . $fileName);
                        }
					}else{
						handel_error('File type not allowed for .' . $fileName);
					}
				}elseif ($fileError !== UPLOAD_ERR_NO_FILE){
					handel_error('Error uploading file: ' . $fileName);
				}
			}
			if(count($uploadedFiles) > 0){
				return $uploadedFiles;
			}else{
				return ;
			}
		}
		else{
			//Handel single file upload
			if($_FILES[$image_for]['error'] !== UPLOAD_ERR_OK){
				handel_error('Error uploading file: ' . $_FILES[$image_for]['name']);
			}
			$fileTmpPath = $_FILES[$image_for]['tmp_name'];
			$fileName = $_FILES[$image_for]['name'];
			$fileSize = $_FILES[$image_for]['size'];
			$fileType = $_FILES[$image_for]['type'];
			$fileNameComponents = explode(".", $fileName);
			$fileExtension = strtolower(end($fileNameComponents));



			if (in_array($fileExtension, $allowedfileExtensions) ) {
				// Set a new filename or keep it
				$newFileName = uniqid() . '.' . $fileExtension;
				$uploadFileDir = '../assets/images/uploads/';
				$dest_path = $uploadFileDir . $newFileName;

				if (move_uploaded_file($fileTmpPath, $dest_path)) {
					return  $dest_path;
				} else {
				   handel_error('Error moving the uploaded file.');
				}
			} else {
				handel_error('File type not allowed.');
		 	}
		}
	}else{
		handel_error('File upload is required.');
	}

}





?>
