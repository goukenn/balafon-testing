<?php
// @command: balafon --run .test/db/module.resolution.php
// relove 
use igk\authentications\WebAuthn\Database\WebAuthnDbUtility;
use igk\authentications\WebAuthn\Models\WebauthnAuthentications;
use IGK\Helper\JSon;
use IGK\Manager\ApplicationControllerManager;
use IGK\Models\Users;
// $m = ApplicationControllerManager::RetrieveControllerFromReference('#ref-module(igk/Balafon/AdvancedComponent)');
// $m = ApplicationControllerManager::RetrieveControllerFromReference('#ref-module("igk.Balafon.AdvancedComponent")');
// igk_wln_e("done", $m);
WebAuthnDbUtility::CreateAuthEntry(Users::select_row(['clLogin'=>'cbondje@igkdev.com']), 
'basic-fit', "local.com", "iiii");
igk_wln_e("lkjsdf", JSon::Encode(WebauthnAuthentications::select_all()));