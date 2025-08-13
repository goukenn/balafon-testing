<?php
// demo - formatter 
use igk\btmSyntax\Lib\FormatterDefinitionLoader;
use IGK\Helper\JSon;
$definition = [
    "scopeName" => "php.source.detected",
    "repository" => [
        "start-char"=>[
            "match"=>"^[a-z]"
        ],
        "php-vars" => (object)[
            "name" => "block-name",
            "match" => "\b[a-z][a-z0-9\-]*\b",
            "throwError" => "True",
            "cardinality" => "kk9",
            "captures" => [
                "0" => [
                    "name" => "basic.data.name",
                    "patterns"=>[
                        ["include"=>"#start-char"]
                    ]
                ]
            ]
        ],
        "color-model" => (object)[
            "patterns" => [
                ["include" => "#basic-color"],
                ["include" => "#basic-color"]
            ]
        ],
        "basic-color"=>(object)[
            "match"=>"#color",
        ] 
    ],
    // "patterns" => [
    //     ["include" => "#php-vars"],
    //     ["begin" => "<<<", "end" => ",", "name" => "local"]
    // ]
];
$def = FormatterDefinitionLoader::Load($definition);
igk_wln(__FILE__ . ":" . __LINE__, $def, [
    "output" => JSon::Encode($def, [
        "ignore_empty" => true,
        "ignore_null" => false
    ], JSON_PRETTY_PRINT)
]);
igk_wln_e(
    "serialize",
    "-------------------------------------",
    json_encode($def, JSON_PRETTY_PRINT)
);