<?php

// @command: balafon --run .test/svg/load_youtube.php
use IGK\System\Html\XML\XmlNode;
$doc = igk_app()->getDoc();

igk_svg_bind_svgs($doc);


$doc->renderAJX();


// $src = file_get_contents($file = '/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Data/R/svg/icons/youtube.svg');
// $n = new XmlNode('x'); // igk_create_node('notagnode');
// $n->load("<list-item>".$src."</list-item>");


igk_wln_e('done'); // $n->render());