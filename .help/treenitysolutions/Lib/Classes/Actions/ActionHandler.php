<?php
namespace  treenitySolutions\Actions;
use IGK\Actions\ActionBase;

/**
* auto generate doc.
* @package treenitySolutions\Actions
*/
class ActionHandler extends ActionBase {

    /**
    * auto generate doc.
    * @var mixed
    */
    private static $sm_instance;
	///.get instance

    /**
    * auto generate doc.
    * @param null|mixed $ctrl
    */
    public static function getInstance($ctrl=null){
		if (!self::$sm_instance){
			self::$sm_instance = new ActionHandler();
			self::$sm_instance->ctrl = $ctrl;
		}
		return self::$sm_instance ;
	}
	///.ctr
	private function __construct(){
	}

    /**
    * auto generate doc.
    */
    public function question(){
		if(igk_qr_confirm()){
		}
	}
}