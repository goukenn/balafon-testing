<?php

// @command: balafon --run .test/modules/igk_windows_rtf/render_levellist.php

use IGK\System\IO\StringBuilder;
use igk\Windows\Rtf\RtfLevelList as cList;

class TxtEngineRenderer{
    var $tabstop = ' ';
    public function render($item){
        $sb = new StringBuilder;
        $tab = [$item];
        $tabstop = $this->tabstop ?? "\t";

        while (count($tab)>0){
            $q = array_shift($tab);
            $s = '';
            if ($q->getIsRoot()){
                $s .= '+ ';
            }else{
                $s .= str_repeat($tabstop, $q->getLevel()).'- ';
            }
            if ($rt = $q->getRoot()){
                $s.= $rt;
            } 
            if($childs = $q->getChilds()){
                array_unshift($tab, ...$childs);
            }
            $sb->appendLine($s);
        }
        return $sb.'';
    }
}

class RtfLevelList extends cList{
   
}

$n = new RtfLevelList;
$n->setRoot('\\\'00');
$a = new RtfLevelList;
$b = new RtfLevelList;
$cm = new RtfLevelList;
$cm->setRoot('M');
$n->append($a);
$n->append($b);


$b->getParent()->append($cm);

echo (new TxtEngineRenderer())->render($n);

igk_wln_e('.');