<?php
/**
 * 
 * @package 
 * @property stdClass $source source definition
 * @property stdClass $info info definition
 */
interface IJSArrayRef{
}
trait Basic{
    var $info;
}
class OP implements IJSArrayRef{
    use Basic;
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
exit;