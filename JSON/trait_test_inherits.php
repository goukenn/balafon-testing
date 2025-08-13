<?php
// + | --------------------------------------------------------------------
// + | check how to annotation Helper to get file used in class declaration
// + |
use igk\btmSyntax\Lib\Traits\PatternUsageTrait;
use igk\js\common\IJSStringify;
use IGK\System\Helpers\AnnotationHelper; 
trait AProp{
    var $a;
}
trait BProp{
    var $b;
}
class AClass{
    use AProp;
    use BProp;    
    use PatternUsageTrait;
}
class BClass{
    use AProp, BProp;
}
$bas = AnnotationHelper::GetUses(AClass::class);
igk_wln_e("info , ", $bas);
// $cl = new ReflectionClass(AClass::class);
// $source = $cl->getFileName();
// $utraist = $cl->getTraitNames() ;
// $iface = $cl->getInterfaceNames();
// $utraist = array_merge($utraist,  $iface);
// $p = new ReflectionProperty(AClass::class, 'b');
// $p = new ReflectionProperty(BClass::class, 'b');
// $mm = $cl->getProperty("patterns");
// $loader = [];
// // load traits
// array_map(function($a) use (& $loader, $source){
//     $v_p = igk_sys_reflect_class($a); // new ReflectionClass($a);
//     $v_tf= $v_p->getFileName();
//     if ($v_tf && ($v_tf!= $source)){
//         // get User 
//         $usages = AnnotationHelper::GetUses($a);
//         if ($usages)
//             $loader = array_merge($loader, $usages); 
//     }
// },$utraist); 
$loader = array_unique($loader);
igk_wln_e($p, $utraist, "loader:", $loader);