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
	}
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

	// Validate input


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
                $response['status'] = 0;
                $response['message'] = "Error moving the uploaded file.";
                header('Content-Type: application/json');
                echo json_encode($response);
                return; // Stop execution if upload fails
            }
        } else {
            $response['status'] = 0;
            $response['message'] = "Invalid file type or size.";
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }
    }else{
		$response['status'] = 0;
		$response['message'] = "Profile picture is required.";
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
		header('Content-Type: application/json');
		echo json_encode($validation_error);
		return;

	}

	$registration = new Database\Operations\UserOperations();
	$result = $registration->insert_data($data);
    if ($result) {
		if(is_array($result)){
			header('Content-Type: application/json');
			echo json_encode($result);
			return;
		}
        $response['status'] = 1;
        $response['message'] = "User registered successfully!";
		$response['action'] = 'register';
		$response['redirect_url'] = 'login.php';
    } else {
        $response['status'] = 0;
        $response['message'] = "Failed to register user.";
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

	$login = new Database\Operations\UserOperations();
	$result = $login->get_individual_data_from_email($email);
	if ($result && is_array($result) ) {
		if (!password_verify($password, $result['password'])){
			$response['status'] = 0;
			$response['message'] = "Invalid email or password.";
			header('Content-Type: application/json');
			echo json_encode($response);
			return;
		}else{
			$_SESSION['user_id'] = $result['id'];
			$_SESSION['username'] = $result['username'];
			$_SESSION['email'] = $result['email'];
			$_SESSION['role'] = $result['role'];
			$response['status'] = 1;
			$response['message'] = "Login successful!";
			$response['action'] = 'login';
			$response['redirect_url'] = 'dashboard.php';
			$response['redirect_url'] = $result['role'] == 'admin' ? ADMIN_URL : 'index.php';

		}

	} else {
		$response['status'] = 0;
		$response['message'] = "Invalid email or password.";
	}

	header('Content-Type: application/json');
	echo json_encode($response);

}

/**
 * Validate user registration data
 *
 * @param array $data
 * @return array|bool
 */
function validate_data( $data ){
	if(isset ($data['email'] ) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
		$response['status'] = 0;
		$response['message'] = "Invalid email address.";
		return $response;
	}
	if (isset($data['password']) && strlen($data['password']) < 6){
		$response['status'] = 0;
		$response['message'] = "Password must be at least 6 characters long.";
		return $response;
	}

	if (isset($data['password']) && $data['password'] != $data['confirm_password']){
		$response['status'] = 0;
		$response['message'] = "Passwords do not match.";
		return $response;
	}
	if (isset($data['role']) && !in_array($data['role'], ['admin', 'user'])){
		$response['status'] = 0;
		$response['message'] = "Invalid user type.";
		return $response;
	}

	if (isset($data['username']) && strlen($data['username']) < 3){
		$response['status'] = 0;
		$response['message'] = "Username must be at least 3 characters long.";
		return $response;
	}

	if(isset($data['profile_pic']) && empty($data['profile_pic'])){
		$response['status'] = 0;
		$response['message'] = "Profile picture is required.";
		return $response;
	}
	return false;
}
?>
