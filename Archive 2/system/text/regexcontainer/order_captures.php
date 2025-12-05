<?php
// @command: balafon --run .test/system/text/regexcontainer/order_captures.php
// order regex categories by from/to logic
use IGK\System\Console\Logger;
use IGK\System\Text\RegexCaptureInfo;
use IGK\System\Text\RegexTreatCapture;
$l = "ab=cordination abbc acbba";
$regex = '/(a(?P<name>b)(=))(cord(ination|onnée))/';
$regex = '/(a(?P<name>b)(=(cor)di(na)ti(?P<onlist>on)))/';
preg_match($regex, $l, $tab, PREG_OFFSET_CAPTURE, 0);
// $c = RegexCaptureInfo::CreateFrom(['to'=>10,'pos'=>10]);
// igk_wln_e($c);
if (!function_exists('igk_regex_order_captures')) {
    function igk_regex_order_captures($captures)
    {
        return RegexTreatCapture::OrderCaptures($captures);
    }
}
if (!function_exists('igk_regex_treat_capture')) {
    /**
     * 
     * @param mixed $tab 
     * @param mixed $capture_info 
     * @param mixed $capture 
     * @param callable(string $v, ICaptureInfo $cap) $callable 
     * @return void 
     * @throws Exception 
     */
    function igk_regex_treat_capture(string $source_value, int $offset, $capture_info, $capture, $callable) {
        return RegexTreatCapture::TreatCapture($source_value, $offset, $capture_info, $capture, $callable);
    }
}
/*
$tl = array_shift($tab);
$capture_info = igk_regex_order_captures($tab);  
$capture = [
    2 => "meta.capture.render",
    // 5 => function ($cap, $callable=null) {
    //     $cap->data =  '[' . $cap->value . ']';
    // },
    'onlist' => function ($cap, $c) {
        $cap->data = '@'.$cap->value;
    },
    6 => function ($cap, $c) {
        $cap->data = '@@@'.$cap->value;
    }
];  
$l = igk_regex_treat_capture($tl[0], $tl[1], $capture_info, $capture, function ($cap, $info) {
    list($id, $patterns) = $info ? igk_extract($info, 'name|patterns') : [0, 0];
    $cap->data = '[*presentation*]';
}); 
*/
preg_match('/.+(?P<type>\\b(?:admin|user)\\b).+/', implode("\n", 
['presentation of : user plus info',
'mardi',
'mercredi']), $tab, RegexTreatCapture::REGEX_FLAG);
$l = RegexTreatCapture::CreateFromRegexResult($tab, [
    0=>function(string $v){
        return '<p>'.$v.'</p>';
    },
    'type'=>function($cap){
        /**
         * @var \IGK\System\Text\IRegexCaptureInfo $cap
         */  
        $n = igk_create_node('div');
        $n['class'] = 'card card-'.$cap->value;
        $n->text('@'.$cap->value);
        return $n->render();
    }
]);
$v = $l ? $l->treat() : ''; // treatRegex('/(?P<type>(admin|user)/', 'presentation of : admin');
Logger::info('done : ' . $v);
igk_exit();