<?php
// @command: balafon --run .test/bet/napoleon.php
use com\igkdev\projects\BetWithNapoleon\BetLoginInfo;
use com\igkdev\projects\BetWithNapoleon\BetNapoleonEndPointManager;
use com\igkdev\projects\BetWithNapoleon\BetSourceTypes;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;

$ctrl = BetWithNapoleonController::ctrl(true);
$g = new BetLoginInfo;
$g->password = 'nplBon@je1983';
$g->username = 'goukenn';
$g->clientSourceType = BetSourceTypes::Desktop_new;
$def_file = $ctrl->getDataDir()."/local.def.dat";
$cookie_file = __DIR__ . '/cookie.txt';
/**
* auto generate doc.
* @param string $cookie_file
* @return mixed
*/
function load_cookie_file(string $cookie_file)
{
    $c = file_get_contents($cookie_file);
    $tab = explode("\n", $c);
    $o = [];
    while ((count($tab) > 0)) {
        $line = array_shift($tab);
        if (empty($line) || preg_match("/^# /", trim($line))) continue;
        $cf = preg_split("/\\s+/", $line);
        $v = array_pop($cf);
        $k =  array_pop($cf);
        $o[$k] = $v;
    }
    return $o;
}
$manager = new BetNapoleonEndPointManager;
$manager->bet_endpoint = 'https://local.com:7300/bet';
$manager->bet_endpoint = $ctrl->getConfigs()->napoleon_endpoint;
$uri = $manager->login();
$required_cookie_name = igk_getv($command->options, '--required-cookie-name') ?? 'ct-prod-bcknd';
$v_timeout = igk_getv($command->options, '--timeout') ?? -1;
$v_new = property_exists($command->options, '--new');
$_headers = [
    'accept-content: application/json',
    'Accept: application/json, text/plain, */*',
    'Accept-Language: en-GB,en;q=0.5',
    'Accept-Encoding: gzip, deflate, br, zstd',
    'Content-Type: application/json',
    'User-Agent:firefox',
    'Connection:keep-alive',
    'host:api.web.production.betler.napoleonsports.be', 
    'Access-Control-Request-Headers:content-type,x-aws-waf-token',
    'TE: trailers',
    'Access-Control-Request-Method: POST',
    'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:136.0) Gecko/20100101 Firefox/136.0'
];
$cookies = [];
$cookies_entries = null;
$bss = '';
/**
* auto generate doc.
* @param string $site
* @param null|mixed $cf
* @return mixed
*/
function get_firefox_cookie(string $site, $cf = null)
{
    $cf =  $cf ?? '/Users/charlesbondjedoue/Library/Application Support/Firefox/Profiles/yx5rd8i9.default-release-1702400894716/cookies.sqlite';
    $ad = igk_get_data_adapter('sqlite3');
    $rows = null;
    $r = null;
    if ($ad->connect($cf)) {
        $rows = $ad->select_all('moz_cookies', ['host like \'%' . $site . '%\'']);
        $tab = $rows ? array_merge(...array_map(function ($i) {
            $i = (object)$i;
            return [$i->name => $i->value];
        }, $rows)) : [];
        if ($tab) {
            $tab = (array)igk_extract_obj(
                $tab,
                'aws-waf-token|f3k2kqs7xc'
            );
        }
        $r = igk_sys_cookies_build($tab);
        $ad->close();
    }
    return $r;
}
$bss = get_firefox_cookie('napoleon');
if (file_exists($cookie_file)) {
    if (!$v_new) {
        $cookies_entries = load_cookie_file($cookie_file);
        if ($gprod = igk_getv($cookies_entries, $required_cookie_name)) {
            $_headers[] = 'Cookie: ' . $bss . ";" . $required_cookie_name . "=" . $gprod . ";";
        }
    }
    unlink($cookie_file);
}
if (!$cookies_entries) {
    if(file_exists($def_file)){
        $_headers[] = 'Cookie: '.file_get_contents($def_file);
        unlink($def_file);
    } else
        $_headers[] =
            'Cookie: ' . $bss . ";" . $required_cookie_name . "=" . igk_getv($params, 0);
}

$_options = [
    'COOKIEJAR' => $cookie_file,
    'COOKIEFILE' => $cookie_file,
    'TIMEOUT' => $v_timeout
];
while (true) {
    Logger::print('post uri :');
    $response = igk_curl_post_uri($uri, json_encode($g), $_options, $_headers, false); 
    $status = igk_curl_status();
    $inf = igk_curl_info();
    if ($status == 202) {
        sleep(1);
        continue;
    }
    break;
}
Logger::SetColorizer(new Colorize);
Logger::print(json_encode(compact('response', 'status'), JSON_PRETTY_PRINT));
igk_exit();