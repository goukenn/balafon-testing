<?php
// @command: balafon --run .test/db/woh/firebase_check_select.php
use com\igkdev\projects\WOHApiController\System\Firebase\Traits\FirebaseTrait;
$ctrl = WOHApiController::ctrl(true);
class AutoSelect{
    use FirebaseTrait;
    public function getController(){
        global $ctrl;
        return  WOHApiController::ctrl(true);
    }
    public function check_selection(){
        $db = $this->_firebatabase();
        $ref = $db->getReference('clients');
        // $ref->push(['email'=>'ainfo@local.com', 'pos'=>1]);
        $c = $ref
        ->orderByChild("email")
        ->equalTo("ainfo@local.cossm")
        ->getSnapshot()->getValue();
        igk_wln_e($c);
    }
}
$cl = new AutoSelect;
$cl->check_selection();