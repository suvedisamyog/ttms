<?php
use App\TTMS\Database\Operations\UserOperations;

include_once TEMPLATE_PATH . 'users/navigation.php';
$is_logged = is_logged_in();

if(!$is_logged){
	header('Location: login.php?redirect=' . SITE_URL . '?page=profile');
	return;
}

$users = new UserOperations('users');
$user = $users->get_individual_data_from_id(get_current_user_attr('user_id'));

?>
<div class="container mt-5">
    <!-- Profile Picture Section -->
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            <img id="profilePic" src="<?php echo htmlspecialchars($user['profile_pic'] ?? ''); ?>" alt="Profile Picture" class="img-fluid " style="max-width: 150px;">
            <button class="btn btn-primary mt-3" id="changePictureBtn">Change Picture</button>
			<button class="btn btn-warning mt-3 d-none" id="UpdateProfilePic">Update Profile</button>
			  <!-- Hidden File Input -->
			  <input type="file" id="profilePicInput" class="d-none" accept="image/*">
        </div>
    </div>

    <!-- Profile Information Section -->
    <div class="row mt-4">
        <div class="col-md-6 offset-md-3">
            <div class="mb-3">
                <label for="username" class="form-label"><strong>Username:</strong></label>
                <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><strong>Email:</strong></label>
                <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>
            </div>

            <!-- Change Password Button -->
            <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>
            <button id="btn_logout" class="btn btn-danger w-100 mt-3">Logout <i class="bi bi-box-arrow-right"></i> </button>

        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="change_password">
                        <div class="mb-3">
                            <label for="oldPassword" class="form-label">Old Password</label>
                            <input type="password" name="old_password" id="oldPassword" class="form-control" name="old_password" required>
							<span class="text-danger m-2" id="old_password-error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" id="newPassword" name="new_password" class="form-control" name="new_password" required>
							<span class="text-danger m-2" id="new_password-error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                            <input type="password" id="confirmNewPassword" class="form-control" name="new_confirm_password" name="confirm_new_password" required>
							<span class="text-danger m-2" id="new_confirm_password-error"></span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
