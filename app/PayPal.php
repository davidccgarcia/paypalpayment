<?php

namespace App;

class PayPal
{
    private $_apiContext;
    private $shopping_cart;
    private $_clientId = "AaRATwQHX0XwzHPGRWv3zHLEiKrVRLwEwZuLyKnXgmvpvwOvdg2UM0X8Weijo8X7rRxaW8NMULMA_DjW";
    private $_clientSecret = "EG_LkpSkZZ2pBdNbX_z8kB_9ORqUsdPQ1VNvg0ljnXUGimkryqcEQ_s1Tz-VLPWqMo96XNEZW9qCBANP";

    public function __construct($shopping_cart)
    {
        $this->_apiContext = \PaypalPayment::ApiContext($this->_clientId, $this->_clientSecret);
        $config = config("paypal_payment");
        $flatConfig = array_dot($config);

        $this->_apiContext->setConfig($flatConfig);
        $this->shopping_cart = $shopping_cart;
    }

    public function generate()
    {
        
    }

}
