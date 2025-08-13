<?php
use IGK\System\IO\File\PHPDocCommentParser;
class A{
    /**   
     * information du jour 
     * @param data[]|int $i ok i
     * @deprecated
     * @return void
     * @response(
     *      Users[]|Json
     * )
     * @url https://igkdev.com/schema-db
     */
      /**
     * entry falback action
     * @param int $i id index demo 
     * @return array 
     * @description Data "to pass" to entry view\
     *              uniform data
     * @responses(
     * {
     * "200":{"description":"OK", "links":{"myLink":{"operationId":"users"}}, "content":{"application/json":{"schema":{"$ref":"#/component"}}}},
     * "401":{"description":"(Unauthorized) Invalid or missing Access Token"}
     * })
     * @security(['bearerAuth'])
     */
    public function doSome(){
    }
}
/**
 * parse document 
 * @package 
 */
$ref = new ReflectionMethod('A', 'doSome');
$cm = $ref->getDocComment();
igk_wln_e($cm, PHPDocCommentParser::ParsePhpDocComment($cm));