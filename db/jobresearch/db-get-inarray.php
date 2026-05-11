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
igk_wln("candidate to join filter: ", $infos,"");
Jobs::registerMacro("joinOnJobId", function($call){
    $cl = static::class;
    $rt = $cl::column($cl::FD_ID);
    $c = [];
    if ($call){
        $c[] = $rt."=".$call;
    }
    return [$cl::table()=>[$rt."=".$call]]; 
});
JobForemJobs::registerMacro("targetOnJobId", function(){
    $cl = static::class;
    return $cl::column(JobForemJobs::FD_JOB_ID);
});
$lb = Jobs::joinOnJobId(JobForemJobs::targetOnJobId());
/**
* auto generate doc.
* @param ModelBase $model
* @param null|mixed $prefix
* @param null|mixed $filter
* @return mixed
*/
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
/**
* auto generate doc.
* @param mixed $column
* @return mixed
*/
function igk_db_only_column_regex($column){
    if (is_array($column)){
        $column = implode("|", $column);
    }
    return sprintf("/\b(?!%s)\b[\w][\w\d_]*\b/i", $column);
}
$list = igk_db_column_list(Jobs::model(), 'jbc_', igk_db_only_column_regex("id")); 
$q = JobForemJobs::prepare(JobForemJobs::table())
->join($lb)
->columns(
    array_merge(
        Jobs::columnList('j_', DbUtils::OnlyColumnFilterRegex(implode("|", [Jobs::FD_ID,
         Jobs::FD_TITLE,
         Jobs::FD_USER_ID]))),
        JobForemJobs::columnOnlyArray(['prefix'=>'santa_', JobForemJobs::FD_ID, JobForemJobs::FD_REFERENCE])
    )
)
->where([Jobs::column(Jobs::FD_USER_ID)=>1]);
$g = Jobs::columnSelectArray(Jobs::FD_CREATE_AT, Jobs::FD_PRINTABLE);
$c = $q->query_fetch(); 
foreach($c as $row){
    $ids[]= $row['j_id']; 
}
$r=null;
igk_wln_e("basic", $r );