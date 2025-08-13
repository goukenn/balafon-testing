<?php
// get report list 
use IGK\Helper\IO;
use IGK\Helper\StringUtility;
use igk\js\common\JSExpression;
use igk\js\Vite\Helpers\ViteHelperUtility;
use igk\js\vueSFC\System\IO\VueSFCFile;
use igk\js\vueSFC\VueSFCTransformOptions;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
use IGK\System\IO\StringBuilder;
function transformVueFile(string $filename){
    $file = VueSFCFile::FromFile($filename);
    $options = new VueSFCTransformOptions;
    $options->type = 'ssr-script'; 
    return $file->transform($options);
}
$path = 
'/Volumes/Data/Dev/Javascript/current-script-uri/demo-gen-rendertostring/src';
$list = igk_io_getfiles($path, '/\.(vue|phtml)$/', true);// ViteHelperUtility::GetProjectVueFiles($path);
igk_wln("list ",  $list);
// for all list of items for vue / transform each template to  render methods 
//
$entry_app = 'App.vue';
$gp = [];
$found =  false;
foreach($list as $f){
    $gname = substr($f, strlen($path)+1);
    $ext = igk_io_path_ext($gname);
    $fn = igk_io_basenamewithoutext($f);
    $dir = dirname($gname);
    $name = (($dir != '.')?  $dir.'/' : '').$fn;    
    $key = StringUtility::CamelClassName($name);
    $transform = '';
    $src = '';
    switch($ext){
        case 'vue':
            if ($entry_app && $entry_app=='App.vue'){
                $found = true;
                continue 2;
            }
            $src = transformVueFile($f);
            break;
        case 'phtml':
            break;
        case 'js':
            break;
    }
    $gp[$key] = JSExpression::Litteral(
        $src
    );
    // igk_wln("name : ". StringUtility::CamelClassName($name));
}
if ($found){
    $dir = Path::Combine($path,$entry_app);
    $options = new VueSFCTransformOptions; 
    $options->components = $gp;
    $file = VueSFCFile::FromFile($dir); 
    $options->type = 'ssr-script'; 
    if ($file){
        $main_src = $file->transform($options);
    }
}
$ch = '';
$sb = new StringBuilder; 
foreach($gp as $k=>$v){
    $sb->append($ch.$k.":".$v);
    $ch = ',';
} 
echo $sb.'';
$temp = igk_io_tempdir('node-vite-temp');
IO::CreateDir($temp);
Logger::info("tempdir : ".$temp);
chdir($temp);
igk_io_w2file('main.js', $sb.'');
igk_io_w2file("package.json", json_encode([
    "type"=>"module",
    "license"=>'MIT',
    'author'=>'C.A.D BONDJE DOUE'
]));
$args = '';
`yarn add vue@3 && yarn add vite`;
$r = `node main.js {$args}`; 
IO::RmDir($temp);
exit;