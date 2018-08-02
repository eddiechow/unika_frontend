<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\PackageShoppingCartDA;
use DataAccess\BottleShoppingCartDA;
use DataAccess\PerfumeShoppingCartDA;
use DataAccess\ProductPackageDA;
use DataAccess\ProductBottleDA;
use DataAccess\ProductPerfumeDA;
use Data\ShoppingCart;

try {
    if (isLoggedInCustomerExist()) {

        $pgscda = new PackageShoppingCartDA();
        $boscda = new BottleShoppingCartDA();
        $pescda = new PerfumeShoppingCartDA();

        $pgda = new ProductPackageDA();
        $boda = new ProductBottleDA();
        $peda = new ProductPerfumeDA();

        if (isset($_GET['action']) && $_GET['action'] == "add") {

            $post_data = file_get_contents("php://input");
            $request = json_decode($post_data);

            if (empty($request->category) || empty($request->productID)) {
                $data['error'] = "Fail to add to cart";
            } else if ($request->category == "package") {

                $package = $pgda->getOnePackageByID(xssFilter($request->productID), Database::getConnection());
                if (empty($request->productID)) {
                    $data['error'] = "Fail to add to cart";
                } else {
                    if(!$package->getProductID()) {
                        $data['error'] = "Sorry! The perfume is sold out.";
                    } else if ($pgscda->getOnePackageByCustID($_COOKIE['customerID'], Database::getConnection())->getProductID()) {
                        $data['cartError'] = "A item of package already exists in the cart.";
                    } else if(!$package->getStockEnough() || $package->getQtyInStock() < 1) {
                        $data['error'] = "The item is not enough in stock.";
                    } else {
                        $shoppingCart = new ShoppingCart();
                        $shoppingCart->setCustomerID($_COOKIE['customerID']);
                        $shoppingCart->setProductID(xssFilter($request->productID));
                        $pgscda->insert($shoppingCart, Database::getConnection());
                        $data['sucess'] = "Add to cart successfully";
                    }
                }
            } else if ($request->category == "bottle") {

                if (empty($request->productID)) {
                    $data['error'] = "Fail to add to cart";
                } else {

                    $bottle = $boda->getOneBottleByID(xssFilter($request->productID), Database::getConnection());
                    if(!$bottle->getProductID()) {
                        $data['error'] = "Sorry! The perfume is sold out.";
                    } else if ($boscda->getOneBottleByCustID($_COOKIE['customerID'], Database::getConnection())->getProductID()) {
                        $data['cartError'] = "A item of bottle already exists in the cart.";
                    } else if(!$bottle->getStockEnough() || $bottle->getQtyInStock() < 1) {    
                        $data['error'] = "The item is not enough in stock.";
                    } else {
                        $shoppingCart = new ShoppingCart();
                        $shoppingCart->setCustomerID($_COOKIE['customerID']);
                        $shoppingCart->setProductID(xssFilter($request->productID));
                        $boscda->insert($shoppingCart, Database::getConnection());
                        $data['sucess'] = "Add to cart successfully";
                    }
                }
            } else if ($request->category == "perfume") {

                if (empty($request->productID)) {
                    $data['error'] = "Fail to add to cart";
                } else if (empty($request->quantity) || ($request->quantity > 200 || $request->quantity < 1)) {
                    $data['error'] = "Please select quantity 1 to 200 mL";
                } else if (empty($request->note) || $request->note != 'Base' && $request->note != 'Middle' && $request->note != 'High') {
                    $data['error'] = "Please select note";
                } else {

                    $perfume = $peda->getOnePerfumeByID(xssFilter($request->productID), Database::getConnection());
                    if ($perfume->getProductID()) {

                        $allPerfumeInCart = $pescda->getAllPerfumeByCustID($_COOKIE['customerID'], Database::getConnection());

                        $noteCount = 0;
                        $existProduct = false;
                        
                        foreach ($allPerfumeInCart as &$value) {

                            if ($value->getNote() == $request->note)
                                $noteCount ++;

                            if ($value->getProductID() == $request->productID)
                                $existProduct = true;
                        }

                        if (!$existProduct && $noteCount < 2 &&
                                $request->quantity > 0 && $request->quantity <= 200 && 
                                $perfume->getStockEnough() && $perfume->getProductID() && $perfume->getQtyInStock() >= $request->quantity) {

                            $shoppingCart = new ShoppingCart();
                            $shoppingCart->setCustomerID($_COOKIE['customerID']);
                            $shoppingCart->setProductID(xssFilter($request->productID));
                            $shoppingCart->setNote(xssFilter($request->note));
                            $shoppingCart->setQuantity(xssFilter($request->quantity));
                            
                            $pescda->insert($shoppingCart, Database::getConnection());
                            $data['sucess'] = "Add to cart successfully";                                
                        } else if(!$perfume->getProductID()) {
                            $data['error'] = "Sorry! The perfume is sold out.";
                        } else if ($noteCount >= 2) {
                            if ($request->note == 'Base')
                                $note = 'base';
                            else if ($request->note == 'Middle')
                                $note = 'middle';
                            else if ($request->note == 'High')
                                $note = 'high';

                            $data['cartError'] = "Two items of " . $note . " note perfume already exist in the cart.";
                        } else if ($existProduct) {
                            $data['cartError'] = $perfume->getProductNameEnUs()." exist in the cart.";
                        } else if(!$perfume->getStockEnough() || $perfume->getQtyInStock() < $request->quantity) {
                            $data['error'] = "The item is not enough in stock.";
                        } else if (!($request->quantity > 0 && $request->quantity <= 200)) {
                            $data['error'] = "Please select quantity 1 to 200";
                        }
                    } else {
                        $data['error'] = "Fail to add to cart";
                    }
                }
            }
        } else if (isset($_GET['action']) && $_GET['action'] == "remove") {

            $post_data = file_get_contents("php://input");
            $request = json_decode($post_data);

            if (empty($request->category)) {
                $data['error'] = "Fail to delete from cart";
            } else if ($request->category == "package") {
                $pgscda->delete($_COOKIE['customerID'], Database::getConnection());
            } else if ($request->category == "bottle") {
                $boscda->delete($_COOKIE['customerID'], Database::getConnection());;
            } else if ($request->category == "perfume") {
                if(empty($request->productID))
                    $data['error'] = "Fail to remove from cart";
                else
                    $pescda->delete($_COOKIE['customerID'], $request->productID, Database::getConnection());
            }

        } else if (isset($_GET['action']) && $_GET['action'] == "update") {

            $post_data = file_get_contents("php://input");
            $request = json_decode($post_data);

            if (empty($request->category) || empty($request->productID)) {
                $data['error'] = "Fail to update the item in cart";
            } else if ($request->category == "package" && !empty($request->productID)) {
                
            } else if ($request->category == "bottle" && !empty($request->productID)) {
                
            } else if ($request->category == "perfume" && !empty($request->productID)) {
                $perfume = $peda->getOnePerfumeByID(xssFilter($request->productID), Database::getConnection());
                if ($perfume->getProductID()) {

                    $allPerfumeInCart = $pescda->getAllPerfumeByCustID($_COOKIE['customerID'], Database::getConnection());

                    $noteCount = 0;
                    $existProduct = false;

                    foreach ($allPerfumeInCart as &$value) {
                        if ($value->getNote() == $request->note && $value->getProductID()!=$request->productID)
                            $noteCount ++;
                    }

                    if (!$existProduct && $noteCount < 2 &&
                            $request->quantity > 0 && $request->quantity <= 200 && 
                            $perfume->getStockEnough() && $perfume->getQtyInStock() >= $request->quantity) {

                        $shoppingCart = new Data\ShoppingCart();
                        $shoppingCart->setCustomerID($_COOKIE['customerID']);
                        $shoppingCart->setProductID($request->productID);
                        $shoppingCart->setNote($request->note);
                        $shoppingCart->setQuantity($request->quantity);
                        $pescda->update($shoppingCart, Database::getConnection());
                        $data['sucess'] = "Edit perfume - ".$perfume->getCategoryNameEnUs()." in cart successfully";
                    } else if ($noteCount >= 2) {
                        if ($request->note == 'Base')
                            $note = 'base';
                        else if ($request->note == 'Middle')
                            $note = 'middle';
                        else if ($request->note == 'High')
                            $note = 'high';

                        $data['error'] = "You cannot include more than two " . $note . " note in cart.";
                    } else if(!$perfume->getStockEnough() || $perfume->getQtyInStock() < $request->quantity) {
                        $data['error'] = "The item is not enough in stock.";
                    } else if (!($request->quantity > 0 && $request->quantity <= 200)) {
                        $data['error'] = "Please select quantity 1 to 200";
                    }
                } else {
                    $data['cartError'] = "Fail to update perfume in cart";
                }
            }

        } else if (!isset($_GET['action'])) {

            $pgscid = $pgscda->getOnePackageByCustID($_COOKIE['customerID'], Database::getConnection())->getProductID();
            $package = $pgda->getOnePackageByID($pgscid, Database::getConnection());
            if ($pgscid) {
                if ($package->getProductID()) {
                    if(!$package->getStockEnough() || $package->getQtyInStock() < 1) {
                        $data['package']['productID'] = $pgscid;
                        $data['package']['nonEnough'] = true;
                    } else {
                        $data['package']['productID'] = $package->getProductID();
                        $data['package']['productName'] = $package->getProductNameEnUs();
                        $data['package']['originalPrice'] = $package->getOriginalPrice();
                        $data['package']['nowPrice'] = $package->getNowPrice();
                        $data['package']['nonEnough'] = false;
                        $data['package']['soldOut'] = false;
                    }
                } else {
                    $data['package']['productID'] = $pgscid;
                    $data['package']['soldOut'] = true;
                }
            }
                
            $boscid = $boscda->getOneBottleByCustID($_COOKIE['customerID'], Database::getConnection())->getProductID();
            $bottle = $boda->getOneBottleByID($boscid, Database::getConnection());
            if ($boscid) {
                if ($bottle->getProductID()) {
                    if(!$bottle->getStockEnough() || $bottle->getQtyInStock() < 1){
                        $data['bottle']['productID'] = $boscid;
                        $data['bottle']['nonEnough'] = true;
                    } else {
                        $data['bottle']['productID'] = $bottle->getProductID();
                        $data['bottle']['productName'] = $bottle->getProductNameEnUs();
                        $data['bottle']['bottleCapacity'] = $bottle->getBottleCapacity();
                        $data['bottle']['originalPrice'] = $bottle->getOriginalPrice();
                        $data['bottle']['nowPrice'] = $bottle->getNowPrice();
                        $data['bottle']['nonEnough'] = false;
                        $data['bottle']['soldOut'] = false;
                    }
                } else {
                    $data['bottle']['productID'] = $boscid;
                    $data['bottle']['soldOut'] = true;
                }
            }

            $allPerfumeInCart = $pescda->getAllPerfumeByCustID($_COOKIE['customerID'], Database::getConnection());
            if (count($allPerfumeInCart) > 0) {
                $data['perfume'] = array();
                foreach ($allPerfumeInCart as &$value) {
                    $perfume = $peda->getOnePerfumeByID($value->getProductID(), Database::getConnection());
                    if($perfume->getProductID()){
                        if(!$perfume->getStockEnough() || $perfume->getQtyInStock() < 50) {
                            $product['productID'] = $value->getProductID();
                            $product['nonEnough'] = true;
                        } else {
                            $product['productID'] = $perfume->getProductID();
                            $product['productName'] = $perfume->getProductNameEnUs();
                            $product['category'] = $perfume->getCategoryNameEnUs();
                            $product['originalPrice'] = $perfume->getOriginalPrice();
                            $product['nowPrice'] = $perfume->getNowPrice();
                            $product['qtyInStock'] = $perfume->getQtyInStock();
                            $product['quantity'] = $value->getQuantity();
                            $product['note'] = $value->getNote();
                            $product['nonEnough'] = false;
                            $product['soldOut'] = false;
                        }
                    } else {
                        $product['productID'] = $value->getProductID();
                        $product['soldOut'] = true;
                    }
                    array_push($data['perfume'], $product);
                }
            }
        }

    }

} catch (Exception $ex) {
    $data['databaseError'] = Database::getDbErrorMsg();
}

if(!empty($data))
    echo json_encode($data);