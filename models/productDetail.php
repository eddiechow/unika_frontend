<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\ProductPackageDA;
use DataAccess\ProductBottleDA;
use DataAccess\ProductPerfumeDA;

try {
    if (!empty($_GET['category'])) {

        if ($_GET['category'] == "package") {

            $productDA = new ProductPackageDA();
            $package = $productDA->getOnePackageByID($_GET['productID'], Database::getConnection());

            if($package->getProductID()){
                $data['productID'] = $package->getProductID();
                $data['productNameEnUs'] = $package->getProductNameEnUs();
                $data['productNameZhHant'] = $package->getProductNameZhHant();
                $data['category'] = $_GET['category'];
                $data['descriptionEnUs'] = $package->getDescriptionEnUs();
                $data['descriptionZhHant'] = $package->getDescriptionZhHant();
                $data['originalPrice'] = $package->getOriginalPrice();
                $data['discount'] = $package->getDiscount();
                $data['nowPrice'] = $package->getNowPrice();
                $data['qtyInStock'] = $package->getQtyInStock();
                $data['releaseDate'] = $package->getReleaseDate();
            } else {
                $data = null;
            }

        } else if ($_GET['category'] == "bottle") {

            $productDA = new ProductBottleDA();
            $bottle = $productDA->getOneBottleByID($_GET['productID'], Database::getConnection());

            if($bottle->getProductID()){
                $data['productID'] = $bottle->getProductID();
                $data['productNameEnUs'] = $bottle->getProductNameEnUs();
                $data['productNameZhHant'] = $bottle->getProductNameZhHant();
                $data['category'] = $_GET['category'];
                $data['descriptionEnUs'] = $bottle->getDescriptionEnUs();
                $data['descriptionZhHant'] = $bottle->getDescriptionZhHant();
                $data['bottleCapacity'] = $bottle->getBottleCapacity();
                $data['originalPrice'] = $bottle->getOriginalPrice();
                $data['discount'] = $bottle->getDiscount();
                $data['nowPrice'] = $bottle->getNowPrice();
                $data['qtyInStock'] = $bottle->getQtyInStock();
                $data['releaseDate'] = $bottle->getReleaseDate();
            } else {
                $data = null;
            }

        } else if ($_GET['category'] == "perfume") {
            //"AS330093"
            $productDA = new ProductPerfumeDA();
            $perfume = $productDA->getOnePerfumeByID($_GET['productID'], Database::getConnection());

            if($perfume->getProductID()){
                $data['productID'] = $perfume->getProductID();
                $data['productNameEnUs'] = $perfume->getProductNameEnUs();
                $data['productNameZhHant'] = $perfume->getProductNameZhHant();
                $data['category'] = $_GET['category'];
                $data['descriptionEnUs'] = $perfume->getDescriptionEnUs();
                $data['descriptionZhHant'] = $perfume->getDescriptionZhHant();
                $data['perfumeCategoryCode'] = $perfume->getPerfumeCategoryCode();
                $data['categoryNameEnUs'] = $perfume->getCategoryNameEnUs();
                $data['categoryNameZhHant'] = $perfume->getCategoryNameZhHant();
                $data['originalPrice'] = $perfume->getOriginalPrice();
                $data['discount'] = $perfume->getDiscount();
                $data['nowPrice'] = $perfume->getNowPrice();
                $data['qtyInStock'] = ($perfume->getQtyInStock()>=50)?$perfume->getQtyInStock():0;
                $data['releaseDate'] = $perfume->getReleaseDate();
            } else {
                $data = null;
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
