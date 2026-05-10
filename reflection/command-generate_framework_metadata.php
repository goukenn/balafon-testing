<?php
// @author: C.A.D. BONDJE DOUE
// @filename: generate_framework_metadata.php
// @date: 20260211 16:45:47 
// @command: balafon --run .test/reflection/command-generate_framework_metadata.php
// @usage : --dir:directory_to_check --regex:regex_to_handle_file --url:download_uri --title:framework_title [--update-doc]
// + | -------------------------------------------------------------------------
// + | detect reflection function/classes/traits/interface/conditional. build balafon metadata  sdk.json
// + |

use IGK\Helper\Activator;
use IGK\Helper\IO;
use IGK\Helper\StringUtility;
use IGK\System\Annotations\PhpDocBlocReader;
use IGK\System\Console\App;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;
use IGK\System\Console\Commands\Utility\FrameworkMetadataGenerator;
use IGK\System\Console\Commands\Utility\FrameworkMetadataRegexMatcherPattern;
use IGK\System\Console\Commands\Utility\FrameworkRegLevelManager;
use IGK\System\Console\Helper\ConsoleUtility;

require_once __DIR__ . '/FrameworkMetadataGenerator.php';
require_once __DIR__ . '/FrameworkMetadataRegexMatcherPattern.php';
require_once __DIR__ . '/FrameworkRegLevelManager.php';
require_once __DIR__ . '/IFrameworkRegLevelDocLocation.php';

/**
 * @var mixed $command
 */

if (ConsoleUtility::SupportHelp($command)) {
    igk_wln(implode("\n", ['', "generate framework metadata", "", ""]));
    igk_wln(App::Gets(App::GREEN, 'options:'), '');
    ConsoleUtility::ShowOptionsCommand(
        [
            '--dir:[]' => 'directory to analyse',
            '--regex:regex_to_handle_file' => 'regex for file matching',
            '--title:title' => 'framework title',
            '--update-doc' => 'flag: update documents'
        ]
    );
    igk_exit();
}
/**
 * auto generate doc.
 * @param mixed $g
 * @param mixed & $t
 * @param mixed $level
 * @return mixed
 */
function meta_reg_level($g, &$t, $level)
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
/**
* auto generate doc.
* @param mixed $c
* @param mixed $type
* @param mixed $namespace
* @param mixed $extra
* @return array
*/
function meta_getPhpDocInfo($c, $type, ?string $namespace, $extra = null): array
{
    $p = [];
    if ($type == 'type') {
        $pack = ($extra ? igk_getv($extra, 'package') : null) ?? $namespace;
        $p[] = '@package ' . $pack;
        if ($extra) {
            if (is_string($extra)) {
                $p[] = '@author ' . $extra;
            }
            if (is_array($extra)) {
                foreach ($extra as $k => $v) {
                    if ((!$v) || ($k == 'package')) continue;
                    if (!is_string($v)) {
                        $v = implode(' ', (array)$v);
                    }
                    $p[] = '@' . $k . ' ' . trim($v);
                }
            }
        }
    } else {
        if (igk_getv($c, 'property')) {
            $p[] = '@var ' . (igk_getv($c, 'type') ?? 'mixed');
        }
        if (isset($c->params)) {
            foreach ($c->params as $k => $rp) {
                if (is_string($rp)) {
                    $rp = ['name' => $rp];
                }
                $t = igk_getv($rp, 'type');
                $d = igk_getv($rp, 'default');
                if (($t && igk_str_startwith($t, '?')) || $d == 'null') {
                    if (!$t) {
                        $t = '?mixed';
                    } else {
                        if ($d == 'null') {
                            $t = ' ' . trim($t, '? ');
                        }
                    }
                    $t = 'null|' . substr($t, 1);
                }
                $p[] = sprintf('@param %s %s', $t ?? 'mixed', igk_getv($rp, 'name'));
            }
        }
        if (isset($c->return)) {
            $p[] = sprintf('@return %s', $c->return);
        } else {
            if (in_array($type, ['function', 'subfunc'])) {
                $p[] = sprintf('@return void');
            }
        }
    }
    return $p;
}
/**
* auto generate doc.
* @param string $tn type name
* @return mixed
*/
function meta_getPhpDocDefaultSummary(string $tn)
{
    return igk_getv([
        '__construct' => '.ctr',
        '__toString' => 'get string presentation.',
        '__isset' => 'check if isset innaccessible property',
        '__unset' => 'unset innacessible property',
        '__destruct' => 'destructor',
        '__get' => '.destructor',
        '__set' => 'destructor',
        '__call' => 'Triggered when calling an inaccessible or undefined method on an object.',
        '__callStatic' => 'Triggered when calling an inaccessible or undefined static method.',
        '__clone' => 'Called when an object is cloned using clone.',
        '__sleep' => 'Called before serialize() — defines which properties to serialize.',
        '__wakeup' => 'Called after unserialize().',
        '__serialize' => 'Custom serialization logic.',
        '__unserialize' => 'Custom unserialization logic.',
        '__debugInfo' => 'Used by var_dump() to customize debug output.',
        '__invoke' => 'Called when an object is used as a function.',
        '__set_state' => 'Called when exporting with var_export().',
    ], $tn) ?? "auto generate doc.";
}
/**
* update doc definition parameters
* @param mixed $meta_definition
* @param mixed $location
* @param string $type
* @return true|false
*/
function meta_updateParams($meta_definition, $location, string $type = 'func')
{
    list($v_doc, $v_params) = igk_extract($meta_definition, 'doc|params');
    $v_params = $v_params ?? [];
    if (isset($v_doc)) {

        $reader = new PhpDocBlocReader();
        $ct = $reader->readDoc($v_doc, []);
        $update = false;
        $def = $v_params ? [] : null;
        if (!is_null($params = $ct->param ?? $def)) {
            if (is_string($params)) {
                $params = [$params];
            }
            $auto_count = 0;
            $_var = array_merge(...array_map(function ($a)use(& $auto_count) {
                $var = [];
                preg_match('/(&\\s*)?(\$[a-zA-Z_][a-zA-Z_0-9]*)/', $a, $var);
                if ($var){
                    $m_key = meta_param_name($var[0]);
                    return [$m_key => $m_key];
                }
                else {
                    $n = 'var_'.$auto_count;
                    $auto_count++;
                    return [$n=>$n];
                }
            }, $params));

            $dt = $v_params;
            $otd = [];
           // $ix = 0;
            while (count($dt)) {
                $q = array_shift($dt);
                $vtype = 'mixed';
                if ($q && !is_string($q)){
                    $vtype = igk_getv($q, 'type', $vtype);
                    $q= igk_getv($q, 'name') ?? igk_die('missing name');
                    $q = meta_param_name($q);
                }

                if (isset($_var[$q])) {
                    $otd[] = array_shift($params);
                } else {
                    $otd[] = sprintf('%s %s', $vtype, $q);
                    $update = true;
                }
            }
            $ct->param = null;
            $ct->param = $otd;
        }
        if (!$ct->return && (preg_match('/@return\\b/', $v_doc) || ($type=='func'))) {
            $ct->return = 'void';
            $update = true;
        }
        if ($update) {

            $meta_definition->doc = $ct->render();
            $from = min($location->from, $meta_definition->location->from);
            $location->to = min($meta_definition->location->to, $location->from);
            $location->from = $from;
            return true;
        }
    }
    return false;
}
/**
* auto generate doc.
* @return string
*/
function meta_param_name(string $n){
    if ($n[0] == '&'){
        $n = '& '.ltrim(substr($n, 1));
    }
    return $n;
}
/**
* auto generate doc.
* @param mixed $e
* @param mixed $c
* @param mixed $funcs
* @param string $src
* @param string $type
* @param null|string $namespace
* @param string $tabSeparator
* @param '\n' $docLineFeedPrefix
* @return void
*/
function meta_updateBuffer(
    $e,
    $c,
    $funcs,
    string $src,
    string $type = 'function',
    ?string $namespace = null,
    $tabSeparator = "    ",
    $docLineFeedPrefix = FrameworkMetadataGenerator::DOC_LF_PREFIX
) {
    $k_buffer = FrameworkMetadataGenerator::PROP_BUFFER;
    $extra = igk_getv($funcs, FrameworkMetadataGenerator::PROP_TYPE_EXTRA_DEF);
    $bf = igk_getv($funcs, $k_buffer);
    if (!$bf) return;
    $v_replaceDefinition = igk_createobj(['from' => $e->from, 'to' => $e->to]);
    $v_have_subs = $bf && isset($bf->subs);
    $doc = '';

    if (!$v_have_subs && !meta_updateParams($c, $v_replaceDefinition, $type) && !(!isset($c->doc) && isset($funcs[$k_buffer])))
        return;
    if (!isset($c->doc)) {
        $p = meta_getPhpDocInfo($c, $type, $namespace, $extra);
        $default_summary = meta_getPhpDocDefaultSummary($e->beginCaptures['n'][0]);
        $doc = implode("\n", array_filter([
            "/**",
            "* " . $default_summary,
            $p ? "* " . implode("\n* ", $p)  : null,
            $type == 'func' ? "* @return mixed" : null,
            "*/",
        ])) . "\n";
        $c->doc = $doc;
        $v_replaceDefinition->from = $e->from;
        $v_replaceDefinition->to = $e->from;
    } else {
        //$doc = '';
    }
    $doc = $c->doc;
    $doc = $docLineFeedPrefix . FrameworkRegLevelManager::FormatDoc($doc, $e, $tabSeparator);
    meta_appendReplace($bf, (object)['from' => $v_replaceDefinition->from, 'to' => $v_replaceDefinition->to, 's' => $doc]);
    
}
/**
* auto generate doc.
* @param mixed $bf
* @param mixed $data
* @return void
*/
function meta_appendReplace($bf, $data){
    $bf->replaces[] = $data;
}
/**
* auto generate doc.
* @param mixed $src
* @param mixed &$funcs
* @param '\n' $docLineFeedPrefix
* @return void
*/
function meta_getGlobalFuncs($src, &$funcs, $docLineFeedPrefix = FrameworkMetadataGenerator::DOC_LF_PREFIX)
{
    $level = new FrameworkRegLevelManager;
    $level->location =  $funcs['::location_index'];
    $regex = new RegexMatcherContainer;
    $regex->patternCreatorClass = FrameworkMetadataRegexMatcherPattern::class;
    $pos = 0;
    $here_doc = [];
    $regex->autoStore = false;
    RegexMatcherUtility::AppendPhpHereDoc($regex, $here_doc);
    $regex->autoStore = true;
    foreach ($here_doc as $k)
        $regex->append($k);
    $tp = $regex->createPattern(['patterns' => $here_doc]);
    $c_string = $regex->appendStringDetection('string', true)->last();
    $php_docblock = $regex->begin('\/\*\*', '\*\/', 'php-docblock')->last();
    $l = $regex->begin('\{', '\}', 'block')->last();
    $l->isBlock = true;
    $c_xg = $regex->begin('\?>', '<\?', 'outside-core')->last();
    $c_l = $regex->appendSingleLineComment()->last();
    $c_m = $regex->appendMultilineComment()->last();
    $ns_block = $regex->begin('\\bnamespace\\b\\s*', '(?<=;|\})', 'namespace-bock')->last();
    $ns_curl_block = $regex->createPattern(['begin' => '\{', 'end' => '\}', 'tokenID' => 'ns-curl-block']);
    $ns_curl_block->isBlock = true;
    $ns_block->patterns = [
        $c_l,
        $c_m,
        $regex->createPattern(['match' => '(?P<n>[_a-zA-Z][_a-zA-Z0-9\\\\]*)', 'tokenID' => 'namespace']),
        $ns_curl_block
    ];
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
    $v_empty_line = $regex->appendEmptyLineDetection()->last();
    $ns_curl_block->patterns =  $l->patterns = [
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
        $v_empty_line,
        $sub_subblock,
    ];
    $sub_subblock->patterns = [
        $php_docblock,
        $c_string,
        $tp,
        $c_l,
        $c_m,
        $indef_2,
        $cond_func_list,
        $v_empty_line,
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
        'empty-line' => function ($e) use (&$funcs) {
            if (($e->from < $e->to) && isset($funcs[FrameworkMetadataGenerator::PROP_BUFFER])) {
                meta_appendReplace($funcs[FrameworkMetadataGenerator::PROP_BUFFER], 
                (object)[
                    'from' => $e->from,
                    'to' => $e->to,
                    's' => ''
                ]);
            }
        },
        'php-docblock' => function ($e) use (&$doc_block, &$php_docmarker, &$funcs, $level, $docLineFeedPrefix) {
            $php_docmarker = $e->value;
            $level->docLocationInfo = Activator::CreateNewInstance(IFrameworkRegLevelDocLocation::class, $e);

            if (isset($funcs[FrameworkMetadataGenerator::PROP_BUFFER])) {
                $reader = new PhpDocBlocReader();
                $c = $reader->readDoc($php_docmarker, [], []);
                if (empty(trim($c->summary))) {
                    $c->summary = meta_getPhpDocDefaultSummary('');
                    $php_docmarker = $c->render();
                    $doc  = FrameworkRegLevelManager::FormatDoc($php_docmarker, $e, $level->separator);
                    $bfr = &$funcs[FrameworkMetadataGenerator::PROP_BUFFER]->replaces;
                    if (!is_null($level->docReplaceWith)) {
                        array_pop($bfr);
                    }
                    meta_appendReplace($funcs[FrameworkMetadataGenerator::PROP_BUFFER], 
                    // $bfr[] = 
                    (object)['from' => $e->from, 'to' => $e->to, 's' => $docLineFeedPrefix . $doc]);

                    $level->docReplaceWith = $php_docmarker;
                    // + | set null to raise replacement 
                    $php_docmarker = null;
                }
            }
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
                if (!in_array($namespace, $tbNamespaces[$g]))
                    $tbNamespaces[$g][] = $namespace;
            }
            if (!isset($tbNamespaces[$namespace])) {
                $tbNamespaces[$namespace] = [];
            }
        },
        'func_list' => function ($e, &$funcs) use ($l, &$namespace, $level, $src, &$pos) {
            $g = igk_conf_get($e->captures, 'n/0');
            $g = ($namespace ? $namespace . "\\" : "") . $g;
            $return = null;
            $params = FrameworkRegLevelManager::ReadFuncParams($src, $pos, $return);
            $c = meta_reg_level($g, $funcs, $level);
            if (is_object($c) || $params || $return || isset($funcs[FrameworkMetadataGenerator::PROP_BUFFER])) {
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
        },
        'func_conditional' => function ($e, &$funcs) use (&$namespace, $src, &$pos, $level, $docLineFeedPrefix) {
            $g = igk_conf_get($e->captures, 'n/0');
            $g = ($namespace ? $namespace . "\\" : "") . $g;
            $return = null;
            $params = FrameworkRegLevelManager::ReadFuncParams($src, $pos, $return);
            $c = meta_reg_level($g, $funcs['::conditionals_functions'], $level);
            if (is_object($c) || $params || $return || isset($funcs[FrameworkMetadataGenerator::PROP_BUFFER])) {
                if (!is_object($c)) {
                    $c = (object)[];
                    $funcs['::conditionals_functions'][$g] = $c;
                }
                if ($params)
                    $c->params = $params;
                if ($return) {
                    $c->return = $return;
                }
                $tt = isset($funcs[FrameworkMetadataGenerator::PROP_INDEF]) ? 'subfunc' : 'func';
                if (isset($c->doc)) {
                    $reader = new PhpDocBlocReader();
                    $c_doc = $reader->readDoc($c->doc, [], []);
                    if (is_null($c_doc->return)) {
                        $c_doc->return = '';
                        $c->doc = $c_doc->render();
                        if (isset($funcs[FrameworkMetadataGenerator::PROP_BUFFER])) {
                            // + | replace last detected buffer 
                            if ($rep = &$funcs[FrameworkMetadataGenerator::PROP_BUFFER]->replaces) {
                                if (!is_array($rep)) {
                                    igk_wln_e(__FILE__ . ":" . __LINE__, 'null container ... ');
                                }
                                $rep[count($rep) - 1]->s = $docLineFeedPrefix . FrameworkRegLevelManager::FormatDoc($c->doc, $e, $level->separator);
                            }
                        }
                    }
                }
                meta_updateBuffer($e, $c, $funcs, $src, $tt);
            }
        },
        'in-def' => function (\IGK\System\Text\RegexMatcherCapture $e, &$funcs) use (&$namespace, $level, $src, &$sub_func_list, &$props_list, &$live_doc) {
            /**
             * auto generate doc.
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
            $tkey = '::'.$t;
            if (isset($funcs[$tkey][$tn])) {
                if (!is_array($funcs[$tkey][$tn])) {
                    $funcs[$tkey][$tn] = [$funcs[$tkey][$tn]];
                }
                $funcs[$tkey][$tn][] = $c;
            } else
                $funcs[$tkey][$tn] = $c;
            $sub_func_list = [];
            $props_list = [];
        },
        'inner_funcs' => function ($e, &$funcs) use (&$sub_func_list, $src, &$pos, $level) {
            $modifier = igk_conf_get($e->beginCaptures, 'modifier/0');
            $abstract = igk_conf_get($e->beginCaptures, 'abstract/0');
            $tn = igk_conf_get($e->beginCaptures, 'n/0');
            $stn = igk_conf_get($e->beginCaptures, 'static/0');
            $return = null;
            $params = FrameworkRegLevelManager::ReadFuncParams($src, $pos, $return);
            if (empty($modifier)) {
                $modifier = 'public';
            }
            $d = $level->getDocInfo();
            $doc = is_object($d) || is_array($d) ? igk_getv($d, 'doc') : null;
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
            if (is_object($d)) {
                $v_p['location'] = $d->location;
            } else {
                $v_p['location'] = $level->docLocationInfo;
                igk_die(__FILE__.":".__LINE__ .':: not an object');
            }
            $c = (object)array_filter($v_p);
            meta_updateBuffer($e, $c, $funcs, $src, 'subfunc');
            if (preg_match('/(public|protected)/', $modifier)) {
                $sub_func_list[$tn] = $c;
            }
        },
        'inner_props' => function ($e) use (&$props_list, $level, &$funcs, $src) {
            $modifier = igk_conf_get($e->beginCaptures, 'modifier/0');
            $tn = igk_conf_get($e->beginCaptures, 'n/0');
            $v_type = igk_conf_get($e->beginCaptures, 'type/0');
            $stn = igk_conf_get($e->beginCaptures, 'static/0');
            $d = $level->getDocInfo();
            $i = strlen($tn) + $e->beginCaptures['n'][1] -  $e->from;
            $l = substr($e->value, $i, -1);
            $args = [];
            if (!empty($l)) {
                $targs = FrameworkRegLevelManager::ReadArgDeclaration($tn . $l);
                array_shift($targs);
                foreach ($targs as $k => $v) {
                    if (is_numeric($k))
                        $args[] = $v;
                    else
                        $args[] = $k;
                }
            }
            $doc = $d ? igk_getv($d, 'doc') : null;
            if (is_null($doc)) {
                meta_updateBuffer($e, $ref = (object)['type' => $v_type, 'property' => true], $funcs, $src, 'subfunc');
                $doc = igk_getv($ref, 'doc');
            }
            if (empty($modifier) || (preg_match('/(public|protected|var)/', $modifier))) {
                // + | ignore private properties
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
                    $funcs[FrameworkMetadataGenerator::PROP_BUFFER] = null;
                } else
                    $live_doc->depth++;
            } else {
                $nsflag = false;
            }
            if ($g->match->tokenID == 'in-def') {
                $funcs[FrameworkMetadataGenerator::PROP_INDEF] = 1;
            }
        }
        if ($e = $regex->end($g, $src, $pos)) {
            igk_is_debug() && Logger::info('tokenid:: ' . $e->tokenID);
            if ($e->tokenID == 'block') {
                $live_doc->depth = max(0, $live_doc->depth--);
            }
            if ($live_doc->detect && (is_null($e->info) || (($e->info === $g) && ($live_doc->detect->item == $g)))) {
                $doc_block = array_pop($live_doc->data);
                $live_doc->detect = $live_doc->detect->prev;
            } else {
                if ($php_docmarker && in_array($e->tokenID, ['inner_funcs', 'inner_props', 'func_conditional'])) {
                    $doc_block = $php_docmarker;
                    $php_docmarker = null;
                }
            }
            if ($e->tokenID && ($fc = igk_getv($callbacks, $e->tokenID))) {
                $fc($e, $funcs);
            }
            if ($e->tokenID == 'in-def') {
                unset($funcs[FrameworkMetadataGenerator::PROP_INDEF]);
            }
            if ($e->match->isBlock) {
                // + | --------------------------------------------------------------------
                // + | end of block
                // + |
                $php_docmarker = null;
                $php_docblock = null;
                $v_replaces = null;
                if (isset($funcs[FrameworkMetadataGenerator::PROP_BUFFER]))
                    $v_replaces = &$funcs[FrameworkMetadataGenerator::PROP_BUFFER]->replaces;
                if (!is_null($level->docReplaceWith) && ($v_replaces)) {
                    array_pop($v_replaces);
                    $level->docReplaceWith = null;
                }
                unset($v_replaces);
            }
        }
    }

    if ($php_docmarker){
        $v_replaces = &$funcs[FrameworkMetadataGenerator::PROP_BUFFER]->replaces;
        $v_replaces[] = (object)['from'=>$level->docLocationInfo->from, 'to'=>$level->docLocationInfo->to, 's'=>''];    
        //igk_wln_e("end doc block....", $level);
    }
    unset($funcs['::live-doc']);
}
$c = igk_getv($command->options, '--dir') ?? IGK_LIB_DIR;
$update_doc = property_exists($command->options, '--update-doc');
if ($regex = igk_getv($command->options, '--regex', null)) {
    $regex = "/" . $regex . "/";
}
$extra = null;
if (property_exists($command->options, '--author')) {
    $extra['author'] = empty($s = igk_getv($command->options, '--author')) ? IGK_AUTHOR : $s;
}
if ($package = igk_getv($command->options, '--package')) {
    $extra['package'] = $package;
}
/**
 * auto generate doc.
 * @param mixed $n
 * @return array
 */
function meta_to_array($n): array
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
    'versions' => meta_to_array(igk_getv($command->options, '--version', ['1.0']))
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
if ($extra) {
    $funcs[FrameworkMetadataGenerator::PROP_TYPE_EXTRA_DEF] = $extra;
}
$ln = strlen($c) + 1;
$treat = function ($tf) use (&$funcs, $ln, $update_doc) {
    if (is_link($tf)) return;
    Logger::info('treat ' . $tf);
    $rc = './' . substr($tf, $ln);
    $buffer = '';
    if ($update_doc) {
        // + | init buffer 
        $funcs[FrameworkMetadataGenerator::PROP_BUFFER] = FrameworkMetadataGenerator::InitBufferObject($buffer);
    }
    $funcs['::files'][] = $rc;
    $funcs['::location_index'] = count($funcs['::files']) - 1;
    $c = file_get_contents($tf);
    meta_getGlobalFuncs($c, $funcs);
    $update_doc && meta_updateBufferList($funcs[FrameworkMetadataGenerator::PROP_BUFFER], $c);
    if ($update_doc && $buffer) {
        Logger::warn('update file: ' . $tf);
        igk_io_w2file($tf, $buffer);
    }
    unset($funcs[FrameworkMetadataGenerator::PROP_BUFFER]);
};
/**
* auto generate doc.
* @param mixed $bf
* @param string $src
* @return void
*/
function meta_updateBufferList($bf, string $src)
{
    if (!$bf || !($rp = $bf->replaces)) {
        return;
    }
    usort($rp, function ($a, $b) {
        return $a->from <=> $b->from;
    });
    $sb = '';
    $pos = 0;
    while (count($rp) > 0) {
        $q = array_shift($rp);
        if ($q->from<$pos){
            
            continue;
        }
        $sb .= rtrim(substr($src, $pos, $q->from - $pos)) . $q->s;
        $pos = $q->to;
    }
    $sb .= substr($src, $pos);
    $bf->buffer = $sb;
    $bf->pos = strlen($sb);
}
/**
* auto generate doc.
* @param string $buffer
* @param mixed $rp
* @return void
*/
function meta_replace_value(string $buffer, $rp)
{
    return $buffer;
}
$fc = $treat;
$regex = $regex ?? '/\.php$/';
$fc = function ($c) use ($regex, $treat) {
    if (preg_match($regex, $c)) {
        $treat($c);
    }
};
$m = IO::GetFiles($c, $fc, true) ?? [];
unset($funcs['::location_index']);
unset($funcs[FrameworkMetadataGenerator::PROP_TYPE_EXTRA_DEF]);
$sk = SORT_NATURAL | SORT_REGULAR;
ksort($funcs, $sk);
ksort($traits, $sk);
ksort($interfaces, $sk);
ksort($classes, $sk);
ksort($cond_funcs, $sk);
igk_wln_e(json_encode($funcs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
