<?php
use function igk_resources_gets as __;
use IGK\Helper\ActionHelper;
use IGK\Helper\ViewHelper;
use IGK\System\Html\Css\CssSession;
use IGK\System\Http\Request;
use IGK\System\Http\WebFileResponse;
use WinUI\Layout;
$___IGK_PHP_SETTER_VAR___['pwa'] = $pwa = null;
CssSession::getInstance()->setTheme("indigo");
igk_reg_hook(IGKEvents::HOOK_HTML_META, function(){
    echo "<meta name='igk-app-version' content='igkdev3.0' />";
});
$___IGK_PHP_SETTER_VAR___['doc']->body["class"] = "igk-body";
$___IGK_PHP_SETTER_VAR___['doc']->head->add("link")->setAttributes([
"rel"=>"canonical", "href"=>$___IGK_PHP_GETTER_VAR___['ctrl']->getAppUri()
]);
// + |--------------------------------------------------------
// + | SEO set canonical
// + |
igk_reg_hook(IGKEvents::HOOK_HTML_META, function(){
    echo "<meta name='igk-app-version' content='igkdev2.0' />";
});
$___IGK_PHP_SETTER_VAR___['favicon'] = $favicon = $___IGK_PHP_GETTER_VAR___[igk_express_eval('$ctrl->getResourcesDir() . "/Img/favicon.ico"')];
return;