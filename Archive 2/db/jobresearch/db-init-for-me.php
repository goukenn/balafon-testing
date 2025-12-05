<?php
// 
// @desc: auto generate the files data - initalize job search logic$
// @command: balafon --run .test/db/jobresearch/db-init-for-me.php --querydebug
// - options
// --- --from:Y-m-d
// --- --to:Y-m-d
// --- --exclude:Y-m-d[,Y-m-d] ranges
// command sample: balafon --run .test/db/jobresearch/db-init-for-me.php --querydebug willy.meli@yahoo.fr --to:2025-03-15 --from:2025-01-01 --exclude:2025-01-10,2025-01-20 --exclude:2025-02-01
use com\igkdev\projects\ForemJobDashboard\ContractTypes;
use com\igkdev\projects\ForemJobDashboard\Models\JobEnterprises;
use com\igkdev\projects\ForemJobDashboard\ModelUtilities\MainTaskModelUtility;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
// @params 
// string default_user 
// - 
/**
 * 
 * @param mixed $arg0
 * @return mixed 
 */
function get_dates(){
    func_get_arg(0) ? extract(func_get_arg(0)): null; 
    return include __DIR__ . '/db-init.php';
};
$ctrl = ForemJobDashboardController::ctrl(true);
$login = igk_getv($params, 0);
$user = $ctrl::SignInUser($login);
Logger::SetColorizer(new Colorize);
$faker = \Faker\Factory::create();
$e_contract = ContractTypes::GetCacheData(ContractTypes::EMPOYEE);
$from = $to = null;
$to = igk_getv($command->options, '--to', null);
$from = igk_getv($command->options, '--from', null);
$excludes = igk_getv($command->options, '--exclude', null);
if ($excludes){
    if (!is_array($excludes)){
        if (false === strpos($excludes, ',')){
            $excludes = [sprintf('%s,%s', $excludes, $excludes)];
        }
        else{
            $excludes = [$excludes];
        }
    }
    $excludes = array_map(function($c){
        if (false === strpos($c, ',')){
            $c = sprintf('%s,%s', $c, $c);
        }
        return explode(',', $c, 2);
    }, $excludes);
}
if (($main = $ctrl::modelUtility("MainTask")) instanceof MainTaskModelUtility) {
    $date = null;
    $cuser = $main->registerUser($user) ?? igk_die('missing register user');
    $dates = get_dates(compact('from', 'to', 'excludes'));
    $tcount = 0;
    ob_start();
    while (count($dates) > 0) {
        $date = array_key_first($dates);
        $info = $dates[$date];
        unset($dates[$date]);
        $dd  = [];
        $rl = explode("-", $date);
        foreach ($info[0]['tsearch'] as $its) {
            $it = $its['day'];
            $sit = $its['search'];
            while ($sit > 0) {
                $dd[] = date('Y-m-d', strtotime(sprintf("%s-%s-%s +%s day", $rl[0], $rl[1],  $rl[2], $it)));
                $sit--;
            }
        }
        while (count($dd) > 0) {
            $date = array_shift($dd);
            $job = $main->addJob('@@FAKE:'.$faker->sentence(), $cuser, intval(strtotime($date)));
            if ($job) {
                $job->description = $faker->paragraph(3);
                $job->summary = $faker->paragraph(rand(2,6));
                $job->update();
                $is_job = $main->isForemJobs($job);
                if ($is_job) {
                    Logger::info("forem jobs");
                } else {
                    Logger::warn("not a forem jobs");
                }
            }
        }
        $tcount++;
    }
    $src = ob_get_contents();
    ob_end_clean();
    // Logger::print($src);
    Logger::print("total-count : ".$tcount); 
}
igk_exit();
// JobEnterprises