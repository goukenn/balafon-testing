<?php
// @command : balafon --run .test/html/inline-indent.php
use IGK\System\Console\Colorize;
use IGK\System\Console\Html\HtmlColorizer;
use IGK\System\Console\Logger as ConsoleLogger;
use IGK\System\Html\Dom\HtmlDoctype;
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Text\RegexMatcherContainer;
use Illuminate\Log\Logger;
$doc = igk_create_xmlnode('html');
$head = $doc->head();
$head->title()->setContent('Front-End project');
$meta = new HtmlNode('meta');
$meta->setAttributes(['name' => 'viewport', 'content' => "width=device-width, initial-scale=1.0"]);
$head->add($meta);
$body = $doc->body()->setAttributes([
    'class' => 'vite-app-container'
]);
$app = $body->div()->setAttributes(['id' => ('app')]);
    $app->comment('app-content');
$script = new HtmlNode('script');
$script->activate('crossorigin')
    ->setAttributes(['type' => 'module', 'src' => '']);
$body->add($script);
ConsoleLogger::SetColorizer(new HtmlColorizer);
$l = $doc->render((object)['Indent'=>true]);
ConsoleLogger::print($l);
igk_exit();