<?php
// @author: C.A.D. BONDJE DOUE
// @filename: extract-classes.php
// @date: 20260409 08:29:26
// @desc: extract classes definition 
// @command: balafon --run .test/commands/reflections/extract-classes.php
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\File\PHPScriptBuilder;
use IGK\System\IO\Path;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherContainerTreatment;
use IGK\System\Text\RegexMatcherUtility;

$dir = igk_getv($params, 0) ?? igk_die('directory required');
if (!is_dir($dir)) {
    $dir = __DIR__ . '/data/' . $dir;
    if (!is_dir($dir)) {
        igk_die('missing directory');
    }
}
$outdir = igk_getv($command->options, '--outdir') ?? $dir . '/output';
$inf = (object)[
    'outdir' => $outdir,
    'file' => null,
    'modifier' => null,
    'type' => null,
    'name' => null,
    'filter' => null,
    'src' => null,
    'namespace' => null,
    'uses'=>[],
    'filters' => [],
    'handle' => [
        'php-ns-name' => function ($e, $inf) {
            $inf->namespace = $e->value;
        },
        'php-use' => function ($e, $inf) {
            $inf->uses[] = $e->value;
        },
        'php-class-modifier' => function ($e, $inf) {
            $inf->modifier = $e->value;
        },
        'php-class-type' => function ($e, $inf) {
            $inf->type = $e->value;
        },
        'php-block' => function ($e, $inf) {
            $inf->src = $e->value;
            if ($fc = $inf->save){
                sort($inf->uses);
                $fc();
            } 
        },
        'php-identifier' => function ($e, $inf) {
            if ($inf->type)
                $inf->name = $e->value; 
        }
    ]
];
$inf->save = Closure::fromCallable(function() use ($inf){
    $inf = $this;
    $inf->name || igk_die('missing name');
    $t = implode(' ', array_filter([$inf->modifier, $inf->type, $inf->name])); 
    $s = sprintf(implode("\n", ['%s', $inf->src]), $t);
    if ($inf->uses){
        $s = implode("\n", $inf->uses)."\n\n".$s;
    }
    $script = new PHPScriptBuilder;
    $script->namespace($inf->namespace)
    ->type('function')
    ->defs($s);
    $file = Path::Combine($inf->outdir, $inf->name.".php");
    Logger::info('generate: '.$file);
    igk_io_w2file($file, $script->render()); 
    foreach (explode('|', 'name|type|modifier|src') as $k) {
        $inf->{$k} = null;
    }
})->bindTo($inf);
IO::GetFiles($dir, function ($f) use ($inf) {
    if (preg_match('/\.php$/', $f)) {
        $inf->file = $f;
        treat($inf);
        $inf->namespace = null;
        $inf->uses = [];
    }
}, true, $v_exclude);
function treat($inf)
{
    $src = file_get_contents($inf->file); # indication
    $regex = new RegexMatcherContainer;
    $comments[] = $regex->appendMultilineComment()->last();
    $comments[] = $regex->appendSingleLineComment()->last();
    $comments[] = $regex->match("#.*", "php-instruct")->last();
    $v_here_doc = [];
    RegexMatcherUtility::AppendPhpHereDoc($regex, $v_here_doc);
    $v_string = $regex->appendStringDetection('string', true)->last();
    $v_modifier = $regex->match('\\b(static|abstract|final)\\b', 'php-class-modifier')->last();
    $v_type = $regex->match('\\b(class|interface|trait)\\b', 'php-class-type')->last();
    $v_exclude = $regex->begin('\?>', '<\?(php|=)\\b', 'php-exclude')->last();
    $v_variable = $regex->match('\$[a-zA-Z_][a-zA-Z_0-9]*\\b', 'php-variable')->last();
    $v_namespace = $regex->begin('\\bnamespace\\b', '(?<=;|\})', 'php-nsblock')->last();
    $v_use = $regex->begin('\\buse\\b', '(?<=;)', 'php-use')->last();
    $v_identifier = $regex->match('\\b[a-zA-Z_][a-zA-Z_0-9]*', 'php-identifier')->last();
    $v_ns_identifier = $regex->createPattern([
        "match"=>"[a-zA-Z_][a-zA-Z_0-9]*(\\\\[a-zA-Z_][a-zA-Z_0-9]*)*",
        "tokenID"=>"php-ns-name"
    ]);
    $v_ns_block = $regex->createPattern([
        "begin"=>"\{",
        "end"=>"\}",
        "tokenID"=>"php-ns-block"
    ]);
    $v_namespace->patterns = [
        $comments,
        $v_ns_identifier,
        $v_ns_block,
    ];
    $v_block_def = $regex->begin('\{', '\}', 'php-block')->last();
    $v_block_sub_def = $regex->begin('\{', '\}', 'php-sub-block')->last();
    $v_ns_block->block = [
        $comments,
        $v_here_doc,
        $v_string,
        $v_modifier,
        $v_exclude,
        $v_identifier,
        $v_type,
        $v_variable,
        $v_block_def,
    ];
    $v_block_sub_def->patterns = [
        $comments,
        $v_here_doc,
        $v_string,
        $v_block_sub_def
    ];
    $v_block_def->patterns = [
        $comments,
        $v_here_doc,
        $v_string,
        $v_block_sub_def,
    ];
    $treat = new RegexMatcherContainerTreatment;
    $treat->listener = $inf;
    $treat->treat($src, $regex);
}