<?php
// @command: balafon --run .test/utils/json_to_assoc_array.php [file]
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherParentChainReplacement;
// dum json _export 
$file = igk_getv($params, 0) ?? igk_die('missing file name');
if ($data = json_decode(file_get_contents($file))) {
    $out = igk_dump_export($data);
    Logger::print($out);
}
Logger::success("done");
exit;