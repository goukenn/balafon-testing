<?php
$a = 8;
$b = 9;
function eval_args($x){
    global $a, $b;
    $x = ltrim(rtrim($x, ")"), "(");
    $src = implode("\n", ["<?php",
        "\$g = [$x];"
    ]);
    echo "source : ".$src . "\n";
    $tokens = token_get_all($src);
    // echo "tokens : \n";
    // print_r($tokens);
    // exit;
    while(count($tokens)>0){
        $e = array_shift($tokens);
        $id = 0;
        if (is_array($e)){
            $id = $e[0];
            $e = $e[1];
        }
        switch($id){
            case T_VARIABLE:
                echo "------------------------------var detected: $e \n";
                $vars[$e] = 1;
                $name = substr($e,1);
                $x = str_replace($e,'igk_express_var("'.$name.'")', $x);
                break;
        }
    }
    return $x;
}
function pass($x){
    echo "in pass ", $x , "\n";
}
$sam = eval ('return pass(eval_args("(\$a, \$b)"));');
echo 'done; ';
var_dump($sam);