<?php
// @command: balafon --run .test/bet/napoleon_extract_firefox_cookies.php
// firefox folder   
use IGK\Helper\IO;
use IGK\System\Console\Logger;
class MozCacheRow
{
    /**
     * @var number
     */
    var $creationTime;
    /**
     * @var number
     */
    var $expiry;
    /**
     * @var string
     */
    var $host;
    /**
     * @var number
     */
    var $id;
    /**
     * @var number
     */
    var $inBrowserElement;
    /**
     * @var number
     */
    var $isHttpOnly;
    /**
     * @var number
     */
    var $isPartitionedAttributeSet;
    /**
     * @var number
     */
    var $isSecure;
    /**
     * @var number
     */
    var $lastAccessed;
    /**
     * @var string
     */
    var $name;
    /**
     * @var string
     */
    var $originAttributes;
    /**
     * @var string
     */
    var $path;
    /**
     * @var number
     */
    var $rawSameSite;
    /**
     * @var number
     */
    var $sameSite;
    /**
     * @var number
     */
    var $schemeMap;
    /**
     * @var number
     */
    var $value;
}
$cf = __DIR__ . '/sampl.sqlite';
if (file_exists($cf)) {
    unlink($cf);
}
// open firefox : 
// hit : about:profiles 
// select the current active profile
// found find : cookies.profilie
$cf = '/Users/charlesbondjedoue/Library/Application Support/Firefox/Profiles/yx5rd8i9.default-release-1702400894716/cookies.sqlite';
$ad = igk_get_data_adapter('sqlite3');
$rows = null;
$site = 'napoleonsports';
if ($ad->connect($cf)) {
    // $b = $ad->createTable('users', [
    //     'id'=>(object)['clType'=>'int'],
    //     'login'=>(object)['clType'=>"varchar(40)"],
    // ]);
    // $ad->insert('users',['id'=>1, 'login'=>'cbondje@igkdev.com']);
    // $ad->insert('users',['id'=>2, 'login'=>'bondje.doue@igkdev.com']);
    $rows = $ad->select_all('moz_cookies', ['host like \'%'.$site.'%\'']);
    $tab = $rows ? array_merge(...array_map(function($i){
        $i = (object)$i;
        return [$i->name=>$i->value];
    }, $rows)) : [];
    Logger::print('site cookies');
    if($tab){
        $tab = (array)igk_extract_obj($tab, 'superbetLocale|aws-waf-token|f3k2kqs7xc|ct-prod-bcknd');
    }
    $r = igk_sys_cookies_build($tab);
    echo implode("\n", [$r,  ' ----- ',
    json_encode($tab)]), PHP_EOL;
    // $tables = $ad->listTables()->fetch_all();
    // igk_wln(json_encode($tables));
    $ad->close();
}
igk_wln_e('end...');
$cp = igk_getv($params, 0) ?? getcwd();
$path = realpath($cp);
$files = IO::GetFiles($path, '/\.lite/i', true);
igk_wln_e($path, $files);