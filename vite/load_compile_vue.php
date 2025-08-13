<?php
use igk\js\Vue3\System\IO\VueSFCFile;
$sf = new VueSFCFile;
$sf->loadFile('/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Projects/app_test/ViteViews/mars.vue');
echo $sf->compile();
igk_exit();