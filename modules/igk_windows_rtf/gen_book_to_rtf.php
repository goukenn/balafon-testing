<?php
// @author: C.A.D. BONDJE DOUE
// @filename: gen_book_to_rtf.php
// @date: 20260129 13:53:47
// @desc: convert markdown in folder to book.rtf
// @command: balafon --run .test/modules/igk_windows_rtf/gen_book_to_rtf.php location
use IGK\Helper\Activator;
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Markdown\MarkdownConverter;
use IGK\System\IO\Path;
use IGK\System\Number;
use IGK\System\Text\RegexMatcherUtility;
use igk\Windows\Rtf\Markdown\IRtfFontStyleDefinition;
use igk\Windows\Rtf\RtfDocument;
use igk\Windows\Rtf\RtfEntryPage;
use igk\Windows\Rtf\RtfFonts;
use igk\Windows\Rtf\Markdown\RtfMarkdowFilterHost;
use igk\Windows\Rtf\Markdown\RtfFromMarkdownListener;
use igk\Windows\Rtf\RtfBulletNFCTypes;
use igk\Windows\Rtf\RtfConstants;
use igk\Windows\Rtf\RtfUtility;

$location = igk_getv($params, 0);
$book_title = igk_getv($params, 1) ?? 'BALAFON';
/**
 * bullet reference info
 * @package 
 * @property ?string $root 
 * @property ?string $level level of the bullet start a 0 for the first bullet
 * @property ?string $path
 */
interface IRtfBulletReference {}
/**
* auto generate doc.
* @param string $s
* @param mixed $separator
* @return IRtfBulletReference
*/
function getBulletRefererence(string $s, $separator = RtfConstants::BulletSeparator)
{
    return RtfUtility::BulletPlaceHolderInfo($s, $separator);
}
/**
* auto generate doc.
*/
$doc = new RtfDocument();
$doc->fonts = [
    RtfFonts::Calibri,
    RtfFonts::Helvetica,
    RtfFonts::Arial,
    RtfFonts::CourierNew,
    RtfFonts::Consolas,
    RtfFonts::Menlo,
];
const FENCE_FCOLOR = 8;
$doc->colors = [
    "#222",
    "#040816",
    "#1F497D",
    "#1B7CBC",
    "#aaa", 
    "#EEE", 
    "#00f",
    "#3344DD", 
    FENCE_FCOLOR => "#444", 
    "#FF0",
    "#555", 
    "#999", 
];
$doc->titleFontSizes = [
    1 => 18,
    2 => 16,
    3 => 14,
    4 => 12,
    5 => 10,
    6 => 8,
];
$doc->titleFonts = [
    1 => 2,
    2 => 1
];
$doc->setStyleSheet([
    1=>"\\outlinelevel0",
    2=>"\\outlinelevel1",
    3=>"\\outlinelevel2",
    4=>"\\outlinelevel4",
]);
$doc->setTitleFontStyle([
    1=>"\\sb480\\fs64\\cf4\\sa480",
    2=>"\\sb480\\fs54\\cf4",    
    3=>"\\sb480\\fs48\\cf11",
    4=>"\\sb480\\fs40\\cf11",
    5=>"\\sb480\\fs32\\cf4",
    6=>"\\sb480\\fs24\\cf4",
]);
$doc->lang = "\\lang1036";
$doc->setProperties([
    'footer-font-size'=>'\\fs16',
    'header-font-size'=>'\\fs16'
]);
$g = new MarkdownConverter;
$container = new RtfMarkdowFilterHost($doc);
$listener = new RtfFromMarkdownListener($container);
$listener->quoteFontIndex = 1;
$listener->quoteColorIndex = 4;
$listener->setStyle('inline-code', Activator::CreateNewInstance(
    IRtfFontStyleDefinition::class,
    ['fontFamily' => 4, 'foreColor' => 4, 'bgColor' => 6]
));
$listener->setStyle('link', Activator::CreateNewInstance(
    IRtfFontStyleDefinition::class,
    ["u" => 1, 'foreColor' => 7]
));
$listener->setStyle('fence-code', Activator::CreateNewInstance(
    IRtfFontStyleDefinition::class,
    ['fontFamily' => 5, 'paragrahBgColor' => 6, 'fontSize' => 8]
));
$listener->emptyOutputListener = function () use ($g) {
    return empty($g->getOutput());
};
$g->setOutputTreatmentListener($listener);
$outfile = Path::Combine($location, 'book.rtf');
$gard = new RtfEntryPage();
$summary = new RtfEntryPage();
$summary->line("Table of Content");
$doc->append($gard);
$files = [];
$location = igk_str_rm_last($location, "/", 1);
$ln = strlen($location) ;
IO::GetFiles($location, function ($a) use (&$files, $location, $ln) {
    if (preg_match("/\/(chapter|chapitre)_.+\.md$/i", $a)) {
        $k = strtolower(substr($a, $ln + 1));
        $files[$k] = $a;
        return true;
    }
    return false;
}, true);
uksort($files, function ($a, $b) {
    return strnatcmp($a, $b);
});
$page = false;
$props = implode(array_filter([$doc->lang, $doc->{'header-font-size'}, $doc->{'footer-font-size'}]));
foreach ($files as $k => $f) {
    $src = file_get_contents($f);
    $src = str_replace('â"'."\n",'â"'."\n", $src);
    $g->transform($src, null, null);
    $o = $g->getOutput();
    if ($page){
        $doc->section();
    }
    $title = sprintf('%s - %s', $book_title , $k );
    $doc->setHeader(sprintf('\\pard\\tx9360%s{\\ql %s}\\tab{\\qr\\chpgn}', $props, $title));
    $doc->setFooter(sprintf('\\pard\\qc%s \\chpgn', $props));
    $doc->bookmark($k, '');
    $doc->appendItem($o);
    $page = true;
}
echo $doc->render() . PHP_EOL;
$doc->save($outfile);
Logger::success('complete ' . $outfile);
igk_exit();