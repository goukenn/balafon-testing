<?php

function a()
{
    throw new TypeError("basic");
    igk_wln('invoke call : method a', $this->pList);
}
function b($x, $b= static::PAS_DUR, int & $c = 1, $tab=[12,   8,   6])
{
    list($_this) = igk_extract(get_defined_vars(), '_this');
    igk_wln('invoke call : method b', get_class($_this));
    igk_wln(get_defined_vars());
}