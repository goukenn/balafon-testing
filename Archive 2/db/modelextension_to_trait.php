<?php
use IGK\Models\ModelBase;
use IGK\Models\ModelEntryExtension;
use IGK\System\ExtensionUtils;
$methods = [];
ExtensionUtils::LoadMethods($methods, ModelEntryExtension::class, ModelBase::class );
$sb = "";
ksort($methods);
foreach($methods as $name=>$t){
    list($cl, $n, $params) = $t;
    $cl = new ReflectionMethod(ModelEntryExtension::class, $name);
    $ret = "";
    $sb .= "/**\n* extension methods \n*/\n";
    $sb .= "public abstract static function ".$cl->getName()."(";
    $ep = "";
    foreach($params as $p){
        $type = "";
        $default = "";
        if ($p->hasType()){
            $ct = $p->getType()->getName();
            if (!IGKType::IsPrimaryType($ct))
                $ct = "\\".$ct;
            $type = $ct. " ";
        }
        if ($p->isVariadic()){
            $type.="...";
        }
        if ($p->isPassedByReference()){
            $type.="&";
        }
        if ($p->isDefaultValueAvailable()){
            if ($p->isDefaultValueConstant()){
                $default = " = ".$p->getDefaultValueConstantName();
            }else{
                 $default = " = ".(is_null($def=$p->getDefaultValue())?'null': 
                 (is_bool($def) ? ($def? 'true' : 'false'):
                  $def));
            }
        }
        $sb.= $ep.$type.'$'.$p->getName().$default;
        $ep = ", ";
    }
    $cl->getParameters();
    if ($cl->hasReturnType()){
        $ret.= ": ".$cl->getReturnType();
    }
    $sb.=")".$ret.";".PHP_EOL;
}
echo $sb;