$(document).ready(function () {
	// Toggle sidebar
	$('#sidebarCollapse').on('click', function () {
		$('#sidebar').toggleClass('active');
	});

	// Handel user registration form ui.
	$('#user-tab').on('click', function () {
		$(this).toggleClass('active');
		$('#admin-tab').removeClass('active');
		$('input[name="profile_pic"]').attr('accept', '');
		$('label[for="profile_pic"]').html('Profile Picture<spam class="text-danger" >*</spam>');
		$('label[for="email"]').html('Email<spam class="text-danger" >*</spam>');
		$('input[name="address"]').closest('.col-md-6.mb-4').addClass('d-none');
		$('input[name="site_url"]').closest('.col-md-6.mb-4').addClass('d-none');


	});
	// Handel admin registration form ui.
	$('#admin-tab').on('click', function () {
		$(this).toggleClass('active');
		$('#user-tab').removeClass('active');
		$('input[name="profile_pic"]').attr('accept', 'image/*');
		$('label[for="profile_pic"]').html('Logo<spam class="text-danger">*</spam>');
		$('label[for="username"]').html('Company Name<spam class="text-danger">*</spam>');
		$('input[name="address"]').closest('.col-md-6.mb-4').removeClass('d-none');
		$('input[name="site_url"]').closest('.col-md-6.mb-4').removeClass('d-none');
	});

	// Handel registration form.
	$('#registration_form').on('submit', function (e) {
		e.preventDefault();
		console.log('Form submitted');
		var data = new FormData(this);
		var userType = $('#admin-tab').hasClass('active') ? 'admin' : 'user';
		data.append('user_type', userType);
		data.append('action', 'register');

		var validation_error = validate_form_data(data);
		if (validation_error ) {
			return false;
		}
		handel_form_data(data,'POST');

	});

});

// Handel login form.

$('#login_form').on('submit', function (e) {
	e.preventDefault();
	var data = new FormData(this);
	data.append('action', 'login');
	var validation_error = validate_form_data(data);
	if (validation_error ) {
		return false;
	}
	handel_form_data(data,'POST');
});

// Validate form data
validate_form_data = (data) => {
	var error = false;

	$('.text-danger').text('').hide();
	$('.form-control').removeClass('is-invalid');
	var required_fields = ['username', 'email', 'password', 'confirm_password', 'profile_pic'];
	for (const [key, value] of data.entries()) {
		if (required_fields.includes(key) && String(value).trim() === '') {
            $('input[name="' + key + '"]').addClass('is-invalid');
            $("#" + key + "-error").text('This field is required').show();
            error = true;
        }
		switch (key) {
			case 'email':
				if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
					$('input[name="' + key + '"]').addClass('is-invalid');
					$("#" + key + "-error").text('Please enter a valid email address.').show();
					error = true;
				}
				break;
			case 'password':
				if (value.length < 6) {
					$('input[name="' + key + '"]').addClass('is-invalid');
					$("#" + key + "-error").text('Password must be at least 6 characters long.').show();
					error = true;
				}
				break;
			case 'confirm_password':
				if (value !== data.get('password')) {
					$('input[name="' + key + '"]').addClass('is-invalid');
					$("#" + key + "-error").text('Password does not match.').show();
					error = true;
				}
				break;
			case 'username':
				if (value.length < 3) {
					$('input[name="' + key + '"]').addClass('is-invalid');
					$("#" + key + "-error").text('Username must be at least 3 characters long.').show();
					error = true;
				}
				break;


		};
	}
	return error;
}


handel_form_data = (data,method) => {
	$.ajax({
		url: '../../src/Ajax.php',
		type: method,
		data: data,
		cache: false,
		contentType: false,
		processData: false,
		complete: function (response) {
			var res = JSON.parse(response.responseText);

			if( res.status == 1){
				switch (res.action) {
					case 'register':
						registration_success(res);
						break;
					case 'login':
						window.location.href = res.redirect_url;
						break;
					default:
						break;
				}
			}else{
				alert_error(res.message);
			}
		},
		error: function (error) {
			alert_error(error.message);
		}
	});
}

registration_success = (res ) => {
	$('#registration_form').trigger('reset');
	$('#registration_form').find('.form-control').removeClass('is-invalid');
	alert_success(res);

}

alert_success = (res  ) => {
	Swal.fire({
		title: res.message,
		icon: 'success',
		confirmButtonText: 'OK'
	}).then((result) => {
		if (result.isConfirmed) {
			if(res.redirect_url){
			window.location.href = res.redirect_url;
			}
	}
	});
}

alert_error = (message) => {
	Swal.fire({
		title: message,
		icon: 'error',
		confirmButtonText: 'OK'

	});
}
