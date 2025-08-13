<?php
class ViewHandler{
    private static $sm_instance;
    var $tab = [];
    var $attrbInd = false;
    public static function getInstance(){
        is_null(self::$sm_instance) && self::$sm_instance = new self;
        return self::$sm_instance;
    }
    private function __construct(){  
        $this->tab = ["class"=>null, "style"=>null];      
    }
    function attribString(){
        $s = ""; 
        foreach ($this->tab as $key => $value) {
            if (is_null($value))
                continue;            
            $s.= " ".$key.'="'.trim($value,'"').'"';
            $d = 1;
        }
        return $s;
    }
}
$__igk_attr__ = Closure::fromCallable(function($arr){
    if (key_exists("class", $arr)){
        $cl = trim($arr["class"] ?? "", '"');
        $tab = explode(" ", $this->tab["class"] ?? "");
        $carr = array_map(function($a)use(& $tab){
            if (strpos($a,'-')===0){
                // remove 
                $k = substr($a, 1);
                if ( ($index = array_search($k, $tab)) === false){
                    unset($tab[$index]);
                }
            }else{
                return $a;
            }
        }, explode(" ", $cl) );
        $arr["class"] = implode(' ', array_filter(array_merge($tab, $carr)));
    }
    $this->tab = array_merge($this->tab, $arr);
    $this->attribBind = true;
})->bindTo(ViewHandler::getInstance());
include_once "/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Lib/Classes/IGKObject.php";
include_once "/Volumes/Data/Dev/PHP/balafon_site_dev/src/application/Lib/igk/Lib/Classes/System/Html/Css/CssSession.php";
ob_start();
include("datac.php");
$c = ob_get_contents();
ob_end_clean();
$g = $c;
$g = str_replace('%__igk_attribute__%', ViewHandler::getInstance()->attribString(), $g);
echo $g;