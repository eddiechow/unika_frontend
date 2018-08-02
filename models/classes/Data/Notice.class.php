<?php
namespace Data;

class Notice {
    
    private $contentEnUs;
    private $contentZhHant;
    
    function getContentEnUs() {
        return $this->contentEnUs;
    }

    function getContentZhHant() {
        return $this->contentZhHant;
    }

    function setContentEnUs($contentEnUs) {
        $this->contentEnUs = $contentEnUs;
    }

    function setContentZhHant($contentZhHant) {
        $this->contentZhHant = $contentZhHant;
    }


}
