<?php
use IGK\System\Text\IRegexMatchInfo;
class TypeScriptDefinitionHandler{
    private $m_docComment;
    private $m_output = '';
    var $converter;
    /**
     * 
     * @param IRegexMatchInfo $g 
     * @param int $pos 
     * @param string $data 
     * @return void 
     */
    public function treat($g, int $pos, string $data){
        if ($g->parentInfo) return; 
        switch ($g->tokenID) {
            case 'global-def':
                $v_content = substr(substr($g->value, 1),0, -1);
                $def = [];
                $this->converter->detectTypeScriptBlockDeclaration(trim($v_content), $def);
                uksort($def, 'strcasecmp');
                $this->m_output = implode(";\n", array_merge(array_map(function($i, $k){
                    return implode(':', [$k,$i]);
                }, $def, array_keys($def))));
                break; 
            default: 
                break;
        }
    }
    /**
     * 
     * @return string 
     */
    public function output(){ 
        return $this->m_output; 
    }
}