<?php
// @author: C.A.D. BONDJE DOUE
// @file: FrameworkRegLevelManager
// @date: 20260228 08:56:23
namespace IGK\System\Console\Commands\Utility;

use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Console\Commands\Utility\IFrameworkRegLevelManager;
/**
* auto generate doc.
* @package IGK
* @author C.A.D. BONDJE DOUE
*/
class FrameworkRegLevelManager
{
    /**
     * level marker separator
     * @var string
     */
    var $separator = '    ';
    /**
     * document
     * @var ?string
     */
    var $doc;
    /**
     * location information
     * @var ?mixed|IFrameworkRegLevelManager location information 
     */
    var $docLocationInfo;
    /**
     * doc replaced with 
     * @var mixed
     */
    var $docReplaceWith;
    /**
     * location
     * @var ?int 
     */
    var $location;
    /**
    * auto generate doc.
    * @param string $doc
    * @param mixed $e
    * @param mixed $tabSeparator
    */
    public static function FormatDoc(string $doc, $e, $tabSeparator)
    { 
        $d = FrameworkRegLevelManager::GetDepth($e);
        $tab = str_repeat($tabSeparator, $d); 
        $doc = $tab . implode("\n" . $tab, array_map('trim', explode("\n", $doc)));
        return $doc;
    }
    /**
     * auto generate doc.
     * @param mixed $src
     * @param mixed $definition
     */
    public static function ReadArgDeclaration($src, $definition = false)
    {
        $regex = new RegexMatcherContainer;
        $pos = 0;
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
    * read only function parameters
    * @param mixed $src
    * @param mixed & $pos
    * @param mixed & $return
    * @return array
    */
    public static function ReadFuncParams(string $src, int &$pos, &$return): array
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
        $e_stop = false;
        while ($g = $regex->detect($src, $pos)) {
            if ($e = $regex->end($g, $src, $pos)) {
                if ($e->getisRootCaptured()) {
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
    * auto generate doc.
    * @param ?string $type
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
        } else if ($this->docReplaceWith) {
            $d['doc'] = $this->docReplaceWith;
            $this->docReplaceWith = null;
        }
        if ($type) {
            $d['type'] = $type;
        }
        if ($d) {
            if ($this->docLocationInfo)
                $d['location']=(object)$this->docLocationInfo->to_array();
            $d['$r'] = $this->location;
            return (object)$d;
        }
        return 1;
    }
    /**
    * auto generate doc.
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
    /**
    * auto generate doc.
    * @return void
    */
    public function __construct()
    {
    }
}