<?php
// @author: C.A.D. BONDJE DOUE
// @filename: load_extented_option.php
// @date: 20241112 07:30:44
// @desc: load extended option check
// @command: balafon --run .test/regex/load_extented_option.php
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

$src = <<<'JSON'
{
    "patterns": [
        {
            "begin": "(?x)\n  (?:\n    \\# \\s* (type:)\n    \\s*+ (?# we want `\\s*+` which is possessive quantifier since\n             we do not actually want to backtrack when matching\n             whitespace here)\n    (?! $ | \\#)\n  )\n",
            "end": "(?:$|(?=\\#))"
        }
    ]
}
JSON;
$data = json_decode($src);
$ll = json_decode(<<<'JSON'
{
    "begin": "(?x)\n  (?:\n    \\# \\s* (type:)\n    \\s*+ (?# we want `\\s*+` which is possessive quantifier since\n             we do not actually want to backtrack when matching\n             whitespace here)\n    (?! $ | \\#)\n  )\n"
        
}
JSON);
$ctn = new RegexMatcherContainer;
$l = $ctn->begin("(?x)\n    # litteral\n    ia", '\)', 'litteral')->last();
$l->patterns = [
    $l
];
$ctn->treat("ia) info", function($g){
    igk_wln_e("the base handle : ".$g->tokenID, $g->value);
});
$out = RegexMatcherUtility::TreatExtended($tr);
igk_wln_e($tr, $out);
$ctn->treat($c, function ($g) {});
$data = json_decode(<<<JSON
{
    "patterns":[
        {
            "begin": "{$c}",
            "end": "(?:$|(?=\\\\#))"
        }
    ]
}
JSON);
$error = json_last_error_msg();
igk_wln_e("treat extended: ", RegexMatcherUtility::TreatExtended(substr($c, 4)));
$js = <<<JS

let lit = inf.s.split("\n");
let _gt = [];
lit.forEach(i=>{
    i = i.trimStart();
    if (/^#/.test(i)) return;
    i = i.replace(/^\| /, "|");
    _gt.push(i);
    //
})
_s = _gt.join('');

JS;