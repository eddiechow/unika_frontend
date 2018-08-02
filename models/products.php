<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\ProductPackageDA;
use DataAccess\ProductBottleDA;
use DataAccess\ProductPerfumeDA;
use DataAccess\PerfumeCategoryDA;

try {
    if (!empty($_GET['category'])) {

        $data['products'] = array();
        

        if ($_GET['category'] == "package") {

            $productDA = new ProductPackageDA();
            $allPackages = $productDA->getAllPackages(Database::getConnection());

            foreach ($allPackages as &$value) {
                $package['productID'] = $value->getProductID();
                $package['productName'] = $value->getProductNameEnUs();
                $package['category'] = "package";
                $package['stockEnough'] = $value->getStockEnough();
                $package['originalPrice'] = $value->getOriginalPrice();
                $package['discount'] = $value->getDiscount();
                $package['discountTitle'] = $value->getDiscountTitleEnUs();
                $package['nowPrice'] = $value->getNowPrice();
                $package['qtyInStock'] = $value->getQtyInStock();
                $package['releaseDate'] = $value->getReleaseDate();
                array_push($data['products'], $package);
            }

            $data['categoryName'] = "Package";
        } else if ($_GET['category'] == "bottle") {

            $productDA = new ProductBottleDA();
            $allBottles = $productDA->getAllBottles(Database::getConnection());

            foreach ($allBottles as &$value) {
                $bottle['productID'] = $value->getProductID();
                $bottle['productName'] = $value->getProductNameEnUs();
                $bottle['category'] = "bottle";
                $bottle['stockEnough'] = $value->getStockEnough();
                $bottle['originalPrice'] = $value->getOriginalPrice();
                $bottle['discount'] = $value->getDiscount();
                $bottle['discountTitle'] = $value->getDiscountTitleEnUs();
                $bottle['nowPrice'] = $value->getNowPrice();
                $bottle['bottleCapacity'] = $value->getBottleCapacity();
                $bottle['qtyInStock'] = $value->getQtyInStock();
                $bottle['releaseDate'] = $value->getReleaseDate();
                array_push($data['products'], $bottle);
            }

            $data['categoryName'] = "Bottle";
        } else if ($_GET['category'] == "perfume") {

            $productDA = new ProductPerfumeDA();

            if(!empty($_GET['perfumeCategory'])){
                $allPerfume = $productDA->getPerfumeByCategory(xssFilter($_GET['perfumeCategory']), Database::getConnection());
                
                $perfumeCategoryDA = new PerfumeCategoryDA();
                $perfumeCategory = $perfumeCategoryDA->getOnePerfumeCategories($_GET['perfumeCategory'], Database::getConnection());
                
                if($perfumeCategory->getPerfumeCategoryCode()){
                    foreach ($allPerfume as &$value) {
                        $perfume['productID'] = $value->getProductID();
                        $perfume['productName'] = $value->getProductNameEnUs();
                        $perfume['category'] = "perfume";
                        $perfume['stockEnough'] = $value->getStockEnough();
                        $perfume['originalPrice'] = $value->getOriginalPrice();
                        $perfume['discount'] = $value->getDiscount();
                        $perfume['discountTitle'] = $value->getDiscountTitleEnUs();
                        $perfume['nowPrice'] = $value->getNowPrice();
                        $perfume['qtyInStock'] = ($value->getQtyInStock()>=50)?$value->getQtyInStock():0;
                        $perfume['releaseDate'] = $value->getReleaseDate();
                        array_push($data['products'], $perfume);
                    }

                    $data['categoryName'] = "Perfume";
                    $data['perfumeCategoryCode'] = $perfumeCategory->getPerfumeCategoryCode();
                    $data['perfumeCategoryName'] = $perfumeCategory->getCategoryNameEnUs();
                } else {
                    $data = null;
                }
            } else {
                $allPerfume = $productDA->getAllPerfume(Database::getConnection());

                foreach ($allPerfume as &$value) {
                    $perfume['productID'] = $value->getProductID();
                    $perfume['productName'] = $value->getProductNameEnUs();
                    $perfume['perfumeCategoryName'] = $value->getCategoryNameEnUs();
                    $perfume['category'] = "perfume";
                    $perfume['stockEnough'] = $value->getStockEnough();
                    $perfume['originalPrice'] = $value->getOriginalPrice();
                    $perfume['discount'] = $value->getDiscount();
                    $perfume['discountTitle'] = $value->getDiscountTitleEnUs();
                    $perfume['nowPrice'] = $value->getNowPrice();
                    $perfume['qtyInStock'] = ($value->getQtyInStock()>=50)?$value->getQtyInStock():0;
                    $perfume['releaseDate'] = $value->getReleaseDate();
                    array_push($data['products'], $perfume);
                }

                $data['categoryName'] = "Perfume";
            }

        } else {
            $data = null;
        }
    } else {
        $data = null;
    }
    
} catch (mysqli_sql_exception $ex) {
    $data['databaseError'] = Database::getDbErrorMsg();
}

if (isset($data))
    echo json_encode($data);
