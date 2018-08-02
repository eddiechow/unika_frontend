<?php

header('Content-Type: application/json');
require 'global.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;

use DataAccess\OrderDA;
use DataAccess\OrderLineDA;

use DataAccess\ProductPackageDA;
use DataAccess\ProductBottleDA;
use DataAccess\ProductPerfumeDA;

use DataAccess\CustomerDA;

try {
    if (isLoggedInCustomerExist()) {
        $apiContext = PayPalAPI::getApiContext();
        
        if(isset($_GET['orderID'])){

            $oda = new OrderDA();
            $order = $oda->getOneOrder($_GET['orderID'], $_COOKIE['customerID'], false, Database::getConnection());
            if($order->getOrderID()){
                if(isset($_GET['approved']) && $_GET['approved']=='true'){
                    if(isset($_GET['paymentId']) && isset($_GET['token']) && isset($_GET['PayerID'])){
                        try{
                            $payment = Payment::get(xssFilter($_GET['paymentId']), $apiContext);

                            $execution = new PaymentExecution();
                            $execution->setPayerId(xssFilter($_GET['PayerID']));

                            $payment->execute($execution, $apiContext);
                            
                            $order->setPayPalPaymentID($payment->getId());
                            $order->setPayPalTransactionID($payment->transactions[0]->related_resources[0]->sale->id);
							
							$barCode = null;
							$existBarCode = false;
							do {
								$barCode = randomDigit(21);
								$existBarCode = false;
								$allExistOrder = $oda->getAllOrders(null, Database::getConnection());
								foreach ($allExistOrder as &$value){
									if($barCode==$value->getBarCode()){
										$existBarCode = true;
										break;
									}
								}
							} while($barCode>18446744073709551615 || $barCode<1000000000 || $existBarCode);
							
							$order->setBarCode($barCode);
							
                            $oda->update($order, Database::getConnection());

                            $data['status'] = 'success';
                            $data['message'] = "Payment success";                           
                        } catch (mysqli_sql_exception $ex) {
                            $data['status'] = 'fail';
                            $data['message'] = Database::getDbErrorMsg();
                        } catch (PayPalConnectionException $ex) {
                            if ($ex->getCode()==404) {
                                $data['status'] = 'fail';
                                $data['message'] = "The order is not found.";
                            } else {
                                $data['status'] = 'fail';
                                $data['message'] = "Failed to connect to PayPal";
                            }
                        } catch (Exception $ex) {
                            $data['status'] = 'fail';
                            $data['message'] = "Paypal payment system error. Please try again later.";  
                        }
                        
                    } 
                } else if(isset($_GET['approved']) && $_GET['approved']=='false') {
                    $data['status'] = 'cancel';
                    $data['message'] = "Your payment has been cancelled.";    
                } else {
                    if (!$order->getRejected()) {
						$customerDA = new CustomerDA();
						$customer = $customerDA->getOneCustomerByID($_COOKIE['customerID'], Database::getConnection());
						if(!$customer->getActivated()){
							$data['error'] = "Your email address is not verified. Please verify email and try again later.";
						}
						
                        if($order->getPayPalPaymentID()){
                            try {
                                $payment = Payment::get($order->getPayPalPaymentID(), $apiContext);
                                if($payment->getState()=='failed'){
                                    $order->setPayPalPaymentID(null);
                                    $oda->update($order, Database::getConnection());
                                    $payment = null;
                                }
                            } catch (PayPalConnectionException $ex) {
                                if ($ex->getCode()==6) {
                                    $data['error'] = "Failed to connect to PayPal";
                                } else if ($ex->getCode()==404) {
                                    $order->setPayPalPaymentID(null);
                                    $oda->update($order, Database::getConnection());
                                    $payment = null;
                                } else {
                                    $data['error'] = "PayPal Payment System Error. Please try again later.";
                                }
                            }
                        }

                        $olda = new OrderLineDA();
                        $pgol = $olda->getPackageOrderLine($order->getOrderID(), Database::getConnection());
                        $bool = $olda->getBottleOrderLine($order->getOrderID(), Database::getConnection());
                        $peol = $olda->getPerfumeOrderLine($order->getOrderID(), Database::getConnection());

                        $rejectOrder = false;
                        $rejectReason = null;    

                        if(!$order->getPayPalPaymentID() || $payment->getState()=='created'){
                            if($pgol->getProductID()){
                                $pgda = new ProductPackageDA();
                                $package = $pgda->getOnePackageByID($pgol->getProductID(), Database::getConnection());
                                if(!$package->getProductID()){
                                    $rejectOrder = true;
                                    $rejectReason .= "\n- The package - ".$pgol->getProductNameEnUs()." is sold out.";
                                } else if(!$package->getStockEnough()) {
                                    $rejectOrder = true;
                                    $rejectReason .= "\n- The package - ".$pgol->getProductNameEnUs()." does not exist in stock.";
                                }
                            }

                            if(!$rejectOrder) {
                                if($bool->getProductID()){
                                    $boda = new ProductBottleDA();
                                    $bottle = $boda->getOneBottleByID($bool->getProductID(), Database::getConnection());
                                    if(!$bottle->getProductID()){
                                        $rejectOrder = true;
                                        $rejectReason .= "\n- The bottle - ".$bool->getProductNameEnUs()." is sold out.";
                                    } else if(!$bottle->getStockEnough()) {
                                        $rejectOrder = true;
                                        $rejectReason .= "\n- The bottle - ".$bool->getProductNameEnUs()." does not exist in stock.";
                                    }
                                }
                            }

                            if(!$rejectOrder) {
                                if(count($peol)>0){
                                    $peda = new ProductPerfumeDA();
                                    foreach ($peol as &$value) {
                                        $perfume = $peda->getOnePerfumeByID($value->getProductID(), Database::getConnection());
                                        if(!$perfume->getProductID()){
                                            $rejectOrder = true;
                                            $rejectReason .= "\n- \"".$perfume->getProductNameEnUs()."\" is sold out.";
                                            break;
                                        } else if(!$perfume->getStockEnough()) {
                                            $rejectOrder = true;
                                            $rejectReason .= "\n- \"".$perfume->getProductNameEnUs()."\" does not exist in stock.";
                                            break;
                                        } else if ($value->getQuantity() > $perfume->getQtyInStock()) {
                                            $rejectOrder = true;
                                            $rejectReason .= "\n- \"".$perfume->getProductNameEnUs()."\" is not enough in stock.";
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        if(!$rejectOrder && !isset($data['error'])){
                            if(!$order->getPayPalPaymentID()){

                                $payer = new Payer();
                                $payer->setPaymentMethod("paypal");

                                $total = 0;
                                $items = array();

                                if($pgol->getProductID()){
                                    $packageItem = new Item();
                                    $packageItem->setName($pgol->getProductNameEnUs()." (Package)")
                                               ->setCurrency('HKD')
                                               ->setQuantity(1)
                                               ->setSku($pgol->getProductID())
                                               ->setPrice($pgol->getNowPrice());
                                    array_push($items, $packageItem);
                                    $total += $pgol->getSubTotal();
                                }

                                if($bool->getProductID()){
                                    $bottleItem = new Item();
                                    $bottleItem->setName($bool->getProductNameEnUs()." (Bottle)")
                                               ->setCurrency('HKD')
                                               ->setQuantity(1)
                                               ->setSku($bool->getProductID())
                                               ->setPrice($bool->getNowPrice());
                                    array_push($items, $bottleItem);
                                    $total += $bool->getSubTotal();
                                }

                                if(count($peol)>0){
                                    foreach ($peol as &$value) {
                                        $perfumeItem = new Item();
                                        $perfumeItem->setName($value->getProductNameEnUs()." (Perfume - ".$value->getNote()." Note)")
                                                    ->setDescription("Category: ".$value->getPerfumeCategoryNameEnUs())
                                                    ->setCurrency('HKD')
                                                    ->setQuantity($value->getQuantity())
                                                    ->setSku($value->getProductID())
                                                    ->setPrice($value->getNowPrice());
                                        array_push($items, $perfumeItem);
                                        $total += $value->getSubTotal();
                                    }
                                }

                                $itemList = new ItemList();
                                $itemList->setItems($items);

                                $details = new Details();
                                $details->setShipping(0)
                                        ->setTax(0)
                                        ->setSubtotal($total);

                                $amount = new Amount();
                                $amount->setCurrency("HKD")
                                    ->setTotal($total)
                                    ->setDetails($details);

                                $transaction = new Transaction();
                                $transaction->setAmount($amount)
                                            ->setItemList($itemList)
                                            ->setInvoiceNumber($order->getOrderID());       

                                $redirectUrls = new RedirectUrls();
                                $redirectUrls->setReturnUrl($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].ROOT_PATH.'payment?orderID='.$order->getOrderID().'&approved=true')
                                             ->setCancelUrl($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].ROOT_PATH.'payment?orderID='.$order->getOrderID().'&approved=false');

                                $payment = new Payment();
                                $payment->setIntent("sale")
                                    ->setPayer($payer)
                                    ->setTransactions(array($transaction))
                                    ->setRedirectUrls($redirectUrls);                        
                            }

                            try {
                                if ($payment->getState()=='approved'){
                                    $data['error'] = "You have finished paying for the order";
                                } else {
                                    if(!$payment->getState()){
                                        $payment->create($apiContext);
                                        $order->setPayPalPaymentID($payment->getId());
                                        $oda->update($order, Database::getConnection());                             
                                    }  
                                    $data['approvalUrl'] = $payment->getApprovalLink();                             
                                }
                            } catch (mysqli_sql_exception $ex) {
                                $data['error'] = Database::getDbErrorMsg();
                            } catch (PayPalConnectionException $ex) {
                                if ($ex->getCode()==6) {
                                    $data['error'] = "Failed to connect to PayPal";
                                } else {
                                    $data['error'] = "PayPal Paymeny System Error. Please try again later.";
                                }
                            } catch (Exception $ex) {
                                $data['error'] = "Paypal payment system error. Please try again later.";  
                            }
                        } else {
							if ($rejectOrder) {
								$order->setRejected(true);
								$order->setApproveDate(date('Y-m-d H:i:s'));
								$oda->update($order, Database::getConnection());
								$data['error'] =  "The order is rejected. Reason:".$rejectReason;
								$data['rejected'] = true;
							}
                        }
                    } else {
                        $data['error'] =  "The order has been rejected.";
                    }
                }
            } else {
                if($_GET['approved']=='false'|| $_GET['approved']=='true'){
                    $data['status'] = 'fail';
                    $data['message'] = "The order is not found.";
                } else {
                    $data['error'] = "The order is not found";
                }
            }
        }
    }
} catch (mysqli_sql_exception $ex) {
    $data['error'] = Database::getDbErrorMsg();
} catch (\PayPal\Exception $ex) {
    $data['error'] = "Failed to connect to PayPal";
}

if(!empty($data))
    echo json_encode($data);