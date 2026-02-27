<?php
// @author: C.A.D. BONDJE DOUE
// @filename: generate_framework_metadata.php
// @date: 20260211 16:45:47 
// @command: balafon --run .test/reflection/generate_framework_metadata.php
// usage : --dir:directory to check --regex:regex_to_handle_file --url:download_uri --title:frameworktitle

// + | -------------------------------------------------------------------------
// + | detect reflection function/classes/traits/interface/conditional. function  
// + |
use IGK\Helper\IO;
use IGK\Helper\StringUtility;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherPattern;
use IGK\System\Text\RegexMatcherUtility;

class RegLevlMananerRegexMatcherPattern extends RegexMatcherPattern
{
    var $isBlock;
}
class RegLevelManager
{
    var $doc;
    var $location;
    public static function ReadArgDeclaration($src, $definition = false)
    {
        $regex = new RegexMatcherContainer;
        $pos = 0;
        // define
        $block = $regex->begin("\(", "\)", "block")->last();
        $array_block = $regex->begin("\[", "\]", "block-array")->last();
        $string = $regex->appendStringDetection('string', true)->last();
        $regex->autoStore = false;
        $l = [
            $string,
            $regex->appendMultilineComment(),
            $regex->appendSingleLineComment()
        ];
        $regex->autoStore = true;
        $name = $regex->match("(?:(?P<ref>&)\\s*)?(?P<n>(\.\.\.)?\\$[a-zA-Z_][a-zA-Z_0-9]*)\\b", "name")->last();
        if ($definition) {
            $regex->match("(\?)?(\\\\)?[a-zA-Z_][a-zA-Z_0-9]*\\b((\\\\[a-zA-Z_][a-zA-Z_0-9]*)+)?", "type")->last();
            $tcons = $regex->begin("\\s*=", "(?=,|\))", "const")->last();
            $tcons->patterns = [
                $l,
                $block,
                $array_block
            ];
        }

        $regex->match("\\s*(,|=)\\s*", "skip")->last();
        $block->patterns = [
            $l,
            $block
        ];
        $array_block = [
            $l,
            $array_block
        ];
        $r = [];
        $info = (object)[
            'type' => null,
            'const' => null,
            'last' => null
        ];
        $fc_handle = [
            'name' => function ($e) use (&$r, $info) {
                $c = $e->value;
                $ref = igk_conf_get($e->beginCaptures, 'ref/0');
                $n = igk_conf_get($e->beginCaptures, 'n/0');
                if ($info->type || $info->const || $ref) {
                    $c = ['name' => implode(' ', array_filter([$ref, $n]))];
                    if ($info->type) {
                        $c['type'] = $info->type;
                    }
                    if ($info->const) {
                        $c['default'] = trim($info->const);
                    }
                }
                $r[] = &$c;
                $info->last = &$c;
                $info->type = null;
            },
            'type' => function ($e) use (&$r, $info) {
                $info->type = $e->value;
            },
            'const' => function ($e) use (&$r, $info) {
                $info->const = trim(substr(ltrim($e->value), 1));
                $l = &$info->last;
                if (!is_array($l)) {
                    $l = ['name' => $l];
                }
                unset($info->last);
                $l['default'] = is_numeric($info->const) ? floatval($info->const) : trim($info->const);
                $info->const = null;
            }
        ];
        $is_debug = igk_is_debug();
        while ($g = $regex->detect($src, $pos)) {
            if ($e = $regex->end($g, $src, $pos)) {
                $is_debug && Logger::info('check: ' . $e->tokenID . ' value:[' . $e->value . ']');
                if ($fc = igk_getv($fc_handle, $e->tokenID)) {
                    $fc($e);
                }
            }
        }
        return $r;
    }
    /**
     * 
     * @param string $src 
     * @param int &$pos 
     * @return array 
     */
    public static function ReadFuncParams(string $src, int &$pos, &$return)
    {
        $tab = [];
        $regex = new RegexMatcherContainer;
        $cm[] = $regex->appendSingleLineComment()->last();
        $cm[] = $regex->appendMultilineComment()->last();
        $cm[] = $regex->appendStringDetection('string', true)->last();
        $cm[] = $tarray = $regex->begin('\[', '\]', 'array')->last();


        $brank = $regex->begin('\(', '\)', 'brank')->last();
        $tarray->patterns =
            $brank->patterns = [
                $cm,
                $brank
            ];

        $treturn = $regex->begin(':', '(?=\{|;)', 'return')->last();
        $stop = $regex->match('(?=\{)', 'stop')->last();

        // define

        $e_stop = false;
        while ($g = $regex->detect($src, $pos)) {
            if ($e = $regex->end($g, $src, $pos)) {
                if ($e->getisRootCaptured()) {
                    // Logger::warn('tokenid:' .$e->tokenID);
                    if ($e_stop == false) {
                        if ($e->tokenID == 'brank') {
                            if (!empty($v = substr($e->value, 1, -1))) {
                                $tab = self::ReadArgDeclaration($v, true);
                            }
                            $e_stop = $pos;
                        }
                    } else if ($e->tokenID == 'return') {
                        $return = trim(substr($e->value, 1));
                        break;
                    } else {
                        if ($e->tokenID == 'stop') {
                            $pos = $e_stop;
                            break;
                        }
                    }
                }
            }
        }
        return $tab;
    }
    /**
     * 
     * @return array{doc: mixed}|int 
     */
    public function getDocInfo(?string $type = null)
    {
        $d = [];
        if ($this->doc) {
            $c = &$this->doc;
            if (is_array($c)) {
                $m = array_pop($c);
            } else {
                $m = $c;
                $c = '';
            }
            $d['doc'] = $m;
        }
        if ($type) {
            $d['type'] = $type;
        }
        if ($d) {
            $d['$r'] = $this->location;
            return (object)$d;
        }
        return 1;
    }
    /**
     * 
     * @param mixed $e 
     * @return int 
     */
    public static function GetDepth($e): int
    {
        $i = 0;
        $g = $e->parentInfo;
        while ($g) {
            if ($g->match->isBlock) {
                $i++;
            }
            $g = $g->parent;
        }

        return $i;
    }
}
/**
 * 
 * @param mixed $g 
 * @param mixed &$t 
 * @param mixed $level 
 * @return mixed 
 */
function _reg_level($g, &$t, $level)
{
    $c = $level->getDocInfo();
    if (isset($t[$g])) {
        if (!is_array($t[$g])) {
            $t[$g] = [$t[$g]];
        }
        $t[$g][] = $c;
    } else
        $t[$g] = $c;
    return $c;
}
function meta_getPhpDocInfo($c, $type, $namespace): array
{
    $p = [];
    if ($type == 'type') {
        if ($namespace)
            $p[] = '@package ' . $namespace;
    } else {
        if (igk_getv($c, 'property')){
            $p[] = '@var '. (igk_getv($c, 'type') ?? 'mixed');
        }
        // for function 
        if (isset($c->params)) {
            foreach ($c->params as $k => $rp) {
                if (is_string($rp)) {
                    $rp = ['name' => $rp];
                }
                $t = igk_getv($rp, 'type');
                $d = igk_getv($rp, 'default');
                if (($t && igk_str_startwith($t, '?')) || $d == 'null') {
                    if (!$t) $t = '?mixed';
                    $t = 'null|' . substr($t, 1);
                }
                $p[] = sprintf('@param %s %s', $t ?? 'mixed', igk_getv($rp, 'name'));
            }
        }
        if (isset($c->return)) {
            $p[] = sprintf('@return %s', $c->return);
        }
    }
    return $p;
}
function meta_getPhpDocDefaultSummary($e){
    $tn= $e->beginCaptures['n'][0];
    return igk_getv([
        '__construct'=>'.ctr',
        '__toString'=>'get string presentation.',
        '__isset'=>'check if isset innaccessible property',
        '__unset'=>'unset innacessible property',
        '__destruct'=>'destructor',
        '__get'=>'.destructor',
        '__set'=>'destructor',
        '__call'=>'Triggered when calling an inaccessible or undefined method on an object.',
        '__callStatic'=>'Triggered when calling an inaccessible or undefined static method.',
        '__clone'=>'Called when an object is cloned using clone.',
        '__sleep'=>'Called before serialize() — defines which properties to serialize.',
        '__wakeup'=>'Called after unserialize().',
        '__serialize'=>'Custom serialization logic.',
        '__unserialize'=>'Custom unserialization logic.',
        '__debugInfo'=>'Used by var_dump() to customize debug output.',
        '__invoke'=>'Called when an object is used as a function.',
        '__debugInfo'=>'Used by var_dump() to customize debug output.',
        '__set_state'=>'Called when exporting with var_export().',
    ], $tn) ?? "auto generate doc.";
}
/**
 * 
 * @param mixed $e 
 * @param mixed $c 
 * @param mixed $funcs 
 * @param mixed $src 
 * @param string $type 
 * @param mixed $namespace 
 * @return void 
 */
function meta_updateBuffer($e, $c, $funcs, string $src, string $type = 'function', ?string $namespace = null, $tabSeparator = "    ")
{
    $bf = igk_getv($funcs, '::buffer');
    $v_have_subs = $bf && isset($bf->subs);
    $doc = '';
    if ($v_have_subs || (!isset($c->doc) && isset($funcs['::buffer']))) {
        if (!isset($c->doc)) {
            $p = meta_getPhpDocInfo($c, $type, $namespace);
            $default_summary = meta_getPhpDocDefaultSummary($e);
            $doc = implode("\n", array_filter([
                "/**",
                "* ".$default_summary,
                $p ? "* " . implode("\n* ", $p)  : null,
                "*/",
            ])) . "\n";
            $c->doc = $doc;
        } else {
            $doc = '';
        }
        if ($type == 'subfunc') {
            if (!isset($bf->subs)) {
                $bf->subs = [];
            }
           
            // $live_doc = $funcs['::live-doc'];
            $d = RegLevelManager::GetDepth($e);
            $tab = str_repeat($tabSeparator, $d); // live_doc->depth);
            $doc = $tab . implode("\n" . $tab, explode("\n", $doc));
            $bf->subs[] = (object)['from' => $e->from, 'to' => $e->to, 's' => "\n\n" . $doc . $e->value];
        } else {
            // missing doc
            $rv = $e->value;
            $buffer = $bf;
            if (isset($buffer->subs)) {
                $nbuffer = '';
                $coffset = 0;
                while (count($buffer->subs) > 0) {
                    $q = array_shift($buffer->subs);
                    if ($q->from > $e->to) igk_die('invalid position');
                    $from = $q->from - $e->from;
                    $nb = rtrim(substr($rv, $coffset, $from - $coffset)) . $q->s;

                    $coffset = $q->to - $e->from;
                    $nbuffer .= $nb;
                }
                $nbuffer .= substr($rv, $coffset);
                $rv = $nbuffer;
            }

            $doc = empty($doc) ? "\n" : "\n\n" . $doc;
            $buffer->buffer .= rtrim(substr($src, $buffer->pos, $e->from - $buffer->pos)) . $doc . $rv;
            $buffer->pos = $e->to;
        }
    }
}
/**
 * 
 */
function getGlobalFuncs($src, &$funcs)
{
    $level = new RegLevelManager;
    $level->location =  $funcs['::location_index'];
    $regex = new RegexMatcherContainer;
    $regex->patternCreatorClass = RegLevlMananerRegexMatcherPattern::class;
    $pos = 0;
    // define
    $here_doc = [];
    $regex->autoStore = false;
    RegexMatcherUtility::AppendPhpHereDoc($regex, $here_doc);
    $regex->autoStore = true;
    foreach ($here_doc as $k)
        $regex->append($k);

    $tp = $regex->createPattern(['patterns' => $here_doc]);

    // $start_php = $regex->createPattern(['begin'=>'<\?php\\b', 'end'=> '\? >', 'tokenID'=>'php-block']);


    $c_string = $regex->appendStringDetection('string', true)->last();
    $php_docblock = $regex->begin('\/\*\*', '\*\/', 'php-docblock')->last();
    $l = $regex->begin('\{', '\}', 'block')->last();
    $l->isBlock = true;

    $c_xg = $regex->begin('\?>', '<\?', 'outside-core')->last();
    $c_l = $regex->appendSingleLineComment()->last();
    $c_m = $regex->appendMultilineComment()->last();
    $regex->match('\\bnamespace\\b\\s*(?P<n>[_a-zA-Z][_a-zA-Z0-9\\\\]*)\\b', 'namespace');
    $indef = $regex->begin('((?P<modifier>(abstract|final))\\b\\s*)?\\b(?P<type>interface|trait|class)\\b\\s*(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b', '(?<=\})', 'in-def')->last();
    
    $indef_2 = $regex->begin('\\bnew\\b\\s*\\b(?P<type>class)\\b\\s*', '(?<=\})', 'in-def-2')->last();

    $regex->match('use\\s+function(\\s*&\\s*|\\s+)(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b', 'use_func_list');
    $func_list = $regex->match('\\bfunction(\\s*&\\s*|\\s+)(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b', 'func_list')->last();
    $cond_func_list = $regex->createPattern([
        'match' => '\\bfunction(\\s*&\\s*|\\s+)(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b',
        'tokenID' => 'func_conditional'
    ]);
    $inner_funcs = $regex->createPattern([
        'match' => '(?:\\b(?P<abstract>abstract)\\b\\s+)?(?:\\b(?P<modifier>public|private|protected)\\b\\s+)?(?:\\b(?P<static>static)\\b\\s+)?\\bfunction(\\s*&\\s*|\\s+)(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b',
        'tokenID' => 'inner_funcs'
    ]);
    $inner_funcs_2 = $regex->createPattern([
        'match' => '(?:\\b(?P<modifier>public|private|protected)\\b\\s+)?(?:\\b(?P<abstract>abstract)\\b\\s+)?(?:\\b(?P<static>static)\\b\\s+)?\\bfunction(\\s*&\\s*|\\s+)(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b',
        'tokenID' => 'inner_funcs'
    ]);
    $inner_funcs_3 = $regex->createPattern([
        'match' => '(?:\\b(?P<modifier>public|private|protected)\\b\\s+)?(?:\\b(?P<static>static)\\b\\s+)?(?:\\b(?P<abstract>abstract)\\b\\s+)?\\bfunction(\\s*&\\s*|\\s+)(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b',
        'tokenID' => 'inner_funcs'
    ]);
    $inner_funcs_3 = $regex->createPattern([
        'match' => '(?:\\b(?P<static>static)\\b\\s+)?(?:\\b(?P<modifier>public|private|protected)\\b\\s+)?(?:\\b(?P<abstract>abstract)\\b\\s+)?\\bfunction(\\s*&\\s*|\\s+)(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b',
        'tokenID' => 'inner_funcs'
    ]);
    $inner_props = $regex->createPattern([
        'begin' => '\\b((?P<modifier>public|private|protected|var)\\b\\s*)(?P<type>(\\\\)?[_a-zA-Z][_a-zA-Z0-9\\\\]*\\b\\s*)?(?P<n>\\$[_a-zA-Z][_a-zA-Z0-9]*)\\b',
        'end' => ';',
        'tokenID' => 'inner_props'
    ]);
    $static_props = $regex->createPattern([
        //'begin' => '\\b((?P<modifier>public|private|protected|var)\\b\\s*)\\b(?P<n>\\$[_a-zA-Z][_a-zA-Z0-9]*)\\b',
        //'(?:\\b(?P<modifier>public|private|protected|var)\\b\\s+)?(?:\\b(?P<static>static)\\b\\s+)?\\b\$(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b',
        'begin' => '(?:\\b(?P<modifier>public|private|protected)\\b\\s+)?((?P<static>static)\\b\\s*)(?P<n>\\$[_a-zA-Z][_a-zA-Z0-9]*)\\b',
        'end' => ';',
        'tokenID' => 'inner_props'
    ]);
    $inner_consts = $regex->createPattern([
        'begin' => '\\b((?P<modifier>const)\\b\\s+)\\b(?P<n>[_a-zA-Z][_a-zA-Z0-9]*)\b',
        'end' => ';',
        'comment' => 'detect constants',
        'tokenID' => 'inner_props'
    ]);

    $subblock = $regex->createPattern([
        'begin' => '\{',
        'end' => '\}',
        'tokenID' => 'sub-block',
        'isBlock' => true
    ]);
    $sub_subblock = $regex->createPattern([
        'begin' => '\{',
        'end' => '\}',
        'tokenID' => 'sub-subblock',
        'isBlock' => true
    ]);
    $l->patterns = [
        $php_docblock,
        $c_string,
        $c_l,
        $c_m,
        $cond_func_list,
        $indef_2,
        $indef,
        $tp,
        $l,
    ];
    $subblock->patterns = [
        $php_docblock,
        $c_string,
        $tp,
        $c_l,
        $c_m,
        $static_props,
        $inner_consts,
        $inner_props,
        $inner_funcs,
        $inner_funcs_2,
        $inner_funcs_3,
        $sub_subblock,
        // $sub_def_subblock
    ];
    // $sub_def_subblock->patterns = [
    //   
    // ];
    $sub_subblock->patterns = [
        $php_docblock,
        $c_string,
        $tp,
        $c_l,
        $c_m,
        $indef_2,
        $cond_func_list,
        $sub_subblock
    ];
    $indef_2->patterns = $indef->patterns = [
        $c_l,
        $c_m,
        $subblock
    ];
    $block_lip =  $regex->createPattern([
        'begin' => '\{',
        'end' => '\}',
        'tokenID' => 'block-skip'
    ]);
    $indef_2->patterns = $block_lip->patterns = [
        $c_l,
        $c_m,
        $block_lip,
    ];


    $namespace = '';
    $type = '';
    $name = '';
    $tbNamespaces = &$funcs['::namespaces'];
    $doc_block = null;
    $level->doc = &$doc_block;
    $sub_func_list = [];
    $props_list = [];
    $nsflag = 0;
    $callbacks = [
        'php-docblock' => function ($e) use (&$doc_block, &$php_docmarker) {
            
            $php_docmarker = $e->value;
        },
        'namespace' => function ($e) use (&$namespace, &$tbNamespaces, &$nsflag) {
            $namespace = igk_conf_get($e->captures, 'n/0');
            $nsflag = true;
            $u = igk_uri($namespace);
            if (($g = dirname($u)) && ($g != '.')) {
                $g = StringUtility::NS($g);
                if (!isset($tbNamespaces[$g])) {
                    $tbNamespaces[$g] = [];
                }
                $tbNamespaces[$g][] = $namespace;
            }
            if (!isset($tbNamespaces[$namespace])) {
                $tbNamespaces[$namespace] = [];
            }
        },
        'func_list' => function ($e, &$funcs) use ($l, &$namespace, $level, $src, &$pos) {
            $g = igk_conf_get($e->captures, 'n/0'); //igk_getv($e->captures, 'n');
            $g = ($namespace ? $namespace . "\\" : "") . $g;
            $return = null;
            $params = RegLevelManager::ReadFuncParams($src, $pos, $return);

            $c = _reg_level($g, $funcs, $level);
            if (is_object($c) || $params || $return || isset($funcs['::buffer'])) {
                if (!is_object($c)) {
                    $c = (object)[];
                    $funcs[$g] = $c;
                }
                if ($params)
                    $c->params = $params;
                if ($return) {
                    $c->return = $return;
                }
                meta_updateBuffer($e, $c, $funcs, $src, 'func');
            }
            // if (isset($funcs[$g])){
            //     if (!is_array($funcs[$g])){
            //         $funcs[$g] = [$funcs[$g]];
            //     }
            //     $funcs[$g][] = $c;
            // } else
            //     $funcs[$g] = $c;
        },
        'func_conditional' => function ($e, &$funcs) use (&$namespace, $src, &$pos, $level) {
            $g = igk_conf_get($e->captures, 'n/0'); //igk_getv($e->captures, 'n');
            $g = ($namespace ? $namespace . "\\" : "") . $g;
            $return = null;
            $params = RegLevelManager::ReadFuncParams($src, $pos, $return);
            $c = _reg_level($g, $funcs['::conditionals_functions'], $level);
            if (is_object($c) || $params || $return || isset($funcs['::buffer'])) {
                if (!is_object($c)) {
                    $c = (object)[];
                    $funcs['::conditionals_functions'][$g] = $c;
                }
                if ($params)
                    $c->params = $params;
                if ($return) {
                    $c->return = $return;
                }
                $tt = isset($funcs['::indef']) ? 'subfunc' : 'func';


                meta_updateBuffer($e, $c, $funcs, $src, $tt);
            }
        },
        'in-def' => function (\IGK\System\Text\RegexMatcherCapture $e, &$funcs) use (&$namespace, $level, $src, &$sub_func_list, &$props_list, &$live_doc) {
            /**
             * @var mixed $live_doc
             */
            $t = igk_conf_get($e->beginCaptures, 'type/0');
            $modifier = igk_conf_get($e->beginCaptures, 'modifier/0');
            $n = igk_conf_get($e->beginCaptures, 'n/0');
            $tn = ($namespace ? $namespace . "\\" : "") . $n;
            $c = $level->getDocInfo($t);
            $is_conditional = !is_null($e->parentInfo) && ($live_doc->depth > 1);
            if (is_object($c)) {
                if ($is_conditional)
                    $c->is_conditional = $is_conditional;
                if ($modifier) {
                    $c->modifier = $modifier;
                }
                if ($sub_func_list) {
                    $c->funcs = $sub_func_list;
                }
                if ($props_list) {
                    $c->props = $props_list;
                }
                meta_updateBuffer($e, $c, $funcs, $src, 'type', $namespace);
            }
            if (isset($funcs['::' . $t][$tn])) {
                if (!is_array($funcs['::' . $t][$tn])) {
                    $funcs['::' . $t][$tn] = [$funcs['::' . $t][$tn]];
                }
                $funcs['::' . $t][$tn][] = $c;
            } else
                $funcs['::' . $t][$tn] = $c;

            $sub_func_list = [];
            $props_list = [];
        },
        'inner_funcs' => function ($e, &$funcs) use (&$sub_func_list, $src, &$pos, $level) {

            $modifier = igk_conf_get($e->beginCaptures, 'modifier/0');
            $abstract = igk_conf_get($e->beginCaptures, 'abstract/0');
            $tn = igk_conf_get($e->beginCaptures, 'n/0');
            $stn = igk_conf_get($e->beginCaptures, 'static/0');
            $return = null;
            $params = RegLevelManager::ReadFuncParams($src, $pos, $return);

            if (empty($modifier) || (preg_match('/(public|protected)/', $modifier))) {
                if (empty($modifier)) {
                    $modifier = 'public';
                }
                $d = $level->getDocInfo();
                $doc = $d ? igk_getv($d, 'doc') : null;
            
                $v_p =  [
                    'modifier' => $modifier,
                    'doc' => $doc
                ];
                if ($params) {
                    $v_p['params'] = $params;
                }
                if ($return) {
                    $v_p['return'] = $return;
                }
                if (!empty($stn)) {
                    $v_p['static'] = true;
                }
                if ($abstract) {
                    $v_p['abstract'] = true;
                }
                $c = (object)array_filter($v_p);
                meta_updateBuffer($e, $c, $funcs, $src, 'subfunc');
                $sub_func_list[$tn] = $c;
            }
        },
        'inner_props' => function ($e) use (&$props_list, $level, & $funcs, $src) {
            $modifier = igk_conf_get($e->beginCaptures, 'modifier/0');
            $tn = igk_conf_get($e->beginCaptures, 'n/0');
            $v_type = igk_conf_get($e->beginCaptures, 'type/0');
            $stn = igk_conf_get($e->beginCaptures, 'static/0');
            $d = $level->getDocInfo();
           
            $i = strlen($tn) + $e->beginCaptures['n'][1] -  $e->from;
            $l = substr($e->value, $i, -1);
            $args = [];
            if (!empty($l)) {
                $targs = RegLevelManager::ReadArgDeclaration($tn . $l);
                array_shift($targs);
                foreach ($targs as $k => $v) {
                    if (is_numeric($k))
                        $args[] = $v;
                    else
                        $args[] = $k;
                }
            }

            $doc = $d ? igk_getv($d, 'doc') : null;
           if (is_null($doc)){
               meta_updateBuffer($e, $ref = (object)['type'=>$v_type, 'property'=>true], $funcs, $src, 'subfunc');
               $doc = $ref->doc;
           }
            if (empty($modifier) || (preg_match('/(public|protected|var)/', $modifier))) {
                // ignore private properties
                if (empty($modifier) || ($modifier == "var")) {
                    $modifier = 'public';
                }
                array_unshift($args, $tn);
                while (count($args) > 0) {
                    $tn = array_shift($args);
                    $vp = [];

                    $props_list[$tn] = array_filter([
                        'static' => !empty($stn),
                        'modifier' => $modifier,
                        'doc' => $doc
                    ]);
                }
            }
        }
    ];
    $live_doc = (object)[
        'data' => [],
        'detect' => null,
        'depth' => 0
    ];
    $funcs['::live-doc'] =  $live_doc;
    while ($g = $regex->detect($src, $pos)) {
       // Logger::warn('innerfunc:'.$g->match->tokenID);
        if ($php_docmarker) {
            if (in_array($g->match->tokenID, ['in-def', 'func_list', 'inner_funcs', 'inner_props'])) {                
                $live_doc->data[] = $php_docmarker;
                $live_doc->detect = (object)['prev' => $live_doc->detect, 'item' => $g];                
                $php_docmarker = null;
            }
        }


        if (!$g->start) {

            if ($g->match->tokenID == 'block') {
                if ($nsflag) {
                    // + | start block of ns flag
                    $nsflag = false;
                } else
                    $live_doc->depth++;
            } else {
                $nsflag = false;
            }
            if ($g->match->tokenID == 'in-def') {
                $funcs['::indef'] = 1;
            }
        }
        if ($e = $regex->end($g, $src, $pos)) {

            igk_is_debug() && Logger::info('tokenid::' . $e->tokenID);
            if ($e->tokenID == 'block') {
                $live_doc->depth = max(0, $live_doc->depth--);
            }
            if ($live_doc->detect && (is_null($e->info) || (($e->info === $g) && ($live_doc->detect->item == $g)))) {
                $doc_block = array_pop($live_doc->data);
                $live_doc->detect = $live_doc->detect->prev;
            } else {
                if ($php_docmarker && in_array($e->tokenID, ['inner_funcs', 'inner_props', 'func_conditional'])){
                     $doc_block = $php_docmarker;
                     $php_docmarker = null;
                }
            }
            if ($e->tokenID && ($fc = igk_getv($callbacks, $e->tokenID))) {
                $fc($e, $funcs);
            }
            if ($e->tokenID == 'in-def') {
                unset($funcs['::indef']);
            }
        }
    }
    unset($funcs['::live-doc']);
}

$c = igk_getv($command->options, '--dir') ?? IGK_LIB_DIR;
$update_doc = property_exists($command->options, '--update-doc');
if ($regex = igk_getv($command->options, '--regex', null)) {
    $regex = "/" . $regex . "/";
}

// $m = [__DIR__.'/demo_class.php']; //'/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Lib/Tests/System/Text/RegexMatcherContainerTest.php'];
function _to_array($n): array
{
    if (!is_array($n))
        $n = [$n];
    return $n;
}
$interfaces = [];
$traits = [];
$classes = [];
$cond_funcs = [];
$tbNamespaces = [];
$meta_info = (object)[
    'framework' => igk_getv($command->options, '--title', IGK_PLATEFORM_NAME),
    'url' => igk_getv($command->options, '--url'),
    'versions' => _to_array(igk_getv($command->options, '--version', ['1.0']))
];
if ($meta_info->framework == IGK_PLATEFORM_NAME) {
    $meta_info->url = 'https://balafon.igkdev.com/get-download';
    $meta_info->versions = [IGK_VERSION];
}
$locations = [];
$funcs = [
    '::meta' => $meta_info,
    '::conditionals_functions' => &$cond_funcs,
    '::class' => &$classes,
    '::trait' => &$traits,
    '::interface' => &$interfaces,
    '::namespaces' => &$tbNamespaces,
];
$ln = strlen($c) + 1;
$treat = function ($tf) use (&$funcs, $ln, $update_doc) {
    if (is_link($tf)) return;

    Logger::info('treat ' . $tf);
    $rc = './' . substr($tf, $ln);
    if ($update_doc) {
        $buffer = '';
        $funcs['::buffer'] = (object)['pos' => 0, 'buffer' => &$buffer];
    }
    $funcs['::files'][] = $rc;
    $funcs['::location_index'] = count($funcs['::files']) - 1;
    $c = file_get_contents($tf);
    getGlobalFuncs($c, $funcs);

    if ($update_doc && $buffer) {
        $p = $funcs['::buffer']->pos;
        $buffer .= substr($c, $p);
        Logger::warn('update file: ' . $tf);
        igk_wln_e($buffer);
        // igk_io_w2file($tf, $buffer);
    }
    unset($funcs['::buffer']);
};
$fc = $treat;
$regex = $regex ?? '/\.php$/';
$fc = function ($c) use ($regex, $treat) {
    if (preg_match($regex, $c)) {
        $treat($c);
    }
};

$m = IO::GetFiles($c, $fc, true) ?? [];

unset($funcs['::location_index']);
$sk = SORT_NATURAL | SORT_REGULAR;
ksort($funcs, $sk);
ksort($traits, $sk);
ksort($interfaces, $sk);
ksort($classes, $sk);
ksort($cond_funcs, $sk);

igk_wln_e(json_encode($funcs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
