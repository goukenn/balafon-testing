<?php
// @command : balafon --run .test/php/check-support.trait.php

/**
* auto generate doc.
*/
trait One{
    /**
    * auto generate doc.
    */
    function onApply(){
        echo "appy";
    }
}
/**
* auto generate doc.
*/
class BaseA{
    use One;
}
/**
* auto generate doc.
*/
class BaseB extends BaseA{
}
/**
* auto generate doc.
* @param string $class_name
* @param string $trait_class
* @return mixed
*/
function is_support_trait(string $class_name , string $trait_class){
    return igk_sys_reflect_is_support_trait($class_name, $trait_class);
}
$trait = (new ReflectionClass(BaseB::class))->getTraits();
print_r($trait);
echo is_support_trait(BaseB::class, One::class). "\n";
igk_exit();