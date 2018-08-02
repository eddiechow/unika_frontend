<?php

namespace DataAccess;

use Data\Notice;

class NoticeDA {

    public function getAllNotice($conn) {
        $list = array();

        $stmt = $conn->prepare("SELECT `Content_en-US`, `Content_zh-Hant` FROM `notice` WHERE `Date_Deleted` IS NULL");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($resultContentEnUs, $resultContentZhHant);

        while ($stmt->fetch()) {
            $notice = new Notice();

            $notice->setContentZhHant($resultContentZhHant);
            $notice->setContentEnUs($resultContentEnUs);

            array_push($list, $notice);
        }

        $stmt->close();
        unset($stmt);
        $conn->close();
        unset($conn);

        return $list;
    }

}
