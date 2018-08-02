<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class PayPalAPI {

    public static function getApiContext(){
        $apiContext = new ApiContext(
                new OAuthTokenCredential(
                        PAYPAL_CLIENT_ID,
                        PAYPAL_SECRET
                )
        );

        $apiContext->setConfig([
                'mode'=>PAYPAL_MODE,
                'http.ConnectionTimeOut' => 20,
                'log.LogEnabled' => false,
                'log.FileName' => '',
                'log.LogLevel' => 'FINE',
                'validation.level' => 'log'
        ]);
        
        return $apiContext;
    }
    
}
