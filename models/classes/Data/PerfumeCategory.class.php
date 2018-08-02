<?php
namespace Data;

class PerfumeCategory {
    
    private $perfumeCategoryCode;
    private $categoryNameEnUs;
    private $categoryNameZhHant;
    
    function getPerfumeCategoryCode() {
        return $this->perfumeCategoryCode;
    }

    function getCategoryNameEnUs() {
        return $this->categoryNameEnUs;
    }

    function getCategoryNameZhHant() {
        return $this->categoryNameZhHant;
    }

    function setPerfumeCategoryCode($perfumeCategoryCode) {
        $this->perfumeCategoryCode = $perfumeCategoryCode;
    }

    function setCategoryNameEnUs($categoryNameEnUs) {
        $this->categoryNameEnUs = $categoryNameEnUs;
    }

    function setCategoryNameZhHant($categoryNameZhHant) {
        $this->categoryNameZhHant = $categoryNameZhHant;
    }
    
}
