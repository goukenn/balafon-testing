<?php
// @command: balafon --run .test/core/zip-corejs.php
$zip = new ZipArchive();
$zip->open(__DIR__.'/archive.zip',   ZipArchive::CREATE | ZipArchive::OVERWRITE);
$files = igk_zip_dir(IGK_LIB_DIR.'/Scripts', $zip, "Lib/igk", "/(Lib\/igk\/(temp|application|.Caches|Data\/(config.xml|domain.conf)))|(\.(vscode|git(ignore)?|gkds|DS_Store|bak)$)/", true);
$zip->close();
igk_wln_e($files);