<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\PackageShoppingCartDA;
use DataAccess\BottleShoppingCartDA;
use DataAccess\PerfumeShoppingCartDA;

use DataAccess\ProductPackageDA;
use DataAccess\ProductBottleDA;
use DataAccess\ProductPerfumeDA;

use Data\Order;
use Data\OrderLine;
use DataAccess\OrderDA;
use DataAccess\OrderLineDA;

use DataAccess\CustomerDA;

try {
    if (isLoggedInCustomerExist()) {
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $pgscda = new PackageShoppingCartDA();
            $boscda = new BottleShoppingCartDA();
            $pescda = new PerfumeShoppingCartDA();

            $pgda = new ProductPackageDA();
            $boda = new ProductBottleDA();
            $peda = new ProductPerfumeDA();
			
			$customerDA = new CustomerDA();
			$customer = $customerDA->getOneCustomerByID($_COOKIE['customerID'], Database::getConnection());
			if(!$customer->getActivated()){
				$data['error']['checkout'] = "Your email address is not verified. Please verify email and try again later.";
			}

            $pgscid = $pgscda->getOnePackageByCustID($_COOKIE['customerID'], Database::getConnection())->getProductID();
            $package = $pgda->getOnePackageByID($pgscid, Database::getConnection());
			if(!isset($data['error']['checkout'])) {
				if($pgscid){
					if (!$package->getProductID()) {
						$data['error']['checkout'] = "Sorry! The package is sold out.";
					} else if(!$package->getStockEnough()) {
						$data['error']['checkout'] = "Sorry! The package is out of stock.";
					}            
				}
			}

            $boscid = $boscda->getOneBottleByCustID($_COOKIE['customerID'], Database::getConnection())->getProductID();
            $bottle = $boda->getOneBottleByID($boscid, Database::getConnection());
            if(!isset($data['error']['checkout'])) {
                if ($boscid) {
                    if (!$bottle->getProductID()) {
                        $data['error']['checkout'] = "Sorry! The bottle is sold out.";
                    } else if(!$bottle->getStockEnough()) {
                        $data['error']['checkout'] = "Sorry! The bottle is out of stock.";
                    } 
                } else {
                    $data['error']['checkout'] = "Please select a bottle.";
                }
            }

            $allPerfumeInCart = $pescda->getAllPerfumeByCustID($_COOKIE['customerID'], Database::getConnection());
            $baseNote = 0;
            $middleNote = 0;
            $highNote = 0;
            $totalSelectedPerfumeQty = 0;
            if(!isset($data['error']['checkout'])) {
                if (count($allPerfumeInCart) > 0) {
                    foreach ($allPerfumeInCart as &$value) {
                        $perfume = $peda->getOnePerfumeByID($value->getProductID(), Database::getConnection());
                        if(!$perfume->getProductID()){
                            $data['error']['checkout'] = "Sorry! The perfume is sold out.";
                            break;
                        } else if(!$perfume->getStockEnough()) {
                            $data['error']['checkout'] = "Sorry! The perfume is out of stock.";
                            break;
                        } else if($perfume->getQtyInStock() < $value->getQuantity()) {
                            $data['error']['checkout'] = "Sorry! The perfume is enough in stock.";
                            break;
                        } else {
                            $totalSelectedPerfumeQty += $value->getQuantity();
                        }

                        if($value->getNote() == 'Base'){
                            $baseNote++;
                        } else if($value->getNote() == 'Middle'){
                            $middleNote++;
                        } else if($value->getNote() == 'High'){
                            $highNote++;
                        } 

                    }
                }

            }
            
            if(!isset($data['error']['checkout'])) {
                if($totalSelectedPerfumeQty > $bottle->getBottleCapacity()){
                    $data['error']['checkout'] = "Remaining bottle capacity is not enough.";
                } else if($baseNote==0 || $middleNote==0 || $highNote==0){
                    $data['error']['checkout'] = "Please select ".
                             (($baseNote==0)?("1 to 2 item(s) of base note perfume".(($middleNote==0 && $highNote==0)?", ":(($middleNote==0 || $highNote==0)?" and ":"")).""):"").
                             (($middleNote==0)?"1 to 2 item(s) of middle note perfume".(($highNote==0)?"and ":""):"").
                             (($highNote==0)?"1 to 2 item(s) of high note perfume.":"");
                }                 
            }

            if(!isset($data['error']['checkout'])) {
                $oda = new OrderDA();
                $orderID = 0;
                do {
                    $orderID = randomDigit(10);
                    $existOrderID = $oda->getOneOrder($orderID, null, true, Database::getConnection());
                } while ($orderID>4294967295 || $orderID<100 || ($existOrderID->getOrderID()==$orderID));
                
                $order = new Order($_COOKIE['customerID'], $orderID, date('Y-m-d H:i:s'), false);
                $oda->insert($order, Database::getConnection());

                $olda = new OrderLineDA();
                if($pgscid){
                    if ($package->getProductID()) {
                        $packageOrderLine = new OrderLine($order->getOrderID(), $pgscid, $package->getOriginalPrice());
                        if($package->getDiscount()){
                            $packageOrderLine->setDicount($package->getDiscount());
                        }
                        $olda->insertPackageOrderLine($packageOrderLine, Database::getConnection());
                        $pgscda->delete($_COOKIE['customerID'], Database::getConnection());
                    }
                }

                if ($boscid) {
                    if ($bottle->getProductID()) {
                        $bottleOrderLine = new OrderLine($order->getOrderID(), $boscid, $bottle->getOriginalPrice());
                        if($bottle->getDiscount()){
                            $bottleOrderLine->setDicount($bottle->getDiscount());
                        }
                        $olda->insertBottleOrderLine($bottleOrderLine, Database::getConnection());
                        $boscda->delete($_COOKIE['customerID'],  Database::getConnection());
                    }                  
                }

                if (count($allPerfumeInCart) > 0) {
                    foreach ($allPerfumeInCart as &$value) {
                        $perfume = $peda->getOnePerfumeByID($value->getProductID(), Database::getConnection());
                        $perfumeOrderLine = new OrderLine($order->getOrderID(), $value->getProductID(), $perfume->getOriginalPrice());
                        $perfumeOrderLine->setNote($value->getNote());
                        $perfumeOrderLine->setQuantity($value->getQuantity());
                        if($perfume->getDiscount()){
                            $perfumeOrderLine->setDicount($perfume->getDiscount());
                        }
                        $olda->insertPerfumeOrderLine($perfumeOrderLine, Database::getConnection());
                        $pescda->delete($_COOKIE['customerID'], $value->getProductID(), Database::getConnection());
                    }
                }
               
                $data['success']['checkout'] = true;
                $data['success']['orderID'] = $order->getOrderID();
            }
            
        }
    }

} catch (Exception $ex) {
    $data['databaseError'] = Database::getDbErrorMsg();
}

if(!empty($data))
    echo json_encode($data);