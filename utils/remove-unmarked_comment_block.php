<?php
// @author: C.A.D. BONDJE DOUE
// @filename: remove-unmarked_comment_block.php
// @date: 20260423 11:30:37
// @desc: remove unmarked comment block . single line comment , cleaup script 
// @command: balafon --run .test/utils/remove-unmarked_comment_block.php [dir_or_file]
use IGK\Helper\IO;
use IGK\System\Console\App;
use IGK\System\Console\Logger;
use IGK\System\Php\Helper\PhpScriptUtility;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

function detect_init(array & $list, string $file)
{
    if (!isset($list[$file])) {
        $list[$file] = igk_createobj([
            'count' => 1,
            'file' => $file,
            'rp' => [],
            'litterals' => [],
        ]);
    }
}
function detect_comments(array &$list, string $file)
{
    $src = file_get_contents($file);
    $regex = new RegexMatcherContainer;
    $regex->appendStringDetection('string', true);
    $heredoc = [];
    RegexMatcherUtility::AppendPhpHereDoc($regex, $heredoc);
    $regex->match("\/\/ (\+|@).+", 'php-ignore');
    $regex->match("\/\/\/ [a-zA-Z]+:", 'php-markup');
    $regex->match("\/\/#\{\{(.+)+", 'php-balafon-var-operation');
    $regex->begin("\/\*\*", "\*\/", "php-doc");
    $regex->appendSingleLineComment();
    $regex->appendMultilineComment();
    $regex->begin('\?>', '<\?(php\\b|=)', 'php-outside');
    $list['::file'] = $file;
    $fc_handle = [
        'single-comment' => function ($e, $pos, &$list) {
            $file = $list['::file'];
            detect_init($list, $file);

            $list[$file]->count++;
            $list[$file]->rp[] = $e;
            $list[$file]->litterals[] = sprintf('[%s] at %s', $e->value, $e->from);
        },
        'comment-multiline' => function ($e) use (&$list) {
            $file = $list['::file'];
            detect_init($list, $file); 
            $list[$file]->rp[] = $e;
            $list[$file]->litterals[] = sprintf('[%s] at %s', $e->value, $e->from);
        }
    ];
    $pos = 0;
    $pos = PhpScriptUtility::SkipShebang($src, $pos);
    $is_debug = igk_is_debug();
    $is_debug && Logger::info('detect-comment-on-file: ' . $file);
    while ($g = $regex->detect($src, $pos)) {
        if ($e = $regex->end($g, $src, $pos)) {
            $id = $e->tokenID;

            if ($id && ($fc = igk_getv($fc_handle, $id))) {
                $fc($e, $pos, $list);
            }
        }
    }
    unset($list['::file']);
}
function removeTargetInformation(string $src, array $rp)
{
    $out = '';
    $offset = 0;
    while (count($rp) > 0) {
        $q = array_shift($rp);
        $out .= substr($src, $offset, $q->from - $offset);
        $offset = $q->to;
    }
    $out .= substr($src, $offset);
    return $out;
}
function showAndRemoveCommentList(array $list, bool $cleanAll = false)
{
    $v_canread_line = function_exists('readline');
    $v_count = 0;
    $v_TCount = count($list);
    foreach ($list as $file => $info) {
        Logger::print($info->file);
        Logger::info(implode("\n", $info->litterals));
        $v_count++;
        if ($cleanAll || $v_canread_line && readline(sprintf(
            '(%4s/%5s) : update file %s ? %s',
            $v_count,
            $v_TCount,
            $info->file,
            App::Gets(App::GRAY, '(y/n) ')
        )) == 'y') {
            $newSrc = removeTargetInformation(file_get_contents($info->file), $info->rp);
            igk_io_w2file($info->file, $newSrc);
        }
    }
}
/**
 * 
 * @param mixed $params 
 * @param mixed $command 
 * @return void 
 */
function run_script($params, $command)
{


    list($dir,) = igk_extract($params, '0|1');
    list($isCleanAll) = igk_prop_exists($command->options, '--clean-all');
    $regex = sprintf('/%s/', igk_getv($command->options, '--regex') ?? '\.php$');
    $list = [];
    $exclude = ['.git', 'vendor', '.vscode', 'node_modules'];
    igk_wln('directory: ', realpath($dir), '');
    if (is_dir($dir)) {
        IO::GetFiles($dir, function ($file) use (&$list, $regex) {
            if (preg_match($regex, $file)) {
                detect_comments($list, $file);
            }
        }, true, $exclude);
    } else {
        detect_comments($list, $dir);
    }

    if (count($list) > 0) {
        Logger::print('Items : ' . count($list));
        showAndRemoveCommentList($list, $isCleanAll);
    } else {
        Logger::info('no comment found.');
    }
}

/**
 * @var array $params
 * @var mixed $command
 */

run_script($params, $command);
igk_exit();
