<?php
// @author: C.A.D. BONDJE DOUE
// @filename: anonymous_class.php
// @date: 20251209 10:22:22
// @desc: test anonymous class in php8 
// @command: balafon --run .test/php8/anonymous_class.php
use IGK\Helper\IO;
echo "\n";
echo "return ".json_encode(get_included_files(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . ";".PHP_EOL;
IO::GetList()
igk_exit();

/**
* auto generate doc.
*/
interface IBehaviour{

    /**
    * auto generate doc.
    */
    function dosome();
} 
$a = new class implements IBehaviour{
    var $x;
    function dosome()
    {
        igk_wln('do something ------ ');
    }
};
igk_wln_e('the class', $a, $a->dosome());