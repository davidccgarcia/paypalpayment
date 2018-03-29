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
        $payment = \PaypalPayment::payment()->setIntent('sale')
            ->setPayer($this->payer())
            ->setTransactions([$this->transaction()])
            ->setRedirectUrls($this->redirectURLs());

        try {
            $payment->create($this->_apiContext);
        } catch (Exception $e) {
            dd($e);
            exit(1);
        }

        return $payment;
    }

    /**
     * Returns payment's info
     *
     */
    public function payer()
    {
        return \PaypalPayment::payer()
            ->setPaymentMethod('paypal');
    }

    /**
     * Returns transaction's info
     *
     */
    public function transaction()
    {
        return \PaypalPayment::transaction()
            ->setAmount($this->amount())
            ->setItemList($this->items())
            ->setDescription('Tu compra en ssldigital')
            ->setInvoiceNumber(uniqid());
    }

    public function items()
    {
        $items = [];

        array_push($items, $this->paypalItem());

        return \PaypalPayment::itemList()->setItems($items);
    }

    public function paypalItem()
    {
        return \PaypalPayment::item()->setName('Certificado digital')
            ->setDescription('El siguiente es un certificado digital')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(5);
    }

    public function amount()
    {
        return \PaypalPayment::amount()
            ->setCurrency('USD')
            ->setTotal('5');
    }

    /**
     * 
     *
     */
    public function redirectURLs()
    {
        $baseURL = url('/');

        return \PaypalPayment::redirectUrls()
            ->setReturnUrl("$baseURL/payments/store")
            ->setCancelUrl("$baseURL/carrito");
    }

    /**
     *
     *
     */
    public function execute($paymentId, $payerId)
    {
        $payment = \PaypalPayment::getById($paymentId, $this->_apiContext);

        $execution = \PaypalPayment::PaymentExecution()
            ->setPayerId($payerId);

        return $payment->execute($execution, $this->_apiContext);
    }

}
