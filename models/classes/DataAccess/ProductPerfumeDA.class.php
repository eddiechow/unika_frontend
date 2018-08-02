<?php

namespace DataAccess;

use Data\Perfume;

class ProductPerfumeDA {

    public function getAllPerfume($conn, $discountOnly=false) {

        $list = array();

        $stmt = $conn->prepare("SELECT `product_perfume`.`Product_ID`, `product_perfume`.`Product_Name_en-US`, `product_perfume`.`Product_Name_zh-Hant`, `product_perfume_category`.`Category_Name_en-US`, `product_perfume_category`.`Category_Name_zh-Hant`, `product_perfume`.`Qty_In_Stock`, `product_perfume`.`Price`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_perfume`.`Release_Date` "
                . "FROM `product_perfume` "
                . "INNER JOIN `product_perfume_category` USING(`Perfume_Category_Code`) "
                . "LEFT JOIN `product_perfume_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL AND `product_perfume_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `product_perfume`.`Date_Deleted` IS NULL".(($discountOnly)?" AND `product_perfume_discount`.`Discount_Code` IS NOT NULL AND `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL":""));
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultCategoryNameEnUs, $resultCategoryNameZhHant, $resultQtyInStock, $resultPrice, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleasDate);
        
        while ($stmt->fetch()) {
            $perfume = new Perfume();
            $perfume->setProductID($resultProductID);
            $perfume->setProductNameEnUs($resultProductNameEnUs);
            $perfume->setProductNameZhHant($resultProductNameZhHant);
            $perfume->setCategoryNameEnUs($resultCategoryNameEnUs);
            $perfume->setCategoryNameZhHant($resultCategoryNameZhHant);
            $perfume->setQtyInStock($resultQtyInStock);
            $perfume->setOriginalPrice($resultPrice);
            $perfume->setDiscount($resultDiscount);
            $perfume->setDiscountTitleEnUs($resultDiscountTitleEnUs);
            $perfume->setDescriptionZhHant($resultDiscountTitleZhHant);
            $perfume->setReleaseDate(strtotime($resultReleasDate));
            array_push($list, $perfume);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }
    
    public function getAllDiscountPerfume($discountCode, $conn) {

        $list = array();

        $stmt = $conn->prepare("SELECT `product_perfume`.`Product_ID`, `product_perfume`.`Product_Name_en-US`, `product_perfume`.`Product_Name_zh-Hant`, `product_perfume_category`.`Perfume_Category_Code`, `product_perfume_category`.`Category_Name_en-US`, `product_perfume_category`.`Category_Name_zh-Hant`, `product_perfume`.`Qty_In_Stock`, `product_perfume`.`Price`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_perfume`.`Release_Date` "
                . "FROM `product_perfume` "
                . "INNER JOIN `product_perfume_category` USING(`Perfume_Category_Code`) "
                . "LEFT JOIN `product_perfume_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL AND `product_perfume_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `discount`.`Discount_Code`=? AND `product_perfume`.`Date_Deleted` IS NULL AND `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL");
        $stmt->bind_param("s", $discountCode);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultCategoryCode, $resultCategoryNameEnUs, $resultCategoryNameZhHant, $resultQtyInStock, $resultPrice, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleasDate);
        
        while ($stmt->fetch()) {
            $perfume = new Perfume();
            $perfume->setProductID($resultProductID);
            $perfume->setProductNameEnUs($resultProductNameEnUs);
            $perfume->setProductNameZhHant($resultProductNameZhHant);
            $perfume->setPerfumeCategoryCode($resultCategoryCode);
            $perfume->setCategoryNameEnUs($resultCategoryNameEnUs);
            $perfume->setCategoryNameZhHant($resultCategoryNameZhHant);
            $perfume->setQtyInStock($resultQtyInStock);
            $perfume->setOriginalPrice($resultPrice);
            $perfume->setDiscount($resultDiscount);
            $perfume->setDiscountTitleEnUs($resultDiscountTitleEnUs);
            $perfume->setDescriptionZhHant($resultDiscountTitleZhHant);
            $perfume->setReleaseDate(strtotime($resultReleasDate));
            array_push($list, $perfume);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }
    
    public function getPerfumeByCategory($categoryCode, $conn) {

        $list = array();

        $stmt = $conn->prepare("SELECT `product_perfume`.`Product_ID`, `product_perfume`.`Product_Name_en-US`, `product_perfume`.`Product_Name_zh-Hant`, `product_perfume_category`.`Category_Name_en-US`, `product_perfume_category`.`Category_Name_zh-Hant`, `product_perfume`.`Qty_In_Stock`, `product_perfume`.`Price`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_perfume`.`Release_Date` "
                . "FROM `product_perfume` "
                . "INNER JOIN `product_perfume_category` USING(`Perfume_Category_Code`) "
                . "LEFT JOIN `product_perfume_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL AND `product_perfume_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `product_perfume`.`Perfume_Category_Code`=?  AND `product_perfume`.`Date_Deleted` IS NULL");
        $stmt->bind_param("s", $categoryCode);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultCategoryNameEnUs, $resultCategoryNameZhHant, $resultQtyInStock, $resultPrice, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleasDate);
        
        while ($stmt->fetch()) {
            $perfume = new Perfume();
            $perfume->setProductID($resultProductID);
            $perfume->setProductNameEnUs($resultProductNameEnUs);
            $perfume->setProductNameZhHant($resultProductNameZhHant);
            $perfume->setCategoryNameEnUs($resultCategoryNameEnUs);
            $perfume->setCategoryNameZhHant($resultCategoryNameZhHant);
            $perfume->setQtyInStock($resultQtyInStock);
            $perfume->setOriginalPrice($resultPrice);
            $perfume->setDiscount($resultDiscount);
            $perfume->setDiscountTitleEnUs($resultDiscountTitleEnUs);
            $perfume->setDescriptionZhHant($resultDiscountTitleZhHant);
            $perfume->setReleaseDate(strtotime($resultReleasDate));
            array_push($list, $perfume);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }

    public function getOnePerfumeByID($productID, $conn) {

        $perfume = new Perfume();

        $stmt = $conn->prepare("SELECT `product_perfume`.`Product_ID`, `product_perfume`.`Product_Name_en-US`, `product_perfume`.`Product_Name_zh-Hant`, `product_perfume`.`Description_en-US`, `product_perfume`.`Description_zh-Hant`, `product_perfume_category`.`Perfume_Category_Code`, `product_perfume_category`.`Category_Name_en-US`, `product_perfume_category`.`Category_Name_zh-Hant`, `product_perfume`.`Qty_In_Stock`, `product_perfume`.`Price`, `discount`.`Discount`, `discount`.`Title_en-US`, `discount`.`Title_zh-Hant`, `product_perfume`.`Release_Date` "
                . "FROM `product_perfume` "
                . "INNER JOIN `product_perfume_category` USING(`Perfume_Category_Code`) "
                . "LEFT JOIN `product_perfume_discount` USING(`Product_ID`) "
                . "LEFT JOIN `discount` ON `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `discount`.`Date_Deleted` IS NULL AND `product_perfume_discount`.`Discount_Code`=`discount`.`Discount_Code` "
                . "WHERE `product_perfume`.`Product_ID`=? AND `product_perfume`.`Date_Deleted` IS NULL");
        $stmt->bind_param("s", $productID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultProductID, $resultProductNameEnUs, $resultProductNameZhHant, $resultDescriptionEnNameEnUs, $resultDescriptionNameZhHant, $resultCategoryCode, $resultCategoryNameEnUs, $resultCategoryNameZhHant, $resultQtyInStock, $resultPrice, $resultDiscount, $resultDiscountTitleEnUs, $resultDiscountTitleZhHant, $resultReleasDate);
        
        $stmt->fetch();
        
        $perfume->setProductID($resultProductID);
        $perfume->setProductNameEnUs($resultProductNameEnUs);
        $perfume->setProductNameZhHant($resultProductNameZhHant);
        $perfume->setDescriptionEnUs($resultDescriptionEnNameEnUs);
        $perfume->setDescriptionZhHant($resultDescriptionNameZhHant);
        $perfume->setPerfumeCategoryCode($resultCategoryCode);
        $perfume->setCategoryNameEnUs($resultCategoryNameEnUs);
        $perfume->setCategoryNameZhHant($resultCategoryNameZhHant);
        $perfume->setQtyInStock($resultQtyInStock);
        $perfume->setOriginalPrice($resultPrice);
        $perfume->setDiscount($resultDiscount);
        $perfume->setDiscountTitleEnUs($resultDiscountTitleEnUs);
        $perfume->setDescriptionZhHant($resultDiscountTitleZhHant);

        $perfume->setReleaseDate(strtotime($resultReleasDate));

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $perfume;
    }

    public function getOnePerfumePhotoByID($productID, $conn) {

        $stmt = $conn->prepare("SELECT `Photo` FROM `product_perfume` WHERE `Product_ID`=?");
        $stmt->bind_param("s", $productID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultPhotoBlob);
        
        $stmt->fetch();
        $perfumePhoto = $resultPhotoBlob;

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $perfumePhoto;
    }
}