<!DOCTYPE html>
<html lang="en">
<head>

    <?php
    include 'functions.php';
    $accessToken = '309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e';
    $clientID = 'd64dad5dbfc34db1b77e33eff6afd7d8';
    //$clientSecret = 'da7836e88c7347cfaad25fffdb9b92bf';
    $redirectURI = 'http://localhost/instafeed/index.php';

      if(isset($_GET['media_id'])){
        $media_id = $_GET['media_id'];
        $url = "https://api.instagram.com/v1/media/{$media_id}/likes";
        $ch = curl_init();

        curl_setopt_array($ch, array (
          CURLOPT_URL       =>  $url,
          CURLOPT_POST  =>  true,
          CURLOPT_POSTFIELDS  =>  http_build_query($accessToken), //farligt enl. säkerhet?
          CURLOPT_RETURNTRANSFER  =>  true
        ));

        curl_exec($ch);

        curl_close($ch);

        //return $result;
        /*
      $media_id = $_GET['media_id'];

        //return $result;

        $url = "https://api.instagram.com/v1/media/".$media_id."/likes";
        $fields = array(
            'access_token'       =>      '309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        */

        header("Location: ".$redirectURI);
        die();
      }

    ?>

</head>
<body>
 
</body>
</html>