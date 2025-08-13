<?php
// @author: C.A.D. BONDJE DOUE
// @filename: gen_new.php
// @date: 20241112 07:20:58
// @desc: generate new markdown file 
// @command: balafon --run .test/markdown/gen_new.php
$outfile = igk_getv($params, 0) ?? igk_die("missing output file");
$title = igk_getv($command->option, '--title') ?? 'application';
$data = [];
$data[] = "# ".$title;
// application loading definition 
$data[] = "## FEATURES";
$data[] = "## REALEASE"; 
$data[] = "# KNOW ISSUES";
igk_io_w2file($outfile, implode("\n", $data));