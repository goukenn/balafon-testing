<?php
// @command: balafon --run .test/markdown/check-files.php
use igk\Markdown\Formatters\MarkdownParser;
use igk\Markdown\Markdown;
// $file ='/Volumes/Data/Dev/PHP/balafon_site_dev/Works/Balafon/Ai-Documentation/Balafon-Claude-AI/Chapitre_12_Syntaxe_BView_et_Module_Parser.md';
$file ='/Volumes/Data/Dev/PHP/balafon_site_dev/Works/Balafon/Ai-Documentation/Balafon-Claude-AI/check.md';
// $file ='/Volumes/Data/Dev/PHP/balafon_site_dev/Works/Balafon/Ai-Documentation/Balafon-Claude-AI/Chapitre_13_Injection_Dependances_Services.md';
$options = [
    'formatCodeBlock'=>true
];
$src = file_get_contents($file);
$l = substr($src, 40);
$c = igk_create_node('div');
$c->markdown($src, $options);
echo implode("\n",[
    "<html>",
    '<style>body{height:100%; height:100%;} *{ padding:0px; margin:0px; box-sizing: border-box; } .md-doc .string{color: #ff7373; } .md-doc{padding:4px; background-color:#eee;} span.preserve{white-space-collapse:preserve-spaces;}',
    ' div.line{display: flex; flex-direction:row; align-items: baseline; justify-content:start; }',
    '.no-selection{user-select: none;}',
    '.md-doc .operator{ color: gold;}',
    '.md-doc div.line > *{display:inline-block;}',
    '.md-doc .comment_docbloc{color: #325522;}',
    '.md-doc .func_call{ color: #1F9CF0; font-weight: bold;}',
    '.md-doc .begin_php_proc{ color: #1B7CBC;}',
    '.md-doc .rword{ color: #1B7CBC;}',
    '.md-doc .ns_litteral{ color: #4EC9B0;}',
    '.md-doc .single_comment{ color: #6A9955; font-style: italic;}',
    '.md-doc .code-box {font-family:monospace; font-size: 8pt; border: 1px solid #333; background-color: #222; color:white; border-radius: 4px;}',
    '.md-doc .code-box .line-gutter{  padding:4px 2px; width: 2em; background-color:red; color:white; margin-right: 4px;}',
    '</style>']);
echo '<body>'.PHP_EOL;
$c->renderAJX();
echo "</body></html>";
igk_exit(); 