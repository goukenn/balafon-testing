<?php
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Html\Dom\HtmlNoTagNode;
/**
 * represent bootstrap toast component
 * @package igk\bootstrap\Components
 */
class CardHeader extends ComponentBase{
    protected function initialize()
    {
        $this["class"] = "card-header";
    }
}