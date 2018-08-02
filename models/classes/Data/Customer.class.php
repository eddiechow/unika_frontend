<?php
namespace Data;

class Customer {
    
    private $customerID;
    private $email;
    private $password;
    private $customerSurname;
    private $customerGivenName;
    private $regeionCode;
    private $mobilePhoneNo;
    private $gender;
    private $address;
    private $activated;
    
    private $ageGroup;
    
    function __construct() {
        $this->ageGroup = new AgeGroup();
    }
            
    function getCustomerID() {
        return $this->customerID;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function getCustomerSurname() {
        return $this->customerSurname;
    }

    function getCustomerGivenName() {
        return $this->customerGivenName;
    }

    function getAgeGroupCode() {
        return $this->ageGroup->getAgeGroupCode();
    }

    function getAgeGroupEnUs() {
        return $this->ageGroup->getAgeGroupEnUs();
    }

    function getAgeGroupZhHant() {
        return $this->ageGroup->getAgeGroupZhHant();
    }

    function getRegeionCode() {
        return $this->regeionCode;
    }

    function getMobilePhoneNo() {
        return $this->mobilePhoneNo;
    }

    function getGender() {
        return $this->gender;
    }

    function getAddress() {
        return $this->address;
    }

    function getActivated() {
        return $this->activated;
    }

    function setCustomerID($customerID) {
        $this->customerID = $customerID;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setCustomerSurname($customerSurname) {
        $this->customerSurname = $customerSurname;
    }

    function setCustomerGivenName($customerGivenName) {
        $this->customerGivenName = $customerGivenName;
    }

    function setAgeGroupCode($ageGroupCode) {
        $this->ageGroup->setAgeGroupCode($ageGroupCode);
    }

    function setAgeGroupEnUs($ageGroupEnUs) {
        $this->ageGroup->setAgeGroupEnUs($ageGroupEnUs);
    }

    function setAgeGroupZhHant($ageGroupZhHant) {
        $this->ageGroup->setAgeGroupZhHant($ageGroupZhHant);
    }

    function setRegeionCode($regeionCode) {
        $this->regeionCode = $regeionCode;
    }

    function setMobilePhoneNo($mobilePhoneNo) {
        $this->mobilePhoneNo = $mobilePhoneNo;
    }

    function setGender($gender) {
        $this->gender = $gender;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function setActivated($activated) {
        $this->activated = $activated;
    }


}
