<?php
// + | --------------------------------------------------------------------
// + | access to protected method not allowed 
// + |

class TheDataClass{
    protected $sample;
    public function setSample($value){
        $this->sample = $value;
    }
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
exit;