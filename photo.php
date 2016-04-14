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

    <!-- Video player -->
    <link href="http://vjs.zencdn.net/5.0.0/video-js.css" rel="stylesheet">
    <!-- If you'd like to support IE8 -->
    <script src="http://vjs.zencdn.net/ie8/1.1.0/videojs-ie8.min.js"></script>
 
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
      

  <div class="container">   
      <!-- Main component for a primary marketing message or call to action -->

    <?php
      if(isset($_GET['photo'])){
        $id = $_GET['photo'];

        $url="https://api.instagram.com/v1/media/{$id}?access_token=".accessToken;
        $get = connectToInstagram($url);
        $obj = json_decode($get, true, 512);

        $isValid = $obj['meta']['code'];
        if(isset($obj['meta']['error_type'])){
          $error_type = $obj['meta']['error_type'];
        }
        $length = count($obj['data']);

        if($isValid !==400){
          /*Show user profile data (pics, comments etc.)*/
          $type = $obj['data']['type'];
          $img = $obj['data']['images']['standard_resolution']['url'];
          $comments_count = $obj['data']['comments']['count'];
          if(isset($obj['data']['videos']['standard_resolution']['url'])){
            $video = $obj['data']['videos']['standard_resolution']['url'];
          }
          $created_time = $obj['data']['created_time'];
          $likes_count = $obj['data']['likes']['count'];
          $filter = $obj['data']['filter'];
          $profile_username = $obj['data']['user']['username'];
          $profile_picture = $obj['data']['user']['profile_picture'];
          $profile_full_name = $obj['data']['user']['full_name'];
          $text = $obj['data']['caption']['text'];
          $text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
          $text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
            
          $time = getTime($created_time);
          ?>

          <div class='photo-feed-wrapper'>
            
            <div class='row photo-wrapper'>

              <!-- Photo box -->
              <div class='media-box'>
                <!-- Profile-box -->
                <div class='profile-box'>
                  
                  <div class='title-box-big'>
                     <div class='profile-picture'>
                        <?php echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='40' width='40'></a>"; ?>
                      </div>   
                        <div class='profile-top'>
                    <?php
                    echo "<span class='time'>{$time}</span>";
                    echo "<a href='/instafeed/profile.php?username={$profile_username}'><span class='name'>@".$profile_username."</span></a>";             
                    echo "</div>";

                    echo "<div class='profile-bottom'>";
                    echo "<span class='time'>".$filter."</span>";

                    if($profile_full_name){
                      echo "<span class='name'>".$profile_full_name."</span>";
                    }
                    ?>
                    </div>
                    </div> <!-- title-box end -->

                    <!-- Users liked photo -->
                    <?php
                    $j = 0;
                    echo "<div class='media-contents'>";

                      if($type == 'image'){ 
                      echo "<img class='center' src='".$img."' height='510' width='510'>";
                    }else{
                      echo "<video id='my-video' class='video-js' controls preload='auto' width='510' height='510' poster='MY_VIDEO_POSTER.jpg' data-setup='{}' style='margin: 0px auto;'>";
                          echo "<source src='".$video."' type='video/mp4'>";
                          echo "<source src='".$video."' type='video/webm'>"; //not .webm, change this?
                          echo "<p class='vjs-no-js'>";
                            echo "To view this video please enable JavaScript, and consider upgrading to a web browser that";
                            echo "<a href='http://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a>";
                        echo "</p>";
                      echo "</video>";
                    }

                    echo "<div class='info-box'>";
                    echo "<span>".$likes_count." likes</span>";
                  echo "</div>"; /*<-- info-box end -->*/

              /*Comment box*/
              echo "<div class='comments-box'>";

              echo "<div class='title-box'>";

                echo "<div class='profile-picture'>";
                  echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='32' width='32'></a>";
                echo "</div>";    
                echo "<div class='profile-top'>";
                      echo "<h4>".$text."</h4>";
                echo "</div>";

                  echo "</div>";

                  echo "<div class='comments-big'>";
                  echo "<p>".$comments_count." comments</p>";
                    echo "<ul class='commentList'>";
                        


                    /*Show comments*/
                $j = 0;
                $commentsLength = count($obj['data']['comments']['data']);
                //echo "<ul>";
                while($j<$commentsLength){
                  $created_time = $obj['data']['comments']['data'][$j]['created_time'];
                  $text = $obj['data']['comments']['data'][$j]['text'];
                  $text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
                  $text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
                  $comment_username = $obj['data']['comments']['data'][$j]['from']['username'];
                  $comment_picture = $obj['data']['comments']['data'][$j]['from']['profile_picture'];
                  $comment_id = $obj['data']['comments']['data'][$j]['from']['id'];

                  $time = getTime($created_time);
                    echo "<li>";
                        echo "<div class='commenterImage'>";
                          echo "<a href='/instafeed/profile.php?username={$comment_username}'><img src='".$comment_picture."' alt='".$comment_username."' height='32' width='32'></a>";
                        echo "</div>";

                        echo "<div class='commentText'>";
                          echo "<p class=''><a href='/instafeed/profile.php?username={$comment_username}'>@".$comment_username."</a> ".$text."</p> <span class='date sub-text'>".$time."</span>";
                        echo "</div>";
                    echo "</li>";
                  $j++;
                }
                    echo "</ul>";
                echo "</div>";

                    echo "<form class='form-inline'' role='form'>";
                      echo "<div class='post-comment'>";
                            echo "<div class='form-group'>";
                                echo "<input class='form-control' type='text' placeholder='Add a comment...' />";
                            echo "</div>";
                            echo "<div class='form-group'>";
                                echo "<button class='btn btn-default'>Add</button>";
                            echo "</div>";
                      echo "</div>";
                    echo "</form>";

                echo "</div>"; /*<-- comments-box end -->*/

                  echo "</div>"; /*<-- media-contents end -->*/

                  echo "</div>"; /*<-- media-content-big end -->*/


              

            echo "</div>"; /*<-- row photo-wrapper end -->*/
          echo "</div>";

          echo "</div>"; /*<-- photo-feed-wrapper end -->*/

        }else if($error_type == 'APINotFoundError'){
          echo "<div class='photo-feed-wrapper'>";
            
            echo "<div class='row photo-wrapper'>";

              /*Photo box*/
              echo "<div class='media-box'>";

                /*Profile-box*/
                echo "<div class='profile-box'>";
                  
                  echo "<div class='center'>";
                    echo "<h2>Private profile, direct to profile by using _309165108 in the photo id</h2>";
                    echo "</div>"; /*<-- title-box end -->*/

                echo "</div>"; /*<-- profile-box end -->*/

              echo "</div>"; /*<-- media-box end -->*/

            echo "</div>"; /*<-- row photo-wrapper end -->*/

          echo "</div>"; /*<-- photo-feed-wrapper end -->*/
        }else{
          echo "<div class='photo-feed-wrapper'>";
            
            echo "<div class='row photo-wrapper'>";

              /*Photo box*/
              echo "<div class='media-box'>";

                /*Profile-box*/
                echo "<div class='profile-box'>";
                  
                  echo "<div class='center'>";
                    echo "<h2>Media could not be found</h2>";
                    echo "</div>"; /*<-- title-box end -->*/

                echo "</div>"; /*<-- profile-box end -->*/

              echo "</div>"; /*<-- media-box end -->*/

            echo "</div>"; /*<-- row photo-wrapper end -->*/

          echo "</div>"; /*<-- photo-feed-wrapper end -->*/
        }

        /*echo "<pre>";
        print_r($obj);
        echo "</pre>";*/
        }
    ?>

    </div> <!-- /container -->

    <!-- <div class="top-button">
      <h3><a href="#">^</a></h3>
    </div> -->

    </div>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<!-- video player -->
<script src="http://vjs.zencdn.net/5.0.0/video.js"></script>
 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
 
</body>
</html>