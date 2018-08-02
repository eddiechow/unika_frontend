<?php

header('Content-Type: application/json');
require 'global.php';

use DataAccess\NoticeDA;
use DataAccess\DiscountDA;

$nda = new NoticeDA();
$allNotices = $nda->getAllNotice(Database::getConnection());

$data = array();

foreach ($allNotices as &$value) {
    $notice['content']=$value->getContentEnUs();
    array_push($data, $notice);
}

$dda = new DiscountDA();
$allDiscount = $dda->getAllDiscounts(Database::getConnection());

foreach ($allDiscount as &$value) {
    $notice['content']=$value->getTitleEnUS();
    $notice['discountCode']=$value->getDiscountCode();
    $notice['discount']=$value->getDiscount();
    array_push($data, $notice);
}

if (isset($data))
    echo json_encode($data);

