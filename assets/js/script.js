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

	//Initialize multiselect
	$('.multiselect').select2({
		placeholder: 'Select an option',
		allowClear: true,
		minimumResultsForSearch: 0

	});

	//Initialize text editor
	tinymce.init({
        selector: '#package_description',
		plugins: 'anchor autolink charmap codesample emoticons  link lists media searchreplace table visualblocks wordcount',
		toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link  table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 300,
    });

	//Make active class on the current page.
	$('#sidebar ul li.active > a.dropdown-toggle').each(function() {
        var target = $(this).data('bs-target');
        $(target).collapse('show');
        $(this).attr('aria-expanded', 'true');
		$(this).removeClass('collapsed');
		$(this).siblings('ul').addClass('show');
    });

	// Handel registration form.
	$('#registration_form').on('submit', function (e) {
		e.preventDefault();
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

	//Handel package form.
	$('#packages_form').on('submit', function (e) {
		e.preventDefault();
		var data = new FormData(this);
		data.append('action', 'add_package');
		for (const [key, value] of data.entries()) {
			console.log(key + ' : ' + value);
		}
		var validation_error = validate_form_data(data);
		if (validation_error ) {
			return false;
		}

		handel_form_data(data,'POST');
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

	//Handel Popup form for currency settings.
	$('#add_currency').on('click', function (e) {
		e.preventDefault();
		Swal.fire({
			title: 'Add Currency',
			html: `
				<form id="currency_form">
					<div class="form-group">
						<label for="currency_name">Currency Name</label>
						<input type="text" id="currency_name" name="currency_name" class="form-control">
					</div>
					<div class="form-group">
						<label for="currency_symbol">Currency Symbol</label>
						<input type="text" id="currency_symbol" name="currency_symbol" class="form-control">
					</div>
				</form>
			`,
			showCancelButton: true,
			showCloseButton: true,
			confirmButtonText: 'Add',
			preConfirm: () => {
				const currencyName = Swal.getPopup().querySelector('#currency_name').value;
				const currencySymbol = Swal.getPopup().querySelector('#currency_symbol').value;
				if (!currencyName  || !currencySymbol) {
					Swal.showValidationMessage(`Please enter all fields`);
				}
				return { currencyName: currencyName,currencySymbol: currencySymbol };
			}
		}).then((result) => {
			if (result.isConfirmed) {

				const data = new FormData();
				data.append('name', result.value.currencyName);
				data.append('symbol', result.value.currencySymbol);
				data.append('action', 'add_currency');
				handel_form_data(data, 'POST');
			}
		});
	});


	//Handel Popup from for category settings
	$('#add_category').on('click', function (e) {
		e.preventDefault();
		Swal.fire({
			title: 'Add Category',
			html: `
				<form id="category_form">
					<div class="form-group
					">
						<label for="category_name">Category Name</label>
						<input type="text" id="category_name" name="category_name" class="form-control">
					</div>
				</form>
			`,
			showCancelButton: true,
			showCloseButton: true,
			confirmButtonText: 'Add',
			preConfirm: () => {
				const categoryName = Swal.getPopup().querySelector('#category_name').value;
				if (!categoryName) {
					Swal.showValidationMessage(`Please enter all fields`);
				}
				return { categoryName: categoryName };
			}
		}).then((result) => {
			if (result.isConfirmed) {
				const data = new FormData();
				data.append('name', result.value.categoryName);
				data.append('action', 'add_category');
				handel_form_data(data, 'POST');
			}
		});


});

});

//Handel edit in setting pages.
$(document).on('click', '.setting_edit_btn', function() {
	var id = $(this).data('id');
	var action_for = $(this).closest('tr').data('action');
	var name = $(this).closest('tr').find('td:nth-child(2)').text(); // get the name of the category or currency
	var title = action_for == 'currencies' ? 'Currency' : 'Category';
	if ( action_for === 'currencies') {
		var symbol = $(this).closest('tr').find('td:nth-child(3)').text();

	}
	Swal.fire({
		title: 'Edit ' + title,
		html: `
			<form id="edit_setting_form">
				<div class="form-group ">
					<label for="name">${title} Name</label>
					<input type="text" id="name" name="name" class="form-control" value="${name}">
				</div>
				${action_for === 'currencies' ? `
				<div class="form-group">
					<label for="symbol">${title} Symbol</label>
					<input type="text" id="symbol" name="symbol" class="form-control" value="${symbol}">
				</div>
				` : ''}
			</form>
		`,
		showCancelButton: true,
		showCloseButton: true,
		confirmButtonText: 'Update',
		preConfirm: () => {
			const name = Swal.getPopup().querySelector('#name').value;
			const symbol = action_for === 'currencies' ? Swal.getPopup().querySelector('#symbol').value : '';
			if (!name ||  (action_for === 'currencies' && !symbol ) ) {
				Swal.showValidationMessage(`Please enter all fields`);
			}
			return { name: name, symbol: symbol };
		}
	}).then((result) => {
		if (result.isConfirmed) {
			var data = {
				id: id,
				name: result.value.name,
				action: action_for
			};

			if (action_for === 'currencies') {
				data.symbol = result.value.symbol;
			}
			data = JSON.stringify(data);
			handel_form_data(data, 'PUT');
		}
	}
	);



});
// Handel delete operations
$(document).on('click', '.delete_btn', function() {
	alert_confirmation({
		title: 'Are you sure?',
		text: 'You won\'t be able to revert this!',
		index: $(this).closest('tr').data('index'),
		action: $(this).closest('tr').data('action'),
		id : $(this).data('id')

	});


});


// Validate form data
validate_form_data = (data) => {
	var error = false;

	$('.text-danger[id]').text('').hide();
	$('.form-control').removeClass('is-invalid');
	if( data.get('action') === 'add_package'){
		var required_fields = ['package_name', 'package_total_travelers', 'package_days', 'package_nights', 'package_price' , 'package_deadline' ,'package_thumbnail_image'] ;
	}else{
		var required_fields = ['username', 'email', 'password', 'confirm_password', 'profile_pic'];

	}
	for (const [key, value] of data.entries()) {
		if (required_fields.includes(key) && String(value).trim() === '') {
          error = handel_client_side_validation_error(key , 'This field is required.');

        }
		switch (key) {
			case 'email':
				if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {

					error = handel_client_side_validation_error(key , 'Please enter a valid email address.');
				}
				break;
			case 'password':
				if (value.length < 6) {
					error = handel_client_side_validation_error(key , 'Password must be at least 6 characters long.');
				}
				break;
			case 'confirm_password':
				if (value !== data.get('password')) {
					error = handel_client_side_validation_error(key , 'Password does not match.');
				}
				break;
			case 'username':
			case 'package_name':
				if (value.length < 3) {
					error = handel_client_side_validation_error(key , 'Name must be at least 3 characters long.');
				}
				break;
			case 'package_total_travelers':
			case 'package_days':
			case 'package_price':
				if( value < 1 || isNaN(value)){
					error = handel_client_side_validation_error(key , 'Please enter a valid number.');
				}
				break;
			case 'package_nights':
				if( value < 0 || isNaN(value)){
					error = handel_client_side_validation_error(key , 'Please enter a valid number.');
				}
				break;
			case 'package_discount':
				if (isNaN(value) || value < 0 || value > 100) {
					error = handel_client_side_validation_error(key , 'Please enter a valid discount percentage.');
				}
				break;

			case 'package_deadline':
				var date = new Date(value);
				if (date <= new Date()) {
					error = handel_client_side_validation_error(key , 'Please enter a valid date.');
				}
				break;

		};
	}
	return error;
}


//handel client side validation error
function handel_client_side_validation_error(key , message) {
	$('input[name="' + key + '"]').addClass('is-invalid');
	$("#" + key + "-error").text(message).show();
	return true;
}


handel_form_data = (data,method) => {
	// var contentType = (method === 'PUT') ? 'application/json' : false;
	$.ajax({
		url: '../../src/Ajax.php',
		type: method,
		data: data,
		cache: false,
		contentType: false,
		processData: false,
		complete: function (response) {
			console.log(response.responseText);
			// var res = typeof response.responseText === 'string' ? JSON.parse(response.responseText) : response.responseText;
			var res =JSON.parse(response.responseText);
			console.log(res);
			if( res.status == 1){
				switch (res.action) {
					case 'register':
						$('#registration_form').trigger('reset');
						$('#registration_form').find('.form-control').removeClass('is-invalid');
						alert_success(res);
						break;
					case 'add_package':
						$('#packages_form').trigger('reset');
						$('#packages_form').find('.form-control').removeClass('is-invalid');
						alert_success(res);
					case 'login':
						alert_success(res);
						break;
					case 'add_currency':
					case 'add_category':
					case 'update':
						alert_success(res);
						break;
					case 'delete':
						$('tr[data-index="' + res.index + '"]').remove();
                        alert_success(res);
						break;
					default:
						break;
				}
			}else{
				alert_error(res.message);
			}
		},
		error: function (xhr, status, error) {
			// console.log(xhr);
			// console.log(status);
			// console.log(error);
			alert_error(error.message);
		}
	});
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
	if (res.redirect_url) {
        setTimeout(() => {
            window.location.href = res.redirect_url;
        }, 3000);
    }
}

alert_error = (message) => {
	Swal.fire({
		title: message,
		icon: 'error',
		confirmButtonText: 'OK'

	});
}

alert_confirmation = (data) => {
	Swal.fire({
		title: data.title,
		text: data.text,
		icon: 'warning',
		showCancelButton: true,
		showCloseButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No'
	}).then((result) => {
		if (result.isConfirmed) {
			var newData = {
				id : data.id,
				action : data.action,
				index : data.index
			}
			handel_form_data($.param(newData),'DELETE');

		}
	});

}
