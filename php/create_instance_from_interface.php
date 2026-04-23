<?php
// @command: balafon --run .test/php/create_instance_from_interface.php
use IGK\Helper\Activator;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;

/**
* auto generate doc.
* @package 1
* @property string $id anned
*/
interface ILocalization{
}
/**
* auto generate doc.
*/
class JO implements ILocalization{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $locale;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $x = 89;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $y = 0;
    /**
    * auto generate doc.
    * @var mixed
    */
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
$r = create_instance_from_interface(JO::class);
igk_wln_e("done", $r);