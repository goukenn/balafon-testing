<?php
// + | --------------------------------------------------------------------
// + | tranform wor7051 to wp_2023 - table to 
// + |
use IGK\System\Database\MySQL\Helper\MySQLDbHelper;

$ad = igk_get_data_adapter(IGK_MYSQL_DATAADAPTER);
$ad->connect();
$filter = explode('|', 'wor7051_yoast_indexable|wor7051_yoast_indexable_hierarchy|wor7051_yoast_migrations|wor7051_yoast_primary_term|wor7051_yoast_seo_links');
$uri = 'http://localhost:7700';
$tables = [];
$tab = array_map(function($n)use($ad, $filter, $uri, & $tables){
    $table = $n->firstValue();
    if (in_array($table, $filter)){
        return null;
    }
    $ntable = str_replace('wor7051_','wp_2023_',$table);
    $tables[] = $ntable;
    $q = sprintf('INSERT INTO %s VALUES', $ntable);
    $ss = MySQLDbHelper::DumpInsertTable($ad->sendQuery(
        sprintf('select * from %s', $table)
        )->to_array());
        if (empty($ss))
        return null;
    $q.=$ss.';';
    $o = $q;
    $o = str_replace('http://localhost:7700', $uri, $o);
    $o = str_replace('http://localhost', $uri, $o);
    $o = str_replace('http://ttre.be', $uri, $o); 
    $o = str_replace('0000-00-00 00:00:00', date('Y-m-d').' 00:00:00', $o);
    $o = str_replace('wor7051_','wp_2023_',$o);
    return $o;
},$ad->sendQuery('show tables like \'wor7%\'')->to_array()); 
$r = implode(PHP_EOL, [
    'SET foreign_key_checks=0;',
    'LOCK TABLES `'.implode("` WRITE, `", $tables).'` WRITE;',
    implode("\n", array_filter($tab)),
    'UNLOCK TABLES;',
    'SET foreign_key_checks=1;',
]);  
igk_wl($r."\n");
igk_exit();
$ad->close();
exit;