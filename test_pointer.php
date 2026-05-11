<?php
use IGK\System\Console\Logger;

/**
* auto generate doc.
*/
trait AAC {
    /**
    * auto generate doc.
    */
    function doA(){
    }
}
/**
* auto generate doc.
*/
trait AAT{
    use AAC;
    /**
    * auto generate doc.
    */
    function doAat(){
    }
}
/**
* auto generate doc.
*/
trait AAB{
    use AAT;
    /**
    * auto generate doc.
    */
    function doAab(){
    }
}
/**
* auto generate doc.
*/
class A{
}
/**
* auto generate doc.
*/
class B  extends A{
    use AAB;
}
/**
* auto generate doc.
*/
class C extends B{
}
/**
* auto generate doc.
* @param mixed $cl
* @return array
*/
function igk_get_class_traits($cl){
    $tab = array_values(class_parents($cl));
    array_unshift($tab, $cl);
    $traits = [];
    $filter = [];
    while(count($tab)>0){
        $q = array_shift($tab);    
        if (key_exists($q, $filter)){
            continue;
        }
        if ($v_tr = class_uses($q)){
            $filter[$q] = 1;
            $traits = array_merge($traits, $v_tr); 
            $tab += $v_tr;
        }
    }
    return array_unique($traits);
}
/**
* auto generate doc.
* @param array $tab
* @return mixed
*/
function detect_cycle(array $tab){
    $slow = 0;
    $fast = 0;
    while(true){
        $slow = $tab[$slow];
        $fast = $tab[$tab[$fast]];
        if ($slow == $fast){
            break;
        }
    }
    $slow = 0;
    while ($slow!=$fast){
        $slow = $tab[$slow];
        $fast = $tab[$fast];        
    }
    return $slow;
}
$data = [0, 1 , 2 , 3 ,4 ];
Logger::print("Found duplicate");
echo detect_cycle($data);
igk_exit();
Logger::info("create a value copy ");
$r = $data;
$r[0] = 8;
print_r($data);
Logger::warn(sprintf("value is equal ? %s ", $r == $data));
Logger::info("create a reference pointer");
$r = & $data;
$r[0] = 8;
print_r($data);
Logger::warn(sprintf("value is equal ? %s ", $r == $data));
igk_exit();