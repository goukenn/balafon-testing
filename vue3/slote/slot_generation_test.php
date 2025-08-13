<?php
use igk\js\Vue3\Compiler\VueSFCCompiler;
$d = igk_create_notagnode();
$d->div()->slot();
$s = VueSFCCompiler::ConvertToVueRenderMethod($d);
$expected = 'render(props, {slots}){const{h}=Vue;return [h(\'div\',slots.default())]}';
igk_wln_e($s);