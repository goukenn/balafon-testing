<?php
// @author: C.A.D. BONDJE DOUE
// @filename: Untitled-1
// @date: 20251221 20:04:01
// @desc: auto inject value on user 
// @command: balafon --run .test/phonebooks/user_display.php --user:login
use IGK\Models\Users;
use IGK\Services\IAppService;
use IGK\System\Console\Logger;
use IGK\System\Http\Request;
use IGK\System\IInjectable;
use IGK\System\Services\Traits\ServicePropertyTrait;

/**
* auto generate doc.
*/
interface IUserDisplay extends IInjectable , IAppService
{
    /**
    * auto generate doc.
    * @param Users $user
    * @return string
    */
    function render(Users $user):string;
}
/**
* auto generate doc.
*/
class UserDisplay  implements IUserDisplay{
    use ServicePropertyTrait {
        getConfigurableProperties as getConfigurablePropertiesTrait;
    }
    /**
    * auto generate doc.
    * @var mixed
    */
    var $x;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $t;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $s; 
    /**
     * defini users
     * @var Users
     */
    var $user;
    /**
    * .ctr
    * @param int $i
    * @param IFooService $foo
    * @param Request $request
    * @param IFooService $faa
    */
    public function __construct(protected int $i, 
    IFooService $foo,
    Request $request,
    IFooService $faa
    )
    { 
        igk_wln($foo, $faa, $request, $foo === $faa);
    }
    /**
    * auto generate doc.
    * @param Users $user
    * @return string
    */
    function render(Users $user):string{
     return   $this->i.' : vs '. $this->x.'='. $user->clGuid.':'.$user->clLogin;
    }
    /**
    * auto generate doc.
    * @return array
    */
    public function getConfigurableProperties(): array
    {
        $p = $this->getConfigurablePropertiesTrait();
        $p['user']->required = true;
        return $p;
    }
    /**
    * auto generate doc.
    * @param null|Users $user
    */
    public function setT(?Users $user){
        $this->t = $user;
    }
    /**
    * auto generate doc.
    * @param Users $user
    */
    public function setUser(Users $user){
        $this->user = $user; 
    }
}
/**
* auto generate doc.
*/
interface IFooService extends IInjectable{
}
/**
* auto generate doc.
*/
class FooService implements IFooService{
    /**
    * .ctr
    * @param string $x
    */
    public function __construct(string $x)
    {
        Logger::info('create foo service '.$x);
    }
}
/**
* auto generate doc.
*/
class FaaService implements IFooService{
    /**
    * .ctr
    * @param mixed $x
    */
    public function __construct($x)
    {
        Logger::info('create faa service '.$x);
    }
}
IGKServices::Register(IUserDisplay::class, UserDisplay::class
, [
   '@args'=>[
        3,
       FooService::class,
   ],
    'user'=>'{75B203A4-3555-8261-31F2-69055A1A8D3F}',
]);
Users::registerMacro('display', function(IUserDisplay $display){
    return $display->render($this);
}); 
igk_debug(true);
$s = $user->display();  
Logger::print('display: '.$s);
igk_wln_e('done');