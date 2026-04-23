<?php
/**
* auto generate doc.
* @package 1
* @property stdClass $info info definition
*/

interface IJSArrayRef{
}
/**
* auto generate doc.
*/
trait Basic{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $info;
}
/**
* auto generate doc.
*/
class OP implements IJSArrayRef{
    use Basic;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $x;
}
$op = new OP;
if ($op instanceof Basic){
    echo "is basic";
} else {
    echo "instance not a basic field\n";
}
if ($op instanceof IJSArrayRef){
    echo "info : ";
    print_r($op->source);
}
igk_exit();