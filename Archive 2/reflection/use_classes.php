<?php
// balafon --run .test/reflection/use_classes.php
use IGK\System\IO\StringBuilder;
$ctrl = igk_getv($params, 0) ?? igk_die('missing controller params');
$class  = igk_getv($params, 1) ?? igk_die('missing class name');
$ctrl = igk_getctrl($ctrl);
$ctrl->register_autoload();
if ($class=='='){
    $class = get_class($ctrl);
}else{
    $class = $ctrl->resolveClass($class) ?? igk_die(sprintf('class resolution failed: [%s]', $class));
}
if (!$class || !class_exists($class, false)){
    igk_die('missing class ', $class);
}
function test_reflection_use_classes_readFileHeader(string $file, & $info = null){
    $tokens = token_get_all(file_get_contents($file), 0);
    $sb = new StringBuilder;
    $info = [
        'namespace'=>null
    ];
    $exclude = [
        T_CLASS,
        T_INTERFACE,
        T_TRAIT,
        T_ABSTRACT,
        T_PUBLIC,
    ];
    $rns = 0;
    while(count($tokens)){
        $v = $q = array_shift($tokens);
        // allow top namespace read on use read outside class-trait-interface or function declration 
        if (is_array($q)){
            $v= $q[1];
            $q = $q[0];
        }        
        if (in_array($q, $exclude)){
            break;
        }
        switch($q){
            case T_NAMESPACE:
                $rns = 1;
                break;
            case T_NAME_QUALIFIED: // 265 
                if ($rns){
                    $info['namespace'] = $v;
                    $rns = 0;
                }
                break;
        }
        $sb->append($v);
    }
    return $sb;
}
$ref = new \ReflectionClass($class);
igk_wln("file : ".$ref->getFileName());
$content  = test_reflection_use_classes_readFileHeader( $ref->getFileName() ); 
$v_result = [];
if ($v = preg_match_all("/use\s+(?P<name>[^\s;]+)(\s+as\s+(?P<alias>[^\s+;]+))?/im", $content, $tab)){
    for($i = 0; $i < $v ; $i++){
        $n = $tab['name'][$i];
        $a = $tab['alias'][$i];
        $v_result[$n] = empty($a) ? $n : $a;
    }
}
igk_wln_e(
    $v_result
);
igk_exit();