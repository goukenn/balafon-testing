<?php
// use \JSonBindAsAnnotation;
use IGK\System\IO\JSon\Annotations\JSonBindAsAnnotation;

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
     * @JSonBindAsAnnotation(arrayOf<TempOracle>)
     * @var mixed
     */
    var $childs;
}