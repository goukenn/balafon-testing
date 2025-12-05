<?php
// @author: C.A.D. BONDJE DOUE
// @filename: main.php
// @date: 20240912 08:25:23
// @desc: pcss style builder 
// @command: balafon --run .test/tools/stylebuilder/main.php
// first parse core library to get all matching class
use IGK\System\Console\App;
use IGK\System\Console\Logger;
use IGK\System\Exceptions\CssParserException;
use IGK\System\Exceptions\ArgumentTypeNotValidException;
use IGK\System\Html\Css\CssClassNameDetector;
use IGK\System\Html\Css\CssKeyFrame;
use IGK\System\Html\Css\CssMedia;
use IGK\System\Html\Css\CssParser;
use IGK\System\Html\Css\CssUtils;
use IGK\System\IO\StringBuilder;
/**
 * detect and view 
 * @package 
 */
// + | load files 
//$content = file_get_contents(__DIR__.'/core.css');
// $content = file_get_contents(__DIR__ . '/job.css');
$content = file_get_contents(__DIR__ . '/frames.css');
$parser = CssParser::Parse($content);
$detector = new CssClassNameDetector;
$b = $detector->map($parser->to_array());
// igk_wln("result: ", json_encode($b, JSON_PRETTY_PRINT));
// ob_start();
// igk_wln("maped : ", json_encode($detector->getReferencedByIndex(105)));
// $c = ob_get_contents();
// $c = preg_replace_callback("/('|\")[^\\1]*\\1/", function ($m) {
//     return App::Gets(App::GREEN, $m[0]);
// }, $c);
// ob_end_clean();
// $ref = $detector->getReference('.number');
// Logger::info('outside');
Logger::info('regex definition');
Logger::print($detector->getMatchRegex());
$src = "basic fit-w fith igk-col-xsm-9-4 fit-w fitb";
$tp = [$detector::MEDIA_KEY => ['max-height:400px' => ['.fit-o' => ['width' => 120]]]];
$tp = [];
$p = $detector->resolv($src, $tp);
$p = $detector->resolv('body info', $tp);
$p = $detector->resolv('<div className="no-card"></div>', $tp);
// preg_match("/bon(?!a)/",  'bona');
if ($p) {
    print_r($p);
    $l = $detector->renderToCss($p, (object)['lf' => '']);
    Logger::print($l);
}
Logger::success('done');
igk_exit();