<?php
// balafon --run .test/test_pointer.php
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
 * 
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
    // $harren = (object)['v'=>$tab[0], 'next'=>$tab[$tab[0]]];
    // transform to pointer fields 
    // $head = null; 
    // $tcount = count($tab);
    // $c = 0;
    // $p = null;
    // while($c < $tcount){
    //     if (is_null($head)){
    //         $q = $tab[$c];
    //     }else {
    //         $q = $tab[$p->v];
    //     }
    //     if (($q>=0) && ($q<$tcount)){
    //         if (is_null($head)){
    //             $head = (object)["v"=>$q, "next"=>null];
    //             $p = $head;
    //         }else{
    //             $p->next = (object)["v"=>$q, "next"=>null];
    //         }
    //         $c++;
    //     }
    //     else {
    //         // item not found in  index
    //         break;
    //     }
    // }
    // return false;
}
// create an array of item 
$data = [0, 1 , 2 , 3 ,4 ];
Logger::print("Found duplicate");
echo detect_cycle($data);
exit;
// create a copy of the array 
Logger::info("create a value copy ");
$r = $data;
$r[0] = 8;
print_r($data);
Logger::warn(sprintf("value is equal ? %s ", $r == $data));
// create a reference pointer to the array 
Logger::info("create a reference pointer");
$r = & $data;
$r[0] = 8;
print_r($data);
Logger::warn(sprintf("value is equal ? %s ", $r == $data));
exit;
// print_r(class_parents(C::class));
// igk_wln ( igk_get_class_traits(C::class));
// exit;
// $d = ['false', 1,false,4];
// $a = & $d;
// igk_wln("first value ", each($d)===false);
// while (($p = next($d)) != false) {
//     igk_wln("item : ", $p);
// }