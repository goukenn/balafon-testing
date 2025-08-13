<?php
// @command: balafon --run .test/php/create_instance_from_interface.php
use IGK\Helper\Activator;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
// because some time we want to create a string declaration type 
/**
 * 
 * @package
 * @property string $name
 * @property string $id anned
 */
interface ILocalization{
}
class JO implements ILocalization{
    var $locale;
    var $x = 89;
    var $y = 0;
    var $t = [];
}
/**
 * create instance from interface 
 * @param string $interface 
 * @return object 
 * @throws Exception 
 * @throws IGKException 
 */
function create_instance_from_interface($class_name, $resolver=null){
    return Activator::CreateFromInterface($class_name, $resolver);
}
Logger::SetColorizer(new Colorize);
// $r = create_instance_from_interface(ILocalization::class);
$r = create_instance_from_interface(JO::class);
igk_wln_e("done", $r);