<!--- Connectie met de database --->
<?php
$database="pwsinlogsysteem";
$link = mysqli_connect("localhost",'root','usbw',$database) 
    or die (mysqli_connect_error());
?>
<?php
$code = $_GET['code'];
$type = $_GET['type'];
$show = false;
if ($type == 'wachtwoord') {
    ?>
 
    <head>
    <link rel="stylesheet" href="style2.css">
    <body>
        <div class="login-page">
        <div class="form">
      <h2>Wachtwoord wijzigen</h2><br>


    <?
    $result = mysqli_query($link
        , "SELECT * FROM `account` WHERE `code`='$code' LIMIT 1");
    $user = mysqli_fetch_assoc($result);
    if (count($user)) { 
        $show = true;
        if (isset($_POST['reset'])) {
            if ($_POST['wachtwoord'] == $_POST['wachtwoord2']) {
                $wachtwoord = mysqli_real_escape_string($link, $_POST['wachtwoord']);
                $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
                $result = mysqli_query($link, "UPDATE account SET wachtwoord = '$hash', code = NULL WHERE code = '$code'")
                or die (mysqli_error($link));
                echo "Je wachtwoord is veranderd. <br> Je wordt in 5 seconden doorgestuurd naar de homepagina.";
                // doorsturen naar homepage na 5 sec
                header('Refresh: 5; URL=login.php');
            } else {
                echo "wachtwoorden komen niet overeen probeer opnieuw.";
            }
        }
    } else {
        echo "De link klopt niet";
    }

    if ($show == true) {

    ?>
        <form method="post" class="login-form">
        
        <input type="text" name="wachtwoord" placeholder="wachtwoord" required><br>
        <input type="text" name="wachtwoord2" placeholder="bevestig wachtwoord" required><br>
        <input class="button" type="submit" name="reset" value="Vraag nieuw wachtwoord aan">
           </form>
<? }
}?>
            
            </div></div></body>
