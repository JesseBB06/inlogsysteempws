<!--- Connectie met de database --->
<?php
session_start();
include 'connect.php';
include 'vendor\autoload.php';

use PragmaRX\Google2FA\Google2FA;

//Secret key aanmaken
$google2fa = new Google2FA();
$tfa = new RobThree\Auth\TwoFactorAuth('Pws');
 


//stap terug
if(isset($_POST['terug'])){
    if($_SESSION['stap']>1){
        $_SESSION['stap']--;
    }
}
if (isset($_POST['login'])) {
    //ReCaptcha
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
      {
            $secret = '6LfYnigaAAAAAJry6gpl0xbxdqOLWN5WpIlbK65s';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if($responseData->success)
            {
                if($_SESSION['stap'] == 1){
                    $emailadres = mysqli_real_escape_string($link, $_POST['emailadres']);
                    $wachtwoord = mysqli_real_escape_string($link, $_POST['wachtwoord']);

                    $result = mysqli_query($link
                        , "SELECT * FROM account WHERE `emailadres`='$emailadres' LIMIT 1"
                    );
                    $user = mysqli_fetch_assoc($result);
                    $hash = $user['wachtwoord'];
                    $_SESSION['secret'] = $user['secretKey'];

                    if (count($user)) { 
                        if (password_verify($wachtwoord, $hash)) {
                            $_SESSION['stap'] = 2;
                        }else {
                            $warning = "Wachtwoord is incorrect.";
                        }
                    }else {
                        $warning = "emailadres is incorrect.";
                    }
                }else if($_SESSION['stap'] == 2){
                    $result = $tfa->verifyCode($_SESSION['secret'], $_POST['code']);
                    if($result){
                        session_unset();
                        $_SESSION['login'] = true;
                        header("location: profiel.php");
                    }
                }
            }
            else
            {
                $warning = 'Robot verification failed, please try again.';
            }
       }else{
        $warning = "De reCAPTCHA is nog niet geverifieerd";
    }
}else{
if(!isset($_SESSION['stap']) || $_SESSION['stap'] != 2){
        $_SESSION['stap'] = 1;
    }
}
?>
<!--- HTML code: form's --->
<head>
    <link rel="stylesheet" href="style2.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet"> 
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
</head>
<body>
    <div class="login-page">
        <div class="form">
            <h2>Log in</h2>
            <p class="message">Om toegang te krijgen tot ons profielwerkstuk over inlogsystemen.</p><br>
            <p class="warning"><?php if(isset($warning)){ echo $warning;} ?></p>
            <p class="success"><?php if(isset($success)){ echo $success;} ?></p>
            <form method="post" class="login-form" id="demo-form">
                <?php if($_SESSION['stap'] == 1){ ?>
                <input type="hidden" name="id">
                <input type="text" id="emailadres" name="emailadres" placeholder="emailadres" required><br>
                <input type="password" id="wachtwoord" name="wachtwoord" placeholder="wachtwoord" required><br><br>
                <div class="g-recaptcha" data-sitekey="6LfYnigaAAAAALkaFhJP8h5lierWV1hL_b--OK1S"></div>
                <input type="submit" name="login" value="Login" class="button">
                <p class="message">Wachtwoord vergeten? <a href="wachtwoordvergeten.php">Klik hier</a></p>
                <p class="message">Nog niet geregistreerd? <a href="register.php">Maak een account</a></p>
                <?php }else if($_SESSION['stap'] == 2){ ?>
                    <h2>Vul de code van de authenitcator app in</h2>
                    <input type="text" name="code">
                    <input type="submit" name="login" value="verstuur code">
                <input class="button" type="submit" name="terug" value="Terug">
                <?php } ?>
            </form>

        </div>
        <div class="form">
            <p>&copy; 2020 - Gemaakt door: Toine van Wonderen & Jesse Blom<br> (Jan Van Egmond Lyceum, Purmerend)<br> klas: 6 vwo.</p><br>
                  <p>Begeleider: MLI - Menno Merlijn</p>
        </div>
    </div>
</body>
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
