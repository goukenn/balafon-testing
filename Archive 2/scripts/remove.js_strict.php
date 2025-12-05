<?php
use IGK\System\Regex\Replacement;
$s = <<<'JS'
"use strict";
console.log("info")
JS;
$rp = new Replacement;
$rp->add('/(\'|")use\\s+strict(s)?\\s*(\\1)(;)?(\\s+)?/', '');
$s = $rp->replace($s);
igk_wln('first: ', $s);
$s = <<<'JS'
'use strict';
console.log("seconde")
JS;
$s = $rp->replace($s);
igk_wln('seconde : ', $s);
igk_wln_e("done");