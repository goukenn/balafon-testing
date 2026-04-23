<?php
// @command: balafon --run .test/list-services.php
use IGK\Services\IAppService;
// + | BALAFON service : 
// + | a class that will be registered only a initialize only when need.

/**
* auto generate doc.
*/
class PHPCodeFormatterService implements IAppService{
    /**
     * initialize the service 
     * @return bool 
     */
    public function init($configs = null): bool { 
        return true;
    }
    /**
    * Called when an object is used as a function.
    */
    function __invoke()
    {
        igk_wln_e("invoke the service .... ");
    }
}
/**
* auto generate doc.
*/
class PHPMyCodeFormatterService implements IAppService{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $singleDefinitionPerFile;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $mergeConstants;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $removeComments;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $removePhpDocBlock;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $onlyDefinition;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $author;
    /**
    * auto generate doc.
    * @param null|mixed $configs
    * @return bool
    */
    public function init($configs = null): bool {
        is_null($configs) && igk_die('missing configurations');
        foreach($this as $k=>$v){
            $this->$k = igk_getv($configs, $k, $v);
        }
        return true;
    }
    /**
    * Called when an object is used as a function.
    */
    function __invoke()
    {
        igk_wln_e("invoke the service .... mys sample service .... ", $this);
    }
}
$app = igk_app();
$p = $app->getService('php-formatter');
$l = IGKServices::getInstance()->services();  
igk_wln_e(json_encode($l, JSON_PRETTY_PRINT));