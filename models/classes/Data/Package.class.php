<?php

namespace Data;

class Package extends Product {

    function getProductID() {
        return $this->productID;
    }

    function getProductNameEnUs() {
        return $this->productNameEnUs;
    }

    function getProductNameZhHant() {
        return $this->productNameZhHant;
    }

    function getDescriptionEnUs() {
        return $this->descriptionEnUs;
    }

    function getDescriptionZhHant() {
        return $this->descriptionZhHant;
    }

    function getQtyInStock() {
        return $this->qtyInStock;
    }
    
    function getStockEnough() {
        return ($this->getQtyInStock() > 0);
    }

    function getOriginalPrice() {
        return $this->originalPrice;
    }

    function getDiscount() {
        return $this->discount;
    }

    function getDiscountTitleEnUs() {
        return $this->discountTitleEnUs;
    }

    function getDiscountTitleZhHant() {
        return $this->discountTitleZhHant;
    }

    function getReleaseDate() {
        return $this->releaseDate;
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

    function setDescriptionEnUs($descriptionEnUs) {
        $this->descriptionEnUs = $descriptionEnUs;
    }

    function setDescriptionZhHant($descriptionZhHant) {
        $this->descriptionZhHant = $descriptionZhHant;
    }

    function setQtyInStock($qtyInStock) {
        $this->qtyInStock = $qtyInStock;
    }

    function setOriginalPrice($originalPrice) {
        $this->originalPrice = $originalPrice;
    }

    function setDiscount($discount) {
        $this->discount = $discount;
    }

    function setDiscountTitleEnUs($discountTitleEnUs) {
        $this->discountTitleEnUs = $discountTitleEnUs;
    }

    function setDiscountTitleZhHant($discountTitleEnUs) {
        $this->discountTitleZhHant = $discountTitleEnUs;
    }

    function setReleaseDate($releaseDate) {
        $this->releaseDate = $releaseDate;
    }

}
