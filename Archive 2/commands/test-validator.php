<?php
// @author: C.A.D. BONDJE DOUE
// @filename: test-validator.php
// @date: 20230304 08:05:51
// @desc: 
// command: balafon --run .test/command/test-validator.php
use IGK\System\Http\Request;
use IGK\System\WinUI\Forms\FormData;
use IGK\System\WinUI\Forms\RequestValidatorBase;
class JumData extends FormData{
    var $x;
    // var $y;
    protected function getContentSecure(Request $request): ?array
    {
        return [
            "y"=>$request->getContentSecurity(self::SC_INTEGER)
        ];
    }
    /**
     * not required assoc array to bind
     * @return null|array 
     */
    public function getNotRequired(): ?array{
        return ["y"=>88];
    }
    /**
     * assoc of default custom value
     * @return null|array 
     */
    public function getDefaultValues(): ?array{
        return null;
    }
}
class TestJsonValidator extends RequestValidatorBase{
}
$g = Request::getInstance();
// $g->setJsonData('{"x":"8<script >alert(\"ok\")</script><div>ok</div>"}');
$g->setJsonData('{"x":[1,3]}');
$data = JumData::ValidateJSon($g, new TestJsonValidator(), $errors);
igk_wln_e(__FILE__.":".__LINE__ , "validation : complete ", $data, $errors);