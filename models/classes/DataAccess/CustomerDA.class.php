<?php

namespace DataAccess;

use Data\Customer;
use Data\AgeGroup;

class CustomerDA {

    public function getAgeGroups($conn) {
        $list = array();

        $stmt = $conn->prepare("SELECT * FROM `customer_age_group`");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultAgeGroupCode, $resultAgeGroupEnUS, $resultAgeGroupZhHant);
        while ($stmt->fetch()) {
            $ageGroup = new AgeGroup();
            $ageGroup->setAgeGroupCode($resultAgeGroupCode);
            $ageGroup->setAgeGroupEnUs($resultAgeGroupEnUS);
            $ageGroup->setAgeGroupZhHant($resultAgeGroupZhHant);
            array_push($list, $ageGroup);
        }
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }

    public function getOneCustomerByID($customerID, $conn) {

        $customer = new Customer();

        $stmt = $conn->prepare("SELECT `customer`.`Customer_ID`, `customer`.`Email`, `customer`.`Password`, `customer`.`Customer_Surname`, `customer`.`Customer_GivenName`, `customer`.`Regeion_Code`, `customer`.`Mobile_Phone_No`, `customer`.`Gender`, `customer`.`Age_Group_Code`, `customer_age_group`.`Age_Group_en-US`, `customer_age_group`.`Age_Group_zh-Hant`, `customer`.`Address`, `customer`.`Activated` FROM `customer`, `customer_age_group` WHERE `customer`.`Customer_ID`=? AND `Date_Deleted` IS NULL");
        $stmt->bind_param("s", $customerID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultCustomerID, $resultEmail, $resultPassword, $resultCustomerSurname, $resultCustomerGivenName, $resultRegeionCode, $resultMobilePhoneNo, $resultGender, $resultAgeGroupCode, $resultAeGroupEnUs, $resultAgeGroupZhHant, $resultAddress, $resultActivated);
        $stmt->fetch();

        $customer->setCustomerID($resultCustomerID);
        $customer->setEmail($resultEmail);
        $customer->setPassword($resultPassword);
        $customer->setCustomerSurname($resultCustomerSurname);
        $customer->setCustomerGivenName($resultCustomerGivenName);
        $customer->setAgeGroupCode($resultAgeGroupCode);
        $customer->setAgeGroupEnUs($resultAeGroupEnUs);
        $customer->setAgeGroupZhHant($resultAgeGroupZhHant);
        $customer->setRegeionCode($resultRegeionCode);
        $customer->setMobilePhoneNo($resultMobilePhoneNo);
        $customer->setGender($resultGender);
        $customer->setAddress($resultAddress);
        $customer->setActivated(($resultActivated==1)?true:false);

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $customer;
    }

    public function getOneCustomerByEmail($customerEmail, $conn) {
        $customer = new Customer();

        $stmt = $conn->prepare("SELECT `customer`.`Customer_ID`, `customer`.`Email`, `customer`.`Password`, `customer`.`Customer_Surname`, `customer`.`Customer_GivenName`, `customer`.`Regeion_Code`, `customer`.`Mobile_Phone_No`, `customer`.`Gender`, `customer`.`Age_Group_Code`, `customer_age_group`.`Age_Group_en-US`, `customer_age_group`.`Age_Group_zh-Hant`, `customer`.`Address`, `customer`.`Activated` FROM `customer`, `customer_age_group` WHERE `customer`.`Email`=? AND `Date_Deleted` IS NULL");
        $stmt->bind_param("s", $customerEmail);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultCustomerID, $resultEmail, $resultPassword, $resultCustomerSurname, $resultCustomerGivenName, $resultRegeionCode, $resultMobilePhoneNo, $resultGender, $resultAgeGroupCode, $resultAeGroupEnUs, $resultAgeGroupZhHant, $resultAddress, $resultActivated);
        $stmt->fetch();

        $customer->setCustomerID($resultCustomerID);
        $customer->setEmail($resultEmail);
        $customer->setPassword($resultPassword);
        $customer->setCustomerSurname($resultCustomerSurname);
        $customer->setCustomerGivenName($resultCustomerGivenName);
        $customer->setAgeGroupCode($resultAgeGroupCode);
        $customer->setAgeGroupEnUs($resultAeGroupEnUs);
        $customer->setAgeGroupZhHant($resultAgeGroupZhHant);
        $customer->setRegeionCode($resultRegeionCode);
        $customer->setMobilePhoneNo($resultMobilePhoneNo);
        $customer->setGender($resultGender);
        $customer->setAddress($resultAddress);
        $customer->setActivated(($resultActivated==1)?true:false);

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $customer;
    }

    public function insert($customer, $conn) {
        $customerID = $customer->getCustomerID();
        $email = $customer->getEmail();
        $password = $customer->getPassword();
        $customerSurname = $customer->getCustomerSurname();
        $customerGivenName = $customer->getCustomerGivenName();
        $ageGroupCode = $customer->getAgeGroupCode();
        $regeionCode = $customer->getRegeionCode();
        $mobilePhoneNo = $customer->getMobilePhoneNo();
        $gender = $customer->getGender();
        $address = $customer->getAddress();

        $stmt = $conn->prepare("INSERT INTO `customer`(`Customer_ID`, `Email`, `Password`, `Customer_Surname`, `Customer_GivenName`, `Regeion_Code`, `Mobile_Phone_No`, `Gender`, `Age_Group_Code`, `Address`) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssiisis", $customerID, $email, $password, $customerSurname, $customerGivenName, $regeionCode, $mobilePhoneNo, $gender, $ageGroupCode, $address);
        $stmt->execute();
        
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

    public function update($customer, $conn) {
        $customerID = $customer->getCustomerID();
        $email = $customer->getEmail();
        $password = $customer->getPassword();
        $customerSurname = $customer->getCustomerSurname();
        $customerGivenName = $customer->getCustomerGivenName();
        $ageGroupCode = $customer->getAgeGroupCode();
        $regeionCode = $customer->getRegeionCode();
        $mobilePhoneNo = $customer->getMobilePhoneNo();
        $gender = $customer->getGender();
        $address = $customer->getAddress();
        $activated = ($customer->getActivated())?'1':'0';

        $stmt = $conn->prepare("UPDATE `customer` SET `Email`=?,`Password`=?,`Customer_Surname`=?,`Customer_GivenName`=?,`Regeion_Code`=?,`Mobile_Phone_No`=?,`Gender`=?,`Age_Group_Code`=?,`Address`=?,`Activated`=? WHERE `Customer_ID`=? AND `Date_Deleted` IS NULL");
        $stmt->bind_param("ssssiisisis", $email, $password, $customerSurname, $customerGivenName, $regeionCode, $mobilePhoneNo, $gender, $ageGroupCode, $address, $activated, $customerID);
        $stmt->execute();
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

    public function delete($customerID, $conn) {
        $stmt = $conn->prepare("UPDATE `customer` SET `Date_Deleted`=? WHERE `Customer_ID`=?");
        $delectDate = date("Y-m-d H:i:s");
        $stmt->bind_param("ss", $delectDate, $customerID);
        $stmt->execute();
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

}
