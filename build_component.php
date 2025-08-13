<?php
// testing 
use IGK\System\Html\HtmlNodeBuilder;
$main = igk_create_node("vue_component");
echo get_class($main) . "\n";
$builder = new HtmlNodeBuilder($main);
$menus = [
    "home",
    "about",
    "index"
];
$bmenus = igk_html_node_vue_menus($menus);
$builder([
    "bootstrap_offcanvas#modal-menu" => [
        "nav.dispflex.pad-10.flex-justify-l" => [
            "div" => [
                "google_icon_outlined.igk-btn.pad-4(menu_open)" => [
                    '_' => ['data-bs-toggle' => 'offcanvas',  'data-bs-target' => '#modal-menu']
                ],
            ],
            "h1.marg-l-4" => "menu",
        ],
        "div.content" => [
            "vue_clone(#menu)" => [],
            "div"=>"body .... menu content",
            "clonenode"=>[
                "@"=>$bmenus
            ],
            // "vMenus"=>[
            //     "@"=>[$menus]
            // ]
        ]
    ]
],  $main->vTeleport("body"));
$main->renderAJX();
exit;