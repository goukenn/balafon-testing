<?php
// @author: C.A.D. BONDJE DOUE
// @filename: check_glue_regex.php
// @date: 20250821 08:03:44
// @desc: 
// @command: balafon --run .test/module/igk.phpFormatter/check_glue_regex.php

$s = preg_replace("/(?<=;)\\s*(?!=\/\/)/", "-splitting-", ";  avec");
$s = preg_replace("/\s+/", "-splitting-", " ");
igk_wln_e(__FILE__.":".__LINE__ , $s);