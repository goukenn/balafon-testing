<?php
// @author: C.A.D. BONDJE DOUE
// @filename: pg_realobject.php
// @date: 20260421 14:42:04
// @desc: connect to postgre real connection 
// @command: balafon --run .test/database/postgre/pg_realobject.php
use IGK\System\Console\Logger;

extension_loaded('pgsql') || igk_die('missing postgre sql');
$ob = [
    'dbname'=>null, 
    'user'=>'postgres',
    'password'=>'rootbonaje',
    'host'=>'0.0.0.0',
    'port'=>5432,
    'options'=>null
];
$connexion = implode(' ', array_filter(array_map(function($b, $a){
    if (empty($b)) return null;
    return implode('=',[$a,$b]);
}, $ob, array_keys($ob))));
function getPostgresTables($conn): array
{
    $result = pg_query($conn, "
        SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = 'public'
          AND table_type = 'BASE TABLE'
        ORDER BY table_name
    ");
    if (!$result) {
        return [];
    }
    $tables = pg_fetch_all($result, PGSQL_ASSOC);
    pg_free_result($result); 
    return array_column($tables ?? [], 'table_name');
}
$con = null;
try{
if ($con = pg_connect($connexion)){
    $p = getPostgresTables($con); 
    igk_wln($p);
pg_close($con);
}
else{
    Logger::danger('missing connexion');
}
} catch(\Exception $ex){
    Logger::info('missing :::: '.$ex->getMessage());
}