<?php
// @command: balafon --run .test/tools/reflector/app.php .test/tools/reflector/temp/conditional_function_mixed_ouput.php
if (!function_exists('jumping')){
    $a = 'sample';

/**
* auto generate doc.
*/
function jumping(){
        echo 'jumping';
    }
    if (!function_exists('cad')){

/**
* auto generate doc.
*/
function cad(){
            echo 'aaaa';
        }
    }
    $ee = 'pratical'; 
}