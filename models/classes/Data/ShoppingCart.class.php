<?php
namespace Data;

class ShoppingCart {

    private $customerID;
    private $productID;
    private $note;
    private $quantity;
    
    function getCustomerID() {
        return $this->customerID;
    }

    function getProductID() {
        return $this->productID;
    }

    function getNote() {
        return $this->note;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function setCustomerID($customerID) {
        $this->customerID = $customerID;
    }

    function setProductID($productID) {
        $this->productID = $productID;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }


}
