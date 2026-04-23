<?php
// @author: C.A.D. BONDJE DOUE
// @filename: rename_column_tables.php
// @date: 20250811 08:35:31
// @desc: rename columns table 
// @command: balafon --run .test/scripts/db/rename_column_tables.php
use IGK\Controllers\BaseController;
use IGK\System\Console\Logger;
use IGK\System\Regex\Replacement;

if ((!$params) || (count($params) < 2)) {
    igk_die('missing required parameter');
}
$table = $params[0];
$pattern = Replacement::RegexExpressionFromString($params[1] ?? '');
$new = $params[2];
$ad = $ctrl->getDataAdapter();
\IGK\System\IO\Helper::GenerateModel($ctrl, function (BaseController $ctrl, string $table, $info, &$manifest = []) 
use ($ad, $pattern, $new) {
    $g = $ad->sendQuery('Describe '.$table.';');
    if (!$g)return;
    $grammar = $ad->getGrammar();
    foreach($g->getRows() as $row){
        if (preg_match($pattern, $row->Field)){
            $old_column = $row->Field;
            $v_info = $info->columnInfo;
            $new_column = preg_replace($pattern, $new, $old_column);
            if (!isset($v_info[$new_column])){
                Logger::warn("can't rename the column $old_column to $new_column. possible existing or missing");
                continue;
            }
            Logger::info('replace '.$old_column.'==>'.$new_column);
            $b = $v_info[$new_column];
            $ts = $grammar->getColumnInfo($b);
            $query = sprintf(
                'ALTER TABLE %s CHANGE COLUMN %s %s %s',
                $table, 
                $old_column,
                $new_column,
                $ts
            );
            $f = $ad->sendQuery($query);
            igk_wln("result : ".$f);
        }
    }
});
Logger::success('done');
igk_exit();