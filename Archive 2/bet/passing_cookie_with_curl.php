<?php
// @author: C.A.D. BONDJE DOUE
// @filename: passing_cookie_with_curl.php
// @date: 20250322 15:58:27
// @desc: passing cookies to curl 
// @command: balafon --run .test/bet/passing_cookie_with_curl.php
$uri = 'https://local.com:7300/bet/session';
$_options = [
    'COOKIE'=>implode('', ['sample=555; Secure;', 'guide=true; Secure;'])
];
$_headers = [];
$c = igk_curl_post_uri($uri, null, $_options, $_headers);
igk_wln_e("response : ", $c);
// {"sample":"555","guide":"true"}