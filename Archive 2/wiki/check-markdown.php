<?php

// @command: balafon --run .test/wiki/check-markdown.php
$f = "/Volumes/Data/Projects/Balafon/Ai-Documentation/claude/treat/wiki_theme_and_styles/Wiki_Balafon_Themes_et_Styles.md";
$f = __DIR__.'/data/one.md';
$t = igk_create_node();
$src = file_get_contents(
    $f
    );
$md = $t->div()->markdown($src, 
['allowLinkDocument'=>true]);

$md->renderAJX();
igk_exit();