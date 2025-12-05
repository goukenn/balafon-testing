<?php
// + | --------------------------------------------------------------------
// + | tranform wor7051 to wp_2023 - table to 
// + |
use IGK\System\Database\MySQL\Helper\MySQLDbHelper;
$ad = igk_get_data_adapter(IGK_MYSQL_DATAADAPTER);
$ad->connect();
$filter = explode('|', 'wor7051_yoast_indexable|wor7051_yoast_indexable_hierarchy|wor7051_yoast_migrations|wor7051_yoast_primary_term|wor7051_yoast_seo_links');
$uri = 'http://localhost:7700';
// production 
// $uri = 'https://ttre.be';
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
// $o = "INSERT INTO wp_2023_posts VALUES";
// $ch = '';
// foreach ([
//     'attachment',
//     'custom_css',
//     'customize_changeset',
//     'nav_menu_item',
//     'oembed_cache',
//     'page',
//     'revision',
//     'tm_client',
//     'tm_portfolio',
//     'tm_service',
//     'tm_team_member',
//     'tm_testimonial',
//     'wpcf7_contact_form',
// ] as $k) {
//     $ss = MySQLDbHelper::DumpInsertTable($ad->sendQuery(
//         sprintf('select * from wor7051_posts where post_type=\'%s\';', $k)
//     )->to_array());
//     if (!empty($ss)) {
//         $o .= $ch . $ss;
//         $ch = ',';
//     }
// }
// $o .= ";";
// $o = "";
// $o .= "\n" . "INSERT INTO wp_2023_users VALUES";
// $ch = '';
// $ss = MySQLDbHelper::DumpInsertTable($ad->sendQuery(
//     sprintf('select * from wor7051_users;', $k)
// )->to_array());
// if (!empty($ss)) {
//     $o .= $ch . $ss;
//     $ch = ',';
// }
// $o .= ";";
// $o = str_replace('http://localhost:7700', 'https://ttre.be', $o);
// $o = str_replace('http://localhost', 'https://ttre.be', $o);
// $o = str_replace('http://ttre.be', 'https://ttre.be', $o);
// $o = str_replace('0000-00-00 00:00:00', date('Y-m-d').' 00:00:00', $o);
// echo $o;
// $ad->close();
// exit;