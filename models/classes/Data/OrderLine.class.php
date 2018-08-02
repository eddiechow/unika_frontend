<?php
namespace Data;

class OrderLine {

    private $orderID;
    private $productID;
    private $productNameEnUs;
    private $productNameZhHant;
    private $titleEnUs;
    private $titleZhHant;
    private $dicount = null;
    private $price;
    private $perfumeCategoryNameEnUs;
    private $perfumeCategoryNameZhHant;
    private $note;
    private $bottleCapacity;
    private $quantity;
    
    function __construct($orderID, $productID, $price) {
        $this->orderID = $orderID;
        $this->productID = $productID;
        $this->price = $price;
    }

    function getOrderID() {
        return $this->orderID;
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

    function getTitleEnUs() {
        return $this->titleEnUs;
    }

    function getTitleZhHant() {
        return $this->titleZhHant;
    }

    function getDicount() {
        return $this->dicount;
    }

    function getPrice() {
        return $this->price;
    }
    
    function getNowPrice() {
        return ($this->dicount!=null)? $this->getPrice() * (1-($this->getDicount() * 0.01)): $this->getPrice();
    }

    function getPerfumeCategoryNameEnUs() {
        return $this->perfumeCategoryNameEnUs;
    }

    function getPerfumeCategoryNameZhHant() {
        return $this->perfumeCategoryNameZhHant;
    }

    function getNote() {
        return $this->note;
    }

    function getBottleCapacity() {
        return $this->bottleCapacity;
    }

    function getQuantity() {
        return $this->quantity;
    }
    
    function getSubTotal() {
        return $this->getQuantity() * $this->getNowPrice();
    }

    function setProductNameEnUs($productNameEnUs) {
        $this->productNameEnUs = $productNameEnUs;
    }

    function setProductNameZhHant($productNameZhHant) {
        $this->productNameZhHant = $productNameZhHant;
    }

    function setTitleEnUs($titleEnUs) {
        $this->titleEnUs = $titleEnUs;
    }

    function setTitleZhHant($titleZhHant) {
        $this->titleZhHant = $titleZhHant;
    }

    function setDicount($dicount) {
        $this->dicount = $dicount;
    }

    function setPerfumeCategoryNameEnUs($perfumeCategoryNameEnUs) {
        $this->perfumeCategoryNameEnUs = $perfumeCategoryNameEnUs;
    }

    function setPerfumeCategoryNameZhHant($perfumeCategoryNameZhHant) {
        $this->perfumeCategoryNameZhHant = $perfumeCategoryNameZhHant;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function setBottleCapacity($bottleCapacity) {
        $this->bottleCapacity = $bottleCapacity;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }


}
