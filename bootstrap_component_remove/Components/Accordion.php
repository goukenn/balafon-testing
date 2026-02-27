<?php
// @author: C.A.D. BONDJE DOUE
// @file: Accordion.php
// @date: 20220116 05:45:34
namespace igk\bootstrap\Components;

/**
* auto generate doc.
* @package igk\bootstrap\Components
*/
class Accordion extends ComponentBase{

    /**
    * auto generate doc.
    */
    protected function initialize()
    {
        $this["class"] = "accordion";
    }

    /**
    * auto generate doc.
    */
    public function open(){
        return $this->setClass("+open");
    }
}