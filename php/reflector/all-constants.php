<?php
// @author: C.A.D. BONDJE DOUE
// @filename: all-constants.php
// @date: 20250722 19:03:24
// @desc: get all library constants
// @command: balafon --run test/php/reflector/all-constants.php

use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

$n = 'sample';
define($n, "12");
$regex = new RegexMatcherContainer;
$block = $regex->begin('<\?php\\b', '(\?>)', 'php-start')->last();
$regex->autoStore = false;

$tc = [
    $regex->appendStringDetection('string',true)->last(),
    $regex->appendMultilineComment()->last(),
    $regex->appendSingleLineComment()->last(),    
];
RegexMatcherUtility::AppendPhpHereDoc($regex, $tc);

$patterns = [
  ...$tc
];
$cond = $regex->createPattern(['begin'=>'\(', 'end'=>'\)','tokenID'=>'cond']); 
$defined = $regex->begin('(?=\\bdefine\\b)', '(?<=\))', 'defined')->last();

$subblock = $regex->createPattern(['begin'=>'\(', 'end'=>'\)','tokenID'=>'subblock']);
$subblock->patterns = [
    ...$tc,
    $subblock 
];
$cond->patterns = [
    ...$tc,
    $regex->createPattern(['begin'=>'(?=,)', 'end'=>'(?=\))','tokenID'=>'extra-arg', 'patterns'=>[
        $regex->createPattern(['match'=>",", 'tokenID'=>'s-extra-arg']),
        ...$tc,
        $subblock
    ]]), 
    $cond
];
$defined->patterns = [
    $regex->createPattern(['match'=>"\\bdefine\\b", 'tokenID'=>'s-define']),
    ...$tc,
    $cond,
    
];

$patterns[] = $defined;
$regex->autoStore = true;

$block->patterns = $patterns;
$constants = [];
$dir = igk_getv($params, 0) ?? IGK_LIB_DIR; 
try{
IO::GetFiles($dir, function($f)use($regex, & $constants){
    if (!preg_match('/\.(php)$/', $f)){
        return;
    }
    igk_is_debug() && Logger::info('treat : '.$f);
    // $f = '/Volumes/Data/Dev/PHP/balafon2/src/Lib/igk/Lib/Classes/System/Console/Commands/Projects/CreateUserProfileClassCommand.php';
    $src = file_get_contents($f);
    $pos = 0;
    $regex->resetTreatment();
    $litteral = [];
    $start = false;
    while($g = $regex->detect($src,$pos)){
        if ($e = $regex->end($g, $src, $pos)){
            $tid = $e->tokenID;
            igk_is_debug() && Logger::info('tokendID: '.$tid.'['.$e->value.']');
            if ($tid == 's-define'){
                $start = true;
            }
             if ($tid=='s-extra-arg'){
                $start = false;
            }
            if ($e->tokenID=='defined'){
                //Logger::danger('tokendID::: '.$tid);
                if ($litteral){ 
                    $cc = igk_str_remove_quote($litteral[0]);
                    if (!preg_match("/^[A-Z_0-9]+$/", $cc)){
                        igk_wln_e("failed : ", $cc, 
                        igk_io_collapse_path($f)
                        );
                    }

                    $constants[$cc] = 1;
                } else{
                    igk_is_debug() && Logger::warn('/!\ defined litteral '. $e->value);
                }
                $litteral =[];
                $start = false;
            }
            if ( $start && ($e->tokenID=='string')){
                $litteral[] = $e->value;
            }
          
        }
    };

    //  throw new Exception("d");
}, true);
}
catch(\Exception $ex){

}

$constants = array_keys($constants);
sort($constants);

echo json_encode(compact('constants'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
Logger::success('done');
igk_exit();