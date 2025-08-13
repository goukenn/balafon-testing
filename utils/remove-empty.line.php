<?php
// @command: balafon --run .test/utils/remove-empty.line.php
// @desc: remove empty line 
// 
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
/**
 * glus js code 
 * @param string $code 
 * @return string 
 * @throws IGKException 
 * @throws Exception 
 */
function glue_js_code(string $code){
    $o = '';
    $v_rgx = new RegexMatcherContainer;
    $v_rgx->begin('(`)([^`]*)', "\\1");
    $offset = 0;
    $p = 0;
    while($g = $v_rgx->detect($code, $offset)){
        if ($e = $v_rgx->end($g, $code, $offset)){
            $sb = substr($code, $p, $e->from - $p);
            $o .= glue_rm_empty($sb) . $e->value; 
            $p = $e->to; //$offset;
        }
    }
    if ($p<strlen($code)){
        $o .= glue_rm_empty(substr($code, $p));
    }
    return $o; 
}
function glue_php_code(string $code){
    $o = '';
    $v_rgx = new RegexMatcherContainer;
    $v_rgx->begin('<<<(\'|")?([a-zA-Z][a-zA-Z_]*)\\1?', "^\\2\\b");
    $offset = 0;
    $p = 0;
    while($g = $v_rgx->detect($code, $offset)){
        if ($e = $v_rgx->end($g, $code, $offset)){
            $sb = substr($code, $p, $e->from - $p);
            $o .= glue_rm_empty($sb) . $e->value; 
            $p = $e->to; //$offset;
        }
    }
    if ($p<strlen($code)){
        $o .= glue_rm_empty(substr($code, $p));
    }
    return $o;
}
function glue_rm_empty($content){
   return  implode("\n", array_filter(explode("\n", $content), function ($s) { 
        return (strlen(trim($s))!==0); 
    }));
}
function transform($file){
$content = file_get_contents($file);
    $ext = igk_io_path_ext($file);
    switch ($ext) {
        case 'js':
            $g = glue_js_code($content);
            break;
        case 'php':
        case 'phtml':
        case 'pinc':
            // just glue code without <<< 
            $g = glue_php_code($content);
            break;
        default:
            $g = glue_rm_empty($content);
            break;
    }
    igk_io_w2file($file, $g);
}
$file = igk_getv($params, 0);
if ($file && file_exists($file)) {
    if (is_dir($file)){
        $files = IO::GetFiles($file, "/\.(js|php|phtml)$/", true);
        foreach($files as $file){
            Logger::info('treat: '.$file);
            transform($file);
        }
    }
    else{
        transform($file);
    }
    Logger::success("done");
} else {
    Logger::danger("request existing file!");
}
igk_exit();