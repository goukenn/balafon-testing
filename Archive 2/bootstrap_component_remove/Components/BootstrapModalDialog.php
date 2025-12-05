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
    var $title;
    var $body;
    var $footer;
    protected function initialize()
    {
        parent::initialize();
        $this['class'] = 'modal-dialog';
    }
}