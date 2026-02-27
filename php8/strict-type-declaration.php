<?php
// @command: balafon --run .test/php8/strict-type-declaration.php
// declare(strict_types=1);
function doFoo(int $i): int{
    return $i + 100;
}
echo doFoo('8');