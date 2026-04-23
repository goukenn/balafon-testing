<?php
// @author: C.A.D. BONDJE DOUE
// @filename: address_parser.php
// @date: 20250827 08:42:56
// @desc: address parser 
// @command: balafon --run .test/text/regex/address_parser.php address
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;

$src = igk_getv($params, 0);
$regex = new RegexMatcherContainer;
$fpcode = $regex->match('\\d{3,5}', 'postalcode')->last();
$fstreet_name = $regex->createPattern(['match'=>'(.+?)(?=\\d|,)', 'tokenID'=>'streetname']); 
$fcity_name = $regex->createPattern(['match'=>'(.+?)(?=$)', 'tokenID'=>'city']); 
$fcountry = $regex->createPattern(['match'=>'(?<=,)(.+?)(?=$)', 'tokenID'=>'country']); 
$addr = $regex->begin('^\\s*\\d+(?:(?:\/|-)[a-zA-Z0-9]+)?', '$', 'address-with-number')->last();
$addr->patterns = [
    $fpcode,
    $fstreet_name,
    $fcity_name,
    $fcountry 
];
$fnumber = $regex->createPattern(['match'=>'\\d+(?:(?:\/|-)[a-zA-Z0-9]+)?', 'tokenID'=>'number']);
$addr = $regex->begin('^(.+?)(?=\\d)', '$', 'address-with-street-name')->last();
$addr->patterns = [
    $fpcode,
    $fnumber,
    $fcountry,
    $regex->createPattern(['match'=>'[a-zA-ZÀ-ÿ\\s\\-]+', 'tokenID'=>'city']),
];
$address = (object)[];
$pos=0;
$handler = [
    'country'=>function($e, $a){ $a->country = ucfirst(trim($e->value));}
];
while($g = $regex->detect($src, $pos)){
    if ($e = $regex->end($g, $src, $pos)){
        $tid = $e->tokenID;
        Logger::info('tokenID:'.$tid);
        switch($tid){
            case 'number':
                $address->number = is_numeric($e->value) ? intval($e->value) : $e->value;
                break;
            case 'postalcode':
                $address->postalcode = intval($e->value);
                break;
            case 'streetname':
                $address->streetname = trim($e->value);
                break;
            case 'city':
                $address->city = ucfirst(strtolower(trim($e->value)));
                break;
            case 'address-with-number':
                $address->number = $e->beginCaptures[0][0];
                break;
            case 'address-with-street-name':
                $address->streetname = trim($e->beginCaptures[0][0]);
                break;
            default:
                if (isset($handler[$tid])){
                    $handler[$tid]($e, $address);
                }
                break;
        }
    }
}
igk_wln_e(json_encode($address, JSON_UNESCAPED_UNICODE));