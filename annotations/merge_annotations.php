<?php
// @command: balafon --run .test/annotations/merge_annotations.php
use igk\docs\swagger\Annotations\SwaggerSecurityFlowAnnotation;
use IGK\System\Annotations\AnnotationBase;
use IGK\System\Annotations\AnnotationInfoAnnotation;
use IGK\System\Console\Logger;
use IGK\System\Helpers\AnnotationHelper;

$_globals_security_schemes = [];
/**
 * summary definition schemes 
 *     
 * @BAnnotation(title='sample')
 * @BAnnotation(title='sample_x')
 * @package 
 */
class Sample
{
    /**
    * auto generate doc.
    * @return void
    */
    function actions() {}
}
/**
* auto generate doc.
*/
class BAnnotation extends AnnotationBase
{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $title;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $version;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $description;
}
/**
* auto generate doc.
* @param array &$tab
* @return void
*/
function global_action_list(string $class_or_name, &$tab)
{
    $ref = igk_sys_reflect_class($class_or_name) ?? new ReflectionClass($class_or_name);
    $annotations =  AnnotationHelper::GetAnnotations($ref);
    $s  = null;
}
if (!function_exists('igk_logger_dashline')) {
    /**
     * logger dash line 
     * @param int $counter 
     * @param string $litteral 
     * @return void 
     */
function igk_logger_dashline($counter = 80, $litteral = '-')
    {
        Logger::print(str_repeat($litteral, $counter));
    }
}
# 
igk_logger_dashline();
Logger::print('binding multiple properties actions');
igk_logger_dashline();
$ref = global_action_list(Sample::class, $_globals_security_schemes);
$g = new SwaggerSecurityFlowAnnotation('implicit');
$g->tokenUrl = 'token-list';
igk_wln_e($g , $ref, $_globals_security_schemes);