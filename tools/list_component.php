<?php
// @author: C.A.D. BONDJE DOUE
// @filename: list_component.php
// @date: 20240912 08:01:18
// @desc: list all registrated system components.
// @command: balafon --run .test/tools/list_component.php
$g = array_filter(array_map(function($g){
    if (preg_match("/^".IGK_FUNC_NODE_PREFIX."/", $g)){
        return substr($g, strlen(IGK_FUNC_NODE_PREFIX));
    }
} , get_defined_functions()['user']));
sort($g);
igk_wln(implode("\n", $g));
igk_exit();