<?php

header('Content-Type: application/json');
require 'global.php';

use PayPal\Api\Payment;
use PayPal\Exception\PayPalConnectionException;

use DataAccess\OrderDA;
use DataAccess\OrderLineDA;


try {
    if (isLoggedInCustomerExist()) {
        $apiContext = PayPalAPI::getApiContext();
        
        $oda = new OrderDA();
        $olda = new OrderLineDA();
        $orders = $oda->getAllOrders($_COOKIE['customerID'], Database::getConnection());
        
        $data = array();
        
        foreach ($orders as &$value){
            $order['orderID'] = $value->getOrderID();
            $order['orderDate'] = $value->getOrderDate();
            if($value->getPayPalPaymentID()) {
                try {
                    $payment = Payment::get($value->getPayPalPaymentID(), $apiContext);
                    if($payment->getState()=='approved'){
                        $transactions = $payment->getTransactions();
                        $relatedResources = $transactions[0]->getRelatedResources();
                        $sale = $relatedResources[0]->getSale();
                        if($sale->getState()=='completed'){
                            $order['status'] = 'Paid';
                            $order['paidDate'] = strtotime($sale->getCreateTime());
                            $order['refundedAmount'] = null;
                            $order['refundedDate'] = null;
                        } else if(($sale->getState()=='refunded' || $sale->getState()=='partially_refunded')){
                            $refund = $relatedResources[1]->getRefund();
                            $order['status'] = 'Refunded';
                            $order['paidDate'] = strtotime($sale->getCreateTime());
                            $order['refundedAmount'] = $refund->getAmount()->getTotal();
                            $order['refundedDate'] = strtotime($refund->getCreateTime());
                        }
                    } else {
                        $order['status'] = "Unpaid";
                        $order['paidDate'] = null;
                        $order['refundedAmount'] = null;
                        $order['refundedDate'] = null;
                    }
                } catch (PayPalConnectionException $ex) {
                    if ($ex->getCode()==6) {
                        throw new PayPalConnectionException(null, "Failed to connect to Paypal", 6);
                    } else if ($ex->getCode()==404) {
                        $order['status'] = "Unpaid";
                        $order['paidDate'] = null;
                        $order['refundedAmount'] = null;
                        $order['refundedDate'] = null;
                    } else {
                        throw new PayPalConnectionException(null, "Failed to connect to Paypal", 6);
                    }
                } catch (Exception $ex) {
                    throw new PayPalConnectionException(null, "Failed to connect to Paypal", 6);
                }
            } else {
                $order['status'] = "Unpaid";
                $order['paidDate'] = null;
                $order['refundedAmount'] = null;
                $order['refundedDate'] = null;
            }
            
            if(!$value->getRejected() && !$value->getApproveDate()){
                $order['approved'] = "Approving";
            } else if(!$value->getRejected() && $value->getApproveDate()){
                $order['approved'] = "Approved";
            } else if($value->getRejected() && $value->getApproveDate()){
                $order['approved'] = "Rejected";
            }
            
            $order['total'] = 0;
            $package = $olda->getPackageOrderLine($value->getOrderID(), Database::getConnection());
            if($package->getProductID()){
                $order['package']['productID'] = $package->getProductID();
                $order['package']['productName'] = $package->getProductNameEnUs();
                $order['package']['originalPrice'] = $package->getPrice();
                $order['package']['discount'] = $package->getDicount();
                $order['package']['nowPrice'] = $package->getNowPrice();
                $order['package']['subTotal'] = $package->getSubTotal();
                $order['total'] += $package->getNowPrice();
            }
            
            $bottle = $olda->getBottleOrderLine($value->getOrderID(), Database::getConnection());
            if($bottle->getProductID()){
                $order['bottle']['productID'] = $bottle->getProductID();
                $order['bottle']['productName'] = $bottle->getProductNameEnUs();
                $order['bottle']['bottleCapacity'] = $bottle->getBottleCapacity(); 
                $order['bottle']['originalPrice'] = $bottle->getPrice();   
                $order['bottle']['discount'] = $bottle->getDicount();
                $order['bottle']['nowPrice'] = $bottle->getNowPrice();
                $order['bottle']['subTotal'] = $bottle->getSubTotal();
                $order['total'] += $bottle->getNowPrice();
            }
            
            $perfumeArray = $olda->getPerfumeOrderLine($value->getOrderID(), Database::getConnection());
            if(count($perfumeArray)>0){
                $order['perfume'] = array();
                foreach ($perfumeArray as &$value) {
                    $perfume['productID'] = $value->getProductID();
                    $perfume['productName'] = $value->getProductNameEnUs();
                    $perfume['categoryName'] = $value->getPerfumeCategoryNameEnUs();
                    $perfume['originalPrice'] = $value->getPrice(); 
                    $perfume['discount'] = $value->getDicount();
                    $perfume['nowPrice'] = $value->getNowPrice();
                    $perfume['quantity'] = $value->getQuantity();
                    $perfume['note'] = $value->getNote();
                    $perfume['subTotal'] = $value->getSubTotal();
                    $order['total'] += $value->getSubTotal();
                    array_push($order['perfume'], $perfume);
                }
            }

            array_push($data, $order);
        }
    }
} catch (mysqli_sql_exception $ex) {
    $data['error'] = Database::getDbErrorMsg();
} catch (PayPalConnectionException $ex) {
    if ($ex->getCode()==6)
        $data['error'] = "Failed to connect to Paypal";
    else
        $data['error'] = "Paypal Error";

} catch (Exception $ex) {
    $data['error'] = "System Error";
}

if(!empty($data))
    echo json_encode($data);