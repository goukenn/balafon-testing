<?php
// @command: balafon --run .test/css/inline-inclusion.php
use IGK\System\Html\Dom\HtmlDocTheme;
$theme = new HtmlDocTheme(null, 'temp',false);
$theme['.vspace-2'] = 'padding-top:2em; padding-bottom:2em;';
$theme['.info'] = '(:.vspace-2) background-color:red;';
igk_wln($theme->get_css_def(true, true));
igk_exit();