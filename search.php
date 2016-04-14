<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>InstaFeed.me - Search Instagram profiles</title>
 
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link href="css/stylesheet.css?version=1" rel="stylesheet">
 
</head>
<body>
<?php
include 'functions.php';
?> 

<div class="wrapper">
<div class="container">

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
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#search" aria-expanded="false" aria-controls="search">
            <span class="sr-only">Toggle navigation</span>
            <span class="glyphicon glyphicon-search"></span>
          </button>
          <a class="navbar-brand" href="index.php">InstaFeed.me</a>
        </div>

        <div class="photo-feed-wrapper">
        <div id="navbar" class="collapse navbar-collapse">
          <div class='hidden-xs'>
            <ul class="nav navbar-nav">
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
        </div>

          <ul class="nav navbar-nav navbar-right">
            <?php isLoggedIn(); ?>
          </ul>
        </div><!--/.nav-collapse -->



        <div class='visible-xs'>
          <div id="search" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li>
              <div class="search-box" class="collapse navbar-collapse">
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
            </li>
          </ul>
        </div><!--/.nav-collapse -->
        </div>

        
        </div><!--/.photo-feed-wrapper-collapse -->
      </div><!--/.container-collapse -->
    </nav>

    <!-- Main component for a primary marketing message or call to action -->
    <div class="container">

		    <div class="row marketing">

          <!-- ************************************
          ********* Table tabs for mobile *********
          ***************************************-->
          <div class='visible-xs'>
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#users" aria-controls="users" role="tab" data-toggle="tab">@Users</a></li>
              <li role="presentation"><a href="#tags" aria-controls="tags" role="tab" data-toggle="tab">#Tags</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

              <!-- Users table -->
              <div role="tabpanel" class="tab-pane active" id="users">
                <?php
                if(isset($_GET['search_result'])){
                  $name = str_replace(' ', '+', $_GET['search_result']);
                 
                  $url="https://api.instagram.com/v1/users/search?q={$name}&access_token=".accessToken;
                  $get = connectToInstagram($url);
                  $obj = json_decode($get, true, 512);

                  $isValid = $obj['meta']['code'];
                  $length = count($obj['data']);

                  if($get){
                  ?>
                    <div class='search-result'>
                      <table class='table'>
                        <thead class='hidden-xs'><tr>
                          <th>@Users</th>
                        </tr></thead>
                      <tbody>
                  <?php
                    /*Show user profile data (pics, comments etc.)*/
                    for($i=0; $i<$length; $i++){
                      $profile_username = $obj['data'][$i]['username'];
                      $profile_picture = $obj['data'][$i]['profile_picture'];
                      $profile_full_name = $obj['data'][$i]['full_name'];

                      echo "<tr><th>";
                        echo "<div class='profile-picture'>";
                          echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='42' width='42'></a>";
                        echo "</div>";    
                        echo "<div class='search-user'>";
                            echo "<a href='/instafeed/profile.php?username={$profile_username}'><p class='taskDescription'>@".$profile_username."</a>";
                          if($profile_full_name){
                            echo "<i> (".$profile_full_name.")</i></p>";
                          }else{
                            echo "</p></a>";
                          }
                        echo "</div>";
                      echo "</th></tr>";
                    }
                  ?>
                    </tbody>
                    </table>
                    </div> <!--/.search-result collapse -->
                  <?php
                  }else if($get === FALSE){
                    echo "Error 404. Please try searching something else.";
                  }
                }
                ?>
              </div>

              <!-- Tags table -->
              <div role="tabpanel" class="tab-pane" id="tags">
                <?php
                if(isset($_GET['search_result'])){
                  $tag = str_replace(' ', '', $_GET['search_result']);
                 
                  $url="https://api.instagram.com/v1/tags/search?q={$tag}&access_token=".accessToken;
                  $get = connectToInstagram($url);
                  $obj = json_decode($get, true, 512);

                  $isValid = $obj['meta']['code'];
                  $length = count($obj['data']); 

                  if($get){
                  ?>
                    <div class='search-result'>
                      <table class='table'>
                        <thead class='hidden-xs'><tr>
                          <th>#Tags</th>
                        </tr></thead>
                      <tbody>
                  <?php
                    /*Show user profile data (pics, comments etc.)*/
                    for($i=0; $i<$length; $i++){        
                      $tag_count = $obj['data'][$i]['media_count'];
                      $tag_name = $obj['data'][$i]['name'];

                      echo "<tr><th>";
                        echo "<div class='profile-top'>";
                            echo "<a href='/instafeed/tag.php?tag={$tag_name}'><p class='taskDescription'>#".$tag_name."</a><b> ".$tag_count." photos</b></p>";
                        echo "</div>";
                      echo "</th></tr>";
                      }
                  ?>
                      </tbody>
                      </table>
                    </div>
                  <?php
                  }else if($get === FALSE){
                    echo "Error 404. Please try searching something else.";
                  }
                }
                ?>
              </div>
            </div>
          </div>

        <!-- **************************************
          **** Tables for tablets and desktops ****
          ***************************************-->

        <div class="hidden-xs">
          <div class="col-xs-6">
  	        <?php
                if(isset($_GET['search_result'])){
                  $name = str_replace(' ', '+', $_GET['search_result']);
                 
                  $url="https://api.instagram.com/v1/users/search?q={$name}&access_token=".accessToken;
                  $get = connectToInstagram($url);
                  $obj = json_decode($get, true, 512);

                  $isValid = $obj['meta']['code'];
                  $length = count($obj['data']);

                  if($get){
                  ?>
                    <div class='search-result'>
                      <table class='table'>
                        <thead class='hidden-xs'><tr>
                          <th>@Users</th>
                        </tr></thead>
                      <tbody>
                  <?php
                    /*Show user profile data (pics, comments etc.)*/
                    for($i=0; $i<$length; $i++){
                      $profile_username = $obj['data'][$i]['username'];
                      $profile_picture = $obj['data'][$i]['profile_picture'];
                      $profile_full_name = $obj['data'][$i]['full_name'];

                      echo "<tr><th>";
                        echo "<div class='profile-picture'>";
                          echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='42' width='42'></a>";
                        echo "</div>";    
                        echo "<div class='search-user'>";
                            echo "<a href='/instafeed/profile.php?username={$profile_username}'><p class='taskDescription'>@".$profile_username."</a>";
                          if($profile_full_name){
                            echo "<i> (".$profile_full_name.")</i></p>";
                          }else{
                            echo "</p></a>";
                          }
                        echo "</div>";
                      echo "</th></tr>";
                    }
                  ?>
                    </tbody>
                    </table>
                    </div> <!--/.search-result collapse -->
                  <?php
                  }else if($get === FALSE){
                    echo "Error 404. Please try searching something else.";
                  }
                }
            ?>
          </div>

          <div class="col-xs-6">
          	<?php
                if(isset($_GET['search_result'])){
                  $tag = str_replace(' ', '', $_GET['search_result']);
                 
                  $url="https://api.instagram.com/v1/tags/search?q={$tag}&access_token=".accessToken;
                  $get = connectToInstagram($url);
                  $obj = json_decode($get, true, 512);

                  $isValid = $obj['meta']['code'];
                  $length = count($obj['data']); 

                  if($get){
                  ?>
                    <div class='search-result'>
                      <table class='table'>
                        <thead class='hidden-xs'><tr>
                          <th>#Tags</th>
                        </tr></thead>
                      <tbody>
                  <?php
                    /*Show user profile data (pics, comments etc.)*/
                    for($i=0; $i<$length; $i++){        
                      $tag_count = $obj['data'][$i]['media_count'];
                      $tag_name = $obj['data'][$i]['name'];

                      echo "<tr><th>";
                        echo "<div class='profile-top'>";
                            echo "<a href='/instafeed/tag.php?tag={$tag_name}'><p class='taskDescription'>#".$tag_name."</a><b> ".$tag_count." photos</b></p>";
                        echo "</div>";
                      echo "</th></tr>";
                      }
                  ?>
                      </tbody>
                      </table>
                    </div>
                  <?php
                  }else if($get === FALSE){
                    echo "Error 404. Please try searching something else.";
                  }
                }
            ?>
          </div>
        </div>
      </div>
      </div>

    </div> <!-- /container -->
</div> <!-- /wrapper -->
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
 
</body>
</html>