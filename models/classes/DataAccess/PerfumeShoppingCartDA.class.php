<?php

namespace DataAccess;

use Data\ShoppingCart;

class PerfumeShoppingCartDA {

    public function getAllPerfumeByCustID($customerID, $conn) {
        $list = array();

        $stmt = $conn->prepare("SELECT `Customer_ID`, `Product_ID`, `Note`, `Qty` FROM `perfume_shopping_cart` WHERE `Customer_ID`=?");
        $stmt->bind_param("s", $customerID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultCustomerID, $resultProductID, $resultNote, $resultQty);
        
        while ($stmt->fetch()) {
            $shoppingCart = new ShoppingCart();
            $shoppingCart->setCustomerID($resultCustomerID);
            $shoppingCart->setProductID($resultProductID);
            $shoppingCart->setNote($resultNote);
            $shoppingCart->setQuantity($resultQty);
            array_push($list, $shoppingCart);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }
    
    public function insert($shoppingCart, $conn) {
        $customerID = $shoppingCart->getCustomerID();
        $productID = $shoppingCart->getProductID();
        $quantity = $shoppingCart->getQuantity();
        $note = $shoppingCart->getNote();

        $stmt = $conn->prepare("INSERT INTO `perfume_shopping_cart`(`Customer_ID`, `Product_ID`, `Qty`, `Note`) VALUES (?,?,?,?)");
        $stmt->bind_param("ssis", $customerID, $productID, $quantity, $note);
        $stmt->execute();
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

    public function update($shoppingCart, $conn) {
        $customerID = $shoppingCart->getCustomerID();
        $productID = $shoppingCart->getProductID();
        $quantity = $shoppingCart->getQuantity();
        $note = $shoppingCart->getNote();

        $stmt = $conn->prepare("UPDATE `perfume_shopping_cart` SET `Qty`=?, `Note`=? WHERE `Customer_ID`=? AND `Product_ID`=?");
        $stmt->bind_param("isss", $quantity, $note, $customerID, $productID);
        $stmt->execute();
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

    public function delete($customerID, $productID, $conn) {
        $stmt = $conn->prepare("DELETE FROM `perfume_shopping_cart` WHERE `Customer_ID`=? AND `Product_ID`=?");
        $stmt->bind_param("ss", $customerID, $productID);
        $stmt->execute();
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

}
