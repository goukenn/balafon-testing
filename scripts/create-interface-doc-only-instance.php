<?php

// @author: C.A.D. BONDJE DOUE
// @filename: create-interface-doc-only-instance.php
// @date: 20250810 21:29:33
// @desc: demonstration of create interface doc only instance 
// @command: balafon --run .test/scripts/create-interface-doc-only-instance.php

use IGK\Helper\Activator;
use IGK\System\Console\Logger;
use IGK\System\IToArray;
use IGK\System\Text\RegexMatcherContainer;

/**
 * 
 * @package 
 * @property int $z litteral definition
 * @var {s} $i
 */
interface B {}
/**
 * 
 * @package 
 * @property int|mixed $x 
 * @property int|mixed $y 
 */
interface A extends B {}

$props = Activator::GetInstanceProperties(A::class);

if (($obj = Activator::CreateNewInstance(A::class, ['z'=>500])) instanceof A){
    $obj->x = 888;
    $objs = Activator::CreateNewInstance(A::class, $obj->to_array());
    $r = $objs === $obj;
    $objd = Activator::GetInstanceProperties(A::class);
    if ($obj instanceof IToArray){
        igk_wln((array)$obj->to_array());
    }
}

igk_wln_e($props, $obj);