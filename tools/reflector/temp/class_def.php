<?php

trait JumpTrait{
    function doAction(){
        
    }
}


class _AB{
    use JumpTrait;
}

class A{

}

interface IActionList{
    function mark();
    function ci();
}