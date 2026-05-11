<?php
// @author: C.A.D. BONDJE DOUE
// @filename: auto.func.inclusion.php
// @date: 20260228 13:56:43
// @desc: demonstration of auto inclusion
// @command: balafon --run .test/core/modules-concepts/auto.func.inclusion.php
use IGK\System\Console\Logger;
use IGK\System\Modules;
use IGK\System\Modules\ModuleIncludeDefinitionUtility;
use IGK\System\Modules\Traits\ModuleIncludeDefinitionInvokeTrait;
/**
* auto generate doc.
* @package
*/
class InclusionType{
    use ModuleIncludeDefinitionInvokeTrait;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    const PAS_DUR='in ref';
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    private $m_refList;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    public $pList = 'litteral';
    /**
    * .ctr
    * @return void
    */
    public function __construct()
    {
       $this->m_refList = [];  
    }
    /**
    * auto generate doc.
    * @return void
    */
    protected function &getInvocationList()
    {
        return $this->m_refList;
    }
    /**
    * auto generate doc.
    * @return mixed
    */
    public function & getRefListReference(){
        $c = & $this->m_refList;
        return $c;
    }
    /**
    * Triggered when calling an inaccessible or undefined method on an object.
    * @param mixed $name
    * @param mixed $arguments
    * @return void
    */
    public function __call($name, $arguments){
        return $this->invokeInclusion($name, $arguments);       
    }
}
define('PAS_DUR', 'dur constant');
$file = '/Volumes/Data/wwwroot/core/Packages/Modules/ionicons/.module.pinc';
$g = new InclusionType;
$ref = & $g->getRefListReference(); 
$m = igk_require_module('ionicons');
$caches = ModuleIncludeDefinitionUtility::BindFile($file, $ref);
$g->b(12);
$fl = null;
igk_wln_e(__FILE__.":".__LINE__ , $caches, $fl);
Logger::success('done');
igk_exit();