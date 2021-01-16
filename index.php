<?php
include 'connect.php';

   if (isset($_SESSION['login']) and $_SESSION['login']) {
       return true;
    }else{
        header("location: login.php");
}
if(isset($_POST['logout'])){
    $_SESSION['login'] = false;
    header('location: login.php');
}
?>
<head>
<link rel="stylesheet" href="style2.css">
<form method="post">
    <input class="button2" type="submit" name="logout" value="UITLOGGEN">
</form><br>
    <div class="box">
        <h2>Profielwerkstuk inlogsystemen</h2>
        <h3>Gemaakt door Toine van Wonderen & Jesse Blom</h3>
        <h4>6 vwo - Jan Van Egmond Lyceum</h4>
        <h4>Begeleider Menno Merlijn (MLI)</h4>
    </div><br>
    <div class='box'>
        <iframe src="PWS%20inlogsystemen%20-%20Toine%20&%20Jesse%20V2.pdf" width="100%" height="700px"></iframe>
    </div>
</head>
