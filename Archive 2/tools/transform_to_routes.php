<?php
use igk\js\Vue3\Libraries\VueRouter;
use IGK\System\Http\RequestResponse;
use IGK\System\IO\Path;
use IGK\System\IO\StringBuilder;
use \igk\js\common\JSExpression;
class VueRouteResponse extends RequestResponse
{
    public function render()
    {
    }
}
class AppAction
{
    public function users()
    {
    }
    /**
     * 
     * @param int $id 
     * @return VueRouteResponse 
     */
    public function list_get(int $id = 8)
    {
    }
    public function list()
    {
    }
    public function list_post(int $id = 8)
    {
    }
}
class VueRouterInfo
{
    var $name;
    var $verb;
    var $description;
    var $path;
    var $component;
}
// because routes in vue application consiste with path and component definitions with some extrat js definition a api
// route must return a js expression that render definition with template / setup script on possibility style 
class VueSFCHelper
{
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
            // filter parameter 
            // filter documents 
            $ref = $method;
            $method = $ref->getName();
            $comment = $ref->getDocComment();
            // if (empty($ref->getDocComment()))
            // {
            //     continue;
            // }
            $info = new VueRouterInfo;
            $verbs = "get";
            if (preg_match("/_(?P<verb>(get|post|option|delete|put|store))$/", $method, $tab)) {
                $verbs = $tab['verb'];
                $method = igk_str_rm_last($method, '_' . $verbs);
            }
            // $info->deprecated = true;
            $info->description = "description of ... " . $method;
            $info->name = $method;
            $info->path = $path . $info->name . self::GetArgs($ref);
            $info->verb = $verbs;
            if ($method == 'index')
                $method = null;
            // if (SwaggerGenerator::UpdateRefInfo($g, $ref, $info, $doc)) {
            //     $args = ltrim($info->getArgs(), '/');
            //     $doc->addPath(Path::Combine($page, $method, $args), $verbs, $info);
            // }
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
exit;