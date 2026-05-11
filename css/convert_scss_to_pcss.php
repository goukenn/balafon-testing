<?php
use IGK\Css\CssConverter; 
use IGK\System\IO\File\PHPScriptBuilder;
use IGK\System\IO\StringBuilder;
use IGK\Tests\Css\CssScssToPhpMethodHandler;

$file = igk_getv($params, 0);
if (!$file || !file_exists($file)) {
    igk_die("file is missing. expect pcssfile to be converted to .pcss");
}
$sb = new StringBuilder;
$data = CssConverter::ParseFormSCSS($file);
$builder = new PHPScriptBuilder;
$builder->type("function");
$def = new StringBuilder;
$props = new StringBuilder;
$media = "";
$color = "";
/**
* auto generate doc.
* @param mixed $src
* @return mixed
*/
function inline($src){
    return implode('', array_map('trim', explode("\n", $src)));
}
foreach($data as $k=>$d){
    if (empty($d)){
        continue;
    }
    switch($k){
        case CssConverter::MEDIA_KEYFRAME_KEY:
            foreach($d as $k=>$v){
                if (strpos($k, "@")===0){
                    foreach($v as $kk=>$vv){
                        $def->appendLine('$def->addRule(\''.$k.
                         ' '.$kk.'\', "'. sprintf(inline(sprintf('%s', $vv))).'");');    
                    }
                }else{
                    $def->appendLine('$def->addRule(\'@keyframes'.
                         ' '.$kk.'\', "'. sprintf(inline(sprintf('%s', $v))).'");');                       
                }
            }
            break;
        case CssConverter::MEDIA_KEY:
            foreach($d as $cond=>$data){
                $def->appendLine('$media = $theme->reg_media("'.$cond.'");');
                foreach($data as $rr=>$r){
                    if (!empty($s = trim(implode("; ", array_map(function($a,$b){ return implode(':', [$b, $a]); }, $r, array_keys($r)))))){
                        $s .= ';';
                        $def->appendLine('$media[\''.$rr.'\'] = \''.addslashes($s).'\';');
                    }
                }
            }
            break;
            case CssConverter::MEDIA_VARIABLES_KEY:
                foreach($d as $cond=>$data){                    
                    if (strpos($data,'$')===0 ){
                        $p = igk_getv($d, substr($data,1));
                        $data = $p;
                    }
                    $data = rtrim($data,"'");
                    $props->appendLine('$prop[\''.$cond.'\'] = \''.addslashes($data).'\';');                    
                }
                break;
        default:
            if (!empty($s = trim($s = implode("; ", array_map(function($a,$b){ return implode(':', [$b, $a]); }, $d, array_keys($d)))))){
                $s.= ";";
                $def->appendLine('$def[\''.$k.'\'] = \''.addslashes($s).'\';');
            }
        break;
    }
}
$s = (<<<'PHP'
/**
 * @var mixed $def
 * @var mixed $prop
 */

PHP);
if ($p = $props.''){
    $s .= $p.PHP_EOL;
}
if ($p = $def.''){
    $s .= $p.PHP_EOL;
}  
$builder->defs($s); 
echo "".$builder->render();
igk_wln_e("done"); 