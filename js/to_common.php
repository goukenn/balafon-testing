<?php
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherPattern;
$container = new RegexMatcherContainer;
$empty_line = new RegexMatcherPattern($container);
$empty_line->match = "^\\s*$";
$empty_line->tokenID = 'empty-line';
// + | ----------------------------
// if tick variable data definition 
// + 
$c = new RegexMatcherPattern($container);
$c->begin = "(`)";
$c->end = "`";
$c->tokenID = 'string.tic.litteral';
$c->patterns = [(object)[
    "match"=>"\\\\."
]]; 
$container->append($c);
$c = new RegexMatcherPattern($container);
$c->begin = "<<<(\\w+)\\b";
$c->end = "^\\1\\b";
$c->tokenID = 'string.ofd.litteral';
$c->patterns = [(object)[
    "match"=>"\\\\."
]]; 
$container->append($c);
$c = new RegexMatcherPattern($container);
$c->begin = "('|\")";
$c->end = "\\1";
$c->tokenID = 'string.litteral';
$c->patterns = [(object)[
    "match"=>"\\\\."
]];
$b = new RegexMatcherPattern($container);
$b->begin = '\\{';
$b->end = '\\}';
$container->loadRepository([
    'string-litteral' => $c,
    'block-litteral' => $b,
    'empty-line' =>$empty_line
]);
$container->append($empty_line);
$container->append($c);
$container->begin('\\(', '\\)');
$container->begin('\\{', '\\}');
$g = $container->begin('\\bexport\\b', '$', 'export-detected')->last();
$g->patterns = [
    (object)["match" => "\\b(default|const|function)\\b", "tokenID"=>"export.function"],
    (object)["include" => "#string-litteral"],
    (object)["include" => "#empty-line"],
    (object)[
        "begin" => "\\{",
        "end" => "\\}",
        "name"=> "li.block",
        "tokenID"=>"cblock",
        "patterns" => [
            (object)["include" => "#string-litteral"],
            (object)["include" => "#block-litteral"],
        ]
    ], 
    (object)[
        "begin" => "\\(",
        "end" => "\\)"
    ]
];
$src = file_get_contents($params[0]);
$o = '';
$offset = 0;
$boffset = 0;
$clf = null;
while ($g = $container->detect($src, $offset)) {
    if ($e = $container->end($g, $src, $offset)){ 
        Logger::info('end:'.$e->tokenID);
        if (is_null($e->parentInfo)){
            switch ($e->tokenID)
            {
                case 'export-detected':
                case 'empty-line':
                    $v_emptyline = ($e->tokenID == 'empty-line');
                    $lf = $v_emptyline ? $clf: null;
                    $o .= rtrim($lf.substr($src, $boffset, $e->from-$boffset));
                    $boffset = $e->to;
                    if ($v_emptyline)
                        $clf = "\n" ;
                break;
            }
        }
    }
}
$o .= $clf.substr($src, $boffset);
igk_wln("output : ", $o);
igk_exit();