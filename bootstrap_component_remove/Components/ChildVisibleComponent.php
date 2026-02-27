<?php
namespace igk\bootstrap\Components;

/**
* auto generate doc.
* @package igk\bootstrap\Components
*/
class ChildVisibleComponent extends ComponentBase
{

    /**
    * auto generate doc.
    */
    public function getIsVisible()
    {
        return ($this->getChildCount() > 0) || !empty($this->getContent());
    }
}