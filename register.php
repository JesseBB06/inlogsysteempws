<!--- Connectie met de database --->
<?php
$database="pwsinlogsysteem";
$link = mysqli_connect("localhost",'root','usbw',$database) 
    or die (mysqli_connect_error());
if ($link -> connect_errno) {
  echo "Failed to connect to MySQL: " . $link -> connect_error;
  exit();
}
 ?>

<!--- HTML code: form's --->
<head>
    <link rel="stylesheet" href="style.css">
    <body>
        <div class="login-page">
        <div class="form">
      <h2>Registreer</h2><br>
      <form method="post" class="login-form">
        <input type="hidden" name="id">
        <input type="text" name="voornaam" placeholder="voornaam" required><br>
        <input type="text" name="achternaam" placeholder="achternaam" required><br>
        <input type="text" name="emailadres" placeholder="emailadres" required><br>
        <input type="text" name="telefoonnummer" placeholder="telefoonnummer" required><br>
        <input type="password" name="wachtwoord" placeholder="wachtwoord" required><br>
        <input type="password" name="wachtwoord2" placeholder="herhaal wachtwoord" required><br>
        <input class="button" type="submit" name="verzend" value="registreer">
          <p class="message">Already registered? <a href="http://localhost/pws%20inlogsysteem/login.php">Sign In</a></p>
    </form>
      
  </div>
</div>
    
    </body>
</head>
 <?php
    /* Als er op de button 'verzend' geklikt is en wachtwoord komt overeen met wachtwoord2 dan kan je verder */
    if(isset($_POST['verzend'])){
        if($_POST['wachtwoord'] == $_POST['wachtwoord2']){
            if($_POST['wachtwoord']){
                /* Variabeles voor de veiligheid */
                $id  = mysqli_real_escape_string($link,$_POST['id']); 
                $voornaam  = mysqli_real_escape_string($link,$_POST['voornaam']); 
                $achternaam  = mysqli_real_escape_string($link,$_POST['achternaam']); 
                $emailadres  = mysqli_real_escape_string($link,$_POST['emailadres']); 
                $telefoonnummer  = mysqli_real_escape_string($link,$_POST['telefoonnummer']); 
                $wachtwoord = mysqli_real_escape_string($link,$_POST['wachtwoord']); 
                /* De 'requirements' van het wachtwoord checken. */
                $uppercase = preg_match('@[A-Z]@', $wachtwoord);
                $lowercase = preg_match('@[a-z]@', $wachtwoord);
                $number    = preg_match('@[0-9]@', $wachtwoord);
                $specialChars = preg_match('@[^\w]@', $wachtwoord);

                if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($wachtwoord) < 8) {
                    echo "<p style='color:red;'>Je wachtwoord moet minimaal 8 tekens lang zijn, minimaal één hoofdletter, één cijfer en één speciaal teken bevatten.</p>";
                }else{
            /* Check of het emailadres een geldig emailadres is en check of het emailadres al bekend is in de database. */
            if(!filter_var($_POST['emailadres'], FILTER_VALIDATE_EMAIL)) {
                exit('Dit is een niet geldig emailadres.');
            }
            $select = mysqli_query($link, "SELECT `emailadres` FROM `account` WHERE `emailadres` = '".$_POST['emailadres']."'") or exit(mysqli_error());
            if(mysqli_num_rows($select)) {
                exit('Dit emailadres is al in gebruik.');
            }
        /* Ingevoerde velden in de database zetten. + encripten van het wachtwoord + tegen sql injecties.*/
        $result = $link -> prepare(" INSERT INTO `account`(`id`, `voornaam`, `achternaam`, `emailadres`, `telefoonnummer`, `wachtwoord`) VALUES (?,?,?,?,?,'".password_hash($wachtwoord, PASSWORD_BCRYPT)."')")or exit(mysqli_error());
        $result -> bind_param("sssss", $id, $voornaam, $achternaam, $emailadres, $telefoonnummer);
                    $result->execute();
                    $result->store_result();
    
            echo "De gebruiker is aangemaakt.";
            $result -> close();
            $link -> close();
        /* echo als de wachtwoorden niet gelijk aan elkaar zijn */
        }
        }
    }else{
            echo "<p style='color:red;'>De gekozen wachtwoorden zijn niet gelijk aan elkaar.</p>";
        }
    }

 ?>
