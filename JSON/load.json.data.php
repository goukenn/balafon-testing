<?php
// @author: C.A.D. BONDJE DOUE
// @filename: load.json.data.php
// @date: 20250128 15:51:21
// @desc: check JSon::BindData to fullfill object class with json data
// @command: balafon --run .test/JSON/load.json.data.php 
use IGK\Helper\JSon; 
use IGK\System\IO\JSon\Annotations\JSonBindAsAnnotation as JSonBindAs;

include __DIR__ . '/TempOracle.php'; 
if (!class_exists('A', false)) {
    /**
    * auto generate doc.
    * @package test
    * @author C.A.D. BONDJE DOUE
    */
    /**
    * auto generate doc.
    * @package
    */
    class A
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
        var $title;
        /**
        * auto generate doc.
        * @var mixed
        */
        var $version;
        /**
        * auto generate doc.
        * @var mixed
        */
        var $cars;
        /**
        * auto generate doc.
        * @var mixed
        */
        var $local;
        /**
        * auto generate doc.
        * @var mixed
        */
        var $siri;
        /**
        * auto generate doc.
        * @var mixed
        */
        var $age;
        /**
        * auto generate doc.
        */
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
    "age":"4580",
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
JSon::BindData($c, $data);
igk_wln_e('done', $c); 