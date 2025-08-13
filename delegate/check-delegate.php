<?php
// @command: balafon --run .test/delegate/check-delegate.php
// # phpdelegate = 

use Google\Service\Spanner\Delete;
use IGK\System\Delegate;
 
class ActionListener extends Delegate{
    /**
     * 
     * @param null|int $x some data 
     * @param null|int $y inline y
     * @return void 
     * @throws Exception 
     */
    public function __invoke(?int $x=null, ?int $y=null){
        igk_assert_die((($c =func_num_args())!=2), sprintf('require parameters: %s got %s', 2, $c));
        call_user_func_array([parent::class, __FUNCTION__], func_get_args());
    }
}

$actionEventHandler = ActionListener::CreateDelegate(function(){
    igk_wln("action list");
});
$actionEventHandler->add($fc1 = function(){
    igk_wln('loading sample delete');
});

$actionEventHandler->add($fc1);

$actionEventHandler(10,3);

$actionEventHandler->remove($fc1);


$actionEventHandler(12,30);



igk_exit();