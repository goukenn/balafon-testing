<?php

if (true){
  function conditional(){
    echo 'init conditional';
  } 
  echo "after call";
}  else {
    echo $j + 1;
    function jump(){
        return 23;
    }
    function conditional(){
        return 23;
    }
}
// finish 
echo 'finish';