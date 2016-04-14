<!DOCTYPE html>
<html>
<head>
<?php
	include 'functions.php';
	
	$_SESSION['previous_page'] = $_SERVER['HTTP_REFERER']; //Get previous page to redirect when logged out
	
	if(isset($_COOKIE['testToken']) && isset($_COOKIE['testUsername'])){
	    setcookie('testToken', null, -1, '/');
	    setcookie('testUsername', null, -1, '/');
	}
	header('Location: '.$_SESSION['previous_page']);
?>
</head>

<body>

</body>
</html>