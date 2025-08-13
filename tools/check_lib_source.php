<?php
use IGK\System\Console\Logger;
use Symfony\Component\ErrorHandler\Error\FatalError;
$dir = realpath(__DIR__.'/../../src/application/Lib/igk');
$list = [$dir];
$files = [];
while(count($list)>0)
{
    $dir = array_shift($list);
    if ($hdir = opendir($dir)){
        while($m = readdir($hdir)){
            if (($m=='.') || ($m=='..'))
                continue;
            $m = $dir .'/'.$m;
            if (is_dir($m)){
                array_unshift($list, $m);
            } else if (preg_match('/\.php$/i', $m)){
                $files[] = $m;
            }
        }
        closedir($hdir);
    }
}
// sort($files);
foreach($files as $f){
    echo $f . "\n";
    ob_start();
    try{
    $c = `php $f`;// include($f);
    }catch(Error $ex){
    } catch(Exception $ex){
    } catch(FatalError $ex){
        echo "FFF";
    }
    $s = ob_get_contents();
    ob_end_clean();
    if (!empty($s)){
        echo $f.' : '.$s."\n";
    }
}