<!--- Connectie met de database --->
<?php
$database="pwsinlogsysteem";
$link = mysqli_connect("localhost",'root','usbw',$database) 
    or die (mysqli_connect_error());
?>
<!--- HTML code: form's --->
<head>
    <link rel="stylesheet" href="style2.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet"> 
    <body>
    <div class="login-page">
    <div class="form">
    <h2>Log in</h2>
        <p class="message">Om toegang te krijgen tot ons profielwerkstuk over inlogsystemen.</p><br>
    <form method="post" class="login-form">
    <input type="hidden" name="id">
    <input type="text" id="emailadres" name="emailadres" placeholder="emailadres" required><br>
    <input type="password" id="wachtwoord" name="wachtwoord" placeholder="wachtwoord" required><br><br>
<!-- VOOR DE RECAPTCHA VEILIGHEID GEBRUIKER --->
    <input class="button" type="submit" name="login" value="Login">
           <!-- class="g-recaptcha" data-sitekey="HIER MOET NOG EEN SITEKEY AANGEMAAKT WORDEN" data-callback='onSubmit' data-action='submit' -->
        <p class="message">Wachtwoord vergeten? <a href="http://localhost/pws%20inlogsysteem/wachtwoordvergeten.php">Klik hier</a></p>
      <p class="message">Nog niet geregistreerd? <a href="http://localhost/pws%20inlogsysteem/register.php">Maak een account</a></p>
        </form>
<?php
if (isset($_POST['login'])) {

        $emailadres = mysqli_real_escape_string($link, $_POST['emailadres']);
        $wachtwoord = mysqli_real_escape_string($link, $_POST['wachtwoord']);

        $result = mysqli_query($link
            , "SELECT * FROM account WHERE `emailadres`='$emailadres' LIMIT 1"
        );
        $user = mysqli_fetch_assoc($result);
        $hash = $user['wachtwoord'];
        
        if (count($user)) { 
            if (password_verify($wachtwoord, $hash)) {
                
                $_SESSION['login'] = true;
                header("location: index.php");

            } else {
               echo "<p style='color:red;'><br>Wachtwoord en/of emailadres zijn incorrect.</p>";
            }
        } else {
            echo "<p style='color:red;'><br>Wachtwoord en/of emailadres zijn incorrect.</p>";
        }
    }
    ?>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
   function onSubmit(token) {
     document.getElementById("demo-form").submit();
   }
 </script>
        
        </div>
        <div class="form">
            <p>&copy; 2020 - Gemaakt door: Toine van Wonderen & Jesse Blom<br> (Jan Van Egmond Lyceum, Purmerend)<br> klas: 6 vwo.</p><br>
                  <p>Begeleider: MLI - Menno Merlijn</p>
        </div>
    </div>
    </body>
</head>
