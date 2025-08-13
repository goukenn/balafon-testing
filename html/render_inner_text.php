<?php
// @command: balafon --run .test/html/render_inner_text.php
use IGK\System\Html\HtmlRenderer;
$n = igk_create_node('div');
$n->span('info');
$n->h1()->Content = " Local";
$n->h2()->Content = " Deparatement ";
$n->text('VAR de salle');
$s = HtmlRenderer::GetInneText($n);
igk_wln_e($s);