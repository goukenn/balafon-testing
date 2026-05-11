<?php
// @author: C.A.D. BONDJE DOUE
// @filename: load-cs-metadata.php
// @date: 20260402 15:37:29
// @desc: load csharp meta data
// @command: balafon --run .test/csharp/load-cs-metadata.php
use IGK\Helper\Activator;
use IGK\Helper\IO;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\Console\Logger;
use IGK\System\IO\File\PHPScriptBuilder;
use IGK\System\Text\RegexMatcherContainer;

require_once __DIR__ . '/../language/load-metadata-common.php';
/**
* auto generate doc.
* @package
*/
class CSharpEntityFactory
{
    /**
    * auto generate doc.
    * @param string $type
    * @param mixed $read_params
    * @return void
    */
    public static function CreateDefinition(string $type, $read_params)
    {
        $cl = PHPScriptBuilder::GetFullType('CSharpEntity' . ucfirst($type), __NAMESPACE__);
        if (class_exists($cl)) {
            $o = Activator::CreateNewInstance($cl, $read_params);
            return $o;
        }
    }
}
/**
* auto generate doc.
* @package
*/
class CSharpEntityMember
{
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $name;
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
    var $type;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $modifier;
}
/**
* auto generate doc.
* @package
*/
class CSharpEntityEvent extends CSharpEntityMember {}
/**
* auto generate doc.
* @package
*/
class CSharpEntityDelegate extends CSharpEntityMember {}
/**
* auto generate doc.
* @package
*/
class CSharpEntityMethod extends CSharpEntityMember {}
/**
* auto generate doc.
* @package
*/
class CSharpEntityOperator extends CSharpEntityMethod {}
/**
* auto generate doc.
* @package
*/
class CSharpEntityProperty extends CSharpEntityMember
{
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $get = false;
    /**
    * auto generate doc.
    * @var mixed
    * @return void
    */
    var $set = false;
}
/**
* auto generate doc.
* @param mixed $i
* @return mixed
*/
function igk_detect_csharp_reset_prop($i)
{
    $i->type = null;
    $i->name = null;
    $i->docs = null;
    $i->modifier = null;
    $i->returnType = null;
    $i->propertyMarker = (object)[];
}
/**
* auto generate doc.
* @param mixed $i
* @return mixed
*/
function igk_detect_update_name($i)
{
    $v_fn = '';
    if ($i->fullname) {
        $v_fn = $i->fullname . '.' . $i->name;
    } else {
        $v_fn = ($i->namespace ? $i->namespace . '::' : '') . $i->name;
    }
    $i->fullname = $v_fn;
}
/**
* auto generate doc.
* @param mixed $i
* @return mixed
*/
function igk_detect_save_state($i)
{
    array_push($i->states, $i->current);
}
/**
* auto generate doc.
* @param mixed $i
* @return mixed
*/
function igk_detect_restore_state($i)
{
    $tab = array_pop($i->states);
    if ($tab) {
        list($name, $target) = igk_extract($tab, 'name|target');
        $i->items = &$target->items;
        $i->current = $tab;
    } else {
        $i->current = null;
        $i->items = null;
    }
}
/**
* auto generate doc.
* @param mixed $i
* @param mixed $type
* @param mixed $e
* @return mixed
*/
function igk_detect_init_read($i, $type, $e)
{
    if (!$i->read) {
        $i->read = (object)[
            'type' => $type, 
            'token' => null,
            'glue' => false,
            'name' => null,
            'docs' => $i->docs,
            'modifier' => $i->modifier
        ];
        $i->docs = null;
        $i->modifier = null;
    }
    if ($i->read->type != $type) igk_die('missing read event token');
    if (!$i->read->name) {
        $v = $e->value;
        if ($i->read->token) {
            if ($i->read->glue) {
                $v = $i->read->token . '.' . $v;
                $i->read->glue = false;
            } else {
                $i->read->name = $v;
                $v = $i->read->token;
            }
        }
        $i->read->token = $v;
    }
}
/**
* auto generate doc.
* @param string $src
* @param mixed & $output
* @param null|array $handler
* @return mixed
*/
function igk_detect_csharp_metadata(string $src, &$output,  ?array $handler = null)
{
    $regex = new RegexMatcherContainer;
    $comments[] = $v_docs = $regex->match('\/\/\/.*', 'csharp-doc')->last();
    $comments[] = $regex->appendMultilineComment()->last();
    $comments[] = $regex->appendSingleLineComment()->last();
    $v_string = $regex->begin('(?:\$|@|b)?"', '"', 'csharp-litteral-string')->last();
    $v_char = $regex->begin('\'', "'", 'csharp-litteral-char')->last();
    $v_ns = $regex->begin('\\bnamespace\\b', '(?<=;|\})', 'csharp-namespace')->last();
    $v_using = $regex->begin('\\busing\\b', '(?<=;)', 'csharp-using-block')->last();
    $v_delegate = $regex->begin('\\bdelegate\\b', '(?<=;)', 'csharp-delegate')->last();
    $v_event = $regex->begin('\\bevent\\b', '(?<=;)', 'csharp-event')->last();
    $v_rf = $regex->match('#.*$')->last();
    $v_modifier = $regex->match('\\b(public|static|readonly|protected|private|abstract|final|virtual|override|sealed|partial)\\b', 'csharp-modifier')->last();
    $v_attribute = $regex->begin('\[', '\]', 'csharp-attribute')->last();
    $v_end_instruct = $regex->createPattern(['match' => ';', 'tokenID' => 'csharp-end-instruct']);
    $v_type_end_instruct = $regex->createPattern(['match' => ';', 'tokenID' => 'csharp-type-end-instruct']);
    $v_type_block_container = $regex->begin('(?=\\b(class|interface|struct|enum)\\b)', '(?<=\})', 'csharp-type-block-container')->last();
    $v_type = $regex->createPattern(['match' => '\\b(class|interface|struct|enum)\\b', 'tokenID' => 'csharp-type']);
    $v_token = $regex->createPattern(['match' => '\\b([a-zA-Z_][a-zA-Z_0-9]*)\\b', 'tokenID' => 'csharp-token']);
    $v_glue_token = $regex->createPattern(['match' => '\.', 'tokenID' => 'csharp-glue-token']);
    $v_type_block = $regex->createPattern(['begin' => '\{', 'end' => '\}', 'tokenID' => 'csharp-type-block']);
    $v_type_block_container->patterns = [
        $comments,
        $v_string,
        $v_type,
        $v_glue_token,
        $v_token,
        $v_type_block
    ];
    $v_bracket = $regex->createPattern(['begin'=>'\(', 'end'=>'\)'], 'csharp-brackets');
    $v_square_bracket = $regex->createPattern(['begin'=>'\[', 'end'=>'\]'], 'csharp-brackets');
    $v_bracket->patterns = [
        $comments,
        $v_string,
        $v_bracket,
        $v_square_bracket,
    ];
    $v_attribute->patterns = [
        $comments,
        $v_string,
        $v_bracket,
        $v_square_bracket
    ];
    $v_nsblock = $regex->createPattern(['begin' => '\{', 'end' => '\}', 'tokenID' => 'csharp-ns-block']);
    $v_nsname = $regex->createPattern(['match' => '\\b([a-zA-Z_][a-zA-Z_0-9]*)\\b', 'tokenID' => 'csharp-ns-name']);
    $v_func_block = $regex->createPattern(['begin' => '\{', 'end' => '\}', 'tokenID' => 'csharp-func-block']);
    $v_chain_block = $regex->createPattern(['begin' => '\{', 'end' => '\}', 'tokenID' => 'csharp-chain-block']);
    $v_fc_call = $regex->createPattern(['begin' => '\(', 'end' => '\)', 'tokenID' => 'csharp-call']);
    $v_call = $regex->begin('\(', '\)', 'csharp-root-call')->last();
    $v_call->patterns = [
        $comments,
        $v_string,
        $v_fc_call
    ];
    $v_chain_block->patterns = [
        $comments,
        $v_docs,
        $v_string,
        $v_chain_block
    ];
    $v_func_block->patterns = [
        $v_chain_block
    ];
    $v_nsblock->patterns = [
        $v_rf,
        $v_ns,
        $v_modifier,
        $v_type,
        $v_glue_token,
        $v_token,
        $v_chain_block,
    ];
    $v_ns->patterns = [
        $comments,
        $v_nsname,
        $v_nsblock,
    ];
    $v_event->patterns = [
        $comments,
        $regex->createPattern((['tokenID' => 'csharp-event-token', 'match' => $v_token->match])),
        $regex->createPattern((['tokenID' => 'csharp-event-glue', 'match' => "\\."])),
    ];
    $v_delegate_params = $regex->createPattern(['tokenID' => 'csharp-delegate-params', 'begin' => "\(", "end" => '\)']);
    $v_delegate->patterns = [
        $comments,
        $regex->createPattern((['tokenID' => 'csharp-delegate-token', 'match' => $v_token->match])),
        $regex->createPattern((['tokenID' => 'csharp-delegate-glue', 'match' => "\\."])),
        $v_delegate_params,
        $regex->createPattern((['tokenID' => 'csharp-delegate-end', 'match' => ";"])),
    ];
    $v_delegate_params->patterns = [
        $comments,
        $v_string
    ];
    $v_type_sub_block = $regex->createPattern(['begin' => '\{', 'end' => '\}', 'tokenID' => 'csharp-type-subblock']);
    $v_marker_property = $regex->createPattern(['match' => '\\b(get|set)\\b', 'tokenID' => 'csharp-property-marker']);
    $v_type_sub_block->patterns = [
        $comments,
        $v_string,
        $v_marker_property,
        $v_chain_block
    ];
    $v_operator_def = $regex->createPattern(['begin' => '\\boperator\\b', 'end' => '(?<=\})', 'tokenID' => 'csharp-operator-def']);
    $v_operator_def->patterns = [
        $comments,
        $regex->createPattern(["tokenID" => "csharp-operator-name", "match" => '\+(\+)?|-(-)?|\*|!']),
        $v_string,
        $v_call,
        $v_chain_block,
    ];
    $v_type_block->patterns = [
        $comments,
        $v_attribute,
        $v_string,
        $v_docs,
        $v_modifier,
        $v_type_block_container,
        $v_event,
        $v_delegate,
        $v_glue_token,
        $v_operator_def,
        $v_token,
        $v_type_end_instruct,
        $v_call,
        $v_type_sub_block
    ];
    $pos = 0;
    $fc_handler = array_merge([
        'csharp-doc'=>function($e, $i){
            if (!$i->docs){
                $i->docs = [];
            }
            $i->docs[] = $e->value;
            $i->filter = $i->filters['filter_doc'];
        },
        'csharp-delegate' => function ($e, $i) {
            $v_r = $i->read;
            $i->items['delegates'][$v_r->name] = CSharpEntityFactory::CreateDefinition('delegate', [
                'name' => $v_r->name,
                'type' => $v_r->token,
                'docs' => $v_r->docs,
                'modifier' => $v_r->modifier,
            ]);
            $i->read = null;
        },
        'csharp-delegate-token' => function ($e, $i) {
            igk_detect_init_read($i, 'delegate', $e);
        },
        'csharp-delegate-glue' => function ($e, $i) {
            $i->read->glue = true;
        },
        'csharp-event' => function ($e, $i) {
            $v_r = $i->read;
            $i->items['events'][$v_r->name] = CSharpEntityFactory::CreateDefinition('event', [
                'name' => $v_r->name,
                'type' => $v_r->token,
                'docs' => $v_r->docs,
                'modifier' => $v_r->modifier,
            ]);
            $i->read = null;
        },
        'csharp-event-token' => function ($e, $i) {
            igk_detect_init_read($i, 'event', $e);
        },
        'csharp-event-glue' => function ($e, $i) {
            $i->read->glue = true;
        },
        'csharp-property-marker' => function ($e, $i) {
            if (!$i->read)
                $i->propertyMarker->{$e->value} = true;
        },
        'csharp-type-end-instruct' => function ($e, $i) use ($v_type) {
            extract(igk_extract_assoc($i, 'returnType|name|modifier'));
            if ($returnType && $name) {
                $i->items['members'][$name] =  CSharpEntityFactory::CreateDefinition('member', [
                    'name' => $name,
                    'modifier' => $modifier,
                    'type' => $returnType
                ]);
                igk_detect_csharp_reset_prop($i);
            } else {
                if ($i->read && ($i->read->type == 'method')) {
                    $i->read = null;
                }
            }
        },
        'csharp-type-subblock' => function ($e, $i) {
            extract(igk_extract_assoc($i, 'returnType|name|modifier'));
            if ($returnType && $name) {
                list($get, $set) = igk_extract($i->propertyMarker, 'get|set');
                $i->items['properties'][$name] =  CSharpEntityFactory::CreateDefinition('property', [
                    'name' => $name,
                    'modifier' => $modifier,
                    'type' => $returnType,
                    'get' => $get,
                    'set' => $set,
                ]);
                igk_detect_csharp_reset_prop($i);
            }
            $i->read = null;
        },
        'csharp-root-call' => function ($e, $i) {
            $v = $e->value;
            list($returnType, $name, $modifier, $docs) = igk_extract($i, 'returnType|name|modifier|docs');
            if ($returnType && $name) {
                $i->items['methods'][] = CSharpEntityFactory::CreateDefinition('method', ['type' => $returnType, 
                'name' => $name, 'modifier' => $modifier,
                'docs'=> $docs]);
                $i->read = (object)[
                    'type' => 'method',
                ];
                igk_detect_csharp_reset_prop($i);
            }
        },
        'csharp-glue-token' => function ($e, $i) {
            if ($i->returnType) {
                $i->glueToken = true;
            }
        },
        'csharp-modifier' => function ($e, $i) {
            if ($i->modifier) {
                if (!is_array($i->modifier)) {
                    $i->modifier = [$i->modifier];
                }
                $i->modifier[] = $e->value;
            } else
                $i->modifier = $e->value;
        },
        'csharp-operator-def' => function ($e, $i) {
            list($returnType, $name, $modifier) = igk_extract($i, 'returnType|operatorName|modifier');
            if ($returnType && $name) {
                $i->items['operators'][] =  CSharpEntityFactory::CreateDefinition(
                    'operator',
                    ['type' => $returnType, 'name' => $name, 'modifier' => $modifier]
                );
                $i->operatorName = null;
                igk_detect_csharp_reset_prop($i);
            }
        },
        'csharp-operator-name' => function ($e, $i) {
            $i->operatorName = $e->value;
        },
        'csharp-ns-name' => function ($e, $i) {
            $ns = $e->value;
            if ($i->namespace) {
                $ns = $i->namespace . '.' . $ns;
                array_unshift($i->nslist, $i->namespace);
            }
            $i->namespace = $ns;
        },
        'csharp-ns-block' => function ($e, $i) {
            $i->namespace = array_shift($i->nslist);
            $i->fullname = null;
        },
        'csharp-type' => function ($e, $i) {
            $i->type = $e->value;
        },
        'csharp-type-block' => function ($e, $i) {
            $i->fullname = null;
        },
        "csharp-token" => function ($e, $i) {
            if ($i->type && is_null($i->name)) {
                $i->name = $e->value;
                $t = $i->type;
                $o = &$i->output;
                if ($i->current)
                    igk_detect_save_state($i);
                igk_detect_update_name($i);
                $et = '::' . $t;
                $cl = $i->fullname;
                if (!isset($o[$et])) {
                    $o[$et] = [];
                }
                $obj = Activator::CreateNewInstance(MetaDataDefinition::class, (object)[
                    'docs' => $i->docs,
                    'modifier' => $i->modifier,
                    'type'  => $t,
                    'items' => []
                ]);
                $i->items = &$obj->items;
                $obj->setFileIndex($o['::current-file-index']);
                $o[$et][$cl] = $obj;
                $i->current = ['name' => $cl, 'target' => $obj];
                igk_detect_csharp_reset_prop($i);
            } else {
                if ($i->returnType) {
                    if ($i->glueToken) {
                        $i->returnType .= '.' . $e->value;
                        $i->glueToken = false;
                    } else {
                        $i->name = $e->value;
                    }
                } else {
                    $i->returnType = $e->value;
                }
            }
        }
    ], $handler ?? []);
    $inf = (object)[
        'output' => &$output,
        'states' => [],  
        'items' => null, 
        'current' => null, 
        'modifier' => null,
        'name' => null,
        'docs' => null,
        'type' => null,
        'returnType' => null,
        'params' => null,
        'glueToken' => false, 
        'namespace' => null,
        'fullname' => null, 
        'nslist' => [], 
        'read' => null,
        'propertyMarker' => (object)[],
        'filter'=>null,
        'filters'=>[
            'filter_doc'=>function($e, $i){
                if ($e->tokenID != 'csharp-doc'){
                    $i->filter = null; 
                }
            }
        ]
    ];
    while ($g = $regex->detect($src, $pos)) {
        if ($e = $regex->end($g, $src, $pos)) {
            $id = $e->tokenID;
            Logger::info('id:' . $id . ' value:' . json_encode($e->value));
            if ($inf->filter){
                $fc = $inf->filter;
                if ($fc($e, $inf)){
                    continue;
                }
            }
            if ($id && ($fc = igk_getv($fc_handler, $id))) {
                Logger::info('handle: ' . $id);
                $fc($e, $inf);
            }
        }
    }
}
$exclude = igk_getv($command->options, '--exclude_dir') ?? [];
if (is_string($exclude)) {
    $explude = explode('|', $exclude);
}
$dir = igk_getv($params, 0) ?? igk_die('require directory');
if (!is_dir($dir)) {
    $dir = __DIR__ . '/data/' . $dir;
}
if (!is_dir($dir)) {
    igk_die('directory not exists');
}
$output = new MetadataEntityDefinition;
$output['::meta'] = ['language' => 'csharp'];
igk_metadata_treat_definition('igk_detect_csharp_metadata', $dir, $exclude, $output, '/\.cs$/');
Logger::success('complete');
igk_wln_e("the output:", JSon::Encode($output, JSonEncodeOption::IgnoreEmpty(),  JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
igk_exit();