<?php
/**
 * get relative path new 
 * @param string $source from path name
 * @param string $target target path name
 * @param string $separator default separator
 * @return void 
 */
function igk_io_get_relativepath_2(string $source, string $target, string $separator = DIRECTORY_SEPARATOR):?string{
    $vsource = igk_uri($source);
    $vtarget = igk_uri($target);
    if ($vsource==$vtarget){
        return './';
    }
    $v_cpath = null;
    $v_found = false;
    $v_count = 0;    
    $v_cp = [];
    if (substr($vtarget, -1) == '/'){
        $v_cp[] = '';
    }   
    while( ($v_cpath = dirname($vtarget)) && ($vtarget!= $v_cpath)){
        // retrieve start directory to source 
        array_unshift($v_cp, basename($vtarget));
        if (strpos($vsource, $v_cpath) === 0){
            $v_found = true;
            break;
        }
        $vtarget = $v_cpath; 
    }
    if ($v_found || ($vtarget=='/')){        
        $l = '';
        if (strpos($vsource, $v_cpath)!==0){
            igk_die("no matching");
        }
        if ($v_cpath=='/'){
            $v_cpath='';
        }
        $l = substr($vsource, strlen($v_cpath)+1);
        if (empty($l) || (strpos($l, "/") === false)){
            // found is in subfolder 
            $v_count = 0;
        } else {
            $v_count  = count(explode('/', trim($l,'/')));
            // if ($l[0]=='/'){
            //     $v_count --;
            // } 
        }
        $out = '';
        $out = $v_count == 0 ? './' : str_repeat("../", $v_count);
        $out.= implode("/", $v_cp);
        if ($separator != '/'){
            $out = str_replace('/', $separator, $out);
        }
        return $out;
    }
    return null;
}
function test($source, $target, $expected){
    $r = igk_io_get_relativepath_2($source, $target);
    igk_wln(compact("source", "target", "r"), $r == $expected);
}
test("/A/B/C/", "/A/B/C", "../C");
exit;
// same directory test
// test("/A/B/C", "/A/B/C", "./");
test("/A/B/C", "/A/B/B", "./B");
// sub directory test
test("/A/B/C", "/A/B/C/E/F", "./E/F");
test("/A/B/C", "/A/B/C/E/F/G/D", "./E/F/G/D");
test("/A/public/B/C/M/S", "/A/application/B/C/D/Z", '../../../../../application/B/C/D/Z');
// test("/tmp/B/C/D/E/J/K/L/M", "/Volumes/Data/B/C/D/E", "../../../../../../../../../Volumes/Data/B/C/D/E");
test("/src/public/assets/_lib_/Scripts/igk.js",
"/src/application/Lib/igk/Scripts/igk.js", 
"../../../../../application/Lib/igk/Scripts/igk.js");
test("/tmp/B/C/D/E/J/K/L/M", "/Volumes/Data/B/C/D/E", "../../../../../../../../../Volumes/Data/B/C/D/E");
test("/src/public/assets/_lib_/Scripts/",
"/src/application/Lib/igk/Scripts/igk.js/", 
"../../../../../application/Lib/igk/Scripts/igk.js/");
exit;