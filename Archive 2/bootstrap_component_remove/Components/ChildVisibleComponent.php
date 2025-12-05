<?php
namespace igk\bootstrap\Components;
class ChildVisibleComponent extends ComponentBase
{
    public function getIsVisible()
    {
        return ($this->getChildCount() > 0) || !empty($this->getContent());
    }
}