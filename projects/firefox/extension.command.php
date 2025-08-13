<?php
// @command: balafon --run .test/projects/firefox/extension.command.php
use IGK\System\Console\Helper\ConsoleUtility;
require_once(__DIR__.'/lib/FExManinest.php');
function create_extension($outdir, $command){
    $ref_option = (object)[
        'name'=>igk_getv($command->options, '--name', 'balafon-extension')
    ];
    $lib = [
        $outdir.'/manifest.json'=>function($f, $command)use($ref_option){
            $man = new FExManinest;
            $man->description = 'border les';
            $man->name = $ref_option->name;
            $man->manifest_version = 2;
            $man->version = '1.0';
            $man->icons = (object)[];
            $man->permissions = [
                "cookies",
                "http://*/*",
                "https://*/*"
            ];
            $man->content_scripts = [
                (object)[
                    'matches'=>[
                        '*://*.igkdev.com/*',
                        '*://*.napoleonsports.be/*'
                    ],
                    'js'=>['main.js']
                ]
            ];
            unset($man->browser_specific_settings);
            igk_io_w2file(
                $f, json_encode($man, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT)
            );
        }
    ];
    $lib[$outdir.'/main.js']=function($f){
        igk_io_w2file(
            $f, implode("\n", ['"use strict";', '/* entry extension*/'])
        );
    };
    $lib[$outdir.'/background-sw.js']=function($f){
        igk_io_w2file(
            $f, implode("\n", ['"use strict";', '/* entry extension*/'])
        );
    };
    ConsoleUtility::MakeFiles($lib, $command, false);
}
($outdir = igk_getv($params, 0)) || igk_die('missing out directory');
create_extension($outdir, $command);