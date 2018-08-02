<?php

class Hash {
    
    public function getHash($plainText) {
        $salt = substr(str_replace('+','.',base64_encode(md5(mt_rand(), true))),0,30);
        $parts = explode('$', crypt($plainText, sprintf('$6$rounds=100000$%s$', $salt)));
        return $parts[3].'$'.$parts[4];
    }
    
    public function verifyHash($plainText, $hashValue){
        $hashValueParts = explode('$', '$'.$hashValue);
        $parts = explode('$', crypt($plainText, sprintf('$6$rounds=100000$%s$', $hashValueParts[1])));
        return $hashValue == ($parts[3].'$'.$parts[4]);
    }


}