<?php
// @command: balafon --run .test/system/text/regexcontainer/load_tm_language.json.php
use IGK\System\IO\File\TmLanguage\Helper\TmLanguageUtility;
use IGK\System\Text\IRegexCaptureInfo;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

$file = __DIR__ . '/demo.tm-language.json';
$container = TmLanguageUtility::CreateRegexMatcherContainerFromFile($file);
$src = implode("\n", [
    'background-color" durand le : jour ": red;'
]);
$out = '';
$container->treat($src, function (IRegexCaptureInfo $g, int $next_position, ?string $data)use(& $out) {
    if (!$g->getisRootCaptured()){
        $n = igk_create_node("div");
        $n["class"] = 'property';
        $n->text($g->value);
        return $n->render();
    } else {
        $out.= $g->value;
    }
});
$out.= substr($src,
$container->getLastPosition()
);
igk_wln_e("done:", $out);