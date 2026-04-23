<?php
// @command: balafon --run .test/testuri.php
use IGK\System\IO\Path;

$dirname = "igk/dev-tools";
$g = preg_replace("/[^0-9\_a-z\/]/i", "",$dirname);
igk_wln_e("sanitize ", $g);