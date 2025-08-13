<?php
// @command: balafon --run .test/regex/totmlanguage.php
use IGK\System\IO\File\TmLanguage\Converters\RegexMatcherContainerTmDefinition;
$c = new RegexMatcherContainerTmDefinition;
$c->{'$scope'} = 'domination';
igk_wln_e("basic ::::: ", json_encode($c));