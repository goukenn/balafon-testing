<?php
// save to database 
// @command: balafon --run .test/auth/check_webauth.php
use IGK\System\IO\Path;
use lbuchs\WebAuthn\Binary\ByteBuffer;
use lbuchs\WebAuthn\WebAuthn;
require_once __DIR__.'/init-auth.php';
$data = json_decode(file_get_contents('/Volumes/Data/wwwroot/core/Projects/app_test/Views/auth/webauth.server2.json'), true);
$registerClientDataJSON = igk_getv($data['response'], 'clientDataJSON');
$registerAttest= igk_getv($data['response'], 'attestationObject');
// fake challenge validation 
$challenge = ($clientDataJSONData = json_decode(base64_decode($registerClientDataJSON), true))['challenge'];
$l = ByteBuffer::fromBase64Url($challenge)->getBinaryString();
$challenge = $l;
// $nAttest = ByteBuffer::fromBase64Url(base64_decode($registerAttest))->getBinaryString();
$nAttest = base64_decode($registerAttest);
// igk_wln_e($challenge, $l, (new ByteBuffer(base64_decode($challenge)))->getBinaryString());
$data = $webauth->processCreate(base64_decode($registerClientDataJSON), 
$nAttest, $challenge,
 true, true, false);
igk_io_w2file(Path::Combine(__DIR__, 'out.server.txt'), serialize($data));