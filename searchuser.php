<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>InstaFeed.me - Search Instagram profiles</title>
 
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<body>

<?php
include 'functions.php';

		echo "<form method='post' action='searchuser.php'>";
		echo "<br>";
		echo "Search user<br>";
		echo "<input type='text' name='search_user' placeholder='Enter username...' value='pewdiepie'><br>";
		echo "<input type='text' name='search_result' placeholder='Enter results count...' value='2'>";
		echo "Search results to show (number)<br>";
		echo "<input type='submit' name='submit' value='Search'>";
		echo "</form>";

	if(isset($_POST['submit'])){
		$name = getSearchUser();
		$number = getSearchResult();
		$obj = showSearchResult($name,$number);

		//$nobj = searchUser('jaglid',50);

		/*echo "<pre>";
		print_r($nobj);
		echo "</pre>";*/
	}

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

</body>
</html>