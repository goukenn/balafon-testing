<?php
// @author: C.A.D. BONDJE DOUE
// @file: FrameworkMetadataGenerator.php
// @date: 20260227 09:58:21
namespace IGK\System\Console\Commands\Utility;
/**
* auto generate doc.
* @package IGK
* @author C.A.D. BONDJE DOUE
*/
class FrameworkMetadataGenerator{
    /**
     * line feed prefix
     */
    const DOC_LF_PREFIX = "\n";
    /**
    * auto generate doc.
    * @var mixed
    */
    const PROP_TYPE_EXTRA_DEF = '::type_extra';
    /**
    * auto generate doc.
    * @var mixed
    */
    const PROP_BUFFER = '::buffer';
    /**
    * auto generate doc.
    * @var mixed
    */
    const PROP_INDEF = '::indef';
    /**
    * auto generate doc.
    * @var mixed
    */
    const PROP_NAMESPACES = '::namespaces';
    /**
    * initialize buffer object
    * @param mixed & $buffer
    * @return object
    */
    public static function InitBufferObject(& $buffer){
        return (object)[
            'pos' => 0,            
            'buffer' => &$buffer,  
            'replaces'=>[],        
        ];
    }
}