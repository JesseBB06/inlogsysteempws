<?php
include 'connect.php';
if(isset($_POST['logout'])){
    $_SESSION['login'] = false;
    header('location: login.php');
}
?>

<form method="post">
    <input type="submit" name="logout">
</form>