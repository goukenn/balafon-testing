<?php
// @command: balafon --run .test/swagger/pkce.php
// 1. register client 
use igk\docs\swagger\Database\SwaggerPKCEExtraData;
use igk\docs\swagger\SwaggerAccess;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\Models\Users;
// $u = Users::currentUser();
// $s = Users::checkLogin('cbondje@igkdev.com', 'adminBonaje123'); //
// $x = hash_equals('sample','Sample');
$date1 = "2025-01-02";
$date3 = "2026-01-01";
echo $date3 > $date1, PHP_EOL;
exit;
$j = SwaggerAccess::PKCELoginAndTokenResponse('cbondje@igkdev.com','adminBonaje123', 
'1ba45b705c2e755a498a2ddb06c52860',
'vntt4HUbBm3LXf6wCJp98n307Y6qSZ0V3IOWyPzxjQc',
null,
ForemJobDashboardController::ctrl(), null); 
igk_wln_e($j);
igk_wln_e(json_encode(compact('u','s', 'x')));
if (!($user = igk_get_user_bylogin('cbondje@igkdev.com'))){
    igk_die('missing user');
}
list($challenge_code,$challenge_code_method, $accessCode, $code_verifier)=igk_extract([],
'challenge_code|challenge_code_method|accesCode|code_verifier'); 
if ($def = SwaggerAccess::Check($user->clGuid)){
    if ($v_l = $def->extraDefinition()){
        $v_e = igk_getv($v_l->pkce, $accessCode);
        if ($v_e instanceof SwaggerPKCEExtraData){
            $v_e->code_challenge = $challenge_code;
            $v_e->code_method = $challenge_code_method;
            $v_e->from_ip = igk_server()->RemoteIp();
        }
        igk_wln_e(JSon::Encode($v_l->pkce, JSonEncodeOption::IgnoreEmpty()));
    }
}
//$def->data = [];
//$def->save();
//$def->update();
// SwaggerOAuth::PKCEAuthentication([]);
igk_wln_e($def->to_json());
// 2. drop client
//$def->delete();