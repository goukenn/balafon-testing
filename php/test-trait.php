<?php
// @author: C.A.D. BONDJE DOUE
// @filename: test-trait.php
// @date: 20250731 16:51:38
// @desc: use trait logic a
// @command: balafon --run .test/php/test-trait.php

/**
* auto generate doc.
*/
abstract class A
{
    /**
    * auto generate doc.
    */
    public function a()
    {
        igk_wln_e('from root '.__FUNCTION__);
    }
}
/**
* auto generate doc.
*/
trait TraitA
{
    /**
    * auto generate doc.
    */
    public function a()
    {
        igk_wln_e("from trait ___" . __METHOD__, $this);
    }
}
/**
* auto generate doc.
*/
class BBBB extends A
{
    use TraitA{
        a as logicA;
    }
    /**
    * auto generate doc.
    */
    public function a(){
        $this->logicA();
        igk_wln_e('form class '. __METHOD__);
    }
}
$b = new BBBB();
echo $b->a();
exit;