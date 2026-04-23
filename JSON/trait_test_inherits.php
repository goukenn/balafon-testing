<?php
// + | --------------------------------------------------------------------
// + | check how to annotation Helper to get file used in class declaration
// + |
use igk\btmSyntax\Lib\Traits\PatternUsageTrait;
use igk\js\common\IJSStringify;
use IGK\System\Helpers\AnnotationHelper;

/**
* auto generate doc.
*/
trait AProp{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $a;
}
/**
* auto generate doc.
*/
trait BProp{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $b;
}
/**
* auto generate doc.
*/
class AClass{
    use AProp;
    use BProp;    
    use PatternUsageTrait;
}
/**
* auto generate doc.
*/
class BClass{
    use AProp, BProp;
}
$bas = AnnotationHelper::GetUses(AClass::class);
igk_wln_e("info , ", $bas);
$loader = array_unique($loader);
igk_wln_e($p, $utraist, "loader:", $loader);