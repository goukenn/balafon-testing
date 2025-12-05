<?php
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;
abstract class ComponentBase extends HtmlNode{
    protected $tagname = "div";
    public function setParentTarget(string $id){
        return $this->setAttribute("data-bs-parent", $id);
    }
}