<?php
// @command: balafon --run .test/core/payment.logic/with_hooks.php

/**
* auto generate doc.
*/
interface IPayment{

    /**
    * auto generate doc.
    * @param mixed $options
    */
    public function initPayment($options);
}

/**
* auto generate doc.
*/
class BitcoinPayment implements IPayment{

    /**
    * auto generate doc.
    * @param mixed $options
    */
    public function initPayment($options)
    {
        throw new \Exception('Not implemented');
    }
}

/**
* auto generate doc.
*/
class BancontactPayment implements IPayment{

    /**
    * auto generate doc.
    * @param mixed $options
    */
    public function initPayment($options)
    {
        throw new \Exception('Not implemented');
    }
}
igk_reg_hook('payment_filter', function($e){
    $tab = & $e->args['payments'];
    $tab[] = new BancontactPayment;
});
igk_reg_hook('payment_filter', function($e){
    $tab = & $e->args['payments'];
    $tab[] = new BitcoinPayment;
});
$tab = [];
igk_hook('payment_filter', ['payments'=>& $tab]);
igk_wln_e("list of payment ", $tab);