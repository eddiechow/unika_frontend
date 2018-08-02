<?php

namespace DataAccess;

use Data\Bottle;

class ProductBottleDA {

    public function getAllBottles($conn, $discountOnly=false) {
        $list = array();

        $stmt = $conn->prepare("SELECT `product_bottle`.`Product_ID`, `product_bottle`.`Product_Name_en-US`, `product_bottle`.`Product_Name_zh-Hant`, `product_bottle`.`Qty_In_Stock`, `product_bottle`.`Price`, `product_bottle`.`Bottle_Capacity`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_bottle`.`Release_Date` "
                . "FROM `product_bottle` "
                . "LEFT JOIN `product_bottle_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL AND `product_bottle_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `product_bottle`.`Date_Deleted` IS NULL".(($discountOnly)?" AND `product_bottle_discount`.`Discount_Code` IS NOT NULL AND `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL":""));
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultQtyInStock, $resultPrice, $resultBottleCapacity, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleaseDate);
        
        while ($stmt->fetch()) {
            $bottle = new Bottle();
            $bottle->setProductID($resultProductID);
            $bottle->setProductNameEnUs($resultProductNameEnUs);
            $bottle->setProductNameZhHant($resultProductNameZhHant);
            $bottle->setQtyInStock($resultQtyInStock);
            $bottle->setOriginalPrice($resultPrice);
            $bottle->setDiscount($resultDiscount);
            $bottle->setDiscountTitleEnUs($resultDiscountTitleEnUs);
            $bottle->setDescriptionZhHant($resultDiscountTitleZhHant);
            $bottle->setBottleCapacity($resultBottleCapacity);
            $bottle->setReleaseDate(strtotime($resultReleaseDate));
            array_push($list, $bottle);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }
    
    public function getAllDiscountBottles($discountCode, $conn) {
        $list = array();

        $stmt = $conn->prepare("SELECT `product_bottle`.`Product_ID`, `product_bottle`.`Product_Name_en-US`, `product_bottle`.`Product_Name_zh-Hant`, `product_bottle`.`Qty_In_Stock`, `product_bottle`.`Price`, `product_bottle`.`Bottle_Capacity`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_bottle`.`Release_Date` "
                . "FROM `product_bottle` "
                . "LEFT JOIN `product_bottle_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL AND `product_bottle_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `discount`.`Discount_Code`=? AND `product_bottle`.`Date_Deleted` IS NULL AND `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL");
        $stmt->bind_param("s", $discountCode);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultQtyInStock, $resultPrice, $resultBottleCapacity, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleaseDate);
        
        while ($stmt->fetch()) {
            $bottle = new Bottle();
            $bottle->setProductID($resultProductID);
            $bottle->setProductNameEnUs($resultProductNameEnUs);
            $bottle->setProductNameZhHant($resultProductNameZhHant);
            $bottle->setQtyInStock($resultQtyInStock);
            $bottle->setOriginalPrice($resultPrice);
            $bottle->setDiscount($resultDiscount);
            $bottle->setDiscountTitleEnUs($resultDiscountTitleEnUs);
            $bottle->setDescriptionZhHant($resultDiscountTitleZhHant);
            $bottle->setBottleCapacity($resultBottleCapacity);
            $bottle->setReleaseDate(strtotime($resultReleaseDate));
            array_push($list, $bottle);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }

    public function getOneBottleByID($productID, $conn) {
        $bottle = new Bottle();

        $stmt = $conn->prepare("SELECT `product_bottle`.`Product_ID`, `product_bottle`.`Product_Name_en-US`, `product_bottle`.`Product_Name_zh-Hant`, `product_bottle`.`Description_en-US`, `product_bottle`.`Description_zh-Hant`, `product_bottle`.`Qty_In_Stock`, `product_bottle`.`Bottle_Capacity`, `product_bottle`.`Price`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_bottle`.`Release_Date` "
                . "FROM `product_bottle` "
                . "LEFT JOIN `product_bottle_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL "
                . "AND `product_bottle_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `product_bottle`.`Product_ID`=? AND `product_bottle`.`Date_Deleted` IS NULL");
        $stmt->bind_param("s", $productID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultDescriptionEnUs, $resultDescriptionZhHant, $resultQtyInStock, $resultBottleCapacity, $resultPrice, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleaseDate);
        
        $stmt->fetch();
        $bottle->setProductID($resultProductID);
        $bottle->setProductNameEnUs($resultProductNameEnUs);
        $bottle->setProductNameZhHant($resultProductNameZhHant);
        $bottle->setDescriptionEnUs($resultDescriptionEnUs);
        $bottle->setDescriptionZhHant($resultDescriptionZhHant);
        $bottle->setQtyInStock($resultQtyInStock);
        $bottle->setBottleCapacity($resultBottleCapacity);
        $bottle->setOriginalPrice($resultPrice);
        $bottle->setDiscount($resultDiscount);
        $bottle->setDiscountTitleEnUs($resultDiscountTitleEnUs);
        $bottle->setDescriptionZhHant($resultDiscountTitleZhHant);
        $bottle->setReleaseDate(strtotime($resultReleaseDate));

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $bottle;
    }

    public function getOneBottlePhotoByID($productID, $conn) {

        $stmt = $conn->prepare("SELECT `Photo` FROM `product_bottle` WHERE `Product_ID`=?");
        $stmt->bind_param("s", $productID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultPhotoBlob);
        
        $stmt->fetch();
        $bottlePhoto = $resultPhotoBlob;

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $bottlePhoto;
    }
    
    
}