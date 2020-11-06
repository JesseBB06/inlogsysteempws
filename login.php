<!--- Connectie met de database --->
<?php
$database="pwsinlogsysteem";
$link = mysqli_connect("localhost",'root','usbw',$database) 
    or die (mysqli_connect_error());
?>
<!--- HTML code: form's --->
<form method="post">
    <input type="hidden" name="id">
    Emailadres:<input type="text" name="emailadres" required><br>
    Wachtwoord:<input type="password" name="wachtwoord" required><br>
    <input type="submit" name="login" value="login">
</form>
<?php
/* Kijken of je een emailadres en wachtwoord hebt opgegeven */
if (!empty($_POST['emailadres']) and !empty($_POST['wachtwoord'])) {
if (isset($_POST['login'])) {

    
        /* variabeles aanmaken voor de veiligheid */
        $emailadres = mysqli_real_escape_string($link, $_POST['emailadres']);
        $wachtwoord = mysqli_real_escape_string($link, $_POST['wachtwoord']);
        /* Kijken met welk emailadres we te maken hebben */
        $result = mysqli_query($link, "SELECT * FROM account WHERE `emailadres`='$emailadres' LIMIT 1");
        $user = mysqli_fetch_assoc($result);
        /* Kijken of het emailadres gelijk staat met het wachtwoord van dat emailadres */   
        $result = mysqli_query($link
            , "SELECT wachtwoord FROM `account` WHERE `emailadres`='$emailadres' LIMIT 1");
        $row = mysqli_fetch_array($result);
        $hash = $row['wachtwoord'];
        if (count($user)) { /* kijken of er data zit in $user als dat zo is ga je verder. */
            if (password_verify($wachtwoord, $hash)) {/* Alle user gegevens zetten we in de session zodat je erg makkelijk gegevens van de user kan tonen zonder de database te hoeven gebruiken. */    
        $_SESSION['login'] = true;
                echo "je bent ingelogd";
                header("location: index.php");

    }else {
                /* wachtwoord niet juist? >ook geen session */
                echo "Wachtwoord en/of email is incorrect.";
                
    }
        }else{
            echo "Wachtwoord en/of email is incorrect.";
        }
    
    }else{
    echo "Voer een geldig emailadres en/of wachtwoord in.";
}
}

?>
