<?php
use IGK\System\IO\Path;
use IGK\System\Npm\JsonPackage;
$dir = $params[0];
// $json = new JsonPackage;
// $json->version = "1.0";
$data = json_encode(["description"=>"description"]);
foreach(["", "en","zh-cn","zh-tw","fr","de","it","es","ja","ko","ru","pt-br","tr","pl","cs","hu" ] as $lang){
    if ((""!=$lang ) && ('en'!=$lang)){
        $lang = ".".$lang;
    } else {
        $lang =  "";
    }
    $file = Path::Combine($dir, sprintf("package.nls%s.json", $lang));
    if (!file_exists($file)){ 
        igk_io_w2file($file, $data);
    }
}