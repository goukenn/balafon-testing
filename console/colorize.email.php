<?php
// @command : balafon --run .test/console/colorize.email.php
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
Logger::SetColorizer(new Colorize);
Logger::print("hello my friend : \"you did something: cbondje@igkdev.com now reply goto https://igkdev.com !!\"");
// check negate look behind
// preg_match("/(?<!\\\\)a/", "\\a", $tab);
// print_r($tab);
Logger::print("\"Merci \\\" de \" notre presentation voir");
Logger::print("# sample ");
Logger::print("# avec no amitier ");
Logger::print("avec no amitier ");
igk_exit();