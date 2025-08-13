<?php
// @command : balafon --run .test/php/check-support.trait.php
trait One{
    function onApply(){
        echo "appy";
    }
}
class BaseA{
    use One;
}
class BaseB extends BaseA{
}
function is_support_trait(string $class_name , string $trait_class){
    return igk_sys_reflect_is_support_trait($class_name, $trait_class);
}
$trait = (new ReflectionClass(BaseB::class))->getTraits();
print_r($trait);
echo is_support_trait(BaseB::class, One::class). "\n";
igk_exit();