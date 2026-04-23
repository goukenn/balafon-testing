<?php
use IGK\Tests\BaseTestCase;

/**
* auto generate doc.
*/
class igk_snake extends BaseTestCase{
    /**
    * auto generate doc.
    */
    public function test_snake_1(){
        $this->assertEquals(
            "p_presentation_avion",
            igk_str_snake("PPresentationAvion")
        );
        $this->assertEquals(
            "a_v_i_a_t_i_o_n",
            igk_str_snake("AVIATION")
        );
    }
}