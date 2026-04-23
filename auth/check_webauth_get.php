<?php
// @command: balafon --run .test/auth/check_webauth_get.php
use IGK\System\IO\Path;
use lbuchs\WebAuthn\Binary\ByteBuffer;

$ctrl = AppTestProject::ctrl(true);
$_view_dir = $ctrl->getViewDir();
$sfile = $_view_dir .'/auth/webauth.data.json';
$pfile = $_view_dir .'/auth/webauth.server2.json';
$v_fdata = Path::CombineAndFlattenPath(__DIR__,"./out.server.txt" );
$data = [];
if (file_exists($v_fdata))
    $data = unserialize(file_get_contents($v_fdata));
else {
    igk_die('missing data ./out.server.txt');
}
$publicKeys = igk_getv($data, 'credentialPublicKey');
require_once __DIR__.'/init-auth.php';
$args = $webauth->getGetArgs([$data->credentialId],30);
igk_wln_e(json_encode($args, JSON_PRETTY_PRINT));
$client = json_decode(file_get_contents($sfile), true);
$response = $client['credentials']['response'];
$challenge = ($clientDataJSONData = json_decode(base64_decode($response['clientDataJSON']), true))['challenge'];
$l = ByteBuffer::fromBase64Url($challenge)->getBinaryString();
$challenge = $l;
$server_r = json_decode(file_get_contents($pfile), true)['response'];
$gp = $server_r['clientDataJSON'] == igk_getv($response,'clientDataJSON');
echo $server_r['clientDataJSON'] , PHP_EOL;
echo $response['clientDataJSON'] , PHP_EOL;
$get_data = $webauth->processGet(
    base64_decode($response['clientDataJSON']),
    base64_decode(igk_getv($response,'authenticatorData')),
    base64_decode(igk_getv($response,'signature')),
    $publicKeys,
    $challenge,
);
igk_wln_e('data ', $data, $publicKeys, $get_data);