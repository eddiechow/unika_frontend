<?php

session_start();

require 'global.php';
header('Content-Type: application/json');

use DataAccess\CustomerDA;

$customerDA = new CustomerDA();

if (isset($_GET['action']) && $_GET['action']=="closeModal") {

    unset($_SESSION["genVerificationTokenTime"]);
    unset($_SESSION["verificationToken"]);
    
} else {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {

        if (isset($_SESSION["registeredCustID"]))
            $regiseredCustID = $_SESSION["registeredCustID"];
        else if (isLoggedInCustomerExist())
            $regiseredCustID = $_COOKIE['customerID'];

        if (isset($regiseredCustID)) {
            try {
                $customer = $customerDA->getOneCustomerByID($regiseredCustID, Database::getConnection());

                $activitonToken = randomDigit(6);

                $subject = "UNIKA - Email Verification";
                $body = "Dear " . ($customer->getGender() == 'M' ? "Mr." : "Miss") . " " . $customer->getCustomerSurname()
                        . ",<br><br>Welcome to UNIKA! Please verify your email address"
                        . ".<br>Your verification token is <b>" . $activitonToken . "</b>. The token will be voided after 5 minutes"
                        . ".<br>If you have any problem, please contect us <unikapims@gmail.com>"
                        . ".<br><br>Thinks,"
                        . ".<br>UNKIA";

                sendmail($customer->getEmail(), $customer->getCustomerSurname() . ", " . $customer->getCustomerGivenName(), $subject, $body);

                $_SESSION["genVerificationTokenTime"] = time();
                $_SESSION["verificationToken"] = $activitonToken;
            } catch (Exception $ex) {
                $data['error'] = $ex->getMessage();
            }
        }

        if (!isset($data['error']))
            $data['success'] = true;
    } else if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $post_data = file_get_contents("php://input");
        $request = json_decode($post_data);

        if (empty($request->verificationToken))
            $data['error']['verificationTokenError'] = "Please input email verification token";
        else if (!isset($_SESSION["genVerificationTokenTime"]) || !isset($_SESSION["verificationToken"]) || $_SESSION["genVerificationTokenTime"] + 300 <= time() || $_SESSION["verificationToken"] != xssFilter($request->verificationToken)) {
            $data['error']['verificationTokenError'] = "Verification token is invalid";
            if (!isset($_SESSION["genVerificationTokenTime"]) || !isset($_SESSION["verificationToken"]) || $_SESSION["genVerificationTokenTime"] + 300 <= time()) {
                unset($_SESSION["genVerificationTokenTime"]);
                unset($_SESSION["verificationToken"]);
            }
        } else if ($_SESSION["verificationToken"] == xssFilter($request->verificationToken))
            try {
                if (isset($_SESSION["registeredCustID"]))
                    $regiseredCustID = $_SESSION["registeredCustID"];
                else if (isLoggedInCustomerExist())
                    $regiseredCustID = $_COOKIE['customerID'];

                if (isset($regiseredCustID) && isset($regiseredCustID)) {
                    $customer = $customerDA->getOneCustomerByID($regiseredCustID, Database::getConnection());
                    $customer->setActivated(true);
                    $customerDA->update($customer, Database::getConnection());
                    unset($_SESSION["genVerificationTokenTime"]);
                    unset($_SESSION["verificationToken"]);
                    if (isset($_SESSION["registeredCustID"]))
                        unset($_SESSION["registeredCustID"]);
                }
            } catch (Exception $ex) {
                $data['error']['databaseError'] = $ex->getMessage();
            }

        if (!isset($data['error']))
            $data['success'] = true;
    }

    if (isset($data))
        echo json_encode($data);
    
}

