<?php
// @author: C.A.D. BONDJE DOUE
// @filename: test-trait.php
// @date: 20250731 16:51:38
// @desc: use trait logic a

// @command: balafon --run .test/php/test-trait.php
// NOTE : les traits permette d'encapsuler directement une fonctionnalité 
abstract class A
{
    public function a()
    {
        igk_wln_e('from root '.__FUNCTION__);
    }
}
trait TraitA
{
    public function a()
    {
        igk_wln_e("from trait ___" . __METHOD__, $this);
    }
}

// NOTE : a trait can access a protected class member - required in class 

class BBBB extends A
{
    use TraitA{
        a as logicA;
    }
    public function a(){
        //parent::a();
        $this->logicA();
        igk_wln_e('form class '. __METHOD__);
    }
}
$b = new BBBB();

echo $b->a();

exit;
