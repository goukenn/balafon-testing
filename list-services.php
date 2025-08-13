<?php
// @command: balafon --run .test/list-services.php
use IGK\Services\IAppService;
// + | BALAFON service : 
// + | a class that will be registered only a initialize only when need.
class PHPCodeFormatterService implements IAppService{
    /**
     * initialize the service 
     * @return bool 
     */
    public function init($configs = null): bool { 
        return true;
    }
    function __invoke()
    {
        igk_wln_e("invoke the service .... ");
    }
}
class PHPMyCodeFormatterService implements IAppService{
    var $singleDefinitionPerFile;
    var $mergeConstants;
    var $removeComments;
    var $removePhpDocBlock;
    var $onlyDefinition;
    var $author;
    public function init($configs = null): bool {
        is_null($configs) && igk_die('missing configurations');
        foreach($this as $k=>$v){
            $this->$k = igk_getv($configs, $k, $v);
        }
        return true;
    }
    function __invoke()
    {
        igk_wln_e("invoke the service .... mys sample service .... ", $this);
    }
}
// IGKServices::Register('php-formatter', PHPCodeFormatterService::class);
// IGKServices::Register('php-formatter', PHPMyCodeFormatterService::class);
$app = igk_app();
$p = $app->getService('php-formatter');
$l = IGKServices::getInstance()->services();  
// $p();
igk_wln_e(json_encode($l, JSON_PRETTY_PRINT));