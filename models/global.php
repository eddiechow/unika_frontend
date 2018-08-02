<?php

require '../vendor/autoload.php';

require 'configuration.php';

        
spl_autoload_register(function ($className) {
    require "classes/" . str_replace("\\", "/", $className) . ".class.php";
});

function isLoggedInCustomerExist() {
    $conn = Database::getConnection();
    if (isset($_COOKIE['customerID'])) {
        $customerDA = new DataAccess\CustomerDA();
        $customer = $customerDA->getOneCustomerByID($_COOKIE['customerID'], $conn);
        if ($customer->getCustomerID() != $_COOKIE['customerID']) {
            setcookie("customerID", null, time() - 3600, null, null, false, true);
            unset($_COOKIE['customerID']);
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}


function sendmail($to, $toName, $subject, $body) {
    try {
        $mail = new PHPMailer();

	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->SMTPAuth = EMAIL_SMTP_AUTH;
	$mail->SMTPSecure = EMAIL_SMTP_SECURE;
	$mail->Host = EMAIL_HOST;
	$mail->Port = EMAIL_PORT;
	$mail->Username = EMAIL_USERNAME;
        $mail->Password = EMAIL_PASSWORD;
	$mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->isHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->addAddress($to, $toName);

        if (!$mail->send())
            throw new Exception("Failed to send mail");
    } catch (Exception $ex) {
        throw new Exception($ex->getMessage());
    }
}

function randomDigit($length) {
    $result = "";
    for ($i = 1; $i <= $length; $i++) {
        $result .= mt_rand(0, 9);
    }
    return $result;
}

function xssFilter($data) {
    $data1 = trim($data);
    $data2 = stripslashes($data1);
    $data3 = htmlspecialchars($data2);
    return $data3;
}
