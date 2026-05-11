<?php
// @author: C.A.D. BONDJE DOUE
// @filename: common.php
// @date: 20260402 23:52:31
// @desc: common metadata
use IGK\Helper\IO;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\Console\Logger;
use IGK\System\IToJSon;
use IGK\System\Polyfill\ArrayAccessSelfTrait;
use IGK\System\Polyfill\ArrayGetRefAccessSelfTrait;
use IGK\System\Polyfill\JsonSerializableTrait;
/**
* auto generate doc.
* @package
*/
class MetaDataDefinition implements JsonSerializable
{
    use JsonSerializableTrait;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $type;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $modifier;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $docs;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $comment;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $file;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $items;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    private $m_index;
    /**
    * auto generate doc.
    * @param int $index
    * @return void
    */
    public function setFileIndex(int $index){
        $this->m_index = $index;
    }
    /**
    * auto generate doc.
    * @return void
    */
    public function _json_serialize(){
        $d = ['$file'=>$this->m_index>=0 ? $this->m_index :null];
        $v =  \IGK\System\Reflection\Helper\ReflectionHelper::GetObjectVars($this);
        $c = array_merge($d, $v); 
        unset($c['type']);
        return $c;        
    }
}
/**
* auto generate doc.
* @package
*/
class MetadataEntityDefinition implements ArrayAccess,  IToJSon
{
    use ArrayGetRefAccessSelfTrait;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    private $m_properties;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    private $m_output;
    /**
     * entities resolution 
     * @return string[] 
     */
    public function getEntities(){
        return [
            '::struct','::class','::interface', '::enum', '::meta'
        ];
    }
    /**
    * .ctr
    * @return void
    */
    public function __construct()
    {
        $this->m_output = [
            '::files'=>[], 
            '::meta'=>[]
        ];
        $this->m_properties = [
            '::current-file' => null,
        ];
    }
    /**
    * auto generate doc.
    * @param mixed $key
    * @return void
    */
    protected function & _access_refoffset_get($key)
    {
        $n = null;
        if (method_exists($this, $fc = 'getRef' . ucfirst($key))) {
            $n = &call_user_func_array([$this, $fc], []);
        } else if ($this->isEntityDefinition($key)) {
            $n = &$this->_refOutput($key);
        } else {
            if (in_array($key, explode('|', '::files'))){
                $n = & $this->m_output[$key]; 
            }else {
                $n = $this->_access_offsetGet($key);
            }
        }
        return $n;
    }
    /**
    * auto generate doc.
    * @param string $type
    * @return void
    */
    private function &_refOutput(string $type)
    {
        $n = null;
        if (key_exists($type, $this->m_output)) {
            $n = &$this->m_output[$type];
        }
        return $n;
    }
    /**
    * auto generate doc.
    * @return void
    */
    public function &getRefEnum()
    {
        return $this->_refOutput('enum');
    }
    /**
    * auto generate doc.
    * @return void
    */
    public function &getRefClass()
    {
        return $this->_refOutput('class');
    }
    /**
    * auto generate doc.
    * @return void
    */
    public function & getRefInterface()
    {
        return $this->_refOutput('interface');
    }
    /**
    * auto generate doc.
    * @return void
    */
    public function & getRefStruct()
    {
        return $this->_refOutput('struct');
    }
    /**
     * if is entity definition 
     * @param string $key 
     * @return bool 
     */
    public function isEntityDefinition(string $key): bool
    {
        return in_array($key, $this->getEntities());
    }
    /**
     * check weather key exists
     * @param mixed $n 
     * @return bool 
     */
    public function offsetExists($n): bool
    {
        if (in_array($n, $this->getEntities())){
            return key_exists($n, $this->m_output);
        }
        return key_exists($n, $this->m_properties);
    }
    /**
    * auto generate doc.
    * @return array{::files: array}
    */
    public function &getOutput()
    {
        return $this->m_output;
    }
    /**
    * auto generate doc.
    * @return void
    */
    public function getCurrentFile()
    {
        return igk_getv($this->m_properties, '::current-file');
    }
    /**
    * auto generate doc.
    * @param string $file
    * @return void
    */
    public function setCurrentFile(string $file)
    {
        $this->m_properties['::current-file'] = $file;
    }
    /**
    * auto generate doc.
    * @param mixed $n
    * @param mixed & $v
    * @return void
    */
    protected function _access_offsetSet($n, &$v)
    {
        if (in_array($n, $this->getEntities())){
            $this->m_output[$n] = $v;
            return;
        }
        $this->m_properties[$n] = $v;
    }
    /**
    * auto generate doc.
    * @param mixed $key
    * @return void
    */
    protected function _access_offsetGet($key)
    {
        $n = null;
        if (key_exists($key, $this->m_properties)) {
            $n = $this->m_properties[$key];
        }
        return $n;
    }
    /**
    * auto generate doc.
    * @param mixed $n
    * @return void
    */
    protected function _access_offsetExists($n)
    {
        return key_exists($n, $this->m_properties);
    }
    /**
    * auto generate doc.
    * @param mixed $n
    * @return void
    */
    public function _access_offset_unset($n)
    {
        unset($this->m_properties[$n]);
    }
    /**
    * auto generate doc.
    * @return void
    */
    public function & getRefFiles(){
        return $this->m_output['::files'];
    }
    /**
     * output definition 
     * @param mixed $options 
     * @param int $flag 
     * @return false|string 
     */
    public function to_json($options = null, int $flag = 0)
    {
        return JSon::Encode($this->m_output, $options, $flag);
    }
    /**
    * .destructor
    * @param string $key
    * @return void
    */
    public function &__get(string $key)
    {
        $n = null;
        if (method_exists($this,  $fc = 'get' . ucfirst($key))) {
            $n = call_user_func_array([$this, $fc], []);
            assert($n === $this->m_output, 'assert failed');
        }
        return $n;
    }
}
/**
* auto generate doc.
* @param mixed & $output
* @param string $type
* @param string $name
* @return mixed
*/
function igk_metadata_store_files_def(&$output, string $type, string $name)
{
    $rk = '::files';
    $cf = $output['::current-file'];
    $ref = null;
    $ref = & $output[$rk]; 
    if (!isset($ref[$cf][$type])) {
        $ref[$cf][$type] = [];
    } 
    $ref[$cf][$type][] = $name;
}
/**
* auto generate doc.
* @param mixed $callback
* @param string $dir
* @param array $exclude
* @param array &$output
* @param string $pattern
* @return void
*/
function igk_metadata_treat_definition($callback, string $dir, array $exclude, &$output, string $pattern)
{
    $dir = realpath($dir);
    $ln = strlen($dir);
    IO::GetFiles($dir, function ($fc) use (&$output, $callback, $pattern, $ln) {
        if (preg_match($pattern, $fc)) {
            Logger::info('Treat definition: ' . $fc);
            $f = substr($fc, $ln - 1);
            $output['::files'][] = $f;
            $output['::current-file'] = $f;
            $output['::current-file-index'] = count($output['::files'])-1;
            $callback(file_get_contents($fc), $output);
        }
    }, true, $exclude);
    unset($output['::current-file']);
}