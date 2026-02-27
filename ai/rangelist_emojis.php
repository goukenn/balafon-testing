<?php
// @command: balafon --run .test/ai/rangelist_emojis.php

use IGK\System\Text\UnicodeUtility;

// echo "\u{1F1EF} \u{1F1F5}";
echo mb_ord('🇰🇷', 'UTF-8');
echo "\n";
echo "=".sprintf("\x%s", dechex(127472));//, 'UTF-8');
echo "\n";

// echo "R= \xF0\x9F\x98\x80";//, 'UTF-8');
// echo "\n";
$k = UnicodeUtility::UnicodeToUtf8Bytes(0x1F1F0);
$r = UnicodeUtility::UnicodeToUtf8Bytes(0x1F1F7);
echo " ??? ". $k['utf8'].$r['utf8'];
echo "\n";

echo UnicodeUtility::EmojisFlag('CM');
echo UnicodeUtility::EmojisFlag('FR');
echo UnicodeUtility::EmojisFlag('BE');
echo UnicodeUtility::EmojisFlag('ZM');
echo UnicodeUtility::EmojisFlag('KI');
// echo "~ \xF0\x9F\x87\xB0\xF0\x9F\x87\xB7 ~";
// echo "---";
// echo " after: ";
// echo '🇰🇷';
echo "\n";
$faces = [
    'happy'   => "\u{1F600}",
    'love'    => "\u{1F60D}",
    'cool'    => "\u{1F60E}",
    'think'   => "\u{1F914}",
    'cry'     => "\u{1F62D}",
    'angry'   => "\u{1F621}",
    'robot'   => "\u{1F916}",
    'skull'   => "\u{1F480}",
];

foreach ($faces as $name => $emoji) {
    echo sprintf("%-8s -> %s\n", $name, $emoji), PHP_EOL;
}


exit;

$c = 56800;
$i = 0;
$f = "\\uc0\\u55356 \\u56808 \\u55356 \\u%s \\\n";
$f = "[56814, %s]\\uc0\\u55356 \\u56814 \\u55356 \\u%s \\\n";
$l = [];
foreach(range(1, 100) as $k){
    $i = $c + $k;
    $l[] = sprintf($f, $i, $i);  
    $g = hexdec('1F600');
    echo "\u{1F601}", PHP_EOL;
}
//echo implode("", $l);
exit;