<?php

header('Content-Type: application/json');
require 'global.php';

use Data\Customer;
use DataAccess\CustomerDA;

if($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    
    $data['error']=null;

    $post_data = file_get_contents("php://input");
    $request = json_decode($post_data);
    
    $customerDA = new CustomerDA();
    $customer = new Customer();

    if(empty($request->surname))
        $data['error']['invalidSurname'] = "Surname is required.";
    else
        $customer->setCustomerSurname(xssFilter($request->surname));
    
    if(empty($request->givenName))
        $data['error']['invalidGivenName'] = "Given Name is required.";
    else
        $customer->setCustomerGivenName(xssFilter($request->givenName));
    
    if(empty($request->gender))
        $data['error']['invalidGender'] = "Please select gender.";
    else
        $customer->setGender(xssFilter($request->gender));

    if(empty($request->email) || !Format::validEmail(xssFilter($request->email)))
        $data['error']['invalidEmail'] = "Valid email address is required."; 
    else
        try{
            $existCustomer = $customerDA->getOneCustomerByEmail(xssFilter($request->email), Database::getConnection());
            if($existCustomer->getEmail()==xssFilter($request->email))
                $data['error']['invalidEmail'] = "Email address exists.";
            else {
                $customer->setEmail(xssFilter($request->email));
                unset($existCustomer);
            }
        } catch (Exception $ex) {
            $data['error']['main'] = $ex->getMessage();
        }

    if(empty($request->password))
        $data['error']['invalidPassowrd'] = "Passowrd is required";
    else if(!Format::passwordStrong(xssFilter($request->password)))
        $data['error']['invalidPassowrd'] = "Password must be at least 8 characters including at least 1 uppercase letter, 1 lowercase letter and 1 number.";

    if(empty($request->confirmPassword))
        $data['error']['invalidConfirmPassword'] = "Confirm Password password is required";
    else if($request->password!=$request->confirmPassword)
        $data['error']['invalidConfirmPassword'] = "Passwords do not match";
    else
        $customer->setPassword(Hash::getHash(xssFilter($request->confirmPassword)));

    if(empty($request->regionCode))
        $data['error']['invalidRegionCode'] = "Please select Country/Region Code.";
    else
        $customer->setRegeionCode(xssFilter($request->regionCode));
    
    if(empty($request->mobilePhoneNumber))
        $data['error']['invalidMobilePhoneNumber'] = "Mobile phone number is required.";
    else if(!ctype_digit(xssFilter($request->mobilePhoneNumber)))
        $data['error']['invalidMobilePhoneNumber'] = "The valid mobile phone number is invalid.";
    else
        $customer->setMobilePhoneNo(xssFilter($request->mobilePhoneNumber));
    
    if(empty($request->ageGroupCode))
        $data['error']['invalidAgeGroupCode'] = "Please select age group.";
    else
        $customer->setAgeGroupCode(xssFilter($request->ageGroupCode));

    if(empty($request->address))
        $data['error']['invalidAddress'] = "Address is required.";
    else
        $customer->setAddress(xssFilter($request->address));
    

    if($data['error']==null){
        try {
            $randCustomerID = null;
            $existCustomer = null;
            do {
                $randCustomerID = 'C'.randomDigit(7);
                $existCustomer = $customerDA->getOneCustomerByID($randCustomerID, Database::getConnection());
                $customer->setCustomerID($randCustomerID);
            } while($existCustomer->getCustomerID()==$randCustomerID);
            $customerDA->insert($customer, Database::getConnection());
            $_SESSION["registeredCustID"] = $customer->getCustomerID();
            $data['success'] = true;
        } catch (Exception $ex) {
            $data['error']['main'] = $ex->getMessage();
        }
    }
    
    if(!empty($data))
        echo json_encode($data);
}