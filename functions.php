<!--- Connectie met de database --->
<?php
$database="pwsinlogsysteem";
$link = mysqli_connect("localhost",'root','usbw',$database) 
    or die (mysqli_connect_error());
?>
<?php
function randomstring()
{
    $length = '20';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomstring = '';
    for ($i = 0; $i < $length; $i++) {
        $randomstring .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomstring;
}

function wachtwoordvergeten($emailadres)
{
    $code = randomstring();

    $result = mysqli_query($link
        , "SELECT voornaam FROM account WHERE `emailadres`='$emailadres' LIMIT 1");
    $user = mysqli_fetch_assoc($result);
    $row = mysqli_fetch_array($result);
    $voornaam = $row['voornaam'];
    
    $message = "Hey $voornaam, \r\nMet deze link kan je een nieuw wachtwoord aanvragen.\r\nZET HIER DE LINK NEER\r\nDit is een automatisch  bericht. Klopt het niet dat jij je wachtwoord hebt aangevraagd te wijzigen kan je dit negeren.\r\nLukt het niet de link te openen of gaat er iets anders mis, neem contact op met de beheerder.
                ";

    if (count($user)) {
        mail("$emailadres", "Wachtwoord vergeten", "$message");


        $result = mysqli_query($link, "UPDATE account SET code = '$code' WHERE emailadres = '$emailadres'")
        or die (mysqli_error($link));
    } else {
        echo "Dit email adres is niet bekend in onze database. Maak een nieuw account aan.";
    }
}
?>
