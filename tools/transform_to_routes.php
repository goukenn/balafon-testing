<?php
use igk\js\Vue3\Libraries\VueRouter;
use IGK\System\Http\RequestResponse;
use IGK\System\IO\Path;
use IGK\System\IO\StringBuilder;
use \igk\js\common\JSExpression;

/**
* auto generate doc.
*/
class VueRouteResponse extends RequestResponse
{
    /**
    * auto generate doc.
    */
    public function render()
    {
    }
}
/**
* auto generate doc.
*/
class AppAction
{
    /**
    * auto generate doc.
    */
    public function users()
    {
    }
    /**
    * auto generate doc.
    * @param int $id
    * @return VueRouteResponse
    */
    public function list_get(int $id = 8)
    {
    }
    /**
    * auto generate doc.
    */
    public function list()
    {
    }
    /**
    * auto generate doc.
    * @param int $id
    */
    public function list_post(int $id = 8)
    {
    }
}
/**
* auto generate doc.
*/
class VueRouterInfo
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
    var $verb;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $description;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $path;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $component;
}
/**
* auto generate doc.
*/
class VueSFCHelper
{
    /**
    * auto generate doc.
    * @param string $class_name
    * @param mixed $path
    * @return ?array
    */
    public static function BuildRouteFrom(string $class_name, $path = '/'): ?array
    {
        $d = igk_sys_reflect_class($class_name);
        if (!($methods = $d->getMethods(ReflectionMethod::IS_PUBLIC))) {
            return null;
        }
        $v_otab = [];
        $a = $class_name;
        foreach ($methods as $method) {
            if ($method->isAbstract()) {
                continue;
            }
            $ref = $method;
            $method = $ref->getName();
            $comment = $ref->getDocComment();
            $info = new VueRouterInfo;
            $verbs = "get";
            if (preg_match("/_(?P<verb>(get|post|option|delete|put|store))$/", $method, $tab)) {
                $verbs = $tab['verb'];
                $method = igk_str_rm_last($method, '_' . $verbs);
            }
            $info->description = "description of ... " . $method;
            $info->name = $method;
            $info->path = $path . $info->name . self::GetArgs($ref);
            $info->verb = $verbs;
            if ($method == 'index')
                $method = null;
            $info->component = sprintf('defineAsynComponent(/* */()=>import("%s"))', Path::Combine($path, $info->name));
            $key = $class_name."/".$info->name;
            if ($key){
                if (isset($v_otab[$key])){
                    $key .= '_'.$verbs;
                }
            }
            $v_otab[$key] = $info;
        }
        return $v_otab;
    }
    /**
     * retrieve default args methods 
     * @param ReflectionMethod $meth 
     * @return null|string 
     */
    public static function GetArgs(ReflectionMethod $meth): ?string
    {
        if ($g = $meth->getParameters()) {
            $sb = [];
            foreach ($g as $key => $value) {
                $type = null;
                $typen = null;
                $primary = false;
                if ($value->hasType()) {
                    $type = $value->getType();
                    $typen = $type->getName();
                    if (!($primary = IGKType::IsPrimaryType($typen)) && IGKType::IsInjectable($type->getName())) {
                        continue;
                    }
                }
                $s = ':' . $value->getName();
                if ($primary) {
                    switch (strtolower($typen)) {
                        case 'int':
                            $s .= '(\\\\d+)';
                            break;
                        case 'float':
                            $s .= '(\\\\d+(.\\\\d+)?)';
                            break;
                    }
                }
                if ($value->isOptional()) {
                    $s .= '?';
                } else {
                    if ($value->isVariadic()) {
                        if ($value->isDefaultValueAvailable()) {
                            $s .= '*';
                        } else {
                            $s .= '+';
                        }
                    }
                }
                $sb[] = $s;
            }
            if ($sb)
                return '/' . implode("/", $sb);
        }
        return null;
    }
}
$path = "/";
$routes = VueSFCHelper::BuildRouteFrom(AppAction::class, $path);
$router = new VueRouter();
foreach($routes as $r){
    $route = $router->addRoute(
        $r->path,        
        [
            'component'=> JSExpression::Litteral($r->component)
        ]
    );
    $route->name = $r->name;
}
echo $router->render();
print_r($routes);
igk_exit();