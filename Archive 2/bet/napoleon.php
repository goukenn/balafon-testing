<?php
// @command: balafon --run .test/bet/napoleon.php
use com\igkdev\projects\BetWithNapoleon\BetLoginInfo;
use com\igkdev\projects\BetWithNapoleon\BetNapoleonEndPointManager;
use com\igkdev\projects\BetWithNapoleon\BetSourceTypes;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
// x-amzn-waf-action
// OptanonConsent=isGpcEnabled=0&datestamp=Fri+Mar+21+2025+12%3A59%3A57+GMT%2B0100+(Central+European+Standard+Time)&version=202411.2.0&browserGpcFlag=0&isIABGlobal=false&hosts=&consentId=95923be2-4a58-4a7b-a0bd-36a487629181&interactionCount=1&isAnonUser=1&landingPath=NotLandingPage&groups=C0004%3A1%2CC0003%3A1%2CC0002%3A1%2CC0001%3A1&intType=1; OptanonAlertBoxClosed=2025-03-21T11:59:57.069Z; _gcl_au=1.1.807202830.1742558397; _ga=GA1.1.825248162.1742558382; _ga_4ZGNLMBD6W=GS1.1.1742558382.1.1.1742558446.11.0.0;…lMkZlbi1iZSUyRnNwb3J0LWJldHMlMkZiYXNrZXRiYWxsJTJGdG9kYXlcIixcImxwdFwiOlwiJUYwJTlGJThGJTgwJTIwQmV0JTIwb24lMjBCYXNrZXRiYWxsJTIwYXQlMjBOYXBvbGVvbiUyMEdhbWVzJTIwJUYwJTlGJThGJTg2XCIsXCJscHJcIjpcImh0dHBzOi8vd3d3Lmdvb2dsZS5jb21cIn0iLCJwcyI6IjAwNGEwNjU2LTQzNmYtNDlhOC04ZDMyLTFiODgxMGU5ZGNkZSIsInB2YyI6IjEiLCJzYyI6ImUxNjJjYTRlLTFjOWItNTcyNS1iYWI5LTYyZGRlMTMyZDc0ODotMSIsImVjIjoiNCIsInB2IjoiMSIsInRpbSI6ImUxNjJjYTRlLTFjOWItNTcyNS1iYWI5LTYyZGRlMTMyZDc0ODoxNzQyNTU4NDA0MzU1Oi0xIn0=; _fbp=fb.1.1742558414279.4408902294032681
// session_start(); // Start session to store authentication if needed
// // API URL
// $url = "https://api.web.production.betler.napoleonsports.be/api/v1/login";
// // Login data
// $data = [
//     "username" => "goukenn", // Replace with actual username
//     "password" => "nplBon@je1983"  // Replace with actual password
// ];
// // Convert data to JSON
// $jsonData = json_encode($data);
// // Initialize cURL
// $ch = curl_init($url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
// curl_setopt($ch, CURLOPT_HTTPHEADER, [
//     "Content-Type: application/json"
// ]);
// // Execute request
// $response = curl_exec($ch);
// $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// curl_close($ch);
// if ($httpCode === 200) {
//     echo "Login Successful: " . $response;
//     $_SESSION['auth_token'] = json_decode($response, true)['token']; // Store token in session
// } elseif ($httpCode === 202) {
//     echo "Login request received. Waiting for completion...";
//     // Poll the API until we get a 200
//     $retryCount = 0;
//     $maxRetries = 10;
//     $retryInterval = 3; // Wait 3 seconds before retrying
//     while ($retryCount < $maxRetries) {
//         sleep($retryInterval); // Wait before retrying
//         $retryCount++;
//         $ch = curl_init($url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         if (isset($_SESSION['auth_token']))
//         curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $_SESSION['auth_token']]);
//         $response = curl_exec($ch);
//         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         curl_close($ch);
//         if ($httpCode === 200) {
//             echo "Login successful after retry: " . $response;
//             break;
//         }
//     }
//     if ($httpCode !== 200) {
//         echo "Max retries reached, login not completed.";
//     }
// } else {
//     echo "Login failed with HTTP Code: $httpCode";
// }
// exit;
// $src = 'W;6.5.1;RaR7/ZMKqH/BVheMmF/edQ==;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';
// $g = explode(';', $src, 3);
// $rm = gzuncompress(base64_decode($g[2]));
// $rcm = '';//base64_decode($g[3]);
// igk_wln_e($rm, $rcm, $g);
$ctrl = BetWithNapoleonController::ctrl(true);
$g = new BetLoginInfo;
$g->password = 'nplBon@je1983';
$g->username = 'goukenn';
$g->clientSourceType = BetSourceTypes::Desktop_new;
$def_file = $ctrl->getDataDir()."/local.def.dat";
$cookie_file = __DIR__ . '/cookie.txt';
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
// echo json_encode($g);
// exit;
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
    'host:api.web.production.betler.napoleonsports.be', // host deny access on balafon
    'Access-Control-Request-Headers:content-type,x-aws-waf-token',
    'TE: trailers',
    'Access-Control-Request-Method: POST',
    'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:136.0) Gecko/20100101 Firefox/136.0'
];
$cookies = [];
$cookies_entries = null;
$bss = '';
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
                //'superbetLocale|aws-waf-token|f3k2kqs7xc'
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
//
$_options = [
    'COOKIEJAR' => $cookie_file,
    'COOKIEFILE' => $cookie_file,
    'TIMEOUT' => $v_timeout
];
while (true) {
    Logger::print('post uri :');
    // login first - 
    $response = igk_curl_post_uri($uri, json_encode($g), $_options, $_headers, false); //BetNapoleonEndPointManager')
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