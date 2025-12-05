<?php

// @command: balafon --run .test/db/reset-column-id.php

use IGK\Models\Users;
use IGK\System\Console\Logger;

$tab = Users::select_all(null, ['Columns' => ['clId']]);
$count = 1;
$ad = Users::model()->getDataAdapter();
$g = $ad->getFilter();
$ad->setFilter(false);
foreach ($tab as $row) {
    try {
        $row->update(
            ['clId' => $count]
        );
        $count++;
    } catch (\Exception $ex) {
        Logger::danger('missing update - condition');
        $count = $row->clId+1;
    }
}
$ad->setFilter($g);

$ad->sendQuery(sprintf(
    'ALTER TABLE %s AUTO_INCREMENT=%s',
    $ad->escape_string(Users::table()),
    $count
));



Logger::success('-> done');
