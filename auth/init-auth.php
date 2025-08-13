<?php
use lbuchs\WebAuthn\WebAuthn;
$rpName = 'igkdev (local.com)';
$rpID = 'local.com';
$webauth = new WebAuthn($rpName, $rpID);