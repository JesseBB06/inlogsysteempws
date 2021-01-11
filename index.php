<?php
session_start();
include 'vendor\autoload.php';

use PragmaRX\Google2FA\Google2FA;
    
$google2fa = new Google2FA();

$tfa = new RobThree\Auth\TwoFactorAuth('Pws');

$secret = $tfa->createSecret();

$qrCodeUrl = $google2fa->getQRCodeUrl(
    'pws',
    'pws@gmail.com',
    $secret
);


if(isset($_POST['submit'])){
    $result = $tfa->verifyCode($_SESSION['secret'], $_POST['code']);
    if($result){
        echo "Success";
    }else{
        echo "Wrong";
    }
}else{
    $_SESSION['secret'] = $secret;
}
?>

<p><?php echo "<img src='https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".$qrCodeUrl."&choe=UTF-8' alt=''>" ?></p>
<form method="post">
    <input type="text" name="code">
    <button type="submit" name="submit">Submit</button>
</form>