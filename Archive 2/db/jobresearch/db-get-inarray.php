<?php
// @command: balafon --run .test/db/jobresearch/db-get-inarray.php --querydebug
use com\igkdev\projects\ForemJobDashboard\Models\JobForemJobs;
use com\igkdev\projects\ForemJobDashboard\Models\Jobs;
use IGK\Database\IDbColumnProperties; 
use IGK\Models\ModelBase;
use IGK\System\Console\Logger;
use IGK\System\Database\DbUtils;
$ctrl = ForemJobDashboardController::ctrl(true);
// + | retrieve column info list 
// $g = new ReflectionClass(DbColumnInfoTrait::class);
// $tp = [];
// foreach($g->getProperties() as $p){
//     $tp[$p->getName()] = 1;
// }
// $keys = array_keys($tp);
// sort($keys);
// igk_wln_e($g, '* @property mixed $'.implode("\n* @property mixed $", $keys)."");
/**
 * is column join candidate
 * @param IDbColumnProperties $column 
 * @return bool
 */
function igk_db_is_column_join_candidate($column){
    return (
        $column->clIsIndex || $column->clIsPrimary || $column->clAutoIncrement
    );
}
$infos = Jobs::model()->getTableColumnInfo();
$infos = array_filter($infos,function($t){
    return igk_db_is_column_join_candidate($t);
});
// TODO: generate function definition ...
igk_wln("candidate to join filter: ", $infos,"");
// // - | drop list of database 
// $ad = $ctrl::getDataAdapter();
// $ad->connect();
// // $basic = [...["on"=>true]];
// $tab = igk_explode('dumpql|prisma|wp_erp|symfony_doctrine');
// array_filter($tab, function($db)use($ad){
//     try{
//         Logger::info('drop database '.$db);
//         $ad->sendQuery(sprintf('drop database `%s`', $db));
//     }
//     catch(Exception $ex){
//         Logger::danger($ex->getMessage());
//     }
// });
// $ad->close();
Jobs::registerMacro("joinOnJobId", function($call){
    $cl = static::class;
    $rt = $cl::column($cl::FD_ID);
    $c = [];
    if ($call){
        // align condition 
        $c[] = $rt."=".$call;
    }
    // return [$cl::table()=>[$rt=>$call]]; 
    return [$cl::table()=>[$rt."=".$call]]; 
});
JobForemJobs::registerMacro("targetOnJobId", function(){
    $cl = static::class;
    return $cl::column(JobForemJobs::FD_JOB_ID);
});
// SELECT * FROM `tbigk_foremjobs_job_forem_jobs` LETF JOIN `igkdev.dev`.`tbigk_foremjobs_jobs` WHERE `tbigk_foremjobs_jobs`.`id`='1';
$lb = Jobs::joinOnJobId(JobForemJobs::targetOnJobId());
// igk_wln_e("data ", $basic, json_encode($lb, JSON_PRETTY_PRINT));
function igk_db_column_list(ModelBase $model, $prefix=null, $filter=null){
    $keys = array_keys($model->getTableColumnInfo());
    $tkey = null;
    $keys = array_map(function($a)use($model, & $tkey, $prefix, $filter){
        if ($filter){
            $cond = ($filter instanceof Closure) && ($filter($a, $model));
            $cond = $cond || (is_array($filter) && in_array($a, $filter));
            $cond = $cond || (is_string($filter) && preg_match($filter, $a));
            if ($cond) return;
        }
        $t = $model::column($a);
        $v = null;
        if ($prefix instanceof Closure){
            $v = $prefix($t);
        }else if (is_string($prefix) && (strlen($prefix = trim($prefix))>0)){
            $v = $prefix.$a;
        } else if (is_array($prefix) && key_exists($a, $prefix)){
            $v = $prefix[$a];
        }
        if ($v)
            $tkey[$t] = $v; 
        else 
            $tkey[] = $t;
    }, $keys);
    return $tkey;
}
function igk_db_only_column_regex($column){
    if (is_array($column)){
        $column = implode("|", $column);
    }
    return sprintf("/\b(?!%s)\b[\w][\w\d_]*\b/i", $column);
}
// print_r(DbUtils::ModelColumns(Jobs::model(), Jobs::FD_DESCRIPTION, Jobs::FD_TITLE))
// ;
// igk_wln("after ", Jobs::columnOnlyArray([]));
// igk_wln("after ", Jobs::columnOnlyArray(['prefix'=>function($id){
//     //igk_wln_e("base ", $id);
//     return igk_getv([
//         Jobs::FD_CONTRACT_ID=>'idPC'
//     ], $id, $id);
// }, Jobs::FD_CONTRACT_ID]));
// exit;
$list = igk_db_column_list(Jobs::model(), 'jbc_', igk_db_only_column_regex("id")); //::columns("jbx", "id");
$q = JobForemJobs::prepare(JobForemJobs::table())
// ->with(Jobs::table())
->join($lb)
// ->join([Jobs::table()=>[Jobs::FD_ID]])
// ->join([...JobForemJobs::joinOnJobId(Jobs::joinTargetId())])
->columns(
    array_merge(
        Jobs::columnList('j_', DbUtils::OnlyColumnFilterRegex(implode("|", [Jobs::FD_ID,
         Jobs::FD_TITLE,
         Jobs::FD_USER_ID]))),
        // JobForemJobs::columnList('jf_', "/Create_At/"),
        JobForemJobs::columnOnlyArray(['prefix'=>'santa_', JobForemJobs::FD_ID, JobForemJobs::FD_REFERENCE])
    )
)
->where([Jobs::column(Jobs::FD_USER_ID)=>1]);
$g = Jobs::columnSelectArray(Jobs::FD_CREATE_AT, Jobs::FD_PRINTABLE);
$c = $q->query_fetch(); 
// with fech do 
foreach($c as $row){
    $ids[]= $row['j_id']; 
}
// foreach($q->execute()->getRows() as $row){
//     igk_wln("row ", $row['j_id']);
// }
$r=null;
//$r =$q->execute()->to_json(null, JSON_PRETTY_PRINT);
igk_wln_e("basic", $r );