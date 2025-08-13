<?php
// @author: C.A.D. BONDJE DOUE
// @filename: load_extented_option.php
// @date: 20241112 07:30:44
// @desc: load extended option check
// @command: balafon --run .test/regex/load_extented_option.php
//   "begin": "(?x)\n  (?:\n    \\# \\s* (type:)\n    \\s*+ (?# we want `\\s*+` which is possessive quantifier since\n             we do not actually want to backtrack when matching\n             whitespace here)\n    (?! $ | \\#)\n  )\n",
//         "end": "(?:$|(?=\\#))"
// $src = file_get_contents('/Users/charlesbondjedoue/Desktop/mark_2.json');
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
// igk_wln_e("data: ", $data, 'ddddd', json_last_error_msg());
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
// $c = "(?x)\n  (?:\n    \\# \\s* (type:)\n    \\s*+ (?# we want `\\s*+` which is possessive quantifier since\n             we do not actually want to backtrack when matching\n             whitespace here)\n    (?! $ | \\#)\n  )\n";
// // remove comment 
// $tr = igk_getv(RegexMatcherUtility::TreatByRemoveRootScopePattern($ctn, substr($c, 4)), 0);
$out = RegexMatcherUtility::TreatExtended($tr);
igk_wln_e($tr, $out);
$ctn->treat($c, function ($g) {});
// igk_wln_e("resolve", $ll, json_last_error_msg());
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
// igk_wln_e("data measure : ", $data, $error, $c);
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