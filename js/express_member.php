<?php
use igk\js\common\JSExpression;
use igk\js\common\JSExpressionOptions;
use igk\js\common\Traits\JSVariableStringifyTrait;
class D extends IGKObject{
    use JSVariableStringifyTrait;
    protected $m_content;
    protected $m_theme = [];
    protected $m_plugins = [];
    public function getContent(){
        return $this->m_content;
    }
    public function setContent($v){
        $this->m_content = $v;
    }
}
class B extends D{
    // var $x; 
}
$s = new B();
$s->content = ["./src/**/*.{html,js}"];
// $s->x = "jjj";
echo $s->to_js(["map"=>['content'=>'db:facebook']]);
exit;