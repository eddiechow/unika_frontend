<?php

session_start();

header('Content-Type: application/json');
require 'global.php';

use DataAccess\CustomerDA;

if (isset($_GET['action'])) { 
    
    $customerDA = new CustomerDA();
    
    if ($_GET['action'] == "sendSecurityToken") {

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $post_data = file_get_contents("php://input");
            $request = json_decode($post_data);
            
            if(!empty($request->email)){
                try {
                    if(!Format::validEmail(xssFilter($request->email))) {
                        $data['error']['email'] = "The email address is invalid.";
                    } else {
                        $customer = $customerDA->getOneCustomerByEmail($request->email, Database::getConnection());
                        if ($customer->getEmail() == $request->email) {
                            $securityToken = randomDigit(6);

                            $subject = "UNIKA - Reset Password";
                            $body = "Dear " . ($customer->getGender() == 'M' ? "Mr." : "Miss") . " " . $customer->getCustomerSurname()
                                    . ",<br><br>Your security token is <b>" . $securityToken . "</b>. The token will be voided after 5 minutes"
                                    . ".<br>If you have any problem, please contect us <unikapims@gmail.com>"
                                    . ".<br><br>Thinks,"
                                    . ".<br>UNKIA";
                            try {
                                sendmail($customer->getEmail(), $customer->getCustomerSurname() . ", " . $customer->getCustomerGivenName(), $subject, $body);

                                $_SESSION["genSecurityTokenTime"] = time();
                                $_SESSION["securityToken"] = $securityToken;
                                $_SESSION["savedEmail"] = $customer->getEmail();
                                $data['seccess']['email'] = "The security token has been sent.";
                                
                            } catch (Exception $ex) {
                                $data['error']['system'] = $ex->getMessage();
                            }
                            
                        } else {
                            $data['error']['email'] = "The email does not exist.";
                        }
                    }
                } catch (Exception $ex) {
                    $data['error']['system'] = Database::getDbErrorMsg();
                }
            } else {
                $data['error']['email'] = "Please input email";
            }
        }
    } else if ($_GET['action'] == "resetPassword") {

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $post_data = file_get_contents("php://input");
            $request = json_decode($post_data);
            
            if(!empty($request->newPassword) && !empty($request->confirmPassword) && !empty($request->securityToken)) {
                
                if(isset($_SESSION["savedEmail"])){
                    try {
                        $customer = $customerDA->getOneCustomerByEmail($_SESSION["savedEmail"], Database::getConnection());
                        if ($customer->getEmail() != $_SESSION["savedEmail"]) {
                            $data['error']['email'] = "Failed to reset password";
                        } else if (!isset($_SESSION["genSecurityTokenTime"]) || !isset($_SESSION["securityToken"]) || $_SESSION["genSecurityTokenTime"] + 300 <= time() || xssFilter($request->securityToken) != $_SESSION["securityToken"]) {
                            $data['error']['other'] = "Security token is invalid";
                            if (!isset($_SESSION["genSecurityTokenTime"]) || !isset($_SESSION["securityToken"]) || $_SESSION["genSecurityTokenTime"] + 300 <= time()){
                                unset($_SESSION["genSecurityTokenTime"]);
                                unset($_SESSION["securityToken"]);
                            }
                        } else if ($request->newPassword != $request->confirmPassword) {
                            $data['error']['other'] = "New password and confirm password are not match";
                        } else if (!Format::passwordStrong($request->newPassword)) {
                            $data['error']['other'] = "Password must be at least 8 characters including at least 1 uppercase letter, 1 lowercase letter and 1 number";
                        }

                        if (!isset($data['error']['other']) && !isset($data['error']['email'])) {
                            $password = Hash::getHash($request->newPassword);
                            $customer->setPassword($password);
                            $customerDA->update($customer, Database::getConnection());
                            $data['success']['final'] = true;
                        }
                    } catch (Exception $ex) {
                        $data['error']['system'] = Database::getDbErrorMsg();
                    }
                } else {
                    $data['error']['system'] = "Failed to reset password";
                }
            } else {
                $data['error']['other'] = "Please input security token, new password and confirm password"; 
            }
            
        }
    } else if ($_GET['action'] == "closeModal") {

        unset($_SESSION["genSecurityTokenTime"]);
        unset($_SESSION["securityToken"]);
        unset($_SESSION["savedEmail"]);

    }
    
    if (isset($data))
        echo json_encode($data);
}