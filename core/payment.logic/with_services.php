<?php
// @command: balafon --run .test/core/payment.logic/with_services.php
use IGK\Services\IAppService;
use IGK\Services\IAppServiceContainer;
use IGK\System\IO\DotEnvConfiguration;
use IGK\System\Services\Traits\ServiceContainerTrait;
use IGK\System\Services\Traits\ServicePropertyTrait;
use IGK\Models\Users;
interface IPaymentService extends IAppService{
    function initPayment($options);
}
class PayPalPayment implements IPaymentService
{
    use ServicePropertyTrait;
    var $apiKey;
    public function __construct(private ?Users $user)
    {
    }
    public function initPayment($options)
    {
        igk_wln('start payment with paypal');
    }
    /**
     * 
     * @return \IGK\System\Services\IAppServiceProperty[] 
     */
    public function getConfigurableProperties(): array
    {
        return [
            'apiKey'=>(object)[
                'required'=>true,
                'type'=>'string',
                'description'=>'paypal reference api key'
            ]
        ];
    }   
}
class VisaPayment implements IPaymentService{
     use ServicePropertyTrait;
    public function initPayment($options)
    {
        igk_wln_e('init payment with VISA');
    }
}
class PaymentServiceContainer implements IAppServiceContainer
{
    use ServiceContainerTrait {
        register as traitRegister;
    } 
    public function getConfigurableProperties(): array
    {
        return [];
    }
    public function init($configs = null): bool
    {
        return true;
    }
    public function register(string $name, IAppService $service): bool
    {
        if ($service instanceof IPaymentService){
            return $this->traitRegister($name, $service);
        }
        return false;
    }
}
//$l = DotEnvConfiguration::Get('PAYPAL_API_KEY');
//igk_wln_e(__FILE__.":".__LINE__ , $l);
# 1. system register first a service container for payment
IGKServices::Register('payment', PaymentServiceContainer::class);
IGKServices::Register('payment.paypal', PayPalPayment::class);
IGKServices::Register('payment.visa', VisaPayment::class);
 $container = IGKServices::Get('payment');
 $all = [];
 foreach($container->listServicesKeys() as $m){
    $all[] = IGKServices::Get($container->getName().'.'.$m);
 }
$r = IGKServices::Get('payment.paypal');
// //$r = IGKServices::Get('payment.visa');
igk_wln($container, $r);
$r->initPayment([]);
/**
 * container number of initiated service payment 
 */
igk_wln_e('list of initialized payment setting', $container->count(), $container->listServicesKeys(), $all);
// return [
//     'payment.paypal'=>[
//         PayPalPayment::class
//     ]
// ];