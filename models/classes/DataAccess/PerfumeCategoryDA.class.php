<?php

namespace DataAccess;

use Data\PerfumeCategory;

class PerfumeCategoryDA {
    
    public function getAllPerfumeCategories($conn) {

        $list = array();

        $stmt = $conn->prepare("SELECT * FROM `product_perfume_category`");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultPerfumeCategoryCode, $resultCategoryNameEnUs, $resultCategoryNameZhHant);

        while ($stmt->fetch()) {
            $perfumeCategory = new PerfumeCategory();
            $perfumeCategory->setPerfumeCategoryCode($resultPerfumeCategoryCode);
            $perfumeCategory->setCategoryNameEnUs($resultCategoryNameEnUs);
            $perfumeCategory->setCategoryNameZhHant($resultCategoryNameZhHant);
            array_push($list, $perfumeCategory);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }
    
    public function getOnePerfumeCategories($categoryCode, $conn) {

        $perfumeCategory = new PerfumeCategory();

        $stmt = $conn->prepare("SELECT * FROM `product_perfume_category` WHERE `Perfume_Category_Code`=?");
        $stmt->bind_param("s", $categoryCode);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultPerfumeCategoryCode, $resultCategoryNameEnUs, $resultCategoryNameZhHant);

        $stmt->fetch();
        $perfumeCategory->setPerfumeCategoryCode($resultPerfumeCategoryCode);
        $perfumeCategory->setCategoryNameEnUs($resultCategoryNameEnUs);
        $perfumeCategory->setCategoryNameZhHant($resultCategoryNameZhHant);

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $perfumeCategory;
    }
    
    
}
