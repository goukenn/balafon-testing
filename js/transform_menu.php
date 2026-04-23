<?php
use IGK\Helper\Activator;
use IGK\System\WinUI\Menus\Menu;
use IGK\Helper\JSon;
use igk\js\common\JSExpression;
use igk\js\Vue3\Vite\ViteMenuHelper as ViteViteMenuHelper;
use igk\js\Vue3\Vite\ViteMenuInfo as ViteViteMenuInfo;

$data = [
    "menu.local"=>[
        "title"=>"local"
    ]
    ,"menu.local.list.basic.info"=>[
        "title"=>"info"
    ]
];
/**
* auto generate doc.
*/
class ViteMenuHelper extends ViteViteMenuHelper
{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $source; 
}
/**
* auto generate doc.
*/
class ViteMenuInfo extends ViteViteMenuInfo{
}
echo "build menu for balafon + vite application : " . PHP_EOL;
echo JSExpression::Stringify((object)ViteMenuHelper::Build($data), (object)[
    'ignoreNull'=>true, 
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;