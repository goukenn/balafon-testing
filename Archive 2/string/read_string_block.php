<?php
use IGK\System\IO\StringBlockReader;
$s = '( { "brandname"=>{"type":"string()", "description"=>"brand name"} })';
$reader = new StringBlockReader;
$reader->start = '{';
$reader->end = '}';
$g = $reader->read($s);
igk_wln_e($g);