<?php
// @command: balafon --run .test/php8/strict-type-declaration.php

/**
* auto generate doc.
* @param int $i
* @return int
*/
function doFoo(int $i): int{
    return $i + 100;
}
echo doFoo('8');