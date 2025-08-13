<?php
// @author: C.A.D. BONDJE DOUE
// @filename: read_args.php
// @date: 20250206 07:13:47
// @desc: check read args expression 
// @command: balafon --run .test/php/read_args.php
// test : migration 
use IGK\Helper\StringUtility;
$src = "security=\"sample\", action={\"one\":5 }";
$g = StringUtility::ReadArgs($src);
$s = ["security"=>"sample", "action"=>["one"=>4]];
igk_wln_e($g, $g == $s);