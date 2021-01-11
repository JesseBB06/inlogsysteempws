<!--- Connectie met de database --->
<?php
include 'connect.php';
include "functions.php";
if (isset($_POST['reset'])) {
     if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
  {
        $secret = '6LfYnigaAAAAAJry6gpl0xbxdqOLWN5WpIlbK65s';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success)
        {
            $email = mysqli_real_escape_string($link, $_POST['email']);
            if(!empty($email)){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if(wachtwoordvergeten($email)){
                        $success = "Er is een email verzonden met een link om je wachtwoord te veranderen";   
                    }else{
                        $error = "Er is iets misgegaan probeer het opnieuw";
                    }
                }else{
                    $error = "Dit emailadres staan wij niet toe, omdat het geen geschikt emailadres is.";
                }
            }else{
                $error = "Je hebt geen email ingevuld";
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
?>
<head>
    <link rel="stylesheet" href="style2.css">
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
</head>
    <body>
        <div class="login-page">
        <div class="form">
      <h2>Wachtwoord vergeten?</h2><br>
      <form method="post" class="login-form">
        <input type="hidden" name="id">
        <p class="warning"><?php if(isset($error)){ echo $error;} ?></p>
        <p class="success"><?php if(isset($success)){ echo $success;} ?></p>
        <input type="text" name="email" placeholder="emailadres" required><br>
        <div class="g-recaptcha" data-sitekey="6LfYnigaAAAAALkaFhJP8h5lierWV1hL_b--OK1S"></div>
        <input class="button" type="submit" name="reset" value="Vraag nieuw wachtwoord aan">
          <p class="message">Al een account aangemaakt? <a href="login.php">Log in</a></p>
           </form>
            </div>
        </div>
    </body>
