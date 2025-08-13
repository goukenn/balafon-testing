<?php
use igk\devtools\DocumentParser\UriDetector;
use IGK\Mapping\PropertyMapper;
use IGK\System\Uri;
$file = '/tmp/notes.txt';
$g = file_get_contents($file);
$v_detector = new UriDetector;
$uris = $v_detector->match($g);
usort($uris, function($a,$b){
    return strcmp($a->uri, $b->uri);
});
 $tab = [];
array_map(function($u)use(& $tab){
    $c = new Uri($u->uri);
    if (empty($e = $c->getSiteUri())){
        if (($q = parse_url('//'.$u->uri)) && (isset($q['host']))){
            $tab['https://'.$q['host']] = 1;
            return;
        } 
        return;
    }
    $tab[$e] = 1;
}, $uris);
igk_wln(var_export(array_keys($tab), true));
exit;