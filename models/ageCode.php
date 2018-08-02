<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\CustomerDA;

try {
    $customerDA = new CustomerDA();
    $ageGroups = $customerDA->getAgeGroups(Database::getConnection());
    
    $data = array();
    foreach ($ageGroups as &$value){
        $ageGroup['ageGroupCode'] = $value->getAgeGroupCode();
        $ageGroup['ageGroupEnUs'] = $value->getAgeGroupEnUs();
        $ageGroup['ageGroupZhHant'] = $value->getAgeGroupZhHant();
        array_push($data, $ageGroup);
    } 
    
} catch (Exception $ex) {
    $data['databaseError'] = Database::getDbErrorMsg();
}

if(!empty($data))
    echo json_encode($data);

