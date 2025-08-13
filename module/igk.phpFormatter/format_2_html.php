<?php
// @author: C.A.D. BONDJE DOUE
// @filename: format_2_html.php
// @date: 20250731 10:57:27
// @desc: show usage of global html formatter
// @command: balafon --run .test/module/igk.phpFormatter/format_2_html.php

use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\Helper\StringUtility;
use igk\phpFormatter\Formatters\CodeFormatterFormatterEngineInfo;
use igk\phpFormatter\Formatters\CodeToHtmlFormatter as FormattersCodeToHtmlFormatter;
use igk\phpFormatter\IPHPFormatterModule;
use IGK\System\Console\Logger;
use IGK\System\Exceptions\ArgumentTypeNotValidException;
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Html\HtmlRenderer;
use IGK\System\Text\Formatters\FormatterBase;
use IGK\System\Text\Formatters\FormatterPattern;
use IGK\System\Text\Formatters\Traits\FormatterChainTransformTrait;
use IGK\System\Text\Formatters\Traits\FormatterSplitterTrait;
use IGK\System\Text\Formatters\Traits\FormatterTreatChainLogicTrait;
use IGK\System\Text\IReplaceCapturedFormatDefinition;
use IGK\System\Text\RegexMatcherCapture;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherPattern;

use function igk_html_host as _h; 

class CodeToHtmlFormatter extends FormattersCodeToHtmlFormatter
{
}

$regex = new RegexMatcherContainer;
$regex->patternCreatorClass = FormatterPattern::class; 
// include __DIR__.'/format_2_html.regex.definition.pinc';

// Logger::info('export - source');
// $c = $regex->export('source.php');
// echo JSon::Encode($c, JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT);
// exit;
/**
 * @var ?IPHPFormatterModule
 */
$mod = igk_require_module('igk/phpFormatter');

$regex = $mod->getFormatRegexContainer('source.php');


$engine = new CodeToHtmlFormatter();
$engine->autoFormat = true;
$engine->showLine = true;
$engine->baseLanguage = 'php';
$engine->lineSplitter = '😒';
$engine->viewLine = true;
$regex->setEngineInfo(new CodeFormatterFormatterEngineInfo($engine) );

$src = 'hello';
$transform = $engine->exec($regex, $src, true); 
igk_wln($transform);
igk_exit();

echo "<!DOCTYPE html>";
// alternative to convert bmstring - 
// echo mb_convert_encoding(''.
echo _h(
    'html',
    _h(
        'head',
        _h('meta')->setAttributes(['charset' => 'utf8']) // + important indicate to browser to use utf8-encoding
    ),
    _h('body', igk_ob_get_func(function () use ($transform, $file) {
        $n = new HtmlNode('code');
        $n->text($transform); 
        echo _h('nav.head', 'for : '.$file);
        echo _h('main.source', $n), PHP_EOL;
        echo _h('script', <<<'JS'

JS);
        echo _h('style', <<<CSS
body{
    background-color:#040819;
    color:white;
}
.number{
    color:yellow;
}
.brank{
    color: skyblue;
}
.string{
    color: orangered;
}
.cond{
    background-color: transparent;
}
.line{
    background-color: #28082b;
    white-space: nowrap;
    padding-left:0px;
    position:relative;
}
.line .line-gutter{     
    display:inline-block;   
    position:sticky;
    border: none;
    width: 2.6em;
    height: 100%;
    left: 0px;
    top:0px;
    background-color: indigo;
    text-align: right;
    padding-right: 4px;
    margin-right: 8px;
    z-index:10;
}

.code-tab, code{
    font-family: monospace, courier, sans-serif;
}
.comment, .single_comment,.directive_comment,.comment_docbloc{
    color: green;
}
code{
    display: flex; 
    flex-direction: column;
    width: max-content;
    min-width:100%;
}
code span{
    display: inline-block;
    vertical-align:bottom;
    white-space: preserve-spaces nowrap;
}
code *{
    vertical-align:bottom;
}   
span.reserved_words{
    color: indianred;
}
span.rp{
    color: indianred;
}
span.reserved_type{
    color: #9CDCFE;
} 
span.f_curl_brace, span.curl_brace, .curl_start, .curl_end{
    color:#D228E0;
}
span.directive{
    color:#ff6c00;
}
span.func_call{
    color:#ff30a5;
}
span:focus{
    background-color:red;
    outline:none;
}
span.arg_interpolation{
    color:red;
}
.endinstruct{
    color:var(--f-cl-endinstruct);
}
.tilt_string{
    color: orange;
}
:root{
    --f-cl-endinstruct:#eee;
    --f-cl-operator:#ac869b;
    --f-cl-php-var:#1F9CF0;
    --f-cl-type-declare-curl: #6197a8;
    --f-cl-attrib-color:#1F9CF0; 
    --f-cl-string: #B1026C;
    --f-cl-here-doc: #db4336;
}
.operator{ 
    color:var(--f-cl-operator);
}
.phpvar{
    color:var(--f-cl-php-var);
}
.type_declaration{
    color:var(--f-cl-type-declare-curl);
}
.html_close_tag{
    color:var(--f-cl-string);
}
.tag{
    color: var(--f-cl-string);
}
.attrib{
    color: var(--f-cl-attrib-color);
}
.html_inner{
    color: #eee;
}
main.source{
    overflow-x: auto;
}
.here_doc{
    color: var(--f-cl-here-doc);
}
CSS);
    }))
)->setAttributes([
    'charset' => 'UTF8',
    'locale' => 'en'
])

    // ,
    // 'UTF8',
    // 'UTF8',
    //  )
;

Logger::success('done');
igk_exit();
