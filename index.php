<?php
require_once 'vendor/autoload.php';

use App\EmailProcessor;
use App\Config;

$mail = new EmailProcessor();

$id = mt_rand(1, 999);
$steepOne = $mail->mxRecordValidate();
$steepTwo = $mail->sendEmail($id);
$steepThree = $mail->checkEmail($id);

if ($steepOne === true && $steepTwo === true && !empty($steepThree)) {
    echo '<h2>Message was successfully sent and received. Email ' . Config::getUser('email') . ' works well!</h2>';
}