<?php
// @author: C.A.D. BONDJE DOUE
// @file: Nav.php
// @date: 20220116 06:35:06
namespace igk\bootstrap\Components;
use igk\bootstrap\Utils;

/**
* auto generate doc.
* @package igk\bootstrap\Components
*/
class NavBar extends ComponentBase{
    /**
    * auto generate doc.
    * @var mixed
    */
    protected $tagname = "nav";
    /**
    * auto generate doc.
    */
    protected function initialize()
    {
        $this["class"] = "navbar";
    }
    /**
    * auto generate doc.
    * @param string $brandTitle
    * @param null|array $items
    */
    public static function Create(string $brandTitle, ?array $items = null){
        $n = new self();
        $dv = $n->add("div");
        $dv["class"] = "container-fluid";
        $dv->a("#")->setContent($brandTitle)->setClass("navbar-brand");
        $dv->add(Utils::CreateNavbarTogglerButton());
        return $n;
    }
}