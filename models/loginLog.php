<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\CustomerLoginLogDA;

try{
    if (isLoggedInCustomerExist()) {
        $cllda = new CustomerLoginLogDA();
        $logs = $cllda->getLoginLogByCustID($_COOKIE['customerID'], null, Database::getConnection());
        $data =  array();
        foreach($logs as &$value) {
            $loginLog['date'] = $value->getTime();
            $loginLog['status'] = $value->getStatus();
            $loginLog['os'] = $value->getOs();
            $loginLog['browser'] = $value->getBrowser();
            $loginLog['ip'] = $value->getIp();
            array_push($data, $loginLog);
        }
    }
} catch (Exception $ex) {
    $data['error'] = Database::getDbErrorMsg();
}

if (isset($data))
    echo json_encode($data);
