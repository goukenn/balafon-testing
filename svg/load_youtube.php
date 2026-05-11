<?php

// @command: balafon --run .test/svg/load_youtube.php
use IGK\System\Html\XML\XmlNode;
$doc = igk_app()->getDoc();

igk_svg_bind_svgs($doc);


$doc->renderAJX();







igk_wln_e('done'); 