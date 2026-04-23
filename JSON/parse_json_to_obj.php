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

/**
* auto generate doc.
*/
class PHPDevPackageObj extends ArrayList implements JsonSerializable
{
    use JSONArraySerializableAllTrait;
}
/**
* auto generate doc.
*/
class PHPDevPackageObjTypeConverter extends JSONTypeConverterBase{
    /**
    * auto generate doc.
    * @param mixed $value
    */
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
/**
* auto generate doc.
*/
class LColorTypeConverter extends JSONTypeConverterBase{
    /**
    * auto generate doc.
    * @param mixed $value
    */
    public function convertFrom($value) {
        $cl = new LColor;
        if (is_object($value)){
            $cf = explode("|", "red|green|blue|alpha");
            list($red, $green, $blue, $alpha) = igk_extract($value, $cf);
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
/**
* auto generate doc.
*/
class LColor{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $red;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $green;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $blue;
}
/**
 * 
 */
/**
* auto generate doc.
* @package test
* @author C.A.D. BONDJE DOUE
*/
class PHPObj implements JsonSerializable
{
    /**
    * auto generate doc.
    * @var PHPDevPackageObj
    */
    var $devPackages;
    /**
    * auto generate doc.
    */
    var $colors;
    /**
     * 
     * @var string
     */
    /**
    * auto generate doc.
    * @var igk\jsonParser\JSONVersion
    */
    /**
     * require definition 
     * @var string[]
     * @DecodeAs(string[])
     */
    /**
    * auto generate doc.
    * @var mixed
    */
   use JSONInstanceVarSerializableSkipNullTrait;
}
/**
* auto generate doc.
*/
class JUserTypeConverter extends JSONTypeConverterBase
{
    /**
    * auto generate doc.
    * @param mixed $value
    */
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
* auto generate doc.
* @package 1
*/
class JUser implements JsonSerializable
{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $name;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $firstname;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $age;
    use JSONInstanceVarSerializableSkipNullTrait;
}
$src = json_encode([
    "colors"=> null,
]);
$obj = JSONParser::Parse($src, PHPObj::class, [
    "strict" => true
]);
if ($obj instanceof PHPObj){
    if ($obj->colors){
    $obj->colors[0]->green +=60;
    }
}
igk_wln($obj, json_encode($obj));
$type = "...string";
$is_array = JSDecodeAnnotationHelper::IsRequestArray($type);
igk_wln("--- is array ? ---", $is_array);
$tm = (object)["z"=>0, "x"=>5, "y"=>9];
$ttm = (object)[];
$m = igk_extract_assoc($tm, explode('|', 'z|y|t'));
foreach($m as $k=>$v){
    $ttm->{$k} = $v;
}
$l = json_encode($ttm, JSON_PRETTY_PRINT);
$l = preg_replace("/(\").*\\1/","\e[0;32m\\0\e[0m", $l,1, $count);
echo $l;
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