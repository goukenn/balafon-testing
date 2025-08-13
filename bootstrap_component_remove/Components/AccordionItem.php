<?php
// @author: C.A.D. BONDJE DOUE
// @file: AccordionItem.php
// @date: 20220116 05:49:13
namespace igk\bootstrap\Components;
class AccordionItem extends ComponentBase{
    private $m_header;
    private $m_body;
    private $m_footer;
    protected function initialize()
    {
        $this["class"] = "accordion-item";
        $this->m_header = (new ChildVisibleComponent("div"))->setClass("accordion-header");
        $this->m_body = (new ChildVisibleComponent("div"))->setClass("accordion-body");
        $this->m_footer = (new ChildVisibleComponent("div"))->setClass("accordion-footer");
        parent::_Add($this->m_header);
        parent::_Add($this->m_body);
        parent::_Add($this->m_footer);
    }
    public function getHeader(){return $this->m_header; }
    public function getBody(){return $this->m_body; }
    public function getFooter(){return $this->m_footer; }
}