<?php
// @command: balafon --run .test/bviewParser/property_attributes.php
use IGK\System\ArrayMapKeyValue;
use IGK\System\Console\Logger;
use IGK\System\Html\HtmlActiveAttrib;
use IGK\System\Html\HtmlNodeTagExplosionDefinition;
use IGK\System\IO\Configuration\ConfigurationReader;
use IGK\System\Text\RegexMatcherContainer;
use function igk_html_host as _h;

$s = 'div.title%primal!disable#primary-definition(basic, litteral)[style:"padding-top:calc(80px + 1em)"]';
$d = _h($s, 'info');
$c = $d->render();
igk_wln_e($c);