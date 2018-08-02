<?php

namespace Data;

class Perfume extends Product {

    private $perfumeCategory;

    function __construct() {
        $this->perfumeCategory = new PerfumeCategory();
    }

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

    function getPerfumeCategoryCode() {
        return $this->perfumeCategory->getPerfumeCategoryCode();
    }

    function getCategoryNameEnUs() {
        return $this->perfumeCategory->getCategoryNameEnUs();
    }

    function getCategoryNameZhHant() {
        return $this->perfumeCategory->getCategoryNameZhHant();
    }

    function getQtyInStock() {
        return $this->qtyInStock;
    }
    
    function getStockEnough() {
        return ($this->getQtyInStock() >= 50);
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

    function setPerfumeCategoryCode($perfumeCategoryCode) {
        $this->perfumeCategory->setPerfumeCategoryCode($perfumeCategoryCode);
    }

    function setCategoryNameEnUs($categoryNameEnUs) {
        $this->perfumeCategory->setCategoryNameEnUs($categoryNameEnUs);
    }

    function setCategoryNameZhHant($categoryNameZhHant) {
        $this->perfumeCategory->setCategoryNameZhHant($categoryNameZhHant);
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
