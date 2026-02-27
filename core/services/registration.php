<?php
// @command: balafon --run .test/core/services/registration.php
use IGK\Actions\Dispatcher;
use IGK\Actions\DispatcherService;
use IGK\Controllers\ApplicationController;
use IGK\Controllers\SysDbController;
use IGK\System\IInjectable;
class DummyController extends ApplicationController{
}
$svg_module = igk_require_module('igk/svg');
class EventService implements IInjectable{
    public function __construct(public $a=null){
        igk_wln("construct with a", $a);
    }
    function dispatch(){
        igk_wln('dispatching....');
    }
}
// $service = DispatcherService::CreateOrGetServiceInstance(DummyController::ctrl(true), [EventService::class=>["@args"=>[2]]]);
// igk_wln_e("sample", $service);
class A{
    function a(EventService $sr){
    }
}
// IGKServices::getInstance()->__set(EventService::class, new EventService);
$parameters = (new ReflectionMethod(A::class, 'a'))->getParameters();
// une manière d'obtenir / forcer la création d'une instance de service IInjectable 
// $service = DispatcherService::CreateOrGetServiceInstance(SysDbController::ctrl(true), [EventService::class=>["@args"=>[2]]]);
igk_debug(true);
$tab = Dispatcher::GetInjectArgsByParameters($parameters, [], DummyController::ctrl(true));
$tab = Dispatcher::GetInjectArgsByParameters($parameters, []);
$tab2 = Dispatcher::GetInjectArgsByParameters($parameters, []);
$tab3 = Dispatcher::GetInjectArgsByParameters($parameters, [], $svg_module);
igk_wln_e("compare: ", $tab[0]->a , $tab2[0]->a, $svg_module->getDeclaredDir(), $tab3);
$tab2 = Dispatcher::GetInjectArgsByParameters($parameters, []);
// require only IAppService
$src = igk_app()->getService(EventService::class); // null car EventService n'est pas un IAppService
$tab[0]->dispatch();
igk_wln_e('service is ? ', $src, $tab);