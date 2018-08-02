<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\ProductPackageDA;
use DataAccess\ProductBottleDA;
use DataAccess\ProductPerfumeDA;

try {
    $data['discount'] = array();
    
    $packageDA = new ProductPackageDA();
    $allPackages = $packageDA->getAllPackages(Database::getConnection(), true);
    if (count($allPackages)>0) {
        $packageRand = rand(0,count($allPackages)-1);
        $package['productID'] = $allPackages[$packageRand]->getProductID();
        $package['productName'] = $allPackages[$packageRand]->getProductNameEnUs();
        $package['stockEnough'] = $allPackages[$packageRand]->getStockEnough();
        $package['category'] = "package";
        $package['originalPrice'] = $allPackages[$packageRand]->getOriginalPrice();
        $package['discount'] = $allPackages[$packageRand]->getDiscount();
        $package['discountTitle'] = $allPackages[$packageRand]->getDiscountTitleEnUs();
        $package['nowPrice'] = $allPackages[$packageRand]->getNowPrice();
        $package['qtyInStock'] = $allPackages[$packageRand]->getQtyInStock();
        $package['releaseDate'] = $allPackages[$packageRand]->getReleaseDate();
        array_push($data['discount'], $package);
    }
    
    $bottleDA = new ProductBottleDA();
    $allBottles = $bottleDA->getAllBottles(Database::getConnection(), true);
    if (count($allBottles)>0){
        $bottleRand = rand(0,count($allBottles)-1);
        $bottle['productID'] = $allBottles[$bottleRand]->getProductID();
        $bottle['productName'] = $allBottles[$bottleRand]->getProductNameEnUs();
        $bottle['stockEnough'] = $allBottles[$bottleRand]->getStockEnough();
        $bottle['category'] = "bottle";
        $bottle['originalPrice'] = $allBottles[$bottleRand]->getOriginalPrice();
        $bottle['discount'] = $allBottles[$bottleRand]->getDiscount();
        $bottle['discountTitle'] = $allBottles[$bottleRand]->getDiscountTitleEnUs();
        $bottle['nowPrice'] = $allBottles[$bottleRand]->getNowPrice();
        $bottle['qtyInStock'] = $allBottles[$bottleRand]->getQtyInStock();
        $bottle['releaseDate'] = $allBottles[$bottleRand]->getReleaseDate();
        array_push($data['discount'], $bottle);
    }

    $perfumeDA = new ProductPerfumeDA();
    $allPerfume = $perfumeDA->getAllPerfume(Database::getConnection(), true);
    if(count($allPerfume)>0){
        $discountPerfumeRand = array_rand($allPerfume, (count($allPerfume)>2)?2:count($allPerfume));
        if (is_array($discountPerfumeRand) || is_object($discountPerfumeRand)){
            foreach ($discountPerfumeRand as &$value) {
                $perfume['productID'] = $allPerfume[$value]->getProductID();
                $perfume['productName'] = $allPerfume[$value]->getProductNameEnUs();
                $perfume['category'] = "perfume";
                $perfume['perfumeCategoryName'] = $allPerfume[$value]->getCategoryNameEnUs();
                $perfume['stockEnough'] = $allPerfume[$value]->getStockEnough();
                $perfume['originalPrice'] = $allPerfume[$value]->getOriginalPrice();
                $perfume['discount'] = $allPerfume[$value]->getDiscount();
                $perfume['discountTitle'] = $allPerfume[$value]->getDiscountTitleEnUs();
                $perfume['nowPrice'] = $allPerfume[$value]->getNowPrice();
                $perfume['qtyInStock'] = ($allPerfume[$value]->getQtyInStock()>=50)?$allPerfume[$value]->getQtyInStock():0;
                $perfume['releaseDate'] = $allPerfume[$value]->getReleaseDate();
                array_push($data['discount'], $perfume);
            }        
        } else {
            $perfume['productID'] = $allPerfume[$discountPerfumeRand]->getProductID();
            $perfume['productName'] = $allPerfume[$discountPerfumeRand]->getProductNameEnUs();
            $perfume['category'] = "perfume";
            $perfume['perfumeCategoryName'] = $allPerfume[$discountPerfumeRand]->getCategoryNameEnUs();
            $perfume['stockEnough'] = $allPerfume[$discountPerfumeRand]->getStockEnough();
            $perfume['originalPrice'] = $allPerfume[$discountPerfumeRand]->getOriginalPrice();
            $perfume['discount'] = $allPerfume[$discountPerfumeRand]->getDiscount();
            $perfume['nowPrice'] = $allPerfume[$discountPerfumeRand]->getNowPrice();
            $perfume['discountTitle'] = $allPerfume[$discountPerfumeRand]->getDiscountTitleEnUs();
            $perfume['qtyInStock'] = ($allPerfume[$discountPerfumeRand]->getQtyInStock()>=50)?$allPerfume[$value]->getQtyInStock():0;
            $perfume['releaseDate'] = $allPerfume[$discountPerfumeRand]->getReleaseDate();
            array_push($data['discount'], $perfume);
        }        
    }

    
} catch (mysqli_sql_exception $ex) {
    $data['databaseError'] = Database::getDbErrorMsg();
}

if (isset($data))
    echo json_encode($data);