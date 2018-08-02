<?php

namespace DataAccess;

use Data\OrderLine;

class OrderLineDA {

    public function getPackageOrderLine($orderID, $conn) {

        $stmt = $conn->prepare("SELECT `package_order_line`.`Order_ID`, `package_order_line`.`Product_ID`, `product_package`.`Product_Name_en-US`, `product_package`.`Product_Name_zh-Hant`, `package_order_line`.`Unit_Price`, `package_order_line`.`Discount` "
                . "FROM `package_order_line` INNER JOIN `product_package` ON `package_order_line`.`Product_ID`=`product_package`.`Product_ID` "
                . "WHERE `package_order_line`.`Order_ID`=?");
        $stmt->bind_param("s", $orderID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultOrderID, $resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultUnitPrice, $resultDiscount);

        $stmt->fetch();

        $orderLine = new OrderLine($resultOrderID, $resultProductID, $resultUnitPrice);
        $orderLine->setQuantity(1);
        $orderLine->setDicount($resultDiscount);
        $orderLine->setProductNameEnUs($resultProductNameEnUs);
        $orderLine->setProductNameZhHant($resultProductNameZhHant);

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $orderLine;
    }

    public function insertPackageOrderLine($orderLine, $conn) {

        $stmt = $conn->prepare("INSERT INTO `package_order_line`(`Order_ID`, `Product_ID`, `Unit_Price`, `Discount`) VALUES (?,?,?,?)");
        $stmt->bind_param("isii", $orderLine->getOrderID(), $orderLine->getProductID(), $orderLine->getPrice(), $orderLine->getDicount());
        $stmt->execute();

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

    public function getBottleOrderLine($orderID, $conn) {

        $stmt = $conn->prepare("SELECT `bottle_order_line`.`Order_ID`, `bottle_order_line`.`Product_ID`, `product_bottle`.`Product_Name_en-US`, `product_bottle`.`Product_Name_zh-Hant`, `product_bottle`.`Bottle_Capacity`, `bottle_order_line`.`Unit_Price`, `bottle_order_line`.`Discount` "
                . "FROM `bottle_order_line` INNER JOIN `product_bottle` ON `bottle_order_line`.`Product_ID`=`product_bottle`.`Product_ID` "
                . "WHERE `bottle_order_line`.`Order_ID`=?");
        $stmt->bind_param("s", $orderID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultOrderID, $resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultBottleCapacity, $resultUnitPrice, $resultDiscount);

        $stmt->fetch();

        $orderLine = new OrderLine($resultOrderID, $resultProductID, $resultUnitPrice);
        $orderLine->setBottleCapacity($resultBottleCapacity);
        $orderLine->setQuantity(1);
        $orderLine->setDicount($resultDiscount);
        $orderLine->setProductNameEnUs($resultProductNameEnUs);
        $orderLine->setProductNameZhHant($resultProductNameZhHant);

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $orderLine;
    }

    public function insertBottleOrderLine($orderLine, $conn) {
        $stmt = $conn->prepare("INSERT INTO `bottle_order_line`(`Order_ID`, `Product_ID`, `Unit_Price`, `Discount`) VALUES (?,?,?,?)");
        $stmt->bind_param("isii", $orderLine->getOrderID(), $orderLine->getProductID(), $orderLine->getPrice(), $orderLine->getDicount());
        $stmt->execute();

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

    public function getPerfumeOrderLine($orderID, $conn) {
        $list = array();

        $stmt = $conn->prepare("SELECT `perfume_order_line`.`Order_ID`, `perfume_order_line`.`Product_ID`, `product_perfume`.`Product_Name_en-US`, `product_perfume`.`Product_Name_zh-Hant`, `product_perfume_category`.`Category_Name_en-US`, `product_perfume_category`.`Category_Name_zh-Hant`, `perfume_order_line`.`Unit_Price`, `perfume_order_line`.`Discount`, `perfume_order_line`.`Qty`, `perfume_order_line`.`Note` "
                . "FROM `perfume_order_line` INNER JOIN `product_perfume` ON `perfume_order_line`.`Product_ID`=`product_perfume`.`Product_ID` "
                . "INNER JOIN `product_perfume_category` ON `product_perfume`.`Perfume_Category_Code`=`product_perfume_category`.`Perfume_Category_Code` "
                . "WHERE `perfume_order_line`.`Order_ID`=?");
        $stmt->bind_param("s", $orderID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultOrderID, $resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultPerfumeCategoryNameEnUs, $resultPerfumeCategoryNameZhHant, $resultUnitPrice, $resultDiscount, $resultQty, $resultNote);
        
        while ($stmt->fetch()) {

            $orderLine = new OrderLine($resultOrderID, $resultProductID, $resultUnitPrice);
            $orderLine->setDicount($resultDiscount);
            $orderLine->setProductNameEnUs($resultProductNameEnUs);
            $orderLine->setProductNameZhHant($resultProductNameZhHant);
            $orderLine->setPerfumeCategoryNameEnUs($resultPerfumeCategoryNameEnUs);
            $orderLine->setPerfumeCategoryNameZhHant($resultPerfumeCategoryNameZhHant);
            $orderLine->setQuantity($resultQty);
            $orderLine->setNote($resultNote);

            array_push($list, $orderLine);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }

    public function insertPerfumeOrderLine($orderLine, $conn) {
        $stmt = $conn->prepare("INSERT INTO `perfume_order_line`(`Order_ID`, `Product_ID`, `Unit_Price`, `Discount`, `Qty`, `Note`) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("isiiis", $orderLine->getOrderID(), $orderLine->getProductID(), $orderLine->getPrice(), $orderLine->getDicount(), $orderLine->getQuantity(), $orderLine->getNote());
        $stmt->execute();

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

}
