<?php
// @command: balafon --run .test/bviewParser/property_attributes.php

use IGK\System\ArrayMapKeyValue;
use IGK\System\Console\Logger;
use IGK\System\Html\HtmlActiveAttrib;
use IGK\System\Html\HtmlNodeTagExplosionDefinition;
use IGK\System\IO\Configuration\ConfigurationReader;
use IGK\System\Text\RegexMatcherContainer;

use function igk_html_host as _h;

// $d = _h('div.title[style:"background-color:red;padding:8px;"]', 'info');
// $c = $d->render();
$s = 'div.title%primal!disable#primary-definition(basic, litteral)[style:"padding-top:calc(80px + 1em)"]';
// $definition = HtmlNodeTagExplosionDefinition::ExplodeTag2($s,null);


// print_r($definition);

// Logger::print('finish');

// igk_exit();


$d = _h($s, 'info');
$c = $d->render();


igk_wln_e($c);
