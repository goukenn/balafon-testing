<?php
// @command: balafon --run .test/modules/igk.tools.Reflector/dummy/imbricked-function.php

/**
* auto generate doc.
*/
function a()
{
/**
* auto generate doc.
*/
function b(){
        echo "b call";
    } 
}
a();
print_r(get_defined_functions());
b();
exit;