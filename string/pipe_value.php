<?php
// + | usign str piep value exeemple
// balafon --run .test/string/pipe_value.php
use IGK\System\Console\Logger;
use IGK\System\DataArgs;
use IGK\System\Html\HtmlContext;
use IGK\System\Html\HtmlLoadingContextOptions;
use IGK\System\Html\HtmlReader;
use IGK\System\Templates\BindingExpressionReader;
$reader = new BindingExpressionReader;
// $data = igk_str_pipe_args("'raw->x' | uppercase", $c);
// igk_wln("data", $data);
// $n = igk_create_node("div");
// igk_engine_html_load_content($n,  "{{ 'or copy link  {0} to your browser {1}' | lang;'{{\$raw->unsubscribe_uri}}',88 }}  ", []);
// $n->renderAJX(); 
// $v = $reader->treatContent(" info {{ 'raw->x vs {0}' | uppercase|lang;55 }} </code> '{{ \$raw->y }} champion ", ['x'=>'Basic data --- ', 'y'=>555]);
// $v = $reader->treatContent(" info {{ 'raw->x vs {0}' | uppercase|lang;'{{188}}' }} </code> '{{ \$raw->y }} champion ", ['x'=>'Basic data --- ', 'y'=>555]);
// $v = $reader->treatContent(" info {{ 'raw->x vs {0}' | uppercase|lang;'{{\$raw->x}}' }} </code> '{{ \$raw->y }} champion ", ['x'=>'Basic data --- ', 'y'=>555]);
// igk_wln("done", $v);
igk_debug(true);
$v = $reader->treatContent("data : {{ \$raw }} - {{ \$ctrl->getName() }} ", (object)['raw'=>'11', 'ctrl'=>TtreController::ctrl()]);
igk_wln_e("done", $v);
$v = $reader->treatContent("{{ \$raw->type }}", ['type'=>'em']);
igk_wln_e("done", $v);
$g = igk_str_pipe_value('info {0}', 'lang;1;2;5|uppercase');
igk_wln($g);
$g = igk_str_pipe_value('25', 'currency;fmt=EUR|uppercase');
igk_wln($g);
$g = igk_str_pipe_value('2500', 'currency;fmt=USD|uppercase');
igk_wln($g);
$g = igk_str_pipe_value('2500', 'currency;fmt=XAF|uppercase');
igk_wln($g);
igk_debug(true);
$n = igk_create_node("div");
igk_engine_html_load_content($n,  "{{ 'or copy link  {0} to your browser {1}' | lang;'{{\$raw->unsubscribe_uri}}',88 }}  ", []);
$n->renderAJX(); 
igk_exit();