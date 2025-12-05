<?php

function first(){
    global $jp;
$jump = 12;
$roco = function ($a,$b)   use   ($jump){
return 1 + $jump;
};
}