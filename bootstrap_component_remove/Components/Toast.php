<?php
namespace igk\bootstrap\Components;
use IGK\System\Html\Dom\HtmlNode;

/**
 * represent bootstrap toast component
 * @package igk\bootstrap\Components
 */
class Toast extends ComponentBase{
    /**
    * auto generate doc.
    * @var mixed
    */
    protected $tagname = "div";
    /**
    * auto generate doc.
    * @var mixed
    */
    private $m_header;
    /**
    * auto generate doc.
    * @var mixed
    */
    private $m_body;
    /**
    * auto generate doc.
    */
    public function getBody(){
        return $this->m_body;
    }
    /**
    * auto generate doc.
    */
    public function getHeader(){
        return $this->m_header;
    }
    /**
    * .ctr
    * @param null|string $id
    * @param null|HtmlNode $header
    * @param null|HtmlNode $body
    */
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
    /**
    * auto generate doc.
    */
    public function getCanAddChilds()
    {
        return false;
    }
    /**
    * auto generate doc.
    * @param null|mixed $options
    */
    public function getRenderedChilds($options=null){ 
        return [
            $this->m_header,
            $this->m_body
        ];
    }
}