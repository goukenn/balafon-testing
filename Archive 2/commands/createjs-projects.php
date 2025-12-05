<?php
use IGK\Helper\IO;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\Console\Helper\ConsoleUtility;
use IGK\System\Console\Logger;
use IGK\System\Cron\CommandHelper;
use IGK\System\IO\Path;
use IGK\System\Npm\JsonPackage;
use IGK\System\Shell\OsShell;
($dir = igk_getv($params, 0 ) ) ?? igk_die('required directory');
$p_dir = Path::CombineAndFlattenPath(igk_io_packagesdir(), 'node_modules', $dir);
IO::CreateDir($p_dir);
$package = new JsonPackage;
$package->author = IGK_AUTHOR;
$package->private = false;
$package->license = 'MIT';
$package->name = $dir;
$package->version = '1.0';
$package->description = 'lib for command line on balafon - server'; 
$package->main = 'src/main.js';
$package->type = 'commonjs';
$package->devDependencies=[
    "cli-color"=>'^1.0',
    "esbuild"=>'*'
];
$package->scripts = [
    'dev'=>'node --watch --watch-path=src build.js',
    'build'=>'export NODE_ENV=production && node build.js'
];
$_base = getcwd();
chdir($p_dir);
igk_io_w2file('package.json', JSon::Encode($package, JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
$bind = [];
$bind['src/main.js'] = function($f){
    igk_io_w2file($f, '"use strict";');
};
$bind['.env'] = function($f){
    igk_io_w2file($f, implode("\n", ['VITE_APP_URI=']));
};
$bind['build.js'] = function($f){
    igk_io_w2file($f, implode("\n", [<<<'EOF'
// @ts-nocheck
const esbuild = require('esbuild')
const is_production = process.env.NODE_MODE == 'production';

esbuild.build({
    entryNames:'main',
    entryPoints:[
        'src/main.js'
    ],
    outdir:"dist", 
    bundle:true,
    minify: is_production 
});
EOF    ]));
};
IO::CreateDir('src/lib'); 
ConsoleUtility::MakeFiles($bind, $command, true);
$code = false;
if (OsShell::Where('code')){
    Logger::success('code '.igk_io_get_relativepath($_base, $p_dir));
    $code = true;
}
Logger::success("cd ".igk_io_get_relativepath($_base, $p_dir));
Logger::success("yarn ");
Logger::success("yarn dev");
if ($code && function_exists('readline')){
    if (readline('open with code ? (y|n) ') == 'y'){
        `code $(pwd)`;
    }
}
igk_exit();