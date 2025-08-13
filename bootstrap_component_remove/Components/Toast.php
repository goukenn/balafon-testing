<?php
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;
/**
 * represent bootstrap toast component
 * @package igk\bootstrap\Components
 */
class Toast extends ComponentBase{
    protected $tagname = "div";
    private $m_header; 
    private $m_body; 
    public function getBody(){
        return $this->m_body;
    }
    public function getHeader(){
        return $this->m_header;
    }
    public function __construct(?string $id=null, ?HtmlNode $header=null, ?HtmlNode $body=null)
    {
        parent::__construct();
        $this["class"] = "toast";
        $this["role"] = "alert";
        $this["aria-live"] = "assertive";
        $this["aria-atomic"] = "true";        
        $id && $this->setId($id);
        $this->m_header = new HtmlNode("div");
        $this->m_header["class"] = "toast-header";
        $this->m_body = new HtmlNode("div");
        $this->m_body["class"] = "toast-body";
        $this->m_childs[] = $this->m_header;
        $this->m_childs[] = $this->m_body;
        $header && $this->m_header->add($header);
        $body && $this->m_header->add($body);
    }
    public function getCanAddChilds()
    {
        return false;
    }
    public function getRenderedChilds($options=null){ 
        return [
            $this->m_header,
            $this->m_body
        ];
    }
}