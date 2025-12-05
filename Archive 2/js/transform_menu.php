<?php
use IGK\Helper\Activator;
use IGK\System\WinUI\Menus\Menu;
use IGK\Helper\JSon;
use igk\js\common\JSExpression;
use igk\js\Vue3\Vite\ViteMenuHelper as ViteViteMenuHelper;
use igk\js\Vue3\Vite\ViteMenuInfo as ViteViteMenuInfo;
$data = [
    // "menu.home.presentation" => [
    //     "title"=>"Presentation",
    //     "type"=>1,
    //     "target"=>"#",
    //     "locations"=>['home','aside']
    // ],
    // "menu.home" => [
    //     "title" => "home",
    //     'locations'=>JSExpression::CreateMethod('function()',"{ return false; }")
    // ],
    // "menu.home.about" => [
    //     "title" => "home"
    // ],
    // "menu.settings" => [
    //     "title" => "Settings",
    //     "auth"=>false
    // ],
    // "menu.settings.options" => [
    //     "title" => "Options",
    //     "target"=>"/settings",
    //     "auth"=>"@admin/operator"
    // ],
    // "menu.info"=>[
    //     "items"=>[
    //         "base"=>"indication",
    //         "jour de soleil"
    //     ]
    // ],
    "menu.local"=>[
        "title"=>"local"
    ]
    ,"menu.local.list.basic.info"=>[
        "title"=>"info"
    ]
];
class ViteMenuHelper extends ViteViteMenuHelper
{
    var $source; 
}
class ViteMenuInfo extends ViteViteMenuInfo{
}
echo "build menu for balafon + vite application : " . PHP_EOL;
// echo JSon::Encode(ViteMenuHelper::Build($data), (object)[
//     'ignore_empty'=>true
// ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo JSExpression::Stringify((object)ViteMenuHelper::Build($data), (object)[
    'ignoreNull'=>true, 
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;