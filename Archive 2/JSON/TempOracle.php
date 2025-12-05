<?php
// use \JSonBindAsAnnotation;
use IGK\System\IO\JSon\Annotations\JSonBindAsAnnotation;
class TempOracle{
    var $name;
    var $title;
    /**
     * @JSonBindAsAnnotation(arrayOf<TempOracle>)
     * @var mixed
     */
    var $childs;
}