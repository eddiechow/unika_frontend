<?php
namespace Data;

class Order {
    
    private $customerID;
    private $orderID;
    private $orderDate;
    private $rejected;
    private $barCode;
    private $approveDate;
    private $payPalPaymentID;
    private $payPalTransactionID;
    
    function __construct($customerID, $orderID, $orderDate, $rejected) {
        $this->customerID = $customerID;
        $this->orderID = $orderID;
        $this->orderDate = $orderDate;
        $this->rejected = $rejected;
    }

    function getCustomerID() {
        return $this->customerID;
    }

    function getOrderID() {
        return $this->orderID;
    }

    function getOrderDate() {
        return $this->orderDate;
    }

    function getRejected() {
        return $this->rejected;
    }

    function getBarCode() {
        return $this->barCode;
    }

    function getApproveDate() {
        return $this->approveDate;
    }
    
    function getPayPalPaymentID() {
        return $this->payPalPaymentID;
    }

    function getPayPalTransactionID() {
        return $this->payPalTransactionID;
    }

    function setCustomerID($customerID) {
        $this->customerID = $customerID;
    }

    function setOrderID($orderID) {
        $this->orderID = $orderID;
    }

    function setOrderDate($orderDate) {
        $this->orderDate = $orderDate;
    }

    function setRejected($rejected) {
        $this->rejected = $rejected;
    }

    function setBarCode($barCode) {
        $this->barCode = $barCode;
    }

    function setApproveDate($approveDate) {
        $this->approveDate = $approveDate;
    }

    function setPayPalPaymentID($payPalPaymentID) {
        $this->payPalPaymentID = $payPalPaymentID;
    }

    function setPayPalTransactionID($payPalTransactionID) {
        $this->payPalTransactionID = $payPalTransactionID;
    }

}
