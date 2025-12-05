<?php
// @author: C.A.D. BONDJE DOUE
// @filename: detect_g_functions.php
// @date: 20250729 22:46:14
// @desc: encrypt 

// @command: balafon --run .test/module/igk.phpFormatter/detect_g_functions.php [dir]

use IGK\Helper\IO;
use IGK\Helper\StringUtility;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

$dir = igk_getv($params, 0) ?? IGK_LIB_DIR;
$regex = new RegexMatcherContainer;
$pos = 0;
// define
$regex->match('<\?php', 'f-php-start');
$regex->match('\?>', 'f-php-stop');
$here_doc = [];
RegexMatcherUtility::AppendPhpHereDoc($regex, $here_doc);
$_doc_comment = $regex->appendCommentDocBlock()->last();
$line_comment = $regex->appendSingleLineComment("\\h*\/\/")->last();
$string = $regex->appendStringDetection('string', true)->last();
$_multi_comment = $regex->appendMultilineComment()->last();
$_depth_comment = $regex->match('(^\\h*\/\/.+)', 'depth-line-comment')->last();

$regex->match('\$[a-zA-Z_][a-zA-Z_0-9]*', 'f-var');
$regex->match('\\b(public|static|protected|private|final)\\b', 'f-modifier');

$f_curl = $regex->createPattern(['begin' => '\{', 'end' => '\}', 'tokenID' => 'f_curl']);
$f_cond_curl = $regex->createPattern(['begin' => '\{', 'end' => '\}', 'tokenID' => 'f_condition_curl']);
$f_class = $regex->begin('\\b(?<type>class|interface|trait)\\b\\s*\\b(?P<name>[a-zA-Z_][a-zA-Z_0-9]*)\\b', '(?<=\})', 'f-class')->last();
$f_nspace = $regex->match('\\b(namespace)\\b\\s*\\b(?P<name>[a-zA-Z_][a-zA-Z_0-9]*(?:(\\\\[a-zA-Z_][a-zA-Z_0-9]*)+)?)\\b\\s*;', 'f-namespace')->last();
$comments = [
    $_multi_comment,
    $_depth_comment,
    $_doc_comment,
    $line_comment,
];

$f_cond = $regex->createPattern(['begin' => '\(', 'end' => '\)', 'tokenID' => 'f_cond']);

$f_conditional = $regex->begin('\\b(?<type>if|else|elseif)\\b', '(?<=;|\})', 'f-conditional')->last();

$f_c_func = $regex->createPattern([
    'begin' => '\\b(function)\\b(?:(\\s+(&\\s*))?|(\\s*))(?P<name>[a-zA-Z_][a-zA-Z_0-9]*)\\s*(?=\()',
    'end' => '(?<=\}|;)',
    'tokenID' => 'f-func-global-block',
]);
$_sub_curl_definition = $regex->createPattern([
    "begin" => "(?<=\{)",
    "end" => "(?=\})",
    'tokenID' => 'f-subpattern-curl',
    'scopedBoundary' => true,
    'lineFeed' => true,
    'flags' => ['instruct']
]);
$_sub_curl_definition->patterns = [
    ...$here_doc,
    ...$comments,
    $string,
    $f_curl,    
    $_sub_curl_definition
];

$regex->append($f_c_func);
$f_curl->patterns = [
    $f_curl
];
$f_class->patterns = [
    $f_curl
];
$f_cond->patterns = [
    $f_cond,
];
$f_conditional->patterns = [
    $string,
    $f_cond,
    $f_conditional,
    $f_class ,
    $f_cond_curl,
];
$f_cond_curl->patterns = [
    $f_c_func,
    $f_curl,
     $f_class ,
    $f_conditional,
];
$f_c_func->patterns = [
   $f_curl,
];

/**
 * 
 * @param RegexMatcherContainer $regex 
 * @param string $src 
 * @param mixed &$list 
 * @param string $file 
 * @return void 
 * @throws Exception 
 * @throws IGKException 
 * @throws Error 
 */
function d_function(RegexMatcherContainer $regex, string $src, &$list, string $file)
{
    $pos = 0;
    $regex->resetTreatment();
    $engine = new EngineLoader();
    $engine->list = &$list;
    $engine->namespace = null;
    $engine->file = $file;
    while ($g = $regex->detect($src, $pos)) {
        if ($e = $regex->end($g, $src, $pos)) {
            $tid = $e->tokenID;
            $fcn = $tid ? StringUtility::FuncName($tid) : null;
            igk_is_debug() && Logger::info('tokenid:' . $tid . ' [' . json_encode($e->value) . ']');
            if ($engine->php_code) {
                if ($tid && method_exists($engine, $fc = 'visit_php_' . $fcn)) {
                    call_user_func_array([$engine, $fc], [$e, $tid, &$pos]);
                    continue;
                }
            }
            if ($fcn && method_exists($engine, $fc = 'visit_' . $fcn)) {
                call_user_func_array([$engine, $fc], [$e, $tid, &$pos]);
            }
        }
    }
}

$list = [];
IO::GetFiles($dir, function ($file) use ($regex, &$list) {
    if (!preg_match('/\.(php|phtml|pinc)$/', $file))
        return;
    $src = file_get_contents($file);
    Logger::info('trait: ' . $file);
    d_function($regex, $src, $list, $file);
    return false;
}, true);


/**
 * 
 * @package 
 */
class EngineLoader
{
    var $list;
    var $php_code;
    var $namespace;
    /**
     * file definition 
     * @var ?string
     */
    var $file;
    var $flags = [];
    private function _get_name(string $n){
          if ($prefix = $this->namespace) {
            $n = $prefix . "\\" . $n;
        }
        return $n;
    }
    private function _reg(string $type, string $n, $def = null)
    {
        if ($prefix = $this->namespace) {
            $n = $prefix . "\\" . $n;
        }
        if ($def){
            $def->name = $n;
        }
        if (!isset($this->list[$type])) {
            $this->list[$type] = [];
        }
        $this->list[$type][$n] = $def ?? 1;
        if ($f = $this->file) {
            if (!isset($this->list['files'])) {
                $this->list['files'] = [];
            }
            $this->list['files'][$f][$type][$n] = $def ?? 1;
        }
        $this->flags['last'] = $def;
    }
    public function visit_f_php_start()
    {
        $this->php_code = true;
    }
    public function visit_f_php_stop()
    {
        $this->php_code = false;
    }
    public function visit_php_f_var() {}
    public function visit_php_f_cond($e)
    {
        $this->flags['cond'] = $e->value;
    }
    function _init_def(){
         $def = (object)[
            'conditional_type'=>null,
            'conditional' => igk_getv($this->flags, 'cond'),
            'name'=>null,
            'file'=>igk_io_collapse_path($this->file),
        ];
        return $def;
    }
    public function visit_php_f_func_global_block($e)
    {
        $def = $this->_init_def();
        if ($n = igk_getv($e->beginCaptures, 'name')) {
            $this->_reg('functions', $n[0], $def);            
        }
        $this->flags['cond'] = null;
    }
    public function visit_php_f_class($e)
    {
        $def = $this->_init_def();
        $n = igk_getv($e->beginCaptures, 'name');
        $t = igk_getv(['class' => 'classes', 'interface' => 'interfaces', 'trait' => 'traits'], igk_getv(igk_getv($e->beginCaptures, 'type'), 0));
        $def->name = $n[0];
        $this->_reg($t, $n[0], $def);
         $this->flags['cond'] = null;
    }
    public function visit_php_f_namespace($e)
    {
        $n = igk_getv($e->beginCaptures, 'name');
        if ($n = igk_getv($e->beginCaptures, 'name')) {
            $this->namespace = $n[0];
        }
    }
    public function visit_php_f_conditional($e)
    {
        $n = igk_getv(igk_getv($e->beginCaptures, 'type'), 0);
        if ($c = igk_getv($this->flags, 'last')) {
            $c->conditional_type = $n;
        }
    }
}

extract(igk_extract_var($list, 'functions|classes|traits|interfaces'));

if ($functions) {
    ksort($functions);
    igk_wln("# functions");
    igk_wln(json_encode(array_keys($functions), JSON_PRETTY_PRINT));
}
foreach (explode('|', 'interfaces|traits|classes') as $k) {
    if (isset($$k) && $$k) {
        ksort($$k);
        igk_wln("# " . $k);
        igk_wln(json_encode(array_keys($$k), JSON_PRETTY_PRINT));
    }
}
print_r($classes['RASTA\\Basic\\XCS']);

igk_exit();
