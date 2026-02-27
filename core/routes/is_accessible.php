<?php
// @author: C.A.D. BONDJE DOUE
// @filename: is_accessible.php
// @date: 20260220 14:33:22
// @desc: check if path is asseccisble 
// @command: balafon --run .test/core/routes/is_accessible.php

use IGK\System\Http\RouteHandler;

$path = '/sample/{id:guid}';
$uri = '/sample/75B203A4-3555-8261-31F2-69055A1A8D3F';

/**
 * accessible data definition 
 * @param string $uri 
 * @param string $path 
 * @return bool 
 */
function is_accessible(string $uri, string $path):bool{
        if ($regex = RouteHandler::GetRouteRegex($path, [])){
            igk_wln('the regex '. $regex);
            return preg_match($regex, $uri);
        }
        return false;

}

igk_wln( "? ".is_accessible($uri, $path));

igk_exit();





