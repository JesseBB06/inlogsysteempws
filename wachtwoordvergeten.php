<!--- Connectie met de database --->
<?php
$database="pwsinlogsysteem";
$link = mysqli_connect("localhost",'root','usbw',$database) 
    or die (mysqli_connect_error());
?>
<?php
include "functions.php"; 
if (isset($_POST['reset'])) {
    $email = mysqli_real_escape_string($link, $_POST['email']);
    wachtwoordvergeten($email);
    $error = "email is verzonden";
}
?>
<head>
    <link rel="stylesheet" href="style2.css">
    <body>
        <div class="login-page">
        <div class="form">
      <h2>Wachtwoord vergeten?</h2><br>
      <form method="post" class="login-form">
        <input type="hidden" name="id">
        
        <input type="text" name="email" placeholder="emailadres" required><br>
        <input class="button" type="submit" name="reset" value="Vraag nieuw wachtwoord aan">
          <p class="message">Al een account aangemaakt? <a href="http://localhost/pws%20inlogsysteem/login.php">Log in</a></p>
           </form>
            </div>
        </div>
    </body>
</head>
