<?php
use igk\js\common\JSExpression;
use igk\js\common\JSExpressionOptions;
use igk\js\common\Traits\JSVariableStringifyTrait;

/**
* auto generate doc.
*/
class D extends IGKObject{
    use JSVariableStringifyTrait;
    /**
    * auto generate doc.
    * @var mixed
    */
    protected $m_content;
    /**
    * auto generate doc.
    * @var mixed
    */
    protected $m_theme = [];
    /**
    * auto generate doc.
    * @var mixed
    */
    protected $m_plugins = [];
    /**
    * auto generate doc.
    */
    public function getContent(){
        return $this->m_content;
    }
    /**
    * auto generate doc.
    * @param mixed $v
    */
    public function setContent($v){
        $this->m_content = $v;
    }
}
/**
* auto generate doc.
*/
class B extends D{
}
$s = new B();
$s->content = ["./src/**/*.{html,js}"];
echo $s->to_js(["map"=>['content'=>'db:facebook']]);
igk_exit();