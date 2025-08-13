<?php
// @author: C.A.D. BONDJE DOUE
// @file: BootstrapComponentBase.php
// @date: 20230312 06:44:26
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;
///<summary></summary>
/**
* 
* @package igk\bootstrap\Components
*/
abstract class BootstrapComponentBase extends HtmlNode{
    protected function initialize()
    {
        parent::initialize();
        $this['igk-bs-component'] = 'true';
    }
}