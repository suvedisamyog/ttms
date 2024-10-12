<?php
/**
 * For Debuggin purpose only
 */
// ini_set('log_errors', 'On');
// ini_set('error_log', dirname(__DIR__) . '/debug.log');

// function lg($message) {
//     error_log(date('[Y-m-d H:i:s] ') . print_r($message, true) . PHP_EOL, 3, dirname(__DIR__) . '/debug.log');
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $header ?></title>
	<?php
		get_stylesheet();
		// get_script();
	?>
</head>
<body>
<div id="wrapper" class="d-flex">
