#!/usr/bin/env php
<?php

if (!isset($_ENV["BALAFON_URI"])){  
    $_ENV["BALAFON_URI"] = "https://igkdev.com/balafon";
}
echo "Install balafon " . PHP_EOL;
if (empty($root_uri = $_ENV["BALAFON_URI"])){
    echo "BALAFON_URI environment not specified (-2)". PHP_EOL;
    exit(-2);
};
shell_exec("mkdir -p src/application");
shell_exec("curl -A firefox {$root_uri}/get-download -o balafon.zip");
shell_exec("unzip  -d src/application -o balafon.zip");
shell_exec("unlink balafon.zip"); 
$pwd = getcwd();
$s = shell_exec("which balafon");
$cmd = 'balafon'; 
$cp = $pwd."/src/application/Lib/igk/bin";
if (empty($s)){
    $tg = "PATH={$cp}:\$PATH"; 
    shell_exec("grep -qxF 'export {$tg}' ~/.profile || echo 'export {$tg}' >> ~/.profile");
    shell_exec("grep -qxF 'export {$tg}' ~/.profile || echo 'export {$tg}' >> ~/.bashrc");
    shell_exec("chmod -R 775 src/application/Lib/igk/bin");    
}
putenv("PATH=".$_SERVER["PATH"] . PATH_SEPARATOR .$cp);
shell_exec("balafon --install-site --root-dir:src/public");