<?php
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;

/**
* auto generate doc.
* @package igk\bootstrap\Components
*/
abstract class ComponentBase extends HtmlNode{
    /**
    * auto generate doc.
    * @var mixed
    */
    protected $tagname = "div";
    /**
    * auto generate doc.
    * @param string $id
    */
    public function setParentTarget(string $id){
        return $this->setAttribute("data-bs-parent", $id);
    }
}