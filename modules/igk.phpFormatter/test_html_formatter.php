<?php
// @command: balafon --run .test/modules/igk.phpFormatter/test_html_formatter.php [filter]
// @author: C.A.D. BONDJE DOUE
// @filename: test_html_formatter.php
// @date: 20250807 16:47:37
// @desc: just to test html formatter

use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use igk\phpFormatter\Formatters\FormatterPattern;
use igk\phpFormatter\Formatters\HtmlFormatter;
use igk\phpFormatter\Formatters\HtmlFormatterPattern;
use IGK\System\Console\Logger;
use IGK\System\IO\File\TmLanguage\Converters\RegexMatcherContainerTmLanguageConverter;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

$data = json_decode(file_get_contents( "/Volumes/Data/wwwroot/core/Packages/Modules/igk/phpFormatter/Lib/Tests/datas/check.json"));
 
$filter = igk_getv($params, 0);
function igk_php_formatter_format(string $src){
    $regex = new RegexMatcherContainer;        
    HtmlFormatter::InitFormatter($regex);    
    $engine = new HtmlFormatter;
    $s = $engine->exec($regex, $src, true); 
    return $s;
}

 foreach ($data as $n => $m) {
    if ($filter && !preg_match("/".$filter."/", $n)){
        continue;
    }
    $e = igk_php_formatter_format($m->src);
    if ($e != $m->expected){
        Logger::danger("error: ".$n);
        Logger::info('++ '.$e);
        Logger::info('-- '.$m->expected);
    } else{
        Logger::success('ok - '.$n);
    }
 }

 igk_exit();