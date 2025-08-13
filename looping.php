<?php
class A{
    var $v;
    var $childs;
    public function __construct($v, ?array $childs=null){
        $this->childs = $childs;
        $this->v = $v;    
    }
    public function __toString()
    {
        return $this->v;
    }
    public function render(){
        return "#".$this->v;
    }
}
$tab = [
    "A",
    "B",
    new A("C"),
    new A ("SDP",  [ "X", new A("Z", ["D", "E", new A("SAMPLING"), "F"]) ]),
    'Z'
];
// ar
function render($tab){
    $o = "";
    $q = $p = null;
    $nodes = [];
    while(count($tab)>0){
        $q = array_shift($tab);
        if ($q instanceof A){
            if (count($nodes)>0){
                if ($nodes[0] === $q){
                    echo "close node - render \n";
                    $p = array_shift($nodes);
                    echo $p->render();
                    continue;
                } 
                // else {
                //     echo "no closing found\n";
                //     break;
                // }
            }
            echo "A detected : ".$q."\n";
            //if ($q->childs){
                array_unshift($tab, ...array_merge(($q->childs) ? $q->childs: [], [$q]));
                array_unshift($nodes, $q);
                continue;
            // }else{
            //     echo "have no childs \n";
            //     echo $q->render();
            // }
        } else {
            echo "->".$q."\n";
        }
    }
    return $o;
}
render($tab);