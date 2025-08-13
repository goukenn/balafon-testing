<?php


namespace  treenitySolutions\Actions;

use IGK\Actions\ActionBase;

class ActionHandler extends ActionBase {
	private static $sm_instance;
	 
	
	///.get instance
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
	
	
	public function question(){
		if(igk_qr_confirm()){
			
		}
	}
	
	
}