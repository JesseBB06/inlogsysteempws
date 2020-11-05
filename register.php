<!--- Connectie met de database --->
<?php
$database="pwsinlogsysteem";
$link = mysqli_connect("localhost",'root','usbw',$database) 
    or die (mysqli_connect_error());
 ?>
<!--- HTML code: form's --->
<form method="post">
    <input type="hidden" name="id">
    Voornaam:<input type="text" name="voornaam" required><br>
    Achternaam:<input type="text" name="achternaam" required><br>
    Emailadres:<input type="text" name="emailadres" required><br>
    Telefoonnummer:<input type="text" name="telefoonnummer" required><br>
    Wachtwoord:<input type="password" name="wachtwoord" required><br>
    Herhaal wachtwoord:<input type="password" name="wachtwoord2" required><br>
    <input type="submit" name="verzend" value="verzend">
</form>
 <?php
    /* Als er op de button 'verzend' geklikt is en wachtwoord komt overeen met wachtwoord2 dan kan je verder */
    if(isset($_POST['verzend'])){
        if($_POST['wachtwoord'] == $_POST['wachtwoord2']){
            /* Check of het emailadres een geldig emailadres is en check of het emailadres al bekend is in de database. */
            if(!filter_var($_POST['emailadres'], FILTER_VALIDATE_EMAIL)) {
                exit('Dit is een niet geldig emailadres.');
            }
            $select = mysqli_query($link, "SELECT `emailadres` FROM `account` WHERE `emailadres` = '".$_POST['emailadres']."'") or exit(mysqli_error());
            if(mysqli_num_rows($select)) {
                exit('Dit emailadres is al in gebruik.');
            }
            /* Variabeles voor de veiligheid */
        $id  = mysqli_real_escape_string($link,$_POST['id']); 
        $voornaam  = mysqli_real_escape_string($link,$_POST['voornaam']); 
        $achternaam  = mysqli_real_escape_string($link,$_POST['achternaam']); 
        $emailadres  = mysqli_real_escape_string($link,$_POST['emailadres']); 
        $telefoonnummer  = mysqli_real_escape_string($link,$_POST['telefoonnummer']); 
        $wachtwoord = mysqli_real_escape_string($link,$_POST['wachtwoord']); 
            
        /* Ingevoerde velden in de database zetten. + encripten van het wachtwoord */
        $result = mysqli_query($link," INSERT INTO `account`(`id`, `voornaam`, `achternaam`, `emailadres`, `telefoonnummer`, `wachtwoord`) VALUES ('$id','$voornaam','$achternaam','$emailadres','$telefoonnummer','".password_hash($wachtwoord, PASSWORD_BCRYPT)."')") 
    or die (mysqli_error($link));
            echo "De gebruiker is aangemaakt.";
        /* echo als de wachtwoorden niet gelijk aan elkaar zijn */
        }else{
            echo "De gekozen wachtwoorden zijn niet gelijk aan elkaar.";
        }
    }
 ?>
