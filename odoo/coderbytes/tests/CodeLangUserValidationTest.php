<?php

use PHPUnit\Framework\TestCase;
/**
* auto generate doc.
* @package
*/
class CodeLangUserValidationTest extends TestCase{
    /**
    * auto generate doc.
    * @return void
    */
    public function setUp(): void{ 
    }
    /**
    * auto generate doc.
    * @return void
    */
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

