<!--- Connectie met de database --->
<?php
include 'connect.php';
$code = $_GET['code'];
$show = false;
$result = mysqli_query($link, "SELECT * FROM `account` WHERE `code`='$code' LIMIT 1");
$user = mysqli_fetch_assoc($result);
if (count($user)) {
    $aangemaakt = strtotime($user['tijd']);
    $huidig = strtotime(date("Y-m-d H:i:s"));
    $diff = abs($huidig - $aangemaakt);  
    $years = floor($diff / (365*60*60*24));  
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 -  
    $months*30*60*60*24)/(60*60*24)); 
    $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
    if($hours>=3){
        $result = mysqli_query($link, "UPDATE account SET code = NULL WHERE code = '$code'")
            or die (mysqli_error($link));
        header("location: index.php");
    }else if($days!=0){
        $result = mysqli_query($link, "UPDATE account SET code = NULL WHERE code = '$code'")
            or die (mysqli_error($link));
        header("location: index.php");
    }
    
    $show = true;
    if (isset($_POST['reset'])) {
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
        {
            $secret = '6LfYnigaAAAAAJry6gpl0xbxdqOLWN5WpIlbK65s';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if($responseData->success)
            {
                $wachtwoord = mysqli_real_escape_string($link, $_POST['wachtwoord']);
                $wachtwoord2 = mysqli_real_escape_string($link, $_POST['wachtwoord2']);
                $uppercase = preg_match('@[A-Z]@', $wachtwoord);
                $lowercase = preg_match('@[a-z]@', $wachtwoord);
                $number    = preg_match('@[0-9]@', $wachtwoord);
                $specialChars = preg_match('@[^\w]@', $wachtwoord);
                if ($wachtwoord == $wachtwoord2) {
                    if($uppercase && $lowercase && $number && $specialChars && strlen($wachtwoord) >= 8) {
                        $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
                        $result = mysqli_query($link, "UPDATE account SET code = NULL, wachtwoord='$hash' WHERE code = '$code'")
                            or die (mysqli_error($link));
                        $success = "Je wachtwoord is veranderd. <br> Je wordt in 5 seconden doorgestuurd naar de homepagina.";
                        // doorsturen naar homepage na 5 sec
                        header('Refresh: 5; URL=login.php');
                    }else{
                        $error = "Je wachtwoord moet minimaal 8 tekens lang zijn, minimaal één hoofdletter, één cijfer en één speciaal teken bevatten.";
                    }
                } else {
                    $error = "wachtwoorden komen niet overeen probeer opnieuw.";
                }
            }
            else
            {
                $error = 'Recaptcha is niet gelukt, probeer het opnieuw.';
            }
        }else{
            $error = "De reCAPTCHA is nog niet geverifieerd";
        }
    }
} else {
    header("location: login.php");
}
?>
 
<head>
    <link rel="stylesheet" href="style2.css">
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
</head>
    <body>
        <div class="login-page">
            <div class="form">
            <h2>Wachtwoord wijzigen</h2><br>
            <p class="warning"><?php if(isset($error)){ echo $error;} ?></p>
            <p class="success"><?php if(isset($success)){ echo $success;}else{ ?></p>
<?php if ($show == true) { ?>
            <form method="post" class="login-form">
                <input type="password" name="wachtwoord" placeholder="wachtwoord" required><br>
                <input type="password" name="wachtwoord2" placeholder="bevestig wachtwoord" required><br>
                <div class="g-recaptcha" data-sitekey="6LfYnigaAAAAALkaFhJP8h5lierWV1hL_b--OK1S"></div>
                <input class="button" type="submit" name="reset" value="Vraag nieuw wachtwoord aan">
            </form>
<?php } 
    }?>
            </div>
        </div>
    </body>     