<?php
use igk\js\Vite\Helpers\ViteHelperUtility;
$ctrl=  AppTestProject::ctrl();
$ctrl->register_autoload();
igk_require_module(igk\js\Vite::class);
$src = ViteHelperUtility::GetCoreStyleContent($ctrl);
echo $src;