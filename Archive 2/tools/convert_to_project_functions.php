<?php
// convert php file with function to protected function list 
// need to use token to detected global function. 
use IGK\System\Console\Logger;
use IGK\System\IO\File\PHPScriptBuilder;
use IGK\System\IO\StringBuilder;
!isset($command) && igk_die("command required");
$file = igk_getv($params, 0) ?? igk_die("need a files");
$src = file_get_contents($file);
$tokens = token_get_all($src);
$depth = 0;
$functions = [];
$read = false;
$read_content = false;
$skip = false;
$read_comment = '';
$read_here_docs = false;
$here_docs = [];
$here_key = '@__:here_content__';
$mode_skip = 0;
$usings = [];
$read_use = 0;
$namespace = null;
$reader = null;
$outside = '';
$char_ = '';
$coding = 1;
$offset = 0;
$anonymouse_level = 0;
$lf  = '';
while (count($tokens) > 0) {
    $q = array_shift($tokens);
    $v = $e = $q;
    if (is_array($q)) {
        $e = $q[0];
        $v = $v[1];
    } else {
        $e = -1;
    }
    $offset += strlen($v);
    igk_debug_wln("token name ".token_name($e)." v: ".$v);
    if ($reader)
    {
        if (!$reader($e, $v, $depth)){
            $reader = null;
        }
        continue;        
    }
    if ($read_use == 2){
        if ($v==';'){
            $read_use=0;
            $skip = false;
        }else{
            $usings[count($usings)-1] .= $v;
        }
        continue;
    }
    if ($char_ == $v){
        $char_ = '';
        $v = '';
        continue;
    }
    if ($read_here_docs){
        $content .= $v;
        if ($e == T_END_HEREDOC){            
            $content = & $functions[$name]->content;
            $read_here_docs = false;
        }
        continue;
    }
    else if ($read_content){
        $content .= $v;
    }
    switch ($e) {
        case 393:
        case T_START_HEREDOC:
            if ($read_content){
                //
                $read_here_docs = true;
                $functions[$name]->here_docs[] = ''; 
                $idx = count($functions[$name]->here_docs)-1;
                $content .= $here_key.$idx.'@';
                $content = & $functions[$name]->here_docs[$idx];
            }
            break;
        case 397:
            $mode_skip = 1; // "::"
            break;
        case T_CLASS:
            if (!$mode_skip){
                igk_die("convert do not support class definition");
            }
            break;
        case T_FUNCTION:
            $read = !$skip && ($depth == 0) && ($anonymouse_level==0); 
            if ($skip &&  ($depth == 0)){
                if ($read_use == 1){
                    $read_use = 2;
                    $usings[] = $v; 
                    $v = '';
                }
            }
            if ($read){
                $v = '';
            }
            $skip = false;
            break;
        case T_COMMENT:
            if ($depth==0){
                $read_comment .= $v.PHP_EOL;
                $v = '';
            }
            break;
        case T_DOC_COMMENT:
            if ($depth==0){
                $read_comment .= $v;
                $v = '';
            }
            break;
        case T_USE:
            if ($depth == 0 ){
                $skip = true;
                $read_use = 1;
            }
            break;
        case T_NAME_QUALIFIED:
            if ($read_use == 1){
                $usings[] = $v;
                $read_use = 0;
                $v = '';
                $char_ = ';';
                $skip = false;
            }
            break;
        case T_NAMESPACE:
            $reader = function($e, $v)use (& $namespace){
                if ($e==T_STRING){
                    $namespace = $v; 
                } else if (($v=='{') || ($v==';')){
                    return false;
                }
                return true;
            };
            break;
        case T_STRING:
            if ($read_use == 1 )
            {
                if ($depth == 0){
                    $usings[] = $v;
                }
                $read_use = 0;
                $v = '';
                break;
            }
            if ($read && ($depth == 0)) {
                $functions[$v] = (object)['comment'=>$read_comment, 'content'=>'', 'here_docs'=>[]];
                $name = $v;
                $content = & $functions[$v]->content;
                $read_content = true;
                $v = '';
            }
            $read_comment  = '';
            $read = false;
            break;
        default:
            switch ($v) {
                case '(':
                    // to avoid anonymous
                    $anonymouse_level++;
                    break;
                case ')':
                    $anonymouse_level--;
                    break;
                case "{":
                    $depth++;
                    if ($e != T_CURLY_OPEN){
                    } 
                    break;
                case "}":
                    if ($e!= T_ENCAPSED_AND_WHITESPACE){
                        $depth--;
                    }
                    if (($depth<=0) && ($read_content)){
                        unset($content);
                        $content = '';
                        $read_content = false;
                        $v = '';                     
                    }
                    break;
            }
            break;
    }
    if (!$read_content && !$read && !$read_use){
        if ($e == T_OPEN_TAG){
            if ($coding==1){
                $v='';
                $coding = 2;
            }
        } 
        if (!empty(trim($v)) || is_numeric($v)){
            $outside = rtrim($outside.$v);
            $lf = '';
        }
        else{
            if (strlen($v)>0){
                $plf = $lf;
                $lf = substr(rtrim($v, " \r\t"), -1) == "\n" ? "\n" : " ";
                if ($plf == $lf) 
                    $lf = ' ';
               $outside = rtrim($outside).$lf;
            }
        }
    }
} 
$keys = array_keys($functions);
sort($keys);
echo implode("\n", $keys) . PHP_EOL;
if ($export_file = igk_getv($command->options, "--export")){
    Logger::info("exporting to file : ".realpath($export_file));
    $v_osrc = new StringBuilder;
    $_append_tab = function ($src, $d=1){
        return implode(str_repeat("\n\t", $d),explode("\n", $src));
    };
    sort($usings);
    foreach($usings as $u){
        $v_osrc->appendLine("use ".$u.";");
    }
    foreach($keys as $u){
        $v_osrc->appendLine('if (!function_exists("'.$u.'")){');
            if ($functions[$u]->comment){
                $v_osrc->appendLine("\t".$_append_tab($functions[$u]->comment));    
            }else{
                $v_osrc->appendLine("\t".'///<summary>function </summary>');
                $v_osrc->appendLine("\t".'/**');
                $v_osrc->appendLine("\t".'* function __desc__');
                $v_osrc->appendLine("\t".'*/');
            }
            $content = $functions[$u]->content;
            $content = "\t".implode("\n\t",explode("\n", "function ".$u.$content));
            if ($doc = $functions[$u]->here_docs){
                foreach($doc as $k=>$v){
                    if (preg_match("/\s*".$here_key.$k.'@/', $content, $tab_r)){
                        // preg replace consider backspace 
                        $content = str_replace($tab_r[0],  "\n".$v, $content);
                    }
                } 
            } 
            $v_osrc->appendLine($content);
        $v_osrc->appendLine('}');
    }
    if ($outside){
        $v_osrc->append("\n".rtrim($outside));
    }
    $builder = new PHPScriptBuilder;
    $builder->type('function')
    ->namespace($namespace)
    ->defs($v_osrc.'');
    igk_io_w2file($export_file, $out = $builder->render());
    igk_debug_wln($out);
    Logger::success("done exporting");
}
igk_exit();