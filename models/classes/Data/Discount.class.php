<?php
namespace Data;

class Discount {

    private $discountCode;
    private $discount;
    private $titleEnUS;
    private $titleZhHant;
    
    function getDiscountCode() {
        return $this->discountCode;
    }

    function getDiscount() {
        return $this->discount;
    }

    function getTitleEnUS() {
        return $this->titleEnUS;
    }

    function getTitleZhHant() {
        return $this->titleZhHant;
    }

    function setDiscountCode($discountCode) {
        $this->discountCode = $discountCode;
    }

    function setDiscount($discount) {
        $this->discount = $discount;
    }

    function setTitleEnUS($titleEnUS) {
        $this->titleEnUS = $titleEnUS;
    }

    function setTitleZhHant($titleZhHant) {
        $this->titleZhHant = $titleZhHant;
    }


}
