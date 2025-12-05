<?php
// @author: C.A.D. BONDJE DOUE
// @filename: main.php
// @date: 20250906 18:25:56
// @desc: 
// @command: balafon --run .test/balafon/commands/main.php

 
use IGK\System\Console\EnvironmentCommandScripts;
use IGK\System\Console\Logger; 

Logger::print('testing balafon command'); 

$s = EnvironmentCommandScripts::DetectCachingCommand();

igk_wln_e($s, 'end detaching');

