<?php
namespace Data;

class Product {

    protected $productID;
    protected $productNameEnUs;
    protected $productNameZhHant;
    protected $descriptionEnUs;
    protected $descriptionZhHant;
    protected $qtyInStock;
    protected $originalPrice;
    protected $discount = null;
    protected $discountTitleEnUs = null;
    protected $discountTitleZhHant = null;
    protected $releaseDate;
    
    function getNowPrice() {
        return ($this->discount != null) ? $this->originalPrice * (1-($this->discount * 0.01)) : $this->originalPrice;
    }
}
