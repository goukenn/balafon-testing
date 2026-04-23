<?php
// @command: balafon --run .test/db/woh/firebase_check_select.php
use com\igkdev\projects\WOHApiController\System\Firebase\Traits\FirebaseTrait;

$ctrl = WOHApiController::ctrl(true);
/**
* auto generate doc.
*/
class AutoSelect{
    use FirebaseTrait;
    /**
    * auto generate doc.
    */
    public function getController(){
        global $ctrl;
        return  WOHApiController::ctrl(true);
    }
    /**
    * auto generate doc.
    */
    public function check_selection(){
        $db = $this->_firebatabase();
        $ref = $db->getReference('clients');
        $c = $ref
        ->orderByChild("email")
        ->equalTo("ainfo@local.cossm")
        ->getSnapshot()->getValue();
        igk_wln_e($c);
    }
}
$cl = new AutoSelect;
$cl->check_selection();