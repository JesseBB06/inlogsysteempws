<?php

$servername = "localhost";
$dBUsername = "root";
$dBPassword = "usbw";
$dBName = "pws";

$link = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if(!$link){
    die("connection failed: ".mysqli_connect_error());
}
?>