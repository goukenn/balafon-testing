<?php
// @command: balafon --run .test/forms/validate_file.php
use IGK\System\Html\Forms\Validations\FileValidator;
use IGK\System\Html\Forms\Validations\Annotations\FormFieldAnnotation as FormField;
use IGK\System\Html\Forms\Validations\InspectorFormFieldValidationBase;
use IGK\System\Http\Request; 
$json_data = <<<JSON
{

    "wRM002": {
        "name": "Simulator Screenshot - iPhone 16 Pro - 2024-11-20 at 12.19.25.png",
        "type": "image/png", 
        "tmp_name": "/tmp/phpcFfcHf",
        "error": 0,
        "size": 100
    }
}
JSON;
$json_data = <<<JSON
{

    "wRM002": {
        "name": "Simulator Screenshot - iPhone 16 Pro - 2024-11-20 at 12.19.25.png",
        "type": "image/png", 
        "tmp_name": "/tmp/phpcFfcHf",
        "error": 0,
        "size": 100
    }
}
JSON;
class DemoValidationField extends InspectorFormFieldValidationBase
{
    /**
     * 
     * @var mixed
     * @FormField(type=file, placeholder=choose - a file, accept=image/*, maxSize=300, required=1, multiple=true)
    */
    var $wRM002;
}
$r = json_decode($json_data);
$val = new DemoValidationField;
$request = Request::getInstance();
$_FILES = [
    'wRM002'=>(array)$r->wRM002
];
if ($val->validateFromRequest($request, $errors)){
    igk_wln_e("validated");
}else{
    igk_wln_e("error", igk_ob_get_func('print_r', [$errors]));
}
$ls = new FileValidator;
$l = $ls->validate($r->wRM002);
igk_wln_e("done", $l);