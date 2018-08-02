<?php

namespace DataAccess;

use Data\Package;

class ProductPackageDA {

    public function getAllPackages($conn, $discountOnly=false) {
        $list = array();
        $stmt = $conn->prepare("SELECT `product_package`.`Product_ID`, `product_package`.`Product_Name_en-US`, `product_package`.`Product_Name_zh-Hant`, `product_package`.`Qty_In_Stock`, `product_package`.`Price`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_package`.`Release_Date` "
                . "FROM `product_package` "
                . "LEFT JOIN `product_package_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL AND `product_package_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `product_package`.`Date_Deleted` IS NULL".(($discountOnly)?" AND `product_package_discount`.`Discount_Code` IS NOT NULL AND `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL":""));
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultQtyInStock, $resultPrice, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleaseDate);
        
        while ($stmt->fetch()) {
            $package = new Package();
            $package->setProductID($resultProductID);
            $package->setProductNameEnUs($resultProductNameEnUs);
            $package->setProductNameZhHant($resultProductNameZhHant);
            $package->setQtyInStock($resultQtyInStock);
            $package->setOriginalPrice($resultPrice);
            $package->setDiscount($resultDiscount);
            $package->setDiscountTitleEnUs($resultDiscountTitleEnUs);
            $package->setDescriptionZhHant($resultDiscountTitleZhHant);
            $package->setReleaseDate(strtotime($resultReleaseDate));
            array_push($list, $package);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }
    
    public function getAllDiscountPackages($discountCode, $conn) {
        $list = array();
        $stmt = $conn->prepare("SELECT `product_package`.`Product_ID`, `product_package`.`Product_Name_en-US`, `product_package`.`Product_Name_zh-Hant`, `product_package`.`Qty_In_Stock`, `product_package`.`Price`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_package`.`Release_Date` "
                . "FROM `product_package` "
                . "LEFT JOIN `product_package_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL AND `product_package_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `discount`.`Discount_Code`=? AND `product_package`.`Date_Deleted` IS NULL AND `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL");
        $stmt->bind_param("s", $discountCode);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultQtyInStock, $resultPrice, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleaseDate);
        
        while ($stmt->fetch()) {
            $package = new Package();
            $package->setProductID($resultProductID);
            $package->setProductNameEnUs($resultProductNameEnUs);
            $package->setProductNameZhHant($resultProductNameZhHant);
            $package->setQtyInStock($resultQtyInStock);
            $package->setOriginalPrice($resultPrice);
            $package->setDiscount($resultDiscount);
            $package->setDiscountTitleEnUs($resultDiscountTitleEnUs);
            $package->setDescriptionZhHant($resultDiscountTitleZhHant);
            $package->setReleaseDate(strtotime($resultReleaseDate));
            array_push($list, $package);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }

    public function getOnePackageByID($productID, $conn) {
        $package = new Package();

        $stmt = $conn->prepare("SELECT `product_package`.`Product_ID`, `product_package`.`Product_Name_en-US`, `product_package`.`Product_Name_zh-Hant`, `product_package`.`Description_en-US`, `product_package`.`Description_zh-Hant`, `product_package`.`Qty_In_Stock`, `product_package`.`Price`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_package`.`Release_Date` "
                . "FROM `product_package` "
                . "LEFT JOIN `product_package_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL "
                . "AND `product_package_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `product_package`.`Product_ID`=? AND `product_package`.`Date_Deleted` IS NULL");
        $stmt->bind_param("s", $productID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultDescriptionEnUs, $resultDescriptionZhHant, $resultQtyInStock, $resultPrice, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleaseDate);
        
        $stmt->fetch();
        $package->setProductID($resultProductID);
        $package->setProductNameEnUs($resultProductNameEnUs);
        $package->setProductNameZhHant($resultProductNameZhHant);
        $package->setDescriptionEnUs($resultDescriptionEnUs);
        $package->setDescriptionZhHant($resultDescriptionZhHant);
        $package->setQtyInStock($resultQtyInStock);
        $package->setOriginalPrice($resultPrice);
        $package->setDiscount($resultDiscount);
        $package->setDiscountTitleEnUs($resultDiscountTitleEnUs);
        $package->setDescriptionZhHant($resultDiscountTitleZhHant);
        $package->setReleaseDate(strtotime($resultReleaseDate));

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $package;
    }

    public function getOnePackagePhotoByID($productID, $conn) {

        $stmt = $conn->prepare("SELECT `Photo` FROM `product_package` WHERE `Product_ID`=?");
        $stmt->bind_param("s", $productID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultPhotoBlob);
        
        $stmt->fetch();
        $packagePhoto = $resultPhotoBlob;

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $packagePhoto;
    }

    
}