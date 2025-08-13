#!/usr/bin/env php
<?php
if (!isset($_ENV["BALAFON_URI"])){  
    $_ENV["BALAFON_URI"] = "https://igkdev.com/balafon";
    // echo "BALAFON_URI environment not specified (-1)". PHP_EOL;
    // exit(-1);
}
echo "Install balafon " . PHP_EOL;
if (empty($root_uri = $_ENV["BALAFON_URI"])){
    echo "BALAFON_URI environment not specified (-2)". PHP_EOL;
    exit(-2);
};
`mkdir -p src/application`;
`curl -A firefox {$root_uri}/get-download -o balafon.zip`;
`unzip  -d src/application -o balafon.zip`;
`unlink balafon.zip`; 
$pwd = getcwd();
$s = `which balafon`;
$cmd = 'balafon'; 
$cp = $pwd."/src/application/Lib/igk/bin";
if (empty($s)){
    $tg = "PATH={$cp}:\$PATH"; 
    `grep -qxF 'export {$tg}' ~/.profile || echo 'export {$tg}' >> ~/.profile`;
    `grep -qxF 'export {$tg}' ~/.profile || echo 'export {$tg}' >> ~/.bashrc`;
    `chmod -R 775 src/application/Lib/igk/bin`;    
}
putenv("PATH=".$_SERVER["PATH"] . PATH_SEPARATOR .$cp);
`balafon --install-site --root-dir:src/public`;