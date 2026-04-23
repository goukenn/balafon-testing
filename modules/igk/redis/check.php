<?php
// @command: balafon --run .test/modules/igk/redis/check.php

igk_hook(IGKEvents::HOOK_DOWNLOAD_ASSETS, [
    'ctrl'=>AppTestProject::ctrl(true)
]);