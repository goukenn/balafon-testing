<?php
// @author: C.A.D. BONDJE DOUE
// @filename: command-tube-reception.php
// @date: 20250905 11:39:56
// @desc: script to handle tbe command 
// @command: | balafon --run .test/scripts/command-tube-reception.php
// @balafon-command: tube
// @balafon-example: balafon --find src/application/Projects '\.jp(e)?g$' | balafon --run .test/scripts/command-tube-reception.php shell rm  
use IGK\Helper\IO;
use IGK\System\Console\App; 
use IGK\System\Console\Logger;
use IGK\System\IO\Path;

$is_debug = igk_is_debug('cli-tube'); 
!defined('IGK_FRAMEWORK') && igk_die('missing framework');
if (!isset($command)) {
    igk_die('$command is missing');
}
/**
* auto generate doc.
* @param callable $r
* @return void
*/
function _treat_std_in(callable $r)
{
    $sb = '';
    if ($c = fopen(IO::STDIN_STREAM, 'r')) {
        while (!feof($c)) {
            if (false !== ($tc = fread($c, 4096))) {
                $sb .= $tc;
                if (strlen($tc) < 496) {
                    $r($sb);
                    $sb = '';
                }
            }
        }
        fclose($c);
    }
    if ($sb) {
        $r($sb);
    }
}
$invocation_command = [
    'default' => function (...$params) {
        ($gdks_dir = constant('IGK_GKDS_FILE_DIR')) || igk_die('IGK_GKDS_FILE_DIR constant must be set');
        $c = fopen(IO::STDIN_STREAM, 'r');
        $sb = '';
        IO::CreateDir($of = $gdks_dir); 
        Logger::info('move to gdks files: '.$of);
        $dlib = getenv('IGK_SITE_DEV_DIR').'/src/application';
        if ($c) {
            while (!feof($c)) {
                if (false !== ($tc = fread($c, 4066))) {
                    foreach (explode("\n", $tc) as $k) {
                        if (!$k || !is_file($k)) {
                            continue;
                        }
                        $g = igk_str_rm_start($k, $dlib);
                        $opath = Path::Combine($of, $g);
                        IO::CreateDir(dirname($opath));
                        rename($k, $p = $opath);
                        Logger::info('store: ' . $p);
                    }
                }
            }
            fclose($c);
        }
        igk_wln("writing: ", $sb);
    },
    'unlink' => function (...$params) {
        $sb = '';
        if ($c = fopen(IO::STDIN_STREAM, 'r')) {
            while (!feof($c)) {
                if (false !== ($tc = fread($c, 4066))) {
                    $sb .= $tc;
                }
            }
            fclose($c);
        }
        $rm = 0;
        foreach (explode("\n", $sb) as $l) {
            if (empty($l) || ! file_exists($l)) {
                continue;
            }
            @unlink($l);
            $rm++;
        }
        Logger::info('removed: ' . $rm);
    },
    'shell' => function (?string $exec) {
        $exec = implode(' ', func_get_args());
        _treat_std_in(function (string $v) use ($exec) {
            foreach (explode("\n", $v) as $l) {
                if (empty($l)) continue;
                $c = escapeshellarg($l);
                echo shell_exec("$exec $c");
            }
        });
    },
    'sys' => function (?string $exec) {
        if (function_exists($exec)) {
            _treat_std_in(function (string $v) use ($exec) {
                foreach (explode("\n", $v) as $l) {
                    echo call_user_func_array($exec, [$l]);
                }
            });
        }
    },
    'zip' => function (?string $outfile, ?string $entry_dir=null){
        !$outfile && igk_die('required outfile');
        $zip = new ZipArchive();
        if ($zip->open($outfile, ZipArchive::CREATE | ZipArchive::OVERWRITE)){
            _treat_std_in(function (string $v) use ($zip, $entry_dir) {
                foreach (explode("\n", $v) as $l) {
                    if (is_file($l)){
                        $en = $l;
                        if ($entry_dir){
                            $en = igk_str_rm_start($l, $entry_dir, 1);
                        }
                        $zip->addFile($l, $en);
                    };
                }
            });
            $zip->close();
        }
    }
];
if (igk_getv($command->options, '--help')) {
    Logger::info('usage command - param info');
    $helps = [
        'unlink' => 'unlink list of files',
        'shell'  => 'exec shell command',
        'sys'    => 'passing string to system function',
        'zip'    => 'zip archive',
    ];
    foreach (array_keys($invocation_command) as $c) {
        Logger::print(App::Gets(App::GREEN, $c));
        $desc = igk_getv($helps, $c);
        if ($desc) {
            Logger::print("\t" . $desc);
        }
    }
    igk_exit();
}
$interactive = $command->app->isInteractive();
if ($interactive === true) {
    fwrite(STDERR, "No piped input. Usage: producer | php script.php\n");
    igk_exit(1);
}
$fc = igk_getv($params, 0, 'default');
if ($r = igk_getv($invocation_command, $fc)) {
    $r(...array_slice($params, 1));
}
igk_exit();