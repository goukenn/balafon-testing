<?php
// @author: C.A.D. BONDJE DOUE
// @filename: gen-page-search.php
// @date: 20251015 09:01:13
// @desc: 
// @command: balafon --run .test/forem-searchjob/gen-page-search.php
// @balafon-command: forem-gen-page
use function igk_resources_gets as __;
use function igk_html_host as _h; 

$ctrl = ForemJobDashboardController::ctrl(true);
if (!isset($user)){
    $user = igk_sys_default_user(); 
}
$n = _h('div.presentation', _h('h2', __('Job technique')) , 
_h('p', 
    sprintf(__('the job descriptions : %s'), $user->clLogin))
);
igk_wln_e($n->render());