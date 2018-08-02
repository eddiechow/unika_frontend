<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\CustomerDA;

try {
    if($_SERVER['REQUEST_METHOD'] === "GET"){
        if (isLoggedInCustomerExist()) {
            if(isLoggedInCustomerExist()) {
                $customerDA =  new CustomerDA();
                $customer = $customerDA->getOneCustomerByID($_COOKIE['customerID'], Database::getConnection());
                $data['name'] = strtoupper($customer->getCustomerSurname())." ".$customer->getCustomerGivenName();
                $data['email'] = $customer->getEmail();
            }
        }
    } else if($_SERVER['REQUEST_METHOD'] === "POST"){
        $post_data = file_get_contents("php://input");
        $request = json_decode($post_data);
        
        $data['error'] = null;
        
        if(empty($request->name))
            $data['error']['invalid']['name'] = "Name is required.";
        else
            $name = $request->name;
        
        if(empty($request->email) || !Format::validEmail(xssFilter($request->email)))
            $data['error']['invalid']['email'] = "Valid email address is required.";
        else
            $email = $request->email;
        
        if(empty($request->message))
            $data['error']['invalid']['message'] = "Message is required.";
        else
            $message = $request->message;
        
        if($data['error']==null){ 
            $subject = "UNIKA - Contact US";
            $body = "UNIKA,"
                    . "<br><br>".$message
                    . "<br><br>If you have any problem, please contect me <".$email.">"
                    . ".<br><br>Thinks,"
                    . ".<br>".$name;

            try {
                sendmail(EMAIL_FROM, "UNKIA", $subject, $body);
                $data['success'] = "The message has been sent.";
            } catch (Exception $ex) {
                $data['error']['main'] = $ex->getMessage();
            }
        }
    }
} catch (Exception $ex) {
    $data['error']['main'] = Database::getDbErrorMsg();
}

if(!empty($data))
    echo json_encode($data);