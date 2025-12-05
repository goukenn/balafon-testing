<?php
// @command: balafon --run .test/bet/napoleon_extract_session.php
use IGK\Helper\StringUtility;
use IGK\System\Text\RegexMatcherContainer;
use IGK\Tests\BaseTestCase\ConnexionStringTest;
$src = '';
$form = igk_getv($command->options, '--from', 'firefox');
$data = "titre=La roue du temps; auteur=Charles;";
$data .= " editeur=Sample;";
$data = "marqueur=info;infx;" . $data;
echo $data, PHP_EOL;
$tab = igk_sys_cookies_read_value($data);
$tab['auteur'] = 'BONDJE';
igk_wln_e($tab);
// $g = StringUtility::ReadArgs($data, ';');
igk_exit();
igk_wln_e($data, $g);