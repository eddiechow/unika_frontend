<?php

namespace DataAccess;

use Data\ShoppingCart;

class BottleShoppingCartDA {

    public function getOneBottleByCustID($customerID, $conn) {
        $shoppingCart = new ShoppingCart();

        $stmt = $conn->prepare("SELECT `Customer_ID`, `Product_ID` FROM `bottle_shopping_cart` WHERE `Customer_ID`=?");
        $stmt->bind_param("s", $customerID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultCustomerID, $resultProductID);
        $stmt->fetch();

        $shoppingCart->setCustomerID($resultCustomerID);
        $shoppingCart->setProductID($resultProductID);

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $shoppingCart;
    }

    public function insert($shoppingCart, $conn) {
        $customerID = $shoppingCart->getCustomerID();
        $productID = $shoppingCart->getProductID();

        $stmt = $conn->prepare("INSERT INTO `bottle_shopping_cart`(`Customer_ID`, `Product_ID`) VALUES (?,?)");
        $stmt->bind_param("ss", $customerID, $productID);
        $stmt->execute();
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

    public function delete($customerID, $conn) {
        $stmt = $conn->prepare("DELETE FROM `bottle_shopping_cart` WHERE `Customer_ID`=?");
        $stmt->bind_param("s", $customerID);
        $stmt->execute();
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

}
