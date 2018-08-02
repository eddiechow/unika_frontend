<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\CustomerDA;
use DataAccess\PackageShoppingCartDA;
use DataAccess\BottleShoppingCartDA;
use DataAccess\PerfumeShoppingCartDA;

use DataAccess\CustomerLoginLogDA;
use Data\CustomerLoginLog;

if (isset($_GET['action'])) {

    $customerDA = new CustomerDA();
    $cllda = new CustomerLoginLogDA();

    if ($_GET['action'] == "login") {

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $post_data = file_get_contents("php://input");
            $request = json_decode($post_data);

            $loginLog = new CustomerLoginLog(true);
            
            if (!empty($request->email) && !empty($request->password)) {
                try {
                    if(!Format::validEmail(xssFilter($request->email))) {
                        $data['error'] = "The email address is invalid.";
                    } else {
                        $customer = $customerDA->getOneCustomerByEmail($request->email, Database::getConnection());
                        
                        $isLocked = false;
                        $unlockedTime = null;
                        $logs = null;
                        if($customer->getCustomerID()){
                            $logs = $cllda->getLoginLogByCustID($customer->getCustomerID(), time()-(60*60*30), Database::getConnection());
                            for ($i=0; $i<count($logs); $i++) {
                                if ($logs[$i]->getLocked()) {
                                    $isLocked = true;
                                }
                                if ($logs[$i]->getUnlocked()) {
                                    $isLocked = false;
                                    $unlockedTime = $logs[$i]->getTime();
                                }
                            }
                        }
                        
                        if ($isLocked){
                            $loginLog->setCustomerID($customer->getCustomerID());
                            $loginLog->setSuccess(false);
                            $loginLog->setPasswordIncorrect(false);
                            $loginLog->setLocked(false);
                            $loginLog->setAcStatusTempLock(true);
                            $loginLog->setAcStatusNonActive(false);
                            $cllda->insert($loginLog, Database::getConnection());
                            $data['error'] = "Your account is locked. Please login again later."; 
                        } else {
                            
                            if($unlockedTime != null){
                                $logs = null;
                                $logs = $cllda->getLoginLogByCustID($customer->getCustomerID(), time()+60, Database::getConnection());
                            }
                            
                            
                            if ($customer->getEmail() == $request->email && Hash::verifyHash($request->password, $customer->getPassword())) {
                                setcookie("customerID", $customer->getCustomerID(), null, null, null, false, true);
                                $loginLog->setCustomerID($customer->getCustomerID());
                                $loginLog->setSuccess(true);
                                $loginLog->setPasswordIncorrect(false);
                                $loginLog->setLocked(false);
                                $loginLog->setAcStatusTempLock(false);
                                $loginLog->setAcStatusNonActive(($customer->getActivated())?false:true);
                                $cllda->insert($loginLog, Database::getConnection());
                            } else if($customer->getEmail() == $request->email && !Hash::verifyHash($request->password, $customer->getPassword())) {
                                $attempts = 1;
                                for ($i=0; $i<=count($logs); $i++) {
                                    if($i<count($logs)){
                                        if ($logs[$i]->getPasswordIncorrect()){
                                            if($attempts == 4){
                                                $loginLog->setCustomerID($customer->getCustomerID());
                                                $loginLog->setSuccess(false);
                                                $loginLog->setPasswordIncorrect(true);
                                                $loginLog->setLocked(true);
                                                $loginLog->setAcStatusTempLock(false);
                                                $loginLog->setAcStatusNonActive(($customer->getActivated())?false:true);
                                                $cllda->insert($loginLog, Database::getConnection());
                                                $data['error'] = "You have attempted to input incorrect password. Your account is lock for 30 minutes."; 
                                                break;
                                            }
                                            $attempts++;
                                        } else if ($logs[$i]->getSuccess()){
                                            $attempts = 1;
                                        }
                                    } else if ($i==count($logs)){
                                        $loginLog->setCustomerID($customer->getCustomerID());
                                        $loginLog->setSuccess(false);
                                        $loginLog->setPasswordIncorrect(true);
                                        $loginLog->setLocked(false);
                                        $loginLog->setAcStatusTempLock(false);
                                        $loginLog->setAcStatusNonActive(($customer->getActivated())?false:true);
                                        $cllda->insert($loginLog, Database::getConnection());
                                        $data['error'] = "Email or password is incorrect.";                                     
                                    }
                                }
                            } else {
                                $data['error'] = "Email or password is incorrect."; 
                            }                        
                        }
                    }
                } catch (Exception $ex) {
                    $data['error'] = Database::getDbErrorMsg();
                }
            } else {
                $data['error'] = "Please enter email and passowrd";
            }

            if (!isset($data['error']))
                $data['success'] = true;
        }
    } else if ($_GET['action'] == "logout") {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            setcookie("customerID", null, time() - 3600, "", "", false, true);
            unset($_COOKIE['customerID']);
            $data['success'] = true;
        }
    } else if ($_GET['action'] == "getLoggedInAccount") {

        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $data['loggedInCustomer'] = null;
            try {
                if (isLoggedInCustomerExist()) {
                    $customer = $customerDA->getOneCustomerByID($_COOKIE['customerID'], Database::getConnection());
                    
                    $packageDA = new PackageShoppingCartDA();
                    $package = $packageDA->getOnePackageByCustID($_COOKIE['customerID'], Database::getConnection());
                    $packageItemNum = ($package->getProductID())?1:0;
                    
                    $bottleDA = new BottleShoppingCartDA();
                    $bottle = $bottleDA->getOneBottleByCustID($_COOKIE['customerID'], Database::getConnection());
                    $bottleItemNum = ($bottle->getProductID())?1:0;
                    
                    $perfumDA = new PerfumeShoppingCartDA();
                    $perfumeItemNum = count($perfumDA->getAllPerfumeByCustID($_COOKIE['customerID'], Database::getConnection()));
                    
                    $data['loggedInCustomer']['customerGivenName'] = $customer->getCustomerGivenName();
                    $data['loggedInCustomer']['activated'] = $customer->getActivated();
                    $data['loggedInCustomer']['itemNumInCart'] = $packageItemNum + $bottleItemNum + $perfumeItemNum;
                }
            } catch (Exception $ex) {
                $data['databaseError'] = Database::getDbErrorMsg();
            }
        }

        session_start();
        if (isset($_SESSION["registeredCustID"]))
            unset($_SESSION["registeredCustID"]);
    }

    if (isset($data))
        echo json_encode($data);
}