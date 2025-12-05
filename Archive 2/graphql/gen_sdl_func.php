<?php
// balafon --run .test/graphql/gen_sdl_func.php
use igk\io\GraphQl\GraphQlQueryOptions;
use igk\io\GraphQl\System\Database\Helpers\GraphQlDbHelper;
use IGK\Models\ModelBase;
use IGK\Models\Users;
$fc = function (Users $user, int $x, int $y = 4, string $limit="100", ?GraphQlQueryOptions $option=null){
};
$p = new ReflectionFunction($fc); 
echo "resolution : ".PHP_EOL;
echo GraphQlDbHelper::GenSDLFuncParameter($p->getParameters());
exit;