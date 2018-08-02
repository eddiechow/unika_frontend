<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\PerfumeCategoryDA;

try {
    $perfumeCategoryDA = new PerfumeCategoryDA();
    $perfumeCategories = $perfumeCategoryDA->getAllPerfumeCategories(Database::getConnection()); 

    $data = array();
    foreach ($perfumeCategories as &$value){
        $ageGroup['perfumeCategoryCode'] = $value->getPerfumeCategoryCode();
        $ageGroup['categoryNameEnUs'] = $value->getCategoryNameEnUs();
        $ageGroup['categoryNameZhHant'] = $value->getCategoryNameZhHant();
        array_push($data, $ageGroup);
    }
 
} catch (mysqli_sql_exception $ex) {
    $data['databaseError'] = Database::getDbErrorMsg();
}

if (isset($data))
    echo json_encode($data);

