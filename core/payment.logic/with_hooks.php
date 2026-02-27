<?php
// @command: balafon --run .test/core/payment.logic/with_hooks.php
interface IPayment{
    public function initPayment($options);
}
class BitcoinPayment implements IPayment{
    public function initPayment($options)
    {
        throw new \Exception('Not implemented');
    }
}
class BancontactPayment implements IPayment{
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