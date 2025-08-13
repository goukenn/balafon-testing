<?php
// @command: balafon --run .test/db/jobresearch/foremjob-reverse-folder-to-definion.php folder
// - balafon --run .test/db/jobresearch/foremjob-reverse-folder-to-definion.php /Users/charlesbondjedoue/Desktop/jobs
use com\igkdev\projects\ForemJobDashboard\Database\Import\JobEntryImportInfo;
use com\igkdev\projects\ForemJobDashboard\Database\Import\JobImportInfo;
use IGK\Helper\IO;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
ForemJobDashboardController::ctrl(true);
function get_enterpise_info(string $dir, $date, &$enterpriseList)
{
    $load_ref = function ($es, &$enterpriseList, $date, $ent) {
        if (!isset($enterpriseList[$es])) {
            $inf = new JobImportInfo;
            $inf->enterprise = $es;
            $inf->title = $es;
            $inf->jobs = [];
            $enterpriseList[$es] = $inf;
        }
        $inf = $enterpriseList[$es];
        // + | load jobs 
        $entry = new JobEntryImportInfo;
        $entry->date = $date;
        if (file_exists($desc = Path::Combine($ent, "description.txt"))) {
            $entry->description = file_get_contents($desc);
        }
        if (file_exists($desc = Path::Combine($ent, "response.txt"))) {
            $entry->responses = file_get_contents($desc);
        } else if (file_exists($desc = Path::Combine($ent, "response.pdf"))) {
            $g = file_get_contents($desc);
            $type = IO::MimeTypeFromBuffer($g);
            if ($type == 'application/pdf') {
                $entry->responses = (object)[
                    'type' => $type,
                    'encode'=>'base64',
                    'content' => base64_encode($g)
                ];
            }
        }
        $inf->jobs[] = $entry;
    };
    foreach (IO::GetDirs($dir, null, false) as $ent) {
        $es = basename($ent);
        if (preg_match('/^forem/i', $es)) {
            // Logger::danger('forem job found');
            foreach (IO::GetDirs($ent, null, false) as $references) {
                $res = basename($references);
                $load_ref("Forem_" . $res, $enterpriseList, $date, $references);
            }
        } else {
            $load_ref($es, $enterpriseList, $date, $ent);
        }
    }
}
$year = "/\/\\d{4}$/";
$mount  = "/\/\\d{2}$/";
$day = "/\/\\d{2}$/";
$dir = igk_getv($params, 0);
$data = [];
if ($yp = IO::GetDirs($dir, $year, false)) {
    sort($yp);
    while (count($yp) > 0) {
        $m_dir = array_shift($yp);
        $ys = basename($m_dir);
        if ($mp = IO::GetDirs($m_dir, $mount, false)) {
            sort($mp);
            while (count($mp) > 0) {
                $s_dir = array_shift($mp);
                $ms = basename($s_dir);
                if ($lp = IO::GetDirs($s_dir, $day, false)) {
                    sort($lp);
                    foreach ($lp as $dp) {
                        $ds = basename($dp);
                        $date = sprintf('%s-%s-%s', $ys, $ms, $ds);
                        get_enterpise_info($dp, $date, $data);
                    }
                }
            }
        }
    }
}
$r = JSon::Encode(array_values($data), JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
igk_wln_e($r);