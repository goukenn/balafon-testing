<?php
// @author: C.A.D. BONDJE DOUE
// @file: Nav.php
// @date: 20220116 06:35:06
namespace igk\bootstrap\Components;
use igk\bootstrap\Utils;
class NavBar extends ComponentBase{
    protected $tagname = "nav";
    protected function initialize()
    {
        $this["class"] = "navbar";
    }
    public static function Create(string $brandTitle, ?array $items = null){
        $n = new self();
        $dv = $n->add("div");
        $dv["class"] = "container-fluid";
        $dv->a("#")->setContent($brandTitle)->setClass("navbar-brand");
        $dv->add(Utils::CreateNavbarTogglerButton());
        //igk_html_build_menu($dv, $items, null, null, "nav");
        return $n;
    }
}