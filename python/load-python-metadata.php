<?php
// @author: C.A.D. BONDJE DOUE
// @filename: load-python-metadata.php
// @date: 20260409 12:52:40
// @desc: load python metadata 
// @command: balafon --run .test/python/load-python-metadata.php
use IGK\Helper\Activator;
use IGK\Helper\ArrayUtils;
use IGK\Helper\IO;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherContainerTreatment;
use IGK\System\Text\RegexMatcherPattern;

require_once __DIR__ . '/../language/load-metadata-common.php';
/**
 * entities
 * @package 
 */
class PythonEntityElement 
{
    var $name;
    var $type;
    var $docs;
    var $modifier;
    var $returnType;
    var $method_params;
    var $items;
    var $fullname;
    var $decorator;
    var $offset;
    var $src;
}
/**
 * 
 * @package 
 */
class PythonDoc
{
    var $short;
    var $description;
    var $Attributes;
    var $Args;
    var $Raises;
    var $Example;
    /**
     * render documentation
     * @return string 
     */
    public function render(): string
    {
        $d = [];
        $dt = get_object_vars($this);
        unset($dt['short'], $dt['description']);
        $ch = '';
        if ($this->short) {
            $d[] = '# ' . $this->short;
            $d[] = '';
            $ch = "\n";
        }
        if ($this->description) {
            $d[] = $ch . $this->short;
        }
        $lf = false;
        foreach ($dt as $k => $v) {
            if ($v && ($k[0] == strtoupper($k[0]))) {
                if ($lf)
                    $d[] = '';
                $d[] = "## " . $k;
                if (!is_array($v)) {
                    $v = [$v];
                }
                $d = array_merge($d, [''], $v);
                $lf = true;
            }
        }
        return implode("\n", $d);
    }
}
/**
 * utility class helper
 * @package 
 */
abstract class PythonUtility
{
    /**
     * format docstring 
     * @param string $doc 
     * @param int $depth 
     * @param string $tab 
     * @return string 
     */
    public static function FormatDocument(string $doc, $depth = 1, $tab = "\t")
    {
        if (igk_str_startwith($doc, $l = '"""'))
            $doc = igk_str_rm_start(igk_str_rm_last($doc, $l, 1), $l, 1);
        $df = str_repeat($tab, $depth);
        return sprintf($df . '%s', implode("\n" . $df, array_map(function ($l) {
            return trim($l);
        }, explode("\n", '"""' . "\n" . $doc . "\n" . '"""'))));
    }
}
$dir = igk_getv($params, 0);
if ($dir && !is_dir($dir)) {
    $dir = __DIR__ . '/tests/data/' . $dir;
    if (!is_dir($dir)) {
        igk_die('missing directory');
    }
} else {
    igk_die('required directory');
}

function python_meta_init_block_depth($e, $inf)
{
    $inf->depth_start_def = $e->value;
} 


$outdir = igk_getv($command->options, '--outdir') ?? $dir . '/output';
$regex = new RegexMatcherContainer;
$v_py_comments[] = $regex->match('#.*', 'py-comment')->last();
$regex->appendEmptyLineDetection();
$r = $regex->match('^(?=[^\\s])', 'py-auto-start')->last();
$r->captureMode = RegexMatcherPattern::AUTO_RESET_CAPTURE_MODE;
$v_py_docs = $regex->begin('"""', '"""', 'py-doc-comment')->last();
$v_py_string = $regex->begin('(?:rf|fr|rt|tr|f|r|t)?("|\')', "\\1", 'py-string')->last();
$v_py_string->patterns = [
    $regex->createPattern([
        'match' => '\\\\.',
    ])
];
$v_py_number = $regex->match('\\b\\d+(\.[\\d]+)?\\b', 'py-number')->last();
$v_py_modifier = $regex->match('\\b(async)\\b', 'py-modifier')->last();
$v_py_modifier = $regex->match('(@[a-zA-Z_][a-zA-Z_0-9]*)\\b', 'py-decorator')->last();
$v_py_complex_number = $regex->match('\\b\\d+(\.[\\d]+)?j\\b', 'py-complex-number')->last();
$v_py_type = $regex->match('\\b(def|class)\\b', 'py-definition')->last();
$v_py_indent = $regex->match('^( |\\t)+(?=[^\\n])', "py-indent")->last();
$v_py_pass = $regex->match('\\b(pass)\\b', "py-pass")->last();
$v_py_dic = $regex->begin('\{', '\}', "py-dic")->last();
$v_py_return = $regex->begin('\\b(return)\\b', '$', "py-return")->last();
$v_py_import = $regex->begin('\\b(import)\\b', "$", 'py-import')->last();
$v_py_from = $regex->begin('\\b(from)\\b', "$", 'py-from')->last();
$v_py_block_depth = $regex->match('\\b(if|elif|else|while|for|try|except|finally|case)\\b', "py-block-def-start")->last();


$v_py_reserved_word = $regex->match('\\b(False|None|True|and|as|assert|async|await|break|class|case|continue|elif|else|excerpt|finally|for|global|if|in|is|match|nonlocal|not|or|return|try|while|yield)\\b', "py-reserved-word")->last();
$v_py_reserved_word = $regex->match('\\b(complex|bool|int|str|float|tuple|list|dict|set|bytes)\\b', "py-primitive-type")->last();
$v_py_typedef = $regex->match('\\b(int|str|cls)\\b', "py-type-def")->last();
$v_py_typedef = $regex->match('\\b(match|case)\\b', "py-soft-keyword")->last();
$v_py_tuple = $regex->createPattern(['tokenID' => 'py-tuple', 'begin' => "\(", 'end' => "\)"]);
$v_py_list = $regex->createPattern(['tokenID' => 'py-list', 'begin' => "\[", 'end' => "\]"]);
$v_py_operator = $regex->match("(\+|<<|>>|>=|(=|-|\+|\/(\/)?|\*)?=|<|<=|!=|\*-|\/(\/)?|%|\^|&|\||@)", 'py-operator')->last();
$v_py_ellipsis_operator = $regex->match("(\.\.\.)", 'py-ellipsis-operator')->last();
# @ matrix operator
$v_py_tuple->patterns = $v_py_list->patterns = $v_py_dic->patterns = [
    $v_py_comments,
    $v_py_list,
    $v_py_dic,
    $v_py_tuple,
    $v_py_string,
    $v_py_number
];
$v_py_return->patterns = [
    $v_py_comments,
    $v_py_list,
    $v_py_dic,
    $v_py_tuple,
    $v_py_string,
    $v_py_number
];
$v_py_ref_identifier = $regex->match('(\*){1,2}[a-zA-Z_][a-zA-Z_0-9]*', "py-ref-var")->last();
$v_py_identifier = $regex->match('[a-zA-Z_][a-zA-Z_0-9]*', "py-identifier")->last();
$v_py_lamda = $regex->match('\\b(lamda)\\b', 'py-lamda')->last();
$v_py_annotations = $regex->begin('->', '(?=:)', 'py-annotations')->last();
$v_py_method_declare = $regex->begin(
    '\(',
    '\)',
    'py-method-param'
);
$v_py_method_end_declare = $regex->match(
    ':',
    'py-definition-declare'
);
if (!$params) {
    $src = implode("\n", [
        "               ",
        "def hello():",
        "",
        "    pass"
    ]);
    $src = implode("\n", [
        "               ",
        "def hello():   pass",
        ""
    ]);
    $src = implode("\n", [
        "               ",
        "def hello():   " .
            '""" information du jour """',
        " pass",
        ""
    ]);
}
$treat = new RegexMatcherContainerTreatment;
$treat->listener = (object)[
    'output' => [
        '::files' => [],
        '::def' => [],
        '::class' => [],
        '::metadata' => (object)[
            'language' => 'python'
        ]
    ],
    'file' => null,
    'filter' => null,
    'postfilter' => null,
    'depth' => -1,
    'modifier' => null,
    'type' => null,
    'name' => null,
    'annotations' => null,
    'method_params' => null,
    'decorator' => null,
    'current' => null,
    'parent' => null,
    'last_depth' => null,
    'depth_start_def' => null,
    'fullname_callback' => function ($i) {
        $s = [];
        while ($i) {
            $s[] = $i->info->name;
            $i = $i->parent;
        }
        return implode('.', $s);
    },
    'handle' => [
        'py-block-def-start' => function ($e, $inf) {
            python_meta_init_block_depth($e, $inf);
        },
        'py-decorator' => function ($e, $inf) {
            ArrayUtils::AttachValue($inf->decorator, $e->value);
        },
        'py-auto-start' => function ($e, $inf) {
            if ($inf->current) {
                call_user_func_array($inf->closeCurrent, func_get_args());
            }
        },
        'py-method-param' => function ($e, $inf) {
            $inf->method_params = $e->value;
        },
        'py-modifier' => function ($e, $inf) {
            if ($inf->modifier) {
                if (!is_array($inf->modifier)) {
                    $inf->modifier = [$inf->modifier];
                }
                $inf->modifier[] = $e->value;
            } else $inf->modifier = $e->value;
        },
        'py-definition-declare' => function ($e, $inf) {
            extract(igk_extract_assoc($inf, 'name|type|modifier|depth|current|handle|fullname_callback|method_params|decorator|depth_start_def'));
            $c = $current;
            $fc_update_depth = function (&$depth, $inf, $c) {
                if ($depth < 0) {
                    $inf->depth = $depth = 0;
                } else {
                    $depth = $c ? $c->depth + 1 : $depth + 1;
                }
            };
            $fc_create_current = function ($inf, $c, $depth, $b, $e) {
                $inf->current = (object)[
                    'parent' => $c,
                    'depth' => $depth,
                    'info' => $b,
                    'offset' => $e->from
                ];
            };
            if ($depth_start_def) {
                $fc_update_depth($depth, $inf, $c);
                $fc_create_current($inf, $c, $depth, (object)['src'=>'', 'offset'=>$e->from], $e);
                $inf->depth_start_def = null;
                return;
            }

            if (is_null($type))
                return;
            $name || igk_die('missing name definition');

            $rt = (($type == 'def') && $inf->annotations) ? preg_replace('/\\s+/', ' ', trim(substr($inf->annotations, 2))) : '';




            $b = Activator::CreateNewInstance(PythonEntityElement ::class, [
                'name' => $name,
                'type' => $type,
                'src' => '',
                'offset' => $e->from,
                'returnType' => $rt,
                'modifier' => $modifier,
                'decorator' => $decorator,
                'method_params' => $method_params,
                'fullname' => implode('.', array_filter([$c ? $fullname_callback($c) : null, $name]))
            ]);
            if ($depth < 0) {
                $inf->depth = $depth = 0;
            } else {
                $depth = $c ? $c->depth + 1 : $depth + 1;
            }
            $fc_create_current($inf, $c, $depth, $b, $e);

            $inf->output['::' . $b->type][$b->fullname] = $b;
            $inf->postfilter = Closure::fromCallable(function ($e, $inf, $src) {
                Logger::info('write: ' . json_encode($e->value));
                $id = $e->tokenID;
                extract(igk_extract_assoc($inf, 'current|last_depth'));
                if ($current) {
                    if ('py-indent' == $id) {
                        if ($last_depth <= $current->depth) {
                            Logger::warn('*******************sample changed.**********************');
                            call_user_func_array($inf->closeCurrent, func_get_args());
                        }
                    }
                }
            })->bindTo($inf);
            $inf->annotations = null;
            $inf->name = null;
            $inf->type = null;
            $inf->modifier = null;
            $inf->method_params = null;
            $inf->decorator = null;
        },
        'py-definition' => function ($e, $inf) {
            $inf->type = $e->value;
        },
        'py-annotations' => function ($e, $inf) {
            $inf->annotations = $e->value;
        },
        'py-doc-comment' => function ($e, $inf, $src) {
            if (($current = $inf->current) && !($current->info->docs)) {
                if (trim(substr($src, $current->offset,  $e->from - $current->offset)) == ':') {
                    $current->info->docs = $e->value;
                }
            }
        },
        'py-identifier' => function ($e, $inf) {
            if ($inf->type) {
                $inf->name = $e->value;
            }
        },
        'py-pass' => function ($e, $inf, $src) {
            // + | just skip move to parent  
            call_user_func_array($inf->closeCurrent, func_get_args());
        },
        'py-indent' => function ($e, $inf) {
            if ($inf->depth == -1) {
                igk_die('invalid indent');
            }
            $n_df = strlen($e->value);
            $n_df = ($e->value[0] == ' ') ? $n_df * 0.25 : $n_df;
            $inf->last_depth = $n_df;
        }
    ],
    'closeCurrent' => function ($e, $inf, $src) {
        $offset = $inf->current->info->offset;
        $inf->current->info->src .= rtrim(substr($src, $offset, $e->to - $offset));
        $inf->current = $inf->current->parent;
        if ($inf->current) {
            $inf->depth = $inf->current->depth;
        } else {
            $inf->depth = -1;
        }
    }
];
if (!$params) {
    $treat->treat($src, $regex);
    if ($current = $treat->listener->current) {
        $current->info->src .= rtrim(substr($src, $current->offset));
    }
}

$ln = strlen($dir) + 1;
IO::GetFiles($dir, function ($f) use ($treat, $regex, $ln) {
    if (preg_match('/\.py$/', $f)) {
        $treat->listener->file = $f;
        $treat->listener->output['::files'][] = substr($f, $ln);
        $treat->treat($src = file_get_contents($f), $regex);
        if ($current = $treat->listener->current) {
            $current->info->src .= rtrim(substr($src, $current->offset));
        }
    }
}, true, $v_exclude);
igk_wln(JSon::Encode($treat->listener->output, JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
igk_exit();
