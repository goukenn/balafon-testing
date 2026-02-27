<?php
// @author: C.A.D. BONDJE DOUE
// @file: BootstrapModalDialog.php
// @date: 20230312 07:09:12
namespace igk\bootstrap\Components;
///<summary></summary>
/**
* 
* @package igk\bootstrap\Components
*/
class BootstrapModalDialog extends BootstrapComponentBase{

    /**
    * auto generate doc.
    * @var mixed
    */
    var $title;

    /**
    * auto generate doc.
    * @var mixed
    */
    var $body;

    /**
    * auto generate doc.
    * @var mixed
    */
    var $footer;

    /**
    * auto generate doc.
    */
    protected function initialize()
    {
        parent::initialize();
        $this['class'] = 'modal-dialog';
    }
}