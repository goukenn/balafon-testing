<?php
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;
/**
 * represent bootstrap toast component
 * @package igk\bootstrap\Components
 */
class ToastHeader extends ComponentBase{
    public function __construct(string $title)
    {
        parent::__construct();
        $this->obdata(function()use($title){
            ?>
            <img src="..." class="rounded me-2" alt="...">
            <strong class="me-auto"><?= $title ?></strong>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        <?php
        },'notagnode');
    }
}