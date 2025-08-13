<?php
// balafon --run .test/ttre/treat_erral.php > .test/ttre/eural_code.json
$tcode = explode("\n", file_get_contents(__DIR__."/eural_code_list_2.txt"));
$data = [];
$key = null;
$value = '';
foreach($tcode as $l){
    if (preg_match("/^[0-9 ]+/", $l, $d)){
        if($key && $value){
            $data[$key] = $value; 
        }
        $key = str_replace ( ' ', '', $d[0]);
        $value = substr($l, strlen($d[0]));
        $tab = explode('-', $value);
        if (count($tab)>1){
            array_shift($tab);
            $value = igk_str_encode_to_utf8(trim(implode("-", $tab)));
        }
    } else{
        if ($key){
            $value .= $l;
        }
    }
}
if ($key && $value){
    $data[$key] = $value;
}
//\u813f => "à"
//\u00e0 => "à"
//\u00ee => "î"
//\u732b => "è"
//\u00ea => "ê"
//\u951a => "ê"
//\u2019 => "'"
//\u00e7 => "ç"
//\u83bd => "ç"
//\u00fb => "û"
//\u00f4 => "ô"
//\u00a0 => " "
//\u00ef => "ï"
//\u8302 => "ï"
//\u0153 => "oe"
igk_wln(json_encode((object)$data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit;