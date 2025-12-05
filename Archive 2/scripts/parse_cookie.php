<?php
// @command: balafon --run .test/scripts/parse_cookie.php
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
$src = 'OptanonConsent=isGpcEnabled=0&datestamp=Fri+Mar+21+2025+12%3A59%3A57+GMT%2B0100+(Central+European+Standard+Time)&version=202411.2.0&browserGpcFlag=0&isIABGlobal=false&hosts=&consentId=95923be2-4a58-4a7b-a0bd-36a487629181&interactionCount=1&isAnonUser=1&landingPath=NotLandingPage&groups=C0004%3A1%2CC0003%3A1%2CC0002%3A1%2CC0001%3A1&intType=1; OptanonAlertBoxClosed=2025-03-21T11:59:57.069Z; _gcl_au=1.1.807202830.1742558397; _ga=GA1.1.825248162.1742558382; _ga_4ZGNLMBD6W=GS1.1.1742558382.1.1.1742558493.33.0.0;…TcyNS1iYWI5LTYyZGRlMTMyZDc0ODotMSIsImVjIjoiNCIsInB2IjoiMSIsInRpbSI6ImUxNjJjYTRlLTFjOWItNTcyNS1iYWI5LTYyZGRlMTMyZDc0ODoxNzQyNTU4NDA0MzU1Oi0xIn0=; _fbp=fb.1.1742558414279.4408902294032681; aws-waf-token=c5d296c4-ee50-4f47-84c1-909ece6aecce:DAoApc5T5XILAAAA:yWVssdmSHWKiSGsBtNLZwX/3PVi94G5ccL6+I7f74zSpQGl+G1X5LMQC1pdXZMc4s1Gc0xmN1B0MWMzNGu3P2/3a4r+/4eBxB2XEQafYnbsEwS5NbBNbT/uI+ReNyFpsD4W7R4ymlLLxK5SYU6EB4CMVQZrqbf0GxIutqihSb+jRjxWT+HL4i2urk1/31lCTCTM=; ct-prod-bcknd=34db63bf-7757-4565-88db-45b0b733e916|8200798';
$src = 'dataSample=1';
$container = new RegexMatcherContainer;
$container->match(';| |&|=', 'split');
$container->begin('(?i)[a-z0-9\-]+', '(?=(?:;|=))', 'names');
// $container->begin('=', '(?=;|=)', 'names');
$tab = [];
$container->treat($src, function($g, & $pos) use (& $tab){
    switch($g->tokenID){
        case 'names':
            // Logger::print($g->value);
            $tab[$g->value] = 1;
            break;
    }
});
print_r($tab);
igk_exit();