<!--- Connectie met de database --->
<?php
session_start();
include 'connect.php';


include '../vendor/autoload.php';

use PragmaRX\Google2FA\Google2FA;

//Secret key aanmaken
$google2fa = new Google2FA();
$tfa = new RobThree\Auth\TwoFactorAuth('Pws');
$secret = $tfa->createSecret();



//stap terug
if(isset($_POST['terug'])){
    if($_SESSION['stap']>1){
        $_SESSION['stap']--;
    }
}
/* Als er op de button 'verzend' geklikt is en wachtwoord komt overeen met wachtwoord2 dan kan je verder */
if(isset($_POST['verzend'])){
            
            if($_SESSION['stap'] == 1)
            {
                //reCAPTCHA
                if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
              {
                $secret = 'HIER STOND DE SECRET RECAPTCHA KEY';
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                $responseData = json_decode($verifyResponse);
                if($responseData->success)
                {
                    /* Variabeles voor de veiligheid */
                    $_SESSION['id'] = mysqli_real_escape_string($link,$_POST['id']); 
                    $_SESSION['voornaam']  = mysqli_real_escape_string($link,$_POST['voornaam']); 
                    $_SESSION['achternaam']  = mysqli_real_escape_string($link,$_POST['achternaam']); 
                    $_SESSION['emailadres']  = mysqli_real_escape_string($link,$_POST['emailadres']); 
                    $_SESSION['telefoonnummer']  = mysqli_real_escape_string($link,$_POST['telefoonnummer']); 
                    $_SESSION['wachtwoord'] = mysqli_real_escape_string($link,$_POST['wachtwoord']); 
                    /* De 'requirements' van het wachtwoord checken. */
                    $uppercase = preg_match('@[A-Z]@', $_SESSION['wachtwoord']);
                    $lowercase = preg_match('@[a-z]@', $_SESSION['wachtwoord']);
                    $number    = preg_match('@[0-9]@', $_SESSION['wachtwoord']);
                    $specialChars = preg_match('@[^\w]@', $_SESSION['wachtwoord']);
                    if($_POST['wachtwoord'] != $_POST['wachtwoord2']){
                        $warning = "Wachtwoorden zijn niet aan elkaar gelijk";
                    }else{
                        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_SESSION['wachtwoord']) < 8) {
                            $warning = "Je wachtwoord moet minimaal 8 tekens lang zijn, minimaal één hoofdletter, één cijfer en één speciaal teken bevatten.";
                        }else{
                            /* Check of het emailadres een geldig emailadres is en check of het emailadres al bekend is in de database. */
                            if(!filter_var($_SESSION['emailadres'], FILTER_VALIDATE_EMAIL)) {
                                $warning = "Dit emailadres staan wij niet toe, omdat het geen geschikt emailadres is.";
                            }else{
                                $select = mysqli_query($link, "SELECT `emailadres` FROM `account` WHERE `emailadres` = '".$_POST['emailadres']."'") or exit(mysqli_error());
                                if(mysqli_num_rows($select)) {
                                    $warning = "Dit emailadres is bij ons al in gebruik.";
                                }else{
                                    //qrCodeUrl aanmaken met de secret key
                                    $qrCodeUrl = $google2fa->getQRCodeUrl('inlogveiligheid.nl', $_SESSION['emailadres'], $_SESSION['secret']);
                                    $_SESSION['stap'] = 2;
                                }   
                            }
                        }
                    }
                }
                else
                {
                    $warning = 'Recaptcha is niet gelukt, probeer het opnieuw.';
                }
           }else{
                  $warning = "De reCAPTCHA is nog niet geverifieerd";
              }
            }else if($_SESSION['stap'] == 2)
            {
                $result = $tfa->verifyCode($_SESSION['secret'], $_POST['code']);
                if($result){
                    // Ingevoerde velden in de database zetten. + encripten van het wachtwoord + tegen sql injecties.
                    $result = $link -> prepare(" INSERT INTO `account`(`voornaam`, `achternaam`, `emailadres`, `telefoonnummer`, `wachtwoord`, `secretKey`) VALUES (?,?,?,?,'".password_hash($_SESSION['wachtwoord'], PASSWORD_DEFAULT)."', ?)")or exit(mysqli_error());
                    $result -> bind_param("sssss", $_SESSION['voornaam'], $_SESSION['achternaam'], $_SESSION['emailadres'], $_SESSION['telefoonnummer'], $_SESSION['secret']);
                    $result->execute();
                    $result->store_result();
                    $result -> close();
                    $link -> close();
                    if($result){
                        session_unset();
                        $_SESSION['login'] = true;
                        header("Location: index.php");
                    }
                }else{
                    $warning = "De code is onjuist";
                }
            }
}else{
    if(!isset($_SESSION['stap']) || $_SESSION['stap'] != 2){
        $_SESSION['stap'] = 1;
        $_SESSION['secret'] = $secret;
    }else if($_SESSION['stap']==2){
        
        //qrCodeUrl aanmaken met de secret key
        $qrCodeUrl = $google2fa->getQRCodeUrl('inlogveiligheid.nl', $_SESSION['emailadres'], $_SESSION['secret']);
        
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
                <p class="warning"><?php if(isset($warning)){ echo $warning;} ?></p>
                <p class="success"><?php if(isset($success)){ echo $success;} ?></p>
            <?php if($_SESSION['stap'] == 1){ ?>
            <h2>Registreren</h2><br>
                <form method="post" class="login-form">
                    <input type="hidden" name="id">
                    <input type="text" name="voornaam" placeholder="Voornaam" required><br>
                    <input type="text" name="achternaam" placeholder="Achternaam" required><br>
                    <input type="text" name="emailadres" placeholder="Emailadres" required><br>
                    <input type="text" name="telefoonnummer" placeholder="Telefoonnummer" required><br>
                    <input type="password" name="wachtwoord" placeholder="Wachtwoord" minlength="8" required><br>
                    <input type="password" name="wachtwoord2" placeholder="Herhaal wachtwoord" minlength="8" required><br><br>
                    <div class="g-recaptcha" data-sitekey="6LfYnigaAAAAALkaFhJP8h5lierWV1hL_b--OK1S"></div>
                    <input class="button" type="submit" name="verzend" value="Maak account aan">
                    <p class="message">Al een account aangemaakt? <a href="login.php">Log in</a></p>
                </form>  
            <?php }else if($_SESSION['stap'] == 2){ ?>
                <h2>Authenticator app koppelen</h2>
                <p>Scan deze QR-code in de authenticator app en vul de code in.</p>
                <?php echo "<img src='https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".$qrCodeUrl."&choe=UTF-8' alt=''>" ?>
                <form method="post">
                    <input type="text" name="code">
                    <input class="button" type="submit" name="verzend" value="Verzend code">
                    <input class="button" type="submit" name="terug" value="Terug">
                </form>
            
            <?php } ?>
            </div>
        </div>
    
    </body>
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
