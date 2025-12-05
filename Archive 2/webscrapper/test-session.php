<?php
// @command: balafon --run .test/webscrapper/test-session.php
use IGK\System\Http\CurlHttpClient;
$client = new CurlHttpClient;
$client->session = true;
$buri = 'https://localhost:7300/testapi/handle_session';
$u = $client->request($buri);
igk_wln("response 1 : ".$u);
$u = $client->request($buri);
igk_wln("response 2 : ".$u);
$u = $client->request($buri);
igk_wln_e("response 3 : ".$u);