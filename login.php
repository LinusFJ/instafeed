<!DOCTYPE html>
<html lang="en">
<head>

    <?php
    $clientID = 'd64dad5dbfc34db1b77e33eff6afd7d8';
    //$clientSecret = 'da7836e88c7347cfaad25fffdb9b92bf';
    $redirectURI = 'http://localhost/instafeed/index.php';

    header("Location: https://api.instagram.com/oauth/authorize/?client_id=".$clientID."&redirect_uri=".$redirectURI."&response_type=code");
    die();
    ?>

</head>
<body>
 
</body>
</html>