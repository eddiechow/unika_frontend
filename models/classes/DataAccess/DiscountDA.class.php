<?php

namespace DataAccess;

use Data\Discount;

class DiscountDA {
    
    public function getAllDiscounts($conn) {
        $list = array();

        $stmt = $conn->prepare("SELECT `Discount_Code`, `Discount`, `Title_en-US`, `Title_zh-Hant` FROM `discount` WHERE `discount`.`Start_Date`<=NOW() AND `discount`.`End_Date`>=NOW() AND `Date_Deleted` IS NULL");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultDiscountCode, $resultDiscount, $resultTitleEnUs, $resultTitleZhHant);

        while ($stmt->fetch()) {

            $discount = new Discount();

            $discount->setDiscountCode($resultDiscountCode);
            $discount->setDiscount($resultDiscount);
            $discount->setTitleEnUS($resultTitleEnUs);
            $discount->setTitleZhHant($resultTitleZhHant);

            array_push($list, $discount);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }
    
    public function getOneDiscount($discountCode, $conn) {

        $stmt = $conn->prepare("SELECT `Discount_Code`, `Discount`, `Title_en-US`, `Title_zh-Hant` FROM `discount` WHERE `Discount_Code`=? AND `Start_Date`<=NOW() AND `End_Date`>=NOW() AND `Date_Deleted` IS NULL");
        $stmt->bind_param("s", $discountCode);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultDiscountCode, $resultDiscount, $resultTitleEnUs, $resultTitleZhHant);
        $stmt->fetch();
        
        $discount = new Discount();
        $discount->setDiscountCode($resultDiscountCode);
        $discount->setDiscount($resultDiscount);
        $discount->setTitleEnUS($resultTitleEnUs);
        $discount->setTitleZhHant($resultTitleZhHant);


        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $discount;
    }
    
}
