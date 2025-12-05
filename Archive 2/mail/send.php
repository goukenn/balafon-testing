<?php
// @command: balafon --run .test/mail/send.php
use IGK\Controllers\LayoutParam;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
use IGK\System\Net\Mail;
use IGK\System\Uri;
class MailRendererEngine
{
    public function render($n)
    {
        $tagname = $n->getTagName();
        if (strtolower($tagname) ==  'svg') {
            $url =  base64_encode(
                implode("", [
                    $n->render() // '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" fill="red"><rect width="100" height="100"></rect></svg>'
                ])
            );
            $img = IGKGD::Create(32, 32);
            ob_start();
            $red = $img->CreateColorRGB(255, 30, 30);
            $img->rect(0, 0, 16, 16, $red);
            $img->render();
            $sc = ob_get_contents();
            ob_end_clean();
            $l = base64_encode($sc);
            // return '<img src="data:image/png;base64,'.htmlentities($l).'" alt="directory"/>';
            $src = 'data:image/svg+xml;base64,' . $url;
            //return '<img src="' . $src . '" />';
            // '                 https://igkdev.com/mail-previewer/mail/decode?d'
            return '<img alt="res" src="https://igkdev.com/mail-previewer/mail/decode/file.svg?d=' . htmlentities($url) . '" />';
            // return '<img src="'.htmlentities('https://igkdev.com/mail-previewer/mail/decode/favicon.ico?d='.htmlentities($url)).'" />';
        }
    }
}
list($to, $subject, $msg) = igk_extract($params, '0|1|2');
$account_name = igk_getv($command->options, '--account-name');
$ctrl = $ctrl ?? igk_getv($command->options, '--controller');
$view = igk_getv($command->options, '--view', 'default');
$from = igk_configs()->mail_user;
$form = Mail::MailFromArrayToString(['title' => $account_name, 'mail' => $from]);
if (empty($msg)) { 
    $g = new Uri('bcl://send-mail.local/' . $view);
    $_SERVER['REQUEST_URI'] = $g->getRequestUri();
    $_SERVER['QUERY_STRING'] = $g->getQuery();
    $ctrl->getConfigs()->no_auto_cache_view = true;
    $ctrl->layoutParam = new LayoutParam;
    $ctrl->layoutParam->viewSingleView = true;
    $ctrl->layoutParam->defaultStyle = IGKConstants::DEFAULT_THEME_STYLE;
    igk_server()->prepareServerInfo();
    $ctrl->cssBindStyle($ctrl->getDoc(), $ctrl->layoutParam->defaultStyle);
    $ctrl->setCurrentView($view);
    $t = $ctrl->getTargetNode();
    $t["class"] = "+mail-view";
    $mail_options = igk_mail_option();
    $mail_options->Engine = new MailRendererEngine;
    $msg = $t->render($mail_options);
} 
igk_wln($msg); 
$m = Mail::Mail($to, $subject, $msg, "{$account_name} <{$from}>");
Logger::SetColorizer(new Colorize);
Logger::print(json_encode(["from" => igk_configs()->mail_user, "to" => $to], JSON_PRETTY_PRINT));
Logger::print("send mail: " . $m);
igk_exit();