<?php
// @command: balafon --run .test/flutter/reader.php
use igk\Google\flutter\System\IO\IResolveTypeListener;
use igk\Google\flutter\System\IO\XCodeprojFile;
use IGK\Helper\Activator;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;

$file = '/private/tmp/sample/ios/Runner.xcodeproj/project.pbxproj';
/**
* auto generate doc.
*/
class LitteralClass{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $offensive;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $defensive;
}
/**
* auto generate doc.
*/
class StaSample implements IResolveTypeListener{
    /**
    * auto generate doc.
    * @param string $property
    * @param mixed $value
    * @param null|string $id
    */
    function treat(string $property, $value, ?string $id=null){
        if ($property=='IPHONEOS_DEPLOYMENT_TARGET'){
            $value = 15.4;
        }
        return $value;
    }
    /**
    * auto generate doc.
    * @param mixed $id
    * @param mixed $def
    */
    function resolve($id, $def){
        $m = igk_getv([
            'IPHONEOS_DEPLOYMENT_TARGET'=>'',
            'litteral'=>LitteralClass::class
        ], $id);
        if ($m ){
            if (class_exists($m)){
            return Activator::CreateNewInstance($m, $def);
            }
        }
        return null; 
    }
}
$qr = new StaSample;
$r = XCodeprojFile::Open($file, $qr);
Logger::SetColorizer(new Colorize);
Logger::print(json_encode($r->getEntries(), JSON_PRETTY_PRINT));
print_r($r);
$r->save('/private/tmp/sample/ios/Runner.xcodeproj/project_treat.pbxproj');
igk_exit();