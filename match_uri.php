<?php
$p = "/^(\/(?P<lang>fr|en|nl))?(\/(?P<function>[^\/;]+)(\/(?P<params>([^\/;]+\/?)+)|\/)?|\/)?(;((?P<options>([^;]+=([^;\?]+;?|;;)?)+)))?$/i";
$data = preg_match($p, "/lvgStock/products/load;fmt=csv;sep=");
igk_wln_e($data);