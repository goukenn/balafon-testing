<?php
// @author: C.A.D. BONDJE DOUE
// @filename: remove-empty.line.php
// @date: 20260329 20:11:00
// @desc: remove empty line on files 
// @command: balafon --run .test/utils/remove-empty.line.php
// @desc: remove empty line 
use IGK\Helper\IO;
use IGK\System\Console\Helper\ConsoleUtility;
use IGK\System\Console\Logger;
use IGK\System\Php\Helper\PhpScriptUtility;
use IGK\System\Text\RegexMatcherContainer;

if (ConsoleUtility::SupportHelp($command)) {
    igk_wln('Remove Empty line on Script');
    igk_wln('Usage: ');
    igk_wln(' file_or_dir [options]');
    igk_wln_e('');
}
/**
 * glus js code 
 * @param string $code 
 * @return string 
 * @throws IGKException 
 * @throws Exception 
 */
function glue_js_code(string $code)
{
    $o = '';
    $v_rgx = new RegexMatcherContainer;
    $v_rgx->begin('(`)([^`]*)', "\\1");
    $offset = 0;
    $p = 0;
    while ($g = $v_rgx->detect($code, $offset)) {
        if ($e = $v_rgx->end($g, $code, $offset)) {
            $sb = substr($code, $p, $e->from - $p);
            $o .= glue_rm_empty($sb) . $e->value;
            $p = $e->to;
        }
    }
    if ($p < strlen($code)) {
        $o .= glue_rm_empty(substr($code, $p));
    }
    return $o;
}
/**
* auto generate doc.
* @param mixed $code
* @return mixed
*/
function glue_on_header_code($code)
{
    $regex = new RegexMatcherContainer;
    $regex->match('<\?php\\b', 'php-preproc');
    $regex->begin('\\b(use)\\b', ';', 'use-list');
    $regex->appendSingleLineComment();
    $regex->appendMultilineComment();
    $regex->begin('\\b(namespace)\\b', ';', 'use-namespace');
    $regex->match('[^\\s]+', 'break');
    $pos = 0;
    $src = $code;
    $header = false;
    $ln = '';
    $mark = false;
    $pos = PhpScriptUtility::SkipShebang($src, $pos);
    while ($g = $regex->detect($src, $pos)) {
        if ($e = $regex->end($g, $src, $pos)) {
            if (($e->tokenID == 'break') || ($header && ($e->tokenID=='comment-multiline'))) {
                $pos = $e->from;
                break;
            }
            if (!$header && ($e->tokenID!='php-preproc')){
                $header = true;
            }
        }
    }
    return $pos;
}
/**
* auto generate doc.
* @param string $code
* @return mixed
*/
function glue_php_code(string $code)
{
    $o = '';
    $v_rgx = new RegexMatcherContainer;
    $v_rgx->begin('<<<(\'|")?([a-zA-Z][a-zA-Z_]*)\\1?', "^\\2\\b");
    $offset = 0;
    $p = 0;
    if ($offset = glue_on_header_code($code)){
        $o .= glue_rm_empty(substr($code, 0, $offset))."\n\n";
        $p = $offset;
    }
    while ($g = $v_rgx->detect($code, $offset)) {
        if ($e = $v_rgx->end($g, $code, $offset)) {
            $sb = substr($code, $p, $e->from - $p);
            $o .= glue_rm_empty($sb) . $e->value;
            $p = $e->to;
        }
    }
    if ($p < strlen($code)) {
        $o .= glue_rm_empty(substr($code, $p));
    }
    return $o;
}
/**
* auto generate doc.
* @param mixed $content
* @return mixed
*/
function glue_rm_empty($content)
{
    return  implode("\n", array_filter(explode("\n", $content), function ($s) {
        return (strlen(trim($s)) !== 0);
    }));
}
/**
* auto generate doc.
* @param mixed $file
* @return mixed
*/
function transform($file)
{
    $content = file_get_contents($file);
    $ext = igk_io_path_ext($file);
    switch ($ext) {
        case 'js':
            $g = glue_js_code($content);
            break;
        case 'php':
        case 'phtml':
        case 'pinc':
        case 'pcss':
            $g = glue_php_code($content);
            break;
        default:
            $g = glue_rm_empty($content);
            break;
    }
    igk_io_w2file($file, $g);
}
$file = igk_getv($params, 0);
$skip = igk_getv($command->options, '--skip');
if ($file && file_exists($file)) {
    if (is_dir($file)) {
        $files = IO::GetFiles($file, "/\.(js|php|phtml|pcss|pinc)$/", true);
        foreach ($files as $file) {
            if ($skip && preg_match('/(' . $skip . ')/', $file)) {
                continue;
            }
            Logger::info('treat: ' . $file);
            transform($file);
        }
    } else {
        transform($file);
    }
    Logger::success("done");
} else {
    Logger::danger("request existing file!");
}
igk_exit();