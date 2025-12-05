<?php
// + | --------------------------------------------------------------------
// + | demonstration of how to transformat json data to a validated fields. 
// + | using module "igk/jsonParser
use igk\jsonParser\Annotations\JSDecodeAnnotationHelper;
use igk\jsonParser\Annotations\JSONTypeConverterBase;
use igk\jsonParser\JSONParser;
use JSDecodeAsAnnotation as DecodeAs;
use igk\jsonParser\Traits\JSONArraySerializableAllTrait;
use igk\jsonParser\Traits\JSONInstanceVarSerializableSkipNullTrait;
use IGK\System\Collections\ArrayList;
use igk\btmSyntax\Formatters;
class PHPDevPackageObj extends ArrayList implements JsonSerializable
{
    use JSONArraySerializableAllTrait;
}
class PHPDevPackageObjTypeConverter extends JSONTypeConverterBase{
    public function convertFrom($value) {
        $ref = new PHPDevPackageObj;
        if (is_object($value)){
            foreach($value as $k=>$v){
                if (is_int($k)) throw new IGKException("int key not allowed not allowed");
                $ref[$k] = $v;
            }
        }
        return $ref;
     }
}
class LColorTypeConverter extends JSONTypeConverterBase{
    public function convertFrom($value) {
        $cl = new LColor;
        if (is_object($value)){
            $cf = explode("|", "red|green|blue|alpha");
            list($red, $green, $blue, $alpha) = igk_extract($value, $cf);
            //extract(igk_extract($value, $cf));
            $tab  = compact(...$cf); 
            $g = array_fill_keys(array_keys(get_class_vars(get_class($cl))), 1);
            foreach( $tab as $k=>$v){ 
                if (!(isset($g[$k])) ||($v ===null)) continue;
                if (!is_numeric($v)){
                    throw new IGKException("value is not numeric");
                }
                $cl->{$k} = floatval($v); unset($g[$k]);
            }
        }
        return $cl;
    }
}
class LColor{
    var $red; 
    var $green;
    var $blue;
}
/**
 * 
 */
class PHPObj implements JsonSerializable
{
    /**
     * 
     * @var PHPDevPackageObj
     */
    var $devPackages;
    /**
     * 
     * @var ?LColor[]
     */
    var $colors;
    /**
     * 
     * @var string
     */
    //var $name;
    /**
     * 
     * @var igk\jsonParser\JSONVersion
     *
     */
    //var $version;
    /**
     * require definition 
     * @var string[]
     * @DecodeAs(string[])
     */
    //var $required;
    /**
     * 
     * @var mixed
     * @DecodeAs(...JUser)
     */
   // var $users;
   use JSONInstanceVarSerializableSkipNullTrait;
}
class JUserTypeConverter extends JSONTypeConverterBase
{
    public function convertFrom($value)
    {
        list($name, $firstname) = igk_extract($value, explode('|', 'name|firstname'));
        $u = new JUser();
        $u->firstname = $firstname;
        $u->name = $name;
        return $u;
    }
}
/**
 * 
 * @package 
 * @T_ypeConverter(JSUserTypeConverter)
 */
class JUser implements JsonSerializable
{
    var $name;
    var $firstname;
    var $age;
    use JSONInstanceVarSerializableSkipNullTrait;
}
$src = json_encode([
    "colors"=> null,
    // [
    //     ["red"=>20,
    //     "green"=>255,
    //     'blue'=>55]
    // ],
    // "devPackages" =>
    // (object)[
    //     "sample/data" => "local.com"
    // ],
    // "name" => "charles",
    // "version" => 3.2,
    // "users" => [
    //     (object)["name" => "c.", "firstname" => "Basic"],
    //     (object)["name" => "d."]
    // ]
]);
$obj = JSONParser::Parse($src, PHPObj::class, [
    "strict" => true
]);
if ($obj instanceof PHPObj){
    // $obj->devPackages['halo'] = '5';
    if ($obj->colors){
    $obj->colors[0]->green +=60;
    // $obj->colors[0]->blue = 52;
    }
}
igk_wln($obj, json_encode($obj));
// $doc = new ReflectionProperty(PHPObj::class, 'version');
// $comment = $doc->getDocComment();
// $p = null;
// if ($comment) { 
// $b = JSDecodeAnnotationHelper::Convert($obj, 'version');
//igk_wln_e("convert .... ", $b, $obj );
// }
//$p = AnnotationHelper::GetAnnotations($doc);
// igk_wln('comment:', $comment);
// igk_wln('p:', $p);
$type = "...string";
$is_array = JSDecodeAnnotationHelper::IsRequestArray($type);
igk_wln("--- is array ? ---", $is_array);
// $obj = new JSONVersion(3, 2); 
// igk_wln_e(igk_extract($obj, ['major', 'minor', 'basic']), $obj.'');
// igk_wln_e('------------', $obj);
$tm = (object)["z"=>0, "x"=>5, "y"=>9];
$ttm = (object)[];
$m = igk_extract_assoc($tm, explode('|', 'z|y|t'));
foreach($m as $k=>$v){
    $ttm->{$k} = $v;
}
$l = json_encode($ttm, JSON_PRETTY_PRINT);
// echo "\e[0;32m";
$l = preg_replace("/(\").*\\1/","\e[0;32m\\0\e[0m", $l,1, $count);
echo $l;
// echo "\e[0m";
$lib_src = <<<'JSON'
{
    "scopeName":"source.testing",
    "patterns":[
        {
            "begin":"(\").*\\1",
            "end":"\\1",
            "patterns":[

            ]
        }
    ]
}
JSON;
$formatter = Formatters::CreateFrom((object)json_decode($lib_src));
igk_wln_e("format: ", $formatter->format($src));