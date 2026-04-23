<?php
// @command: balafon --run .test/core/phpdoc/readmulti-property.php
use IGK\System\Annotations\PhpDocBlocReader;

$p = <<<'PHP'
/**
 * @function jumping
 * @author C.A.D. BONDJE DOUE
 * @property string $x
 * @property string $y
 * @method string job()
 * @method string joba()
 * @security(Auth)
 * */
PHP;
$reader = new PhpDocBlocReader;
$c = $reader->readDoc($p,[],null);
igk_wln_e($c->render());