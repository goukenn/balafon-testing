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

/**
* auto generate doc.
*/
class ConsoleLogListener implements IFormatterListener{
    /**
    * auto generate doc.
    */
    public function getTransform() { }
    /**
    * auto generate doc.
    * @return ?string
    */
    public function getLineFeed(): ?string {
        return '';
     }
    /**
    * auto generate doc.
    * @param string $treated_data
    * @param string $source_data
    * @param null|string $tokenID
    * @param null|string $name
    * @param null|array $names
    * @param null|FormatterMatchInfo $matcher
    */
    public function handleToken(string $treated_data, string $source_data, ?string $tokenID = null, ?string $name = null, ?array $names = null, ?FormatterMatchInfo $matcher = null) { }
}
$def = json_decode(file_get_contents(__DIR__."/demo.btm-syntax.json"));
$v_formatter = Formatter::CreateFrom($def);
$src = ["bonjour tout le monde", "pour le meilleur. aurevoir!!!"];
$v_formatter->debug = true;
$v_formatter->listener = new ConsoleLogListener;
$r = $v_formatter->format($src);
igk_wln_e("resource: ", $r);