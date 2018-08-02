<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\DiscountDA;

use DataAccess\ProductPackageDA;
use DataAccess\ProductBottleDA;
use DataAccess\ProductPerfumeDA;


try {
    if(!empty($_GET['code'])){
        $dda = new DiscountDA();
        $discount = $dda->getOneDiscount($_GET['code'], Database::getConnection());
        if($discount->getDiscountCode()){
            
            $data['discountTitle'] = $discount->getTitleEnUS();
            $data['discount'] = $discount->getDiscount();
            
            $data['products'] = array();

            $packageDA = new ProductPackageDA();
            $allPackages = $packageDA->getAllDiscountPackages($_GET['code'], Database::getConnection());
            foreach ($allPackages as &$value) {
                $package['productID'] = $value->getProductID();
                $package['productName'] = $value->getProductNameEnUs();
                $package['category'] = "package";
                $package['categoryName'] = "Package";
                $package['stockEnough'] = $value->getStockEnough();
                $package['originalPrice'] = $value->getOriginalPrice();
                $package['discount'] = $value->getDiscount();
                $package['nowPrice'] = $value->getNowPrice();
                $package['qtyInStock'] = $value->getQtyInStock();
                $package['releaseDate'] = $value->getReleaseDate();
                array_push($data['products'], $package);
            }

            $bottleDA = new ProductBottleDA();
            $allBottles = $bottleDA->getAllDiscountBottles($_GET['code'], Database::getConnection());
            foreach ($allBottles as &$value) {
                $bottle['productID'] = $value->getProductID();
                $bottle['productName'] = $value->getProductNameEnUs();
                $bottle['category'] = "bottle";
                $bottle['categoryName'] = "Bottle";
                $bottle['stockEnough'] = $value->getStockEnough();
                $bottle['originalPrice'] = $value->getOriginalPrice();
                $bottle['discount'] = $value->getDiscount();
                $bottle['nowPrice'] = $value->getNowPrice();
                $bottle['bottleCapacity'] = $value->getBottleCapacity();
                $bottle['qtyInStock'] = $value->getQtyInStock();
                $bottle['releaseDate'] = $value->getReleaseDate();
                array_push($data['products'], $bottle);
            }

            $perfumeDA = new ProductPerfumeDA();
            $allPerfume = $perfumeDA->getAllDiscountPerfume($_GET['code'], Database::getConnection());
            foreach ($allPerfume as &$value) {
                $perfume['productID'] = $value->getProductID();
                $perfume['productName'] = $value->getProductNameEnUs();
                $perfume['category'] = "perfume";
                $perfume['categoryName'] = "Perfume";
                $perfume['perfumeCategoryCode'] = $value->getPerfumeCategoryCode();
                $perfume['perfumeCategoryName'] = $value->getCategoryNameEnUs();
                $perfume['stockEnough'] = $value->getStockEnough();
                $perfume['originalPrice'] = $value->getOriginalPrice();
                $perfume['discount'] = $value->getDiscount();
                $perfume['nowPrice'] = $value->getNowPrice();
                $perfume['qtyInStock'] = ($value->getQtyInStock()>=50)?$value->getQtyInStock():0;
                $perfume['releaseDate'] = $value->getReleaseDate();
                array_push($data['products'], $perfume);
            }               
        }
    
    }


    
} catch (mysqli_sql_exception $ex) {
    $data['databaseError'] = Database::getDbErrorMsg();
}

if (isset($data))
    echo json_encode($data);