<?php
use igk\language\python\System\IO\PythonScriptBuilder;
$py = new PythonScriptBuilder;
$src = ""; // "def a():\n\ta=\"\"\"\"print(a)\na = 12\n\"\"\"\n\tif True:\n\t\tpass";
$q = //$py->build(...['def a():', 'a = """print(a)',"a = 12", 'OK """', "x=8",
$py->build(...["if False:",
        $py->build(...['if True:', 'x = 8', 
        'basic =""" info', 'done"""', 
        'pass'])
])
    //])
    ; 
igk_wln_e($src, $q);
$src = <<<'PYTHON'
def info():
a = """
basic information 
""" % "local"
pass
PYTHON;
$src = <<<'PYTHON'
def info():
a = """ basic information """ % """local""" 
PYTHON;
$src_2 = <<<'PYTHON'
def info():
a = """ basic information 
""" % """local""" base
PYTHON;
$src_3 = <<<'PYTHON'
def info():
a = """ basic information 
""" % """local
""" base
PYTHON;
$m = new PythonScriptBuilder;
$tab = explode("\n", $src);
$g = $m->build(...$tab);
igk_wln_e("basic ::::: ", $g);
$tab = explode("\n", $src);
$join = false;
$rt = [];
$detector = (object)[
    "buffer" => '',
    "join" => false,
    "match" => "/(\"){3}/",
    "offset" => 0,
    "counter" => 0
];
function python_detect_match($detector, $a, &$v_open = 0)
{
    $list = [];
    while (python_detect_symbol($detector, $a, $list)) {
        $pos = $list[0][1];
        if ($detector->join) {
            // if escaped 
            if ($a[$pos - 1] == "\\") {
                $detector->offset = 1 + $pos;
                continue;
            } else {
                // glue litteral on first list 
                $detector->buffer .= substr($a, $detector->offset, $pos + 3 - $detector->offset);
            }
        } else {
            $detector->buffer = substr($a, 0, $pos + 3);
        }
        $v_open++;
        $detector->offset = $pos + 3;
        $detector->join = true;
    }
}
function python_detect_symbol($detector, $a, &$list)
{
    return preg_match($detector->match, $a, $list, PREG_OFFSET_CAPTURE, $detector->offset);
}
array_map(function ($a) use ($detector, &$rt) {
    $detector->offset = 0;
    if ($detector->join) {
        $detector->buffer .= "\n";
        // try detect end 
        $end = false;
        while (python_detect_symbol($detector, $a, $list)) {
            // end meatch
            $pos = $list[0][1];
            if (($pos > 0) && ($a[$pos - 1] == "\\")) {
                // is e
                $detector->offset = $pos++;
            } else {
                // close the buffer 
                $detector->buffer .= substr($a, 0, $pos + 3);
                $a = substr($a, $pos + 3);
                $detector->join = false;
                if (strlen($a) > 0) {
                    $v_bckbuffer = $detector->buffer;
                    $detector->offset = 0;
                    $v_open = 0;
                    $detector->join = true;
                    python_detect_match($detector, $a, $v_open);
                    if ($v_open) {
                        $cp = substr($a, $detector->offset);
                        if ( ($v_open % 2) == 0) {
                            $rt[] = $detector->buffer . $cp;
                            $detector->join = false;
                            $detector->count = 0;
                            $detector->buffer = '';
                            $end = true;
                            return;
                        } else { 
                            $detector->buffer .= $cp;
                        }
                        return;
                    }
                }
                if (!$detector->join) {
                    $rt[] = $detector->buffer;
                    $detector->buffer = '';
                    $end = true;
                    $a = '';
                }
            }
        }
        if (!$end)
            $detector->buffer .= $a;
        return;
    } else {
        $v_open = 0;
        python_detect_match($detector, $a, $v_open);
        if (!$detector->join)
            $rt[] = $a;
        else {
            if (($v_open % 2) == 0) {
                $rt[] = $detector->buffer . substr($a, $detector->offset);
                $detector->join = false;
                $detector->count = 0;
                $detector->buffer = '';
            } else {
                $detector->count = $v_open;
                $detector->buffer .= substr($a, $detector->offset);
            }
        }
    }
}, $tab);
if ($detector->buffer){
    $rt[] = $detector->buffer;
    $detector->buffer= '';
}
igk_wln("the rt, ");
print_r($rt);
igk_wln_e(
    "\n\n",
    implode("\n\t", $rt),
    "done"
);
echo $src;
echo "\n";
$matches = "";
$k = preg_match_all("/\"{3}/", $src, $matches, PREG_OFFSET_CAPTURE);
echo (" - ");
print_r($matches);
igk_wln_e($k);