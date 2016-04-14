<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>InstaFeed.me - Search Instagram profiles</title>
 
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
 
</head>
<body>

<div class="page-header">
    <h1>InstaFeed.me - Search Instagram profiles</h1>

	    <?php
		// instagram user id lookup here https://codeofaninja.com/tools/find-instagram-user-id
		$instagram_uid="13506898";
		 
		// instruction to get your access token here https://www.codeofaninja.com/2015/05/get-instagram-access-token.html
		$access_token="309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e";
		$photo_count=12;
		 
		$json_link="https://api.instagram.com/v1/users/{$instagram_uid}/media/recent/?";
		$json_link.="access_token={$access_token}&count={$photo_count}";

		$json = file_get_contents($json_link);
		$obj = json_decode($json, true, 512);

		//*
		echo "<pre>";
		print_r($obj);
		echo "</pre>";
		//*/


		foreach($obj['pagination'] as $item) {
		    print $item['next_url'];
		    
		}

		?>
</div>
 
<div class="container">
<div class="col-lg-12">
 
<!-- Instagram feed will be here -->
 
</div>
</div>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
 
</body>
</html>