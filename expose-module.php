<?php
// $module = igk_require_module(igk\js\Vue3::class);
$module = AppTestProject::ctrl();
$asset = $module->exposeAssets();
igk_wln_e("expose assets ", $asset);