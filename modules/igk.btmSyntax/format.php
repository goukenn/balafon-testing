<?php
// @author: C.A.D. BONDJE DOUE
// @filename: format.php
// @date: 20250716 14:22:44
// @desc: 
// @command: balafon --run .test/modules/igk.btmSyntax/format.php
// + | --------------------------------------------------------------------
// + | 
// + |

use igk\btmSyntax\Formatter;
use igk\btmSyntax\FormatterMatchInfo;
use igk\btmSyntax\IFormatterListener;
class ConsoleLogListener implements IFormatterListener{
    public function getTransform() { }
    public function getLineFeed(): ?string {
        return '';
     }
    public function handleToken(string $treated_data, string $source_data, ?string $tokenID = null, ?string $name = null, ?array $names = null, ?FormatterMatchInfo $matcher = null) { }
}
$def = json_decode(file_get_contents(__DIR__."/demo.btm-syntax.json"));
$v_formatter = Formatter::CreateFrom($def);
$src = ["bonjour tout le monde", "pour le meilleur. aurevoir!!!"];
$v_formatter->debug = true;
$v_formatter->listener = new ConsoleLogListener;
$r = $v_formatter->format($src);
igk_wln_e("resource: ", $r);