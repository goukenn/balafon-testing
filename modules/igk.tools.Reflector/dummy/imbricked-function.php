<?php

// @command: balafon --run .test/modules/igk.tools.Reflector/dummy/imbricked-function.php

function a()
{
    function b(){

        echo "b call";
    } 
}

a();
print_r(get_defined_functions());

b();

// a();

exit;