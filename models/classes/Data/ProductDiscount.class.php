<?php
namespace Data;

class ProductDiscount {
    
    private $productID;
    private $productNameEnUs;
    private $productNameZhHant;
    private $price;
    private $discountCode;
    private $discount;

    function getProductID() {
        return $this->productID;
    }

    function getProductNameEnUs() {
        return $this->productNameEnUs;
    }

    function getProductNameZhHant() {
        return $this->productNameZhHant;
    }

    function getPrice() {
        return $this->price;
    }

    function getDiscountCode() {
        return $this->discountCode;
    }

    function getDiscount() {
        return $this->discount;
    }

    function setProductID($productID) {
        $this->productID = $productID;
    }

    function setProductNameEnUs($productNameEnUs) {
        $this->productNameEnUs = $productNameEnUs;
    }

    function setProductNameZhHant($productNameZhHant) {
        $this->productNameZhHant = $productNameZhHant;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setDiscountCode($discountCode) {
        $this->discountCode = $discountCode;
    }

    function setDiscount($discount) {
        $this->discount = $discount;
    }


}
