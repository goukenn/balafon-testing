<?php

use PHPUnit\Framework\TestCase; 

class CodeLangUserValidationTest extends TestCase{
    public function setUp(): void{ 
    }
    public function test_definition(){
         $l = 0;
         $fc = Closure::fromCallable('codelangusernamevalidation');
        foreach([
            'low'=>'false',
            'information'=>'false',
        ] as $s=>$v){
            $lt_  = $fc($s) == $v;
            $this->assertTrue( $lt_, $s . ' not produce '.$v);  
            if ($lt_){
                $l ++;
            }
        }
    }

}

