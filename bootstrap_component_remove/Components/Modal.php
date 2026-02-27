<?php
// @author: C.A.D. BONDJE DOUE
// @file: Modal.php
// @date: 20230312 06:43:43
namespace igk\bootstrap\Components;
use IGKValueListener;
///<summary></summary>
/**
* 
* @package igk\bootstrap\Components
*/
class Modal extends BootstrapComponentBase{

    /**
    * auto generate doc.
    * @var mixed
    */
    var $dialog;

    /**
    * auto generate doc.
    * @var mixed
    */
    var $labelledby;

    /**
    * auto generate doc.
    */
    protected function initialize()
    {
        parent::initialize();
        $this["class"] = "modal";
        $this["tabindex"] = "-1";
        $this["aria-labelledby"] = new IGKValueListener($this, "labelledby");
        $this->dialog = new BootstrapModalDialog();
        $this->add($this->dialog);
    }
}