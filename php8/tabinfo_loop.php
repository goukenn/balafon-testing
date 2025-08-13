<?php
use IGK\System\Configuration\Controllers\ControllerAndArticlesController;
use IGK\System\Html\Dom\HtmlComponents;
ini_set('display_error', 1);
error_reporting(-1);
$ctrl = ControllerAndArticlesController::ctrl();
// if (!($c = $this->SelectedController))
//             return;
$t = igk_create_node('div');
$v_dv = $t->div();
        $txb = $v_dv->addCol("igk-col-3-3")->addColViewBox()->addComponent(
            $ctrl,
            HtmlComponents::AJXTabControl,
            "view_result",
            1
        );
$suri = igk_register_temp_uri(get_class()) . "/controller";
$ctab = [
    "Info" => (object)[
        "uri" => $suri . "/infotab",
        "tab" => "infotab"
    ],
    "View" => (object)["uri" => $suri . "/views", "tab" => "views"],
    "Articles" => (object)["uri" => $suri . "/articles", "tab" => "articles"]
];
!empty($vtab = $ctrl->getParam("tab:editresult")) || ($vtab = "infotab");
foreach ($ctab as $k => $v) {
    $txb->addTabPage($k, $v->uri, $vtab == $v->tab);
}
igk_wln_e($t->render());