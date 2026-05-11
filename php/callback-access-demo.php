<?php
// + | --------------------------------------------------------------------
// + | access to protected method not allowed 
// + |
/**
* auto generate doc.
* @package
*/
class TheDataClass{
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    protected $sample;
    /**
    * auto generate doc.
    * @param mixed $value
    * @return void
    */
    public function setSample($value){
        $this->sample = $value;
    }
    /**
    * auto generate doc.
    * @param mixed $obj
    * @param null|callable $callback
    * @return void
    */
    static function DoAction($obj, ?callable $callback=null){
        $fc =function() use($obj){
            echo 'show sample : '.$this->sample, PHP_EOL;
        };
        call_user_func_array($fc->bindTo($obj), []);
        if ($callback){
            if ($fc = $callback->bindTo($obj)){
                $fc();
            }
        }
    }
}
$callback = function(){
    echo 'inner sample '.$this->sample, PHP_EOL;
};
echo 'start sample ..... : ';
$t = new TheDataClass;
$t->setSample('indigo');
TheDataClass::DoAction($t, null);
TheDataClass::DoAction($t, $callback);
igk_exit();