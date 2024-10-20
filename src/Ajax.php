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

			break;
	}
}elseif( ($_SERVER['REQUEST_METHOD'] == 'DELETE') ) {
	parse_str(file_get_contents('php://input'), $_DELETE);
    $table = isset($_DELETE['action']) ? $_DELETE['action'] : '';
    $id = isset($_DELETE['id']) ? $_DELETE['id'] : '';
	$index = isset($_DELETE['index']) ? $_DELETE['index'] : '';

		$response = array();
		//return error if id or action is empty
		if( empty($id) || empty($table) || $index == ''){
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
	$data = [
		'name' => $name
	];
	if( $action === 'currencies'){
		$symbol = isset($data['symbol']) ? $data['symbol'] : '';
		$data['symbol'] = $symbol;
		$table = 'currencies';
	}elseif( $action === 'categories'){
		$table = 'categories';
	}
	if ( $table === '' ) {
		handel_error();
	}

	$validation_error = validate_data($data);
	if ($validation_error !== false){
		handel_error($validation_error);
	}
	$currency = new Database\Operations\UserOperations($table);
	$result = $currency->update_data($id, $data);
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
	$profile_pic = null;
	if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileSize = $_FILES['profile_pic']['size'];
        $fileType = $_FILES['profile_pic']['type'];
        $fileNameComponents = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameComponents));

		$allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];

        if (in_array($fileExtension, $allowedfileExtensions) ) {
            // Set a new filename or keep it
            $newFileName = uniqid() . '.' . $fileExtension;
            $uploadFileDir = '../assets/images/uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $profile_pic = $dest_path;
            } else {
               handel_error('Error moving the uploaded file.');
            }
        } else {
            handel_error('File type not allowed.');
        }
    }else{
		handel_error('Profile picture is required.');
	}

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
 * Handle user login
 */
function handel_login(){
	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
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
				$result['role'] == 'admin' ? 'admin.php' : 'index.php'
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
		handel_success(
			"Currency added successfully!",
			"add_currency",
			"admin.php?page=settings"
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
	return false;
}
?>
