<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\CustomerDA;

try{
    if (isLoggedInCustomerExist()) {

        $customerDA = new CustomerDA();

        if (isset($_GET['action']) && $_GET['action'] == "edit") {

            $post_data = file_get_contents("php://input");
            $request = json_decode($post_data);

            $customer = $customerDA->getOneCustomerByID($_COOKIE['customerID'], Database::getConnection());

            $data['error'] = null;
            if(empty($request->currentPassword)) {
                $data['error']['invalidCurrentPassword'] = "Please input current password fisrt";
            } else if(!Hash::verifyHash($request->currentPassword, $customer->getPassword())) {
                $data['error']['invalidCurrentPassword'] = "Current password is not correct";
            } else {
                $customerEmailChanged = false;

                if(!empty($request->password) || !empty($request->confirmPassword)){
                    if(empty($request->password))
                        $data['error']['invalidPassowrd'] = "Passowrd is required";
                    else if(!Format::passwordStrong(xssFilter($request->password)))
                        $data['error']['invalidPassowrd'] = "Password must be at least 8 characters including at least 1 uppercase letter, 1 lowercase letter and 1 number.";

                    if(empty($request->confirmPassword))
                        $data['error']['invalidConfirmPassword'] = "Confirm Password password is required";
                    else if($request->password!=$request->confirmPassword)
                        $data['error']['invalidConfirmPassword'] = "Passwords do not match";
                    else
                        if($request->currentPassword == $request->confirmPassword){
                            $data['error']['invalidPassowrd'] = "New passowrd and current password is match";
                            $data['error']['invalidConfirmPassword'] = "New passowrd and current password is match";
                        }
                        else
                            $customer->setPassword(Hash::getHash(xssFilter($request->confirmPassword)));              
                }

                if(empty($request->surname))
                    $data['error']['invalidSurname'] = "Surname is required.";
                else if($request->surname!=$customer->getCustomerSurname())
                    $customer->setCustomerSurname(xssFilter($request->surname));

                if(empty($request->givenName))
                    $data['error']['invalidGivenName'] = "Given Name is required.";
                else if($request->givenName!=$customer->getCustomerGivenName())
                    $customer->setCustomerGivenName(xssFilter($request->givenName));

                if(empty($request->gender))
                    $data['error']['invalidGender'] = "Please select gender.";
                else if($request->gender!=$customer->getGender())
                    $customer->setGender(xssFilter($request->gender));

                if(empty($request->email) || !Format::validEmail(xssFilter($request->email)))
                    $data['error']['invalidEmail'] = "Valid email address is required.";  
                else if($request->email!=$customer->getEmail())
                    try{
                        $existCustomer = $customerDA->getOneCustomerByEmail(xssFilter($request->email), Database::getConnection());
                        if($existCustomer->getEmail()==xssFilter($request->email))
                            $data['error']['invalidEmail'] = "Email address exists.";
                        else {
                            $customer->setEmail(xssFilter($request->email));
                            $customer->setActivated(false);
                            $customerEmailChanged = true;
                            unset($existCustomer);
                        }
                    } catch (Exception $ex) {
                        $data['error']['main'] = $ex->getMessage();
                    }

                if(empty($request->regionCode))
                    $data['error']['invalidRegionCode'] = "Please select Country/Region Code.";
                else if($request->regionCode!=$customer->getRegeionCode())
                    $customer->setRegeionCode(xssFilter($request->regionCode));

                if(empty($request->mobilePhoneNumber))
                    $data['error']['invalidMobilePhoneNumber'] = "Mobile phone number is required.";
                else if(!ctype_digit(xssFilter($request->mobilePhoneNumber)))
                    $data['error']['invalidMobilePhoneNumber'] = "The valid mobile phone number is invalid.";
                else if($request->mobilePhoneNumber!=$customer->getMobilePhoneNo())
                    $customer->setMobilePhoneNo(xssFilter($request->mobilePhoneNumber));

                if(empty($request->ageGroupCode))
                    $data['error']['invalidAgeGroupCode'] = "Please select age group.";
                else if($request->ageGroupCode!=$customer->getAgeGroupCode())
                    $customer->setAgeGroupCode(xssFilter($request->ageGroupCode));

                if(empty($request->address))
                    $data['error']['invalidAddress'] = "Address is required.";
                else if($request->address!=$customer->getAddress())
                    $customer->setAddress(xssFilter($request->address));

                if($data['error']==null){
                    try {
                        $customerDA->update($customer, Database::getConnection());
                        if($customerEmailChanged)
                            $data['success']['message'] = "Profile is edited sucessfully, but you need to verify your email.";
                        else
                            $data['success']['message'] = "Profile is edited sucessfully.";

                        $data['success']['emailChanged'] = $customerEmailChanged;
                    } catch (Exception $ex) {
                        $data['error']['main'] = $ex->getMessage();
                    }
                }     
            }

        } else {

            try{
                $costomer = $customerDA->getOneCustomerByID($_COOKIE['customerID'], Database::getConnection());
                $data['surname'] = $costomer->getCustomerSurname();
                $data['givenName'] = $costomer->getCustomerGivenName();
                $data['gender'] = $costomer->getGender();
                $data['email'] = $costomer->getEmail();
                $data['regionCode'] = $costomer->getRegeionCode();
                $data['mobilePhoneNumber'] = $costomer->getMobilePhoneNo();
                $data['ageGroupCode'] = $costomer->getAgeGroupCode();
                $data['address'] = $costomer->getAddress();

            } catch (Exception $ex) {
                $data['error']['databaseError'] = $ex->getMessage();
            }
        }

        if(!empty($data))
            echo json_encode($data);
    }
} catch (Exception $ex) {
    $data['error']['databaseError'] = $ex->getMessage();
}
