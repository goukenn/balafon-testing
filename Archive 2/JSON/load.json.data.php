<?php
// @author: C.A.D. BONDJE DOUE
// @filename: load.json.data.php
// @date: 20250128 15:51:21
// @desc: check JSon::BindData to fullfill object class with json data
// @command: balafon --run .test/JSON/load.json.data.php
use IGK\Helper\JSon;
use IGK\System\Annotations\PhpDocBlocReader;
use IGK\System\Helpers\AnnotationHelper;
use IGK\System\IO\JSon\Annotations\JSonBindAsAnnotation; 
include __DIR__ . '/TempOracle.php'; 
if (!class_exists('A', false)) {
    class A
    {
        /**
         * 
         * @var mixed
         * @JSonBindAsAnnotation(mixed, required=true)
         */
        var $name;
        var $title;
        /**
         * @JSonBindAsAnnotation(version)
         * @var mixed
         */
        var $version;
        /**
         * @JSonDecodeAs(\Cars[])
         * @var mixed
         */
        var $cars;
        /**
         * @JSonBindAsAnnotation(array)
         * @var mixed
         */
        var $local;
        /**
         * @JSonBindAsAnnotation(arrayOf<TempOracle>)
         * @var mixed
         */
        var $siri;
        function join()
        {
            return implode("\n", array_map(function($a){ return is_object($a) || is_array($a)? json_encode($a) : $a;},  (array)$this));
        }
    }
}
$src = <<<'JSON'
{
    "name":"alder",
    "title":"Master Chief Admin",
    "version":"1.0",
    "local":"sample",
    "siri":[{
        "name":"sampling"
    }, {
        "name":"indigo",
        "childs":{"name":"first childrend", "tag":"patching", "title":"hello"}
    }]
}
JSON;
$c = new A();
$data = json_decode($src);
// $c= (object)['version'=>null];
JSon::BindData($c, $data);
// JSonBindAsAnnotation::GetRequiredProperty(A::class);
igk_wln_e($c, $c->join());