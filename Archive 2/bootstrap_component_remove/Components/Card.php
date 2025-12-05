<?php
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Html\Dom\HtmlNoTagNode;
/**
 * represent bootstrap toast component
 * @package igk\bootstrap\Components
 */
class Card extends ComponentBase{
    private $m_cardbody;
    private $m_header;
    private $m_footer; 
    protected function initialize(){
       $this["class"]  = "card"; 
       $this->m_cardbody = new HtmlNode("div");
       $this->m_cardbody["class"] = "card-body";
       $this->m_header = new HtmlNoTagNode();
       $this->m_footer = new ChildVisibleComponent("div");
       $this->m_footer["class"] = "card-footer";
       parent::_Add($this->m_header);
       parent::_Add($this->m_cardbody);
       parent::_Add($this->m_footer);
    }
    public function getHeader(){ return $this->m_header; }
    public function getBody(){return $this->m_cardbody; }
    public function getFooter(){return $this->m_footer; }
    function _Add($n, $force = false): bool
    {        
        return $this->m_cardbody->_Add($n);
    }
    /**
     * get body content helper
     * @return mixed 
     */
    public function getBodyContent(){
        return $this->m_cardbody->getContent();
    }
    /**
     * set body content helper;
     * @param mixed $v 
     * @return $this 
     */
    public function setBodyContent($v){
        $this->m_cardbody->setContent($v);
        return $this;
    }
}