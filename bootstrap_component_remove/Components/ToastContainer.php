<?php
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;

/**
 * represent bootstrap toast component
 * @package igk\bootstrap\Components
 */
class ToastContainer extends ComponentBase{
    /**
    * .ctr
    */
    public function __construct()
    {
        parent::__construct("div");
        $this["class"] = "toast-container"; 
    }
    /**
    * auto generate doc.
    * @param mixed $n
    */
    protected function _Add($n){
        if ($n instanceof Toast){
            return parent::_Add($n);
        }
        return false;
    }
}