<?php
// @author: C.A.D. BONDJE DOUE
// @file: Accordion.php
// @date: 20220116 05:45:34
namespace igk\bootstrap\Components;
class Accordion extends ComponentBase{
    protected function initialize()
    {
        $this["class"] = "accordion";
    }
    public function open(){
        return $this->setClass("+open");
    }
}