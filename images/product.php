<?php
require '../models/global.php';

use DataAccess\ProductPackageDA;
use DataAccess\ProductBottleDA;
use DataAccess\ProductPerfumeDA;

if($_GET['category'] == "package"){
    
    $productPerfumeDA = new ProductPackageDA();
    $image = $productPerfumeDA->getOnePackagePhotoByID(xssFilter($_GET["productID"]), Database::getConnection());

    if(!empty($image)){
        header('Content-Type: image/jpeg');
        echo $image;
    } else {
        header("Location: ..");
    }
    
} else if($_GET['category'] == "bottle") {
    
    $productPerfumeDA = new ProductBottleDA();
    $image = $productPerfumeDA->getOneBottlePhotoByID(xssFilter($_GET["productID"]), Database::getConnection());

    if(!empty($image)){
        header('Content-Type: image/jpeg');
        echo $image;
    } else {
        header("Location: ..");
    }
    
} else if($_GET['category'] == "perfume") {
 
    $productPerfumeDA = new ProductPerfumeDA();
    $image = $productPerfumeDA->getOnePerfumePhotoByID(xssFilter($_GET["productID"]), Database::getConnection());

    if(!empty($image)){
        header('Content-Type: image/jpeg');
        echo $image;
    } else {
        header("Location: ..");
    }
    
} 