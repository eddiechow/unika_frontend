<?php

namespace Data;

class AgeGroup {

    private $ageGroupCode;
    private $ageGroupEnUs;
    private $ageGroupZhHant;
    
    function getAgeGroupCode() {
        return $this->ageGroupCode;
    }

    function getAgeGroupEnUs() {
        return $this->ageGroupEnUs;
    }

    function getAgeGroupZhHant() {
        return $this->ageGroupZhHant;
    }

    function setAgeGroupCode($ageGroupCode) {
        $this->ageGroupCode = $ageGroupCode;
    }

    function setAgeGroupEnUs($ageGroupEnUs) {
        $this->ageGroupEnUs = $ageGroupEnUs;
    }

    function setAgeGroupZhHant($ageGroupZhHant) {
        $this->ageGroupZhHant = $ageGroupZhHant;
    }


}
