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

interface IUserDisplay extends IInjectable , IAppService
{
    function render(Users $user):string;
}
class UserDisplay  implements IUserDisplay{
    use ServicePropertyTrait {
        getConfigurableProperties as getConfigurablePropertiesTrait;
    }
    var $x;
    var $t;
    var $s; 
    /**
     * defini users
     * @var Users
     */
    var $user;
    public function __construct(protected int $i, 
    //Users
    IFooService $foo,
    Request $request,
    IFooService $faa
    )
    { 
        igk_wln($foo, $faa, $request, $foo === $faa);
    }
    function render(Users $user):string{
     return   $this->i.' : vs '. $this->x.'='. $user->clGuid.':'.$user->clLogin;
    }
    public function getConfigurableProperties(): array
    {
        $p = $this->getConfigurablePropertiesTrait();
        $p['user']->required = true;
        return $p;
    } 
    
    public function setT(?Users $user){
        $this->t = $user;
    }
    public function setUser(Users $user){
        $this->user = $user; 
    }
}

interface IFooService extends IInjectable{

}
class FooService implements IFooService{
    public function __construct(string $x)
    {
        Logger::info('create foo service '.$x);
    }
}

class FaaService implements IFooService{
 public function __construct($x)
    {
        Logger::info('create faa service '.$x);
    }
}

// system register class service to all definition 
// inject manually an IUserDisplay contract with a concrete class  
IGKServices::Register(IUserDisplay::class, UserDisplay::class
, [
   '@args'=>[
        3,
        //'cbondje@igkdev.com',
       FooService::class,
       //FaaService::class
   ],
    // 't'=>'hello my friend',
    // 'x'=>4879,
    //'user'=>'cbondje@igkdev.com',
    'user'=>'{75B203A4-3555-8261-31F2-69055A1A8D3F}',
    //'user'=>'cbondje@igkdev.com',
]);

// $l = IGKServices::Register(IUserDisplay::class, UserDisplay::class, [
//     540,
//     'x'=>12
// ]);


Users::registerMacro('display', function(IUserDisplay $display){
    return $display->render($this);
}); 
igk_debug(true);
$s = $user->display();  

Logger::print('display: '.$s);

igk_wln_e('done');
