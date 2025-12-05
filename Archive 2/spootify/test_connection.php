<?php
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
$ctrl = bantubeatController::ctrl();
$ctrl::register_autoload();
$secret = $ctrl->getConfig("api/spotify/secret");
$clientid = $ctrl->getConfig("api/spotify/clientid");
$options = [
    'scope' => [
        'user-read-email',
    ],
];
$session = new Session(
    $clientid,
    $secret
);
$rgt = $session->getAuthorizeUrl($options);
$api = new SpotifyWebAPI();
$session->requestAccessToken(1);
$token = $session->getAccessToken();
$api->setAccessToken($token);
print_r($api->me());
igk_wln_e("secret", $secret, compact("secret", "clientid", "session"));