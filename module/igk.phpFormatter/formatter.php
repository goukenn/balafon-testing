<?php

use IGK\Helper\StringUtility;
use igk\phpFormatter\IFormatterBuild;
use igk\phpFormatter\IFormatterInfo;
use igk\phpFormatter\Traits\FormatterBuildTrait;
use IGK\System\IO\StringBuilder;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;
use IGK\System\Console\Logger;
use IGK\System\Exceptions\CssParserException;
use IGK\System\Exceptions\ArgumentTypeNotValidException;
use IGK\System\Text\RegexMatcherCapture;
use IGK\System\Text\RegexMatcherPattern;
use IGK\System\Text\Traits\FormatRegexMatcherTrait;
use IGK\System\Text\Traits\ReplaceUtilityTrait;

use function igk_resources_gets as __;

// @command: balafon --run .test/module/igk.phpFormatter/formatter.php
require_once __DIR__ . '/PHPFormatterTmSyntaxTrait.php';

function igk_assert_func_num_arg(int $number, int $expected)
{
    if ($number != $expected) {
        igk_die(sprintf(__('invalid number of argument. expected %s got %s'), $expected, $number));
    }
}
class PHPFormatter implements IFormatterBuild, IFormatterInfo
{
    use FormatRegexMatcherTrait;
    use FormatterBuildTrait;
    use PHPFormatterTmSyntaxTrait;
    use ReplaceUtilityTrait;
    
    private $m_sb;
    var $depth;
    var $lineFeed = false;
    var $tabStop = '    ';
    var $lineFeedSeparator = "\n";
    var $flags = [];
    const CONDITIONAL_WORDS = 'if|else|for|while|do|foreach|switch';
    private $m_fconditional_info = null;
    private $m_replacement = [];

    public function __construct()
    {
        $this->m_sb = new StringBuilder;
        $this->depth = 0;
        $this->lineFeed = false;
        $this->tabStop = '-123';
        $this->initFlags($this->flags);
    }

    /**
     * 
     * @param StringBuilder $builder 
     * @param igk\phpFormatter\IFormatterInfo $info 
     * @param string $before 
     * @param string $data 
     * @param int $at 
     * @return void 
     * @throws Exception 
     */
    public function build(StringBuilder $builder, IFormatterInfo $info, string $before, string $data, int $at)
    {
        $s = $data;
        $i = $info;
        $w = 1;
        $__STEP__ = igk_env_count(__METHOD__);
        igk_is_debug() && Logger::print('formatter->build::::: ' . $__STEP__ . ' [' . json_encode($data) . '] depth=' . $info->depth . ' linefeed=' . $info->lineFeed);

        extract(igk_extract_var($info->flags, 'lineFeed|instruct|closeBracket|appendInlineInstruct|start-curl|preserveData'));


        if (!empty(trim($before)) || ($before == ' ') || isset($info->flags['before'])) {
            if (isset($i->flags['before'])) {
                $before = $i->flags['before'];
                unset($i->flags['before']);
            }
            if (isset($i->flags['lf'])) {
                $before = ltrim($before);
            }
            $builder->append($before);
        }
        if (igk_getv($i->flags, 'brankStart')) {
            $this->flags['start-curl'][] = $builder->rtrim()->length() + strlen($s);
            $info->unsetFlag('lf');
            $info->unsetFlag('brankStart');
            $info->lineFeed = true;
        }
        if (igk_getv($i->flags, 'closeBracket')) {
            if ($t = &$this->flags['start-curl']) {
                $c = array_pop($t);
                $ts = substr("" . $builder, $c);
                if (empty(trim($ts))) {
                    $builder->rtrim();
                    $info->unsetFlag('lf');
                } else {

                    // $builder->rmLast($info->lineFeedSeparator);
                    $info->lineFeed = true;
                }
            }
            $info->unsetFlag('closeBracket');
        }
        $_line_feed_after = false;
        if (isset($info->flags['appendInlineInstruct'])) {
            if (isset($info->flags['instruct'])) {
                if (isset($info->flags['lf'])) {
                    $builder->rmLast($info->lineFeedSeparator);
                    $info->lineFeed = true;
                    $info->unsetFlag('lf');
                    $info->unsetFlag('instruct');
                }
            }
            $info->unsetFlag('appendInlineInstruct');
        }
        if (isset($info->flags['preserveData'])) {
            //     $info->lineFeed = false;
            //     $info->unsetFlag('lf');
            //     $info->unsetFlag('instruct');
            Logger::info("preserve data:>>>" . $data);
            $info->unsetFlag('preserveData');
            if ((!$builder->isEmpty() || (strlen(trim($s)) > 0))) {
                $builder->append($s);
            }
        } else {



            if (igk_getv($i->flags, 'lf')) {
                // remove white space 
                $s = ltrim($s);
                if (!empty($s)) {
                    $s = $i->tab() . $s;
                    $i->unsetFlag('lf');
                } else {
                    $w = 0;
                }
            }
            if ($w && (!$builder->isEmpty() || (strlen(trim($s)) > 0))) {
                $builder->append($s);
                if ($i->lineFeed) {
                    $builder->append($i->lineFeedSeparator);
                    $i->setFlag('lf', 1);
                }
                $i->lineFeed = $_line_feed_after;
            }
        }
        igk_is_debug() && Logger::success('formatter->append >>> [' . json_encode($builder . '') . ']');
    }
    /**
     * init flags
     * @param mixed &$flags 
     * @return void 
     */
    public function initFlags(&$flags) {}

    function reset()
    {
        $this->flags = [];
        $this->initFlags($this->flags);
    }

    /**
     * handle treat capture 
     * @param string $value 
     * @param mixed $cap 
     * @param string $sourceValue 
     * @param int $pos 
     * @return mixed 
     * @throws Exception 
     */
    protected function treatCapture(string $value, $cap, string $sourceValue, int $pos): string
    {
        $n = igk_getv($cap, 'name');
        $s = $value;
        if (!is_null($g = self::TreatCaptureReplace($s, $cap, $sourceValue, $pos))) {
            return $g;
        }
        $capVisitor = $this->m_captureVisitor ?? $this;
        $prefix = $capVisitor === $this ? '_visit_capture_' : '';
        if (method_exists($capVisitor, $fc = $prefix . StringUtility::FuncName($n))) {
            return call_user_func_array([$capVisitor, $fc], func_get_args());
        }
        // $s = $value;
        // $n = igk_create_node("div");
        // $n['class'] = igk_getv($cap, 'name');
        // $n->content = trim($value);
        // return $n->render();
        return trim($value);
    }
    /**
     * format code and initialize
     * @param string $format 
     * @return string 
     */
    public function format(string $format): string
    {
        // to formaat we take only on null block 
        $regex = $this->createRegexContainer();
        $regex->captureTreatmentListener = Closure::fromCallable([$this, 'treatCapture']);
        $pos = 0;
        $info = [];
        $offset = 0;
        $listener = $this->formatListener ?? $this;
        while ($g = $regex->detect($format, $pos)) {
            if ($e = $regex->end($g, $format, $pos)) {
                $tid = $e->tokenID;
                $s = $e->value;
                igk_is_debug() && Logger::info('tokenidss: ' . $tid . ' [' . json_encode($e->value) . '] ' . $pos . ' from ' . $e->from);
                if (is_null($e->parentInfo)) {
                    $info = &$this->flags;
                    $before = substr($format, $offset, $e->from - $offset);
                    if ($e->emptyLine)
                        continue;
                    // format custom replacement value
                    $s = $e->value = $this->_treatReplacement($e, $format);

                    $cinf = $this->m_fconditional_info;
                    if (self::IsConditionalReservedWord($e, 'f-reservedword')) {
                        if ($q = $cinf) {
                            if ($q->stop && ($q->dcmode == 0)) {
                                // on stop for end instruction 
                                $this->depth = max(0, $this->depth - 1);
                            }
                        } else {
                            self::RequireConditionalParent($e->value) ?? igk_die('required conditional parent');
                        }
                        $v_p = null;
                        $v_c = $this->m_fconditional_info = $this->CreateConditonalInfo($e->value);
                        if ($q) {
                            if ($q->isElse() && ($q->stop)) {
                                // stop the parent                                 
                                $q = $q->parent->parent;
                            } else {
                                (!$q->stop) && ($e->value == 'else') && igk_die('`else` not allowed');
                            }
                            while ($q && $q->stop) {
                                if ($q->supportChild($e->value)) {
                                    $q->appendChild($v_c);
                                    $v_p = $q;
                                    break;
                                } else {
                                    // 
                                    $q = $q->parent;
                                }
                            }
                        }
                        if (is_null($v_p) && $v_c->requiredParent()) {
                            igk_die('invalid syntax');
                        }
                        $v_c->parent = $v_p;
                        if (igk_getv($this->flags, 'lf')) {
                            $this->unsetFlag('lf');
                            $this->m_sb->rtrim();
                            $this->m_sb->append($this->lineFeedSeparator . $this->tab());
                        }
                        if (!$v_c->supportCondition()) {
                            $this->lineFeed = true;
                        }
                        if ($v_c->isElse() && $v_p) {
                            $v_c->cond = self::GetChainChildsCondition($this, $v_p);
                        }
                        $this->depth++;
                    } else {
                        if ($q = $cinf) {
                            $dirty = !(empty(trim($e->value)) || strstr($tid, 'comment') || strstr($tid, 'wspace'));
                            if ($q->stop && $dirty) {
                                $this->depth = max(0, $this->depth - 1);
                                // stop and go to parent 
                                $this->m_fconditional_info = $cinf->parent;
                                $this->flags['instruct'] = null;
                            } else if ($q->supportCondition() && !$q->cond && $dirty) {
                                if ($q->dirty) {
                                    igk_die('bad syntax - conditional bad syntax ');
                                }
                                $q->dirty = true;
                            }
                        }
                    }
                    switch ($tid) {
                        case 'single-comment':
                            $this->lineFeed = true;
                            if (isset($info['instruct'])) {
                                $s = $before . $s;
                                $before = '';
                                $this->m_sb->rtrim("\n");
                                unset($info['instruct']);
                                $this->unsetFlag('lf');
                            } else {
                                $this->m_sb->rtrim();
                                $s = trim($s);
                                $s = $this->lineFeedSeparator . $this->tab() . $s;
                            }
                            break;
                        case 'f-instruct':

                            break;
                        default:

                            if (method_exists($listener, $fc = 'visit_' . StringUtility::FuncName($tid))) {
                                $s = call_user_func_array([$listener, $fc], [$e->value, $e, $pos]);
                            }
                            break;
                    }
                    if (($s === false) || (is_null($s)))
                        continue;
                    $this->build($this->m_sb, $this, $before, $s, $pos, $format);
                    $offset = $pos;
                } else {
                    Logger::danger('not allowed ' . $tid);

                    // if (method_exists($listener, $fc = 'visit_sub_' . StringUtility::FuncName($tid))) {
                    //     $tab = [[$listener, $fc], [$e->value, $e, $pos]];
                    //     // replacement use for invocation 
                    //     $this->m_replacement[] = [
                    //         $e,
                    //         null,
                    //         'marked' => true,
                    //         'type' => 'mark',
                    //         'for' => $tid,
                    //         'invoke' => function ($render = false) use ($tab) {
                    //             $args = $tab[1];
                    //             array_unshift($args, $render);
                    //             call_user_func_array($tab[0], $args);
                    //         }
                    //     ];
                    // }
                    // if (!is_null($e->match->replaceWith)) {
                    //     $this->m_replacement[] = [
                    //         $e,
                    //         $e->match->replaceWith
                    //     ];
                    // } else {
                    //     $this->formatSubPattern($e, $format, $this->m_replacement);
                    // }
                }
            }
        }
        $r = &$this->m_fconditional_info;
        while ($r) {
            if (!$r->stop) {
                igk_die('condition not stop');
            }
            $r = $r->parent;
        }
        if ($this->m_fconditional_info) {
            igk_die('still in condition ');
        }
        $this->m_sb->rtrim();
        return $this->m_sb . '';
    }
    protected function visit_f_instruct($s)
    {
        $s = sprintf('%s', trim($s));
        $info['instruct'] = 1;
        $this->lineFeed = true;
        if ($this->m_fconditional_info) {
            if ($this->m_fconditional_info->dcmode == 0)
                $this->m_fconditional_info->stop = true;
        }
        return $s;
    }
   
    /**
     * 
     * @param mixed $e 
     * @return string 
     */
    protected function _treatReplacement($e): string
    {

        $s = $e->value;
        $pr = false;
        $ns = '';
        $offset = 0;
        $start = $e->from;
        $sub = null;
        $v_def = [];
        $tid = $e->tokenID;
        if (!is_null($l = self::ResolveCapture($e, $v_def, $this->m_replacement))) {
            return $l;
        }
        $sb = new StringBuilder($ns);
        $restore = function ($state) {
            // + | restore
            foreach ($state as $k => $v) {
                $this->{$k} = $v;
            }
        };
        $save_state = function ($r) {
            $state = igk_extract_obj($r, 'depth|lineFeed|flags');
            return $state;
        };
        igk_is_debug() && Logger::success('start - replacement');
        $state = igk_extract_obj($this, 'depth|lineFeed');
        $v_baseoffset = 0;
        $cp_first_mark = [];
        while (count($this->m_replacement) > 0) {
            $q = array_shift($this->m_replacement);
            $te = $q[0];
            $p = $q[1];
            $remove = igk_getv($q, 'remove') || $te->emptyLine;
            $marked = igk_getv($q, 'marked');
            if (($te->from < $e->from) || ($te->to > $e->to)) {
                array_unshift($q);
                break;
            }
            if ($marked) {
                if (is_null($sub)) {
                    $cp_first_mark[] = $q;
                } else {
                    // marker definition 
                    $sub->mark[] = $q;
                }
                // invoque definition 
                if ($invoke = igk_getv($q, 'invoke')) {
                    $invoke(false);
                }
                continue;
            }
            igk_is_debug() && Logger::warn('replace: value[' . json_encode($te->value) . '] * ' . $te->from . ' vs ' . $offset);

            $rpv = $te->value;
            $tc = $te->from - $offset - $start;
            $v_update = false;  // update the current data
            if ($sub) {
                $chains = self::_GetChainList($sub, $te, $last);

                if ($chains) {
                    // $sub_state = $save_state($this); // state at the end 
                    if (($mc = count($sub->mark)) == 0) {
                        $v_baseoffset = $e->from;
                        $last = null;
                        $restore($state); // state global 
                    } else {
                        $v_baseoffset = $last->to - $e->from;
                        $gstate = $sub->mark[$mc - 1][2];
                        $restore($gstate); // clear setting 
                        // disable flag storage
                        //$this->lineFeed = false;
                        //$this->flags = [];
                    }

                    $v_cdef = [];
                    self::ResolveCapture($te, $v_cdef, []);

                    $rpv = self::UpdateMarkedValue($rpv, $chains, $te->from, $this, true);

                    if ($v_cdef) {
                        $rpv = self::UpdateCaptureDef($te, $v_cdef, $rpv);
                    }
                    $offset = $v_baseoffset;
                    $ns = '';
                    // $restore($sub_state);
                    $tc = $te->from - $offset - $start;
                    unset($chains);
                    $v_update = true;

                    $p = $this->treatLast($rpv, $this);
                }
            }

            if ($tc < 0) {
                igk_wln_e('forward group detected');
            }
            $_pos = 0;
            if ($remove) {
                $p = '';
                $_pos = 1;
            } else {
                if (!$v_update) {
                    $p = self::ReplaceData($rpv, $te, $p);
                }
            }
            $pr = true;
            // if ($te->match->autoLineFeed) {
            //     $this->lineFeed = true;
            //     $this->setFlag('lf', true);
            // }
            // + | render offscreen not necessary because of rendering on other system
            // Logger::success('offscreen ------------------');
            // $before = '|'; //substr($s, $offset, $tc);
            // $this->build($sb, $this, $before, $p, $te->from, '');


            $offset = ($te->to + $_pos) - $start;
            if (!$remove) {
                $this->lineFeed = $this->lineFeed || $te->match->autoLineFeed;
            }
            $_inf = [$te, $p, $save_state($this), 'updated' => $v_update];
            if (is_null($sub)) {
                $sub = (object)[
                    'min' => $te->from,
                    'max' => $te->to,
                    'mark' => [$_inf]
                ];
                if ($cp_first_mark) {
                    while (count($cp_first_mark)) {
                        array_unshift($sub->mark, array_pop($cp_first_mark));
                    }
                }
            } else { // save state at the end of the block
                $sub->mark[] = $_inf;
            }
        }

        // + | restore global state --- 
        $restore($state);
        if ($sub) {
            $ns = '';

            $ns = self::UpdateMarkedValue($e->value, $sub->mark, $e->from, $this, false);
        }
        if ($pr) {
            $ns .= substr($s, $offset, $e->to - $offset - $start);
            $s = $ns;
        }
        $s = self::UpdateCaptureDef($e, $v_def, $s);
        $s = self::ReplaceData($s, $e);

        return $s;
    }
    /**
     * retrieve chain list 
     * @param mixed $sub subchain mark container 
     * @param mixed $te source element 
     * @param mixed &$last last element
     * @return array 
     * @throws Exception 
     */
    private static function _GetChainList($sub, $te, &$last)
    {
        $last = null;
        $tm = &$sub->mark;
        $chains = [];
        while (($tc = count($tm)) > 0) {
            $last = $sub->mark[$tc - 1][0];
            if ($last->from > $te->from) {
                array_unshift($chains, array_pop($tm));
                if ($tc > 1) {
                    if (igk_getv($gc = $tm[$tc - 2], 'marked') && ($gc[0] === $last)) {
                        array_unshift($chains, array_pop($tm));
                    }
                }
                continue;
            }
            break;
        }
        return $chains;
    }
   
    /**
     * 
     * @param string $value 
     * @param array $mark 
     * @param int $start 
     * @param mixed $builder 
     * @param bool $glue 
     * @return string 
     * @throws Exception 
     * @throws Error 
     * @throws IGKException 
     * @throws CssParserException 
     * @throws ArgumentTypeNotValidException 
     * @throws ReflectionException 
     */
    public static function UpdateMarkedValue(string $value, array $mark, int $start, $builder, bool $subchain)
    {
        igk_assert_func_num_arg(func_num_args(), 5);
        $s = '';
        $offset = 0;
        $el = false;
        $sb = new StringBuilder($s);

        while (count($mark) > 0) {
            $q = array_shift($mark);
            $e = $q[0];
            $p = $q[1];
            $updated = igk_getv($q, 'updated');
            $v_p = 0;
            if (is_null($p)) {
                if ($invoke = igk_getv($q, 'invoke')) {
                    $invoke(true);
                }
                continue;
            }
            $match = ($e->match instanceof PHPFormatRegexMatcherPattern) ? $e->match : null;
            if ($e->emptyLine) {
                if (!$el && !empty($s)) {
                    $builder->lineFeed = true;
                    $builder->build($sb, $builder, '', $p, $start, '');
                }
                $el = true;
                $v_p = 1;
            } else {
                if ($match) {
                    $builder->lineFeed = $builder->lineFeed || ($match->lineFeed) || $match->autoLineFeed;
                    if ($match->autoLineFeed) {
                        $builder->setFlag('lf', true);
                    }
                    if ($match->flags) {
                        foreach ($match->flags as $c) {
                            $builder->setFlag($c, true);
                        }
                    }
                    if ($trim = $match->trimmed) {
                        if (is_string($trim) && in_array($trim, ['trim', 'rtrim', 'ltrim'])) {
                            $p = $trim($p);
                        } else
                            $p = trim($p);
                    }
                    if ($updated) {
                        $builder->setFlag('preserveData', true);
                    }
                }
                $before = substr($value, $offset, $e->from - $offset - $start);
                $builder->build($sb, $builder, $before, $p, $start, '');
                $el = false;
                if ($updated) {
                    $builder->loadStates($q[2]);
                }
            }
            $offset = ($e->to - $start) + $v_p;
        }
        if (!$subchain && !empty($l = rtrim(substr($value, $offset)))) {
            igk_wln("append extract :------------------------------------------ " . $l);
            $builder->build($sb, $builder, '', $l, $start, '');
        }
        igk_is_debug() && igk_wln(__FILE__ . ":" . __LINE__,  json_encode($s));
        return $s;
    }
    public function loadStates($state)
    {
        foreach ($state as $k => $v) {
            $this->{$k} = $v;
        }
    }
    /**
     * treat last builder information 
     * @param StringBuilder $sb 
     * @param IFormatterInfo $info 
     * @param mixed $builder 
     * @return void 
     */
    function treatLast(string $sb, IFormatterInfo $info): string
    {
        $lb = $info->lineFeedSeparator;
        if (isset($info->flags['instruct'])) {
            if (isset($info->flags['lf'])) {
                $info->lineFeed = true;
                $info->unsetFlag('lf');
            }
            return igk_str_rm_last($sb . '', $lb);
        }
        return $sb;
    }
   


    protected function visit_f_wspace(string $v,)
    {
        return ' ';
    }
    public function visit_f_notsymbol($v)
    {
        return ' ' . trim($v);
    }
    protected function visit_f_operator(string $v)
    {
        return sprintf(' %s ', trim($v));
    }
    protected function visit_empty_line(string $v)
    {
        if (!$this->lineFeed) {
            $this->lineFeed = true;
            $this->m_sb->rtrim();
            if ($this->m_sb->isEmpty()) {
                $this->lineFeed = false;
                return false;
            }
        }
        return '';
    }
    protected function visit_sub_curl_start(bool $render, string $v)
    {
        $this->depth++;
        $this->lineFeed = true;
        $this->unsetFlag('lf');
        if ($render) {
            $this->flags['brankStart'] = true;
        }
    }
    protected function visit_sub_curl_end(bool $render, string $v)
    {
        $this->depth = max(0, $this->depth - 1);
        $this->lineFeed = true;
        if ($render)
            $this->flags['closeBracket'] = true;
    }
    protected function visit_curl_start(string $v)
    {
        if ($this->m_fconditional_info) {
            $this->depth--;
            $this->m_fconditional_info->dcmode = PHPFormatterConditionalInfo::READ_BLOCK;
            $this->m_sb->rtrim();
            $this->unsetFlag('lf');
            $this->setFlag('before', '');
        }
        $this->depth++;
        $this->flags['start-curl'][] = $this->m_sb->length() + 1;
        $this->lineFeed = true;
        return trim($v);
    }
    protected function visit_curl_end(string $v)
    {
        if (isset($this->flags['start-curl'])) {
            $t = &$this->flags['start-curl'];
        } else {
            return;
        }
        $g = array_pop($t);
        $this->depth = max(0, $this->depth - 1);
        $lf = isset($this->flags['lf']);
        if ($g) {
            $this->unsetFlag('lf');
            $ln = $this->m_sb->length();
            $sb = substr($this->m_sb . '', $g, $ln - $g);
            if (empty(trim($sb))) {
                $this->m_sb->rtrim();
            } else {
                if ($lf) $this->m_sb->rtrim();
                $this->m_sb->append($this->lineFeedSeparator . $this->tab());
            }
        }
        if ($this->m_fconditional_info) {
            if ($this->m_fconditional_info->dcmode == PHPFormatterConditionalInfo::READ_BLOCK) {
                $this->m_fconditional_info->stop = true;
            }
        }
        $this->lineFeed = true;
        return trim($v);
    }
    public function visit_f_func_declare(string $v, $e)
    {
        $m = [];
        $m[] = $e->beginCaptures[1][0];
        if (isset($e->beginCaptures[2]) && $e->beginCaptures[2][0]) {
            $m[] = trim($e->beginCaptures[2][0]);
        }
        return trim(implode(' ', $m)) . ' ';
    }
    public function visit_f_symbol(string $v)
    {
        return trim($v) . ' ';
    }
    public function createRegexContainer()
    {
        $regexContainer = new RegexMatcherContainer;
        $this->initRegex($regexContainer);
        return $regexContainer;
    }

    protected function visit_f_func($v)
    {
        if ($v_c = $this->m_fconditional_info) {
            if ($this->m_fconditional_info->supportCondition()) {
                if (!$v_c->cond && $v_c->dirty) {
                    $this->m_fconditional_info->cond = $v;
                    $this->lineFeed = true;
                }
            }
        }
        return trim($v);
    }
    static function IsConditionalReservedWord(RegexMatcherCapture $e, $rid = 'reserved-word'): bool
    {
        $tid = $e->tokenID;
        return ($tid == $rid) && in_array($e->value, self::ConditionalWords());
    }
    static function ConditionalWords(): array
    {
        return explode('|', self::CONDITIONAL_WORDS);
    }
    static function CreateConditonalInfo(string $word)
    {
        return PHPFormatterConditionalInfo::CreateConditionalInfo($word);
    }
    /**
     * 
     * @param string $word 
     * @return bool 
     */
    static function RequireConditionalParent(string $word): bool
    {
        return $word == 'else';
    }
    public function GetChainChildsCondition($self, $parent)
    {
        $p = $parent;
        $cond = [$parent->cond];
        while ($p->subchild_parent) {
            $p = $p->subchild_parent;
            $cond[] = $p->cond;
        }
        return count($cond) > 1 ? sprintf('(%s)', implode(' && ', $cond)) : $cond[0];
    }
    protected function _visit_capture_operator($s)
    {
        return sprintf(' %s ', trim($s));
    }
    protected function _visit_capture_here_doc($s)
    {
        return $s;
    }
}

class PHPFormatterConditionalInfo
{
    var $type;
    var $cond;
    var $stop;
    var $parent;
    var $dcmode;
    var $dirty = false;
    var $subchild_parent;
    /**
     * store level childs
     * @var array
     */
    var $childs = [];
    const START = 0;
    const READ_BLOCK = 1;
    var $singleElement;
    protected function __construct()
    {
        $this->stop = false;
        $this->dcmode = self::START;
    }
    public function appendChild($c)
    {
        if ($this->type == 'if') {
            ($c->type == 'if') && igk_die('`if` not a valid level type');
            if ($c->type == 'else') {
                $this->singleElement = $c;
            }
        }
        $this->childs[] = $c;
    }
    /**
     * create and conditional info 
     * @param string $word 
     * @return static 
     */
    public static function CreateConditionalInfo(string $word)
    {
        $c = new static;
        $c->type = $word;


        return $c;
    }
    public function supportCondition(): bool
    {
        return $this->type != 'else';
    }
    public function isElse()
    {
        return $this->type == 'else';
    }
    public function supportChild(string $v): bool
    {
        switch ($this->type) {
            case 'if':
                return in_array($v, ['else', 'elseif']);
                break;
        }
        return false;
    }
    public function requiredParent(): bool
    {
        return $this->isElse();
    }
}

class PHPFormatRegexMatcherPattern extends RegexMatcherPattern
{
    var $replaceWith;
    var $autoLineFeed = false;
    var $trimmed = false;
    /**
     * append line after render
     * @var bool
     */
    var $lineFeed = false;
    /**
     * flags definition 
     * @var mixed
     */
    var $flags;
}
$formatter = new PHPFormatter;





echo  $formatter->format(file_get_contents(__DIR__ . '/data/exo.comment.php')), PHP_EOL;
exit;
