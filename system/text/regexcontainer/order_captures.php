<?php
// @command: balafon --run .test/system/text/regexcontainer/order_captures.php
use IGK\System\Console\Logger;
use IGK\System\Text\RegexCaptureInfo;
use IGK\System\Text\RegexTreatCapture;

$l = "ab=cordination abbc acbba";
$regex = '/(a(?P<name>b)(=))(cord(ination|onnée))/';
$regex = '/(a(?P<name>b)(=(cor)di(na)ti(?P<onlist>on)))/';
preg_match($regex, $l, $tab, PREG_OFFSET_CAPTURE, 0);
if (!function_exists('igk_regex_order_captures')) {
    /**
    * auto generate doc.
    * @param mixed $captures
    * @return mixed
    */
function igk_regex_order_captures($captures)
    {
        return RegexTreatCapture::OrderCaptures($captures);
    }
}
if (!function_exists('igk_regex_treat_capture')) {
    /**
    * auto generate doc.
    * @param string $source_value
    * @param int $offset
    * @param mixed $capture_info
    * @param mixed $capture
    * @param mixed $callable
    * @return void
    */
    function igk_regex_treat_capture(string $source_value, int $offset, $capture_info, $capture, $callable) {
        return RegexTreatCapture::TreatCapture($source_value, $offset, $capture_info, $capture, $callable);
    }
}

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
    * auto generate doc.
    * @var \IGK\System\Text\IRegexCaptureInfo $cap
    */  
        $n = igk_create_node('div');
        $n['class'] = 'card card-'.$cap->value;
        $n->text('@'.$cap->value);
        return $n->render();
    }
]);
$v = $l ? $l->treat() : ''; 
Logger::info('done : ' . $v);
igk_exit();