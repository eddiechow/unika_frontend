<?php

namespace DataAccess;

use Data\Order;

class OrderDA {

    public function getAllOrders($customerID, $conn) {
        $list = array();

        $stmt = $conn->prepare("SELECT `Order_ID`, `Customer_ID`, `Order_date`, `Rejected`, `Bar_Code`, `Approve_Date`, `PayPal_Payment_ID` FROM `order` WHERE ".(($customerID!=null)?"`Customer_ID`=? AND ":"")."`Date_Deleted` IS NULL");
		if($customerID!=null)
			$stmt->bind_param("s", $customerID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultOrderID, $resultCustomerID, $resultOrderDate, $resultRejected, $resultBarCode, $resultApproveDate, $resultPayPalPaymentID);

        while ($stmt->fetch()) {

            $order = new Order($resultCustomerID, sprintf("%010s", $resultOrderID), strtotime($resultOrderDate), ($resultRejected == 1) ? true : false);

            $order->setBarCode($resultBarCode);
            $order->setApproveDate($resultApproveDate);
            $order->setPayPalPaymentID($resultPayPalPaymentID);

            array_push($list, $order);
        }
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }
    
    public function getOneOrder($orderID, $customerID, $allRecord, $conn) {

        $stmt = $conn->prepare("SELECT `Order_ID`, `Customer_ID`, `Order_date`, `Rejected` FROM `order` WHERE `Order_ID`=?".(($customerID!=null)?" AND `Customer_ID`=?":"").((!$allRecord)?" AND `Date_Deleted` IS NULL":""));
        if($customerID!=null)
            $stmt->bind_param("is", $orderID, $customerID);
        else
            $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultOrderID, $resultCustomerID, $resultOrderDate, $resultRejected);

        $stmt->fetch();

        $order = new Order($resultCustomerID, sprintf("%010s", $resultOrderID), strtotime($resultOrderDate), ($resultRejected == 1) ? true : false);

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $order;
    }

    public function insert($order, $conn) {

        $stmt = $conn->prepare("INSERT INTO `order`(`Order_ID`, `Customer_ID`, `Order_date`, `Rejected`) VALUES (?,?,?,?)");
        $isRejected = ($order->getRejected())?1:0;
        $stmt->bind_param("issi", $order->getOrderID(), $order->getCustomerID(), $order->getOrderDate(), $isRejected);
        $stmt->execute();
        
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }
    
    public function update($order, $conn) {

        $stmt = $conn->prepare("UPDATE `order` SET `PayPal_Payment_ID`=?, `PayPal_Transaction_ID`=?, `Rejected`=?, `Approve_Date`=?, `Bar_Code`=? WHERE `Order_ID`=?");
        $isRejected = ($order->getRejected())?1:0;
        $stmt->bind_param("ssisii", $order->getPayPalPaymentID(), $order->getPayPalTransactionID(), $isRejected, $order->getApproveDate(), $order->getBarCode(), $order->getOrderID());
        $stmt->execute();
        
        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);
    }

}
