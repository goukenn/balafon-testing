<?php
use IGK\System\IO\JSon\Annotations\JSonBindAsAnnotation as JSonBindAs;

/**
* auto generate doc.
*/
class TempOracle{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $name;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $title;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $childs;

    /**
     * 
     * @var mixed
     * @JSonBindAs(Int)
     */
    var $age;
}