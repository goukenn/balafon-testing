<?php

// @command: balafon --run .test/tools/reflector/app.php .test/tools/reflector/temp/conditional_function.php

if (!function_exists('local_conditional')){
    function local_conditional(){
        igk_wln_e("basic call");
    }
}

if (!function_exists('version'))
    function version(){
        echo 'sample';
    };

