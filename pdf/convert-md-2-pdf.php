<?php
// @command: balafon --run .test/pdf/convert-md-2-pdf.php

use IGK\Helper\StringUtility;
use igk\pdflib\PDFCssProperties;
use igk\pdflib\PDFDocument;
use IGK\System\IO\Markdown\IMarkdownConverterListener;
use IGK\System\IO\Markdown\IMarkdownElementListener;
use IGK\System\IO\Markdown\MarkdownConverter;
use IGK\System\Text\RegexMatcherCapture;

/**
 * markdown to pdf listener 
 * @package 
 */
class MarkdownToPdfListener implements IMarkdownConverterListener
{
    /**
     * 
     * @var mixed
     */
    var $appendOutputListener;
    /**
     * 
     * @var mixed
     */
    var $lf = null;

    /**
     * collection of items
     * @var array
     */
    private $m_items;
    /**
     * font-sizes definitions 
     * @var mixed
     */
    private $m_fontsizes;

    /**
     * current definition info
     * @var mixed
     */
    private $m_current;

    public function prepareTextBeforeAppendToBuffer(string $input): string
    {
        return $input;
    }
    public function didHandleOutput(bool &$isSingle, string &$output) {}

    public function beforeBufferLine(RegexMatcherCapture $capture, MarkdownConverter $converter, bool $lineFeed) {}

    public function default(string $data): ?string
    {
        $item = self::CreateItem('p');
        $item->value = $data;
        $this->_append($item);
        return null;
    }
    protected function _append($item)
    {
        $this->m_items[] = $item;
    }

    public function endLineFeedToBuffer(): ?string
    {
        return null;
    }

    public function setFontSizes(?array $fontSizes)
    {
        $this->m_fontsizes = $fontSizes;
    }

    public function __construct()
    {
        $this->m_items = [];
    }

    public function postTreatOutput(string $output): string
    {
        return $output;
    }

    public function endState(): ?string
    {
        return null;
    }

    /**
     * 
     * @param string $title 
     * @param int $level 
     * @param null|string $slug 
     * @return void 
     */
    public function title(string $title, int $level, ?string $slug = null)
    {
        $item = self::CreateItem('title');
        $item->level = $level;
        $item->value = $title;
        $item->styleDefinition->fontSize = igk_getv($this->m_fontsizes, $level);
        $this->m_items[] = $item;
    }

    /**
     * 
     * @param string $type 
     * @return object 
     */
    public static function CreateItem(string $type)
    {
        return MarkdownToPdfItemBase::CreateItem($type);
    }

    public function rtrimOutput(string $output): string
    {
        return rtrim($output);
    }
    public function _filter_line_feed() {}
    protected function _filter_word() {}
    protected function _filter_list_item(string $value)
    {
        $li = self::CreateItem('li');
        $this->_init_current('ul');
        $this->m_current->node->add($li);
        $li->value = $value;
    }
    protected function _init_current(string $type)
    {
        if (is_null($this->m_current) || ($this->m_current->type != $type)) {

            $this->m_current = (object)[
                'node' => self::CreateItem($type),
                'type' => $type
            ];
            $this->_append($this->m_current->node);
        }
    }
    /**
     * 
     * @param null|string $token_id 
     * @param string $value 
     * @param bool $isRoot 
     * @param Closure $callback 
     * @param RegexMatcherCapture $capture 
     * @param null|array $options 
     * @return never 
     */
    function filter(?string $token_id, string $value, bool $isRoot, closure $callback, RegexMatcherCapture $capture, ?array $options = null)
    {
        $tab = func_get_args();
        $un = StringUtility::FuncName($token_id);
        $c = __NAMESPACE__ . '/Regex2PDF' . $un;
        if (class_exists($c)) {
            $cl = new $c;
            return $cl->convert($value, $isRoot, $callback, $capture, $options);
        }
        $ns = ($isRoot ? '_filter_' : '_willtread_') . $un;
        return call_user_func_array([$this, $ns], [$value, $callback, $capture]);
    }
    protected function _filter_text_header(string $value) {}

    public function output()
    {
        $doc = new PDFDocument();
        $renderer = new MarkdownToPdfItemRenderer;
        $renderer->doc = $doc;
        
        

        foreach ($this->m_items as $item) {
            $renderer->render($item);
        }
        return $doc->output();
    }
}

class MarkdownToPdfItemRenderer
{

    var $doc;
    var $location;
    public function __construct()
    {
        $this->location = (object)['x' => 10, 'y' => '10'];
    }
    public function render($item)
    {
        if ($item instanceof MarkdownToPdfContainer){
            $item->render($this);
            return;
        }
        $p = $this->doc->p();
        if ($style = $item->getStyle()) {
            $p->setStyle($style);
        }
        $p->Content = $item->value;
    }
}
abstract class MarkdownToPdfItemBase
{
    /**
     * 
     * @var PDFCssProperties
     */
    public $styleDefinition;
    public function __construct()
    {
        $this->styleDefinition = new PDFCssProperties;
        $this->styleDefinition->borderSize = null;
        $this->styleDefinition->borderColor = null;
        $this->styleDefinition->borderStyle = null;
        $this->styleDefinition->borderWidth = 0;
        $this->styleDefinition->left = '10mm';
    }
    public static function CreateItem(string $type)
    {
        $cl  = __NAMESPACE__ . '\\MarkdownToPdfItem' . ucfirst(StringUtility::FuncName($type));
        return new $cl;
    }
    /**
     * 
     * @return null|string 
     */
    public function getStyle(): ?string
    {
        $c = array_filter((array)$this->styleDefinition);
        $r = implode(';', array_map(function ($v, $k) {
            $k = igk_getv(['fontsize' => 'font-size'], strtolower($k), strtolower(preg_replace('/([A-Z])/', '-\\1', $k)));
            return implode(':', [$k, $v]);
        }, $c, array_keys($c)));
        return $r;
    }
}

class MarkdownToPdfItemP extends MarkdownToPdfItemBase
{
    /**
     * value of the title
     * @var ?string
     */
    var $value;
}
class MarkdownToPdfItemTitle extends MarkdownToPdfItemP
{
    /**
     * text level 
     * @var int
     */
    var $level = 1;
}

abstract class MarkdownToPdfContainer extends MarkdownToPdfItemBase{
 /**
     * text level 
     * @var int
     */
    private $m_childs;
    public function __construct()
    {
        parent::__construct();
        $this->m_childs = [];
    }
    public function add(MarkdownToPdfItemLi $item)
    {
        $this->m_childs[] = $item;
    }
    public function render($renderer){
        foreach($this->m_childs as $item){
            $renderer->render($item);
        }
    }
}

class MarkdownToPdfItemUl extends MarkdownToPdfContainer
{
   
}

class MarkdownToPdfItemLi extends MarkdownToPdfItemBase
{
    /**
     * value 
     * @var ?string
     */
    var $value;
    public function __construct()
    {
        parent::__construct(); 
    }
   
}



$src = implode("\n", [
    
    
    
    
    
    
    
    
    
    '- mangoes',
    '- potatoes',
    '- pb-data',
    '> sdk',
    '> snk'

]);

$listener = new MarkdownToPdfListener;
$listener->setFontSizes([
    1 => '92pt',
    2 => '64pt',
    3 => '32pt',
    4 => '28pt',
    5 => '24pt',
    6 => '12pt',
]);
$converter = new MarkdownConverter;
$converter->setOutputTreatmentListener($listener);
$converter->transformToHtml($src);
igk_wln_e($listener->output());
