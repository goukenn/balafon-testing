<?php
/**
* auto generate doc.
*/

class A{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $v;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $childs;
    /**
    * .ctr
    * @param mixed $v
    * @param null|array $childs
    */
    public function __construct($v, ?array $childs=null){
        $this->childs = $childs;
        $this->v = $v;    
    }
    /**
    * get string presentation.
    */
    public function __toString()
    {
        return $this->v;
    }
    /**
    * auto generate doc.
    */
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
/**
* auto generate doc.
* @param mixed $tab
*/
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
            }
            echo "A detected : ".$q."\n";
                array_unshift($tab, ...array_merge(($q->childs) ? $q->childs: [], [$q]));
                array_unshift($nodes, $q);
                continue;
        } else {
            echo "->".$q."\n";
        }
    }
    return $o;
}
render($tab);