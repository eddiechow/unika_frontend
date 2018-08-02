<?php
namespace Data;

class CustomerLoginLog {
    
    private $customerID;
    private $success;
    private $passwordIncorrect;
    private $locked;
    private $unlocked;
    private $acStatusTempLock;
    private $acStatusNonActive;
    private $status = null;
    private $time;
    private $os;
    private $browser;
    private $ip;
    
    function __construct($getFromSystem=false) {
        if($getFromSystem){
            $this->setOs();
            $this->setBrowser();
            $this->setTime();
            $this->setIp();            
        }

    }
            
    function getCustomerID() {
        return $this->customerID;
    }

    function getSuccess() {
        return $this->success;
    }

    function getPasswordIncorrect() {
        return $this->passwordIncorrect;
    }

    function getLocked() {
        return $this->locked;
    }
    
    function getUnlocked() {
        return $this->unlocked;
    }

    function getAcStatusTempLock() {
        return $this->acStatusTempLock;
    }

    function getAcStatusNonActive() {
        return $this->acStatusNonActive;
    }

    function getStatus() {
        return $this->status;
    }

    function getTime() {
        return $this->time;
    }

    function getOs() {
        return $this->os;
    }

    function getBrowser() {
        return $this->browser;
    }

    function getIp() {
        return $this->ip;
    }

    function setCustomerID($customerID) {
        $this->customerID = $customerID;
    }

    function setSuccess($success) {
        $this->success = $success;
    }

    function setPasswordIncorrect($passwordIncorrect) {
        $this->passwordIncorrect = $passwordIncorrect;
    }

    function setLocked($locked) {
        $this->locked = $locked;
    }
    
    function setUnlocked($unlocked) {
        $this->unlocked = $unlocked;
    }

    function setAcStatusTempLock($acStatusTempLock) {
        $this->acStatusTempLock = $acStatusTempLock;
    }

    function setAcStatusNonActive($acStatusNonActive) {
        $this->acStatusNonActive = $acStatusNonActive;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setTime($time=null) {
        if($time!=null) {
            $this->time = $time;
        } else {
            $this->time = time();
        }
    }

    function setOs($os=null) {
        if($os!=null) {
            $this->os = $os;
        } else {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $os_platform = "Unknown OS Platform";

            $os_array = array(
                    '/windows/i'            =>  'Windows',
                    '/mac/i'                =>  'MacOS',
                    '/linux/i'              =>  'Linux',
                    '/ubuntu/i'             =>  'Ubuntu',
                    '/iphone/i'             =>  'iPhone',
                    '/ipod/i'               =>  'iPod',
                    '/ipad/i'               =>  'iPad',
                    '/android/i'            =>  'Android',
                    '/blackberry/i'         =>  'BlackBerry',
                    '/webos/i'              =>  'Mobile'
                                );

            foreach ($os_array as $regex => $value) { 
                if (preg_match($regex, $user_agent))
                    $os_platform = $value;
            }   

            $this->os = $os_platform;
        }
    }

    function setBrowser($browser=null) {
        if($browser!=null) {
            $this->browser = $browser;
        } else {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $browser = "Unknown Browser";

            $browser_array  =   array(
                    '/msie/i'       =>  'Internet Explorer',
                    '/firefox/i'    =>  'Firefox',
                    '/safari/i'     =>  'Safari',
                    '/chrome/i'     =>  'Chrome',
                    '/opera/i'      =>  'Opera',
                    '/netscape/i'   =>  'Netscape',
                    '/maxthon/i'    =>  'Maxthon',
                    '/konqueror/i'  =>  'Konqueror',
                    '/mobile/i'     =>  'Handheld Browser',
                    '/edge/i'       =>  'Microsoft Edge'
                                );

            foreach ($browser_array as $regex => $value) {
                if (preg_match($regex, $user_agent)) 
                    $browser = $value;
            }

            $this->browser = $browser;
        }
    }

    function setIp($ip=null) {
        if($ip!=null){
            $this->ip = $ip;
        } else {
            $this->ip = file_get_contents('https://api.ipify.org');
        }
    }


}
