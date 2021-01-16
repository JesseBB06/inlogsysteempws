<?php
function isLogin(){
    if(isset($_SESSION['login']) and $_SESSION['login'] == true){
        return true;
    }
    return false;
}

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

function wachtwoordvergeten($emailadres){
    include 'connect.php';
    $result = mysqli_query($link, "SELECT * FROM account WHERE `emailadres`='$emailadres' LIMIT 1");
    $user = mysqli_fetch_assoc($result);
    $voornaam = $user['voornaam'];
    if (count($user)) {
        $code = randomstring();
        $codeGelijk = false;
        while($codeGelijk != true){
            $result = mysqli_query($link, "SELECT * FROM `account` WHERE `code`='$code' LIMIT 1");
            if(mysqli_num_rows($result)>0){
                $code = randomstring();
            }else{
                $codeGelijk = true;
            } 
        }
        
        $message = "Hey $voornaam, \r\nMet deze link kan je een nieuw wachtwoord aanvragen.\r\n inlogveiligheid.nl/wachtwoordwijzigen.php?code=$code' \r\nDit is een automatisch  bericht. Klopt het niet dat jij je wachtwoord hebt aangevraagd te wijzigen kan je dit negeren.\r\nLukt het niet de link te openen of gaat er iets anders mis, neem contact op met de beheerder. Deze link is voor 3 uur geldig.";
        $tijd = date("Y-m-d H:i:s");
        if(mail("$emailadres", "Wachtwoord vergeten", "$message") && mysqli_query($link, "UPDATE account SET code = '$code', tijd='$tijd' WHERE emailadres = '$emailadres'")){
            return true;
        }else{
            return false;
        }
    } else {
        return false;
    }
}
?>
