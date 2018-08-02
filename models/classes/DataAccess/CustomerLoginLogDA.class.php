<?php

namespace DataAccess;

use Data\CustomerLoginLog;

class CustomerLoginLogDA {

    public function getLoginLogByCustID($customerID, $fromDate, $conn) {
        $list = array();

        $stmt = $conn->prepare("SELECT * FROM `customer_login_log` WHERE `Customer_ID`=?".(($fromDate!=null)?" AND `Time`>=?":"")." ORDER BY `customer_login_log`.`Time` ASC");
        if ($fromDate!=null)
            $stmt->bind_param("ss", $customerID, date('Y-m-d H:i:s', $fromDate));
        else
            $stmt->bind_param("s", $customerID);
        
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultCustomerID, $resultSuccess, $resultPasswordIncorrect, $resultLocked, $resultUnlockedBy, $resultAcStatusTempLock, $resultAcStatusNonActive, $resultTime, $resultOs, $resultBrowser, $resultIp);

        while ($stmt->fetch()) {
            $loginLog = new CustomerLoginLog();
            if ($resultSuccess == 1 && $resultAcStatusNonActive == 0)
                $loginLog->setStatus("Login successfully.");
            else if ($resultPasswordIncorrect == 1)
                $loginLog->setStatus("Input incorrect password.");
            else if ($resultLocked == 1)
                $loginLog->setStatus("Input incorrect password for 5 times. The account is locked for 30 minutes.");
            else if ($resultAcStatusTempLock == 1)
                $loginLog->setStatus("Attempt to login while the account is locked.");
            else if ($resultSuccess == 1 && $resultAcStatusNonActive == 1)
                $loginLog->setStatus("Login successfully without verified email.");
            else if ($resultUnlockedBy != null)
                $loginLog->setStatus("The account is unlocked.");

            $loginLog->setCustomerID($resultCustomerID);
            $loginLog->setSuccess(($resultSuccess == 1)?true:false);
            $loginLog->setPasswordIncorrect(($resultPasswordIncorrect == 1)?true:false);
            $loginLog->setLocked(($resultLocked == 1)?true:false);
            $loginLog->setUnlocked(($resultUnlockedBy != null)?true:false);
            $loginLog->setTime(strtotime($resultTime));
            $loginLog->setOs($resultOs);
            $loginLog->setBrowser($resultBrowser);
            $loginLog->setIp($resultIp);

            array_push($list, $loginLog);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }

    public function insert($loginLog, $conn) {
        $customerID = $loginLog->getCustomerID();
        $success = ($loginLog->getSuccess()) ? 1 : 0;
        $passwordIncorrect = ($loginLog->getPasswordIncorrect()) ? 1 : 0;
        $locked = ($loginLog->getLocked()) ? 1 : 0;
        $acStatusTempLock = ($loginLog->getAcStatusTempLock()) ? 1 : 0;
        $acStatusNonActive = ($loginLog->getAcStatusNonActive()) ? 1 : 0;
        $time = date('Y-m-d H:i:s', $loginLog->getTime());
        $os = $loginLog->getOs();
        $browser = $loginLog->getBrowser();
        $ip = $loginLog->getIp();

        $stmt = $conn->prepare("INSERT INTO `customer_login_log`(`Customer_ID`, `Success`, `Password_Incorrect`, `Locked`, `Ac_Status_Temp_Lock`, `Ac_Status_Non_Active`, `Time`, `OS`, `Browser`, `IP`) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("siiiiissss", $customerID, $success, $passwordIncorrect, $locked, $acStatusTempLock, $acStatusNonActive, $time, $os, $browser, $ip);
        $stmt->execute();
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

}
