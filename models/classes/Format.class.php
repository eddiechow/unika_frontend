<?php

class Format {
    
    public static function validEmail($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        } else {
            return true;
        }
    }
    
    public static function passwordStrong($password){
        if(!preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",$password)) {
            return false;
        } else {
            return true;
        }
    }

}
