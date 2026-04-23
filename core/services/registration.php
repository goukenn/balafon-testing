<?php
// @command: balafon --run .test/core/services/registration.php
use IGK\Actions\Dispatcher;
use IGK\Actions\DispatcherService;
use IGK\Controllers\ApplicationController;
use IGK\Controllers\SysDbController;
use IGK\System\IInjectable;

/**
* auto generate doc.
*/
class DummyController extends ApplicationController{
}
$svg_module = igk_require_module('igk/svg');
/**
* auto generate doc.
*/
class EventService implements IInjectable{
    /**
    * .ctr
    * @param null|ublic $a
    */
    public function __construct(public $a=null){
        igk_wln("construct with a", $a);
    }
    /**
    * auto generate doc.
    */
    function dispatch(){
        igk_wln('dispatching....');
    }
}
/**
* auto generate doc.
*/
class A{
    /**
    * auto generate doc.
    * @param EventService $sr
    */
    function a(EventService $sr){
    }
}
$parameters = (new ReflectionMethod(A::class, 'a'))->getParameters();
igk_debug(true);
$tab = Dispatcher::GetInjectArgsByParameters($parameters, [], DummyController::ctrl(true));
$tab = Dispatcher::GetInjectArgsByParameters($parameters, []);
$tab2 = Dispatcher::GetInjectArgsByParameters($parameters, []);
$tab3 = Dispatcher::GetInjectArgsByParameters($parameters, [], $svg_module);
igk_wln_e("compare: ", $tab[0]->a , $tab2[0]->a, $svg_module->getDeclaredDir(), $tab3);
$tab2 = Dispatcher::GetInjectArgsByParameters($parameters, []);
$src = igk_app()->getService(EventService::class); 
$tab[0]->dispatch();
igk_wln_e('service is ? ', $src, $tab);