<?php
// @author: C.A.D. BONDJE DOUE
// @filename: generate_search_pdf.php
// @date: 20250219 07:59:26
// @desc: script to generate pdf for search  
// @command: balafon --run .test/db/jobresearch/foremjobs/db-view-user-registered-job.php [username]
// to use it please : start server 
// >open -a docker
// >docker-compose start 
// >mysql -uroot -p -h 0.0.0.0 igkdev.dev
use com\igkdev\projects\ForemJobDashboard\Models\JobEnterprises;
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use IGK\Helper\JSon;
use IGK\System\Console\Colorize;
use \IGK\System\Console\Logger;
use function igk_resources_gets as __;
($user = igk_getv($params, 0)) || igk_die('missing user name');
($user = igk_get_user_bylogin($user)) || igk_die('user not found');
$ctrl = ForemJobDashboardController::ctrl(true);
// JobEnterprises::Insert([
//     "id"=>3,
//     "name"=>"AMAZON WEB SERVICES"
// ]);
// login to controller 
$c = $ctrl::login($user, false, false, false);
$ctrl->checkUser(false);
$c_user = $ctrl->getUserProfile();// currentUser();
// Logger::SetColorize(new Colorize);
Logger::SetColorizer(new Colorize);
Logger::print(sprintf(__('Generate ForemJobList for: "%s"'), $user->clLogin));
igk_environment()->querydebug = 1;
$tuser = $c_user->user();
$s = Jobs::prepare()
->distinct(true)
// ->columns([Jobs::FD_ID(), Jobs::FD_GUID()])
->with(JobEnterprises::table(), "enterprise", true)
->where([Jobs::FD_USER_ID=>$tuser->id])
->execute();
// igk_wln_e(__FILE__.":".__LINE__ , $s->to_array());
$r = JobEnterprises::count();
$f = [
    'tolalRegisteredEnterprises'=>$r,
    'userRegisteredEnterprises'=>JobEnterprises::count([]),
    'userJobs'=>$s->to_array()
];
Logger::print(JSon::Encode($f, null, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));