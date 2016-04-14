<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>InstaFeed.me - Search Instagram profiles</title>
 
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <!-- Custom styles for this template -->
    <link href="css/stylesheet.css?version=1" rel="stylesheet">
 
</head>
<body>
<?php
include 'functions.php';
?>

	<div class="wrapper">
      <!-- Static navbar -->
      <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">InstaFeed.me</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">
              <span></span> Home</a></li>
            <li><a href="search.php">
              <span></span> Search</a></li>
            <li class="active"><a href="profile.php">
              <span></span> Profile</a></li>
            <li><a href="about.php">
              <span></span> About & Privacy</a></li>
          </ul>
            <ul class="nav navbar-nav navbar-right">
	            <div class="search-box"> 
					<form method='get' action='search.php'>
	              		<div class="input-group stylish-input-group">
	                    	<input type="text" class="form-control" name='search_result' placeholder="User or Tag" value='pewdiepie'>
	                    	<span class="input-group-addon">
	                        	<button type="submit" name='submit'>
	                            	<span class="glyphicon glyphicon-search"></span>
	                       		</button>  
	                    	</span>
	                	</div>
					</form>
	            </div>
	        </ul>
        </div><!--/.nav-collapse -->
        	
      </div>
    </nav>
			

	<div class="container">		
      <!-- Main component for a primary marketing message or call to action -->

		<a href="login.php">Login with Instagram</a>

    <?php

    while(isset($nextURL)){

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $nextURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $instagramString = curl_exec($ch);
      curl_close($ch);

      $instagram = json_decode($instagramString, true); 

      $data = $instagram['data'];
      $pagination = $instagram['pagination'];
      $nextURL = $pagination['next_url'];

      for($i=0;$i<count($data);$i++){
        $imageData = $data[$i];
        $id = $imageData['id'];
        $images = $imageData['images'];
        $standardImage = $images['standard_resolution'];
        $lowResolution = $images['low_resolution'];
        $thumbnail = $images['thumbnail'];
        echo "<div class='instagramData'><a onClick='javascript:modalData('" . $id . "');'><img class='photo' src='" . $lowResolution['url'] . "' alt='instagram' ></a></div>";

      }
    }

    ?>

    </div> <!-- /container -->

    <div class="top-button">
      <h3><a href="#">^</a></h3>
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